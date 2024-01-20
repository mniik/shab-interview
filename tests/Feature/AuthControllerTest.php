<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions,WithFaker;

    /**
     * A register user feature test.
     *
     * @test
     */
    public function auth_register(): void
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password(6, 24),
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['status', 'data', 'message'])
            ->assertJsonPath('data.email', $userData['email']);
    }

    /**
     * A failed validation register feature test.
     *
     * @test
     */
    public function auth_register_failed_validation(): void
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password(2, 2),
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['message', 'errors']);
    }

    /**
     * A Login user feature test.
     *
     * @test
     */
    public function auth_login(): void
    {
        $fakePassword = $this->faker->password(6);

        $user = User::factory()->create(['password' => $fakePassword]);
        $userData = [
            'email' => $user->email,
            'password' => $fakePassword,
        ];

        $response = $this->postJson('/api/login', $userData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['status', 'data', 'message']);
    }

    /**
     * A Login failed to find user feature test.
     *
     * @test
     */
    public function auth_login_finding_user_failed(): void
    {
        $userData = [
            'email' => $this->faker->email,
            'password' => $this->faker->password(6),
        ];

        $response = $this->postJson('/api/login', $userData);

        $response->assertJsonStructure(['error'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * A Login failed to match user password feature test.
     *
     * @test
     */
    public function auth_login_password_check_failed(): void
    {
        $fakePassword = $this->faker->password(6);

        $user = User::factory()->create(['password' => $fakePassword]);

        $userData = [
            'email' => $user->email,
            'password' => $this->faker->password(6),
        ];

        $response = $this->postJson('/api/login', $userData);

        $response->assertJsonStructure(['error'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
