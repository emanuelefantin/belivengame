<script setup lang="ts">
import DeveloperProductionTpl from '@/components/game/production/DeveloperProductionTpl.vue';
import Project from '@/components/game/production/Project.vue';
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/app/AppGameLayout.vue';
import { useGameStore } from '@/stores/game';
import { SharedData } from '@/types';
import { TyDeveloper, TyGame, TyProject } from '@/types/belivengame';
import { Head, router, usePage } from '@inertiajs/vue3';
import { EqualIcon, PlusCircleIcon } from 'lucide-vue-next';
import { computed } from 'vue';
import { toast } from 'vue-sonner';

const page = usePage<SharedData>();
const game: TyGame = <TyGame>page.props.game;
const init_game: boolean = <boolean>page.props.init_game;
const developers: TyDeveloper[] = <TyDeveloper[]>page.props.developers;

const gameStore = useGameStore();
//inizializza il gioco
if (init_game) {
    gameStore.setStartData(game);
}
gameStore.initGame();

const toDoProjects = computed(() => {
    return gameStore.activeProjects.filter((project) => project.generation_completed == true && project.development_completed !== true);
});

function assignDeveloperToProject() {
    router.visit('/api/game/project/assign', {
        method: 'post',
        data: {
            project_id: gameStore.activeProject.id,
            developer_id: gameStore.activeDeveloper.id,
            date: gameStore.dateCurrent,
        },
        onSuccess: (page) => {
            gameStore.assignDeveloperToProject(gameStore.activeProject as TyProject, gameStore.activeDeveloper as TyDeveloper);
            toast.success('sail up -d....si inizia a programmare!');
            // Reset dei valori attivi
            gameStore.activeProject = null;
            gameStore.activeDeveloper = null;
        },
        onError: (errors) => {
            toast.error(errors.error);
        },
    });
}
</script>

<template>
    <Head title="Production" />

    <AppLayout>
        <div class="flex flex-col">
            <div class="h-25 items-center justify-between border-b p-4" v-if="!gameStore.gameEnd">
                <p class="text-muted-foreground mb-2 text-center">Seleziona lo sviluppatore più adatto per il progetto e inizia la creazione!</p>
                <div class="w-full text-center">
                    <span class="">
                        {{ gameStore.activeDeveloper ? `${gameStore.activeDeveloper.name}` : 'SELEZIONA UNO SVILUPPATORE' }}
                    </span>
                    <span class="mx-3">
                        <PlusCircleIcon class="text-primary inline h-5 w-5" />
                    </span>
                    <span class="">
                        {{ gameStore.activeProject ? `${gameStore.activeProject.name}` : 'SELEZIONA UN PROGETTO' }}
                    </span>
                    <span class="mx-3">
                        <EqualIcon class="text-primary inline h-5 w-5" />
                    </span>
                    <span class="">
                        <Button :disabled="!gameStore.activeDeveloper || !gameStore.activeProject" @click="assignDeveloperToProject()"
                            >SVILUPPA PROGETTO</Button
                        >
                    </span>
                </div>
            </div>
            <div class="grid w-full grid-cols-2" style="height: calc(100vh - 225px)">
                <div class="scrollbar flex h-full w-full flex-col overflow-hidden overflow-y-auto rounded-xl p-4">
                    <DeveloperProductionTpl
                        v-for="developer in developers"
                        :key="developer.id"
                        :item="developer"
                        :active="gameStore.activeDeveloper?.id === developer.id"
                        @click="gameStore.activeDeveloper == null ? (gameStore.activeDeveloper = developer) : (gameStore.activeDeveloper = null)"
                    />
                </div>
                <div class="scrollbar flex h-full w-full flex-col overflow-hidden overflow-y-auto rounded-xl p-4">
                    <Project
                        v-for="project in toDoProjects"
                        :key="project.id"
                        :item="project"
                        :active="gameStore.activeProject?.id === project.id"
                        @click="gameStore.activeProject == null ? (gameStore.activeProject = project) : (gameStore.activeProject = null)"
                    />

                    <div class="self-center justify-self-center text-center" v-if="toDoProjects.length === 0 && !gameStore.gameEnd">
                        Non ci sono progetti da sviluppare e i tuoi Developers si stanno annioiando...<br />
                        Invia i tuoi commerciali a firmare nuovi contratti!<br />

                        <Button class="btn btn-primary mt-5" @click="router.get('/game/sales')"> Vedi le riunioni in corso </Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
