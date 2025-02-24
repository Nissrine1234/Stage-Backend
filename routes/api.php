<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    ProviderController,
    CallsController,
    FacturesController,
    DeliveriesController,
    OffresController,
    GestionServiceAchatController,
    DemandsController,
    UsersController
};
// Groupe principal API v1
Route::prefix('portail')->group(function () {

    // ✅ Authentification
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    });

    // ✅ Fournisseurs
    Route::prefix('providers')->group(function () {
        Route::post('/become', [ProviderController::class, 'becomeProvider']);
        Route::post('/create', [ProviderController::class, 'createProvider']);
        Route::put('/update/{id}', [ProviderController::class, 'updateProvider'])->middleware('auth:sanctum');
        Route::delete('/delete/{id}', [ProviderController::class, 'deleteProvider'])->middleware('auth:sanctum');
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/admin/fournisseurs/{id}/accept', [AdminController::class, 'acceptFournisseur']);
    });
    
    // ✅ Appels d'Offres
    Route::prefix('calls')->group(function () {
        Route::get('/', [CallsController::class, 'listCalls']);
        Route::post('/', [CallsController::class, 'createCall']);
        Route::put('/{id}', [CallsController::class, 'updateCall'])->middleware('auth:sanctum');
        Route::delete('/{id}', [CallsController::class, 'deleteCall'])->middleware('auth:sanctum');
        Route::get('/{id}', [CallsController::class, 'showCall']);
    });

    // ✅ Factures
    Route::prefix('factures')->group(function () {
        Route::get('/', [FacturesController::class, 'listFactures']);
        Route::post('/', [FacturesController::class, 'createFacture']);
        Route::get('/{id}', [FacturesController::class, 'showFacture']);
        Route::put('/{id}', [FacturesController::class, 'updateFacture']);
        Route::delete('/{id}', [FacturesController::class, 'deleteFacture']);
    });

    // ✅ Livraisons
    Route::prefix('deliveries')->group(function () {
        Route::get('/', [DeliveriesController::class, 'listDeliveries']);
        Route::post('/', [DeliveriesController::class, 'createDelivery']);
        Route::get('/{id}', [DeliveriesController::class, 'showDelivery']);
        Route::put('/{id}', [DeliveriesController::class, 'updateDelivery']);
        Route::delete('/{id}', [DeliveriesController::class, 'deleteDelivery']);
    });

    // ✅ Offres
    Route::prefix('offers')->group(function () {
        Route::get('/', [OffresController::class, 'listOffers']);
        Route::post('/', [OffresController::class, 'createOffer']);
        Route::get('/{id}', [OffresController::class, 'showOffer']);
        Route::put('/{id}', [OffresController::class, 'updateOffer']);
        Route::delete('/{id}', [OffresController::class, 'deleteOffer']);
    });

    // ✅ Services d'Achat
    Route::prefix('services-achat')->group(function () {
        Route::get('/', [GestionServiceAchatController::class, 'index']);
        Route::get('/{id}', [GestionServiceAchatController::class, 'show']);
        Route::post('/', [GestionServiceAchatController::class, 'store']);
        Route::put('/{id}', [GestionServiceAchatController::class, 'update']);
        Route::delete('/{id}', [GestionServiceAchatController::class, 'destroy']);
    });

    // ✅ Demandes
    Route::prefix('demands')->group(function () {
        Route::get('/', [DemandsController::class, 'index']);
        Route::get('/{id}', [DemandsController::class, 'show']);
        Route::post('/', [DemandsController::class, 'store']);
        Route::put('/{id}', [DemandsController::class, 'update']);
        Route::delete('/{id}', [DemandsController::class, 'destroy']);
    });

    // ✅ Utilisateurs
    Route::prefix('users')->group(function () {
        Route::get('/', [UsersController::class, 'index']);
        Route::get('/{id}', [UsersController::class, 'show']);
        Route::post('/', [UsersController::class, 'store']);
        Route::put('/{id}', [UsersController::class, 'update']);
        Route::delete('/{id}', [UsersController::class, 'destroy']);
    });
});
