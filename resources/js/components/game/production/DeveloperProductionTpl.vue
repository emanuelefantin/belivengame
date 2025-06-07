<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Progress from '@/components/ui/progress/Progress.vue';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { useGameStore } from '@/stores/game';
import { TyDeveloper, TyProject, TySeller } from '@/types/belivengame';
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    item: TySeller;
    active:boolean;
}>();

const perc_xp = computed(() => {
    return parseInt(props.item.xp / 100);
});

const gameStore = useGameStore();
</script>

<template>
    <div class="grid auto-rows-min items-center gap-4 border-b md:grid-cols-3 cursor-pointer hover:bg-accent" :class="{ 'bg-primary/40': props.active }">
        <div class="relative p-3">{{ props.item.name }}</div>
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
        <div class="relative p-1 text-right">
            <span v-if="props.item.active_project">al lavoro...</span>
        </div>
    </div>
</template>
