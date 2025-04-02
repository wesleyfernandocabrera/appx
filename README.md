### Passo a passo
Clone Repositório
```sh
git clone -b https://github.com/wesleyfernandocabrera/appx.git 
```
```sh
cd appx
```

Suba os containers do projeto
```sh
docker-compose up -d
```


Crie o Arquivo .env
```sh
cp .env.example .env
```

Acesse o container app
```sh
docker-compose exec app bash
```


Instale as dependências do projeto
```sh
composer install
```

Gere a key do projeto Laravel
```sh
php artisan key:generate
```

OPCIONAL: Gere o banco SQLite (caso não use o banco MySQL)
```sh
touch database/database.sqlite
```

Rodar as migrations
```sh
php artisan migrate
```

Acesse o projeto
[http://localhost:8000](http://localhost:8000)



#criar usuario via portal register

#rodar os insert para cria as permissão
INSERT INTO roles (id, name, created_at, updated_at)  
VALUES (1, 'admin', NOW(), NOW());


INSERT INTO roles (id, name, created_at, updated_at)  
VALUES (2, 'view', NOW(), NOW());

INSERT INTO roles (id, name, created_at, updated_at)  
VALUES (3, 'editor', NOW(), NOW());



#rodar os insert para setar a permissão

INSERT INTO role_user
(user_id, role_id)
VALUES(1, 1);
