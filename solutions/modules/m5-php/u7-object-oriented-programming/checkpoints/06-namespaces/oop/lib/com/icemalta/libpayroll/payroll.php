<?php

namespace com\icemalta\libpayroll;

class Manager
{
    public function getPaySlip(): string
    {
        return 'This is the payslip for the manager.';
    }
}

class Employee
{
    public function getPaySlip(): string
    {
        return 'This is the payslip for the Employee.';
    }
}

class Contractor
{
    public function getPaySlip(): string
    {
        return 'This is the payslip for the contractor.';
    }
}
