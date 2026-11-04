# Notes: Unit 4 self-study

## Link to the original post

- Each post has both `url` and `uri`. `url` is the page people read, on the author's own server. `uri` is the identifier servers use between themselves, and often does not open a readable page. The reference answer uses `$post->url`.
- Mastodon is made of many servers, so the link often leads to another server, not mastodon.social. That is expected.
- The reference answer puts a small button at the end of the card body. A link on the date, or around the whole footer, is also fine, as long as it opens in a new tab with `target="_blank"`.
- Any `print_r()` or `var_dump()` used to find the property must be removed.

## Extra credit: caching the DNS records

- `session_start()` sends a cookie, `PHPSESSID`, so it must run before any output. In `mastodon.php` it goes at the very top of the PHP block, before `<!doctype html>`. If it comes after any HTML, PHP warns that headers have already been sent.
- The reference answer only calls `dns_get_record()` when `$_SESSION['mastodonDns']` is not set yet. Every later request copies the stored array into `$mastodonDns`, so the rest of the page does not change.
- `isset()` is the check to use. `??=` (`$_SESSION['mastodonDns'] ??= dns_get_record(...)`) does the same in one line and is also fine.
- The cache lasts for one visitor's session. It is not shared between visitors, and it never expires on its own while the session lives. Sharing a cache between all visitors, or refreshing it after a set time, needs a file, a database or a cache server. That is beyond what the step asks.
- The suggested test, storing the lookup time with the records, is optional. The reference answer does not keep it.
