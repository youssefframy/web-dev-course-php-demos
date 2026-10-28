# Notes: Unit 2 self-study

## Acceptable alternatives

- Any clear way of showing the four request details is fine: `printf()`, `<?= ?>` in HTML, or a table.
- `$_SERVER['REQUEST_TIME_FLOAT']` also works for the time, if it goes through `date()`. Printing the raw timestamp does not meet the step.
- `$_SERVER['PHP_SELF']` is close to `REQUEST_URI`, but it does not include `?test=1`, so it fails step 3.
- For the global variables, `get_defined_vars()` at the top level of the page gives the same list as `$GLOBALS`. Either `print_r()` or `var_dump()` is fine.
- For the file name, `$_SERVER['SCRIPT_FILENAME']` gives the same full path as `__FILE__` on this page. They differ inside an included file: `__FILE__` in `includes/footer.php` is the footer's path, while `SCRIPT_FILENAME` is still the page's.

## Things to look out for

- `$GLOBALS` includes `$_SERVER`, so the server environment appears twice on the page. That is expected.
- Some browsers send no user agent, which leaves `HTTP_USER_AGENT` unset. Every mainstream browser sends one, so the reference answer does not check for it. Unit 3 covers the `if` you would need.
