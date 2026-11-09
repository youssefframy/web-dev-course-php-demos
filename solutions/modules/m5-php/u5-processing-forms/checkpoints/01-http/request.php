<?php
// Request information

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
