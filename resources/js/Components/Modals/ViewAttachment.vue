<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { Vue3Lottie } from 'vue3-lottie';

const props = defineProps({
    name: {
        type: String,
        default: null,
    },
    file: {
        type: String,
        default: null,
    },
    type: {
        type: String,
        default: null,
    },
})

const emit = defineEmits(['close', 'update:disclaimerChecked']);
const close = (val) => {
    show.value = false
    setTimeout(() => {
        emit('close', val);
    }, 200); 
}

const show = ref(false)

const isPDF = computed(() =>{
    if( /pdf/i.test(props.type)){
        return true
    }else{
        return false
    }
})
const loading = ref(true)


onMounted(() => {
    show.value = true
})
</script>
<template>
    <div 
        class="fixed inset-0 bg-gray-300/50 z-10 flex items-end justify-center"
        @click="close"
    >
        <Transition
            enter-active-class="transform transition-transform duration-200 ease-in-out"
            enter-from-class="translate-y-full"
            enter-to-class="translate-y-0"
            leave-active-class="transform transition-transform duration-200 ease-in-out"
            leave-from-class="translate-y-0"
            leave-to-class="translate-y-full"
        >
            <div @click.stop v-if="show" class="bg-white w-full max-w-[450px] h-[87vh] rounded-t-xl shadow-lg p-4">
                <div class="flex flex-row-reverse">
                    <button @click="close">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm7.707-3.707a1 1 0 0 0-1.414 1.414L10.586 12l-2.293 2.293a1 1 0 1 0 1.414 1.414L12 13.414l2.293 2.293a1 1 0 0 0 1.414-1.414L13.414 12l2.293-2.293a1 1 0 0 0-1.414-1.414L12 10.586 9.707 8.293Z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
                <div class="overflow-y-auto w-full h-[100%] ">
                    <div class="h-full">
                        <h2 class="font-bold pb-5">{{ name }}</h2>
                        <div class="h-full">
                            <img class="rounded-xl" v-if="!isPDF" :src="file" @load="loading = false" v-show="!loading" alt="">
                            <div class="h-[90%] pb-4" v-else>
                                <iframe
                                    :src="file"
                                    @load="loading = false"
                                    v-show="!loading"
                                    type="application/pdf"
                                    width="100%"
                                    height="100%"
                                    class="rounded-xl"
                                />
                            </div>
                            <Vue3Lottie
                            v-if="loading"
                                animationLink="/animation/loading_document.json" 
                                width="100%" 
                            />
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>