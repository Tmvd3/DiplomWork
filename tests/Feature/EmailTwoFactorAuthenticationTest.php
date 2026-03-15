<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\EmailTwoFactorCodeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailTwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_requires_email_two_factor_confirmation(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'password' => bcrypt('Password1'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'Password1',
            'remember' => '1',
        ]);

        $response->assertRedirect(route('two-factor.challenge'));
        $this->assertGuest();
        $this->assertSame($user->id, session('auth.two_factor.user_id'));
        $this->assertTrue((bool) session('auth.two_factor.remember'));

        $code = null;

        Notification::assertSentTo(
            $user,
            EmailTwoFactorCodeNotification::class,
            function (EmailTwoFactorCodeNotification $notification) use (&$code) {
                $code = $notification->code;

                return true;
            }
        );

        $this->post(route('two-factor.verify'), [
            'code' => $code,
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
        $this->assertNull($user->fresh()->email_two_factor_code);
        $this->assertNull(session('auth.two_factor.user_id'));
    }

    public function test_register_starts_two_factor_flow_without_immediate_login(): void
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'new-user@example.com',
            'password' => 'Password1',
            'password_confirmation' => 'Password1',
        ]);

        $user = User::where('email', 'new-user@example.com')->first();

        $response->assertRedirect(route('two-factor.challenge'));
        $this->assertNotNull($user);
        $this->assertGuest();
        $this->assertSame($user?->id, session('auth.two_factor.user_id'));

        Notification::assertSentTo($user, EmailTwoFactorCodeNotification::class);
    }
}
