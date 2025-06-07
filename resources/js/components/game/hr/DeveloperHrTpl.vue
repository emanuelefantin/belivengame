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
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import Progress from '@/components/ui/progress/Progress.vue';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { formatMoney } from '@/lib/utils';
import { useGameStore } from '@/stores/game';
import { TyDeveloper } from '@/types/belivengame';
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    item: TyDeveloper;
}>();

const perc_xp = computed(() => {
    return parseInt(props.item.xp / 100);
});

const gameStore = useGameStore();

function hire(item: TyDeveloper) {
    router.visit('/api/game/hr/hire', {
        method: 'post',
        data: {
            developer_id: item.id,
            date: gameStore.dateCurrent,
        },
        onSuccess: (page) => {
            toast.success('Un nuovo DEV è entrato nel tuo team!');
        },
        onError: (errors) => {},
    });
}
function fire(item: TyDeveloper) {
    router.visit('/api/game/hr/fire', {
        method: 'post',
        data: {
            developer_id: item.id,
            date: gameStore.dateCurrent,
        },
        onSuccess: (page) => {
            // gameStore.setCashDb(page.props.game.cash_current); // Aggiorna il denaro corrente dal server
            toast.success('Sviluppatore licenziato... e ora chi scrive poesie in PHP?');
        },
        onError: (errors) => {},
    });
}
</script>

<template>
    <div class="grid auto-rows-min items-center gap-4 border md:grid-cols-4">
        <div class="relative p-1">{{ props.item.name }}</div>
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
        <div class="relative p-1">{{ formatMoney(props.item.salary) }} <span class="text-muted-foreground text-sm">/mese</span></div>
        <div class="relative p-1 text-right">
            <Button v-if="props.item.hired == false" @click="hire(props.item)">Assumi</Button>

            <AlertDialog v-if="props.item.hired == true">
                <AlertDialogTrigger>
                    <Button>Licenzia</Button>
                </AlertDialogTrigger>
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>Sei sicuro?</AlertDialogTitle>
                        <AlertDialogDescription>
                            Stai per licenziare {{ props.item.name }}. Questa azione non può essere annullata e perderai un membro del tuo team.
                        </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel>No no no...resta con noi</AlertDialogCancel>
                        <AlertDialogAction @click="fire(props.item)">Sì!</AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </div>
    </div>
</template>
