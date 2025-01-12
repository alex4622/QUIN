<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\AchatController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ProduitController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/ventes/create', [VenteController::class, 'create'])->name('ventes.create');
Route::post('/ventes', [VenteController::class, 'store'])->name('ventes.store');
Route::put('/ventes/{vente}', [VenteController::class, 'update'])->name('ventes.update');
Route::get('/ventes/pdf', [VenteController::class, 'generatePDF'])->name('ventes.pdf');
Route::delete('/ventes/{id}', [VenteController::class, 'destroy'])->name('ventes.destroy');

Route::get('/achats/create', [AchatController::class, 'create'])->name('achats.create');
Route::post('/achats', [AchatController::class, 'store'])->name('achats.store');
Route::put('/achats/{achat}', [AchatController::class, 'update'])->name('achats.update');
Route::delete('/achats/{id}', [AchatController::class, 'destroy'])->name('achats.destroy');

// Routes pour les clients
Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
Route::delete('/clients/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');

// Routes pour les fournisseurs
Route::get('/fournisseurs', [FournisseurController::class, 'index'])->name('fournisseurs.index');
Route::post('/fournisseurs', [FournisseurController::class, 'store'])->name('fournisseurs.store');
Route::put('/fournisseurs/{fournisseur}', [FournisseurController::class, 'update'])->name('fournisseurs.update');
Route::delete('/fournisseurs/{id}', [FournisseurController::class, 'destroy'])->name('fournisseurs.destroy');

// Routes pour la gestion des stocks
Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
Route::post('/stocks/{produit}/ajuster', [StockController::class, 'ajuster'])->name('stocks.ajuster');
Route::post('/stocks/{produit}/minimum', [StockController::class, 'updateMinimum'])->name('stocks.minimum');

// Routes pour les produits
Route::get('/produits', [ProduitController::class, 'index'])->name('produits.index');
Route::post('/produits', [ProduitController::class, 'store'])->name('produits.store');
Route::put('/produits/{produit}', [ProduitController::class, 'update'])->name('produits.update');

Route::get('/ventes', [VenteController::class, 'index'])->name('ventes.index');
Route::get('/achats', [AchatController::class, 'index'])->name('achats.index');
