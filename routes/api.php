<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\NotificationsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Point d’entrée des routes de l’API RESTful.
| Toutes les routes sont automatiquement placées sous le middleware "api".
| Séparation claire par rôle : invité, utilisateur, organisateur, administrateur.
|--------------------------------------------------------------------------
*/

// 🔐 Authentification & Sécurité 
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');              // Inscription
    Route::post('/login', 'login');                    // Connexion
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    Route::post('/forgot-password', 'sendResetLinkEmail'); // Demande de réinitialisation
    Route::post('/reset-password', 'resetPassword');   // Réinitialisation
});

// ✅ Routes protégées par authentification Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // 🧑 Utilisateur connecté
    Route::get('/user', fn(Request $request) => $request->user());

    // 👤 Gestion du profil utilisateur
    Route::prefix('user')->controller(UsersController::class)->group(function () {
        Route::get('/profile', 'profile');     // Voir/éditer profil
        Route::put('/update', 'update');       // Mise à jour des données
        Route::delete('/delete', 'delete');    // Suppression du compte
    });

    // 🎟️ Gestion des tickets de l'utilisateur
    Route::prefix('ticket')->controller(TicketsController::class)->group(function () {
        Route::get('/', 'list');                     // Liste des tickets
        Route::post('/reserve', 'store');            // Réserver un ticket
        Route::put('/{id}', 'ticketUpdate');         // Modifier un ticket
    });
});

// 👨‍💼 Organisateur : Gestion des événements
    Route::prefix('events')->controller(EventsController::class)->group(function () {
        Route::get('/', 'index');                  // Liste des événements créés
        Route::post('/', 'store');                // Créer un événement
        Route::get('/{id}', 'show');              // Détail d’un événement
        Route::put('/{id}', 'eventUpdate');       // Modifier un événement
        Route::delete('/{id}', 'eventDelete');    // Supprimer un événement
    });

// ✅ Vérification d'email (optionnelle si email vérifié requis)
Route::middleware(['auth:sanctum', 'verified'])->get('/user', fn(Request $request) => $request->user());

// 💳 Transactions (gérées publiquement ou pour des tests)
Route::prefix('transactions')->controller(TransactionsController::class)->group(function () {
    Route::get('/', 'list');                  // Liste des transactions
    Route::post('/', 'store');                // Créer une transaction
    Route::put('{transaction}', 'update');    // Modifier
    Route::delete('{transaction}', 'delete'); // Supprimer
    Route::get('filter', 'filter');           // Filtrage
});

// 👮 Admin : gestion complète du système
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {

    // 🔍 Événements
    Route::prefix('events')->controller(EventsController::class)->group(function () {
        Route::get('/', 'index');                 // Voir tous les événements
        Route::get('/{id}', 'show');                 // Voir tous les événements
        Route::delete('/{id}', 'eventDelete');    // Supprimer un événement
    });

    // 👥 Utilisateurs
    Route::prefix('users')->controller(UsersController::class)->group(function () {
        Route::get('/', 'index');              // Tous les utilisateurs
        Route::post('/contact', 'contact');              // Tous les utilisateurs        
        Route::get('/filter', 'filter');       // Filtrage par critères
        Route::delete('/{id}', 'delete');      // Suppression
    });

    // 💸 Transactions
    Route::prefix('transactions')->controller(TransactionsController::class)->group(function () {
        Route::get('/', 'list');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'delete');
        Route::get('/filter', 'filter');
    });

    // 🔔 Notifications
    Route::prefix('notifications')->controller(NotificationsController::class)->group(function () {
        Route::get('/', 'list');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'delete');
        Route::get('/filter', 'filter');
    });
});

// 🔔 Notifications publiques ou accessibles à tous
Route::prefix('notifications')->controller(NotificationsController::class)->group(function () {
    Route::get('/', 'list');
    Route::post('/', 'store');
    Route::put('{notification}', 'update');
    Route::delete('{notification}', 'delete');
    Route::get('filter', 'filter');
});

