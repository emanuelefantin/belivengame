<script setup lang="ts">
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { formatMoney } from '@/lib/utils';
import { TyGame } from '@/types/belivengame';
import { router } from '@inertiajs/vue3';
import { BadgeEuroIcon, CalendarDays, CircleX, CodeIcon, Handshake } from 'lucide-vue-next';
import moment from 'moment';
import { computed } from 'vue';

const props = defineProps<{
    item: TyGame;
}>();

const gameDays = computed(() => {
    let days = moment(props.item.date_current).diff(moment(props.item.date_start), 'days');
    return days.toLocaleString('it-IT', {
        useGrouping: true,
    });
});
</script>

<template>
    <div
        class="grid cursor-pointer grid-cols-5 content-center gap-4 border-b transition-colors duration-200 hover:bg-gray-100"
        :class="{ 'text-red-500': props.item.cash_current < 0 }"
        @click="
            router.visit('/game/continue', {
                method: 'post',
                data: {
                    id: props.item.id,
                },
            })
        "
    >
        <div class="p-4 font-bold">
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
        <div class="p-4 text-muted-foreground p-4">
            <CalendarDays class="mr-2 inline-block" />
            {{ gameDays }} Giorni
        </div>
        <div class="text-muted-foreground p-4">
            <TooltipProvider>
                <Tooltip>
                    <TooltipTrigger as-child>
                        <Handshake class="mr-2 inline-block" />
                        {{ props.item.sellers.length }}
                    </TooltipTrigger>
                    <TooltipContent> Venditori </TooltipContent>
                </Tooltip>
            </TooltipProvider>
        </div>
        <div class="text-muted-foreground p-4">
            <TooltipProvider>
                <Tooltip>
                    <TooltipTrigger as-child>
                        <CodeIcon class="mr-2 inline-block" />
                        {{ props.item.developers.length }}
                    </TooltipTrigger>
                    <TooltipContent> Sviluppatori </TooltipContent>
                </Tooltip>
            </TooltipProvider>
        </div>
        <div class="p-4 text-right">
            <TooltipProvider>
                <Tooltip>
                    <TooltipTrigger as-child>
                        <BadgeEuroIcon class="mr-2 inline-block" />
                        {{ formatMoney(props.item.cash_current) }}
                    </TooltipTrigger>
                    <TooltipContent> Cassa </TooltipContent>
                </Tooltip>
            </TooltipProvider>
        </div>
    </div>
</template>
