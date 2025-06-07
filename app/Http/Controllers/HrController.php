<?php

namespace App\Http\Controllers;

use App\Events\GameUpdated;
use App\Helpers\GameHelper;
use App\Http\Controllers\Traits\HasGame;
use App\Models\Developer;
use App\Models\Project;
use App\Models\Seller;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class HrController extends Controller
{
    use HasGame;

    /**
     * Lista Sellers e Developers da assumere
     */
    public function index(Request $request)
    {
        $game = $this->getGame($request);
        // Recupera i venditori e gli sviluppatori che non sono stati assunti
        // e che non sono stati licenziati (fired_at è null)
        $sellers = $game->sellers()->where('hired', false)->whereNull('fired_at')->get();
        $developers = $game->developers()->where('hired', false)->whereNull('fired_at')->get();
        // $projects = $game->projects()->where('completed', false)->get();

        return Inertia::render('game/Hr', [
            'game' => $game,
            'sellers' => $sellers,
            'developers' => $developers,
            // 'projects' => $projects,
        ]);
    }

    /**
     * Lista Sellers e Developers assunti
     */
    public function index_hired(Request $request)
    {
        $game = $this->getGame($request);

        $sellers = $game->sellers()->where('hired', true)->get()->append('active_project');
        $developers = $game->developers()->where('hired', true)->get()->append('active_project');

        return Inertia::render('game/HrHired', [
            'game' => $game,
            'sellers' => $sellers,
            'developers' => $developers,
        ]);
    }

    /**
     * Assume una nuova risorsa
     */
    public function hire(Request $request)
    {
        $game = $this->getGame($request);
        $date = $request->input('date');

        if ($request->has('developer_id')) {
            $item = $game->developers()->findOrFail($request->input('developer_id'));
        } elseif ($request->has('seller_id')) {
            $item = $game->sellers()->findOrFail($request->input('seller_id'));
        }

        $item->hired = true;
        $item->hired_at = $date;
        $item->save();

        //aggiorno la spesa mensile del gioco
        $game->updateMonthExpenses($item->salary);
        // //se è un seller creo un nuovo progetto
        // if ($item instanceof \App\Models\Seller) {
        //     $faker = Factory::create();
        //     $project = new Project();
        //     $project->game_id = $game->id;
        //     $project->seller_id = $item->id; // assegno il progetto al venditore attivo
        //     $project->seller_xp = $item->xp; // assegno l'XP del venditore attivo
        //     $project->name = $faker->company();
        //     $project->generation_started_at = $date; // data di inizio generazione
        //     $project->budget = GameHelper::calcBudget($item->xp); // budget basato sull'XP del venditore
        //     $project->complexity = GameHelper::calcComplexity($item->xp); // complessità del progettobasata sull'XP del venditore
        //     $project->save();
        // }

        $this->_repopulateSellersAndDevelopers($game);

        //aggiorno i dati di gioco
        GameUpdated::dispatch($game);

        return to_route('game.hr');
    }

    /**
     * Licenzia una risorsa.
     * In caso di licenziamento calcola lo stipendio mensile fino a questo momento e lo scala dal conto di gioco.
     */
    public function fire(Request $request)
    {
        $game = $this->getGame($request);
        $date = Carbon::parse($request->input('date'));

        if ($request->has('developer_id')) {
            $item = $game->developers()->findOrFail($request->input('developer_id'));
        } elseif ($request->has('seller_id')) {
            $item = $game->sellers()->findOrFail($request->input('seller_id'));
        }

        $item->hired = false;
        $item->fired_at = $date;
        $item->save();


        //calcolo la spesa per il mese corrente
        //se nello stesso mese in cui è stato assunto calcolo dal giorno di assunzione
        $hired_at = Carbon::parse($item->hired_at);
        if (($hired_at->year == $date->year) && ($hired_at->month == $date->month)) {
            $date_start = $hired_at;
        } else {
            //altrimenti da inizio mese
            $date_start = Carbon::parse($request->input('date'))->startOfMonth();
        }
        $days = $date_start->diffInDays($date, true) + 1;
        $cost = $item->salary / 30 * $days;
        $game->cashMinus($cost);

        $this->_repopulateSellersAndDevelopers($game);

        $game->updateMonthExpenses(-$item->salary);

        //aggiorno i dati di gioco
        GameUpdated::dispatch($game);

        return to_route('game.hr.hired');
    }

    /**
     * Repopola i venditori e gli sviluppatori disponibili se il numero è inferiore a 5.
     *
     * @param [type] $game
     * @return void
     */
    private function _repopulateSellersAndDevelopers($game)
    {
        if ($game->developers()->where('hired', false)->whereNull('fired_at')->count() < 5) {
            Developer::factory(rand(5, 10))->create([
                'game_id' => $game->id,
                'hired' => false, 
            ]);
        }
        if ($game->sellers()->where('hired', false)->whereNull('fired_at')->count() < 5) {
            Seller::factory(rand(5, 10))->create([
                'game_id' => $game->id,
                'hired' => false, 
            ]);
        }
    }
}
