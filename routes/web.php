<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\WorkController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/work/{project:slug}', [WorkController::class, 'show'])->name('work.show');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/locale', [LocaleController::class, 'update'])->name('locale.update');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('projects', ProjectController::class)->except(['show']);
        Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);
        Route::resource('skills', SkillController::class)->except(['show', 'create', 'edit']);
        Route::resource('services', ServiceController::class)->except(['show', 'create', 'edit']);
        Route::resource('social-links', SocialLinkController::class)
            ->parameters(['social-links' => 'social_link'])
            ->except(['show', 'create', 'edit']);
        Route::resource('settings', SettingController::class)->except(['show', 'create', 'edit']);
        Route::resource('messages', ContactMessageController::class)
            ->parameters(['messages' => 'contact_message'])
            ->only(['index', 'update', 'destroy']);

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});
