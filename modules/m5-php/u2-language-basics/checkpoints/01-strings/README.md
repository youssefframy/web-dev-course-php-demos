# Checkpoint 01: Strings and comments

## Goal

Build text in PHP in several ways, and choose the right kind of string for the job.

## Concepts

- A variable name starts with `$`, then a letter or `_`. After that, letters, digits and `_` are allowed. No spaces. Write two-word names in camel case, `$firstName`, or with underscores, `$first_name`. This course uses camel case.
- Names are case-sensitive: `$surname` and `$Surname` are two different variables.
- A value typed straight into the code, such as `'Maria'` or `42`, is a literal. A name you give to something, such as `$surname`, is an identifier.
- PHP works out each variable's type from its value. You never declare it.
- The `.` operator joins strings: `'Hello ' . $firstName`. This is called concatenation.
- Single quotes give you exactly what you typed. Double quotes also replace each variable name with its value. This is called interpolation.

  ```php
  echo 'Hi $firstName';  // Hi $firstName
  echo "Hi $firstName";  // Hi Maria
  ```

- A heredoc is a long double-quoted string. It starts with `<<<` and a name of your choice, and ends with the same name and a `;`. Inside it, quotes need no backslashes and variables are interpolated. Put the closing name on its own line.

  ```php
  $note = <<<NOTE
      <p>"$firstName" says hello.</p>
      NOTE;
  ```

- A nowdoc is the same, with the name in single quotes: `<<<'NOTE'`. Like a single-quoted string, it is not interpolated.
- PHP sends a new line in your output as a `\n` character, and HTML ignores it. To start a new line on the page, use HTML such as `<p>` or `<br>`.
- Comments are ignored by PHP:

  | Comment | Use |
  |---|---|
  | `// ...` | One line. The usual choice. |
  | `# ...` | One line, shell style. |
  | `/* ... */` | Several lines. |
  | `/** ... */` | A documentation comment, which can hold tags such as `@author`. Tools such as phpDocumentor read it. |

## Before you start

Start the server in this checkpoint's folder:

```sh
cd modules/m5-php/u2-language-basics/checkpoints/01-strings
php -S localhost:8000
```

## Steps

1. Open `strings.php` and visit <http://localhost:8000/strings.php>. *Check: the page says `Maria`.*

   The file has no closing `?>`. Nothing comes after the PHP, so it is not needed. PSR-12 says to leave it out.

2. Follow along: add `echo $Surname;` at the end of the file. Save and refresh. *Check: a warning about an undefined variable `$Surname`. The variable is called `$surname`, with a lower-case s.* Remove the line.

3. Follow along: add this line at the end of the file:

   ```php
   echo 'My name is ' . $firstName;
   ```

   *Check: `MariaMy name is Maria`, all on one line. There are two `echo` statements, but nothing tells the browser to start a new line.*

   Wrap the second one in a paragraph instead:

   ```php
   echo '<p>My name is ' . $firstName . '</p>';
   ```

4. Follow along: add these two lines, which differ only in their quotes:

   ```php
   echo '<p>My surname is $surname</p>';
   echo "<p>My surname is $surname</p>";
   ```

   *Check: the first shows `$surname` as typed. The second shows `Borg`.*

5. Follow along: add a short biography as a heredoc, then show it:

   ```php
   $bio = <<<BIO
       <p>
           $firstName $surname lives in Mosta. She describes herself as
           "a <strong>back-end</strong> developer in training".
       </p>
       BIO;

   echo $bio;
   ```

   *Check: the quotes appear on the page, and you did not need a single backslash.*

6. Change `<<<BIO` to `<<<'BIO'`, so that it becomes a nowdoc. Refresh. *Check: `$firstName $surname` is shown as typed.* Change it back.

7. Follow along: add one comment of each kind from the table above. Add a documentation comment at the very top of the file, under `<?php`, with an `@author` tag giving your name. *Check: the page looks exactly the same as before.*

8. Format the file (Shift+Alt+F, or Shift+Option+F on a Mac).

9. Your turn: create `postcard.php` in this folder.
   - Start it with a documentation comment, with your name in an `@author` tag.
   - Create three variables: `$town`, `$sender` and `$weather`.
   - Using one heredoc, build a postcard: an `<h2>` that says `Greetings from` and the town, then a paragraph that uses all three variables and contains `"Wish you were here!"`, quotes included. Echo it.
   - Under the postcard, show the sender's signature twice. Use concatenation with single quotes the first time, and interpolation with double quotes the second time: `<p>Love, Maria</p>`. Both lines must look the same on the page.

   Visit <http://localhost:8000/postcard.php>. *Check: change `$town`, refresh, and the heading and paragraph both change.*

## Done when

- [ ] `strings.php` shows the paragraphs from steps 3 to 5, and its comments do not show on the page.
- [ ] `postcard.php` builds the postcard with a heredoc, and shows the signature twice in the same way.
- [ ] Both files are formatted.
- [ ] You can say when you would pick a heredoc over a double-quoted string, and when you would pick a nowdoc.

## Previous / Next

Previous: [Unit 2 overview](../../README.md). Next: [02 Data types](../02-data-types/README.md).
