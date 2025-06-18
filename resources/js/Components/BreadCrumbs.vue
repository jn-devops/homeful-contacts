<script setup>
import { HomeIcon } from '@heroicons/vue/20/solid'; // This import is present but not used in the current template
import { Link, router, useForm } from '@inertiajs/vue3'; // These imports are present but not directly used in the current template for Link/router/useForm
import { onMounted, ref } from 'vue';
import axios from 'axios'; // Ensure axios is imported if you're using it

// Define component props
const props = defineProps({
    pages: {
        type: Object,
        required: true,
        // Example structure for 'pages' object:
        // pages: [
        //   { name: 'Personal', href: '#', route_name: 'some.route.name', current: true },
        //   { name: 'Employment', href: '#', route_name: 'another.route.name', current: false },
        //   { name: 'Docs Requirements', href: '#', route_name: 'docs.route.name', current: false },
        // ]
    },
});

// Reactive references for DOM elements and data
const divRefs = ref([]); // Used to store references to breadcrumb divs for scrolling
const total = ref(0); // Total number of attachments
const totalAccomplished = ref(0); // Number of uploaded attachments

// Lifecycle hook: runs after the component is mounted
onMounted(() => {
    // Fetch number of attachments from the backend
    // Assuming 'route('media.get_number_of_attachments')' is defined globally or via Inertia's Ziggy
    axios.get(route('media.get_number_of_attachments'))
        .then(response => {
            total.value = response.data.total;
            totalAccomplished.value = response.data.uploaded;
        })
        .catch(error => {
            console.error('Failed to get number of attachments:', error);
        });

    // Scroll to the current page's breadcrumb on mount
    for (let index = 0; index < props.pages.length; index++) {
        // Assuming 'route().current(props.pages[index].route_name)' checks the current Inertia route
        if (route().current(props.pages[index].route_name)) {
            const currentDiv = divRefs.value[props.pages[index].name];
            if (currentDiv) {
                currentDiv.scrollIntoView({
                    behavior: "smooth",
                    inline: 'center',
                    block: 'nearest'
                });
                currentDiv.focus(); // Focus on the element for accessibility
            }
        }
    }
});

// Define custom events that this component can emit
const emit = defineEmits(['navigate-page']);

// Function to emit navigation event
const navigateTo = (link) => {
    emit('navigate-page', link);
};
</script>

<template>
    <div class="py-1"> <div class="mx-auto w-full max-w-screen-xl px-1 py-1 sm:px-6 lg:px-8">
            <div class="flex space-x-2 p-1 rounded-lg bg-white shadow-sm border border-gray-200 overflow-x-auto scrollbar-hide">
                <div
                    v-for="(page, index) in pages"
                    :key="page.name"
                    @click="navigateTo(page.href)"
                    class="relative flex-shrink-0 flex items-center justify-center cursor-pointer
                           px-4 py-2 rounded-md whitespace-nowrap text-sm font-medium
                           transition-colors duration-200 ease-in-out"
                    :class="page.current ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900'"
                    :ref="el => (divRefs[page.name] = el)"
                >
                    <div
                        class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-semibold mr-2"
                        :class="page.current ? 'bg-white text-blue-600' : 'bg-gray-200 text-gray-600'"
                    >
                        {{ index + 1 }}
                    </div>

                    {{ page.name }}

                    <template v-if="page.name === 'Docs Requirements'">
                        <span
                            class="text-xs ml-1 opacity-80"
                            :class="page.current ? 'text-white' : 'text-gray-500'"
                        >
                            ({{ totalAccomplished }}/{{ total }})
                        </span>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Utility classes to hide the scrollbar across different browsers */
.scrollbar-hide::-webkit-scrollbar {
    display: none; /* For Chrome, Safari, and Opera */
}
.scrollbar-hide {
    -ms-overflow-style: none;  /* For Internet Explorer and Edge */
    scrollbar-width: none;  /* For Firefox */
}
</style>