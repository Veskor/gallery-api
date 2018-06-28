### Napomena za inicijalnu konfiguraciju ovog API

Nakon kloniranja potrebno je :

-   Instalirati dependencije pomocu `composer install`
-   Kreirati `.env` fajl (`cp .env.example .env`) i prilagoditi potrebama
-   Generisati application key `php artisan key:generate`
-   Generisati JWT secret `php artisan jwt:secret`
-   Izvrsiti DB migracije `php artisan migrate`
-   Generisati DB seedove (samo u okruzenju u kom je ovo potrebno) `php artisan db:seed`
