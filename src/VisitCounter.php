<?php

namespace Abyzs\VetmanagerVisits;

use DateTime;
use DateInterval;


class VisitCounter
{
    public array $weekCount;
    public array $dayCount;


    public function weekCount(array $array): int
    {
        $this->weekCount = [];
        $today = date("Y-m-d 00:00:00");
        $week = DateTime::createFromFormat('Y-m-d H:i:s', $today);
        $week->sub(new DateInterval('P7D'));
        foreach ($array as $value) {
            if (
                $value['invoice_date'] >= $week->format('Y-m-d H:i:s')
            ) {
                $this->weekCount[] = $value['invoice_date'];
            }
        }
        return count($this->weekCount);
    }


    public function dayCount(array $array): int
    {
        $this->dayCount = [];
        $today = date("Y-m-d 00:00:00");
        $day = DateTime::createFromFormat('Y-m-d H:i:s', $today);
        $day->add(new DateInterval('P1D'));
        foreach ($array as $value) {
            if (
                $value['invoice_date'] >= $today && $value['invoice_date'] < $day->format('Y-m-d H:i:s')
            ) {
                $this->dayCount[] = $value['invoice_date'];
            }
        }
        return count($this->dayCount);
    }
}