<script setup>

import AIFInformationForm from "@/Pages/AIF/Partials/AIFInformationFormV2.vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutV2.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from "vue";
import ExitModal from "@/Components/Modals/ExitModal.vue";
import ConfirmModal from "@/Components/Modals/ConfirmModal.vue";

const props = defineProps({
    aif: Object,
    lazarus_url: String,
    lazarus_token: String,
});

const authChild = ref(null);
const showLogoutModal = ref(false)
const showLogoutConfirmationModal = ref(false)
const navigateModalLink = ref(null)
const isNavigated = ref(false)

const handleTrigger = () => {
    if (authChild.value) {
        if(authChild.value.checkFormDirty()){
            showLogoutConfirmationModal.value = true
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
    if (authChild.value) {
        if(authChild.value.checkFormDirty()){
            isNavigated.value = true
        }else{
            navigateToNext()
        }
    }
}

const navigateToNext = (toSave = false) => {
    if(toSave){
        saveForm(toSave)
    }
    router.visit(navigateModalLink.value, {preserveScroll: true})
}

const saveForm = (toSave = false) => {
    if (authChild.value) {
        if(toSave){
            authChild.value.saveThisForm()
            return 
        }
        routeToLogout()
    }
}
</script>

<template>
    <Head title="AIF Information" />

    <AuthenticatedLayout @trigger-exit="handleTrigger" @navigate-page="navigatePage">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">

            <h3 class="font-bold text-[#006FFD] mt-4 uppercase">AIF Information</h3>

            <AIFInformationForm
                :aif = "aif"
                ref="authChild"
                :api_token="lazarus_token"
                :api_url="lazarus_url"
            />

        </div>
    </AuthenticatedLayout>

    <ExitModal
        v-if="showLogoutModal"
        @close="showLogoutModal = false"
        @trigger-save="saveForm"
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
