#!/usr/bin/env php
<?php

/**
 * PHPNuxBill Database Installer
 * 
 * This script initializes the PHPNuxBill database with all required tables.
 * Run this ONCE after creating a fresh database.
 * 
 * Usage: php scripts/setup/install_database.php
 */

// Color output for CLI
function colorOutput($text, $color = 'white')
{
    $colors = [
        'red' => "\033[31m",
        'green' => "\033[32m",
        'yellow' => "\033[33m",
        'blue' => "\033[34m",
        'cyan' => "\033[36m",
        'white' => "\033[37m",
        'reset' => "\033[0m"
    ];
    return $colors[$color] . $text . $colors['reset'];
}

echo "\n";
echo colorOutput("═══════════════════════════════════════════════════════\n", 'cyan');
echo colorOutput("  PHPNuxBill - DATABASE INSTALLER\n", 'cyan');
echo colorOutput("═══════════════════════════════════════════════════════\n", 'cyan');
echo "\n";

// Check if config.php exists
$configFile = dirname(dirname(dirname(__FILE__))) . '/config.php';
if (!file_exists($configFile)) {
    echo colorOutput("✗ ERROR: config.php not found!\n", 'red');
    echo "\n";
    echo "Please create config.php from config.sample.php first:\n";
    echo "  1. Copy config.sample.php to config.php\n";
    echo "  2. Edit config.php with your database credentials\n";
    echo "  3. Run this installer again\n\n";
    exit(1);
}

// Load configuration
require_once $configFile;

// Check if database credentials are configured
if (!isset($db_host) || !isset($db_user) || !isset($db_name)) {
    echo colorOutput("✗ ERROR: Database credentials not configured!\n", 'red');
    echo "\n";
    echo "Please edit config.php and set:\n";
    echo "  \$db_host = 'localhost';  // Your database host\n";
    echo "  \$db_user = 'root';       // Your database user\n";
    echo "  \$db_password = '';       // Your database password\n";
    echo "  \$db_name = 'phpnuxbill'; // Your database name\n\n";
    exit(1);
}

echo "Configuration:\n";
echo "══════════════\n";
echo "  • Database Host: " . colorOutput($db_host, 'cyan') . "\n";
echo "  • Database Name: " . colorOutput($db_name, 'cyan') . "\n";
echo "  • Database User: " . colorOutput($db_user, 'cyan') . "\n";
echo "\n";

// Test database connection
echo colorOutput("Testing database connection...\n", 'yellow');

try {
    $dsn = "mysql:host=$db_host;charset=utf8mb4";
    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo colorOutput("✓ Database connection successful\n", 'green');
} catch (PDOException $e) {
    echo colorOutput("✗ Database connection failed: " . $e->getMessage() . "\n", 'red');
    echo "\n";
    echo "Please check:\n";
    echo "  1. MySQL/MariaDB is running (check XAMPP control panel)\n";
    echo "  2. Database credentials in config.php are correct\n";
    echo "  3. Database user has proper permissions\n\n";
    exit(1);
}

// Check if database exists
echo colorOutput("Checking database...\n", 'yellow');

try {
    $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$db_name'");
    $dbExists = $stmt->fetch();

    if ($dbExists) {
        echo colorOutput("✓ Database '$db_name' exists\n", 'green');

        // Check if database has tables
        $pdo->exec("USE `$db_name`");
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (count($tables) > 0) {
            echo colorOutput("⚠ WARNING: Database already has " . count($tables) . " tables!\n", 'yellow');
            echo "\n";
            echo "Found tables: " . implode(', ', array_slice($tables, 0, 5));
            if (count($tables) > 5) {
                echo " and " . (count($tables) - 5) . " more...";
            }
            echo "\n\n";
            echo "Do you want to:\n";
            echo "  1. DROP and recreate database (⚠ ALL DATA WILL BE LOST)\n";
            echo "  2. Cancel installation\n\n";
            echo "Enter choice (1 or 2): ";

            $handle = fopen("php://stdin", "r");
            $choice = trim(fgets($handle));
            fclose($handle);

            if ($choice !== '1') {
                echo "\n" . colorOutput("Installation cancelled by user.\n", 'yellow');
                exit(0);
            }

            echo "\n" . colorOutput("Dropping existing database...\n", 'yellow');
            $pdo->exec("DROP DATABASE `$db_name`");
            echo colorOutput("✓ Database dropped\n", 'green');
        }
    }

    // Create database if it doesn't exist
    echo colorOutput("Creating database '$db_name'...\n", 'yellow');
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    echo colorOutput("✓ Database created\n", 'green');

    // Use the database
    $pdo->exec("USE `$db_name`");
} catch (PDOException $e) {
    echo colorOutput("✗ Database check failed: " . $e->getMessage() . "\n", 'red');
    exit(1);
}

// Import SQL file
$sqlFile = dirname(dirname(dirname(__FILE__))) . '/docs/sql/phpnuxbill.sql';

