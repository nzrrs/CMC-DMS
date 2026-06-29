# CMC-DMS — Backend

Laravel 13 backend for the CMC Document Management System.

---

## Table of Contents

1. [Installation](#installation)
2. [Project Structure](#project-structure)
3. [Services](#services)
4. [Authentication](#authentication)
5. [Routes Overview](#routes-overview)
6. [Middleware & RBAC](#middleware--rbac)
7. [PhpWord](#phpword)

---

## Installation

### Requirements

* PHP 8.5+
* Composer
* MySQL

### Setup

```bash
composer install

cp .env.example .env

php artisan key:generate

# Configure your database credentials in .env

php artisan migrate --seed

php artisan serve
```

---

## Project Structure

```text
backend/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Api/
│   │       │   ├── AuthController.php
│   │       │   ├── DocumentController.php
│   │       │   └── GroupController.php
│   │       ├── AuthController.php
│   │       ├── DocumentController.php
│   │       └── GroupController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Employee.php
│   │   ├── DocumentType.php
│   │   ├── DocumentField.php
│   │   ├── DocumentGroup.php
│   │   ├── GeneratedDocument.php
│   │   └── Role.php
│   └── Services/
│       ├── DocumentGenerationService.php
│       └── GroupDocumentService.php
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── README.md
├── resources/
│   └── views/
│       ├── auth/
│       ├── components/
│       ├── documents/
│       ├── groups/
│       └── layouts/
├── routes/
│   ├── web.php
│   └── api.php
├── storage/
│   └── app/
│       ├── templates/
│       └── generated/
└── tests/
    ├── Feature/
    ├── Unit/
    └── postman/
        └── CMC-DMS.postman_collection.json
```

---

## Services

### `DocumentGenerationService`

Responsible for generating a single Word document.

Responsibilities:

* Loads the `.docx` template from `storage/app/templates/`
* Resolves each placeholder according to its configured `field_type`
* Replaces placeholders using PhpWord's `TemplateProcessor`
* Saves generated documents in `storage/app/generated/`
* Updates the generation status in the `generated_documents` table
* Automatically increments the document version for each user and document type

Supported field types:

| Type     | Description                                                                                       |
| -------- | ------------------------------------------------------------------------------------------------- |
| `AUTO`   | Retrieves data from the configured database table and column                                      |
| `INPUT`  | User-provided text                                                                                |
| `SELECT` | User-selected value                                                                               |
| `DATE`   | Date input                                                                                        |
| `NUMBER` | Numeric input                                                                                     |
| `SYSTEM` | System-generated values such as reference number, current date, or authenticated user information |

### `GroupDocumentService`

Responsible for generating complete document groups.

Responsibilities:

* Retrieves every document belonging to a group
* Generates each document using `DocumentGenerationService`
* Creates a ZIP archive containing all generated documents
* Returns the archive for download

---

## Authentication

The backend provides two authentication mechanisms.

### Web Authentication

The web application uses Laravel's session authentication.

After a successful login:

* The authenticated user is stored in the session.
* Protected routes are guarded by the `auth` middleware.
* Guest routes are protected by the `guest` middleware.

### API Authentication

The REST API uses Laravel Sanctum.

Authenticate using:

| Method | Endpoint           | Description                                     |
| ------ | ------------------ | ----------------------------------------------- |
| POST   | `/api/auth/login`  | Authenticate and receive a Sanctum access token |
| POST   | `/api/auth/logout` | Revoke the current Sanctum token                |

All protected API endpoints require:

```http
Authorization: Bearer <token>
```

---

## Routes Overview

### Web Routes (`routes/web.php`)

| Method | URI                               | Description                                |
| ------ | --------------------------------- | ------------------------------------------ |
| GET    | `/`                               | Redirect to login or documents             |
| GET    | `/login`                          | Display the login page                     |
| POST   | `/login`                          | Authenticate user                          |
| POST   | `/logout`                         | Logout                                     |
| GET    | `/documents`                      | List accessible document types             |
| GET    | `/documents/{document}`           | Display the document generation form       |
| POST   | `/documents/{document}/generate`  | Generate a document                        |
| GET    | `/documents/download`             | Download the latest generated document     |
| GET    | `/documents/history`              | View generation history                    |
| GET    | `/generated/{generated}/download` | Download a generated document from history |
| GET    | `/groups/{group}`                 | Display a document group                   |
| POST   | `/groups/{group}/generate`        | Generate all documents in the group        |
| GET    | `/groups/download`                | Download the generated ZIP archive         |

### API Routes (`routes/api.php`)

#### Authentication

| Method | URI                | Description                                     |
| ------ | ------------------ | ----------------------------------------------- |
| POST   | `/api/auth/login`  | Authenticate and receive a Sanctum access token |
| POST   | `/api/auth/logout` | Logout and revoke the current token             |

#### Documents

| Method | URI                                  | Description                                         |
| ------ | ------------------------------------ | --------------------------------------------------- |
| GET    | `/api/documents`                     | List available document types                       |
| GET    | `/api/documents/{document}/fields`   | Retrieve the fields required to generate a document |
| POST   | `/api/documents/{document}/generate` | Generate a document                                 |
| GET    | `/api/documents/{document}/download` | Download the generated document                     |

#### Groups

| Method | URI                            | Description                                      |
| ------ | ------------------------------ | ------------------------------------------------ |
| GET    | `/api/groups`                  | List available document groups                   |
| GET    | `/api/groups/{group}/fields`   | Retrieve the fields required to generate a group |
| POST   | `/api/groups/{group}/generate` | Generate all documents in the group              |
| GET    | `/api/groups/{group}/download` | Download the generated ZIP archive               |

---

## Middleware & RBAC

Authentication and authorization are enforced at two levels.

### Middleware

* `guest` protects authentication pages.
* `auth` protects all web routes.
* `auth:sanctum` protects all API routes.

### Role-Based Access Control

Users can only access document types assigned to their role.

The `role_document_types` pivot table determines which document types are available for each role. Both the web and API controllers filter documents based on the authenticated user's role.

---

## PhpWord

Document generation relies on **PhpOffice/PhpWord**.

`TemplateProcessor` loads a Word template and replaces `${placeholder}` variables with values resolved by `DocumentGenerationService`.

```php
$processor = new TemplateProcessor(storage_path('app/templates/template.docx'));

$processor->setValue('fullname', $value);

$processor->saveAs(storage_path('app/generated/output.docx'));
```

Template files are stored in:

```text
storage/app/templates/
```

Generated documents and ZIP archives are stored in:

```text
storage/app/generated/
```

> [!TIP]
> Template placeholders must exactly match the corresponding `field_key` values defined in the `document_fields` table. Otherwise, placeholders may remain unreplaced in the generated document.
