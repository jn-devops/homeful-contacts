<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/Inputs/TextInput.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import GuestLayoutV2 from '@/Layouts/GuestLayoutV2.vue';
import PlainBlackButton from '@/Components/Buttons/PlainBlackButton.vue';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayoutV2>
        <Head title="Forgot Password" />
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
                </div>
                <div class="p-10 w-full">
                    <h2 class="text-2xl font-extrabold">Forgot Password</h2>
                    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                        Forgot your password? No problem. Just let us know your email
                        address and we will email you a password reset link that will allow
                        you to choose a new one.
                    </div>
            
                    <div
                        v-if="status"
                        class="mb-4 text-sm font-medium text-green-600 dark:text-green-400"
                    >
                        {{ status }}
                    </div>
            
                    <form @submit.prevent="submit">
                        <div class="w-full mt-5">
                            <TextInput
                                placeholder="Email Address"
                                no-border-radius
                                label="Email"
                                v-model="form.email"
                                :error-message="form.errors.email"
                            />
                        </div>

                        <div class="mt-5">
                            <PlainBlackButton type="submit">
                                <span class="font-semibold">Submit</span>
                            </PlainBlackButton>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </GuestLayoutV2>
</template>
