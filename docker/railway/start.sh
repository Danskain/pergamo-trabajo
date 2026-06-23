#!/usr/bin/env sh
set -eu

PORT="${PORT:-8080}"
DB_WAIT_TIMEOUT="${DB_WAIT_TIMEOUT:-90}"

mkdir -p   /var/www/html/storage/framework/cache   /var/www/html/storage/framework/sessions   /var/www/html/storage/framework/views   /var/www/html/storage/logs   /var/www/html/bootstrap/cache

wait_for_database() {
  if [ "${DB_CONNECTION:-}" != "mysql" ] || [ -z "${DB_HOST:-}" ]; then
    return 0
  fi

  echo "Esperando la base de datos ${DB_HOST}:${DB_PORT:-3306}..."

  start_time=$(date +%s)

  while true; do
    if php -r '
      $host = getenv("DB_HOST") ?: "127.0.0.1";
      $port = getenv("DB_PORT") ?: "3306";
      $database = getenv("DB_DATABASE") ?: "";
      $username = getenv("DB_USERNAME") ?: "";
      $password = getenv("DB_PASSWORD") ?: "";

      try {
          new PDO(
              sprintf("mysql:host=%s;port=%s;dbname=%s", $host, $port, $database),
              $username,
              $password,
              [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
          );
          exit(0);
      } catch (Throwable $exception) {
          fwrite(STDERR, $exception->getMessage());
          exit(1);
      }
    ' >/dev/null 2>&1; then
      echo "Base de datos disponible."
      return 0
    fi

    current_time=$(date +%s)
    elapsed=$((current_time - start_time))

    if [ "$elapsed" -ge "$DB_WAIT_TIMEOUT" ]; then
      echo "No se pudo conectar a la base de datos dentro de ${DB_WAIT_TIMEOUT}s."
      return 1
    fi

    sleep 2
  done
}

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
  wait_for_database
  php artisan migrate --force
fi

php artisan storage:link >/dev/null 2>&1 || true

exec php -S 0.0.0.0:"${PORT}" -t public public/index.php
