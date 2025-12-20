# System Architecture

## Overview

Carbon-Forum follows a **Model-View-Controller (MVC)** architectural pattern, implemented in raw PHP without a heavy framework overhead. It is designed for high performance and simplicity.

## Core Components

### 1. Entry Point & Routing (`index.php`)

*   **Responsibility**: All requests (except for static files and direct script access) are routed through `index.php`.
*   **Mechanism**:
    *   It parses the `REQUEST_URI`.
    *   It defines a `$Routes` array mapping HTTP methods (GET, POST, etc.) and Regex URL patterns to Controller names.
    *   It matches the request against these patterns.
    *   It extracts parameters (e.g., Topic ID, Page Number) and populates `$_GET` / `$_REQUEST`.
    *   It includes the corresponding file from `controller/`.

### 2. Bootstrap & Configuration (`common.php`)

*   **Responsibility**: Initializes the application environment.
*   **Actions**:
    *   Starts the execution timer.
    *   Loads `config.php`. If missing, redirects to `install/`.
    *   Initializes the Database Connection (`$DB`).
    *   Initializes the Cache System (`$MCache` - Memcached/Redis/XCache).
    *   Loads system configuration from the database (`carbon_config` table).
    *   Defines global helper functions (e.g., `AlertMsg`, `Auth`, `Redirect`).
    *   Determines the client type (Desktop, Mobile, App/Spider).

### 3. Controller Layer (`controller/`)

*   **Responsibility**: Business logic and request handling.
*   **Workflow**:
    *   Validates input parameters.
    *   Checks user permissions (`Auth()` function).
    *   Interacts with the Database/Cache to retrieve or modify data.
    *   Prepares variables for the View.
    *   Includes the appropriate View file.

### 4. View Layer (`view/`)

*   **Responsibility**: Presentation logic.
*   **Structure**:
    *   **Themes**: Separated into `default` (Desktop), `mobile` (Mobile Web), and `api` (JSON output).
    *   **Templates**: Native PHP files.
    *   **Layouts**: `layout.php` usually serves as the master template (Header/Footer), wrapping specific content views.

### 5. Library Layer (`library/`)

*   **Responsibility**: Reusable components and database abstraction.
*   **Key Classes**:
    *   `DB` (`PDO.class.php`): Custom wrapper around PHP PDO. Handles connection, query execution, and logging.
    *   `WhiteHTMLFilter`: Security class for sanitizing user input (XSS protection).
    *   `Oauth`: Handling third-party authentication.

## Data Flow

1.  **Request**: User accesses `/t/123`.
2.  **Route**: `index.php` matches `GET /t/(?<id>[0-9]+)` -> `topic`.
3.  **Controller**: `controller/topic.php` is included.
    *   Checks Cache for Topic #123.
    *   If miss, queries `carbon_topics` table.
    *   Queries `carbon_posts` for replies.
    *   Updates View Count (buffered via Cache).
4.  **View**: `view/default/topic.php` is included.
    *   Renders HTML using the data provided by the controller.
5.  **Response**: HTML is sent to the browser.

## Caching Strategy

Carbon-Forum employs a two-level caching strategy:

1.  **Application Cache (Memcached/Redis)**:
    *   Used for frequently accessed data: Configs, User Info, Topic Metadata.
    *   Configurable via `config.php`.
2.  **Database Cache**:
    *   The `carbon_config` table caches some computed statistics.

## Security Architecture

*   **Input Sanitization**: All user-generated HTML is filtered through `WhiteHTMLFilter` (whitelist-based) to prevent XSS.
*   **SQL Injection**: Uses PDO Prepared Statements for all database queries.
*   **CSRF Protection**: `FormHash()` generates a token based on the user's session, verified on state-changing requests.
*   **Authentication**: Session-based, backed by secure Cookies (`UserCode` hash verification).

## Extension Points

*   **Services**: `service/` directory allows encapsulating complex business logic, though currently lightly used.
*   **Hooks**: The `plugin` system is not explicitly seen in the core structure but `library/` allows adding new utility classes easily.
