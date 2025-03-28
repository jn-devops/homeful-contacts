<script setup>
import CoBorrowerInformationForm from "@/Pages/CoBorrower/Partials/CoBorrowerInformationFormV2.vue";
import CoBorrowerEmploymentForm from "@/Pages/CoBorrower/Partials/CoBorrowerEmploymentFormV2.vue";
import CoBorrowerAddress from "@/Pages/CoBorrower/Partials/CoBorrowerAddress.vue";
import CoBorrowerSpouse from "@/Pages/CoBorrower/Partials/CoBorrowerSpouse.vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutV2.vue';
import SectionBorder from "@/Components/SectionBorder.vue";
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref } from "vue";
import ExitModal from "@/Components/Modals/ExitModal.vue";
import ConfirmModal from "@/Components/Modals/ConfirmModal.vue";

const props = defineProps({
    contact: Object,
    lazarus_url: String,
    lazarus_token: String,
});

const page = usePage();

const primaryCoboInfo = ref(null);
const secondaryCoboInfo = ref(null);
const primaryCoboSpouse = ref(null);
const secondaryCoboSpouse = ref(null);
const primaryCoboAddress = ref(null);
const secondaryCoboAddress = ref(null);
const primaryCoboEmployment = ref(null);
const secondaryCoboEmployment = ref(null);
const showLogoutModal = ref(false)
const showLogoutConfirmationModal = ref(false)
const navigateModalLink = ref(null)
const isNavigated = ref(false)
const messageText = ref('You have unsaved changes that will be lost.')

const handleTrigger = () => {
    if(primaryCoboInfo.value.checkFormDirty()){
        showLogoutModal.value = true
        messageText.value = "You have unsaved changes in Primary Co-borrower Info"
    }else if(primaryCoboSpouse.value?.checkFormDirty()){
        showLogoutModal.value = true
        messageText.value = "You have unsaved changes in Primary Co-borrower Spouse"
    }else if(primaryCoboAddress.value.checkFormDirty()){
        showLogoutModal.value = true
        messageText.value = "You have unsaved changes in Primary Co-borrower Address"
    }else if(primaryCoboEmployment.value.checkFormDirty()){
        showLogoutModal.value = true
        messageText.value = "You have unsaved changes in Primary Co-borrower Eployment"
    }else if(secondaryCoboInfo.value.checkFormDirty()){
        showLogoutModal.value = true
        messageText.value = "You have unsaved changes in Secondary Co-borrower Info"
    }else if(secondaryCoboSpouse.value?.checkFormDirty()){
        showLogoutModal.value = true
        messageText.value = "You have unsaved changes in Secondary Co-borrower Spouse"
    }else if(secondaryCoboAddress.value.checkFormDirty()){
        showLogoutModal.value = true
        messageText.value = "You have unsaved changes in Secondary Co-borrower Address"
    }else if(secondaryCoboEmployment.value.checkFormDirty()){
        showLogoutModal.value = true
        messageText.value = "You have unsaved changes in Secondary Co-borrower Eployment"
    }else{
        showLogoutConfirmationModal.value = true
    }
}

const routeToLogout = () => {
    router.post(route('logout'))
}

const navigatePage = (link) => {
    navigateModalLink.value = link
    if(primaryCoboInfo.value.checkFormDirty() || primaryCoboSpouse.value?.checkFormDirty() || primaryCoboAddress.value.checkFormDirty() || primaryCoboEmployment.value.checkFormDirty() || secondaryCoboInfo.value.checkFormDirty() || secondaryCoboSpouse.value?.checkFormDirty() || secondaryCoboAddress.value.checkFormDirty() || secondaryCoboEmployment.value.checkFormDirty()){
        isNavigated.value = true
    }else{
        navigateToNext()
    }
}

const navigateToNext = (toSave = false) => {
    if(toSave){
        saveForm()
    }
    router.visit(navigateModalLink.value, {preserveScroll: true})
}

