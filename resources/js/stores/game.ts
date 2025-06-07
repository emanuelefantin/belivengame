import { defineStore } from 'pinia';
import moment from 'moment';
import { TyDeveloper, TyGame, TyProject } from '@/types/belivengame';
import { router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import { toHandlers } from 'vue';
import { useEcho } from "@laravel/echo-vue";

export const useGameStore = defineStore('game', {
    state: () => ({
        game: {} as TyGame,
        initialized: false, //indica se il gioco è stato inizializzato con tutti i dati necessari
        elapsedTime: 0,
        dateCurrent: new Date(),
        dateCurrentFormatted: moment(new Date()).format('DD-MM-YYYY'),
        velocity: 0.5,
        timerInterval: null as ReturnType<typeof setInterval> | null,
        cashStart: 0,
        cashCurrent: 0,
        cashMonthExpenses: 0,
        activeProjects: [] as TyProject[],
        activeDeveloper: null as TyDeveloper | null, // Sviluppatore attivo selezionato
        activeProject: null as TyProject | null, // Progetto attivo selezionato
        saveQueue: 0,
        savingInProgress: false, // Indica se il salvataggio è in corso
    }),
    actions: {
        initGame() {
            // if (!this.initialized) {
            //     router.get('/dashboard');
            // }
            this.startTimer();
        },
        /**
         * Imposta i dati iniziali del gioco.
         * Viene chiamato solo una volta quando il gioco viene caricato o ripreso.
         * @param game 
         */
        setStartData(game: any) {
            this.game = game as TyGame; // Assicurati che il tipo sia TyGame
            this.dateCurrent = game.date_current ? new Date(game.date_current) : new Date();
            this.cashStart = parseFloat(game.cash_start) || this.cashStart;
            this.cashCurrent = parseFloat(game.cash_current) || this.cashCurrent;
            this.cashMonthExpenses = parseFloat(game.cash_month_expenses) || this.cashMonthExpenses;
            this.activeProjects = game.projects || [];
            //imposto il progetto come inizializzato
            //in questo modo garantisco un solo punto di accesso per l'inizializzazione del gioco
            //e disabilito i refresh di pagina
            this.initialized = true;

            // console.log(moment(this.dateCurrent).startOf('month').format('DD-MM-YYYY'));
        },
        /**
         * Gestione del timer del gioco.
         * @param dateCurrent 
         * @returns 
         */
        startTimer(dateCurrent: Date | null = null) {
            if (this.timerInterval) return; // Prevengo avvio di un timer già in esecuzione
            if (this.velocity <= 0) return; // Se la velocità è zero, non avviare il timer

            if (dateCurrent !== null) {
                this.dateCurrent = dateCurrent;
            }

            this.timerInterval = setInterval(() => {
                this.elapsedTime++;
                this.dateCurrent = moment(this.dateCurrent).add(1, 'day').toDate(); // Aggiunge un giorno alla data corrente
                this.dateCurrentFormatted = moment(this.dateCurrent).format('DD-MM-YYYY'); // Aggiorna la data formattata

                this.updateProjects();
                this.controlPaychecks();
            }, 1000 / this.velocity); // Imposta intervallo in base alla velocità
        },
        stopTimer() {
            if (this.timerInterval) {
                clearInterval(this.timerInterval);
                this.timerInterval = null;
            }
        },
        resetTimer() {
            this.stopTimer();
            this.elapsedTime = 0;
        },
        setVelocity(velocity: number) {
            if (velocity == 0) {
                this.stopTimer();
                this.velocity = 0;
                toast.info('Il gioco è in pausa.');
                return;
            }
            this.velocity = velocity;
            // Restart del timer con la nuova velocità (senza azzeramento del tempo)
            this.stopTimer();
            this.startTimer();
        },
        /**
         * Logica per aggiornare i progetti attivi
         */
        updateProjects() {
            let do_save = false;
            this.activeProjects.forEach(project => {
                //se la generazione non è completata, incrementa il progresso della generazione
                //per essere completata, il progresso deve raggiungere 10000
                if (project.generation_completed !== true) {
                    project.generation_progress += project.seller_xp / 10; // Incrementa il progresso del progetto in base all'XP del venditore
                    // Se il progresso raggiunge o supera 10000, segna il progetto come completato
                    if (project.generation_progress >= 10000) {
                        project.generation_completed = true;
                        project.generation_completed_at = this.dateCurrent; // Imposta la data di completamento

                        do_save = true; // Indica che è necessario salvare il gioco

                        toast.success('Abbiamo firmato il contratto con: ' + project.name + '! Assegnalo a uno sviluppatore.');
                    }
                }
                //gestione dello sviluppo del progetto
                if (project.development_completed !== true && project.developer_id) {
                    project.development_progress += project.developer_xp / 10; // Incrementa il progresso dello sviluppo in base all'XP dello sviluppatore
                    // Se il progresso raggiunge o supera 10000, segna lo sviluppo come completato
                    if (project.development_progress >= 10000) {
                        project.development_completed = true;
                        project.development_completed_at = this.dateCurrent; // Imposta la data di completamento

                        do_save = true; // Indica che è necessario salvare il gioco

                        toast.success('Il progetto ' + project.name + ' è stato completato! Money on the road!');
                    }
                }
            });

            if (do_save) {
                this.saveGame();
            }

        },
        addProjects(projects: TyProject[]) {
            projects.forEach(project => {
                // Controlla se il progetto esiste già
                let existingProject = this.activeProjects.find(p => p.id === project.id);
                if (!existingProject) {
                    this.activeProjects.push(project);
                }
            });
        },
        assignDeveloperToProject(
            activeProject: TyProject,
            activeDeveloper: TyDeveloper
        ) {
            let project = this.activeProjects.find(p => p.id === activeProject.id);
            if(project === undefined) {
                toast.error('Progetto non trovato.');
                return;
            }
            project.developer_id = activeDeveloper.id; // Assegna l'ID dello sviluppatore al progetto
            project.developer_xp = activeDeveloper.xp; // Assegna l'XP dello sviluppatore al progetto
        },
        controlPaychecks() {
            // Se è il primo giorno del mese, paga gli stipendi
            if (moment(this.dateCurrent).isSame(moment(this.dateCurrent).startOf('month'), 'day')) {
                router.post('/api/game/paychecks', {
                    date_current: this.dateCurrent,
                }, {
                    onBefore: () => {
                        // this.savingInProgress = true; // Imposta lo stato di salvataggio in corso
                    },
                    onFinish: () => {
                    },
                    onSuccess: (page) => {
                        toast.info('Stipendi pagati...corri a fatturare!');
                    },
                    onError: () => {
                        toast.error('Errore durante il pagamento degli stipendi.');
                    }
                });
            }
        },

        updateFromServer(data: {}) {
            this.cashCurrent = parseFloat(data.game.cash_current) || this.cashCurrent;
            this.cashMonthExpenses = parseFloat(data.game.cash_month_expenses) || this.cashMonthExpenses;
        },

        saveGame() {
            // Questo previene il salvataggio multiplo se un salvataggio è già in corso
            if (this.savingInProgress) {
                this.saveQueue++;
                return;
            }

            // Invia i dati del gioco al server per il salvataggio
            router.post('/api/game/save', {
                date_current: this.dateCurrent,
                projects: this.activeProjects,
            }, {
                preserveState: false,
                onBefore: () => {
                    this.savingInProgress = true; // Imposta lo stato di salvataggio in corso
                },
                onFinish: () => {
                    this.savingInProgress = false; // Reimposta lo stato di salvataggio in corso
                    this.saveQueue--;
                    // Se ci sono salvataggi in coda eseguo un salvataggio unico
                    if (this.saveQueue > 0) {
                        this.saveQueue = 0; // Reset della coda
                        this.saveGame(); // chiama ricorsivamente saveGame
                    }
                },
                onSuccess: (page) => {
                    // this.setCashDb(page.props.game.cash_current); // Aggiorna il denaro corrente dal server
                    // this.cashDelta = 0; // Reset del delta del denaro
                    toast.success('Gioco salvato con successo!');
                },
                onError: () => {
                    toast.error('Errore durante il salvataggio del gioco.');
                }
            });
        }
    },
});