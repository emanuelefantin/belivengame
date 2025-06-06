<script setup lang="ts">
// import AppLayout from '@/layouts/AppLayout.vue';
// import AppLayout from '@/layouts/AppLayout.vue';

import AppLayout from '@/layouts/app/AppFullLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { SharedData } from '@/types';
import GameComponent from '@/components/game/Game.vue';
import { TyGame } from '@/types/belivengame';
import { useGameStore } from '@/stores/game';


const page = usePage<SharedData>();
const games:TyGame[] = <TyGame[]>page.props.games;

const form = useForm({
    name: null,
});

const gameStore = useGameStore();
gameStore.resetTimer();

</script>

<template>
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-2">
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                     <GameComponent v-for="game in games" :key="game.id" :item="game" />
                </div>
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border p-4">
                    <h2>Inizia una nuova partita</h2>

                    <div class="flex w-full max-w-sm items-center gap-1.5">
                        <form @submit.prevent="form.post('/api/game/new')">
                            <input type="text" v-model="form.name" />
                            <div v-if="form.errors.name">{{ form.errors.name }}</div>
                            <button type="submit" :disabled="form.processing">Crea</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                <PlaceholderPattern />sss
            </div>
        </div>
    </AppLayout>
</template>
