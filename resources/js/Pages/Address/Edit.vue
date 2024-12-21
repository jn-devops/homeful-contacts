<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AddressForm from "@/Pages/Address/Partials/AddressForm.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    contact: Object
});
</script>

<template>
    <Head title="Personal Information" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Address Information
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <AddressForm
                        :contact = "contact"
                        address_type = "Primary"
                        class="max-w-xl"
                    />

                    <SectionBorder/>

                    <AddressForm
                        :contact = "contact"
                        address_type = "Secondary"
                        class="max-w-xl"
                    />
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
