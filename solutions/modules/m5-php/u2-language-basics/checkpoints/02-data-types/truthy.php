<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Truthy or falsy?</title>
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
    <h1>Truthy or falsy?</h1>
    <table>
        <tr>
            <th>Value</th>
            <th>My guess</th>
            <th>Type</th>
            <th>Numeric?</th>
            <th>Truthy or falsy?</th>
        </tr>
        <?php $value = 0; ?>
        <tr>
            <td><code>0</code></td>
            <td>falsy</td>
            <td><?= gettype($value) ?></td>
            <td><?= is_numeric($value) ? 'yes' : 'no' ?></td>
            <td><?= $value ? 'truthy' : 'falsy' ?></td>
        </tr>
        <?php $value = 0.0; ?>
        <tr>
            <td><code>0.0</code></td>
            <td>falsy</td>
            <td><?= gettype($value) ?></td>
            <td><?= is_numeric($value) ? 'yes' : 'no' ?></td>
            <td><?= $value ? 'truthy' : 'falsy' ?></td>
        </tr>
        <?php $value = '0'; ?>
        <tr>
            <td><code>'0'</code></td>
            <td>falsy</td>
            <td><?= gettype($value) ?></td>
            <td><?= is_numeric($value) ? 'yes' : 'no' ?></td>
            <td><?= $value ? 'truthy' : 'falsy' ?></td>
        </tr>
        <?php $value = ''; ?>
        <tr>
            <td><code>''</code></td>
            <td>falsy</td>
            <td><?= gettype($value) ?></td>
            <td><?= is_numeric($value) ? 'yes' : 'no' ?></td>
            <td><?= $value ? 'truthy' : 'falsy' ?></td>
        </tr>
        <?php $value = '0.0'; ?>
        <tr>
            <td><code>'0.0'</code></td>
            <td>truthy</td>
            <td><?= gettype($value) ?></td>
            <td><?= is_numeric($value) ? 'yes' : 'no' ?></td>
            <td><?= $value ? 'truthy' : 'falsy' ?></td>
        </tr>
        <?php $value = 'false'; ?>
        <tr>
            <td><code>'false'</code></td>
            <td>truthy</td>
            <td><?= gettype($value) ?></td>
            <td><?= is_numeric($value) ? 'yes' : 'no' ?></td>
            <td><?= $value ? 'truthy' : 'falsy' ?></td>
        </tr>
        <?php $value = ' '; ?>
        <tr>
            <td><code>' '</code></td>
            <td>truthy</td>
            <td><?= gettype($value) ?></td>
            <td><?= is_numeric($value) ? 'yes' : 'no' ?></td>
            <td><?= $value ? 'truthy' : 'falsy' ?></td>
        </tr>
        <?php $value = []; ?>
        <tr>
            <td><code>[]</code></td>
            <td>falsy</td>
            <td><?= gettype($value) ?></td>
            <td><?= is_numeric($value) ? 'yes' : 'no' ?></td>
            <td><?= $value ? 'truthy' : 'falsy' ?></td>
        </tr>
        <?php $value = null; ?>
        <tr>
            <td><code>null</code></td>
            <td>falsy</td>
            <td><?= gettype($value) ?></td>
            <td><?= is_numeric($value) ? 'yes' : 'no' ?></td>
            <td><?= $value ? 'truthy' : 'falsy' ?></td>
        </tr>
        <?php $value = '4.2'; ?>
        <tr>
            <td><code>'4.2'</code></td>
            <td>truthy</td>
            <td><?= gettype($value) ?></td>
            <td><?= is_numeric($value) ? 'yes' : 'no' ?></td>
            <td><?= $value ? 'truthy' : 'falsy' ?></td>
        </tr>
        <?php $value = '4.2kg'; ?>
        <tr>
            <td><code>'4.2kg'</code></td>
            <td>truthy</td>
            <td><?= gettype($value) ?></td>
            <td><?= is_numeric($value) ? 'yes' : 'no' ?></td>
            <td><?= $value ? 'truthy' : 'falsy' ?></td>
        </tr>
    </table>
</body>

</html>
