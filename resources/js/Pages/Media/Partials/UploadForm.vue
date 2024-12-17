<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import {computed, ref, unref} from "vue";

// Import filepond
import vueFilePond, { setOptions } from 'vue-filepond';

// Import filepond plugins
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.esm.js';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.esm.js';

// Import filepond styles
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
import DangerButton from "@/Components/DangerButton.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    csrf_token: String,
    contact: Object,
    name: String,
    label: String
});

const filepondAvatarInput = ref(null); // Reference the input to clear the files later

const form = useForm({
    name: props.name,
    file: null
});

const uploadMedia = () => {
    form.patch(route('media.update'), {
            onSuccess: () => {
                // filepondAvatarInput.value.removeFiles();
                // filepondAvatarInput.value = null;
            },
        });
};

const removeMedia = () => {
    form.delete(route('media.destroy'), {
        errorBag: 'removeMedia',
        preserveScroll: true,
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
};
// Remove the server id on file remove
const handleFilePondAvatarRemoveFile = (error, file) => {
    form.file = null;
};

const label = computed(() => props.label ?? props.name);
const filename = computed(() => props.contact[props.name]?.file_name);
const url = computed(() => props.contact[props.name]?.preview_url);

</script>

<template>
    <section>
        <form
            @submit.prevent="uploadMedia"
            class="space-y-6"
        >
            <div class="mt-4">
                <InputLabel for="file" :value=label />
            </div>
            <template v-if="url">
                <img :src="url"/>
                <DangerButton :disabled="form.processing" @click.prevent="removeMedia">
                    Remove File
                </DangerButton>
            </template>
            <template v-else>
                <FilePond
                    name="file"
                    ref="filepondAvatarInput"
                    class-name="my-pond"
                    allow-multiple="false"
                    accepted-file-types="image/*"
                    @init="handleFilePondInit"
                    @processfile="handleFilePondAvatarProcess"
                    @removefile="handleFilePondAvatarRemoveFile"
                />
                <InputError class="mt-2" :message="form.errors.file" />
                <PrimaryButton :disabled="form.processing">Upload File</PrimaryButton>
            </template>
        </form>
    </section>
</template>
