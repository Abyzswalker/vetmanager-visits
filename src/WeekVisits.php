<?php

namespace Abyzs\VetmanagerVisits;

use DateTime;
use DateInterval;


final class WeekVisits implements Visits
{
    public function count(array $array): int
    {
        $weekCount = [];
        $today = date("Y-m-d 00:00:00");
        $week = DateTime::createFromFormat('Y-m-d H:i:s', $today);
        $week->sub(new DateInterval('P7D'));
        foreach ($array as $value) {
            if (
                $value['invoice_date'] >= $week->format('Y-m-d H:i:s')
            ) {
                $weekCount[] = $value['invoice_date'];
            }
        }
        return count($weekCount);
    }
}