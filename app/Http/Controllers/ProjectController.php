<?php

namespace App\Http\Controllers;

use App\Helpers\GameHelper;
use App\Http\Controllers\Traits\HasGame;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Faker\Factory;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use HasGame;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

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

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(StoreProjectRequest $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(Project $project)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Project $project)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(UpdateProjectRequest $request, Project $project)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Project $project)
    // {
    //     //
    // }
}
