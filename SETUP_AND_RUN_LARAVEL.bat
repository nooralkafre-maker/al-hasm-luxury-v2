@echo off
chcp 65001 >nul
title AL HASM Properties - Laravel Setup
cd /d "%~dp0"
echo ============================================
echo  AL HASM - Laravel Website + Admin Dashboard
 echo ============================================
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\setup.ps1"
if errorlevel 1 (
 echo.
 echo Setup failed. Review the message above.
 pause
 exit /b 1
)
start "AL HASM Laravel Server" /D "%~dp0" cmd /k "php artisan serve --host=127.0.0.1 --port=8000"
timeout /t 3 >nul
start "" "http://127.0.0.1:8000"
echo.
echo Website opened: http://127.0.0.1:8000
echo Dashboard login: http://127.0.0.1:8000/admin/login
echo Local credentials can be found in LOCAL_ACCESS_NOT_FOR_UPLOAD.txt
pause
