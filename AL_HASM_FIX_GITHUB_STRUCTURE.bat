@echo off
setlocal EnableExtensions
cd /d "%~dp0"
echo -----------------------------------------
echo AL HASM LUXURY V2 - GitHub Structure Fix
echo -----------------------------------------
echo.
if not exist ".git\config" (
    echo ERROR: This file must be inside the GitHub Desktop cloned al-hasm-luxury-v2 folder.
    echo Please copy this BAT file into the project folder containing the hidden .git directory.
    pause
    exit /b 1
)
findstr /i /c:"al-hasm-luxury-v2" ".git\config" >nul
if errorlevel 1 (
    echo ERROR: This folder is not connected to al-hasm-luxury-v2.
    echo For safety, no files were changed.
    pause
    exit /b 1
)
set /a MOVED=0
set /a ERRORS=0
echo Verified: correct GitHub repository.
echo Fixing folders...
echo.
call :move "env.example" ".env.example"
call :move "download (6)" ".gitignore"
call :move "extensions.json" ".vscode\extensions.json"
call :move "launch.json" ".vscode\launch.json"
call :move "tasks.json" ".vscode\tasks.json"
call :move "AdminAuthController.php" "app\Http\Controllers\AdminAuthController.php"
call :move "AdminDataController.php" "app\Http\Controllers\AdminDataController.php"
call :move "PublicDataController.php" "app\Http\Controllers\PublicDataController.php"
call :move "Agent.php" "app\Models\Agent.php"
call :move "Appointment.php" "app\Models\Appointment.php"
call :move "Deal.php" "app\Models\Deal.php"
call :move "Lead.php" "app\Models\Lead.php"
call :move "Post.php" "app\Models\Post.php"
call :move "Property.php" "app\Models\Property.php"
call :move "Setting.php" "app\Models\Setting.php"
call :move "User.php" "app\Models\User.php"
call :move "AppServiceProvider.php" "app\Providers\AppServiceProvider.php"
call :move "app.php" "bootstrap\app.php"
call :move "download (1)" "bootstrap\cache\.gitignore"
call :move "providers.php" "bootstrap\providers.php"
call :move "app (8).php" "config\app.php"
call :move "auth.php" "config\auth.php"
call :move "cache.php" "config\cache.php"
call :move "database.php" "config\database.php"
call :move "filesystems.php" "config\filesystems.php"
call :move "logging.php" "config\logging.php"
call :move "session.php" "config\session.php"
call :move "0001_01_01_000000_create_users_table.php" "database\migrations\0001_01_01_000000_create_users_table.php"
call :move "2026_10_24_000010_create_property_management_tables.php" "database\migrations\2026_10_24_000010_create_property_management_tables.php"
call :move "DatabaseSeeder.php" "database\seeders\DatabaseSeeder.php"
call :move "demo_data.json" "database\seeders\demo_data.json"
call :move "dashboard.html" "preview\dashboard.html"
call :move "index.html" "preview\index.html"
call :move "download" "public\.htaccess"
call :move "dashboard-v2.css" "public\assets\css\dashboard-v2.css"
call :move "dashboard.css" "public\assets\css\dashboard.css"
call :move "luxury-v2.css" "public\assets\css\luxury-v2.css"
call :move "site.css" "public\assets\css\site.css"
call :move "alhasm-logo.webp" "public\assets\img\alhasm-logo.webp"
call :move "architecture-1.svg" "public\assets\img\architecture-1.svg"
call :move "architecture-2.svg" "public\assets\img\architecture-2.svg"
call :move "architecture-3.svg" "public\assets\img\architecture-3.svg"
call :move "architecture-4.svg" "public\assets\img\architecture-4.svg"
call :move "architecture-5.svg" "public\assets\img\architecture-5.svg"
call :move "architecture-6.svg" "public\assets\img\architecture-6.svg"
call :move "favicon.png" "public\assets\img\favicon.png"
call :move "dashboard.js" "public\assets\js\dashboard.js"
call :move "demo-data.js" "public\assets\js\demo-data.js"
call :move "site.js" "public\assets\js\site.js"
call :move "index.php" "public\index.php"
call :move "robots.txt" "public\robots.txt"
call :move "admin.blade.php" "resources\views\admin.blade.php"
call :move "login.blade.php" "resources\views\login.blade.php"
call :move "site.blade.php" "resources\views\site.blade.php"
call :move "api.php" "routes\api.php"
call :move "console.php" "routes\console.php"
call :move "web.php" "routes\web.php"
call :move "preview-server.ps1" "scripts\preview-server.ps1"
call :move "setup.ps1" "scripts\setup.ps1"
call :move "download (7)" "storage\app\public\.gitignore"
call :move "download (4)" "storage\framework\cache\data\.gitignore"
call :move "download (3)" "storage\framework\sessions\.gitignore"
call :move "download (2)" "storage\framework\views\.gitignore"
call :move "download (5)" "storage\logs\.gitignore"
echo.
echo -----------------------------------------
echo Moved %MOVED% files. Errors: %ERRORS%
echo -----------------------------------------
if not "%ERRORS%"=="0" (
  echo Some files need attention. Please send the screenshot.
) else (
  echo SUCCESS: Laravel project folders restored.
  echo Return to GitHub Desktop: Commit to main, then Push origin.
)
echo.
pause
exit /b

:move
if not exist "%~1" (
  if not exist "%~2" (
    echo MISSING: %~1
    set /a ERRORS+=1
  )
  exit /b 0
)
if exist "%~2" (
  echo SKIPPED (already exists): %~2
  exit /b 0
)
for %%G in ("%~2") do if not exist "%%~dpG" mkdir "%%~dpG" >nul
move "%~1" "%~2" >nul
if errorlevel 1 (
  echo FAILED: %~1
  set /a ERRORS+=1
) else (
  set /a MOVED+=1
)
exit /b 0
