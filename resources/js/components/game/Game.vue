<script setup lang="ts">
import { TyGame } from '@/types/belivengame';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    item: TyGame;
}>();

</script>

<template>
    <div
        class="grid auto-rows-min gap-4 md:grid-cols-2"
        @click="
            router.visit('/game/continue', {
                method: 'post',
                data: {
                    id: props.item.id,
                },
            })
        "
    >
        <div class="relative border p-4">{{ props.item.name }}</div>
    </div>
</template>
