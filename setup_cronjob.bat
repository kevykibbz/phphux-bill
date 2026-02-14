@echo off
REM PHPNuxBill Cronjob Setup Script for Windows
REM Run this script as Administrator to setup scheduled tasks

echo ========================================
echo  PHPNuxBill Cronjob Setup (Windows)
echo ========================================
echo.

REM Check for admin privileges
net session >nul 2>&1
if %errorLevel% neq 0 (
    echo ERROR: This script requires Administrator privileges!
    echo Right-click and select "Run as administrator"
    pause
    exit /b 1
)

echo Setting up PHPNuxBill scheduled tasks...
echo.

REM Task 1: Check expired accounts every 5 minutes
schtasks /create /tn "PHPNuxBill-Cron" /tr "c:\xampp\php\php.exe c:\xampp\htdocs\phpnuxbill\system\cron.php" /sc minute /mo 5 /f
if %errorLevel% equ 0 (
    echo [OK] Created task: PHPNuxBill-Cron (runs every 5 minutes^)
) else (
    echo [ERROR] Failed to create PHPNuxBill-Cron task
)

echo.

REM Task 2: Send reminders daily at 8 AM
schtasks /create /tn "PHPNuxBill-Reminder" /tr "c:\xampp\php\php.exe c:\xampp\htdocs\phpnuxbill\system\cron_reminder.php" /sc daily /st 08:00 /f
if %errorLevel% equ 0 (
    echo [OK] Created task: PHPNuxBill-Reminder (runs daily at 8:00 AM^)
) else (
    echo [ERROR] Failed to create PHPNuxBill-Reminder task
)

echo.
echo ========================================
echo  Setup Complete!
echo ========================================
echo.
echo To verify tasks were created:
echo   schtasks /query /tn "PHPNuxBill-Cron"
echo   schtasks /query /tn "PHPNuxBill-Reminder"
echo.
echo To test manually:
echo   php c:\xampp\htdocs\phpnuxbill\system\cron.php
echo   php c:\xampp\htdocs\phpnuxbill\system\cron_reminder.php
echo.
echo To remove tasks:
echo   schtasks /delete /tn "PHPNuxBill-Cron" /f
echo   schtasks /delete /tn "PHPNuxBill-Reminder" /f
echo.
pause
