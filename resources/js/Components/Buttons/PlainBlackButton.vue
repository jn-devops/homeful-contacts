<script setup>
const props = defineProps({
    type: {
        type: String,
        default: "button"
    }
})
</script>
<template>
    <button :type="type" class="bg-black text-white w-full p-3 font-bold">
        <slot />
    </button>
</template>