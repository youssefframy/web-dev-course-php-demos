<?php
session_start();

const SORT_COLUMNS = ['id', 'name', 'surname', 'grade'];

/**
 * Load a value from the session or, failing that, from a cookie.
 * @param string $key the key of the value to load
 * @param bool $json whether the cookie holds JSON that must be decoded
 * @return mixed the requested value, or null if not found
 */
function loadValue(string $key, bool $json = false): mixed
{
    $result = null;
    if (isset($_SESSION[$key])) {
        $result = $_SESSION[$key];
    } elseif (isset($_COOKIE[$key])) {
        $result = $json ? json_decode($_COOKIE[$key], true) : $_COOKIE[$key];
        $_SESSION[$key] = $result;
    }
    return $result;
}

/**
 * Save a value in the session and in a cookie.
 * @param string $key key to give the value
 * @param mixed $value the value to save
 * @param bool $json whether to JSON encode the value for the cookie
 * @param int $exp seconds until the cookie expires (default: about one month)
 * @return void
 */
function saveValue(string $key, mixed $value, bool $json = false, int $exp = 2628288): void
{
    $_SESSION[$key] = $value;
    setcookie($key, $json ? json_encode($value) : $value, time() + $exp, '/');
}

/**
 * Add the student sent by the form to the list.
 * @return void
 */
function addStudent(): void
{
    global $studentList, $errors;
    $name = trim((string) filter_input(INPUT_POST, 'student_name'));
    $surname = trim((string) filter_input(INPUT_POST, 'student_surname'));
    $grade = filter_input(INPUT_POST, 'student_grade', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 100]]);

    if ($name === '' || $surname === '') {
        $errors[] = 'Please give a name and a surname.';
    }
    if ($grade === false || $grade === null) {
        $errors[] = 'The grade must be a whole number from 0 to 100.';
    }
    if (count($errors) > 0) {
        return;
    }

    $studentList[] = ['name' => $name, 'surname' => $surname, 'grade' => $grade];
    saveValue('studentList', $studentList, true);
}

/**
 * Delete the student whose id is in the address.
 * @return void
 */
function deleteStudent(): void
{
    global $studentList;
    $id = filter_input(INPUT_GET, 'studId', FILTER_VALIDATE_INT);
    if ($id !== false && $id !== null) {
        unset($studentList[$id]); // Does nothing if the id is not there
        saveValue('studentList', $studentList, true);
    }
}

/**
 * Remember which column the list is sorted by, and in which direction.
 * Asking for the column that is already active flips the direction.
 * @return void
 */
function setSort(): void
{
    global $sortBy, $sortDir;
    $requested = filter_input(INPUT_GET, 'sortBy');
    if (!in_array($requested, SORT_COLUMNS, true)) {
        return;
    }
    if ($requested === $sortBy) {
        $sortDir = $sortDir === 'asc' ? 'desc' : 'asc';
    } else {
        $sortBy = $requested;
        $sortDir = 'asc';
    }
    saveValue('sortBy', $sortBy);
    saveValue('sortDir', $sortDir);
}

/**
 * Sort the list by the saved column and direction.
 * @return void
 */
function sortStudents(): void
{
    global $studentList, $sortBy, $sortDir;
    if ($sortBy === 'id') {
        $sortDir === 'asc' ? ksort($studentList) : krsort($studentList);
    } else {
        $direction = $sortDir === 'asc' ? 1 : -1;
        uasort($studentList, fn($a, $b) => $direction * ($a[$sortBy] <=> $b[$sortBy]));
    }
}

/**
 * Keep only the students whose name, surname or grade contains the search text.
 * The saved list is not changed.
 * @param array $students the full list
 * @param string $search the text to look for
 * @return array the matching students, with their ids as keys
 */
function searchStudents(array $students, string $search): array
{
    if ($search === '') {
        return $students;
    }
    $needle = strtolower($search);
    return array_filter(
        $students,
        fn($student) => str_contains(strtolower("{$student['name']} {$student['surname']} {$student['grade']}"), $needle)
    );
}

// Initialise values
$errors = [];
$studentList = loadValue('studentList', true) ?? [];
$sortBy = loadValue('sortBy');
if (!in_array($sortBy, SORT_COLUMNS, true)) {
    $sortBy = 'id';
}
$sortDir = loadValue('sortDir') === 'desc' ? 'desc' : 'asc';
$search = trim((string) filter_input(INPUT_GET, 'q'));

// Determine action
$action = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = filter_input(INPUT_POST, 'action');
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = filter_input(INPUT_GET, 'action');
}

switch ($action) {
    case 'add':
        addStudent();
        break;
    case 'delete':
        deleteStudent();
        break;
    case 'sort':
        setSort();
        break;
}

sortStudents();

$visibleStudents = searchStudents($studentList, $search);
