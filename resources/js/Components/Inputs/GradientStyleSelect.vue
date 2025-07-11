<script setup>
import DefaultGradientBorder from '../Container/DefaultGradientBorder.vue';
const props = defineProps({
    label: {
        type: String,
        default: "Default Text"
    },
    modelValue: {
        type: [String, Number],
        default: ""
    },
    error: {
        type: String,
        default: null
    },
    required: {
        type: Boolean,
        default:false
    },
    options: {
        type: Object,
        default:[]
    },
})

const emit = defineEmits(['update:modelValue'])

const updateInput = (newVal) => {
    emit('update:modelValue', newVal.target.value)
}

</script>
<template>
    <div>
        <label for="input" class="font-bold text-sm">{{ label }} <span v-if="required" class="text-red-600">*</span></label>
        <DefaultGradientBorder>
           <select :value="modelValue" @input="updateInput" class="w-full border-none focus:outline-none focus:ring-0">
                <option value="" selected>Select Project</option>
                <template v-for="(item, index) in options" :key="index">
                    <option :value="item.id">{{ item.description }}</option>
                </template>
           </select>
        </DefaultGradientBorder>
        <div class="text-red-700 italic">{{ error }}</div>
    </div>
</template>