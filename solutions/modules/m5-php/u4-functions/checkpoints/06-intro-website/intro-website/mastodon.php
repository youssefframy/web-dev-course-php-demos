<?php
$tag = 'php';
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
        case 'CAA':
            return "{$record['flags']} {$record['tag']} {$record['value']}";
        case 'CNAME':
        case 'NS':
        case 'PTR':
            return $record['target'];
        default:
            return 'Unsupported record type';
    }
}

function getMastodonPosts(string $tag): array
{
    $url = "https://mastodon.social/api/v1/timelines/tag/$tag?limit=12";
    $jsonData = file_get_contents($url);
    return json_decode($jsonData) ?? [];
}
?>
<!doctype html>
<html lang="en">
<?php include 'includes/head.php' ?>

<body <?= isset($_COOKIE['darkmode']) && $_COOKIE['darkmode'] === 'true' ? 'data-bs-theme="dark"' : '' ?>>
    <?php include 'includes/menu.php' ?>

    <div class="container">
        <h1><?= strtoupper($tag) ?> @ Mastodon</h1>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <!-- Posts go here -->
            <?php
            foreach (getMastodonPosts($tag) as $post) {
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
                <?php
                foreach ($mastodonDns as $record) {
                    echo '<tr>';
                    echo "<td>{$record['type']}</td>";
                    echo '<td>' . getDnsRecordValue($record) . '</td>';
                    echo "<td>{$record['ttl']}</td>";
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php include 'includes/footer.php' ?>
</body>

</html>
