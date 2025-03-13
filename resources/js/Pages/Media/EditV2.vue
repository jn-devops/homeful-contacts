<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutV2.vue';
import UploadForm from "@/Pages/Media/Partials/UploadForm.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import { Head, Link } from '@inertiajs/vue3';
import Signature from './Partials/Signature.vue';
import { computed } from 'vue';

const props = defineProps({
    contact: Object,
    matrices: Object,
});

</script>

<template>
    <Head title="Attachments" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <h3 class="font-bold text-[#006FFD] mt-4 uppercase">Document Information</h3>
            <div class="grid grid-cols-12 gap-4 pb-10">
                <div v-if="matrices.length === 0" class="col-span-full mt-16">
                    <div class="w-full">
                        <div class="w-full text-center flex items-center justify-center">
                            <svg class="w-32 h-32 text-orange-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm9.408-5.5a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM10 10a1 1 0 1 0 0 2h1v3h-1a1 1 0 1 0 0 2h4a1 1 0 1 0 0-2h-1v-4a1 1 0 0 0-1-1h-2Z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="text-center text-lg font-semibold text-gray-700 mt-4">
                            Please complete the Customer Information Form first so that we can show the required documents that need to be uploaded.
                        </div>
                    </div>
                </div>
                <div v-else v-for="(item, index) in matrices" :key="index" class="col-span-full lg:col-span-6 xl:col-span-4">
                    <UploadForm
                        :contact = "contact"
                        :name = "item?.code"
                        :label = "item?.name"
                        :preview-url="item?.url"
                        :file-type="item?.type"
                    />
                </div>
                <!-- <div class="col-span-full lg:col-span-6 xl:col-span-4">
                    <p class="text-sm font-medium text-gray-700 w-full text-start pb-4">Signature</p>
                    <div class="w-full h-[300px] flex flex-col items-center justify-center bg-white rounded-lg">
                        <Signature 
                            :contact="contact"
                        />
                    </div>
                </div> -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>
