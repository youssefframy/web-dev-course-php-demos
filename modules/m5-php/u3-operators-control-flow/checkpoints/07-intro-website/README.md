# Checkpoint 07: Intro Website: menu, summary and dark mode

## Goal

Use loops and conditions on the Intro Website: build the menu from an array, show the PHP Summary as badges and a table instead of dumps, and add a dark mode switch.

## Concepts

- An array of arrays can describe a list of things. Each menu item below is an array with a `name` and a `url`, and `$menuItems` holds all of them.
- `$menuItem['url']` reads one value from the inner array. Inside a heredoc or a double-quoted string, wrap it in braces: `{$menuItem['url']}`.
- A loop turns the array into HTML. Adding a page to the menu then means adding one line to the array, not copying a block of HTML.
- A cookie is a small named value that the browser stores and sends back with every request. PHP reads the ones it receives from the superglobal `$_COOKIE`.
- The server cannot see whether the browser is in light or dark mode. So a small JavaScript file shows a switch and stores the choice in a cookie called `darkmode`. PHP reads the cookie and, if it is `'true'`, adds `data-bs-theme="dark"` to `<body>`. That attribute switches Bootstrap to its dark colours.
- `isset($_COOKIE['darkmode']) && $_COOKIE['darkmode'] === 'true'` checks that the cookie exists before comparing it. `&&` stops as soon as one side is false, so a visitor with no cookie never reaches the comparison.

## Before you start

Unit 3 has its own copy of the Intro Website in [`intro-website/`](../../intro-website), as it was at the end of Unit 2. To carry on with your own copy instead, copy it over the provided one:

```sh
cp -R modules/m5-php/u2-language-basics/intro-website/. modules/m5-php/u3-operators-control-flow/intro-website/
```

You need the PHP Summary page from Unit 2. Start the server:

```sh
cd modules/m5-php/u3-operators-control-flow/intro-website
php -S localhost:8000
```

## Steps

1. Follow along: at the very top of `includes/menu.php`, above `<div class="container">`, describe the menu as an array:

   ```php
   <?php
   $menuItems = [
       ['name' => 'Home', 'url' => '/index.php'],
       ['name' => 'About PHP', 'url' => '/aboutphp.php'],
       ['name' => 'About MariaDB', 'url' => '/aboutmariadb.php'],
       ['name' => 'Configuration', 'url' => '/config.php'],
       ['name' => 'PHP Summary', 'url' => '/phpsummary.php'],
   ];
   ?>
   ```

   The slides call the fourth page PHP Info, at `/phpinfo.php`. This site has called it Configuration, at `/config.php`, since Unit 1, so keep that.

2. Follow along: replace everything between `<ul class="navbar-nav me-auto mb-2 mb-lg-0">` and `</ul>`, so that the list becomes:

   ```php
   <ul class="navbar-nav me-auto mb-2 mb-lg-0">
       <?php
       $scriptName = $_SERVER['SCRIPT_NAME'];
       foreach ($menuItems as $menuItem) {
           $isActive = $menuItem['url'] === $scriptName ? 'active' : '';
           echo <<<ITEM
               <li class="nav-item">
                   <a class="nav-link $isActive"
                       aria-current="page" href="{$menuItem['url']}">{$menuItem['name']}</a>
               </li>
           ITEM;
       }
       ?>
   </ul>
   ```

   Visit <http://localhost:8000> and click through every page. *Check: the menu looks exactly as before, and the current page is highlighted. The five hand-written `<li>` blocks are gone.*

