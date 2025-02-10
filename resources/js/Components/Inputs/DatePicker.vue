<script setup>
import { ExclamationCircleIcon } from '@heroicons/vue/20/solid';
import { onMounted, ref } from 'vue';
import VueDatepicker from "vue-tailwind-datepicker";

const props = defineProps({
  label: {
    type: String,
    default: 'Default Label',
  },
  required: {
    type: Boolean,
    default: false,
  },
  placeholder: {
    type: String,
    default: 'Enter text...',
  },
  errorMessage: {
    type: String,
    default: null,
  },
  helperMessage: {
    type: String,
    default: null,
  },
});

const model = defineModel({
    type: String,
})

const dateFormat  =  {
    date: 'YYYY-MM-DD',
    month: 'MMM'
  }


</script>
<template>
    <div>
        <label class="block text-sm/6 font-medium" :class="errorMessage ? 'text-red-600' : 'text-gray-900'">{{label}} <span v-if="required" class="text-red-600">*</span></label>
        <VueDatepicker 
            v-model="model"
            as-single
            :formatter="dateFormat"
            class="h-9 focus:ring-0 focus:outline-[#006FFD] focus:outline-2 rounded-none focus:border-none"
        />
        <p v-if="errorMessage" class="mt-2 text-sm text-red-600" id="email-error">{{ errorMessage }}</p>
        <p v-if="helperMessage" class="mt-2 text-sm text-gray-500" id="email-description">{{ helperMessage }}</p>
    </div>
</template>
  