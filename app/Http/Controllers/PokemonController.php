<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Service\PokedexService;

class PokemonController extends Controller
{
    public function index(Request $request)
    {
        $pokemon = '';
        $substituir = false;
        $dataForm = $request->all();

        if (isset($dataForm['pokemon']) && !empty($dataForm['pokemon'])) {
            $pokemon = '/' . strtolower($dataForm['pokemon']);
        }
        if (isset($dataForm['url']) && !empty($dataForm['url'])) {
            $pokemon = $dataForm['url'];
            $substituir = true;
        }

        $pokedexService = new PokedexService();
        $data = $pokedexService->allPokemonFromPokedex($pokemon, $substituir);

        return response()->json($data, 200);
    }
}
