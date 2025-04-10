<script setup>
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue';
import TextInput from '@/Components/Inputs/TextInput.vue';
import DatePicker from '@/Components/Inputs/DatePicker.vue';
import SelectInput from '@/Components/Inputs/SelectComboboxes.vue';
import SuccessToast from '@/Components/Notification/SuccessToast.vue';
import WarningToast from '@/Components/Notification/WarningToast.vue';
import {useForm, usePage} from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';
import PlainBlackButton from '@/Components/Buttons/PlainBlackButton.vue';
import { Vue3Lottie } from 'vue3-lottie';

const props = defineProps({
    contact: Object,
    employment_type: String,
    api_token: String,
    api_url: String,
});

const checkFormDirty = () => {
    return form.isDirty
};
const saveThisForm = () => {
    updateEmployment()
};

defineExpose({
    checkFormDirty,
    saveThisForm
});

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


const employment_records = () => {
    if (null != props.contact?.employment) {
        return Object.groupBy(props.contact?.employment, employment => employment.type)
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
    monthly_gross_income: employment_record()?.monthly_gross_income,
    employment_status: employment_record()?.employment_status ?? 'Regular',
    rank: employment_record()?.rank ?? null,
    years_in_service: employment_record()?.years_in_service ?? null,

    employer_name: employment_record()?.employer?.name,
    employment_type: employment_record()?.employment_type,
    current_position: employment_record()?.current_position,
    employer_email: employment_record()?.employer?.email,
    employer_contact_no: employment_record()?.employer?.contact_no,
    employer_nationality: employment_record()?.employer?.nationality,
    employer_industry: employment_record()?.employer?.industry,
    employer_year_established: employment_record()?.employer?.year_established,
    employer_total_number_of_employees: employment_record()?.employer?.total_number_of_employees,

    employer_address_type: employment_record()?.employer?.address?.type ?? employment_record()?.employer?.name ? 'Work' : "Work",
    employer_address_ownership: employment_record()?.employer?.address?.ownership ? employment_record().employer.address.ownership : 'Owned',
    employer_address_address1: employment_record()?.employer?.address?.address1,
    employer_address_locality: employment_record()?.employer?.address?.locality,
    employer_address_sublocality: employment_record()?.employer?.address?.sublocality,
    employer_address_administrative_area: employment_record()?.employer?.address?.administrative_area,
    employer_address_postal_code: employment_record()?.employer?.address?.postal_code,
    employer_address_region: employment_record()?.employer?.address?.region,
    employer_address_country: employment_record()?.employer?.address?.country ?? employment_record()?.employer?.name ? 'PH' : "PH",

    tin: employment_record()?.id?.tin,
    pagibig: employment_record()?.id?.pagibig,
    sss: employment_record()?.id?.sss,
    gsis: employment_record()?.id?.gsis,
})

