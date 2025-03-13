<script setup>
import PlainBlackButton from '@/Components/Buttons/PlainBlackButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayoutV2 from '@/Layouts/GuestLayoutV2.vue';
import { Vue3Lottie } from "vue3-lottie";
import { Head, useForm } from '@inertiajs/vue3';
import GradientStyleInput from '@/Components/Inputs/GradientStyleInput.vue';
import GradientStylePhoneNumber from '@/Components/Inputs/GradientStylePhoneNumber.vue';
import GradientStyleTextArea from '@/Components/Inputs/GradientStyleTextArea.vue';
import SuccessTimerToast from '@/Components/Notification/SuccessTimerToast.vue';
import { ref } from 'vue';
import ErrorTimerToast from '@/Components/Notification/ErrorTimerToast.vue';

const props = defineProps({
    name: {
        type: String,
        default: ''
    },
    mobile: {
        type: String,
        default: ''
    },
});

const successToast = ref(false);
const failedToast = ref(false);

const form = useForm({
    name: props.name,
    mobile: props.mobile,
    message: '',
});

const submit = () => {
    form.post(route('unqualified-user.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            successToast.value = true;
        },
        onError: () => {
            failedToast.value = true;
        }
    });
}

</script>

<template>
    <Head title="Unqualified" />

    <GuestLayoutV2>
        <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
        >
            <SuccessTimerToast 
                v-if="successToast"
                v-model:show="successToast"
                title="Successfully Sent"
                body="Thank you for bringing your concern to our attention. One of our representatives will reach out to you shortly."
            />
        </Transition>
        <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
        >
            <ErrorTimerToast 
                v-if="failedToast"
                v-model:show="failedToast"
                title="Failed"
                body="There is someting wrong with your submission. Please try again."
            />
        </Transition>
        <div class="flex flex-col mt-10 px-8">
            <div class="mt-6 w-full">
                <div class="flex flex-col items-center">
                    <div class="w-40">
                        <Vue3Lottie 
                            animationLink="/animation/SadFaceAnimation.json" 
                            width="100%" 
                        />
                    </div>
                    <h1 class="text-xl font-bold mt-7">Registration Failed</h1>
                    <p class="text-center mt-3 text-sm">We appreciate your interest! After reviewing your Home Match criteria, it seems that there isn't a suitable match based on your GMI and age. However, we would be happy to explore other options that may work for you.</p>
                </div>
                <div class="mt-16 pb-20">
                    <form @submit.prevent="submit">
                        <div class="mt-10">
                            <GradientStyleInput
                                label="Name"
                                placeholder="Enter Name"
                                required
                                v-model="form.name"
                                :error="form.errors.name"
                            />
                        </div>
                        <div class="mt-3">
                            <GradientStylePhoneNumber
                                label="Mobile Number"
                                placeholder="+63 900 000 0000"
                                required
                                v-model="form.mobile"
                                :error="form.errors.mobile"
                            />
                        </div>
                        <div class="mt-3">
                            <GradientStyleTextArea
                                label="Message"
                                placeholder="Enter Message"
                                type="textarea"
                                v-model="form.message"
                                :error="form.errors.message"
                            />
                        </div>
                        <PlainBlackButton class="mt-5" type="submit">Submit</PlainBlackButton>
                    </form>
                </div>
            </div>
        </div>
    </GuestLayoutV2>
</template>
