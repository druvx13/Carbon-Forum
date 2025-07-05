# Carbon-Forum [![Build Status](https://travis-ci.org/lincanbin/Carbon-Forum.svg?branch=develop)](https://travis-ci.org/lincanbin/Carbon-Forum)

Carbon-Forum is a high-performance, open-source forum software written in PHP. It's designed to be lightweight, fast, and feature-rich, providing a modern discussion platform.

**Project Status:** This project is currently archived by its original author and is not actively maintained. This fork aims to provide updated documentation and potentially minor organizational improvements.

## Key Features

*   **Mobile-First Design:** Responsive layout for seamless use on desktops, tablets, and smartphones.
*   **Real-time Notifications:** Instant alerts for mentions, new replies, and other relevant activities.
*   **Tag-Based Discussions:** Organize topics with tags, similar to StackOverflow or Quora, for better discoverability.
*   **High Performance:** Optimized for speed with both frontend and backend enhancements.
*   **Asynchronous Operations:** Improves loading speed and user experience.
*   **SEO Friendly:** Designed with search engine optimization in mind, including mobile adaptation and Sitemap support.
*   **Draft Saving:** Automatic saving of drafts to prevent data loss.
*   **Modern Notification Center:** Centralized place for all user notifications.
*   **Multi-language Support:** Interface available in multiple languages.
*   **OAuth Integration:** Supports login via popular services like GitHub, QQ, Weibo (requires configuration).
*   **Docker Support:** Includes a Dockerfile for easy containerized deployment.
*   **Sphinx Search Integration:** (Optional but recommended for large forums) Provides powerful search capabilities.

## Demo / Official Website (Legacy)

