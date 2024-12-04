<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import {useForm, usePage} from '@inertiajs/vue3';

const props = defineProps({
    contact: Object,
    co_borrower_type: String,
    employment_type: String,
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

const co_borrower_employment_records = () => {
    if (null != co_borrower()?.employment) {
        return Object.groupBy(co_borrower().employment, employment => employment.type)
    }

    return null;
};

const employment_record = () => {
    if (co_borrower_employment_records())
        if (props.employment_type in co_borrower_employment_records()) {
            return co_borrower_employment_records()[props.employment_type][0]
        }

    return null;
};

const form = useForm({
    co_borrower_type: props.co_borrower_type,
    co_borrower_employment: props.employment_type,
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
    employer_address_ownership: employment_record()?.employer?.address?.ownership ?? 'Unknown',
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

const updateCoBorrowerEmployment = () => {
    form.patch(route('co_borrower-employment.update'), {
        errorBag: 'updateCoBorrowerEmployment',
        preserveScroll: true,
    });
};

</script>

<template>
    <section>
        <form
            @submit.prevent="updateCoBorrowerEmployment"
            class="mt-6 space-y-6"
        >
            <h3 class="font-sans text-l text-gray-800 dark:text-gray-300 leading-tight">
                {{ props.employment_type }} Employment
            </h3>

            <div>
                <InputLabel for="employment_status" value="Employment Status" />

                <TextInput
                    id="employment_status"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.employment_status"
                    required
                    autofocus
                />

                <div class="text-xs text-gray-600 dark:text-gray-400">{{ usePage().props.enums.employment_statuses.join(', ') }}</div>
                <InputError class="mt-2" :message="form.errors.employment_status" />
            </div>

            <div>
                <InputLabel for="monthly_gross_income" value="Gross Monthly Income" />

                <TextInput
                    id="monthly_gross_income"
                    type="number"
                    class="mt-1 block w-full"
                    v-model="form.monthly_gross_income"
                    required
                />

                <InputError class="mt-2" :message="form.errors.monthly_gross_income" />
            </div>

            <div>
                <InputLabel for="current_position" value="Employment Position" />

                <TextInput
                    id="current_position"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.current_position"
                    required
                />

                <InputError class="mt-2" :message="form.errors.current_position" />
            </div>

            <div>
                <InputLabel for="employment_type" value="Employment Type" />

                <TextInput
                    id="employment_type"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.employment_type"
                    required
                />

                <div class="text-xs text-gray-600 dark:text-gray-400">{{ usePage().props.enums.employment_types.join(', ') }}</div>
                <InputError class="mt-2" :message="form.errors.employment_type" />
            </div>

            <div>
                <InputLabel for="employer_name" value="Employer Name" />

                <TextInput
                    id="employer_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.employer_name"
                />

                <InputError class="mt-2" :message="form.errors.employer_name" />
            </div>

            <div>
                <InputLabel for="employer_email" value="Employer Email" />

                <TextInput
                    id="employer_email"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.employer_email"
                />

                <InputError class="mt-2" :message="form.errors.employer_email" />
            </div>

            <div>
                <InputLabel for="employer_contact_no" value="Employer Contact Number" />

                <TextInput
                    id="employer_contact_no"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.employer_contact_no"
                />

                <InputError class="mt-2" :message="form.errors.employer_contact_no" />
            </div>

            <div>
                <InputLabel for="employer_nationality" value="Employer Nationality" />

                <TextInput
                    id="employer_nationality"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.employer_nationality"
                />

                <div class="text-xs text-gray-600 dark:text-gray-400">{{ usePage().props.enums.nationalities.join(', ') }}</div>
                <InputError class="mt-2" :message="form.errors.employer_nationality" />
            </div>

            <div>
                <InputLabel for="employer_industry" value="Employer Industry" />

                <TextInput
                    id="employer_industry"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.employer_industry"
                />

                <div class="text-xs text-gray-600 dark:text-gray-400">{{ usePage().props.enums.industries.join(', ') }}</div>
                <InputError class="mt-2" :message="form.errors.employer_industry" />
            </div>

            <!--            address-->

            <div>
                <InputLabel for="employer_address_type" value="Employer Address Type" />

                <TextInput
                    id="employer_address_type"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.employer_address_type"
                />

                <InputError class="mt-2" :message="form.errors.employer_address_type" />
            </div>

            <div>
                <InputLabel for="employer_address_ownership" value="Employer Address Ownership" />

                <TextInput
                    id="employer_address_ownership"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.employer_address_ownership"
                />

                <InputError class="mt-2" :message="form.errors.employer_address_ownership" />
            </div>

            <div>
                <InputLabel for="employer_address_address1" value="Employer Address Address 1" />

                <TextInput
                    id="employer_address_address1"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.employer_address_address1"
                />

                <InputError class="mt-2" :message="form.errors.employer_address_address1" />
            </div>

            <div>
                <InputLabel for="employer_address_locality" value="Employer Address City" />

                <TextInput
                    id="employer_address_locality"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.employer_address_locality"
                />

                <InputError class="mt-2" :message="form.errors.employer_address_locality" />
            </div>

            <div>
                <InputLabel for="employer_address_administrative_area" value="Employer Address Province" />

                <TextInput
                    id="employer_address_administrative_area"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.employer_address_administrative_area"
                />

                <InputError class="mt-2" :message="form.errors.employer_address_administrative_area" />
            </div>

            <div>
                <InputLabel for="employer_address_postal_code" value="Employer Address ZIP Code" />

                <TextInput
                    id="employer_address_postal_code"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.employer_address_postal_code"
                />

                <InputError class="mt-2" :message="form.errors.employer_address_postal_code" />
            </div>

            <div>
                <InputLabel for="employer_address_region" value="Employer Address Region" />

                <TextInput
                    id="employer_address_region"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.employer_address_region"
                />

                <InputError class="mt-2" :message="form.errors.employer_address_region" />
            </div>

            <div>
                <InputLabel for="employer_address_country" value="Employer Address Country" />

                <TextInput
                    id="employer_address_country"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.employer_address_country"
                    required
                />

                <InputError class="mt-2" :message="form.errors.employer_address_country" />
            </div>

            <!--            id-->

            <div>
                <InputLabel for="tin" value="TIN" />

                <TextInput
                    id="tin"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.tin"
                    required
                />

                <InputError class="mt-2" :message="form.errors.tin" />
            </div>

            <div>
                <InputLabel for="pagibig" value="Pag-IBIG" />

                <TextInput
                    id="pagibig"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.pagibig"
                />

                <InputError class="mt-2" :message="form.errors.pagibig" />
            </div>

            <div>
                <InputLabel for="sss" value="SSS" />

                <TextInput
                    id="sss"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.sss"
                />

                <InputError class="mt-2" :message="form.errors.sss" />
            </div>

            <div>
                <InputLabel for="gsis" value="GSIS" />

                <TextInput
                    id="gsis"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.gsis"
                />

                <InputError class="mt-2" :message="form.errors.gsis" />
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
