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

        <h2 class="section-title">Contributors</h2>
        <div class="contributors-list">
            <div v-for="contributor in contributors" :key="contributor.id" class="contributor-card">
                <img :src="contributor.avatar_url" :alt="contributor.login" class="contributor-avatar">
                <div class="contributor-info">
                    <a :href="contributor.html_url" target="_blank" class="contributor-name">{{ contributor.login }}</a>
                    <span class="contributions">{{ contributor.contributions }} commits</span>
                    <div class="contributor-stats">
                        <span class="additions">+{{ contributor.total_additions }}</span>
                        <span class="deletions">-{{ contributor.total_deletions }}</span>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="section-title">Commit History</h2>
        <div class="commits-slider">
            <div class="commits-track">
                <div v-for="commit in commits" :key="commit.sha" class="commit-card">
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
                    </div>
                    <div class="commit-message">{{ commit.commit.message }}</div>
                    <div class="commit-stats">
                        <span class="additions">+{{ commit.stats?.additions || 0 }}</span>
                        <span class="deletions">-{{ commit.stats?.deletions || 0 }}</span>
                    </div>
                    <div class="commit-sha">
                        <a :href="commit.html_url" target="_blank" class="sha-link">
                            {{ commit.sha.substring(0, 7) }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: {
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
    },
    methods: {
        formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString(undefined, options);
        },
    },
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
    color: #60A5FA; /* Light blue color */
    font-style: italic;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.info-card, .contributor-card, .commit-card {
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
    grid-template-columns: repeat(3, 1fr);  /* Three columns for stars, forks, watchers */
}

.stat-row:last-child {
    grid-template-columns: repeat(2, 1fr);  /* Two columns for open/closed issues */
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
    color: #EAB308; /* Yellow */
}

.stat-forks {
    color: #60A5FA; /* Light blue */
}

.stat-watchers {
    color: #2563eb; /* Original blue */
}

.stat-open-issues {
    color: #22C55E; /* Green */
}

.stat-closed-issues {
    color: #EF4444; /* Red */
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
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
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

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: white;
    margin: 2rem 0 1rem;
}

.commits-slider {
    width: 100%;
    overflow-x: auto;
    padding: 1rem 0;
}

.commits-track {
    display: flex;
    gap: 1rem;
    padding: 0.5rem;
}

.commit-card {
    flex: 0 0 300px;
    background: white;
    border-radius: 8px;
    padding: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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

.commit-stats {
    display: flex;
    gap: 1rem;
    margin-bottom: 0.5rem;
    font-family: monospace;
    font-size: 0.875rem;
}

.additions {
    color: #22C55E; /* Green */
}

.deletions {
    color: #EF4444; /* Red */
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

/* Add smooth scrolling */
.commits-slider {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

.commits-slider::-webkit-scrollbar {
    height: 6px;
}

.commits-slider::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.commits-slider::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 3px;
}

.contributor-stats {
    display: flex;
    gap: 0.75rem;
    margin-top: 0.25rem;
    font-family: monospace;
    font-size: 0.875rem;
}
</style>
