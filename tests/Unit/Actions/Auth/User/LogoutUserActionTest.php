<?php

namespace Tests\Unit\Actions\Auth\User;

use App\Actions\Auth\User\LogoutUserAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutUserActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_successfully_logs_out_an_authenticated_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->assertAuthenticatedAs($user);

        $action = LogoutUserAction::run();

        $this->assertTrue($action->successful());
        $this->assertGuest();
    }
}
