<?php

namespace Abyzs\VetmanagerVisits;

use PHPUnit\Framework\TestCase;
use function getenv;


class AuthApiTest extends TestCase
{
    protected $auth;


    protected function setUp(): void
    {
        $this->auth = new AuthApi(getenv('TEST_DOMAIN_NAME'), getenv('TEST_API_KEY'));
    }

    public function testGetInvoices(): void
    {
        $this->assertIsArray($this->auth->giveInvoices());
    }
}