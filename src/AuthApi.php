<?php

namespace Abyzs\VetmanagerVisits;

use DateTime;
use DateInterval;
use GuzzleHttp\Client;
use Otis22\VetmanagerRestApi\Model;
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
use Otis22\VetmanagerRestApi\URI\OnlyModel;
use function Otis22\VetmanagerUrl\url;
use function Otis22\VetmanagerRestApi\byApiKey;


final class AuthApi implements Auth
{
    private string $domain;
    private $api;
    private Client $client;
    private OnlyModel $uri;
    private $authHeaders;
    public array $result = [];


    public function __construct(string $domain, $api)
    {
        $this->authHeaders = byApiKey($this->api = $api);
        $this->client = new Client(['base_uri' => url($this->domain = $domain)->asString()]);
        $this->uri = new OnlyModel(
            new Model('invoice')
        );
    }

    public function giveInvoices(): array
    {
        $today = date("Y-m-d 00:00:00");
        $week = DateTime::createFromFormat('Y-m-d H:i:s', $today);
        $week->sub(new DateInterval('P7D'));

        $paged = PagedQuery::forGettingTop(
            new Query(
                new Filters(
                    new NotInArray(
                        new Property('status'),
                        new ArrayValue(["save", "deleted"])
                    ),
                    new MoreThan(
                        new Property('invoice_date'),
                        new StringValue($week->format('Y-m-d H:i:s'))
                    ),
                ),
                new Sorts(
                    new DescBy(
                        new Property('invoice_date')
                    ))
            ),
            30
        );

        do {
            $response = json_decode(
                strval(
                    $this->client->request(
                        'GET',
                        $this->uri->asString(),
                        [
                            'headers' => $this->authHeaders->asKeyValue(),
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