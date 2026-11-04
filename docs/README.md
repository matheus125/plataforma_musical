# Plataforma de Performance Musical Competitiva

MVP com Laravel 11 API, Next.js PWA, PostgreSQL e Redis.

## Escopo implementado

- Autenticacao por Laravel Sanctum com token Bearer.
- Cadastro, login, `/api/me` e logout.
- Catalogo de musicas, detalhe de musica e envio de audio para performance.
- Feed publico e detalhe de performance.
- Storage local com abstracao preparada para S3/R2.
- Fundacao de tabelas, services, jobs e events para IA vocal, rankings, desafios, monetizacao, analytics e internacionalizacao.
- PWA basico com manifest e service worker.
- Docker Compose com PostgreSQL, Redis, API Laravel e frontend Next.js.

## Documentos

- [Architecture Foundation](architecture-foundation.md)

## Fora do MVP

IA real, pagamentos reais, ranking exibido na UI, desafios exibidos na UI e real-time foram deixados fora de proposito.

## Rodando com Docker

```bash
cd infra
docker compose up --build
```

API: `http://localhost:8000/api`

Frontend: `http://localhost:3000`

Usuario seed: `test@example.com` / `password`

## Observacao sobre Laravel 11

O Composer atual bloqueia algumas versoes 11.x por advisories do Packagist. Como o requisito foi Laravel 11, o projeto fixa Laravel 11 e deixa `audit.block-insecure=false` em `backend/composer.json`. Em producao, revise `composer audit` e atualize quando houver versao 11.x sem advisory ou planeje upgrade para a linha suportada mais recente.
