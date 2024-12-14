<script setup>
// No need for additional code since there's no logic or props defined
</script>

<template>
    <div class="grid gap-8 justify-items-start" v-bind="$attrs">
        <slot></slot>
    </div>
</template>
