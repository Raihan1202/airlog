<?php

namespace Tests\Feature;

use App\Models\Tool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToolControllerTest extends TestCase
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

    public function test_tool_page_can_be_rendered(): void
    {
        $user = $this->createRegularUser();
        Tool::create(['name' => 'Obeng']);

        $response = $this
            ->actingAs($user)
            ->get(route('tools.index'));

        $response->assertOk();
        $response->assertSee('Obeng');
    }

    public function test_admin_can_create_new_tool(): void
    {
        $admin = $this->createAdminUser();

        $response = $this
            ->actingAs($admin)
            ->post('/manage/tools/update', [
                'peralatan_id' => 0,
                'tools_name' => 'Tang Kombinasi',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('successMessage', 'Peralatan baru berhasil ditambahkan');

        $this->assertDatabaseHas('alat', [
            'name' => 'Tang Kombinasi',
        ]);
    }

    public function test_admin_can_update_existing_tool(): void
    {
        $admin = $this->createAdminUser();
        $tool = Tool::create(['name' => 'Nama Lama']);

        $response = $this
            ->actingAs($admin)
            ->post('/manage/tools/update', [
                'peralatan_id' => $tool->id,
                'tools_name' => 'Nama Baru',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('successMessage', 'Peralatan berhasil diperbarui');

        $this->assertDatabaseHas('alat', [
            'id' => $tool->id,
            'name' => 'Nama Baru',
        ]);
    }

    public function test_regular_user_cannot_manage_tools(): void
    {
        $user = $this->createRegularUser();

        $response = $this
            ->actingAs($user)
            ->post('/manage/tools/update', [
                'peralatan_id' => 0,
                'tools_name' => 'Alat Ilegal',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('errorMessage', 'Anda tidak memiliki akses admin.');
        
        $this->assertDatabaseMissing('alat', ['name' => 'Alat Ilegal']);
    }

    public function test_admin_can_delete_tool(): void
    {
        $admin = $this->createAdminUser();
        $tool = Tool::create(['name' => 'Alat Rusak']);

        $response = $this
            ->actingAs($admin)
            ->post('/manage/tools/delete', [
                'peralatan_id' => $tool->id,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('successMessage', 'Peralatan berhasil dihapus');

        $this->assertDatabaseMissing('alat', ['id' => $tool->id]);
    }

    public function test_tool_validation_error(): void
    {
        $admin = $this->createAdminUser();

        $response = $this
            ->actingAs($admin)
            ->post('/manage/tools/update', [
                'peralatan_id' => 0,
                'tools_name' => '',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('errorMessage'); 
    }
}