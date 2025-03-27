<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    title: {
        type: String,
        default: "Are you sure?"
    },
    description: {
        type: String,
        default: "There is no turning back."
    },
    trueLabel: {
        type: String,
        default: "Yes"
    },
    falseLabel: {
        type: String,
        default: "No"
    },
})

const emit = defineEmits(['close', 'handle-true']);
const close = (val) => {
    show.value = false
    setTimeout(() => {
        emit('close', val);
    }, 200); 
}

const handleClickOutside = (event) => {
    if (event.target === event.currentTarget) {
        close();
    }
};

const handleSave = () => {
    emit('handle-true')
    close()
}

const show = ref(false)

onMounted(() => {
    show.value = true
})
</script>
<template>
    <div 
        class="fixed inset-0 bg-gray-300/50 z-10 flex items-start justify-center"
        @click="handleClickOutside"
    >
        <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
        >
            <div 
                v-if="show" 
                class="bg-white w-full max-w-[450px] rounded-xl shadow-lg p-4 relative mt-20 mx-5"
                @click.stop
            >
                <div class="absolute top-3 right-3">
                    <button class="" @click="close">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm7.707-3.707a1 1 0 0 0-1.414 1.414L10.586 12l-2.293 2.293a1 1 0 1 0 1.414 1.414L12 13.414l2.293 2.293a1 1 0 0 0 1.414-1.414L13.414 12l2.293-2.293a1 1 0 0 0-1.414-1.414L12 10.586 9.707 8.293Z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
                <div class="overflow-y-auto w-full h-full ">
                    <div class="mt-4 px-10 text-center font-semibold">
                        <p class="text-lg">{{ title }} </p>
                        <p class="font-normal text-sm">{{ description }}</p>
                    </div>
                    <div class="flex gap-1 mt-6 items-center justify-center pb-5">
                        <button @click="handleSave" class="bg-green-500 px-2 text-white rounded text-sm font-semibold shadow">{{ trueLabel }}</button>
                        <button @click="close" class="bg-red-500 px-2 text-white rounded text-sm font-semibold shadow">{{ falseLabel }}</button>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>