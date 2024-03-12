<?php

namespace App\Http\Service;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class PokedexService
{
    public function allPokemonFromPokedex(string $url = '', bool $substituir = false): array
    {
        $response = !$substituir ? Http::get('https://pokeapi.co/api/v2/pokemon' . $url) : Http::get($url);

        if((empty($url) && !$substituir) || $substituir) {
            return $this->listaPokemon($response);
        }

        return $this->buscaPorNomePokemon($response);
    }

    private function listaPokemon(Response $response)
    {
        $return = [];
        $return['total'] = $response['count'];
        $return['proximo'] = $response['next'];
        $return['anterior'] = $response['previous'];
        $return['pokemon'] = [];

        foreach ($response['results'] as $pokemon) {
            $pokemonInfo = Http::get($pokemon['url']);
            array_push($return['pokemon'], $this->pegaDadosPokemon($pokemonInfo));
        }

        return $return;
    }

    private function buscaPorNomePokemon(Response $response)
    {
        $return = [];
        $return['total'] = 1;
        $return['proximo'] = null;
        $return['anterior'] = null;
        $return['pokemon'] = [];

        array_push($return['pokemon'], $this->pegaDadosPokemon($response));

        return $return;
    }

    private function pegaDadosPokemon($pokemonInfo)
    {
        $tipos = [];
        $status = [];

        foreach ($pokemonInfo['types'] as $type) {
            array_push($tipos, [
                'nome' => $type['type']['name']
            ]);
        }

        foreach ($pokemonInfo['stats'] as $stat) {
            array_push($status, [
                'nome' => $stat['stat']['name'],
                'valor' => $stat['base_stat']
            ]);
        }

        return [
            'imagem' => $pokemonInfo['sprites']['other']['official-artwork']['front_default'],
            'nome' => $pokemonInfo['name'],
            'numero' => $pokemonInfo['id'],
            'tipos' => $tipos,
            'altura' => round(($pokemonInfo['height'] * 0.1), 2),
            'peso' => round(($pokemonInfo['weight'] * 0.1), 2),
            'status' => $status
        ];
    }
}
