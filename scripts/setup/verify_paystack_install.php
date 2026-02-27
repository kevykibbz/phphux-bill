<?php

/**
 * Paystack Gateway - Installation Verification Script
 * 
 * Checks if Paystack gateway is properly installed and configured
 * 
 * Usage: php scripts/setup/verify_paystack_install.php
 */

require_once dirname(dirname(dirname(__FILE__))) . '/init.php';
require_once dirname(dirname(dirname(__FILE__))) . '/system/paymentgateway/paystack_install.php';

echo "\n";
echo "═══════════════════════════════════════════════════════\n";
echo "  PAYSTACK GATEWAY - INSTALLATION VERIFICATION\n";
echo "═══════════════════════════════════════════════════════\n\n";

// Get installation status
$status = PaystackInstaller::getStatus();
$validation = PaystackInstaller::validateConfig();

// Overall status
echo "Overall Status:\n";
echo "══════════════\n";
if ($status['installed'] && $validation['valid']) {
    echo "  ✓ Paystack Gateway: READY\n";
} elseif ($status['installed']) {
    echo "  ⚠ Paystack Gateway: INSTALLED (needs configuration)\n";
} else {
    echo "  ✗ Paystack Gateway: NOT INSTALLED\n";
}
echo "\n";

// File status
echo "File Status:\n";
echo "════════════\n";
echo "  • Gateway file (paystack.php): " . ($status['gateway_file_exists'] ? '✓ Exists' : '✗ Missing') . "\n";
echo "  • Installer file (paystack_install.php): " . (file_exists(__DIR__ . '/../../system/paymentgateway/paystack_install.php') ? '✓ Exists' : '✗ Missing') . "\n";
echo "  • Callback file (paystack.ts): " . ($status['callback_file_exists'] ? '✓ Exists' : '✗ Missing') . "\n";
echo "  • Vercel config (vercel.json): " . (file_exists(__DIR__ . '/../../vercel.json') ? '✓ Exists' : '✗ Missing') . "\n";
echo "  • Package file (package.json): " . (file_exists(__DIR__ . '/../../package.json') ? '✓ Exists' : '✗ Missing') . "\n";
echo "\n";

// Database configuration status
echo "Database Configuration:\n";
echo "═══════════════════════\n";

$configStatus = [
    '✓ Configured' => 0,
    '⚠ Empty' => 0,
    '✗ Missing' => 0
];

foreach ($status['configs'] as $setting => $info) {
    $icon = '✗';
    $statusText = 'Missing';

    if ($info['exists']) {
        if ($info['configured']) {
            $icon = '✓';
            $statusText = 'Configured';
            $configStatus['✓ Configured']++;
        } else {
            $icon = '⚠';
            $statusText = 'Empty';
            $configStatus['⚠ Empty']++;
        }
    } else {
        $configStatus['✗ Missing']++;
    }

    // Show value for non-secret configs
    $valueDisplay = '';
    if ($info['configured'] && !in_array($setting, ['paystack_secret_key', 'paystack_public_key'])) {
        $valueDisplay = ' (' . substr($info['value'], 0, 50) . (strlen($info['value']) > 50 ? '...' : '') . ')';
    } elseif ($info['configured']) {
        $valueDisplay = ' (***hidden***)';
    }

    echo "  $icon $setting: $statusText$valueDisplay\n";
}

echo "\n";
echo "Summary: {$configStatus['✓ Configured']} configured, {$configStatus['⚠ Empty']} empty, {$configStatus['✗ Missing']} missing\n";
echo "\n";

// Validation results
if (!$validation['valid']) {
    echo "Validation Errors:\n";
    echo "══════════════════\n";
    foreach ($validation['errors'] as $error) {
        echo "  ✗ $error\n";
    }
    echo "\n";
}

if (!empty($validation['warnings'])) {
    echo "Warnings:\n";
    echo "═════════\n";
    foreach ($validation['warnings'] as $warning) {
        echo "  ⚠ $warning\n";
    }
    echo "\n";
}

// Vercel deployment status
echo "Vercel Deployment:\n";
echo "══════════════════\n";

