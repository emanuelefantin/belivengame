<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// controllo che l'utente sia proprietario del gioco
Broadcast::channel('game.{gameId}', function ($user, $gameId) {
    return $user->games()->where('id', $gameId)->exists();
});