<script setup>
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue';
import TextInput from '@/Components/Inputs/TextInput.vue';
import DatePicker from '@/Components/Inputs/DatePicker.vue';
import SelectInput from '@/Components/Inputs/SelectComboboxes.vue';
import SuccessToast from '@/Components/Notification/SuccessToast.vue';
import {useForm, usePage, } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';
import WarningToast from '@/Components/Notification/WarningToast.vue';
import PlainBlackButton from '@/Components/Buttons/PlainBlackButton.vue';
import axios from 'axios';
import { Vue3Lottie } from "vue3-lottie";

const props = defineProps({
    contact: Object,
    address_type: String,
    api_token: String,
    api_url: String,
    sameWithPermanentAddress: {
        type : Boolean,
        default: false
    },
});

const checkFormDirty = () => {
    return form.isDirty
};
const saveThisForm = () => {
    updateAddress()
};

defineExpose({
    checkFormDirty,
    saveThisForm
});

const emit = defineEmits(['update:sameWithPermanentAddress']);

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
    sublocality: address()?.sublocality,
    locality: address()?.locality,
    administrative_area: address()?.administrative_area,
    postal_code: address()?.postal_code,
    region: address()?.region,
    country: address()?.country ?? 'PH',
    full_address: address()?.full_address ?? '',
    sameWithPermanentAddress: props.sameWithPermanentAddress,
})

const updateAddress = async () => {
    await form.patch(route('address.update'), {
        errorBag: 'updateAddress',
        preserveScroll: true,
    });
};

const ownershipList = usePage().props.enums.ownerships.map(item => ({
    id: item,
    name: item
}));

const hasValidationError = ref(false);

