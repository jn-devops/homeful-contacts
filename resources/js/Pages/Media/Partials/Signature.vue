<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import SignaturePad from '@/Components/Inputs/SignaturePad.vue';
import CenterModal from '@/Components/Modals/CenterModal.vue';
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue';
import SecondaryDismissButton from '@/Components/Buttons/SecondaryDismissButton.vue';

const props = defineProps({
    contact: Object,
});

const signature = ref(null);
const isSignaturePadOpen = ref(false);

const updateSignature = ([signatureVal]) => {
    signature.value = signatureVal;
    close();
};

const close = () => {isSignaturePadOpen.value = !isSignaturePadOpen.value;}

</script>
<template>
    <div>
        <div v-if="signature" @click="isSignaturePadOpen = !isSignaturePadOpen" class="cursor-pointer">
            <img :src="signature" alt="">
        </div>
        <div v-else>
            <SecondaryButton @click="isSignaturePadOpen = !isSignaturePadOpen" customClass="w-auto">
                <div class="flex flex-row items-center gap-1">
                    <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M15.514 3.293a1 1 0 0 0-1.415 0L12.151 5.24a.93.93 0 0 1 .056.052l6.5 6.5a.97.97 0 0 1 .052.056L20.707 9.9a1 1 0 0 0 0-1.415l-5.193-5.193ZM7.004 8.27l3.892-1.46 6.293 6.293-1.46 3.893a1 1 0 0 1-.603.591l-9.494 3.355a1 1 0 0 1-.98-.18l6.452-6.453a1 1 0 0 0-1.414-1.414l-6.453 6.452a1 1 0 0 1-.18-.98l3.355-9.494a1 1 0 0 1 .591-.603Z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-white font-bold text-center">
                        Add Signature
                    </p>
                </div>
            </SecondaryButton>
        </div>
        <!-- Signature Modal -->
        <CenterModal :isOpen="isSignaturePadOpen" @update:isOpen="isSignaturePadOpen = $event">
            <div>
                <SignaturePad 
                    :signatureVal="signature" 
                    @update="updateSignature"  
                    @close="close"  
                />
            </div>
        </CenterModal>

    </div>
</template>