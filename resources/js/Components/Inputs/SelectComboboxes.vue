<script setup>
    import { computed, ref, toRef, watch, watchEffect } from 'vue'
    import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'
    import {
        Combobox,
        ComboboxButton,
        ComboboxInput,
        ComboboxLabel,
        ComboboxOption,
        ComboboxOptions,
    } from '@headlessui/vue'

    const props = defineProps({
        label: {
            type: String,
            default: 'Default Label',
        },
        required: {
            type: Boolean,
            default: false,
        },
        errorMessage: {
            type: String,
            default: null,
        },
        helperMessage: {
            type: String,
            default: null,
        },
        options: {
            type: Array,
            default: null,
        },
        modelValue: {
            type: [String],
            default: null,
        },
    });

    const emit = defineEmits(['update:modelValue'])

    const query = ref('')
    const selectedOption = ref(props.options.find(item => item.id === props.modelValue))
    const div_ref = ref(null)
    const filteredOptions = computed(() =>{
            let r_val = []
            query.value === ''
                ? r_val = props.options
                : r_val = props.options.filter((person) => {
                    return person.name.toLowerCase().includes(query.value.toLowerCase())
                })
            r_val.unshift({ 'id': null, 'name': 'N/A' })
            // Remove duplicates based on `id`
            r_val = r_val.filter((value, index, self) => 
                index === self.findIndex((t) => t.id === value.id)
            )
            return r_val
        }
    )

    watch(selectedOption, (newValue) => {
        emit('update:modelValue', newValue.id)
    })

    const errorMessage = toRef(props, 'errorMessage');

    watch(errorMessage, (newValue) => {
        if (newValue && div_ref.value) {
            if (newValue && div_ref.value) {
                div_ref.value.scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(() => {
                    div_ref.value.focus();
                }, 300);
            }
        }
    });
    
</script>
<template>
    <Combobox as="div" v-model="selectedOption" @update:modelValue="query = ''">
        <ComboboxLabel class="block text-sm/6 font-medium text-gray-900">{{ label }} <span v-if="required" class="text-red-600">*</span></ComboboxLabel>
        <div class="relative">
            <ComboboxInput 
                :class="{
                  'text-red-900 outline-2 outline-red-500 placeholder:text-red-500 focus:outline-red-600': errorMessage,
                }"
                class="block w-full bg-white py-1.5 pl-3 pr-12 text-base text-gray-900 border-none ring-0 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-1 focus:-outline-offset-1 focus:outline-[#006FFD] sm:text-sm/6" 
                @change="query = $event.target.value" 
                @blur="query = ''" 
                :display-value="(option) => option?.name" 
                placeholder="Select from the options" 
                autocomplete="off" 
            />
            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                <ChevronUpDownIcon class="size-5 text-gray-400" aria-hidden="true" />
            </ComboboxButton>

            <ComboboxOptions v-if="filteredOptions.length > 0" class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-gray-400 focus:outline-none sm:text-sm">
                <ComboboxOption v-for="person in filteredOptions" :key="person.id" :value="person" as="template" v-slot="{ active, selected }">
                    <li :class="['relative cursor-default select-none py-2 pl-3 pr-9', active ? 'bg-[#006FFD] text-white outline-none' : 'text-gray-900']">
                        <span :class="['block truncate', selected && 'font-semibold']">
                        {{ person.name }}
                        </span>

                        <span v-if="selected" :class="['absolute inset-y-0 right-0 flex items-center pr-4', active ? 'text-white' : 'text-[#006FFD]']">
                            <CheckIcon class="size-5" aria-hidden="true" />
                        </span>
                    </li>
                </ComboboxOption>
            </ComboboxOptions>
        </div>
    </Combobox>
    <p v-if="errorMessage" ref="div_ref" class="mt-2 text-sm text-red-600" id="email-error">{{ errorMessage }}</p>
    <p v-if="helperMessage" class="mt-2 text-sm text-gray-500" id="email-description">{{ helperMessage }}</p>
</template>
  
