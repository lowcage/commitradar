<script>
export default {
    name: 'ShowSettingButton',
    props: {
        modelValue: {
            type: Boolean,
            default: false,
        }
    },
};
</script>

<template>
    <input id="checkbox" type="checkbox" :checked="modelValue" @change="$emit('update:modelValue', !modelValue)">
    <label class="toggle" for="checkbox">
        <div id="bar1" class="bars"></div>
        <div id="bar2" class="bars"></div>
        <div id="bar3" class="bars"></div>
    </label>
</template>

<style scoped>
/* From Uiverse.io by vinodjangid07 */
#checkbox {
    display: none;
}

.toggle {
    position: relative;
    width: 40px;
    height: 40px;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    transition-duration: .3s;
}

.bars {
    width: 50%;
    height: 3px;
    background-color: #fdfbd4;
    border-radius: 10px;
    transition-duration: .3s;
}

/* #checkbox:checked + .toggle .bars {
  margin-left: 13px;
} */

#checkbox:checked + .toggle #bar2 {
    transform: translateY(4px) rotate(60deg);
    margin-left: 0;
    transform-origin: right;
    transition-duration: .3s;
    z-index: 2;
}

#checkbox:checked + .toggle #bar1 {
    transform: translateY(11px) rotate(-60deg);
    transition-duration: .3s;
    transform-origin: left;
    z-index: 1;
}

#checkbox:checked + .toggle {
    transform: rotate(180deg);
}
#checkbox:checked + .toggle #bar3 {
  transform: translateY(-3px) rotate(0deg);
  transition-duration: .3s;
  transform-origin:right;
}

</style>