if (!file_exists($sqlFile)) {
    echo colorOutput("✗ ERROR: SQL file not found at: $sqlFile\n", 'red');
    exit(1);
}

echo "\n";
echo colorOutput("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n", 'cyan');
echo colorOutput("  IMPORTING DATABASE SCHEMA\n", 'cyan');
echo colorOutput("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n", 'cyan');
echo "\n";

echo "Reading SQL file...\n";
$sqlContent = file_get_contents($sqlFile);
$fileSize = filesize($sqlFile);
echo colorOutput("✓ Loaded " . number_format($fileSize) . " bytes\n", 'green');

echo "\nExecuting SQL statements...\n";

try {
    // Split SQL file into individual statements
    $statements = array_filter(
        array_map(
            'trim',
            preg_split('/;[\r\n]+/', $sqlContent)
        ),
        'strlen'
    );

    $executed = 0;
    $skipped = 0;
    $errors = 0;

    foreach ($statements as $statement) {
        // Skip comments and empty statements
        if (
            empty($statement) ||
            strpos($statement, '--') === 0 ||
            strpos($statement, '/*') === 0 ||
            strpos($statement, 'SET ') === 0 ||
            strpos($statement, '/*!') === 0
        ) {
            $skipped++;
            continue;
        }

        try {
            $pdo->exec($statement);
            $executed++;

            // Show progress every 10 statements
            if ($executed % 10 == 0) {
                echo ".";
                if ($executed % 50 == 0) {
                    echo " $executed\n";
                }
            }
        } catch (PDOException $e) {
            // Only show error for important statements
            if (
                stripos($statement, 'CREATE TABLE') !== false ||
                stripos($statement, 'INSERT INTO') !== false
            ) {
                echo "\n" . colorOutput("⚠ Error: " . $e->getMessage() . "\n", 'yellow');
                $errors++;
            }
        }
    }

    echo "\n\n";
    echo colorOutput("✓ Database import completed\n", 'green');
    echo "  • Statements executed: $executed\n";
    echo "  • Statements skipped: $skipped\n";
    if ($errors > 0) {
        echo "  • Errors (non-critical): $errors\n";
    }
} catch (Exception $e) {
    echo colorOutput("✗ Import failed: " . $e->getMessage() . "\n", 'red');
    exit(1);
}

// Verify installation
echo "\n";
echo colorOutput("Verifying installation...\n", 'yellow');

$requiredTables = [
    'tbl_appconfig',
    'tbl_customers',
    'tbl_plans',
    'tbl_routers',
    'tbl_transactions',
    'tbl_users',
    'tbl_bandwidth',
    'tbl_payment_gateway'
];

$allTablesExist = true;
$stmt = $pdo->query("SHOW TABLES");
$existingTables = $stmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($requiredTables as $table) {
    if (in_array($table, $existingTables)) {
        echo colorOutput("  ✓ $table\n", 'green');
    } else {
        echo colorOutput("  ✗ $table (MISSING)\n", 'red');
        $allTablesExist = false;
    }
}

echo "\n";
echo "Total tables created: " . count($existingTables) . "\n";

if (!$allTablesExist) {
    echo "\n";
    echo colorOutput("⚠ WARNING: Some required tables are missing!\n", 'yellow');
    echo "Installation may be incomplete. Please check the SQL file.\n";
    exit(1);
}

// Success message
echo "\n";
echo colorOutput("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n", 'cyan');
echo colorOutput("  ✓ INSTALLATION SUCCESSFUL!\n", 'green');
echo colorOutput("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n", 'cyan');
echo "\n";

echo "Next Steps:\n";
echo "═══════════\n";
echo "1. Access PHPNuxBill: " . colorOutput("http://localhost/phpnuxbill/", 'cyan') . "\n";
echo "\n";
echo "2. Login with default credentials:\n";
echo "   • Username: " . colorOutput("admin", 'cyan') . "\n";
echo "   • Password: " . colorOutput("admin", 'cyan') . "\n";
echo "   " . colorOutput("⚠ CHANGE PASSWORD IMMEDIATELY!", 'yellow') . "\n";
echo "\n";
echo "3. Configure Payment Gateway (Optional):\n";
echo "   • Go to: Settings > Payment Gateway\n";
echo "   • Select: Paystack (or other gateway)\n";
echo "   • The plugin will auto-configure database settings!\n";
echo "\n";
echo "4. Add Your First Router:\n";
echo "   • Go to: Settings > Routers\n";
echo "   • Add Mikrotik router details\n";
echo "\n";
echo "5. Create Internet Plans:\n";
echo "   • Go to: Services > Plans\n";
echo "   • Create internet packages\n";
echo "\n";

echo colorOutput("═══════════════════════════════════════════════════════\n", 'cyan');
echo "\n";

echo colorOutput("Happy Billing! 🚀\n", 'green');
echo "\n";

exit(0);
