<script setup>
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue';
import TextInput from '@/Components/Inputs/TextInput.vue';
import DatePicker from '@/Components/Inputs/DatePicker.vue';
import SelectInput from '@/Components/Inputs/SelectComboboxes.vue';
import SuccessToast from '@/Components/Notification/SuccessToast.vue';
import WarningToast from '@/Components/Notification/WarningToast.vue';
import {useForm, usePage} from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    spouse: Object,
    employment_type: String
});

const employment_records = () => {
    if (null != props.spouse?.employment) {
        return Object.groupBy(props.spouse?.employment, employment => employment.type)
    }

    return null;
};

const employment_record = () => {
    if (employment_records())
        if (props.employment_type in employment_records()) {
            return employment_records()[props.employment_type][0]
        }

    return null;
};

const form = useForm({
    type: props.employment_type,
    employment_status: employment_record()?.employment_status,
    monthly_gross_income: employment_record()?.monthly_gross_income,
    current_position: employment_record()?.current_position,
    employment_type: employment_record()?.employment_type,
    employer_name: employment_record()?.employer?.name,
    employer_email: employment_record()?.employer?.email,
    employer_contact_no: employment_record()?.employer?.contact_no,
    employer_nationality: employment_record()?.employer?.nationality,
    employer_industry: employment_record()?.employer?.industry,

    employer_address_type: employment_record()?.employer?.address?.type ?? 'Work',
    employer_address_ownership: employment_record()?.employer?.address?.ownership,
    employer_address_address1: employment_record()?.employer?.address?.address1,
    employer_address_locality: employment_record()?.employer?.address?.locality,
    employer_address_administrative_area: employment_record()?.employer?.address?.administrative_area,
    employer_address_postal_code: employment_record()?.employer?.address?.postal_code,
    employer_address_region: employment_record()?.employer?.address?.region,
    employer_address_country: employment_record()?.employer?.address?.country ?? 'PH',

    tin: employment_record()?.id?.tin,
    pagibig: employment_record()?.id?.pagibig,
    sss: employment_record()?.id?.sss,
    gsis: employment_record()?.id?.gsis,
})

const employmentStatusList = usePage().props.enums.employment_statuses.map(item => ({
    id: item,
    name: item
}));

const employerNationalityList = usePage().props.enums.nationalities.map(item => ({
    id: item,
    name: item
}));
const industryList = usePage().props.enums.industries.map(item => ({
    id: item,
    name: item
}));
const employmentTypeList = usePage().props.enums.employment_types.map(item => ({
    id: item,
    name: item
}));

const updateSpouseEmployment = () => {
    form.patch(route('spouse-employment.update'), {
        errorBag: 'updateSpouseEmployment',
        preserveScroll: true,
    });
};

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
                message="Successfully Saved Spouse Employment Data"

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
            @submit.prevent="updateSpouseEmployment"
            class="mt-6 space-y-6"
        >
            <h3 class="font-bold text-[#CC035C] mt-4 uppercase">Spouse Employment Information:</h3>

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-full lg:col-span-2">
                    <TextInput 
                        v-model="form.monthly_gross_income"
                        label="Gross Monthly Income"
                        placeholder="Enter Monthly Gross Income"
                        type="number"
                        :errorMessage="form.errors.monthly_gross_income"
                        required
                    />
                </div>
                <div class="col-span-full lg:col-span-2">
                    <SelectInput 
                        v-model="form.employment_status"
                        label="Employment Status"
                        :options="employmentStatusList"
                        :errorMessage="form.errors.employment_status"
                        required
                    />
                </div>
                <div class="col-span-full lg:col-span-4">
                    <TextInput 
                        v-model="form.employer_name"
                        label="Employer Name"
                        placeholder="Enter Employer Name"
                        :errorMessage="form.errors.employer_name"
                    />
                </div>
                <div class="col-span-full lg:col-span-4">
                    <TextInput 
                        v-model="form.current_position"
                        label="Employment Position"
                        placeholder="Enter Employment Position"
                        :errorMessage="form.errors.current_position"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <SelectInput 
                        v-model="form.employment_type"
                        label="Employment Type"
                        :options="employmentTypeList"
                        :errorMessage="form.errors.employment_type"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.employer_email"
                        label="Email"
                        placeholder="Enter Employer Email"
                        :errorMessage="form.errors.employer_email"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.employer_contact_no"
                        label="Contact No."
                        type="number"
                        max="11"
                        placeholder="09*********"
                        :errorMessage="form.errors.employer_contact_no"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <SelectInput 
                        v-model="form.employer_nationality"
                        label="Nationality"
                        :options="employerNationalityList"
                        :errorMessage="form.errors.employer_nationality"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <SelectInput 
                        v-model="form.employer_industry"
                        label="Industies"
                        :options="industryList"
                        :errorMessage="form.errors.employer_industry"
                    />
                </div>
                <div class="col-span-full lg:col-span-2">
                    <TextInput 
                        v-model="form.employer_address_type"
                        label="Address Type"
                        placeholder="Enter Employer Address Type"
                        :errorMessage="form.errors.employer_address_type"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.employer_address_ownership"
                        label="Address Ownership"
                        placeholder="Enter Employer Address Ownership"
                        :errorMessage="form.errors.employer_address_ownership"
                    />
                </div>
                <div class="col-span-full lg:col-span-4">
                    <TextInput 
                        v-model="form.employer_address_address1"
                        label="Address"
                        placeholder="Enter Employer Address"
                        :errorMessage="form.errors.employer_address_address1"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.employer_address_locality"
                        label="City"
                        placeholder="Enter Employer City"
                        :errorMessage="form.errors.employer_address_locality"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.employer_address_administrative_area"
                        label="Province"
                        placeholder="Enter Employer Province"
                        :errorMessage="form.errors.employer_address_administrative_area"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.employer_address_postal_code"
                        label="ZIP Code"
                        placeholder="Enter Employer ZIP Code"
                        :errorMessage="form.errors.employer_address_postal_code"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.employer_address_region"
                        label="Region"
                        placeholder="Enter Employer Region"
                        :errorMessage="form.errors.employer_address_region"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.employer_address_country"
                        label="Country"
                        placeholder="Enter Employer Country"
                        :errorMessage="form.errors.employer_address_country"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.tin"
                        label="TIN"
                        placeholder="Enter TIN"
                        :errorMessage="form.errors.tin"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.pagibig"
                        label="Pag-IBIG"
                        placeholder="Enter Pag-IBIG Number"
                        :errorMessage="form.errors.pagibig"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.sss"
                        label="SSS"
                        placeholder="Enter SSS Number"
                        :errorMessage="form.errors.sss"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="formgsis"
                        label="GSIS"
                        placeholder="Enter GSIS Number"
                        :errorMessage="form.errorsgsis"
                    />
                </div>
            </div>
            <div class="w-full text-center pb-10">
                <PrimaryButton :disabled="form.processing" type="submit" customClass="w-auto">
                    <div class="flex flex-row items-center gap-2">
                        <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7.414A2 2 0 0 0 20.414 6L18 3.586A2 2 0 0 0 16.586 3H5Zm3 11a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v6H8v-6Zm1-7V5h6v2a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                            <path fill-rule="evenodd" d="M14 17h-4v-2h4v2Z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-white font-bold text-center">
                            Save Employment Information
                        </p>
                    </div>
                </PrimaryButton>
            </div>
        </form>
    </section>
</template>
