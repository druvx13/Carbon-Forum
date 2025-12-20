# Carbon-Forum

**High Performance, Light-weight, and Secure Forum Software written in PHP.**

---

## 📖 Table of Contents

1.  [Project Overview](#project-overview)
2.  [Features](#features)
3.  [Technology Stack](#technology-stack)
4.  [Directory Structure](#directory-structure)
5.  [System Requirements](#system-requirements)
6.  [Installation](#installation)
7.  [Configuration](#configuration)
8.  [Database Setup](#database-setup)
9.  [Administration](#administration)
10. [Development Workflow](#development-workflow)
11. [Security](#security)
12. [Limitations](#limitations)
13. [License](#license)

---

## 🔭 Project Overview

Carbon-Forum is an open-source forum software designed with performance and simplicity in mind. Unlike heavy legacy forum systems, Carbon-Forum avoids complex frameworks, utilizing a native PHP MVC architecture to ensure maximum speed and minimal resource usage.

It features a modern, responsive user interface, real-time notifications, and a robust tagging system, making it ideal for communities ranging from small interest groups to large-scale discussions.

---

## ✨ Features

*   **High Performance**: Custom-built MVC without heavy dependencies.
*   **Topic Tagging**: Flexible tagging system instead of rigid categories.
*   **Real-time Notifications**: Mention users (@user) and get instant alerts.
*   **Mobile Ready**: Dedicated mobile theme for optimal experience on small screens.
*   **API Support**: JSON API for building third-party clients.
*   **Search**: Built-in support for Sphinx search engine.
*   **Caching**: Native support for Memcached and Redis.
*   **OAuth Integration**: Sign in with GitHub, QQ, WeChat, and Weibo.
*   **Markdown & Rich Text**: Support for rich content formatting.

---

## 🛠 Technology Stack

*   **Language**: PHP 5.4+ (Compatible with PHP 7/8)
*   **Database**: MySQL / MariaDB
*   **Server**: Nginx / Apache / IIS
*   **Frontend**: jQuery, Plain JS, HTML5/CSS3
*   **Caching**: Memcached / Redis (Optional)

---

## 📂 Directory Structure

For a detailed breakdown, see [DIRECTORY_STRUCTURE.md](DIRECTORY_STRUCTURE.md).

*   `controller/`: Request logic.
*   `view/`: HTML Templates.
*   `library/`: Core classes and helpers.
*   `install/`: Installer scripts.
*   `upload/`: User-uploaded content.

---

## 🖥 System Requirements

*   **OS**: Linux (Recommended), Windows, macOS.
*   **Web Server**: Nginx (Recommended), Apache, IIS.
*   **PHP Extensions**: `pdo_mysql`, `mbstring`, `curl`, `gd`.
*   **Database**: MySQL 5.5+ or MariaDB 5.5+.

---

## 🚀 Installation

For detailed deployment instructions, see [DEPLOYMENT.md](DEPLOYMENT.md).

### Quick Start (Docker)

```bash
git clone https://github.com/lincanbin/Carbon-Forum.git
cd Carbon-Forum
docker build -t carbon-forum .
docker run -d -p 80:80 --name carbon-forum carbon-forum
```

Visit `http://localhost/install/` to complete the setup.

### Manual Installation

1.  Upload files to your web root.
2.  Set write permissions for `config.php` (if exists), `upload/`, and `library/`.
3.  Configure URL rewriting (Nginx/Apache).
4.  Navigate to `/install/` in your browser.

---

## ⚙️ Configuration

Configuration is managed in two places:
1.  `config.php`: Environment settings (DB config, Cache config).
2.  **Admin Dashboard**: Site settings (Title, Description, Page size).

See [CONFIGURATION.md](CONFIGURATION.md) for a full reference.

---

## 🗄 Database Setup

The schema is automatically installed via the web installer.
To manually inspect the schema, check `install/database.sql`.
See [DATA_FLOW.md](DATA_FLOW.md) for schema documentation.

---

## 🔧 Administration

Administrators can manage the forum via the Dashboard (`/dashboard`).

Key capabilities include:
*   **System Settings**: Configure site name, SEO descriptions, and pagination limits.
*   **User Management**: Ban users, manage roles, and reset passwords.
*   **Content Management**: Lock topics, sticky posts, and delete spam.
*   **Tag Management**: Create, merge, and delete topic tags.
*   **Statistics**: View daily and monthly growth charts.

See [ADMIN_GUIDE.md](ADMIN_GUIDE.md) for a detailed walkthrough.

---

## 💻 Development Workflow

1.  **Local Setup**: Use the provided `Dockerfile` or a local AMP stack.
2.  **MVC Pattern**:
    *   Add a route in `index.php`.
    *   Create a controller in `controller/`.
    *   Create a view in `view/default/`.
3.  **Code Style**: Follow standard PHP PSR guidelines where possible, though the codebase has its own legacy style (PascalCase for variables).
4.  **Testing**:
    *   Run `test.php` (if available) for basic checks.
    *   Verify changes across Desktop and Mobile views.

---

## 🛡 Security

Carbon-Forum implements rigorous security standards:
*   **XSS Protection**: Whitelist-based HTML filter.
*   **CSRF Protection**: Token-based form verification.
*   **SQL Injection**: Full use of PDO Prepared Statements.
*   **Session Security**: Signed cookies to prevent tampering.

See [SECURITY.md](SECURITY.md) for details.

---

## ⚠️ Limitations & Assumptions

*   **DB Foreign Keys**: The schema does not use foreign key constraints; integrity is managed at the application level.
*   **Routing**: Requires web server rewrite rules; will not work on simple PHP hosting without `.htaccess` or Nginx config access.
*   **Uploads**: Local filesystem storage only (by default); no native S3 support.

---

## 📄 License

Carbon-Forum is licensed under the **Apache License 2.0**.
See [LICENSE](LICENSE) for the full text.

Copyright (c) 2006-2017 Canbin Lin.
