<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Child;
use App\Models\GrowthRecord;
use App\Models\NutritionRecord;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create users
        $user1 = User::create([
            'name' => 'Ibu Siti',
            'email' => 'siti@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        $user2 = User::create([
            'name' => 'Ibu Ani',
            'email' => 'ani@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        $user3 = User::create([
            'name' => 'Ibu Dewi',
            'email' => 'dewi@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        // Create children for user1
        $child1 = Child::create([
            'user_id' => $user1->id,
            'name' => 'Amir',
            'gender' => 'male',
            'birth_date' => '2023-06-15',
            'birth_weight' => 3.5,
            'birth_height' => 50.2,
        ]);

        $child2 = Child::create([
            'user_id' => $user1->id,
            'name' => 'Nisa',
            'gender' => 'female',
            'birth_date' => '2024-03-20',
            'birth_weight' => 3.2,
            'birth_height' => 49.5,
        ]);

        // Create children for user2
        $child3 = Child::create([
            'user_id' => $user2->id,
            'name' => 'Rara',
            'gender' => 'female',
            'birth_date' => '2023-09-10',
            'birth_weight' => 3.8,
            'birth_height' => 51.0,
        ]);

        $child4 = Child::create([
            'user_id' => $user2->id,
            'name' => 'Budi',
            'gender' => 'male',
            'birth_date' => '2024-01-05',
            'birth_weight' => 3.6,
            'birth_height' => 50.8,
        ]);

        // Create child for user3
        $child5 = Child::create([
            'user_id' => $user3->id,
            'name' => 'Yuki',
            'gender' => 'female',
            'birth_date' => '2023-12-01',
            'birth_weight' => 3.4,
            'birth_height' => 50.0,
        ]);

        // Create growth records for child1 (Amir) - showing at risk stunting
        GrowthRecord::create([
            'child_id' => $child1->id,
            'measurement_date' => '2025-07-15',
            'age_in_months' => 1,
            'weight' => 3.8,
            'height' => 51.2,
            'head_circumference' => 37.5,
            'weight_for_age_zscore' => -0.1,
            'height_for_age_zscore' => -0.2,
            'weight_for_height_zscore' => 0.1,
            'stunting_status' => 'normal',
            'wasting_status' => 'normal',
            'underweight_status' => 'normal',
            'ai_analysis' => 'Bayi dalam kondisi normal dan berkembang sesuai usia. Pertumbuhan berat dan tinggi badan dalam range normal.',
            'recommendations' => 'Lanjutkan pemberian ASI eksklusif. Pastikan nutrisi ibu tetap terjaga untuk kualitas ASI.',
        ]);

        GrowthRecord::create([
            'child_id' => $child1->id,
            'measurement_date' => '2025-08-15',
            'age_in_months' => 2,
            'weight' => 4.5,
            'height' => 54.3,
            'head_circumference' => 38.8,
            'weight_for_age_zscore' => 0.0,
            'height_for_age_zscore' => -0.1,
            'weight_for_height_zscore' => 0.2,
            'stunting_status' => 'normal',
            'wasting_status' => 'normal',
            'underweight_status' => 'normal',
            'ai_analysis' => 'Pertumbuhan bayi masih dalam pola normal. Kecepatan pertumbuhan tinggi badan mulai terlihat.',
            'recommendations' => 'Pertahankan pemberian ASI eksklusif. Mulai persiapan MPASI pada usia 6 bulan.',
        ]);

        GrowthRecord::create([
            'child_id' => $child1->id,
            'measurement_date' => '2025-12-15',
            'age_in_months' => 6,
            'weight' => 7.2,
            'height' => 65.5,
            'head_circumference' => 42.5,
            'weight_for_age_zscore' => -0.3,
            'height_for_age_zscore' => -0.5,
            'weight_for_height_zscore' => 0.0,
            'stunting_status' => 'at_risk',
            'wasting_status' => 'normal',
            'underweight_status' => 'normal',
            'ai_analysis' => 'Bayi mulai menunjukkan tanda-tanda risiko stunting. Tinggi badan berada di bawah standar untuk usia 6 bulan. Perlu monitoring lebih ketat dan perbaikan asupan nutrisi.',
            'recommendations' => 'Mulai MPASI dengan makanan bernutrisi tinggi. Tambahkan protein hewani (telur, ikan) minimal 3x seminggu. Teruskan pemberian ASI sampai 2 tahun. Kontrol ke posyandu setiap bulan.',
        ]);

        GrowthRecord::create([
            'child_id' => $child1->id,
            'measurement_date' => '2026-01-15',
            'age_in_months' => 7,
            'weight' => 7.8,
            'height' => 66.8,
            'head_circumference' => 43.2,
            'weight_for_age_zscore' => -0.4,
            'height_for_age_zscore' => -0.8,
            'weight_for_height_zscore' => -0.1,
            'stunting_status' => 'at_risk',
            'wasting_status' => 'normal',
            'underweight_status' => 'normal',
            'ai_analysis' => 'Risiko stunting meningkat. Pertumbuhan tinggi badan melambat. Diperlukan intervensi nutrisi yang lebih agresif.',
            'recommendations' => '1. Berikan MPASI 3-4 kali sehari\n2. Setiap makanan harus mengandung protein\n3. Tambah makanan sumber zat besi (daging, hati, kacang)\n4. Berikan buah dan sayur setiap hari\n5. Monitor berat badan setiap minggu\n6. Konsultasi dengan ahli gizi',
        ]);

        // Create growth records for child2 (Nisa) - normal growth
        GrowthRecord::create([
            'child_id' => $child2->id,
            'measurement_date' => '2024-04-20',
            'age_in_months' => 1,
            'weight' => 3.5,
            'height' => 50.8,
            'head_circumference' => 37.2,
            'weight_for_age_zscore' => -0.2,
            'height_for_age_zscore' => -0.3,
            'weight_for_height_zscore' => -0.1,
            'stunting_status' => 'normal',
            'wasting_status' => 'normal',
            'underweight_status' => 'normal',
            'ai_analysis' => 'Bayi perempuan dalam kondisi normal. Pertumbuhan sesuai kurva pertumbuhan WHO.',
            'recommendations' => 'Pemberian ASI eksklusif. Pastikan frekuensi menyusui minimal 8-12 kali per hari.',
        ]);

        GrowthRecord::create([
            'child_id' => $child2->id,
            'measurement_date' => '2024-10-20',
            'age_in_months' => 7,
            'weight' => 8.1,
            'height' => 67.5,
            'head_circumference' => 43.0,
            'weight_for_age_zscore' => 0.1,
            'height_for_age_zscore' => 0.2,
            'weight_for_height_zscore' => 0.3,
            'stunting_status' => 'normal',
            'wasting_status' => 'normal',
            'underweight_status' => 'normal',
            'ai_analysis' => 'Pertumbuhan bayi sangat baik. Berada di atas standar untuk usia 7 bulan.',
            'recommendations' => 'Lanjutkan MPASI yang berkualitas. Teruskan ASI tanpa batas waktu.',
        ]);

        // Create growth records for child3 (Rara) - severely stunted
        GrowthRecord::create([
            'child_id' => $child3->id,
            'measurement_date' => '2023-10-10',
            'age_in_months' => 1,
            'weight' => 4.0,
            'height' => 52.0,
            'head_circumference' => 37.8,
            'weight_for_age_zscore' => 0.1,
            'height_for_age_zscore' => 0.2,
            'weight_for_height_zscore' => 0.0,
            'stunting_status' => 'normal',
            'wasting_status' => 'normal',
            'underweight_status' => 'normal',
            'ai_analysis' => 'Bayi lahir dengan berat dan tinggi normal.',
            'recommendations' => 'Pemberian ASI eksklusif.',
        ]);

        GrowthRecord::create([
            'child_id' => $child3->id,
            'measurement_date' => '2024-04-10',
            'age_in_months' => 7,
            'weight' => 7.9,
            'height' => 66.0,
            'head_circumference' => 42.8,
            'weight_for_age_zscore' => -0.5,
            'height_for_age_zscore' => -0.7,
            'weight_for_height_zscore' => -0.2,
            'stunting_status' => 'at_risk',
            'wasting_status' => 'normal',
            'underweight_status' => 'normal',
            'ai_analysis' => 'Anak menunjukkan tanda risiko stunting. Diperlukan perbaikan nutrisi.',
            'recommendations' => 'Tingkatkan asupan protein. Berikan MPASI berkualitas. Monitoring pertumbuhan setiap bulan.',
        ]);

        GrowthRecord::create([
            'child_id' => $child3->id,
            'measurement_date' => '2025-10-10',
            'age_in_months' => 25,
            'weight' => 11.5,
            'height' => 81.2,
            'head_circumference' => 48.0,
            'weight_for_age_zscore' => -1.0,
            'height_for_age_zscore' => -1.5,
            'weight_for_height_zscore' => -0.5,
            'stunting_status' => 'stunted',
            'wasting_status' => 'normal',
            'underweight_status' => 'underweight',
            'ai_analysis' => 'Anak menunjukkan tanda stunting yang jelas. Tinggi badan signifikan di bawah standar untuk usia 25 bulan. Berat badan juga di bawah rata-rata. Memerlukan intervensi nutrisi dan medis.',
            'recommendations' => '1. KONSULTASI DENGAN DOKTER - Periksa penyakit penyerta\n2. Tingkatkan asupan kalori dan protein\n3. Berikan makanan 4-5x sehari\n4. Setiap makanan harus berkualitas gizi tinggi\n5. Tambah suplemen zat besi dan zinc\n6. Monitoring ketat setiap minggu',
        ]);

        // Create nutrition records for child1 (Amir)
        NutritionRecord::create([
            'child_id' => $child1->id,
            'meal_date' => '2026-01-15',
            'meal_type' => 'breakfast',
            'food_name' => 'Bubur nasi dengan ayam',
            'portion_size' => 150,
            'calories' => 180,
            'protein' => 8.5,
            'carbohydrates' => 25,
            'fat' => 4.5,
            'fiber' => 1.0,
            'calcium' => 60,
            'iron' => 2.0,
            'zinc' => 1.2,
            'vitamin_a' => 150,
            'vitamin_c' => 5,
            'vitamin_d' => 0,
            'notes' => 'Bubur lunak, cocok untuk bayi 7 bulan',
        ]);

        NutritionRecord::create([
            'child_id' => $child1->id,
            'meal_date' => '2026-01-15',
            'meal_type' => 'snack',
            'food_name' => 'Pisang',
            'portion_size' => 50,
            'calories' => 45,
            'protein' => 0.5,
            'carbohydrates' => 11,
            'fat' => 0.3,
            'fiber' => 0.7,
            'calcium' => 5,
            'iron' => 0.2,
            'zinc' => 0.1,
            'vitamin_a' => 30,
            'vitamin_c' => 8,
            'vitamin_d' => 0,
            'notes' => 'Pisang matang, dipotong kecil',
        ]);

        NutritionRecord::create([
            'child_id' => $child1->id,
            'meal_date' => '2026-01-15',
            'meal_type' => 'lunch',
            'food_name' => 'Nasi tim dengan telur dan sayur bayam',
            'portion_size' => 180,
            'calories' => 250,
            'protein' => 12.0,
            'carbohydrates' => 30,
            'fat' => 7.0,
            'fiber' => 1.5,
            'calcium' => 120,
            'iron' => 3.5,
            'zinc' => 1.8,
            'vitamin_a' => 350,
            'vitamin_c' => 12,
            'vitamin_d' => 0,
            'notes' => 'Dikukus sampai lembut, dihaluskan untuk memudahkan menelan',
        ]);

        NutritionRecord::create([
            'child_id' => $child1->id,
            'meal_date' => '2026-01-15',
            'meal_type' => 'dinner',
            'food_name' => 'Bubur ikan dengan wortel',
            'portion_size' => 160,
            'calories' => 160,
            'protein' => 10.5,
            'carbohydrates' => 18,
            'fat' => 4.0,
            'fiber' => 1.0,
            'calcium' => 80,
            'iron' => 2.5,
            'zinc' => 1.5,
            'vitamin_a' => 200,
            'vitamin_c' => 6,
            'vitamin_d' => 0,
            'notes' => 'Ikan segar dipilih, dihilangkan semua duri',
        ]);

        // Create nutrition records for child2 (Nisa)
        NutritionRecord::create([
            'child_id' => $child2->id,
            'meal_date' => '2026-01-15',
            'meal_type' => 'breakfast',
            'food_name' => 'Bubur susu dengan sereal bayi',
            'portion_size' => 200,
            'calories' => 200,
            'protein' => 6.0,
            'carbohydrates' => 32,
            'fat' => 4.0,
            'fiber' => 0.5,
            'calcium' => 200,
            'iron' => 2.0,
            'zinc' => 1.0,
            'vitamin_a' => 100,
            'vitamin_c' => 0,
            'vitamin_d' => 10,
            'notes' => 'Susu formula untuk bayi 10 bulan',
        ]);

        NutritionRecord::create([
            'child_id' => $child2->id,
            'meal_date' => '2026-01-15',
            'meal_type' => 'lunch',
            'food_name' => 'Nasi kuning dengan daging sapi cincang',
            'portion_size' => 200,
            'calories' => 280,
            'protein' => 15.0,
            'carbohydrates' => 32,
            'fat' => 8.0,
            'fiber' => 1.0,
            'calcium' => 80,
            'iron' => 4.0,
            'zinc' => 3.5,
            'vitamin_a' => 50,
            'vitamin_c' => 10,
            'vitamin_d' => 0,
            'notes' => 'Daging sapi berkualitas, dimasak lembut dan dicincang halus',
        ]);

        NutritionRecord::create([
            'child_id' => $child2->id,
            'meal_date' => '2026-01-15',
            'meal_type' => 'snack',
            'food_name' => 'Yogurt plain',
            'portion_size' => 100,
            'calories' => 60,
            'protein' => 3.5,
            'carbohydrates' => 5,
            'fat' => 2.0,
            'fiber' => 0,
            'calcium' => 120,
            'iron' => 0.1,
            'zinc' => 0.5,
            'vitamin_a' => 20,
            'vitamin_c' => 0,
            'vitamin_d' => 1,
            'notes' => 'Yogurt tanpa gula tambahan',
        ]);

        // Create nutrition records for child3 (Rara) - intervention diet
        NutritionRecord::create([
            'child_id' => $child3->id,
            'meal_date' => '2026-01-15',
            'meal_type' => 'breakfast',
            'food_name' => 'Nasi tim dengan tahu',
            'portion_size' => 200,
            'calories' => 220,
            'protein' => 10.0,
            'carbohydrates' => 28,
            'fat' => 6.0,
            'fiber' => 1.0,
            'calcium' => 150,
            'iron' => 2.5,
            'zinc' => 1.2,
            'vitamin_a' => 80,
            'vitamin_c' => 5,
            'vitamin_d' => 0,
            'notes' => 'Tahu lembut yang mudah dicerna untuk anak risiko stunting',
        ]);

        NutritionRecord::create([
            'child_id' => $child3->id,
            'meal_date' => '2026-01-15',
            'meal_type' => 'lunch',
            'food_name' => 'Nasi merah dengan ikan bawal dan sayur sawi',
            'portion_size' => 250,
            'calories' => 350,
            'protein' => 18.0,
            'carbohydrates' => 40,
            'fat' => 9.0,
            'fiber' => 2.0,
            'calcium' => 100,
            'iron' => 4.5,
            'zinc' => 2.5,
            'vitamin_a' => 280,
            'vitamin_c' => 15,
            'vitamin_d' => 0,
            'notes' => 'Makanan untuk intervensi stunting - tinggi protein dan zat besi',
        ]);

        NutritionRecord::create([
            'child_id' => $child3->id,
            'meal_date' => '2026-01-15',
            'meal_type' => 'snack',
            'food_name' => 'Kacang rebus',
            'portion_size' => 80,
            'calories' => 120,
            'protein' => 8.0,
            'carbohydrates' => 10,
            'fat' => 4.5,
            'fiber' => 2.5,
            'calcium' => 50,
            'iron' => 2.0,
            'zinc' => 1.5,
            'vitamin_a' => 0,
            'vitamin_c' => 0,
            'vitamin_d' => 0,
            'notes' => 'Kacang lembut, dipotong agar tidak tersedak',
        ]);

        NutritionRecord::create([
            'child_id' => $child3->id,
            'meal_date' => '2026-01-15',
            'meal_type' => 'dinner',
            'food_name' => 'Bubur daging dengan brokoli',
            'portion_size' => 220,
            'calories' => 260,
            'protein' => 14.0,
            'carbohydrates' => 28,
            'fat' => 7.0,
            'fiber' => 2.0,
            'calcium' => 90,
            'iron' => 3.0,
            'zinc' => 2.0,
            'vitamin_a' => 180,
            'vitamin_c' => 35,
            'vitamin_d' => 0,
            'notes' => 'Daging berkualitas dengan sayuran hijau untuk mendukung pemulihan dari stunting',
        ]);

        $this->command->info('Dummy data seeded successfully!');
    }
}
