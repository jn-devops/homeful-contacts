<script setup>

import AIFInformationForm from "@/Pages/AIF/Partials/AIFInformationFormV2.vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutV2.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    aif: Object
});
</script>

<template>
    <Head title="AIF Information" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-6 lg:px-8">

            <h3 class="font-bold text-[#CC035C] mt-4 uppercase">AIF Information</h3>

            <AIFInformationForm
                :aif = "aif"
            />

        </div>
    </AuthenticatedLayout>
</template>
