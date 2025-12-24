# JurnalKu - Aplikasi Jurnal Digital

Aplikasi jurnal digital untuk siswa yang terdiri dari frontend Flutter dan backend Laravel API.

## 📱 Frontend (Flutter)

Aplikasi mobile yang dibangun dengan Flutter untuk mencatat dan mengelola jurnal siswa.

### Fitur Utama
- 🔐 **Login** - Autentikasi dengan NIS dan password
- 📝 **Jurnal** - Mencatat dan mengelola jurnal harian
- 👤 **Profil** - Mengelola profil dan foto pengguna
- 📊 **Dashboard** - Ringkasan aktivitas dan statistik
- 🔍 **Explore** - Menjelajahi konten dan fitur
- 📈 **Progress** - Melacak kemajuan belajar
- ⚙️ **Settings** - Pengaturan aplikasi
- 📋 **Catatan Sikap** - Mencatat sikap dan perilaku
- 🤝 **Permintaan Saksi** - Fitur permintaan saksi
- 📖 **Panduan** - Panduan penggunaan aplikasi

### Teknologi
- **Flutter** 3.2.2+
- **Dart** SDK
- **HTTP** ^1.6.0 - HTTP client
- **Dio** ^5.3.2 - Advanced HTTP client
- **Shared Preferences** ^2.2.2 - Local storage
- **Image Picker** ^1.0.4 - Pemilihan gambar
- **Cupertino Icons** ^1.0.2 - iOS style icons

### Instalasi Frontend

1. **Prerequisites**
   - Flutter SDK 3.2.2+
   - Dart SDK
   - Android Studio / VS Code

2. **Setup**
   ```bash
   cd jurnalku
   flutter pub get
   flutter run
   ```

3. **Konfigurasi API**
   ```dart
   // Edit lib/services/api_service.dart
   static const String baseUrl = 'http://localhost:8000/api';
   ```

### Struktur Frontend
```
lib/
├── services/              # API services dan HTTP clients
├── widgets/               # Custom reusable widgets
├── main.dart             # Entry point aplikasi
├── login.dart            # Halaman login
├── explore_login.dart    # Halaman explore sebelum login
├── dashboard_page.dart   # Dashboard utama
├── jurnal_page.dart      # Halaman jurnal
├── profile_page.dart     # Halaman profil pengguna
├── explore_page.dart     # Halaman explore
├── progres_page.dart     # Halaman progress
├── setting_screen.dart   # Halaman pengaturan
├── catatan_sikap.dart    # Halaman catatan sikap
├── permintaan_saksi.dart # Halaman permintaan saksi
└── panduan_page.dart     # Halaman panduan
```

## 🚀 Backend (Laravel API)

REST API yang dibangun dengan Laravel untuk mengelola data jurnal dan autentikasi.

### Fitur API
- 🔐 **Authentication** - JWT/Sanctum token-based authentication
- 👥 **User Management** - Manajemen data pengguna/siswa
- 📸 **File Upload** - Upload dan manajemen foto profil
- 🔍 **Search & Filter** - Pencarian dan filter data siswa
- 📊 **Data Management** - CRUD operations untuk jurnal dan data

### Teknologi
- **Laravel** 12.0
- **PHP** 8.2+
- **Laravel Sanctum** 4.2 - API Authentication
- **JWT Auth** 2.2 - Token management
- **SQLite** - Database (development)
- **Composer** - Dependency management

### Instalasi Backend

1. **Prerequisites**
   - PHP >= 8.2
   - Composer
   - mysql

2. **Setup**
   ```bash
   cd jurnalku-api
   composer install
   cp .env.example .env
   php artisan key:generate
   touch database/database.sqlite
   php artisan migrate
   php artisan db:seed
   php artisan storage:link
   php artisan serve
   ```

### API Endpoints

#### Authentication
```http
POST /api/login
{
  "nis": "12345678",
  "password": "password123"
}

POST /api/logout (protected)
GET  /api/profile (protected)
GET  /api/user (protected)
```

#### User Management
```http
GET /api/users?search=john&rombel=XII RPL 1&page=1&per_page=10
POST /api/upload-photo (protected)
```

