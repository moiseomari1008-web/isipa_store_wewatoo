<?php

use App\Http\Controllers\AttributionController;
use App\Http\Controllers\CategorieProduitController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\CommandeProduitController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ProduitsPanierController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('utilisateurs', UserController::class)->parameters([
    'utilisateurs' => 'user',
]);

Route::apiResource('roles', RoleController::class);
Route::apiResource('permissions', PermissionController::class);
Route::apiResource('attributions', AttributionController::class);
Route::apiResource('categories-produits', CategorieProduitController::class)->parameters([
    'categories-produits' => 'categorie_produit',
]);
Route::apiResource('produits', ProduitController::class);
Route::apiResource('commandes', CommandeController::class);
Route::apiResource('paiements', PaiementController::class);
Route::apiResource('commande-produits', CommandeProduitController::class);
Route::apiResource('paniers', PanierController::class);
Route::apiResource('produits-paniers', ProduitsPanierController::class);
