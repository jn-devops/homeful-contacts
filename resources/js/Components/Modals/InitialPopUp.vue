<script setup>
import { onMounted, ref, computed, watch } from 'vue';
import { Vue3Lottie } from 'vue3-lottie';

const emit = defineEmits(['close']);

const show = ref(false);
const currentSlide = ref(0);
const displayedDescription = ref('');
const typingSpeed = 20; // Speed of typing in milliseconds

const slides = [
    {
        lottieUrl: '/animation/loading_document.json',
        title: 'Complete Data Form & Documents',
        description: 'Complete the Data Form and submit all Required Documents. This is the first essential step to get started.',
    },
    {
        lottieUrl: '/animation/PaperPen2.json',
        title: 'Fill in Required Fields',
        description: 'If you don\'t have all your information or documents yet, at least fill in all the fields marked with an asterisk (*).',
    },
    {
        lottieUrl: '/animation/Sevendays.json',
        title: '7-Day Completion Window',
        description: 'Once required fields are filled up, you may choose to log-out. Then complete the remaining info and docs within 7 days. Please log in to your buyer portal thru the link we sent you via email & sms.',
    }
];

const isLastSlide = computed(() => currentSlide.value === slides.length - 1);
const isFirstSlide = computed(() => currentSlide.value === 0);

let typingInterval = null;

const typeText = (text) => {
    if (typingInterval) {
        clearInterval(typingInterval);
    }
    displayedDescription.value = '';
    let i = 0;
    typingInterval = setInterval(() => {
        if (i < text.length) {
            displayedDescription.value += text.charAt(i);
            i++;
        } else {
            clearInterval(typingInterval);
            typingInterval = null;
        }
    }, typingSpeed);
};

const nextSlide = () => {
    if (!isLastSlide.value) {
        currentSlide.value++;
    }
};

const prevSlide = () => {
    if (!isFirstSlide.value) {
        currentSlide.value--;
    }
};

const close = () => {
    show.value = false;
    emit('close');
};

onMounted(() => {
    show.value = true;
    typeText(slides[currentSlide.value].description);
});

watch(currentSlide, (newVal) => {
    typeText(slides[newVal].description);
});
</script>

<template>
    <div
        class="fixed inset-0 bg-gray-900/70 z-50 flex items-center justify-center p-4 sm:p-0"
        @click.self="close"
        role="dialog"
        aria-modal="true"
        aria-labelledby="instruction-modal-title"
    >
        <Transition
            enter-active-class="transition duration-400 ease-out"
            enter-from-class="opacity-0 scale-90"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-90"
        >
            <div
                v-if="show"
                class="bg-white dark:bg-gray-800 w-full max-w-md mx-auto rounded-2xl shadow-xl p-6 sm:p-8 flex flex-col transform transition-all duration-300 ease-in-out"
                @click.stop
            >
                <div class="flex justify-end mb-4">
                    <button @click="close" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <h2 id="instruction-modal-title" class="text-3xl font-extrabold text-gray-900 dark:text-white mb-6 text-center leading-tight">
                    Instruction
                </h2>

                <div class="relative overflow-hidden flex-grow flex flex-col items-center justify-center">
                    <Transition
                        mode="out-in"
                        enter-active-class="transition duration-700 ease-out transform"
                        enter-from-class="translate-x-full opacity-0"
                        enter-to-class="translate-x-0 opacity-100"
                        leave-active-class="transition duration-500 ease-in transform absolute w-full"
                        leave-from-class="translate-x-0 opacity-100"
                        leave-to-class="-translate-x-full opacity-0"
                    >
                        <div :key="currentSlide" class="flex flex-col items-center text-center px-2">
                            <div class="w-40 h-40 mb-6 bg-gray-100 dark:bg-gray-700 rounded-3xl overflow-hidden flex items-center justify-center shadow-inner">
                                <Vue3Lottie
                                    :animationLink="slides[currentSlide].lottieUrl"
                                    width="100%"
                                />
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-3 leading-snug">{{ slides[currentSlide].title }}</h3>
                            <p class="text-base text-gray-700 dark:text-gray-300 mb-6 min-h-[4.5rem] md:min-h-[5rem] text-wrap px-2">{{ displayedDescription }}</p>
                        </div>
                    </Transition>
                    <div class="flex space-x-2 mt-4 mb-6">
                        <span
                            v-for="(slide, index) in slides"
                            :key="index"
                            class="block h-1.5 transition-all duration-300 cursor-pointer rounded-full"
                            :class="{
                                'w-8 bg-black': currentSlide === index,
                                'w-3 bg-gray-400 dark:bg-gray-600': currentSlide !== index
                            }"
                            @click="currentSlide = index"
                        ></span>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button
                        @click="prevSlide"
                        :disabled="isFirstSlide"
                        class="px-6 py-2 text-sm font-medium transition-all duration-200"
                        :class="{
                            'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600': !isFirstSlide,
                            'opacity-50 cursor-not-allowed': isFirstSlide
                        }"
                    >
                        Previous
                    </button>

                    <button
                        v-if="!isLastSlide"
                        @click="nextSlide"
                        class="bg-black text-white px-6 py-2 text-sm font-medium shadow-md hover:bg-black focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200"
                    >
                        Next
                    </button>
                    <button
                        v-else
                        @click="close"
                        class="bg-black text-white px-6 py-2 text-sm font-medium shadow-md hover:bg-black focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200"
                    >
                        Start
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>