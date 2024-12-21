<script setup>

import EmploymentForm from "@/Pages/Employment/Partials/EmploymentForm.vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
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
                Employment Information
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <EmploymentForm
                        :contact = "contact"
                        employment_type = "Primary"
                        class="max-w-xl"
                    />

                    <SectionBorder/>

                    <EmploymentForm
                        :contact = "contact"
                        employment_type = "Sideline"
                        class="max-w-xl"
                    />

                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
