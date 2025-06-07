<?php

namespace App\Http\Controllers;

use App\Helpers\GameHelper;
use App\Http\Controllers\Traits\HasGame;
use App\Models\Project;
use Faker\Factory;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use HasGame;

    public function create(Request $request)
    {
        $game = $this->getGame($request);
        $date = $request->input('date');
        $seller_id = $request->input('seller_id');

        // Verifico se il venditore esiste e se è assunto
        $item = $game->sellers()->where('id', $seller_id)->where('hired', true)->first();
        if (!$item) {
            return to_route('game.hr');
        }

        //se è un seller creo un nuovo progetto
        $faker = Factory::create();
        $project = new Project();
        $project->game_id = $game->id;
        $project->seller_id = $item->id; // assegno il progetto al venditore attivo
        $project->seller_xp = $item->xp; // assegno l'XP del venditore attivo
        $project->name = $faker->company();
        $project->generation_started_at = $date; // data di inizio generazione
        $project->budget = GameHelper::calcBudget($item->xp); // budget basato sull'XP del venditore
        $project->complexity = GameHelper::calcComplexity($item->xp); // complessità del progettobasata sull'XP del venditore
        $project->save();

        return to_route('game.sales');
    }

    /**
     * Assegna uno sviluppatore a un progetto.
     *
     * @param Request $request
     * @return void
     */
    public function assign(Request $request)
    {
        $game = $this->getGame($request);
        $date = $request->input('date');
        $project_id = $request->input('project_id');
        $developer_id = $request->input('developer_id');
        // Verifico se il venditore esiste e se è assunto
        $developer = $game->developers()
            ->where('id', $developer_id)
            ->where('hired', true)
            ->first()
            ->append('active_project');
            // ->where('active_project', false); // Verifico che lo sviluppatore non abbia già un progetto attivo
        if (!$developer) {
            return redirect()->back()->withErrors(['error' => 'Sviluppatore non trovato']);
        }
        if($developer->active_project){
            return redirect()->back()->withErrors(['error' => 'Lo sviluppatore è già impegnato in un progetto.']);
        }
        //controllo esistenza del progetto
        $project = $game->projects()->find($project_id);
        if (!$project) {
            return redirect()->back()->withErrors(['error' => 'Progetto non trovato.']);
        }

        // Verifico se il progetto è già completo
        if($project->is_complete || $project->development_completed) {
            return redirect()->back()->withErrors(['error' => 'Il progetto è già completo.']);
        }

        // Verifico se il progetto è stato generato (se è in progress non lo posso lavorare)
        if(!$project->generation_completed) {
            return redirect()->back()->withErrors(['error' => 'Il progetto non è ancora stato firmato dal cliente.']);
        }

        // Verifico se il progetto ha già uno sviluppatore assegnato
        if($project->developer_id) {
            return redirect()->back()->withErrors(['error' => 'Il progetto ha già uno sviluppatore assegnato.']);
        }

        //assegno lo sviluppatore al progetto
        $project->developer_id = $developer->id; 
        $project->developer_xp = $developer->xp; 
        $project->development_started_at = $date; 
        $project->save();

        return redirect()->back();
    }

}
