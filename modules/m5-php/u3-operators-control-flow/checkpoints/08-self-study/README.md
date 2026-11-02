# Checkpoint 08: Self-study

## Goal

Use what you have learnt in this unit, without step-by-step instructions, to build the footer's links from an array, the same way as the menu.

## Concepts

Nothing new in PHP. You need:

- an array of arrays, and a `foreach` with a heredoc, from checkpoint 07's menu
- the Bootstrap Icons class names, from Unit 2 checkpoint 06

For the extra credit, you need one piece of JavaScript. `window.matchMedia('(prefers-color-scheme: dark)').matches` is `true` when the visitor's operating system is set to dark mode.

## Before you start

You need the Intro Website from checkpoint 07, with the programmatic menu and dark mode. If you skipped it, copy the reference files over yours:

```sh
cp -R solutions/modules/m5-php/u3-operators-control-flow/checkpoints/07-intro-website/intro-website/. modules/m5-php/u3-operators-control-flow/intro-website/
```

## Steps

1. In `includes/footer.php`, store the three social links (PHP, MariaDB and GitHub) in an array. Each link needs its address, its label for `aria-label`, and its icon class.
2. Replace the three hand-written `<li>` blocks with a loop that builds them from the array. The rocket and the copyright line on the left stay as they are.
3. Check that every page's footer looks exactly as before, in light and dark mode.
4. Add a fourth link to the array, for example Bootstrap at <https://getbootstrap.com> with the icon `bi-bootstrap`. Check that a fourth icon appears without any change to the HTML.
5. Extra credit: change `js/darkmode.js` so that a visitor's first visit follows their system setting. If there is no `darkmode` cookie yet, set it from the system preference. PHP has already drawn the page by the time the script runs, so the page needs one reload to pick up the new cookie. Once the cookie exists, the switch decides as before.

   To test it, delete the `darkmode` cookie in your browser's developer tools (Application, then Cookies), switch your operating system to dark mode, and reload.

## Done when

- [ ] The footer's links come from an array and a loop, and look exactly as before.
- [ ] A fourth array entry adds a fourth icon.
- [ ] Extra credit: with no cookie, the site starts in the system's mode, and the switch still works afterwards.
- [ ] Your work is committed and pushed (see [How to submit](../../../../../README.md#how-to-submit)).

## Previous / Next

Previous: [07 Intro Website: menu, summary and dark mode](../07-intro-website/README.md). Next: Unit 4, coming soon.
