<script setup>

import SpouseInformationForm from "@/Pages/Spouse/Partials/SpouseInformationForm.vue";
import SpouseEmploymentForm from "@/Pages/Spouse/Partials/SpouseEmploymentForm.vue";
import CoBorrowerForm from "@/Pages/CoBorrower/Partials/CoBorrowerForm.vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SectionBorder from "@/Components/SectionBorder.vue";

import { Head } from '@inertiajs/vue3';
import EmploymentForm from "@/Pages/Personal/Partials/EmploymentForm.vue";

const props = defineProps({
    contact: Object
});
</script>

<template>
    <Head title="Spouse Information" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Spouse Information
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <CoBorrowerForm
                        :contact = "contact"
                        co_borrower_type = "Primary"
                        class="max-w-xl"
                    />

                    <SectionBorder/>

                    <CoBorrowerForm
                        :contact = "contact"
                        co_borrower_type = "Secondary"
                        class="max-w-xl"
                    />
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
