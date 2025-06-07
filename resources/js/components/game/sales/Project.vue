<script setup lang="ts">
import { TyGame, TyProject } from '@/types/belivengame';
import Progress from '@/components/ui/progress/Progress.vue';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { computed } from 'vue';
import { formatMoney } from '@/lib/utils';

const props = defineProps<{
    item: TyProject;
}>();

const perc_xp = computed(() => {
    return parseInt(props.item.generation_progress / 10000 * 100);
});
</script>

<template>
    <div class="grid auto-rows-min gap-4 md:grid-cols-4 items-center  p-1">
        <div class="relative p-3">{{ props.item.name }}</div>
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
    </div>
</template>
