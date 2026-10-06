<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
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

    public function test_customer_can_register_and_is_authenticated(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Nami Navegante',
            'email' => 'nami@example.test',
            'password' => 'contrasena-segura',
            'password_confirmation' => 'contrasena-segura',
        ]);

        $response->assertRedirect(route('catalog.index'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'nami@example.test',
            'role' => UserRole::Client->value,
        ]);
    }

    public function test_registration_rejects_invalid_data(): void
    {
        $this->from(route('register'))
            ->post(route('register.store'), [
                'name' => '',
                'email' => 'correo-invalido',
                'password' => 'corta',
                'password_confirmation' => 'distinta',
            ])
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_registration_rejects_an_existing_email(): void
    {
        User::factory()->create(['email' => 'existente@example.test']);

        $this->from(route('register'))
            ->post(route('register.store'), [
                'name' => 'Robin Arqueologa',
                'email' => 'existente@example.test',
                'password' => 'contrasena-segura',
                'password_confirmation' => 'contrasena-segura',
            ])
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors('email');
    }

    public function test_registration_rejects_role_submitted_by_browser(): void
    {
        $this->from(route('register'))
            ->post(route('register.store'), [
                'name' => 'Usuario Malicioso',
                'email' => 'malicioso@example.test',
                'password' => 'contrasena-segura',
                'password_confirmation' => 'contrasena-segura',
                'role' => UserRole::Administrator->value,
            ])
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors('role');

        $this->assertDatabaseCount('users', 0);
    }
}
