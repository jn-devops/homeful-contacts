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
                        <div class="leading-4">
                            <h3 class="text-sm">HOMEFUL.PH</h3>
                            <p class="text-sm uppercase font-bold">Privacy Policy</p>
                        </div>
                        <div class="text-sm mt-4 mb-8">
                            <p class="pb-2">
                                We, Joy~Nostalg Group, respect your privacy and recognize your need for appropriate protection and management of your personal data that you have directly and voluntarily entrusted us. We have developed this Privacy Policy in order to protect your personal data in accordance with the Data Privacy Act of 2012 (DPA), its Implementing Rules and Regulations (IRR), other issuances of National Privacy Commission (NPC) and other relevant laws of the Philippines.
                            </p>
                            <p class="pb-2">
                                Personal data refers to all types of personal information, sensitive personal information and privileged information. Personal information refers to “any information, whether recorded in a material form or not, from which the identity of an individual is apparent or can be reasonably and directly ascertained by the entity holding the information, or when put together with other information would directly and certainly identify an individual”.
                            </p>
                            <p class="pb-2">
                                This Privacy Policy tells you how we process (meaning, how we collect, use, share, and retain) and protect your personal data. It also tells you what steps you can take if you want us to change how we use your personal data, or if you want us to stop using your personal data.
                            </p>
                            <p class="pb-2">
                                Why do we collect your personal data?
                            </p>
                            <p class="pb-2">
                                To enable us to perform processes related with recruitment and hiring, it is important that Joy~Nostalg Group collects, uses, stores and retains your personal data when it is reasonable and necessary.
                            </p>
                            <p class="pb-2">
                                In particular, we are using your information to:
                            </p>
                            <ul class="font-normal list-disc ps-8 mb-2">
                                <li>Consider and evaluate your suitability for employment and, with your permission, inform and consider you for future job opportunities that may be of interest to you;</li>
                                <li>Provide us with the necessary information to perform related screening and background investigation;</li>
                                <li>Communicate with you about your employment application</li>
                            </ul>
                            <p class="pb-2">
                                What type of personal data we collect?
                            </p>
                            <p class="pb-2">
                                If you elect to apply for a job opening with Joy~Nostalg Group, you will be required to provide certain personal and sensitive personal Information in order to complete your application for employment.
                            </p>
                            <p class="pb-2">
                                The types of personal information that you are required to provide in connection with your application for employment may vary depending on the particular position for which you are submitting an application.
                            </p>
                            <p class="pb-2">
                                You are also required to provide us a copy of your resume/curriculum vitae and if you will be hired, you will submit the documents listed in the Pre-employment Requirements which contains personal and sensitive personal information.
                            </p>
                            <p class="pb-2">
                                The types of personal data collected by Joy~Nostalg Group from you includes the following:
                            </p>
                            <ul class="font-normal list-disc ps-8 mb-2">
                                <li>Basic personal information such as names, present address, contact number, email address;</li>
                                <li>Educational background, employment record, government examination passed;</li>
                                <li>Compensation and benefits package from current and previous employers;</li>
                                <li>Companies with pending application and status; and</li>
                                <li>Motivations for applying and character references</li>
                            </ul>
                            <p class="pb-2">
                                Who do we share your personal data with?
                            </p>
                            <p class="pb-2">
                                As a general rule, we are not allowed to share your data to a third party, except in limited circumstances as noted below.
                            </p>
                            <p class="pb-2">
                                By giving your consent, you authorize Joy~Nostalg Group to disclose your information to our affiliates or accredited third parties or independent/non-affiliated third parties, whether local or foreign in the following circumstances:
                            </p>
                            <ul class="font-normal list-disc ps-8 mb-2">
                                <li>As necessary for the proper execution of processes related to the declared purpose. The use or disclosure is reasonably necessary, required or authorized by or under law</li>
                            </ul>
                            <p class="pb-2">
                                This means we might provide information to the following:
                            </p>
                            <ul class="font-normal list-disc ps-8 mb-2">
                                <li>Our affiliates, subsidiaries, partner companies, organizations, or agencies including their sub-contractors or prospective business partners that act as our service providers and contractors;</li>
                                <li>Law enforcement and government agencies;</li>
                            </ul>
                            <p class="pb-2">
                                However, these companies may only use such personal data for the purpose(s) disclosed in this Privacy Policy and may not use it for any other purpose.
                            </p>
                            <p class="pb-2">
                                How do we protect your personal data?
                            </p>
                            <p class="pb-2">
                                We take reasonable steps to make sure that your personal data we collect, use or disclose are accurate, complete, and up-to-date. We strictly enforce our Privacy Policy within the Joy~Nostalg Group and we have implemented technological, organizational and physical security measures to protect your information we hold from loss, misuse, modification, unauthorized or accidental access or disclosure, alteration or destruction.
                            </p>
                            <p class="pb-2">
                                We put in effect safeguards such as the following:
                            </p>
                            <ul class="font-normal list-disc ps-8 mb-2">
                                <li>We keep and protect your personal data using secured servers behind a firewall, deploying encryption on computing devices and physical security controls.</li>
                                <li>We restrict access to your personal data only to qualified and authorized personnel who hold your personal data with strict confidentiality.</li>
                                <li>Any personal data that you provide is initially processed and stored by Joy~Nostalg Group Using a secured connection, only authorized Joy~Nostalg Group personnel can then access and download your personal data from the system.</li>
                            </ul>
                            <p class="pb-2">
                                How long do we keep your personal data?
                            </p>
                            <p class="pb-2">
                                Joy~Nostalg Group reserves the right to retain your personal data in our files located in the Philippines after 6 months from accomplishment of this form, unless you request your data to be deleted in our systems, databases, hardcopies immediately. Once deleted, your personal data will no longer be searchable or included in anonymous searches and will be completely removed from all the storage location. If Joy~Nostalg Group is required to retain any portion of your personal data for such purposes, it shall use reasonable efforts to limit such data to what is necessary to accomplish the particular purpose.
                            </p>
                            <p class="pb-2">
                                What if there are changes in our Privacy Policy?
                            </p>
                            <p class="pb-2">
                                From time to time, it may be necessary for Joy~Nostalg Group to change this Privacy Policy. If we change our Privacy Policy, we will communicate to data subject the latest Privacy Policy and it will take effect immediately. Changes will not be retroactively applied and will not alter how we handle previously collected personal data without obtaining your consent, unless required by law.
                            </p>
                            <p class="pb-2">
                                How you can access, correct and update the personal data we have about you?
                            </p>
                            <p class="pb-2">
                                To exercise your rights which include right to access, modify, erase and object to processing your personal data within a reasonable time after such request or should you have any inquiries, feedbacks on this Privacy Policy, and/or complaints to Joy~Nostalg Group, and you may reach us through a written letter or through an email to our Data Protection Officer (DPO).
                            </p>
                            <p class="pb-2">
                                Our contact details:
                            </p>
                            <p class="pb-2">
                                37/F Joy Nostalg Center ADB Avenue Ortigas Center, Pasig City
                                eve.dpo@elanvital.com.ph
                                (02) 8570-8888 
                            </p>
                            <p class="pb-2">
                                You may also lodge a complaint before the National Privacy Commission (NPC). For further details, please refer to NPC’s website: https://privacy.gov.ph/mechanics-for-complaints/.
                            </p>
                            <p class="pb-2">
                                Our decisions to provide such access or consider any request for correction, erasure and objection to process your personal data as it appears in our records are always subject to any exceptions under applicable and relevant laws and/or the DPA, its IRR and other issuances of NPC.
                            </p>
                            <p class="pb-2">
                                To cover for the cost of verifying a request for information and locating, retrieving, reviewing and copying any material requested, we may charge you reasonable fees based on administrative costs.
                            </p>
                            <p class="pb-2">
                                By sending your application,, you explicitly and unambiguously consent to the collection, processing and storage of your personal information by Joy~Nostalg Group, for the purpose(s) described in the foregoing Privacy Policy, and that you have freely and voluntarily signed this Consent and fully understood the contents of the Privacy Policy. Further, you agree to hold Joy~Nostalg Group, its directors, officers and employees, free and harmless from any liability, claim or damages of any nature, which may arise from its reliance on this Consent.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>