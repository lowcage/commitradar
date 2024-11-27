<template>
    <div class="repository-page">
        <h1>Repository Data</h1>
        <p><strong>Your Token:</strong> {{ token }}</p>
    </div>
</template>

<script>
export default {
    props: {
        token: {
            type: String,
            required: true,
        },
    },
};
</script>

<style>
.repository-page {
    padding: 20px;
}
</style>
