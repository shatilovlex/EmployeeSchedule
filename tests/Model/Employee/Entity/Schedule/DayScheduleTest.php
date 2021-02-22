<?php

namespace App\Tests\Model\Employee\Entity\Schedule;

use App\Model\Employee\Entity\Schedule\DaySchedule;
use App\Model\Employee\Entity\TimeRange;
use PHPUnit\Framework\TestCase;

class DayScheduleTest extends TestCase
{
    public function testSuccess(): void
    {
        $daySchedule = new DaySchedule($day = "2021-02-22");
        $daySchedule->addTimeRanges($timeRage = new TimeRange("09:00", "10:00"));
        $this->assertEquals($day, $daySchedule->getDay());
        $this->assertIsArray($daySchedule->getTimeRanges());
        $this->assertSame([$timeRage], $daySchedule->getTimeRanges());
    }
}
