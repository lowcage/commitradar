<template>
    <div class="repository-page">

        <div class="page-header sticky-header">
            <div class="header-content">
                <div class="left-section">
                    <h1 class="page-title">
                        Repository:
                        <span class="repo-name">{{ owner }} / {{ repo }}</span>
                    </h1>
                </div>
                <div class="token-input-group">
                    <input v-model="localToken" type="text" placeholder="Enter OpenAI API Key..." class="token-input" />
                    <button @click="saveToken" class="token-save-button">Save Token</button>
                </div>
                <div class="center-section">
                    <div class="date-range-with-buttons">
                        <div class="date-group">
                            <span class="label">From:</span>
                            <span class="date">{{ startDate }}</span>
                            <div class="date-buttons">
                                <button @click="adjustDate('start', -1)">←</button>
                                <button @click="adjustDate('start', 1)">→</button>
                            </div>
                        </div>

                        <div class="date-dash">–</div>

                        <div class="date-group">
                            <span class="label">To:</span>
                            <span class="date">{{ endDate }}</span>
                            <div class="date-buttons">
                                <button @click="adjustDate('end', -1)">←</button>
                                <button @click="adjustDate('end', 1)">→</button>
                            </div>
                        </div>
                    </div>



                    <div class="status-row">
                        <div class="commit-status">
                            <span class="label"><span v-if="!allCommitsLoaded">⚠️</span>All commits loaded:</span>
                            <span :class="allCommitsLoaded ? 'status-yes': 'status-no'">{{ allCommitsLoaded ? 'Yes' : 'No' }}</span>
                        </div>
                        <div class="commit-status">
                            <span class="label"><span v-if="!detailedCommitsLoaded && !commitsEmpty">⚠️</span>Commit details loaded:</span>
                            <span :class="detailedCommitsLoaded || commitsEmpty ? 'status-yes' : 'status-no'">
                                {{ detailedCommitsLoaded || commitsEmpty ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                </div>


                <div class="right-section">
                    <button class="back-button" @click="goHome">
                        Back to Home
                    </button>
                </div>
            </div>
        </div>

    <div class="page-content">
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
                            :class="{ 'contributor-hidden': hiddenContributors.has(contributor.login) }"
                        >
                            <!-- Hide button -->
                            <div class="contributor-actions">
                                <button
                                    class="hide-commits-btn"
                                    @click="toggleContributorVisibility(contributor.login)"
                                >
                                    {{ hiddenContributors.has(contributor.login) ? 'Show Contributor' : 'Hide Contributor' }}
                                </button>

                                <button
                                    class="hide-commits-btn"
                                    @click="showOnlyContributor(contributor.login)"
                                >
                                    Show Only This Contributor
                                </button>
                            </div>

                            <!-- Header -->
                            <div class="contributor-header">
                                <img :src="contributor.avatar_url" :alt="contributor.login" class="contributor-avatar-large" :style="{ border: '3px solid ' + getContributorColor(contributor.login), borderRadius: '100%' }" />
                                <a :href="contributor.html_url" target="_blank" class="contributor-name">{{ contributor.login }}</a>
                            </div>

                            <!-- Meta -->
                            <div class="contributor-meta">
                                <span>{{ contributor.contributions }} total commits</span>
                                <span v-if="contributor.filtered_lines_total > 0">
                            {{ contributor.filtered_lines_total }} lines changed (recent commits)
                        </span>
                            </div>

                            <!-- Dummy stat grid -->
                            <div class="contributor-stat-grid">
                                <div class="stat-box">
                                    <div class="stat-value">5.3</div>
                                    <div class="stat-label">Avg. Commit Score</div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-value">9%</div>
                                    <div class="stat-label">Of All Commits</div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-value">87</div>
                                    <div class="stat-label">Lines/Day</div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-value">{{ contributor.activeDays ? contributor.activeDays.size : 0 }}
                                    </div>
                                    <div class="stat-label">Active Days</div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-value">76%</div>
                                    <div class="stat-label">Commit message quality</div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-value">9%</div>
                                    <div class="stat-label">Churn Rate</div>
                                </div>
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
                <div class="info-header commit-header-bar">
                    <h3>Recent {{ allCommits.length }} Commits
                        <span v-if="detailedCommitsLoaded">
                        ({{ filteredCommitsCount }} above baseline)
                    </span>
                    </h3>
                    <div v-if="detailedCommitsLoaded" class="baseline-control">
                        <label for="baseline-slider">Baseline: {{ baseline }} lines</label>
                        <input
                            id="baseline-slider"
                            type="range"
                            min="0"
                            max="50"
                            step="1"
                            v-model="baseline"
                        />
                        <span class="baseline-note">Commits below baseline do not affect scorings.</span>
                    </div>
                </div>
            <div class="info-content">
            <div class="commits-slider">
                    <div class="commits-track">
                        <div v-if="commitsEmpty" class="no-commits">
                            <strong>No commits found for the selected date range.</strong>
                        </div>
                        <div
                            v-if="!commitsEmpty"
                            v-for="commit in visibleCommits"
                            :key="commit.sha"
                            class="commit-card"
                            :class="{ 'commit-muted': commit.stats && isBelowBaseline(commit) }"
                        >
                            <div class="commit-header">
                                <img
                                    :src="commit.author?.avatar_url"
                                    :alt="commit.author?.login || commit.commit.author.name"
                                    :style="{ border: '3px solid ' + getContributorColor(commit.author?.login) }"
                                    class="commit-avatar"
                                >
                                <div class="commit-author">
                                    <span class="author-name">{{ commit.author?.login || commit.commit.author.name }}</span>
                                    <span class="commit-date">{{ formatDate(commit.commit.author.date) }}</span>
                                </div>
                            </div>

                            <div class="commit-message">{{ commit.commit.message }}</div>

                            <div
                                v-if="commit.stats"
                                class="commit-stats commit-stats-below"
                            >
                                <span class="additions">+{{ commit.stats.additions }}</span>
                                <span class="deletions">-{{ commit.stats.deletions }}</span>
                            </div>

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
                        @click="loadMoreCommits"
                        :disabled="loadingMore || !hasMoreCommits || allCommitsLoaded"
                        class="load-button"
                    >
                        {{ loadingMore ? 'Loading More Commits...' : 'Load All Commits' }}
                    </button>
                    <button
                        @click="loadAllCommitDetails(5)"
                        class="load-button"
                        :disabled="loadingDetailed || !hasCommitsWithoutDetails"
                    >
                    <span v-if="loadingDetailed">
                        Loading details... {{ detailedProgress }}/{{ allCommits.length }}
                    </span>
                        <span v-else>
                        Load Details for Recent Commits
                    </span>
                    </button>
                </div>

            </div>
        </div>

        <div class="charts-row">
            <CommitActivityChart :chart-data="commitActivityChartData" :key="chartKey"/>
            <LinesActivityChart :chart-data="linesPerWeekChartData" :key="linesChartKey" />
        </div>

        <div class="info-card issue-section">
            <div class="info-header commit-header-bar">
                <h3>Recent {{ issues.length }} Issues and Pull Requests <i>(maximum 500)</i></h3>
                <span class="baseline-note">Issues and PRs outside of filter range are grayscaled, and do not effect scorings.</span>
                <div class="issue-filter-bar">
                    <div class="filter-row">
                        <label>
                            State:
                            <select v-model="issueStateFilter">
                                <option value="all">All</option>
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                            </select>
                        </label>

                        <label>
                            Milestone:
                            <select v-model="selectedMilestone">
                                <option :value="null">All</option>
                                <option v-for="m in availableMilestones" :key="m" :value="m">{{ m }}</option>
                            </select>
                        </label>
                    </div>

                    <div class="filter-row checkbox-row">
                        <label>
                            <input type="checkbox" v-model="showPullRequests" />
                            Show Pull Requests
                        </label>
                    </div>
                </div>
            </div>
            <div class="info-content">
            <div class="issues-slider">
                    <div class="issues-track">
                        <div
                            v-if="issues.length === 0"
                            class="no-commits"
                        >
                            <strong>No issues found.</strong>
                        </div>

                        <div
                            v-for="issue in filteredIssues"
                            :key="issue.id"
                            class="commit-card"
                            :class="{
                                'commit-closed': issue.state === 'closed',
                                'commit-open': issue.state === 'open',
                                'commit-muted': !isIssueInDateRange(issue)
                            }"
                            >
                            <!-- Fejléc: avatar + login + dátum -->
                            <div class="commit-header">
                                <img
                                    :src="getAvatarForLogin(issue.user?.login)"
                                    :alt="issue.user?.login"
                                    class="commit-avatar"
                                    :style="{ border: '3px solid ' + getContributorColor(issue.user?.login) }"
                                />
                                <div class="commit-author">
                                    <span class="author-name">{{ issue.user?.login }}</span>
                                    <span class="commit-date">{{ formatDate(issue.created_at) }}</span>
                                </div>
                            </div>

                            <!-- Cím -->
                            <div class="commit-message">
                                <a
                                    :href="`https://github.com/${owner}/${repo}/issues/${issue.number}`"
                                    target="_blank"
                                    class="sha-link"
                                >
                                    <span class="text-gray-400">#{{ issue.number }}</span> – {{ issue.title }}
                                </a>
                            </div>

                            <!-- Metaadatok -->
                            <div class="commit-meta">
                                <div>
                                    <strong>Assigned: </strong>
                                    <span v-if="issue.assignees && issue.assignees.length">
        {{ issue.assignees.slice(0, 2).join(', ') }}
      </span>
                                    <span v-else>–</span>
                                </div>

                                <div v-if="issue.state === 'closed'">
                                    <strong>Closed:</strong>
                                    {{ formatDate(issue.closed_at) }} by
                                    {{ issue.closed_by|| 'unknown' }}
                                </div>
                            </div>

                            <div class="issue-footer">
                                <div class="issue-milestone">
                                    {{ issue.milestone || 'No milestone' }}
                                </div>
                                <div class="issue-pr" v-if="issue.pull_request">
                                    <span class="pr-badge">PR</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-card">
                <div class="info-header">
                    <h3>Milestones</h3>
                </div>
            <div class="info-content">
            <ul class="milestone-list">
                    <li v-for="m in milestones" :key="m.id" class="milestone-item">
                        <strong>{{ m.title }}</strong> — {{ m.state }}
                        <div class="milestone-meta">
                            {{ m.open_issues }} open / {{ m.closed_issues }} closed |
                            Due: {{ formatDate(m.due_on) }}
                        </div>
                        <p class="milestone-desc">{{ m.description }}</p>
                    </li>
                </ul>
            </div>
        </div>



    </div>
</div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import CommitActivityChart from "@/Components/CommitActivityChart.vue";
import LinesActivityChart from "@/Components/LinesActivityChart.vue";


export default {
    data() {
        return {
            localStartDate: this.startDate,
            localEndDate: this.endDate,
            detailedCommits: {}, // sha -> { additions, deletions }
            loadingDetailed: false,
            detailedProgress: 0,
            page: 2, // 1-et már lekértünk backendről
            allCommits: [...this.commits],
            loadingMore: false,
            baseline: 10,
            hiddenContributors: new Set(),
            chartKey: 0,
            linesChartKey: 0,
            allCommitsLoaded: !this.hasMoreCommits,
            showPullRequests: true,
            issueStateFilter: 'all',
            selectedMilestone: null,
            localToken: this.openai_token || '',
        };
    },

    components: {
        CommitActivityChart,
        LinesActivityChart,
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
        startDate: { type: String, required: true },
        endDate: { type: String, required: true },
        hasMoreCommits: Boolean,
        commitsEmpty: Boolean,
        issues: {type:Array, required:true},
        milestones: {type:Array, required:true},
        openai_token: String
    },
    methods: {
        async loadMoreCommits() {
            if (this.loadingMore || !this.hasMoreCommits) return;

            this.loadingMore = true;

            try {
                let keepFetching = true;

                while (keepFetching) {
                    const response = await fetch(`https://api.github.com/repos/${this.owner}/${this.repo}/commits?per_page=50&page=${this.page}&since=${this.startDate}T00:00:00Z&until=${this.endDate}T23:59:59Z`, {
                        headers: {
                            Authorization: `Bearer ${this.token}`,
                            Accept: 'application/vnd.github.v3+json',
                        }
                    });

                    if (!response.ok) {
                        console.error('Failed to load commits');
                        break;
                    }

                    const newCommits = await response.json();

                    if (newCommits.length === 0) {
                        keepFetching = false;
                        break;
                    }

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

                    if (newCommits.length < 50) {
                        keepFetching = false;
                    } else {
                        this.page++;
                    }

                    this.chartKey++;
                }
            } catch (error) {
                console.error('Error fetching commits:', error);
            } finally {
                this.loadingMore = false;
                this.allCommitsLoaded = true;
            }
        },

        async loadAllCommitDetails(batchSize = 5) {
            if (this.loadingDetailed) return;

            this.loadingDetailed = true;

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
            this.chartKey++;
            this.linesChartKey++;
        },

        async saveToken() {
            try {
                const response = await fetch(route('github.save.token'), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ openai_token: this.localToken })
                });

                if (response.ok) {
                    alert("Token saved successfully!");
                } else {
                    alert("Failed to save token.");
                }
            } catch (error) {
                console.error("Error saving token:", error);
                alert("Error occurred while saving token.");
            }
        },

        formatDate(dateString) {
            if (!dateString) return 'No due date';
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString(undefined, options);
        },
        getAvatarForLogin(login) {
            const match = this.contributors.find(c => c.login === login);
            return match?.avatar_url || 'https://avatars.githubusercontent.com/u/0?v=4';
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
            const start = new Date(this.startDate);
            const end = new Date(this.endDate);

            this.contributors.forEach(contributor => {
                contributor.filtered_lines_total = 0;
                contributor.activeDays = new Set();
            });

            this.allCommits.forEach(commit => {
                const login = commit.author?.login;
                const stats = commit.stats;
                const date = commit.commit.author.date.substring(0, 10);
                const commitDate = new Date(date);

                if (!login || !stats) return;

                const contributor = this.contributors.find(c => c.login === login);
                if (!contributor) return;

                const lines = stats.additions + Math.abs(stats.deletions);
                if (lines >= this.baseline) {
                    contributor.filtered_lines_total += lines;
                }

                if (commitDate >= start && commitDate <= end) {
                    contributor.activeDays.add(date);
                }
            });

            this.issues.forEach(issue => {
                const login = issue.user?.login;
                if (!login) return;

                const contributor = this.contributors.find(c => c.login === login);
                if (!contributor) return;

                const created = new Date(issue.created_at);
                const closed = issue.closed_at ? new Date(issue.closed_at) : null;

                if (created >= start && created <= end) {
                    contributor.activeDays.add(issue.created_at.substring(0, 10));
                }

                if (closed && closed >= start && closed <= end && issue.closed_by?.login === login) {
                    contributor.activeDays.add(issue.closed_at.substring(0, 10));
                }
            });

            // Opcionálisan készíthetsz számértéket is
            this.contributors.forEach(c => {
                c.activeDayCount = c.activeDays.size;
            });
        },
        toggleContributorVisibility(login) {
            if (this.hiddenContributors.has(login)) {
                this.hiddenContributors.delete(login);
            } else {
                this.hiddenContributors.add(login);
            }
        },
        showOnlyContributor(login) {
            this.hiddenContributors = new Set(
                this.contributors
                    .map(c => c.login)
                    .filter(l => l !== login)
            );
        },
        generateColorFromString(str) {
            let hash = 0;
            for (let i = 0; i < str.length; i++) {
                hash = str.charCodeAt(i) + ((hash << 5) - hash);
            }
            const hue = Math.abs(hash) % 360;
            return `hsl(${hue}, 70%, 55%)`;
        },
        getContributorColor(login) {
            if (!login) return '#9ca3af'; // szürke fallback
            return this.generateColorFromString(login);
        },
        goHome() {
            window.location.href = "/";
        },
        adjustDate(type, amount) {
            const current = new Date(type === 'start' ? this.localStartDate : this.localEndDate);
            current.setDate(current.getDate() + amount);

            const formatted = current.toISOString().split('T')[0];

            if (type === 'start') {
                this.localStartDate = formatted;
            } else {
                this.localEndDate = formatted;
            }

            this.reloadWithNewDates();
        },
        reloadWithNewDates() {
            this.$inertia.get(route('github.repository'), {
                owner: this.owner,
                repo: this.repo,
                startDate: this.localStartDate,
                endDate: this.localEndDate,
            });
        },
        truncate(text, length) {
            if (!text) return '';
            return text.length <= length ? text : text.slice(0, length) + '...';
        },
        isIssueInDateRange(issue) {
            const created = new Date(issue.created_at);
            const closed = issue.closed_at ? new Date(issue.closed_at) : null;
            const start = new Date(this.startDate);
            const end = new Date(this.endDate);

            return (created >= start && created <= end) ||
                (closed && closed >= start && closed <= end);
        },
    },
    watch: {
        baseline(newVal) {
            this.calculateFilteredContributorStats();
            this.chartKey++;
            this.linesChartKey++;
        },
    },
    computed: {
        filteredCommits() {
            return this.allCommits.filter(c => {
                const stats = c.stats;
                return stats && (stats.additions + Math.abs(stats.deletions)) >= this.baseline;
            });
        },
        availableMilestones() {
            const titles = this.issues
                .map(i => i.milestone)
                .filter(m => m);
            return [...new Set(titles)];
        },
        filteredCommitsCount() {
            return this.allCommits.filter(commit => {
                const stats = commit.stats;
                if (!stats) return false;
                const totalLines = stats.additions + Math.abs(stats.deletions);
                return totalLines >= this.baseline;
            }).length;
        },
        detailedCommitsLoaded() {
            return this.allCommits.length > 0 &&
                this.allCommits.every(c => !!this.detailedCommits[c.sha]);
        },
        hasCommitsWithoutDetails() {
            return this.allCommits.some(c => !this.detailedCommits[c.sha]);
        },
        visibleCommits() {
            return this.allCommits.filter(commit => {
                const login = commit.author?.login;
                return !login || !this.hiddenContributors.has(login);
            });
        },
        commitActivityChartData() {
            const contributorLogins = this.contributors.map(c => c.login);
            const mainContributors = new Set(contributorLogins);

            const buckets = {};
            const labels = [];

            const dateCursor = new Date(this.startDate);
            const endDate = new Date(this.endDate);

            while (dateCursor <= endDate) {
                const key = dateCursor.toISOString().split('T')[0];
                labels.push(key);
                buckets[key] = { total: 0, other: 0 };
                for (const login of contributorLogins) {
                    buckets[key][login] = 0;
                }
                dateCursor.setDate(dateCursor.getDate() + 1);
            }

            for (const commit of this.filteredCommits) {
                const key = commit.commit.author.date.substring(0, 10);
                const login = commit.author?.login;
                if (!(key in buckets)) continue;

                if (login && mainContributors.has(login)) {
                    buckets[key][login]++;
                    buckets[key].total++;
                } else {
                    buckets[key].other++;
                }
            }

            const datasets = [{
                label: 'Total (Main Contributors)',
                data: labels.map(l => buckets[l].total),
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                tension: 0.3,
                fill: true,
            }];

            for (const login of contributorLogins) {
                datasets.push({
                    label: login,
                    data: labels.map(l => buckets[l][login]),
                    borderColor: this.getContributorColor(login),
                    backgroundColor: 'transparent',
                    tension: 0.3,
                });
            }

            return { labels, datasets };
        },
        linesPerWeekChartData() {
            const contributorLogins = this.contributors.map(c => c.login);
            const mainContributors = new Set(contributorLogins);

            const buckets = {};
            const labels = [];

            const dateCursor = new Date(this.startDate);
            const endDate = new Date(this.endDate);

            while (dateCursor <= endDate) {
                const key = dateCursor.toISOString().split('T')[0];
                labels.push(key);
                buckets[key] = { total: 0, other: 0 };
                for (const login of contributorLogins) {
                    buckets[key][login] = 0;
                }
                dateCursor.setDate(dateCursor.getDate() + 1);
            }

            for (const commit of this.filteredCommits) {
                const key = commit.commit.author.date.substring(0, 10);
                const login = commit.author?.login;
                const stats = commit.stats;
                if (!stats || !(key in buckets)) continue;

                const lines = stats.additions + Math.abs(stats.deletions);

                if (login && mainContributors.has(login)) {
                    buckets[key][login] += lines;
                } else {
                    buckets[key].other += lines;
                }

                buckets[key].total += lines;
            }

            const datasets = [{
                label: 'Total (Main Contributors)',
                data: labels.map(k => buckets[k].total),
                borderColor: '#22c55e',
                backgroundColor: 'rgba(34,197,94,0.1)',
                tension: 0.3,
                fill: true,
            }];

            for (const login of contributorLogins) {
                datasets.push({
                    label: login,
                    data: labels.map(k => buckets[k][login]),
                    borderColor: this.getContributorColor(login),
                    tension: 0.3,
                    fill: false,
                });
            }

            return { labels, datasets };
        },
        sortedIssues() {
            return [...this.issues].sort(
                (a, b) => new Date(b.created_at) - new Date(a.created_at)
            );
        },
        filteredIssues() {
            return this.sortedIssues.filter(issue => {
                if (!this.showPullRequests && issue.pull_request) return false;
                if (this.issueStateFilter !== 'all' && issue.state !== this.issueStateFilter) return false;
                if (this.selectedMilestone && issue.milestone !== this.selectedMilestone) return false;

                const login = issue.user?.login;
                if (login && this.hiddenContributors.has(login)) return false;

                return true;
            });
        },
    },
    mounted() {
        this.contributors.forEach(c => {
            c.activeDays = new Set();
        });

        this.allCommits.forEach(commit => {
            const login = commit.author?.login;
            if (!login) return;

            const date = commit.commit.author.date.substring(0, 10);
            const commitDate = new Date(date);
            const start = new Date(this.startDate);
            const end = new Date(this.endDate);

            if (commitDate >= start && commitDate <= end) {
                const contributor = this.contributors.find(c => c.login === login);
                if (contributor) {
                    contributor.activeDays.add(date);
                }
            }
        });

        this.issues.forEach(issue => {
            const login = issue.user?.login;
            if (!login) return;

            const start = new Date(this.startDate);
            const end = new Date(this.endDate);

            const created = new Date(issue.created_at);
            const closed = issue.closed_at ? new Date(issue.closed_at) : null;

            const contributor = this.contributors.find(c => c.login === login);
            if (!contributor) return;

            if (created >= start && created <= end) {
                contributor.activeDays.add(issue.created_at.substring(0, 10));
            }
            if (closed && closed >= start && closed <= end && issue.closed_by?.login === login) {
                contributor.activeDays.add(issue.closed_at.substring(0, 10));
            }
        });
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
    width: 100%;
    box-sizing: border-box;
    min-height: 100vh;
}

