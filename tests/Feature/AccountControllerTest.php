<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createUserWithPosition()
    {
        return User::factory()->create([
            'position' => 'Staff', 
            'joined' => now(),
            'fullname' => 'Old Fullname',
            'email' => 'original@example.com', 
        ]);
    }

    public function test_account_settings_page_is_displayed(): void
    {
        $this->withoutExceptionHandling(); 
        $user = $this->createUserWithPosition();

        $response = $this->actingAs($user)->get(route('account.settings'));

        $response->assertOk();
        $response->assertViewIs('account.settings');
    }

    public function test_account_details_can_be_updated(): void
    {
        $user = $this->createUserWithPosition();
		
        $newData = [
            'fullname' => 'Updated Fullname',
            'phone_number' => '08123456789',
            'address' => 'Jl. Baru No 1',
            'city' => 'Jakarta',
            'state' => 'DKI',
            'zip_code' => '12345',
            'country' => 'Indonesia',
        ];

        $response = $this
            ->actingAs($user)
            ->from(route('account.settings'))
            ->patch(route('account.update.details'), $newData);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('account.settings'))
            ->assertSessionHas('successMessage', 'Account details updated successfully!');

        $user->refresh();

        $this->assertSame('Updated Fullname', $user->fullname);
        $this->assertSame('08123456789', $user->phone_number);
        $this->assertSame('Jakarta', $user->city);

        $this->assertSame('original@example.com', $user->email);
    }
}