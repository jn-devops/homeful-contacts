<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutV2.vue';
import EmploymentFormV2 from '@/Pages/Employment/Partials/EmploymentFormV2.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
   contact: Object,
   lazarus_url: String,
   lazarus_token: String,
});
</script>

<template>
    <Head title="Employment Information" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <EmploymentFormV2
                :contact = "contact"
                employment_type = "Primary"
                class="w-full"
                :api_token="lazarus_token"
                :api_url="lazarus_url"
            />
        </div>

    </AuthenticatedLayout>
</template>
