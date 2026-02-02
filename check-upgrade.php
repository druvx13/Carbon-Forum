#!/usr/bin/env php
<?php
/**
 * Carbon-Forum 6.0.0 Pre-Upgrade Check Script
 * 
 * This script checks if your server environment meets the requirements
 * for upgrading to Carbon-Forum 6.0.0
 * 
 * Run this script before upgrading:
 * php check-upgrade.php
 */

echo "\n";
echo "==========================================================\n";
echo "  Carbon-Forum 6.0.0 Pre-Upgrade Environment Check\n";
echo "==========================================================\n\n";

$errors = [];
$warnings = [];
$passed = 0;
$failed = 0;

// Check PHP Version
echo "Checking PHP Version...\n";
$phpVersion = PHP_VERSION;
echo "  Current PHP Version: $phpVersion\n";
if (version_compare(PHP_VERSION, '8.0.0', '>=')) {
    echo "  ✓ PHP version is compatible (8.0+)\n\n";
    $passed++;
} else {
    echo "  ✗ PHP version is too old! Requires PHP 8.0 or higher\n\n";
    $errors[] = "PHP version must be 8.0 or higher (current: $phpVersion)";
    $failed++;
}

// Check Required Extensions
$requiredExtensions = [
    'pdo_mysql' => 'PDO MySQL extension',
    'mbstring' => 'Multibyte String extension',
    'curl' => 'cURL extension',
    'gd' => 'GD Graphics extension',
    'dom' => 'DOM extension',
    'json' => 'JSON extension'
];

echo "Checking Required PHP Extensions...\n";
foreach ($requiredExtensions as $ext => $name) {
    if (extension_loaded($ext)) {
        echo "  ✓ $name is installed\n";
        $passed++;
    } else {
        echo "  ✗ $name is NOT installed\n";
        $errors[] = "$name is required but not installed";
        $failed++;
    }
}
echo "\n";

// Check Recommended Extensions
$recommendedExtensions = [
    'memcached' => 'Memcached extension (for caching)',
    'redis' => 'Redis extension (alternative caching)',
    'opcache' => 'OPcache extension (performance)'
];

echo "Checking Recommended PHP Extensions...\n";
foreach ($recommendedExtensions as $ext => $name) {
    if (extension_loaded($ext)) {
        echo "  ✓ $name is installed\n";
    } else {
        echo "  ⚠ $name is not installed (recommended but not required)\n";
        $warnings[] = "$name is recommended for better performance";
    }
}
echo "\n";

// Check Database Connection
echo "Checking Database Connection...\n";
if (file_exists(__DIR__ . '/config.php')) {
    require(__DIR__ . '/config.php');
    try {
        $pdo = new PDO(
            'mysql:host=' . DBHost . ';port=' . (defined('DBPort') ? DBPort : 3306) . ';dbname=' . DBName,
            DBUser,
            DBPassword
        );
        echo "  ✓ Database connection successful\n";
        $passed++;
        
        // Check MySQL Version
        $mysqlVersion = $pdo->query('SELECT VERSION()')->fetchColumn();
        echo "  MySQL Version: $mysqlVersion\n";
        
        $versionNumber = preg_replace('/[^0-9.].*/', '', $mysqlVersion);
        if (version_compare($versionNumber, '5.7.0', '>=')) {
            echo "  ✓ MySQL version is compatible (5.7+)\n";
            $passed++;
        } else {
            echo "  ✗ MySQL version is too old! Requires MySQL 5.7 or higher\n";
            $errors[] = "MySQL version must be 5.7 or higher (current: $mysqlVersion)";
            $failed++;
        }
        
        // Check current database charset
        $dbCharset = $pdo->query("SELECT DEFAULT_CHARACTER_SET_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . DBName . "'")->fetchColumn();
        echo "  Current Database Charset: $dbCharset\n";
        if ($dbCharset === 'utf8mb4') {
            echo "  ✓ Database is already using utf8mb4\n";
        } else {
            echo "  ⚠ Database is using $dbCharset (utf8mb4 recommended)\n";
            $warnings[] = "Consider converting database to utf8mb4 for better Unicode support";
        }
        
    } catch (PDOException $e) {
        echo "  ✗ Database connection failed: " . $e->getMessage() . "\n";
        $errors[] = "Cannot connect to database: " . $e->getMessage();
        $failed++;
    }
} else {
    echo "  ⚠ config.php not found (expected for fresh install)\n";
    $warnings[] = "config.php not found - this is normal for fresh installations";
}
echo "\n";

// Check File Permissions
echo "Checking File Permissions...\n";
$writablePaths = [
    __DIR__ . '/upload',
    __DIR__ . '/install',
    __DIR__ . '/update',
    __DIR__
];

foreach ($writablePaths as $path) {
    if (is_dir($path) && is_writable($path)) {
        echo "  ✓ $path is writable\n";
        $passed++;
    } else if (is_dir($path)) {
        echo "  ✗ $path is NOT writable\n";
        $errors[] = "$path must be writable";
        $failed++;
    }
}
echo "\n";

// Check for old files that should be updated
echo "Checking for legacy code patterns...\n";
if (file_exists(__DIR__ . '/common.php')) {
    $commonContent = file_get_contents(__DIR__ . '/common.php');
    if (strpos($commonContent, 'get_magic_quotes_gpc') !== false) {
        echo "  ⚠ Legacy magic quotes code detected in common.php\n";
        $warnings[] = "common.php contains legacy magic quotes code (will be removed in upgrade)";
    } else {
        echo "  ✓ No legacy magic quotes code in common.php\n";
        $passed++;
    }
}
echo "\n";

// Summary
echo "==========================================================\n";
echo "  Environment Check Summary\n";
echo "==========================================================\n\n";

echo "Checks Passed: $passed\n";
echo "Checks Failed: $failed\n";
echo "Warnings: " . count($warnings) . "\n\n";

if ($failed > 0) {
    echo "❌ CRITICAL ERRORS FOUND:\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
    echo "\nPlease fix these errors before upgrading to Carbon-Forum 6.0.0\n\n";
    exit(1);
}

if (count($warnings) > 0) {
    echo "⚠️  WARNINGS:\n";
    foreach ($warnings as $warning) {
        echo "  - $warning\n";
    }
    echo "\nThese warnings won't prevent the upgrade but should be addressed.\n\n";
}

if ($failed === 0) {
    echo "✓ Your environment meets the requirements for Carbon-Forum 6.0.0!\n\n";
    echo "Next steps:\n";
    echo "  1. Backup your database and files\n";
    echo "  2. Update the code to version 6.0.0\n";
    echo "  3. Run: cd library && composer install\n";
    echo "  4. Visit: http://yourdomain.com/update\n";
    echo "  5. Clear all caches\n";
    echo "  6. Test thoroughly\n\n";
    echo "See UPGRADE_NOTES.md for detailed migration instructions.\n\n";
    exit(0);
}
