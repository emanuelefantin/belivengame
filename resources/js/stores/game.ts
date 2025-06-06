import { defineStore } from 'pinia';
import moment from 'moment';
import { TyProject } from '@/types/belivengame';
import { router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import { toHandlers } from 'vue';

export const useGameStore = defineStore('game', {
    state: () => ({
        initialized: false, //indica se il gioco è stato inizializzato con tutti i dati necessari
        elapsedTime: 0,
        dateCurrent: new Date(),
        dateCurrentFormatted: moment(new Date()).format('DD-MM-YYYY'),
        velocity: 0.5,
        timerInterval: null as ReturnType<typeof setInterval> | null,
        cashStart: 0,
        //cash
        cashCurrent: 0,
        cashDb:0, // Denaro salvato nel database
        cashDelta: 0,
        cashShow: 0,

        activeProjects: [] as TyProject[],
        saveQueue: 0,
        savingInProgress: false, // Indica se il salvataggio è in corso
    }),
    actions: {
        initGame(dateCurrent: Date | null = null) {
            // if (!this.initialized) {
            //     router.get('/dashboard');
            // }
            this.startTimer(dateCurrent);
        },
        /**
         * Imposta i dati iniziali del gioco.
         * Viene chiamato solo una volta quando il gioco viene caricato o ripreso.
         * @param game 
         */
        setStartData(game: any) {
            this.dateCurrent = game.date_start ? new Date(game.date_start) : new Date();
            this.cashStart = parseFloat(game.cash_start) || this.cashStart;
            this.cashCurrent = parseFloat(game.cash_current) || this.cashCurrent;
            this.cashShow = this.cashCurrent; // Inizialmente mostra il denaro corrente
            this.cashDb = this.cashCurrent; // Inizializza il denaro salvato nel database
            this.activeProjects = game.projects || [];

            this.initialized = true;
        },
        /**
         * Gestione del timer del gioco.
         * @param dateCurrent 
         * @returns 
         */
        startTimer(dateCurrent: Date | null = null) {
            if (this.timerInterval) return; // Prevengo avvio di un timer già in esecuzione
            if(this.velocity <= 0) return; // Se la velocità è zero, non avviare il timer

            if (dateCurrent !== null) {
                this.dateCurrent = dateCurrent;
            }

            this.timerInterval = setInterval(() => {
                this.elapsedTime++;
                this.dateCurrent = moment(this.dateCurrent).add(1, 'day').toDate(); // Aggiunge un giorno alla data corrente
                this.dateCurrentFormatted = moment(this.dateCurrent).format('DD-MM-YYYY'); // Aggiorna la data formattata

                this.updateProjects();
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
            if(velocity == 0){
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
            this.activeProjects.forEach(project => {
                //se la generazione non è completata, incrementa il progresso della generazione
                //per essere completata, il progresso deve raggiungere 10000
                if (project.generation_completed !== true) {
                    project.generation_progress += project.seller_xp / 10; // Incrementa il progresso del progetto in base all'XP del venditore
                    // Se il progresso raggiunge o supera 10000, segna il progetto come completato
                    if (project.generation_progress >= 10000) {
                        project.generation_completed = true;
                        project.generation_completed_at = this.dateCurrent; // Imposta la data di completamento

                        // this.cashCurrent += parseFloat(project.budget); // Aggiungi il budget del progetto al denaro corrente
                        this.addCashDelta(parseFloat(project.budget)); // Aggiungi il budget del progetto al delta del denaro
                        this.saveGame();
                        toast.success('Abbiamo firmato il contratto con: ' + project.name + '! Assegnalo a uno sviluppatore.');
                    }
                }
            });
        },
        addProjects(projects: TyProject[]) {
            projects.forEach(project => {
                // Controlla se il progetto esiste già
                const existingProject = this.activeProjects.find(p => p.id === project.id);
                if (!existingProject) {
                    this.activeProjects.push(project);
                }
            });
        },

        setCashDb(cash: number) {
            this.cashDb = cash;
            if(this.cashDelta == 0){
                this.cashShow = this.cashDb; // Se non ci sono delta, mostra il denaro corrente
            }else{
                this.cashShow = this.cashCurrent + this.cashDelta; // Mostra il denaro corrente più il delta
            }
        },
        addCashDelta(delta: number) {
            this.cashDelta += delta;
            this.cashCurrent += delta; // Aggiungi il delta al denaro corrente
            this.cashShow = this.cashCurrent + this.cashDelta; // Mostra il denaro corrente più il delta
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
                cash_current: this.cashCurrent,
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
                    this.setCashDb(page.props.game.cash_current); // Aggiorna il denaro corrente dal server
                    this.cashDelta = 0; // Reset del delta del denaro
                    toast.success('Gioco salvato con successo!');
                },
                onError: () => {
                    toast.error('Errore durante il salvataggio del gioco.');
                }
            });
        }
    },
});