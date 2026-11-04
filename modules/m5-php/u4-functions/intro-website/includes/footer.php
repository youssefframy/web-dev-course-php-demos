<?php
$socialLinks = [
    ['name' => 'PHP', 'url' => 'https://www.php.net', 'icon' => 'bi-filetype-php'],
    ['name' => 'MariaDB', 'url' => 'https://mariadb.org', 'icon' => 'bi-database'],
    ['name' => 'GitHub', 'url' => 'https://github.com', 'icon' => 'bi-github'],
];
?>

<div class="container">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
        <div class="col-md-6 d-flex align-items-center">
            <a href="/" class="me-2 text-body-secondary text-decoration-none lh-1" aria-label="Home">
                <i class="bi bi-rocket-takeoff fs-4"></i>
            </a>
            <span class="text-body-secondary">&copy; <?= date('Y') ?> Learning PHP, ICE Malta</span>
        </div>

        <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
            <?php
            foreach ($socialLinks as $socialLink) {
                echo <<<LINK
                    <li class="ms-3">
                        <a class="text-body-secondary" href="{$socialLink['url']}" aria-label="{$socialLink['name']}">
                            <i class="bi {$socialLink['icon']} fs-4"></i>
                        </a>
                    </li>
                LINK;
            }
            ?>
        </ul>
    </footer>
</div>
