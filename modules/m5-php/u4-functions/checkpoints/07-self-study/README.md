# Checkpoint 07: Self-study

## Goal

Use what you have learnt in this unit, without step-by-step instructions, to link each Mastodon post to the original.

## Concepts

Nothing new for the main task. You need:

- the Mastodon page from checkpoint 06
- `print_r()` or `var_dump()`, from Unit 2, to see what the API sends back
- `target="_blank"`, to open a link in a new tab

For the extra credit, you need one new idea. A session keeps values for one visitor between requests, on the server. `session_start()` must run before the page sends any output. After that, the superglobal `$_SESSION` is an array that PHP keeps for that visitor, so a value you store in it on one request is still there on the next.

## Before you start

You need the Intro Website from checkpoint 06, with the Mastodon page. If you skipped it, copy the reference files over yours:

```sh
cp -R solutions/modules/m5-php/u4-functions/checkpoints/06-intro-website/intro-website/. modules/m5-php/u4-functions/intro-website/
```

## Steps

1. In `mastodon.php`, find out which property of `$post` holds the address of the original post. Print one post with `print_r()` or `var_dump()`, then remove the debugging line.
2. Add a link to every card that opens the original post on Mastodon, in a new tab.
3. Check that the link opens the right post for at least three cards, in light and dark mode.
4. Extra credit: cache the result of `dns_get_record()` in `$_SESSION`, so that the lookup runs only on a visitor's first visit to the page. Every later visit in the same session uses the stored records.

   To test it, show the time of the lookup somewhere on the page, and store it in the session with the records. Refresh a few times: the time should not change. Delete the `PHPSESSID` cookie in the developer tools and refresh: the time should change once.

## Done when

- [ ] Every post has a link that opens the original on Mastodon, in a new tab.
- [ ] No debugging output is left on the page.
- [ ] Extra credit: the DNS records are looked up once per session.
- [ ] Your work is committed and pushed (see [How to submit](../../../../../README.md#how-to-submit)).

## Previous / Next

Previous: [06 Intro Website: folders, DNS and Mastodon](../06-intro-website/README.md). Next: Unit 5, coming soon.
