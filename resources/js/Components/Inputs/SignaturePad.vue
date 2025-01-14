<script setup lang='ts'>
import { onMounted, ref } from 'vue'
import { VueSignaturePad } from '@selemondev/vue3-signature-pad'
import type { CanvasSignature } from '@selemondev/vue3-signature-pad'
import { watch } from 'vue';
import SecondaryButton from '../Buttons/SecondaryButton.vue';
import SecondaryDismissButton from '../Buttons/SecondaryDismissButton.vue';

const props = defineProps({
    signatureVal: {
        type: String,
        default: ''
    },
});

const signatureValue = ref(props.signatureVal);

const options = ref({
  penColor: 'rgb(0,0,0)',
  backgroundColor: 'rgb(255, 255, 255)',
  maxWidth: 2,
  minWidth: 2,
})

const colors = [
  {
    color: 'rgb(51, 133, 255)',
  },
  {
    color: 'rgb(85, 255, 51)',
  },
  {
    color: 'rgb(255, 85, 51)',
  },
]
const signature = ref<Signature>()

function handleUndo() {
  return signature.value?.undo()
}

function handleClearCanvas() {
  return signature.value?.clearCanvas()
}

function handleSaveSignature() {
  return signature.value?.saveSignature() && alert(signature.value?.saveSignature())
}

const emit = defineEmits(['update', 'close']);

function saveSignature() {
  emit('update', [signature.value?.saveSignature()]);
}

</script>

<template>
  <div class='flex flex-col space-y-2'>
    <div class='p-4 bg-white rounded-md'>
      <div class='relative bg-gray-100 rounded-md'>
        <VueSignaturePad
          ref='signature'
          height='250px'
          width='100%'
          :max-width='options.maxWidth'
          :min-width='options.minWidth'
          :options='{
            penColor: options.penColor,
            backgroundColor: options.backgroundColor,
          }'
        />

        <div class='absolute flex flex-col space-y-2 top-3 right-4'>
          <button
            type='button'
            class='grid p-2 bg-white rounded-md shadow-md place-items-center'
            @click='handleUndo'
          >
            <svg
              xmlns='http://www.w3.org/2000/svg'
              width='20'
              height='20'
              viewBox='0 0 24 24'
            >
              <path
                fill='none'
                stroke='#000'
                stroke-linecap='round'
                stroke-linejoin='round'
                stroke-width='2'
                d='M10 8H5V3m.291 13.357a8 8 0 1 0 .188-8.991'
              />
            </svg>
          </button>
          <!-- <button
            type='button'
            class='grid p-2 bg-white rounded-md shadow-md place-items-center'
            @click='handleClearCanvas'
          >
            <svg
              xmlns='http://www.w3.org/2000/svg'
              width='20'
              height='20'
              viewBox='0 0 14 14'
            >
              <path
                fill='none'
                stroke='#000'
                stroke-linecap='round'
                stroke-linejoin='round'
                d='M11.5 8.5h-9l-.76 3.8a1 1 0 0 0 .21.83a1 1 0 0 0 .77.37h8.56a1.002 1.002 0 0 0 .77-.37a1.001 1.001 0 0 0 .21-.83zm0-3a1 1 0 0 1 1 1v2h-11v-2a1 1 0 0 1 1-1H4a1 1 0 0 0 1-1v-2a2 2 0 1 1 4 0v2a1 1 0 0 0 1 1zm-3 8V11'
              />
            </svg>
          </button>
          <button
            type='button'
            class='grid p-2 bg-white rounded-md shadow-md place-items-center'
            @click='handleSaveSignature'
          >
            <svg
              xmlns='http://www.w3.org/2000/svg'
              width='20'
              height='20'
              viewBox='0 0 24 24'
            >
              <path
                fill='#000'
                d='M21 7v14H3V3h14zm-2 .85L16.15 5H5v14h14zM12 18q1.25 0 2.125-.875T15 15t-.875-2.125T12 12t-2.125.875T9 15t.875 2.125T12 18m-6-8h9V6H6zM5 7.85V19V5z'
              />
            </svg>
          </button> -->
        </div>
      </div>
    </div>
    <div class="flex items-center justify-center">
      <SecondaryButton @click="saveSignature">
          <div class="flex flex-row items-center gap-1">
              <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                  <path fill-rule="evenodd" d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7.414A2 2 0 0 0 20.414 6L18 3.586A2 2 0 0 0 16.586 3H5Zm3 11a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v6H8v-6Zm1-7V5h6v2a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                  <path fill-rule="evenodd" d="M14 17h-4v-2h4v2Z" clip-rule="evenodd"/>
              </svg>
              <p class="text-white font-bold text-center">
                  Save and Close
              </p>
          </div>
      </SecondaryButton>
      <SecondaryDismissButton @click="$emit('close')">
          <div class="flex flex-row items-center gap-1">
              <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                  <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm7.707-3.707a1 1 0 0 0-1.414 1.414L10.586 12l-2.293 2.293a1 1 0 1 0 1.414 1.414L12 13.414l2.293 2.293a1 1 0 0 0 1.414-1.414L13.414 12l2.293-2.293a1 1 0 0 0-1.414-1.414L12 10.586 9.707 8.293Z" clip-rule="evenodd"/>
              </svg>

              <p class="text-white font-bold text-center">
                  Close Modal
              </p>
          </div>
      </SecondaryDismissButton>
  </div>
  </div>
</template>