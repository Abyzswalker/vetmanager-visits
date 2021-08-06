<?php

namespace Abyzs\VetmanagerVisits;


use Otis22\VetmanagerRestApi\Model\Property;
use Otis22\VetmanagerRestApi\Query\Filter\MoreThan;
use Otis22\VetmanagerRestApi\Query\Filter\NotInArray;
use Otis22\VetmanagerRestApi\Query\Filter\Value\ArrayValue;
use Otis22\VetmanagerRestApi\Query\Filter\Value\StringValue;
use Otis22\VetmanagerRestApi\Query\Filters;
use Otis22\VetmanagerRestApi\Query\Query;
use Otis22\VetmanagerRestApi\Query\Sort\DescBy;
use Otis22\VetmanagerRestApi\Query\Sorts;
use PHPUnit\Framework\TestCase;


class InvoiceFilterTest extends TestCase
{
    public function testQuery(): void
    {
        $this->assertEquals(
            [
                'sort' => '[{"property":"invoice_date","direction":"DESC"}]',
                'filter' => '[{"property":"status","value":["save","deleted"],"operator":"not in"},{"property":"invoice_date","value":"2013-01-01","operator":">"}]',
            ],
            (
            new Query(
                new Filters(
                    new NotInArray(
                        new Property('status'),
                        new ArrayValue(["save", "deleted"])
                    ),
                    new MoreThan(
                        new Property('invoice_date'),
                        new StringValue('2013-01-01')
                    )
                ),
                new Sorts(
                    new DescBy(
                        new Property('invoice_date')
                    ))
            )
        )->asKeyValue()
        );
    }
}