<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { formatMoney } from '@/lib/utils';
import { useGameStore } from '@/stores/game';
import { router } from '@inertiajs/vue3';
import { useEcho, useEchoPresence } from '@laravel/echo-vue';
import { CalendarDays, ChevronRightIcon, ChevronsRightIcon, FastForwardIcon, PauseIcon } from 'lucide-vue-next';

const gameStore = useGameStore();
gameStore.initGame();

useEcho(`game.${gameStore.game.id}`, 'GameUpdated', (e: {}) => {
    gameStore.updateFromServer(e);
});

</script>

<template>
    <div class="grid auto-rows-min gap-4">
        <div class="relative p-4 text-right">
            <div class="grid grid-cols-4 items-center gap-1 text-center">
                <div class="relative p-1">Patrimonio {{ formatMoney(gameStore.cashCurrent) }}</div>
                <div class="relative p-1">
                    <!-- {{ gameStore.elapsedTime }} Giorni -->
                    Spese/mese {{ formatMoney(gameStore.cashMonthExpenses) }}
                </div>
                <div class="relative p-1">
                    <CalendarDays class="mr-2 inline h-4 w-4" />
                    {{ gameStore.dateCurrentFormatted }}
                </div>
                <div class="relative p-1" v-if="!gameStore.gameEnd">
                    <div
                        class="inline h-10 w-10 cursor-pointer border p-1"
                        @click="gameStore.setVelocity(0)"
                        :class="gameStore.velocity == 0 ? 'bg-primary/10' : ''"
                    >
                        <PauseIcon class="inline" />
                    </div>
                    <div
                        class="inline h-10 w-10 cursor-pointer border p-1"
                        @click="gameStore.setVelocity(0.5)"
                        :class="gameStore.velocity == 0.5 ? 'bg-primary/40' : ''"
                    >
                        <ChevronRightIcon class="inline" />
                    </div>
                    <div
                        class="inline aspect-square h-10 w-10 cursor-pointer border p-1"
                        @click="gameStore.setVelocity(1)"
                        :class="gameStore.velocity == 1 ? 'bg-primary/70' : ''"
                    >
                        <ChevronsRightIcon class="inline" />
                    </div>
                    <div
                        class="inline aspect-square h-10 w-10 cursor-pointer border p-1"
                        @click="gameStore.setVelocity(5)"
                        :class="gameStore.velocity == 5 ? 'bg-primary' : ''"
                    >
                        <FastForwardIcon class="inline" />
                    </div>
                </div>
            </div>
        </div>

        <AlertDialog v-model:open="gameStore.gameEndDialogOpen">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle> Noooo...sei in bancarotta! </AlertDialogTitle>
                    <AlertDialogDescription>
                        Hai esaurito tutti i tuoi fondi, non hai più soldi per pagare le spese mensili e il direttore della banca ti sta chiamando....
                        <br />
                        <br />
                        Fuggi in Brasile o...inizia una nuova partita per ricominciare da capo (ma stai più attento alle spese)!
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Vedi i toi risultati</AlertDialogCancel>
                    <AlertDialogAction @click="router.get('/dashboard')">Vai alla Dashboard</AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </div>
</template>
