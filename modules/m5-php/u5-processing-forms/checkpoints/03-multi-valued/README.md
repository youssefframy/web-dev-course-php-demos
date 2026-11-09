# Checkpoint 03: Multi-valued fields

## Goal

Receive fields that can hold more than one value, such as a multiple select and a group of checkboxes, and a radio group that holds exactly one.

## Concepts

- A `<select multiple>` lets the visitor pick several options. A group of checkboxes with the same name does the same.
- Normally PHP keeps only the last value it receives for a name. Adding `[]` to the name, as in `name="reason[]"`, tells PHP to collect every value into an array: `$_POST['reason']`.
- An `<option>` without a `value` sends its text. `<option>Sales</option>` sends `Sales`.
- A checkbox that is not ticked sends nothing at all. If none in a group are ticked, `$_POST['contact_method']` does not exist, so check it with `isset()` before using it.
- Radio buttons with the same `name` form a group: only one can be chosen. Each needs its own `value`, which is what PHP receives. `checked` on one of them makes it the default.
- `join(', ', $array)` returns the array's values as one string, with `', '` between them. `implode()` is the same function under another name.

## Before you start

You need the contact form from checkpoint 02. If you skipped it, copy the reference answer over yours:

```sh
cp -R solutions/modules/m5-php/u5-processing-forms/checkpoints/02-first-form/contact-form/. modules/m5-php/u5-processing-forms/contact-form/
```

Start the server in `contact-form/`.

## Steps

1. Follow along: in the form, between the email and the comments, add a multiple select:

   ```html
   <label for="reason" style="display: inline-block; width: 100px;">Query Type</label>
   <select name="reason[]" id="reason" multiple>
       <option>Technical Support</option>
       <option>Sales</option>
       <option>Bug Report</option>
       <option>General</option>
   </select><br><br>
   ```

   In the slides each `<option>` has a `name`. An option's `name` does nothing, so it is left out here.

   In the PHP block, after the `printf()`, add:

   ```php
   if (isset($_POST['reason']) && count($_POST['reason']) > 0) {
       echo '<p>Reasons for contacting us:</p>';
       echo '<ul>';
       foreach ($_POST['reason'] as $reason) {
           echo "<li>$reason</li>";
       }
       echo '</ul>';
   }
   ```

   The slides only check `count()`. Without `isset()`, sending the form with nothing selected gives a warning, because `$_POST['reason']` does not exist.

   Hold Ctrl (Cmd on a Mac) to select Sales and General, and send. *Check: a list with both reasons.* Send again with nothing selected. *Check: no list, and no warning.*

2. Follow along: under the select, add checkboxes and a radio group:

   ```html
   <label style="display: inline-block; width: 100px;">Contact me by</label>
   <input type="checkbox" name="contact_method[]" value="email">Email
   <input type="checkbox" name="contact_method[]" value="phone">Phone
   <input type="checkbox" name="contact_method[]" value="sms">SMS
   <input type="checkbox" name="contact_method[]" value="whatsapp">WhatsApp
   <br><br>
   <label style="display: inline-block; width: 100px;">Subscribe to Newsletter</label>
   <input type="radio" name="newsletter" value="yes">Yes
   <input type="radio" name="newsletter" value="no" checked>No
   <br><br>
   ```

   In the PHP block, after the reasons, add:

   ```php
   if (isset($_POST['contact_method'])) {
       printf('<p>We can contact you by %s</p>', join(', ', $_POST['contact_method']));
   }
   printf('<p>You %s want to subscribe to our newsletter</p>', $_POST['newsletter'] === 'yes' ? 'do' : 'do not');
   ```

   The slides give the second radio button the value `No` and then compare with `'no'`. Here both values are lower case.

   Tick Email and WhatsApp, choose Yes, and send. *Check: `We can contact you by email, whatsapp` and `You do want to subscribe`.* Send with no boxes ticked and No. *Check: no contact line, and `You do not want to subscribe`.*

3. Follow along: look at the Payload in the Network tab after sending with two reasons and two methods.

   *Check: `reason[]` and `contact_method[]` each appear twice, once per value. PHP joins them into arrays.*

4. Your turn: add a group of checkboxes called `best_time[]`, with the values `morning`, `afternoon` and `evening`, after the contact methods. When any are ticked, show `The best time to reach you is morning or evening`, with `or` between the values.

   *Check: one, two and three ticked boxes all read correctly, and none ticked shows nothing.*

## Done when

- [ ] The selected reasons show as a list.
- [ ] The ticked contact methods show as one line, separated by commas.
- [ ] The newsletter line follows the radio button.
- [ ] Sending with nothing selected or ticked gives no warnings.
- [ ] You can say why the names end in `[]`.

## Previous / Next

Previous: [02 A first form](../02-first-form/README.md). Next: [04 File uploads](../04-file-uploads/README.md).
