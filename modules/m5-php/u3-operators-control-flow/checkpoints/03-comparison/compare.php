<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Guess the result</title>
    <style>
        table {
            border-collapse: collapse;
        }

        th,
        td {
            padding: 0.25rem 0.75rem;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <h1>Guess the result</h1>
    <table>
        <tr>
            <th>Expression</th>
            <th>My guess</th>
            <th>PHP says</th>
        </tr>
        <?php $result = 42 == '42'; ?>
        <tr>
            <td><code>42 == '42'</code></td>
            <td>true</td>
            <td><?= var_export($result, true) ?></td>
        </tr>
    </table>
</body>

</html>
