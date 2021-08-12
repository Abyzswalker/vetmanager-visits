<?php

namespace Abyzs\VetmanagerVisits;

use DateInterval;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Otis22\VetmanagerRestApi\Headers\Auth\ApiKey;
use Otis22\VetmanagerRestApi\Headers\Auth\ByApiKey;
use Otis22\VetmanagerRestApi\Headers\WithAuth;
use PHPUnit\Framework\TestCase;


class InvoicesTest extends TestCase
{
    public function testGive(): void
    {
        $today = date("Y-m-d 00:00:00");
        $timeInterval = DateTime::createFromFormat('Y-m-d H:i:s', $today);

        $arr = [
            'success' => 1,
            'message' => 'test',
            'data' => [
                'totalCount' => 1,
                'invoice' => [
                        'id' => 1,
                        'status' => 'exec',
                        'invoice_date' => $timeInterval
                    ]
            ]
        ];
        
        $request = json_encode($arr);
        $mock = new MockHandler(
            [
                new Response(
                    200,
                    [],
                    $request
                )
            ]
        );
        $handlerStack = HandlerStack::create($mock);
        $invoices =  new Invoices(
            new Client(['handler' => $handlerStack]),
            new WithAuth(
                new ByApiKey(
                    new ApiKey('testApi')
                )
            ),
            new InvoiceFilter(new DateInterval('P0D'))
        );
        $this->assertArrayHasKey(
            'invoice_date',
            $invoices->give()
        );
    }
}