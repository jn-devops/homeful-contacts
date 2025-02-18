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
                            <h3 class="font-bold">Privacy Policy</h3>
                            <p class="text-xs">Effective Date: February 14, 2025</p>
                        </div>
                        <div class="text-sm mt-4 mb-8">
                            <ol class="list-decimal list-inside font-bold">
                                <li class="mb-2">
                                    <span class="">Introduction</span>
                                    <p class="font-normal ps-4">HOMEFUL Shop ("we," "our," or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and protect your personal information when you use our e-commerce platform showcasing real estate projects from Raemulan Lands Inc.</p>
                                </li>
                                <li class="mb-2">
                                    <span class="">Information We Collect</span>
                                    <p class="font-normal ps-4">We may collect the following types of information:</p>
                                    <ul class="font-normal list-disc ps-8">
                                        <li><span class="font-bold">Personal Information:</span> Name, email address, phone number, and other contact details when you register, inquire, or sign up for our services.</li>
                                        <li><span class="font-bold">Property Preferences:</span> Details about your desired property type, location, and budget.</li>
                                        <li><span class="font-bold">Payment and Financial Data:</span> If applicable, we may collect payment-related details for transactions.</li>
                                        <li><span class="font-bold">Technical Data:</span> IP address, browser type, device information, and browsing behavior on our platform.</li>
                                    </ul>
                                </li>
                                <li class="mb-2">
                                    <span class="">How We Use Your Information</span>
                                    <p class="font-normal ps-4">We use your information for the following purposes:</p>
                                    <ul class="font-normal list-disc ps-8">
                                        <li>To provide, personalize, and improve our services.</li>
                                        <li>To connect you with real estate projects and property sellers.</li>
                                        <li>To process inquiries and facilitate transactions.</li>
                                        <li>To enhance user experience and provide customer support.</li>
                                        <li>To communicate promotional offers, updates, and marketing materials (with your consent).</li>
                                        <li>To comply with legal obligations and protect our platform from fraud.</li>
                                    </ul>
                                </li>
                                <li class="mb-2">
                                    <span class="">Sharing of Information</span>
                                    <p class="font-normal ps-4">We may share your information with:</p>
                                    <ul class="font-normal list-disc ps-8">
                                        <li><span class="font-bold">Real Estate Developers and Partners:</span> To facilitate property transactions.</li>
                                        <li><span class="font-bold">Service Providers:</span> Third-party vendors assisting in website operation, analytics, and marketing.</li>
                                        <li><span class="font-bold">Legal Authorities:</span> When required to comply with legal obligations or protect our rights.</li>
                                    </ul>
                                </li>
                                <li class="mb-2">
                                    <span class="">Data Security</span>
                                    <p class="font-normal ps-4">We implement appropriate security measures to protect your data from unauthorized access, alteration, or disclosure. However, no online platform is entirely risk-free.</p>
                                </li>
                                <li class="mb-2">
                                    <span class="">Your Rights</span>
                                    <p class="font-normal ps-4">You may request access, correction, or deletion of your personal information by contacting us at Raemulan Lands Inc. You may also opt out of marketing communications at any time.</p>
                                </li>
                                <li class="mb-2">
                                    <span class="">Changes to This Policy</span>
                                    <p class="font-normal ps-4">We reserve the right to update this Privacy Policy. Any changes will be posted on this page with an updated effective date.</p>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>