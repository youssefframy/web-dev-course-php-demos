<?php

class Employee
{
    public string $name;
    public string $surname;
    public string $jobTitle;
    public float $hourlyRate;
    public bool $paidOvertime = false;

    public function getBasicDetailsString(): string
    {
        return "$this->name $this->surname, $this->jobTitle";
    }
}

// Creating objects
$emp1 = new Employee();
$emp1->name = 'Alice';
$emp1->surname = 'Anderson';
$emp1->jobTitle = 'Head of Technology';

$emp2 = new Employee();
$emp2->name = 'Bob';
$emp2->surname = 'Barker';
$emp2->jobTitle = 'Chief Marketing Officer';

// Your turn: a third employee, with a rate
$emp3 = new Employee();
$emp3->name = 'Claire';
$emp3->surname = 'Curmi';
$emp3->jobTitle = 'Junior Programmer';
$emp3->hourlyRate = 21.50;
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>OOP in PHP</title>
</head>

<body>
    <h1>OOP</h1>
    <p><?= $emp1->getBasicDetailsString() ?></p>
    <p><?= $emp2->getBasicDetailsString() ?></p>
    <p><?= $emp3->getBasicDetailsString() ?>, €<?= $emp3->hourlyRate ?>/hour</p>
</body>

</html>
