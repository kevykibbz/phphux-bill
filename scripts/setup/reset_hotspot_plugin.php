<?php

/**
 * Remove Hotspot Voucher Plugin Data
 * This removes plugin tables and configs to test auto-installation
 * 
 * Usage: php scripts/setup/reset_hotspot_plugin.php
 */

require_once dirname(dirname(dirname(__FILE__))) . '/init.php';

echo "\n";
echo "═══════════════════════════════════════════════════════\n";
echo "  HOTSPOT PLUGIN - RESET FOR AUTO-INSTALL TEST\n";
echo "═══════════════════════════════════════════════════════\n\n";

echo "⚠ This will remove ALL hotspot plugin data!\n";
echo "Tables to drop:\n";
echo "  • tbl_hotspot_payments\n";
echo "  • tbl_hotspot_vouchers\n";
echo "  • tbl_hotspot_tokens\n";
echo "  • tbl_hotspot_token_activity_logs\n\n";

echo "Configs to delete:\n";
echo "  • hotspot_* (all hotspot configs)\n\n";

// Drop tables
$tables = ['tbl_hotspot_payments', 'tbl_hotspot_vouchers', 'tbl_hotspot_tokens', 'tbl_hotspot_token_activity_logs'];
$dropped = 0;

foreach ($tables as $table) {
    try {
        $db = ORM::get_db();
        $db->exec("DROP TABLE IF EXISTS $table");
        echo "✓ Dropped table: $table\n";
        $dropped++;
    } catch (Exception $e) {
        echo "✗ Failed to drop $table: " . $e->getMessage() . "\n";
    }
}

echo "\n";

// Delete configs
$configs = ORM::for_table('tbl_appconfig')
    ->where_like('setting', 'hotspot%')
    ->find_many();

$deleted = 0;
foreach ($configs as $cfg) {
    if ($cfg->delete()) {
        echo "✓ Deleted config: {$cfg->setting}\n";
        $deleted++;
    }
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "  SUMMARY\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
echo "✓ Tables dropped: $dropped\n";
echo "✓ Configs deleted: $deleted\n\n";

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "  NEXT: TEST AUTO-INSTALL\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "Access the hotspot plugin in admin panel:\n";
echo "  Plugins > Hotspot\n\n";
echo "The plugin should auto-create tables and configs!\n\n";

echo "═══════════════════════════════════════════════════════\n\n";
