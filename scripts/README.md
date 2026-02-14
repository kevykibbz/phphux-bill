# Scripts Directory

This folder contains utility scripts, automation tools, and backup files.

## 📁 Contents

### Setup Scripts
- `setup_cronjob.ps1` - PowerShell script to configure cron jobs on Windows
- `setup_cronjob.bat` - Batch script for cron job setup

### Test Scripts
Test scripts have been moved to `tests/scripts/`

### Backup Files
- `*.backup` - Configuration backups (gitignored)
- `config.php.backup` - Config backup files
- `cookies.txt` - Cookie data (gitignored)

### Deployment Scripts
Deployment documentation is in `docs/deployment/`

## ⚠️ Security Note

**Files in this folder may contain sensitive data and should be gitignored:**
- Backup files
- Configuration copies
- Any files with credentials

## 📝 Usage

### Setting up Cron Jobs

**Windows (PowerShell):**
```powershell
.\scripts\setup_cronjob.ps1
```

**Windows (CMD):**
```cmd
scripts\setup_cronjob.bat
```

**Linux/Mac:**
```bash
# Manually add to crontab
crontab -e

# Add line:
*/5 * * * * php /path/to/phpnuxbill/system/cron.php
```

## 🔒 Gitignore Rules

The following patterns are gitignored in this folder:
- `*.backup`
- `*.tmp`
- `config.php*`
- `cookies.txt`

## 📂 Related Folders

- `tests/scripts/` - Test automation scripts
- `docs/deployment/` - Deployment documentation
- `docs/testing/` - Testing guides

---

**Keep this folder organized and secure!**
