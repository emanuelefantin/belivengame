<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupContent,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useGameStore } from '@/stores/game';
import { type NavItem } from '@/types';
import { Code, DoorOpenIcon, Handshake, Users } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

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

// const footerNavItems: NavItem[] = [
//     {
//         title: 'Dashboard',
//         href: '/dashboard',
//         icon: LayoutGrid,
//     },
// ];

const gameStore = useGameStore();

const saveAndExit = () => {
    gameStore.saveGame(true);
};
</script>

<template>
    <Sidebar collapsible="icon" variant="sidebar">
        <SidebarHeader class="px-8">
            <!-- <SidebarMenu> -->
            <!-- <SidebarMenuItem> -->
            <!-- <SidebarMenuButton size="lg" as-child> -->
            <!-- <Link :href="'#'"> -->
            <div class="flex h-12 w-full items-center justify-center">
                <AppLogo />
            </div>
            <!-- </Link> -->
            <!-- </SidebarMenuButton> -->
            <!-- </SidebarMenuItem> -->
            <!-- </SidebarMenu> -->
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <!-- <NavFooter :items="footerNavItems" /> -->
            <!-- <NavUser /> -->
            <SidebarGroup :class="`group-data-[collapsible=icon]:p-0 ${$props.class || ''}`">
                <SidebarGroupContent>
                    <SidebarMenu>
                        <SidebarMenuItem>
                            <SidebarMenuButton
                                class="text-neutral-600 hover:text-neutral-800 dark:text-neutral-300 dark:hover:text-neutral-100"
                                as-child
                                :tooltip="gameStore.gameEnd ? 'Torna alla dashboard':'Salva e torna alla dashboard'"
                            >
                                <a @click="saveAndExit()" rel="noopener noreferrer" class="cursor-pointer">
                                    <component :is="DoorOpenIcon" />
                                    <span>{{ gameStore.gameEnd ? 'Esci':'Salva e esci' }}</span>
                                </a>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroupContent>
            </SidebarGroup>
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
