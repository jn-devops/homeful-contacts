<script setup>
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import GuestLayoutV2 from '@/Layouts/GuestLayoutV2.vue';
import TextInput from '@/Components/Inputs/TextInput.vue';
import PasswordInput from '@/Components/Inputs/PasswordInput.vue';
import PlainBlackButton from '@/Components/Buttons/PlainBlackButton.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayoutV2>
        <div class="flex flex-col">
            <div class="flex flex-col items-start w-full">
                <div class="relative w-full">
                    <div class="w-full h-[400px]">
                        <img 
                            :src="usePage().props.data.appURL + '/images/ModelUnitWalkthrough.gif'" alt="IMG"
                            class="w-full h-full object-cover"
                        >
                    </div>
                    <div class="z-20 absolute top-0 left-0 w-full h-[100px] bg-gradient-to-t from-transparent to-black">
                    </div>
                </div>
                <div class="p-10 w-full">
                    <form @submit.prevent="submit">
                        <h2 class="text-2xl font-extrabold">Buyer Log In</h2>
                        <div class="w-full mt-5">
                            <TextInput
                                placeholder="Email Address"
                                no-border-radius
                                v-model="form.email"
                                :error-message="form.errors.email"
                            />
                        </div>
                        <div class="w-full mt-5">
                            <PasswordInput
                                placeholder="Password"
                                no-border-radius
                                v-model="form.password"
                                :error-message="form.errors.password"
                            />
                        </div>
                        <div class="">
                            <span class="text-blue-600 text-xs font-semibold cursor-pointer" @click="router.get(route('password.request'))">Forgot Password?</span>
                        </div>
                        <div class="mt-5">
                            <PlainBlackButton type="submit">
                                <span class="font-semibold">Login</span>
                            </PlainBlackButton>
                        </div>
                    </form>
                    <div class="text-gray-600 text-center text-sm font-semibold py-3">
                        Not a member? <Link :href="route('register')"><span class="text-blue-600">Register Now</span></Link>
                    </div>
                    <hr class="my-3" />
                    <div class="flex flex-col justify-center items-center">
                        <p class="text-gray-600 text-center text-sm">Or continue with</p>
                        <div class="flex items-center gap-4 mt-3">
                            <!-- Google -->
                            <svg class="cursor-pointer" width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="0.5" width="40" height="40" rx="20" fill="#ED3241"/>
                                <mask id="mask0_79_1480" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="15" y="14" width="11" height="12">
                                    <path d="M25.7835 18.9686C25.8522 19.3368 25.8897 19.7218 25.8897 20.1235C25.8897 23.2657 23.7867 25.5 20.6104 25.5C19.888 25.5002 19.1727 25.3581 18.5053 25.0818C17.8379 24.8055 17.2315 24.4003 16.7208 23.8896C16.21 23.3788 15.8049 22.7724 15.5286 22.105C15.2522 21.4376 15.1101 20.7223 15.1104 20C15.1101 19.2777 15.2522 18.5624 15.5286 17.895C15.8049 17.2276 16.21 16.6212 16.7208 16.1104C17.2315 15.5997 17.8379 15.1945 18.5053 14.9182C19.1727 14.6419 19.888 14.4998 20.6104 14.5C22.0954 14.5 23.3363 15.0464 24.2884 15.9337L22.7379 17.4841V17.4802C22.1608 16.9304 21.4283 16.6483 20.6104 16.6483C18.7956 16.6483 17.3206 18.1814 17.3206 19.9966C17.3206 21.8114 18.7956 23.3478 20.6104 23.3478C22.2569 23.3478 23.3777 22.4064 23.6079 21.1135H20.6104V18.9686H25.7841H25.7835Z" fill="white"/>
                                </mask>
                                <g mask="url(#mask0_79_1480)">
                                    <rect x="14.501" y="14" width="12" height="12" fill="white"/>
                                </g>
                            </svg>
                            <!-- Apple -->
                            <svg class="cursor-pointer" width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="0.5" width="40" height="40" rx="20" fill="#1F2024"/>
                                <mask id="mask0_79_1481" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="15" y="14" width="11" height="12">
                                    <path d="M22.8864 14.006C22.8609 13.9775 21.9422 14.0173 21.1427 14.885C20.3432 15.752 20.4662 16.7465 20.4842 16.772C20.5022 16.7975 21.6242 16.8373 22.3404 15.8285C23.0567 14.8198 22.9119 14.0353 22.8864 14.006ZM25.3719 22.8058C25.3359 22.7338 23.6282 21.8803 23.7872 20.2393C23.9462 18.5975 25.0434 18.1475 25.0607 18.0988C25.0779 18.05 24.6129 17.5063 24.1202 17.231C23.7584 17.0369 23.358 16.9258 22.9479 16.9055C22.8669 16.9033 22.5857 16.8343 22.0074 16.9925C21.6264 17.0968 20.7677 17.4343 20.5314 17.4478C20.2944 17.4613 19.5894 17.0563 18.8312 16.949C18.3459 16.8553 17.8314 17.0473 17.4632 17.195C17.0957 17.342 16.3967 17.7605 15.9077 18.8728C15.4187 19.9843 15.6744 21.7453 15.8574 22.2928C16.0404 22.8395 16.3262 23.7358 16.8122 24.3898C17.2442 25.1278 17.8172 25.64 18.0564 25.814C18.2957 25.988 18.9707 26.1035 19.4387 25.8643C19.8152 25.6333 20.4947 25.5005 20.7632 25.5103C21.0309 25.52 21.5589 25.6258 22.0997 25.9145C22.5279 26.0623 22.9329 26.0008 23.3387 25.8358C23.7444 25.67 24.3317 25.0415 25.0172 23.7673C25.2774 23.1748 25.3959 22.8545 25.3719 22.8058Z" fill="white"/>
                                    <path d="M22.8864 14.006C22.8609 13.9775 21.9422 14.0173 21.1427 14.885C20.3432 15.752 20.4662 16.7465 20.4842 16.772C20.5022 16.7975 21.6242 16.8373 22.3404 15.8285C23.0567 14.8198 22.9119 14.0353 22.8864 14.006ZM25.3719 22.8058C25.3359 22.7338 23.6282 21.8803 23.7872 20.2393C23.9462 18.5975 25.0434 18.1475 25.0607 18.0988C25.0779 18.05 24.6129 17.5063 24.1202 17.231C23.7584 17.0369 23.358 16.9258 22.9479 16.9055C22.8669 16.9033 22.5857 16.8343 22.0074 16.9925C21.6264 17.0968 20.7677 17.4343 20.5314 17.4478C20.2944 17.4613 19.5894 17.0563 18.8312 16.949C18.3459 16.8553 17.8314 17.0473 17.4632 17.195C17.0957 17.342 16.3967 17.7605 15.9077 18.8728C15.4187 19.9843 15.6744 21.7453 15.8574 22.2928C16.0404 22.8395 16.3262 23.7358 16.8122 24.3898C17.2442 25.1278 17.8172 25.64 18.0564 25.814C18.2957 25.988 18.9707 26.1035 19.4387 25.8643C19.8152 25.6333 20.4947 25.5005 20.7632 25.5103C21.0309 25.52 21.5589 25.6258 22.0997 25.9145C22.5279 26.0623 22.9329 26.0008 23.3387 25.8358C23.7444 25.67 24.3317 25.0415 25.0172 23.7673C25.2774 23.1748 25.3959 22.8545 25.3719 22.8058Z" fill="white"/>
                                </mask>
                                <g mask="url(#mask0_79_1481)">
                                    <rect x="14.501" y="14" width="12" height="12" fill="white"/>
                                </g>
                            </svg>
                            <!-- Facebok -->
                            <svg class="cursor-pointer" width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="0.5" width="40" height="40" rx="20" fill="#006FFD"/>
                                <mask id="mask0_79_1482" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="17" y="14" width="7" height="12">
                                    <path d="M19.0729 26V20.3693H17.584V18.3419H19.0729V16.6103C19.0729 15.2496 19.9524 14 21.9789 14C22.7994 14 23.4061 14.0787 23.4061 14.0787L23.3583 15.9719C23.3583 15.9719 22.7396 15.9658 22.0643 15.9658C21.3335 15.9658 21.2165 16.3026 21.2165 16.8616V18.3419H23.4164L23.3207 20.3693H21.2165V26H19.0729Z" fill="white"/>
                                </mask>
                                <g mask="url(#mask0_79_1482)">
                                    <rect x="14.501" y="14" width="12" height="12" fill="white"/>
                                </g>
                            </svg>
    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayoutV2>
</template>
