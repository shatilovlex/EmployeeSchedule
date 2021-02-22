<?php

namespace App\Tests\Model\Employee\Entity;

use App\Model\Employee\Entity\TimeRange;
use App\Model\Employee\Entity\WorkDaySchedule;
use PHPUnit\Framework\TestCase;

class WorkDayScheduleTest extends TestCase
{
    public function testSuccess()
    {
        $timeRanges = [
            new TimeRange("09:00", "10:00")
        ];
        $workDaySchedule = new WorkDaySchedule($timeRanges);
        $this->assertEquals($timeRanges, $workDaySchedule->getTimeRanges());
    }
}
