<script setup>
const props = defineProps({
    type: {
        type: String,
        default: "button"
    }
})
</script>
<template>
    <button :type="type" class="border-2 border-black text-black w-full p-3 font-bold">
        <slot />
    </button>
</template>