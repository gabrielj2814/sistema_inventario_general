# Requerimientos para poder instalar el sistema
- Docker XD

## Instalación

recomiendo que creen el archivo .env si no esta creado, pueden usar de ejemplo el archivo .example.env y los sigueintes datos son solo de ejemplo que puedes usar, recomiendo solo cambiar lo que tena el texto "cambiar" del resto puede dejar lo demas como esta.
``` env
DB_PASSWORD_CUSTOM="cambiar"
DB_CONNECTION=mysql
DB_HOST=db-mysql
DB_PORT=3306
DB_DATABASE=inventario_db
DB_USERNAME=root
DB_PASSWORD="${DB_PASSWORD_CUSTOM}"
DOCKER_CACHE_PORT="6003:6379"
L5_SWAGGER_GENERATE_ALWAYS="true"
DEBUG_PASSWORD_ADMIN=cambiar
DOCKER_PHP_PORT="9202:9000"
JWT_KEY="cambiar"
```
Para poder levantar el sistema con docker se requiuere del siguiente comando.
```shell
docker compose -f docker-compose-dev.yml up -d
```
Recomiendo que en este punto crees la base de datos usando el mismo nombre que configuraste en .env, puedes usar PhpMyAdmin para poder crear la base de datos o cualquier herramienta que te permita conectarte al servicio de MySQL. 

Y ya aviendo creado la base de datos entraremos al contenedor de php para poder instalar las dependencias de proyecto laravel.
```shell
composer install
```
Ahora precederemos a generar el key del proyecto laravel y lo haremos con el siguiente comando.
```shell
php artisan key:generate
```

Ahora que ya instalamos las dependecias de laravel daremos de daja los servicios para luego, volverlos a levantar otra vez, esto con el objetivo que el contendor de la api se levante correctamente,
ya que cuando levantamos los servicios por primera vez el contenedor de la api, no tenia las dependecias de laravel instaladas motivo por el cual, el servicio no se levanto correctamente.
```shell
docker compose -f docker-compose-dev.yml down
docker compose -f docker-compose-dev.yml up -d
```
Con esto procederemos a crear el usuario Root con el que podras entrar al sistema esto lo haremos desde el contenedor de php y la clave del usuario sera la que esta en la variable de entorno DEBUG_PASSWORD_ADMIN.
```shell
php artisan app:root-default
```












<!-- <p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT). -->


