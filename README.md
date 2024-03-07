# inicie-pokedex-back
Desafio Inicie - Pokedex - back

# Requisitos
- PHP 8.2
- Docker


docker-compose build app
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app php artisan key:generate