.page-header.sticky-header {
    position: sticky;
    top: 0;
    background-color: #0f172a;
    z-index: 10;
    padding: 15px 30px;
    border-bottom: 1px solid #334155;
}

.page-content {
    padding: 2rem;
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

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.info-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.info-header {
    background: #f8fafc;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 1rem;
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
.contributor-name {
    color: #2563eb;
    text-decoration: none;
    font-weight: 500;
}

.contributor-name:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
}

.commits-slider, .issues-slider {
    width: 100%;
    overflow-x: auto;
    padding: 1rem 0;
    scroll-behavior: smooth;
}

.commits-track, .issues-track {
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

.load-button {
    background-color: #2563eb;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 1rem;
    margin-right: 1rem
}

.load-button:disabled {
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
}

.stat-label {
    font-size: 0.75rem;
    color: #6b7280;
}

.contributor-final-score {
    text-align: center;
    font-size: 1rem;
    font-weight: 600;
    color: #2563eb;
    margin-top: 0.25rem;
}

.commit-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.baseline-control {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.baseline-control label {
    font-size: 0.875rem;
    color: #374151;
}

.load-commits-details-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 1.5rem;
}

.commit-stats-below {
    display: flex;
    gap: 1rem;
    justify-content: center;
    font-family: monospace;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

.contributor-hidden {
    opacity: 0.4;
    filter: grayscale(80%);
    transition: all 0.2s ease;
}

.contributor-stats-card {
    position: relative;
}

.charts-row {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 1rem; /* kisebb hézag */
    margin-top: 2rem;
    margin-bottom: 4rem;
}

.charts-row .info-card {
    flex: 0 0 47%;
    max-width: 47%;
    box-sizing: border-box;
}

.demo-load-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    justify-content: center;
    flex-wrap: wrap;
}

.demo-load-buttons button {
    background-color: #4f46e5;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    border: none;
    font-weight: 500;
    cursor: pointer;
}

.demo-load-buttons button:hover {
    background-color: #4338ca;
}

.header-content {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.date-range {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 14px;
    color: #e2e8f0;
}

.date {
    font-weight: bold;
    color: #fcd34d;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.left-section,
.center-section,
.right-section {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-title {
    font-size: 20px;
    color: #f8fafc;
    margin: 0;
}

.repo-name {
    font-weight: bold;
    color: #38bdf8;
}

.date-range, .commit-status {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 14px;
    color: #e2e8f0;
}

.date {
    font-weight: bold;
    color: #fcd34d;
}

.commit-status .status-no {
    font-weight: bold;
    font-size: 14px;
    color: #f87171;
}

.commit-status .status-yes {
    font-weight: bold;
    font-size: 14px;
    color: #57aa44;
}

.baseline-note {
    font-size: 0.85rem;
    color: #6b7280; /* gray-500 */
}

.date-range-with-buttons {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    gap: 1rem;
    flex-wrap: wrap;
    margin-right: 2rem;
    color: white;
    font-weight: 500;
}

.date-group {
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: rgba(255, 255, 255, 0.1);
    padding: 0.5rem 1rem;
    border-radius: 8px;
}

.date-buttons {
    margin-top: 4px;
    display: flex;
    gap: 0.25rem;
}

.date-buttons button {
    padding: 4px 8px;
    font-size: 0.9rem;
    background-color: #0f172a;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.date-buttons button:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

.date-dash {
    align-self: center;
    font-size: 1.2rem;
    margin: 0 0.5rem;
    color: #9ca3af;
}

.contributor-avatar-large {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 3px solid;
}

.issue-body {
    font-size: 0.9rem;
    color: #444;
    margin-bottom: 0.25rem;
}
.issue-info p {
    margin: 0.25rem 0;
    font-size: 0.85rem;
}

.commit-card.commit-closed {
    border-left: 5px solid #dc2626; /* piros */
}

.commit-card.commit-open {
    border-left: 5px solid #22c55e; /* zöld */
}

.issue-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    font-size: 0.8rem;
    color: #374151;
}

.issue-milestone {
    font-weight: 500;
    color: #6b7280;
}

.issue-pr .pr-badge {
    background-color: #2563eb;
    color: white;
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    border-radius: 6px;
    font-weight: 600;
}

.issue-filter-bar {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
    padding: 0 1rem;
    font-size: 0.9rem;
    color: #374151;
}

.issue-filter-bar label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.milestone-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.milestone-item {
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.milestone-meta {
    font-size: 0.85rem;
    color: #6b7280;
}

.milestone-desc {
    font-size: 0.9rem;
    color: #374151;
}

.issue-section {
    margin-bottom: 4rem;
}

.issue-filter-bar {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1rem;
    padding: 0 1rem;
    font-size: 0.9rem;
    color: #374151;
}

.issue-filter-bar .filter-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.issue-filter-bar .checkbox-row {
    margin-top: 0.25rem;
}

.token-input-group {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.token-input {
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    flex: 1;
}

.token-save-button {
    background-color: #2563eb;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    border: none;
    font-weight: 600;
    cursor: pointer;
}

.contributor-actions {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
    z-index: 2;
}

.hide-commits-btn {
    background: #f3f4f6;
    border: none;
    padding: 0.25rem 0.6rem;
    border-radius: 6px;
    font-size: 0.75rem;
    cursor: pointer;
    transition: background-color 0.2s;
}

.hide-commits-btn:hover {
    background: #e5e7eb;
}



</style>
