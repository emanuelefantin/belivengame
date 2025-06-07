<?php

namespace App\Http\Controllers;

use App\Events\GameUpdated;
use App\Helpers\GameHelper;
use App\Http\Controllers\Traits\HasGame;
use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Models\Developer;
use App\Models\Game;
use App\Models\Project;
use App\Models\Seller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GameController extends Controller
{
    use HasGame;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);

        //dopo la validazione faccio un check manuale per evitare nomi duplicati
        if (Game::where('name', $validated['name'])->where('user_id', auth()->id())->exists()) {
            $validated['name'] = $validated['name'] . ' ' . now()->format('Y-m-d H:i:s');
        }

        $date_start = now();

        $game = new Game($validated);
        $game->slug = str($validated['name'])->slug();
        $game->user_id = auth()->id();
        $game->date_current = $date_start;
        $game->date_start = $date_start;
        $game->save();

        //creazione Sellers
        Seller::factory(rand(10, 20))->create([
            'game_id' => $game->id,
            'hired' => false, // inizialmente non assunti
        ]);


        $xp = rand(1000, 2000);
        $salary = $xp / 4;
        $active_seller = Seller::factory()->create([
            'game_id' => $game->id,
            'hired' => true, // almeno un venditore deve essere assunto all'inizio
            'hired_at' => $date_start, // data di assunzione corrente
            'xp' => $xp, // xp
            'salary' => $salary, // salario iniziale
        ]);

        $game->updateMonthExpenses($active_seller->salary);

        //creo un progetto iniziale per il venditore attivo
        $project = new Project();
        $project->game_id = $game->id;
        $project->seller_id = $active_seller->id; // assegno il progetto al venditore attivo
        $project->seller_xp = $active_seller->xp; // assegno l'XP del venditore attivo
        $project->name = 'Il nostro primo progetto!';
        $project->generation_started_at = $date_start; // data di inizio generazione
        $project->budget = GameHelper::calcBudget($active_seller->xp); // budget iniziale basato sull'XP del venditore
        $project->complexity = GameHelper::calcComplexity($active_seller->xp) * 0.8; // complessità iniziale basata sull'XP del venditore
        $project->save();

        //creazione Developers
        Developer::factory(rand(10, 20))->create([
            'game_id' => $game->id,
            'hired' => false, // inizialmente non assunti
        ]);

        $xp = rand(1000, 2000);
        $salary = $xp / 4;
        $active_developer = Developer::factory()->create([
            'game_id' => $game->id,
            'hired' => true, // almeno un sviluppatore deve essere assunto all'inizio
            'hired_at' => $date_start, // data di assunzione corrente
            'xp' => $xp, // xp
            'salary' => $salary, // salario iniziale
        ]);

        $game->updateMonthExpenses($active_developer->salary);

        $request->session()->put('current_game_id', $game->id);

        return to_route('game.production', ['init_game' => true])->with('success', 'Nuovo gioco creato con successo!');
    }

    /**
     * Creazione di una nuova partita.
     */
    public function store(StoreGameRequest $request)
    {
        $date_current = $request->input('date_current');
        $projects = $request->input('projects', []);

        $game = $this->getGame($request);
        $cash_delta = 0;

        // Aggiorno i progetti associati al gioco
        foreach ($projects as $projectData) {
            $project = Project::find($projectData['id']);
            if ($project && $project->game_id === $game->id) {
                //controllo se un progetto passa dallo stato di sviluppo a completato
                if (!$project->development_completed && $projectData['development_completed']) {
                    //imposta il progetto come completato
                    $project->completed = true;
                    $project->completed_at = Carbon::parse($date_current);

                    // aggiungo il budget del progetto completato al cash
                    $cash_delta += $project->budget;
                }

                $project->generation_completed = $projectData['generation_completed'] ?? false;
                $project->generation_progress = $projectData['generation_progress'] ?? 0;
                $project->generation_started_at = $projectData['generation_started_at'] ?? null;
                $project->generation_completed_at = $projectData['generation_completed_at'] ?? null;

                $project->development_completed = $projectData['development_completed'] ?? false;
                $project->development_progress = $projectData['development_progress'] ?? 0;
                $project->development_started_at = $projectData['development_started_at'] ?? null;
                $project->development_completed_at = $projectData['development_completed_at'] ?? null;
                $project->save();
            }
        }

        $game->date_current = Carbon::parse($date_current);
        $game->cash_current = $game->cash_current + $cash_delta;
        $game->save();

        //aggiorno i dati di gioco
        GameUpdated::dispatch($game);

        return redirect()->back();
    }

    /**
     * Paga gli stipendi ai venditori e agli sviluppatori.
     *
     * @return void
     */
    public function paychecks(Request $request)
    {
        $date_current = $request->input('date_current');
        $game = $this->getGame($request);
        // sottraggo il totale degli stipendi dal cash corrente del gioco
        $game->cash_current -= $game->cash_month_expenses;
        $game->save();
        //aggiorno i dati di gioco
        GameUpdated::dispatch($game);
    }

    /**
     * Continuazione della partita selezionata.
     *
     * @param Request $request
     * @return void
     */
    public function continue(Request $request)
    {
        $this->getGame($request);
        return to_route('game.production', ['init_game' => true])->with('success', 'Riprendendo la partita...');
    }
}
