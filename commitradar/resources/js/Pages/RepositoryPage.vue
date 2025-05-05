<template>
    <div class="repository-page">
        <div class="page-header">
            <h1 class="page-title">Repository Details: <span class="repo-name">{{ repository.name }}</span></h1>
            <Link :href="route('index')" class="back-button" as="button">
                Back to Home
            </Link>
        </div>
        <div class="info-grid">
            <div class="info-card">
                <div class="info-header">
                    <h3>Basic Info</h3>
                </div>
                <div class="info-content">
                    <div class="info-item">
                        <span class="info-label">Name</span>
                        <span class="info-value">{{ repository.name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Owner</span>
                        <span class="info-value">{{ repository.owner.login }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Description</span>
                        <span class="info-value">{{ repository.description || 'No description available' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Primary Language</span>
                        <span class="info-value">{{ repository.language }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Visibility</span>
                        <span class="info-value">{{ repository.private ? 'Private' : 'Public' }}</span>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-header">
                    <h3>Stats</h3>
                </div>
                <div class="info-content">
                    <div class="stat-grid">
                        <div class="stat-row">
                            <div class="stat-item">
                                <span class="stat-value stat-stars">{{ repository.stargazers_count }}</span>
                                <span class="stat-label">Stars</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-value stat-forks">{{ repository.forks_count }}</span>
                                <span class="stat-label">Forks</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-value stat-watchers">{{ repository.watchers_count }}</span>
                                <span class="stat-label">Watchers</span>
                            </div>
                        </div>
                        <div class="stat-row">
                            <div class="stat-item">
                                <span class="stat-value stat-open-issues">{{ repository.open_issues_count }}</span>
                                <span class="stat-label">Open Issues</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-value stat-closed-issues">{{ repository.closed_issues_count || 0 }}</span>
                                <span class="stat-label">Closed Issues</span>
                            </div>
                        </div>
                        <div class="stat-row single-stat-row">
                            <div class="stat-item">
                                <span class="stat-value">{{ totalCommits }}</span>
                                <span class="stat-label">Total Commits (Top Contributors)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-header">
                    <h3>Details</h3>
                </div>
                <div class="info-content">
                    <div class="info-item">
                        <span class="info-label">Default Branch</span>
                        <span class="info-value">{{ repository.default_branch }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Created</span>
                        <span class="info-value">{{ formatDate(repository.created_at) }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Last Updated</span>
                        <span class="info-value">{{ formatDate(repository.updated_at) }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Size</span>
                        <span class="info-value">{{ repository.size }} KB</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">License</span>
                        <span class="info-value">{{ repository.license?.name || 'No license specified' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">URL</span>
                        <a :href="repository.html_url" target="_blank" class="repo-link">View on GitHub</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="contributors-section">
            <div class="info-card">
                <div class="info-header">
                    <h3>Contributors</h3>
                </div>
                <div class="info-content">
                    <div class="contributors-list">
                        <div
                            v-for="contributor in contributors"
                            :key="contributor.id"
                            class="contributor-stats-card"
                        >
                            <!-- Kép + név -->
                            <div class="contributor-header">
                                <img :src="contributor.avatar_url" :alt="contributor.login" class="contributor-avatar-large" />
                                <a :href="contributor.html_url" target="_blank" class="contributor-name">{{ contributor.login }}</a>
                            </div>

                            <!-- Commits & lines -->
                            <div class="contributor-meta">
                                <span>{{ contributor.contributions }} commits</span>
                                <span v-if="contributor.filtered_lines_total > 0">
                {{ contributor.filtered_lines_total }} lines changed
            </span>
                            </div>

                            <!-- Dummy stat grid -->
                            <div class="contributor-stat-grid">
                                <div class="stat-box">
                                    <div class="stat-value">5.3</div>
                                    <div class="stat-label">Avg. Commit Score</div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-value">87</div>
                                    <div class="stat-label">Lines/Day</div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-value">12</div>
                                    <div class="stat-label">Active Days</div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-value">21</div>
                                    <div class="stat-label">Above Baseline</div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-value">9%</div>
                                    <div class="stat-label">Of All Commits</div>
                                </div>
                            </div>

                            <!-- Final usefulness score -->
                            <div class="contributor-final-score">
                                <span class="score-label">Usefulness Score:</span>
                                <span class="score-value">7.8 / 10</span>
                            </div>
                        </div>
                    </div>

                    <div class="contributor-notes">
                        <p class="info-note">
                            Note: Only main contributors are listed here. Some commits might belong to other authors.
                        </p>
                        <p v-if="allCommits.length > totalCommits" class="info-note info-warning">
                            ⚠️ Discrepancy detected: More commits found than contributors' total.
                            <br>
                            This happens when commits are authored by users not officially listed as contributors by GitHub.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-card">
            <div class="info-header">
                <p v-if="Object.keys(detailedCommits).length === 0" class="commit-note">
                    ℹ️ Showing basic commit info. Detailed stats (additions/deletions) not loaded.
                </p>
                <h3>Recent {{ allCommits.length }} Commits</h3>
                <div class="baseline-control">
                    <label for="baseline-slider">Baseline: {{ baseline }} lines</label>
                    <input
                        id="baseline-slider"
                        type="range"
                        min="0"
                        max="50"
                        step="1"
                        v-model="baseline"
                    />
                </div>
            </div>
            <div class="info-content">
                <div class="commits-slider">
                    <div class="commits-track">
                        <div v-for="commit in allCommits" :key="commit.sha" class="commit-card" :class="{ 'commit-muted': isBelowBaseline(commit) }">
                            <div class="commit-header">
                                <img
                                    :src="commit.author?.avatar_url"
                                    :alt="commit.author?.login || commit.commit.author.name"
                                    class="commit-avatar"
                                >
                                <div class="commit-author">
                                    <span class="author-name">{{ commit.author?.login || commit.commit.author.name }}</span>
                                    <span class="commit-date">{{ formatDate(commit.commit.author.date) }}</span>
                                </div>
                                <div class="commit-stats">
                                    <span class="additions">+{{ commit.stats?.additions || 0 }}</span>
                                    <span class="deletions">-{{ commit.stats?.deletions || 0 }}</span>
                                </div>
                            </div>
                            <div class="commit-message">{{ commit.commit.message }}</div>
                            <div class="commit-sha">
                                <a :href="commit.html_url" target="_blank" class="sha-link">
                                    {{ commit.sha.substring(0, 7) }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="load-commits-details-wrapper">
                    <button
                        @click="loadAllCommitsAndDetails"
                        :disabled="loadingMore || loadingDetailed"
                        class="load-everything-button"
                    >
                        {{ loadingDetailed ? `Loading Details... ${detailedProgress}/${allCommits.length}` : 'Load All Commits + Details' }}
                    </button>
                </div>

                <!---<div class="load-more-container">
                    <button
                        v-if="!noMoreCommits"
                        @click="loadMoreCommits"
                        class="load-more-button"
                        :disabled="loadingMore"
                    >
                        {{ loadingMore ? 'Loading...' : 'Load More Commits' }}
                    </button>
                    <p v-else class="no-more-text">No more commits available.</p>
                </div>-->
            </div>
        </div>

        <div class="load-detailed-container">
            <button
                v-if="!loadingDetailed"
                @click="loadAllCommitDetails(5)"
                class="load-detailed-button"
            >
                Load Detailed Commit Stats
            </button>
            <div v-else class="detailed-progress">
                Loading... {{ detailedProgress }}/{{ allCommits.length }}
            </div>
        </div>


        <CommitActivityChart :activity="commitActivity" />
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import CommitActivityChart from "@/Components/CommitActivityChart.vue";

export default {
    data() {
        return {
            detailedCommits: {}, // sha -> { additions, deletions }
            loadingDetailed: false,
            detailedProgress: 0,
            page: 2, // 1-et már lekértünk backendről
            allCommits: [...this.commits],
            loadingMore: false,
            noMoreCommits: false,
            baseline: 10,
        };
    },

    components: {
        CommitActivityChart,
        Link,
    },
    props: {
        token: {
            type: String,
            required: true,
        },
        repository: {
            type: Object,
            required: true,
        },
        contributors: {
            type: Array,
            required: true,
        },
        commits: {
            type: Array,
            required: true,
        },
        totalCommits: {
            type: Number,
            required: true
        },
        commitActivity: {
            type: Array,
            required: true,
        },
        owner: { type: String, required: true },
        repo: { type: String, required: true },
    },
    methods: {
        async loadMoreCommits() {
            if (this.loadingMore || this.noMoreCommits) return;

            this.loadingMore = true;
            try {
                const response = await fetch(`https://api.github.com/repos/${this.owner}/${this.repo}/commits?per_page=50&page=${this.page}`, {
                    headers: {
                        Authorization: `Bearer ${this.token}`,
                        Accept: 'application/vnd.github.v3+json',
                    }
                });

                if (response.ok) {
                    const newCommits = await response.json();

                    if (newCommits.length === 0) {
                        this.noMoreCommits = true;
                    } else {
                        this.allCommits.push(...newCommits.map(commit => ({
                            sha: commit.sha,
                            html_url: commit.html_url,
                            commit: {
                                author: {
                                    name: commit.commit.author.name,
                                    date: commit.commit.author.date,
                                },
                                message: commit.commit.message,
                            },
                            author: commit.author ? {
                                login: commit.author.login,
                                avatar_url: commit.author.avatar_url,
                            } : null,
                        })));
                        this.page++;
                    }
                } else {
                    console.error('Failed to load more commits');
                    this.noMoreCommits = true;
                }
            } catch (error) {
                console.error('Error fetching commits:', error);
                this.noMoreCommits = true;
            } finally {
                this.loadingMore = false;
            }
        },

        async loadAllCommitDetails(batchSize = 5) {
            if (this.loadingDetailed) return;

            this.loadingDetailed = true;
            this.detailedProgress = 0;

            while (true) {
                const remainingCommits = this.allCommits.filter(c => !this.detailedCommits[c.sha]);
                const batch = remainingCommits.slice(0, batchSize);

                if (batch.length === 0) break; // vége

                try {
                    const response = await fetch(route('github.commits.details'), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({
                            owner: this.owner,
                            repo: this.repo,
                            shas: batch.map(c => c.sha),
                        }),
                    });

                    if (response.ok) {
                        const detailedBatch = await response.json();

                        detailedBatch.forEach(commit => {
                            // mentjük külön map-be
                            this.detailedCommits[commit.sha] = {
                                additions: commit.additions,
                                deletions: commit.deletions,
                            };

                            // és frissítjük az allCommits tömbben is, hogy a UI lássa
                            const found = this.allCommits.find(c => c.sha === commit.sha);
                            if (found) {
                                found.stats = {
                                    additions: commit.additions,
                                    deletions: commit.deletions,
                                };
                            }
                        });

                        this.detailedProgress += batch.length;
                    } else {
                        console.error('Failed to load commit details');
                        break;
                    }
                } catch (error) {
                    console.error('Error loading detailed commits', error);
                    break;
                }

                await new Promise(resolve => setTimeout(resolve, 300)); // 300ms delay
            }

            this.loadingDetailed = false;
            this.calculateFilteredContributorStats();
        },

        async loadAllCommitsAndDetails() {
            while (!this.noMoreCommits) {
                await this.loadMoreCommits();
            }

            await this.loadAllCommitDetails(5);
        },

        formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString(undefined, options);
        },
        commitLines(commit) {
            const stats = commit.stats;
            if (!stats) return 0;
            return stats.additions + Math.abs(stats.deletions);
        },
        isBelowBaseline(commit) {
            return this.commitLines(commit) < this.baseline;
        },

        calculateFilteredContributorStats() {
            // nullázás minden meglévő contributorra
            this.contributors.forEach(contributor => {
                contributor.filtered_lines_total = 0;
            });

            // commitokon végigmegyünk
            this.allCommits.forEach(commit => {
                const stats = commit.stats;
                const authorLogin = commit.author?.login;

                if (!stats || !authorLogin) return;

                const lines = stats.additions + Math.abs(stats.deletions);
                if (lines < this.baseline) return;

                const contributor = this.contributors.find(c => c.login === authorLogin);
                if (contributor) {
                    contributor.filtered_lines_total += lines;
                }
            });
        }

    },
    watch: {
        baseline(newVal) {
            this.calculateFilteredContributorStats();
        },
    }
};
</script>

<style>
html, body {
    margin: 0;
    padding: 0;
    height: 100%;
    background: linear-gradient(135deg, #4a00e0, #8e2de2);
    overflow-x: hidden;
}

.repository-page {
    padding: 2rem;
    width: 100%;
    box-sizing: border-box;
    min-height: 100vh;
    overflow-y: auto;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.back-button {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background-color: rgba(255, 255, 255, 0.1);
    color: white;
    border-radius: 0.5rem;
    font-weight: 500;
    text-decoration: none;
    transition: background-color 0.2s;
    backdrop-filter: blur(10px);
}

.back-button:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

.page-title {
    font-size: 2rem;
    font-weight: 600;
    color: white;
    margin-bottom: 2rem;
}

.repo-name {
    color: #60A5FA;
    font-style: italic;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.info-card, .contributor-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.info-header {
    background: #f8fafc;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.info-header h3 {
    margin: 0;
    font-size: 1.25rem;
    color: #374151;
    font-weight: 600;
}

.info-content {
    padding: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-label {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0.25rem;
}

.info-value {
    color: #111827;
    font-weight: 500;
}

.stat-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.stat-row {
    display: grid;
    gap: 1rem;
}

.stat-row:first-child {
    grid-template-columns: repeat(3, 1fr);
}

.stat-row:last-child {
    grid-template-columns: repeat(2, 1fr);
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 8px;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 600;
}

.stat-stars {
    color: #EAB308;
}

.stat-forks {
    color: #60A5FA;
}

.stat-watchers {
    color: #2563eb;
}

.stat-open-issues {
    color: #22C55E;
}

.stat-closed-issues {
    color: #EF4444;
}

.stat-label {
    font-size: 0.875rem;
    color: #6b7280;
    margin-top: 0.25rem;
}

.repo-link {
    color: #2563eb;
    text-decoration: none;
    font-weight: 500;
}

.repo-link:hover {
    text-decoration: underline;
}

.contributors-list {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    justify-content: center;
}

.contributor-card {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.contributor-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 1rem;
}

.contributor-info {
    display: flex;
    flex-direction: column;
}

.contributor-name {
    color: #2563eb;
    text-decoration: none;
    font-weight: 500;
}

.contributor-name:hover {
    text-decoration: underline;
}

.contributions {
    font-size: 0.875rem;
    color: #6b7280;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
}

.commits-slider {
    width: 100%;
    overflow-x: auto;
    padding: 1rem 0;
    scroll-behavior: smooth;
}

.commits-track {
    display: flex;
    gap: 1rem;
    padding: 0.5rem;
}

.commit-card {
    flex: 0 0 280px;
    background: white;
    border-radius: 12px;
    padding: 1.25rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s;
}

.commit-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

.commits-slider::-webkit-scrollbar {
    height: 8px;
}

.commits-slider::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.commits-slider::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}


.commit-header {
    display: flex;
    align-items: center;
    margin-bottom: 0.75rem;
}

.commit-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    margin-right: 0.75rem;
}

.commit-author {
    display: flex;
    flex-direction: column;
}

.author-name {
    font-weight: 500;
    color: #111827;
    font-size: 0.875rem;
}

.commit-date {
    font-size: 0.75rem;
    color: #6b7280;
}

.commit-message {
    font-size: 0.875rem;
    color: #374151;
    margin-bottom: 0.75rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.additions {
    color: #22C55E;
}

.deletions {
    color: #EF4444;
}

.commit-sha {
    font-family: monospace;
    font-size: 0.875rem;
}

.sha-link {
    color: #2563eb;
    text-decoration: none;
}

.sha-link:hover {
    text-decoration: underline;
}

.contributor-stats {
    display: flex;
    gap: 0.75rem;
    margin-top: 0.25rem;
    font-family: monospace;
    font-size: 0.875rem;
}

.load-more-container {
    text-align: center;
    margin-top: 1.5rem;
}

.load-more-button {
    background-color: #2563eb;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s;
}

.load-more-button:hover {
    background-color: #1e40af;
}

.contributors-section {
    margin-bottom: 2rem;
}

.contributor-notes {
    margin-top: 2rem;
    border-top: 1px solid #e5e7eb;
    padding-top: 1rem;
}

.info-note {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0.75rem;
    line-height: 1.4;
}

.info-warning {
    color: #dc2626;
    font-weight: 600;
}

.no-more-text {
    color: #22C55E;
    font-size: 1rem;
    font-weight: 600;
    margin-top: 1rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    background: rgba(34, 197, 94, 0.1);
    display: inline-block;
}

.load-detailed-container {
    text-align: center;
    margin-top: 1rem;
}

.load-detailed-button {
    background-color: #2563eb;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 600;
}

.detailed-progress {
    margin-top: 1rem;
    font-size: 1rem;
    color: white;
}

.commit-note {
    margin-bottom: 1rem;
    color: #eab308;
    background-color: rgba(234, 179, 8, 0.1);
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.95rem;
}

.load-everything-button {
    background-color: #2563eb;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 1rem;
}

.load-everything-button:disabled {
    background-color: #94a3b8;
    cursor: not-allowed;
}

.commit-muted {
    opacity: 0.4;
    filter: grayscale(100%);
}

.lines-total {
    font-size: 0.875rem;
    color: #0f172a;
    font-weight: 500;
}

.single-stat-row {
    grid-template-columns: 1fr !important;
}

.contributor-stats-card {
    max-width: 700px;
    width: 100%;
    flex: 1 1 420px;
    margin: 0 auto;
    padding: 1.25rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}



.contributor-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 0.5rem;
}

.contributor-avatar-large {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    margin-bottom: 0.5rem;
}

.contributor-meta {
    font-size: 0.875rem;
    color: #4b5563;
    text-align: center;
    margin-bottom: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.contributor-stat-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}


.stat-box {
    flex: 1 1 90px;
    max-width: 120px;
    background: #f3f4f6;
    border-radius: 8px;
    padding: 0.5rem;
    text-align: center;
}


.stat-value {
    font-size: 1.1rem;
    font-weight: bold;
    color: #111827;
}

.stat-label {
    font-size: 0.75rem;
    color: #6b7280;
}

.contributor-final-score {
    font-size: 1rem;
    font-weight: 600;
    color: #2563eb;
    margin-top: 0.25rem;
}


</style>
