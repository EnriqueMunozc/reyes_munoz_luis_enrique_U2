<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginFormTest extends TestCase
{
    public function test_guest_can_view_the_login_form(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Iniciar sesion')
            ->assertSee('name="password"', false);
    }
}
