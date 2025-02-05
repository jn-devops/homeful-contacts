<script setup>
import { ExclamationCircleIcon } from '@heroicons/vue/20/solid';
import { onMounted, ref } from 'vue';
import VueDatepicker from "vue-tailwind-datepicker";
import DefaultGradientBorder from '../Container/DefaultGradientBorder.vue';

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
  error: {
    type: String,
    default: null,
  },
  helperMessage: {
    type: String,
    default: null,
  },
  required: {
        type: Boolean,
        default:false
    },
});

const model = defineModel({
    type: String,
})

const dateFormat  =  {
    date: 'YYYY-MM-DD',
    month: 'MMM',
}

// const emit = defineEmits(['update:modelValue'])

// const updateInput = (newVal) => {
//     emit('update:modelValue', newVal.target.value)
// }


</script>
<template>
    <div>
        <label class="block text-sm/6 font-medium mb-2" :class="error ? 'text-red-600' : 'text-gray-900'">{{label}} <span v-if="required" class="text-red-600">*</span></label>
        <DefaultGradientBorder>
            <VueDatepicker 
                v-model="model"
                as-single
                :formatter="dateFormat"
                :required="required"
                class="h-9 focus:ring-0 focus:outline-0 border-none text-gray-800 text-lg"
            />
        </DefaultGradientBorder>
        <p v-if="error" class="mt-2 text-sm text-red-600" id="email-error">{{ error }}</p>
        <p v-if="helperMessage" class="mt-2 text-sm text-gray-500" id="email-description">{{ helperMessage }}</p>
    </div>
</template>
  