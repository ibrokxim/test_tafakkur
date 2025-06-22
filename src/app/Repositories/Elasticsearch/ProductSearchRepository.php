<?php
namespace App\Repositories\Elasticsearch;

use App\Models\Product;
use App\Repositories\Contracts\ProductSearchRepositoryInterface;
use Elastic\Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;

class ProductSearchRepository implements ProductSearchRepositoryInterface
{
    private Client $client;
    private string $indexName;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->indexName = 'products_index';
    }

    /**
     * Поиск продуктов в Elasticsearch.
     */
    public function search(string $query): Collection
    {
        $items = $this->client->search([
            'index' => $this->indexName,
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['title^5', 'description', 'category_title'],
                        'fuzziness' => 'AUTO'
                    ]
                ]
            ]
        ]);

        // Получаем ID найденных документов
        $ids = array_column($items['hits']['hits'], '_id');

        // Загружаем модели из основной базы данных (PostgreSQL)
        return Product::findMany($ids)
            ->sortBy(function ($product) use ($ids) {
                // Сохраняем порядок релевантности, полученный от Elasticsearch
                return array_search($product->getKey(), $ids);
            });
    }

    /**
     * Индексация одного продукта.
     */
    public function index(Product $product): void
    {
        $this->client->index([
            'index' => $this->indexName,
            'id' => $product->id,
            'body' => [
                'title' => $product->title,
                'description' => $product->description,
                'price' => $product->price,
                'category_title' => $product->category->title ?? null,
            ]
        ]);
    }

    /**
     * Удаление продукта из индекса.
     */
    public function delete(Product $product): void
    {
        $this->client->delete([
            'index' => $this->indexName,
            'id' => $product->id,
        ]);
    }
}
