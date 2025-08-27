<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AuthorizationController;
use Laravel\Passport\Http\Controllers\ApproveAuthorizationController;
use Laravel\Passport\Http\Controllers\DenyAuthorizationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Bienvenida
Route::get('/', function () {
    return view('welcome');
});

//Oauth
Route::middleware(['web', 'auth'])->prefix('oauth')->group(function () {
    Route::get('authorize', [AuthorizationController::class, 'authorize'])->name('passport.authorizations.authorize');
    Route::post('approve', [ApproveAuthorizationController::class, 'approve'])->name('passport.authorizations.approve');
    Route::delete('deny', [DenyAuthorizationController::class, 'deny'])->name('passport.authorizations.deny');
});

//Login & Logout & ValidateToken
Route::post('auth/login', [AuthController::class, 'login']);
Route::get('auth/logout', [AuthController::class, 'logout']);
Route::get('auth/validate-token', [AuthController::class, 'validateToken']);

//SSO Login & SSO Logout
Route::post('auth/sso/login', [AuthController::class, 'ssoLogin']);
Route::get('auth/sso/logout', [AuthController::class, 'ssoLogout']);

//Redireción de OAUTH
Route::get('/login', function (Request $request) {
    $returnUrl = $request->input('return_url');

    // Redirige a tu frontend de login (Vue)
    $frontendUrl = rtrim(env('APP_FRONTEND_URL'), '/');
    return redirect()->away($frontendUrl . 'sso/login?return_url=' . urlencode($returnUrl));
})->name('login');
