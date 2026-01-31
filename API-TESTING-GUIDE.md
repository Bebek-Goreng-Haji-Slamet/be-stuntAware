# Panduan Testing API StuntAware

## Setup Awal

### 1. Jalankan Server Laravel

```bash
cd /home/raffi-ahmad/Music/api-stuntAware/api
php artisan serve
# Server akan berjalan di http://localhost:8000
```

### 2. Import Collection Postman

- Buka Postman
- Click "Import"
- Pilih file `Postman-Collection.json`
- Collection akan otomatis ter-import dengan semua request

### 3. Set Variable Postman

Di tab "Variables" Postman Collection:

- `base_url`: `http://localhost:8000/api`
- `token`: Kosongkan (akan diisi setelah login)

## Test Flow / Urutan Testing

### STEP 1: Health Check

```
GET /api/health
```

**Expected Response:**

```json
{
    "status": "ok",
    "timestamp": "2026-01-31T10:30:00Z",
    "service": "Stunting Prevention API"
}
```

---

### STEP 2: Authentication

#### 2.1 Register User

```
POST /api/auth/register
```

**Request Body:**

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Expected Response (201):**

```json
{
    "message": "User registered successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "token": "1|token_string"
}
```

#### 2.2 Login User

```
POST /api/auth/login
```

**Request Body:**

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Expected Response (200):**

```json
{
    "message": "Logged in successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "token": "1|token_string"
}
```

**PENTING:** Simpan token ini dan set di variable `{{token}}` Postman

#### 2.3 Get Current User

```
GET /api/auth/me
Headers: Authorization: Bearer {{token}}
```

**Expected Response (200):**

```json
{
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2026-01-31T10:30:00Z"
    }
}
```

---

### STEP 3: Children Management

#### 3.1 Create Child

```
POST /api/children
Headers: Authorization: Bearer {{token}}
```

**Request Body:**

```json
{
    "name": "Amir",
    "gender": "male",
    "birth_date": "2023-06-15",
    "birth_weight": 3.5,
    "birth_height": 50.2
}
```

**Expected Response (201):**

```json
{
    "message": "Child created successfully",
    "data": {
        "id": 1,
        "user_id": 1,
        "name": "Amir",
        "gender": "male",
        "birth_date": "2023-06-15",
        "birth_weight": "3.50",
        "birth_height": "50.20",
        "age_in_months": 19,
        "age_in_years": 1
    }
}
```

**Simpan child_id (1) untuk langkah berikutnya**

#### 3.2 Get All Children

```
GET /api/children
Headers: Authorization: Bearer {{token}}
```

**Expected Response (200):**

```json
{
    "data": [
        {
            "id": 1,
            "name": "Amir",
            "gender": "male",
            "birth_date": "2023-06-15",
            "age_in_months": 19
        }
    ]
}
```

#### 3.3 Get Child Detail

```
GET /api/children/1
Headers: Authorization: Bearer {{token}}
```

#### 3.4 Update Child

```
PUT /api/children/1
Headers: Authorization: Bearer {{token}}
```

**Request Body:**

```json
{
    "name": "Amir Updated",
    "gender": "male",
    "birth_date": "2023-06-15"
}
```

---

### STEP 4: Growth Records

#### 4.1 Create Growth Record

```
POST /api/children/1/growth
Headers: Authorization: Bearer {{token}}
```

**Request Body:**

```json
{
    "measurement_date": "2026-01-15",
    "age_in_months": 19,
    "weight": 11.5,
    "height": 82.3,
    "head_circumference": 48.2
}
```

**Expected Response (201):**

```json
{
    "message": "Growth record created successfully",
    "data": {
        "id": 1,
        "child_id": 1,
        "measurement_date": "2026-01-15",
        "age_in_months": 19,
        "weight": "11.50",
        "height": "82.30",
        "head_circumference": "48.20",
        "weight_for_age_zscore": "-0.50",
        "height_for_age_zscore": "-1.20",
        "weight_for_height_zscore": "0.30",
        "stunting_status": "at_risk",
        "wasting_status": "normal",
        "underweight_status": "normal",
        "ai_analysis": "Anak menunjukkan tanda-tanda risiko stunting...",
        "recommendations": "Tingkatkan asupan nutrisi..."
    }
}
```

