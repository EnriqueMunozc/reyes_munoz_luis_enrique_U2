<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase {
        refreshTestDatabase as private runRefreshTestDatabase;
    }

    protected function refreshTestDatabase(): void
    {
        $this->assertSame('sqlsrv', config('database.default'));
        $this->assertSame('redline_test', config('database.connections.sqlsrv.database'));

        $this->runRefreshTestDatabase();
    }

    public function test_client_can_log_in_and_is_sent_to_catalog(): void
    {
        $user = $this->userWithRole(UserRole::Client);

        $this->post(route('login.store'), $this->credentialsFor($user))
            ->assertRedirect(route('catalog.index'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_administrator_can_log_in_and_is_sent_to_administration(): void
    {
        $user = $this->userWithRole(UserRole::Administrator);

        $this->post(route('login.store'), $this->credentialsFor($user))
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_receive_a_generic_error(): void
    {
        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => 'desconocido@example.test',
                'password' => 'contrasena-invalida',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_client_cannot_use_administration_as_intended_destination(): void
    {
        $user = $this->userWithRole(UserRole::Client);

        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));

        $this->post(route('login.store'), $this->credentialsFor($user))
            ->assertRedirect(route('catalog.index'));
    }

    public function test_authenticated_user_can_log_out(): void
    {
        $user = $this->userWithRole(UserRole::Client);

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }

    private function userWithRole(UserRole $role): User
    {
        return User::factory()->create([
            'password' => Hash::make('contrasena-segura'),
            'role' => $role,
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function credentialsFor(User $user): array
    {
        return [
            'email' => $user->email,
            'password' => 'contrasena-segura',
        ];
    }
}
