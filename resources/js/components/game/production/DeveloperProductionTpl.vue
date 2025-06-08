<script setup lang="ts">
import Progress from '@/components/ui/progress/Progress.vue';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { useGameStore } from '@/stores/game';
import { TySeller } from '@/types/belivengame';
import { CodeIcon } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    item: TySeller;
    active: boolean;
}>();

const perc_xp = computed(() => {
    return parseInt(props.item.xp / 100);
});

const gameStore = useGameStore();
</script>

<template>
    <div
        class="hover:bg-accent grid auto-rows-min items-center gap-4 border-b md:grid-cols-3"
        :class="{ 'bg-primary/40': props.active, 'cursor-not-allowed': props.item.active_project, 'cursor-pointer': !props.item.active_project }"
    >
        <div class="relative p-3">
            <CodeIcon class="mr-2 inline-block w-4 h-4" />
            {{ props.item.name }}
        </div>
        <div class="relative grid p-1">
            <TooltipProvider>
                <Tooltip>
                    <TooltipTrigger as-child>
                        <Progress v-model="perc_xp" />
                    </TooltipTrigger>
                    <TooltipContent>
                        <p>XP: {{ props.item.xp }}</p>
                    </TooltipContent>
                </Tooltip>
            </TooltipProvider>
        </div>
        <div class="relative p-1 text-center">
            <span v-if="props.item.active_project && !gameStore.gameEnd">al lavoro...</span>
        </div>
    </div>
</template>
