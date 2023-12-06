# GP-API

Para executar a api em ambiente de desenvolvimento, você deve atender os requisitos, são eles:

- PHP 8.1.25
- Composer 2.2.18
## Instalação

Clone o repositorio e acesse a raiz do projeto, e execute a instalação das dependências usando composer.

```bash
cd gp-api
composer install
```

Crie uma cópia do arquivo.env.example, renomeando para .env
```bash
cp .env.example .env
```
Agora crie uma key com o comando
```bash
php artisan key:generate
```
Abra o .env e configure as credenciais do banco de dados de acordo, ela possuem o prefixo DB_

Para criar as tabelas use:
```bash
php artisan migrate
```
Para garantir o bom funcionamento do storage e uploads de imagens, execute:
```bash
php artisan storage:link
```
Para popular com dados fakes use:
```bash
php artisan db:seed
```
E por fim, para executar o ambiente de desenvolvimento execute:
```bash
php artisan serve
```
