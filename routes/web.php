<?php

use App\Http\Controllers\RoutingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PhoneUnitController;
use App\Http\Controllers\Admin\AccessoryStockController;
use App\Http\Controllers\Admin\PurchaseInvoiceController;
use App\Http\Controllers\Admin\SalesInvoiceController;
use App\Http\Controllers\Admin\LedgerController;
use App\Http\Controllers\Admin\ReportsController;


require __DIR__ . '/auth.php';

Route::prefix('admin')->name('admin.')->group(function () {

    //admin
    Route::resource('users', UserController::class);

    //categroies
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');

    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');

    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    //products
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');

    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');

    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    //supliers
    Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('suppliers', [SupplierController::class, 'store'])->name('suppliers.store');

    Route::get('suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');

    Route::delete('suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');


    //customers
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');

    Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');

    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    Route::get('phone-units', [PhoneUnitController::class, 'index'])->name('phone_units.index');
    Route::get('phone-units/create', [PhoneUnitController::class, 'create'])->name('phone_units.create');
    Route::post('phone-units', [PhoneUnitController::class, 'store'])->name('phone_units.store');

    Route::get('phone-units/{phoneUnit}', [PhoneUnitController::class, 'show'])->name('phone_units.show');

    // Optional (edit/update) if you need
    Route::get('phone-units/{phoneUnit}/edit', [PhoneUnitController::class, 'edit'])->name('phone_units.edit');
    Route::put('phone-units/{phoneUnit}', [PhoneUnitController::class, 'update'])->name('phone_units.update');

    // Optional delete
    Route::delete('phone-units/{phoneUnit}', [PhoneUnitController::class, 'destroy'])->name('phone_units.destroy');


    Route::get('accessory-stock', [AccessoryStockController::class, 'index'])->name('accessory_stock.index');
    Route::get('accessory-stock/{product}', [AccessoryStockController::class, 'show'])->name('accessory_stock.show');

    Route::get('accessory-stock/{product}/adjust', [AccessoryStockController::class, 'createAdjustment'])->name('accessory_stock.adjust.create');
    Route::post('accessory-stock/{product}/adjust', [AccessoryStockController::class, 'storeAdjustment'])->name('accessory_stock.adjust.store');

    //Purchaser
    Route::get('purchases', [PurchaseInvoiceController::class, 'index'])->name('purchases.index');
    Route::get('purchases/create', [PurchaseInvoiceController::class, 'create'])->name('purchases.create');
    Route::post('purchases', [PurchaseInvoiceController::class, 'store'])->name('purchases.store');

    Route::get('purchases/{purchase}', [PurchaseInvoiceController::class, 'show'])->name('purchases.show');

    Route::post('purchases/{purchase}/payments', [PurchaseInvoiceController::class, 'storePayment'])
        ->name('purchases.payments.store');
    //customer sales

    Route::get('sales', [SalesInvoiceController::class, 'index'])->name('sales.index');
    Route::get('sales/create', [SalesInvoiceController::class, 'create'])->name('sales.create');
    Route::post('sales', [SalesInvoiceController::class, 'store'])->name('sales.store');

    Route::get('sales/{sale}', [SalesInvoiceController::class, 'show'])->name('sales.show');

    Route::post('sales/{sale}/payments', [SalesInvoiceController::class, 'storePayment'])
        ->name('sales.payments.store');

    //ledgers
    Route::get('ledgers/suppliers', [LedgerController::class, 'supplierIndex'])->name('ledgers.suppliers.index');
    Route::get('ledgers/customers', [LedgerController::class, 'customerIndex'])->name('ledgers.customers.index');

    Route::get('reports/profit', [ReportsController::class, 'profit'])->name('reports.profit');
    Route::get('reports/stock', [ReportsController::class, 'stock'])->name('reports.stock');
    Route::get('reports/due', [ReportsController::class, 'due'])->name('reports.due');
    Route::get('reports/daily-sales', [ReportsController::class, 'dailySales'])->name('reports.dailySales');

    //profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});



Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::get('', [RoutingController::class, 'index'])->name('root');
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});


Route::get('/login', function () {
    return view('auth.signin');
})->name('login');

// Login action
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('index'); // create resources/views/dashboard.blade.php
    });
});
