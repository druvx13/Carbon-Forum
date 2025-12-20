# Deployment Guide

This guide covers the deployment of Carbon-Forum in production environments.

## System Requirements

*   **PHP**: 5.4.0 or higher (PHP 7.x/8.x recommended for performance).
    *   Extensions: `pdo_mysql`, `mbstring`, `curl`, `gd`.
    *   Optional: `memcached`, `redis`, `xcache` (for caching).
*   **Web Server**: Nginx (recommended), Apache, or IIS.
*   **Database**: MySQL 5.5+ or MariaDB 5.5+.
*   **OS**: Linux (recommended), Windows, or macOS.

## Installation Methods

### Method 1: Docker (Recommended)

1.  **Clone the repository**:
    ```bash
    git clone https://github.com/lincanbin/Carbon-Forum.git
    cd Carbon-Forum
    ```

2.  **Build and Run**:
    ```bash
    docker build -t carbon-forum .
    docker run -d -p 80:80 --name carbon-forum carbon-forum
    ```
    *Note: You will need an external MySQL/MariaDB container or server linked.*

3.  **Access the Installer**:
    Navigate to `http://localhost/install/` and follow the on-screen instructions.

### Method 2: Manual Installation (Nginx + PHP-FPM)

1.  **Upload Files**:
    Copy the repository contents to your web root (e.g., `/var/www/html/`).

2.  **Set Permissions**:
    Ensure the web server user (e.g., `www-data`) has write access to:
    *   `config.php` (created during install)
    *   `upload/`
    *   `library/` (if using Composer)

    ```bash
    chown -R www-data:www-data /var/www/html
    chmod -R 755 /var/www/html/upload
    ```

3.  **Configure Nginx**:
    Use the provided `nginx.conf` logic. Key requirement is the rewrite rule for routing:

    ```nginx
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Protect sensitive files
    location ~ /(library|view|install|Dockerfile|LICENSE|README.md) {
        deny all;
        return 404;
    }
    ```

4.  **Run Composer** (Optional but recommended):
    ```bash
    composer install --no-dev --optimize-autoloader
    ```

5.  **Run Installer**:
    Open `http://yourdomain.com/install/` in your browser.

## Post-Installation

1.  **Security Cleanup**:
    *   Delete the `install/` directory after successful installation.
    *   Ensure `config.php` is not readable by the public (handled by web server config).

2.  **Cron / Scheduled Tasks**:
    Carbon-Forum triggers some maintenance tasks on page loads, but for high-traffic sites, consider offloading this.

## Troubleshooting

### "404 Not Found" on all pages except Home
**Cause**: URL Rewriting is not configured correctly.
**Fix**: Ensure your Nginx/Apache config passes all requests to `index.php`.

### "Database Connection Error"
**Cause**: Incorrect credentials or host in `config.php`.
**Fix**: Edit `config.php` manually to correct `DBHost`, `DBUser`, or `DBPassword`.

### "Permission Denied" on Uploads
**Cause**: `upload/` directory is not writable.
**Fix**: `chmod 777 upload/` or `chown` to the web server user.

## Updates

To update Carbon-Forum:
1.  Backup your `config.php` and `upload/` directory.
2.  Backup your Database.
3.  Overwrite the codebase with the new version.
4.  Restore `config.php`.
5.  Visit `http://yourdomain.com/update/` to run database migrations.