#### 4.2 Get All Growth Records

```
GET /api/children/1/growth
Headers: Authorization: Bearer {{token}}
```

#### 4.3 Get Growth Trend

```
GET /api/children/1/growth/trend
Headers: Authorization: Bearer {{token}}
```

**Expected Response:**

```json
{
    "data": {
        "weight_trend": "increasing",
        "height_trend": "normal",
        "latest_measurements": {
            "weight": "11.50",
            "height": "82.30"
        }
    }
}
```

#### 4.4 Get Growth Record Detail

```
GET /api/children/1/growth/1
Headers: Authorization: Bearer {{token}}
```

---

### STEP 5: Nutrition Records

#### 5.1 Create Nutrition Record (dari database makanan)

```
POST /api/children/1/nutrition
Headers: Authorization: Bearer {{token}}
```

**Request Body:**

```json
{
    "meal_date": "2026-01-15",
    "meal_type": "breakfast",
    "food_name": "Nasi kuning",
    "portion_size": 100
}
```

**Expected Response (201):**

```json
{
    "message": "Nutrition record created successfully",
    "data": {
        "id": 1,
        "child_id": 1,
        "meal_date": "2026-01-15",
        "meal_type": "breakfast",
        "food_name": "Nasi kuning",
        "portion_size": "100.00",
        "calories": "130.00",
        "protein": "2.50",
        "carbohydrates": "28.00",
        "fat": "0.30"
    }
}
```

#### 5.2 Create Custom Nutrition Record

```
POST /api/children/1/nutrition/custom
Headers: Authorization: Bearer {{token}}
```

**Request Body:**

```json
{
    "meal_date": "2026-01-15",
    "meal_type": "lunch",
    "food_name": "Makanan Custom",
    "portion_size": 150,
    "calories": 250,
    "protein": 10,
    "carbohydrates": 35,
    "fat": 5,
    "fiber": 2,
    "calcium": 120,
    "iron": 2,
    "zinc": 1.5,
    "vitamin_a": 500,
    "vitamin_c": 30,
    "vitamin_d": 5,
    "notes": "Makanan pilihan keluarga"
}
```

#### 5.3 Get All Nutrition Records

```
GET /api/children/1/nutrition
Headers: Authorization: Bearer {{token}}
```

#### 5.4 Get Daily Nutrition Summary

```
GET /api/children/1/nutrition/summary?date=2026-01-15
Headers: Authorization: Bearer {{token}}
```

**Expected Response:**

```json
{
    "data": {
        "date": "2026-01-15",
        "total_meals": 2,
        "total_calories": 380,
        "total_protein": 12.5,
        "total_carbohydrates": 63,
        "total_fat": 5.3,
        "meals": [
            {
                "meal_type": "breakfast",
                "calories": 130
            },
            {
                "meal_type": "lunch",
                "calories": 250
            }
        ]
    }
}
```

#### 5.5 Get Nutritional Needs

```
GET /api/children/1/nutrition/needs
Headers: Authorization: Bearer {{token}}
```

**Expected Response:**

```json
{
    "data": {
        "age_in_months": 19,
        "recommended_calories": 1200,
        "recommended_protein": 30,
        "recommended_carbohydrates": 160,
        "recommended_fat": 40,
        "recommended_calcium": 500,
        "recommended_iron": 6,
        "recommended_zinc": 3
    }
}
```

#### 5.6 Get Recommendations

```
GET /api/children/1/nutrition/recommendations
Headers: Authorization: Bearer {{token}}
```

**Expected Response:**

```json
{
    "data": {
        "age_group": "toddler",
        "status": "at_risk",
        "recommendations": [
            "Tingkatkan asupan protein dari daging, telur, dan kacang-kacangan",
            "Berikan makanan bergizi tinggi 3-4 kali sehari"
        ],
        "suggested_foods": ["Daging ayam", "Telur", "Ikan", "Kacang-kacangan"]
    }
}
```

