# Checkpoint 06: Intro Website: folders, DNS and Mastodon

## Goal

Use functions on the Intro Website: list the site's own files with a recursive function, and build a new page that shows DNS records and recent posts from Mastodon.

## Concepts

- A built-in function comes with PHP, such as `scandir()` or `dns_get_record()`. A user-defined function is one you write, such as `displayDirStruct()`. You call both the same way.
- `scandir($dir)` returns the names of everything in a folder, including `.` (the folder itself) and `..` (its parent). `array_diff()` returns the values of the first array that are not in the second, so it removes the names you do not want listed.
- `is_dir()` is true for a folder. A folder can contain more folders, to any depth, which is the shape recursion handles well.
- DNS is the internet's address book. It turns a name such as `mastodon.social` into addresses and other facts, stored as records of different types: `A` and `AAAA` for IPv4 and IPv6 addresses, `MX` for mail servers, `NS` for name servers, `TXT` for free text, and more. `dns_get_record()` looks them up and returns an array with one inner array per record. Each type keeps its value under a different key, so a function with a `switch` picks the right one.
- Mastodon is a social network made of many independent servers. Each server has a public API: addresses that return data as JSON instead of a web page. `https://mastodon.social/api/v1/timelines/tag/php` returns recent public posts tagged `#php`.
- `file_get_contents()` can read an address on the internet as well as a file. `json_decode()` turns the JSON text into PHP objects. You read an object's properties with `->`, for example `$post->content`. Objects are covered properly in Unit 7.
- `$post->content` is already HTML, written by Mastodon, so it is echoed as it is.

## Before you start

You need the Intro Website from checkpoint 01. If you skipped it, copy the reference files over yours:

```sh
cp -R solutions/modules/m5-php/u4-functions/checkpoints/01-system-darkmode/intro-website/. modules/m5-php/u4-functions/intro-website/
```

This checkpoint needs an internet connection. Start the server:

```sh
cd modules/m5-php/u4-functions/intro-website
php -S localhost:8000
```

## Steps

1. Follow along: in `includes/menu.php`, add one line at the end of `$menuItems`:

   ```php
   ['name' => 'Mastodon Posts', 'url' => '/mastodon.php'],
   ```

   *Check: `Mastodon Posts` appears at the end of the menu. Clicking it gives a 404 for now.*

2. Follow along: in `phpsummary.php`, add the listing function above `<!doctype html>`:

   ```php
   <?php
   function displayDirStruct(string $dir): void
   {
       $dirContents = array_diff(scandir($dir), ['..', '.', '.git', '.DS_Store']);
       echo '<ul>';
       foreach ($dirContents as $file) {
           if (is_dir($dir . '/' . $file)) {
               echo "<li>{$file}:</li>";
               displayDirStruct($dir . '/' . $file);
           } else {
               echo "<li>$file</li>";
           }
       }
       echo '</ul>';
   }
   ?>
   ```

   Then add a third card after the Server Environment card, just before the `</div>` that closes the container:

   ```php
   <div class="card" style="margin-top: 10px;">
       <div class="card-header">
           Local Root
       </div>
       <div class="card-body">
           <?php displayDirStruct('.'); ?>
       </div>
   </div>
   ```

   Visit <http://localhost:8000/phpsummary.php>. *Check: a Local Root card listing every page, with `css:`, `includes:` and `js:` each followed by an indented list of their files.* Create an empty folder `test` inside `includes`, and refresh. *Check: `test:` appears inside `includes:` with no change to the function.* Delete the `test` folder.

3. Follow along: create `mastodon.php`, starting from the same template as the other pages:

   ```php
   <!doctype html>
   <html lang="en">
   <?php include 'includes/head.php' ?>

   <body <?= isset($_COOKIE['darkmode']) && $_COOKIE['darkmode'] === 'true' ? 'data-bs-theme="dark"' : '' ?>>
       <?php include 'includes/menu.php' ?>

       <div class="container">
           <h1>PHP @ Mastodon</h1>
           <div class="row row-cols-1 row-cols-md-3 g-4">
               <!-- Posts go here -->
           </div>

           <h2 class="mt-4">DNS records</h2>
           <p>
               Data retrieved from <a href="https://mastodon.social" target="_blank">mastodon.social</a>
           </p>
           <table class="table">
               <thead>
                   <tr>
                       <th>Record Type</th>
                       <th>Value</th>
                       <th>TTL</th>
                   </tr>
               </thead>
               <tbody>
                   <!-- DNS Info goes here -->
               </tbody>
           </table>
       </div>

       <?php include 'includes/footer.php' ?>
   </body>

   </html>
   ```

   On the slides, the paragraph and the table sit inside the posts' grid. Here they come after it, so the table gets the full width.

   *Check: the page opens from the menu, its menu item is highlighted, and it follows dark mode.*

