<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutV2.vue';
import { Head, router } from '@inertiajs/vue3';
import AddressForm from '@/Pages/Address/Partials/AddressFormV2.vue';
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';import ExitModal from '@/Components/Modals/ExitModal.vue';
import ConfirmModal from '@/Components/Modals/ConfirmModal.vue';

const props = defineProps({
   contact: Object,
   lazarus_url: String,
   lazarus_token: String,
});

const presentAddress = ref(null);
const permanentAddress = ref(null);
const showLogoutModal = ref(false)
const showLogoutConfirmationModal = ref(false)
const navigateModalLink = ref(null)
const isNavigated = ref(false)
const messageText = ref('You have unsaved changes that will be lost.')

const handleTrigger = () => {
    if (presentAddress.value && permanentAddress.value) {
        if(presentAddress.value.checkFormDirty()){
            showLogoutModal.value = true
            messageText.value = "You have unsaved changes in Present Address"
        }else if(permanentAddress.value.checkFormDirty()){
            showLogoutModal.value = true
            messageText.value = "You have unsaved changes in Permanent Address"
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
    if(presentAddress.value.checkFormDirty() || permanentAddress.value.checkFormDirty()){
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
    if (permanentAddress.value.checkFormDirty()){
        permanentAddress.value.saveThisForm()
    }
    if (presentAddress.value.checkFormDirty()){
        presentAddress.value.saveThisForm()
    }
}

const saveFormToLogout = () => {
    saveForm()
    routeToLogout()
}

onMounted(() => {

})
</script>

<template>
    <Head title="Address Information" />

    <AuthenticatedLayout @trigger-exit="handleTrigger" @navigate-page="navigatePage">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            
            <AddressForm
                ref="presentAddress"
                :contact = "contact"
                :api_token="lazarus_token"
                :api_url="lazarus_url"
                address_type = "Present"
                class="w-full"
            />

            <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
            
            <AddressForm
                ref="permanentAddress"
                :contact = "contact"
                :api_token="lazarus_token"
                :api_url="lazarus_url"
                address_type = "Permanent"
                class="w-full"
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
