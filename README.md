[![ReadMeSupportPalestine](https://raw.githubusercontent.com/Safouene1/support-palestine-banner/master/banner-project.svg)](https://s.id/standwithpalestine)

# PHPNuxBill - PHP Mikrotik Billing System

![PHPNuxBill](install/img/logo.png)

A powerful billing system for managing Mikrotik hotspots and PPPoE connections with built-in payment gateway support.

## ✨ Key Features

- 📱 **Hotspot & PPPoE Management** - Full support for Mikrotik devices
- 🎟️ **Voucher System** - Generate, print, and manage WiFi vouchers
- 💳 **Payment Gateway Integration** - Support for multiple payment processors
- 📊 **Multi-Router Support** - Manage multiple Mikrotik routers from one dashboard
- 🔄 **Auto-Renewal** - Automatic package renewal using user balance
- 📝 **Self-Registration** - Allow customers to register themselves
- 📧 **Notifications** - WhatsApp, SMS, and Telegram notifications
- 🌐 **Multi-Language** - Support for multiple languages
- 🎨 **Customizable UI** - Flexible theme system
- 📡 **FreeRADIUS Support** - Database-backed RADIUS authentication

See [How it Works](https://github.com/hotspotbilling/phpnuxbill/wiki/How-It-Works---Cara-kerja) for more details.

## 🎯 Quick Start Guide

**New to PHPNuxBill?** Follow this path:

1. **📖 Read This README** - You're here! Get the overview
2. **📁 Understand the Structure** - See [FOLDER_STRUCTURE.md](FOLDER_STRUCTURE.md) for organized layout
3. **🚀 Install** - Follow the [Installation](#-installation) section below
4. **⚙️ Setup Payment Gateway** - Check [docs/setup/](docs/setup/) for Paystack integration
5. **👨‍💻 Contribute** - Read [docs/development/](docs/development/) for coding guidelines

**All documentation is now organized in the [`docs/`](docs/) folder** for easy navigation!

## 🔌 Extensions

- [Payment Gateway Plugins](https://github.com/orgs/hotspotbilling/repositories?q=payment+gateway)
- [Additional Plugins](https://github.com/orgs/hotspotbilling/repositories?q=plugin)

Download and install plugins directly from the Plugin Manager in the admin panel.

## 💻 System Requirements

**Minimum Requirements:**

- Linux or Windows OS (Linux recommended for cron jobs)
- PHP 8.2 or higher
- MySQL 5.7+ / MariaDB 10.3+
- PHP Extensions:
  - PDO & MySQLi
  - GD2 (image library)
  - cURL
  - ZIP
  - Mbstring
  - OpenSSL

**Compatible with:**
- XAMPP / WAMP / LAMP stacks
- Docker containers
- Raspberry Pi devices
- VPS / Cloud hosting

## 🚀 Installation

### Quick Start

1. **Clone or download** this repository
2. **Import database** from `install/phpnuxbill.sql`
3. **Configure** your database in `config.php` (copy from `config.sample.php`)
4. **Set permissions** - Ensure `system/uploads` is writable
5. **Access** your installation via web browser
6. **Login** with default credentials and change them immediately

### Detailed Instructions

For detailed installation guide, visit: [Installation Wiki](https://github.com/hotspotbilling/phpnuxbill/wiki)

### Configuration

Create `config.php` from the sample:
```bash
cp config.sample.php config.php
```

Edit database credentials and other settings as needed.

## 📚 Documentation

### Official Resources
- [Official Wiki](https://github.com/hotspotbilling/phpnuxbill/wiki)
- [FreeRADIUS Setup](https://github.com/hotspotbilling/phpnuxbill/wiki/FreeRadius)
- [API Documentation](docs/insomnia.rest.json) - Insomnia REST collection
- [Quick API Reference](docs/QUICK_REFERENCE.md)
- [Changelog](docs/CHANGELOG.md)

### 🗂️ Organized Documentation Structure

All documentation is now organized in the [`docs/`](docs/) folder:

**📖 [Documentation Index](docs/README.md)** - Start here for complete documentation overview

**🛠️ Setup & Configuration** ([`docs/setup/`](docs/setup/))
- [Paystack Setup Instructions](docs/setup/PAYSTACK_SETUP_INSTRUCTIONS.md) - Complete Paystack integration guide
- [Paystack Quick Start](docs/setup/PAYSTACK_QUICK_START.md) - Fast setup for Paystack gateway
- [Setup Scripts](scripts/setup/README.md) - Database and configuration utilities

**👨‍💻 Developer Documentation** ([`docs/development/`](docs/development/))
- [Codebase Summary](docs/development/CODEBASE_SUMMARY.md) - Architecture overview
- [Development Guidelines](docs/development/DEVELOPMENT_GUIDELINES.md) - Coding standards
- [Project Structure](docs/development/PROJECT_STRUCTURE.md) - Detailed file organization
- [Project Memory](docs/development/PROJECT_MEMORY.md) - Decision history and context

**🚀 Deployment** ([`docs/deployment/`](docs/deployment/))
- [Deployment Guide](docs/deployment/README.md) - Deployment overview
- [Vercel Deployment](docs/deployment/VERCEL_DEPLOYMENT.md) - Deploy to Vercel
- [Docker Setup](docs/deployment/docker-compose.example.yml) - Docker configuration
- [Dockerfile Example](docs/deployment/Dockerfile.example)

**🧪 Testing** ([`docs/testing/`](docs/testing/))
- [Testing Guide](docs/testing/README.md) - Testing overview
- [Live Testing Guide](docs/testing/LIVE_TESTING_GUIDE.md) - Production testing
- [Test Suite](tests/README.md) - Automated tests

### 📁 Project Organization

**[FOLDER_STRUCTURE.md](FOLDER_STRUCTURE.md)** - Complete guide to the organized codebase structure

The project follows a clean, organized structure:
- **`docs/`** - All documentation (setup, development, deployment, testing)
- **`scripts/setup/`** - Setup and configuration scripts
- **`private/`** - Sensitive files (gitignored)
- **Root** - Only essential core files

### 🔧 Utility Scripts

Located in [`scripts/setup/`](scripts/setup/):
- `add_missing_configs.php` - Add missing database configurations
- `paystack_setup.sql` - Paystack payment gateway setup
- `missing_config_setup.sql` - System configuration defaults

Run from project root:
```bash
php scripts/setup/add_missing_configs.php
```

## 🤝 Community & Support

### Free Support
- [GitHub Discussions](https://github.com/hotspotbilling/phpnuxbill/discussions)
- [Telegram Group](https://t.me/phpmixbill)

### Technical Support
This software is free and open source, provided without warranty.

Professional technical support available starting from $50 USD.
For free assistance, use GitHub Discussions or the Telegram Group.

Contact: [Telegram @ibnux](https://t.me/ibnux)

## 🔐 Security

**Best Practices:**
- Never commit `config.php` or files containing credentials
- Store sensitive files (API keys, secrets) in the `private/` folder (gitignored)
- Use strong database passwords
- Keep PHP and dependencies updated
- Enable HTTPS in production
- Regularly backup your database
- Review `.gitignore` to ensure sensitive files are excluded

**Secure File Storage:**
- `private/` folder - For API keys and sensitive credentials (automatically gitignored)
- `config.php` - Database credentials (already gitignored)
- `.env` files - Environment variables (gitignored)

**For Developers:**
- Review [.ai-instructions.md](.ai-instructions.md) for AI assistant security guidelines
- Follow guidelines in [docs/development/DEVELOPMENT_GUIDELINES.md](docs/development/DEVELOPMENT_GUIDELINES.md)

## 📝 License

GNU General Public License version 2 or later

See [LICENSE](LICENSE) file for details.

## 💖 Support Development

If this project helps your business, consider supporting its development:

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://paypal.me/ibnux)

**Bank Transfer:**
- BCA: 5410454825
- Mandiri: 163-000-1855-793
- a.n Ibnu Maksum

## 🌟 Credits

Developed and maintained by the PHPNuxBill community.

## 📂 Project Organization

This project follows a clean, professional structure:
- **Documentation** → [`docs/`](docs/) (setup, development, deployment, testing)
- **Setup Scripts** → [`scripts/setup/`](scripts/setup/)
- **Secure Storage** → `private/` (gitignored)

See [FOLDER_STRUCTURE.md](FOLDER_STRUCTURE.md) for complete details.

---

**Note:** This is a community-maintained fork with additional features and improved organization. Always backup before updating.

- [mixradius.com](https://mixradius.com/) Paid Services Billing Radius
- [mlink.id](https://mlink.id)
- [https://github.com/sonyinside](https://github.com/sonyinside)

## Thanks
We appreciate all people who are participating in this project.

<a href="https://github.com/hotspotbilling/phpnuxbill/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=hotspotbilling/phpnuxbill" />
</a>
