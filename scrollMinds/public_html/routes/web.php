<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\HomeController;
use App\Models\Video;

Route::get('/home', [AuthController::class, 'showHome'])->name('home');
Route::post('/home', [AuthController::class, 'handleHomePost'])->name('home.post');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/user', [AuthController::class, 'showUser'])->name('user');
Route::post('/user', [AuthController::class, 'handleUserPost'])->name('user.post');

Route::resource('videos', VideoController::class)->only(['create', 'store']);
Route::get('/scroll', function () {
    return view('scroll.index');
})->name('scroll.index');

Route::get('/videos', function () {
    return Video::orderBy('fecha_subida', 'desc')->paginate(5);
});

Route::get('videos/{videoId}', [VideoController::class, 'show'])->name('videos.show');

Route::get('/tests/create/{videoId}', [TestController::class, 'create'])->name('tests.create');
Route::post('/tests/storeQuestions/{videoId}', [TestController::class, 'storeQuestions'])->name('tests.storeQuestions');

Route::get('/api/videos', [VideoController::class, 'index']);
Route::get('/api/videos-with-test', [VideoController::class, 'getVideos']);

Route::middleware('auth')->group(function () {
    Route::post('/api/preguntas/{pregunta}/responder', [TestController::class, 'responder']);
    Route::post('/api/tests/{test}/submit', [TestController::class, 'submitTest']);
});

Route::get('/api/auth-check', function (Illuminate\Http\Request $request) {
    if (auth()->check()) {
        return response()->json([
            'authenticated' => true,
            'user' => auth()->user()
        ]);
    } else {
        return response()->json([
            'authenticated' => false,
            'user' => null
        ]);
    }
});

Route::post('videos/{videoId}/like', [VideoController::class, 'like'])->name('videos.like');

Route::get('/clear-cache', function () {
    Artisan::call('config:cache');
    return 'Cache cleared';
});