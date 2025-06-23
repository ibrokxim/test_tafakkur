<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_unauthenticated_user_cannot_access_products(): void
    {
        // Пытаемся получить доступ без токена
        $response = $this->getJson('/api/products');
        // Проверяем, что получили ошибку 401 Unauthorized
        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_list_products(): void
    {
        Product::factory(3)->create();

        $response = $this->actingAs($this->user, 'api')->getJson('/api/products');

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    public function test_authenticated_user_can_create_a_product(): void
    {
        $category = Category::factory()->create();
        $productData = [
            'title' => 'New Awesome Product',
            'price' => 123.45,
            'description' => 'A description.',
            'category_id' => $category->id,
        ];

        $response = $this->actingAs($this->user, 'api')->postJson('/api/products', $productData);

        $response->assertCreated();
        $response->assertJsonPath('data.title', 'New Awesome Product'); // Проверяем, что данные в ответе верны
        $this->assertDatabaseHas('products', ['title' => 'New Awesome Product']); // Проверяем, что запись появилась в БД
    }

    public function test_authenticated_user_can_update_a_product(): void
    {
        $product = Product::factory()->create();
        $updateData = ['title' => 'Updated Product Title'];

        $response = $this->actingAs($this->user, 'api')->putJson("/api/products/{$product->id}", $updateData);

        $response->assertOk();
        $this->assertDatabaseHas('products', ['id' => $product->id, 'title' => 'Updated Product Title']);
    }

    public function test_authenticated_user_can_delete_a_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user, 'api')->deleteJson("/api/products/{$product->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('products', ['id' => $product->id]); // Проверяем, что запись удалена из БД
    }
}
