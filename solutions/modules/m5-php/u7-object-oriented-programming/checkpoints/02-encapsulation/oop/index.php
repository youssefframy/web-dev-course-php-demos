<?php

class Employee
{
    private string $name;
    private string $surname;
    private string $jobTitle;
    private float $hourlyRate = 0;
    private bool $paidOvertime = false;

    public function getBasicDetailsString(): string
    {
        return "$this->name $this->surname, $this->jobTitle";
    }

    public function getGrossPay(float $hoursWorked): float
    {
        return $hoursWorked * $this->hourlyRate;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;
        return $this;
    }

    public function getJobTitle(): string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(string $jobTitle): self
    {
        $this->jobTitle = $jobTitle;
        return $this;
    }

    public function getHourlyRate(): float
    {
        return $this->hourlyRate;
    }

    /**
     * The type hint already refuses text. The rule here refuses zero and negative rates.
     */
    public function setHourlyRate(float $hourlyRate): self
    {
        if ($hourlyRate > 0) {
            $this->hourlyRate = $hourlyRate;
        }
        return $this;
    }

    public function isPaidOvertime(): bool
    {
        return $this->paidOvertime;
    }

    public function setPaidOvertime(bool $paidOvertime): self
    {
        $this->paidOvertime = $paidOvertime;
        return $this;
    }
}

// Creating objects
$emp1 = new Employee();
$emp1->setName('Alice');
$emp1->setSurname('Anderson');
$emp1->setJobTitle('Head of Technology');

$emp2 = new Employee();
$emp2->setName('Bob')->setSurname('Barker')->setJobTitle('Chief Marketing Officer');

$emp3 = new Employee();
$emp3->setName('Claire')->setSurname('Curmi')->setJobTitle('Junior Programmer');
$emp3->setHourlyRate(21.50);

// Your turn: a rate that is refused
$emp3->setHourlyRate(-5);
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
    <p>
        Name: <?= $emp3->getBasicDetailsString() ?><br>
        Wage for 160 hours: €<?= $emp3->getGrossPay(160) ?>
    </p>
</body>

</html>