function closeToastFunction(){
    hasValidationError.value = false;
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

const ownership = ref([])
const ownership_loading = ref(true)
const formatted_ownership = ref([])
const getOwnership = async () => {
    try {
        const response = await axios.get(props.api_url+'api/admin/home-ownerships?per_page=1000', {
        headers: {
            Authorization: `Bearer ${props.api_token}`,
        },
        });
        ownership.value = response.data
        
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
        case 'ownership':
            return data.data.map(list => ({
              id: list.description,
              name: list.description
            }));
            break;
        default:
            break;
    }
};

const concatinateFullAddress = () => {
    let sublocality = formatted_api_barangay.value.find(item => item.id === form.sublocality);
    let locality = formatted_api_city.value.find(item => item.id === form.locality);
    let administrative_area = formatted_api_province.value.find(item => item.id === form.administrative_area);
    if(form.region == 13){
        form.full_address = `${form.address1} ${sublocality?.name ?? ''} ${locality?.name ?? ''}`;
    }else{
        form.full_address = `${form.address1} ${sublocality?.name ?? ''} ${locality?.name ?? ''} ${administrative_area?.name ?? ''}`;
    }
}

watch(form, (newValue, oldValue) => {
    hasValidationError.value = (form.hasErrors) ? true : false;
});

watch(
    () => props.api_token,
    (newValue, oldValue) => {
        // getRegion().then(() => {
        //     formatted_api_regions.value = formatAPItoComponent(api_regions.value, 'region')
        //     regions_loading.value = false

        // });
    }
);

watch(regions_loading, (newValue, oldValue) => {
    if(!newValue){
        getProvince(form.region).then(() => {
            formatted_api_province.value = formatAPItoComponent(api_province.value, 'province')
            province_loading.value = false
        })
        
    }
})

watch(province_loading, (newValue, oldValue) => {
    if(!newValue){
        getCity(form.region, form.administrative_area).then(() => {
            formatted_api_city.value = formatAPItoComponent(api_city.value, 'city')
            city_loading.value = false
        })
        
    }
})

watch(city_loading, (newValue, oldValue) => {
    if(!newValue){
        getBarangay(form.region, form.administrative_area, form.locality).then(() => {
            formatted_api_barangay.value = formatAPItoComponent(api_barangay.value, 'barangay')
            barangay_loading.value = false
        })
        
    }
})

watch(
    () => form.region,
    (newValue, oldValue) => {
        province_loading.value = true
        getProvince(newValue).then(() => {
            formatted_api_province.value = formatAPItoComponent(api_province.value, 'province')
            province_loading.value = false
            concatinateFullAddress()
        })
    }
)

watch(
    () => form.administrative_area,
    (newValue, oldValue) => {
        city_loading.value = true
        getCity(form.region, newValue).then(() => {
            formatted_api_city.value = formatAPItoComponent(api_city.value, 'city')
            city_loading.value = false
            concatinateFullAddress()
        })
    }
)

watch(
    () => form.locality,
    (newValue, oldValue) => {
        barangay_loading.value = true
        getBarangay(form.region, form.administrative_area, newValue).then(() => {
            formatted_api_barangay.value = formatAPItoComponent(api_barangay.value, 'barangay')
            barangay_loading.value = false
            concatinateFullAddress()
        })
    }
)

watch(
    () => form.sublocality,
    (newValue, oldValue) => {
        concatinateFullAddress()
    }
)

watch(
    () => form.address1,
    (newValue, oldValue) => {
        concatinateFullAddress()
    }
)

watch(
    () => form.sameWithPermanentAddress,
    (newValue, oldValue) => {
        emit('update:sameWithPermanentAddress', newValue)
    }
)

onMounted(() => {
    getRegion().then(() => {
            formatted_api_regions.value = formatAPItoComponent(api_regions.value, 'region')
            regions_loading.value = false
        });
    getCountries().then(() => {
        formatted_country.value = formatAPItoComponent(countries.value, 'country')
        country_loading.value = false
    })
    getOwnership().then(() => {
        formatted_ownership.value = formatAPItoComponent(ownership.value, 'ownership')
        ownership_loading.value = false
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
                :message="'Successfully Saved ' + props.address_type + ' address'"
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
            @submit.prevent="updateAddress"
            class="mt-6 space-y-6"
        >
            <div>
                <h3 class="font-bold text-[#006FFD] mt-4 uppercase">{{ props.address_type }} Address:</h3>
            </div>
            <div class="grid grid-cols-12 gap-4">
                <div v-if="!ownership_loading" class="col-span-full lg:col-span-2">
                    <SelectInput
                        v-model="form.ownership"
                        label="Ownership"
                        :options="formatted_ownership"
                        :errorMessage="form.errors.ownership"
                        required
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
                <div v-if="!country_loading" class="col-span-full lg:col-span-2">
                    <SelectInput
                        v-model="form.country"
                        label="Country"
                        :options="formatted_country"
                        :errorMessage="form.errors.country"
                        required
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
                <div v-if="!regions_loading" class="col-span-full lg:col-span-4">
                    <SelectInput
                        v-model="form.region"
                        label="Region"
                        :options="formatted_api_regions"
                        :errorMessage="form.errors.region"
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
                <div v-if="!province_loading" class="col-span-full lg:col-span-4">
                    <SelectInput
                        v-model="form.administrative_area"
                        label="Province"
                        :options="formatted_api_province"
                        :errorMessage="form.errors.administrative_area"
                        required
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
                <div v-if="!city_loading" class="col-span-full lg:col-span-3">
                    <SelectInput
                        v-model="form.locality"
                        label="City"
                        :options="formatted_api_city"
                        :errorMessage="form.errors.locality"
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
                <div v-if="!barangay_loading" class="col-span-full lg:col-span-3">
                    <SelectInput
                         v-model="form.sublocality"
                        label="Barangay"
                        :options="formatted_api_barangay"
                        :errorMessage="form.errors.sublocality"
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
                <div class="col-span-full lg:col-span-4">
                    <TextInput
                        v-model="form.address1"
                        label="Unit No., House/Bldg/St. Name"
                        placeholder="Enter Address"
                        :errorMessage="form.errors.address1"
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
                <div class="col-span-full">
                    <TextInput
                        v-model="form.full_address"
                        label="Full Address"
                        placeholder="Enter Full Address"
                        :errorMessage="form.errors.full_address"
                        required
                    />
                </div>
                
            </div>
            <div v-if="address_type == 'Present'" class="flex flex-row items-center gap-2 cursor-pointer">
                <input type="checkbox" v-model="form.sameWithPermanentAddress" class="rounded text-black border-black focus:ring-0">
                <label class="text-sm italic"> Same with Permanent Address </label>
            </div>
            <div class="w-full lg:flex lg:flex-row lg:items-center lg:justify-center text-center pb-10">
                <div class="w-full lg:w-64">
                    <PlainBlackButton :disabled="form.processing" type="submit" customClass="w-auto">
                        <p class="text-white font-bold text-center">
                            Save {{ props.address_type }} Address
                        </p>
                    </PlainBlackButton>
                </div>
            </div>
            
        </form>
    </section>
</template>
