# Unit 5: Processing Forms

Until now, every page has only sent data to the browser. In this unit the browser sends data back. You look at what travels between browser and server, then build a contact form that PHP receives, checks, refills and stores files from. You make PHP remember a visitor between requests with sessions and cookies. Finally you put it all together in Convertomatic, a small unit converter that keeps a history.

## What you need

The same dev container as Units 1 to 4. Check PHP in a terminal:

```sh
php -v
```

The first line should start with `PHP 8.3`.

## How to run your pages

Checkpoint 01 has its own folder of practice files. Checkpoints 02 to 08 all work on one page, in [`contact-form/`](contact-form). Checkpoints 09 and 10 work on the app in [`convertomatic/`](convertomatic). Start the server in the folder the checkpoint names, for example:

```sh
cd modules/m5-php/u5-processing-forms/contact-form
php -S localhost:8000
```

Keep formatting every PHP file before you save it (Shift+Alt+F, or Shift+Option+F on a Mac).

## Checkpoints

| No. | Title | Outcome |
|---|---|---|
| 01 | [HTTP requests](checkpoints/01-http/README.md) | What a request and a response contain, verbs, status codes, and `$_GET` |
| 02 | [A first form](checkpoints/02-first-form/README.md) | A self-processing page with `$_POST`, made sticky |
| 03 | [Multi-valued fields](checkpoints/03-multi-valued/README.md) | Multiple selects, checkboxes and radio buttons |
| 04 | [File uploads](checkpoints/04-file-uploads/README.md) | `$_FILES`, checking the file type, saving with a unique name |
| 05 | [Server-side validation](checkpoints/05-server-validation/README.md) | A list of errors, and a form that keeps every choice |
| 06 | [Client-side validation](checkpoints/06-client-validation/README.md) | JavaScript checks before the form is sent |
| 07 | [Filtering input](checkpoints/07-filtering/README.md) | `filter_input()`, and escaping output with `htmlspecialchars()` |
| 08 | [Sessions and cookies](checkpoints/08-sessions-cookies/README.md) | Remembering a visitor with `$_SESSION` and `setcookie()` |
| 09 | [Convertomatic](checkpoints/09-convertomatic/README.md) | The unit project: a converter with a history |
| 10 | [Self-study](checkpoints/10-self-study/README.md) | Validating the other fields, more conversions, and the extra credit |

Checkpoints 02 to 08 build on each other, and each one tells you how to start from the previous answer. Checkpoint 10 builds on 09.
