<script setup lang="ts">
import Developer from '@/components/game/DeveloperTpl.vue';
import AppLayout from '@/layouts/app/AppGameLayout.vue';
import { useGameStore } from '@/stores/game';
import { SharedData } from '@/types';
import { TyGame } from '@/types/belivengame';
import { Head, usePage } from '@inertiajs/vue3';

const page = usePage<SharedData>();
const game: TyGame = <TyGame>page.props.game;
const init_game: boolean = <boolean>page.props.init_game;

const gameStore = useGameStore();
//inizializza il gioco
if (init_game) {
    gameStore.setStartData(game);
}
gameStore.initGame();
</script>

<template>
    <Head title="Production" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video h-full items-stretch overflow-hidden rounded-xl border"
                >
                    <div class="flex h-screen flex-col bg-gray-500">
                        <div class="flex h-32 bg-gray-200">ssss</div>
                        <div class="mx-auto h-full w-2/3 flex-1 overflow-y-auto bg-gray-300 bg-white p-4 text-lg shadow-lg">
                            <Developer v-for="developer in game.developers" :key="developer.id" :item="developer" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
