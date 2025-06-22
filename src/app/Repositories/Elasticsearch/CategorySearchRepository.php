<?php
namespace App\Repositories\Elasticsearch;

use App\Models\Category;
use App\Repositories\Contracts\CategorySearchRepositoryInterface;
use Elastic\Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;

class CategorySearchRepository implements CategorySearchRepositoryInterface
{
    private Client $client;
    private string $indexName = 'categories_index';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function search(string $query): Collection
    {
        $items = $this->client->search([
            'index' => $this->indexName,
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['title^3', 'description'],
                        'fuzziness' => 'AUTO'
                    ]
                ]
            ]
        ]);

        $ids = array_column($items['hits']['hits'], '_id');
        return Category::findMany($ids)
            ->sortBy(fn($category) => array_search($category->getKey(), $ids));
    }

    public function index(Category $category): void
    {
        $this->client->index([
            'index' => $this->indexName,
            'id' => $category->id,
            'body' => $category->toArray()
        ]);
    }

    public function delete(Category $category): void
    {
        $this->client->delete([
            'index' => $this->indexName,
            'id' => $category->id,
        ]);
    }
}
