<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasGame;
use App\Http\Requests\StoreSellerRequest;
use App\Http\Requests\UpdateSellerRequest;
use App\Models\Seller;
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
        $sellers = $game->sellers()->where('hired', true)->get()->append('active_project');
        $projects = $game->projects()->where('completed', false)->get();

        return Inertia::render('game/Sales', [
            'game' => $game,
            'sellers' => $sellers,
            'projects' => $projects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSellerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Seller $seller)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seller $seller)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSellerRequest $request, Seller $seller)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seller $seller)
    {
        //
    }
}
