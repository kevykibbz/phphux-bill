# Root Directory Organization

## ✅ Clean Root Directory Structure

```
phpnuxbill/
├── .ai-instructions.md          # 🤖 Persistent AI instructions
├── .env                          # ⚠️ Environment variables (NEVER COMMIT)
├── .env.example                  # ✅ Environment template
├── .gitignore                    # Git exclusions
├── CHANGELOG.md                  # Version history
├── CODEBASE_SUMMARY.md           # Project overview
├── composer.json                 # PHP dependencies
├── config.php                    # ⚠️ Config (NEVER COMMIT)
├── config.sample.php             # ✅ Config template
├── DEVELOPMENT_GUIDELINES.md     # Coding standards
├── Dockerfile                    # Docker configuration
├── docker-compose.example.yml    # Docker compose template
├── index.php                     # Main entry point
├── init.php                      # Bootstrap
├── LICENSE                       # License file
├── phpunit.xml.dist              # PHPUnit configuration
├── PROJECT_MEMORY.md             # Long-term project memory
├── PROJECT_STRUCTURE.md          # Architecture docs
├── README.md                     # Main documentation
├── update.php                    # Update script
├── vercel.json                   # Vercel configuration
├── version.json                  # Version info
│
├── 📁 admin/                     # Admin panel
├── 📁 api/                       # API endpoints
├── 📁 docs/                      # Documentation
│   ├── deployment/               # Deployment guides
│   ├── testing/                  # Testing guides
│   └── *.md                      # Other docs
├── 📁 pages/                     # Public pages
├── 📁 pages_template/            # Page templates
├── 📁 payment_gateways/          # Payment integrations
├── 📁 qrcode/                    # QR code library
├── 📁 scan/                      # Scanner functionality
├── 📁 scripts/                   # 🆕 Utility scripts
│   ├── setup_cronjob.ps1
│   ├── setup_cronjob.bat
│   └── *.backup (gitignored)
├── 📁 system/                    # Core application
│   ├── autoload/
│   ├── controllers/
│   ├── plugin/
│   └── ...
├── 📁 tests/                     # 🆕 Test suite
│   ├── unit/
│   ├── integration/
│   ├── api/
│   ├── fixtures/
│   ├── helpers/
│   └── scripts/                  # Test automation scripts
├── 📁 ui/                        # User interface
└── 📁 vendor/                    # Composer dependencies
```

## 📂 Folder Purposes

| Folder | Purpose | Commit? |
|--------|---------|---------|
| `admin/` | Admin panel files | ✅ Yes |
| `api/` | API endpoints | ✅ Yes |
| `docs/` | Documentation | ✅ Yes (selective) |
| `docs/deployment/` | Deployment guides | ⚠️ Selective |
| `docs/testing/` | Testing guides | ⚠️ Selective |
| `pages/` | Public pages | ✅ Yes |
| `payment_gateways/` | Payment integrations | ⚠️ Selective |
| `qrcode/` | QR code library | ✅ Yes |
| `scripts/` | Utility scripts | ⚠️ Selective |
| `system/` | Core application | ✅ Yes |
| `tests/` | Test suite | ✅ Yes |
| `tests/scripts/` | Test automation | ⚠️ Selective |
| `ui/` | UI templates | ✅ Yes |
| `vendor/` | Dependencies | ❌ No (via composer) |

## 🗑️ Removed from Root

The following files have been organized:

**Moved to `scripts/`:**
- `setup_cronjob.ps1`
- `setup_cronjob.bat`
- `config.php.backup`
- `cookies.txt`

**Moved to `docs/deployment/`:**
- `VERCEL_DEPLOYMENT.md`

**Moved to `docs/testing/`:**
- `LIVE_TESTING_GUIDE.md`

**Moved to `docs/`:**
- `QUICK_REFERENCE.md`

**Moved to `tests/scripts/`:**
- `test_webhook.ps1`
- `test_webhook.sh`

## 📝 Root Documentation Files

### Essential Reading Order

1. **[README.md](README.md)** - Start here
2. **[.ai-instructions.md](.ai-instructions.md)** - AI assistant guidelines
3. **[PROJECT_MEMORY.md](PROJECT_MEMORY.md)** - Long-term project guidelines
4. **[PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md)** - Architecture details
5. **[DEVELOPMENT_GUIDELINES.md](DEVELOPMENT_GUIDELINES.md)** - Coding standards
6. **[CODEBASE_SUMMARY.md](CODEBASE_SUMMARY.md)** - Quick overview

### Quick References

- **Setup:** [README.md](README.md)
- **Development:** [DEVELOPMENT_GUIDELINES.md](DEVELOPMENT_GUIDELINES.md)
- **Testing:** [tests/README.md](tests/README.md)
- **Deployment:** [docs/deployment/README.md](docs/deployment/README.md)
- **Changes:** [CHANGELOG.md](CHANGELOG.md)

## 🔒 Security Files (NEVER COMMIT)

```
.env                    # Environment variables
config.php              # Configuration with credentials
scripts/*.backup        # Backup files
scripts/cookies.txt     # Cookie data
*.tmp                   # Temporary files
vendor/                 # Dependencies (use composer)
```

## ✅ Benefits of Organization

### Before
```
phpnuxbill/
├── setup_cronjob.ps1         ❌ Cluttered root
├── test_webhook.ps1          ❌ Mixed purposes
├── VERCEL_DEPLOYMENT.md      ❌ Hard to find
├── config.php.backup         ❌ Security risk
└── ... (50+ files)           ❌ Overwhelming
```

### After
```
phpnuxbill/
├── .ai-instructions.md       ✅ Clear purpose
├── README.md                 ✅ Main docs
├── scripts/                  ✅ Organized
├── docs/                     ✅ Easy to find
├── tests/                    ✅ Separated
└── ... (core files only)     ✅ Clean
```

## 🎯 Maintaining Organization

### When Adding New Files

**Ask yourself:**

1. **Is it a script?** → `scripts/`
2. **Is it a test?** → `tests/[type]/`
3. **Is it documentation?** → `docs/` or root (if major)
4. **Is it production code?** → `system/`, `api/`, etc.
5. **Is it temporary?** → Use `.tmp` extension, gitignore

### File Naming Conventions

```
✅ Good:
- payment_gateway.php
- CustomerTest.php
- deployment-guide.md
- setup-cron.ps1

❌ Bad:
- temp1.php
- test.php
- backup.php
- final_FINAL_v2.php
```

## 📋 Maintenance Checklist

### Weekly
- [ ] Remove temporary files
- [ ] Update documentation if needed
- [ ] Check for orphaned files
- [ ] Review git status

### Monthly  
- [ ] Audit root directory
- [ ] Archive old backups
- [ ] Update .gitignore if needed
- [ ] Review folder structure

### Before Release
- [ ] Clean temporary files
- [ ] Verify documentation current
- [ ] Check no secrets in files
- [ ] Organize assets properly

## 🆘 Troubleshooting

**Can't find a file?**
- Check `scripts/` for utility scripts
- Check `docs/` for documentation
- Check `tests/` for test-related files
- Use `git log --all --full-history -- filename` to find moves

**Root directory cluttered again?**
- Review file purposes
- Apply organization rules
- Update .gitignore
- Document in CHANGELOG.md

## 📞 Questions?

- Check [.ai-instructions.md](.ai-instructions.md) for AI guidelines
- Check [PROJECT_MEMORY.md](PROJECT_MEMORY.md) for project rules
- Check folder README files for specific purposes

---

**Keep the root clean! Organized code is maintainable code.**

**Last Updated:** February 14, 2026