const saveForm = () => {
    if (primaryCoboInfo.value.checkFormDirty()){
        primaryCoboInfo.value.saveThisForm()
    }
    if (primaryCoboSpouse.value?.checkFormDirty()){
        primaryCoboSpouse.value?.saveThisForm()
    }
    if (primaryCoboAddress.value.checkFormDirty()){
        primaryCoboAddress.value.saveThisForm()
    }
    if (primaryCoboEmployment.value.checkFormDirty()){
        primaryCoboEmployment.value.saveThisForm()
    }
    if (secondaryCoboInfo.value.checkFormDirty()){
        secondaryCoboInfo.value.saveThisForm()
    }
    if (secondaryCoboSpouse.value?.checkFormDirty()){
        secondaryCoboSpouse.value?.saveThisForm()
    }
    if (secondaryCoboAddress.value.checkFormDirty()){
        secondaryCoboAddress.value.saveThisForm()
    }
    if (secondaryCoboEmployment.value.checkFormDirty()){
        secondaryCoboEmployment.value.saveThisForm()
    }
}

const saveFormToLogout = () => {
    saveForm()
    routeToLogout()
}

</script>

<template>
    <Head title="Co-Borrower Information" />

    <AuthenticatedLayout @trigger-exit="handleTrigger" @navigate-page="navigatePage">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <h1 class="mt-10 text-xl font-bold text-[#006FFD]">Co-Borrower 1:</h1>
            <CoBorrowerInformationForm
                :contact = "contact"
                co_borrower_type = "Primary"
                ref="primaryCoboInfo"
            />
            <CoBorrowerSpouse
                v-if="page?.props?.auth?.user?.contact?.co_borrowers?.find(item => item.type == 'Primary')?.civil_status == 'Married'"
                :contact = "page.props.auth.user.contact"
                co_borrower_type = "Primary"
                ref="primaryCoboSpouse"
            />

            <CoBorrowerAddress
                :contact = "contact"
                co_borrower_type = "Primary"
                address_type = "Primary"
                :api_token="lazarus_token"
                :api_url="lazarus_url"
                ref="primaryCoboAddress"
            />
            
            <CoBorrowerEmploymentForm
                :contact = "contact"
                co_borrower_type = "Primary"
                employment_type = "Primary"
                :api_token="lazarus_token"
                :api_url="lazarus_url"
                ref="primaryCoboEmployment"
            />
            <hr class="border-1 border-gray-300" />
            <h1 class="mt-10 text-xl font-bold text-[#006FFD]">Co-Borrower 2:</h1>
            <CoBorrowerInformationForm
                :contact = "contact"
                co_borrower_type = "Secondary"
                employment_type = "Primary"
                ref="secondaryCoboInfo"
            />
            <CoBorrowerSpouse
                v-if="page?.props?.auth?.user?.contact?.co_borrowers?.find(item => item.type == 'Secondary')?.civil_status == 'Married'"
                :contact = "contact"
                co_borrower_type = "Secondary"
                ref="secondaryCoboSpouse"
            />

            <CoBorrowerAddress
                :contact = "contact"
                co_borrower_type = "Secondary"
                address_type = "Primary"
                :api_token="lazarus_token"
                :api_url="lazarus_url"
                ref="secondaryCoboAddress"
            />
            
            <CoBorrowerEmploymentForm
                :contact = "contact"
                co_borrower_type = "Secondary"
                employment_type = "Primary"
                :api_token="lazarus_token"
                :api_url="lazarus_url"
                ref="secondaryCoboEmployment"
            />

        </div>
    </AuthenticatedLayout>

    <ExitModal
        v-if="showLogoutModal"
        @close="showLogoutModal = false"
        @trigger-save="saveFormToLogout"
    />

    <ConfirmModal
        v-if="showLogoutConfirmationModal"
        title="Are you sure you want to logout?"
        description="Make sure you know your credential. It was sent through your email."
        @close="showLogoutConfirmationModal = false"
        @handle-true="routeToLogout"
    />

    <ConfirmModal
        v-if="isNavigated"
        title="Unsaved Form Changes"
        description="Make sure to save your data before you navigate to other pages."
        true-label="Save and Proceed"
        false-label="Cancel"
        @close="isNavigated = false"
        @handle-true="navigateToNext(true)"
    />
</template>
