<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutV2.vue';
import SpouseInformationForm from "@/Pages/Spouse/Partials/SpouseInformationFormV2.vue";
import SpouseEmploymentForm from "@/Pages/Spouse/Partials/SpouseEmploymentFormV2.vue";
import { Head, router } from '@inertiajs/vue3';
import ExitModal from '@/Components/Modals/ExitModal.vue';
import ConfirmModal from '@/Components/Modals/ConfirmModal.vue';
import { ref } from 'vue';

const props = defineProps({
    spouse: Object,
    lazarus_url: String,
    lazarus_token: String,
});

const spouseInfo = ref(null);
const spouseEmployment = ref(null);
const showLogoutModal = ref(false)
const showLogoutConfirmationModal = ref(false)
const navigateModalLink = ref(null)
const isNavigated = ref(false)
const messageText = ref('You have unsaved changes that will be lost.')

const handleTrigger = () => {
    if (spouseInfo.value && spouseEmployment.value) {
        if(spouseInfo.value.checkFormDirty()){
            showLogoutModal.value = true
            messageText.value = "You have unsaved changes in Spouse Information"
        }else if(spouseEmployment.value.checkFormDirty()){
            showLogoutModal.value = true
            messageText.value = "You have unsaved changes in Spouse Employment"
        }else{
            showLogoutConfirmationModal.value = true
        }
    }
}

const routeToLogout = () => {
    router.post(route('logout'))
}

const navigatePage = (link) => {
    navigateModalLink.value = link
    // if(spouseInfo.value.checkFormDirty() || spouseEmployment.value.checkFormDirty()){
    if(spouseInfo.value.checkFormDirty()){
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
    if (spouseEmployment.value.checkFormDirty()){
        spouseEmployment.value.saveThisForm()
    }
    if (spouseInfo.value.checkFormDirty()){
        spouseInfo.value.saveThisForm()
    }
}

const saveFormToLogout = () => {
    saveForm()
    routeToLogout()
}

</script>

<template>
    <Head title="Spouse Information" />

    <AuthenticatedLayout @trigger-exit="handleTrigger" @navigate-page="navigatePage">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">

            <SpouseInformationForm
                :spouse = "spouse"
                ref="spouseInfo"
                :api_token="lazarus_token"
                :api_url="lazarus_url"
            />

            <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">

            <!-- <SpouseEmploymentForm
                :spouse = "spouse"
                ref="spouseEmployment"
                employment_type = "Primary"
                :api_token="lazarus_token"
                :api_url="lazarus_url"
            /> -->

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
