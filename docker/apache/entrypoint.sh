#!/bin/sh
set -eu

mkdir -p /usr/local/apache2/conf/ssl

echo "[apache] Waiting for TLS cert files..."
while [ ! -f /usr/local/apache2/conf/ssl/server.key ] || [ ! -f /usr/local/apache2/conf/ssl/server.crt ]; do
  sleep 1
done

echo "[apache] Waiting for Laravel public/index.php..."
while [ ! -f /var/www/html/public/index.php ]; do
  sleep 1
done

exec /usr/local/apache2/bin/httpd -DFOREGROUND

