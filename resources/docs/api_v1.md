# 1. Authentication

## [POST] /login (Public)
> Masuk ke sistem dan dapatkan token.
Silakan masukkan email dan password untuk mendapatkan Bearer Token. Token ini diperlukan untuk mengakses endpoint yang dilindungi.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| login | text | yes | Email atau Username |
| password | password | yes | Password akun |

## [POST] /logout
> Keluar dari sesi (Revoke Token).
Menghapus token akses saat ini dari server.

## [GET] /profile
> Cek Profil User
Mendapatkan detail data diri user yang sedang login.

## [PUT] /profile
> Update data profil
Memperbarui data diri dasar pengguna seperti nama, alamat, dan kontak.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| fullname | text | yes | Nama Lengkap |
| phone_number | text | no | Nomor HP |
| address | text | no | Alamat Lengkap |
| city | text | no | Kota |
| state | text | no | Provinsi/State |
| zip_code | text | no | Kode Pos |
| country | text | no | Negara |
| signature | text | no | Tanda Tangan (Base64 String) |

## [POST] /change-password
> Ganti Password
Mengubah password user saat ini dengan yang baru.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| current_password | password | yes | Password saat ini |
| new_password | password | yes | Password baru (min 8 karakter) |
| new_password_confirmation | password | yes | Konfirmasi password baru |

# 2. Tools (Peralatan)

## [GET] /tools
> List Semua Alat
Mendapatkan daftar semua peralatan yang tersedia.

## [POST] /tools/save
> Tambah/Edit Alat
Membuat alat baru (jika ID=0) atau mengupdate alat yang sudah ada (jika ID>0).

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| tools_name | text | yes | Nama Peralatan |
| peralatan_id | number | yes | ID Alat (0 untuk baru) |

## [POST] /tools/delete
> Hapus Alat
Menghapus data peralatan berdasarkan ID.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| peralatan_id | number | yes | ID Alat yang akan dihapus |

# 3. Positions (Jabatan)

## [GET] /positions
> List Jabatan
Mendapatkan daftar posisi/jabatan pegawai.

## [POST] /positions/save
> Tambah/Edit Jabatan
Membuat jabatan baru (ID=0) atau mengupdate jabatan lama (ID>0).

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| position_name | text | yes | Nama Jabatan |
| position_id | number | yes | ID Jabatan (0 untuk baru) |

## [POST] /positions/delete
> Hapus Jabatan
Menghapus data jabatan berdasarkan ID.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| position_id | number | yes | ID Jabatan yang akan dihapus |

# 4. Units (Unit Kerja)

## [GET] /units
> List Unit Kerja
Mendapatkan daftar semua unit kerja/mesin yang terdaftar.

## [POST] /units
> Buat Unit Baru (Admin)
Menambahkan unit kerja baru ke dalam sistem. Hanya Admin yang bisa melakukan ini.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| nama | text | yes | Nama Unit Kerja |

## [PUT] /units/{id}
> Update Unit (Admin)
Mengubah nama unit kerja berdasarkan ID.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| id | url | yes | ID Unit (URL) |
| nama | text | yes | Nama Unit Baru |

## [DELETE] /units/{id}
> Hapus Unit (Admin)
Menghapus unit kerja dari sistem.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| id | url | yes | ID Unit (URL) |

# 5. User Management

## [GET] /users
> List User
Mendapatkan daftar pengguna (dengan pagination).

## [GET] /technicians
> List Teknisi
Mendapatkan daftar pengguna khusus dengan role Teknisi.

## [POST] /users
> Buat User Baru (Admin)
Menambahkan user baru. Password default: "password123".

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| modalAddressFirstName | text | yes | Nama Depan |
| modalAddressLastName | text | yes | Nama Belakang |
| modalUsername | text | yes | Username Login |
| modalGelar | text | no | Gelar (Title) |
| position | number | yes | Posisi Jabatan |
| modalAddressEmail | text | yes | Email |
| modalPhoneNumber | text | yes | No HP |
| modalAddressCountry | text | yes | Negara |
| modalAddressAddress1 | text | yes | Alamat Baris 1 |
| modalAddressAddress2 | text | no | Alamat Baris 2 |
| modalAddressCity | text | yes | Kota |
| modalAddressState | text | yes | Provinsi |
| modalAddressZipCode | text | yes | Kode Pos |
| customRadioIcon-01 | number | yes | Access Level (0=User, 1=Staff, 2=Admin) |
| technician | text | no | Check jika Teknisi (value=1) |
| signature | text | yes | Tanda Tangan (Base64 String) |

## [PUT] /users/{id}
> Update User (Admin)
Mengubah data pengguna lain secara lengkap.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| id | url | yes | ID User (URL) |
| editFirstName | text | yes | Nama Depan |
| editLastName | text | yes | Nama Belakang |
| editUsername | text | yes | Username |
| editGelar | text | no | Gelar |
| position | number | yes | Posisi Jabatan |
| editEmail | text | yes | Email |
| editPhoneNumber | text | yes | No HP |
| editCountry | text | yes | Negara |
| editAddress1 | text | yes | Alamat 1 |
| editAddress2 | text | no | Alamat 2 |
| editCity | text | yes | Kota |
| editState | text | yes | Provinsi |
| editZipCode | text | yes | Kode Pos |
| editRadioIcon-01 | number | yes | Access Level (0,1,2) |
| editTechnician | text | no | Check jika Teknisi (value=1) |
| editSignature | text | yes | Tanda Tangan Baru (Base64) |

