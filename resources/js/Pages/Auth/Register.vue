<script setup>
import GuestLayoutV2 from '@/Layouts/GuestLayoutV2.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import GradientStyleInput from '@/Components/Inputs/GradientStyleInput.vue';
import GradientStylePhoneNumber from '@/Components/Inputs/GradientStylePhoneNumber.vue';
import PlainBlackButton from '@/Components/Buttons/PlainBlackButton.vue';
import SecondaryPlainBlack from '@/Components/Buttons/SecondaryPlainBlack.vue';
import { computed, ref } from "vue";
import GradientStyleDateInput from '@/Components/Inputs/GradientStyleDateInput.vue';
import Agreements from '@/Components/Agreements.vue';
import TermAndCondition from '@/Components/TermAndCondition.vue';


function capitalizeWords(value) {
  if (!value) return '';
  return value
    .replace(/\s+/g, ' ') // normalize spaces
    .split(' ')
    .map(word =>
      word ? word[0].toUpperCase() + word.slice(1).toLowerCase() : ''
    )
    .join(' ');
}

const props = defineProps({
    callback: String,
    showExtra: {
        type: Boolean,
        default: false
    },
    autoPassword: {
        type: String,
        default: ''
    },
    name: {
        type: String,
        default: ''
    },
    email: {
        type: String,
        default: ''
    },
    mobile: {
        type: String,
        default: ''
    },
});

const showTC = ref(false)
const showCoBorrowerGMI = ref(false)
const showAgreementPage = ref(false)

const toggleTC = () => {
    showTC.value = !showTC.value
}

const togglePP = () => {
    showAgreementPage.value = !showAgreementPage.value
}

const form = useForm({
    name: props.name,
    email: props.email,
    mobile: props.mobile,
    password: props.autoPassword,
    password_confirmation: props.autoPassword,
    date_of_birth: '',
    monthly_gross_income: null,
    cobo_monthly_gross_income: null,
    cobo_date_of_birth: '',
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

const disclaimerChecked = ref(false);

const showPassword = computed(() => props.autoPassword === '')

const viewAgreements = () => {
    disclaimerChecked.value = false
    togglePP()
}

</script>

<template>
    <GuestLayoutV2>
        <div class="flex flex-col mt-10 px-8">
            <div class="flex flex-row items-start w-full">
                <div class="basis-1/3 ">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 19-7-7 7-7"/>
                    </svg>
                </div>
                <div class="basis-1/3 text-center font-bold">
                    Sign Up
                </div>
                <div class="basis-1/3">
                </div>
            </div>
            <div class="w-full mt-10">
                <p class="text-2xl font-extrabold">
                    Create an Account to <br> Get Started
                </p>
            </div>
            <form @submit.prevent="submit">
                <div class="mt-10">
                    <GradientStyleInput
                        label="Full Name"
                        placeholder="Enter Full Name"
                        required
                        v-model="form.name"
                        @update:modelValue="val => form.name = capitalizeWords(val)"
                        :error="form.errors.name"    
                    />
                </div>
                <div class="mt-5">
                    <GradientStylePhoneNumber
                        label="Mobile Number"
                        placeholder="+63 XXX XXXX XXX"
                        required
                        v-model="form.mobile"
                        :error="form.errors.mobile"
                    />
                </div>
                <div class="mt-5">
                    <GradientStyleInput
                        label="Email Address"
                        placeholder="Enter Email"
                        required
                        type="email"
                        v-model="form.email"
                        :error="form.errors.email"
                    />
                </div>
                <div v-if="showPassword">
                    <div class="mt-5">
                        <GradientStyleInput
                            label="Password"
                            type="password"
                            placeholder="Enter Password"
                            v-model="form.password"
                            :error="form.errors.password"
                        />
                    </div>
                    <div class="mt-5">
                        <GradientStyleInput
                            label="Confirm Password"
                            type="password"
                            placeholder="Confirm Password"
                            v-model="form.password_confirmation"
                            :error="form.errors.password_confirmation"
                        />
                    </div>
                </div>
                <div v-if="showExtra">
                    <div class="mt-5">
                        <GradientStyleDateInput
                            label="Birthdate"
                            required
                            v-model="form.date_of_birth"
                            :error="form.errors.date_of_birth"
                        />
                    </div>
                    <div class="mt-5">
                        <GradientStyleInput
                            label="Gross Monthly Income"
                            type="number"
                            placeholder="Enter Gross Monthly Income"
                            required
                            v-model="form.monthly_gross_income"
                            :error="form.errors.monthly_gross_income"
                        />
                    </div>
                    <!-- <div class="mt-1">
                        <div class="flex flex-row items-center gap-2">
                            <input type="checkbox" v-model="showCoBorrowerGMI" id="add_coborrower" class="rounded text-black border-black focus:ring-0">
                            <label for="add_coborrower" class="text-sm"> Add Co-borrower</label>
                        </div>
                    </div>
                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <div v-if="showCoBorrowerGMI">
                            <div class="mt-5" >
                                <GradientStyleInput
                                    label="Co-Borrower's GMI"
                                    type="number"
                                    placeholder="Enter Co-borrower's Gross Monthly Income"
                                    v-model="form.cobo_monthly_gross_income"
                                    :error="form.errors.cobo_monthly_gross_income"
                                />
                                
                            </div>
                            <div class="mt-5">
                                <GradientStyleDateInput
                                    label="Coborrower's Birthdate"
                                    required
                                    v-model="form.cobo_date_of_birth"
                                    :error="form.errors.cobo_date_of_birth"
                                />
                            </div>
                        </div>
                    </Transition> -->
                </div>
                <div class="mt-10 px-2">
                    <div class="flex items-center mb-4 gap-2" @click="viewAgreements">
                        <!-- <input type="checkbox"  @click="viewAgreements" v-model="disclaimerChecked" id="default-checkbox" class="w-5 h-5 text-[#F7C947] rounded-sm focus:ring-[#E94572]"> -->
                         <div v-if="disclaimerChecked" class="w-6 h-5 bg-[#F7C947] rounded border-2 border-black">
                            <svg class="w-full h-full text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 11.917 9.724 16.5 19 7.5"/>
                            </svg>
                         </div>
                         <div v-else class="w-[25px] h-[20px] rounded border-2 border-black">
                         </div>
                        <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            I've read and agree with the <b class="underline cursor-pointer">Terms of Use</b> and the <b class="underline cursor-pointer">Privacy Policy</b>.
                        </label>
                    </div>
                </div>
                <div class="">
                    <PlainBlackButton type="submit" :disabled="!disclaimerChecked">
                        Register
                    </PlainBlackButton>
                </div>
                <div class="mt-3">
                    <Link :href="route('login')" >
                        <SecondaryPlainBlack>
                            Login
                        </SecondaryPlainBlack>
                    </Link>
                </div>
                <div class="mt-8">
                    <hr class="border-1 border-gray-300" />
                </div>
                <div class="flex flex-col items-center mb-10">
                    <div class="mt-4 text-gray-500">or continue with</div>
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
            </form>
            <Agreements 
                v-if="showAgreementPage" 
                @close="togglePP"
                v-model:disclaimerChecked="disclaimerChecked"
            />
        </div>
    </GuestLayoutV2>
</template>
