<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_show_page_can_be_rendered(): void
    {
        $user = User::factory()->create([
            'name' => 'testuser',
            'joined' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('profile.show', $user->name));

        $response->assertOk();
        $response->assertViewIs('profile.show');
        $response->assertViewHas(['user', 'notifications', 'unreadCount']);
    }

    public function test_profile_notifications_page_can_be_rendered(): void
    {
        $user = User::factory()->create([
            'name' => 'testuser',
            'joined' => now(),
        ]);

        $author = User::factory()->create();
        $notification = Notification::factory()->create([
            'author_id' => $author->id,
            'title' => 'Test Notification',
            'created_at' => now(),
        ]);
        
        $user->notifications()->attach($notification->id, ['status' => 0]);

        $response = $this
            ->actingAs($user)
            ->get(route('profile.notifications', $user->name));

        $response->assertOk();
        $response->assertViewIs('profile.notifications');
        $response->assertViewHas('allNotifications'); 
        $response->assertSee('Test Notification');
    }

    public function test_generate_qr_code_redirects_correctly(): void
    {
        $user = User::factory()->create(['name' => 'qruser']);

        $response = $this
            ->actingAs($user)
            ->get(route('profile.qr', $user->name));

        $response->assertRedirect();
        
        $targetUrl = $response->headers->get('Location');
        $this->assertStringContainsString('api.qrserver.com', $targetUrl);
    }
}