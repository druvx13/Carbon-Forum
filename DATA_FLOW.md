# Database Schema & Data Flow

## Data Flow Overview

Carbon-Forum relies on a relational database (MySQL/MariaDB) as the primary source of truth, augmented by an optional caching layer (Memcached/Redis).

### Read Path
1.  **Controller** requests data.
2.  **Cache Check**: If caching is enabled, the system checks the In-Memory Store (e.g., Key `Topic_123`).
3.  **DB Query**: On cache miss, a SQL query is executed via `PDO.class.php`.
4.  **Populate Cache**: The result is stored in the cache for future requests.
5.  **Return**: Data is returned to the Controller.

### Write Path
1.  **Controller** receives data.
2.  **DB Write**: Data is inserted/updated in the Database.
3.  **Cache Invalidation**: Related cache keys are deleted or updated to ensure consistency.

---

## Database Schema

The database consists of the following tables, prefixed (default `carbon_`).

### Core System

| Table | Purpose | Key Columns |
| :--- | :--- | :--- |
| `carbon_config` | System-wide configuration Key-Value store. | `ConfigName` (PK), `ConfigValue` |
| `carbon_users` | User accounts and profile data. | `ID`, `UserName`, `Password` (MD5+Salt), `UserRoleID` |
| `carbon_roles` | Role definitions (RBAC). | `ID`, `Name`, `Description` |
| `carbon_log` | System access and error logs. | `ID`, `UserName`, `DateCreated`, `ErrDescription` |
| `carbon_statistics`| Daily statistics (Users, Topics, Posts). | `DaysDate`, `TotalUsers`, `TotalTopics` |

### Content

| Table | Purpose | Key Columns |
| :--- | :--- | :--- |
| `carbon_topics` | Discussion threads. | `ID`, `Topic`, `UserID`, `Tags`, `IsLocked` |
| `carbon_posts` | Replies and content within topics. | `ID`, `TopicID`, `UserID`, `Content`, `IsTopic` |
| `carbon_tags` | Taxonomy/Tags for topics. | `ID`, `Name`, `TotalPosts` |
| `carbon_posttags` | Many-to-Many relationship between Posts/Topics and Tags. | `TagID`, `TopicID` |

### User Interaction

| Table | Purpose | Key Columns |
| :--- | :--- | :--- |
| `carbon_notifications` | User notifications (Mentions, Replies). | `ID`, `UserID`, `Type`, `TopicID` |
| `carbon_favorites` | User bookmarks/favorites (Topics, Tags). | `UserID`, `FavoriteID`, `Type` |
| `carbon_inbox` | Private conversation metadata. | `ID`, `SenderID`, `ReceiverID` |
| `carbon_messages` | Private message content. | `ID`, `InboxID`, `Content` |
| `carbon_postrating` | Ratings/Likes on posts. | `UserName`, `TopicID`, `Rating` |
| `carbon_vote` | Polls attached to topics. | `TopicID`, `Items`, `Result` |

### Assets & Extensions

| Table | Purpose | Key Columns |
| :--- | :--- | :--- |
| `carbon_upload` | Metadata for uploaded files. | `ID`, `FileName`, `FilePath`, `UserID` |
| `carbon_pictures` | Metadata for uploaded images. | `ID`, `PicUrl`, `TopicID` |
| `carbon_app` | OAuth Application registry (for API clients). | `AppKey`, `AppSecret` |
| `carbon_app_users`| Mapping between App users and Forum users. | `AppID`, `OpenID`, `UserID` |
| `carbon_link` | Friendly links / Blogroll. | `ID`, `Name`, `URL` |
| `carbon_dict` | Dictionary/Search indexing (internal usage). | `ID`, `Title`, `Abstract` |

---

## Key Relationships

*   **Topics <-> Users**: One-to-Many (`carbon_topics.UserID` -> `carbon_users.ID`).
*   **Posts <-> Topics**: One-to-Many (`carbon_posts.TopicID` -> `carbon_topics.ID`).
*   **Topics <-> Tags**: Many-to-Many (via `carbon_posttags` or serialized `Tags` column in `carbon_topics` for display).
*   **Users <-> Roles**: Many-to-One (`carbon_users.UserRoleID` -> `carbon_roles.ID`).

## Indexing Strategy

*   **Primary Keys**: All tables have an `ID` auto-increment primary key (except `carbon_config` and `carbon_postrating`).
*   **Foreign Keys**: Explicit FK constraints are **disabled** (`SET FOREIGN_KEY_CHECKS=0` in schema) to rely on application-level integrity and improve write performance.
*   **Performance Indices**:
    *   `UserName` is hashed or indexed for fast lookups.
    *   `TopicID` and `PostTime` are indexed in `carbon_posts` for pagination.
    *   `LastTime` in `carbon_topics` is indexed for sorting the topic list.

## Data Lifecycle

*   **Creation**: Records are created via `INSERT` statements in Controllers.
*   **Modification**: `UPDATE` statements handle edits. Optimistic locking is not strictly enforced but logic exists to handle race conditions (e.g., view counts).
*   **Deletion**: Soft deletes are often used (e.g., `IsDel` flag in `carbon_topics` and `carbon_posts`). Hard deletes are reserved for admin actions (`PermanentlyDelete`).
