# Spiral Store – Backend API

## Project Description

Spiral Store is an e-commerce REST API developed using Laravel. It provides product management, user authentication, and role-based authorization using Laravel Sanctum.

The API allows authenticated users to view products while restricting product creation, updating, and deletion to administrators.

## Technologies Used

- PHP
- Laravel
- MySQL
- Laravel Sanctum
- REST API
- Composer

## Features

### Authentication
- User registration
- User login
- User logout
- Password hashing
- Token-based authentication using Laravel Sanctum
- Token revocation upon logout

### Authorization
- Admin and normal user roles
- Authentication middleware
- Admin middleware
- Protected API routes
- Unauthorized guest requests return HTTP 401
- Restricted user actions return HTTP 403

### Product Management
- Retrieve all products
- Retrieve a specific product
- Create products (Admin only)
- Update products (Admin only)
- Delete products (Admin only)

## API Endpoints

### Authentication

| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/register` | Register a new user |
| POST | `/api/login` | Login |
| POST | `/api/logout` | Logout and revoke token |

### Products

| Method | Endpoint | Access |
|---|---|---|
| GET | `/api/products` | Authenticated users |
| GET | `/api/products/{product}` | Authenticated users |
| POST | `/api/products` | Admin only |
| PUT/PATCH | `/api/products/{product}` | Admin only |
| DELETE | `/api/products/{product}` | Admin only |

Protected endpoints require a valid Sanctum bearer token.

## Installation and Setup

### 1. Clone the Repository

```bash
git clone https://github.com/horeki850-sudo/Spiral-backend.git
```

### 2. Open the Project Folder

```bash
cd Spiral-backend
```

### 3. Install Dependencies

```bash
composer install
```

### 4. Configure Environment

Copy `.env.example` to `.env` and configure your database connection.

Example MySQL configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_db
DB_USERNAME=root
DB_PASSWORD=
```

Use your own database credentials if they are different.

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Database Migrations

```bash
php artisan migrate
```

### 7. Start the Laravel Server

```bash
php artisan serve
```

Default API base URL:

`http://127.0.0.1:8000/api`

## User Roles

| Role | Permissions |
|---|---|
| Admin | View, create, update, and delete products |
| Normal User | View products and product details |
| Guest | Cannot access protected product endpoints |

Newly registered users receive the default `user` role. Administrator privileges must be assigned through an authorized administrative process.

## Security

- Passwords are hashed before storage.
- Password hashes are not included in API responses.
- Authentication uses Laravel Sanctum personal access tokens.
- Logout revokes the current access token.
- Admin middleware restricts product management operations.
- User registration does not allow public role assignment.

## Testing Results

The following authorization and CRUD tests were performed:

| Test | Expected Result |
|---|---|
| Guest accesses products | 401 Unauthorized |
| Normal user views products | 200 OK |
| Normal user creates product | 403 Forbidden |
| Normal user updates product | 403 Forbidden |
| Normal user deletes product | 403 Forbidden |
| Admin creates product | 201 Created |
| Admin updates product | 200 OK |
| Admin deletes product | 200 OK |

## Frontend Repository

https://github.com/horeki850-sudo/Spiral-frontend

## Backend Repository

https://github.com/horeki850-sudo/Spiral-backend

## Developers

Developed as part of the Activity 8 requirements for authentication, authorization, and GitHub collaboration.
## API Authentication and Authorization

This API uses Laravel Sanctum for token-based authentication.

### Authentication Endpoints

| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/register` | Register a new user |
| POST | `/api/login` | Log in and receive an authentication token |
| POST | `/api/logout` | Log out and revoke the current token |

### Access Control

- Guests cannot access protected product endpoints.
- Authenticated users can view products.
- Only users with the admin role can create, update, or delete products.
- Unauthorized requests return HTTP 401.
- Authenticated users without admin permission receive HTTP 403.

### Authorization Middleware

Product management routes use the `auth:sanctum` and `admin` middleware to protect administrative operations.