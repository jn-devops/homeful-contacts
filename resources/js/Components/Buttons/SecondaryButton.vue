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
        :class="`bg-white text-black w-full sm:w-auto px-6 py-3 font-bold border border-black rounded`"
        :type="type"
    >
        <slot />
</button>
</template>