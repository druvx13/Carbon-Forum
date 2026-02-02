# Carbon-Forum 6.0.0 Upgrade Notes

## Major Changes from 5.9.0 to 6.0.0

### PHP Version Requirement
- **Old:** PHP 5.4.0+
- **New:** PHP 8.0.0+

The codebase has been updated to require PHP 8.0 or higher. Legacy PHP 5.x compatibility code has been removed.

### MySQL Version Requirement
- **Old:** MySQL 5.0+
- **New:** MySQL 5.7+ or MariaDB 10.2+

Character set changed from `utf8` to `utf8mb4` for better Unicode support.

### Removed Deprecated Code
- Removed magic quotes handling (deprecated in PHP 5.4, removed in PHP 7.0)
- Removed PHP 5.3.6 backward compatibility code
- Removed `PDO::MYSQL_ATTR_INIT_COMMAND` for charset (now using DSN charset parameter)

### Updated Dependencies

#### Composer Dependencies
- `stichoza/google-translate-php`: Updated from ~3.2 to ^5.1
- `phpmailer/phpmailer`: Added ^6.9 (was using embedded 5.2.16)

To update composer dependencies:
```bash
cd library
composer update
```

#### JavaScript Libraries (Manual Update Required)

**jQuery**
- **Current:** 1.11.3 (from 2014)
- **Target:** 3.7.1 (latest stable)
- **Security Note:** jQuery 1.x has known security vulnerabilities

To update jQuery:
1. Download jQuery 3.7.1 from https://code.jquery.com/jquery-3.7.1.min.js
2. Replace `/static/js/jquery.js` with the new version
3. Test all pages, especially:
   - File upload functionality
   - AJAX operations
   - Mobile responsive behavior
   - Admin dashboard

**Potential Breaking Changes with jQuery 3.x:**
- `.size()` removed (use `.length` instead)
- `.andSelf()` removed (use `.addBack()` instead)
- Some animation behaviors changed
- Event delegation may need updates

### Docker Image Updates
- **Base Image:** Ubuntu 14.04 → Ubuntu 22.04
- **PHP:** php5-fpm → php8.1-fpm
- **Sphinx paths:** Updated for PHP 8.1

To rebuild Docker image:
```bash
docker build -t carbon-forum:6.0 .
```

### CI/CD Updates
Travis CI configuration updated to test against:
- PHP 8.0
- PHP 8.1
- PHP 8.2
- PHP 8.3
- PHP nightly

### Database Schema
No database schema changes are required for this upgrade. The character set change from `utf8` to `utf8mb4` is handled automatically by PDO connection string.

However, if you want to convert existing data to utf8mb4:
```sql
ALTER DATABASE your_database_name CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
ALTER TABLE your_table_name CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Migration Steps

1. **Backup Everything**
   ```bash
   # Backup database
   mysqldump -u username -p database_name > backup.sql
   
   # Backup files
   tar -czf carbon-forum-backup.tar.gz /path/to/carbon-forum/
   ```

2. **Check PHP Version**
   ```bash
   php -v  # Should be 8.0 or higher
   ```

3. **Check MySQL Version**
   ```bash
   mysql --version  # Should be 5.7 or higher
   ```

4. **Update Code**
   - Pull the latest code from repository
   - Or extract the new version files

5. **Update Dependencies**
   ```bash
   cd library
   composer install
   ```

6. **Update jQuery (if not using CDN)**
   - Download jQuery 3.7.1
   - Replace `/static/js/jquery.js`

7. **Run Update Script**
   - Navigate to `http://yourdomain.com/update`
   - Follow the update wizard

8. **Clear Cache**
   - Clear MemCache/Redis if enabled
   - Clear browser cache
   - Clear any reverse proxy cache (if applicable)

9. **Test Functionality**
   - Test user registration and login
   - Test topic creation and replies
   - Test file uploads
   - Test search functionality
   - Test notifications
   - Test admin dashboard

### Known Issues & Compatibility

**PHPMailer Update**
The embedded PHPMailer 5.2.16 has been replaced with Composer-managed PHPMailer 6.9. If you're using custom email configurations, review the PHPMailer 6.x documentation for API changes.

**Sphinx Search**
If using Sphinx search, ensure your Sphinx configuration is compatible with PHP 8.x. The Sphinx PHP extension may need recompilation for PHP 8.

**Custom Modifications**
If you have custom modifications to the code, review them for PHP 8 compatibility:
- Check for use of deprecated functions
- Review string-to-number comparisons
- Check array access to non-arrays
- Review error handling

### Performance Improvements

PHP 8.0+ offers significant performance improvements over PHP 5.x/7.x:
- JIT compiler (Just-In-Time compilation)
- Improved type system
- Better memory management
- Faster string operations

Expected performance gain: 10-30% depending on workload.

### Security Improvements

- Removed outdated magic quotes code
- Updated to utf8mb4 prevents some character encoding attacks
- Modern PHP 8 includes security hardening
- Updated dependencies fix known vulnerabilities
- PHPMailer 6.x includes security fixes

### Support

For issues or questions about the upgrade:
- GitHub Issues: https://github.com/lincanbin/Carbon-Forum/issues
- Documentation: https://github.com/lincanbin/Carbon-Forum

### Rollback Plan

If you encounter critical issues:

1. Stop the web server
2. Restore from backup:
   ```bash
   mysql -u username -p database_name < backup.sql
   tar -xzf carbon-forum-backup.tar.gz -C /
   ```
3. Restart the web server

### Future Deprecations

Consider these for future versions:
- PHPAnalysis class may need updates for PHP 8.x compatibility
- Some OAuth implementations may need updates
- Consider migrating to modern PHP frameworks (Laravel, Symfony)
