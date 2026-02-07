#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

# 1) Bootstrap Laravel if not present.
if [ ! -f artisan ]; then
  tmp="/tmp/laravel_app"
  rm -rf "$tmp"
  composer create-project laravel/laravel "$tmp" --no-interaction --prefer-dist

  rsync -a \
    --exclude '.git/' \
    --exclude '.idea/' \
    --exclude 'README.md' \
    --exclude 'docker/' \
    --exclude 'challenge-overlay/' \
    "$tmp"/ /var/www/html/

  rm -rf "$tmp"
fi

# 2) Ensure env exists
if [ ! -f .env ]; then
  cp .env.example .env
fi

# 3) Force SQLite config in .env
# Using APP_ENV=local. Tests use their own config (RefreshDatabase) but we also keep local simple.
php -r '
$path = ".env";
$env = file_get_contents($path);
$replacements = [
  "DB_CONNECTION" => "sqlite",
  "DB_DATABASE" => "/var/www/html/database/database.sqlite",
];
foreach ($replacements as $k => $v) {
  if (preg_match("/^{$k}=.*$/m", $env)) {
    $env = preg_replace("/^{$k}=.*$/m", "{$k}={$v}", $env);
  } else {
    $env .= "\n{$k}={$v}";
  }
}
file_put_contents($path, $env);
'

# 4) Ensure SQLite file exists
mkdir -p database
if [ ! -f database/database.sqlite ]; then
  touch database/database.sqlite
fi

# 5) Copy challenge overlay ONCE (so we never overwrite candidate edits)
if [ ! -f .challenge_initialized ]; then
  echo "Applying code challenge overlay..."
  rsync -a challenge-overlay/ ./
  touch .challenge_initialized
fi

# 6) Install dependencies (idempotent)
composer install --no-interaction --prefer-dist

# 7) Generate app key if missing/blank
php -r '
$env = file_get_contents(".env");
if (!preg_match("/^APP_KEY=(.*)$/m", $env, $m)) { exit(1); }
$k = trim($m[1]);
if ($k === "" || $k === "base64:") { exit(1); }
exit(0);
' || php artisan key:generate

# 8) Migrate DB for local usage
php artisan migrate --force

echo "Container ready."
echo "Run tests: docker compose exec app php artisan test"

# 9) Serve (optional).
php artisan serve --host=0.0.0.0 --port=8080
