<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    spouse: Object,
});

const form = useForm({
    first_name: props.spouse?.first_name,
    middle_name: props.spouse?.middle_name,
    last_name: props.spouse?.last_name,
    civil_status: "Married",
    sex: props.spouse?.sex ?? 'Male' === usePage().props.auth.user.contact.sex ? 'Female' : 'Male',
    nationality: props.spouse?.nationality ?? usePage().props.auth.user.contact.nationality,
    date_of_birth: props.spouse?.date_of_birth,
    email: props.spouse?.email,
    mobile: props.spouse?.mobile
})

const updateSpouseInformation = () => {
    form.patch(route('spouse.update'), {
        errorBag: 'updateSpouseInformation',
        preserveScroll: true,
    });
};

</script>

<template>
    <section>
        <form
            @submit.prevent="updateSpouseInformation"
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
                    required
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
                />

                <InputError class="mt-2" :message="form.errors.date_of_birth" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="mobile" value="Mobile" />

                <TextInput
                    id="mobile"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.mobile"
                    required
                />

                <InputError class="mt-2" :message="form.errors.mobile" />
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
