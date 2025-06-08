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
    <div class="cursor-pointer border p-4 text-center" :class="page.url.startsWith(item.href) ? 'bg-primary' : ''" @click="router.get(props.item.href)">
        <component :is="item.icon" class="inline" /> {{ item.title }}
    </div>
</template>
