<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    contact: Object,
    employment_type: String
});

const employment_records = () => {
    if (null != props.contact?.employment) {
        return Object.groupBy(props.contact?.employment, address => address.type)
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
    employment_type: employment_record()?.employment_type
})
</script>

<template>
    <section>
        <form
            @submit.prevent="form.patch(route('employment.update'))"
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
                    autofocus
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
                    autofocus
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
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.employment_type" />
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
