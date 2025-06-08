<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Progress from '@/components/ui/progress/Progress.vue';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { useGameStore } from '@/stores/game';
import { TyDeveloper, TyProject, TySeller } from '@/types/belivengame';
import { router } from '@inertiajs/vue3';
import { Handshake } from 'lucide-vue-next';
import { computed } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    item: TySeller;
}>();

const perc_xp = computed(() => {
    return parseInt(props.item.xp / 100);
});

const gameStore = useGameStore();

function createProject(item: TyDeveloper) {
    router.visit('/api/game/project/create', {
        method: 'post',
        data: {
            seller_id: item.id,
            date: gameStore.dateCurrent,
        },
        onSuccess: (page) => {
            toast.success('Nuovo cliente in vista! Il tuo commerciale sta lavorando per portare a casa un bel contratto firmato.');
            gameStore.addProjects(page.props.projects as TyProject[]);
        },
        onError: (errors) => {},
    });
}
</script>

<template>
    <div class="hover:bg-accent grid auto-rows-min items-center gap-4 border-b p-2 md:grid-cols-3">
        <div class="relative p-3">
            <Handshake class="mr-2 inline-block w-4 h-4" />
            {{ props.item.name }}
        </div>
        <div class="relative grid">
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
            <Button v-if="!props.item.active_project && !gameStore.gameEnd" @click="createProject(props.item)">Crea nuovo progetto</Button>
            <span v-if="props.item.active_project && !gameStore.gameEnd">in riunione...</span>
        </div>
    </div>
</template>
