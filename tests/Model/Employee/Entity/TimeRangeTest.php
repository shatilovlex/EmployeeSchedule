<?php

namespace App\Tests\Model\Employee\Entity;

use App\Model\Employee\Entity\TimeRange;
use PHPUnit\Framework\TestCase;

class TimeRangeTest extends TestCase
{
    public function testSuccess()
    {
        $timeRange = new TimeRange($start = "09:00", $end = "10:00");
        $this->assertEquals($start, $timeRange->getStart());
        $this->assertEquals($end, $timeRange->getEnd());
    }
}
