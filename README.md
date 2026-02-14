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

- [Official Wiki](https://github.com/hotspotbilling/phpnuxbill/wiki)
- [FreeRADIUS Setup](https://github.com/hotspotbilling/phpnuxbill/wiki/FreeRadius)
- [API Documentation](docs/insomnia.rest.json)
- [Changelog](CHANGELOG.md)

### 📖 Project Documentation

**Essential Reading:**
- [AI Instructions](.ai-instructions.md) - Guidelines for AI assistants working on this project
- [Project Memory](PROJECT_MEMORY.md) - Long-term project guidelines and critical warnings
- [Project Structure](PROJECT_STRUCTURE.md) - Complete architecture documentation
- [Development Guidelines](DEVELOPMENT_GUIDELINES.md) - Coding standards and best practices
- [Codebase Summary](CODEBASE_SUMMARY.md) - Quick reference overview

**Organized Documentation:**
- [Deployment Guides](docs/deployment/) - How to deploy to various platforms
- [Testing Documentation](docs/testing/) - Testing strategies and guides
- [Root Organization](docs/ROOT_ORGANIZATION.md) - File organization reference

**Development Resources:**
- [Testing Suite](tests/README.md) - How to write and run tests
- [Scripts Documentation](scripts/README.md) - Utility scripts and automation

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

- Never commit `config.php` or files containing credentials
- Use strong database passwords
- Keep PHP and dependencies updated
- Enable HTTPS in production
- Regularly backup your database

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

---

**Note:** This is a community-maintained fork with additional features. Always backup before updating.

- [mixradius.com](https://mixradius.com/) Paid Services Billing Radius
- [mlink.id](https://mlink.id)
- [https://github.com/sonyinside](https://github.com/sonyinside)

## Thanks
We appreciate all people who are participating in this project.

<a href="https://github.com/hotspotbilling/phpnuxbill/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=hotspotbilling/phpnuxbill" />
</a>
