<script setup>
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue';
import TextInput from '@/Components/Inputs/TextInput.vue';
import DatePicker from '@/Components/Inputs/DatePicker.vue';
import SelectInput from '@/Components/Inputs/SelectComboboxes.vue';
import SuccessToast from '@/Components/Notification/SuccessToast.vue';
import WarningToast from '@/Components/Notification/WarningToast.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';
import PlainBlackButton from '@/Components/Buttons/PlainBlackButton.vue';
import { Vue3Lottie } from 'vue3-lottie';

const props = defineProps({
    aif: Object,
    api_token: String,
    api_url: String,
});

const checkFormDirty = () => {
    return form.isDirty
};
const saveThisForm = () => {
    updateAIFInformation()
};

defineExpose({
    checkFormDirty,
    saveThisForm
});

const form = useForm({
    first_name: props.aif?.first_name,
    middle_name: props.aif?.middle_name,
    last_name: props.aif?.last_name,
    name_suffix: props.aif?.name_suffix,
    mothers_maiden_name: props.aif?.mothers_maiden_name,
    civil_status: props.aif?.civil_status,
    sex: props.aif?.sex,
    nationality: props.aif?.nationality,
    date_of_birth: props.aif?.date_of_birth ?? '',
    email: props.aif?.email,
    mobile: props.aif?.mobile,
    other_mobile: props.aif?.other_mobile,
    landline: props.aif?.landline,
    tin: props.aif?.tin,
    relationship_to_buyer: props.aif?.relationship_to_buyer,
})

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

const updateAIFInformation = async () => {
    await form.patch(route('aif.update'), {
        errorBag: 'updateAIFInformation',
        preserveScroll: true,
    });
};

