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
        <?php $result = 42 === '42'; ?>
        <tr>
            <td><code>42 === '42'</code></td>
            <td>false</td>
            <td><?= var_export($result, true) ?></td>
        </tr>
        <?php $result = 42 != '42'; ?>
        <tr>
            <td><code>42 != '42'</code></td>
            <td>false</td>
            <td><?= var_export($result, true) ?></td>
        </tr>
        <?php $result = 42 !== '42'; ?>
        <tr>
            <td><code>42 !== '42'</code></td>
            <td>true</td>
            <td><?= var_export($result, true) ?></td>
        </tr>
        <?php $result = 1.0 == 1; ?>
        <tr>
            <td><code>1.0 == 1</code></td>
            <td>true</td>
            <td><?= var_export($result, true) ?></td>
        </tr>
        <?php $result = 1.0 === 1; ?>
        <tr>
            <td><code>1.0 === 1</code></td>
            <td>false</td>
            <td><?= var_export($result, true) ?></td>
        </tr>
        <?php $result = 'apple' < 'banana'; ?>
        <tr>
            <td><code>'apple' &lt; 'banana'</code></td>
            <td>true</td>
            <td><?= var_export($result, true) ?></td>
        </tr>
        <?php $result = 5 <=> 3; ?>
        <tr>
            <td><code>5 &lt;=&gt; 3</code></td>
            <td>1</td>
            <td><?= var_export($result, true) ?></td>
        </tr>
        <?php $result = 3 <=> 5; ?>
        <tr>
            <td><code>3 &lt;=&gt; 5</code></td>
            <td>-1</td>
            <td><?= var_export($result, true) ?></td>
        </tr>
        <?php $result = true && false; ?>
        <tr>
            <td><code>true && false</code></td>
            <td>false</td>
            <td><?= var_export($result, true) ?></td>
        </tr>
        <?php $result = true || false; ?>
        <tr>
            <td><code>true || false</code></td>
            <td>true</td>
            <td><?= var_export($result, true) ?></td>
        </tr>
        <?php $result = (true xor true); ?>
        <tr>
            <td><code>true xor true</code></td>
            <td>false</td>
            <td><?= var_export($result, true) ?></td>
        </tr>
        <?php $result = !false; ?>
        <tr>
            <td><code>!false</code></td>
            <td>true</td>
            <td><?= var_export($result, true) ?></td>
        </tr>
        <?php $result = ['a', 'b'] == [1 => 'b', 0 => 'a']; ?>
        <tr>
            <td><code>['a', 'b'] == [1 =&gt; 'b', 0 =&gt; 'a']</code></td>
            <td>true</td>
            <td><?= var_export($result, true) ?></td>
        </tr>
        <?php $result = ['a', 'b'] === [1 => 'b', 0 => 'a']; ?>
        <tr>
            <td><code>['a', 'b'] === [1 =&gt; 'b', 0 =&gt; 'a']</code></td>
            <td>false</td>
            <td><?= var_export($result, true) ?></td>
        </tr>
        <?php $result = null ?? 'empty'; ?>
        <tr>
            <td><code>null ?? 'empty'</code></td>
            <td>'empty'</td>
            <td><?= var_export($result, true) ?></td>
        </tr>
        <?php $result = 10 > 5 ? 'big' : 'small'; ?>
        <tr>
            <td><code>10 &gt; 5 ? 'big' : 'small'</code></td>
            <td>'big'</td>
            <td><?= var_export($result, true) ?></td>
        </tr>
    </table>
</body>

</html>
