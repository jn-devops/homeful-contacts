<script setup>
import { onMounted, ref } from 'vue';

const emit = defineEmits(['close'])
const close = (val) => {
    show.value = false
    setTimeout(() => {
        emit('close', val);
    }, 100); 
}

const show = ref(false)

onMounted(() => {
    show.value = true
})
</script>
<template>
    <div 
        class="fixed inset-0 bg-gray-300/50 z-10 flex items-end justify-center"
    >
        <Transition
            enter-active-class="transform transition-transform duration-200 ease-in-out"
            enter-from-class="translate-y-full"
            enter-to-class="translate-y-0"
            leave-active-class="transform transition-transform duration-200 ease-in-out"
            leave-from-class="translate-y-0"
            leave-to-class="translate-y-full"
        >
            <div v-if="show" class="bg-white w-full max-w-[450px] h-[600px] rounded-t-xl shadow-lg p-4">
                <div class="flex flex-row-reverse">
                    <button @click="close">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm7.707-3.707a1 1 0 0 0-1.414 1.414L10.586 12l-2.293 2.293a1 1 0 1 0 1.414 1.414L12 13.414l2.293 2.293a1 1 0 0 0 1.414-1.414L13.414 12l2.293-2.293a1 1 0 0 0-1.414-1.414L12 10.586 9.707 8.293Z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
                <div class="overflow-y-auto w-full h-full">
                    <div>
                        <div class="leading-5">
                            <h3 class="font-bold">Term and Condition</h3>
                            <p class="text-xs">Effective Date: February 14, 2025</p>
                        </div>
                        <div class="text-sm mt-4 mb-8">
                            <ol class="list-decimal list-inside font-bold">
                                <li class="mb-2">
                                    <span class="">Acceptance of Terms</span>
                                    <p class="font-normal ps-4">By accessing or using HOMEFUL Shop, you agree to abide by these Terms of Use. If you do not agree, please refrain from using our platform.</p>
                                </li>
                                <li class="mb-2">
                                    <span class="">Use of the Platform</span>
                                    <ul class="font-normal list-disc ps-8">
                                        <li>Users must provide accurate and lawful information when using the platform.</li>
                                        <li>HOMEFUL Shop provides property listings for informational purposes only. We do not guarantee the accuracy of listings.</li>
                                        <li>The Home Match Calculator is provided as a guide and does not constitute financial advice.</li>
                                        <li>Unauthorized use, scraping, or tampering with the platform is strictly prohibited.</li>
                                    </ul>
                                </li>
                                <li class="mb-2">
                                    <span class="">Property Listings</span>
                                    <ul class="font-normal list-disc ps-8">
                                        <li>Listings are provided by Raemulan Lands Inc. and third-party developers.</li>
                                        <li>HOMEFUL Shop does not own or sell properties directly but serves as an intermediary.</li>
                                        <li>Prices, availability, and details are subject to change without notice.</li>
                                    </ul>
                                </li>
                                <li class="mb-2">
                                    <span class="">Partner Program</span>
                                    <ul class="font-normal list-disc ps-8">
                                        <li>Sellers must comply with our policies and legal requirements.</li>
                                        <li>HOMEFUL Shop reserves the right to terminate partnerships if unethical or fraudulent activities are detected.</li>
                                    </ul>
                                </li>
                                <li class="mb-2">
                                    <span class="">Limitation of Liability</span>
                                    <ul class="font-normal list-disc ps-8">
                                        <li>HOMEFUL Shop is not liable for any direct, indirect, or incidental damages related to property transactions or platform use.</li>
                                        <li>Users agree to use the platform at their own risk.</li>
                                    </ul>
                                </li>
                                <li class="mb-2">
                                    <span class="">Governing Law</span>
                                    <p class="font-normal ps-4">These Terms of Use shall be governed by the laws of [Insert Jurisdiction].</p>
                                </li>
                                <li class="mb-2">
                                    <span class="">Contact Us</span>
                                    <p class="font-normal ps-4">For any questions, contact us at [Insert Contact Information].</p>
                                </li>
                            </ol>
                            By using our platform, you acknowledge and agree to these terms.
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>