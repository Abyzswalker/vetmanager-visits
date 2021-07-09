<?php

namespace Abyzs\VetmanagerVisits;

use ReflectionClass;
use PHPUnit\Framework\TestCase;


class VisitCounterTest extends TestCase
{
    protected VisitCounter $visitCounter;

    /**
     * @var void
     */
    protected function setUp(): void
    {
        $this->visitCounter = new VisitCounter(getenv('TEST_DOMAIN_NAME'), getenv('TEST_API_KEY'));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetInvoices(): void
    {
        $this->assertIsArray($this->visitCounter->getInvoices());
    }

    /**
     * @throws \ReflectionException
     */
    public function testGetDayCount(): void
    {
        $today = date("Y-m-d 00:00:00");
        $arr = Array(
            ['invoice_date' => $today],
            ['invoice_date' => '2021-07-02 23:59:59']
        );
        $class = new ReflectionClass(VisitCounter::class);
        $method = $class->getMethod('getDayCount');
        $method->setAccessible(true);
        $obj = new VisitCounter('test', 'test_api');

        $result = $method->invoke($obj, $arr);
        $this->assertCount(1, $result);
    }
}