const api_relation = ref([])
const relation_loading = ref(true)
const formatted_relation = ref([])
const getRelation = async () => {
    try {
        
        const response = await axios.get(props.api_url+'api/admin/maintenance/relationships?per_page=1000', {
                headers: {
                    Authorization: `Bearer ${props.api_token}`,
                },
            });
        api_relation.value = response.data
        
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}
const api_name_suffix = ref([])
const name_suffix_loading = ref(true)
const formatted_name_suffix = ref([])
const getNameSuffix = async () => {
    try {
        
        const response = await axios.get(props.api_url+'api/admin/name-suffixes?per_page=300', {
                headers: {
                    Authorization: `Bearer ${props.api_token}`,
                },
            });
            api_name_suffix.value = response.data
        
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

const api_civil_status = ref([])
const civil_status_loading = ref(true)
const formatted_civil_status = ref([])
const getCivilStatus = async () => {
    try {
        
        const response = await axios.get(props.api_url+'api/admin/civil-statuses?per_page=300', {
                headers: {
                    Authorization: `Bearer ${props.api_token}`,
                },
            });
            api_civil_status.value = response.data
        
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

const api_nationality = ref([])
const nationality_loading = ref(true)
const formatted_nationality = ref([])
const getNationality = async () => {
    try {
        
        const response = await axios.get(props.api_url+'api/admin/nationalities?per_page=300', {
                headers: {
                    Authorization: `Bearer ${props.api_token}`,
                },
            });
            api_nationality.value = response.data
        
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

const formatAPItoComponent = (data, type) => {
    switch (type) {
        case 'relation':
            return data.data.map(list => ({
              id: list.description,
              name: list.description
            }));
            break;
        case 'name_suffix':
            return data.data.map(list => ({
              id: list.description,
              name: list.description
            }));
            break;
        case 'civil_status':
            return data.data.map(list => ({
              id: list.description,
              name: list.description
            }));
            break;
        case 'nationality':
            return data.data.map(list => ({
              id: list.description,
              name: list.description
            }));
            break;
        default:
            break;
    }
};

const hasValidationError = ref(false);

function closeToastFunction(){
    hasValidationError.value = false;
}

watch(form, (newValue, oldValue) => {
    hasValidationError.value = (form.hasErrors) ? true : false;
});

onMounted(() => {
    getRelation().then(() => {
        formatted_relation.value = formatAPItoComponent(api_relation.value, 'relation')
        relation_loading.value = false
    })
    getNameSuffix().then(() => {
        formatted_name_suffix.value = formatAPItoComponent(api_name_suffix.value, 'name_suffix')
        name_suffix_loading.value = false
    })
    getCivilStatus().then(() => {
        formatted_civil_status.value = formatAPItoComponent(api_civil_status.value, 'civil_status')
        civil_status_loading.value = false
    })
    getNationality().then(() => {
        formatted_nationality.value = formatAPItoComponent(api_nationality.value, 'nationality')
        nationality_loading.value = false
    })
})

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
                message="Successfully Saved AIF Data"
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
            @submit.prevent="updateAIFInformation"
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
                <div v-if="!name_suffix_loading" class="col-span-full lg:col-span-3">
                    <SelectInput 
                        v-model="form.name_suffix"
                        label="Suffix"
                        :options="formatted_name_suffix"
                        :errorMessage="form.errors.name_suffix"
                    />
                </div>
                <div v-else class="col-span-full lg:col-span-3 flex items-center justify-center">
                    <div class="w-20">
                        <Vue3Lottie 
                            animationLink="/animation/simple_loading_animation.json" 
                            width="100%" 
                        />
                    </div>
                </div>
                <div class="col-span-full lg:col-span-4">
                    <TextInput 
                        v-model="form.mothers_maiden_name"
                        label="Mother's Maiden Name"
                        placeholder="Enter Mother's Maiden Name"
                        :errorMessage="form.errors.mothers_maiden_name"
                    />
                </div>
                <div v-if="!civil_status_loading" class="col-span-full lg:col-span-3">
                    <SelectInput 
                        v-model="form.civil_status"
                        label="Civil Status"
                        :options="formatted_civil_status"
                        :errorMessage="form.errors.civil_status"
                    />
                </div>
                <div v-else class="col-span-full lg:col-span-3 flex items-center justify-center">
                    <div class="w-20">
                        <Vue3Lottie 
                            animationLink="/animation/simple_loading_animation.json" 
                            width="100%" 
                        />
                    </div>
                </div>
                <div class="col-span-full lg:col-span-2">
                    <SelectInput 
                        v-model="form.sex"
                        label="Gender"
                        :options="genderList"
                        :errorMessage="form.errors.sex"
                    />
                </div>
                <div v-if="!nationality_loading" class="col-span-full lg:col-span-3">
                    <SelectInput 
                        v-model="form.nationality"
                        label="Nationality"
                        :options="nationalityList"
                        :errorMessage="form.errors.nationality"
                    />
                </div>
                <div v-else class="col-span-full lg:col-span-3 flex items-center justify-center">
                    <div class="w-20">
                        <Vue3Lottie 
                            animationLink="/animation/simple_loading_animation.json" 
                            width="100%" 
                        />
                    </div>
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
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.tin"
                        label="Taxpayer Identification Number"
                        placeholder="Enter TIN"
                        :errorMessage="form.errors.tin"
                    />
                </div>
                <div v-if="!relation_loading" class="col-span-full lg:col-span-3">
                    <SelectInput 
                        v-model="form.relationship_to_buyer"
                        label="Relationship to Buyer"
                        required
                        :options="formatted_relation"
                        :errorMessage="form.errors.relationship_to_buyer"
                    />
                </div>
            </div>
            <div class="w-full lg:flex lg:flex-row lg:items-center lg:justify-center text-center pb-10">
                <div class="w-full lg:w-64">
                    <PlainBlackButton :disabled="form.processing" type="submit" customClass="w-auto">
                        <p class="text-white font-bold text-center">
                            Save AIF Information
                        </p>
                    </PlainBlackButton>
                </div>
            </div>
        </form>
    </section>
</template>
