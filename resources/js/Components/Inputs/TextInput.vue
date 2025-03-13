<script setup>
import { ExclamationCircleIcon } from '@heroicons/vue/20/solid';
import { onMounted, ref, toRef, watch } from 'vue';

const props = defineProps({
  label: {
    type: String,
    default: '',
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
  type: {
    type: String,
    default: 'text',
  },
  max: {
    type: Number,
    default: null,
  },
  readOnly: {
    type: Boolean,
    default: false,
  },
  noBorderRadius: {
    type: Boolean,
    default: true
  }
});

const model = defineModel({
    type: String,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });

function filterInput(event) {
  if(props.type == 'number'){
    const value = event.target.value;
    model.value = value.replace(/[^0-9]/g, '');
  }
}

const errorMessage = toRef(props, 'errorMessage');

watch(errorMessage, (newValue) => {
  if (newValue && input.value) {
    input.value.scrollIntoView({ behavior: 'smooth', block: 'center' });
    setTimeout(() => {
      input.value.focus();
    }, 300);
  }
});

</script>
<template>
    <div>
        <label class="block text-sm/6 font-medium" :class="errorMessage ? 'text-red-600' : 'text-gray-900'">{{label}} <span v-if="required" class="text-red-600">*</span></label>
        <div class="grid grid-cols-1">
            <input 
                v-model="model"
                :maxlength="max"
                ref="input"
                :readonly="readOnly"
                @input="filterInput"
                class="col-start-1 row-start-1 block w-full bg-white py-1.5 pl-3 pr-10 text-base border-none focus:ring-0 outline outline-1 -outline-offset-1 focus:outline focus:outline-2 focus:-outline-offset-2 sm:pr-9 sm:text-sm/6"
                :class="{
                  'text-red-900 outline-red-300 placeholder:text-red-300 focus:outline-red-600': errorMessage,
                  'text-gray-900 outline-gray-300 placeholder:text-gray-400 focus:outline-[#006FFD]': !errorMessage,
                  'rounded-md' : !noBorderRadius,
                }"
                :placeholder="placeholder" 
            />
            <ExclamationCircleIcon v-if="errorMessage" class="pointer-events-none col-start-1 row-start-1 mr-3 size-5 self-center justify-self-end text-red-500 sm:size-4" aria-hidden="true" />
        </div>
        <p v-if="errorMessage" class="mt-2 text-sm text-red-600" id="email-error">{{ errorMessage }}</p>
        <p v-if="helperMessage" class="mt-2 text-sm text-gray-500" id="email-description">{{ helperMessage }}</p>
    </div>
</template>
  