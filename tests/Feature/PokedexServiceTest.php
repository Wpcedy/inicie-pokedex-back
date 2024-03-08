<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Service\PokedexService;
use Illuminate\Support\Facades\Http;

class PokedexServiceTest extends TestCase
{
    public function testAllPokemonFromPokedex()
    {
        $pokedexservice = new PokedexService();
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon' => Http::response(
                [
                    "count" => 3,
                    "next" => "https://pokeapi.co/api/v2/pokemon?offset=40&limit=20",
                    "previous" => "https://pokeapi.co/api/v2/pokemon?offset=20&limit=20",
                    "results" => [
                        [
                            "name" => "bulbasaur",
                            "url" => "https://pokeapi.co/api/v2/pokemon/1/"
                        ],
                        [
                            "name" => "charmander",
                            "url" => "https://pokeapi.co/api/v2/pokemon/4/"
                        ],
                        [
                            "name" => "squirtle",
                            "url" => "https://pokeapi.co/api/v2/pokemon/7/"
                        ]
                    ]
                ]
            )
        ]);
        $results = $pokedexservice->allPokemonFromPokedex();
        $this->assertEquals(3, $results['total']);
        $this->assertCount(3, $results['pokemon']);
        $this->assertEquals('https://pokeapi.co/api/v2/pokemon?offset=40&limit=20', $results['proximo']);
        $this->assertEquals('https://pokeapi.co/api/v2/pokemon?offset=20&limit=20', $results['anterior']);
        $this->assertEquals('bulbasaur', $results['pokemon'][0]['nome']);
        $this->assertEquals('charmander', $results['pokemon'][1]['nome']);
        $this->assertEquals('squirtle', $results['pokemon'][2]['nome']);
        $this->assertTrue(true);
    }
}
