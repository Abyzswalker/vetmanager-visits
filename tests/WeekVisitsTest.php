<?php

namespace Abyzs\VetmanagerVisits;

use PHPUnit\Framework\TestCase;


class WeekVisitsTest extends TestCase
{
    protected $weekvisits;

    public function setUp(): void
    {
        $this->weekvisits = new WeekVisits();
    }

    public function testWeekCount(): void
    {
        $this->assertEquals(2, $this->weekvisits->count(ArrayFromDate::date()));
    }
}