<script setup lang="ts">
import { TyGame, TyProject } from '@/types/belivengame';
import Progress from '@/components/ui/progress/Progress.vue';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { computed } from 'vue';
import { formatMoney } from '@/lib/utils';
import { useGameStore } from '@/stores/game';

const props = defineProps<{
    item: TyProject;
    active:boolean;
}>();

const gameStore = useGameStore();

const perc_xp = computed(() => {
    return parseInt(props.item.development_progress / props.item.complexity * 100);
});
</script>

<template>
    <div class="grid auto-rows-min gap-4 md:grid-cols-4 border-b items-center  hover:bg-accent" :class="{ 'bg-primary/40': props.active,'cursor-not-allowed': (props.item.development_progress > 0),'cursor-pointer': (props.item.development_progress == 0) }">
        <div class="p-3 ">
            <TooltipProvider>
                <Tooltip>
                    <TooltipTrigger as-child>
                        <div class="truncate">{{ props.item.name }}</div>
                    </TooltipTrigger>
                    <TooltipContent>
                       <p>{{ props.item.name }}</p>
                    </TooltipContent>
                </Tooltip>
            </TooltipProvider>
        </div>
        <div class="p-3">{{ formatMoney(props.item.budget) }}</div>
        <div>
            <TooltipProvider>
                <Tooltip>
                    <TooltipTrigger as-child>
                        <Progress v-model="perc_xp"  />
                    </TooltipTrigger>
                    <TooltipContent>
                        <p>Progresso: {{ parseInt(props.item.development_progress) }} / {{ props.item.complexity }}</p>
                    </TooltipContent>
                </Tooltip>
            </TooltipProvider>
        </div>
        <div>
            <span v-if="props.item.development_progress && !gameStore.gameEnd">in corso...</span>
        </div>
    </div>
</template>
