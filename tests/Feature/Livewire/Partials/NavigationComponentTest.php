<?php

namespace Tests\Feature\Livewire\Partials;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NavigationComponentTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_correctly_for_guests(): void
    {
        Livewire::test('partials.navigation-component')->assertSuccessful();
    }

    public function test_renders_correctly_for_authenticated_users(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test('partials.navigation-component')
            ->assertSet('user.id', $user->id)
            ->assertSee($user->name);
    }

    public function test_logout_action_invalidates_session_and_redirects(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test('partials.navigation-component')
            ->call('logout')
            ->assertRedirect(route('landing.page'));

        $this->assertGuest();
    }
}
