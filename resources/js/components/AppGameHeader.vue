<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { useGameStore } from '@/stores/game';
import type { BreadcrumbItemType, SharedData } from '@/types';
import { TyGame } from '@/types/belivengame';
import { usePage } from '@inertiajs/vue3';
import { reactive, ref, watch } from 'vue';
import HeaderTimer from './game/HeaderTimer.vue';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const page = usePage<SharedData>();

const globalTimer = useGameStore();
</script>

<template>
    <header
        class="border-sidebar-border/70 flex h-16 shrink-0 items-center gap-2 border-b px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex grid w-full grid-cols-2">
            <div class="flex items-center gap-2">
                <SidebarTrigger class="-ml-1" />
                <!-- {{ globalTimer.elapsedTime }} -->
                <span class="text-sidebar-text text-lg font-semibold">{{ page.props.game.name }}</span>
            </div>
            <!-- <div> -->
                <!-- <template v-if="breadcrumbs && breadcrumbs.length > 0">
                    <Breadcrumbs :breadcrumbs="breadcrumbs" />
                </template> -->
            <!-- </div> -->
            <div class="items-center justify-end">
                <HeaderTimer :date_current="page.props.game.date_current" />
            </div>
        </div>
    </header>
</template>
