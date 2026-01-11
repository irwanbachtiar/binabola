# Deploy Update Script - BinaBola Production
# Usage: .\deploy-update.ps1 "commit message"

param(
    [string]$CommitMessage = "Update aplikasi"
)

$ErrorActionPreference = "Stop"

Write-Host "=== BinaBola Deploy to Production ===" -ForegroundColor Cyan
Write-Host ""

# 1. Git commit dan push
Write-Host "[1/5] Commit changes to GitHub..." -ForegroundColor Yellow
cd "D:\project ai\bina bola\binabola"
git add .
git commit -m "$CommitMessage"
git push origin main
Write-Host "[OK] Pushed to GitHub" -ForegroundColor Green
Write-Host ""

# 2. Create zip
Write-Host "[2/5] Creating deployment package..." -ForegroundColor Yellow
if (Test-Path "D:\binabola_update.zip") {
    Remove-Item "D:\binabola_update.zip" -Force
}
Compress-Archive -Path "D:\project ai\bina bola\binabola\*" -DestinationPath "D:\binabola_update.zip" -Force
Write-Host "[OK] Package created" -ForegroundColor Green
Write-Host ""

# 3. Upload to server
Write-Host "[3/5] Uploading to server..." -ForegroundColor Yellow
scp "D:\binabola_update.zip" binabola@103.190.214.94:~
Write-Host "[OK] Upload complete" -ForegroundColor Green
Write-Host ""

# 4. Extract and deploy
Write-Host "[4/5] Deploying on server..." -ForegroundColor Yellow
ssh -o StrictHostKeyChecking=no binabola@103.190.214.94 @"
cd /tmp
rm -rf binabola_update
mkdir binabola_update
unzip -o ~/binabola_update.zip -d binabola_update
echo 'serverBola@2026' | sudo -S rsync -av --exclude='.env' --exclude='storage/logs/*' --exclude='storage/app/public/uploads/*' /tmp/binabola_update/ /var/www/binabola/
cd /var/www/binabola
composer install --no-dev --optimize-autoloader --no-interaction
rm -rf /tmp/binabola_update
echo 'DEPLOY_SUCCESS'
"@
Write-Host "[OK] Deployment complete" -ForegroundColor Green
Write-Host ""

# 5. Clear cache
Write-Host "[5/5] Clearing Laravel cache..." -ForegroundColor Yellow
ssh -o StrictHostKeyChecking=no binabola@103.190.214.94 @"
cd /var/www/binabola
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo 'serverBola@2026' | sudo -S chown -R nginx:nginx /var/www/binabola
echo 'CACHE_CLEARED'
"@
Write-Host "[OK] Cache cleared" -ForegroundColor Green
Write-Host ""

Write-Host "=== Deployment Complete! ===" -ForegroundColor Cyan
Write-Host "URL: http://binabolapelindo.com" -ForegroundColor Green
Write-Host ""
