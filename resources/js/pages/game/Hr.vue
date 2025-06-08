<script setup lang="ts">
import SellerHrTpl from '@/components/game/hr/SellerHrTpl.vue';
import DeveloperHrTpl from '@/components/game/hr/DeveloperHrTpl.vue';
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/app/AppGameLayout.vue';
import { useGameStore } from '@/stores/game';
import { SharedData } from '@/types';
import { TyDeveloper, TyGame, TySeller } from '@/types/belivengame';
import { Head, router, usePage } from '@inertiajs/vue3';

const page = usePage<SharedData>();
const game: TyGame = <TyGame>page.props.game;
const developers: TyDeveloper[] = <TyDeveloper[]>page.props.developers;
const sellers: TySeller[] = <TySeller[]>page.props.sellers;

const gameStore = useGameStore();
gameStore.initGame();
</script>

<template>
    <Head title="Hr" />

    <AppLayout>
        <div class="flex flex-col" style="height: calc(100vh - 125px)">
            <div class="p-4">
                <div class="grid auto-rows-min  md:grid-cols-2">
                    <div>
                        <h1 class="text-xl font-semibold">Gestione Risorse Umane</h1>
                        <p class="text-muted-foreground">Trova i talenti migliori</p>
                    </div>
                    <div class="flex items-center justify-end">
                        <Button class="btn btn-primary" @click="router.get('/game/hr/hired')"> Il tuo team </Button>
                    </div>
                </div>
            </div>
            <div class="flex grid flex-grow grid-cols-2">
                <div>
                    <div class="flex flex-col" style="height: calc(100vh - 220px)">
                        <div class="bg-primary/40 ">
                            <div class="grid auto-rows-min gap-4">
                                <div class="flex h-10 items-center justify-between p-4">
                                    <h2 class="text-lg font-semibold">Commerciali</h2>
                                    <p class="text-muted-foreground">{{ sellers.length }} disponibili</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex w-full flex-grow overflow-hidden">
                            <div class="scrollbar flex h-full w-full flex-col overflow-hidden overflow-y-auto rounded-xl p-4">
                                <SellerHrTpl v-for="person in sellers" :key="person.id" :item="person" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-l border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="flex flex-col" style="height: calc(100vh - 220px)">
                        <div class="bg-primary/40 ">
                            <div class="grid auto-rows-min gap-4">
                                <div class="flex h-10 items-center justify-between p-4">
                                    <h2 class="text-lg font-semibold">Sviluppatori</h2>
                                    <p class="text-muted-foreground">{{ developers.length }} disponibili</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex w-full flex-grow overflow-hidden">
                            <div class="scrollbar flex h-full w-full flex-col overflow-hidden overflow-y-auto rounded-xl p-4">
                        <DeveloperHrTpl v-for="person in developers" :key="person.id" :item="person" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
