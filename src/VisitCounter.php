<?php

namespace Abyzs\VetmanagerVisits;

use DateTime;
use DateInterval;
use GuzzleHttp\Client;
use Otis22\VetmanagerRestApi\Headers\Auth\ApiKey;
use Otis22\VetmanagerRestApi\Headers\Auth\ByApiKey;
use Otis22\VetmanagerRestApi\Headers\WithAuth;
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

class VisitCounter
{
    protected string $domain;
    protected WithAuth $api;
    protected Client $client;
    protected OnlyModel $uri;
    public array $result = [];

    public function __construct(string $domain, string $api)
    {
        $this->domain = $domain;
        $this->api = new WithAuth(
            new ByApiKey(
                new ApiKey($api)
            )
        );
        $this->uri = new OnlyModel(
            new Model('invoice')
        );
    }


    /**
     * @return array <array>
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function getInvoices(): array
    {
        $this->client = new Client(['base_uri' => url($this->domain)->asString()]);

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
                        new Property('id')
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
                            'headers' => $this->api->asKeyValue(),
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

    /**
     * @param array <array>
     * @return array <array>
     */
    private function getWeekCount(array $array): array
    {
        $weekCount = [];
        $today = date("Y-m-d 00:00:00");
        $week = DateTime::createFromFormat('Y-m-d H:i:s', $today);
        $week->sub(new DateInterval('P7D'));
        foreach ($array as $value) {
            if (
                $value['invoice_date'] >= $week->format('Y-m-d H:i:s')
            ) {
                $weekCount[] = $value;
            }
        }
        return $weekCount;
    }


    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function weekCount(): int
    {
        $weekCount = $this->getWeekCount($this->getInvoices());
        return count($weekCount);
    }

    /**
     * @param array <array>
     * @return array <array>
     */
    private function getDayCount(array $array): array
    {
        $dayCount = [];
        $today = date("Y-m-d 00:00:00");
        $day = DateTime::createFromFormat('Y-m-d H:i:s', $today);
        $day->add(new DateInterval('P1D'));
        foreach ($array as $value) {
                if (
                    $value['invoice_date'] >= $today && $value['invoice_date'] < $day->format('Y-m-d H:i:s')
                ) {
                    $dayCount[] = $value['invoice_date'];
                }
        }
        return $dayCount;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function dayCount(): int
    {
        $dayCount = $this->getDayCount($this->getInvoices());
        return count($dayCount);
    }
}