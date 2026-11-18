<?php
namespace unit7;

require 'lib/com/icemalta/libpayroll/payroll.php';

use com\icemalta\libpayroll\{
    Employee as PayrollEmployee,
    Manager as PayrollManager,
    Contractor as PayrollContractor,
    Director as PayrollDirector,
    Worker as PayrollWorker,
    StaffMember
};

/**
 * An employee of the company.
 */
class Employee
{
    public const CURRENCY = '€';
    private const YEAR_HOURS_STANDARD = 1920;
    // Each bracket starts at "from". The tax is gross * rate / 100 - subtract.
    private const TAX_BRACKETS = [
        ['from' => 0, 'rate' => 0, 'subtract' => 0],
        ['from' => 9101, 'rate' => 15, 'subtract' => 1365],
        ['from' => 14501, 'rate' => 25, 'subtract' => 2815],
        ['from' => 19501, 'rate' => 25, 'subtract' => 2725],
        ['from' => 60001, 'rate' => 35, 'subtract' => 8725]
    ];

    private static array $employeeList = [];

    private string $name;
    private string $surname;
    private string $jobTitle;
    protected float $hourlyRate;
    private bool $paidOvertime = false;

    /**
     * @param string $name the employee's name
     * @param string $surname the employee's surname
     * @param string $jobTitle the employee's job title
     * @param float $hourlyRate the employee's hourly rate. Defaults to 0
     * @param bool $paidOvertime true if the employee is paid overtime. Defaults to false
     */
    public function __construct(string $name, string $surname, string $jobTitle, float $hourlyRate = 0, bool $paidOvertime = false)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->jobTitle = $jobTitle;
        $this->setHourlyRate($hourlyRate);
        $this->paidOvertime = $paidOvertime;
        self::$employeeList[] = $this;
    }

    /**
     * @return array the employees created so far
     */
    public static function getEmployees(): array
    {
        return self::$employeeList;
    }

    /**
     * @return int how many employees have been created
     */
    public static function getEmployeeCount(): int
    {
        return count(self::$employeeList);
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

    public function setHourlyRate(float $hourlyRate): self
    {
        if ($hourlyRate >= 0) {
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

    public function getBasicDetailsString(): string
    {
        return "$this->name $this->surname, $this->jobTitle";
    }

    /**
     * @return float extra pay on top of the hourly wage. Children override this.
     */
    public function getBonus(): float
    {
        return 0;
    }

    public function getGrossPay(float $hoursWorked): float
    {
        return $hoursWorked * $this->hourlyRate + $this->getBonus();
    }

    /**
     * @param float $hoursWorked the total hours worked by this employee this year
     * @return object an object containing calculated fields for the income statement
     */
    public function getIncomeStatement(float $hoursWorked): object
    {
        $standardHours = min($hoursWorked, self::YEAR_HOURS_STANDARD);
        $overtimeHours = max($hoursWorked - self::YEAR_HOURS_STANDARD, 0);

        $wageDetail = new \stdClass();
        $wageDetail->standardHours = $standardHours;
        $wageDetail->overtimeHours = $this->paidOvertime ? $overtimeHours : 0;
        $wageDetail->standardGross = $standardHours * $this->hourlyRate + $this->getBonus();
        $wageDetail->overtimeGross = $this->paidOvertime ? $overtimeHours * $this->hourlyRate * 1.5 : 0;
        $wageDetail->totalGross = $wageDetail->standardGross + $wageDetail->overtimeGross;
        $wageDetail->totalTax = $this->getTaxAmount($wageDetail->totalGross);
        $wageDetail->totalNet = $wageDetail->totalGross - $wageDetail->totalTax;
        return $wageDetail;
    }

    /**
     * @param float $grossWage amount to calculate tax for
     * @return float the tax to be paid
     */
    private function getTaxAmount(float $grossWage): float
    {
        // Look for the highest bracket that starts at or below the wage
        foreach (array_reverse(self::TAX_BRACKETS) as $bracket) {
            if ($grossWage >= $bracket['from']) {
                return $grossWage * $bracket['rate'] / 100 - $bracket['subtract'];
            }
        }
        return 0; // negative wage: no tax
    }
}

class TeamLead extends Employee
{
    protected float $bonusEntitlement = 0;

    public function __construct(string $name, string $surname, string $jobTitle, float $hourlyRate = 0, bool $paidOvertime = false, float $bonusEntitlement = 0)
    {
        parent::__construct($name, $surname, $jobTitle, $hourlyRate, $paidOvertime);
        $this->bonusEntitlement = $bonusEntitlement;
    }

    public function getBonusEntitlement(): float
    {
        return $this->bonusEntitlement;
    }

    public function setBonusEntitlement(float $bonusEntitlement): self
    {
        $this->bonusEntitlement = $bonusEntitlement;
        return $this;
    }

    /**
     * Overrides Employee::getBonus(), so every pay calculation includes the bonus.
     */
    public function getBonus(): float
    {
        return $this->bonusEntitlement;
    }
}

// Creating objects
$emp1 = new Employee('Alice', 'Anderson', 'Head of Technology');
$emp2 = new Employee('Bob', 'Barker', 'Chief Marketing Officer');
$emp3 = new Employee('Claire', 'Curmi', 'Junior Programmer', 21.50);
$emp4 = new TeamLead('Dave', 'Doddleston', 'Lead Programmer', 30, true, 4000);
$emp5 = new Employee('Eve', 'Evans', 'Designer', 19);

/**
 * Describes any kind of worker. Works because every Worker has these methods.
 */
function describeHuman(PayrollWorker $human): string
{
    $result = sprintf(
        '<p>%s %s (%s)</p>',
        htmlspecialchars($human->getName()),
        htmlspecialchars($human->getSurname()),
        $human->getRoleDescription()
    );
    if ($human instanceof StaffMember) {
        $result .= "<p>Health: {$human->getHealthBenefitsString()}</p>";
        $result .= "<p>Perks: {$human->getPerksString()}</p>";
    }
    $ps = $human->getPayslipDetails();
    $result .= "<p>Payslip ({$ps['currency']}): {$ps['content']}</p>";
    $result .= '<p>' . htmlspecialchars($human->greet()) . '</p>';
    return $result;
}

// Logging: a folder next to this file
const LOG_DIR = __DIR__ . '/logs';
if (!is_dir(LOG_DIR)) {
    mkdir(LOG_DIR, 0755, true);
}

// Global handlers catch anything we did not handle ourselves
set_error_handler(function (int $severity, string $message, string $file, int $line): bool {
    error_log(sprintf("Error in %s on line %d. %d: %s\n", $file, $line, $severity, $message), 3, LOG_DIR . '/error.log');
    return false; // let PHP's own handling carry on as well
});
set_exception_handler(function (\Throwable $exception) {
    error_log($exception->getMessage() . "\n" . $exception->getTraceAsString() . "\n", 3, LOG_DIR . '/exception.log');
    if (!headers_sent()) {
        http_response_code(500);
    }
    echo 'Something went wrong. Please try again later.';
});

function simpleDivision(float $num1, float $num2): float
{
    return $num1 / $num2;
}

$divResult = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = filter_input(INPUT_POST, 'action');
    switch ($action) {
        case 'divide':
            $num1 = filter_input(INPUT_POST, 'num1');
            $num2 = filter_input(INPUT_POST, 'num2');
            try {
                $divResult = (string) simpleDivision($num1, $num2);
            } catch (\DivisionByZeroError $e) {
                $divResult = 'You cannot divide by zero. Please ensure Num 2 is not 0.';
            } catch (\TypeError $e) {
                $divResult = 'Please ensure both fields are filled in and numeric.';
            } catch (\Exception $e) {
                $divResult = $e->getMessage();
                error_log($e->getMessage() . "\n" . $e->getTraceAsString() . "\n", 3, LOG_DIR . '/exception.log');
            } catch (\Error $e) {
                $divResult = $e->getMessage();
                error_log($e->getMessage() . "\n" . $e->getTraceAsString() . "\n", 3, LOG_DIR . '/error.log');
            }
            break;
    }
}

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
        Wage for 160 hours: <?= Employee::CURRENCY . number_format($emp3->getGrossPay(160), 2) ?>
    </p>

    <p>
        Name: <?= $emp4->getBasicDetailsString() ?><br>
        Wage for 1920 hours: <?= Employee::CURRENCY . number_format($emp4->getGrossPay(1920), 2) ?>
    </p>

    <h3>Income Statement for <?= $emp4->getName() ?> <?= $emp4->getSurname() ?> (<?= $emp4->getJobTitle() ?>)</h3>
    <?php
    $payslip = $emp4->getIncomeStatement(2000);
    $c = Employee::CURRENCY;
    $f = fn(float $n): string => $c . number_format($n, 2);
    ?>
    <table border="1">
        <thead><tr><th>Item</th><th>Value</th></tr></thead>
        <tbody>
            <tr><td>Hours Worked</td><td><?= $payslip->standardHours ?></td></tr>
            <tr><td>Overtime Worked</td><td><?= $payslip->overtimeHours ?></td></tr>
            <tr><td>Standard Gross</td><td><?= $f($payslip->standardGross) ?></td></tr>
            <tr><td>Overtime Gross</td><td><?= $f($payslip->overtimeGross) ?></td></tr>
            <tr><td>Gross Wage</td><td><?= $f($payslip->totalGross) ?></td></tr>
            <tr><td>Tax Due</td><td><?= $f($payslip->totalTax) ?></td></tr>
            <tr><td>Net Wage</td><td><?= $f($payslip->totalNet) ?></td></tr>
        </tbody>
    </table>

    <h3>List of Employees</h3>
    <p>Employees: <?= Employee::getEmployeeCount() ?></p>
    <table border="1">
        <thead><tr><th>Name</th><th>Surname</th><th>Job Title</th><th>Type</th></tr></thead>
        <tbody>
            <?php foreach (Employee::getEmployees() as $employee) { ?>
                <tr>
                    <td><?= $employee->getName() ?></td>
                    <td><?= $employee->getSurname() ?></td>
                    <td><?= $employee->getJobTitle() ?></td>
                    <td><?= $employee instanceof TeamLead ? 'Team Lead' : 'Employee' ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <h3>Using the Payroll Library</h3>
    <?php
    $humans = [
        new PayrollEmployee('Joe', 'Doe'),
        new PayrollManager('Bob', 'Brown'),
        new PayrollDirector('Dana', 'Dimech'),
        new PayrollContractor('Judy', 'Judge')
    ];
    foreach ($humans as $human) {
        echo describeHuman($human);
    }
    ?>

    <h3>Simple Division</h3>
    <?php if ($divResult !== null) { ?>
        <p><strong><?= htmlspecialchars($divResult) ?></strong></p>
    <?php } ?>
    <form name="divideForm" method="POST">
        <input type="hidden" name="action" value="divide">
        <label for="num1">Num 1</label>
        <input type="text" name="num1" id="num1">
        <label for="num2">Num 2</label>
        <input type="text" name="num2" id="num2">
        <input type="submit" value="Divide">
    </form>

    <h3>Reflection</h3>
    <pre><?php
    $lead = $emp4;
    echo 'class_exists(TeamLead::class): ';
    var_dump(class_exists(TeamLead::class));
    echo 'get_class($lead): ', get_class($lead), "\n";
    echo 'get_parent_class($lead): ', get_parent_class($lead), "\n";
    echo 'method_exists($lead, \'getBonus\'): ';
    var_dump(method_exists($lead, 'getBonus'));
    echo "get_class_methods(TeamLead::class) from outside the class (public only):\n";
    print_r(get_class_methods(TeamLead::class));
    echo "get_object_vars(\$lead) from outside the class:\n";
    print_r(get_object_vars($lead)); // empty: every property is private or protected

    $rc = new \ReflectionClass(TeamLead::class);
    echo 'Short name: ', $rc->getShortName(), "\n";
    echo 'Parent: ', $rc->getParentClass()->getName(), "\n";
    echo "Properties (all visibilities):\n";
    foreach ($rc->getProperties() as $property) {
        echo ' - ', $property->getName(), $property->isPrivate() ? ' (private)' : '', $property->isProtected() ? ' (protected)' : '', "\n";
    }
    ?></pre>

</body>

</html>
