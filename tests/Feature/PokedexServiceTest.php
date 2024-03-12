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
    }

    public function testOnePokemonFromPokedex()
    {
        $pokedexservice = new PokedexService();
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/charmander' => Http::response(
                [
                    "id" => 4,
                    "name" => "charmander",
                    "height" => 100,
                    "weight" => 100,
                    "sprites" => [
                        "other" => [
                            "official-artwork" => [
                                "front_default" => "url",
                            ],
                        ],
                    ],
                    "types" => [
                        [
                            "type" => [
                                "name" => "fire",
                            ],
                        ],
                    ],
                    "stats" => [
                        [
                            "base_stat" => 50,
                            "stat" => [
                                "name" => "hp",
                            ],
                        ],
                    ],
                ]
            )
        ]);
        $results = $pokedexservice->allPokemonFromPokedex('/charmander', false);
        $this->assertEquals(1, $results['total']);
        $this->assertCount(1, $results['pokemon']);
        $this->assertEquals(null, $results['proximo']);
        $this->assertEquals(null, $results['anterior']);
        $this->assertEquals('charmander', $results['pokemon'][0]['nome']);
    }

    public function testNextPokemonList()
    {
        $pokedexservice = new PokedexService();
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon?offset=60&limit=20' => Http::response(
                [
                    "count" => 3,
                    "next" => "https://pokeapi.co/api/v2/pokemon?offset=80&limit=20",
                    "previous" => "https://pokeapi.co/api/v2/pokemon?offset=40&limit=20",
                    "results" => [
                        [
                            "name" => "pidgey",
                            "url" => "https://pokeapi.co/api/v2/pokemon/16/"
                        ],
                        [
                            "name" => "rattata",
                            "url" => "https://pokeapi.co/api/v2/pokemon/19/"
                        ],
                        [
                            "name" => "zubat",
                            "url" => "https://pokeapi.co/api/v2/pokemon/41/"
                        ]
                    ]
                ]
            )
        ]);
        $results = $pokedexservice->allPokemonFromPokedex('https://pokeapi.co/api/v2/pokemon?offset=60&limit=20', true);
        $this->assertEquals(3, $results['total']);
        $this->assertCount(3, $results['pokemon']);
        $this->assertEquals('https://pokeapi.co/api/v2/pokemon?offset=80&limit=20', $results['proximo']);
        $this->assertEquals('https://pokeapi.co/api/v2/pokemon?offset=40&limit=20', $results['anterior']);
        $this->assertEquals('pidgey', $results['pokemon'][0]['nome']);
        $this->assertEquals('rattata', $results['pokemon'][1]['nome']);
        $this->assertEquals('zubat', $results['pokemon'][2]['nome']);
    }
}
