<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Tests\TestCase;

class AdministrationAccessTest extends TestCase
{
    public function test_guest_is_redirected_to_login_from_administration(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_client_cannot_access_administration(): void
    {
        $this->actingAs($this->userWithRole(UserRole::Client))
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_administrator_can_access_administration(): void
    {
        $this->actingAs($this->userWithRole(UserRole::Administrator))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Panel administrativo')
            ->assertSee('Usuario de prueba')
            ->assertSee('Administracion')
            ->assertSee('Cerrar sesion');
    }

    private function userWithRole(UserRole $role): User
    {
        $user = new User([
            'name' => 'Usuario de prueba',
            'email' => 'prueba@example.test',
            'password' => 'password',
        ]);

        $user->role = $role;

        return $user;
    }
}