4. Follow along: at the very top of `mastodon.php`, above `<!doctype html>`, look up the DNS records and add a function that picks each record's value:

   ```php
   <?php
   $mastodonDns = dns_get_record('mastodon.social', DNS_ALL);

   function getDnsRecordValue(array $record): int|string
   {
       switch ($record['type']) {
           case 'A':
               return $record['ip'];
           case 'MX':
               return "{$record['pri']} {$record['target']}";
           case 'TXT':
               return $record['txt'];
           case 'AAAA':
               return $record['ipv6'];
           case 'SOA':
               return "{$record['mname']} {$record['rname']}";
           case 'SRV':
               return "{$record['pri']} {$record['weight']} {$record['target']}";
           case 'CNAME':
           case 'NS':
           case 'PTR':
               return $record['target'];
           default:
               return 'Unsupported record type';
       }
   }
   ?>
   ```

   `CNAME`, `NS` and `PTR` share one `return`, using fall-through from Unit 3. Each `return` also ends the function, so no `break` is needed.

   Under `<!-- DNS Info goes here -->`, add:

   ```php
   <?php
   foreach ($mastodonDns as $record) {
       echo '<tr>';
       echo "<td>{$record['type']}</td>";
       echo '<td>' . getDnsRecordValue($record) . '</td>';
       echo "<td>{$record['ttl']}</td>";
       echo '</tr>';
   }
   ?>
   ```

   *Check: a table of records, with `A`, `AAAA`, `NS`, `SOA`, `MX` and `TXT` rows. Some rows may say `Unsupported record type`.* The exact records change over time.

5. Follow along: in the PHP block at the top, under `getDnsRecordValue()`, add a function that fetches the posts:

   ```php
   function getMastodonPosts(string $tag): array
   {
       $url = "https://mastodon.social/api/v1/timelines/tag/$tag?limit=12";
       $jsonData = file_get_contents($url);
       return json_decode($jsonData) ?? [];
   }
   ```

   The slides return whatever `json_decode()` gives. Here `?? []` returns an empty array if the request failed, so the page still loads without posts.

   Under `<!-- Posts go here -->`, add one card per post:

   ```php
   <?php
   foreach (getMastodonPosts('php') as $post) {
   ?>
       <div class="col">
           <div class="card h-100">
               <?php
               if (isset($post->media_attachments[0])) {
                   echo "<img src='{$post->media_attachments[0]->preview_url}' class='card-img-top' alt='{$post->media_attachments[0]->description}'>";
               }
               ?>
               <div class="card-body">
                   <?= $post->content ?>
               </div>
               <div class="card-footer">
                   <?= "<img src='{$post->account->avatar_static}' alt='{$post->account->display_name}' style='max-width: 30px; border-radius: 50%;'>" ?>
                   <?= $post->account->display_name ?>
               </div>
           </div>
       </div>
   <?php } ?>
   ```

   *Check: twelve cards, three to a row, each with the post's text and its author's picture and name. Posts with an image show it at the top. Refresh later and the posts change.*

6. Your turn:
   - Find a row that says `Unsupported record type`, if you have one. It is probably `CAA`, which says which organisations may issue security certificates for the site. Add `print_r($record);` inside the loop for a moment to see its keys, then add a `case 'CAA':` to `getDnsRecordValue()` that returns its `flags`, `tag` and `value`, separated by spaces. Remove the `print_r()`.
   - Store the tag in a variable, `$tag = 'php';`, and use it both in the `<h1>` and in the call to `getMastodonPosts()`. Try `laravel`, then set it back to `php`.

   *Check: no `Unsupported record type` rows, and the heading always matches the posts.*

7. Format every file you changed.

## Done when

- [ ] The PHP Summary page lists the site's files and folders, at every depth.
- [ ] Mastodon Posts is in the menu, added with one line.
- [ ] The Mastodon page shows twelve posts and a table of DNS records, with no warnings.
- [ ] You can say which functions on the Mastodon page are built in and which you wrote.

## Previous / Next

Previous: [05 Types, static variables and recursion](../05-types-static-recursion/README.md). Next: [07 Self-study](../07-self-study/README.md).
