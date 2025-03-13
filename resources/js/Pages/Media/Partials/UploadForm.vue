<script setup>
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue';
import DangerButton from '@/Components/Buttons/DangerButton.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import {computed, onMounted, ref, unref} from "vue";

// Import filepond
import vueFilePond, { setOptions } from 'vue-filepond';

// Import filepond plugins
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.esm.js';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.esm.js';

// Import filepond styles
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
import InputError from "@/Components/InputError.vue";
import SuccessToast from '@/Components/Notification/SuccessToast.vue';

const props = defineProps({
    csrf_token: String,
    contact: Object,
    name: String,
    label: String,
    previewUrl: String,
    fileType: String,
});

const filepondAvatarInput = ref(null); // Reference the input to clear the files later
const sucessMessage = ref('')
const url = ref(props.previewUrl)

const form = useForm({
    name: props.name,
    file: null
});

const uploadMedia = () => {
    form.patch(route('media.update'), {
            onSuccess: () => {
                sucessMessage.value = "Successfully uploaded the file"
            },
        });
};

const removeMedia = () => {
    url.value = null
    form.delete(route('media.destroy'), {
        errorBag: 'removeMedia',
        preserveScroll: true,
        onSuccess: () => {
            sucessMessage.value = "Successfully deleted the file"
        },
    });
};

// Create FilePond component
const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview);

// Set global options on filepond init
const handleFilePondInit = () => {
    setOptions({
        credits: false,
        server: {
            url: '/filepond',
            headers: {
                'X-CSRF-TOKEN': usePage().props.csrf_token,
            }
        }
    });
};
// Set the server id from response
const handleFilePondAvatarProcess = (error, file) => {
    form.file = file.serverId;
    console.log('onprocess');
};
// Remove the server id on file remove
const handleFilePondAvatarRemoveFile = (error, file) => {
    form.file = null;
};

const label = computed(() => props.label ?? props.name);
const filename = computed(() => props.contact[props.name]?.file_name);
// const url = computed(() => props.contact[props.name]?.preview_url);
const uploadedFiles = ref([]);

onMounted(() => {
    
});

</script>

<template>
    <section>
        <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
        >
            <SuccessToast 
                v-if="form.recentlySuccessful"
                :message="sucessMessage"
            />
        </Transition>
        <form
            @submit.prevent="uploadMedia"
            class="space-y-6"
        >
            <div class="mt-4">
                <InputLabel for="file" :value=label />
            </div>
            <template v-if="url">
                <template v-if="fileType == 'application/pdf'">
                    <div class="w-full h-[300px] flex flex-col items-center justify-center bg-gray-300 rounded-xl shadow-xl">
                        <embed :src="url" type="application/pdf" width="100%" height="100%" class="rounded-xl" />
                    </div>
                </template>
                <template v-else>
                    <div class="w-full h-[300px]">
                        <img class="h-full w-full object-cover rounded-lg shadow-xl" :src="url"/>
                    </div>
                </template>
                <DangerButton :disabled="form.processing" @click.prevent="removeMedia">
                    <div class="flex flex-row items-center gap-1">
                        <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-white font-bold text-center">
                            Remove File
                        </p>
                    </div>
                </DangerButton>
            </template>
            <template v-else>
                <FilePond
                    name="file"
                    ref="filepondAvatarInput"
                    class-name="my-pond"
                    allow-multiple="false"
                    accepted-file-types="image/*, application/pdf"
                    @init="handleFilePondInit"
                    @processfile="handleFilePondAvatarProcess"
                    @removefile="handleFilePondAvatarRemoveFile"
                />
                <InputError class="mt-2" :message="form.errors.file" />
                <SecondaryButton :disabled="form.processing" type="submit" customClass="w-auto">
                    <div class="flex flex-row items-center gap-1">
                        <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7.414A2 2 0 0 0 20.414 6L18 3.586A2 2 0 0 0 16.586 3H5Zm3 11a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v6H8v-6Zm1-7V5h6v2a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                            <path fill-rule="evenodd" d="M14 17h-4v-2h4v2Z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-white font-bold text-center">
                            Upload File
                        </p>
                    </div>
                </SecondaryButton>
            </template>
        </form>
    </section>
</template>
