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
        :class="`px-4 py-3 text-sm rounded-full bg-gradient-to-r from-[#CC035C] to-[#FCB115] text-center bg-[length:200%_200%] transition-all duration-500 hover:bg-[position:100%_0] hover:text-base shadow-2xl ${customClass}`"
        :type="type"
    >
        <slot />
</button>
</template>