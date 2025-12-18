# Panduan Implementasi Token Bearer Authentication

## 📋 File yang Telah Dibuat/Diubah

### 1. **Migration**
- `database/migrations/2025_12_17_000001_add_api_token_to_users_table.php`
  - Menambah kolom `api_token` ke tabel users

### 2. **Model**
- `app/Models/User.php` (diupdate)
  - Menambah `api_token` ke `$fillable`
  - Menambah `api_token` ke `$hidden`

### 3. **Controller**
- `app/Http/Controllers/AuthController.php` (baru)
  - `login()` - Generate token bearer
  - `logout()` - Invalidate token
  - `me()` - Get current user info

### 4. **Middleware**
- `app/Http/Middleware/CheckApiToken.php` (baru)
  - Validasi token di setiap request
  - Reject jika token tidak ada atau invalid

### 5. **Routes**
- `routes/api.php` (diupdate)
  - Endpoint login tanpa middleware
  - Semua CRUD routes dilindungi middleware `check.api.token`

### 6. **Bootstrap**
- `bootstrap/app.php` (diupdate)
  - Register middleware alias

### 7. **Seeder**
- `database/seeders/DatabaseSeeder.php` (diupdate)
  - Menambah 2 user test dengan password `password123`

---

## 🚀 Langkah-langkah Setup

### 1. **Jalankan Migration**
```bash
php artisan migrate
```

### 2. **Jalankan Seeder** (untuk membuat user test)
```bash
php artisan db:seed
```

Ini akan membuat 2 user:
- **Email:** `test@example.com` | **Password:** `password123`
- **Email:** `admin@example.com` | **Password:** `password123`

---

## 📱 Testing di Postman

### Step 1: Login untuk Dapatkan Token

**Method:** POST
**URL:** `http://localhost:8000/api/auth/login`

**Headers:**
```
Content-Type: application/json
```

**Body (Raw JSON):**
```json
{
    "email": "test@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "message": "Login berhasil",
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "Test User",
            "email": "test@example.com",
            "created_at": "2025-12-17...",
            "updated_at": "2025-12-17..."
        },
        "api_token": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
    }
}
```

**⚠️ COPY TOKEN INI!** Anda akan membutuhkannya untuk CRUD.

---

### Step 2: Gunakan Token untuk CRUD

Semua request CRUD harus include header:
```
Authorization: Bearer YOUR_TOKEN_HERE
```

#### Contoh: Create Siswa (POST)
**Method:** POST
**URL:** `http://localhost:8000/api/siswa/create`

**Headers:**
```
Authorization: Bearer (paste token dari login)
Content-Type: application/json
```

**Body (Raw JSON):**
```json
{
    "nama": "Budi Santoso",
    "email": "budi@example.com",
    "no_induk": "001"
}
```

#### Contoh: Read Siswa (GET)
**Method:** GET
**URL:** `http://localhost:8000/api/siswa/read`

**Headers:**
```
Authorization: Bearer (paste token dari login)
```

#### Contoh: Update Siswa (PUT)
**Method:** PUT
**URL:** `http://localhost:8000/api/siswa/update/1`

**Headers:**
```
Authorization: Bearer (paste token dari login)
Content-Type: application/json
```

**Body (Raw JSON):**
```json
{
    "nama": "Budi Santoso Updated"
}
```

#### Contoh: Delete Siswa (DELETE)
**Method:** DELETE
**URL:** `http://localhost:8000/api/siswa/delete/1`

**Headers:**
```
Authorization: Bearer (paste token dari login)
```

---

## ⚙️ Setup Environment Variable di Postman (Recommended)

### Cara Otomatis:

1. **Login di Postman** menggunakan request login
2. Di tab **Tests** pada request login, tambahkan script:
```javascript
if (pm.response.code === 200) {
    var jsonData = pm.response.json();
    pm.environment.set("api_token", jsonData.data.api_token);
}
```

3. Di semua CRUD requests, ubah header Authorization menjadi:
```
Authorization: Bearer {{api_token}}
```

4. **Setiap kali login**, token akan otomatis tersimpan di environment variable

