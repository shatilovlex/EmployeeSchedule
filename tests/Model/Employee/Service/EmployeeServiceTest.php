<?php

namespace App\Tests\Model\Employee\Service;

use App\Model\Employee\Service\EmployeeService;
use DomainException;
use PHPUnit\Framework\TestCase;

class EmployeeServiceTest extends TestCase
{

    public function testFindSuccess()
    {
        $employeeService = new EmployeeService();
        $employee = $employeeService->find($id = 1);
        $this->assertEquals($id, $employee->getId());
    }
    public function testFindFailed()
    {
        $employeeService = new EmployeeService();
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Error: Employee not found");
        $employeeService->find($id = 3);
    }
}
