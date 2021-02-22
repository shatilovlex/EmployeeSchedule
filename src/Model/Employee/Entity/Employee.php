<?php

declare(strict_types=1);

namespace App\Model\Employee\Entity;

use JsonSerializable;

class Employee implements JsonSerializable
{
    /**
     * @var int
     */
    private int $id;
    /**
     * @var WorkDaySchedule
     */
    private WorkDaySchedule $workDaySchedule;

    public function __construct(int $id, WorkDaySchedule $workDaySchedule)
    {
        $this->id = $id;
        $this->workDaySchedule = $workDaySchedule;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return WorkDaySchedule
     */
    public function getWorkDaySchedule(): WorkDaySchedule
    {
        return $this->workDaySchedule;
    }

    /**
     * @param WorkDaySchedule $workDaySchedule
     */
    public function setWorkDaySchedule(WorkDaySchedule $workDaySchedule): void
    {
        $this->workDaySchedule = $workDaySchedule;
    }

    public function jsonSerialize(): array
    {
        return [
           'id' => $this->id,
           'workDaySchedule' => $this->workDaySchedule
        ];
    }
}
