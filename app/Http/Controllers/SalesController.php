<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasGame;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SalesController extends Controller
{
    use HasGame;
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $game = $this->getGame($request);
        $sellers = $game->sellers()->where('hired', true)->get()->append('active_project')->sortBy('active_project')->values();

        $projects = $game->projects()->where('generation_completed', false)->get();
        // $projects = $game->projects()->where('completed', false)->get();

        return Inertia::render('game/Sales', [
            'game' => $game,
            'sellers' => $sellers,
            'projects' => $projects,
        ]);
    }
}
