# AI COPILOT - PERSISTENT INSTRUCTIONS

**CRITICAL: Read this file at the start of EVERY coding session**

---

## 🎯 PRIMARY DIRECTIVES

### 1. ⚠️ ALWAYS REMEMBER - LIVE SERVER
- Files at `C:\xampp\htdocs\phpnuxbill` run DIRECTLY on XAMPP
- Changes take effect IMMEDIATELY
- NEVER break existing functionality
- TEST locally before committing

### 2. 🔐 SECURITY - ABSOLUTE RULES

**NEVER commit these files/data:**
- `.env` - Environment variables
- `config.php` - Configuration with credentials
- `*.backup` - Backup files
- Any file containing API keys, passwords, or secrets
- Database credentials
- Payment gateway keys (Paystack, Stripe, etc.)

**ALWAYS check before git operations:**
```bash
# Before committing, verify:
git status
git diff
# Look for: API keys, passwords, database credentials, secrets
```

### 3. 📁 FILE ORGANIZATION - STRICT RULES

| File Type | Location | Notes |
|-----------|----------|-------|
| **Production PHP** | `system/`, `api/`, `admin/` | Core application code |
| **Test Files** | `tests/` (unit, integration, api) | ALL tests here, never in production folders |
| **Scripts** | `scripts/` | PowerShell, bash, utility scripts |
| **Documentation** | Root or `docs/` | .md files for major docs |
| **Deployment Docs** | `docs/deployment/` | Deploy guides, configs |
| **Test Docs** | `docs/testing/` | Testing guides |
| **Config Backups** | `scripts/` | Backup configs (gitignored) |
| **Temporary Files** | Use `.tmp` extension | Add to .gitignore immediately |

### 4. 🧪 TESTING REQUIREMENTS

**When creating tests:**
- ✅ Place in appropriate `tests/` subfolder
- ✅ Name with `*Test.php` suffix
- ✅ Follow PHPUnit conventions
- ✅ Include docblocks
- ✅ Add fixture data to `tests/fixtures/`
- ❌ NEVER mix test code with production code

**Test coverage priorities:**
1. Payment processing - 100%
2. Authentication - 100%
3. Business logic - 80%+
4. API endpoints - 90%+

### 5. 📝 CODE STANDARDS

**Follow PSR-12:**
```php
// Good
class PaymentGateway {
    public function processPayment($amount) {
        // 4 spaces indentation
        if ($amount > 0) {
            return $this->charge($amount);
        }
    }
}

// Bad
class payment_gateway 
{
  function processPayment($amount) 
  {
    if($amount>0){
      return $this->charge($amount);
    }
  }
}
```

**Naming conventions:**
- Variables: `$camelCase`
- Functions: `camelCase()`
- Classes: `PascalCase`
- Constants: `UPPER_SNAKE_CASE`
- Database tables: `tbl_lowercase_underscore`
- Database columns: `snake_case`

---

## 🚨 SCENARIO-BASED ACTIONS

### Scenario: User asks to create a new file

**DECISION TREE:**

1. **Is it a test file?**
   - YES → Place in `tests/unit/`, `tests/integration/`, or `tests/api/`
   - NO → Continue to step 2

2. **Is it a script/utility?**
   - YES → Place in `scripts/`
   - NO → Continue to step 3

3. **Is it documentation?**
   - YES → Place in `docs/` or root for major docs
   - NO → Continue to step 4

4. **Is it production code?**
   - YES → Place in appropriate `system/`, `api/`, or `admin/` subfolder
   - NO → Ask user for clarification

**ALWAYS:**
- Add proper file header with purpose, author, date
- Prevent direct access for PHP files
- Add to .gitignore if it contains sensitive data

### Scenario: User asks to modify existing code

**CHECKLIST:**

- [ ] Read the file completely first
- [ ] Understand current functionality
- [ ] Check for dependencies/references
- [ ] Ensure changes don't break existing features
- [ ] Follow existing code style
- [ ] Add/update comments for complex logic
- [ ] Add/update tests if changing behavior
- [ ] Verify no secrets added

### Scenario: Git operations (commit/push)

**PRE-COMMIT CHECKS:**

```bash
# 1. Check what's being committed
git status
git diff --staged

# 2. Search for common secret patterns
grep -r "password.*=" .
grep -r "api.*key" .
grep -r "sk_live" .
grep -r "pk_live" .

# 3. Verify .gitignore is working
git check-ignore -v <file>
```

**STOP if you find:**
- Database credentials
- API keys (sk_live*, pk_live*, etc.)
- Passwords
- Secret tokens
- Private keys

### Scenario: API key or secret detected

**IMMEDIATE ACTIONS:**

1. **DO NOT COMMIT**
2. Move value to `.env` file:
   ```env
   PAYSTACK_SECRET=sk_live_xxx
   ```
3. Access via `getenv()`:
   ```php
   $secret = getenv('PAYSTACK_SECRET');
   ```
4. Verify `.env` is in `.gitignore`
5. Update `.env.example` with placeholder:
   ```env
   PAYSTACK_SECRET=your_secret_here
   ```

### Scenario: Creating a new feature

**WORKFLOW:**

1. **Plan:** Understand requirements
2. **Design:** Where will files go? (follow organization rules)
3. **Write tests first:** TDD approach
4. **Implement:** Follow code standards
5. **Test:** Run tests, manual testing
6. **Document:** Update relevant docs
7. **Review:** Check for secrets, standards compliance
8. **Commit:** Proper commit message format

### Scenario: Database changes needed

**RULES:**

- ✅ Use prepared statements ALWAYS
- ✅ Use ORM methods when available
- ✅ Use transactions for multi-step operations
- ✅ Document schema changes in CHANGELOG.md
- ❌ NEVER use raw SQL with user input
- ❌ NEVER concatenate SQL strings

