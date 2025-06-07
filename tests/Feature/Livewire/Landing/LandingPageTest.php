<?php

namespace Tests\Feature\Livewire\Landing;

use App\Livewire\Landing\LandingPage;
use Livewire\Livewire;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    public function test_renders_successfully(): void
    {
        Livewire::test(LandingPage::class)
            ->assertStatus(200);
    }
}
