<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_page_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('account.security'));

        $response->assertOk();
        $response->assertViewIs('account.security');
    }

    public function test_password_can_be_updated(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('PasswordLama123!'),
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('account.update.password'), [
                'current_password' => 'PasswordLama123!',
                'password' => 'PasswordBaru123!',
                'password_confirmation' => 'PasswordBaru123!',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('successMessage', 'Password updated successfully!');

        $user->refresh();
        $this->assertTrue(Hash::check('PasswordBaru123!', $user->password));
    }

    public function test_password_update_fails_if_current_password_incorrect(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('PasswordAsli123!'),
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('account.update.password'), [
                'current_password' => 'SalahPassword!!!',
                'password' => 'PasswordBaru123!',
                'password_confirmation' => 'PasswordBaru123!',
            ]);

        $response->assertSessionHasErrors(['current_password']);
        
        $user->refresh();
        $this->assertTrue(Hash::check('PasswordAsli123!', $user->password));
    }
}