<script setup>
import TextInput from '@/Components/Inputs/TextInput.vue';
import SuccessToast from '@/Components/Notification/SuccessToast.vue';
import WarningToast from '@/Components/Notification/WarningToast.vue';
import SelectInput from '@/Components/Inputs/SelectComboboxes.vue';
import DatePicker from '@/Components/Inputs/DatePicker.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import PlainBlackButton from '@/Components/Buttons/PlainBlackButton.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    contact: Object,
    co_borrower_type: String,
});

const checkFormDirty = () => {
    return form.isDirty
};
const checkFormError = () => {
    return form.errors
};
const saveThisForm = () => {
    updateCoBorrower()
};

defineExpose({
    checkFormDirty,
    saveThisForm,
    checkFormError
});

const co_borrowers = () => {
    if (null != props.contact?.co_borrowers) {
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

const spouse = co_borrower()?.spouse

const form = useForm({
    co_borrower_type: props.co_borrower_type,
    first_name: spouse?.first_name,
    middle_name: spouse?.middle_name,
    last_name: spouse?.last_name,
    name_suffix: spouse?.name_suffix,
    mothers_maiden_name: spouse?.mothers_maiden_name,
    civil_status: "Married",
    sex: spouse?.sex ?? ('Male' === co_borrower()?.sex ? 'Female' : 'Male'),
    nationality: spouse?.nationality ?? co_borrower()?.nationality,
    date_of_birth: spouse?.date_of_birth ?? '',
    email: spouse?.email,
    mobile: spouse?.mobile,
    help_number: spouse?.help_number,
    other_mobile: spouse?.other_mobile,
    landline: spouse?.landline,
})

const updateCoBorrower = async () => {
    await form.patch(route('co_borrower-spouse.update'), {
        errorBag: 'updateCoBorrower',
        preserveScroll: true,
    });
};

const hasValidationError = ref(false);

function closeToastFunction(){
    hasValidationError.value = false;
}

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
const nameSuffixList = usePage().props.enums.name_suffixes.map(item => ({
    id: item,
    name: item
}));

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
            @submit.prevent="updateCoBorrower"
            class="mt-6 space-y-6"
        >
            <h3 class="font-bold text-[#006FFD] mt-4">CO-BORROWER SPOUSE:</h3>
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
                    <SelectInput 
                        v-model="form.name_suffix"
                        label="Suffix"
                        :options="nameSuffixList"
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
                            Save Spouse
                        </p>
                    </PlainBlackButton>
                </div>
            </div>
        </form>
    </section>
</template>
