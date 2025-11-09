# Orders API

A RESTful API built with Laravel 12 for managing travel orders, users, and notifications. The API follows modern Laravel best practices with standardized exception handling, proper HTTP status codes, and comprehensive Swagger/OpenAPI documentation.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Architecture](#architecture)
- [Design Patterns](#design-patterns)
- [Folder Structure](#folder-structure)
- [API Documentation](#api-documentation)
- [Authentication](#authentication)
- [Response Format](#response-format)
- [Exception Handling](#exception-handling)
- [Commands](#commands)

## Requirements

- **WSL** (Windows Subsystem for Linux) - for Windows users
- **Docker** and **Docker Compose**
- **PHP 8.2+**
- **Composer**

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd orders-api
```

2. Install dependencies:
```bash
composer install
```

3. Start Docker containers using Laravel Sail:
```bash
./vendor/bin/sail up -d
```

4. Run migrations and seed the database:
```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

5. Generate Swagger documentation:
```bash
./vendor/bin/sail artisan l5-swagger:generate
```

6. Access the application:
   - API Base URL: `http://localhost`
   - Swagger Documentation: `http://localhost/api/documentation`

## Architecture

### Overview

The application follows a **layered architecture** with clear separation of concerns:

```
┌─────────────────────────────────────┐
│         HTTP Request                │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│         Routes (api.php)            │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│      Middleware (Sanctum Auth)      │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│      Form Request Validation        │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│         Controllers                 │
│  (Business Logic & Orchestration)   │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│         Models (Eloquent)           │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│         Database                    │
└─────────────────────────────────────┘
```

### Key Components

1. **Controllers** (`app/Http/Controllers/Api/V1/`)
   - Handle HTTP requests
   - Orchestrate business logic
   - Return standardized JSON responses

2. **Form Requests** (`app/Http/Requests/`)
   - Validate incoming requests
   - Conditional validation based on route context

3. **Resources** (`app/Http/Resources/`)
   - Transform models to API responses
   - Ensure consistent data structure

4. **Exceptions** (`app/Exceptions/`)
   - Custom exception classes
   - Centralized error handling

5. **Traits** (`app/Http/Traits/`)
   - Reusable response methods
   - Standardized API responses

## Design Patterns

### 1. **Repository Pattern** (Implicit)
- Eloquent models act as repositories
- Data access abstraction through models

### 2. **Resource Pattern**
- API Resources transform model data
- Consistent response structure across endpoints
- Located in `app/Http/Resources/`

### 3. **Trait Pattern**
- `ApiResponseTrait` provides reusable response methods
- Controllers use traits for standardized responses
- Reduces code duplication

### 4. **Form Request Pattern**
- Request validation separated from controllers
- Conditional validation based on route context
- Located in `app/Http/Requests/`

### 5. **Exception Handler Pattern**
- Global exception handling in `bootstrap/app.php`
- Custom exception classes for specific error types
- Centralized error response formatting

### 6. **API Versioning**
- Routes prefixed with `/api/v1/`
- Allows for future API versions without breaking changes

### 7. **RESTful Resource Controllers**
- Standard CRUD operations via `apiResource`
- RESTful route naming conventions

## Folder Structure

```
orders-api/
├── app/
│   ├── Exceptions/              # Custom exception classes
│   │   ├── ApiException.php
│   │   ├── ResourceNotFoundException.php
│   │   └── UnauthorizedException.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php          # Base controller with Swagger annotations
│   │   │   └── Api/
│   │   │       └── V1/                 # Version 1 API controllers
│   │   │           ├── TravelOrdersController.php
│   │   │           ├── UserController.php
│   │   │           └── UserNotificationsController.php
│   │   │
│   │   ├── Requests/                   # Form request validation
│   │   │   ├── StoreTravelOrdersRequest.php
│   │   │   ├── StoreUserRequest.php
│   │   │   ├── StoreUserNotificationsRequest.php
│   │   │   └── UpdateTravelOrdersRequest.php
│   │   │
│   │   ├── Resources/                  # API resource transformers
│   │   │   ├── TravelOrderResource.php
│   │   │   ├── UserResource.php
│   │   │   └── UserNotificationResource.php
│   │   │
│   │   └── Traits/                     # Reusable traits
│   │       └── ApiResponseTrait.php
│   │
│   ├── Models/                         # Eloquent models
│   │   ├── TravelOrders.php
│   │   ├── User.php
│   │   └── UserNotifications.php
│   │
│   └── Providers/                      # Service providers
│
├── bootstrap/
│   └── app.php                         # Application bootstrap & exception handling
│
├── config/
│   ├── l5-swagger.php                  # Swagger configuration
│   └── ...
│
├── database/
│   ├── migrations/                     # Database migrations
│   └── seeders/                        # Database seeders
│
├── routes/
│   └── api.php                         # API routes
│
├── storage/
│   └── logs/                           # Application logs
│
├── tests/                              # Test files
│   ├── Feature/
│   └── Unit/
│
├── vendor/                             # Composer dependencies
│
├── composer.json                       # PHP dependencies
├── docker-compose.yml                  # Docker configuration
├── phpunit.xml                         # PHPUnit configuration
└── README.md                           # This file
```

## API Documentation

The API is fully documented using Swagger/OpenAPI. After starting the application, access the interactive documentation at:

**Swagger UI**: `http://localhost/api/documentation`

The documentation includes:
- All available endpoints
- Request/response schemas
- Authentication requirements
- Example requests and responses
- Try-it-out functionality

### Regenerating Documentation

After making changes to controller annotations:

```bash
./vendor/bin/sail artisan l5-swagger:generate
```

## Authentication

The API uses **Laravel Sanctum** for token-based authentication.

### Getting an Access Token

**Endpoint**: `POST /api/authenticate`

**Request Body**:
```json
{
  "email": "admin@admin.com",
  "password": "password"
}
```

**Response**:
```json
{
  "success": true,
  "data": {
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
  },
  "message": "Token created successfully",
  "status_code": 200
}
```

### Using the Token

Include the token in the `Authorization` header for all protected routes:

```
Authorization: Bearer {your-token-here}
```

**Note**: Only users with the name "Admin" can create access tokens via `/api/authenticate`. Regular users should use `/api/v1/userLogin` for authentication.

## Response Format

All API responses follow a standardized format:

### Success Response

```json
{
  "success": true,
  "data": {
    // Response data here
  },
  "message": "Success message",
  "status_code": 200
}
```

### Error Response

```json
{
  "success": false,
  "message": "Error message",
  "status_code": 400,
  "errors": {
    // Validation errors (if applicable)
  }
}
```

### HTTP Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

## Exception Handling

The application implements centralized exception handling:

### Custom Exceptions

- `ApiException` - Base exception for API errors
- `ResourceNotFoundException` - 404 errors
- `UnauthorizedException` - 401 errors

### Global Exception Handler

Located in `bootstrap/app.php`, the handler:
- Catches all exceptions for API routes
- Formats errors consistently
- Logs exceptions for debugging
- Returns appropriate HTTP status codes

### Exception Types Handled

- `ModelNotFoundException` → 404
- `ValidationException` → 422
- `AuthenticationException` → 401
- `QueryException` → 500
- `NotFoundHttpException` → 404
- Custom `ApiException` → Custom status code

## API Endpoints

### Authentication

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/authenticate` | Get access token (Admin only) | No |
| POST | `/api/v1/userLogin` | User login | Yes |

### Users

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/v1/users` | List all users | Yes |
| POST | `/api/v1/users` | Create user | Yes |
| PUT | `/api/v1/users/{id}` | Update user | Yes |

### Travel Orders

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/v1/orders` | List all orders | Yes |
| POST | `/api/v1/orders` | Create order | Yes |
| GET | `/api/v1/orders/{id}` | Get order | Yes |
| PUT | `/api/v1/orders/{id}` | Update order | Yes |
| DELETE | `/api/v1/orders/{id}` | Delete order | Yes |
| POST | `/api/v1/filterOrders` | Filter orders | Yes |
| POST | `/api/v1/ordersByUser` | Get orders by user | Yes |

### Notifications

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/v1/notifications` | List all notifications | Yes |
| POST | `/api/v1/notifications` | Create notification | Yes |
| GET | `/api/v1/notifications/{id}` | Get notification | Yes |
| DELETE | `/api/v1/notifications/{id}` | Delete notification | Yes |
| POST | `/api/v1/showUserNotifications` | Get notifications by user | Yes |

## Commands

### Development

```bash
# Start Docker containers
./vendor/bin/sail up -d

# Stop Docker containers
./vendor/bin/sail down

# View logs
./vendor/bin/sail artisan tail

# Run migrations
./vendor/bin/sail artisan migrate

# Run migrations with seed
./vendor/bin/sail artisan migrate:fresh --seed
```

### Testing

```bash
# Run all tests
./vendor/bin/sail artisan test

# Run specific test suite
./vendor/bin/sail artisan test --testsuite=Feature
```

### Documentation

```bash
# Generate Swagger documentation
./vendor/bin/sail artisan l5-swagger:generate

# Clear cache
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
```

### Routes

```bash
# List all routes
./vendor/bin/sail artisan route:list

# List API routes only
./vendor/bin/sail artisan route:list --path=api
```

## Business Rules

### Travel Orders

- Orders cannot be cancelled if the start date is less than 30 days away
- When attempting to cancel, the API returns a 400 error with an appropriate message

### Authentication

- Only users with the name "Admin" can create access tokens via `/api/authenticate`
- Regular users authenticate via `/api/v1/userLogin`
- All protected routes require a valid Sanctum token

## Technologies Used

- **Laravel 12** - PHP Framework
- **Laravel Sanctum** - API Authentication
- **L5-Swagger** - API Documentation
- **Docker & Laravel Sail** - Development Environment
- **MySQL** - Database
- **PHPUnit** - Testing Framework

## Contributing

1. Create a feature branch
2. Make your changes
3. Write/update tests
4. Ensure all tests pass
5. Update documentation if needed
6. Submit a pull request

