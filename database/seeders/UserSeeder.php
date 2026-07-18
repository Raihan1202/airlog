<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'admin',
                'fullname' => 'Administrator Utama',
                'gelar' => 'S.T',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'access_level' => '2',
                'position' => 'System Administrator',
                'technician' => 1,
                'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAESCAYAAABvXk0WAAAACXBIWXMAAAsTAAALEwEAmpwYAAACzUlEQVR4nO3cMW7CQBBA0Z7+5WQyGRxC0KqYpKAgd40l4JGxdmUjcdt/3DKSTNH3b5/0VwuBgAAAAAAAAAAAAAAAAAAgG8xj9+Urt+LrFq+m4+O1q1T35f93kG2t/sWfS4z3tvz/2oH6h53Rk8n1+q6Dfvt+9m6Dfrq6y3q6vB4W3Wq3fa+Pwb9Xb7cXutp34mTfZr9vN+W0Yx+HW7oR0Wf1+QfV1+pP+r+Ufx9H9OyvqR9k/8r+X6O7eV5ePz+3/6rkdc39m4vH/4x+S4+eH/P9+X8vGv7vjP5zEebDkAAAAAAAAAAAAAAAAAAICf3QXu/4W9d2MPX8nZ7HtPx38nK7t9W74f3IxfIufA/1Pz8Px8Xp/4Z7+//s5+O7+f+Hb7eG9T3tfuOf2/HsB3efj+jgD+fPrt/0f++4dzl8f7c6eD9+f/i/1+O7+fP7T/9/zn/m/+N/1eX3+vv3y/7k/B/n5/P8P/6w5B9b+z/3+P3/3/kc8f7f9/wL7eDz+P7/z/gf+f/8D/AAAAAAAAAAAAAAAAgD+5B9DPrD5rWjAAAAAElFTkSuQmCC',
                'country' => 'Indonesia',
                'phone_number' => '08123456789',
                'address' => 'Jl. Merdeka No. 1',
                'city' => 'Jakarta',
                'state' => 'DKI Jakarta',
                'zip_code' => 10110,
                'joined' => now(),
                'remember_token' => Str::random(10),
            ]);
        }

        if (!User::where('email', 'budi.teknisi@example.com')->exists()) {
            User::create([
                'name' => 'budisantoso',
                'fullname' => 'Budi Santoso, A.Md.T',
                'gelar' => 'A.Md.T',
                'email' => 'budi.teknisi@example.com',
                'password' => Hash::make('password123'),
                'access_level' => '0',
                'position' => 'Karyawan',
                'technician' => 1,
                'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAESCAYAAABvXk0WAAAACXBIWXMAAAsTAAALEwEAmpwYAAACzUlEQVR4nO3cMW7CQBBA0Z7+5WQyGRxC0KqYpKAgd40l4JGxdmUjcdt/3DKSTNH3b5/0VwuBgAAAAAAAAAAAAAAAAAAgG8xj9+Urt+LrFq+m4+O1q1T35f93kG2t/sWfS4z3tvz/2oH6h53Rk8n1+q6Dfvt+9m6Dfrq6y3q6vB4W3Wq3fa+Pwb9Xb7cXutp34mTfZr9vN+W0Yx+HW7oR0Wf1+QfV1+pP+r+Ufx9H9OyvqR9k/8r+X6O7eV5ePz+3/6rkdc39m4vH/4x+S4+eH/P9+X8vGv7vjP5zEebDkAAAAAAAAAAAAAAAAAAICf3QXu/4W9d2MPX8nZ7HtPx38nK7t9W74f3IxfIufA/1Pz8Px8Xp/4Z7+//s5+O7+f+Hb7eG9T3tfuOf2/HsB3efj+jgD+fPrt/0f++4dzl8f7c6eD9+f/i/1+O7+fP7T/9/zn/m/+N/1eX3+vv3y/7k/B/n5/P8P/6w5B9b+z/3+P3/3/kc8f7f9/wL7eDz+P7/z/gf+f/8D/AAAAAAAAAAAAAAAAgD+5B9DPrD5rWjAAAAAElFTkSuQmCC',
                'country' => 'Indonesia',
                'phone_number' => '08123456790',
                'address' => 'Jl. Mawar No. 15',
                'city' => 'Jakarta',
                'state' => 'DKI Jakarta',
                'zip_code' => 10120,
                'joined' => now(),
                'remember_token' => Str::random(10),
            ]);
        }

        if (!User::where('email', 'sari.teknisi@example.com')->exists()) {
            User::create([
                'name' => 'sariwulandari',
                'fullname' => 'Sari Wulandari, S.T',
                'gelar' => 'S.T',
                'email' => 'sari.teknisi@example.com',
                'password' => Hash::make('password123'),
                'access_level' => '0',
                'position' => 'Karyawan',
                'technician' => 1,
                'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAESCAYAAABvXk0WAAAACXBIWXMAAAsTAAALEwEAmpwYAAACzUlEQVR4nO3cMW7CQBBA0Z7+5WQyGRxC0KqYpKAgd40l4JGxdmUjcdt/3DKSTNH3b5/0VwuBgAAAAAAAAAAAAAAAAAAgG8xj9+Urt+LrFq+m4+O1q1T35f93kG2t/sWfS4z3tvz/2oH6h53Rk8n1+q6Dfvt+9m6Dfrq6y3q6vB4W3Wq3fa+Pwb9Xb7cXutp34mTfZr9vN+W0Yx+HW7oR0Wf1+QfV1+pP+r+Ufx9H9OyvqR9k/8r+X6O7eV5ePz+3/6rkdc39m4vH/4x+S4+eH/P9+X8vGv7vjP5zEebDkAAAAAAAAAAAAAAAAAAICf3QXu/4W9d2MPX8nZ7HtPx38nK7t9W74f3IxfIufA/1Pz8Px8Xp/4Z7+//s5+O7+f+Hb7eG9T3tfuOf2/HsB3efj+jgD+fPrt/0f++4dzl8f7c6eD9+f/i/1+O7+fP7T/9/zn/m/+N/1eX3+vv3y/7k/B/n5/P8P/6w5B9b+z/3+P3/3/kc8f7f9/wL7eDz+P7/z/gf+f/8D/AAAAAAAAAAAAAAAAgD+5B9DPrD5rWjAAAAAElFTkSuQmCC',
                'country' => 'Indonesia',
                'phone_number' => '08123456791',
                'address' => 'Jl. Melati No. 22',
                'city' => 'Jakarta',
                'state' => 'DKI Jakarta',
                'zip_code' => 10130,
                'joined' => now(),
                'remember_token' => Str::random(10),
            ]);
        }

        if (!User::where('email', 'andi.supervisor@example.com')->exists()) {
            User::create([
                'name' => 'andisupervisor',
                'fullname' => 'Andi Supervisor, S.T, M.T',
                'gelar' => 'S.T, M.T',
                'email' => 'andi.supervisor@example.com',
                'password' => Hash::make('password123'),
                'access_level' => '1',
                'position' => 'Supervisor',
                'technician' => 0,
                'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAESCAYAAABvXk0WAAAACXBIWXMAAAsTAAALEwEAmpwYAAACzUlEQVR4nO3cMW7CQBBA0Z7+5WQyGRxC0KqYpKAgd40l4JGxdmUjcdt/3DKSTNH3b5/0VwuBgAAAAAAAAAAAAAAAAAAgG8xj9+Urt+LrFq+m4+O1q1T35f93kG2t/sWfS4z3tvz/2oH6h53Rk8n1+q6Dfvt+9m6Dfrq6y3q6vB4W3Wq3fa+Pwb9Xb7cXutp34mTfZr9vN+W0Yx+HW7oR0Wf1+QfV1+pP+r+Ufx9H9OyvqR9k/8r+X6O7eV5ePz+3/6rkdc39m4vH/4x+S4+eH/P9+X8vGv7vjP5zEebDkAAAAAAAAAAAAAAAAAAICf3QXu/4W9d2MPX8nZ7HtPx38nK7t9W74f3IxfIufA/1Pz8Px8Xp/4Z7+//s5+O7+f+Hb7eG9T3tfuOf2/HsB3efj+jgD+fPrt/0f++4dzl8f7c6eD9+f/i/1+O7+fP7T/9/zn/m/+N/1eX3+vv3y/7k/B/n5/P8P/6w5B9b+z/3+P3/3/kc8f7f9/wL7eDz+P7/z/gf+f/8D/AAAAAAAAAAAAAAAAgD+5B9DPrD5rWjAAAAAElFTkSuQmCC',
                'country' => 'Indonesia',
                'phone_number' => '08123456792',
                'address' => 'Jl. Kenanga No. 5',
                'city' => 'Jakarta',
                'state' => 'DKI Jakarta',
                'zip_code' => 10140,
                'joined' => now(),
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
