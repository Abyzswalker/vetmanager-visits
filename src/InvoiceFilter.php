<?php

namespace Abyzs\VetmanagerVisits;

use DateInterval;
use DateTime;
use Otis22\VetmanagerRestApi\Model\Property;
use Otis22\VetmanagerRestApi\Query\Filter\MoreThan;
use Otis22\VetmanagerRestApi\Query\Filter\NotInArray;
use Otis22\VetmanagerRestApi\Query\Filter\Value\ArrayValue;
use Otis22\VetmanagerRestApi\Query\Filter\Value\StringValue;
use Otis22\VetmanagerRestApi\Query\Filters;
use Otis22\VetmanagerRestApi\Query\PagedQuery;
use Otis22\VetmanagerRestApi\Query\Query;
use Otis22\VetmanagerRestApi\Query\Sort\DescBy;
use Otis22\VetmanagerRestApi\Query\Sorts;


class InvoiceFilter
{
    private DateInterval $interval;

    public function __construct(DateInterval $interval)
    {
        $this->interval = $interval;
    }

    public function query(): Query
    {
        $today = date("Y-m-d 00:00:00");
        $timeInterval = DateTime::createFromFormat('Y-m-d H:i:s', $today);
        $timeInterval->sub($this->interval);

        return new Query(PagedQuery::forGettingTop(
            new Query(
                new Filters(
                    new NotInArray(
                        new Property('status'),
                        new ArrayValue(["save", "deleted"])
                    ),
                    new MoreThan(
                        new Property('invoice_date'),
                        new StringValue($timeInterval->format('Y-m-d H:i:s'))
                    )
                ),
                new Sorts(
                    new DescBy(
                        new Property('invoice_date')
                    ))
            ),
            30
        ));
    }
}