<?php

use App\Http\Controllers\Api\Auth\SocialLoginController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Web\Backend\Access\UserController;
use App\Http\Controllers\Web\Backend\PlanController;
use App\Http\Controllers\Web\Frontend\AffiliateController;
use App\Http\Controllers\Web\Frontend\ContactController;
use App\Http\Controllers\Web\Frontend\HomeController;
use App\Http\Controllers\Web\Frontend\SubscriberController;
use App\Http\Controllers\Web\NotificationController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/',[HomeController::class, 'index'])->name('home');

Route::get('/affiliate/{slug}',[AffiliateController::class, 'store'])->name('store');

Route::get('/post',[HomeController::class, 'index'])->name('post.index');
Route::get('/post/show/{slug}',[HomeController::class, 'post'])->name('post.show');

//Social login test routes
Route::get('social-login/{provider}',[SocialLoginController::class,'RedirectToProvider'])->name('social.login');
Route::get('social-login/{provider}/callback',[SocialLoginController::class, 'HandleProviderCallback']);

Route::post('subscriber/store',[SubscriberController::class, 'store'])->name('subscriber.data.store');
Route::post('/admin/music/{id}/default', [MusicController::class, 'setDefault'])->name('admin.music.default');

Route::post('contact/store',[ContactController::class, 'store'])->name('contact.store');

Route::get('/php-version', function() {
    return phpversion();
});


Route::GET('user/info',[HomeController::class, 'user'])->name('user.info');

Route::controller(NotificationController::class)->prefix('notification')->name('notification.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('read/single/{id}', 'readSingle')->name('read.single');
    Route::POST('read/all', 'readAll')->name('read.all');
})->middleware('auth');

Route::get('migrate', function () {
    Artisan::call('migrate:fresh', [
        '--seed' => true // optional, runs seeders after migration
    ]);

    return 'Database migrated successfully!';
});
Route::resource('subscriptions-plans', PlanController::class);
Route::resource('reviews', ReviewController::class);

require __DIR__.'/auth.php';
require __DIR__.'/api-stripe.php';
