<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Route::get('/', function () {
//     return view('frontend');
// })->name('home');

Volt::route('/', 'welcome')->name('home');
Volt::route('article/{slug}', 'article')->name('article');
Volt::route('articles', 'articles')->name('articles');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::middleware(['auth'])->group(function(){

    Volt::route('products/index', 'products.index')->name('products.index');
    Volt::route('products/create', 'products.create')->name('products.create')->middleware(['role:General Admin|Manager']);
    Volt::route('products/{id}/edit', 'products.edit')->name('products.edit')->middleware(['role:General Admin|Manager']);

});

Route::middleware(['auth', 'permission:category-list'])->group(function(){

    Volt::route('categories/index', 'categories.index')->name('categories.index');
     
});

Route::middleware(['auth'])->group(function(){

    Volt::route('resources/index', 'resources.index')->name('resources.index');
     
});

Route::middleware(['auth'])->group(function(){

    Volt::route('adverts/index', 'adverts.index')->name('adverts.index');
     
});

Route::middleware(['auth'])->group(function(){

    Volt::route('subscribers/index', 'subscribers.index')->name('subscribers.index');
     
});

Route::middleware(['auth', 'permission:article-list'])->group(function(){

    Volt::route('articles/index', 'articles.index')->name('articles.index');
    Volt::route('articles/create', 'articles.create')->name('articles.create');
    Volt::route('articles/{id}/edit', 'articles.edit')->name('articles.edit');
     
});

Route::middleware(['auth', 'permission:user-list'])->group(function(){

    Volt::route('users/index', 'users.index')->name('users.index');
    Volt::route('users/create', 'users.create')->name('users.create');
    Volt::route('users/{id}/edit', 'users.edit')->name('users.edit');

});

Route::middleware(['auth', 'permission:role-list'])->group(function(){

    Volt::route('roles/index', 'roles.index')->name('roles.index');
    Volt::route('roles/create', 'roles.create')->name('roles.create');
    Volt::route('roles/{id}/edit', 'roles.edit')->name('roles.edit');

});

require __DIR__.'/auth.php';
