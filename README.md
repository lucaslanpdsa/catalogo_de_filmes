# Catalogo de filmes

## Pré-requisitos

- Docker e Docker Compose instalados

## Configuração

1. Clone o repositório:
```bash
git clone https://github.com/lucaslanpdsa/catalogo_de_filmes.git
cd catalogo_de_filmes
```
2. Crie um arquivo `.env` baseado no `.env.example`
```bash
cp .env.example .env
```

## Executando o projeto

1. Inicie os containers:
```bash
docker compose up -d --build
```
2. Execute as migrations:
```bash
docker compose exec app php artisan migrate
```

## Principais arquivos

- `Http/Controllers/Api/FavoriteController.php`: Controller de favoritos
- `app/Models/Favorite.php`: Model de favoritos
- `routes/api.php`: Rotas da api

## Endpoints

- `GET /api/favorites`: Lista filmes (com filtros por genero, barra de pesquisa e possibilidade de favoritar).
- `POST /api/favorites`: Recebe os dados de um filme que será adicionado aos favoritos.
- `DELETE /api/favorites/{id}`: Deleta um filme pelo seu id.

<details>
 <summary>Video mostrando como rodar o projeto no local</summary>
 https://www.veed.io/view/985bd424-fa8b-42a8-9c00-108f10665041?panel=share
</details>

