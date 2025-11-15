# Run Database Migrations Script
# This script will apply all pending migrations to the SHooad database

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "SHooad Database Migration Tool" -ForegroundColor Cyan
Write-Host "========================================`n" -ForegroundColor Cyan

$dbName = "SHooad"
$dbUser = "root"
$dbPass = ""
$mysqlPath = "C:\xampp\mysql\bin\mysql.exe"

# Check if MySQL is accessible
if (-not (Test-Path $mysqlPath)) {
    Write-Host "ERROR: MySQL not found at $mysqlPath" -ForegroundColor Red
    Write-Host "Please update the `$mysqlPath variable in this script." -ForegroundColor Yellow
    exit 1
}

Write-Host "Database: $dbName" -ForegroundColor Green
Write-Host "User: $dbUser`n" -ForegroundColor Green

# Migration files to run
$migrations = @(
    "migration_add_shipping_fee.sql",
    "fix_cart_selected.sql"
)

foreach ($migration in $migrations) {
    $filePath = Join-Path $PSScriptRoot $migration
    
    if (Test-Path $filePath) {
        Write-Host "Running migration: $migration" -ForegroundColor Yellow
        
        $cmd = "& `"$mysqlPath`" -u $dbUser -D $dbName < `"$filePath`""
        
        try {
            Get-Content $filePath | & $mysqlPath -u $dbUser $dbName 2>&1 | Out-Null
            Write-Host "  ✓ Success`n" -ForegroundColor Green
        } catch {
            Write-Host "  ✗ Failed: $_`n" -ForegroundColor Red
        }
    } else {
        Write-Host "  ⚠ File not found: $filePath`n" -ForegroundColor Yellow
    }
}

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Migration process completed!" -ForegroundColor Cyan
Write-Host "========================================`n" -ForegroundColor Cyan

Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Verify the changes in phpMyAdmin" -ForegroundColor White
Write-Host "2. Test adding products to cart" -ForegroundColor White
Write-Host "3. Test checkout process`n" -ForegroundColor White
