<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutV2.vue';
import UploadForm from "@/Pages/Media/Partials/UploadForm.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    contact: Object
});
</script>

<template>
    <Head title="Spouse Information" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <h3 class="font-bold text-[#CC035C] mt-4 uppercase">Document Information</h3>
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-full lg:col-span-6 xl:col-span-4">
                    <UploadForm
                        :contact = "contact"
                        name = "idImage"
                        label = "ID"
                    />
                </div>
                <div class="col-span-full lg:col-span-6 xl:col-span-4">
                    <UploadForm
                        :contact = "contact"
                        name = "selfieImage"
                        label = "Selfie"
                    />
                </div>
                <div class="col-span-full lg:col-span-6 xl:col-span-4">
                    <UploadForm
                        :contact = "contact"
                        name = "payslipImage"
                        label = "Pay Slip"
                    />
                </div>
                <div class="col-span-full lg:col-span-6 xl:col-span-4">
                    <UploadForm
                        :contact = "contact"
                        name = "signatureImage"
                        label = "Signature"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
