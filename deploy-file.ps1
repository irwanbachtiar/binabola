# Quick Deploy Script - Single File Update
# Usage: .\deploy-file.ps1 "path/to/file.php" "commit message"

param(
    [Parameter(Mandatory=$true)]
    [string]$FilePath,
    [string]$CommitMessage = "Update file"
)

$ErrorActionPreference = "Stop"

Write-Host "=== Quick File Deploy ===" -ForegroundColor Cyan
Write-Host "File: $FilePath" -ForegroundColor White
Write-Host ""

# 1. Git commit
Write-Host "[1/3] Commit to GitHub..." -ForegroundColor Yellow
cd "D:\project ai\bina bola\binabola"
git add $FilePath
git commit -m "$CommitMessage"
git push origin main
Write-Host "✓ Pushed" -ForegroundColor Green
Write-Host ""

# 2. Upload file
Write-Host "[2/3] Upload file to server..." -ForegroundColor Yellow
scp "D:\project ai\bina bola\binabola\$FilePath" binabola@103.190.214.94:/tmp/
$FileName = Split-Path $FilePath -Leaf
Write-Host "✓ Uploaded" -ForegroundColor Green
Write-Host ""

# 3. Deploy file
Write-Host "[3/3] Deploy file..." -ForegroundColor Yellow
$RemotePath = $FilePath -replace '\\', '/'
ssh -o StrictHostKeyChecking=no binabola@103.190.214.94 @"
echo 'serverBola@2026' | sudo -S cp /tmp/$FileName /var/www/binabola/$RemotePath
echo 'serverBola@2026' | sudo -S chown nginx:nginx /var/www/binabola/$RemotePath
cd /var/www/binabola
php artisan view:clear
php artisan view:cache
echo 'FILE_DEPLOYED'
"@
Write-Host "✓ Done" -ForegroundColor Green
Write-Host ""

Write-Host "=== File Deployed! ===" -ForegroundColor Cyan
