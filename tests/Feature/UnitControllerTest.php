<?php

namespace Tests\Feature;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnitControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createRegularUser()
    {
        return User::factory()->create([
            'access_level' => 1,
        ]);
    }

    private function createAdminUser()
    {
        return User::factory()->create([
            'access_level' => 2,
        ]);
    }

    public function test_unit_page_can_be_rendered(): void
    {
        $user = $this->createRegularUser();
        
        Unit::create(['nama' => 'Unit A']);
        Unit::create(['nama' => 'Unit B']);

        $response = $this
            ->actingAs($user)
            ->get(route('units.index'));

        $response->assertOk();
        $response->assertViewIs('manage.units.index');
        $response->assertSee('Unit A');
    }

    public function test_admin_can_create_unit(): void
    {
        $admin = $this->createAdminUser();

        $response = $this
            ->actingAs($admin)
            ->post(route('units.store'), [
                'nama' => 'Unit Baru',
            ]);

        $response->assertRedirect();
        
        $response->assertSessionHas('successMessage', 'Unit berhasil dibuat!');

        $this->assertDatabaseHas('units', [
            'nama' => 'Unit Baru',
        ]);
    }

    public function test_regular_user_cannot_create_unit(): void
    {
        $user = $this->createRegularUser();

        $response = $this
            ->actingAs($user)
            ->post(route('units.store'), [
                'nama' => 'Unit Ilegal',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('errorMessage', 'Hanya Admin yang bisa menambah unit.');

        $this->assertDatabaseMissing('units', [
            'nama' => 'Unit Ilegal',
        ]);
    }

    public function test_admin_can_update_unit(): void
    {
        $admin = $this->createAdminUser();
        $unit = Unit::create(['nama' => 'Nama Lama']);

        $response = $this
            ->actingAs($admin)
            ->put(route('units.update', $unit->id), [
                'nama' => 'Nama Baru',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('successMessage', 'Unit berhasil diperbarui');

        $this->assertDatabaseHas('units', [
            'id' => $unit->id,
            'nama' => 'Nama Baru',
        ]);
    }

    public function test_admin_can_delete_unit(): void
    {
        $admin = $this->createAdminUser();
        $unit = Unit::create(['nama' => 'Unit Dihapus']);

        $response = $this
            ->actingAs($admin)
            ->delete(route('units.destroy', $unit->id));

        $response->assertRedirect();
        $response->assertSessionHas('successMessage', 'Unit berhasil dihapus');

        $this->assertDatabaseMissing('units', [
            'id' => $unit->id,
        ]);
    }

    public function test_create_unit_validation_error(): void
    {
        $admin = $this->createAdminUser();

        $response = $this
            ->actingAs($admin)
            ->from(route('units.index'))
            ->post(route('units.store'), [
                'nama' => '',
            ]);

        $response->assertRedirect(route('units.index'));
        $response->assertSessionHas('errorMessage', 'Nama unit wajib diisi.');
    }
}