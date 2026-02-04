# ucc
Secure event manager with REST API and separate frontend, featuring login + password reset, per-user event CRUD, and an AI-powered helpdesk chat including agent takeover and conversation history.

## Install (Docker)

### Prerequisites
- Docker Desktop (Windows)

### First run
1) Rename `.env.example` to `.env`.

2) Start containers:

```bash
docker compose up -d
```

3) Trust the local dev CA certificate (removes "Not secure" warning in Chrome/Edge for `https://localhost:*`).
This step will prompt for Administrator privileges (Windows certificate store).

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File ".\scripts\trust-dev-ca.ps1"
```

### URLs
- Backend (Laravel API): `https://localhost:8443`
- Frontend (Vite dev): `https://localhost:5173`
- phpMyAdmin: `http://localhost:8080`
