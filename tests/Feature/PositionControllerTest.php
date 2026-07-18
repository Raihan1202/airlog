<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Position;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PositionControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin()
    {
        return User::factory()->create([
            'access_level' => 2,
            'name' => 'admin',
        ]);
    }

    private function createStaff()
    {
        return User::factory()->create([
            'access_level' => 1,
            'name' => 'staff',
        ]);
    }

    public function test_position_index_can_be_rendered(): void
    {
        $user = $this->createStaff();
        Position::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('position.index')); 

        $response->assertOk();
        $response->assertViewIs('positions.index');
        $response->assertViewHas('positions');
    }

    public function test_admin_can_create_new_position(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('positions.update'), [
            'position_id' => 0,
            'position_name' => 'Teknisi Senior',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('successMessage', 'Posisi baru berhasil ditambahkan.');

        $this->assertDatabaseHas('positions', [
            'name' => 'Teknisi Senior',
        ]);
    }

    public function test_admin_can_update_existing_position(): void
    {
        $admin = $this->createAdmin();
        $position = Position::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($admin)->post(route('positions.update'), [
            'position_id' => $position->no, 
            'position_name' => 'New Name',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('successMessage', 'Posisi berhasil diperbarui.');

        $this->assertDatabaseHas('positions', [
            'no' => $position->no,
            'name' => 'New Name',
        ]);
    }

    public function test_admin_can_delete_position(): void
    {
        $admin = $this->createAdmin();
        $position = Position::factory()->create();

        $response = $this->actingAs($admin)->post(route('positions.delete'), [
            'position_id' => $position->no,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('successMessage', 'Posisi berhasil dihapus');

        $this->assertDatabaseMissing('positions', ['no' => $position->no]);
    }

    public function test_non_admin_cannot_manage_positions(): void
    {
        $staff = $this->createStaff(); 

        $response = $this->actingAs($staff)->post(route('positions.update'), [
            'position_id' => 0,
            'position_name' => 'Hacker Position',
        ]);

        $response->assertRedirect(); 
        $response->assertSessionHas('errorMessage', 'Hanya admin yang boleh mengubah jabatan.');
        
        $this->assertDatabaseMissing('positions', ['name' => 'Hacker Position']);
    }
}