<?php

namespace Abyzs\VetmanagerVisits;

use PHPUnit\Framework\TestCase;
use function getenv;


class InvoiceGetterTest extends TestCase
{
    protected $invoicegetter;


    protected function setUp(): void
    {
        $this->invoicegetter = new InvoiceGetter(getenv('TEST_DOMAIN_NAME'), getenv('TEST_API_KEY'));
    }

    public function testGetInvoices(): void
    {
        $this->assertIsArray($this->invoicegetter->getInvoices());
    }
}