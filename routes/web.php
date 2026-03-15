<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Главная страница - редирект на дашборд
Route::get('/', function () {
    return view('home');
})->name('home');

// Аутентификация (только для гостей)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/two-factor-challenge', [AuthController::class, 'showTwoFactorChallenge'])
        ->name('two-factor.challenge');
    Route::post('/two-factor-challenge', [AuthController::class, 'verifyTwoFactorChallenge'])
        ->name('two-factor.verify');
    Route::post('/two-factor-challenge/resend', [AuthController::class, 'resendTwoFactorChallenge'])
        ->name('two-factor.resend');
    Route::post('/reset-password-direct', [AuthController::class, 'resetPasswordDirect'])
        ->name('password.direct-reset');

    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Выход из системы
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Защищенные маршруты (только для авторизованных пользователей)
Route::middleware('auth')->group(function () {

    // ГЛАВНАЯ СТРАНИЦА (Дашборд)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Маршруты cars/create и cars/import/form ДОЛЖНЫ быть до resource,
    // иначе /cars/create перехватит cars/{car} (create как id → 404)
    Route::get('/cars/create', [CarController::class, 'create'])->name('cars.create');
    Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
    Route::get('/cars/import/form', [CarController::class, 'showImportForm'])->name('cars.import.form');
    Route::post('/cars/import', [CarController::class, 'import'])->name('cars.import');

    // АВТОМОБИЛИ - основной CRUD (create, store заданы выше)
    Route::resource('cars', CarController::class)->except(['create', 'store']);

    // Дополнительные маршруты для автомобилей
    Route::prefix('cars')->name('cars.')->group(function () {
        // Обновление пробега (AJAX)
        Route::post('/{car}/update-mileage', [CarController::class, 'updateMileage'])
            ->name('update-mileage');

        // Статистика автомобиля (AJAX)
        Route::get('/{car}/statistics', [CarController::class, 'getStatistics'])
            ->name('statistics');

        // Сброс всех запчастей (отметить все как замененные)
        Route::post('/{car}/reset-parts', [CarController::class, 'resetAllParts'])
            ->name('reset-parts');

        // Экспорт данных автомобиля в JSON
        Route::get('/{car}/export', [CarController::class, 'export'])
            ->name('export');

        // История обслуживания (AJAX)
        Route::get('/{car}/maintenance-history', [CarController::class, 'maintenanceHistory'])
            ->name('maintenance-history');

        // Расчет стоимости обслуживания (AJAX)
        Route::get('/{car}/maintenance-cost', [CarController::class, 'maintenanceCost'])
            ->name('maintenance-cost');
    });

    // ЗАПЧАСТИ
    Route::prefix('parts')->name('parts.')->group(function () {
        // Отметить запчасть как замененную (AJAX)
        Route::post('/{part}/replaced', [PartController::class, 'markAsReplaced'])
            ->name('replaced');

        // Создать новую запчасть (AJAX)
        Route::post('/', [PartController::class, 'store'])
            ->name('store');

        // Удалить выбранные запчасти (AJAX)
        Route::post('/batch-destroy', [PartController::class, 'destroyBatch'])
            ->name('batch-destroy');

        // Отметить выбранные запчасти как заменённые (AJAX)
        Route::post('/batch-replace', [PartController::class, 'replaceBatch'])
            ->name('batch-replace');

        // Обновить запчасть (AJAX)
        Route::put('/{part}', [PartController::class, 'update'])
            ->name('update');

        // Удалить запчасть (AJAX)
        Route::delete('/{part}', [PartController::class, 'destroy'])
            ->name('destroy');

        // Показать детали запчасти
        Route::get('/{part}', [PartController::class, 'show'])
            ->name('show');

        // Получить запчасти по категории (AJAX)
        Route::get('/category/{category}', [PartController::class, 'getByCategory'])
            ->name('by-category');
    });

    // ПРОФИЛЬ ПОЛЬЗОВАТЕЛЯ
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', function () {
            return view('profile.index');
        })->name('index');

        Route::get('/settings', function () {
            return view('profile.settings');
        })->name('settings');

        Route::put('/update', [AuthController::class, 'updateProfile'])
            ->name('update');

        Route::put('/change-password', [AuthController::class, 'changePassword'])
            ->name('change-password');
    });

    // ИСТОРИЯ И ОТЧЕТЫ
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/maintenance', function () {
            return view('reports.maintenance');
        })->name('maintenance');

        Route::get('/costs', function () {
            return view('reports.costs');
        })->name('costs');

        Route::get('/mileage', function () {
            return view('reports.mileage');
        })->name('mileage');
    });

    // АДМИНИСТРАТИВНЫЕ ФУНКЦИИ (если понадобятся в будущем)
    Route::prefix('admin')->name('admin.')->middleware('can:admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/users', function () {
            return view('admin.users');
        })->name('users');
        Route::get('/statistics', function () {
            return view('admin.statistics');
        })->name('statistics');
    });

    // API маршруты для AJAX запросов
    Route::prefix('api')->name('api.')->group(function () {
        // API для автомобилей
        Route::prefix('cars')->name('cars.')->group(function () {
            Route::get('/', [CarController::class, 'apiIndex'])->name('index');
            Route::get('/{car}', [CarController::class, 'apiShow'])->name('show');
            Route::post('/{car}/mileage', [CarController::class, 'apiUpdateMileage'])->name('update-mileage');
        });

        // API для запчастей
        Route::prefix('parts')->name('parts.')->group(function () {
            Route::get('/car/{car}', [PartController::class, 'apiByCar'])->name('by-car');
            Route::get('/status/{status}', [PartController::class, 'apiByStatus'])->name('by-status');
        });

        // API для дашборда
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/stats', [DashboardController::class, 'apiStats'])->name('stats');
            Route::get('/recent-activity', [DashboardController::class, 'apiRecentActivity'])->name('recent-activity');
        });
    });
});

// Паблик маршруты (доступны без авторизации)
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/features', function () {
    return view('features');
})->name('features');

Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/help', function () {
    return view('help');
})->name('help');
