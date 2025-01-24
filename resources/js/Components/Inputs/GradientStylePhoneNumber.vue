<script setup>
import { onMounted, ref } from 'vue';
import DefaultGradientBorder from '../Container/DefaultGradientBorder.vue';
const props = defineProps({
    label: {
        type: String,
        default: "Default Text"
    },
    modelValue: {
        type: String,
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
})

const localValue = ref('')

const emit = defineEmits(['update:modelValue'])

const updateInput = (event) => {
    let temp = ""
    if(event.target.value && event.target.value != "+63 "){
        if(event.target.value.startsWith("+63 ")){
            temp = event.target.value.substring(4)
        }else{
            temp = event.target.value
        }
        localValue.value = "+63 " + formatPhoneNumber(temp)
        let temp2 = event.target.value.substring(4)
        emit('update:modelValue', '0'+temp2.replace(/[^0-9]/g, ''))
    }else{
        empty()
    }
}
const formatPhoneNumber = (str) => {
    let value = str.replace(/\D/g, '');
    if (value.length <= 3) {
        return value; 
    } else if (value.length <= 6) {
        return value.replace(/(\d{3})(\d{0,3})/, '$1-$2'); 
    } else {
        return value.replace(/(\d{3})(\d{3})(\d{0,4})/, '$1-$2-$3');
    }
}
const empty = () => {
    localValue.value = ""
    emit('update:modelValue', "")
}
onMounted(() => {
    if(props.modelValue){
        let temp = props.modelValue.substring(1)
        localValue.value = "+63 " + formatPhoneNumber(temp.replace(/[^0-9]/g, ''))
    }
})

</script>
<template>
    <div>
        <label for="input" class="font-bold text-sm">{{ label }}</label>
        <DefaultGradientBorder>
            <input 
                id="input"
                v-model="localValue"
                @input="updateInput"
                type="text"
                maxlength="16"
                class="p-3 text-black w-full h-full focus:ring-0 border-none"
                :placeholder="placeholder"
            >
        </DefaultGradientBorder>
        <div class="text-red-700 italic">{{ error }}</div>
    </div>
</template>