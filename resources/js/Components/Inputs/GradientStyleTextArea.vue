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
            <textarea
                id="input"
                :value="modelValue"
                @input="updateInput"
                :required="required"
                type="text-area"
                class="p-3 text-black w-full h-full focus:ring-0 border-none"
                :placeholder="placeholder"
            ></textarea>
        </DefaultGradientBorder>
        <div class="text-red-700 italic">{{ error }}</div>
    </div>
</template>