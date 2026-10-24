$ErrorActionPreference = 'Stop'
$Project = Split-Path -Parent $PSScriptRoot
Set-Location $Project
if (-not (Get-Command php -ErrorAction SilentlyContinue)) { Write-Host 'ERROR: PHP is not installed or not on PATH. Install PHP 8.2+ (Laravel Herd is recommended).' -ForegroundColor Red; exit 1 }
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) { Write-Host 'ERROR: Composer was not found on PATH. Install Composer, then rerun this file.' -ForegroundColor Red; exit 1 }
$mods = & php -m
if (-not ($mods -match 'pdo_sqlite')) { Write-Host 'ERROR: PHP pdo_sqlite extension must be enabled to use the provided SQLite database.' -ForegroundColor Red; exit 1 }
if (-not (Test-Path '.env')) { Copy-Item '.env.example' '.env'; Write-Host 'Created local .env' }
if (-not (Test-Path 'database/database.sqlite')) { New-Item -Path 'database/database.sqlite' -ItemType File | Out-Null }
if (-not (Test-Path 'vendor/autoload.php')) { Write-Host 'Installing Laravel dependencies...' -ForegroundColor Cyan; & composer install --no-interaction; if ($LASTEXITCODE -ne 0) { exit 1 } }
$envContents = [System.IO.File]::ReadAllText((Join-Path $Project '.env'))
if ($envContents -match '(?m)^ADMIN_PASSWORD\s*=\s*$') {
  $bytes = New-Object byte[] 24
  [System.Security.Cryptography.RandomNumberGenerator]::Create().GetBytes($bytes)
  $password = [Convert]::ToBase64String($bytes).Replace('+','A').Replace('/','B').TrimEnd('=')
  $email = 'admin@alhasm.local'
  $envContents = [Regex]::Replace($envContents,'(?m)^ADMIN_EMAIL\s*=.*$',"ADMIN_EMAIL=$email")
  $envContents = [Regex]::Replace($envContents,'(?m)^ADMIN_PASSWORD\s*=.*$',"ADMIN_PASSWORD=$password")
  [System.IO.File]::WriteAllText((Join-Path $Project '.env'),$envContents)
  @("LOCAL ONLY - DO NOT UPLOAD THIS FILE TO THE INTERNET","Login URL: http://127.0.0.1:8000/admin/login","Email: $email","Password: $password","Keep this file private. Change the password before deployment.") | Set-Content -Encoding UTF8 'LOCAL_ACCESS_NOT_FOR_UPLOAD.txt'
  Write-Host 'Generated a unique local admin password. Saved in LOCAL_ACCESS_NOT_FOR_UPLOAD.txt' -ForegroundColor Green
}
if ($envContents -match '(?m)^APP_KEY\s*=\s*$') { & php artisan key:generate --force; if ($LASTEXITCODE -ne 0) { exit 1 } }
& php artisan migrate --force
if ($LASTEXITCODE -ne 0) { exit 1 }
if (-not (Test-Path '.setup_complete')) {
  & php artisan db:seed --force
  if ($LASTEXITCODE -ne 0) { exit 1 }
  'Initial seed complete' | Set-Content '.setup_complete'
}
& php artisan storage:link 2>$null | Out-Host
Write-Host ''
Write-Host 'Installation succeeded. Starting server...' -ForegroundColor Green
