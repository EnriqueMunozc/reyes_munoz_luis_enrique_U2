<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationTest extends TestCase
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

    public function test_client_sees_name_and_logout_without_administration_link(): void
    {
        $client = User::factory()->create([
            'name' => 'Cliente de prueba',
            'role' => UserRole::Client,
        ]);

        $this->actingAs($client)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Cliente de prueba')
            ->assertSee('Cerrar sesion')
            ->assertDontSee('>Administracion<', false);
    }

    public function test_administrator_sees_administration_link(): void
    {
        $administrator = User::factory()->create([
            'name' => 'Admin de prueba',
            'role' => UserRole::Administrator,
        ]);

        $this->actingAs($administrator)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Admin de prueba')
            ->assertSee('>Administracion<', false)
            ->assertSee('Cerrar sesion');
    }
}
