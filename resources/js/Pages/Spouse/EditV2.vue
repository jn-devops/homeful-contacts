<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutV2.vue';
import SpouseInformationForm from "@/Pages/Spouse/Partials/SpouseInformationFormV2.vue";
import SpouseEmploymentForm from "@/Pages/Spouse/Partials/SpouseEmploymentFormV2.vue";
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    spouse: Object
});
</script>

<template>
    <Head title="Spouse Information" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-6 lg:px-8">

            <SpouseInformationForm
                :spouse = "spouse"
            />

            <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">

            <SpouseEmploymentForm
                :spouse = "spouse"
                employment_type = "Primary"
            />

        </div>
    </AuthenticatedLayout>
</template>
