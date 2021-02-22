<?php

namespace App\Tests\Model\Employee\Entity;

use App\Model\Employee\Entity\Employee;
use App\Model\Employee\Entity\WorkDaySchedule;
use PHPUnit\Framework\TestCase;

class EmployeeTest extends TestCase
{
    public function testSuccess()
    {
        $employee = new Employee($id = 1, $workDaySchedule = new WorkDaySchedule([]));
        $this->assertEquals($id, $employee->getId());
        $this->assertEquals($workDaySchedule, $employee->getWorkDaySchedule());
    }
}
