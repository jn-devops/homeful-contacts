<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    contact: Object,
});

const form = useForm({
    first_name: props.contact?.first_name ?? usePage().props.auth.user.first_name,
    middle_name: props.contact?.middle_name,
    last_name: props.contact?.last_name ?? usePage().props.auth.user.last_name,
    name_suffix: props.contact?.name_suffix,
    mothers_maiden_name: props.contact?.mothers_maiden_name,
    civil_status: props.contact?.civil_status,
    sex: props.contact?.sex,
    nationality: props.contact?.nationality,
    date_of_birth: props.contact?.date_of_birth,
    email: props.contact?.email ?? usePage().props.auth.user.email,
    mobile: props.contact?.mobile ?? usePage().props.auth.user.mobile,
    other_mobile: props.contact?.other_mobile,
    help_number: props.contact?.help_number,
    landline: props.contact?.landline,
})

const updatePersonalInformation = () => {
    form.patch(route('personal.update'), {
        errorBag: 'updatePersonalInformation',
        preserveScroll: true,
    });
};

</script>

<template>
    <section>
        <form
            @submit.prevent="updatePersonalInformation"
            class="mt-6 space-y-6"
        >
            <div>
                <InputLabel for="first_name" value="First Name" />

                <TextInput
                    id="first_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.first_name"
                    required
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.first_name" />
            </div>

            <div>
                <InputLabel for="middle_name" value="Middle Name" />

                <TextInput
                    id="middle_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.middle_name"
                />

                <InputError class="mt-2" :message="form.errors.middle_name" />
            </div>

            <div>
                <InputLabel for="last_name" value="Last Name" />

                <TextInput
                    id="last_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.last_name"
                    required
                />

                <InputError class="mt-2" :message="form.errors.last_name" />
            </div>

            <div>
                <InputLabel for="name_suffix" value="Suffix" />

                <TextInput
                    id="name_suffix"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name_suffix"
                />

                <InputError class="mt-2" :message="form.errors.name_suffix" />
            </div>

            <div>
                <InputLabel for="mothers_maiden_name" value="Mother's Maiden Name" />

                <TextInput
                    id="mothers_maiden_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.mothers_maiden_name"
                />

                <InputError class="mt-2" :message="form.errors.mothers_maiden_name" />
            </div>

            <div>
                <InputLabel for="civil_status" value="Civil Status" />

                <TextInput
                    id="civil_status"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.civil_status"
                />

                <div class="text-xs text-gray-600 dark:text-gray-400">{{ usePage().props.enums.civil_statuses.join(', ') }}</div>
                <InputError class="mt-2" :message="form.errors.civil_status" />
            </div>

            <div>
                <InputLabel for="sex" value="Sex" />

                <TextInput
                    id="sex"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.sex"
                />

                <div class="text-xs text-gray-600 dark:text-gray-400">{{ usePage().props.enums.sexes.join(', ') }}</div>
                <InputError class="mt-2" :message="form.errors.sex" />
            </div>

            <div>
                <InputLabel for="nationality" value="Nationality" />

                <TextInput
                    id="nationality"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.nationality"
                />

                <div class="text-xs text-gray-600 dark:text-gray-400">{{ usePage().props.enums.nationalities.join(', ') }}</div>
                <InputError class="mt-2" :message="form.errors.nationality" />
            </div>

            <div>
                <InputLabel for="date_of_birth" value="Date of Birth" />

                <TextInput
                    id="date_of_birth"
                    type="date"
                    class="mt-1 block w-full"
                    v-model="form.date_of_birth"
                    required
                />

                <InputError class="mt-2" :message="form.errors.date_of_birth" />
            </div>

            <div>
                <InputLabel for="email" value="Primary Email Address" />

                <TextInput
                    id="email"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.email"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="mobile" value="Primary Mobile" />

                <TextInput
                    id="mobile"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.mobile"
                />

                <InputError class="mt-2" :message="form.errors.mobile" />
            </div>

            <div>
                <InputLabel for="other_mobile" value="Other Mobile" />

                <TextInput
                    id="other_mobile"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.other_mobile"
                />

                <InputError class="mt-2" :message="form.errors.other_mobile" />
            </div>

            <div>
                <InputLabel for="help_number" value="Help Number" />

                <TextInput
                    id="help_number"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.help_number"
                />

                <InputError class="mt-2" :message="form.errors.help_number" />
            </div>

            <div>
                <InputLabel for="landline" value="Land Line" />

                <TextInput
                    id="landline"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.landline"
                />

                <InputError class="mt-2" :message="form.errors.landline" />
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
