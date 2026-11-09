# Checkpoint 09: Convertomatic

## Goal

Build Convertomatic, a unit converter, with functions for the work, a form for the input, and a session and cookies to keep a history of conversions.

## Concepts

- Nothing new: this checkpoint puts together functions from Unit 4 and forms, sessions and cookies from this unit.
- The app is split into files. `index.php` is the page. `includes/main.inc.php` holds the HTML of the app. You add `includes/convert.inc.php` for the PHP that does the work. The `.inc.php` ending is a common way to mark a file that is meant to be included, not visited.
- In the slides, all the PHP goes at the top of `main.inc.php`. But `index.php` outputs its `<head>` before it includes that file, and by then it is too late for `session_start()` and `setcookie()`. Some PHP set-ups hide the problem by holding back output (`output_buffering` in `php.ini`). This dev container does not. So here the PHP lives in its own file, required on the very first line of `index.php`.
- `is_numeric()` is true for a number, or a string that holds one, such as `'42'` or `'-3.5'`.
- `round($value, $places)` rounds to a number of decimal places. In `printf()`, `%.2f` prints a float with two decimals. Building the format with the accuracy, as `"%.{$accuracy}f"`, prints exactly as many as the visitor chose.
- A cookie can only hold text. `json_encode()` turns an array into a JSON string, and `json_decode($json, true)` turns it back into an array. The `true` asks for an array, not an object.
- A cookie holds about 4KB. The history is cut to the last 10 conversions with `array_slice($history, -10)`, so it always fits.
- The only way to delete a cookie is to send it again with an expiry time in the past.
- A comment that starts with `/**` and lists `@param` and `@return` is a docblock. Editors show it when you hover over a call to the function.

## Before you start

The starter app is in the unit's [`convertomatic/`](../../convertomatic) folder. It is the one the slides clone from GitHub, so you do not need to clone anything. Start the server there:

```sh
cd modules/m5-php/u5-processing-forms/convertomatic
php -S localhost:8000
```

Visit <http://localhost:8000>. *Check: a purple page with the Convertomatic form. Nothing happens yet when you click Convert.*

## Steps

1. Follow along: create `includes/convert.inc.php` with:

   ```php
   <?php
   $errors = [];

   /**
    * Checks whether a value was given in the conversion form and whether it is numeric
    * @param mixed $value the value to check
    * @return bool true if value is valid
    */
   function isValidInput($value): bool
   {
       return isset($value) && is_numeric($value);
   }

   /**
    * Convert a value from one unit to another
    * @param string $conversionType the type of conversion to carry out
    * @param float $value the value to convert
    * @param int $accuracy how many decimal places to round the result to
    * @return array an associative array containing 'fromValue', 'toValue', 'fromUnit' and 'toUnit'
    */
   function convert(string $conversionType, float $value, int $accuracy): array
   {
       $result = [];
       switch ($conversionType) {
           case 'c_to_f':
               $convertedValue = round(($value * 9 / 5) + 32, $accuracy);
               $fromUnit = 'C';
               $toUnit = 'F';
               break;
           case 'f_to_c':
               $convertedValue = round(($value - 32) * 5 / 9, $accuracy);
               $fromUnit = 'F';
               $toUnit = 'C';
               break;
           case 'km_to_mi':
               $convertedValue = round($value * 0.621371, $accuracy);
               $fromUnit = 'km';
               $toUnit = 'mi';
               break;
           case 'mi_to_km':
               $convertedValue = round($value / 0.621371, $accuracy);
               $fromUnit = 'mi';
               $toUnit = 'km';
               break;
           case 'cm_to_in':
               $convertedValue = round($value / 2.54, $accuracy);
               $fromUnit = 'cm';
               $toUnit = '"';
               break;
       }
       $result['fromValue'] = $value;
       $result['toValue'] = $convertedValue;
       $result['fromUnit'] = $fromUnit;
       $result['toUnit'] = $toUnit;
       return $result;
   }

   // Process the form
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $inputValue = filter_input(INPUT_POST, 'convert_value');
       if (!isValidInput($inputValue)) {
           $errors[] = 'Invalid input value.';
       } else {
           $conversionType = filter_input(INPUT_POST, 'convert_type');
           $accuracy = (int) filter_input(INPUT_POST, 'convert_accuracy');
           $keepHistory = filter_input(INPUT_POST, 'convert_keep_history') === 'on';
           $result = convert($conversionType, $inputValue, $accuracy);
       }
   }
   ```

   A form field that was not sent gives `null` from `filter_input()`, so `isValidInput()` checks `isset()` as well as `is_numeric()`. The slides start `convert()` with `$value = floatval($value);`. The `float` type hint has already done that, so it is left out.

