# Checkpoint 07: Self-study

## Goal

Use what you have learnt in this unit, without step-by-step instructions, to make the PHP Summary page describe the visitor's request.

## Concepts

Nothing new. You need:

- `$_SERVER`, which you dumped on the PHP Summary page in checkpoint 06
- `printf()` or `<?= ?>`, from checkpoint 04 and Unit 1
- `date()`, from Unit 1 checkpoint 03
- `var_dump()` or `print_r()` inside `<pre>`, from checkpoint 05

Every time a browser asks for a page, it sends a request, and PHP stores details about it in `$_SERVER`. The keys that start with `HTTP_` are copied from what the browser sent. The others are filled in by the server.

## Before you start

You need the Intro Website from checkpoint 06, with the PHP Summary page. If you skipped it, copy the reference files over yours:

```sh
cp -R solutions/modules/m5-php/u2-language-basics/checkpoints/06-footer-summary/intro-website/. modules/m5-php/u2-language-basics/intro-website/
```

## Steps

1. On the PHP Summary page, under the extension lines and above the list of extensions, add a "Your request" section. Show at least these four details, each with a clear label:
   - the method the browser used, for example `GET`
   - the address that was asked for, starting from the first `/`
   - the browser, as the browser describes itself
   - the time of the request, in the same format as the home page's "Last updated" date

   Look through the `$_SERVER` dump to find the right keys. The time is a UNIX timestamp.
2. Visit the page in a second browser, or a private window, and check that the browser line changes.
3. Add `?test=1` to the end of the address in the browser, and check that the address line shows it.
4. Extra credit: add two more sections.
   - Global variables: every variable that exists at that point in the page, with its value. PHP keeps them all in the superglobal `$GLOBALS`. Look it up on <https://www.php.net>.
   - Current file: the full path of the file that is running, and its name on its own. Look up `__FILE__` and `basename()`.

## Done when

- [ ] The PHP Summary page shows the request method, address, browser and time, each labelled.
- [ ] The browser line changes in a different browser, and the address line shows `?test=1`.
- [ ] Extra credit: the page lists the global variables, and shows the current file's path and name.
- [ ] Your work is committed and pushed (see [How to submit](../../../../../README.md#how-to-submit)).

## Previous / Next

Previous: [06 Footer and PHP Summary](../06-footer-summary/README.md). Next: Unit 3, coming soon.
