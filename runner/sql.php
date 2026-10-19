<?php
// The course tool behind ./sql and sql.cmd. Run "./sql help" for the commands.
//
// Every check runs in a scratch database built from the chain in course/chain.json:
// the base snapshot, then the student's own chain files up to the checkpoint. If an
// earlier chain file is empty or fails, the check falls back to the latest reference
// snapshot before the checkpoint and says so.

declare(strict_types=1);

const ROOT = __DIR__ . '/..';
const STUDENT_COMMANDS = <<<TXT
Usage: ./sql <command>

  check [id]           Check one exercise or checkpoint (e.g. m4u4-02-add-fk, m4u4-cp02).
                       With no id, checks the checkpoint you are working on.
  check --all [--ci]   Check everything. --ci fails only on SQL errors.
  watch                Re-check a checkpoint whenever one of its files changes.
  hint <id>            Show the hint for an exercise.
  next                 Show the next exercise to do.
  progress             Show passed, failed and pending exercises per checkpoint.
  reset <db> [--at u]  Load a reference state, e.g. ./sql reset iceadmin --at m4u4.
  rebuild <db|--all>   Rebuild your database from your own chain files, in order.
  shell <db>           Open the MariaDB client on a database.
  drift <db>           Compare db/<db>/<db>.dbml with your database.
  sql2dbml <db>        Write db/<db>/<db>.dbml from your database.

Databases: iceadmin, tvstudio, pos, joinlab.
TXT;
const INSTRUCTOR_COMMANDS = <<<TXT

Instructor commands (set SOLUTIONS to the solutions repository):
  hashgen              Fill expected results in every exercises.json from the solutions.
  snapshot <db> <unit> Write snapshots/<db>/after-<unit>.sql from the reference chain.
  continuity           Replay every chain and run every reference solution.
  autograding          Regenerate .github/classroom/autograding.json.
TXT;

// ---------------------------------------------------------------- database access

function dbHost(): string
{
    return getenv('DB_HOST') ?: '127.0.0.1';
}

function pdo(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO('mysql:host=' . dbHost() . ';charset=utf8mb4', 'root', 'root', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_STRINGIFY_FETCHES => true,
                PDO::ATTR_EMULATE_PREPARES => true,
            ]);
        } catch (PDOException $e) {
            fwrite(STDERR, "Cannot reach the database. Start it with: docker compose up -d --wait\n");
            exit(2);
        }
    }
    return $pdo;
}

function q(string $sql, array $params = []): array
{
    $st = pdo()->prepare($sql);
    $st->execute($params);
    return $st->fetchAll(PDO::FETCH_ASSOC);
}

function useDb(string $db): void
{
    pdo()->exec('USE `' . $db . '`');
}

function freshDb(string $db): void
{
    pdo()->exec("DROP DATABASE IF EXISTS `$db`");
    pdo()->exec("CREATE DATABASE `$db`");
}

function dbExists(string $db): bool
{
    return (bool) q('SELECT 1 FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = ?', [$db]);
}

function client(array $extra = []): array
{
    return array_merge(['mariadb', '-h', dbHost(), '-uroot', '-proot', '--default-character-set=utf8mb4'], $extra);
}

