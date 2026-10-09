<?php

use App\Http\Controllers\CompanyScopeConttroller;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\FinanceController;
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
Route::get('/sales/{sale}/invoice', [SalesController::class, 'downloadInvoice'])->name('sales.invoice.download')->middleware('auth');
Route::put('/sales/{sale}', [SalesController::class, 'updateStatus'])->name('sales.updateStatus')->middleware('auth');
Route::get('/inventory',[InventoryController::class ,'index'])->name('inventory.view')->middleware('auth');
Route::post('/inventory',[InventoryController::class ,'store'])->name('inventory.store')->middleware('auth');
Route::post('/inventory/warehouses', [InventoryController::class, 'storeWarehouse'])->name('inventory.warehouses.store')->middleware('auth');
Route::post('/inventory/locations', [InventoryController::class, 'storeLocation'])->name('inventory.locations.store')->middleware('auth');
Route::post('/inventory/receive', [InventoryController::class, 'receive'])->name('inventory.receive')->middleware('auth');
Route::post('/inventory/issue', [InventoryController::class, 'issue'])->name('inventory.issue')->middleware('auth');
Route::post('/inventory/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust')->middleware('auth');
Route::post('/inventory/transfer', [InventoryController::class, 'transfer'])->name('inventory.transfer')->middleware('auth');
Route::post('/inventory/reservations', [InventoryController::class, 'reserve'])->name('inventory.reservations.store')->middleware('auth');
Route::delete('/inventory/reservations/{reservation}', [InventoryController::class, 'releaseReservation'])->name('inventory.reservations.release')->middleware('auth');
Route::post('/inventory/categories', [CatalogController::class, 'category'])->name('inventory.categories.store')->middleware('auth');
Route::post('/inventory/units', [CatalogController::class, 'unit'])->name('inventory.units.store')->middleware('auth');
Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index')->middleware('auth');
Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store')->middleware('auth');
Route::post('/suppliers', [PurchaseController::class, 'storeSupplier'])->name('suppliers.store')->middleware('auth');
Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index')->middleware('auth');
Route::post('/finance/categories', [FinanceController::class, 'storeCategory'])->name('finance.categories.store')->middleware('auth');
Route::post('/finance/transactions', [FinanceController::class, 'store'])->name('finance.transactions.store')->middleware('auth');