// Check if .vercel directory exists
if (file_exists(__DIR__ . '/../../.vercel')) {
    echo "  ✓ Vercel project linked\n";

    // Check for project config
    $projectJson = __DIR__ . '/../../.vercel/project.json';
    if (file_exists($projectJson)) {
        $projectData = json_decode(file_get_contents($projectJson), true);
        if (isset($projectData['projectId'])) {
            echo "  ✓ Project ID: {$projectData['projectId']}\n";
        }
        if (isset($projectData['orgId'])) {
            echo "  ✓ Organization ID: {$projectData['orgId']}\n";
        }
    }
} else {
    echo "  ⚠ Vercel not linked (run: vercel link or vercel --prod)\n";
}

// Check vercel.json configuration
if (file_exists(__DIR__ . '/../../vercel.json')) {
    $vercelConfig = json_decode(file_get_contents(__DIR__ . '/../../vercel.json'), true);

    if (isset($vercelConfig['builds'])) {
        echo "  ✓ Builds configured: " . count($vercelConfig['builds']) . " build(s)\n";
    }

    if (isset($vercelConfig['routes'])) {
        echo "  ✓ Routes configured: " . count($vercelConfig['routes']) . " route(s)\n";

        // Check for Paystack route
        $hasPaystackRoute = false;
        foreach ($vercelConfig['routes'] as $route) {
            if (isset($route['src']) && strpos($route['src'], 'paystack') !== false) {
                $hasPaystackRoute = true;
                break;
            }
        }

        if ($hasPaystackRoute) {
            echo "  ✓ Paystack callback route configured\n";
        } else {
            echo "  ⚠ Paystack callback route not found in vercel.json\n";
        }
    }
}

echo "\n";

// Recommendations
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "  RECOMMENDATIONS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$recommendations = [];

// Check if installation needed
if (!$status['installed']) {
    $recommendations[] = [
        'priority' => 'HIGH',
        'action' => 'Run installer',
        'command' => 'php system/paymentgateway/paystack_install.php'
    ];
}

// Check if configuration needed
if ($status['installed'] && !$validation['valid']) {
    $recommendations[] = [
        'priority' => 'HIGH',
        'action' => 'Configure API keys in admin panel',
        'command' => 'Login to PHPNuxBill > Settings > Payment Gateway > Paystack'
    ];
}

// Check empty configs
foreach ($status['configs'] as $setting => $info) {
    if ($info['exists'] && !$info['configured'] && $setting !== 'enable_paystack') {
        $recommendations[] = [
            'priority' => 'MEDIUM',
            'action' => "Set value for $setting",
            'command' => 'Admin Panel > Payment Gateway > Paystack'
        ];
    }
}

// Check Vercel deployment
if (!file_exists(__DIR__ . '/../../.vercel')) {
    $recommendations[] = [
        'priority' => 'HIGH',
        'action' => 'Deploy callback to Vercel',
        'command' => 'vercel --prod'
    ];
}

// Check if gateway is enabled
$enabledConfig = ORM::for_table('tbl_appconfig')->where('setting', 'enable_paystack')->find_one();
if ($enabledConfig && $enabledConfig->value !== 'yes') {
    $recommendations[] = [
        'priority' => 'MEDIUM',
        'action' => 'Enable Paystack gateway',
        'command' => 'Admin Panel > Payment Gateway > Paystack > Enable: Yes'
    ];
}

if (empty($recommendations)) {
    echo "  ✓ All checks passed! Paystack gateway is ready to use.\n\n";
    echo "  Next steps:\n";
    echo "    1. Test with a low-value transaction (e.g., 3 KES)\n";
    echo "    2. Use test card: 4084084084084081\n";
    echo "    3. Verify success page displays correctly\n";
    echo "    4. Check transaction in Paystack dashboard\n";
} else {
    foreach ($recommendations as $rec) {
        $priorityColor = $rec['priority'] === 'HIGH' ? '!' : '→';
        echo "  $priorityColor [{$rec['priority']}] {$rec['action']}\n";
        echo "      {$rec['command']}\n\n";
    }
}

echo "\n";
echo "═══════════════════════════════════════════════════════\n\n";

// Exit with appropriate code
if (!$status['installed']) {
    exit(2); // Not installed
} elseif (!$validation['valid']) {
    exit(1); // Installed but not configured
} else {
    exit(0); // All good
}
