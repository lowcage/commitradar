<template>
    <div class="repository-page">
        <h1>Repository Details</h1>
        <ul>
            <li><strong>Name:</strong> {{ repository.name }}</li>
            <li><strong>Owner:</strong> {{ repository.owner.login }}</li>
            <li><strong>Description:</strong> {{ repository.description || 'No description available' }}</li>
            <li><strong>Primary Language:</strong> {{ repository.language }}</li>
            <li><strong>Visibility:</strong> {{ repository.private ? 'Private' : 'Public' }}</li>
            <li><strong>Default Branch:</strong> {{ repository.default_branch }}</li>
            <li><strong>Created At:</strong> {{ formatDate(repository.created_at) }}</li>
            <li><strong>Updated At:</strong> {{ formatDate(repository.updated_at) }}</li>
            <li><strong>Forks:</strong> {{ repository.forks_count }}</li>
            <li><strong>Stars:</strong> {{ repository.stargazers_count }}</li>
            <li><strong>Watchers:</strong> {{ repository.watchers_count }}</li>
            <li><strong>Open Issues:</strong> {{ repository.open_issues_count }}</li>
            <li><strong>Size (KB):</strong> {{ repository.size }}</li>
            <li><strong>License:</strong> {{ repository.license?.name || 'No license specified' }}</li>
            <li><strong>URL:</strong> <a :href="repository.html_url" target="_blank">{{ repository.html_url }}</a></li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        token: {
            type: String,
            required: true,
        },
        repository: {
            type: Object,
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
.repository-page {
    padding: 20px;
}
ul {
    list-style: none;
    padding: 0;
}
li {
    margin: 5px 0;
}
</style>
