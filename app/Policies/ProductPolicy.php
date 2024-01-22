<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->id === $product->user_id;
    }

    /**
     * Determine whether the user can upload media for the model.
     */
    public function addMedia(User $user, Product $product): bool
    {
        return $user->id === $product->user_id;
    }
}
