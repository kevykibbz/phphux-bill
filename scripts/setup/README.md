# Setup Scripts & Utilities

This folder contains setup scripts and configuration utilities for PHPNuxBill.

## 📁 Contents

### Database Setup Scripts

#### `paystack_setup.sql`
SQL script to configure Paystack payment gateway in the database. Adds required configuration entries to `tbl_appconfig`.

**Usage:**
```bash
mysql -u root -p phpnuxbill < paystack_setup.sql
```

#### `missing_config_setup.sql`
SQL script to add missing configuration values that may cause warnings. Includes session settings, menu hooks, and system defaults.

**Usage:**
```bash
mysql -u root -p phpnuxbill < missing_config_setup.sql
```

### PHP Configuration Utilities

#### `add_missing_configs.php`
PHP script that checks for and adds missing configuration values to the database. Provides detailed output of what was added.

**Usage:**
```bash
cd /path/to/phpnuxbill
php scripts/setup/add_missing_configs.php
```

**Features:**
- Checks existing configurations
- Only adds missing entries
- Shows detailed progress
- Safe to run multiple times

## 🎯 When to Use

### First-Time Setup
1. Run `missing_config_setup.sql` for base configuration
2. Run `paystack_setup.sql` if using Paystack gateway
3. Verify with `add_missing_configs.php`

### After Updates
- Run `add_missing_configs.php` to check for new required configs

### Troubleshooting
- Getting "Undefined array key" warnings? Run `add_missing_configs.php`
- Payment gateway not showing? Run `paystack_setup.sql`

## 🔗 Documentation

See the [setup documentation](../../docs/setup/) for detailed guides:
- [PAYSTACK_SETUP_INSTRUCTIONS.md](../../docs/setup/PAYSTACK_SETUP_INSTRUCTIONS.md)
- [PAYSTACK_QUICK_START.md](../../docs/setup/PAYSTACK_QUICK_START.md)

## ⚠️ Important Notes

- **Backup your database** before running SQL scripts
- These scripts use `INSERT IGNORE`, safe for existing configs
- Check database credentials in `config.php` first
- Scripts are located in `scripts/setup/` (not root) for organization

## 🔒 Security

These scripts may contain sensitive information and are excluded from version control via `.gitignore`.
