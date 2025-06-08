<script setup lang="ts">
// import AppLayout from '@/layouts/AppLayout.vue';
import GameComponent from '@/components/game/Game.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/app/AppFullLayout.vue';
import { useGameStore } from '@/stores/game';
import { SharedData } from '@/types';
import { TyGame } from '@/types/belivengame';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import { ArrowLeft, LogOut, Settings } from 'lucide-vue-next';

const page = usePage<SharedData>();
const games: TyGame[] = <TyGame[]>page.props.games;

const form = useForm({
    name: null,
});

const handleLogout = () => {
    router.flushAll();
};

const gameStore = useGameStore();
gameStore.resetTimer();
</script>

<template>
    <AppLayout>
        <div class="grid w-full">
            <div class="grid w-full grid-cols-2" style="height: calc(100vh - 1px)">
                <div class="flex grid h-full w-full flex-col overflow-hidden overflow-y-auto rounded-xl p-4">
                    <div class="self-center justify-self-center text-center">
                        <img src="./../../assets/beliven-game-logo.png" alt="Belivengame Logo" class="mb-10 w-80 justify-self-center" />
                        <h2 class="pb-5 text-xl font-bold text-gray-500">Inizia una nuova partita</h2>

                        <div class="flex gap-1.5 justify-self-center">
                            <form @submit.prevent="form.post('/api/game/new')">
                                <div class="flex gap-1.5">
                                    <Input id="email" type="text" v-model="form.name" placeholder="Nome azienda" />
                                    <Button type="submit" :disabled="form.processing"> Crea </Button>
                                </div>
                            </form>
                        </div>

                        <div class="text-muted-foreground mt-8">
                            Per iniziare una nuova partita, inserisci il nome della tua azienda e premi il pulsante "Crea".
                        </div>
                        <div class="mt-15">
                            <Link class="mr-10" :href="route('profile.edit')" prefetch as="button">
                                <Settings class="mr-2 inline h-4 w-4" />
                                Impostazioni
                            </Link>
                            <Link class="" method="post" :href="route('logout')" @click="handleLogout" as="button">
                                <LogOut class="mr-2 inline h-4 w-4" />
                                Log out
                            </Link>
                        </div>
                    </div>
                </div>
                <div class="border-l">
                    <div class="bg-primary flex h-10 items-center justify-center text-white">
                        <h2 class="text-center text-xl font-bold">Partite salvate</h2>
                    </div>
                    <div class="flex grid h-full w-full flex-col" style="height: calc(100vh - 42px)">
                        <div class="scrollbar flex grid h-full w-full flex-col overflow-y-auto">
                            <div class="self-center justify-self-center text-center" v-if="games.length === 0">
                                <!-- <div class="self-center justify-self-center text-center">as</div> -->
                                Nessuna partita salvata...
                                <br />
                                <ArrowLeft class="mr-2 inline h-4 w-4" /> Inizia una nuova partita!
                            </div>

                            <GameComponent v-for="game in games" :key="game.id" :item="game" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
