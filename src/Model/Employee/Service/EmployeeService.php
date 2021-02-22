<?php

declare(strict_types=1);

namespace App\Model\Employee\Service;

use App\Model\Employee\Entity\Employee;
use App\Model\Employee\Entity\TimeRange;
use App\Model\Employee\Entity\WorkDaySchedule;
use DomainException;
use Generator;

use function array_map;

class EmployeeService
{
    /**
     * @param int $id
     * @throws DomainException
     * @return Employee
     */
    public function find(int $id): Employee
    {
        foreach ($this->getEmployeesData() as $item) {
            if ($item['id'] == $id) {
                $timeRanges = array_map(function ($item) {
                    return new TimeRange($item['start'], $item['end']);
                }, $item['workDaySchedule']);
                return new Employee($item['id'], new WorkDaySchedule($timeRanges));
            }
        }
        throw new DomainException("Error: Employee not found");
    }

    public function getEmployeesData(): Generator
    {
        yield 'Employee who works from the late morning' =>  [
            'id' => 1,
            'workDaySchedule' => [
                [
                    'start' => '10:00',
                    'end' => '13:00',
                ],
                [
                    'start' => '14:00',
                    'end' => '19:00',
                ],
            ],
        ];

        yield 'Employee who works from the early morning' => [
            'id' => 2,
            'workDaySchedule' => [
                [
                    'start' => '09:00',
                    'end' => '12:00',
                ],
                [
                    'start' => '13:00',
                    'end' => '18:00',
                ],
            ],
        ];
    }
}
