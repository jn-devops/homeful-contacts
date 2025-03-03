<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutV2.vue';
import UploadForm from "@/Pages/Media/Partials/UploadForm.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import { Head } from '@inertiajs/vue3';
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
                <div v-for="(item, index) in matrices" :key="index" class="col-span-full lg:col-span-6 xl:col-span-4">
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
