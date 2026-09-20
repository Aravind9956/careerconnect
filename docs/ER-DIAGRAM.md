# CareerConnect - Database ER Diagram & Data Architecture

## 1. Entity Relationship Diagram (Mermaid)

```mermaid
erDiagram
    ROLES ||--o{ USERS : "has"
    USERS ||--o{ USER_PROFILES : "owns"
    USERS ||--o{ JOBS : "posts (Recruiter)"
    CATEGORIES ||--o{ JOBS : "classifies"
    JOBS ||--o{ APPLICATIONS : "receives"
    USERS ||--o{ APPLICATIONS : "submits (Seeker)"
    USERS ||--o{ SAVED_JOBS : "bookmarks"
    JOBS ||--o{ SAVED_JOBS : "bookmarked in"
    USERS ||--o{ NOTIFICATIONS : "receives"

    ROLES {
        int id PK
        string name
        string description
    }

    USERS {
        int id PK
        int role_id FK
        string username
        string email
        string password_hash
        string full_name
        string phone
        enum status
        boolean is_verified
    }

    USER_PROFILES {
        int id PK
        int user_id FK
        string title
        text bio
        text skills
        text experience
        text education
        string location
        string avatar
        string resume
    }

    CATEGORIES {
        int id PK
        string name
        string slug
        string icon
    }

    JOBS {
        int id PK
        int recruiter_id FK
        int category_id FK
        string title
        string company_name
        string location
        enum job_type
        enum experience_level
        string salary_range
        text description
        enum status
    }

    APPLICATIONS {
        int id PK
        int job_id FK
        int user_id FK
        text cover_letter
        string resume_file
        enum status
        timestamp applied_at
    }

    SAVED_JOBS {
        int id PK
        int user_id FK
        int job_id FK
        timestamp saved_at
    }

    OTP_VERIFICATIONS {
        int id PK
        string email
        string otp_code
        enum type
        boolean is_used
        datetime expires_at
    }
```

---

## 2. Table Normalization Analysis (3NF)

1. **First Normal Form (1NF)**: All attributes contain atomic values. Repeated structures (such as role strings or category metadata) have been removed.
2. **Second Normal Form (2NF)**: All non-key attributes are fully functionally dependent on primary keys. Composite keys in junction tables (`applications`, `saved_jobs`) are enforced with `UNIQUE` constraints.
3. **Third Normal Form (3NF)**: Transitive dependencies are eliminated. Role titles and descriptions reside exclusively in `roles`, preventing data anomaly redundancies.
