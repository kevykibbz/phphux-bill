# Paystack Gateway - Quick Install Script
# Run this from PHPNuxBill root: .\scripts\setup\paystack_quick_install.ps1

Write-Host ""
Write-Host "═══════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host "  PAYSTACK GATEWAY - QUICK INSTALLER" -ForegroundColor Cyan
Write-Host "═══════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host ""

# Check if running from correct directory
if (-Not (Test-Path ".\system\paymentgateway\paystack.php")) {
    Write-Host "✗ ERROR: Please run this script from PHPNuxBill root directory" -ForegroundColor Red
    Write-Host ""
    Write-Host "Usage:" -ForegroundColor Yellow
    Write-Host "  cd c:\xampp\htdocs\phpnuxbill" -ForegroundColor Yellow
    Write-Host "  .\scripts\setup\paystack_quick_install.ps1" -ForegroundColor Yellow
    Write-Host ""
    exit 1
}

Write-Host "Step 1: Checking required files..." -ForegroundColor Yellow
Write-Host ""

$files = @(
    "system\paymentgateway\paystack.php",
    "system\paymentgateway\paystack_install.php",
    "api\callback\paystack.ts",
    "vercel.json",
    "package.json"
)

$allFilesExist = $true
foreach ($file in $files) {
    if (Test-Path $file) {
        Write-Host "  ✓ $file" -ForegroundColor Green
    } else {
        Write-Host "  ✗ $file (MISSING)" -ForegroundColor Red
        $allFilesExist = $false
    }
}

if (-Not $allFilesExist) {
    Write-Host ""
    Write-Host "✗ Some required files are missing. Please ensure all plugin files are copied." -ForegroundColor Red
    Write-Host ""
    exit 1
}

Write-Host ""
Write-Host "Step 2: Running auto-installer..." -ForegroundColor Yellow
Write-Host ""

# Run PHP installer
php system\paymentgateway\paystack_install.php

Write-Host ""
Write-Host "Step 3: Configuration checklist" -ForegroundColor Yellow
Write-Host ""

# Check if config.php exists
if (Test-Path "config.php") {
    Write-Host "  ✓ PHPNuxBill config.php found" -ForegroundColor Green
} else {
    Write-Host "  ⚠ config.php not found - Is PHPNuxBill installed?" -ForegroundColor Yellow
}

# Check if Vercel is installed
try {
    $vercelVersion = vercel --version 2>&1
    Write-Host "  ✓ Vercel CLI installed: $vercelVersion" -ForegroundColor Green
} catch {
    Write-Host "  ⚠ Vercel CLI not installed (needed for callback deployment)" -ForegroundColor Yellow
    Write-Host "    Install with: npm install -g vercel" -ForegroundColor Gray
}

Write-Host ""
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
Write-Host "  NEXT STEPS" -ForegroundColor Cyan
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
Write-Host ""

Write-Host "1. Get Paystack API Keys:" -ForegroundColor White
Write-Host "   • Visit: https://dashboard.paystack.com" -ForegroundColor Gray
Write-Host "   • Go to: Settings -- API Keys and Webhooks" -ForegroundColor Gray
Write-Host "   • Copy: Secret Key (sk_test_xxx) and Public Key (pk_test_xxx)" -ForegroundColor Gray
Write-Host ""

Write-Host "2. Configure in Admin Panel:" -ForegroundColor White
Write-Host "   • Login to PHPNuxBill admin" -ForegroundColor Gray
Write-Host "   • Go to: Settings -- Payment Gateway -- Paystack" -ForegroundColor Gray
Write-Host "   • Enter your API keys" -ForegroundColor Gray
Write-Host "   • Enable Paystack: Yes" -ForegroundColor Gray
Write-Host "   • Save Changes" -ForegroundColor Gray
Write-Host ""

Write-Host "3. Deploy Callback to Vercel:" -ForegroundColor White
Write-Host "   • Run: vercel --prod" -ForegroundColor Gray
Write-Host "   • Add env: vercel env add PAYSTACK_SECRET_KEY production" -ForegroundColor Gray
Write-Host "   • Paste your secret key when prompted" -ForegroundColor Gray
Write-Host "   • Redeploy: vercel --prod" -ForegroundColor Gray
Write-Host ""

Write-Host "4. Test Payment:" -ForegroundColor White
Write-Host "   • Create test plan (low price: 3 KES)" -ForegroundColor Gray
Write-Host "   • Make test payment" -ForegroundColor Gray
Write-Host "   • Test card: 4084084084084081 (any CVV/expiry)" -ForegroundColor Gray
Write-Host "   • Verify success page appears" -ForegroundColor Gray
Write-Host ""

Write-Host "═══════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host ""

Write-Host "For detailed documentation, see:" -ForegroundColor Yellow
Write-Host "  system/paymentgateway/PAYSTACK_README.md" -ForegroundColor Gray
Write-Host ""
