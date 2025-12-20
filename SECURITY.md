# Security Documentation

Carbon-Forum is designed with security as a priority. This document outlines the security mechanisms implemented in the application.

## Authentication & Authorization

*   **Hashing**: Passwords are hashed using MD5 with a unique per-user `Salt`.
    *   *Note: While MD5 is legacy, the implementation adds a Salt to mitigate rainbow table attacks. Future versions should migrate to `password_hash` (bcrypt/Argon2).*
*   **Sessions**: User sessions are maintained via cookies:
    *   `UserID`: The user's ID.
    *   `UserExpirationTime`: Timestamp when the session expires.
    *   `UserCode`: A hash signature verifying the authenticity of the cookie data.
    *   **Signature Logic**: `md5($PasswordHash . $Salt . $ExpirationTime . $SystemSalt)`. This prevents cookie tampering.
*   **Role Based Access Control (RBAC)**:
    *   Defined in `carbon_roles`.
    *   Checked via `Auth($MinRoleRequire)` function in controllers.
    *   Roles include: Guest (0), Member (1), VIP (2), Moderator (3), SuperMod (4), Admin (5).

## Input Sanitization & XSS Protection

*   **Mechanism**: **Whitelist-based HTML Filtering**.
*   **Class**: `WhiteHTMLFilter` (located in `library/WhiteHTMLFilter.php`).
*   **Operation**:
    *   All user-submitted content (posts, signatures) passes through `XssEscape()`.
    *   The filter parses the HTML and removes any tags or attributes not explicitly allowed in the whitelist.
    *   **Allowed Tags**: Basic formatting (`b`, `i`, `u`, `p`, `br`, `img`, `a`, `code`, `blockquote`, etc.).
    *   **Dangerous Tags**: `<script>`, `<iframe>` (restricted), `<object>`, `<embed>` are stripped.
    *   **URL Filtering**: Links and Image sources are validated to prevent `javascript:` URIs.

## SQL Injection Prevention

*   **Mechanism**: **Prepared Statements**.
*   **Implementation**: The `DB` class (`library/PDO.class.php`) enforces the use of PDO Prepared Statements.
*   **Policy**: No user input should ever be concatenated directly into a SQL string. All parameters are passed as bound variables.

## CSRF (Cross-Site Request Forgery) Protection

*   **Mechanism**: **FormHash Token**.
*   **Implementation**:
    *   Function `FormHash()` generates a time-insensitive token based on the user's secret hash and the system salt.
    *   State-changing forms (POST/PUT/DELETE) include `<input type="hidden" name="FormHash" value="...">`.
    *   Controllers verify the token: `if ($UserHash != FormHash()) ...`.
*   **Referer Check**: The `ReferCheck()` function also validates the `HTTP_REFERER` header to ensure the request originated from the same domain.

## Other Security Measures

*   **Directory Traversal**: File uploads and inclusions use strict path definitions and filename sanitization (`basename()`).
*   **Upload Security**:
    *   File extensions are whitelisted.
    *   Uploaded files are renamed to hashes (SHA1/MD5) to prevent execution of malicious scripts (e.g., `shell.php`).
    *   Image resizing removes metadata/EXIF which might contain payloads.
*   **Rate Limiting**:
    *   `PostingInterval` config limits how fast a user can post.
*   **Captcha**: `seccode.php` provides image verification for registration and login to prevent bots.

## Reporting Vulnerabilities

If you discover a security vulnerability, please do not disclose it publicly. Contact the maintainer at `lincanbin@hotmail.com` or open a confidential issue on the repository.
