<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import { computed, ref } from "vue";

const props = defineProps({
    callback: String,
    showExtra: {
        type: Boolean,
        default: false
    },
    autoPassword: {
        type: String,
        default: ''
    }
});

const form = useForm({
    name: '',
    email: '',
    mobile: '',
    password: props.autoPassword,
    password_confirmation: props.autoPassword,
    date_of_birth: null,
    monthly_gross_income: 0,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
        onSuccess: (response) => {
            if (props.callback) {
                window.location.href = props.callback;
            }
        },
    });
};

const showPassword = computed(() => props.autoPassword === '');

</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    placeholder="[First Name] [Last Name]"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="mobile" value="Mobile" />

                <TextInput
                    id="mobile"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.mobile"
                    placeholder="(09##) ###-####"
                    required
                    autocomplete="mobile"
                />

                <InputError class="mt-2" :message="form.errors.mobile" />
            </div>

            <template v-if="showPassword">
                <div class="mt-4">
                    <InputLabel for="password" value="Password" />

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel
                        for="password_confirmation"
                        value="Confirm Password"
                    />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                    />
                </div>
            </template>

            <template v-if="showExtra">
                <div class="mt-4">
                    <InputLabel for="date_of_birth" value="Date of Birth" />

                    <TextInput
                        id="date_of_birth"
                        type="date"
                        class="mt-1 block w-full"
                        v-model="form.date_of_birth"
                    />

                    <InputError class="mt-2" :message="form.errors.date_of_birth" />
                </div>
                <div class="mt-4">
                    <InputLabel
                        for="monthly_gross_income"
                        value="Gross Monthly Income"
                    />

                    <TextInput
                        id="monthly_gross_income"
                        type="number"
                        class="mt-1 block w-full"
                        v-model="form.monthly_gross_income"
                        min="0"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.monthly_gross_income" />
                </div>
            </template>


            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                >
                    Already registered?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
