<?php

namespace Abyzs\VetmanagerVisits;

use DateTime;
use DateInterval;


final class TodayVisits implements Visits
{
    public function count(array $array): int
    {
        $todayCount = [];
        $today = date("Y-m-d 00:00:00");
        $day = DateTime::createFromFormat('Y-m-d H:i:s', $today);
        $day->add(new DateInterval('P1D'));
        foreach ($array as $value) {
            if (
                $value['invoice_date'] >= $today && $value['invoice_date'] < $day->format('Y-m-d H:i:s')
            ) {
                $todayCount[] = $value['invoice_date'];
            }
        }
        return count($todayCount);
    }
}