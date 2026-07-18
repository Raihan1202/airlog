<?php

namespace Tests\Feature;

use App\Models\Logbook;
use App\Models\LogbookItem;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogbookItemControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_item_to_logbook(): void
    {
        $user = User::factory()->create();
        $unit = Unit::factory()->create();
        
        $logbook = Logbook::factory()->create([
            'unit_id' => $unit->id,
            'created_by' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('logbook.item.store', ['unit_id' => $unit->id, 'logbook_id' => $logbook->id]), [
                'catatan' => 'Pengecekan Rutin',
                'tanggal_kegiatan' => '2025-12-20',
                'tools' => 'Multitester',
                'teknisi' => $user->id,
                'mulai' => '2025-12-20 08:00:00',
                'selesai' => '2025-12-20 10:00:00',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('successMessage', 'Item logbook berhasil ditambahkan!');

        $this->assertDatabaseHas('logbook_items', [
            'logbook_id' => $logbook->id,
            'catatan' => 'Pengecekan Rutin',
            'tools' => 'Multitester',
        ]);
    }

    public function test_cannot_add_more_than_10_items(): void
    {
        $user = User::factory()->create();
        $unit = Unit::factory()->create();
        
        $logbook = Logbook::factory()->create([
            'unit_id' => $unit->id,
            'created_by' => $user->id,
        ]);

        LogbookItem::factory()->count(10)->create([
            'logbook_id' => $logbook->id,
            'teknisi' => $user->id 
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('logbook.item.store', ['unit_id' => $unit->id, 'logbook_id' => $logbook->id]), [
                'catatan' => 'Item Kesebelas',
                'tanggal_kegiatan' => '2025-12-20',
                'tools' => 'Obeng',
                'teknisi' => $user->id,
                'mulai' => '2025-12-20 10:00:00',
                'selesai' => '2025-12-20 11:00:00',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('errorMessage', 'Maksimal 10 content per logbook sudah tercapai!');
        
        $this->assertEquals(10, LogbookItem::where('logbook_id', $logbook->id)->count());
    }

    public function test_user_can_update_logbook_item(): void
    {
        $user = User::factory()->create();
        $unit = Unit::factory()->create();
        
        $logbook = Logbook::factory()->create([
            'unit_id' => $unit->id,
            'created_by' => $user->id,
        ]);

        $item = LogbookItem::factory()->create([
            'logbook_id' => $logbook->id,
            'catatan' => 'Catatan Lama',
            'teknisi' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('logbook.item.update', ['unit_id' => $unit->id, 'logbook_id' => $logbook->id, 'item_id' => $item->id]), [
                'catatan' => 'Catatan Revisi',
                'tanggal_kegiatan' => '2025-12-21',
                'tools' => 'Tang',
                'teknisi' => $user->id,
                'mulai' => '2025-12-21 09:00:00',
                'selesai' => '2025-12-21 10:00:00',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('successMessage', 'Item logbook berhasil diperbarui!');

        $this->assertDatabaseHas('logbook_items', [
            'id' => $item->id,
            'catatan' => 'Catatan Revisi',
        ]);
    }

    public function test_user_can_delete_own_item(): void
    {
        $user = User::factory()->create();
        $unit = Unit::factory()->create();
        
        $logbook = Logbook::factory()->create([
            'unit_id' => $unit->id,
            'created_by' => $user->id,
        ]);

        $item = LogbookItem::factory()->create([
            'logbook_id' => $logbook->id,
            'teknisi' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->deleteJson(route('logbook.item.destroy', ['unit_id' => $unit->id, 'logbook_id' => $logbook->id, 'item_id' => $item->id]));

        $response->assertOk();
        $response->assertJson(['success' => true, 'message' => 'Item berhasil dihapus!']);

        $this->assertDatabaseMissing('logbook_items', ['id' => $item->id]);
    }
}