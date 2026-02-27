<?php

/**
 * Uninstall Paystack Configuration
 * Removes all Paystack configs from database for testing reinstall
 * 
 * Usage: php scripts/setup/uninstall_paystack.php
 */

require_once dirname(dirname(dirname(__FILE__))) . '/init.php';
require_once dirname(dirname(dirname(__FILE__))) . '/system/paymentgateway/paystack_install.php';

echo "\n";
echo "═══════════════════════════════════════════════════════\n";
echo "  PAYSTACK GATEWAY - UNINSTALLER\n";
echo "═══════════════════════════════════════════════════════\n\n";

echo "⚠ WARNING: This will delete all Paystack configurations!\n";
echo "This is useful for testing the auto-installer.\n\n";

// Check current status
$status = PaystackInstaller::getStatus();

if (!$status['installed']) {
    echo "✓ Paystack is not installed. Nothing to uninstall.\n\n";
    exit(0);
}

echo "Current Configuration:\n";
echo "══════════════════════\n";
foreach ($status['configs'] as $setting => $info) {
    $statusIcon = $info['exists'] ? '✓' : '✗';
    $statusText = $info['exists'] ? 'Exists' : 'Missing';
    if ($info['exists'] && $info['configured']) {
        $valueDisplay = in_array($setting, ['paystack_secret_key', 'paystack_public_key'])
            ? '(***hidden***)'
            : '(' . substr($info['value'], 0, 40) . '...)';
        echo "  $statusIcon $setting: $statusText $valueDisplay\n";
    } else {
        echo "  $statusIcon $setting: $statusText\n";
    }
}

echo "\n";
echo "Removing Paystack configurations...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Run uninstaller
$result = PaystackInstaller::uninstall();

if ($result['success']) {
    echo "✓ " . $result['message'] . "\n";
    echo "  • Configs removed: {$result['configs_removed']}\n\n";
} else {
    echo "✗ " . $result['message'] . "\n\n";
    exit(1);
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "  NEXT: TEST AUTO-INSTALLER\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "Run the installer to test auto-configuration:\n";
echo "  php system/paymentgateway/paystack_install.php\n\n";

echo "Or verify installation:\n";
echo "  php scripts/setup/verify_paystack_install.php\n\n";

echo "Or just access Paystack in admin panel:\n";
echo "  Settings > Payment Gateway > Paystack\n";
echo "  (configs will be auto-created on first load)\n\n";

echo "═══════════════════════════════════════════════════════\n\n";
