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
                        <div class="commit-status">
                            <span class="label"><span v-if="!aiEvaluationDone">⚠️</span>AI evaluated:</span>
                            <span :class="aiEvaluationDone ? 'status-yes': 'status-no'">{{ aiEvaluationDone ? 'Yes' : 'No' }}</span>
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

                            <div class="contributor-stat-grid">
                                <!-- Avg. Commit Score -->
                                <div class="stat-box">
                                    <div class="stat-value">
                                        <span v-if="aiEvaluationDone">{{ contributor.avgCommitScore }}</span>
                                        <span v-else>N/A</span>
                                    </div>
                                    <div class="stat-label">
                                        Avg. Commit Score
                                        <br>
                                        <div class="dependency-badge ai">AI</div>
                                        <div class="dependency-badge details">Details</div>
                                    </div>
                                </div>


                                <!-- % of Recent Commits -->
                                <div class="stat-box">
                                    <div class="stat-value">{{ contributor.percentRecentCommits }}%</div>
                                    <div class="stat-label">
                                         of Recent Commits
                                        <div class="dependency-badge">Enhanced by Details</div>
                                    </div>
                                </div>

                                <!-- Lines/Day -->
                                <div class="stat-box">
                                    <div class="stat-value">
                                          <span v-if="detailedCommitsLoaded && contributor.activeCommitDayCount > 0">
                                            {{ Math.round(contributor.filtered_lines_total / contributor.activeCommitDayCount) }}
                                          </span>
                                        <span v-else>N/A</span>
                                    </div>
                                    <div class="stat-label">
                                        Lines/Day
                                        <div class="dependency-badge details">Details</div>
                                    </div>
                                </div>

                                <!-- Active Commit Days -->
                                <div class="stat-box">
                                    <div class="stat-value">{{ contributor.activeCommitDayCount }}</div>
                                    <div class="stat-label">
                                        Active Commit Days
                                        <div class="dependency-badge">Enhanced by Details</div>
                                    </div>
                                </div>

                                <!-- Commit Msg Quality -->
                                <div class="stat-box">
                                    <div class="stat-value">
                                        <span v-if="aiEvaluationDone">{{ contributor.avgMessageScore }}%</span>
                                        <span v-else>N/A</span>
                                    </div>
                                    <div class="stat-label">
                                        Commit Msg Quality
                                        <br>
                                        <div class="dependency-badge ai">AI</div>
                                    </div>
                                </div>



                                <!-- Churn Rate -->
                                <div class="stat-box">
                                    <div class="stat-value">
                                        <span v-if="detailedCommitsLoaded">
                                          {{ (contributor.churnRate * 100).toFixed(1) }}%
                                        </span>
                                        <span v-else>N/A</span>
                                    </div>
                                    <div class="stat-label">
                                        Churn Rate
                                        <div class="dependency-badge details">Details</div>
                                    </div>
                                </div>

                                <!-- Files Touched -->
                                <div class="stat-box">
                                    <div class="stat-value">
                                        <span v-if="detailedCommitsLoaded">{{ contributor.filesTouched }}</span>
                                        <span v-else>N/A</span>
                                    </div>
                                    <div class="stat-label">Unique Files Touched</div>
                                    <div class="dependency-badge details">Details</div>
                                </div>
                            </div>


                            <hr class="stat-divider" />

                            <div class="contributor-stat-grid">
                                <!-- Issues -->
                                <div class="stat-box">
                                    <div class="stat-value">{{ contributor.openedIssues }}</div>
                                    <div class="stat-label">Issues Opened</div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-value">{{ contributor.closedIssues }}</div>
                                    <div class="stat-label">Issues Closed</div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-value">{{contributor.pullRequests}}</div>
                                    <div class="stat-label">PRs Merged</div>
                                </div>
                                <div class="stat-box">
                                    <div class="stat-value">{{ contributor.activeIssueDayCount }}</div>
                                    <div class="stat-label">Active Issue Days</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="contributor-notes">
                        <p class="info-note">
                            Note: Only main contributors are listed here. Some commits might belong to other authors.
                        </p>
                        <p class="info-note">
                            🧠 <strong>AI</strong> = Requires AI evaluation for scoring. <br>
                            🧾 <strong>Details</strong> = Needs full commit data to calculate accurate metrics.
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
                                <span class="filecount">🗂 {{ commit.stats.fileCount || commit.stats.files?.length || 0 }} files</span>
                            </div>
                            <div v-if="commit.commit_score !== undefined" class="commit-ai-stats">
                                    <span :style="{ color: getScoreColor(commit.commit_score) }">
                                         Score: {{ commit.commit_score }}/10
                                    </span>
                                    <span :style="{ color: getMessageScoreColor(commit.commit_message_score) }">
                                        Message: {{ commit.commit_message_score }}%
                                    </span>
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
                        @click="runAICommitEvaluation"
                        class="load-button"
                        :disabled="!detailedCommitsLoaded || aiEvaluationInProgress || aiEvaluationDone"
                    >
                        <span v-if="aiEvaluationInProgress">Running AI Evaluation...</span>
                        <span v-else-if="aiEvaluationDone">AI Evaluation Complete</span>
                        <span v-else>Run AI Evaluation</span>
                    </button>

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

                            <div class="commit-message">
                                <a
                                    :href="`https://github.com/${owner}/${repo}/issues/${issue.number}`"
                                    target="_blank"
                                    class="sha-link"
                                >
                                    <span class="text-gray-400">#{{ issue.number }}</span> – {{ issue.title }}
                                </a>
                            </div>

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
                        <div class="milestone-progress-bar">
                            <div
                                class="milestone-progress-fill"
                                :style="{ width: calculateMilestoneProgress(m) + '%' }"
                            ></div>
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
import {createTextVNode} from "vue";