## [DELETE] /users/{id}
> Hapus User (Admin)
Menghapus akun pengguna dari sistem.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| id | url | yes | ID User (URL) |

# 6. Logbook System

## [GET] /logbooks
> Filter Logbooks
Mencari data logbook berdasarkan filter tertentu.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| unit_id | text | no | Filter ID Unit |
| id | text | no | Filter ID Spesifik |
| start_date | date | no | Filter Tanggal |
| shift | number | no | Filter Shift (1-3) |
| is_approved | number | no | Status (0=Pending, 1=Approved) |

## [GET] /logbooks-statistics
> Statistik Logbook
Mendapatkan ringkasan jumlah logbook per unit dan status approval.

## [POST] /units/{unit_id}/logbooks
> Buat Logbook
Membuat entri logbook baru (Header).

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| unit_id | url | yes | ID Unit (URL) |
| nameWithTitle | text | yes | Judul Logbook |
| dateWithTitle | date | yes | Tanggal (YYYY-MM-DD) |
| radio_shift | number | yes | Shift (1, 2, 3) |

## [PUT] /units/{unit_id}/logbooks/{logbook_id}
> Update Content Logbook
Mengubah judul, tanggal, atau shift logbook.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| unit_id | url | yes | ID Unit |
| logbook_id | url | yes | ID Logbook |
| nameWithTitle | text | yes | Judul Baru |
| dateWithTitle | date | yes | Tanggal Baru |
| radio_shift | number | yes | Shift Baru |

## [DELETE] /units/{unit_id}/logbooks/{logbook_id}
> Hapus Logbook
Menghapus logbook beserta seluruh catatan (items) di dalamnya.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| unit_id | url | yes | ID Unit |
| logbook_id | url | yes | ID Logbook |

## [POST] /units/{unit_id}/logbooks/{logbook_id}/approve
> Approve Logbook
Menyetujui logbook (Tanda tangan digital Admin/Staff).

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| unit_id | url | yes | ID Unit |
| logbook_id | url | yes | ID Logbook |

## [GET] /units/{unit_id}/logbooks/{logbook_id}/view
> View Logbook HTML (WebView)
Mendapatkan string HTML lengkap dari detail logbook. Digunakan untuk ditampilkan di WebView pada aplikasi mobile agar tampilan 100% sama dengan web/print preview.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| unit_id | url | yes | ID Unit |
| logbook_id | url | yes | ID Logbook |

# 7. Logbook Items (Detail)

## [GET] /units/{unit_id}/logbooks/{logbook_id}/items
> List Content Items
Mendapatkan daftar catatan kegiatan (items) dalam satu logbook.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| unit_id | url | yes | ID Unit |
| logbook_id | url | yes | ID Logbook |
| item_id | query | no | ID Item spesifik (Opsional) |

## [POST] /units/{unit_id}/logbooks/{logbook_id}/items
> Buat Item Baru
Menambahkan catatan kegiatan baru ke dalam logbook.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| unit_id | url | yes | ID Unit |
| logbook_id | url | yes | ID Logbook |
| catatan | text | yes | Isi Catatan Kegiatan |
| tools | text | yes | Alat yang digunakan |
| teknisi | text | yes | ID User Teknisi |
| tanggal_kegiatan | date | yes | Tanggal Pengerjaan |
| mulai | text | yes | Jam Mulai (HH:MM) |
| selesai | text | yes | Jam Selesai (HH:MM) |

## [PUT] /units/{unit_id}/logbooks/{logbook_id}/items/{item_id}
> Update Item
Mengubah detail catatan kegiatan.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| unit_id | url | yes | ID Unit |
| logbook_id | url | yes | ID Logbook |
| item_id | url | yes | ID Item |
| catatan | text | yes | Isi Catatan Kegiatan |
| tools | text | yes | Alat |
| teknisi | text | yes | ID Teknisi |
| tanggal_kegiatan | date | yes | Tanggal |
| mulai | text | yes | Jam Mulai |
| selesai | text | yes | Jam Selesai |

## [DELETE] /units/{unit_id}/logbooks/{logbook_id}/items/{item_id}
> Hapus Item
Menghapus satu baris catatan kegiatan dari logbook.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| unit_id | url | yes | ID Unit |
| logbook_id | url | yes | ID Logbook |
| item_id | url | yes | ID Item |

## [GET] /logbook-items/by-teknisi
> Tugas Saya (Mobile)
Mengambil daftar catatan logbook yang ditugaskan ke user yang sedang login (Teknisi).

# 8. Notifications

## [GET] /notifications
> List Notifikasi
Mendapatkan semua notifikasi milik user.

## [GET] /notifications/unread-count
> Hitung Belum Dibaca
Mendapatkan jumlah notifikasi yang belum dibaca (untuk badge).

## [POST] /notifications
> Kirim Notifikasi (Manual)
Membuat notifikasi baru untuk user tertentu.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| title | text | yes | Judul Notifikasi |
| body | text | yes | Isi Pesan |
| user_ids[] | text | yes | Array ID Penerima (cth: 1,2) |

## [GET] /notifications/{id}/read
> Baca Satu Notifikasi
Menandai notifikasi spesifik sebagai sudah dibaca.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| id | url | yes | ID Notifikasi |

## [GET] /notifications/read-all
> Baca Semua
Menandai seluruh notifikasi user sebagai sudah dibaca.

## [DELETE] /notifications/{id}
> Hapus Notifikasi
Menghapus satu notifikasi.

| Name | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| id | url | yes | ID Notifikasi |