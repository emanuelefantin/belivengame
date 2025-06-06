<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { TyDeveloper } from '@/types/belivengame';
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';
import Progress from '../ui/progress/Progress.vue';

const props = defineProps<{
    item: TyDeveloper;
}>();

const perc_xp = computed(() => {
    return parseInt(props.item.xp / 100);
});

function hire(item: TyDeveloper) {
    router.visit('/api/game/hr/hire', {
        method: 'post',
        data: {
            developer_id: item.id,
        },
        onSuccess: (page) => {

        },
        onError: (errors) => {},
        
    });
}
function fire(item: TyDeveloper) {
    router.visit('/api/game/hr/fire', {
        method: 'post',
        data: {
            developer_id: item.id,
        },
        onSuccess: (page) => {

        },
        onError: (errors) => {},
    });
}
</script>

<template>
    <div class="grid auto-rows-min gap-4 border md:grid-cols-3">
        <div class="relative p-1">{{ props.item.name }}</div>
        <div class="relative grid p-1 md:grid-cols-2">
            <div class="relative pt-2"><Progress v-model="perc_xp" /></div>
            <div class="relative pl-2">{{ props.item.xp }}</div>
        </div>
        <div class="relative p-1">
            <Button v-if="props.item.hired == false" @click="hire(props.item)">Assumi</Button>
            <Button v-if="props.item.hired == true" @click="fire(props.item)">Licenzia</Button>
        </div>
    </div>
</template>
