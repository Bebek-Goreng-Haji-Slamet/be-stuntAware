# 📊 Ringkasan Data Dummy StuntAware API

## File-File yang Dibuat

### 1. **dummy-data.json**

- Format JSON lengkap
- Semua data dalam satu file
- Cocok untuk dokumentasi dan referensi

### 2. **DummyDataSeeder.php** (RECOMMENDED)

- Laravel Seeder untuk import otomatis ke database
- File: `database/seeders/DummyDataSeeder.php`
- Cara jalankan: `php artisan db:seed --class=DummyDataSeeder`

### 3. **dummy-data.sql**

- SQL INSERT statements langsung
- Alternatif jika tidak ingin pakai seeder
- Cocok untuk MySQL/MariaDB

### 4. **DUMMY-DATA-README.md**

- Dokumentasi lengkap cara menggunakan data dummy
- Panduan testing
- Troubleshooting

---

## 📈 Struktur Data Dummy

### Users (3 users)

```
User 1: Ibu Siti (siti@example.com)
  └─ Child 1: Amir (Male, 19 bulan) - At Risk Stunting
  └─ Child 2: Nisa (Female, 10 bulan) - Normal

User 2: Ibu Ani (ani@example.com)
  └─ Child 3: Rara (Female, 25 bulan) - Stunted
  └─ Child 4: Budi (Male, 24 bulan) - Normal

User 3: Ibu Dewi (dewi@example.com)
  └─ Child 5: Yuki (Female, 13 bulan) - Normal
```

### Growth Records (9 records)

```
Amir (4 records):
  - 2025-07-15: Normal (age 1 month)
  - 2025-08-15: Normal (age 2 months)
  - 2025-12-15: At Risk (age 6 months)
  - 2026-01-15: At Risk (age 7 months) ← Status meningkat

Nisa (2 records):
  - 2024-04-20: Normal (age 1 month)
  - 2024-10-20: Normal (age 7 months) ← Pertumbuhan bagus

Rara (3 records):
  - 2023-10-10: Normal (age 1 month)
  - 2024-04-10: At Risk (age 7 months)
  - 2025-10-10: Stunted (age 25 months) ← Severely stunted
```

### Nutrition Records (11 records)

```
Amir (4 meals - 2026-01-15):
  - Breakfast: Bubur nasi dengan ayam (180 cal)
  - Snack: Pisang (45 cal)
  - Lunch: Nasi tim dengan telur & bayam (250 cal)
  - Dinner: Bubur ikan dengan wortel (160 cal)
  Total: 635 calories

Nisa (3 meals - 2026-01-15):
  - Breakfast: Bubur susu dengan sereal (200 cal)
  - Lunch: Nasi kuning dengan daging sapi (280 cal)
  - Snack: Yogurt plain (60 cal)
  Total: 540 calories

Rara (4 meals - 2026-01-15) - INTERVENTION DIET:
  - Breakfast: Nasi tim dengan tahu (220 cal)
  - Lunch: Nasi merah dengan ikan & sawi (350 cal)
  - Snack: Kacang rebus (120 cal)
  - Dinner: Bubur daging dengan brokoli (260 cal)
  Total: 1190 calories (tinggi protein untuk intervensi)
```

---

## 🚀 Cara Menggunakan (Quick Start)

### Step 1: Jalankan Seeder

```bash
cd /home/raffi-ahmad/Music/api-stuntAware/api
php artisan db:seed --class=DummyDataSeeder
```

### Step 2: Login & Cek Data

```bash
# Jalankan API
php artisan serve

# Di Postman/cURL, login dengan:
POST http://localhost:8000/api/auth/login
{
  "email": "siti@example.com",
  "password": "password123"
}

# Dapatkan token, lalu
GET http://localhost:8000/api/children
# Akan menampilkan Amir dan Nisa
```

### Step 3: Testing

- Lihat perkembangan Amir (at risk stunting) di growth records
- Lihat nutrition records untuk diet intervention Rara
- Check recommendations berdasarkan status setiap anak

---

## 📋 Data Validation

### Z-Score Standards

