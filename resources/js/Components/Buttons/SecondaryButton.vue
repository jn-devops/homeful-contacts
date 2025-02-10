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
        :class="`text-white text-xs bg-gradient-to-r from-[#006FFD] to-[#FCB115] text-center bg-[length:200%_200%] transition-all duration-500 hover:bg-[position:100%_0] hover:text-sm shadow-lg font-medium rounded-full px-4 py-2 me-2 mb-2 ${customClass}`"
        :type="type"
    >
        <slot />
</button>
</template>