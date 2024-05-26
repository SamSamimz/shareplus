<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Route;


Route::get('/login',Login::class)->name('login');
Route::get('/register',Register::class)->name('register');








?>