# PHPNuxBill Cronjob Setup Script for Windows (PowerShell)
# Run as Administrator: Right-click PowerShell → Run as Administrator
# Then run: .\setup_cronjob.ps1

Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "  PHPNuxBill Cronjob Setup (Windows)" -ForegroundColor Cyan
Write-Host "========================================`n" -ForegroundColor Cyan

# Check for admin privileges
$isAdmin = ([Security.Principal.WindowsPrincipal][Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)

if (-not $isAdmin) {
    Write-Host "ERROR: This script requires Administrator privileges!" -ForegroundColor Red
    Write-Host "Right-click PowerShell and select 'Run as administrator'" -ForegroundColor Yellow
    Write-Host "`nPress any key to exit..."
    $null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
    exit 1
}

Write-Host "Setting up PHPNuxBill scheduled tasks...`n" -ForegroundColor White

# Paths
$phpExe = "c:\xampp\php\php.exe"
$cronScript = "c:\xampp\htdocs\phpnuxbill\system\cron.php"
$reminderScript = "c:\xampp\htdocs\phpnuxbill\system\cron_reminder.php"

# Verify PHP exists
if (-not (Test-Path $phpExe)) {
    Write-Host "ERROR: PHP not found at $phpExe" -ForegroundColor Red
    Write-Host "Please update the script with correct PHP path" -ForegroundColor Yellow
    exit 1
}

# Task 1: Check expired accounts every 5 minutes
Write-Host "Creating task: PHPNuxBill-Cron..." -ForegroundColor Yellow
try {
    $action = New-ScheduledTaskAction -Execute $phpExe -Argument $cronScript
    $trigger = New-ScheduledTaskTrigger -Once -At (Get-Date) -RepetitionInterval (New-TimeSpan -Minutes 5) -RepetitionDuration ([TimeSpan]::MaxValue)
    $settings = New-ScheduledTaskSettingsSet -AllowStartIfOnBatteries -DontStopIfGoingOnBatteries -StartWhenAvailable
    
    Register-ScheduledTask -TaskName "PHPNuxBill-Cron" -Action $action -Trigger $trigger -Settings $settings -Force | Out-Null
    Write-Host "[OK] Created task: PHPNuxBill-Cron (runs every 5 minutes)" -ForegroundColor Green
} catch {
    Write-Host "[ERROR] Failed to create PHPNuxBill-Cron task: $_" -ForegroundColor Red
}

# Task 2: Send reminders daily at 8 AM
Write-Host "`nCreating task: PHPNuxBill-Reminder..." -ForegroundColor Yellow
try {
    $action = New-ScheduledTaskAction -Execute $phpExe -Argument $reminderScript
    $trigger = New-ScheduledTaskTrigger -Daily -At "08:00"
    $settings = New-ScheduledTaskSettingsSet -AllowStartIfOnBatteries -DontStopIfGoingOnBatteries -StartWhenAvailable
    
    Register-ScheduledTask -TaskName "PHPNuxBill-Reminder" -Action $action -Trigger $trigger -Settings $settings -Force | Out-Null
    Write-Host "[OK] Created task: PHPNuxBill-Reminder (runs daily at 8:00 AM)" -ForegroundColor Green
} catch {
    Write-Host "[ERROR] Failed to create PHPNuxBill-Reminder task: $_" -ForegroundColor Red
}

Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "  Setup Complete!" -ForegroundColor Cyan
Write-Host "========================================`n" -ForegroundColor Cyan

Write-Host "Verify tasks were created:" -ForegroundColor Yellow
Write-Host "  Get-ScheduledTask -TaskName 'PHPNuxBill-*'`n" -ForegroundColor White

Write-Host "Test manually:" -ForegroundColor Yellow
Write-Host "  php c:\xampp\htdocs\phpnuxbill\system\cron.php" -ForegroundColor White
Write-Host "  php c:\xampp\htdocs\phpnuxbill\system\cron_reminder.php`n" -ForegroundColor White

Write-Host "Remove tasks if needed:" -ForegroundColor Yellow
Write-Host "  Unregister-ScheduledTask -TaskName 'PHPNuxBill-Cron' -Confirm:`$false" -ForegroundColor White
Write-Host "  Unregister-ScheduledTask -TaskName 'PHPNuxBill-Reminder' -Confirm:`$false`n" -ForegroundColor White

# Show created tasks
Write-Host "Current PHPNuxBill tasks:" -ForegroundColor Cyan
Get-ScheduledTask -TaskName "PHPNuxBill-*" | Format-Table TaskName, State, @{Label="Next Run";Expression={(Get-ScheduledTaskInfo $_).NextRunTime}} -AutoSize

Write-Host "`nPress any key to exit..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
