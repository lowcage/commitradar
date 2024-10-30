<script setup>

import CustomCheckbox from "@/Components/CustomCheckbox.vue";
</script>

<template>
    <div class="card">
        <CustomCheckbox labelText="Lorem Ipsum" check-box-id="1" style="padding-bottom: 15px"/>
        <CustomCheckbox labelText="Ipsum Lorem" check-box-id="2" />
    </div>
</template>

<style scoped>
    .card {
        width: 600px;
        height: 400px;
        border-radius: 30px;
        background: #4a00e0;
        padding: 30px;
        box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 50px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 26px -18px inset;
    }
</style>
