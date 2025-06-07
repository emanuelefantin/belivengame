<?php

namespace App\Http\Controllers\Traits;

use App\Models\Game;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * Trait comune per i controller che gestiscono il gioco.
 * Fornisce un metodo per ottenere il gioco corrente basato su ID o sessione
 * o reindirizza alla dashboard se non è selezionato o non esiste.
 * Esegue anche un controllo di autorizzazione per assicurarsi che il gioco appartenga all'utente autenticato.
 */
trait HasGame
{
    public function getGame(Request $request): Game | RedirectResponse
    {
        if ($request->input('id')) {
            $currentGameId = $request->input('id');
            // If the request contains a game ID, set it as the current game ID in the session
            $request->session()->put('current_game_id', $currentGameId);
        } else {
            // Retrieve the current game ID from the session
            $currentGameId = $request->session()->get('current_game_id');
        }

        if (!$currentGameId) {
            return redirect()->route('dashboard')->with('error', 'No game selected.')->send();
        }

        // Find the game by ID
        $game = Game::with([
            'user',
            // 'developers' => fn($developers) => $developers->where('hired', true),
            // 'sellers' => fn($sellers) => $sellers->where('hired', true),
            'projects' => fn($projects) => $projects->where('completed', false)
        ])->find($currentGameId);

        if (!$game) {
            return redirect()->route('dashboard')->with('error', 'Game not found.')->send();
        }

        // Controllo che il Game appartenga all'utente autenticato
        if ($game->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return $game;
    }
}
