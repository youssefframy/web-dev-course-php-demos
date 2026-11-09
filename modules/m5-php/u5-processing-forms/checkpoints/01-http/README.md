# Checkpoint 01: HTTP requests

## Goal

See what the browser sends to the server and what comes back, and read the parts PHP gives you.

## Concepts

- Every page load is an HTTP request from the browser and an HTTP response from the server. Both are plain text: a first line, some headers, a blank line, and then a body if there is one.
- The request's first line holds the method, the path and the HTTP version, for example `GET /index.php HTTP/1.1`. Headers follow, such as `Host`, `User-Agent` (which browser) and `Accept` (which content types it understands).
- The response's first line holds a status code, for example `HTTP/1.1 200 OK`. Headers such as `Content-Type` and `Content-Length` follow, then the body: the HTML.
- The method, or verb, says what the request is for. `GET` asks for something. `POST` sends data to create something. `HEAD` is a `GET` without the body. `PUT` replaces, `PATCH` changes part of, and `DELETE` removes something on the server. You will use the last three when you build an API in Unit 9.
- Status codes are grouped by their first digit: 2xx success (`200 OK`, `201 Created`), 3xx go somewhere else (`301 Moved Permanently`, `303 See Other`), 4xx the client got it wrong (`400 Bad Request`, `401 Unauthorized`, `403 Forbidden`, `404 Not Found`, `405 Method Not Allowed`, `413 Payload Too Large`), and 5xx the server failed (`500 Internal Server Error`).
- A query string is the part of an address after `?`: `product.php?id=23&sort=name`. Each `name=value` pair is a query parameter, and `&` separates them.
- PHP puts each part of the request into an array you can read from anywhere: `$_GET` (query parameters), `$_POST` (form data), `$_COOKIE`, `$_FILES` (uploads) and `$_SERVER` (the request and the server). `$_ENV` holds the environment. Together they are known as EGPCS. Because every function can read them, they are called superglobals.

## Before you start

Start the server in this checkpoint's folder:

```sh
cd modules/m5-php/u5-processing-forms/checkpoints/01-http
php -S localhost:8000
```

## Steps

1. Follow along: in `request.php`, under the comment, add:

   ```php
   echo '<h5>The Request</h5>';
   printf('<p>Method: %s</p>', $_SERVER['REQUEST_METHOD']);
   printf('<p>Address: %s</p>', $_SERVER['REQUEST_URI']);
   printf('<p>Query string: %s</p>', $_SERVER['QUERY_STRING'] ?? '(none)');

   echo '<h5>Query Parameters</h5>';
   echo '<pre>';
   print_r($_GET);
   echo '</pre>';

   echo '<h5>Request Headers</h5>';
   printf('<p>Browser: %s</p>', $_SERVER['HTTP_USER_AGENT'] ?? '(unknown)');
   printf('<p>Accepts: %s</p>', $_SERVER['HTTP_ACCEPT'] ?? '(unknown)');
   ```

   Visit <http://localhost:8000/request.php>. *Check: method `GET`, no query string, and an empty array.*

2. Follow along: visit <http://localhost:8000/request.php?id=23&sort=name>.

   *Check: the query string `id=23&sort=name`, and an array with `[id] => 23` and `[sort] => name`.* Each request header arrives in `$_SERVER` with `HTTP_` in front of its name.

3. Follow along: watch the same request in the browser. Open the developer tools, go to the Network tab, and refresh. Click `request.php`.

   *Check: under Headers, the request method `GET`, the status `200 OK`, and the request headers `User-Agent` and `Accept`. Under Response, the HTML that PHP produced.*

4. Follow along: see the raw response in a second terminal:

   ```sh
   curl -i "http://localhost:8000/request.php?id=23"
   ```

   `-i` prints the response headers above the body. *Check: `HTTP/1.1 200 OK`, a `Content-type` header, a blank line, then the HTML.* Now ask for a page that does not exist:

   ```sh
   curl -i http://localhost:8000/missing.php
   ```

   *Check: `HTTP/1.1 404 Not Found`.*

5. Your turn: create `product.php` in this folder. It reads the query parameters `id` and `sort` from `$_GET`, and shows `Product ID: 23` and `Sorted by: name`. If a parameter is missing, show `none` instead, using `??`.

   *Check: <http://localhost:8000/product.php?id=23&sort=name> shows both values. <http://localhost:8000/product.php?id=7> shows `Sorted by: none`, with no warning.*

## Done when

- [ ] `request.php` shows the method, the query string and the parameters.
- [ ] You found the same request and response in the Network tab.
- [ ] `product.php` works with and without each parameter.
- [ ] You can say what a `404` and a `500` mean, and whose fault each one is.

## Previous / Next

Previous: [Unit 4, 07 Self-study](../../../u4-functions/checkpoints/07-self-study/README.md). Next: [02 A first form](../02-first-form/README.md).
