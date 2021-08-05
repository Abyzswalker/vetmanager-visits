<?php

namespace Abyzs\VetmanagerVisits;

use PHPUnit\Framework\TestCase;


class TodayVisitsTest extends TestCase
{
    protected $todayvisits;

    public function setUp(): void
    {
        $this->todayvisits = new TodayVisits();
    }

    public function testTodayCount(): void
    {
        $this->assertEquals(1, $this->todayvisits->count(ArrayFromDate::date()));
    }
}