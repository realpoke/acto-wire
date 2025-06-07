<?php

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\LoginPage;
use Livewire\Livewire;
use Tests\TestCase;

class LoginPageTest extends TestCase
{
    public function test_renders_successfully(): void
    {
        Livewire::test(LoginPage::class)
            ->assertStatus(200);
    }
}
