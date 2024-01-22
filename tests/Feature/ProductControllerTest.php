<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use DatabaseTransactions,WithFaker;

    /**
     * store product feature test.
     *
     * @test
     */
    public function store_product(): void
    {
        $this->signIn();
        $productData = [
            'title' => $this->faker->name.' - Product',
            'price' => $this->faker->randomNumber(6),
        ];

        $response = $this->postJson('/api/product', $productData);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'status',
                'data',
                'message',
            ])->assertJsonPath('data.title', $productData['title']);
    }

    /**
     * store product as a guest user feature test
     * .
     *
     * @test
     */
    public function store_product_guest_user(): void
    {
        $productData = [
            'title' => $this->faker->name.' - Product',
            'price' => $this->faker->randomNumber(6),
        ];

        $response = $this->postJson('/api/product', $productData);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * delete product feature test.
     *
     * @test
     */
    public function delete_product(): void
    {
        $this->signIn();

        $product = Product::factory()->create(['user_id' => $this->user->id]);

        $response = $this->delete('/api/product/'.$product->id);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * delete product unauthorized feature test.
     *
     * @test
     */
    public function delete_product_unauthorized(): void
    {
        $this->signIn();

        $product = Product::factory()->for(
            User::factory()
        )->create();

        $response = $this->delete('/api/product/'.$product->id);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * add media to products feature test
     *
     * @return void
     *
     * @test
     */
    public function add_media_to_product()
    {
        $this->signIn();
        $product = Product::factory()->for(
            $this->user, 'user'
        )->create();

        Storage::fake('public');

        $this->postJson('/api/product/'.$product->id.'/add-media', [
            'attachments' => [
                UploadedFile::fake()->image('image1.jpg', 900),
                UploadedFile::fake()->image('image2.png', 700),
            ],
        ])->assertCreated();

        $obj = Product::find($product->id);
        $media = $obj->getMedia('images');

        $this->assertEquals(2, $media->count());

        foreach ($media as $file) {
            Storage::disk('public')->assertExists($file->getUrlGenerator('')->getPathRelativeToRoot());
        }
    }
}
