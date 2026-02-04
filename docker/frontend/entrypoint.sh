#!/bin/sh
set -eu

mkdir -p /ssl

# Ensure we have a cert for Vite HTTPS (shared named volume with Apache).
if [ ! -f /ssl/server.key ] || [ ! -f /ssl/server.crt ]; then
  echo "[frontend] Generating self-signed TLS cert (dev only)..."
  openssl req -x509 -newkey rsa:2048 -sha256 -days 3650 -nodes \
    -subj "/CN=localhost" \
    -addext "subjectAltName=DNS:localhost,IP:127.0.0.1" \
    -keyout /ssl/server.key -out /ssl/server.crt
fi

if [ ! -f /app/package.json ]; then
  echo "[frontend] ./frontend looks empty -> creating Vite + React project (non-interactive)..."

  rm -rf /tmp/vite-init
  mkdir -p /tmp/vite-init

  # Non-interactive scaffold: fetch template directly (no TTY prompts)
  # Template source: Vite monorepo create-vite templates
  npx --yes degit vitejs/vite/packages/create-vite/template-react /tmp/vite-init

  # Copy into the bind-mounted project dir
  cp -a /tmp/vite-init/. /app/
fi

# Ensure Vite uses HTTPS with our certs (do not require user edits).
if [ ! -f /app/vite.config.js ] || ! grep -q "/ssl/server.key" /app/vite.config.js; then
  echo "[frontend] Writing Vite HTTPS config..."
  cat > /app/vite.config.js <<'EOF'
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import fs from 'node:fs'

export default defineConfig({
  plugins: [react()],
  server: {
    host: true,
    port: 5173,
    strictPort: true,
    https: {
      key: fs.readFileSync('/ssl/server.key'),
      cert: fs.readFileSync('/ssl/server.crt'),
    },
    hmr: {
      protocol: 'wss',
      host: 'localhost',
      clientPort: 5173,
    },
  },
})
EOF
fi

if [ ! -d /app/node_modules ] || [ -z "$(ls -A /app/node_modules 2>/dev/null || true)" ]; then
  echo "[frontend] Installing frontend dependencies (npm install)..."
  npm install
fi

exec npm run dev -- --host 0.0.0.0 --port 5173

