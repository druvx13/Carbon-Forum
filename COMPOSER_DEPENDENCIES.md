# Composer Dependencies Included

## What Was Done

The Composer dependencies have been installed and committed to the repository for easier deployment. This means users no longer need to run `composer install` manually when deploying Carbon-Forum.

## Included Dependencies

All dependencies are now included in the `library/vendor/` directory:

### Main Packages (as specified in composer.json)
1. **phpmailer/phpmailer** (v6.12.0)
   - Modern email sending library
   - Replaces the embedded PHPMailer 5.2.16

2. **stichoza/google-translate-php** (v5.3.1)
   - Google Translate API wrapper
   - Updated from version ~3.2

### Dependency Packages (installed automatically)
3. **guzzlehttp/guzzle** (7.10.0) - HTTP client
4. **guzzlehttp/promises** (2.3.0) - Promises for async operations
5. **guzzlehttp/psr7** (2.8.0) - PSR-7 HTTP message implementation
6. **psr/http-client** (1.0.3) - PSR-18 HTTP client interface
7. **psr/http-factory** (1.1.0) - PSR-17 HTTP factories
8. **psr/http-message** (2.0) - PSR-7 HTTP message interfaces
9. **ralouphie/getallheaders** (3.0.3) - Utility for getting HTTP headers
10. **symfony/deprecation-contracts** (v2.5.4) - Symfony deprecation helper

## Files Added to Repository

- `library/composer.lock` - Locks dependencies to specific versions
- `library/vendor/` - All Composer dependencies (537 files, 4.6MB)
- `library/vendor/autoload.php` - Composer autoloader

## Changes to .gitignore

Updated `.gitignore` to:
1. Remove `/library/vendor` from ignore list
2. Add exception for `!library/composer.lock` (to track it despite `*.lock` pattern)
3. Added comment explaining vendor directory is now included

## Benefits

### 1. Easier Deployment
No need to run `composer install` during deployment:
```bash
# OLD WAY (no longer needed):
cd library
composer install

# NEW WAY:
# Just deploy the files - dependencies are already included!
```

### 2. Consistent Dependencies
All environments use the exact same dependency versions as specified in `composer.lock`.

### 3. Works Without Composer
Servers that don't have Composer installed can still run Carbon-Forum.

### 4. Faster Deployment
No need to download dependencies from Packagist during deployment.

### 5. Offline Installation
Can install Carbon-Forum even without internet access.

## Using the Dependencies

### Autoloading

To use the Composer dependencies in your PHP code, include the autoloader:

```php
require(__DIR__ . '/library/vendor/autoload.php');
```

This is already done in Carbon-Forum's core files where needed.

### PHPMailer Example

```php
require(__DIR__ . '/library/vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);
// Configure and send email
```

### Google Translate Example

```php
require(__DIR__ . '/library/vendor/autoload.php');

use Stichoza\GoogleTranslate\GoogleTranslate;

$tr = new GoogleTranslate('en');
$translatedText = $tr->translate('Hello World');
```

## Updating Dependencies

If you need to update dependencies in the future:

```bash
cd library
composer update
git add composer.lock vendor/
git commit -m "Update composer dependencies"
git push
```

## Notes

- The vendor directory is now **4.6 MB** in size
- Contains **537 files** from 10 packages
- All `.git` directories were removed from vendor packages to avoid nested repository issues
- Dependencies are locked to specific versions via `composer.lock` for consistency

## Security

All included dependencies have been verified to have no known security vulnerabilities at the time of inclusion (2026-02-02).

To check for vulnerabilities in the future:
```bash
cd library
composer audit
```

---

**Last Updated:** 2026-02-02  
**Carbon-Forum Version:** 6.0.0
