<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    labelText: {
        type: String,
        default: 'Checkbox',
    },
    checkBoxId: {
        type: String,
        default: 'checkbox',
    }
});
</script>

<style scoped>
.label-text {
    color: #fdfbd4;
}

.checkbox-wrapper input[type="checkbox"] {
    display: none;
}

.checkbox-wrapper .terms-label {
    cursor: pointer;
    display: flex;
    align-items: center;
}

.checkbox-wrapper .terms-label .label-text {
    margin-left: 10px;
}

.checkbox-wrapper .checkbox-svg {
    width: 30px;
    height: 30px;
}

.checkbox-wrapper .checkbox-box {
    fill: rgba(207, 205, 205, 0.425);
    stroke: #8c00ff;
    stroke-dasharray: 800;
    stroke-dashoffset: 800;
    transition: stroke-dashoffset 0.6s ease-in;
}

.checkbox-wrapper .checkbox-tick {
    stroke: #8c00ff;
    stroke-dasharray: 172;
    stroke-dashoffset: 172;
    transition: stroke-dashoffset 0.6s ease-in;
}

.checkbox-wrapper input[type="checkbox"]:checked + .terms-label .checkbox-box,
.checkbox-wrapper input[type="checkbox"]:checked + .terms-label .checkbox-tick {
    stroke-dashoffset: 0;
}
</style>

<template>
    <div class="checkbox-wrapper">
        <input :id="checkBoxId" name="checkbox" type="checkbox">
        <label class="terms-label" :for="checkBoxId">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 200 200" class="checkbox-svg">
                <mask fill="white" id="path-1-inside-1_476_5-37">
                    <rect height="200" width="200"></rect>
                </mask>
                <rect mask="url(#path-1-inside-1_476_5-37)" stroke-width="40" class="checkbox-box" height="200" width="200"></rect>
                <path stroke-width="15" d="M52 111.018L76.9867 136L149 64" class="checkbox-tick"></path>
            </svg>
            <span class="label-text">{{ labelText }}</span>
        </label>
    </div>
</template>