3. Follow along: in `phpsummary.php`, replace everything inside `<div class="container">` with:

   ```php
   <h1>PHP Summary</h1>
   <?php
   printf('<p>You are running PHP %s.</p>', phpversion());

   $extensions = get_loaded_extensions();
   printf('<p>%d extensions are loaded.</p>', count($extensions));
   echo '<p>pdo_mysql loaded: ' . (extension_loaded('pdo_mysql') ? 'yes' : 'no') . '</p>';

   echo '<h2>Your request</h2>';
   printf('<p>Method: %s</p>', $_SERVER['REQUEST_METHOD']);
   printf('<p>Address: %s</p>', $_SERVER['REQUEST_URI']);
   printf('<p>Browser: %s</p>', $_SERVER['HTTP_USER_AGENT']);
   printf('<p>Time: %s</p>', date('d/m/Y H:i', $_SERVER['REQUEST_TIME']));
   ?>
   <div class="card">
       <div class="card-header">
           Loaded Extensions
       </div>
       <div class="card-body">
           <?php
           foreach ($extensions as $extension) {
               echo "<span class='badge text-bg-info' style='margin: 2px;'>$extension</span>";
           }
           ?>
       </div>
   </div>
   <div class="card" style="margin-top: 10px;">
       <div class="card-header">
           Server Environment
       </div>
       <div class="card-body">
           <table class="table table-striped">
               <thead>
                   <tr>
                       <th>Key</th>
                       <th>Value</th>
                   </tr>
               </thead>
               <tbody>
                   <?php
                   foreach ($_SERVER as $key => $value) {
                       echo "<tr><td>$key</td><td>$value</td></tr>";
                   }
                   ?>
               </tbody>
           </table>
       </div>
   </div>
   ```

   The lines down to "Your request" are your work from Unit 2, unchanged. The `var_dump()` sections, and the extra credit sections if you did them, are replaced by the two cards. `var_dump()` is for you, while debugging. The cards are for visitors.

   *Check: each extension is a blue badge, and the server environment is a striped table with one row per key. Find `SCRIPT_NAME` in the table.*

4. Follow along: create the two dark mode files. Their content comes from the course gists:
   - `css/darkmode.css`: <https://gist.github.com/icemaltacode/8da96a49f223a3ef97f80b46c456f60e>
   - `js/darkmode.js`: <https://gist.github.com/icemaltacode/183873b46e4eee7c82f5b8798330382f>

   If you cannot reach GitHub, copy both files from [the reference solution](../../../../../solutions/modules/m5-php/u3-operators-control-flow/checkpoints/07-intro-website/intro-website).

5. Follow along: in `includes/head.php`, add the dark mode stylesheet under the Bootstrap Icons line, so that the three lines read:

   ```html
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
   <link rel="stylesheet" href="css/darkmode.css">
   <script src="/js/bootstrap.bundle.min.js"></script>
   ```

6. Follow along: in `includes/menu.php`, add a moon icon and the switch straight after `</ul>`, and load the script after the last `</div>`. The end of the file becomes:

   ```php
                       ITEM;
                   }
                   ?>
               </ul>
               <i class="bi bi-moon"></i>
               <button class="darkmode" title="darkmode switch" aria-hidden="true"></button>
           </div>
       </div>
   </nav>
   </div>
   <script type="text/javascript" src="js/darkmode.js"></script>
   ```

   Refresh. *Check: a moon and a switch on the right of the menu. Clicking the switch reloads the page and moves the dot, but the colours do not change yet. Only the cookie has been set.*

7. Follow along: in each of the five pages, replace `<body>` with:

   ```php
   <body <?= isset($_COOKIE['darkmode']) && $_COOKIE['darkmode'] === 'true' ? 'data-bs-theme="dark"' : '' ?>>
   ```

   *Check: the switch now turns the whole site dark and back. Dark mode stays on as you move between pages, and after you close and reopen the browser.*

8. Your turn: add an About Bootstrap page.
   - Create `aboutbootstrap.php` by copying `aboutphp.php`. Change the heading, write a short paragraph about Bootstrap, and change the file name inside `filemtime()`.
   - Make it appear in the menu, after About MariaDB, by changing only one file.

   *Check: the new page is highlighted in the menu when you are on it, and it follows dark mode.*

9. Format every file you changed.

## Done when

- [ ] The menu is built by a `foreach` from `$menuItems`, and highlights the current page.
- [ ] The PHP Summary page shows the extensions as badges and the server environment as a table, with no `var_dump()` left.
- [ ] The dark mode switch turns every page dark and light, and the choice survives moving between pages.
- [ ] The About Bootstrap page is in the menu, added with one line.
- [ ] You can say why PHP needs a cookie to know about dark mode.

## Previous / Next

Previous: [06 Loops](../06-loops/README.md). Next: [08 Self-study](../08-self-study/README.md).
