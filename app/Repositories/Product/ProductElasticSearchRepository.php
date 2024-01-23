<?php

namespace App\Repositories\Product;

use Elastic\Elasticsearch\Client;

class ProductElasticSearchRepository extends ProductSearchRepository
{
    public function __construct(private Client $elasticsearch)
    {
    }

    public function search()
    {
        $params = [
            'index' => 'products',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [],
                        'filter' => [],
                    ],
                ],
                'sort' => [],
            ],
        ];

        if ($this->titleQuery) {
            $params['body']['query']['bool']['must'][] = ['match' => ['title' => $this->titleQuery]];
        }

        if ($this->maxPriceQuery) {
            $params['body']['query']['bool']['filter'][] = ['range' => ['price' => ['lte' => $this->maxPriceQuery]]];
        }

        if ($this->sort) {
            $params['body']['sort'] = ['price' => ['order' => 'asc']];
        }

        $response = $this->elasticsearch->search($params);

        return $this->decorateResult($response);
    }

    private function decorateResult($response): array
    {
        $decorateResult = [];
        foreach ($response->asArray()['hits']['hits'] as $hit) {
            $decorateResult['data'][] = $hit['_source'];
        }

        return $decorateResult;
    }
}