*   Project Chinese Website: [www.94cb.com](http://www.94cb.com/) (May be offline)
*   Project English Website: [en.94cb.com](http://en.94cb.com/) (May be offline)

## Requirements

### Server Requirements:

*   **PHP:** Version 5.4.0 or higher (PHP 7.x recommended for better performance and security).
*   **Web Server:** Apache, Nginx, or any other web server that supports PHP.
    *   **Apache:** Requires `mod_rewrite` and `mod_headers`.
    *   **Nginx:** Configuration example provided in `nginx.conf` (requires `ngx_http_rewrite_module`).
    *   **IIS:** Requires `ISAPI_Rewrite` or similar URL rewriting module.
*   **Database:** MySQL version 5.0 or higher.
*   **PHP Extensions:**
    *   `PDO_MYSQL` (for database connectivity)
    *   `mbstring` (for multi-byte string operations)
    *   `curl` (for OAuth and other external requests)
    *   `gd` (for image manipulation, e.g., avatars)
    *   `dom` (for HTML processing, e.g., by `WhiteHTMLFilter`)
    *   Caching (Optional but Recommended): `Memcached`, `Redis`, or `XCache` PHP extension for improved performance.

### Client-Side Requirements:

*   A modern web browser (e.g., Chrome, Firefox, Safari, Edge).

## Installation

There are two primary ways to install Carbon-Forum:

**1. Manual Installation (Traditional Web Server)**

1.  **Download/Clone:**
    *   Download the latest release ZIP file or clone the repository:
        ```bash
        git clone https://github.com/lincanbin/Carbon-Forum.git
        cd Carbon-Forum
        ```

2.  **Upload Files:**
    *   Upload all the Carbon-Forum files to your web server's document root (e.g., `/var/www/html/forum` or `public_html/forum`).

3.  **Create Database:**
    *   Using a MySQL client (like phpMyAdmin, Adminer, or command line), create a new database (e.g., `carbon_forum_db`).
    *   Create a MySQL user and grant it all privileges on the newly created database. Note down the database name, username, and password.

4.  **Directory Permissions:**
    *   Ensure that the root directory of your Carbon-Forum installation is writable by the web server. This is necessary for the installer to create the `config.php` file.
        ```bash
        # Example (adjust path as needed):
        chown -R www-data:www-data /var/www/html/forum
        chmod -R 755 /var/www/html/forum
        # The installer will guide if more specific permissions are needed for /upload
        ```
    *   The `/upload/` directory and its subdirectories will also need to be writable by the web server for file uploads to function.

5.  **Run Installer:**
    *   Open your web browser and navigate to `http://yourdomain.com/path/to/forum/install/`.
    *   Follow the on-screen instructions:
        *   Select your preferred language.
        *   Enter your database host (usually `localhost`), database name, username, and password.
        *   Configure advanced settings (Sphinx, Cache) if needed, or leave them as default for now.
    *   If the installation is successful, it will create a `config.php` file in the root of your Carbon-Forum directory and an `install/install.lock` file.

6.  **First User is Admin:**
    *   After successful installation, navigate to your forum's main page (e.g., `http://yourdomain.com/path/to/forum/`).
    *   Register a new account. **The first user to register automatically becomes the administrator.**

7.  **Security:**
    *   For security reasons, it's recommended to delete the `install/` directory after successful installation, or at least ensure the `install/install.lock` file is present.

**2. Docker Installation**

This method uses the provided `Dockerfile` to run Carbon-Forum in a containerized environment. This setup also includes Nginx, PHP-FPM, MySQL, and Sphinx.

1.  **Prerequisites:**
    *   Docker installed on your system.

2.  **Build or Pull Image:**
    *   **Option A: Pull from Docker Hub (if available for this fork/version):**
        ```bash
        docker pull lincanbin/carbon-forum:latest
        # Note: This image might be from the original author.
        # For this specific repository, you might need to build it yourself.
        ```
    *   **Option B: Build the image locally:**
        ```bash
        git clone https://github.com/your-username/Carbon-Forum.git # Or the path to this repo
        cd Carbon-Forum
        docker build -t my-carbon-forum .
        ```
        (Replace `my-carbon-forum` with your preferred image name).

3.  **Run the Container:**
    *   The provided `Dockerfile` sets up a MySQL database named `knowledge` with user `klg_u` and password `magic*docker`. The installer will still run, and you should use these credentials.
        ```bash
        docker run -it -d -p 8080:80 \
               --name carbon-forum-app \
               my-carbon-forum
        # Or lincanbin/carbon-forum:latest if pulled
        ```
        This maps port 8080 on your host to port 80 in the container. Access the forum at `http://localhost:8080`.

4.  **Run Installer (via Docker):**
    *   Navigate to `http://localhost:8080/install/` in your browser.
    *   When prompted for database details by the Carbon-Forum installer:
        *   **Database Host:** `localhost` (or the MySQL service name if using Docker Compose with a separate MySQL container, but the current Dockerfile runs MySQL within the same container).
        *   **Database Name:** `knowledge`
        *   **Database Account:** `klg_u`
        *   **Database Password:** `magic*docker`
    *   Complete the installation steps.

5.  **First User is Admin:**
    *   As with the manual install, the first user to register via `http://localhost:8080/register` will become the administrator.

## Configuration

Carbon-Forum uses a combination of a PHP configuration file, database settings, and JSON files.

*   **`config.php` (Root Directory):**
    *   This file is generated during installation and is the primary configuration file.
    *   It contains:
        *   Database connection details (`DBHost`, `DBName`, `DBUser`, `DBPassword`).
        *   Language settings (`LanguagePath`).
        *   Cache settings (`EnableMemcache`, `MemCacheHost`, `MemCachePort`, `MemCachePrefix`).
        *   Sphinx search server details (`SearchServer`, `SearchPort`) if configured.
        *   Salt for security purposes (`SALT`).
    *   **Do not commit this file to version control if you customize it directly.**

*   **Database Configuration (`carbon_config` table):**
    *   Many site settings are stored in this table and can be managed through the Admin Dashboard (once logged in as an administrator).
    *   These settings are cached for performance.

*   **OAuth Configuration (`library/Oauth.config.json`):**
    *   Defines the display properties (alias, logo, button image) for OAuth providers.
    *   Actual OAuth App IDs and Secrets need to be configured in the Admin Dashboard (under "Settings" -> "OAuth Settings"). The system will then store these sensitive details in the `carbon_config` database table (prefixed with `OauthKey` for the provider, e.g., `OauthKeyQQ`).

*   **Word Filtering (`library/Filtering.words.config.json`):**
    *   To set up word filtering, copy `library/Filtering.words.config.template.json` to `library/Filtering.words.config.json`.
    *   Edit `library/Filtering.words.config.json` to define your filtering rules (regular expressions, replacements, and potential gag times).

*   **File Uploads (`library/Uploader.config.template.json`):**
    *   This template (`library/Uploader.config.template.json`) shows the structure for configuring file upload settings (e.g., allowed types, size limits).
    *   The application appears to load settings from `controller/upload_file.php` which might use these or have them hardcoded. For customization, you might need to copy the template to `library/Uploader.config.json` and adjust the loading logic if it's not already looking for this file. *(Developer Note: Further investigation needed on how `Uploader.config.json` is precisely used by the application vs. hardcoded controller settings).*

*   **Sphinx Search:**
    *   If using Sphinx, configure it via its own configuration file (typically `sphinx.conf`). The `Dockerfile` provides an example at `docker_resources/sphinx.conf`.
    *   Ensure the `SearchServer` and `SearchPort` in `config.php` (or via Admin Panel) point to your Sphinx daemon.
    *   The installer provides fields to set these.

## Project Structure

A brief overview of the main directories:

*   `controller/`: Contains PHP files that handle requests, interact with the model (database), and select views.
*   `view/`: Contains template files for different themes (default, mobile, api).
    *   `view/default/`: Desktop browser templates.
    *   `view/mobile/`: Mobile browser templates.
    *   `view/api/`: Templates/handlers for API responses.
*   `library/`: Contains core classes, third-party libraries (if not using Composer), and some configuration files.
*   `static/`: Holds static assets like CSS, JavaScript, and images.
*   `language/`: Contains language files for internationalization.
*   `install/`: Contains the installation script and database schema. **Delete after installation.**
*   `update/`: Contains scripts for upgrading the forum.
*   `upload/`: Default directory for user-uploaded files (avatars, images, etc.). Must be writable by the web server.
*   `docker_resources/`: Contains files specific to the Docker setup (Nginx config, startup script, Sphinx config).
*   `common.php`: A core file included by most scripts, containing global functions, session handling, and configuration loading.
*   `index.php`: The main entry point for the application, handles routing.
*   `config.php`: (Generated on install) Core configuration like database credentials.

## Upgrading

1.  **Backup:**
    *   **Files:** Backup your `/upload/` directory (contains user-uploaded content) and your `config.php` file.
    *   **Database:** Create a full backup of your Carbon-Forum database.
2.  **Replace Files:**
    *   Delete all old Carbon-Forum files and directories **EXCEPT** for `/upload/` and `config.php`.
    *   Upload the new version's files and directories into the same location.
3.  **Restore `config.php`:**
    *   Place your backed-up `config.php` file into the root of the new installation.
4.  **Directory Permissions:**
    *   Ensure the entire directory (especially `update/` and the root for `config.php` if it needed to be regenerated) is writable by the web server during the update process.
5.  **Run Updater:**
    *   Open your web browser and navigate to `http://yourdomain.com/path/to/forum/update/`.
    *   Follow any on-screen instructions. This will apply database schema changes and update settings.
6.  **Cleanup:**
    *   After a successful update, it's good practice to remove the `update/` directory or ensure `update/update.lock` is present.

## Contribution

Since the original project is archived, contributions to this fork might focus on:

*   Improving documentation.
*   Modernizing the codebase (e.g., PHP 7/8 compatibility, Composer integration).
*   Bug fixes.
*   Security enhancements.

If you wish to contribute:

1.  Fork the repository.
2.  Create a new branch for your feature or bug fix.
3.  Make your changes.
4.  Submit a pull request with a clear description of your changes.

## Troubleshooting

*   **`config.php` not found / Redirect to `/install/`:** This means `config.php` is missing or unreadable. Ensure the installation was completed or that `config.php` exists and has correct permissions.
*   **Database connection errors:** Double-check database credentials in `config.php` and ensure your MySQL server is running and accessible.
*   **File upload issues:** Verify that the `/upload/` directory and its subdirectories are writable by the web server process.
*   **URL Rewriting not working (pages other than homepage give 404):** Ensure `mod_rewrite` (Apache) or equivalent is enabled and configured correctly. Check the `.htaccess` file or Nginx configuration.
*   **"First user is admin" not working:** Make sure you are the very first user to register after a fresh installation. If other users registered before the intended admin, you might need to manually change the `UserRoleID` in the `carbon_users` table in the database (Admin role ID is typically 5).

## Related Projects (Legacy)

*   [API Documentation](https://github.com/lincanbin/Carbon-Forum-API-Documentation)
*   [Android Client for Carbon Forum](https://github.com/lincanbin/Android-Carbon-Forum)

## License

```
Copyright 2006-2017 Canbin Lin (lincanbin@hotmail.com)

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
```
This fork/modification is also distributed under the Apache License 2.0.
