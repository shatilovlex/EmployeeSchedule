<?php

declare(strict_types=1);

namespace App\Model\Employee\Entity\Schedule;

use JsonSerializable;

class Schedule implements JsonSerializable
{
    /**
     * @var DaySchedule[]
     */
    private array $daysSchedule = [];

    public function addDaySchedule(DaySchedule $daySchedule)
    {
        $this->daysSchedule[] = $daySchedule;
    }

    /**
     * @return DaySchedule[]
     */
    public function getDaysSchedule(): array
    {
        return $this->daysSchedule;
    }

    public function jsonSerialize(): array
    {
        return ['schedule' => $this->daysSchedule];
    }
}
