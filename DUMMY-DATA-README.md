# Panduan Menjalankan Dummy Data

Saya telah membuat data dummy lengkap berdasarkan struktur tabel database. Data ini mencakup berbagai skenario:

- User dengan berbagai anak
- Anak dengan status pertumbuhan normal
- Anak dengan risiko stunting (at_risk)
- Anak yang stunted (severely_stunted)
- Catatan nutrisi untuk setiap anak

## File yang Dibuat

1. **dummy-data.json** - Data dummy dalam format JSON
2. **DummyDataSeeder.php** - Laravel Seeder untuk import ke database

## Cara Menggunakan

### Opsi 1: Menggunakan Laravel Seeder (RECOMMENDED)

**1. Jalankan Seeder**

```bash
cd /home/raffi-ahmad/Music/api-stuntAware/api
php artisan db:seed --class=DummyDataSeeder
```

**2. Atau Reset Database & Seed Bersama**

```bash
php artisan migrate:fresh --seed
# Lalu edit DatabaseSeeder.php untuk include DummyDataSeeder
```

**3. Edit DatabaseSeeder.php**
Buka file `database/seeders/DatabaseSeeder.php` dan tambahkan:

```php
public function run(): void
{
    // ... existing code ...
    $this->call(DummyDataSeeder::class);
}
```

**4. Verify Data**

```bash
php artisan tinker
User::all();
Child::all();
GrowthRecord::all();
NutritionRecord::all();
```

### Opsi 2: Import Manual dari JSON

Jika ingin membuat data sendiri berdasarkan JSON:

1. Buka `dummy-data.json`
2. Copy data dari section yang diinginkan
3. Sesuaikan dengan kebutuhan
4. Insert manual ke database

---

## Data yang Disertakan

### Users (3 users)

| ID  | Name     | Email            | Notes  |
| --- | -------- | ---------------- | ------ |
| 1   | Ibu Siti | siti@example.com | 2 anak |
| 2   | Ibu Ani  | ani@example.com  | 2 anak |
| 3   | Ibu Dewi | dewi@example.com | 1 anak |

### Children (5 anak)

| ID  | Name | Gender | Birth Date | Parent | Status           |
| --- | ---- | ------ | ---------- | ------ | ---------------- |
| 1   | Amir | Male   | 2023-06-15 | Siti   | At Risk Stunting |
| 2   | Nisa | Female | 2024-03-20 | Siti   | Normal           |
| 3   | Rara | Female | 2023-09-10 | Ani    | Stunted          |
| 4   | Budi | Male   | 2024-01-05 | Ani    | Normal           |
| 5   | Yuki | Female | 2023-12-01 | Dewi   | Normal           |

### Growth Records (9 records)

Setiap anak memiliki minimal 2 catatan pertumbuhan dengan:

- **Amir (Child 1)**: 4 records menunjukkan perkembangan dari normal → at risk stunting
- **Nisa (Child 2)**: 2 records menunjukkan pertumbuhan normal yang baik
- **Rara (Child 3)**: 3 records menunjukkan perkembangan dari normal → at risk → stunted
- **Budi (Child 4)**: Belum ada records (tambahkan sesuai kebutuhan)
- **Yuki (Child 5)**: Belum ada records (tambahkan sesuai kebutuhan)

### Nutrition Records (11 records)

Catatan nutrisi untuk setiap anak pada 2026-01-15:

- **Amir**: 4 meals (breakfast, snack, lunch, dinner) = 635 calories
- **Nisa**: 3 meals (breakfast, lunch, snack) = 540 calories
- **Rara**: 4 meals dengan nutrisi tinggi untuk intervensi stunting = 1190 calories

---

## Z-Score & Status Assessment

### Amir (Child 1) - At Risk Stunting

```
Measurement 2026-01-15 (Age 7 months):
- Weight: 7.8 kg
- Height: 66.8 cm
- Height-for-Age Z-Score: -0.8 (at risk)
- Weight-for-Age Z-Score: -0.4 (normal)
- Status: At Risk Stunting
```

### Nisa (Child 2) - Normal

```
Measurement 2024-10-20 (Age 7 months):
- Weight: 8.1 kg
- Height: 67.5 cm
- Height-for-Age Z-Score: 0.2 (above average)
- Weight-for-Age Z-Score: 0.1 (normal)
- Status: Normal
```

### Rara (Child 3) - Stunted

```
Measurement 2025-10-10 (Age 25 months):
- Weight: 11.5 kg
- Height: 81.2 cm
- Height-for-Age Z-Score: -1.5 (stunted)
- Weight-for-Age Z-Score: -1.0 (underweight)
- Status: Stunted + Underweight
```

---

## Nutrition Data Details

### Breakfast Example (Amir)

```
Food: Bubur nasi dengan ayam
Portion: 150g
Calories: 180
Protein: 8.5g
Iron: 2.0mg
Zinc: 1.2mg
Vitamin A: 150mcg
```

### Full Day Summary (Amir - 2026-01-15)

```
Total Calories: 635 kcal
Total Protein: 31.5g
Total Fat: 15.8g
Total Carbs: 84g
Total Calcium: 265mg
Total Iron: 7.7mg
Total Zinc: 4.5mg
```

---

## Testing Rekomendasi

Setelah seeding, lakukan testing dengan order ini:

1. **Login** dengan salah satu user:

    ```
    Email: siti@example.com
    Password: password123
    ```

2. **Get Children** - akan menampilkan Amir dan Nisa

3. **Get Growth Records** - lihat perkembangan Amir yang at risk

4. **Get Recommendations** - akan memberikan saran intervensi berdasarkan status

5. **Get Nutrition Records** - lihat makanan yang dikonsumsi

---

## Verifikasi Seeding

Setelah seeding, jalankan command berikut untuk memverifikasi:

```bash
# Check total records
php artisan tinker
User::count()           # Should be 3
Child::count()         # Should be 5
GrowthRecord::count()  # Should be 9
NutritionRecord::count() # Should be 11

# Check specific user
$user = User::find(1);
$user->children; # Should show 2 children
$user->children[0]->growthRecords; # Should show 4 records for Amir
```

---

## Cara Modifikasi Data

Jika ingin menambah/mengubah data:

### Tambah Growth Record untuk Child

Edit `DummyDataSeeder.php`:

```php
GrowthRecord::create([
    'child_id' => $child4->id, // Budi
    'measurement_date' => '2026-01-20',
    'age_in_months' => 24,
    'weight' => 13.5,
    'height' => 88.0,
    // ... field lainnya
]);
```

### Tambah Nutrition Record

```php
NutritionRecord::create([
    'child_id' => $child4->id,
    'meal_date' => '2026-01-16',
    'meal_type' => 'breakfast',
    'food_name' => 'Nasi kuning dengan telur',
    // ... field lainnya
]);
```

Kemudian jalankan:

```bash
php artisan migrate:fresh
php artisan db:seed --class=DummyDataSeeder
```

---

## Troubleshooting

### Error: "Class DummyDataSeeder not found"

Solusi: Pastikan file berada di `database/seeders/DummyDataSeeder.php`

### Error: "Foreign key constraint failed"

Solusi: Jalankan migrations terlebih dahulu

```bash
php artisan migrate
php artisan db:seed --class=DummyDataSeeder
```

### Data duplikat

Solusi: Reset database

```bash
php artisan migrate:fresh
php artisan db:seed --class=DummyDataSeeder
```

---

## Info Password

Default password untuk semua user:

```
password123
```

Pastikan untuk menggantinya di production!

---

Selamat! Data dummy sudah siap untuk testing API. 🎉
