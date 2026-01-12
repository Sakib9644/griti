<?php

use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\UserController;
use App\Http\Controllers\Api\Auth\SocialLoginController;
use App\Http\Controllers\Api\BarcodeScanController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\FirebaseTokenController;
use App\Http\Controllers\Api\Frontend\categoryController;
use App\Http\Controllers\Api\Frontend\FaqController;
use App\Http\Controllers\Api\Frontend\HomeController;
use App\Http\Controllers\Api\Frontend\ImageController;
use App\Http\Controllers\Api\Frontend\PageController;
use App\Http\Controllers\Api\Frontend\PostController;
use App\Http\Controllers\Api\Frontend\SubcategoryController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\Frontend\SettingsController;
use App\Http\Controllers\Api\Frontend\SocialLinksController;
use App\Http\Controllers\Api\Frontend\SubscriberController;
use App\Http\Controllers\Api\Gateway\Stripe\StripeCallBackController;
use App\Http\Controllers\Api\OnboardingController;
use App\Http\Controllers\Api\PlaninfoController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ThemesController;
use App\Http\Controllers\FoodHealthController;
use App\Http\Controllers\MotivationAiController;
use App\Http\Controllers\NutrationController;
use App\Http\Controllers\OpenAiChatController;
use Illuminate\Support\Facades\Route;


//page
Route::get('/page/home', [HomeController::class, 'index']);


Route::middleware(['auth:api', 'is_trail'])->group(function () {
    Route::get('/category/{search?}', [categoryController::class, 'index']);
    Route::get('/categoryWiseWorkouts/{id}', [categoryController::class, 'categoryWiseWorkouts']);
    Route::get('/themeWiseWorkouts/{id}', [categoryController::class, 'themeWiseWorkouts']);
    Route::get('/trainingLevelWiseWorkouts', [categoryController::class, 'trainingLevelWiseWorkouts']);
    Route::get('/workoutWiseVideos/{id}', [categoryController::class, 'workoutWiseVideos']);
    Route::post('/active_workouts/save', [categoryController::class, 'active_workouts']);
    Route::post('/music/save', [UserController::class, 'assignmusic']);
    Route::get('/music/list', [UserController::class, 'music']);
    Route::get('/user/music', [UserController::class, 'user_music']);
    Route::get('/work_out_list', [categoryController::class, 'work_out_list']);
    Route::get('/themes', [ThemesController::class, 'themes']);
});

Route::GET('/category_wise_themes/{catid?}/{type?}', [categoryController::class, 'category_wise_themes']);
Route::get('/themes_wise_video/{id}', [categoryController::class, 'themes_wise_video']);
Route::get('/circels/{id}', [categoryController::class, 'circels']);
Route::get('/subcategory', [SubcategoryController::class, 'index']);
Route::get('/social/links', [SocialLinksController::class, 'index']);
Route::get('/settings', [SettingsController::class, 'index']);
Route::get('/faq', [FaqController::class, 'index']);
Route::post('subscriber/store', [SubscriberController::class, 'store'])->name('api.subscriber.store');

/*
# Post
*/
Route::middleware(['auth:api'])->controller(PostController::class)->prefix('auth/post')->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/show/{id}', 'show');
    Route::post('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

Route::middleware('auth:api')->controller([UserController::class])->name('sakib')->prefix('sakib')->group(function(){

});

Route::get('/posts', [PostController::class, 'posts']);
Route::get('/post/show/{post_id}', [PostController::class, 'post']);

Route::middleware(['auth:api'])->controller(ImageController::class)->prefix('auth/post/image')->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/delete/{id}', 'destroy');
});

Route::get('dynamic/page', [PageController::class, 'index']);
Route::get('dynamic/page/show/{slug}', [PageController::class, 'show']);

/*
# Auth Route
*/

