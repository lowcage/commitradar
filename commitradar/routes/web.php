<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
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
    $startDate = $request->input('startDate');
    $endDate = $request->input('endDate');

    if (!$owner || !$repo) {
        return redirect('/')
            ->with('auth_error', 'Missing owner or repository name.');
    }

    if (!$token) {
        // Store owner and repo in session for use after authentication
        session([
            'owner' => $owner,
            'repo' => $repo,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

        // Redirect to GitHub for authentication
        return Socialite::driver('github')
            ->scopes(['repo', 'read:org'])
            ->redirect();
    }

    // If token exists, directly redirect to github.repository
    return redirect()->route('github.repository', compact('owner', 'repo', 'startDate', 'endDate'));
})->name('github.redirect');

Route::get('/github/callback', function () {
    try {
        $user = Socialite::driver('github')->user();
        session(['github_token' => $user->token]);

        // Retrieve owner and repo from session
        $owner = session('owner');
        $repo = session('repo');
        $startDate = session('startDate');
        $endDate = session('endDate');

        // Ensure owner and repo exist
        if (!$owner || !$repo) {
            return redirect('/')
                ->with('auth_error', 'Missing owner or repository name after authentication.');
        }

        // Clear owner and repo from session
        session()->forget(['owner', 'repo', 'startDate', 'endDate']);

        // Redirect to the github.repository route with the owner and repo
        return redirect()->route('github.repository', compact('owner', 'repo', 'startDate', 'endDate'));
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
    $startDate = $request->input('startDate', session('startDate'));
    $endDate = $request->input('endDate', session('endDate'));

    $issues = [];
    $page = 1;
    $maxPages = 10;

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
            'page' => 1,
            'since' => $startDate . 'T00:00:00Z',
            'until' => $endDate . 'T23:59:59Z',
        ]);

    $commits = [];

    $hasMoreCommits = false;
    $commitsEmpty = true;

    if ($commitsResponse->successful()) {
        $commitsRaw = $commitsResponse->json();
        $commitsEmpty = count($commitsRaw) === 0;
        $hasMoreCommits = count($commitsRaw) === 50;


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

    //Get latest 500 issues
    while ($page <= $maxPages) {
        $issuesResponse = Http::withToken($token)->get("https://api.github.com/repos/$owner/$repo/issues", [
            'per_page' => 50,
            'page' => $page,
            'state' => 'all',
            'direction' => 'desc',
        ]);

        if ($issuesResponse->failed()) break;

        $pageIssues = $issuesResponse->json();

        // Stop if no more issues
        if (count($pageIssues) === 0) break;

        foreach ($pageIssues as $issue) {
            // Skip pull requests if needed (if you want only pure issues)
            $isPR = isset($issue['pull_request']);

            $issues[] = [
                'id' => $issue['id'],
                'number' => $issue['number'],
                'title' => $issue['title'],
                'body' => $issue['body'],
                'state' => $issue['state'],
                'created_at' => $issue['created_at'],
                'closed_at' => $issue['closed_at'] ?? null,
                'user' => [
                    'login' => $issue['user']['login'] ?? null,
                ],
                'assignees' => array_map(fn($a) => $a['login'], $issue['assignees'] ?? []),
                'closed_by' => $issue['closed_by']['login'] ?? null,
                'milestone' => $issue['milestone']['title'] ?? null,
                'pull_request' => $isPR,
            ];
        }

        if (count($pageIssues) < 50) break;
        $page++;
    }

    // Get milestones
    $milestonesResponse = Http::withToken($token)
        ->get("https://api.github.com/repos/$owner/$repo/milestones", [
            'state' => 'all',
            'per_page' => 100
        ]);

    $milestones = [];
    if ($milestonesResponse->successful()) {
        $milestones = $milestonesResponse->json();
    }


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
        'hasMoreCommits' => $hasMoreCommits,
        'commitsEmpty' => $commitsEmpty,
        'totalCommits' => $totalCommits,
        'commitActivity' => $commitActivity,
        'owner' => $owner,
        'repo' => $repo,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'issues' => $issues,
        'milestones' => $milestones
    ]);
})->name('github.repository');

Route::post('/github/commits/details', function (Request $request) {
    $token = session('github_token');
    $owner = $request->input('owner');
    $repo = $request->input('repo');
    $shas = $request->input('shas'); // ez egy tömb lesz

    if (!$token || !$owner || !$repo || !is_array($shas)) {
        return response()->json(['error' => 'Invalid parameters'], 400);
    }

    $results = [];

    foreach ($shas as $sha) {
        $response = Http::withToken($token)
            ->get("https://api.github.com/repos/$owner/$repo/commits/$sha");

        if ($response->successful()) {
            $commitData = $response->json();
            $results[] = [
                'sha' => $sha,
                'additions' => $commitData['stats']['additions'] ?? 0,
                'deletions' => $commitData['stats']['deletions'] ?? 0,
            ];
        }
    }

    return response()->json($results);
})->name('github.commits.details');

require __DIR__.'/auth.php';
