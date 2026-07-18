<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\LogbookItemController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UnitController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('welcome'));

Route::get('/api-docs', function () {
    $path = resource_path('docs/api_v1.md');
    if (!file_exists($path)) abort(404, 'File api.md not found');

    $content = file_get_contents($path);
    $lines = explode("\n", $content);
    
    $spec = ['base_url' => url('/api/v1'), 'groups' => []];
    $currentGroup = null;
    $currentRoute = null;

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;

        if (str_starts_with($line, '# ') && !str_starts_with($line, '##')) {
            if ($currentRoute) $currentGroup['routes'][] = $currentRoute; // Save prev route
            if ($currentGroup) $spec['groups'][] = $currentGroup; // Save prev group
            
            $currentGroup = [
                'name' => trim(substr($line, 2)),
                'routes' => []
            ];
            $currentRoute = null;
        }
        
        elseif (str_starts_with($line, '## [')) {
            if ($currentRoute) $currentGroup['routes'][] = $currentRoute; // Save prev route

            preg_match('/\[(GET|POST|PUT|DELETE|PATCH)\]\s+(\S+)(.*)/', $line, $matches);
            $isPublic = str_contains(strtolower($matches[3] ?? ''), 'public');

            $currentRoute = [
                'method' => $matches[1],
                'uri' => $matches[2],
                'auth' => !$isPublic,
                'summary' => '',
                'description' => '',
                'params' => []
            ];
        }

        elseif (str_starts_with($line, '> ')) {
            if ($currentRoute) $currentRoute['summary'] = trim(substr($line, 2));
        }

        elseif (str_starts_with($line, '|')) {
            if (str_contains($line, '---')) continue;
            if (str_contains($line, '| Name |') && str_contains($line, '| Type |')) continue;
            
            if ($currentRoute) {
                $cols = array_map('trim', explode('|', trim($line, '|')));
                if (count($cols) >= 4) {
                    $currentRoute['params'][] = [
                        'name' => $cols[0],
                        'type' => $cols[1],
                        'req' => strtolower($cols[2]) === 'yes',
                        'desc' => $cols[3]
                    ];
                }
            }
        }

        else {
            if ($currentRoute) {
                $currentRoute['description'] .= $line . " ";
            }
        }
    }

    if ($currentRoute) $currentGroup['routes'][] = $currentRoute;
    if ($currentGroup) $spec['groups'][] = $currentGroup;

    return view('api_docs', ['spec' => $spec]);
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile/{user:name}', 'show')->name('profile.show');
        Route::get('/profile/{user:name}/notifications', 'notifications')->name('profile.notifications');
        Route::get('/qr-code/{user:name}', 'generateQrCode')->name('profile.qr');
    });

    Route::controller(AccountController::class)->prefix('account')->name('account.')->group(function () {
        Route::get('/settings', 'settings')->name('settings');
        Route::get('/security', 'security')->name('security');
        Route::patch('/settings/details', 'updateDetails')->name('update.details');
        Route::put('/security/password', 'updatePassword')->name('update.password');
    });

    Route::controller(LogbookController::class)
        ->prefix('logbook/{unit_id}/dashboard')
        ->name('logbook.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{logbook_id}/items', 'items')->name('items');
            Route::get('/edit/{logbook_id}', 'edit')->name('edit');
            Route::put('/update/{logbook_id}', 'update')->name('update');
            Route::delete('/delete/{logbook_id}', 'destroy')->name('destroy');
            Route::put('/approve/{logbook_id}', 'approve')->name('approve');
            Route::get('/{logbook_id}/view', 'show')->name('view');
            Route::get('/{logbook_id}/edit-content', 'editContent')->name('edit.content');
        });

    Route::controller(LogbookItemController::class)
        ->prefix('logbook/{unit_id}/dashboard/{logbook_id}/item')
        ->name('logbook.item.')
        ->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{item_id}/edit', 'edit')->name('edit');
            Route::put('/{item_id}', 'update')->name('update');
            Route::delete('/{item_id}', 'destroy')->name('destroy');
        });

    Route::prefix('manage')->group(function () {
        
        Route::controller(ToolController::class)->group(function () {
            Route::get('/tools', 'index')->name('tools.index');
            Route::post('/tools/update', 'update');
            Route::post('/tools/delete', 'delete');
        });

        Route::controller(PositionController::class)->group(function () {
            Route::get('/position', 'index')->name('position.index');
            Route::post('/positions/update', 'update')->name('positions.update');
            Route::post('/positions/delete', 'delete')->name('positions.delete');
        });

        Route::controller(UserController::class)->name('users.')->group(function () {
            Route::get('/users', 'index')->name('index');
            Route::get('/users/create', 'create')->name('create');
            Route::post('/users', 'store')->name('store');
            Route::get('/users/{user}', 'show')->name('show');
            Route::get('/users/{user}/edit', 'edit')->name('edit');
            Route::put('/users/{user}', 'update')->name('update');
            Route::delete('/users/{user}', 'destroy')->name('destroy');
        });
		
		
		Route::controller(UnitController::class)->name('units.')->group(function () {
            Route::get('/units', 'index')->name('index');
            Route::post('/units', 'store')->name('store');
            Route::put('/units/{id}', 'update')->name('update');
            Route::delete('/units/{id}', 'destroy')->name('destroy');
        });
    });
	
	Route::controller(\App\Http\Controllers\NotificationController::class)
        ->prefix('notifications')
        ->name('notifications.')
        ->group(function () {
            Route::get('/mark-all', 'markAllAsRead')->name('markAll');
            Route::get('/{notification}/read', 'markAsRead')->name('read');
			Route::delete('/{id}', 'destroy')->name('destroy');
        });
});