<?php

declare(strict_types=1);

namespace App\Model\Employee\Entity;

use JsonSerializable;

class WorkDaySchedule implements JsonSerializable
{
    private array $timeRanges;

    public function __construct(array $timeRanges)
    {
        $this->timeRanges = [];
        foreach ($timeRanges as $timeRange) {
            $this->addTimeRanges($timeRange);
        }
    }

    /**
     * @param TimeRange $timeRange
     * @return WorkDaySchedule
     */
    public function addTimeRanges(TimeRange $timeRange): self
    {
        $this->timeRanges[] = $timeRange;
        return $this;
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
        return $this->timeRanges;
    }
}
