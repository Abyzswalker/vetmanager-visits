<?php

namespace Abyzs\VetmanagerVisits;

use DateTime;
use DateInterval;
use PHPUnit\Framework\TestCase;


class VisitCounterTest extends TestCase
{
    protected $visitCounter;

    public function setUp(): void
    {
        $this->visitCounter = new VisitCounter();
    }

    /**
     * @throws \ReflectionException
     */
    public function testDayCount(): void
    {
        $today = date("Y-m-d 00:00:00");
        $arr = [
            ['invoice_date' => $today],
            ['invoice_date' => '2021-07-02 23:59:59']
        ];

        $this->assertEquals(1, $this->visitCounter->dayCount($arr));
    }

    public function testWeekCount(): void
    {
        $today = date("Y-m-d 00:00:00");
        $week = DateTime::createFromFormat('Y-m-d H:i:s', $today);
        $week->sub(new DateInterval('P7D'));
        $arr = [
            ['invoice_date' => $today],
            ['invoice_date' => $week->format('Y-m-d H:i:s')],
            ['invoice_date' => '2021-07-02 23:59:59']
        ];

        $this->assertEquals(2, $this->visitCounter->weekCount($arr));
    }
}