<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Impostazioni grafiche',
        href: '/settings/appearance',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Impostazioni grafiche" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Impostazioni grafiche" description="Aggiorna le impostazioni grafiche del tuo account" />
                <AppearanceTabs />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
