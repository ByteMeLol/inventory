<?php

use App\Http\Controllers\CompanyScopeConttroller;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SignupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});


Route::get('/signin', function () {
    return view('index');
})->name('login')->middleware('guest');
Route::post('/login', [SessionController::class, 'create'])->name('login')->middleware('guest');
Route::post('/logout', [SessionController::class, 'destroy'])->name('logout')->middleware('auth');

Route::get('/signup', [SignupController::class, 'create'])->name('signup.create')->middleware('guest'); 
Route::post('/signup', [SignupController::class,'store'])->name('signup.store')->middleware('guest'); 

Route::get('/home', function () {
    return view('home');
})->middleware('auth');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');
Route::get('/sales', [SalesController::class, 'index'])->name('sales.index')->middleware('auth');
Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index')->middleware('auth');
Route::get('/insurance', [InsuranceController::class, 'index'])->name('insurance.index')->middleware('auth');
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index')->middleware('auth');
Route::get('/staff', '\\App\\Http\\Controllers\\StaffController@index')->name('staff.index')->middleware('auth');
Route::post('/staff', '\\App\\Http\\Controllers\\StaffController@store')->name('staff.store')->middleware('auth');
Route::get('/settings', 'App\\Http\\Controllers\\SettingsController@edit')->name('settings.edit')->middleware('auth');
Route::put('/settings', 'App\\Http\\Controllers\\SettingsController@update')->name('settings.update')->middleware('auth');
Route::get('/profile', [ProfileController::class, 'edit'])->middleware('auth');
Route::post('/profile', [ProfileController::class, 'update'])->middleware('auth');
Route::get('/reports', function () {
    return view('reports');
})->middleware('auth');
Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store')->middleware('auth');
Route::post('/expenses',[ExpenseController::class,'store'])->name('expenses.store')->middleware('auth');
Route::post('/insurance/policies', [InsuranceController::class, 'storePolicy'])->name('insurance.policies.store')->middleware('auth');
Route::post('/insurance/claims', [InsuranceController::class, 'storeClaim'])->name('insurance.claims.store')->middleware('auth');
Route::get('/companies/{id}',[CompanyScopeConttroller::class, 'switchCompany'])->name('companies.switch')->middleware('auth');
Route::post('/sales',[SalesController::class,'store'])->name('sales.store')->middleware('auth');
Route::get('/inventory',[InventoryController::class ,'index'])->name('inventory.view')->middleware('auth');
Route::post('/inventory',[InventoryController::class ,'store'])->name('inventory.store')->middleware('auth');
