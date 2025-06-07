<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasGame;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductionController extends Controller
{
    use HasGame;
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $init_game = $request->input('init_game', false);
        $game = $this->getGame($request);

        $developers = $game->developers()->where('hired', true)->get()->append('active_project');
        $projects = $game->projects()
            ->where('generation_completed', true)
            ->where('development_completed', false)
            ->get();

        return Inertia::render('game/Production', [
            'game' => $game,
            'init_game' => $init_game,
            'developers' => $developers,
            'projects' => $projects,
        ]);
    }
}
