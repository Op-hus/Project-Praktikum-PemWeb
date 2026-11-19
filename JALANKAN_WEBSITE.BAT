@echo off
setlocal EnableExtensions EnableDelayedExpansion
chcp 65001 >nul
title SetaraBantu - Jalankan Website

set "PROJECT_DIR=%~dp0"
set "XAMPP_DIR=C:\xampp"
set "PHP_EXE=%XAMPP_DIR%\php\php.exe"
set "MYSQL_EXE=%XAMPP_DIR%\mysql\bin\mysql.exe"
set "PROJECT_URL=http://localhost:8000/"

cls
echo.
echo  ============================================
echo   SETARABANTU - Menyiapkan website
echo  ============================================
echo.

if not exist "%PHP_EXE%" (
    echo [ERROR] PHP XAMPP tidak ditemukan di %PHP_EXE%
    pause
    exit /b 1
)
if not exist "%MYSQL_EXE%" (
    echo [ERROR] MySQL XAMPP tidak ditemukan di %MYSQL_EXE%
    pause
    exit /b 1
)

echo [1/3] Memeriksa MySQL...
call :CHECK_PORT 3306
if not defined PORT_3306 (
    echo [INFO] Menjalankan MySQL...
    start "MySQL XAMPP" /min "%XAMPP_DIR%\mysql_start.bat"
)

for /l %%i in (1,1,15) do (
    call :CHECK_PORT 3306
    if defined PORT_3306 goto :mysql_ready
    timeout /t 1 /nobreak >nul
)
if not defined PORT_3306 (
    echo [ERROR] MySQL belum berjalan. Jalankan MySQL dari XAMPP lalu coba lagi.
    pause
    exit /b 1
)
:mysql_ready
echo [OK] MySQL siap.

echo [2/3] Menyiapkan database setarabantu_db...
"%MYSQL_EXE%" -u root -e "CREATE DATABASE IF NOT EXISTS setarabantu_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;" >nul 2>&1
set "TABLE_EXISTS="
for /f "usebackq delims=" %%A in (`"%MYSQL_EXE%" -u root -N -B -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='setarabantu_db' AND table_name='users';" 2^>nul`) do set "TABLE_EXISTS=%%A"
if not "!TABLE_EXISTS!"=="1" (
    echo [INFO] Mengimpor database.sql...
    "%MYSQL_EXE%" -u root setarabantu_db < "%PROJECT_DIR%database.sql"
    if errorlevel 1 echo [PERINGATAN] Import database gagal. Periksa MySQL.
) else (
    echo [OK] Database sudah tersedia.
)

echo [3/3] Membuka website di browser...
start "SetaraBantu PHP Server" /min "%PHP_EXE%" -S localhost:8000 -t "%PROJECT_DIR%"
set "WEB_READY="
for /l %%i in (1,1,10) do (
    call :CHECK_PORT 8000
    if defined PORT_8000 set "WEB_READY=1" & goto :web_ready
    timeout /t 1 /nobreak >nul
)
:web_ready
if not defined WEB_READY (
    echo [ERROR] Server PHP gagal berjalan di port 8000.
    echo Jalankan manual: "%PHP_EXE%" -S localhost:8000 -t "%PROJECT_DIR%"
    pause
    exit /b 1
)
start "" "%PROJECT_URL%"
echo.
echo Website aktif di: %PROJECT_URL%
echo Tutup jendela server untuk menghentikan website.
pause
exit /b 0

:CHECK_PORT
set "PORT_%~1="
powershell -NoProfile -Command "$c=New-Object Net.Sockets.TcpClient; try { $c.Connect('127.0.0.1',[int]'%~1'); exit 0 } catch { exit 1 } finally { $c.Close() }" >nul 2>&1
if not errorlevel 1 set "PORT_%~1=1"
exit /b
