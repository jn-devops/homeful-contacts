<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutV2.vue';
import { Head } from '@inertiajs/vue3';
import PersonalInformationForm from './Partials/PersonalInformationFormV2.vue';

const props = defineProps({
   contact: Object,
});
</script>

<template>
    <Head title="Personal Information" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <h3 class="font-bold text-[#CC035C] mt-4">PERSONAL DETAILS:</h3>

            <PersonalInformationForm
                :contact = "contact"
                class="w-full"
            />
        </div>
    </AuthenticatedLayout>
</template>
