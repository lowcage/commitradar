<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Cache;

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
})->name('index');

Route::get('/index', function () {
    return redirect()->route('index');
});

Route::get('/github/redirect', function (Illuminate\Http\Request $request) {
    $token = session('github_token');
    $owner = $request->input('owner');
    $repo = $request->input('repo');

    if (!$owner || !$repo) {
        return redirect('/')
            ->with('auth_error', 'Missing owner or repository name.');
    }

    if (!$token) {
        // Store owner and repo in session for use after authentication
        session(['owner' => $owner, 'repo' => $repo]);

        // Redirect to GitHub for authentication
        return Socialite::driver('github')
            ->scopes(['repo', 'read:org'])
            ->redirect();
    }

    // If token exists, directly redirect to github.repository
    return redirect()->route('github.repository', compact('owner', 'repo'));
})->name('github.redirect');

Route::get('/github/callback', function () {
    try {
        $user = Socialite::driver('github')->user();
        session(['github_token' => $user->token]);

        // Retrieve owner and repo from session
        $owner = session('owner');
        $repo = session('repo');

        // Ensure owner and repo exist
        if (!$owner || !$repo) {
            return redirect('/')
                ->with('auth_error', 'Missing owner or repository name after authentication.');
        }

        // Clear owner and repo from session
        session()->forget(['owner', 'repo']);

        // Redirect to the github.repository route with the owner and repo
        return redirect()->route('github.repository', compact('owner', 'repo'));
    } catch (\Exception $e) {
        // Handle authentication failure
        return redirect('/')
            ->with('auth_error', 'Authentication failed or was canceled.');
    }
})->name('github.callback');



//Purely for testing should not be in prod
Route::get('/github/logout', function () {
    // Remove the github_token from the session
    session()->forget('github_token');

    // Redirect to the index page with a success message
    return redirect('/')->with('success', 'You have been logged out of GitHub.');
})->name('github.logout');

Route::match(['GET', 'POST'], '/github/repository', function (Illuminate\Http\Request $request) {
    $token = session('github_token');
    $owner = $request->input('owner');
    $repo = $request->input('repo');

    if (!$token) {
        return redirect('/')->with('auth_error', 'No GitHub token found. Please authenticate first.');
    }

    if (!$owner || !$repo) {
        return redirect('/')->with('auth_error', 'Missing owner or repository name.');
    }

    // Get repository data
    $response = Http::withToken($token)->get("https://api.github.com/repos/$owner/$repo");
    if ($response->failed()) {
        return redirect('/')->with('auth_error', 'Failed to retrieve repository data.');
    }
    $repositoryData = $response->json();

    // Get contributors
    $contributorsResponse = Http::withToken($token)->get("https://api.github.com/repos/$owner/$repo/contributors");
    if ($contributorsResponse->failed()) {
        return redirect('/')->with('auth_error', 'Failed to retrieve contributors data.');
    }
    $contributors = $contributorsResponse->json();

    // Initialize stats
    foreach ($contributors as &$contributor) {
        $contributor['total_additions'] = 0;
        $contributor['total_deletions'] = 0;
    }

    // Get only the last 100 commits with stats
    $commitsResponse = Http::withToken($token)
        ->get("https://api.github.com/repos/$owner/$repo/commits", [
            'per_page' => 100,
            'page' => 1
        ]);
    
    if ($commitsResponse->successful()) {
        $commits = $commitsResponse->json();
        
        // Process commits in batches of 5 to avoid timeout
        $batchSize = 5;
        $batches = array_chunk($commits, $batchSize);
        
        foreach ($batches as $batch) {
            foreach ($batch as $commit) {
                $detailedCommitResponse = Http::withToken($token)
                    ->get("https://api.github.com/repos/$owner/$repo/commits/{$commit['sha']}");
                
                if ($detailedCommitResponse->successful()) {
                    $detailedCommit = $detailedCommitResponse->json();
                    $authorLogin = $detailedCommit['author']['login'] ?? null;
                    
                    if ($authorLogin) {
                        foreach ($contributors as &$contributor) {
                            if ($contributor['login'] === $authorLogin) {
                                $contributor['total_additions'] += $detailedCommit['stats']['additions'] ?? 0;
                                $contributor['total_deletions'] += $detailedCommit['stats']['deletions'] ?? 0;
                                break;
                            }
                        }
                    }
                }
            }
            
            // Add a small delay between batches to prevent rate limiting
            usleep(100000); // 100ms delay
        }
    }

    // Get closed issues count
    $closedIssuesResponse = Http::withToken($token)
        ->get("https://api.github.com/search/issues", [
            'q' => "repo:$owner/$repo is:issue is:closed",
            'per_page' => 1
        ]);
    
    if ($closedIssuesResponse->successful()) {
        $searchResult = $closedIssuesResponse->json();
        $repositoryData['closed_issues_count'] = $searchResult['total_count'];
    }

    return Inertia::render('RepositoryPage', [
        'token' => $token,
        'repository' => $repositoryData,
        'contributors' => $contributors,
        'commits' => array_slice($commits, 0, 50), // Keep last 50 commits for display
    ]);
})->name('github.repository');



require __DIR__.'/auth.php';
