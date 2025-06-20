# Task Manager

Task Manager to aplikacja do zarządzania zadaniami napisana w PHP z użyciem frameworka Symfony.

## Wymagania

- PHP 8.2 lub nowszy
- Composer
- Docker (opcjonalnie)
- MySQL

## Instalacja

1. Sklonuj repozytorium:
   git clone https://github.com/username/task-manager.git
   cd task-manager

2.Uruchamiany docker:
docker-compose up -d --build
Powinno to uruchomić kontenery i utworzyć pustą bazę danych

3. wcodzimy do aplikacji:
 docker exec -it symfony_app bash
instalujemy zależności:
composer install
yarn install

4. Baza danych
wywpołujemy
php bin/console doctrine:database:create - jeżeli baza danych nie została stworzona
php bin/console doctrine:migrations:migrate - wgrywamy migracje
php bin/console doctrine:fixtures:load - załadowanie danych

5. Dane do logowania:
Aplikacja działa na porcie: localhost:8080/
admin:
'email' => 'admin@example.com', 'password' => 'admin123'
user:
'email' => 'user@example.com', 'password' => 'user123'

6. Aplikacja:
Jeżeli zalogujemy sie do aplikacji jako admin będziemy miec dostęp do wszystkich funkcji systemu.
Jako strona startowa wyświetlają się swimeliny.
Edytować task możemy po wejściu w zadanie z poziomu listy zadań

Jeżeli zalogujemy się jako use będziemy mieli dostęp tylko do swimelines

Swimwliny wyświetlają się tylko te które są przypisane do danego urzytkownika.

