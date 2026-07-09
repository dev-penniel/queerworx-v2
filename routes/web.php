<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Route::get('/', function () {
//     return view('frontend');
// })->name('home');

Volt::route('/', 'welcome')->name('home');
Volt::route('article/{slug}', 'article')->name('article');
Volt::route('articles', 'articles')->name('articles');
Volt::route('submit-story', 'submit-story')->name('submit-story');
Volt::route('search', 'search')->name('search');
Volt::route('join-us', 'join-us')->name('join-us');
Volt::route('support', 'support')->name('support');
Volt::route('events', 'events')->name('events');
Volt::route('events/{activity}', 'event-detail')->name('events.show');
Volt::route('community', 'community')->name('community');
Volt::route('programs', 'programs')->name('programs');
Volt::route('programs/{program}', 'program-events')->name('programs.show');
Volt::route('financials', 'financials')->name('financials');
Volt::route('policies', 'policies')->name('policies');
Volt::route('team', 'team')->name('team');
Volt::route('board', 'board')->name('board');
Volt::route('partners', 'partners')->name('partners');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Volt::route('/about', 'about')->name('about');

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

Route::middleware(['auth'])->group(function(){

    Volt::route('categories/index', 'categories.index')->name('categories.index');
     
});

Route::middleware(['auth'])->group(function(){

    Volt::route('resources/index', 'resources.index')->name('resources.index');
     
});

Route::middleware(['auth'])->group(function(){

    Volt::route('admin/documents', 'admin.documents')->name('admin.documents');
    Volt::route('admin/programs', 'admin.programs')->name('admin.programs');
    Volt::route('admin/team-members', 'admin.team-members')->name('admin.team-members');
    Volt::route('admin/join-us', 'admin.join-us')->name('admin.join-us');
    Volt::route('admin/support', 'admin.support')->name('admin.support');
     
});

Route::middleware(['auth'])->group(function(){

    Volt::route('adverts/index', 'adverts.index')->name('adverts.index');
     
});

Route::middleware(['auth'])->group(function(){

    Volt::route('subscribers/index', 'subscribers.index')->name('subscribers.index');
     
});

Route::middleware(['auth'])->group(function(){

    Volt::route('articles/index', 'articles.index')->name('articles.index');
    Volt::route('articles/create', 'articles.create')->name('articles.create');
    Volt::route('articles/{id}/edit', 'articles.edit')->name('articles.edit');
    Volt::route('comments/index', 'comments.index')->name('comments.index');
     
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
