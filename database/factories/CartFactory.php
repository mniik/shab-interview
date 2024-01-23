<?php

namespace Database\Factories;

use App\Enums\CartState;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'user_id' => $user->id,
            'is_active' => $this->faker->randomElement([0, 1]),
        ];
    }

    /**
     * Indicate the Active Cart.
     */
    public function active(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => CartState::Active,
            ];
        });
    }
}
