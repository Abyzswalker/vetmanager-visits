<?php


namespace Abyzs\VetmanagerVisits;

use DateTime;
use DateInterval;


final class ArrayFromDate
{
   public static function date(): array
    {
        $today = date("Y-m-d 00:00:00");
        $week = DateTime::createFromFormat('Y-m-d H:i:s', $today);
        $week->sub(new DateInterval('P7D'));
        $arr = [
            ['invoice_date' => $today],
            ['invoice_date' => $week->format('Y-m-d H:i:s')],
            ['invoice_date' => '2021-07-02 23:59:59']
        ];
        return $arr;
    }
}