const api_tenure = ref([])
const tenure_loading = ref(true)
const formatted_tenure = ref([])
const getTenure = async () => {
    try {
        
        const response = await axios.get(props.api_url+'api/admin/tenures?per_page=300', {
                headers: {
                    Authorization: `Bearer ${props.api_token}`,
                },
            });
        api_tenure.value = response.data
        
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

const api_current_position = ref([])
const current_position_loading = ref(true)
const formatted_current_positions = ref([])
const getCurrentPosition = async () => {
    try {
        
        const response = await axios.get(props.api_url+'api/admin/current-positions?per_page=300', {
                headers: {
                    Authorization: `Bearer ${props.api_token}`,
                },
            });
        api_current_position.value = response.data
        
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

const countries = ref([])
const country_loading = ref(true)
const formatted_country = ref([])
const getCountries = async () => {
    try {
        const response = await axios.get(props.api_url+'api/admin/countries?per_page=1000', {
        headers: {
            Authorization: `Bearer ${props.api_token}`,
        },
        });
        countries.value = response.data
        
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

const api_regions = ref([]);
const formatted_api_regions = ref([]);
const regions_loading = ref(true);
const getRegion = async () => {
  try {
    const response = await axios.get(props.api_url+'api/admin/philippine-regions?per_page=1000', {
      headers: {
        Authorization: `Bearer ${props.api_token}`,
      },
    });
    api_regions.value = response.data;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const api_province = ref([]);
const formatted_api_province = ref([]);
const province_loading = ref(true);
const getProvince = async (region_code = null) => {
    try {
        let link = (region_code) ? props.api_url+'api/admin/philippine-provinces?per_page=1000&region_code='+region_code : props.api_url+'api/admin/philippine-provinces?per_page=1000'
        const response = await axios.get(link, {
        headers: {
            Authorization: `Bearer ${props.api_token}`,
        },
        });
        api_province.value = response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

const api_city = ref([]);
const formatted_api_city = ref([]);
const city_loading = ref(true);
const getCity = async (region_code = null, province_code = null) => {
    try {
        let link = (province_code) ? props.api_url+'api/admin/philippine-cities?per_page=1000&province_code='+province_code+'&region_code='+region_code : props.api_url+'api/admin/philippine-cities?per_page=10'
        
        const response = await axios.get(link, {
        headers: {
            Authorization: `Bearer ${props.api_token}`,
        },
        });
        api_city.value = response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

const api_barangay = ref([]);
const formatted_api_barangay = ref([]);
const barangay_loading = ref(true);
const getBarangay = async (region_code = null, province_code = null, city_code = null) => {
    try {
        let link = (city_code) ? props.api_url+'api/admin/philippine-barangays?per_page=100&city_municipality_code='+city_code+'&province_code='+province_code+'&region_code='+region_code : props.api_url+'api/admin/philippine-barangays?per_page=10'
        const response = await axios.get(link, {
        headers: {
            Authorization: `Bearer ${props.api_token}`,
        },
        });
        api_barangay.value = response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

const updateEmployment = async () => {
    await form.patch(route('employment.update'), {
        errorBag: 'updateEmployment',
        preserveScroll: true,
    });
};

const hasValidationError = ref(false);

const formatAPItoComponent = (data, type) => {
    switch (type) {
        case 'region':
            return data.data.map(list => ({
              id: list.region_code,
              name: list.region_description
            }));
            break;
        case 'province':
            return data.data.map(list => ({
              id: list.province_code,
              name: list.province_description
            }));
            break;
        case 'city':
            return data.data.map(list => ({
              id: list.city_municipality_code,
              name: list.city_municipality_description
            }));
            break;
        case 'barangay':
            return data.data.map(list => ({
              id: list.barangay_code,
              name: list.barangay_description
            }));
            break;
        case 'country':
            return data.data.map(list => ({
              id: list.code,
              name: list.description
            }));
            break;
        case 'current_position':
            return data.data.map(list => ({
              id: list.code,
              name: list.description
            }));
            break;
        case 'tenure':
            return data.data.map(list => ({
              id: list.code,
              name: list.description
            }));
            break;
        default:
            break;
    }
};

function closeToastFunction(){
    hasValidationError.value = false;
}

watch(form, (newValue, oldValue) => {
    hasValidationError.value = (form.hasErrors) ? true : false;
});

watch(regions_loading, (newValue, oldValue) => {
    if(!newValue){
        getProvince(form.employer_address_region).then(() => {
            formatted_api_province.value = formatAPItoComponent(api_province.value, 'province')
            province_loading.value = false
        })
        
    }
})

watch(province_loading, (newValue, oldValue) => {
    if(!newValue){
        getCity(form.employer_address_region, form.employer_address_administrative_area).then(() => {
            formatted_api_city.value = formatAPItoComponent(api_city.value, 'city')
            city_loading.value = false
        })
        
    }
})

watch(city_loading, (newValue, oldValue) => {
    if(!newValue){
        getBarangay(form.employer_address_region, form.employer_address_administrative_area, form.employer_address_locality).then(() => {
            formatted_api_barangay.value = formatAPItoComponent(api_barangay.value, 'barangay')
            barangay_loading.value = false
        })
        
    }
})

watch(
    () => form.employer_address_region,
    (newValue, oldValue) => {
        province_loading.value = true
        getProvince(newValue).then(() => {
            formatted_api_province.value = formatAPItoComponent(api_province.value, 'province')
            province_loading.value = false
        })
    }
)

watch(
    () => form.employer_address_administrative_area,
    (newValue, oldValue) => {
        city_loading.value = true
        getCity(form.employer_address_region, newValue).then(() => {
            formatted_api_city.value = formatAPItoComponent(api_city.value, 'city')
            city_loading.value = false
        })
    }
)

watch(
    () => form.employer_address_locality,
    (newValue, oldValue) => {
        barangay_loading.value = true
        getBarangay(form.employer_address_region, form.employer_address_administrative_area, newValue).then(() => {
            formatted_api_barangay.value = formatAPItoComponent(api_barangay.value, 'barangay')
            barangay_loading.value = false
        })
    }
)

onMounted(() => {
    getRegion().then(() => {
        formatted_api_regions.value = formatAPItoComponent(api_regions.value, 'region')
        regions_loading.value = false
    });
    getCurrentPosition().then(() => {
        formatted_current_positions.value = formatAPItoComponent(api_current_position.value, 'current_position')
        current_position_loading.value = false
    });
    getCountries().then(() => {
        formatted_country.value = formatAPItoComponent(countries.value, 'country')
        country_loading.value = false
    })
    getTenure().then(() => {
        formatted_tenure.value = formatAPItoComponent(api_tenure.value, 'tenure')
        tenure_loading.value = false
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
                :message="'Successfully Saved ' + employment_type + ' Data'"
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
            @submit.prevent="updateEmployment"
            class="mt-6 space-y-6"
        >
            <h3 class="font-bold text-[#006FFD] mt-4 uppercase">{{ employment_type + ' Employment Information:' }}</h3>

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
                <div class="col-span-full lg:col-span-2">
                    <TextInput 
                        v-model="form.rank"
                        label="Rank"
                        placeholder="Enter Rank"
                        :errorMessage="form.errors.rank"
                    />
                </div>
                <div class="col-span-full lg:col-span-4">
                    <TextInput 
                        v-model="form.employer_name"
                        label="Employer Name"
                        required
                        placeholder="Enter Employer Name"
                        :errorMessage="form.errors.employer_name"
                    />
                </div>
                <div class="col-span-full lg:col-span-2">
                    <TextInput 
                        v-model="form.employer_year_established"
                        label="Employer Year Established"
                        placeholder="Enter Year Established"
                        type="number"
                        :errorMessage="form.errors.employer_year_established"
                    />
                </div>
                <div v-if="!current_position_loading" class="col-span-full lg:col-span-4">
                    <SelectInput 
                        v-model="form.current_position"
                        label="Employment Position"
                        required
                        :options="formatted_current_positions"
                        :errorMessage="form.errors.current_position"
                    />
                </div>
                <div v-else class="col-span-full lg:col-span-4 flex items-center justify-center">
                    <div class="w-20">
                        <Vue3Lottie 
                            animationLink="/animation/simple_loading_animation.json" 
                            width="100%" 
                        />
                    </div>
                </div>
                <div v-if="!tenure_loading" class="col-span-full lg:col-span-2">
                    <SelectInput 
                        v-model="form.years_in_service"
                        label="Tenure"
                        :options="formatted_tenure"
                        :errorMessage="form.errors.years_in_service"
                    />
                </div>
                <div v-else class="col-span-full lg:col-span-2 flex items-center justify-center">
                    <div class="w-20">
                        <Vue3Lottie 
                            animationLink="/animation/simple_loading_animation.json" 
                            width="100%" 
                        />
                    </div>
                </div>
                <div class="col-span-full lg:col-span-3">
                    <SelectInput 
                        v-model="form.employment_type"
                        label="Employment Type"
                        required
                        :options="employmentTypeList"
                        :errorMessage="form.errors.employment_type"
                    />
                </div>
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.employer_email"
                        label="Email"
                        required
                        placeholder="Enter Employer Email"
                        :errorMessage="form.errors.employer_email"
                    />
                </div>
                <div class="col-span-full lg:col-span-2">
                    <TextInput 
                        v-model="form.employer_contact_no"
                        label="Contact No."
                        required
                        type="number"
                        :max="11"
                        placeholder="09*********"
                        :errorMessage="form.errors.employer_contact_no"
                    />
                </div>
                <div class="col-span-full lg:col-span-2">
                    <SelectInput 
                        v-model="form.employer_nationality"
                        label="Nationality"
                        :options="employerNationalityList"
                        :errorMessage="form.errors.employer_nationality"
                    />
                </div>
                <div class="col-span-full lg:col-span-5">
                    <SelectInput 
                        v-model="form.employer_industry"
                        label="Industry"
                        required
                        :options="industryList"
                        :errorMessage="form.errors.employer_industry"
                    />
                </div>
                <!-- <div class="col-span-full lg:col-span-2">
                    <TextInput 
                        v-model="form.employer_address_type"
                        readOnly
                        label="Address Type"
                        required
                        placeholder="Enter Employer Address Type"
                        :errorMessage="form.errors.employer_address_type"
                    />
                </div> -->
                <!-- <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.employer_address_ownership"
                        label="Address Ownership"
                        required
                        placeholder="Enter Employer Address Ownership"
                        :errorMessage="form.errors.employer_address_ownership"
                    />
                </div> -->
                <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.employer_total_number_of_employees"
                        label="Total Number of Employees"
                        placeholder="Enter No. of Employees"
                        type="number"
                        :errorMessage="form.errors.employer_total_number_of_employees"
                    />
                </div>
                <div v-if="!country_loading" class="col-span-full lg:col-span-3">
                    <SelectInput
                        v-model="form.employer_address_country"
                        label="Country"
                        :options="formatted_country"
                        :errorMessage="form.errors.employer_address_country"
                        required
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
                <div v-if="!regions_loading" class="col-span-full lg:col-span-3">
                    <SelectInput 
                        v-model="form.employer_address_region"
                        label="Region"
                        required
                        :options="formatted_api_regions"
                        :errorMessage="form.errors.employer_address_region"
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
                <div v-if="!province_loading" class="col-span-full lg:col-span-3">
                    <SelectInput 
                        v-model="form.employer_address_administrative_area"
                        label="Province"
                        required
                        :options="formatted_api_province"
                        :errorMessage="form.errors.employer_address_administrative_area"
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
                <div v-if="!city_loading" class="col-span-full lg:col-span-3">
                    <SelectInput 
                        v-model="form.employer_address_locality"
                        label="City"
                        required
                        :options="formatted_api_city"
                        :errorMessage="form.errors.employer_address_locality"
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
                <div v-if="!barangay_loading" class="col-span-full lg:col-span-3">
                    <SelectInput 
                        v-model="form.employer_address_sublocality"
                        label="Barangay"
                        required
                        :options="formatted_api_barangay"
                        :errorMessage="form.errors.employer_address_sublocality"
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
                    <TextInput 
                        v-model="form.employer_address_address1"
                        label="Unit No., House/Bldg/St. Name"
                        placeholder="Enter Employer Address"
                        :errorMessage="form.errors.employer_address_address1"
                    />
                </div>
                <!-- <div class="col-span-full lg:col-span-3">
                    <TextInput 
                        v-model="form.employer_address_postal_code"
                        label="ZIP Code"
                        placeholder="Enter Employer ZIP Code"
                        :errorMessage="form.errors.employer_address_postal_code"
                    />
                </div> -->
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
                        v-model="form.gsis"
                        label="GSIS"
                        placeholder="Enter GSIS Number"
                        :errorMessage="form.errors.gsis"
                    />
                </div>
            </div>
            <div class="w-full lg:flex lg:flex-row lg:items-center lg:justify-center text-center pb-10">
                <div class="w-full lg:w-64">
                    <PlainBlackButton :disabled="form.processing" type="submit" customClass="w-auto">
                        <p class="text-white font-bold text-center">
                            Save {{ employment_type }} Data
                        </p>
                    </PlainBlackButton>
                </div>
            </div>
        </form>
    </section>
</template>
