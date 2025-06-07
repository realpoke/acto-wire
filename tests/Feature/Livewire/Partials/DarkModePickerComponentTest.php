<?php

namespace Tests\Feature\Livewire\Partials;

use App\Livewire\Partials\DarkModePickerComponent;
use Livewire\Livewire;
use Tests\TestCase;

class DarkModePickerComponentTest extends TestCase
{
    public function test_renders_successfully(): void
    {
        Livewire::test(DarkModePickerComponent::class)
            ->assertStatus(200);
    }
}
