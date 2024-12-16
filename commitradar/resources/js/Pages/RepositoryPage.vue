<template>
    <div class="repository-page">
        <h1>Repository Data</h1>
        <p><strong>Your Token:</strong> {{ token }}</p>

        <div v-if="repository">
            <h2>{{ repository.name }}</h2>
            <p><strong>Description:</strong> {{ repository.description }}</p>
            <p><strong>Stars:</strong> {{ repository.stargazers_count }}</p>
            <p><strong>Forks:</strong> {{ repository.forks_count }}</p>
            <p><strong>Issues:</strong> {{ repository.open_issues_count }}</p>
        </div>
        <div v-else>
            <p>Loading repository data...</p>
        </div>
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
};
</script>

<style>
.repository-page {
    padding: 20px;
}

h1 {
    font-size: 24px;
}

h2 {
    font-size: 20px;
}

p {
    margin: 5px 0;
}
</style>
