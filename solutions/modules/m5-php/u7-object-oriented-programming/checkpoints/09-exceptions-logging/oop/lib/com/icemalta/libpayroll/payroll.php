<?php

namespace com\icemalta\libpayroll;

interface StaffMember
{
    public function getPerksString(): string;
    public function getHealthBenefitsString(): string;
}

trait Greeting
{
    public function greet(): string
    {
        return 'Hello, ' . $this->getName();
    }
}

trait PayslipMetaData
{
    // Constants in traits need PHP 8.2 or later
    public const CURRENCY = '€';

    public function getPayslipDetails(): array
    {
        return [
            'currency' => self::CURRENCY,
            'content' => $this->getPaySlip()
        ];
    }
}

abstract class Worker
{
    use PayslipMetaData, Greeting;

    private string $name;
    private string $surname;

    public function __construct(string $name, string $surname)
    {
        $this->name = $name;
        $this->surname = $surname;
    }

    abstract public function getRoleDescription(): string;

    abstract public function getPaySlip(): string;

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
}

class Manager extends Worker implements StaffMember
{
    public function getPaySlip(): string
    {
        return 'This is the payslip for the manager.';
    }

    public function getRoleDescription(): string
    {
        return 'This is a manager';
    }

    public function getPerksString(): string
    {
        return 'Retirement contribution, 24/7 Gym, Meals, Business-Class Travel';
    }

    public function getHealthBenefitsString(): string
    {
        return 'Full private hospital, dental, cosmetic';
    }
}

class Employee extends Worker implements StaffMember
{
    public function getPaySlip(): string
    {
        return 'This is the payslip for the Employee.';
    }

    public function getRoleDescription(): string
    {
        return 'This is an Employee';
    }

    public function getPerksString(): string
    {
        return 'Retirement contribution, 24/7 Gym, Meals';
    }

    public function getHealthBenefitsString(): string
    {
        return 'Full private hospital, dental';
    }
}

class Director extends Worker implements StaffMember
{
    public function getPaySlip(): string
    {
        return 'This is the payslip for the director.';
    }

    public function getRoleDescription(): string
    {
        return 'This is a Director';
    }

    public function getPerksString(): string
    {
        return 'Retirement contribution, 24/7 Gym, Meals, Business-Class Travel, Company car';
    }

    public function getHealthBenefitsString(): string
    {
        return 'Full private hospital, dental, cosmetic';
    }
}

class Contractor extends Worker
{
    public function getPaySlip(): string
    {
        return 'This is the payslip for the contractor.';
    }

    public function getRoleDescription(): string
    {
        return 'This is a Contractor';
    }
}
