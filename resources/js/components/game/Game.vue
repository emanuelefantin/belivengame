<script setup lang="ts">
import { formatMoney } from '@/lib/utils';
import { TyGame } from '@/types/belivengame';
import { router } from '@inertiajs/vue3';
import { BadgeEuroIcon, CodeIcon } from 'lucide-vue-next';

const props = defineProps<{
    item: TyGame;
}>();

</script>

<template>
    <div
        class="grid auto-rows-min gap-4 grid-cols-4 border-b cursor-pointer hover:bg-gray-100 transition-colors duration-200"
        @click="
            router.visit('/game/continue', {
                method: 'post',
                data: {
                    id: props.item.id,
                },
            })
        "
    >
        <div class="relative p-4 font-bold">{{ props.item.name }}</div>
        <div class="relative p-4 text-muted-foreground">
            <CodeIcon class="inline-block mr-2" />
            {{ props.item.developers.length }}
        </div>
        <div class="relative p-4 text-muted-foreground">
            <BadgeEuroIcon class="inline-block mr-2" />
            {{ props.item.developers.length }}
        </div>
        <div class="relative p-4 text-right">{{ formatMoney(props.item.cash_current) }}</div>
    </div>
</template>
