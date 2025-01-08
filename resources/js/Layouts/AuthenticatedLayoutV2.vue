<script setup>
import BreadCrumbs from '@/Components/BreadCrumbs.vue';

const breadcrumbPages = [
    { name: 'Personal', href: route('personal.edit'), current: route().current('personal.edit') },
    { name: 'Address', href: route('address.edit'), current: route().current('address.edit') },
    { name: 'Employment', href: route('employment.edit'), current: route().current('employment.edit') },
    { name: 'Spouse', href: route('spouse.edit'), current: route().current('spouse.edit') },
    { name: 'AIF', href: route('aif.edit'), current: route().current('aif.edit') },
    { name: 'Co-Borrower', href: route('co_borrower.edit'), current: route().current('co_borrower.edit') },
    { name: 'Attachment', href: route('media.edit'), current: route().current('media.edit') },
]

</script>

<template>
    <div class="min-h-screen bg-gray-100 ">
        <div class="mx-auto max-w-7xl lg:px-8">
            <div class="px-4 py-5 text-sm border-b-2">
                <div class="flex flex-row items-center gap-3 cursor-pointer">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="4" stroke="currentColor" class="size-4 text-[#CC035C]">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                    </div>
                    <h4 class="font-bold">Home Loan Consultation</h4>
                </div>
            </div>
            <div>
                <BreadCrumbs :pages="breadcrumbPages" />
            </div>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>