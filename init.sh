#!/bin/bash

APP_DIR="./task-manager"

echo "🚀 Inicjalizacja projektu Symfony 6.4 LTS..."

if [ ! -d "$APP_DIR" ]; then
  echo "Tworzę katalog $APP_DIR..."
  mkdir -p "$APP_DIR"
fi

if [ -z "$(ls -A $APP_DIR)" ]; then
  echo "Katalog jest pusty - tworzenie nowego projektu Symfony..."

  docker-compose run --rm app bash -c "cd /var/www/html && composer self-update && composer clear-cache && composer create-project symfony/skeleton:\"6.4.*\" ."

  echo "Projekt Symfony utworzony."
else
  echo "Katalog nie jest pusty - pomijam tworzenie projektu Symfony."
fi

echo "Instalacja zależności Node i Yarn..."
docker-compose exec app yarn install

echo "Instalacja zależności PHP przez composer..."
docker-compose exec app composer install --no-interaction --optimize-autoloader

echo "Ustawianie uprawnień..."
docker-compose exec app chown -R www-data:www-data var

echo "✅ Gotowe! Możesz uruchomić aplikację komendą: docker-compose up -d"
