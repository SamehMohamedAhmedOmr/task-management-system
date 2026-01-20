# Entity Relationship Diagram (ERD)

The following diagram illustrates the database schema and relationships for the Task Management System.

```mermaid
erDiagram
    roles ||--o{ users : has
    users ||--o{ tasks : "assigned to"
    users ||--o{ tasks : "created by"
    tasks ||--o{ task_dependencies : "has dependencies"
    tasks ||--o{ task_dependencies : "is dependency of"

    roles {
        bigint id PK
        string name "Manager, User"
        timestamp created_at
        timestamp updated_at
    }

    users {
        bigint id PK
        string name
        string email UK
        string password
        bigint role_id FK
        timestamp created_at
        timestamp updated_at
    }

    tasks {
        bigint id PK
        string title
        text description
        enum status "pending, in_progress, completed, canceled"
        date due_date
        bigint assigned_to FK
        bigint created_by FK
        timestamp created_at
        timestamp updated_at
    }

    task_dependencies {
        bigint id PK
        bigint task_id FK
        bigint depends_on_task_id FK
        timestamp created_at
        timestamp updated_at
    }
```

## Relationships

1. **Roles & Users**: One-to-Many
    - A Role has many Users.
    - A User belongs to one Role.

2. **Users & Tasks**: One-to-Many
    - A User (Manager) can create many Tasks (`created_by`).
    - A User can be assigned to many Tasks (`assigned_to`).

3. **Tasks & Task Dependencies**: Many-to-Many (Self-Referential)
    - A Task can depend on multiple other tasks (`dependencies`).
    - A Task can be a dependency for multiple other tasks (`dependentTasks`).
    - This logic is handled via the `task_dependencies` pivot table.
