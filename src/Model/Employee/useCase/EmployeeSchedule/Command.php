<?php

declare(strict_types=1);

namespace App\Model\Employee\useCase\EmployeeSchedule;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private string $startDate;
    /**
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private string $endDate;
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     */
    private int $employeeId;

    /**
     * Command constructor.
     * @param string $startDate
     * @param string $endDate
     * @param int $employeeId
     */
    public function __construct(string $startDate, string $endDate, int $employeeId)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->employeeId = $employeeId;
    }

    /**
     * @return string
     */
    public function getStartDate(): string
    {
        return $this->startDate;
    }

    /**
     * @return string
     */
    public function getEndDate(): string
    {
        return $this->endDate;
    }

    /**
     * @return int
     */
    public function getEmployeeId(): int
    {
        return $this->employeeId;
    }
}
