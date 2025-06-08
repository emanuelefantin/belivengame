<script setup lang="ts">
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { formatMoney } from '@/lib/utils';
import { TyGame } from '@/types/belivengame';
import { router } from '@inertiajs/vue3';
import { BadgeEuroIcon, CircleX, CodeIcon, Handshake } from 'lucide-vue-next';

const props = defineProps<{
    item: TyGame;
}>();
</script>

<template>
    <div
        class="grid cursor-pointer content-center grid-cols-4 gap-4 border-b transition-colors duration-200 hover:bg-gray-100"
        :class="{'text-red-500' : (props.item.cash_current < 0)}"
        @click="
            router.visit('/game/continue', {
                method: 'post',
                data: {
                    id: props.item.id,
                },
            })
        "
    >
        <div class=" p-4 font-bold">
            <CircleX class="mr-2 inline-block" />
            {{ props.item.name }}
        </div>
        <div class="text-muted-foreground  p-4">
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
        <div class="text-muted-foreground  p-4">
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
        <div class=" p-4 text-right">
            <TooltipProvider>
                <Tooltip>
                    <TooltipTrigger as-child>
                        <BadgeEuroIcon class="mr-2 inline-block" />
                        {{ formatMoney(props.item.cash_current) }}
                    </TooltipTrigger>
                    <TooltipContent> Patrimonio </TooltipContent>
                </Tooltip>
            </TooltipProvider>
        </div>
    </div>
</template>
