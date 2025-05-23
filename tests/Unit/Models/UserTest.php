<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_it_has_the_expected_fillable_attributes(): void
    {
        $user = new User();

        $this->assertSame(
            ['name', 'email', 'password'],
            $user->getFillable(),
            'The $fillable property should contain exactly name, email and password'
        );
    }

    public function test_it_has_the_expected_hidden_attributes(): void
    {
        $user = new User();

        $this->assertSame(
            ['password', 'remember_token'],
            $user->getHidden(),
            'The $hidden property should hide password and remember_token'
        );
    }

    public function test_it_returns_the_expected_cast_configuration(): void
    {
        $user = new User();
        $casts = $user->getCasts();   // triggers the protected casts() method internally

        $this->assertSame('datetime', $casts['email_verified_at'] ?? null);
        $this->assertSame('hashed', $casts['password'] ?? null);
    }

    public function test_it_casts_email_verified_at_to_a_carbon_instance(): void
    {
        /** @var User $user */
        $user = User::factory()->make([
            'email_verified_at' => '2025-05-23 12:34:56',
        ]);

        $this->assertInstanceOf(Carbon::class, $user->email_verified_at);
        $this->assertTrue($user->email_verified_at->eq(Carbon::parse('2025-05-23 12:34:56')));
    }

    public function test_it_hashes_the_password_automatically(): void
    {
        /** @var User $user */
        $user = User::factory()->make(['password' => 'plain-text']);

        $this->assertTrue(Hash::check('plain-text', $user->password));
        $this->assertNotSame('plain-text', $user->password, 'Password must not be stored in plain text');
    }

    public function test_it_hides_sensitive_attributes_when_serialized(): void
    {
        /** @var User $user */
        $user = User::factory()->make();

        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }
}
