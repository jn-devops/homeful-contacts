<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutV2.vue';
import { Head } from '@inertiajs/vue3';
import AddressForm from '@/Pages/Address/Partials/AddressFormV2.vue';
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';``

const props = defineProps({
   contact: Object,
   lazarus_url: String,
   lazarus_token: String,
});

// const token = ref('')

const get_token = async () => {
    try {
        const response = await axios.post('http://homeful-lazarus.test/api/auth/login', {
            email: 'admin@admin.com.ph',
            password: 'weneverknow'
        }, {
            headers: {
                'Content-Type': 'application/json'
            }
        });
        token.value = response.data.token;
    } catch (error) {
        console.error('Error:', error);
    }
}

onMounted(() => {
    // get_token()
})
</script>

<template>
    <Head title="Address Information" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            
            <AddressForm
                :contact = "contact"
                :api_token="lazarus_token"
                :api_url="lazarus_url"
                address_type = "Present"
                class="w-full"
            />

            <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
            
            <AddressForm
                :contact = "contact"
                :api_token="lazarus_token"
                :api_url="lazarus_url"
                address_type = "Permanent"
                class="w-full"
            />

        </div>
    </AuthenticatedLayout>
</template>
