# Checkpoint 01: Dark mode from the system setting

## Goal

Finish the extra credit from Unit 3 together: on a visitor's first visit, the Intro Website starts in light or dark mode to match their operating system.

## Concepts

- Most operating systems have a light and a dark setting, and browsers pass it on to web pages. CSS reads it with the media query `prefers-color-scheme`.
- In JavaScript, `window.matchMedia('(prefers-color-scheme: dark)')` asks the browser about that media query. Its `.matches` property is `true` when the system is set to dark.
- PHP runs on the server and has finished drawing the page before any JavaScript runs in the browser. PHP only learns about dark mode from the `darkmode` cookie, which arrives with the next request.
- So on the first visit the order is: PHP draws a light page, the script sees there is no cookie, sets one from the system setting, and reloads. On the reload, PHP reads the cookie and adds `data-bs-theme="dark"`.
- The script only checks the system setting while there is no cookie. Once there is one, the visitor's own choice with the switch wins.
- The script also stores `'false'` for a light system. Without a cookie, the check would run again on every page.

## Before you start

Unit 4 has its own copy of the Intro Website in [`intro-website/`](../../intro-website), as it was at the end of Unit 3, with the footer built from an array. Only the extra credit is missing. To carry on with your own copy instead, copy it over the provided one:

```sh
cp -R modules/m5-php/u3-operators-control-flow/intro-website/. modules/m5-php/u4-functions/intro-website/
```

If you did the extra credit in Unit 3, compare your `js/darkmode.js` with the steps below. Start the server:

```sh
cd modules/m5-php/u4-functions/intro-website
php -S localhost:8000
```

## Steps

1. Follow along: open `js/darkmode.js`. Find the block that marks the switch as active:

   ```js
   if (darkmodeCookie.get() === 'true') {
       document.querySelector('.darkmode').classList.add('active');
   }
   ```

   Straight above it, after the closing `};` of `darkmodeCookie`, add:

   ```js
   // On the first visit there is no cookie yet, so follow the
   // operating system's light or dark setting. The page was drawn before the
   // cookie existed, so reload it to let PHP add the dark theme.
   if (darkmodeCookie.get() === null) {
       let prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
       darkmodeCookie.set(prefersDark ? 'true' : 'false');
       if (prefersDark) {
           location.reload();
       }
   }
   ```

   The new block has to come before the `active` check, so that the switch shows the right position after the cookie is set.

2. Follow along: read it line by line.
   - `darkmodeCookie.get()` returns `null` when there is no `darkmode` cookie. That only happens on the first visit, or after the cookie is deleted.
   - `prefersDark` is `true` or `false`, from the system setting.
   - The ternary turns it into the string `'true'` or `'false'`, the same values the switch stores.
   - A light page is already correct, so the script only reloads when the system is dark.

3. Follow along: test it with a dark system.
   - Switch your operating system to dark mode.
   - In the browser, open the developer tools, go to Application (Storage in Firefox), then Cookies, then `http://localhost:8000`. Delete the `darkmode` cookie.
   - Reload the page.

   *Check: the page flashes light for a moment, then reloads dark. The switch is on, and a `darkmode` cookie with the value `true` is back in the developer tools.*

4. Follow along: test that the switch still wins. Click the switch.

   *Check: the site goes light, and stays light as you move between pages, even though your system is still dark.*

5. Follow along: test it with a light system. Switch your operating system to light mode, delete the cookie again, and reload.

   *Check: the page stays light, with no reload, and the cookie now holds `false`.*

6. Your turn: in the browser's console, run:

   ```js
   window.matchMedia('(prefers-color-scheme: dark)').matches
   ```

   Change your system setting and run it again. *Check: the answer follows the system setting.* Then answer: why does the site not change colour by itself when you change the system setting, now that the cookie exists?

## Done when

- [ ] With no cookie and a dark system, the site starts dark after one reload.
- [ ] With no cookie and a light system, the site stays light and stores `false`.
- [ ] The switch still changes the mode, and the choice survives moving between pages.
- [ ] You can say why the page needs to reload once, and why the script stores `false`.

## Previous / Next

Previous: [Unit 3, 08 Self-study](../../../u3-operators-control-flow/checkpoints/08-self-study/README.md). Next: [02 Writing functions](../02-functions/README.md).
