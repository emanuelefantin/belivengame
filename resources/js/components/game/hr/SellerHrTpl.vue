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
import { TySeller } from '@/types/belivengame';
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    item: TySeller;
}>();

const perc_xp = computed(() => {
    return parseInt(props.item.xp / 100);
});

const gameStore = useGameStore();

function hire(item: TySeller) {
    router.visit('/api/game/hr/hire', {
        method: 'post',
        data: {
            seller_id: item.id,
            date: gameStore.dateCurrent,
        },
        onSuccess: (page) => {
            toast.success('Yeee! Adesso hai un nuovo commerciale nel tuo team');
            // gameStore.addProjects(page.props.projects as TyProject[]);
        },
        onError: (errors) => {},
    });
}
function fire(item: TySeller) {
    router.visit('/api/game/hr/fire', {
        method: 'post',
        data: {
            seller_id: item.id,
            date: gameStore.dateCurrent,
        },
        onSuccess: (page) => {
            // gameStore.setCashDb(page.props.game.cash_current); // Aggiorna il denaro corrente dal server
            toast.success('Commerciale licenziato...e adesso chi lo sostituisce?');
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

            <span v-if="props.item.active_project">Sta lavorando...</span>
            <AlertDialog v-if="props.item.hired == true && !props.item.active_project">
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
