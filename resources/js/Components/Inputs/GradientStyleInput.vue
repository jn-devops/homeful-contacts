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
    placeholder: {
        type: String,
        default: "Enter something..."
    },
    error: {
        type: String,
        default: null
    },
    email: {
        type: Boolean,
        default:false
    },
    required: {
        type: Boolean,
        default:false
    },
    type:{
        type: String,
        default:"text"
    }
})

const emit = defineEmits(['update:modelValue'])

const updateInput = (newVal) => {
    emit('update:modelValue', newVal.target.value)
}

</script>
<template>
    <div>
        <label for="input" class="font-bold text-sm">{{ label }}</label>
        <DefaultGradientBorder>
            <input 
                id="input"
                :value="modelValue"
                @input="updateInput"
                :required="required"
                :type="type"
                class="p-3 text-black w-full h-full focus:ring-0 border-none"
                :placeholder="placeholder"
            >
        </DefaultGradientBorder>
        <div class="text-red-700 italic">{{ error }}</div>
    </div>
</template>