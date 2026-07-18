<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createNotificationForUser($user)
    {
        $author = User::factory()->create();

        $notification = Notification::factory()->create([
            'author_id' => $author->id,
            'title' => 'Test Notification',
            'body' => 'This is a test body',
        ]);
        
        $user->notifications()->attach($notification->id, ['status' => 0]);

        return $notification;
    }

    public function test_profile_notification_page_can_be_rendered(): void
    {
        $user = User::factory()->create([
            'name' => 'testuser',
            'joined' => now(), 
        ]);
        
        $author = User::factory()->create();
        
        $notification = Notification::factory()->create([
            'author_id' => $author->id,
            'title' => 'Test Notif',
            'body' => 'Body Notif',
            'created_at' => now(),
        ]);
        
        $user->notifications()->attach($notification->id, ['status' => 0]);

        $response = $this
            ->actingAs($user)
            ->get(route('profile.notifications', $user->name));

        $response->assertOk();
        $response->assertViewIs('profile.notifications');
        $response->assertSee('Test Notif');
    }

    public function test_user_can_mark_notification_as_read(): void
    {
        $user = User::factory()->create();
        $notification = $this->createNotificationForUser($user);

        $response = $this
            ->actingAs($user)
            ->get(route('notifications.read', $notification->id));

        $response->assertRedirect(); 
        
        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $user->id,
            'notification_id' => $notification->id,
            'status' => 1,
        ]);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $user = User::factory()->create();
        $this->createNotificationForUser($user);
        $this->createNotificationForUser($user);

        $response = $this
            ->actingAs($user)
            ->get(route('notifications.markAll'));

        $response->assertRedirect();
        
        $this->assertDatabaseMissing('user_notifications', [
            'user_id' => $user->id,
            'status' => 0,
        ]);
    }

    public function test_user_can_delete_notification(): void
    {
        $user = User::factory()->create();
        $notification = Notification::factory()->create(['author_id' => $user->id]);

        $response = $this
            ->actingAs($user)
            ->delete(route('notifications.destroy', $notification->id));

        $response->assertRedirect();
        $response->assertSessionHas('successMessage', 'Notifikasi berhasil dihapus.');

        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }
}