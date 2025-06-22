<?php

namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticsearchService
{
    protected $client;
    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([config('services.elasticsearch.hosts')])
            ->build();
    }
    public function search($index, $query)
    {
        $params = [
            'index' => $index,
            'body' => [
                'query' => [
                    'match' => $query
                ]
            ]
        ];
        return $this->client->search($params);
    }

}