export default {
    data() {
        return {
            localStartDate: this.startDate,
            localEndDate: this.endDate,
            detailedCommits: {},
            loadingDetailed: false,
            detailedProgress: 0,
            page: 2,
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
            aiEvaluationDone: false,
            aiEvaluationInProgress: false,

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
                this.calculateCommitData();
                if (this.detailedCommitsLoaded) {
                    this.calculateFilesTouched();
                }
            }
        },

        async loadAllCommitDetails(batchSize = 5) {
            if (this.loadingDetailed) return;

            this.loadingDetailed = true;

            while (true) {
                const remainingCommits = this.allCommits.filter(c => !this.detailedCommits[c.sha]);
                const batch = remainingCommits.slice(0, batchSize);

                if (batch.length === 0) break;

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
                            this.detailedCommits[commit.sha] = {
                                additions: commit.additions,
                                deletions: commit.deletions,
                                files: commit.files || []
                            };

                            const found = this.allCommits.find(c => c.sha === commit.sha);
                            if (found) {
                                found.stats = {
                                    additions: commit.additions,
                                    deletions: commit.deletions,
                                    files: commit.files || 0,
                                    fileCount: commit.fileCount || (commit.files?.length ?? 0)
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
            this.chartKey++;
            this.linesChartKey++;
            this.calculateCommitData();
            this.calculateChurnRate();
            this.calculateFilesTouched();
        },

        async runAICommitEvaluation() {
            if (!this.detailedCommitsLoaded || this.aiEvaluationDone) return;

            this.aiEvaluationInProgress = true;

            const batches = [];
            const all = this.allCommits;
            for (let i = 0; i < all.length; i += 5) {
                const batch = all.slice(i, i + 5)
                    .filter(c => c.commit && c.stats && Array.isArray(c.stats.files));
                if (batch.length > 0) {
                    batches.push(batch);
                }
            }

            for (const batch of batches) {
                const payload = batch.map(commit => ({
                    sha: commit.sha,
                    message: commit.commit.message,
                    files: commit.stats.files,
                    additions: commit.stats.additions,
                    deletions: commit.stats.deletions,
                }));

                try {
                    const response = await fetch(route('github.ai.evaluate'), {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ commits: payload })
                    });

                    const result = await response.json();

                    result.forEach(entry => {
                        const target = this.allCommits.find(c => c.sha === entry.sha);
                        if (target) {
                            target.commit_score = entry.commit_score;
                            target.commit_message_score = entry.commit_message_score;
                        }
                    });
                    this.calculateAIAverages();
                } catch (err) {
                    console.error("AI Evaluation batch failed:", err);
                }

                await new Promise(resolve => setTimeout(resolve, 300)); // throttle
            }

            this.aiEvaluationInProgress = false;
            this.aiEvaluationDone = true;
        },
        calculateAIAverages() {
            this.contributors.forEach(c => {
                c.avgCommitScore = 0;
                c.avgMessageScore = 0;
            });

            const commitsByLogin = {};

            this.filteredCommits.forEach(commit => {
                const login = commit.author?.login;
                if (!login) return;

                if (!commitsByLogin[login]) {
                    commitsByLogin[login] = [];
                }

                if (commit.commit_score !== undefined && commit.commit_message_score !== undefined) {
                    commitsByLogin[login].push(commit);
                }
            });

            Object.entries(commitsByLogin).forEach(([login, commits]) => {
                const contributor = this.contributors.find(c => c.login === login);
                if (!contributor) return;

                const totalScore = commits.reduce((sum, c) => sum + c.commit_score, 0);
                const totalMsgScore = commits.reduce((sum, c) => sum + c.commit_message_score, 0);
                const count = commits.length;

                contributor.avgCommitScore = +(totalScore / count).toFixed(1);
                contributor.avgMessageScore = +(totalMsgScore / count).toFixed(1);
            });
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
        calculateIssueData() {
            const start = new Date(this.startDate);
            const end = new Date(this.endDate);

            this.contributors.forEach(c => {
                c.activeIssueDays = new Set();
                c.openedIssues = 0;
                c.closedIssues = 0;
                c.pullRequests = 0;
            });

            this.issues.forEach(issue => {
                const login = issue.user?.login;
                if (!login) return;

                const contributor = this.contributors.find(c => c.login === login);
                if (!contributor) return;

                const created = new Date(issue.created_at);
                const closed = issue.closed_at ? new Date(issue.closed_at) : null;

                if(this.isIssueInDateRange(issue)) {
                    if (issue.pull_request && issue.closed_at !== null) {
                        contributor.pullRequests++;
                    }

                    if(created >= start && created <= end) {
                        contributor.openedIssues++
                        contributor.activeIssueDays.add(issue.created_at.substring(0, 10));
                    }

                    if (closed >= start && created <= end) {
                        contributor.closedIssues++
                        contributor.activeIssueDays.add(issue.closed_at.substring(0, 10));
                    }
                }
            });

            this.contributors.forEach(c => {
                c.activeIssueDayCount = c.activeIssueDays.size;
                c.closedIssues -= c.pullRequests
                c.openedIssues -= c.pullRequests
            });
        },
        calculateCommitData() {
            const start = new Date(this.startDate);
            const end = new Date(this.endDate);

            this.contributors.forEach(c => {
                c.activeCommitDays = new Set();
                c.filtered_lines_total = 0;
                c.baselineCommitCount = 0;
            });

            let totalFilteredCommits = 0;

            this.allCommits.forEach(commit => {
                const login = commit.author?.login;
                if (!login) return;

                const date = commit.commit.author.date.substring(0, 10);
                const commitDate = new Date(date);
                const contributor = this.contributors.find(c => c.login === login);
                if (!contributor) return;

                if (commitDate >= start && commitDate <= end) {
                    const stats = commit.stats;

                    if (!stats) {
                        contributor.activeCommitDays.add(date);
                        contributor.baselineCommitCount++;
                        totalFilteredCommits++;
                    } else {
                        const lines = stats.additions + Math.abs(stats.deletions);
                        if (lines >= this.baseline) {
                            contributor.activeCommitDays.add(date);
                            contributor.filtered_lines_total += lines;
                            contributor.baselineCommitCount++;
                            totalFilteredCommits++;
                        }
                    }
                }
            });

            this.contributors.forEach(c => {
                c.activeCommitDayCount = c.activeCommitDays.size;
                c.percentRecentCommits = totalFilteredCommits > 0
                    ? Math.round((c.baselineCommitCount / totalFilteredCommits) * 100)
                    : 0;
            });
        },
        calculateChurnRate() {
            if (!this.detailedCommitsLoaded) return;

            this.contributors.forEach(c => {
                c.totalAdditions = 0;
                c.totalDeletions = 0;
            });

            this.filteredCommits.forEach(commit => {
                const login = commit.author?.login;
                if (!login) return;

                const contributor = this.contributors.find(c => c.login === login);
                if (!contributor) return;

                contributor.totalAdditions += commit.stats.additions;
                contributor.totalDeletions += Math.abs(commit.stats.deletions);
            });

            this.contributors.forEach(c => {
                const total = c.totalAdditions + c.totalDeletions;
                c.churnRate = total > 0 ? (c.totalDeletions / total) : 0;
            });
        },
        calculateFilesTouched() {
            this.contributors.forEach(c => {
                c.touchedFilesSet = new Set();
            });

            this.filteredCommits.forEach(commit => {
                const login = commit.author?.login;
                const files = commit.files || commit.stats?.files;
                if (!login || !Array.isArray(files)) return;

                const contributor = this.contributors.find(c => c.login === login);
                if (!contributor) return;

                files.forEach(filename => {
                    contributor.touchedFilesSet.add(filename);
                });
            });

            this.contributors.forEach(c => {
                c.filesTouched = c.touchedFilesSet.size;
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
            if (!login) return '#9ca3af';
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
            start.setHours(0, 0, 0, 0);

            const end = new Date(this.endDate);
            end.setHours(23, 59, 59, 999);

            return (created >= start && created <= end) ||
                (closed && closed >= start && closed <= end);
        },
        getScoreColor(score) {
            if (score === null || score === undefined) return '#9ca3af';
            if (score >= 9) return '#16a34a';
            if (score >= 7) return '#22c55e';
            if (score >= 5) return '#eab308';
            if (score >= 3) return '#f97316';
            return '#dc2626';
        },

        getMessageScoreColor(percent) {
            if (percent === null || percent === undefined) return '#9ca3af';
            if (percent >= 90) return '#16a34a';
            if (percent >= 75) return '#22c55e';
            if (percent >= 50) return '#eab308';
            if (percent >= 25) return '#f97316';
            return '#dc2626';
        },
        calculateMilestoneProgress(milestone) {
            const total = milestone.open_issues + milestone.closed_issues;
            return total === 0 ? 0 : Math.round((milestone.closed_issues / total) * 100);
        },

    },
    watch: {
        baseline(newVal) {
            this.calculateChurnRate();
            this.calculateCommitData();
            this.calculateFilesTouched();
            this.calculateAIAverages();
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
            c.activeCommitDays = new Set();
            c.activeIssueDays = new Set();
        });

        this.calculateIssueData();
        this.calculateCommitData();
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

.stat-divider {
    border: none;
    border-top: 1px solid #d1d5db;
    margin: 0.5rem 0 0.75rem 0;
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
    gap: 1rem;
    margin-top: 2rem;
    margin-bottom: 4rem;
}

.charts-row .info-card {
    flex: 0 0 47%;
    max-width: 47%;
    box-sizing: border-box;
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

.commit-status {
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
    color: #6b7280;
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
.issue-info p {
    margin: 0.25rem 0;
    font-size: 0.85rem;
}

.commit-card.commit-closed {
    border-left: 5px solid #dc2626;
}

.commit-card.commit-open {
    border-left: 5px solid #22c55e;
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

.dependency-badge {
    display: inline-block;
    margin-top: 4px;
    padding: 2px 6px;
    font-size: 10px;
    border-radius: 4px;
    background-color: #e5e7eb;
    color: #374151;
}
.dependency-badge.ai {
    background-color: #fcd34d;
    color: #78350f;
}
.dependency-badge.details {
    background-color: #bfdbfe;
    color: #1e3a8a;
}

.commit-stats .filecount {
    margin-left: 0.5rem;
    color: #6b7280;
    font-size: 0.85rem;
}

.commit-ai-stats {
    margin-top: 4px;
    font-size: 0.85rem;
    font-weight: 500;
    display: flex;
    gap: 1rem;
}

.milestone-progress-bar {
    height: 8px;
    width: 100%;
    background-color: #e5e7eb;
    border-radius: 4px;
    margin-top: 6px;
    overflow: hidden;
}

.milestone-progress-fill {
    height: 100%;
    background-color: #22c55e;
    transition: width 0.3s ease;
}


</style>
