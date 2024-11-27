<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    contact: Object,
    address_type: String
});

const addresses = () => {
    if (null != props.contact?.addresses) {
        return Object.groupBy(props.contact?.addresses, address => address.type)
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
    type: props.address_type,
    ownership: address()?.ownership,
    address1: address()?.address1,
    locality: address()?.locality,
    administrative_area: address()?.administrative_area,
    postal_code: address()?.postal_code,
    region: address()?.region,
    country: address()?.country ?? 'PH'
})
</script>

<template>
    <section>
        <form
            @submit.prevent="form.patch(route('address.update'))"
            class="mt-6 space-y-6"
        >
            <h3 class="font-sans text-l text-gray-800 dark:text-gray-300 leading-tight">
                {{ props.address_type }} Address
            </h3>

            <div>
                <InputLabel for="ownership" value="Ownership" />

                <TextInput
                    id="ownership"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.ownership"
                    required
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.ownership" />
            </div>

            <div>
                <InputLabel for="address1" value="Address" />

                <TextInput
                    id="address1"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.address1"
                    required
                />

                <InputError class="mt-2" :message="form.errors.address1" />
            </div>

            <div>
                <InputLabel for="locality" value="City" />

                <TextInput
                    id="locality"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.locality"
                    required
                />

                <InputError class="mt-2" :message="form.errors.locality" />
            </div>

            <div>
                <InputLabel for="administrative_area" value="Province" />

                <TextInput
                    id="administrative_area"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.administrative_area"
                    required
                />

                <InputError class="mt-2" :message="form.errors.administrative_area" />
            </div>

            <div>
                <InputLabel for="postal_code" value="ZIP Code" />

                <TextInput
                    id="postal_code"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.postal_code"
                    required
                />

                <InputError class="mt-2" :message="form.errors.postal_code" />
            </div>

            <div>
                <InputLabel for="region" value="Region" />

                <TextInput
                    id="region"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.region"
                    required
                />

                <InputError class="mt-2" :message="form.errors.region" />
            </div>

            <div>
                <InputLabel for="country" value="Country" />

                <TextInput
                    id="country"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.country"
                    required
                />

                <InputError class="mt-2" :message="form.errors.country" />
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