---

### STEP 6: Food Database Search

#### 6.1 Search Foods

```
GET /api/foods/search?q=nasi&limit=10
Headers: Authorization: Bearer {{token}}
```

**Expected Response:**

```json
{
    "data": [
        {
            "id": 1,
            "name": "Nasi putih",
            "calories_per_100g": 130,
            "protein_per_100g": 2.7,
            "carbohydrates_per_100g": 28
        },
        {
            "id": 2,
            "name": "Nasi merah",
            "calories_per_100g": 111,
            "protein_per_100g": 2.6,
            "carbohydrates_per_100g": 24
        },
        {
            "id": 3,
            "name": "Nasi kuning",
            "calories_per_100g": 130,
            "protein_per_100g": 2.5,
            "carbohydrates_per_100g": 28
        }
    ]
}
```

---

### STEP 7: Delete Operations (opsional)

#### 7.1 Delete Growth Record

```
DELETE /api/children/1/growth/1
Headers: Authorization: Bearer {{token}}
```

#### 7.2 Delete Nutrition Record

```
DELETE /api/children/1/nutrition/1
Headers: Authorization: Bearer {{token}}
```

#### 7.3 Delete Child

```
DELETE /api/children/1
Headers: Authorization: Bearer {{token}}
```

---

### STEP 8: Logout

```
POST /api/auth/logout
Headers: Authorization: Bearer {{token}}
```

---

## Checklist Testing

- [ ] Health Check - API responding
- [ ] Register - User dapat didaftar
- [ ] Login - Mendapat token
- [ ] Get Current User - Data user benar
- [ ] Create Child - Child berhasil dibuat
- [ ] Get All Children - List anak muncul
- [ ] Get Child Detail - Detail anak benar
- [ ] Update Child - Anak berhasil diupdate
- [ ] Create Growth Record - Record pertumbuhan dibuat
- [ ] Get All Growth Records - List record muncul
- [ ] Get Growth Trend - Tren pertumbuhan muncul
- [ ] Create Nutrition Record - Record nutrisi dibuat
- [ ] Create Custom Nutrition - Custom nutrition dibuat
- [ ] Get Daily Summary - Summary nutrisi muncul
- [ ] Get Nutritional Needs - Kebutuhan nutrisi muncul
- [ ] Get Recommendations - Rekomendasi muncul
- [ ] Search Foods - Hasil pencarian makanan muncul
- [ ] Delete Operations - Semua delete berfungsi
- [ ] Logout - Logout berhasil

---

## Error Handling & Debugging

### Error 401 - Unauthorized

**Solusi:** Token expired atau tidak valid

- Login ulang
- Set token di variable `{{token}}`

### Error 422 - Unprocessable Entity

**Solusi:** Validasi data gagal

- Check format data
- Pastikan field required terisi

### Error 404 - Not Found

**Solusi:** Resource tidak ditemukan

- Check ID child/record benar
- Pastikan data sudah dibuat terlebih dahulu

### Error 500 - Internal Server Error

**Solusi:** Error di server

- Check laravel logs: `storage/logs/laravel.log`
- Restart server: `php artisan serve`

---

## Tips Testing

1. **Gunakan Environment Variables** - Ganti hardcode ID dengan variables
2. **Pre-request Scripts** - Set token otomatis setelah login
3. **Tests Scripts** - Validasi response otomatis
4. **Dokumentasi API** - Selalu update dokumentasi setelah perubahan
5. **Database Seeding** - Gunakan faker untuk data testing

---

## File-File Testing

1. **Postman-Collection.json** - Full collection untuk Postman
2. **api-tests.json** - JSON testing documentation
3. **JSON-CONTOH-REQUEST.js** - Contoh request bodies
4. **API-TESTING-GUIDE.md** - Panduan ini

---

Selamat testing! 🚀
