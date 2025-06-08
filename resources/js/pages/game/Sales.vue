<script setup lang="ts">
import Project from '@/components/game/sales/Project.vue';
import SellerSaleTpl from '@/components/game/sales/SellerSaleTpl.vue';
import AppLayout from '@/layouts/app/AppGameLayout.vue';
import { useGameStore } from '@/stores/game';
import { SharedData } from '@/types';
import { TySeller } from '@/types/belivengame';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage<SharedData>();
const sellers: TySeller[] = <TySeller[]>page.props.sellers;

const gameStore = useGameStore();
gameStore.initGame();

const activeProjects = computed(() => {
    return gameStore.activeProjects.filter((project) => project.generation_completed !== true);
});
</script>

<template>
    <Head title="Sales" />

    <AppLayout>
        <div class="flex flex-col" style="height: calc(100vh - 125px)">
            <div class="p-4">
                <div class="grid auto-rows-min md:grid-cols-2">
                    <div>
                        <h1 class="text-xl font-semibold">Progetti</h1>
                        <p class="text-muted-foreground">Crea appuntamenti per i tuoi venditori e porta a casa nuovi contratti!</p>
                    </div>
                    <div class="flex items-center justify-end">
                        <!-- <Button class="btn btn-primary" @click="router.get('/game/hr')"> Scopri nuovi talenti </Button> -->
                    </div>
                </div>
            </div>
            <div class="flex grid flex-grow grid-cols-2">
                <div>
                    <div class="flex flex-col" style="height: calc(100vh - 220px)">
                        <div class="bg-primary/40">
                            <div class="grid auto-rows-min gap-4">
                                <div class="flex h-10 items-center justify-between p-4">
                                    <h2 class="text-lg font-semibold">Commerciali</h2>
                                    <p class="text-muted-foreground">{{ sellers.length }} nel tuo team</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex w-full flex-grow overflow-hidden">
                            <div class="scrollbar flex h-full w-full flex-col overflow-hidden overflow-y-auto rounded-xl p-4">
                                <SellerSaleTpl v-for="seller in sellers" :key="seller.id" :item="seller" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-sidebar-border/70 dark:border-sidebar-border border-l">
                    <div class="flex flex-col" style="height: calc(100vh - 220px)">
                        <div class="bg-primary/40">
                            <div class="grid auto-rows-min gap-4">
                                <div class="flex h-10 items-center justify-between p-4">
                                    <h2 class="text-lg font-semibold">Progetti in corso</h2>
                                    <p class="text-muted-foreground">{{ activeProjects.length }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex w-full flex-grow overflow-hidden">
                            <div class="scrollbar flex h-full w-full flex-col overflow-hidden overflow-y-auto rounded-xl p-4">
                                <Project v-for="project in activeProjects" :key="project.id" :item="project" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
