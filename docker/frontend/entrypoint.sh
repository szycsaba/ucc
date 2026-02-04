#!/bin/sh
set -eu

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

if [ ! -d /app/node_modules ] || [ -z "$(ls -A /app/node_modules 2>/dev/null || true)" ]; then
  echo "[frontend] Installing frontend dependencies (npm install)..."
  npm install
fi

exec npm run dev -- --host 0.0.0.0 --port 5173

