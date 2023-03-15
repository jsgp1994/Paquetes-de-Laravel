<?php

use App\Http\Controllers\Paquetes\PackageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(["prefix" => "package"], function (){
    Route::get("/intervention", [PackageController::class, 'intervention_image']);
    Route::get("/QR", [PackageController::class, 'qr_generate']);
    Route::get("/excel/export", [PackageController::class, 'excel_export'])->name('excel.export');
    Route::get("/excel/import", [PackageController::class, 'excel_import'])->name('excel.import');
    Route::post("/google/translate", [PackageController::class, 'google_translate'])->name('google.translate');
    Route::post("/stripe/create_customer", [PackageController::class, 'stripe_create_customer'])->name('stripe.create_customer');
    Route::get("/stripe_payment_method_register", [PackageController::class, 'stripe_payment_method_register'])->name('stripe_payment_method_register');
    Route::get("/stripe_payment_method", [PackageController::class, 'stripe_payment_method'])->name('stripe_payment_method');
    Route::get("/stripe_create_only_pay_form", [PackageController::class, 'stripe_create_only_pay_form'])->name('stripe_create_only_pay_form');
    Route::post("/stripe_payment_method_create", [PackageController::class, 'stripe_payment_method_create'])->name('stripe_payment_method_create');
    Route::post("/stripe_create_only_pay", [PackageController::class, 'stripe_create_only_pay'])->name('stripe_create_only_pay');
    Route::post("/stripe_create_suscription", [PackageController::class, 'stripe_create_suscription'])->name('stripe_create_suscription');
    Route::post("/download_link", [PackageController::class, 'download_link'])->name('download_link');
});