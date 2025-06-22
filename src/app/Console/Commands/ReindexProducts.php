<?php
namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\Contracts\CategorySearchRepositoryInterface;
use App\Repositories\Contracts\ProductSearchRepositoryInterface;
use Illuminate\Console\Command;

class ReindexProducts extends Command
{
    protected $signature = 'search:reindex';
    protected $description = 'Indexes all products to Elasticsearch';

    public function handle(
        ProductSearchRepositoryInterface $searchRepository,
        CategorySearchRepositoryInterface $categorySearchRepository)
    {
        $this->info('Индексация продуктов, пожалуйста подождите');

        foreach (Product::cursor() as $product) {
            $searchRepository->index($product);
            $this->output->write('.');
        }
        $this->info("\nПродукты проиндексированы");

        $this->info('Индексация категорий');
        foreach (Category::cursor() as $category) {
            $categorySearchRepository->index($category);
            $this->output->write('.');
        }
        $this->info("\nКатегории проиндексированы");

        $this->info("\nИндексация завершена");
    }
}
