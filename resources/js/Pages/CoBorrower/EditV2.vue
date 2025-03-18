<script setup>
import CoBorrowerInformationForm from "@/Pages/CoBorrower/Partials/CoBorrowerInformationFormV2.vue";
import CoBorrowerEmploymentForm from "@/Pages/CoBorrower/Partials/CoBorrowerEmploymentFormV2.vue";
import CoBorrowerAddress from "@/Pages/CoBorrower/Partials/CoBorrowerAddress.vue";
import CoBorrowerSpouse from "@/Pages/CoBorrower/Partials/CoBorrowerSpouse.vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutV2.vue';
import SectionBorder from "@/Components/SectionBorder.vue";
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    contact: Object,
    lazarus_url: String,
    lazarus_token: String,
});
</script>

<template>
    <Head title="Co-Borrower Information" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <h1 class="mt-10 text-xl font-bold text-[#006FFD]">Co-Borrower 1:</h1>
            <CoBorrowerInformationForm
                :contact = "contact"
                co_borrower_type = "Primary"
            />

            <CoBorrowerSpouse
                :contact = "contact"
                co_borrower_type = "Primary"
            />

            <CoBorrowerAddress
                :contact = "contact"
                co_borrower_type = "Primary"
                address_type = "Primary"
                :api_token="lazarus_token"
                :api_url="lazarus_url"
            />
            
            <CoBorrowerEmploymentForm
                :contact = "contact"
                co_borrower_type = "Primary"
                employment_type = "Primary"
                :api_token="lazarus_token"
                :api_url="lazarus_url"
            />
            <hr class="border-1 border-gray-300" />
            <h1 class="mt-10 text-xl font-bold text-[#006FFD]">Co-Borrower 2:</h1>
            <CoBorrowerInformationForm
                :contact = "contact"
                co_borrower_type = "Secondary"
                employment_type = "Primary"
            />
            <CoBorrowerSpouse
                :contact = "contact"
                co_borrower_type = "Secondary"
            />

            <CoBorrowerAddress
                :contact = "contact"
                co_borrower_type = "Secondary"
                address_type = "Primary"
                :api_token="lazarus_token"
                :api_url="lazarus_url"
            />
            
            <CoBorrowerEmploymentForm
                :contact = "contact"
                co_borrower_type = "Secondary"
                employment_type = "Primary"
                :api_token="lazarus_token"
                :api_url="lazarus_url"
            />

        </div>
    </AuthenticatedLayout>
</template>
