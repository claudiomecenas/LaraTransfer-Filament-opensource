[![Print ex](https://penseon.com.br/github/laratransfer/prints-laratransfer.jpg)](https://penseon.com.br/github/laratransfer/prints-laratransfer.jpg)  

[![Print ex](https://penseon.com.br/github/laratransfer/prints-laratransfer-2.jpg)](https://penseon.com.br/github/laratransfer/prints-laratransfer-2.jpg)  

# LaraTransfer
### Sistema Gestor de Tranfers
Desenvolvido para a empresa Seu Transfer Ltda  
Docker, Laravel 10, PHP 8.1, Filament v3  
Versçao em desenvolvimento  
Versão beta na branch "hostgator-deploy"  
By claudio@penseon.com.br  

### Como instalar: Passo a passo
Clone Repositório
```sh
git clone -b main git@github.com:claudiomecenas/LaraTransfer-2024.git LaraTransfer
```
```sh
cd LaraTransfer
```


Crie o Arquivo .env
```sh
cp .env.example .env
```


Atualize as variáveis de ambiente do arquivo .env
```dosini
APP_NAME=LaraTransfer
APP_URL=http://localhost:8989

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=nome_que_desejar_db
DB_USERNAME=root
DB_PASSWORD=root

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```


Suba os containers do projeto
```sh
docker compose up -d
```


Acesse o container app
```sh
docker compose exec app bash
```


Instale as dependências do projeto
```sh
composer install
```


Gere a key do projeto Laravel
```sh
php artisan key:generate
```


Execute as Migrations
```sh
php artisan migrate
```

Acesse o projeto
[http://localhost:8989](http://localhost:8989)  


Execute fora do Container
```sh
npm install
npm run dev
```
