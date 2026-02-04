$ErrorActionPreference = 'Stop'

function Test-IsAdministrator {
  $currentIdentity = [Security.Principal.WindowsIdentity]::GetCurrent()
  $principal = New-Object Security.Principal.WindowsPrincipal($currentIdentity)
  return $principal.IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
}

if (-not (Test-IsAdministrator)) {
  Write-Host "[trust-dev-ca] Restarting as Administrator..."
  $argsList = @(
    '-NoProfile',
    '-ExecutionPolicy', 'Bypass',
    '-File', ('"' + $PSCommandPath + '"')
  )
  Start-Process -FilePath 'powershell.exe' -Verb RunAs -ArgumentList $argsList | Out-Null
  exit 0
}

if (-not (Get-Command docker -ErrorAction SilentlyContinue)) {
  throw "[trust-dev-ca] docker CLI not found. Install Docker Desktop and ensure 'docker' is on PATH."
}

$containerName = 'ucc-php'
$certInContainer = '/ssl/ucc-dev-ca.crt'
$tempCert = Join-Path $env:TEMP 'ucc-dev-ca.crt'

Write-Host "[trust-dev-ca] Copying CA cert from container '$containerName'..."
& docker cp "$containerName`:$certInContainer" "$tempCert"

if (-not (Test-Path $tempCert)) {
  throw "[trust-dev-ca] Failed to copy cert to $tempCert. Is '$containerName' running? Run: docker compose up -d"
}

Write-Host "[trust-dev-ca] Importing CA into Windows Trusted Root store..."
& certutil -addstore -f 'Root' "$tempCert" | Out-Host

Write-Host ""
Write-Host "[trust-dev-ca] Done."
Write-Host "[trust-dev-ca] Restart Chrome/Edge completely (close all windows) and reopen:"
Write-Host "  - https://localhost:8443"
Write-Host "  - https://localhost:5173"

