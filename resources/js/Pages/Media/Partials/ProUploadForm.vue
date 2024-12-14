<script setup>
import { MediaLibraryAttachment } from 'media-library-pro-vue3-attachment';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Field from "@/Pages/Media/Components/Field.vue";
import Grid from "@/Pages/Media/Components/Grid.vue";
import { ref, reactive } from 'vue';
import axios from 'axios';

const mediaComponent = ref(null);
const value = reactive({
    name: '',
    media: {},
});
const validationErrors = reactive({});
const isUploadSuccess = ref(false);

const props = defineProps({
    contact: Object,
});
const form = useForm({
    id_image: props.contact?.id_image,
})

function onChange(media) {
    value.media = media;
}
function onSubmit() {
    isUploadSuccess.value = false;
    Object.keys(validationErrors).forEach(key => delete validationErrors[key]);

    axios
        .post('', value)
        .then(res => {
            if (res.data.success) {
                isUploadSuccess.value = true;
                value.name = '';
                value.media = {};

                mediaComponent.value.mediaLibrary.changeState(state => {
                    state.media = [];
                });
            }
        })
        .catch(error => {
            console.error(error);

            if (error && error.response && error.response.data) {
                Object.assign(validationErrors, error.response.data.errors || {});
            }
        });
}

const upload = () => {
    form.patch(route('aif.update'), {
        errorBag: 'updateAIFInformation',
        preserveScroll: true,
    });
};

</script>

<template>
    <section>
        <form
            @submit.prevent="upload"
            class="mt-6 space-y-6"
        >
            <div>
                <Field label="file">
                    <media-library-attachment
                        ref="mediaComponent"
                        name="media"
                        :initial-value="value.media"
                        :validation-rules="{ accept: ['image/png', 'image/jpeg', 'application/pdf'] }"
                        :validation-errors="validationErrors"
                        multiple
                        @change="onChange"
                    ></media-library-attachment>
                </Field>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
