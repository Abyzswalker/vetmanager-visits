<?php

namespace Abyzs\VetmanagerVisits;

use PHPUnit\Framework\TestCase;


class VisitCounterTest extends TestCase
{
    protected VisitCounter $visitCounter;

    /**
     * @var void
     */
    protected function setUp(): void
    {
        $this->visitCounter = new VisitCounter('abyzs', 'API_KEY');
    }

    public function testGetInvoices(): void
    {
        $this->assertIsArray($this->visitCounter->getInvoices());
    }

    public function testGetDayCount(): void
    {
        $today = date("Y-m-d 00:00:00");
        $arr = Array(
            ['invoice_date' => $today],
            ['invoice_date' => '2021-07-02 23:59:59']
        );
        $class = new ReflectionClass('VisitCounter');
        $method = $class->getMethod('getDayCount');
        $method->setAccessible(true);
        $obj = new VisitCounter('test', 'test_api');

        $result = $method->invoke($obj, $arr);
        $this->assertCount(1, $result);
    }

    public function testGetWeekCount(): void
    {
        $today = date("Y-m-d 00:00:00");
        $week = DateTime::createFromFormat('Y-m-d H:i:s', $today);
        $week->sub(new DateInterval('P7D'));
        $arr = Array(
            ['invoice_date' => $today],
            ['invoice_date' => $week],
            ['invoice_date' => '2021-06-25 07:12:15']
        );
        $class = new ReflectionClass('VisitCounter');
        $method = $class->getMethod('getWeekCount');
        $method->setAccessible(true);
        $obj = new VisitCounter('test', 'test_api');

        $result = $method->invoke($obj, $arr);
        $this->assertCount(2, $result);
    }
}