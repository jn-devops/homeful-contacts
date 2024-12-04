<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import {useForm, usePage} from '@inertiajs/vue3';

const props = defineProps({
    contact: Object,
    co_borrower_type: String
});

const co_borrowers = () => {
    if (null != props.contact?.addresses) {
        return Object.groupBy(props.contact?.co_borrowers, co_borrower => co_borrower.type)
    }

    return null;
};

const co_borrower = () => {
    if (co_borrowers())
        if (props.co_borrower_type in co_borrowers()) {
            return co_borrowers()[props.co_borrower_type][0]
        }

    return null;
};

const form = useForm({
    type: props.co_borrower_type,
    first_name: co_borrower()?.first_name,
    middle_name: co_borrower()?.middle_name,
    last_name: co_borrower()?.last_name,
    civil_status: co_borrower()?.civil_status,
    sex: co_borrower()?.sex,
    nationality: co_borrower()?.nationality ?? usePage().props.auth.user.contact.nationality,
    date_of_birth: co_borrower()?.date_of_birth,
    email: co_borrower()?.email,
    mobile: co_borrower()?.mobile
})

const updateCoBorrower = () => {
    form.patch(route('co_borrower.update'), {
        errorBag: 'updateCoBorrower',
        preserveScroll: true,
    });
};

</script>

<template>
    <section>
        <form
            @submit.prevent="updateCoBorrower"
            class="mt-6 space-y-6"
        >
            <h3 class="font-sans text-l text-gray-800 dark:text-gray-300 leading-tight">
                {{ props.co_borrower_type }} Co-Borrower
            </h3>

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
