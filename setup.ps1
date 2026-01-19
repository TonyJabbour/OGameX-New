# OGameX Enhanced - Setup Script for Windows
Write-Host "OGameX Enhanced - Setup" -ForegroundColor Cyan
Write-Host "=======================" -ForegroundColor Cyan

# Check Docker
try {
    docker --version | Out-Null
    Write-Host "[OK] Docker found" -ForegroundColor Green
} catch {
    Write-Host "[ERROR] Docker not found" -ForegroundColor Red
    Write-Host "Download: https://www.docker.com/products/docker-desktop" -ForegroundColor Yellow
    exit 1
}

# Check .env
if (-not (Test-Path ".env")) {
    Write-Host "Creating .env from template..." -ForegroundColor Yellow
    Copy-Item ".env.example" ".env"
    Write-Host "[WARNING] Edit .env with your database password!" -ForegroundColor Yellow
    Write-Host "Then run this script again." -ForegroundColor Yellow
    exit 0
}

# Start containers
Write-Host "Starting Docker containers..." -ForegroundColor Yellow
docker compose up -d

Write-Host ""
Write-Host "Initialization takes 5-10 minutes" -ForegroundColor Cyan
Write-Host "Monitor with: docker logs -f ogamex-app" -ForegroundColor Gray
Write-Host ""
Write-Host "Game: http://localhost" -ForegroundColor Green
Write-Host "PhpMyAdmin: http://localhost:8080" -ForegroundColor Green
