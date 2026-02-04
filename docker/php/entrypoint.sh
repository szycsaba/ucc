#!/bin/sh
set -eu

mkdir -p /ssl

# --- Local dev CA (trust this once on host) ---
if [ ! -f /ssl/ca.key ] || [ ! -f /ssl/ca.crt ]; then
  echo "[php] Generating local dev CA (trust this once on host)..."
  openssl req -x509 -newkey rsa:2048 -sha256 -days 3650 -nodes \
    -subj "/CN=ucc-dev-ca" \
    -addext "basicConstraints=critical,CA:TRUE" \
    -addext "keyUsage=critical,keyCertSign,cRLSign" \
    -keyout /ssl/ca.key -out /ssl/ca.crt
  cp /ssl/ca.crt /ssl/ucc-dev-ca.crt 2>/dev/null || true
fi

# --- Server cert signed by dev CA (for localhost) ---
if [ ! -f /ssl/server.key ] || [ ! -f /ssl/server.crt ]; then
  echo "[php] Generating TLS cert for localhost signed by dev CA..."
  cat > /ssl/server.ext <<'EOF'
basicConstraints=CA:FALSE
keyUsage=critical,digitalSignature,keyEncipherment
extendedKeyUsage=serverAuth
subjectAltName=DNS:localhost,IP:127.0.0.1
EOF
  openssl req -new -newkey rsa:2048 -nodes \
    -subj "/CN=localhost" \
    -keyout /ssl/server.key -out /ssl/server.csr
  openssl x509 -req -sha256 -days 825 \
    -in /ssl/server.csr \
    -CA /ssl/ca.crt -CAkey /ssl/ca.key -CAcreateserial \
    -out /ssl/server.crt -extfile /ssl/server.ext
  rm -f /ssl/server.csr /ssl/server.ext 2>/dev/null || true
fi

# --- Laravel bootstrap (only if project exists / generated) ---
if [ ! -f /var/www/html/artisan ]; then
  echo "[php] ./api looks empty -> creating Laravel 12 project..."
  rm -rf /tmp/laravel-init
  mkdir -p /tmp/laravel-init
  composer create-project --no-interaction laravel/laravel:^12.0 /tmp/laravel-init
  cp -a /tmp/laravel-init/. /var/www/html/
fi

if [ -f /var/www/html/composer.json ]; then
  if [ ! -d /var/www/html/vendor ] || [ -z "$(ls -A /var/www/html/vendor 2>/dev/null || true)" ]; then
    echo "[php] Installing backend dependencies (composer install)..."
    composer install --no-interaction --prefer-dist
  fi

  # Fix common bind-mount permissions for Laravel runtime dirs
  mkdir -p /var/www/html/storage/framework/cache /var/www/html/storage/framework/sessions /var/www/html/storage/framework/views /var/www/html/bootstrap/cache
  chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
  chmod -R ug+rwX /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
  # Docker Desktop/Windows bind mounts may ignore chown/chmod; dev fallback.
  chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

  if [ -f /var/www/html/.env ] && grep -q "^APP_KEY=$" /var/www/html/.env; then
    echo "[php] Generating Laravel APP_KEY (php artisan key:generate)..."
    php /var/www/html/artisan key:generate --no-interaction
  fi
else
  echo "[php] WARNING: ./api/composer.json not found. Create your Laravel project in ./api first."
fi

exec php-fpm -F