2. Follow along: make it the very first line of `index.php`, above `<!doctype html>`:

   ```php
   <?php require 'includes/convert.inc.php'; ?>
   ```

   `require` is `include` that stops the page if the file is missing.

3. Follow along: in `includes/main.inc.php`, replace the last card, the one with `// TODO Show results here`, with an error box and a result card:

   ```php
   <?php if (count($errors) > 0) { ?>
       <div class="p-2 g-col-12">
           <div class="alert alert-danger mb-0">
               <?php
               foreach ($errors as $error) {
                   echo "<p class='mb-0'>$error</p>";
               }
               ?>
           </div>
       </div>
   <?php } ?>
   <?php if (isset($result)) { ?>
       <div class="p-2 g-col-12">
           <div class="card">
               <div class="card-body">
                   <?php
                   printf("<h3>%.{$accuracy}f%s = %.{$accuracy}f%s</h3>", $result['fromValue'], $result['fromUnit'], $result['toValue'], $result['toUnit']);
                   ?>
               </div>
           </div>
       </div>
   <?php } ?>
   ```

   The slides collect `$errors` but never show them. The browser's `required` stops most empty forms anyway, but the server must still say what went wrong.

4. Follow along: try it. Convert 100 Celsius to Fahrenheit, then 10 kilometres to miles with the accuracy at 2.

   *Check: `100.0C = 212.0F` with the slider at 1, then `10.00km = 6.21mi`.* Every result replaces the last one. Nothing is remembered yet.

5. Follow along: add state. Replace the whole of `includes/convert.inc.php` with:

   ```php
   <?php
   session_start();

   $accuracy = 1; // Default accuracy
   $errors = [];

   /**
    * Checks whether a value was given in the conversion form and whether it is numeric
    * @param mixed $value the value to check
    * @return bool true if value is valid
    */
   function isValidInput($value): bool
   {
       return isset($value) && is_numeric($value);
   }

   /**
    * Convert a value from one unit to another
    * @param string $conversionType the type of conversion to carry out
    * @param float $value the value to convert
    * @param int $accuracy how many decimal places to round the result to
    * @return array an associative array containing 'fromValue', 'toValue', 'fromUnit' and 'toUnit'
    */
   function convert(string $conversionType, float $value, int $accuracy): array
   {
       $result = [];
       switch ($conversionType) {
           case 'c_to_f':
               $convertedValue = round(($value * 9 / 5) + 32, $accuracy);
               $fromUnit = 'C';
               $toUnit = 'F';
               break;
           case 'f_to_c':
               $convertedValue = round(($value - 32) * 5 / 9, $accuracy);
               $fromUnit = 'F';
               $toUnit = 'C';
               break;
           case 'km_to_mi':
               $convertedValue = round($value * 0.621371, $accuracy);
               $fromUnit = 'km';
               $toUnit = 'mi';
               break;
           case 'mi_to_km':
               $convertedValue = round($value / 0.621371, $accuracy);
               $fromUnit = 'mi';
               $toUnit = 'km';
               break;
           case 'cm_to_in':
               $convertedValue = round($value / 2.54, $accuracy);
               $fromUnit = 'cm';
               $toUnit = '"';
               break;
       }
       $result['fromValue'] = $value;
       $result['toValue'] = $convertedValue;
       $result['fromUnit'] = $fromUnit;
       $result['toUnit'] = $toUnit;
       return $result;
   }

   // Load history from cookie if current session is empty
   if (isset($_COOKIE['conversionHistory']) && !isset($_SESSION['conversionHistory'])) {
       $_SESSION['conversionHistory'] = json_decode($_COOKIE['conversionHistory'], true);
       $_SESSION['accuracy'] = $_COOKIE['accuracy'] ?? $accuracy;
   }
   if (isset($_SESSION['accuracy'])) {
       $accuracy = (int) $_SESSION['accuracy'];
   }
   $keepHistory = isset($_SESSION['conversionHistory']); // If there's no history, disable keeping history by default

   // Process the form
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $inputValue = filter_input(INPUT_POST, 'convert_value');
       if (!isValidInput($inputValue)) {
           $errors[] = 'Invalid input value.';
       } else {
           $conversionType = filter_input(INPUT_POST, 'convert_type');
           $accuracy = (int) filter_input(INPUT_POST, 'convert_accuracy');
           $keepHistory = filter_input(INPUT_POST, 'convert_keep_history') === 'on';
           $result = convert($conversionType, $inputValue, $accuracy);

           if ($keepHistory) {
               $_SESSION['conversionHistory'][] = $result;
               // A cookie holds about 4KB, so keep only the last 10 conversions
               $_SESSION['conversionHistory'] = array_slice($_SESSION['conversionHistory'], -10);
               $_SESSION['accuracy'] = $accuracy;
               setcookie('conversionHistory', json_encode($_SESSION['conversionHistory']), time() + 604800, '/');
               setcookie('accuracy', $accuracy, time() + 604800, '/');
           } else {
               // The only way to delete a cookie is to make it expire
               unset($_SESSION['conversionHistory']);
               unset($_COOKIE['conversionHistory']);
               setcookie('conversionHistory', '', -1, '/');
               unset($_SESSION['accuracy']);
               unset($_COOKIE['accuracy']);
               setcookie('accuracy', '', -1, '/');
           }
       }
   }
   ```

   What changed:
   - `session_start()` is first, and `$accuracy` starts at 1.
   - Before the form is handled, a history cookie is copied into a new session, and the accuracy is taken from the session.
   - `$keepHistory` starts on only when there is a history.
   - With Keep History on, each result is added to the history, cut to the last 10, and saved in the session and in two cookies. With it off, both are removed and the cookies expire.

   The slides only read the accuracy from the cookie, and only when the session is empty. Here it is also read from the session, so it stays right on every page load.

