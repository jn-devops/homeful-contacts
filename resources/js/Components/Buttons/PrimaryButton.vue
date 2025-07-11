<script setup>
    import { ref } from 'vue';
    const props = defineProps({
        type: {
            type: String,
            default: 'button',
        },
        customClass: {
            type: String,
            default: '',
        },
    })
</script>
<template>
    <button 
        :class="`bg-black text-white w-full px-6 py-3 font-bold border-spacing-0`"
        :type="type"
    >
        <slot />
    </button>
</template>