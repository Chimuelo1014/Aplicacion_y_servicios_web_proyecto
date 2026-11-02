<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicioExternoController;
use App\Http\Controllers\VehiculoController;
use App\Models\Marca;
use App\Models\Vehiculo;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome', [
        'stats' => [
            'disponibles' => Vehiculo::where('estado', 'disponible')->count(),
            'vendidos' => Vehiculo::where('estado', 'vendido')->count(),
            'marcas' => Marca::count(),
        ],
        'destacados' => Vehiculo::with('marca')->where('estado', 'disponible')->latest()->take(6)->get(),
    ]);
})->name('home');

/*
|--------------------------------------------------------------------------
| Autenticación (solo invitados)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register']);

    Route::get('/olvide-contrasena', [PasswordResetController::class, 'showForgot'])->name('password.request');
    Route::post('/olvide-contrasena', [PasswordResetController::class, 'sendLink'])->middleware('throttle:3,1')->name('password.email');
    Route::get('/restablecer-contrasena/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
    Route::post('/restablecer-contrasena', [PasswordResetController::class, 'reset'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Panel (solo usuarios autenticados)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/perfil/contrasena', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/perfil/tokens', [ProfileController::class, 'createToken'])->name('profile.tokens.store');
    Route::delete('/perfil/tokens/{id}', [ProfileController::class, 'destroyToken'])->name('profile.tokens.destroy');

    Route::resource('marcas', MarcaController::class)->except('show');
    Route::get('/marcas/{marca}/modelos-externos', [ServicioExternoController::class, 'modelosPorMarca'])->name('marcas.modelos-externos');

    Route::get('/vehiculos/reporte/pdf', [VehiculoController::class, 'reportePdf'])->name('reportes.vehiculos');
    Route::resource('vehiculos', VehiculoController::class);

    Route::get('/cliente-api', [ServicioExternoController::class, 'clienteApi'])->name('api.cliente');
});
