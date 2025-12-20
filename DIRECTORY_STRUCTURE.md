# Directory Structure Documentation

This document provides a comprehensive overview of the file and directory structure of the Carbon-Forum repository.

## Root Directory

| File / Directory | Description |
| :--- | :--- |
| `controller/` | Contains the MVC Controllers. Each file corresponds to a route or set of routes. |
| `docker_resources/` | Configuration files and resources used for building the Docker environment. |
| `install/` | Installation scripts and initial database schema. Used for first-time setup. |
| `language/` | Localization files. Contains subdirectories for each supported language (e.g., `zh-cn`, `en`). |
| `library/` | Core classes and third-party libraries. Includes Database abstraction, OAuth, and utilities. |
| `service/` | Service layer logic. Currently minimal usage (`inbox.php`). |
| `static/` | Static assets including JavaScript, CSS, fonts, and images. |
| `update/` | Scripts for upgrading the forum software between versions. |
| `upload/` | Directory for user-uploaded content (avatars, topic images, files). |
| `view/` | MVC Views (Templates). Contains themes for different clients (`default`, `mobile`, `api`). |
| `.gitattributes` | Git configuration for file attribute handling. |
| `.gitignore` | Specifies intentionally untracked files to ignore. |
| `.travis.yml` | Travis CI configuration file. |
| `404.php` | The custom error page for 404 Not Found errors. |
| `Dockerfile` | Defines the Docker container environment for the application. |
| `LICENSE` | The main license file for the source code. |
| `README.md` | The primary entry point for project documentation. |
| `common.php` | The system bootstrap file. Handles initialization, config loading, and global functions. |
| `composer.json` | PHP dependency manager configuration. |
| `favicon.ico` | The website icon. |
| `httpd.ini` | ISAPI_Rewrite configuration for IIS. |
| `index.php` | The main application entry point and router. Dispatches requests to controllers. |
| `nginx.conf` | Sample Nginx configuration snippet. |
| `seccode.php` | Generates CAPTCHA images for verification. |
| `web.config` | IIS web server configuration. |

## Subdirectories Detail

### `controller/`

Handles incoming HTTP requests, interacts with the Model/Library layers, and selects Views for response.

*   `home.php`: Homepage logic.
*   `topic.php`: Single topic display logic.
*   `login.php`, `register.php`: Authentication logic.
*   `settings.php`: User settings logic.
*   `admin.php` (or similar, if present): Administration logic.
*   `api/`: (If present) API specific controllers.

### `library/`

Contains the heavy lifting classes.

*   `PDO.class.php`: A wrapper around PHP PDO for database interactions.
*   `Oauth.*.class.php`: Classes for handling OAuth login with various providers.
*   `ImageResize.class.php`: Utility for resizing uploaded images.
*   `WhiteHTMLFilter.php`: XSS filtering and HTML sanitization.
*   `vendor/`: (Generated) Composer dependencies.

### `view/`

Contains the presentation layer.

*   `default/`: The desktop web interface theme.
*   `mobile/`: Optimized theme for mobile devices.
*   `api/`: JSON response structures for API calls.
*   `layout.php`: The master layout template used by other views.

### `install/`

*   `database.sql`: The full database schema definition.
*   `config.tpl`: Template used to generate the production `config.php`.
*   `index.php`: The logic driving the web-based installer.

### `upload/`

**Note**: This directory must be writable by the web server user.

*   `avatar/`: User profile pictures.
*   `files/`: Generic file uploads.
