<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegistrationFormTest extends TestCase
{
    public function test_guest_can_view_the_registration_form(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Crea tu cuenta')
            ->assertSee('name="password"', false);
    }
}
