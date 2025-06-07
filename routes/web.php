<?php

use App\Livewire\Auth\LoginPage;
use App\Livewire\Landing\LandingPage;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingPage::class)->name('landing.page');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', LoginPage::class)->name('login.page');
});
