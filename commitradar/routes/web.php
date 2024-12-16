<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;
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

Route::get('/github/redirect', function (Illuminate\Http\Request $request) {
    $token = session('github_token');
    $repositoryUrl = $request->input('repository_url');
    if(!$token) {
        // Without token redirect to GitHub for authentication
        return Socialite::driver('github')
            ->scopes(['repo', 'read:org'])
            ->redirect();
    }
})->name('github.redirect');

Route::get('/github/callback', function (Request $request) {
    try {
        $user = Socialite::driver('github')->user();
        session(['github_token' => $user->token]);

        // Render the RepositoryPage if authentication is successful
        return Inertia::render('Index');
    } catch (\Exception $e) {
        // Handle 401 or other exceptions and redirect back to the index
        return redirect('/')
            ->with('auth_error', 'Authentication failed or was canceled.');
    }
})->name('github.callback');

Route::get('/github/logout', function () {
    // Remove the github_token from the session
    session()->forget('github_token');

    // Redirect to the index page with a success message
    return redirect('/')->with('success', 'You have been logged out of GitHub.');
})->name('github.logout');

Route::post('/github/repository', function (Illuminate\Http\Request $request) {
    $token = session('github_token');
    $repositoryUrl = $request->input('repository_url');

    if (!$token) {
        return redirect('/')
            ->with('auth_error', 'No GitHub token found. Please authenticate first.');
    }

    // Remove .git suffix if it exists
    if (str_ends_with($repositoryUrl, '.git')) {
        $repositoryUrl = substr($repositoryUrl, 0, -4);
    }

    // Extract owner and repo from the inputted URL
    preg_match('/github\.com\/([^\/]+)\/([^\/]+)/', $repositoryUrl, $matches);

    if (count($matches) < 3) {
        return redirect('/')
            ->with('auth_error', 'Invalid repository URL.');
    }

    $owner = $matches[1];
    $repo = $matches[2];

    // Make the GitHub API call
    $response = Http::withToken($token)->get("https://api.github.com/repos/$owner/$repo");
    if ($response->failed()) {
        return redirect('/')
            ->with('auth_error', 'Failed to retrieve repository data.');
    }

    $repositoryData = $response->json();

    // Render the RepositoryPage with data
    return Inertia::render('RepositoryPage', [
        'token' => $token,
        'repository' => $repositoryData,
    ]);
})->name('github.repository');

require __DIR__.'/auth.php';