### Database Schema
```sql
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    nis VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rombel VARCHAR(50),
    rayon VARCHAR(50),
    grade VARCHAR(10),
    photo_profile VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```


API Endpoints Lengkap
Authentication
1. Login
http

```
POST /api/login
Content-Type: application/json

```

```
{
  "nis": "12345678",
  "password": "password123"
}


```
Response Success (200):

```
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 3,
      "name": "Zahran Fairuz Rahman",
      "nis": "12345678",
      "rombel": "PPLG XI-1",
      "rayon": "Cicurug 9",
      "grade": "XI",
      "photo_profile": "profile_photos/profile_3_1766376392.jpg"
    },
    "token": "4|QkueWp09u8cy9E3QTf0TIxOcestUgM4qLtjOco2k271864d0",
    "token_type": "Bearer"
  }
}
Response Error (401):

{
  "success": false,
  "message": "NIS atau password salah",
  "errors": {
    "credentials": ["NIS atau password tidak valid"]
  }
}

```

2. Logout (Protected)
   
```
POST /api/logout
Authorization: Bearer {token}
Response Success (200):

```

```
{
  "success": true,
  "message": "Logout berhasil"
}

```

3. Get Profile (Protected)

```
GET /api/profile
Authorization: Bearer {token}

```
Response Success (200):

json

```
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
      "photo_profile": "profile_photos/profile_1_1766370347.png",
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  }
}

```
User Management
4. Get All Users (Protected)

```
GET /api/users
Authorization: Bearer {token}

```

Response Success (200):

```
json
{
  "success": true,
  "message": "Data siswa berhasil diambil",
  "data": {
    "users": [
      {
        "id": 1,
        "name": "Abdul Hadi",
        "nis": "12345677",
        "rombel": "PPLG X-1",
        "rayon": "Cicurug 9",
        "grade": "X",
        "photo_profile": null,
        "created_at": "2025-12-22T02:38:50.000000Z",
        "updated_at": "2025-12-22T02:38:50.000000Z"
      },
      {
        "id": 2,
        "name": "Muhammad Fazri",
        "nis": "12344321",
        "rombel": "PPLG X-2",
        "rayon": "Cicurug 9",
        "grade": "X",
        "photo_profile": null,
        "created_at": "2025-12-22T02:38:50.000000Z",
        "updated_at": "2025-12-22T02:38:50.000000Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 5,
      "total": 3,
      "last_page": 1,
      "from": 1,
      "to": 3
    }
  }
}
```
5. Upload Photo (Protected)

```
POST /api/upload-photo
Authorization: Bearer {token}
Content-Type: multipart/form-data

```

photo: [file]
Response Success (200):

```
json
{
  "success": true,
  "message": "Foto profil berhasil diupload",
  "data": {
    "photo_url": "http://localhost:8000/storage/profile_photos/profile_3_1766542801.jpg",
    "photo_path": "profile_photos/profile_3_1766542801.jpg"
  }

```

### Authentication Flow

1. User memasukkan NIS dan password
2. App mengirim request ke `/api/login`
3. Server memvalidasi dan mengembalikan token
4. Token disimpan di SharedPreferences
5. Token digunakan untuk authenticated requests

```http
Authorization: Bearer {your-token}
```

## 🛠️ Development

### Prerequisites
- Flutter SDK 3.2.2+
- Dart SDK
- PHP 8.2+
- Composer
- Android Studio / VS Code

### Quick Start

1. **Start backend**
   ```bash
   cd jurnalku-api
   php artisan serve
   ```

2. **Start frontend**
   ```bash
   cd jurnalku
   flutter run
   ```

### Testing

#### Backend Testing
```bash
cd jurnalku-api
php artisan test
```

#### Frontend Testing
```bash
cd jurnalku
flutter test
```

## 📝 Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License.

## 👥 Team

- **Frontend Developer** - Flutter Mobile App
- **Backend Developer** - Laravel API
- **UI/UX Designer** - App Design

## 📞 Support

Jika ada pertanyaan atau masalah, silakan buat issue di repository ini.
