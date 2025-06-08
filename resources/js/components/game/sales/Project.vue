<script setup lang="ts">
import Progress from '@/components/ui/progress/Progress.vue';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { formatMoney } from '@/lib/utils';
import { useGameStore } from '@/stores/game';
import { TyProject } from '@/types/belivengame';
import { computed } from 'vue';

const props = defineProps<{
    item: TyProject;
}>();

const gameStore = useGameStore();

const perc_xp = computed(() => {
    return parseInt((props.item.generation_progress / 10000) * 100);
});
</script>

<template>
    <div class="grid auto-rows-min items-center gap-4 p-1 md:grid-cols-4">
        <div class="relative p-3">
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
        <div class="relative p-3">{{ formatMoney(props.item.budget) }}</div>
        <div>
            <TooltipProvider>
                <Tooltip>
                    <TooltipTrigger as-child>
                        <Progress v-model="perc_xp" />
                    </TooltipTrigger>
                    <TooltipContent>
                        <p>Progresso: {{ parseInt(props.item.generation_progress) }} / 10000</p>
                    </TooltipContent>
                </Tooltip>
            </TooltipProvider>
        </div>
        <div>
            <span v-if="(props.item.generation_progress > 0) && !gameStore.gameEnd">in corso...</span>
        </div>
    </div>
</template>
