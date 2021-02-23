<?php

declare(strict_types=1);

namespace App\Model\Employee\Service;

use App\Model\Employee\Entity\Employee;
use App\Model\Employee\Entity\Schedule\DaySchedule;
use App\Model\Employee\Entity\Schedule\Schedule;
use App\Model\Employee\Entity\TimeRange;
use DateInterval;
use DatePeriod;
use DateTimeInterface;
use gozoro\russian_calendar\RussianCalendar;

class ScheduleService
{
    private const DATE_FORMAT = 'Y-m-d';
    /**
     * @var RussianCalendar
     */
    private RussianCalendar $calendar;

    public function __construct(RussianCalendar $calendar)
    {
        $this->calendar = $calendar;
    }

    /**
     * Формирование рабочего расписания служащего
     * @param Employee $employee
     * @param DateTimeInterface $dateStart
     * @param DateTimeInterface $dateEnd
     * @return Schedule
     */
    public function generateWorkSchedule(
        Employee $employee,
        DateTimeInterface $dateStart,
        DateTimeInterface $dateEnd
    ): Schedule {
        $schedule = new Schedule();
        $dateInterval = new DateInterval("P1D");
        $period = new DatePeriod($dateStart->setTime(0, 0, 0), $dateInterval, $dateEnd->setTime(23, 59, 59));
        foreach ($period as $dateTime) {
            if (!$this->checkWeekend($dateTime)) {
                $schedule->addDaySchedule($this->getDaySchedule($employee, $dateTime));
            }
        }
        return $schedule;
    }
    /**
     * Формирование не рабочего расписания служащего
     * @param Employee $employee
     * @param DateTimeInterface $dateStart
     * @param DateTimeInterface $dateEnd
     * @return Schedule
     */
    public function generateNotWorkSchedule(
        Employee $employee,
        DateTimeInterface $dateStart,
        DateTimeInterface $dateEnd
    ): Schedule {
        $schedule = new Schedule();
        $dateInterval = new DateInterval("P1D");
        $period = new DatePeriod($dateStart->setTime(0, 0, 0), $dateInterval, $dateEnd->setTime(23, 59, 59));
        foreach ($period as $dateTime) {
            if ($this->checkWeekend($dateTime)) {
                $schedule->addDaySchedule($this->getWeekendDaySchedule($dateTime));
            } else {
                $schedule->addDaySchedule($this->getDaySchedule($employee, $dateTime, true));
            }
        }
        return $schedule;
    }
    /**
     * @param DateTimeInterface $date
     * @return bool
     */
    private function checkWeekend(DateTimeInterface $date): bool
    {
        return $this->calendar->checkWeekend($date->format(self::DATE_FORMAT));
    }

    /**
     * Формирование дневного расписания служащего
     * @param Employee $employee
     * @param DateTimeInterface $dateTime
     * @param bool $invert
     * @return DaySchedule
     */
    private function getDaySchedule(Employee $employee, DateTimeInterface $dateTime, bool $invert = false): DaySchedule
    {
        $daySchedule = new DaySchedule($dateTime->format(self::DATE_FORMAT));
        foreach ($employee->getWorkDaySchedule()->getTimeRanges() as $timeRange) {
            $daySchedule->addTimeRanges($timeRange);
        }
        if ($invert) {
            $daySchedule->invertTimeRanges();
        }
        return $daySchedule;
    }
    /**
     * Формирование дневного расписания служащего выходного дня
     * @param DateTimeInterface $dateTime
     * @param bool $invert
     * @return DaySchedule
     */
    private function getWeekendDaySchedule(DateTimeInterface $dateTime): DaySchedule
    {
        $daySchedule = new DaySchedule($dateTime->format(self::DATE_FORMAT));
        $timeRange = new TimeRange("00:00", "00:00");
        $daySchedule->addTimeRanges($timeRange);
        return $daySchedule;
    }
}
