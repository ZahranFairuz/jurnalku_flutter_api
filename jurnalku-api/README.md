# JurnalKu API

API backend untuk aplikasi JurnalKu yang dibangun dengan Laravel 12 dan menggunakan Laravel Sanctum untuk autentikasi.

## Teknologi

- **Framework**: Laravel 12
- **Database**: SQLite
- **Autentikasi**: Laravel Sanctum
- **PHP**: ^8.2

## Setup & Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd jurnalku-api
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
```bash
php artisan migrate
```

### 5. Jalankan Server
```bash
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

## API Endpoints

### Base URL
```
http://localhost:8000/api
```

### Authentication

#### 1. Login
**POST** `/login`

Login menggunakan NIS dan password.

**Request Body:**
```json
{
    "nis": "string",
    "password": "string"
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "user": {
            "id": 1,
            "name": "Nama Siswa",
            "nis": "12345678",
            "rombel": "XII RPL 1",
            "rayon": "Cicurug 1",
            "grade": "XII",
            "photo_profile": null
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

**Response Error (401):**
```json
{
    "success": false,
    "message": "NIS atau password salah",
    "errors": {
        "credentials": ["NIS atau password tidak valid"]
    }
}
```

#### 2. Logout
**POST** `/logout`

Logout dan hapus token saat ini.

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Logout berhasil"
}
```

### User Management

#### 1. Get All Users
**GET** `/users`

Mendapatkan daftar semua siswa dengan pagination dan filter.

**Query Parameters:**
- `search` (optional): Pencarian berdasarkan nama, NIS, atau rombel
- `rombel` (optional): Filter berdasarkan rombel
- `rayon` (optional): Filter berdasarkan rayon  
- `grade` (optional): Filter berdasarkan grade
- `page` (optional): Halaman (default: 1)
- `per_page` (optional): Jumlah data per halaman (default: 5)

**Example Request:**
```
GET /api/users?search=john&rombel=XII RPL 1&page=1&per_page=10
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Data siswa berhasil diambil",
    "data": {
        "users": [
            {
                "id": 1,
                "name": "Nama Siswa",
                "nis": "12345678",
                "rombel": "XII RPL 1",
                "rayon": "Cicurug 1",
                "grade": "XII",
                "photo_profile": null,
                "created_at": "2024-01-01T00:00:00.000000Z",
                "updated_at": "2024-01-01T00:00:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 5,
            "total": 50,
            "last_page": 10,
            "from": 1,
            "to": 5
        }
    }
}
```

#### 2. Get User Profile
**GET** `/profile`

Mendapatkan profil user yang sedang login.

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Profile berhasil diambil",
    "data": {
        "user": {
            "id": 1,
            "name": "Nama Siswa",
            "nis": "12345678",
            "rombel": "XII RPL 1",
            "rayon": "Cicurug 1",
            "grade": "XII",
            "photo_profile": null,
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    }
}
```

#### 3. Get Current User
**GET** `/user`

Mendapatkan data user yang sedang login (alternatif endpoint).

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Nama Siswa",
        "nis": "12345678",
        "rombel": "XII RPL 1",
        "rayon": "Cicurug 1",
        "grade": "XII",
        "photo_profile": null,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

## Data Models

### User Model
```php
{
    "id": "integer",
    "name": "string",
    "nis": "string (unique)",
    "rombel": "string",
    "rayon": "string", 
    "grade": "string",
    "photo_profile": "string|null",
    "password": "string (hashed)",
    "created_at": "timestamp",
    "updated_at": "timestamp"
}
```

## Error Handling

API menggunakan format response error yang konsisten:

**Validation Error (422):**
```json
{
    "success": false,
    "message": "Validasi gagal",
    "errors": {
        "field_name": ["Error message"]
    }
}
```

**Server Error (500):**
```json
{
    "success": false,
    "message": "Terjadi kesalahan server",
    "error": "Error details"
}
```

**Unauthorized (401):**
```json
{
    "success": false,
    "message": "Unauthenticated"
}
```

## Authentication

API menggunakan Laravel Sanctum untuk autentikasi berbasis token. Setelah login berhasil, sertakan token dalam header:

```
Authorization: Bearer {your-token}
```

## Status Codes

- `200` - OK
- `401` - Unauthorized
- `422` - Validation Error
- `500` - Server Error

## Development

### Menjalankan Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

### Database Seeding
```bash
php artisan db:seed
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).q