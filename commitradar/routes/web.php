<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Index');
});

Route::get('/github/redirect', function () {
    // Redirect to GitHub for authentication
    return Socialite::driver('github')->redirect();
})->name('github.redirect');

Route::get('/github/callback', function (Request $request) {
    try {
        $user = Socialite::driver('github')->user();
        session(['github_token' => $user->token]);

        // Render the RepositoryPage if authentication is successful
        return Inertia::render('RepositoryPage', [
            'token' => $user->token,
        ]);
    } catch (\Exception $e) {
        // Handle 401 or other exceptions and redirect back to the index
        return redirect('/')
            ->with('auth_error', 'Authentication failed or was canceled.');
    }
})->name('github.callback');

require __DIR__.'/auth.php';
