# 📁 PHPNuxBill - Organized Codebase Structure

This document provides a quick reference to the organized folder structure of PHPNuxBill.

## 🎯 Quick Navigation

| What You Need | Go To |
|---------------|-------|
| **Setup instructions** | [`docs/setup/`](docs/setup/) |
| **Developer guides** | [`docs/development/`](docs/development/) |
| **API documentation** | [`docs/`](docs/) (QUICK_REFERENCE.md, insomnia.rest.json) |
| **Deployment guides** | [`docs/deployment/`](docs/deployment/) |
| **Testing guides** | [`docs/testing/`](docs/testing/) |
| **Setup scripts** | [`scripts/setup/`](scripts/setup/) |
| **Maintenance scripts** | [`scripts/`](scripts/) |

## 📚 Documentation Structure

```
docs/
├── README.md                          # Documentation index
├── CHANGELOG.md                       # Version history
├── QUICK_REFERENCE.md                 # API quick reference
├── ROOT_ORGANIZATION.md               # Root structure guide
├── insomnia.rest.json                 # API collection
│
├── development/                       # Developer documentation
│   ├── README.md
│   ├── CODEBASE_SUMMARY.md           # Code architecture overview
│   ├── DEVELOPMENT_GUIDELINES.md      # Coding standards
│   ├── PROJECT_MEMORY.md             # Decision history
│   └── PROJECT_STRUCTURE.md          # Folder structure details
│
├── setup/                            # Setup & configuration guides
│   ├── README.md
│   ├── PAYSTACK_SETUP_INSTRUCTIONS.md
│   └── PAYSTACK_QUICK_START.md
│
├── deployment/                       # Deployment documentation
│   ├── README.md
│   ├── VERCEL_DEPLOYMENT.md
│   ├── docker-compose.example.yml
│   └── Dockerfile.example
│
└── testing/                          # Testing documentation
    ├── README.md
    └── LIVE_TESTING_GUIDE.md
```

## 🛠️ Scripts Structure

```
scripts/
├── README.md
├── setup_cronjob.bat
├── setup_cronjob.ps1
│
└── setup/                            # Setup & configuration scripts
    ├── README.md
    ├── paystack_setup.sql           # Paystack DB setup
    ├── missing_config_setup.sql     # System config setup
    └── add_missing_configs.php      # Config check utility
```

## 🔒 Private Files

```
private/
└── secrets.txt                       # API keys and secrets (gitignored)
```

## 📦 Core Application Structure

```
phpnuxbill/
├── admin/                            # Admin interface
├── api/                              # API endpoints
├── system/                           # Core system files
│   ├── autoload/                    # Auto-loaded classes
│   ├── controllers/                 # Request controllers
│   ├── plugin/                      # Plugin system
│   ├── paymentgateway/              # Payment integrations
│   └── widgets/                     # Dashboard widgets
│
├── ui/                               # User interface
│   ├── ui/                          # Default theme
│   ├── compiled/                    # Smarty compiled templates
│   └── themes/                      # Custom themes
│
├── tests/                           # Test suites
├── vendor/                          # Composer dependencies
└── qrcode/                          # QR code library
```

## 🔑 Configuration Files (Root)

- `config.php` - Main configuration (gitignored)
- `config.sample.php` - Configuration template
- `.env.example` - Environment variables template
- `composer.json` - PHP dependencies
- `phpunit.xml.dist` - Testing configuration

## 🌐 Entry Points

- `index.php` - Main application entry
- `admin/index.php` - Admin panel entry
- `radius.php` - RADIUS integration
- `update.php` - Update handler

## 📝 Root Documentation

Root level only contains:
- `README.md` - Main project README
- `.ai-instructions.md` - AI assistant context (gitignored)

> **All other documentation has been organized into `docs/` folder!**

## 🗂️ What Changed?

### Files Moved to `docs/`:
- ✅ CHANGELOG.md → `docs/CHANGELOG.md`
- ✅ CODEBASE_SUMMARY.md → `docs/development/`
- ✅ DEVELOPMENT_GUIDELINES.md → `docs/development/`
- ✅ PROJECT_MEMORY.md → `docs/development/`
- ✅ PROJECT_STRUCTURE.md → `docs/development/`
- ✅ PAYSTACK_*.md → `docs/setup/`
- ✅ Dockerfile → `docs/deployment/Dockerfile.example`
- ✅ docker-compose.example.yml → `docs/deployment/`

### Files Moved to `scripts/setup/`:
- ✅ paystack_setup.sql
- ✅ missing_config_setup.sql
- ✅ add_missing_configs.php

### Files Moved to `private/`:
- ✅ secrets.txt (contains sensitive API keys)

## 🎨 Benefits

1. **Clear separation** - Docs vs Code vs Scripts
2. **Easy navigation** - Related files grouped together
3. **Better security** - Sensitive files in private/
4. **Cleaner root** - Only essential files at top level
5. **Logical organization** - Intuitive folder names

## 🚀 Getting Started

1. **New to PHPNuxBill?** → Start with [README.md](README.md)
2. **Installing?** → See [docs/setup/](docs/setup/)
3. **Contributing?** → Read [docs/development/](docs/development/)
4. **Deploying?** → Check [docs/deployment/](docs/deployment/)

---

**Last Updated:** February 26, 2026
**Organization Status:** ✅ Complete
