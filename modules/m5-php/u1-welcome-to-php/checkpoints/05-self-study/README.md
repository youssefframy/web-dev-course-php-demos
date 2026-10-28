# Checkpoint 05: Self-study

## Goal

Use what you have learnt in this unit, without step-by-step instructions, to add a page to the Intro Website.

## Concepts

Nothing new. You need:

- `include`, from checkpoint 02
- `phpinfo()`, from checkpoint 01
- the `active` ternary in `menu.php`, from checkpoint 04
- `$phpAge` on the home page, from checkpoint 03

## Before you start

You need the Intro Website from checkpoint 04. If you skipped it, copy the reference files over yours:

```sh
cp -R solutions/modules/m5-php/u1-welcome-to-php/checkpoints/04-active-menu/intro-website/. modules/m5-php/u1-welcome-to-php/intro-website/
```

## Steps

1. Create `config.php`. It has the same head, menu and footer as the other pages, and shows the output of `phpinfo()` inside the container.
2. Add a fourth menu link, Configuration, that opens `config.php`.
3. Make the Configuration link highlight when you are on that page, the same way the other three do.
4. Extra credit: on the home page, show PHP's age in years and also as a total number of days, for example "31 years (11463 days) of heritage". The object in `$phpAge` has a `days` value that holds the total number of days.

`phpinfo()` brings its own styles, so the menu looks slightly different on the Configuration page. That is fine for now.

## Done when

- [ ] The menu has four links, and Configuration opens a page showing the PHP information.
- [ ] The Configuration link is highlighted on that page, and only on that page.
- [ ] Extra credit: the home page shows PHP's age in years and in days.
- [ ] Your work is committed and pushed (see [How to submit](../../../../../README.md#how-to-submit)).

## Previous / Next

Previous: [04 Highlighting the menu](../04-active-menu/README.md). Next: [Unit 2](../../../u2-language-basics/README.md).
