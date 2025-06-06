<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppGameHeader from '@/components/AppGameHeader.vue';
import AppShell from '@/components/AppShell.vue';
import BottomNavigationBtn from '@/components/game/BottomNavigationBtn.vue';
import GameSidebar from '@/components/GameSidebar.vue';
import { Toaster } from '@/components/ui/sonner';
import type { BreadcrumbItemType, NavItem } from '@/types';
import { router } from '@inertiajs/vue3';
import { LayoutGrid } from 'lucide-vue-next';
import 'vue-sonner/style.css'; // vue-sonner v2 requires this import

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const mainNavItems: NavItem[] = [
    {
        title: 'Produzione',
        href: '/game/production',
        icon: LayoutGrid,
    },
    {
        title: 'Sales',
        href: '/game/sales',
        icon: LayoutGrid,
    },
    {
        title: 'HR',
        href: '/game/hr',
        icon: LayoutGrid,
    },
];
</script>

<template>
    <Toaster />

    <AppShell variant="sidebar" class="flex h-screen justify-between">
        <GameSidebar />
        <AppContent variant="sidebar">
            <AppGameHeader :breadcrumbs="breadcrumbs" />

            <!-- <div class="flex h-full flex-col">
                <div class="h-1/10"><div class=""><AppGameHeader :breadcrumbs="breadcrumbs" /></div></div>
                <div class="h-8/10"><slot /></div>
                <div class="h-1/10">
                    <div class="grid grid-cols-3 gap-1 text-center">
                        <div class="cursor-pointer border p-4" @click="router.get('/game/production')">Produzione</div>
                        <div class="cursor-pointer border p-4" @click="router.get('/game/sales')">Sales</div>
                        <div class="cursor-pointer border p-4" @click="router.get('/game/hr')">Hr</div>
                    </div>
                </div>
            </div> -->

            <main class="mb-auto h-full overflow-y-auto"><slot /></main>
            <div class="">
                <div class="grid grid-cols-3 gap-1 text-center">
                    <BottomNavigationBtn v-for="item in mainNavItems" :key="item.title" :item="item" />
                </div>
            </div>
        </AppContent>
    </AppShell>
</template>
