# CMC-DMS — Document Management System

CMC-DMS is a document management module developed for CMC. It automates the generation of Word documents from predefined templates using data stored in the database. Individual documents or complete document groups can be generated and downloaded, with group exports packaged as ZIP archives.

---

## Table of Contents

1. [Tech Stack](#tech-stack)
2. [Repository Structure](#repository-structure)
3. [Installation](#installation)
4. [Documentation](#documentation)
5. [Testing](#testing)
6. [License](#license)

---

## Tech Stack

* **Backend:** Laravel 13 (PHP 8.5)
* **Frontend:** React + Vite + Tailwind CSS v4
* **Database:** MySQL
* **Authentication:** Laravel Sanctum (API) & Session Authentication (Web)
* **Testing:** Pest v4
* **Document Processing:** PhpOffice/PhpWord
* **Archive Generation:** ZipArchive

---

## Repository Structure

```text
CMC-DMS/
├── backend/
│   ├── app/
│   ├── database/
│   │   └── README.md
│   ├── routes/
│   ├── tests/
│   └── README.md
├── frontend/
└── README.md
```

---

## Installation

### Requirements

* PHP 8.5+
* Composer
* Node.js
* MySQL

### Backend

```bash
git clone https://github.com/nzrrs/CMC-DMS.git

cd CMC-DMS/backend

composer install

cp .env.example .env

php artisan key:generate

# Configure your database credentials in .env

php artisan migrate --seed

php artisan serve
```

### Frontend

```bash
cd ../frontend

npm install

npm run dev
```

> [!TIP]
> The frontend expects the Laravel backend to be running before accessing protected features.

---

## Documentation

Additional documentation is available inside the backend directory.

### Backend Documentation

Complete backend architecture, services, authentication flow, routes, middleware, and document generation process are documented in:

**[`backend/README.md`](backend/README.md)**

### Database Documentation

Database schema, relationships, migrations, and the ER diagram are documented in:

**[`backend/database/README.md`](backend/database/README.md)**

> [!NOTE]
> The root README provides a project overview. Detailed backend and database documentation is maintained separately to keep this file concise.

---

## Testing

### Postman

The Postman collection is located at:

```text
backend/tests/postman/CMC-DMS.postman_collection.json
```

Authenticate first using the API login endpoint before accessing protected endpoints.

---

## License

Internal project developed for **CMC Béni Mellal (OFPPT)**.

> [!WARNING]
> This project is intended for internal use. Do not commit `.env`, generated documents, or other sensitive files to version control.
