# Task Manager

**Task Manager** to aplikacja do zarządzania zadaniami napisana w PHP z użyciem frameworka **Symfony**.

---

## Wymagania

- PHP 8.2 lub nowszy  
- Composer  
- Docker (opcjonalnie)  
- MySQL  

---

## Instalacja

### 1. Sklonuj repozytorium

```bash
git clone https://github.com/username/task-manager.git
cd task-manager
```

### 2. Uruchomienie Dockera

```bash
docker-compose up -d --build
```

To polecenie uruchomi kontenery oraz utworzy pustą bazę danych.

### 3. Wejście do kontenera aplikacji

```bash
docker exec -it symfony_app bash
```

Zainstaluj zależności:

```bash
composer install
yarn install
```

### 4. Konfiguracja bazy danych

```bash
php bin/console doctrine:database:create     # Utworzenie bazy danych (jeśli jeszcze nie istnieje)
php bin/console doctrine:migrations:migrate  # Wykonanie migracji
php bin/console doctrine:fixtures:load       # Załadowanie przykładowych danych
```

---

## Dane logowania

Aplikacja jest dostępna pod adresem: [http://localhost:8080](http://localhost:8080)

### Konta testowe:

- **Admin**
  - Email: `admin@example.com`
  - Hasło: `admin123`

- **Użytkownik**
  - Email: `user@example.com`
  - Hasło: `user123`

---

## Funkcjonalność

- Po zalogowaniu jako **admin**, masz dostęp do wszystkich funkcji systemu.
- Użytkownicy widzą jedynie **swimlines**, które są im przypisane.
- Zadania można edytować poprzez kliknięcie konkretnego zadania na liście.
- **Swimlines** są domyślną stroną startową aplikacji.

---

## Uwagi

- Swimlines wyświetlane są tylko te przypisane do zalogowanego użytkownika.
- Konto użytkownika ma ograniczony dostęp tylko do podglądu przypisanych swimlines.
