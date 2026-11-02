# Notes: Unit 3 self-study

## Footer links

- The reference answer stores each link as an array with `name`, `url` and `icon`, the same shape as `$menuItems` in `includes/menu.php`. Any key names are fine, as long as the icon class, address and label all come from the array.
- A `foreach` with a heredoc matches the menu. Echoing the `<li>` with concatenation, or closing PHP and using `<?= ?>` inside the loop, gives the same HTML and is also fine.
- The rocket and copyright on the left are not social links, so they stay as they are.
- Check: adding a fourth array entry, for example `['name' => 'Bootstrap', 'url' => 'https://getbootstrap.com', 'icon' => 'bi-bootstrap']`, must add a fourth icon without touching the HTML.

## Extra credit: system preference

- `window.matchMedia('(prefers-color-scheme: dark)').matches` is `true` when the operating system is set to dark mode.
- PHP draws the page before any JavaScript runs, so on the very first visit the page comes out light. The script sets the cookie and reloads once, and from then on PHP adds `data-bs-theme="dark"` itself.
- The reference answer only checks when there is no cookie yet. Once the visitor has a cookie, their own choice with the switch wins. To try it again, delete the `darkmode` cookie in the browser's developer tools (Application, Cookies).
- Setting the cookie to `'false'` for light-mode visitors is deliberate. Without it, the check would run on every page.
- An alternative is to listen for changes with `matchMedia(...).addEventListener('change', ...)`, so the site follows the system when it switches at sunset. That is beyond what the step asks.
