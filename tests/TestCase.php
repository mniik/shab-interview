<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $user;

    /**
     * use this method to signIn in test cases
     *
     * @return $this
     */
    public function signIn(?User $user = null)
    {
        if (! $user) {
            $user = User::factory()->create();
        }

        $this->user = $user;

        $this->actingAs($this->user);

        return $this;
    }
}
