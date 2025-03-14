<script setup>
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue';
import TextInput from '@/Components/Inputs/TextInput.vue';
import DatePicker from '@/Components/Inputs/DatePicker.vue';
import SelectInput from '@/Components/Inputs/SelectComboboxes.vue';
import SuccessToast from '@/Components/Notification/SuccessToast.vue';
import WarningToast from '@/Components/Notification/WarningToast.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import PlainBlackButton from '@/Components/Buttons/PlainBlackButton.vue';

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
    date_of_birth: props.contact?.date_of_birth ?? '',
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

const civilStatusList = usePage().props.enums.civil_statuses.map(item => ({
    id: item,
    name: item
}));

const genderList = usePage().props.enums.sexes.map(item => ({
    id: item,
    name: item
}));
const nationalityList = usePage().props.enums.nationalities.map(item => ({
    id: item,
    name: item
}));

const hasValidationError = ref(false);

function closeToastFunction(){
    hasValidationError.value = false;
}

watch(form, (newValue, oldValue) => {
    hasValidationError.value = (form.hasErrors) ? true : false;
});

</script>

<template>
    <section>
        <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
        >
            <SuccessToast 
                v-if="form.recentlySuccessful"
                message="Successfully Saved Personal Data"
            />
        </Transition>
        <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
        >
            <WarningToast 
                v-if="hasValidationError"
                @close-toast="closeToastFunction"
                message="There are validation errors. Kindly double check the form."
            />
        </Transition>
        <form
            @submit.prevent="updatePersonalInformation"
            class="mt-6 space-y-6"
        >
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.first_name"
                        label="First Name"
                        placeholder="Enter First Name"
                        :errorMessage="form.errors.first_name"
                        required
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.middle_name"
                        label="Middle Name"
                        placeholder="Enter Middle Name"
                        :errorMessage="form.errors.middle_name"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.last_name"
                        label="Last Name"
                        placeholder="Enter Last Name"
                        :errorMessage="form.errors.last_name"
                        required
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.name_suffix"
                        label="Suffix"
                        placeholder="Enter Name Suffix"
                        :errorMessage="form.errors.name_suffix"
                    />
                </div>
                <div class="col-span-full lg:col-span-4">
                    <TextInput 
                        v-model="form.mothers_maiden_name"
                        label="Mother's Maiden Name"
                        placeholder="Enter Mother's Maiden Name"
                        :errorMessage="form.errors.mothers_maiden_name"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <SelectInput 
                        v-model="form.civil_status"
                        label="Civil Status"
                        :options="civilStatusList"
                        :errorMessage="form.errors.civil_status"
                    />
                </div>
                <div class="col-span-full lg:col-span-2">
                    <SelectInput 
                        v-model="form.sex"
                        label="Gender"
                        :options="genderList"
                        :errorMessage="form.errors.sex"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <SelectInput 
                        v-model="form.nationality"
                        label="Nationality"
                        :options="nationalityList"
                        :errorMessage="form.errors.nationality"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <DatePicker 
                        v-model="form.date_of_birth"
                        label="Date of Birth"
                        :errorMessage="form.errors.date_of_birth"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.email"
                        label="Primary Email Address"
                        placeholder="Enter Primary Email Addresss"
                        :errorMessage="form.errors.email"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.mobile"
                        label="Mobile Number"
                        :max="11"
                        type="number"
                        placeholder="09********"
                        :errorMessage="form.errors.mobile"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.other_mobile"
                        label="Other Mobile"
                        placeholder="Enter Other Mobile"
                        :errorMessage="form.errors.other_mobile"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.help_number"
                        label="Help Number"
                        placeholder="Enter Help Number"
                        :errorMessage="form.errors.help_number"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.landline"
                        label="Landline "
                        placeholder="Enter Landline "
                        :errorMessage="form.errors.landline"
                    />
                </div>
            </div>
            <div class="w-full lg:flex lg:flex-row lg:items-center lg:justify-center text-center pb-10">
                <div class="w-full lg:w-64">
                    <PlainBlackButton :disabled="form.processing" type="submit" customClass="w-auto">
                        <p class="text-white font-bold text-center">
                            Save Personal Data
                        </p>
                    </PlainBlackButton>
                </div>
            </div>
        </form>
    </section>
</template>