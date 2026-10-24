@echo off
title AL HASM - Instant Browser Preview
cd /d "%~dp0"
start "AL HASM Preview Server" powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\preview-server.ps1"
timeout /t 2 >nul
start "" "http://127.0.0.1:8844/preview/index.html"
echo.
echo Website:   http://127.0.0.1:8844/preview/index.html
echo Dashboard: http://127.0.0.1:8844/preview/dashboard.html
echo The preview uses a built-in PowerShell server. No PHP or Composer required.
pause
