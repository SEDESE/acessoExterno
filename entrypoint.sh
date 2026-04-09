#!/bin/bash
# Como este script roda na inicialização, as variáveis de ambiente do CapRover (MySQL) já existem aqui.

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Roda as migrations forçadamente (exigido pelo Laravel em ambiente de produção)
php artisan migrate --force

# Inicia o servidor Apache (processo principal)
exec apache2-foreground