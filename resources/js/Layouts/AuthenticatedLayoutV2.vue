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
    ];

    // Dynamically add 'Spouse' link if civil status is Married
    if (typeof page?.props?.auth?.user?.contact?.civil_status !== 'undefined' && page.props.auth.user.contact.civil_status === 'Married') {
        const employmentIndex = menus.findIndex(item => item.name === 'Employment');
        if (employmentIndex !== -1) {
            menus.splice(employmentIndex + 1, 0, { name: 'Spouse', href: route('spouse.edit'), current: route().current('spouse.edit'), route_name: 'spouse.edit' });
        }
    }

    // Dynamically add 'AIF' link if employment type is OFW
    const primaryEmployment = page?.props?.auth?.user?.contact?.employment?.find(item => item.type === 'Primary');
    if (primaryEmployment && primaryEmployment.employment_type === 'Overseas Filipino Worker (OFW)') {
        const coBorrowerIndex = menus.findIndex(item => item.name === 'Co-Borrower');
        if (coBorrowerIndex !== -1) {
            menus.splice(coBorrowerIndex, 0, { name: 'AIF', href: route('aif.edit'), current: route().current('aif.edit'), route_name: 'aif.edit' });
        }
    }

    return menus;
});

const emit = defineEmits(['trigger-exit', 'navigate-page']);

// Helper to determine if an avatar exists
const hasAvatar = computed(() => {
    return page?.props?.auth?.user?.avatar_url || page?.props?.auth?.user?.contact?.avatar;
});
</script>

<template>
    <div class="min-h-screen bg-gray-100 font-sans antialiased">
        <div class="mx-auto max-w-7xl lg:px-8">
            <div class="fixed top-0 left-0 w-full bg-white shadow-md z-10">
                <div class="px-4 py-3 flex items-center justify-between border-b border-gray-200 lg:px-8">
                    <div class="flex items-center gap-4">
                        <Link :href="route('dashboard')" class="flex items-center text-lg font-semibold text-gray-800 hover:text-blue-600 transition-colors duration-200">
                            <!-- <div class="w-6 h-6 mr-2 text-blue-600" fill="currentColor"></div> Logo Soon-->
                            <span class="sm:block">Customer Information Form</span>
                        </Link>
                    </div>

                    <div class="flex items-center sm:ms-6">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button
                                    type="button"
                                    class="inline-flex items-center rounded-full px-3 py-2 text-sm font-medium text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700"
                                >
                                    <img
                                        v-if="hasAvatar"
                                        :src="page.props.auth.user.avatar_url || page.props.auth.user.contact.avatar"
                                        alt="User Avatar"
                                        class="h-7 w-7 rounded-full object-cover mr-2 border border-gray-200"
                                    >
                                    <svg v-else class="w-6 h-6 text-gray-600 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                    <span class="hidden sm:block font-semibold text-gray-800">{{ page.props.auth.user.name || 'Guest' }}</span>
                                    <svg class="ms-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                <DropdownLink :href="route('consult-page')"> Consult Page </DropdownLink>
                                <div class="border-t border-gray-100 my-1"></div>
                                <button
                                    @click="emit('trigger-exit')"
                                    class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                >
                                    Log Out
                                </button>
                            </template>
                        </Dropdown>
                    </div>
                </div>

                <div class="px-4 py-2 border-t border-gray-200 lg:px-8 bg-gray-50">
                    <BreadCrumbs :pages="breadcrumbPages" @navigate-page="emit('navigate-page', $event)" />
                </div>
            </div>

            <main class="pt-40 pb-8 z-20"> 
                <slot />
            </main>
        </div>
    </div>
</template>