- **Normal**: Z-score between -1.0 and +2.0
- **At Risk**: Z-score between -1.5 and -1.0
- **Stunted**: Z-score < -2.0
- **Severely Stunted**: Z-score < -3.0

### Contoh Data Amir

```
Age 7 months (2026-01-15):
Height: 66.8 cm
Height-for-Age Z-Score: -0.8
Status: AT RISK (mendekati stunted)
Recommendations: Intervensi nutrisi agresif
```

### Contoh Data Rara

```
Age 25 months (2025-10-10):
Height: 81.2 cm
Height-for-Age Z-Score: -1.5
Weight-for-Age Z-Score: -1.0
Status: STUNTED + UNDERWEIGHT
Recommendations: Konsultasi dokter + diet khusus
```

---

## 🔐 Credentials

Semua user menggunakan password: `password123`

```
User 1:
Email: siti@example.com
Password: password123

User 2:
Email: ani@example.com
Password: password123

User 3:
Email: dewi@example.com
Password: password123
```

**PENTING:** Ganti password di production!

---

## 📊 Database Schema Compliance

Data dummy sudah mengikuti struktur table yang tepat:

### Users Table

```
id, name, email, email_verified_at, password, created_at, updated_at
```

### Children Table

```
id, user_id, name, gender, birth_date, birth_weight, birth_height,
created_at, updated_at, deleted_at
```

### Growth Records Table

```
id, child_id, measurement_date, age_in_months, weight, height,
head_circumference, weight_for_age_zscore, height_for_age_zscore,
weight_for_height_zscore, stunting_status, wasting_status,
underweight_status, ai_analysis, recommendations, created_at, updated_at
```

### Nutrition Records Table

```
id, child_id, meal_date, meal_type, food_name, portion_size,
calories, protein, carbohydrates, fat, fiber, calcium, iron, zinc,
vitamin_a, vitamin_c, vitamin_d, notes, created_at, updated_at
```

---

## ✅ Testing Checklist

Setelah seeding, lakukan testing berikut:

- [ ] Query ulang dari database (php artisan tinker)
- [ ] Login dengan user credentials
- [ ] Get children list
- [ ] View growth records progression
- [ ] Check nutrition records
- [ ] Test recommendations API
- [ ] Verify Z-scores calculation
- [ ] Check stunting status assessment

---

## 🔄 Reset Data

Jika ingin reset dan seed ulang:

```bash
# Option 1: Migrate fresh + seed
php artisan migrate:fresh --seed

# Option 2: Hanya seed
php artisan migrate:rollback
php artisan migrate
php artisan db:seed --class=DummyDataSeeder

# Option 3: Delete dari database
php artisan tinker
User::truncate();
Child::truncate();
GrowthRecord::truncate();
NutritionRecord::truncate();
```

---

## 📝 Catatan Penting

1. **Password Hash**: Seeder menggunakan `bcrypt()` untuk hashing password
2. **Foreign Keys**: Pastikan foreign key constraints aktif
3. **Soft Deletes**: Child model menggunakan soft deletes (deleted_at field)
4. **Timestamps**: Semua record sudah memiliki created_at dan updated_at
5. **Data Realistis**: Nutrisi dan pertumbuhan berdasarkan standar WHO

---

## 🎯 Use Cases

### Testing Growth Analysis

Gunakan data Amir yang menunjukkan progres dari normal ke at-risk:

- Lihat bagaimana system mendeteksi trend negatif
- Check recommendations berubah seiring waktu

### Testing Intervention Diet

Gunakan data Rara yang stunted:

- Lihat nutrition records dengan kalori tinggi
- Check recommendations untuk recovery

### Testing Normal Growth

Gunakan data Nisa yang normal:

- Verify system tidak memberikan alert yang salah
- Check normal recommendations

---

## 📞 Support

Untuk pertanyaan atau issue:

1. Check DUMMY-DATA-README.md untuk troubleshooting
2. Verify migrations sudah dijalankan
3. Check foreign key constraints
4. Verify password hashing

---

**Status**: ✅ Data dummy siap untuk testing
**Last Updated**: 31 Januari 2026
**Version**: 1.0
