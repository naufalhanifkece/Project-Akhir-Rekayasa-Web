# Dokumentasi API dengan Token Bearer Authentication

## Alur Penggunaan

1. **Login** untuk mendapatkan token
2. **Gunakan token** di header Authorization untuk akses CRUD routes
3. **Logout** untuk menghapus token

---

## Endpoints

### 1. Login (Dapatkan Token)
**POST** `/api/auth/login`

**Headers:**
```
Content-Type: application/json
```

**Body (JSON):**
```json
{
    "email": "user@example.com",
    "password": "password123"
}
```

**Response Success (200):**
```json
{
    "message": "Login berhasil",
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "user@example.com",
            "created_at": "2025-12-17T10:00:00.000000Z",
            "updated_at": "2025-12-17T10:00:00.000000Z"
        },
        "api_token": "xxxxxxxxxxx...your_token_here...xxxxxxxxxxx"
    }
}
```

**Response Error (401):**
```json
{
    "message": "Email atau password salah",
    "success": false
}
```

---

### 2. Menggunakan Token untuk CRUD

**Semua CRUD Routes memerlukan token di header Authorization:**

```
Authorization: Bearer YOUR_API_TOKEN_HERE
Content-Type: application/json
```

#### Contoh: Create Siswa
**POST** `/api/siswa/create`

**Headers:**
```
Authorization: Bearer YOUR_API_TOKEN_HERE
Content-Type: application/json
```

**Body:**
```json
{
    "nama": "Budi Santoso",
    "email": "budi@example.com",
    "no_induk": "001"
}
```

#### Contoh: Read Siswa
**GET** `/api/siswa/read`

**Headers:**
```
Authorization: Bearer YOUR_API_TOKEN_HERE
```

#### Contoh: Update Siswa
**PUT** `/api/siswa/update/1`

**Headers:**
```
Authorization: Bearer YOUR_API_TOKEN_HERE
Content-Type: application/json
```

**Body:**
```json
{
    "nama": "Budi Santoso Updated",
    "email": "budi_new@example.com"
}
```

#### Contoh: Delete Siswa
**DELETE** `/api/siswa/delete/1`

**Headers:**
```
Authorization: Bearer YOUR_API_TOKEN_HERE
```

---

### 3. Get Current User Info
**GET** `/api/auth/me`

**Headers:**
```
Authorization: Bearer YOUR_API_TOKEN_HERE
```

**Response (200):**
```json
{
    "message": "User berhasil diambil",
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "created_at": "2025-12-17T10:00:00.000000Z",
        "updated_at": "2025-12-17T10:00:00.000000Z"
    }
}
```

---

### 4. Logout
**POST** `/api/auth/logout`

**Headers:**
```
Authorization: Bearer YOUR_API_TOKEN_HERE
```

**Response (200):**
```json
{
    "message": "Logout berhasil",
    "success": true
}
```

---

## Error Responses

### Token tidak ditemukan (401)
```json
{
    "message": "Token tidak ditemukan. Silakan login terlebih dahulu",
    "success": false
}
```

### Token tidak valid (401)
```json
{
    "message": "Token tidak valid atau sudah expired",
    "success": false
}
```

---

## Postman Setup

### Cara 1: Manual di Setiap Request
1. Buka tab "Headers"
2. Tambah header baru:
   - Key: `Authorization`
   - Value: `Bearer YOUR_API_TOKEN_HERE` (ganti dengan token dari login)

### Cara 2: Menggunakan Environment Variable (Recommended)
1. Login dan copy token dari response
2. Klik "Environment" di Postman
3. Buat variable baru: `api_token` dengan value token
4. Di request headers, gunakan: `Authorization: Bearer {{api_token}}`
5. Postman akan otomatis replace dengan nilai variable

### Cara 3: Pre-request Script (Otomatis Login)
Tambahkan di Collection Level atau Folder Level:
```javascript
// Login dulu jika belum ada token
if (!pm.environment.get("api_token")) {
    pm.sendRequest({
        url: pm.environment.get("base_url") + "/api/auth/login",
        method: "POST",
        header: {
            "Content-Type": "application/json"
        },
        body: {
            mode: "raw",
            raw: JSON.stringify({
                email: "user@example.com",
                password: "password123"
            })
        }
    }, function(err, response) {
        if (!err) {
            pm.environment.set("api_token", response.json().data.api_token);
        }
    });
}
```

---

## Langkah-langkah Setup

1. **Jalankan Migration:**
   ```bash
   php artisan migrate
   ```

2. **Buat User (Seeder atau Manual):**
   - Gunakan database seeder atau buat user di database
   - Email: `user@example.com`
   - Password: `password123`

3. **Login di Postman:**
   - POST `/api/auth/login`
   - Dapatkan `api_token`

4. **Gunakan Token:**
   - Tambahkan ke Authorization header
   - Sekarang bisa CRUD!

---

## Keamanan

- ⚠️ **Jangan bagikan token** ke orang lain
- ⚠️ **Jangan commit token** ke git
- ✅ Gunakan environment variable di Postman
- ✅ Ganti password secara berkala
- ✅ Logout untuk invalidate token
