# Carbon-Forum Changelog

## Version 6.0.0 (2026-02-02)

### Major Changes

#### PHP Version Requirement
- **BREAKING:** Updated minimum PHP version from 5.4 to 8.0
- Removed deprecated PHP 5.x compatibility code
- Removed magic quotes handling (deprecated in PHP 5.4, removed in PHP 7.0)
- Removed PHP 5.3.6 backward compatibility code from PDO class

#### Database Improvements
- **BREAKING:** Updated minimum MySQL version requirement from 5.0 to 5.7 (or MariaDB 10.2+)
- Changed default charset from `utf8` to `utf8mb4` for better Unicode support
- Improved support for emojis and special characters
- Database charset conversion available in update script (commented out by default)

#### Updated Dependencies
- Updated `stichoza/google-translate-php` from ~3.2 to ^5.1
- Added `phpmailer/phpmailer` ^6.9 to Composer dependencies
- Added explicit PHP ^8.0 requirement in composer.json

#### Docker Updates
- Updated base image from Ubuntu 14.04 to Ubuntu 22.04
- Updated PHP from php5-fpm to php8.1-fpm
- Updated Sphinx paths for PHP 8.1 compatibility
- Added DEBIAN_FRONTEND=noninteractive for better automated builds

#### CI/CD Updates
- Updated Travis CI to test PHP 8.0, 8.1, 8.2, 8.3, and nightly
- Removed PHP 5.x and 7.x from CI pipeline
- Removed conditional Composer installs (now always runs)
- Removed conditional auto_translate execution

### Security Improvements
- Removed outdated magic quotes code that could mask security issues
- Updated to utf8mb4 to prevent some character encoding attacks
- Modern PHP 8 includes built-in security hardening
- Updated dependencies to fix known vulnerabilities

### Performance Improvements
- PHP 8.0+ offers 10-30% performance improvement over PHP 5.x/7.x
- JIT compiler support (Just-In-Time compilation)
- Better memory management
- Faster string operations

### Documentation
- Added UPGRADE_NOTES.md with detailed migration instructions
- Updated README.md with new requirements
- Added this CHANGELOG.md file

### Known Issues
- jQuery remains at version 1.11.3 (needs manual update to 3.7.x)
  - See UPGRADE_NOTES.md for jQuery upgrade instructions
- Custom PHPMailer classes (5.2.16) still in codebase
  - Migration to Composer-managed PHPMailer 6.x recommended
- Some third-party JavaScript libraries may need updates

### Deprecation Notices
- The embedded PHPMailer classes will be removed in a future version
  - Use Composer-managed PHPMailer instead
- Legacy OAuth implementations may be updated in future versions
- Consider migrating to modern PHP frameworks for long-term support

### Migration Path from 5.9.0

1. **Backup everything** (database and files)
2. Verify PHP 8.0+ is installed
3. Verify MySQL 5.7+ is installed
4. Update Composer dependencies: `cd library && composer install`
5. Run update script: Navigate to `http://yourdomain.com/update`
6. Clear all caches
7. Test thoroughly

See UPGRADE_NOTES.md for detailed migration instructions.

### Breaking Changes

- **PHP 5.x and 7.x are no longer supported**
- **MySQL 5.0-5.6 are no longer officially supported**
- Code relying on magic quotes will not work (should already be fixed)
- Database connection now uses utf8mb4 by default (existing data is not automatically converted)

### Contributors

This update was made to modernize the Carbon-Forum codebase for better security, performance, and maintainability.

---

## Version 5.9.0 and Earlier

See previous releases for changelog of versions 5.9.0 and earlier.
