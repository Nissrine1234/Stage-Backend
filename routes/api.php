<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CallsController;
use App\Http\Controllers\FacturesController;
use App\Http\Controllers\DeliveriesController;
use App\Http\Controllers\OffresController;
use App\Http\Controllers\GestionServiceAchatController;
use App\Http\Controllers\DemandsController;
use App\Http\Controllers\UsersController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// AuthController

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


// ProviderController

Route::post('/become-provider', [ProviderController::class, 'becomeProvider']);
Route::post('/create-provider', [ProviderController::class, 'createProvider'])->middleware('auth:sanctum');
Route::put('/update-provider/{id}', [ProviderController::class, 'updateProvider'])->middleware('auth:sanctum');
Route::delete('/delete-provider/{id}', [ProviderController::class, 'deleteProvider'])->middleware('auth:sanctum');

// CallsController

Route::get('/calls', [CallsController::class, 'listCalls']);
Route::post('/calls', [CallsController::class, 'createCall'])->middleware('auth:sanctum');
Route::put('/calls/{id}', [CallsController::class, 'updateCall'])->middleware('auth:sanctum');
Route::delete('/calls/{id}', [CallsController::class, 'deleteCall'])->middleware('auth:sanctum');
Route::get('/calls/{id}', [CallsController::class, 'showCall']);


// // FacturesController

Route::get('/listFactures', [FacturesController::class, 'listFactures']);
Route::post('/createFacture', [FacturesController::class, 'createFacture']);
Route::get('/showFacture/{id}', [FacturesController::class, 'showFacture']);
Route::put('/updateFacture/{id}', [FacturesController::class, 'updateFacture']);
Route::delete('/deleteFacture/{id}', [FacturesController::class, 'deleteFacture']);

// // DeliveriesController

Route::get('/listDeliveries', [DeliveriesController::class, 'listDeliveries']);
Route::post('/createDelivery', [DeliveriesController::class, 'createDelivery']);
Route::get('/showDelivery/{id}', [DeliveriesController::class, 'showDelivery']);
Route::put('/updateDelivery/{id}', [DeliveriesController::class, 'updateDelivery']);
Route::delete('/deleteDelivery/{id}', [DeliveriesController::class, 'deleteDelivery']);

// // OffresController

Route::get('/listOffers', [OffresController::class, 'listOffers']);
Route::post('/createOffer', [OffresController::class, 'createOffer']);
Route::get('/showOffer/{id}', [OffresController::class, 'showOffer']);
Route::put('/updateOffer/{id}', [OffresController::class, 'updateOffer']);
Route::delete('/deleteOffer/{id}', [OffresController::class, 'deleteOffer']);



// //GestionServiceAchatController


Route::prefix('services-achats')->group(function () {
    Route::get('/', [GestionServiceAchatController::class, 'index']); // Liste des services d'achat
    Route::get('/{id}', [GestionServiceAchatController::class, 'show']); // Détails d'un service
    Route::post('/', [GestionServiceAchatController::class, 'store']); // Ajouter un service
    Route::put('/{id}', [GestionServiceAchatController::class, 'update']); // Modifier un service
    Route::delete('/{id}', [GestionServiceAchatController::class, 'destroy']); // Supprimer un service
});

// // DemandsController


Route::prefix('demandes')->group(function () {
    Route::get('/', [DemandsController::class, 'index']); // Liste des demandes
    Route::get('/{id}', [DemandsController::class, 'show']); // Détails d'une demande
    Route::post('/', [DemandsController::class, 'store']); // Ajouter une demande
    Route::put('/{id}', [DemandsController::class, 'update']); // Modifier une demande
    Route::delete('/{id}', [DemandsController::class, 'destroy']); // Supprimer une demande
});

// // UsersController


Route::prefix('users')->group(function () {
    Route::get('/', [UsersController::class, 'index']); // Liste des utilisateurs
    Route::get('/{id}', [UsersController::class, 'show']); // Détails d'un utilisateur
    Route::post('/', [UsersController::class, 'store']); // Ajouter un utilisateur
    Route::put('/{id}', [UsersController::class, 'update']); // Modifier un utilisateur
    Route::delete('/{id}', [UsersController::class, 'destroy']); // Supprimer un utilisateur
});
