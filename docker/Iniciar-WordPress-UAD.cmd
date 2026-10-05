@echo off
cd /d "%~dp0"
if not exist ".env" (
  echo Falta el archivo docker\.env.
  echo Copia .env.example como .env y cambia todas las contrasenas marcadas con CAMBIAR.
  pause
  exit /b 1
)
echo Iniciando Docker Desktop...
docker desktop start >nul 2>&1
echo Iniciando WordPress UAD...
docker compose up -d database wordpress
if errorlevel 1 (
  echo.
  echo No se pudo iniciar WordPress. Verifica que Docker Desktop este funcionando.
  pause
  exit /b 1
)
start "" "http://localhost:8088"