// Runs SQL text through the MariaDB client, so files behave exactly as they do for the student.
// Returns null on success, or ['code' => int, 'message' => string].
function runSqlText(string $db, string $sql): ?array
{
    $proc = proc_open(client([$db]), [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    fwrite($pipes[0], $sql);
    fclose($pipes[0]);
    stream_get_contents($pipes[1]);
    $err = trim(stream_get_contents($pipes[2]));
    fclose($pipes[1]);
    fclose($pipes[2]);
    $status = proc_close($proc);
    if ($status === 0) {
        return null;
    }
    if (preg_match('/ERROR (\d+) \(\w+\)(?: at line (\d+))?: (.*)/s', $err, $m)) {
        $line = $m[2] !== '' ? " (line {$m[2]})" : '';
        return ['code' => (int) $m[1], 'message' => "ERROR {$m[1]}$line: " . trim($m[3])];
    }
    return ['code' => 0, 'message' => $err !== '' ? $err : "client exited with status $status"];
}

function runSqlFile(string $db, string $path): ?array
{
    return runSqlText($db, file_get_contents($path));
}

function dumpDb(string $db, string $path): void
{
    $cmd = ['mariadb-dump', '-h', dbHost(), '-uroot', '-proot', '--skip-comments', '--skip-dump-date',
        '--skip-extended-insert', '--order-by-primary', '--skip-add-locks', '--skip-disable-keys', $db];
    $proc = proc_open($cmd, [1 => ['file', $path, 'w'], 2 => ['pipe', 'w']], $pipes);
    $err = stream_get_contents($pipes[2]);
    if (proc_close($proc) !== 0) {
        fail("mariadb-dump failed: $err");
    }
    // The dump's first line switches newer clients into sandbox mode. Drop it so any client can load the file.
    $text = preg_replace('{\A/\*M!999999\\\\- enable the sandbox mode \*/\n}', '', file_get_contents($path));
    file_put_contents($path, $text);
}

// ---------------------------------------------------------------- files

function rel(string $path): string
{
    $root = realpath(ROOT) . '/';
    $real = realpath($path) ?: $path;
    foreach ([$root, rtrim((string) getenv('SOLUTIONS'), '/') . '/'] as $prefix) {
        if ($prefix !== '/' && str_starts_with($real, $prefix)) {
            return substr($real, strlen($prefix));
        }
    }
    return $path;
}

// Paths in exercises.json starting with "/" are relative to the repository root;
// others are relative to the checkpoint folder. With SOLUTIONS set, the solutions
// repository's copy of a file is used when it has one.
function resolve(string $path, string $cpDir = ''): string
{
    $relPath = str_starts_with($path, '/') ? ltrim($path, '/') : rel($cpDir) . '/' . $path;
    $sol = getenv('SOLUTIONS');
    if ($sol && is_file("$sol/$relPath")) {
        return "$sol/$relPath";
    }
    return realpath(ROOT) . '/' . $relPath;
}

function stripSqlComments(string $sql): string
{
    $sql = preg_replace('{/\*.*?\*/}s', '', $sql);
    return preg_replace('{^\s*(--|#).*$}m', '', $sql);
}

function isEmptyFile(string $path): bool
{
    if (!is_file($path)) {
        return true;
    }
    $text = file_get_contents($path);
    if (str_ends_with($path, '.sql')) {
        $text = stripSqlComments($text);
    } elseif (str_ends_with($path, '.dbml')) {
        $text = preg_replace('{^\s*//.*$}m', '', $text);
    }
    return trim($text) === '';
}

// A file that changes database would escape the scratch copy, so it is never run.
function forbiddenStatement(string $path): ?string
{
    $sql = stripSqlComments(file_get_contents($path));
    if (preg_match('/^\s*(USE\s+\S+|CREATE\s+DATABASE|DROP\s+DATABASE)/im', $sql, $m)) {
        return "remove \"" . trim($m[1]) . "\" from " . rel($path) . ". ./sql runs your file in the right database for you";
    }
    return null;
}

// ---------------------------------------------------------------- the chain

function chain(): array
{
    static $chain = null;
    return $chain ??= json_decode(file_get_contents(ROOT . '/course/chain.json'), true, flags: JSON_THROW_ON_ERROR);
}

function chainDb(string $db): array
{
    $c = chain()['databases'][$db] ?? null;
    if ($c === null) {
        fail("Unknown database \"$db\". Use one of: " . implode(', ', array_keys(chain()['databases'])));
    }
    return $c;
}

function unitOf(string $cp): string
{
    return explode('-', $cp)[0];
}

// The reference snapshots of a database, as [unit, file, entries applied], oldest first.
function boundaries(string $db): array
{
    $c = chainDb($db);
    $out = [];
    foreach ($c['snapshots'] as $unit => $file) {
        $k = count(array_filter($c['steps'], fn ($s) => unitOf($s['cp']) <= $unit));
        $out[] = [$unit, $file, $k];
    }
    return $out;
}

function snapshotFile(string $db, string $unit): string
{
    $file = chainDb($db)['snapshots'][$unit] ?? null;
    if ($file === null) {
        fail("No snapshot for $db at $unit. Available: " . implode(', ', array_keys(chainDb($db)['snapshots'])));
    }
    return realpath(ROOT) . '/' . $file;
}

// Number of chain steps applied once checkpoint $cp is complete.
function stepsAfter(string $db, string $cp): int
{
    return count(array_filter(chainDb($db)['steps'], fn ($s) => $s['cp'] <= $cp));
}

/**
 * Builds $target as the database $db looks after $n chain steps, using the student's own files.
 * If they cannot get that far, or $fromSnapshot is set, starts from the latest snapshot taken
 * before $unit and replays only the steps after it.
 * Returns ['ok' => bool, 'pending' => bool, 'message' => string, 'note' => ?string].
 */
function buildState(string $db, int $n, string $target, bool $fromSnapshot = false, ?string $unit = null): array
{
    $c = chainDb($db);
    $steps = $c['steps'];
    $bounds = boundaries($db);

    if (!$fromSnapshot) {
        freshDb($target);
        $err = runSqlFile($target, realpath(ROOT) . '/' . $c['snapshots'][$c['base']]);
        if ($err) {
            fail("Cannot load the base snapshot for $db: {$err['message']}");
        }
        $own = true;
        for ($i = 0; $i < $n; $i++) {
            $file = resolve('/' . $steps[$i]['file']);
            if (isEmptyFile($file) || forbiddenStatement($file) || runSqlFile($target, $file)) {
                $own = false;
                break;
            }
        }
        if ($own) {
            return ['ok' => true, 'pending' => false, 'message' => '', 'note' => null];
        }
    }

    // Fall back to the latest reference snapshot at or before step $n, taken before $unit started.
    $best = $bounds[0];
    foreach ($bounds as $b) {
        if ($b[2] <= $n && ($unit === null || $b[0] < $unit) && is_file(realpath(ROOT) . '/' . $b[1])) {
            $best = $b;
        }
    }
    [$unit, $file, $k] = $best;
    freshDb($target);
    $err = runSqlFile($target, realpath(ROOT) . '/' . $file);
    if ($err) {
        fail("Cannot load $file: {$err['message']}");
    }
    for ($i = $k; $i < $n; $i++) {
        $step = $steps[$i];
        $path = resolve('/' . $step['file']);
        if (isEmptyFile($path)) {
            return ['ok' => false, 'pending' => true, 'note' => null,
                'message' => "do {$step['cp']} first ({$step['file']} is empty)"];
        }
        if ($why = forbiddenStatement($path)) {
            return ['ok' => false, 'pending' => false, 'note' => null, 'message' => $why];
        }
        if ($err = runSqlFile($target, $path)) {
            return ['ok' => false, 'pending' => false, 'note' => null,
                'message' => "earlier step {$step['cp']} ({$step['file']}) fails: {$err['message']}"];
        }
    }
    $note = $fromSnapshot ? null : "your chain stops before this point, so this check started from snapshot after-$unit";
    return ['ok' => true, 'pending' => false, 'message' => '', 'note' => $note];
}

// ---------------------------------------------------------------- structure

function normRule(string $rule): string
{
    return $rule === 'NO ACTION' ? 'RESTRICT' : $rule;   // InnoDB treats them the same.
}

function normCheck(string $clause): string
{
    $c = strtolower(str_replace(['`', ' '], '', $clause));
    while (str_starts_with($c, '(') && str_ends_with($c, ')')) {
        $c = substr($c, 1, -1);
    }
    return $c;
}

// A comparable description of a schema: one sorted list of strings per table.
function structureOf(string $schema): array
{
    $out = [];
    foreach (q("SELECT TABLE_NAME t FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_TYPE = 'BASE TABLE'", [$schema]) as $r) {
        $out[$r['t']] = [];
    }
    foreach (q('SELECT TABLE_NAME t, COLUMN_NAME c, COLUMN_TYPE ty, IS_NULLABLE n, COLUMN_DEFAULT d, EXTRA e
                FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ?', [$schema]) as $r) {
        $default = $r['d'] === null ? '' : " default {$r['d']}";
        $extra = $r['e'] !== '' ? " {$r['e']}" : '';
        $out[$r['t']][] = "column {$r['c']} {$r['ty']}" . ($r['n'] === 'YES' ? ' null' : ' not null') . $default . $extra;
    }
    $keys = [];
    foreach (q('SELECT TABLE_NAME t, INDEX_NAME i, COLUMN_NAME c FROM information_schema.STATISTICS
                WHERE TABLE_SCHEMA = ? AND NON_UNIQUE = 0 ORDER BY TABLE_NAME, INDEX_NAME, SEQ_IN_INDEX', [$schema]) as $r) {
        $keys[$r['t']][$r['i']][] = $r['c'];
    }
    foreach ($keys as $t => $indexes) {
        foreach ($indexes as $name => $cols) {
            $out[$t][] = ($name === 'PRIMARY' ? 'primary key' : 'unique') . ' (' . implode(', ', $cols) . ')';
        }
    }
    foreach (fks($schema) as $fk) {
        $out[$fk['table']][] = "foreign key ({$fk['cols']}) -> {$fk['ref']} ({$fk['refCols']}) on update {$fk['update']} on delete {$fk['delete']}";
    }
    foreach (q('SELECT TABLE_NAME t, CHECK_CLAUSE c FROM information_schema.CHECK_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = ?', [$schema]) as $r) {
        $out[$r['t']][] = 'check ' . normCheck($r['c']);
    }
    foreach ($out as &$lines) {
        sort($lines);
    }
    ksort($out);
    return $out;
}

function fks(string $schema): array
{
    $rows = q('SELECT k.CONSTRAINT_NAME n, k.TABLE_NAME t, k.COLUMN_NAME c, k.REFERENCED_TABLE_NAME rt, k.REFERENCED_COLUMN_NAME rc,
                      r.UPDATE_RULE u, r.DELETE_RULE d
               FROM information_schema.KEY_COLUMN_USAGE k
               JOIN information_schema.REFERENTIAL_CONSTRAINTS r
                 ON r.CONSTRAINT_SCHEMA = k.CONSTRAINT_SCHEMA AND r.CONSTRAINT_NAME = k.CONSTRAINT_NAME AND r.TABLE_NAME = k.TABLE_NAME
               WHERE k.CONSTRAINT_SCHEMA = ? AND k.REFERENCED_TABLE_NAME IS NOT NULL
               ORDER BY k.TABLE_NAME, k.CONSTRAINT_NAME, k.ORDINAL_POSITION', [$schema]);
    $out = [];
    foreach ($rows as $r) {
        $key = $r['t'] . '.' . $r['n'];
        $out[$key] ??= ['table' => $r['t'], 'name' => $r['n'], 'ref' => $r['rt'], 'cols' => [], 'refCols' => [],
            'update' => normRule($r['u']), 'delete' => normRule($r['d'])];
        $out[$key]['cols'][] = $r['c'];
        $out[$key]['refCols'][] = $r['rc'];
    }
    foreach ($out as &$fk) {
        $fk['cols'] = implode(', ', $fk['cols']);
        $fk['refCols'] = implode(', ', $fk['refCols']);
    }
    return array_values($out);
}

function diffStructures(array $a, array $b, string $aName, string $bName): array
{
    $out = [];
    foreach (array_unique(array_merge(array_keys($a), array_keys($b))) as $t) {
        if (!isset($a[$t])) {
            $out[] = "table $t is only in $bName";
            continue;
        }
        if (!isset($b[$t])) {
            $out[] = "table $t is only in $aName";
            continue;
        }
        foreach (array_diff($a[$t], $b[$t]) as $l) {
            $out[] = "$t: $l (only in $aName)";
        }
        foreach (array_diff($b[$t], $a[$t]) as $l) {
            $out[] = "$t: $l (only in $bName)";
        }
    }
    return $out;
}

function dataOf(string $schema): array
{
    $out = [];
    foreach (array_keys(structureOf($schema)) as $t) {
        $rows = pdo()->query("SELECT * FROM `$schema`.`$t` ORDER BY 1")->fetchAll(PDO::FETCH_NUM);
        $out[$t] = hash('sha256', implode("\n", array_map(fn ($r) => implode("\x1f", array_map(fn ($v) => $v ?? 'NULL', $r)), $rows)));
    }
    return $out;
}

function dbml(string $endpoint, string $body): array
{
    $url = (getenv('DBML_URL') ?: 'http://127.0.0.1:3000') . $endpoint;
    $ctx = stream_context_create(['http' => ['method' => 'POST', 'content' => $body, 'ignore_errors' => true, 'timeout' => 60]]);
    $res = @file_get_contents($url, false, $ctx);
    if ($res === false) {
        fail('Cannot reach the dbml service. Start it with: docker compose up -d --wait');
    }
    $ok = str_contains($http_response_header[0] ?? '', ' 200 ');
    return [$ok, $res];
}

// Applies a DBML file to a fresh database. Returns null on success, or an error message.
function applyDbml(string $dbmlPath, string $target): ?string
{
    [$ok, $sql] = dbml('/dbml2sql', file_get_contents($dbmlPath));
    if (!$ok) {
        return 'dbml2sql could not read ' . rel($dbmlPath) . ': ' . $sql;
    }
    freshDb($target);
    $err = runSqlText($target, $sql);
    return $err ? 'the SQL generated from ' . rel($dbmlPath) . " fails: {$err['message']}" : null;
}

function driftLines(string $dbmlPath, string $schema): array
{
    if (isEmptyFile($dbmlPath)) {
        return [rel($dbmlPath) . ' is empty'];
    }
    if ($err = applyDbml($dbmlPath, 'drift_check')) {
        return [$err];
    }
    $diff = diffStructures(structureOf('drift_check'), structureOf($schema), 'the DBML', 'the database');
    pdo()->exec('DROP DATABASE drift_check');
    return $diff;
}

// ---------------------------------------------------------------- assertions

function columnInfo(string $schema, string $tc): ?array
{
    [$t, $c] = explode('.', $tc);
    return q('SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?', [$schema, $t, $c])[0] ?? null;
}

function findFk(string $schema, string $spec): ?array
{
    if (!preg_match('/^(\w+)\.(\w+)\s*->\s*(\w+)\.(\w+)$/', $spec, $m)) {
        fail("Bad fk assertion \"$spec\"");
    }
    foreach (fks($schema) as $fk) {
        if ($fk['table'] === $m[1] && $fk['cols'] === $m[2] && $fk['ref'] === $m[3] && $fk['refCols'] === $m[4]) {
            return $fk;
        }
    }
    return null;
}

/** Returns a list of failure messages; empty means every assertion held. */
function assertAll(string $schema, array $asserts, string $cpDir, array $before = []): array
{
    $fails = [];
    useDb($schema);
    foreach ($asserts as $a) {
        if (isset($a['table'])) {
            if (!array_key_exists($a['table'], structureOf($schema))) {
                $fails[] = "table {$a['table']} does not exist";
            }
        } elseif (isset($a['no_table'])) {
            if (array_key_exists($a['no_table'], structureOf($schema))) {
                $fails[] = "table {$a['no_table']} should not exist";
            }
        } elseif (isset($a['column'])) {
            $col = columnInfo($schema, $a['column']);
            if ($col === null) {
                $fails[] = "column {$a['column']} does not exist";
                continue;
            }
            if (isset($a['type'])) {
                $want = strtolower(str_replace(' ', '', $a['type']));
                $have = str_contains($want, '(') ? strtolower(str_replace(' ', '', $col['COLUMN_TYPE'])) : strtolower($col['DATA_TYPE']);
                if ($want !== $have) {
                    $fails[] = "column {$a['column']} is {$col['COLUMN_TYPE']}, expected {$a['type']}";
                }
            }
            if (isset($a['null']) && ($col['IS_NULLABLE'] === 'YES') !== $a['null']) {
                $fails[] = "column {$a['column']} should " . ($a['null'] ? 'allow' : 'not allow') . ' NULL';
            }
        } elseif (isset($a['no_column'])) {
            if (columnInfo($schema, $a['no_column']) !== null) {
                $fails[] = "column {$a['no_column']} should not exist";
            }
        } elseif (isset($a['fk'])) {
            $fk = findFk($schema, $a['fk']);
            if ($fk === null) {
                $fails[] = "no foreign key {$a['fk']}";
                continue;
            }
            foreach (['update', 'delete'] as $rule) {
                if (isset($a[$rule]) && $fk[$rule] !== $a[$rule]) {
                    $fails[] = "foreign key {$a['fk']} has ON " . strtoupper($rule) . " {$fk[$rule]}, expected {$a[$rule]}";
                }
            }
        } elseif (isset($a['no_fk'])) {
            if (findFk($schema, $a['no_fk']) !== null) {
                $fails[] = "foreign key {$a['no_fk']} should not exist";
            }
        } elseif (isset($a['unique'])) {
            preg_match('/^(\w+)\((.*)\)$/', str_replace(' ', '', $a['unique']), $m);
            if (!in_array('unique (' . str_replace(',', ', ', $m[2]) . ')', structureOf($schema)[$m[1]] ?? [], true)) {
                $fails[] = "no UNIQUE {$a['unique']}";
            }
        } elseif (isset($a['check'])) {
            $have = array_map(fn ($r) => normCheck($r['c']),
                q('SELECT CHECK_CLAUSE c FROM information_schema.CHECK_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = ?', [$schema, $a['check']]));
            if (!array_intersect($have, array_map('normCheck', $a['any_of']))) {
                $fails[] = "no CHECK on {$a['check']} equivalent to " . $a['any_of'][0];
            }
        } elseif (isset($a['sql'])) {
            try {
                $rows = pdo()->query($a['sql'])->fetchAll(PDO::FETCH_NUM);
            } catch (PDOException $e) {
                $fails[] = 'check query failed: ' . $e->getMessage();
                continue;
            }
            $got = implode(' | ', array_map(fn ($r) => implode(', ', array_map(fn ($v) => $v ?? 'NULL', $r)), $rows));
            if ($got !== (string) $a['equals']) {
                $fails[] = ($a['say'] ?? $a['sql']) . ": got \"$got\", expected \"{$a['equals']}\"";
            }
        } elseif (isset($a['exec'])) {
            try {
                pdo()->exec($a['exec']);
            } catch (PDOException $e) {
                $fails[] = ($a['say'] ?? $a['exec']) . ' failed: ' . ($e->errorInfo[2] ?? $e->getMessage());
                break;
            }
        } elseif (isset($a['rows_unchanged'])) {
            foreach ($a['rows_unchanged'] as $t) {
                $now = (int) q("SELECT COUNT(*) n FROM `$t`")[0]['n'];
                if (($before[$t] ?? null) !== $now) {
                    $fails[] = "table $t had " . ($before[$t] ?? '?') . " rows before your file and $now after";
                }
            }
        } elseif (isset($a['drift'])) {
            foreach (driftLines(resolve($a['drift'], $cpDir), $schema) as $l) {
                $fails[] = "drift: $l";
            }
            useDb($schema);
        } else {
            fail('Unknown assertion: ' . json_encode($a));
        }
    }
    return $fails;
}

// ---------------------------------------------------------------- results

function normRows(array $rows, bool $ordered, ?array $columns): array
{
    $lines = array_map(fn ($r) => implode("\x1f", array_map(fn ($v) => $v ?? 'NULL', $r)), $rows);
    if (!$ordered) {
        sort($lines);
    }
    if ($columns !== null) {
        array_unshift($lines, implode("\x1f", $columns));
    }
    return $lines;
}

/** Runs one SELECT. Returns [lines, error]. */
function runQuery(string $sql, array $ex): array
{
    $sql = rtrim(trim(stripSqlComments($sql)), ";\n\r\t ");
    try {
        $st = pdo()->query($sql);
        $rows = $st->fetchAll(PDO::FETCH_NUM);
        $cols = null;
        if (!empty($ex['columns'])) {
            $cols = [];
            for ($i = 0; $i < $st->columnCount(); $i++) {
                $cols[] = $st->getColumnMeta($i)['name'];
            }
        }
        return [normRows($rows, !empty($ex['ordered']), $cols), null];
    } catch (PDOException $e) {
        return [null, ($e->errorInfo[1] ?? 0) . ': ' . ($e->errorInfo[2] ?? $e->getMessage())];
    }
}

// ---------------------------------------------------------------- checkpoints

function checkpoints(): array
{
    static $cps = null;
    if ($cps !== null) {
        return $cps;
    }
    $cps = [];
    foreach (glob(realpath(ROOT) . '/modules/*/*/checkpoints/*/exercises.json') as $file) {
        $cp = json_decode(file_get_contents($file), true, flags: JSON_THROW_ON_ERROR);
        $cp['dir'] = dirname($file);
        $cp['json'] = $file;
        $cps[$cp['checkpoint']] = $cp;
    }
    ksort($cps);
    return $cps;
}

function exercises(): array
{
    $out = [];
    foreach (checkpoints() as $cp) {
        foreach ($cp['exercises'] as $ex) {
            $ex['cp'] = $cp['checkpoint'];
            $ex['dir'] = $cp['dir'];
            $out[$ex['id']] = $ex;
        }
    }
    return $out;
}

function chainIndex(string $db, string $path): ?int
{
    foreach (chainDb($db)['steps'] as $i => $s) {
        if (realpath(ROOT) . '/' . $s['file'] === $path || resolve('/' . $s['file']) === $path) {
            return $i;
        }
    }
    return null;
}

/**
 * Runs one exercise. Returns ['status' => PASS|FAIL|PENDING, 'message' => string, 'note' => ?string, 'sql_error' => bool].
 */
function runExercise(array $ex, bool $fromSnapshot = false): array
{
    $result = fn ($status, $message = '', $sqlError = false, $note = null) => compact('status', 'message', 'note') + ['sql_error' => $sqlError];
    $file = isset($ex['file']) ? resolve($ex['file'], $ex['dir']) : null;

    if ($ex['type'] === 'manual') {
        if (!is_file($file)) {
            return $result('PENDING', 'create ' . rel($file));
        }
        $text = file_get_contents($file);
        $open = preg_match_all('/^\s*- \[ \]/m', $text);
        $done = preg_match_all('/^\s*- \[[xX]\]/m', $text);
        if ($done === 0) {
            return $result('PENDING', 'tick the boxes in ' . rel($file) . ' as you go');
        }
        return $open === 0 ? $result('PASS') : $result('PENDING', "$open box(es) left in " . rel($file));
    }

    if ($file !== null) {
        if (isEmptyFile($file)) {
            return $result('PENDING', rel($file) . ' is empty');
        }
        if ($why = forbiddenStatement($file)) {
            return $result('FAIL', $why, true);
        }
    }

    $db = $ex['db'];
    $scratch = "scratch_$db";
    $idx = $file !== null ? chainIndex($db, $file) : null;
    $n = $idx ?? stepsAfter($db, $ex['after'] ?? $ex['cp']);
    $state = buildState($db, $n, $scratch, $fromSnapshot, unitOf($ex['cp']));
    if (!$state['ok']) {
        return $result($state['pending'] ? 'PENDING' : 'FAIL', $state['message']);
    }
    $note = $state['note'];
    useDb($scratch);
    foreach ($ex['setup'] ?? [] as $sql) {
        pdo()->exec($sql);
    }

    switch ($ex['type']) {
        case 'query':
            [$got, $err] = runQuery(file_get_contents($file), $ex);
            if ($err !== null) {
                return $result('FAIL', "your query fails with error $err", true, $note);
            }
            if (!empty($ex['reference'])) {
                [$want, $err2] = runQuery(base64_decode($ex['reference']), $ex);
                if ($err2 !== null) {
                    return $result('FAIL', "the reference query cannot run on your database ($err2). Check your table and column names against the checkpoint README", false, $note);
                }
                if ($got === $want) {
                    return $result('PASS', '', false, $note);
                }
                return $result('FAIL', describeMismatch($got, $want, $ex), false, $note);
            }
            if (empty($ex['hash'])) {
                return $result('FAIL', 'this exercise has no expected result yet. Tell your instructor', false, $note);
            }
            if (hash('sha256', implode("\n", $got)) === $ex['hash']) {
                return $result('PASS', '', false, $note);
            }
            $count = count($got) - (empty($ex['columns']) ? 0 : 1);
            $msg = "your query returns $count row(s)";
            $msg .= isset($ex['rows']) && $ex['rows'] !== $count ? ", expected {$ex['rows']}" : ', but the values' . (empty($ex['columns']) ? '' : ' or column names') . (empty($ex['ordered']) ? '' : ' or the order') . ' differ';
            return $result('FAIL', $msg, false, $note);

        case 'error':
            $err = runSqlFile($scratch, $file);
            $want = (array) $ex['expect'];
            if ($err === null) {
                return $result('FAIL', 'the file ran without an error. Expected error ' . implode(' or ', $want), false, $note);
            }
            if (in_array($err['code'], $want, true)) {
                return $result('PASS', '', false, $note ?? "the server said: {$err['message']}");
            }
            return $result('FAIL', 'expected error ' . implode(' or ', $want) . ", got {$err['message']}", true, $note);

        case 'script':
        case 'structure':
            $before = [];
            foreach (array_keys(structureOf($scratch)) as $t) {
                $before[$t] = (int) q("SELECT COUNT(*) n FROM `$t`")[0]['n'];
            }
            if ($file !== null) {
                foreach (range(1, empty($ex['twice']) ? 1 : 2) as $run) {
                    if ($err = runSqlFile($scratch, $file)) {
                        return $result('FAIL', ($run === 2 ? 'running it a second time fails: ' : '') . $err['message'], true, $note);
                    }
                }
                foreach ($ex['forbid'] ?? [] as $pattern) {
                    if (preg_match('/' . $pattern . '/i', stripSqlComments(file_get_contents($file)))) {
                        $words = trim(str_replace(['\\b', '\\s+', '\\w+'], ['', ' ', '...'], $pattern));
                        return $result('FAIL', rel($file) . " must not use $words", false, $note);
                    }
                }
            }
            $fails = assertAll($scratch, $ex['assert'] ?? [], $ex['dir'], $before);
            return $fails ? $result('FAIL', implode('; ', $fails), false, $note) : $result('PASS', '', false, $note);
    }
    fail("Unknown exercise type {$ex['type']} in {$ex['id']}");
}

function describeMismatch(array $got, array $want, array $ex): string
{
    $h = empty($ex['columns']) ? 0 : 1;
    if ($h && $got[0] !== $want[0]) {
        return 'column names differ: got ' . str_replace("\x1f", ', ', $got[0]) . '; expected ' . str_replace("\x1f", ', ', $want[0]);
    }
    if (count($got) !== count($want)) {
        return 'your query returns ' . (count($got) - $h) . ' row(s), expected ' . (count($want) - $h);
    }
    foreach ($got as $i => $line) {
        if ($line !== $want[$i]) {
            return 'row ' . ($i + 1 - $h) . ' differs: got ' . str_replace("\x1f", ', ', $line) . (empty($ex['ordered']) ? ' (rows compared in sorted order)' : '');
        }
    }
    return 'results differ';
}

function printResult(string $id, array $r): void
{
    echo $r['status'] . " $id" . ($r['message'] !== '' ? ': ' . $r['message'] : '') . "\n";
    if ($r['note']) {
        echo "  note: {$r['note']}\n";
    }
}

/** @return array<string, array> results by exercise id */
function runMany(array $ids, bool $quiet = false, bool $fromSnapshot = false): array
{
    $all = exercises();
    $results = [];
    foreach ($ids as $id) {
        $results[$id] = runExercise($all[$id], $fromSnapshot);
        if (!$quiet) {
            printResult($id, $results[$id]);
        }
    }
    foreach (q("SELECT SCHEMA_NAME s FROM information_schema.SCHEMATA WHERE SCHEMA_NAME LIKE 'scratch\\_%'") as $r) {
        pdo()->exec("DROP DATABASE `{$r['s']}`");
    }
    return $results;
}

function summary(array $results): string
{
    $count = fn ($s) => count(array_filter($results, fn ($r) => $r['status'] === $s));
    return sprintf('%d passed, %d failed, %d pending', $count('PASS'), $count('FAIL'), $count('PENDING'));
}

function idsFor(string $sel): array
{
    $all = exercises();
    if (isset($all[$sel])) {
        return [$sel];
    }
    if (isset(checkpoints()[$sel])) {
        return array_column(checkpoints()[$sel]['exercises'], 'id');
    }
    fail("No exercise or checkpoint called \"$sel\". Run ./sql progress to see them all.");
}

function currentCheckpoint(array $results): ?string
{
    foreach (exercises() as $id => $ex) {
        if ($results[$id]['status'] !== 'PASS') {
            return $ex['cp'];
        }
    }
    return null;
}

function progressRows(array $results): array
{
    $rows = [];
    foreach (checkpoints() as $cpId => $cp) {
        $r = array_intersect_key($results, array_flip(array_column($cp['exercises'], 'id')));
        $count = fn ($s) => count(array_filter($r, fn ($x) => $x['status'] === $s));
        $rows[] = [$cpId, $cp['title'], $count('PASS'), $count('FAIL'), $count('PENDING'), count($r)];
    }
    return $rows;
}

// ---------------------------------------------------------------- commands

function fail(string $msg): never
{
    fwrite(STDERR, $msg . "\n");
    exit(2);
}

function cmdCheck(array $args): int
{
    $ci = in_array('--ci', $args, true);
    $args = array_values(array_diff($args, ['--ci']));
    if (($args[0] ?? null) === '--all') {
        $ids = array_keys(exercises());
    } elseif (isset($args[0])) {
        $ids = idsFor($args[0]);
    } else {
        $cp = currentCheckpoint(runMany(array_keys(exercises()), true));
        if ($cp === null) {
            echo "Everything passes.\n";
            return 0;
        }
        echo "Checking $cp\n";
        $ids = idsFor($cp);
    }
    $results = runMany($ids);
    echo summary($results) . "\n";
    if ($ci) {
        return count(array_filter($results, fn ($r) => $r['sql_error'])) ? 1 : 0;
    }
    return count(array_filter($results, fn ($r) => $r['status'] === 'FAIL')) ? 1 : 0;
}

function cmdProgress(array $args): int
{
    $markdown = in_array('--markdown', $args, true);
    $results = runMany(array_keys(exercises()), true);
    $rows = progressRows($results);
    if ($markdown) {
        echo "| Checkpoint | Title | Passed | Failed | Pending |\n|---|---|---|---|---|\n";
        foreach ($rows as $r) {
            echo "| {$r[0]} | {$r[1]} | {$r[2]}/{$r[5]} | {$r[3]} | {$r[4]} |\n";
        }
        echo "\n" . summary($results) . "\n";
        return 0;
    }
    printf("%-11s %-32s %8s %7s %8s\n", 'Checkpoint', 'Title', 'Passed', 'Failed', 'Pending');
    foreach ($rows as $r) {
        printf("%-11s %-32s %8s %7d %8d\n", $r[0], $r[1], "{$r[2]}/{$r[5]}", $r[3], $r[4]);
    }
    echo summary($results) . "\n";
    return 0;
}

function cmdNext(): int
{
    $results = runMany(array_keys(exercises()), true);
    foreach (exercises() as $id => $ex) {
        if ($results[$id]['status'] !== 'PASS') {
            echo "Next: $id ({$ex['type']})\n";
            echo '  ' . rel($ex['dir']) . "/README.md\n";
            printResult($id, $results[$id]);
            return 0;
        }
    }
    echo "Everything passes.\n";
    return 0;
}

function cmdHint(array $args): int
{
    $ex = exercises()[$args[0] ?? ''] ?? fail('Usage: ./sql hint <exercise id>');
    echo ($ex['hint'] ?? 'No hint for this one. Reread the checkpoint README.') . "\n";
    return 0;
}

function watchedFiles(array $cp): array
{
    $files = [$cp['json']];
    foreach ($cp['exercises'] as $ex) {
        if (isset($ex['file'])) {
            $files[] = resolve($ex['file'], $cp['dir']);
        }
        if (isset($ex['db'])) {
            foreach (chainDb($ex['db'])['steps'] as $s) {
                $files[] = resolve('/' . $s['file']);
            }
        }
    }
    return array_unique($files);
}

function cmdWatch(): int
{
    echo "Watching. Save a file to re-check its checkpoint. Press Ctrl+C to stop.\n";
    $seen = [];
    while (true) {
        clearstatcache();
        foreach (checkpoints() as $cpId => $cp) {
            $stamp = implode(',', array_map(fn ($f) => is_file($f) ? filemtime($f) . ':' . filesize($f) : '-', watchedFiles($cp)));
            if (isset($seen[$cpId]) && $seen[$cpId] !== $stamp) {
                echo "\n" . date('H:i:s') . " $cpId\n";
                echo summary(runMany(idsFor($cpId))) . "\n";
            }
            $seen[$cpId] = $stamp;
        }
        sleep(1);
    }
}

function cmdReset(array $args): int
{
    $db = $args[0] ?? fail('Usage: ./sql reset <db> [--at <unit>]');
    $at = ($args[1] ?? null) === '--at' ? ($args[2] ?? fail('Usage: ./sql reset <db> --at <unit>')) : chainDb($db)['base'];
    $file = snapshotFile($db, $at);
    freshDb($db);
    if ($err = runSqlFile($db, $file)) {
        fail("Cannot load " . rel($file) . ": {$err['message']}");
    }
    echo "$db now matches the reference state after $at.\n";
    return 0;
}

function rebuildOne(string $db): int
{
    $c = chainDb($db);
    freshDb($db);
    if ($err = runSqlFile($db, realpath(ROOT) . '/' . $c['snapshots'][$c['base']])) {
        fail("Cannot load the base snapshot for $db: {$err['message']}");
    }
    $done = "the reference state after {$c['base']}";
    foreach ($c['steps'] as $s) {
        $path = resolve('/' . $s['file']);
        if (isEmptyFile($path)) {
            echo "$db rebuilt up to $done. Next step: {$s['cp']} ({$s['file']}) is empty.\n";
            return 0;
        }
        if ($why = forbiddenStatement($path)) {
            echo "FAIL rebuild $db at {$s['cp']}: $why\n";
            return 1;
        }
        if ($err = runSqlFile($db, $path)) {
            echo "FAIL rebuild $db at {$s['cp']} ({$s['file']}): {$err['message']}\n";
            echo "  $db is left as it was after the step before. Fix the file and run ./sql rebuild $db again.\n";
            return 1;
        }
        $done = $s['cp'];
    }
    echo "$db rebuilt up to $done. Every step ran.\n";
    return 0;
}

function cmdRebuild(array $args): int
{
    $dbs = ($args[0] ?? null) === '--all' ? array_keys(chain()['databases']) : [$args[0] ?? fail('Usage: ./sql rebuild <db|--all>')];
    $rc = 0;
    foreach ($dbs as $db) {
        $rc = max($rc, rebuildOne($db));
    }
    return $rc;
}

function cmdShell(array $args): int
{
    $db = $args[0] ?? fail('Usage: ./sql shell <db>');
    chainDb($db);
    $proc = proc_open(client([$db]), [0 => STDIN, 1 => STDOUT, 2 => STDERR], $pipes);
    return proc_close($proc);
}

function cmdDrift(array $args): int
{
    $db = $args[0] ?? fail('Usage: ./sql drift <db>');
    chainDb($db);
    $file = realpath(ROOT) . "/db/$db/$db.dbml";
    $lines = driftLines($file, $db);
    foreach ($lines as $l) {
        echo "DRIFT $l\n";
    }
    echo $lines ? count($lines) . " difference(s) between db/$db/$db.dbml and $db\n" : "PASS db/$db/$db.dbml matches $db\n";
    return $lines ? 1 : 0;
}

function cmdSql2dbml(array $args): int
{
    $db = $args[0] ?? fail('Usage: ./sql sql2dbml <db>');
    chainDb($db);
    $out = realpath(ROOT) . "/db/$db/$db.dbml";
    if (!isEmptyFile($out) && !in_array('--force', $args, true)) {
        fail("db/$db/$db.dbml already has content. Add --force to replace it.");
    }
    $ddl = '';
    foreach (array_keys(structureOf($db)) as $t) {
        $ddl .= pdo()->query("SHOW CREATE TABLE `$db`.`$t`")->fetch(PDO::FETCH_NUM)[1] . ";\n\n";
    }
    [$ok, $text] = dbml('/sql2dbml', $ddl);
    if (!$ok) {
        fail("sql2dbml failed: $text");
    }
    @mkdir(dirname($out), 0777, true);
    file_put_contents($out, $text);
    echo "Wrote db/$db/$db.dbml. Paste it into https://dbdiagram.io to see the diagram.\n";
    return 0;
}

// ---------------------------------------------------------------- instructor commands

function needSolutions(): void
{
    if (!getenv('SOLUTIONS')) {
        fail('Set SOLUTIONS to the solutions repository, e.g. SOLUTIONS=../ice-bed-solutions ./sql ' . ($GLOBALS['argv'][1] ?? ''));
    }
}

function cmdHashgen(): int
{
    needSolutions();
    foreach (checkpoints() as $cp) {
        $changed = false;
        $json = json_decode(file_get_contents($cp['json']), true);
        foreach ($json['exercises'] as &$ex) {
            if ($ex['type'] !== 'query') {
                continue;
            }
            $file = resolve($ex['file'], $cp['dir']);
            if (!str_starts_with($file, getenv('SOLUTIONS')) || isEmptyFile($file)) {
                echo "SKIP {$ex['id']}: no solution\n";
                continue;
            }
            if (array_key_exists('reference', $ex)) {
                $ex['reference'] = base64_encode(trim(file_get_contents($file)) . "\n");
            } else {
                $state = buildState($ex['db'], stepsAfter($ex['db'], $ex['after'] ?? $cp['checkpoint']), "scratch_{$ex['db']}");
                if (!$state['ok']) {
                    fail("{$ex['id']}: {$state['message']}");
                }
                useDb("scratch_{$ex['db']}");
                foreach ($ex['setup'] ?? [] as $sql) {
                    pdo()->exec($sql);
                }
                [$lines, $err] = runQuery(file_get_contents($file), $ex);
                if ($err !== null) {
                    fail("{$ex['id']}: the solution fails: $err");
                }
                $ex['hash'] = hash('sha256', implode("\n", $lines));
                $ex['rows'] = count($lines) - (empty($ex['columns']) ? 0 : 1);
            }
            $changed = true;
            echo "HASH {$ex['id']}\n";
        }
        unset($ex);
        if ($changed) {
            file_put_contents($cp['json'], json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n");
        }
    }
    return 0;
}

function cmdSnapshot(array $args): int
{
    needSolutions();
    [$db, $unit] = [$args[0] ?? fail('Usage: ./sql snapshot <db> <unit>'), $args[1] ?? fail('Usage: ./sql snapshot <db> <unit>')];
    $file = snapshotFile($db, $unit);
    $k = null;
    foreach (boundaries($db) as $b) {
        if ($b[0] === $unit) {
            $k = $b[2];
        }
    }
    $state = buildState($db, $k, "ref_$db");
    if (!$state['ok'] || $state['note']) {
        fail("The reference chain for $db does not reach $unit: " . ($state['message'] ?: $state['note']));
    }
    dumpDb("ref_$db", $file);
    pdo()->exec("DROP DATABASE `ref_$db`");
    echo 'Wrote ' . rel($file) . "\n";
    return 0;
}

function cmdContinuity(): int
{
    needSolutions();
    $failed = 0;
    $line = function (bool $ok, string $what, string $why = '') use (&$failed) {
        echo ($ok ? 'PASS' : 'FAIL') . " $what" . ($why !== '' ? ": $why" : '') . "\n";
        $failed += $ok ? 0 : 1;
    };

    echo "Chains: replay every reference chain from its base and compare each unit boundary with its snapshot.\n";
    foreach (chain()['databases'] as $db => $c) {
        foreach (boundaries($db) as [$unit, $file, $k]) {
            if (!is_file(realpath(ROOT) . '/' . $file)) {
                echo "SKIP $db after-$unit: $file is not written yet\n";
                continue;
            }
            $state = buildState($db, $k, "ref_$db");
            if (!$state['ok'] || $state['note']) {
                $line(false, "$db after-$unit", $state['message'] ?: $state['note']);
                continue;
            }
            freshDb("snap_$db");
            if ($err = runSqlFile("snap_$db", realpath(ROOT) . '/' . $file)) {
                $line(false, "$db after-$unit", "cannot load $file: {$err['message']}");
                continue;
            }
            $diff = diffStructures(structureOf("ref_$db"), structureOf("snap_$db"), 'the replay', 'the snapshot');
            $dataA = dataOf("ref_$db");
            $dataB = dataOf("snap_$db");
            foreach ($dataA as $t => $h) {
                if (($dataB[$t] ?? null) !== $h) {
                    $diff[] = "$t: data differs";
                }
            }
            $line(!$diff, "$db after-$unit", implode('; ', $diff));
        }
        pdo()->exec("DROP DATABASE IF EXISTS `ref_$db`");
        pdo()->exec("DROP DATABASE IF EXISTS `snap_$db`");
    }

    foreach (['the reference chain' => false, 'the previous snapshot' => true] as $label => $fromSnapshot) {
        echo "\nCheckpoints: every reference solution, starting from $label.\n";
        foreach (runMany(array_keys(exercises()), true, $fromSnapshot) as $id => $r) {
            $line($r['status'] === 'PASS', $id, $r['status'] === 'PASS' ? '' : "{$r['status']} {$r['message']}");
        }
    }
    echo $failed ? "\n$failed failure(s)\n" : "\nThe course adds up.\n";
    return $failed ? 1 : 0;
}

function cmdAutograding(): int
{
    $tests = [];
    foreach (exercises() as $id => $ex) {
        $tests[] = ['name' => $id, 'setup' => '', 'run' => "./sql check $id", 'input' => '', 'output' => "PASS $id",
            'comparison' => 'included', 'timeout' => 2, 'points' => 1];
    }
    $path = realpath(ROOT) . '/.github/classroom/autograding.json';
    @mkdir(dirname($path), 0777, true);
    file_put_contents($path, json_encode(['tests' => $tests], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
    echo 'Wrote .github/classroom/autograding.json with ' . count($tests) . " tests\n";
    return 0;
}

// ---------------------------------------------------------------- main

$args = array_slice($argv, 1);
$cmd = array_shift($args) ?? 'help';
exit(match ($cmd) {
    'check' => cmdCheck($args),
    'watch' => cmdWatch(),
    'hint' => cmdHint($args),
    'next' => cmdNext(),
    'progress' => cmdProgress($args),
    'reset' => cmdReset($args),
    'rebuild' => cmdRebuild($args),
    'shell' => cmdShell($args),
    'drift' => cmdDrift($args),
    'sql2dbml' => cmdSql2dbml($args),
    'hashgen' => cmdHashgen(),
    'snapshot' => cmdSnapshot($args),
    'continuity' => cmdContinuity(),
    'autograding' => cmdAutograding(),
    'help', '--help', '-h' => (function () {
        echo STUDENT_COMMANDS . "\n" . (getenv('SOLUTIONS') ? INSTRUCTOR_COMMANDS . "\n" : '');
        return 0;
    })(),
    default => fail("Unknown command \"$cmd\".\n\n" . STUDENT_COMMANDS),
});
