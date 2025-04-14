<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/Inputs/TextInput.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import GuestLayoutV2 from '@/Layouts/GuestLayoutV2.vue';
import PasswordInput from '@/Components/Inputs/PasswordInput.vue';
import PlainBlackButton from '@/Components/Buttons/PlainBlackButton.vue';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayoutV2>
        <Head title="Reset Password" />
        <div class="flex flex-col">
            <div class="flex flex-col items-start w-full">
                <div class="relative w-full">
                    <div class="w-full h-[400px]">
                        <img 
                            src="https://jn-img.enclaves.ph/Agapeya/Facade/ModelUnitWalkthrough.gif" alt="IMG"
                            class="w-full h-full object-cover"
                        >
                    </div>
                    <div class="z-20 absolute top-0 left-0 w-full h-[100px] bg-gradient-to-t from-transparent to-black">
                    </div>
                    <div class="p-10 w-full">
                        <form @submit.prevent="submit">

                            <h2 class="text-2xl font-extrabold">New Password</h2>
                            <div class="w-full mt-5">
                                <TextInput
                                    placeholder="Email Address"
                                    no-border-radius
                                    v-model="form.email"
                                    label="Email"
                                    required
                                    :error-message="form.errors.email"
                                />
                            </div>
                            <div class="w-full mt-5">
                                <PasswordInput
                                    placeholder="Password"
                                    no-border-radius
                                    v-model="form.password"
                                    label="Password"
                                    required
                                    :error-message="form.errors.password"
                                />
                            </div>
                            <div class="w-full mt-5">
                                <PasswordInput
                                    placeholder="Confirm Password"
                                    no-border-radius
                                    v-model="form.password_confirmation"
                                    label="Confirm Password"
                                    required
                                    :error-message="form.errors.password_confirmation"
                                />
                            </div>

                            <div class="mt-5">
                                <PlainBlackButton type="submit">
                                    <span class="font-semibold">Login</span>
                                </PlainBlackButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </GuestLayoutV2>
</template>