6. Follow along: make the slider and the switch show the saved settings. In `includes/main.inc.php`, the accuracy label and slider become:

   ```php
   <label for="convert_accuracy" class="form-label">Accuracy: <span
           id="accuracy_label"><?= $accuracy ?></span> decimal places</label>
   <input type="range" name="convert_accuracy" id="convert_accuracy" class="form-range" min="0"
       max="5" step="1" oninput="updateAccuracy()" value="<?= $accuracy ?>">
   ```

   And the switch gets `checked` when history is on:

   ```php
   <input name="convert_keep_history" id="convert_keep_history" class="form-check-input"
       type="checkbox" role="switch" style="transform: scale(1.5);" <?= $keepHistory ? 'checked' : '' ?>>
   ```

7. Follow along: show the history under the result. Replace the result card from step 3 with:

   ```php
   <?php
   if (isset($result) || (isset($_SESSION['conversionHistory']) && count($_SESSION['conversionHistory']) > 0)) {
   ?>
       <div class="p-2 g-col-12">
           <div class="card">
               <div class="card-body">
                   <?php
                   if (isset($result)) {
                       printf("<h3>%.{$accuracy}f%s = %.{$accuracy}f%s</h3>", $result['fromValue'], $result['fromUnit'], $result['toValue'], $result['toUnit']);
                       echo '<hr>';
                   }
                   if (isset($_SESSION['conversionHistory'])) {
                       $history = $_SESSION['conversionHistory'];
                       // The newest conversion is already shown above when there is a result
                       $newest = isset($result) ? count($history) - 2 : count($history) - 1;
                       for ($i = $newest; $i >= 0; $i--) {
                           $item = $history[$i];
                           printf(
                               "<h4 class='text-body-secondary'>%.{$accuracy}f%s = %.{$accuracy}f%s</h4>",
                               $item['fromValue'],
                               $item['fromUnit'],
                               $item['toValue'],
                               $item['toUnit']
                           );
                           echo $i === 0 ? '' : '<hr>';
                       }
                   }
                   ?>
               </div>
           </div>
       </div>
   <?php } ?>
   ```

   The newest conversion is the last one in the history. When there is a fresh `$result`, it is already shown at the top, so the loop starts one earlier. The slides always start one earlier, so after a plain page reload the newest conversion disappears.

8. Follow along: test the history.
   - Turn on Keep History and do three conversions. *Check: the newest at the top, the older ones below in grey.*
   - Refresh the page with Ctrl+R (not the form). *Check: the history is still there, the switch is on and the slider is where you left it.*
   - Close the browser, open it again, and visit the page. *Check: the history came back from the cookie.*
   - Turn Keep History off and convert. *Check: only the new result shows, and in the developer tools the `conversionHistory` and `accuracy` cookies are gone.*

9. Your turn: after each conversion the value box is empty again. Make it sticky, so it shows the value just converted. Remember that this value came from the visitor.

   *Check: the value stays after converting. Typing `"><b>` and converting shows those characters in the box, without breaking the page.*

## Done when

- [ ] Every conversion gives the right answer, rounded to the chosen accuracy.
- [ ] With Keep History on, the history survives a refresh and a browser restart, and stops at 10.
- [ ] With it off, the history and both cookies are gone.
- [ ] There are no `headers already sent` warnings.
- [ ] You can say why the PHP is required before `<!doctype html>`.

## Previous / Next

Previous: [08 Sessions and cookies](../08-sessions-cookies/README.md). Next: [10 Self-study](../10-self-study/README.md).
