<?php

declare(strict_types=1);

namespace App\Model\Employee\useCase\EmployeeWeekendSchedule;

use App\Model\Employee\Entity\Schedule\Schedule;
use App\Model\Employee\Service\EmployeeService;
use App\Model\Employee\Service\ScheduleService;
use DateTimeImmutable;

class Handler
{

    /**
     * @var ScheduleService
     */
    private ScheduleService $scheduleService;
    /**
     * @var EmployeeService
     */
    private EmployeeService $employeeService;

    public function __construct(ScheduleService $scheduleService, EmployeeService $employeeService)
    {
        $this->scheduleService = $scheduleService;
        $this->employeeService = $employeeService;
    }

    public function handle(Command $command): Schedule
    {
        $employee = $this->employeeService->find($command->getEmployeeId());
        $startDate = DateTimeImmutable::createFromFormat('Y-m-d', $command->getStartDate());
        $endDate = DateTimeImmutable::createFromFormat('Y-m-d', $command->getEndDate());
        return $this->scheduleService->generateNotWorkSchedule($employee, $startDate, $endDate);
    }
}
