<script setup lang="ts">
import Project from '@/components/game/sales/Project.vue';
import SellerSaleTpl from '@/components/game/sales/SellerSaleTpl.vue';
import AppLayout from '@/layouts/app/AppGameLayout.vue';
import { useGameStore } from '@/stores/game';
import { SharedData } from '@/types';
import { TyGame, TySeller } from '@/types/belivengame';
import { Head, usePage } from '@inertiajs/vue3';

// {{ page.props.user_count }}

const page = usePage<SharedData>();
const sellers: TySeller[] = <TySeller[]>page.props.sellers;

const gameStore = useGameStore();
gameStore.initGame();
</script>

<template>
    <Head title="Sales" />
    
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-2">
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative overflow-hidden rounded-xl border">
                    <SellerSaleTpl
                        v-for="seller in sellers"
                        :key="seller.id"
                        :item="seller"
                    />
                </div>
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative  overflow-hidden rounded-xl border">
                    <Project
                        v-for="project in gameStore.activeProjects.filter((project) => project.generation_completed !== true)"
                        :key="project.id"
                        :item="project"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
