<?php

namespace Abyzs\VetmanagerVisits;

use GuzzleHttp\ClientInterface;
use Otis22\VetmanagerRestApi\Headers;
use Otis22\VetmanagerRestApi\Model;
use Otis22\VetmanagerRestApi\Query\PagedQuery;
use Otis22\VetmanagerRestApi\URI\OnlyModel;


final class Invoices
{
    private $client;
    private $auth;
    private $filter;
    public array $result = [];

    public function __construct(ClientInterface $client, Headers $auth, InvoiceFilter $filter)
    {
        $this->client = $client;
        $this->auth = $auth;
        $this->filter = $filter;
    }

    private function uri(): string
    {
        return (new OnlyModel(
            new Model('invoice')
        ))->asString();
    }

    public function give(): array
    {
        $paged = PagedQuery::forGettingTop(
            $this->filter->query(),
            30
        );

        do {
            $response = json_decode(
                strval(
                    $this->client->request(
                        'GET',
                        $this->uri(),
                        [
                            'headers' => $this->auth->asKeyValue(),
                            'query' => $paged->asKeyValue()
                        ]
                    )->getBody()
                ),
                true
            );
            $paged = $paged->next();
            $this->result = array_merge(
                $response['data']['invoice'],
                $this->result
            );
        } while (count($this->result) < $response['data']['totalCount']);
        return $this->result;
    }
}