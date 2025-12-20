# Configuration Documentation

This document details the configuration options available in Carbon-Forum. Configuration is split between the `config.php` file (environment settings) and the `carbon_config` database table (application settings).

## Environment Configuration (`config.php`)

This file is generated during installation from `install/config.tpl`.

### Basic Settings

| Constant | Description | Default |
| :--- | :--- | :--- |
| `InternalAccess` | Security constant to prevent direct access to included files. | `true` |
| `ForumLanguage` | The language code for the forum. | `zh-cn` |
| `SALT` | A random string used for hashing passwords and cookies. **Keep Secret**. | Generated |
| `PREFIX` | The prefix for database tables. | `carbon_` |
| `DEBUG_MODE` | Enables error reporting if set to `true`. | `false` |

### Database Settings

| Constant | Description |
| :--- | :--- |
| `DBHost` | Database server hostname. |
| `DBPort` | Database server port (default 3306). |
| `DBName` | Database name. |
| `DBUser` | Database username. |
| `DBPassword` | Database password. |

### Cache Settings (Memcached / Redis)

| Constant | Description | Default |
| :--- | :--- | :--- |
| `EnableMemcache` | boolean to enable/disable caching. | `false` |
| `MemCacheHost` | Cache server host. | `localhost` |
| `MemCachePort` | Cache server port (11211 for Memcached, 6379 for Redis). | `11211` |
| `MemCachePrefix` | Prefix for cache keys to avoid collisions. | `Carbon_` |

### Search Settings

| Constant | Description |
| :--- | :--- |
| `SearchServer` | Hostname for Sphinx search server. |
| `SearchPort` | Port for Sphinx search server. |

### API Security

| Variable | Description |
| :--- | :--- |
| `$APISignature` | An array mapping API Keys to API Secrets for signing requests. |

---

## Application Configuration (Database)

Stored in the `carbon_config` table. These can usually be modified via the Admin Dashboard.

### Site Identity
*   `SiteName`: The name of the forum.
*   `SiteDesc`: The meta description of the forum.
*   `SiteDomain`: The primary domain name.

### Content Settings
*   `TopicsPerPage`: Number of topics to show on the index.
*   `PostsPerPage`: Number of posts to show per topic page.
*   `MaxPostChars`: Maximum character limit for a post.
*   `MaxTagsNum`: Maximum number of tags per topic.
*   `MaxTagChars`: Maximum characters per tag.

### Feature Toggles
*   `AllowNewTopic`: Allow users to create new topics.
*   `CloseRegistration`: Disable new user registration.
*   `AllowEditing`: Allow users to edit their own posts.

### SMTP / Email (If configured)
*   `SMTPHost`: SMTP Server address.
*   `SMTPPort`: SMTP Port.
*   `SMTPAuth`: Enable SMTP Authentication (true/false).
*   `SMTPUsername`: SMTP Username.
*   `SMTPPassword`: SMTP Password.

### External Services
*   `AppDomainName`: Domain for the App API.
*   `MobileDomainName`: Domain for the mobile version.

---

## Environment Variables

While Carbon-Forum is primarily configured via `config.php`, Docker deployments may use environment variables to populate this file.

*   `DB_HOST`
*   `DB_NAME`
*   `DB_USER`
*   `DB_PASSWORD`
*   `MC_HOST` (Memcached Host)
