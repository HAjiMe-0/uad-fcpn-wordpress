@echo off
cd /d "%~dp0"
echo Deteniendo WordPress UAD...
docker compose stop
echo Listo. Los documentos y la base de datos se conservaron.
pause

