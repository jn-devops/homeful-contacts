<script setup>
    import { HomeIcon } from '@heroicons/vue/20/solid'
import { Link, router } from '@inertiajs/vue3';
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

    const emit = defineEmits(['navigate-page'])

    const navigateTo = (link) => {
        emit('navigate-page', link)
    }
</script>

<template>
    <div class="border-b border-gray-200 bg-white">
        <div class="mx-auto flex w-full max-w-screen-xl space-x-4 sm:px-6 lg:px-8 overflow-x-auto scrollbar-hide">
            <div class="flex">
                <div
                    v-for="page in pages"
                    :key="page.name"
                    @click="navigateTo(page.href)"
                    class="relative py-3 w-fit h-[50px] ps-9 pe-4 flex items-center justify-center cursor-pointer"
                    :class="page.current ? 'bg-[#006FFD] text-white font-bold' : 'bg-transparent text-gray-500'"
                    :ref="el => (divRefs[page.name] = el)"
                >
                    <div class="text-sm hover:font-semibold z-30 whitespace-nowrap leading-none">
                        {{ page.name }}
                        <br>
                        <!-- <template v-if="page.name == 'Docs Requirements'">
                            <span class="text-xs ">{{ totalAccomplished }}/{{ totalNumber }}</span>
                        </template> -->
                    </div>
                    <div
                        class="absolute z-20 border-t border-r border-r-gray-200 border-t-gray-200 right-[-16px] w-[50px] h-[50px] rotate-45"
                        :class="page.current ? 'bg-[#006FFD]' : 'bg-white'"
                        
                    ></div>
                </div>
            </div>
        </div>
    </div>
    
</template>