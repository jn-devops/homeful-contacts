<script setup>
import { onMounted, onUnmounted } from 'vue';

    const props = defineProps({
        show: {
            type: Boolean,
            default: false
        },
        title: {
            type: String,
            default: ''
        },
        body: {
            type: String,
            default: ''
        }
    })
    let intervalId = null;

    const emit = defineEmits(['update:show'])

    const closeToast = () => {
        emit('update:show', false)
    }

    onMounted(() => {
        setTimeout(() => {
            closeToast();
        }, 5000); 
    });

    onUnmounted(() => {
        clearInterval(intervalId);
    });

</script>

<template>
    <div id="toast-default" class="fixed flex items-center w-full max-w-xs p-4 text-black bg-red-100 rounded-lg shadow dark:text-gray-400 dark:bg-gray-800 rtl:divide-x-reverse divide-gray-200 top-5 right-5 z-50" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-transparent rounded-lg ">
            <svg class="w-6 h-6 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd"/>
            </svg>

        </div>
        <div class="ms-3 text-sm font-normal flex flex-col">
            <div class="font-bold text-base">{{ title }}</div>
            <div class="text-xs">{{ body }}</div>
        </div>
        <button @click="closeToast" type="button" class="ms-auto -mx-1.5 -my-1.5 bg-transparent text-black hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-default" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
</template>