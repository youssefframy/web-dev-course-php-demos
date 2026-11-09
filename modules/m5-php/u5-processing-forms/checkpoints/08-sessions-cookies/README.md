# Checkpoint 08: Sessions and cookies

## Goal

Make the page remember a visitor's name between requests with a session, and between browser restarts with a cookie.

## Concepts

- HTTP is stateless: the server treats every request as if it had never seen the browser before. Without help, a visitor who logged in on one page would be a stranger on the next.
- A session is the server's memory of one visitor. `session_start()` creates one, or finds the visitor's existing one, and makes the superglobal `$_SESSION` an array whose values are kept between that visitor's requests.
- PHP gives each session a random ID and sends it to the browser in a cookie called `PHPSESSID`. The browser sends the cookie back with every request, and that is how PHP finds the right session.
- `session_start()` sends a cookie, and cookies travel in the response headers, which go before any HTML. So it must run before the page outputs anything, not even a blank line. The same goes for `setcookie()`. If output has already started, PHP warns `headers already sent`, and the cookie is not set.
- The session cookie has no expiry date, so the browser deletes it when it closes. The session data on the server is also thrown away after about 24 minutes without a request.
- To remember something longer, store it in your own cookie: `setcookie('username', $user, time() + 604800, '/')`. The arguments are:
  - the cookie's name;
  - its value, a string of up to about 4KB;
  - when it expires, in seconds since 1 January 1970. `time()` is now, and `604800` seconds is one week. Leave it out and the cookie lasts until the browser closes;
  - the path, `/` for the whole site.

  Optional fifth and sixth arguments limit the cookie to a domain, and to HTTPS only.
- A cookie is stored by the visitor's browser, and they can read and change it. Never put anything secret in one, and treat what comes back as tainted input.
- A page with two forms needs to know which one was sent. A hidden field, `<input type="hidden" name="action" value="saveUser">`, sends a value the visitor does not see. PHP checks it to decide what to do.

## Before you start

You need the contact form from checkpoint 07. If you skipped it, copy the reference answer over yours:

```sh
cp -R solutions/modules/m5-php/u5-processing-forms/checkpoints/07-filtering/contact-form/. modules/m5-php/u5-processing-forms/contact-form/
```

Start the server in `contact-form/`.

## Steps

1. Follow along: start the session. Make `session_start();` the very first statement in `index.php`, so the file begins:

   ```php
   <?php
   session_start();

   $email = '';
   ```

2. Follow along: add a second form, for the visitor's name, after the contact form's `</form>` and before the `<script>`:

   ```php
   <?php if (isset($_SESSION['username'])) { ?>
       <h3>Welcome back, <?= htmlspecialchars($_SESSION['username']) ?></h3>
   <?php } else { ?>
       <form name="welcomeForm" method="post" action="index.php" style="margin-top: 20px;">
           <label for="username" style="display: inline-block; width: 100px;">Username</label>
           <input type="text" id="username" name="username" required>
           <input type="hidden" name="action" value="saveUser">
           <input type="submit" value="Save">
       </form>
   <?php } ?>
   ```

   The form only shows when the session has no username. The slides leave out the `</h3>`, and print the name without escaping it. A name is visitor input like any other.

3. Follow along: handle the new form. Find the line `if ($_SERVER['REQUEST_METHOD'] === 'POST') {` that starts the contact form's checks, and replace it with:

   ```php
   // The hidden field 'action' tells us which form was sent
   $action = filter_input(INPUT_POST, 'action');

   if ($action === 'saveUser') {
       $user = filter_input(INPUT_POST, 'username');
       if (isset($user) && $user !== '') {
           $_SESSION['username'] = $user;
       }
   } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
   ```

   The contact form has no `action` field, so it falls through to the `elseif` and is checked as before.

   Type a name and click Save. *Check: `Welcome back, ...` replaces the form.* Open the page in a new tab. *Check: still welcomed.*

4. Follow along: open the developer tools, then Application (Storage in Firefox), then Cookies, then `http://localhost:8000`.

   *Check: a `PHPSESSID` cookie with a long random value, and Expires set to `Session`.* Delete it and refresh. *Check: the name form is back. Without the cookie, PHP could not find your session and started a new, empty one.*

5. Follow along: add "Remember me". In the name form, replace the line with the username input with:

   ```html
   <input type="text" id="username" name="username" required><br><br>
   <label style="display: inline-block; width: 100px;"></label>
   <input type="checkbox" name="remember" value="remember">Remember me<br><br>
   <label style="display: inline-block; width: 100px;"></label>
   ```

   In the PHP, inside `if (isset($user) && $user !== '') {`, under `$_SESSION['username'] = $user;`, add:

   ```php
   if (filter_input(INPUT_POST, 'remember') === 'remember') {
       setcookie('username', $user, time() + 604800, '/');
   }
   ```

   Then, straight after `$errors = [];` and the `$reasons` line, copy a remembered name into a new session:

   ```php
   // A remembered visitor with a new session: copy the cookie into the session
   if (!isset($_SESSION['username']) && isset($_COOKIE['username'])) {
       $_SESSION['username'] = $_COOKIE['username'];
   }
   ```

   The slides do this copy inside the HTML, in an `elseif` that repeats the welcome message. Doing it once, at the top, keeps the HTML to one `if`.

6. Follow along: delete `PHPSESSID` again, refresh, and save a name with Remember me ticked.

   *Check: a `username` cookie that expires in a week.* Close the browser completely, open it again, and visit the page. *Check: welcomed by name, even though `PHPSESSID` is new.*

7. Your turn: add a "Forget me" button under the welcome message.
   - It is a small form with a hidden `action` of `forgetUser` and a submit button.
   - In the PHP, add an `elseif ($action === 'forgetUser')` branch that removes `username` from the session with `unset()`, and deletes the cookie. The only way to delete a cookie is to send it again with an expiry time in the past: `setcookie('username', '', time() - 3600, '/')`.

   *Check: after Forget me, the name form is back, the `username` cookie is gone from the developer tools, and closing and reopening the browser does not bring the name back.*

## Done when

- [ ] A saved name survives moving between tabs, but not deleting `PHPSESSID`.
- [ ] With Remember me, the name survives closing the browser.
- [ ] Forget me clears both the session and the cookie.
- [ ] The contact form still works as in checkpoint 07.
- [ ] You can say why `session_start()` must come before any output.

## Previous / Next

Previous: [07 Filtering input](../07-filtering/README.md). Next: [09 Convertomatic](../09-convertomatic/README.md).
