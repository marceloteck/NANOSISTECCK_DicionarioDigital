@echo off
title Iniciando ambiente de desenvolvimento (Vite / npm)

echo ================================
echo INICIANDO NPM RUN DEV...
echo ================================

:: Vai para o diretório do projeto (onde está o package.json)
cd /d %~dp0

:: Verifica se o node está instalado
where node >nul 2>nul
if %errorlevel% neq 0 (
    echo ERRO: Node.js nao encontrado!
    pause
    exit /b
)

:: Verifica se o npm está instalado
where npm >nul 2>nul
if %errorlevel% neq 0 (
    echo ERRO: npm nao encontrado!
    pause
    exit /b
)

:: Instala dependencias se nao existir node_modules
if not exist node_modules (
    echo Instalando dependencias...
    npm install
)

:: Executa o dev
echo Iniciando servidor de desenvolvimento...
npm run dev

pause