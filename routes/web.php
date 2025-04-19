<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\OTPController;
use Modules\Booking\Controllers\BookingController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\ItineraryPdfController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;

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
Route::get('/intro', 'LandingpageController@index');
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/install/check-db', 'HomeController@checkConnectDatabase');

// Social Login
Route::get('social-login/{provider}', 'Auth\LoginController@socialLogin');
Route::get('social-callback/{provider}', 'Auth\LoginController@socialCallBack');

// Logs
Route::get(config('admin.admin_route_prefix') . '/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware(['auth', 'dashboard', 'system_log_view'])->name('admin.logs');

Route::get('/install', 'InstallerController@redirectToRequirement')->name('LaravelInstaller::welcome');
Route::get('/install/environment', 'InstallerController@redirectToWizard')->name('LaravelInstaller::environment');
Route::fallback([\Modules\Core\Controllers\FallbackController::class, 'FallBack']);

// Hide page update default
Route::get('/update', 'InstallerController@redirectToHome');
Route::get('/update/overview', 'InstallerController@redirectToHome');
Route::get('/update/database', 'InstallerController@redirectToHome');


//OTP
Route::post('/send-otp', [OTPController::class, 'sendOTP'])->name('send.otp');
Route::post('/verify-otp', [OTPController::class, 'verifyOTP'])->name('verify.otp');
Route::post('/save/passangerDetails', [BookingController::class, 'doCheckout'])->name('doCheckout');
Route::post('/save/bookingpassanger', [BookingController::class, 'bookPassengerDetails'])->name('bookPassengerDetails');

Route::get('/get/passengerDetails/{code}/{id}', [BookingController::class, 'getPassengerDetails'])->name('getPassengerDetails');

Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');

//itinerary
Route::get('/adminItinerary', [ItineraryController::class, 'admin']);
// Route::get('/itineraryform', [ItineraryController::class, 'form']);
// Route::get('/itinerary/create', [ItineraryController::class, 'create'])->name('itinerary.create');
Route::get('/itinerary/itineraryform', [ItineraryController::class, 'itineraryform'])->name('itinerary.itineraryform');
Route::post('/itinerary/store', [ItineraryController::class, 'store'])->name('itinerary.store');

Route::get('/singlepdf-itinerary/{id}', [ItineraryPdfController::class, 'singlepdf']);

//customers
Route::get('/customers/customerform', [CustomerController::class, 'customerform'])->name('customers.customerform');
Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');


//invoices
Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
Route::get('/invoicepdf/{id}', [InvoiceController::class, 'generatePDF'])->name('invoices.generatePDF');
Route::get('/invoices/invoiceForm', [InvoiceController::class, 'invoiceForm'])->name('invoice.invoiceForm');
Route::get('/invoices/list', [InvoiceController::class, 'listInvoices'])->name('invoices.list');
Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');