Route::group(['middleware' => 'guest:api'], function ($router) {
    //register
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('/verify-email', [RegisterController::class, 'VerifyEmail']);
    Route::post('/resend-otp', [RegisterController::class, 'ResendOtp']);
    Route::post('/verify-otp', [RegisterController::class, 'VerifyEmail']);
    //login
    Route::post('login', [LoginController::class, 'login'])->name('api.login');
    Route::post('/forget-password', [ResetPasswordController::class, 'forgotPassword']);
    Route::post('/otp-token', [ResetPasswordController::class, 'MakeOtpToken']);
    Route::post('/reset-password', [ResetPasswordController::class, 'ResetPassword']);
    //social login
    Route::post('/social-login', [SocialLoginController::class, 'SocialLogin']);
});

Route::group(['middleware' => ['auth:api', 'api-otp']], function ($router) {
    Route::get('/refresh-token', [LoginController::class, 'refreshToken']);
    Route::get('/get_height_weight', [UserController::class, 'get_height_weight']);
    Route::post('/logout', [LogoutController::class, 'logout']);
    Route::get('/me', [UserController::class, 'me']);
    Route::get('/account/switch', [UserController::class, 'accountSwitch']);
    Route::post('/update-profile', [UserController::class, 'updateProfile']);
    Route::post('/update-avatar', [UserController::class, 'updateAvatar']);
    Route::delete('/delete-profile', [UserController::class, 'destroy']);
    Route::post('/height_weight/update', [UserController::class, 'height_weight']);
    Route::post('/password/update', [UserController::class, 'updatepass']);
});

/*
# Firebase Notification Route
*/

Route::middleware(['auth:api'])->controller(FirebaseTokenController::class)->prefix('firebase')->group(function () {
    Route::get("test", "test");
    Route::post("token/add", "store");
    Route::post("token/get", "getToken");
    Route::post("token/delete", "deleteToken");
});

/*
# In App Notification Route
*/

Route::middleware(['auth:api'])->controller(NotificationController::class)->prefix('notify')->group(function () {
    Route::get('test', 'test');
    Route::get('/', 'index');
    Route::get('status/read/all', 'readAll');
    Route::get('status/read/{id}', 'readSingle');
});

/*
# Chat Route
*/

Route::middleware(['auth:api'])->controller(ChatController::class)->prefix('auth/chat')->group(function () {
    Route::get('/list', 'list');
    Route::post('/send/{receiver_id}', 'send');
    Route::get('/conversation/{receiver_id}', 'conversation');
    Route::get('/room/{receiver_id}', 'room');
    Route::get('/search', 'search');
    Route::get('/seen/all/{receiver_id}', 'seenAll');
    Route::get('/seen/single/{chat_id}', 'seenSingle');
});

/*
# CMS
*/

Route::prefix('cms')->name('cms.')->group(function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');
});
Route::prefix('barcode')->name('barcode')->group(function () {
    Route::get('/{barcode}', [BarcodeScanController::class, 'getProduct']);
});


Route::prefix('plans')->name('barcode')->group(function () {
    Route::get('/', [PlaninfoController::class, 'index']);
    Route::get('/app', [PlaninfoController::class, 'index2']);
});
Route::prefix('reviews')->name('barcode')->group(function () {
    Route::get('/', [ReviewController::class, 'index']);
});
Route::prefix('user/info')->name('user/info')->group(function () {
    Route::POST('/', [OnboardingController::class, 'store']);
});
Route::prefix('nutration')->name('nutration')->group(function () {
    Route::POST('/store', [NutrationController::class, 'store']);
    Route::GET('/my', [NutrationController::class, 'mynutration']);
    Route::GET('/recipes', [NutrationController::class, 'recipes']);
    Route::Post('/chat', [OpenAiChatController::class, 'openAiChat']);
});
Route::prefix('motivation')->name('motivation')->group(function () {

    Route::POST('/chat', [MotivationAiController::class, 'openAiChat']);

});
Route::prefix('food_scanner')->name('food_scanner')->group(function () {

    Route::POST('/checkHealth', [FoodHealthController::class, 'checkHealth']);

});
