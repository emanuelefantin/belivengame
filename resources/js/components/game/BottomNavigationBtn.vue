<script setup lang="ts">
/**
 * Pulsanti di navigazione per la barra inferiore del gioco.
 * Questi pulsanti sono utilizzati per navigare tra le diverse sezioni del gioco.
 */
import type { NavItem, SharedData } from '@/types';
import { router, usePage } from '@inertiajs/vue3';

const props = defineProps<{
    item: NavItem;
}>();

const page = usePage<SharedData>();
</script>

<template>
    <div class="cursor-pointer border p-4 text-center" :class="{
        'bg-primary text-primary-foreground' : page.url.startsWith(item.href),
    }" @click="router.get(props.item.href)">
        <component :is="item.icon" class="inline" /> {{ item.title }}
    </div>
</template>
