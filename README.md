# inicie-pokedex-back
Desafio Inicie - Pokedex - back

# Requisitos
- PHP 8.2
- Docker

## Configurando

```sh
docker-compose build app
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
```

Após configura você conseguirar testar o sistema chamando o localhost na porta 8000

```sh
http://localhost:8000/ping
```

## Exemplo de retorno

Rotas
- GET /ping
- GET /pokemon

## Exemplo de retorno

```sh
{
	"total": 1,
	"proximo": "https://pokeapi.co/api/v2/pokemon?offset=40&limit=20",
	"anterior": "https://pokeapi.co/api/v2/pokemon?offset=20&limit=20",
	"pokemon": [
        {
			"imagem": "https:\/\/raw.githubusercontent.com\/PokeAPI\/sprites\/master\/sprites\/pokemon\/other\/official-artwork\/1.png",
			"nome": "bulbasaur",
			"numero": 1,
			"tipos": [
				{
					"tipo": "grass"
				},
				{
					"tipo": "poison"
				}
			],
			"altura": "70.1",
			"peso": "690.1",
			"status": [
				{
					"hp": 45
				},
				{
					"attack": 49
				},
				{
					"defense": 49
				},
				{
					"special-attack": 65
				},
				{
					"special-defense": 65
				},
				{
					"speed": 45
				}
			]
		}
    ]
}
```
