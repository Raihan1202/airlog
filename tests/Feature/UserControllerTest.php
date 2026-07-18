<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Position;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser()
    {
        return User::factory()->create(['access_level' => '2']); // Admin
    }

    private function createRegularUser()
    {
        return User::factory()->create(['access_level' => '0']); // User Biasa
    }

    /**
     * Test 1: Halaman Index User bisa dibuka oleh Admin
     */
    public function test_admin_can_view_users_list(): void
    {
        $admin = $this->createAdminUser();
        
        // Buat dummy user lain
        User::factory()->create(['name' => 'Karyawan A']);

        $response = $this
            ->actingAs($admin)
            ->get(route('users.index')); // /manage/users

        $response->assertOk();
        $response->assertViewIs('users.index');
        $response->assertSee('Karyawan A');
    }

    /**
     * Test 2: User Biasa DITOLAK akses halaman User
     */
    public function test_regular_user_cannot_view_users_list(): void
    {
        $user = $this->createRegularUser();

        $response = $this
            ->actingAs($user)
            ->get(route('users.index'));

        // Controller menggunakan abort(403)
        $response->assertStatus(403); 
    }

    /**
     * Test 3: Admin BISA membuat User Baru
     */
    public function test_admin_can_create_user(): void
    {
        $admin = $this->createAdminUser();
        
        // Kita butuh posisi valid untuk foreign key
        // Karena migration Position Anda primary key-nya 'no', kita buat manual
        // atau pakai factory jika sudah disesuaikan
        $position = \App\Models\Position::create(['name' => 'Teknisi Senior']);
        
        // Data input sesuai validasi di store() method UserController
        $userData = [
            'modalAddressFirstName' => 'Budi',
            'modalAddressLastName'  => 'Santoso',
            'modalGelar'            => 'S.T.',
            'modalUsername'         => 'budisantoso',
            'modalAddressEmail'     => 'budi@example.com',
            'position'              => $position->no, // Ambil ID dari kolom 'no'
            'modalAddressCountry'   => 'Indonesia',
            'modalAddressAddress1'  => 'Jl. Merdeka No. 1',
            'modalAddressAddress2'  => '',
            'modalPhoneNumber'      => '08123456789',
            'modalAddressCity'      => 'Jakarta',
            'modalAddressState'     => 'DKI Jakarta',
            'modalAddressZipCode'   => '10110',
            'signature'             => 'base64stringdummy', // Dummy signature
            'customRadioIcon-01'    => '0', // Access Level 0
            'technician'            => 'on', // Checkbox technician
        ];

        // Karena method store() mengembalikan JSON
        $response = $this
            ->actingAs($admin)
            ->postJson(route('users.store'), $userData);

        $response->assertOk();
        $response->assertJson(['success' => true, 'message' => 'User created successfully.']);

        $this->assertDatabaseHas('users', [
            'name' => 'budisantoso',
            'email' => 'budi@example.com',
            'fullname' => 'Budi Santoso',
            'access_level' => '0',
            'technician' => 1,
        ]);
    }

    /**
     * Test 4: Admin BISA Edit User
     */
    public function test_admin_can_update_user(): void
    {
        $admin = $this->createAdminUser();
        $position = \App\Models\Position::create(['name' => 'Staff IT']);
        
        $userToEdit = User::factory()->create([
            'name' => 'oldname',
            'email' => 'old@example.com',
            'fullname' => 'Old Name',
            'position' => 'Staff IT' // String di model, tapi controller minta ID posisi
        ]);

        $updateData = [
            'editFirstName'   => 'New',
            'editLastName'    => 'Name',
            'editGelar'       => 'M.Kom',
            'editUsername'    => 'newname',
            'editEmail'       => 'new@example.com',
            'position'        => $position->no,
            'editCountry'     => 'Singapore',
            'editAddress1'    => 'Orchard Road',
            'editAddress2'    => '',
            'editPhoneNumber' => '08987654321',
            'editCity'        => 'Singapore',
            'editState'       => 'SG',
            'editZipCode'     => '55555',
            'editSignature'   => 'newsignature',
            'editRadioIcon-01'=> '1', // Ubah jadi level 1
            // Technician tidak dicentang -> jadi 0
        ];

        $response = $this
            ->actingAs($admin)
            ->putJson(route('users.update', $userToEdit->id), $updateData);

        $response->assertOk();
        $response->assertJson(['success' => true, 'message' => 'User updated successfully']);

        $this->assertDatabaseHas('users', [
            'id' => $userToEdit->id,
            'name' => 'newname',
            'fullname' => 'New Name',
            'email' => 'new@example.com',
            'access_level' => '1',
            'country' => 'Singapore',
        ]);
    }

    /**
     * Test 5: Admin BISA Hapus User Lain
     */
    public function test_admin_can_delete_user(): void
    {
        $admin = $this->createAdminUser();
        $userToDelete = User::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->deleteJson(route('users.destroy', $userToDelete->id));

        $response->assertOk();
        $response->assertJson(['success' => true, 'message' => 'User berhasil dihapus']);

        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }

    /**
     * Test 6: Admin TIDAK BISA Hapus Diri Sendiri
     */
    public function test_admin_cannot_delete_self(): void
    {
        $admin = $this->createAdminUser();

        $response = $this
            ->actingAs($admin)
            ->deleteJson(route('users.destroy', $admin->id));

        $response->assertStatus(403);
        $response->assertJson(['success' => false, 'message' => 'You cannot delete yourself.']);

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    /**
     * Test 7: Validasi Input (Username Duplikat)
     */
    public function test_create_user_validation_duplicate_username(): void
    {
        $admin = $this->createAdminUser();
        $position = \App\Models\Position::create(['name' => 'Teknisi']);
        
        // Buat user existing
        User::factory()->create(['name' => 'existinguser']);

        $userData = [
            'modalAddressFirstName' => 'Budi',
            'modalAddressLastName'  => 'Santoso',
            'modalUsername'         => 'existinguser', // Duplikat
            'modalAddressEmail'     => 'baru@example.com',
            'position'              => $position->no,
            'modalAddressCountry'   => 'ID',
            'modalAddressAddress1'  => 'Alamat',
            'modalPhoneNumber'      => '08123',
            'modalAddressCity'      => 'Kota',
            'modalAddressState'     => 'Provinsi',
            'modalAddressZipCode'   => '12345',
            'signature'             => 'sig', 
            'customRadioIcon-01'    => '0',
        ];

        $response = $this
            ->actingAs($admin)
            ->postJson(route('users.store'), $userData);

        $response->assertStatus(422); // Unprocessable Entity (Validation Error)
        $response->assertJsonFragment(['message' => 'Username is already taken.']);
    }
}