<script setup>
import TextInput from '@/Components/Inputs/TextInput.vue';
import SuccessToast from '@/Components/Notification/SuccessToast.vue';
import WarningToast from '@/Components/Notification/WarningToast.vue';
import SelectInput from '@/Components/Inputs/SelectComboboxes.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import PlainBlackButton from '@/Components/Buttons/PlainBlackButton.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    contact: Object,
    address_type: String,
    co_borrower_type: String,
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


const addresses = () => {
    if (null != co_borrower()?.addresses) {
        return Object.groupBy(co_borrower()?.addresses, address => address.type)
    }

    return null;
};

const address = () => {
    if (addresses())
        if (props.address_type in addresses()) {
            return addresses()[props.address_type][0]
        }

    return null;
};

const form = useForm({
    co_borrower_type: props.co_borrower_type,
    type: props.address_type,
    ownership: address()?.ownership,
    address1: address()?.address1,
    sublocality: address()?.sublocality,
    locality: address()?.locality,
    administrative_area: address()?.administrative_area,
    postal_code: address()?.postal_code,
    region: address()?.region,
    country: address()?.country ?? 'PH'
})

const updateCoBorrower = () => {
    form.patch(route('co_borrower-address.update'), {
        errorBag: 'updateCoBorrower',
        preserveScroll: true,
    });
};

const hasValidationError = ref(false);

function closeToastFunction(){
    hasValidationError.value = false;
}

const ownershipList = usePage().props.enums.ownerships.map(item => ({
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
        <h3 class="font-bold text-[#006FFD] mt-4">CO-BORROWER ADDRESS:</h3>
        <div class="grid grid-cols-12 gap-4">
                <div class="col-span-full lg:col-span-2">
                    <SelectInput
                        v-model="form.ownership"
                        label="Ownership"
                        :options="ownershipList"
                        :errorMessage="form.errors.ownership"
                        required
                    />
                </div>
                <div class="col-span-full lg:col-span-4">
                    <TextInput
                        v-model="form.address1"
                        label="Address"
                        placeholder="Enter Address"
                        :errorMessage="form.errors.address1"
                        required
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput
                        v-model="form.sublocality"
                        label="Barangay"
                        placeholder="Enter Address"
                        :errorMessage="form.errors.sublocality"
                        required
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput
                        v-model="form.locality"
                        label="City"
                        placeholder="Enter City"
                        :errorMessage="form.errors.locality"
                        required
                    />
                </div>
                <div class="col-span-full lg:col-span-4">
                    <TextInput
                        v-model="form.administrative_area"
                        label="Province"
                        placeholder="Enter Province"
                        :errorMessage="form.errors.administrative_area"
                        required
                    />
                </div>
                <div class="col-span-full lg:col-span-4">
                    <TextInput
                        v-model="form.region"
                        label="Region"
                        placeholder="Enter Region"
                        :errorMessage="form.errors.region"
                        required
                    />
                </div>
                <div class="col-span-full lg:col-span-2">
                    <TextInput
                        v-model="form.postal_code"
                        label="Zip Code"
                        placeholder="Enter Zip Code"
                        :errorMessage="form.errors.postal_code"
                        required
                    />
                </div>
                <div class="col-span-full lg:col-span-2">
                    <TextInput
                        v-model="form.country"
                        label="Country"
                        placeholder="Enter Country"
                        :errorMessage="form.errors.country"
                        required
                    />
                </div>
            </div>
            <div class="w-full lg:flex lg:flex-row lg:items-center lg:justify-center text-center pb-10">
                <div class="w-full lg:w-64">
                    <PlainBlackButton :disabled="form.processing" type="submit" customClass="w-auto">
                        <p class="text-white font-bold text-center">
                            Save Address
                        </p>
                    </PlainBlackButton>
                </div>
            </div>
        </form>
    </section>
</template>
