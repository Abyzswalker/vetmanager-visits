<?php

namespace Abyzs\VetmanagerVisits;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use function Otis22\VetmanagerUrl\url;
use function getenv;


class GetInvoicesTest extends TestCase
{
    protected $getinvoices;
    private Client $client;

    protected function setUp(): void
    {
        $this->getinvoices = new GetInvoices(getenv('TEST_DOMAIN_NAME'), getenv('TEST_API_KEY'));
    }

    public function tearDown(): void
    {
        $this->getinvoices = null;
    }

    public function testClient(): void
    {
        $this->client = new Client(['base_uri' => url(getenv('TEST_DOMAIN_NAME'))->asString()]);

        $response = $this->client->request('GET', '/rest/api/invoices');
        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
    }

    public function testGetInvoices(): void
    {
        $this->assertIsArray($this->getinvoices->getInvoices());
    }
}