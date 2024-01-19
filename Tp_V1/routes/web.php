<?php


use App\Http\Controllers\GestionProduitsController;
use Illuminate\Support\Facades\Route;


Route::resource('/produits', GestionProduitsController::class);
