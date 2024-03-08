<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Service\PokedexService;

class PokemonController extends Controller
{
    public function index()
    {
        $pokedexService = new PokedexService();
        $data = $pokedexService->allPokemonFromPokedex();

        return response()->json($data, 200);
    }
}