```php
// Good
$stmt = $db->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->execute([$customerId]);

// Bad - SQL Injection risk!
$sql = "SELECT * FROM customers WHERE id = " . $_GET['id'];
```

### Scenario: User input received

**VALIDATION CHAIN:**

1. **Validate existence:** Check if required fields present
2. **Sanitize:** Remove unwanted characters
3. **Validate format:** Email, phone, etc.
4. **Escape output:** `htmlspecialchars()` for display
5. **Use prepared statements:** For database queries

```php
// Always follow this pattern
$email = $_POST['email'] ?? '';

// Validate
if (empty($email)) {
    throw new InvalidArgumentException('Email required');
}

// Sanitize
$email = filter_var($email, FILTER_SANITIZE_EMAIL);

// Validate again
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new InvalidArgumentException('Invalid email');
}

// Use safely
$stmt = $db->prepare("INSERT INTO customers (email) VALUES (?)");
$stmt->execute([$email]);
```

### Scenario: Adding external dependencies

**PROCESS:**

1. Check if already available in `vendor/`
2. If new, use composer:
   ```bash
   composer require vendor/package
   ```
3. Document in PROJECT_STRUCTURE.md
4. Update README.md if affects setup
5. Commit composer.json and composer.lock

### Scenario: Performance issue reported

**INVESTIGATION STEPS:**

1. **Profile:** Identify bottleneck
2. **Database:** Check queries (use EXPLAIN)
3. **Caching:** Consider caching expensive operations
4. **Optimize:** 
   - Add indexes
   - Eager loading vs N+1 queries
   - Reduce data fetched

---

## 📋 DAILY CHECKLIST

### At session start:
- [ ] Read this file
- [ ] Check PROJECT_MEMORY.md for updates
- [ ] Review current git status
- [ ] Understand user's goal

### During coding:
- [ ] Follow file organization rules
- [ ] No secrets in code
- [ ] Tests for new features
- [ ] Comments for complex logic
- [ ] Follow coding standards

### Before committing:
- [ ] Run tests: `vendor/bin/phpunit`
- [ ] Check for secrets: `git diff --staged`
- [ ] Verify .gitignore working
- [ ] Proper commit message
- [ ] Update CHANGELOG.md if needed

### After pushing:
- [ ] Verify push succeeded
- [ ] No secret warnings from GitHub
- [ ] CI/CD passes (if configured)

---

## 🎨 UI REVAMP PREPARATION

**Remember:** This is a long-term project with planned UI overhaul

**When working on UI:**
- Keep old UI for reference (don't delete)
- Document all changes
- Progressive enhancement (don't break existing)
- Test on multiple devices
- Consider accessibility (WCAG 2.1)
- Use semantic HTML5
- Follow BEM methodology for CSS

---

## 🔄 GIT WORKFLOW

### Branch naming:
```
feature/payment-gateway-integration
fix/customer-login-bug
hotfix/security-vulnerability
ui/dashboard-redesign
refactor/database-optimization
docs/api-documentation
test/payment-coverage
```

### Commit message format:
```
type(scope): subject

Examples:
feat(payment): add Paystack integration
fix(auth): resolve session timeout
docs(api): update webhook guide
test(payment): add unit tests
refactor(db): optimize queries
style(ui): improve dashboard
chore(deps): update composer
```

### Pull request checklist:
- [ ] Tests pass
- [ ] No secrets committed
- [ ] Documentation updated
- [ ] CHANGELOG.md updated
- [ ] Code reviewed
- [ ] No console errors

---

## 💡 QUICK REFERENCE

### Common commands:
```bash
# Run tests
vendor/bin/phpunit

# Run specific test
vendor/bin/phpunit tests/unit/PaymentTest.php

# Code coverage
vendor/bin/phpunit --coverage-html coverage/

# Check PHP syntax
php -l file.php

# Composer update
composer update

# Git status
git status --short
```

### File locations:
```
Production code → system/, api/, admin/
Tests → tests/unit/, tests/integration/, tests/api/
Scripts → scripts/
Docs → docs/ or root
Config → .env (never commit)
Backups → scripts/ (gitignored)
```

### Priority files to check:
- `PROJECT_MEMORY.md` - Long-term guidelines
- `PROJECT_STRUCTURE.md` - Architecture
- `DEVELOPMENT_GUIDELINES.md` - Coding standards
- `.gitignore` - What's excluded
- `.env.example` - Environment template

---

## 🚫 NEVER DO

1. ❌ Commit `.env` or `config.php`
2. ❌ Hardcode API keys or passwords
3. ❌ Mix test code with production
4. ❌ Push without checking for secrets
5. ❌ Delete old UI without backup
6. ❌ Ignore security warnings
7. ❌ Skip testing before commit
8. ❌ Use raw SQL with user input
9. ❌ Leave debug code (var_dump, console.log)
10. ❌ Break existing functionality

---

## ✅ ALWAYS DO

1. ✅ Read this file at session start
2. ✅ Check for secrets before commit
3. ✅ Follow file organization rules
4. ✅ Write tests for new features
5. ✅ Use prepared statements
6. ✅ Validate and sanitize input
7. ✅ Document complex logic
8. ✅ Follow coding standards
9. ✅ Update CHANGELOG.md
10. ✅ Be cautious with live server files

---

## 📞 WHEN IN DOUBT

1. Check PROJECT_MEMORY.md
2. Check DEVELOPMENT_GUIDELINES.md
3. Check existing similar code
4. Ask user for clarification
5. Prefer safer approach

---

**VERSION:** 1.0  
**LAST UPDATED:** February 14, 2026  
**STATUS:** ACTIVE

**This file is your guide. Follow it strictly. Update it when new patterns emerge.**
