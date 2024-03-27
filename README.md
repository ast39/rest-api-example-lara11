<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

### Развертка

После скачивания репозитория требуется небольшая настройка

```
# Установка пакетов композера
$ composer install

# Создать свой .env файл на основе .env.example
$ mv -v .env.example .env
```

Теперь заполните файл .env своими настройками подключения к БД

```
# Драйвер БД
DB_CONNECTION=mysql

# Хост
DB_HOST=127.0.0.1

# Порт
DB_PORT=3306

# Название БД
DB_DATABASE=lara11

# Пользователь БД
DB_USERNAME=lara11

# Пароль к БД
DB_PASSWORD=lara11
```

Прочие настройки .env

```
# Swagger роут для DEV сервера
L5_SWAGGER_DEV_HOST=http://127.0.0.1:8000/api

# Swagger роут для PROD сервера
L5_SWAGGER_PROD_HOST=http://127.0.0.1:8001/api

# Время жизни токена авторизации
TOKEN_LIFE_TIME=86400

# Роут SWAGGER документации
APP_SWAGGER_DOCS=api/docs
```

И последний этап

```
# Генерация Swagger документации
$ php artisan l5-swagger:generate

# Запуск миграций и сидеров
$ php artisan migrate --seed
```

### Рутовая учетка
- login: admin@test.com
- password: admin
