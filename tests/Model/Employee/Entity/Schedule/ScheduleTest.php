<?php

namespace App\Tests\Model\Employee\Entity\Schedule;

use App\Model\Employee\Entity\Schedule\DaySchedule;
use App\Model\Employee\Entity\Schedule\Schedule;
use PHPUnit\Framework\TestCase;

class ScheduleTest extends TestCase
{
    public function testSuccess()
    {
        $schedule = new Schedule();
        $schedule->addDaySchedule($daySchedule = new DaySchedule('2021-02-22'));
        $this->assertIsArray($schedule->getDaysSchedule());
        $this->assertSame([$daySchedule], $schedule->getDaysSchedule());
    }
}
