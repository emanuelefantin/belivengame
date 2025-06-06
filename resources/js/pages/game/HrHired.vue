<script setup lang="ts">
import DeveloperHrTpl from '@/components/game/hr/DeveloperHrTpl.vue';
import SellerHrTpl from '@/components/game/hr/SellerHrTpl.vue';
import Button from '@/components/ui/button/Button.vue';
import { ScrollArea } from '@/components/ui/scroll-area';
import AppLayout from '@/layouts/app/AppGameLayout.vue';
import { useGameStore } from '@/stores/game';
import { SharedData, type BreadcrumbItem } from '@/types';
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
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-2">
                <div>
                    <h1 class="text-xl font-semibold">Il tuo team</h1>
                    <p class="text-muted-foreground">Commerciali e sviluppatori che stanno lavorando per te</p>
                </div>
                <div class="flex items-center justify-end">
                    <Button
                        class="btn btn-primary"
                        @click="router.get('/game/hr')"
                    >
                        Scopri nuovi talenti
                    </Button>

                </div>
            </div>
            <div class="grid auto-rows-min gap-4 md:grid-cols-2">
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                    <div class="flex h-10 items-center justify-between border-b p-4">
                        <h2 class="text-lg font-semibold">Commerciali</h2>
                        <p class="text-muted-foreground">{{ sellers.length }} disponibili</p>
                    </div>
                    <ScrollArea class="h-99 w-full grid-cols-4">
                        <SellerHrTpl v-for="person in sellers" :key="person.id" :item="person" />
                    </ScrollArea>
                </div>
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                    <div class="flex h-10 items-center justify-between border-b p-4">
                        <h2 class="text-lg font-semibold">Sviluppatori</h2>
                        <p class="text-muted-foreground">{{ developers.length }} disponibili</p>
                    </div>
                    <ScrollArea class="h-99 w-full grid-cols-4">
                        <DeveloperHrTpl v-for="person in developers" :key="person.id" :item="person" />
                    </ScrollArea>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
