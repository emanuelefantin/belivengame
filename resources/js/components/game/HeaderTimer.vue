<script setup lang="ts">
import { useGameStore } from '@/stores/game';
import { ChevronRight, ChevronRightIcon, ChevronsRightIcon, FastForwardIcon, MenuIcon, PauseIcon } from 'lucide-vue-next'
import { useEcho } from "@laravel/echo-vue";
import { formatMoney } from '@/lib/utils';

const gameStore = useGameStore();
gameStore.initGame();

useEcho(`game.${gameStore.game.id}`, 'GameUpdated', (e : {}) => {
    gameStore.updateFromServer(e);
});
</script>

<template>
    <div class="grid auto-rows-min gap-4">
        <div class="relative p-4 text-right">
            <div class="grid grid-cols-4 items-center gap-1 text-center">
                <div class="relative p-1">
                    Patrimonio {{ formatMoney(gameStore.cashCurrent) }}
                </div>
                <div class="relative p-1">
                    <!-- {{ gameStore.elapsedTime }} Giorni -->
                    Spese/mese {{ formatMoney(gameStore.cashMonthExpenses) }}
                </div>
                <div class="relative p-1">
                    {{ gameStore.dateCurrentFormatted }}
                </div>
                <div class="relative p-1">
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
    </div>
</template>
