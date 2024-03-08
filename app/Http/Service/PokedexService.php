<?php

namespace App\Http\Service;

use Illuminate\Support\Facades\Http;

class PokedexService
{
    public function allPokemonFromPokedex(): array
    {
        $return = [];
        $response = Http::get('https://pokeapi.co/api/v2/pokemon');

        foreach ($response['results'] as $pokemon) {
            $tipos = [];
            $status = [];
            $pokemonInfo = Http::get($pokemon['url']);

            foreach ($pokemonInfo['types'] as $type) {
                array_push($tipos, [
                    'tipo' => $type['type']['name']
                ]);
            }

            foreach ($pokemonInfo['stats'] as $stat) {
                array_push($status, [
                    $stat['stat']['name'] => $stat['base_stat']
                ]);
            }

            array_push($return, [
                'imagem' => $pokemonInfo['sprites']['other']['official-artwork']['front_default'],
                'nome' => $pokemonInfo['name'],
                'numero' => $pokemonInfo['id'],
                'tipo' => $tipos,
                'altura' => ($pokemonInfo['height'] . 0.1),
                'peso' => ($pokemonInfo['weight'] . 0.1),
                'status' => $status
            ]);
        }

        return $return;
    }
}
