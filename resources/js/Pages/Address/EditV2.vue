<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutV2.vue';
import { Head } from '@inertiajs/vue3';
import AddressForm from '@/Pages/Address/Partials/AddressFormV2.vue';

const props = defineProps({
   contact: Object,
});
</script>

<template>
    <Head title="Address Information" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-6 lg:px-8">

            <AddressForm
                :contact = "contact"
                address_type = "Primary"
                class="w-full"
            />
            
            <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
            
            <AddressForm
                :contact = "contact"
                address_type = "Secondary"
                class="w-full"
            />

        </div>
    </AuthenticatedLayout>
</template>
