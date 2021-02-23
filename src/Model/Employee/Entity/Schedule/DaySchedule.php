<?php

declare(strict_types=1);

namespace App\Model\Employee\Entity\Schedule;

use App\Model\Employee\Entity\TimeRange;
use JsonSerializable;

class DaySchedule implements JsonSerializable
{
    private string $day;
    private array $timeRanges;
    private bool $invertTimeRanges = false;

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

    public function invertTimeRanges(): void
    {
        $this->invertTimeRanges = true;
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

    public function getInverseTimeRanges(): array
    {
        $workDaySchedule = $this->getTimeRanges();
        $notWorkDaySchedule = [];
        $lastItem = [];
        for ($i = 0; $i < count($workDaySchedule); $i++) {
            if ($i == 0) {
                $notWorkDaySchedule[] = $this->setStart($workDaySchedule[$i]);
                $lastItem = $workDaySchedule[$i];
                continue;
            }
            $notWorkDaySchedule[] = new TimeRange($lastItem->getEnd(), $workDaySchedule[$i]->getStart());
            $lastItem = $workDaySchedule[$i];
        }
        $notWorkDaySchedule[] = $this->setEnd($lastItem);
        return $notWorkDaySchedule;
    }
    private function setStart($item): TimeRange
    {
        return new TimeRange('00:00', $item->getStart());
    }

    private function setEnd($item): TimeRange
    {
        return new TimeRange($item->getEnd(), '00:00');
    }

    public function jsonSerialize(): array
    {
        return [
            'day' => $this->day,
            'timeRanges' => ($this->invertTimeRanges) ? $this->getInverseTimeRanges() : $this->getTimeRanges(),
        ];
    }
}
