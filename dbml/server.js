// A small HTTP wrapper around @dbml/cli, so the PHP runner can convert files without Node.
//   POST /dbml2sql  body: DBML  ->  MariaDB DDL
//   POST /sql2dbml  body: DDL   ->  DBML
const http = require('node:http');
const fs = require('node:fs');
const os = require('node:os');
const path = require('node:path');
const { execFile } = require('node:child_process');

const tools = {
  '/dbml2sql': { bin: 'dbml2sql', ext: '.dbml' },
  '/sql2dbml': { bin: 'sql2dbml', ext: '.sql' },
};

http.createServer((req, res) => {
  if (req.url === '/health') {
    res.end('ok');
    return;
  }
  const tool = tools[req.url];
  if (!tool || req.method !== 'POST') {
    res.writeHead(404).end('Not found');
    return;
  }
  let body = '';
  req.on('data', (chunk) => { body += chunk; });
  req.on('end', () => {
    const dir = fs.mkdtempSync(path.join(os.tmpdir(), 'dbml-'));
    const file = path.join(dir, 'input' + tool.ext);
    fs.writeFileSync(file, body);
    const bin = path.join(__dirname, 'node_modules', '.bin', tool.bin);
    execFile(bin, [file, '--mysql'], { cwd: dir }, (err, stdout, stderr) => {
      fs.rmSync(dir, { recursive: true, force: true });
      if (err) {
        res.writeHead(422).end((stderr || stdout || err.message).trim());
        return;
      }
      res.writeHead(200, { 'Content-Type': 'text/plain' }).end(stdout);
    });
  });
}).listen(3000);
