<?php


use App\Http\Controllers\GestionProduitsController;
use Illuminate\Support\Facades\Route;


Route::get('/', [GestionProduitsController::class,'acceuil'])->name('produits.acceuil');
Route::get('/devis', [GestionProduitsController::class,'downloadAll'])->name('produits.devis');
Route::post('/devisone/{id}', [GestionProduitsController::class,'download'])->name('produits.devisone');
Route::get('/cart', [GestionProduitsController::class,'cart'])->name('produits.cart');
Route::delete('/deletefromcart/{id}', [GestionProduitsController::class,'deletefromcart'])->name('produits.deletefromcart');
Route::delete('/deleteallfromcart', [GestionProduitsController::class,'deleteallfromcart'])->name('produits.deleteallfromcart');
Route::post('/addtocart/{produit}', [GestionProduitsController::class,'addtocart'])->name('produits.addtocart');
Route::get('/produits/export', [GestionProduitsController::class,'export'])->name('produits.export');
Route::post('/produits/import', [GestionProduitsController::class,'import'])->name('produits.import');
Route::resource('/produits', GestionProduitsController::class);
