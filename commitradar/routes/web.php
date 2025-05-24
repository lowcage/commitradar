<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;


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

        $owner = session('owner');
        $repo = session('repo');
        $startDate = session('startDate');
        $endDate = session('endDate');

        if (!$owner || !$repo) {
            return redirect('/')
                ->with('auth_error', 'Missing owner or repository name after authentication.');
        }

        session()->forget(['owner', 'repo', 'startDate', 'endDate']);

        // Redirect to the github.repository route with the owner and repo
        return redirect()->route('github.repository', compact('owner', 'repo', 'startDate', 'endDate'));
    } catch (\Exception $e) {
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

        if (count($pageIssues) === 0) break;

        foreach ($pageIssues as $issue) {
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
        'milestones' => $milestones,
    ]);
})->name('github.repository');

Route::post('/github/commits/details', function (Request $request) {
    $token = session('github_token');
    $owner = $request->input('owner');
    $repo = $request->input('repo');
    $shas = $request->input('shas');

    if (!$token || !$owner || !$repo || !is_array($shas)) {
        return response()->json(['error' => 'Invalid parameters'], 400);
    }

    $results = [];

    foreach ($shas as $sha) {
        $response = Http::withToken($token)
            ->get("https://api.github.com/repos/$owner/$repo/commits/$sha");

        if ($response->successful()) {
            $commitData = $response->json();
            $files = $commitData['files'] ?? [];
            $results[] = [
                'sha' => $sha,
                'additions' => $commitData['stats']['additions'] ?? 0,
                'deletions' => $commitData['stats']['deletions'] ?? 0,
                'fileCount' => count($files),
                'files' => collect($files)->pluck('filename')->toArray(), // used for touched stats
            ];
        }
    }

    return response()->json($results);
})->name('github.commits.details');

Route::post('/api/openai/commit-analysis', function (Request $request) {
    $commits = $request->input('commits');

    if (!is_array($commits) || count($commits) === 0 || count($commits) > 5) {
        return response()->json(['error' => 'Invalid or missing commits array'], 400);
    }

    $apiKey = env('OPENAI_API_KEY');
    $model = env('OPENAI_MODEL', 'gpt-4o'); // fallback: gpt-4o
    $promptTemplate = Config::get('openai.commit_analysis_prompt');

    if (!$apiKey || !$promptTemplate) {
        return response()->json(['error' => 'Missing configuration'], 500);
    }

    $finalPrompt = $promptTemplate . "\n\nInput:\n" . json_encode($commits, JSON_PRETTY_PRINT);

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiKey,
        'Content-Type' => 'application/json',
    ])->post('https://api.openai.com/v1/chat/completions', [
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => 'You are a commit quality evaluator.'],
            ['role' => 'user', 'content' => $finalPrompt],
        ],
        'temperature' => 0.2,
    ]);

    if ($response->failed()) {
        return response()->json([
            'error' => 'OpenAI API call failed',
            'details' => $response->json(),
        ], 500);
    }

    $output = $response->json();
    $text = $output['choices'][0]['message']['content'] ?? '';
    $text = preg_replace('/^```json\s*(.*?)\s*```$/s', '$1', trim($text));


    $parsed = json_decode($text, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return response()->json([
            'error' => 'Failed to parse JSON from OpenAI response',
            'raw' => $text,
        ], 500);
    }

    return response()->json($parsed);
})->name('github.ai.evaluate');


require __DIR__.'/auth.php';
