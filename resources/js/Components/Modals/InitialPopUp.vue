
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

onMounted(() => {
    show.value = true
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
                                <span class="text-blue-500">★</span>
                                <span>
                                    Please complete the Data Form &amp; Required Documents.
                                </span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-500">★</span>
                                <span>
                                    Incase you don’t have your complete info and other docs at least fill up all info with asterisk.
                                </span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-500">★</span>
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