<script setup>
import CoBorrowerInformationForm from "@/Pages/CoBorrower/Partials/CoBorrowerInformationFormV2.vue";
import CoBorrowerEmploymentForm from "@/Pages/CoBorrower/Partials/CoBorrowerEmploymentFormV2.vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutV2.vue';
import SectionBorder from "@/Components/SectionBorder.vue";
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    contact: Object
});
</script>

<template>
    <Head title="Co-Borrower Information" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <CoBorrowerInformationForm
                :contact = "contact"
                co_borrower_type = "Primary"
            />

            <CoBorrowerEmploymentForm
                :contact = "contact"
                co_borrower_type = "Primary"
                employment_type = "Primary"
            />

            <CoBorrowerInformationForm
                :contact = "contact"
                co_borrower_type = "Secondary"
                employment_type = "Primary"
            />

        </div>
    </AuthenticatedLayout>
</template>
