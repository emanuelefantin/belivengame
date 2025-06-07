<script setup lang="ts">
// import AppLayout from '@/layouts/AppLayout.vue';
// import AppLayout from '@/layouts/AppLayout.vue';

import GameComponent from '@/components/game/Game.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/app/AppFullLayout.vue';
import { useGameStore } from '@/stores/game';
import { SharedData } from '@/types';
import { TyGame } from '@/types/belivengame';
import { useForm, usePage } from '@inertiajs/vue3';
// import logo from '@/assets/belivengame-logo.png';

const page = usePage<SharedData>();
const games: TyGame[] = <TyGame[]>page.props.games;

const form = useForm({
    name: null,
});

const gameStore = useGameStore();
gameStore.resetTimer();
</script>

<template>
    <AppLayout>
        <div class="grid w-full">
            <div class="grid w-full grid-cols-2" style="height: calc(100vh - 1px)">
                <div class="flex grid h-full w-full flex-col overflow-hidden overflow-y-auto rounded-xl p-4 ">
                    <div class="self-center justify-self-center text-center">
                        <img src="./../../assets/beliven-game-logo.png" alt="Belivengame Logo" class="mb-10 w-80 justify-self-center" />
                        <h2 class="pb-5 text-xl font-bold text-gray-500">Inizia una nuova partita</h2>

                        <div class="flex gap-1.5 justify-self-center">
                            <form @submit.prevent="form.post('/api/game/new')">
                                <div class="flex gap-1.5">
                                    <Input id="email" type="text" v-model="form.name" />
                                    <Button type="submit" :disabled="form.processing"> Crea </Button>
                                </div>
                            </form>
                        </div>

                        <div class="mt-8 text-muted-foreground">
                            Per iniziare una nuova partita, inserisci il nome della tua azienda e premi il pulsante "Crea".
                        </div>
                    </div>
                </div>
                <div class="border-l">
                    <div class="h-10 flex items-center justify-center bg-primary text-white">
                        <h2 class="text-center text-xl font-bold ">Partite in corso</h2>
                    </div>
                    <div class="grid w-full" style="height: calc(100vh - 42px)">
                        <div class="scrollbar grid flex h-full w-full flex-col overflow-hidden overflow-y-auto ">
                            
                            <div class="self-center justify-self-center text-center" v-if="games.length === 0">
                                Nessuna partita salvata...
                                <br>
                                Inizia una nuova partita!
                            </div>

                            <GameComponent v-for="game in games" :key="game.id" :item="game" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
