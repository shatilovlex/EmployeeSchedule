<?php

namespace App\Tests\Controller;

use Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeScheduleControllerTest extends WebTestCase
{
    private const GET_EMPLOYEE_SCHEDULE_ENDPOINT_URL = '/employee-schedule';

    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
    }

    /**
     * @dataProvider getEmployeesData
     */
    public function testScheduleMustBeInValidFormat(int $employeeId): void
    {
        $workDay = '2021-01-11';

        $response = $this->requestSchedule($employeeId, $workDay, $workDay);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        $this->assertResponseContainsSchedule($response);
    }

    /**
     * @dataProvider getEmployeesData
     *
     * @param mixed[] $employeeWorkDaySchedule
     */
    public function testWorkDayScheduleMustContainsEmployeeSchedule(int $employeeId, array $employeeWorkDaySchedule): void
    {
        $workDay = '2021-01-11';

        $response = $this->requestSchedule($employeeId, $workDay, $workDay);
        $responseData = $this->getJsonResponseDataOrNull($response);

        $schedule = $responseData['schedule'] ?? [];
        $workDaySchedule = $this->findDaySchedule($workDay, $schedule);

        $this->assertNotEmpty($workDaySchedule);
        $this->assertEquals($employeeWorkDaySchedule, $workDaySchedule['timeRanges']);
    }

    /**
     * @dataProvider getEmployeesData
     */
    public function testWeekendMustBeExcludedFromWorkSchedule(int $employeeId): void
    {
        $weekendStart = '2021-01-16';
        $weekendEnd = '2021-01-17';

        $response = $this->requestSchedule($employeeId, $weekendStart, $weekendEnd);

        $responseData = $this->getJsonResponseDataOrNull($response);

        $schedule = $responseData['schedule'] ?? null;

        $this->assertIsArray($schedule);
        $this->assertNull($this->findDaySchedule($weekendStart, $schedule));
        $this->assertNull($this->findDaySchedule($weekendEnd, $schedule));
    }

    /**
     * @dataProvider getEmployeesData
     */
    public function testHolidaysMustBeExcludedFromSchedule(int $employeeId): void
    {
        $holiday = '2021-02-23';
        $workDay = '2021-02-24';

        $response = $this->requestSchedule($employeeId, $holiday, $workDay);
        $responseData = $this->getJsonResponseDataOrNull($response);

        $schedule = $responseData['schedule'] ?? [];

        $this->assertIsArray($schedule);
        $this->assertNull($this->findDaySchedule($holiday, $schedule));
        $this->assertNotNull($this->findDaySchedule($workDay, $schedule));
    }

    /**
     * @dataProvider getEmployeesData
     */
    public function testInvalidRequestMustReturnErrors(int $employeeId): void
    {
        $invalidDate = 'invalid date';

        $response = $this->requestSchedule($employeeId, $invalidDate, $invalidDate);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());

        $this->assertResponseContainsErrors($response);
    }

    public function getEmployeesData(): Generator
    {
        yield 'Employee who works from the late morning' =>  [
            'id' => 1,
            'workDaySchedule' => [
                [
                    'start' => '10:00',
                    'end' => '13:00',
                ],
                [
                    'start' => '14:00',
                    'end' => '19:00',
                ],
            ],
        ];

        yield 'Employee who works from the early morning' => [
            'id' => 2,
            'workDaySchedule' => [
                [
                    'start' => '09:00',
                    'end' => '12:00',
                ],
                [
                    'start' => '13:00',
                    'end' => '18:00',
                ],
            ],
        ];
    }

    private function requestSchedule(int $userId, string $startDate, string $endDate): Response
    {
        $this->client->request(Request::METHOD_GET, self::GET_EMPLOYEE_SCHEDULE_ENDPOINT_URL, [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'employeeId' => $userId,
        ]);

        return $this->client->getResponse();
    }

    /**
     * @return mixed[]
     */
    private function getJsonResponseDataOrNull(JsonResponse $response): ?array
    {
        $responseContent = $response->getContent();

        return $responseContent ? json_decode($responseContent, true) : null;
    }

    /**
     * @param mixed[] $schedule
     *
     * @return string[][]|null
     */
    private function findDaySchedule(string $day, array $schedule): ?array
    {
        foreach ($schedule as $daySchedule) {
            if ($daySchedule['day'] === $day) {
                return $daySchedule;
            }
        }

        return null;
    }

    private function assertResponseContainsSchedule(JsonResponse $response): void
    {
        $responseData = $this->getJsonResponseDataOrNull($response);

        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('schedule', $responseData);

        $schedule = $responseData['schedule'];

        $this->assertIsArray($schedule);
        $this->assertNotCount(0, $schedule);

        foreach ($schedule as $daySchedule) {
            $this->assertDayScheduleIsValid($daySchedule);
        }
    }

    /**
     * @param mixed[] $daySchedule
     */
    private function assertDayScheduleIsValid(array $daySchedule): void
    {
        $this->assertArrayHasKey('day', $daySchedule);
        $this->assertArrayHasKey('timeRanges', $daySchedule);

        $day = $daySchedule['day'];
        $timeRanges = $daySchedule['timeRanges'];

        $this->assertIsString($day);
        $this->assertIsArray($timeRanges);
        $this->assertNotCount(0, $timeRanges);

        foreach ($timeRanges as $timeRange) {
            $this->assertTimeRangeIsValid($timeRange);
        }
    }

    /**
     * @param mixed[] $timeRange
     */
    private function assertTimeRangeIsValid(array $timeRange): void
    {
        $this->assertArrayHasKey('start', $timeRange);
        $this->assertArrayHasKey('end', $timeRange);

        $startTime = $timeRange['start'];
        $endTime = $timeRange['end'];

        $this->assertIsString($startTime);
        $this->assertIsString($endTime);
    }

    private function assertResponseContainsErrors(JsonResponse $response): void
    {
        $responseData = $this->getJsonResponseDataOrNull($response);

        $this->assertArrayHasKey('errors', $responseData);

        $errors = $responseData['errors'];

        $this->assertIsArray($errors);
        $this->assertNotCount(0, $errors);
    }
}
