<script setup>
import BreadCrumbs from '@/Components/BreadCrumbs.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const breadcrumbPages = computed(() => {
    let menus = [
            { name: 'Personal', href: route('personal.edit'), current: route().current('personal.edit'), route_name: 'personal.edit' },
            { name: 'Address', href: route('address.edit'), current: route().current('address.edit'), route_name: 'address.edit' },
            { name: 'Employment', href: route('employment.edit'), current: route().current('employment.edit'), route_name: 'employment.edit' },
            { name: 'Co-Borrower', href: route('co_borrower.edit'), current: route().current('co_borrower.edit'), route_name: 'co_borrower.edit' },
            { name: 'Docs Requirements', href: route('media.edit'), current: route().current('media.edit'), route_name: 'media.edit' },
        ]

    if(typeof page?.props?.auth?.user?.contact?.civil_status !== 'undefined'){
        if(page.props.auth.user.contact.civil_status == "Married"){
            const index = menus.findIndex(item => item.name === 'Employment');
            if (index !== -1) {
                menus.splice(index + 1, 0, { name: 'Spouse', href: route('spouse.edit'), current: route().current('spouse.edit'), route_name: 'spouse.edit' });
            }
        }
    }
    if(typeof page?.props?.auth?.user?.contact?.employment?.find(item => item.type == 'Primary')?.employment_type !== 'undefined'){
        if(page.props.auth.user.contact.employment.find(item => item.type == 'Primary').employment_type == "Overseas Filipino Worker (OFW)"){
            const index = menus.findIndex(item => item.name === 'Co-Borrower');
            if (index !== -1) {
                menus.splice(index, 0, { name: 'AIF', href: route('aif.edit'), current: route().current('aif.edit'), route_name: 'aif.edit' },);
            }
        }
    }

    return menus
})

const emit = defineEmits(['trigger-exit', 'navigate-page'])

</script>

<template>
    <div class="min-h-screen bg-gray-100 ">
        <div class="mx-auto max-w-7xl lg:px-8">
            <div class="fixed top-0 left-0 w-full bg-gray-100 z-10">
                <div class="px-4 py-2 text-sm border-b-2 flex">
                    <button class="basis-3/4" @click="emit('trigger-exit')">
                        <div class="flex flex-row items-center gap-3 cursor-pointer">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="4" stroke="currentColor" class="size-4 text-[#006FFD]">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                                </svg>
                            </div>
                            <h4 class="font-bold">Logout</h4>
                        </div>
                    </button>
                    <div class="basis-1/4 flex flex-row-reverse">
                        <div class="sm:ms-6 sm:flex sm:items-center">
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-full w-full border border-transparent px-2 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-300"
                                            >
                                            <svg class="w-7 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                            </svg>

                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('consult-page')"
                                        >
                                            Consult Page
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <BreadCrumbs :pages="breadcrumbPages" @navigate-page="emit('navigate-page', $event)" />
                </div>
            </div>

            <!-- Page Content -->
            <main class="pt-28">
                <slot />
            </main>
        </div>
    </div>
</template>
