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
                            <p class="text-sm uppercase font-bold">Term of Use</p>
                        </div>
                        <div class="text-sm mt-4 mb-8">
                            <p class="pb-2">
                                Please read these Terms and Conditions of Use carefully. It regulates your use of this Website and all material contained within it. Access to and use of this Website is subject to the following terms:
                            </p>
                            <p class="font-bold">1. General</p>
                            <p class="pb-2">
                                This Website, found and accessible at <a href="https://www.homeful.ph" class="italic text-blue-700">www.homeful.ph</a> (the “Website”), is owned and made available for use by Raemulan Lands, Inc. (RLI) and its affiliates, primarily to inform visitors (“You”) of the past, current, and upcoming projects of RLI.
                            </p>
                            <p class="pb-2">
                                The information that RLI makes available on this Website is for general information purposes only, and cannot be interpreted and/or taken as legal advice and/or other professional advice.
                            </p>
                            <p class="font-bold">2. Acceptance of Terms of Use</p>
                            <p class="pb-2">
                                These Terms and Conditions of Use (the “Terms of Use”) govern your access and use of this Website, including any content, functionality, feature, information, materials, data and services offered on or through this Website (collectively, the “Contents”).
                            </p>
                            <p class="pb-2">
                                By accessing, browsing, using, and/or submitting any information and/or documents through this Website, you are expressly accepting and agreeing to be bound and to abide by all the terms and conditions contained in these Terms of Use.
                            </p>
                            <p class="pb-2">
                                To use certain services and functionalities of this Website, you may be subject to additional provisions aside from these Terms of Use. Should there be any conflict between these Terms of Use and the additional provisions, said additional provisions shall prevail.
                            </p>
                            <p class="font-bold">3. Use of Website</p>
                            <p class="pb-2">
                                Your use of this Website is subject to all applicable local, national and international laws and regulations, and you agree not to violate such laws and regulations.
                            </p>
                            <p class="pb-2">
                                In accessing, browsing, using, and/or submitting any information and/or documents through this Website, you agree not to:
                            </p>
                            <ol class="font-normal list-[lower-alpha] ps-8 mb-2">
                                <li>use this Website and/or the Contents for commercial purposes; and/or</li>
                                <li>use this Website and/or Contents for any purpose for which they are not intended; and/or</li>
                                <li>obtain unauthorized access to control this Website and/or to bypass any access restrictions; and/or</li>
                                <li>transmit through or on this Website any viruses, malware and/or other harmful/disruptive/destructive files; and/or</li>
                                <li>remove and/or disassociate from this Website and/or any Content any signs/symbols indicating proprietary rights of RLI and/or its owner; and/or</li>
                                <li>use this Website and/or the Contents for any illegal, immoral, unlawful and/or unauthorized purposes; and/or</li>
                                <li>infringe and/or violate any of the provisions in these Terms of Use.</li>
                            </ol>
                            <p class="pb-2">
                                Any attempt by you and/or any person to deliberately damage this Website is violative of criminal and/or civil laws. RLI reserves the right to seek damages from any such person to the fullest extent permitted by law.
                            </p>
                            <p class="pb-2">
                                RLI has the right to review and monitor all submitted, posted and/or stored User Contents on this Website. RLI reserves the right to remove, delete, or reject any User Content you may submit or upload which:
                            </p>
                            <ol class="font-normal list-[lower-alpha] ps-8 mb-2">
                                <li>is considered as spam; and/or</li>
                                <li>is “personal information” and/or “sensitive personal information,” as defined by Republic Act No. 10173 (the “Data Privacy Act”), which you submitted without the consent of the data owner;</li>
                                <li>contains unlawful, threatening, abusive harassing, defamatory, vulgar, or discriminatory language; and/or</li>
                                <li>is violative of any applicable law.</li>
                            </ol>
                            <p class="pb-2">
                                This Website may require you to submit and/or upload documents and/or information. It is your sole responsibility to ensure that the documents and/or information that you provide through this Website (collectively, the “User Contents”) are true, current, complete and correct. Unless otherwise provided herein or required by applicable law, RLI shall not be responsible for the User Contents that you submit or upload using the Website, including their legality or authenticity.
                            </p>
                            <p class="pb-2">
                                The information provided on this Website is not intended to create any relationship with you, whether in contract or otherwise.
                            </p>
                            <p class="pb-2">
                                The information provided on this Website is not intended to create any relationship with you, whether in contract or otherwise.
                            </p>
                            <p class="font-bold">
                                4. Changes to Terms of Use and/or Website
                            </p>
                            <p class="pb-2">
                                RLI reserves the right to modify, omit, supplement or amend the Contents of this Website and to change these Terms of Use from time to time, in whole or in part, in its sole discretion and for any reason, without prior notice and/or liability to you and/or any other person. Your continued use of this Website after changes to the revisions/amendments to these Terms of Use have been published constitutes your binding acceptance of the updated Terms of Use.
                            </p>
                            <p class="font-bold">
                                5. Ownership of Website Contents
                            </p>
                            <p class="pb-2">
                                All Contents, including but not limited to sounds, photographs, video clips, graphics, texts, button icons, reports, documents, presentations, and software, are, unless otherwise indicated in this Website, owned by or licensed to RLI and are protected by applicable national or local laws, rules and regulations.
                            </p>
                            <p class="pb-2">
                                Any unauthorized use, distribution, reproduction, and recompilation through any form or means of any or all of the Contents are not allowed without the expressed knowledge and prior written approval of the RLI or their respective owners/. You may not modify in any way any part of this Website or any Content thereof.
                            </p>
                            <p class="font-bold">
                                6. External Links
                            </p>
                            <p class="pb-2">
                                This Website may contain links to other websites or resources and businesses operated by third-parties, and such links are provided only for your convenience. The inclusion of any link to another website does not imply endorsement by RLI of said website and/or their owners, products or services.
                            </p>
                            <p class="pb-2">
                                RLI does not own and has not reviewed the linked websites, and is not responsible for the content or availability of any linked websites.
                            </p>
                            <p class="font-bold">
                                7. Privacy and Cookies
                            </p>
                            <p class="pb-2">
                                Your submission of your “personal information” and/or “sensitive personal information” through or in connection with this Website as well as the use of cookies and similar tracking technologies in this Website, shall be governed by the terms and conditions of the RLI’s Privacy Policy for this Website (the “Privacy Policy”).
                            </p>
                            <p class="font-bold">
                                8. Trademarks and Copyrights
                            </p>
                            <p class="pb-2">
                                All trademarks, logos, and service marks displayed on this Website are the property of RLI and/or its affiliates, and cannot be used without the prior written consent of RLI or their respective owner/s. The display of trademarks or copyrighted works on this Website does not convey or create any license or other rights in them in your favor or in favor of any visitor or user of this Website.
                            </p>
                            <p class="pb-2">
                                RLI owns the intellectual property rights in the Contents that is made available on this Website, and/or has obtained the permission of the owner/s to make these available on this Website. The redistribution and/or copying of this Website, in whole or in part, without the written approval from RLI is strictly prohibited.
                            </p>
                            <p class="pb-2">
                                You are authorized to display on your own computer, download, or print the pages from this Website provided that:
                            </p>
                            <ol class="font-normal list-[lower-alpha] ps-8 mb-2">
                                <li>The copyright notice appears on all printouts; and</li>
                                <li>The information is intact and unaltered; and</li>
                                <li>The contents used are for personal, educational, or non-commercial purposes only; and</li>
                                <li>The information is not redistributed and/or copied to any other media.</li>
                            </ol>
                            <p class="pb-2">
                                No right, title, or interest in any downloaded Content is transferred to you as a result of any such downloading or copying. You may not reproduce (except as provided above), publish, transmit, distribute, display, modify, create derivative works from, sell or participate in any sale of, or exploit in any way, in whole or in part, this Website and/or any of its Contents.
                            </p>
                            <p class="pb-2">
                                RLI reserves all its causes of actions, rights and remedies arising from any infringement of the local and international intellectual property laws.
                            </p>
                            <p class="font-bold">
                                9. Disclaimer of Warranties
                            </p>
                            <p class="pb-2">
                                You acknowledge that your access and use of this Website, including any Content, is at your sole risk, and that you assume full responsibility for all risks associated therewith.
                            </p>
                            <p class="pb-2">
                                RLI provides this Website and the Contents on an “as is” and “as available” basis, without any warranty whatsoever, whether express or implied. RLI is not responsible if the information that is made available on this Website is not accurate and/or complete. Any reliance upon the material on this website shall be at your own risk. You agree that it is your responsibility to monitor any changes to the material and the information contained on this website.
                            </p>
                            <p class="pb-2">
                                RLI disclaims implied warranties, including any warranty that any Content is or will be merchantable, of satisfactory quality, accurate, timely, fit for a particular purpose or need, or non-infringing. RLI does not guarantee that this Website or any Content will meet your requirements, is reliable, timely, useful, or suitable, or that defects, if any, will be corrected, including loss of data resulting from delays, and any service interruption caused by RLI.
                            </p>
                            <p class="pb-2">
                                RLI controls this Website from its Philippine office. RLI shall not be responsible for transmission errors or corruption or compromise of data carried over local or interchange telecommunication carriers or other similar channels. If you access and use this Website from other locations, you are responsible for compliance with applicable local laws. RLI makes no representation that the units and/or services referenced herein are appropriate, or available, worldwide and in fact certain services may not be available worldwide.
                            </p>
                            <p class="font-bold">
                                10. Limitation of Liability
                            </p>
                            <p class="pb-2">
                                You are accessing, browsing, and using this Website at your own risk. Under no circumstances will RLI be liable to you for, and you hereby knowingly and voluntarily release the RLI from, any claim relating to any loss or damage that you might directly or indirectly suffer (including but not limited to incidental, special, consequential, or moral damages, loss of profits, and damages resulting from lost data or business interruption) as a result of your use of this Website or its Contents, your inability to use this Website irrespective of the cause of that inability, any delay in or interruption of your use of this Website regardless of the cause of the delay or interruption, any corruption of any data that is transmitted by you to RLI via this Website, or transmitted by RLI to you by any method whatsoever, your reliance upon any Content, any intentional or unintentional disclosure or publication of any User Content transmitted, submitted or uploaded by you through this Website, or any other act, omission, matter or thing whatsoever having to do with this Website or any Content.
                            </p>
                            <p class="font-bold">
                                11. Indemnity
                            </p>
                            <p class="pb-2">
                                By accessing, browsing, using, and/or submitting any information and/or documents through this Website, you agree to indemnify and hold RLI and its directors, officers, employees, representatives, agents and consultants, harmless from and against any and all rights, demands, losses, liabilities, damages, claims, causes of action and suits (whether at law or equity), fees, costs, and attorneys’ fees (including appellate level) of any kind whatsoever arising, directly or indirectly, out of or in connection with your use or misuse of this Website or any Content, your breach of these Terms of Use or the Privacy Policy, the User Content you provide to RLI through this Website, or any negligent or wrongful action or omission by you, including but not limited to infringement of third party intellectual property rights, privacy rights, or negligent or wrongful conduct.
                            </p>
                            <p class="font-bold">
                                12. Force Majeure
                            </p>
                            <p class="pb-2">
                                RLI shall not be liable for any damage, claims, liabilities, losses, costs, and expenses arising out of the delay and/or interruption of its performance of any obligations due to any act of God, act of governmental authority, act of a public enemy, war, riot, flood, typhoon, earthquake, pandemic, natural disaster, civil commotion, insurrection, severe weather conditions or any other cause beyond the reasonable control of RLI.
                            </p>
                            <p class="font-bold">
                                13. Waiver and Severability
                            </p>
                            <p class="pb-2">
                                Any failure of RLI to insist upon or enforce strict performance of any provision of these Terms of Use shall not be construed as a waiver of any of its provision or any of RLI’s rights or remedies.
                            </p>
                            <p class="pb-2">
                                If any provision of these Terms of Use is declared illegal or unenforceable, its invalidity shall not affect the other provisions of these Terms of Use that can be given effect without the invalid provision.
                            </p>
                            <p class="pb-2">
                                If any provision of these Terms of Use does not comply with any applicable law, such provision to the extent possible shall be interpreted in such a manner as to comply with applicable law, or if such interpretation is not possible, it shall be deemed to satisfy the minimum requirements thereof.
                            </p>
                            <p class="font-bold">
                                14. Governing Law
                            </p>
                            <p class="pb-2">
                                These Terms of Use shall be governed by and construed in accordance with the laws of the Republic of the Philippines.
                            </p>
                            <p class="font-bold">
                                15. Contact Information
                            </p>
                            <p class="pb-2">
                                For additional information regarding the Terms of Use and/or this Website, you may contact RLI and/or the Website Administrator at:
                            </p>
                            <p class="pb-2">
                                Telephone No.: (+63) 945 667 7654<br>
                                Email: admin@homeful.com
                            </p>

                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>