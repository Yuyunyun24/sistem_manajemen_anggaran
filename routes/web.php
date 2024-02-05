<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\account;
use App\Http\Controllers\Account\DashboardController;
use App\Http\Controllers\Account\CategoriesDebitController;
use App\Http\Controllers\Account\DebitController;
use App\Http\Controllers\Account\CategoriesCreditController;
use App\Http\Controllers\Account\CreditController;
use App\Http\Controllers\Account\LaporanDebitController;
use App\Http\Controllers\Account\LaporanCreditController;

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

Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');

Route::get('home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout')->middleware('auth');
Route::get('registration', [RegistrationController::class, 'registration'])->name('register-user');
Route::post('custom-registration', [RegistrationController::class, 'customRegistration'])->name('register.custom');


Route::prefix('account')->group(function () {

    //dashboard account
    Route::get('/dashboard', [Account\DashboardController::class, 'index'])->name('account.dashboard.index');

    //categories debit
    Route::get('/categories_debit/search', [Account\CategoriesDebitController::class, 'search'])->name('account.categories_debit.search');
    Route::resource('categories_debit', CategoriesDebitController::class, ['as' => 'account']);

    //debit
    Route::get('/debit/search', [Account\DebitController::class, 'search'])->name('account.debit.search');
    Route::resource('debit', DebitController::class, ['as' => 'account']);  

    //categories credit
    Route::get('/categories_credit/search', [Account\CategoriesCreditController::class, 'search'])->name('account.categories_credit.search');
    Route::resource('categories_credit', CategoriesCreditController::class, ['as' => 'account']);

    //credit
    Route::get('/credit/search', [Account\CreditController::class, 'search'])->name('account.credit.search');
    Route::resource('credit', CreditController::class, ['as' => 'account']);
    
    //laporan debit
    Route::get('/laporan_debit', [Account\LaporanDebitController::class, 'index'])->name('account.laporan_debit.index');
    Route::get('/laporan_debit/check', [Account\LaporanDebitController::class, 'check'])->name('account.laporan_debit.check');

    //laporan credit
    Route::get('/laporan_credit', [Account\LaporanCreditController::class, 'index'])->name('account.laporan_credit.index');
    Route::get('/laporan_credit/check', [Account\LaporanCreditController::class, 'check'])->name('account.laporan_credit.check');

});