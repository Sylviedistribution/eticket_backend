<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Models\User;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\NotificationsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and assigned to the "api"
| middleware group. Build your API!
|
*/


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware(['auth:sanctum', 'verified'])->get('/user', function (Request $request) {
    return $request->user();
});

// Clients Routes
Route::prefix('user')->controller(UsersController ::class)->group(function () {
    Route::get('/', 'index')->middleware('auth:sanctum'); // List one
    Route::post('/update', 'update')->middleware('auth:sanctum'); ; // Update a client
    Route::delete('{client}', 'delete'); // Delete a client
    Route::get('filter', 'filter'); // Filter clients
});

// Commandes Routes
Route::prefix('events')->controller(EventsController::class)->group(function () {
    Route::get('/', 'list')->middleware('auth:sanctum');
    Route::post('/store', 'store')->middleware('auth:sanctum');
    Route::get('{id}', 'show');
    Route::put('{update/id}', 'eventUpdate');
    Route::delete('{id}', 'eventdelete');

});


// Commandes Routes
Route::prefix('ticket')->controller(TicketsController::class)->group(function () {
    Route::get('/', 'list')->middleware('auth:sanctum');
    Route::post('/store', 'store')->middleware('auth:sanctum');
    Route::delete('{commande}', 'delete');
    Route::get('{id}', 'show');
    Route::put('/update', 'ticketUpdate');
    Route::delete('ticket/{ticket}', 'ticketDelete');
});


// Transactions Routes
Route::prefix('transactions')->controller(TransactionsController::class)->group(function () {
    Route::get('/', 'list');
    Route::post('/', 'store');
    Route::put('{transaction}', 'update');
    Route::delete('{transaction}', 'delete');
    Route::get('filter', 'filter');
});

// Notifications Routes
Route::prefix('notifications')->controller(NotificationsController::class)->group(function () {
    Route::get('/', 'list');
    Route::post('/', 'store');
    Route::put('{notification}', 'update');
    Route::delete('{notification}', 'delete');
    Route::get('filter', 'filter');
});

