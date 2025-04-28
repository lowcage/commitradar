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

    // Initialize stats, but we won't aggregate additions/deletions now
    foreach ($contributors as &$contributor) {
        $contributor['total_additions'] = 0;
        $contributor['total_deletions'] = 0;
    }

    // Get only the last 50 commits without fetching detailed stats
    $commitsResponse = Http::withToken($token)
        ->get("https://api.github.com/repos/$owner/$repo/commits", [
            'per_page' => 50,
            'page' => 1
        ]);

    $commits = [];
    if ($commitsResponse->successful()) {
        $commitsRaw = $commitsResponse->json();

        // From the basic commit list we extract the needed data
        foreach ($commitsRaw as $commit) {
            $commits[] = [
                'sha' => $commit['sha'],
                'html_url' => $commit['html_url'],
                'commit' => [
                    'author' => [
                        'name' => $commit['commit']['author']['name'],
                        'date' => $commit['commit']['author']['date'],
                    ],
                    'message' => $commit['commit']['message'],
                ],
                'author' => $commit['author'] ? [
                    'login' => $commit['author']['login'],
                    'avatar_url' => $commit['author']['avatar_url'],
                ] : null,
                // We skip additions/deletions stats because that needs the /commits/:sha call!
            ];
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

    // Get contributors
    $contributorsResponse = Http::withToken($token)->get("https://api.github.com/repos/$owner/$repo/contributors");
    if ($contributorsResponse->failed()) {
        return redirect('/')->with('auth_error', 'Failed to retrieve contributors data.');
    }
    $contributors = $contributorsResponse->json();

    // Calculate total commits from contributors
    $totalCommits = 0;
    foreach ($contributors as &$contributor) {
        $contributor['total_additions'] = 0;
        $contributor['total_deletions'] = 0;
        $totalCommits += $contributor['contributions'] ?? 0;
    }

    // Get commit activity (52 weeks)
    $commitActivityResponse = Http::withToken($token)
        ->get("https://api.github.com/repos/$owner/$repo/stats/commit_activity");

    $commitActivity = [];

    if ($commitActivityResponse->successful()) {
        $commitActivity = $commitActivityResponse->json();
    }


    return Inertia::render('RepositoryPage', [
        'token' => $token,
        'repository' => $repositoryData,
        'contributors' => $contributors,
        'commits' => $commits,
        'totalCommits' => $totalCommits,
        'commitActivity' => $commitActivity,
        'owner' => $owner,
        'repo' => $repo,
    ]);
})->name('github.repository');




require __DIR__.'/auth.php';
