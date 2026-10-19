$extension = php -m | Select-String -Pattern '^mongodb$'
if (-not $extension) {
    Write-Host 'Falta la extension mongodb de PHP. Instalala para tu version de PHP antes de continuar.'
    exit 1
}
composer require 'mongodb/laravel-mongodb:^5.11' --with-all-dependencies
if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }
php artisan optimize:clear
