<?php

namespace Tests\Feature;

use App\Enums\CartState;
use App\Events\OrderPlaced;
use App\Events\ProductAddedToCart;
use App\Events\ProductRemovedFromCart;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    /**
     * A basic feature test example.
     *
     * @test
     */
    public function add_to_cart_tests_adds_product_to_active_cart_and_increments_quantity_if_exists(): void
    {
        $this->signIn();

        // Event and listener verification
        Event::fake([
            \App\Events\ProductAddedToCart::class,
        ]);

        $product = Product::factory()->create();

        $cart = Cart::factory()->active()->create(['user_id' => $this->user->id]);
        $cartItem = CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 1]);

        $response = $this->postJson('/api/cart/'.$product->id.'/add');

        $response->assertStatus(201);

        $cart->refresh(); // Refresh to reflect changes
        $cartItem->refresh();

        $this->assertEquals($cart->id, $cartItem->cart_id);
        $this->assertEquals($product->id, $cartItem->product_id);
        $this->assertEquals(2, $cartItem->quantity); // Quantity incremented

        Event::assertDispatched(ProductAddedToCart::class);
    }

    /**
     * @return void
     *
     * @test
     */
    public function add_to_cart_test_requires_authentication()
    {
        $product = Product::factory()->create();

        $response = $this->postJson('/api/cart/'.$product->id.'/add');

        $response->assertStatus(401);
    }

    /**
     * @return void
     *
     * @test
     */
    public function remove_product_from_cart_and_dispatches_event()
    {
        Event::fake([
            \App\Events\ProductRemovedFromCart::class,
        ]);

        $this->signIn();

        $product = Product::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $this->user->id, 'is_active' => CartState::Active]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 1]);

        $response = $this->postJson('/api/cart/'.$product->id.'/remove');

        $response->assertStatus(204);

        $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $product->id)->first();
        $this->assertNull($cartItem); // Item removed

        Event::assertDispatched(ProductRemovedFromCart::class);
    }

    /**
     * @return void
     *
     * @test
     */
    public function remove_from_cart_throws_exception_if_cart_not_found()
    {
        $this->signIn();

        $product = Product::factory()->create();

        $this->postJson('/api/cart/'.$product->id.'/remove')->assertJsonStructure(['error']);
    }

    /**
     * @return void
     *
     * @test
     */
    public function remove_from_cart_throws_exception_if_product_not_in_cart()
    {
        $this->signIn();

        $product = Product::factory()->create();
        Cart::factory()->create(['user_id' => $this->user->id, 'is_active' => CartState::Active]);

        $this->postJson('/api/cart/'.$product->id.'/remove')->assertJsonStructure(['error']);
    }

    /**
     * @return void
     *
     * @test
     */
    public function submits_the_cart_creates_an_order_and_creates_a_new_cart()
    {
        Event::fake([
            \App\Events\OrderPlaced::class,
        ]);

        $this->signIn();

        $product = Product::factory()->create(['price' => 100]);
        $cart = Cart::factory()->create(['user_id' => $this->user->id, 'is_active' => CartState::Active]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 2]);

        $response = $this->postJson('/api/cart/submit');

        $response->assertStatus(201);

        $order = Order::where('user_id', $this->user->id)->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals($this->user->id, $order->user_id);
        $this->assertCount(1, $order->items);
        $this->assertEquals($product->id, $order->items->first()->product_id);
        $this->assertEquals(2, $order->items->first()->quantity);

        $orderItemPrice = $order->items->sum(fn ($item) => ($item->product->getDeliveryPriceFormula() + $item->product->price) * $item->quantity);
        $this->assertEquals($orderItemPrice, $order->total_price);
        $response->assertJsonPath('data.total_price', $orderItemPrice);

        Event::assertDispatched(OrderPlaced::class);

        $this->assertNull(Cart::where('user_id', $this->user->id)->where('is_active', CartState::Active)->where('id', $cart->id)->first());
        $newCart = Cart::where('user_id', $this->user->id)->where('is_active', CartState::Active)->first();
        $this->assertNotNull($newCart);
        $this->assertEmpty($newCart->cartItems);
    }

    /**
     * @return void
     *
     * @test
     */
    public function submit_cart_throws_an_exception_if_cart_not_found()
    {
        $this->signIn();

        $this->postJson('/api/cart/submit')->assertJsonStructure(['error']);
    }
}
