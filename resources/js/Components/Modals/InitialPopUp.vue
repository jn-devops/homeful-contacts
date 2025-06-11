<script setup>
import { onMounted, ref } from 'vue';

const emit = defineEmits(['close']);
const close = (val) => {
    show.value = false
    setTimeout(() => {
        emit('close', val);
    }, 200); 
}

const agreeButton = () => {
    close(false)
}

const show = ref(false)

// Track each icon's animation state
const animatedIcons = ref([false, false, false])

onMounted(() => {
    show.value = true

    // Trigger each icon animation sequentially
    animatedIcons.value.forEach((_, index) => {
        setTimeout(() => {
            animatedIcons.value[index] = true
        }, index * 200) // 200ms delay between each
    })
})
</script>

<template>
    <div 
        class="fixed inset-0 bg-gray-300/50 z-10 flex items-center justify-center"
        @click="close"
    >
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div @click.stop v-if="show" class="bg-white w-full max-w-[450px] mx-3 rounded-lg shadow-lg p-4">
                <div class="overflow-y-auto w-full h-full">
                    <div>
                        <h2 class="text-lg font-bold mb-4">Instruction</h2>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li class="flex items-start gap-2">
                                <span
                                    class="text-green-500 transition transform duration-300"
                                    :class="animatedIcons[0] ? 'opacity-100 scale-100' : 'opacity-0 scale-75'"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                    </svg>
                                </span>
                                <span>
                                    Please complete the Data Form &amp; Required Documents.
                                </span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span
                                    class="text-green-500 transition transform duration-300"
                                    :class="animatedIcons[1] ? 'opacity-100 scale-100' : 'opacity-0 scale-75'"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                    </svg>
                                </span>
                                <span>
                                    In case you don’t have your complete info and other docs, at least fill up all info with asterisk.
                                </span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span
                                    class="text-green-500 transition transform duration-300"
                                    :class="animatedIcons[2] ? 'opacity-100 scale-100' : 'opacity-0 scale-75'"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                    </svg>
                                </span>
                                <span>
                                    Once you completed the required info, you may logout then complete other remaining info and docs within 7 days time.
                                </span>
                            </li>
                        </ul>
                        <button
                            @click="close"
                            class="w-full mt-6 py-2 bg-gray-900 text-white rounded hover:bg-gray-800 font-semibold"
                        >
                            Okay
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>