---

## ❌ Behavior Ketika Token Invalid/Tidak Ada

### Tanpa Token:
```json
{
    "message": "Token tidak ditemukan. Silakan login terlebih dahulu",
    "success": false
}
```
**HTTP Status:** 401

### Token Salah/Expired:
```json
{
    "message": "Token tidak valid atau sudah expired",
    "success": false
}
```
**HTTP Status:** 401

### Email/Password Salah saat Login:
```json
{
    "message": "Email atau password salah",
    "success": false
}
```
**HTTP Status:** 401

---

## 🔍 Verifikasi Implementation

### Cek 1: Coba Akses CRUD tanpa token
- **URL:** `http://localhost:8000/api/siswa/read` (tanpa header Authorization)
- **Expected:** Error 401 dengan message "Token tidak ditemukan"

### Cek 2: Coba Akses dengan token salah
- **URL:** `http://localhost:8000/api/siswa/read`
- **Header:** `Authorization: Bearer invalid_token_here`
- **Expected:** Error 401 dengan message "Token tidak valid"

### Cek 3: Coba Akses dengan token benar
- **URL:** `http://localhost:8000/api/siswa/read`
- **Header:** `Authorization: Bearer (valid_token)`
- **Expected:** 200 OK dengan data siswa

---

## 📝 Endpoints Summary

| Method | Endpoint | Auth | Purpose |
|--------|----------|------|---------|
| POST | `/api/auth/login` | ❌ | Login & dapatkan token |
| POST | `/api/auth/logout` | ✅ | Logout & invalidate token |
| GET | `/api/auth/me` | ✅ | Get info user yang login |
| POST | `/api/siswa/create` | ✅ | Create siswa |
| GET | `/api/siswa/read` | ✅ | Read semua siswa |
| PUT | `/api/siswa/update/{id}` | ✅ | Update siswa |
| DELETE | `/api/siswa/delete/{id}` | ✅ | Delete siswa |
| POST | `/api/guru/create` | ✅ | Create guru |
| GET | `/api/guru/read` | ✅ | Read semua guru |
| PUT | `/api/guru/update/{id}` | ✅ | Update guru |
| DELETE | `/api/guru/delete/{id}` | ✅ | Delete guru |
| POST | `/api/kelas/create` | ✅ | Create kelas |
| GET | `/api/kelas/read` | ✅ | Read semua kelas |
| PUT | `/api/kelas/update/{id}` | ✅ | Update kelas |
| DELETE | `/api/kelas/delete/{id}` | ✅ | Delete kelas |

---

## 🛡️ Security Notes

✅ **Done:**
- Token harus di-provide untuk akses CRUD
- Token di-generate secara random (80 char)
- Token di-store di database
- Middleware validasi di setiap CRUD request
- Error messages yang clear

⚠️ **Untuk Production:**
- Tambah token expiration
- Implement refresh token
- Rate limiting pada login
- HTTPS wajib
- Hash token di database

---

## 💡 Tips Penggunaan Postman

1. **Jangan hardcode token** - gunakan environment variable
2. **Simpan credential di Collection** untuk quick access
3. **Gunakan Pre-request Script** untuk auto-login jika token expired
4. **Test berbagai skenario:**
   - Login dengan email/password benar
   - Login dengan email/password salah
   - CRUD dengan token valid
   - CRUD tanpa token
   - CRUD dengan token invalid

---

## 🐛 Troubleshooting

**Q: Migration error saat jalankan `php artisan migrate`**
- A: Pastikan `.env` database configuration sudah benar dan database sudah dibuat

**Q: "Token tidak ditemukan" muncul padahal sudah login**
- A: Pastikan header Authorization format: `Bearer YOUR_TOKEN_HERE` (dengan spasi dan kata "Bearer")

**Q: Token benar tapi tetap error "Token tidak valid"**
- A: Pastikan token yang disimpan di database masih aktif (belum di-logout)

**Q: Lupa token yang digenerate**
- A: Login lagi untuk generate token baru

---

Selesai! Sistem token bearer authentication sudah siap digunakan. 🎉
