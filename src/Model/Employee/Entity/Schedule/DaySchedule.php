<?php

declare(strict_types=1);

namespace App\Model\Employee\Entity\Schedule;

use App\Model\Employee\Entity\TimeRange;
use JsonSerializable;

class DaySchedule implements JsonSerializable
{
    private string $day;
    private array $timeRanges;

    public function __construct(string $day)
    {
        $this->day = $day;
        $this->timeRanges = [];
    }

    /**
     * @param TimeRange $timeRange
     * @return DaySchedule
     */
    public function addTimeRanges(TimeRange $timeRange): self
    {
        $this->timeRanges[] = $timeRange;
        return $this;
    }

    /**
     * @return string
     */
    public function getDay(): string
    {
        return $this->day;
    }

    /**
     * @return array
     */
    public function getTimeRanges(): array
    {
        return $this->timeRanges;
    }

    public function jsonSerialize(): array
    {
        return [
            'day' => $this->day,
            'timeRanges' => $this->timeRanges,
        ];
    }
}
