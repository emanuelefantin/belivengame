<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppGameHeader from '@/components/AppGameHeader.vue';
import AppShell from '@/components/AppShell.vue';
import BottomNavigationBtn from '@/components/game/BottomNavigationBtn.vue';
import GameSidebar from '@/components/GameSidebar.vue';
import { Toaster } from '@/components/ui/sonner';
import type { BreadcrumbItemType, NavItem } from '@/types';
import { Code, Handshake, Users } from 'lucide-vue-next';
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
        icon: Code,
    },
    {
        title: 'Sales',
        href: '/game/sales',
        icon: Handshake,
    },
    {
        title: 'HR',
        href: '/game/hr',
        icon: Users,
    },
];
</script>

<template>
    <Toaster />

    <AppShell variant="sidebar" class="flex h-screen justify-between">
        <GameSidebar />
        <AppContent variant="sidebar">
            <AppGameHeader :breadcrumbs="breadcrumbs" />
            <main class="mb-auto h-full overflow-y-auto"><slot /></main>
            <div class="">
                <div class="grid grid-cols-3 gap-1 text-center">
                    <BottomNavigationBtn v-for="item in mainNavItems" :key="item.title" :item="item" />
                </div>
            </div>
        </AppContent>
    </AppShell>
</template>
