<script setup>
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, unref } from "vue";

const props = defineProps({
    contact: Object,
    name: String
});

const form = useForm({
    name: props.name,
    file: null,
});

const file_name = () => {
    return props.contact[props.name].file_name
};

const filename = computed(() => props.contact[props.name]?.file_name);
const unwrappedFilename = computed(() => unref(filename));

const uploadDocument = () => {
    form.post(route('media.store'), {
        errorBag: 'uploadDocument',
        preserveScroll: true,
    });
};

const removeDocument = () => {
    form.delete(route('media.destroy'), {
        errorBag: 'removeDocument',
        preserveScroll: true,
    });
};

</script>

<template>
    {{ filename }}

    <section>
        <form
            @submit.prevent="uploadDocument"
            class="mt-6 space-y-6"
        >
            <div>
                <InputLabel for="file" :value=form.name />

                <div class="flex">
                    <template v-if="filename">
                        <TextInput
                            :model-value="unwrappedFilename"
                            type="text"
                            class="mt-1 block w-full dark:text-orange-400 text-orange-600"
                            readonly
                        />
                    </template>
                    <template v-else>
                        <TextInput
                            id="file"
                            @input="form.file = $event.target.files[0]; uploadDocument()"
                            type="file"
                            accept=".jpg,.jpeg"
                            class="mt-1 block w-full inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                        />

                        <InputError class="mt-2" :message="form.errors.file" />
                    </template>
                </div>
            </div>

            <div class="flex items-center gap-4">

                <template v-if="filename">
                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="removeDocument"
                    >
                        Remove Document
                    </DangerButton>
                </template>
                <template v-else>
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
                </template>
            </div>
        </form>
    </section>
</template>

<style scoped>

</style>
