<script setup>
    import { HomeIcon } from '@heroicons/vue/20/solid'
import { Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

    const props = defineProps({
        pages: Object,
    });

    const divRefs = ref([]);

    onMounted(() => {
        for (let index = 0; index < props.pages.length; index++) {
            if(route().current(props.pages[index].route_name)){
                const currentDiv = divRefs.value[props.pages[index].name];
                if (currentDiv) {
                    currentDiv.scrollIntoView({
                    behavior: "smooth",
                    inline: 'center', 
                    block: 'nearest'
                    });
                    currentDiv.focus();
                }
                
            }
        }
    })

</script>

<template>
    <nav class="flex border-b border-gray-200 bg-white" aria-label="Breadcrumb">
        <ol role="list" class="mx-auto ps-5 flex w-full max-w-screen-xl space-x-4 px-1 sm:px-6 lg:px-8 overflow-x-auto scrollbar-hide">
            <li v-for="page in pages" :key="page.name" class="flex flex-shrink-0" :ref="el => (divRefs[page.name] = el)">
                <div class="flex items-center">
                    <Link :href="page.href" preserve-scroll class="ps-3 pe-5 text-sm font-medium hover:text-[#006FFD] hover:font-semibold" :class="page.current ? 'text-[#006FFD] font-semibold' : 'text-gray-500'" :aria-current="page.current ? 'page' : undefined">{{ page.name }}</Link>
                    <svg class="h-full w-6 shrink-0 text-gray-200" viewBox="0 0 24 44" preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
                        <path d="M.293 0l22 22-22 22h1.414l22-22-22-22H.293z" />
                    </svg>
                </div>
            </li>
        </ol>
    </nav>
</template>