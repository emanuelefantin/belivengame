<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $games = Game::with([
            'user',
            'developers' => fn($developers) => $developers->where('hired', true),
            'sellers' => fn($sellers) => $sellers->where('hired', true),
        ])
            ->where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->get();


        //rimozione della sessione corrente del gioco
        $request->session()->forget('current_game_id');

        return Inertia::render('Dashboard', [
            'games' => $games,
        ]);
    }
}
