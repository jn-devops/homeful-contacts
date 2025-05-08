<script setup>
import ViewAttachment from '@/Components/Modals/ViewAttachment.vue';
import SuccessToast from '@/Components/Notification/SuccessToast.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';


const props = defineProps({
    csrf_token: String,
    contact: Object,
    name: String,
    label: String,
    previewUrl: String,
    fileType: String,
});


const form = useForm({
    file: null,
    file_type: props.name,
    name: props.name,
})

const showAttachment = ref(false)
const toggleAttachment = () => {
    showAttachment.value = !showAttachment.value
}

const handleFileChange = (event) => {
    const file = event.target.files[0]
    if (file) {
        form.file = file
        runAfterFileAdded(file)
    }
}

const sucessMessage = ref('')

const runAfterFileAdded = (file) => {
    console.log('Running function after file added:', file.name)
    form.post(route('filepond-upload-file'),  {
        preserveScroll: true,
        onSuccess: () => {
            console.log('File uploaded successfully!', usePage().props.flash.data);
            sucessMessage.value = "Successfully uploaded a file"
        }
    })

}

const removeMedia = () => {
    form.delete(route('media.destroy'), {
        errorBag: 'removeMedia',
        preserveScroll: true,
        onSuccess: () => {
            sucessMessage.value = "Successfully deleted the file"
        },
    });
};

</script>

<template>
    <section>
        <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
        >
            <SuccessToast 
                v-if="form.recentlySuccessful"
                :message="sucessMessage"
            />
        </Transition>
        <div 
            class="flex flex-row w-full py-4 rounded-xl shadow-lg px-3"
            :class="{'bg-[#84A2FC]' : previewUrl, 'bg-green' : !previewUrl, }"
        >
            <div 
                class="basis-1/12 flex justify-center items-center"
            >
                <img class="w-[50px]" :src="usePage().props.data.appURL + '/images/docicon.svg'" alt="">
            </div>
            <div 
                class="basis-8/12 flex flex-col justify-center px-2"
                :class="{previewUrl : 'basis-7/12'}"
            >
                <h2 
                    class="font-bold"
                    :class="{'text-white' : previewUrl}"
                >{{ label }}</h2>
                <span class="text-sm"></span>
            </div>

            <!-- Condition for File Upload -->
            <div  v-if="previewUrl" class="flex flex-row justify-end items-center basis-3/12 gap-2">
                <div class="flex justify-center items-center cursor-pointer" @click="showAttachment = true">
                    <div class="bg-white rounded-full p-3 shadow-lg">
                        <svg class="w-6 h-6 text-[#0024F2]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M4.998 7.78C6.729 6.345 9.198 5 12 5c2.802 0 5.27 1.345 7.002 2.78a12.713 12.713 0 0 1 2.096 2.183c.253.344.465.682.618.997.14.286.284.658.284 1.04s-.145.754-.284 1.04a6.6 6.6 0 0 1-.618.997 12.712 12.712 0 0 1-2.096 2.183C17.271 17.655 14.802 19 12 19c-2.802 0-5.27-1.345-7.002-2.78a12.712 12.712 0 0 1-2.096-2.183 6.6 6.6 0 0 1-.618-.997C2.144 12.754 2 12.382 2 12s.145-.754.284-1.04c.153-.315.365-.653.618-.997A12.714 12.714 0 0 1 4.998 7.78ZM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div @click="removeMedia" class="flex justify-center items-center cursor-pointer">
                    <div class="bg-white rounded-full p-3 shadow-lg">
                        <svg class="w-6 h-6 text-red-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                        </svg>

                    </div>
                </div>
            </div>
            <template v-else>
                <label :for="name" class="basis-3/12 flex justify-end items-center cursor-pointer">
                    <div class="bg-white rounded-full p-3 shadow-lg">
                        <svg class="w-full h-full text-[#FCB115] " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12 3a1 1 0 0 1 .78.375l4 5a1 1 0 1 1-1.56 1.25L13 6.85V14a1 1 0 1 1-2 0V6.85L8.78 9.626a1 1 0 1 1-1.56-1.25l4-5A1 1 0 0 1 12 3ZM9 14v-1H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-4v1a3 3 0 1 1-6 0Zm8 2a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H17Z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </label>
            </template>

        </div>
        <input type="file" class="hidden" :id="name" @change="handleFileChange">

        <ViewAttachment 
            v-if="showAttachment"
            :name="label"
            :file="previewUrl"
            :type="fileType"
            @close="toggleAttachment"
        />
        
    </section>
</template>
