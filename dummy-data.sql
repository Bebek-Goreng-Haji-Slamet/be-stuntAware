-- ============================================================
-- DUMMY DATA SQL INSERT STATEMENTS
-- StuntAware API Database
-- ============================================================

-- ============================================================
-- 1. INSERT USERS
-- ============================================================
INSERT INTO users (name, email, email_verified_at, password, created_at, updated_at) VALUES
('Ibu Siti', 'siti@example.com', '2026-01-15 10:00:00', '$2y$12$abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', '2026-01-15 10:00:00', '2026-01-15 10:00:00'),
('Ibu Ani', 'ani@example.com', '2026-01-16 10:00:00', '$2y$12$abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', '2026-01-16 10:00:00', '2026-01-16 10:00:00'),
('Ibu Dewi', 'dewi@example.com', '2026-01-17 10:00:00', '$2y$12$abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', '2026-01-17 10:00:00', '2026-01-17 10:00:00');

-- ============================================================
-- 2. INSERT CHILDREN
-- ============================================================
INSERT INTO children (user_id, name, gender, birth_date, birth_weight, birth_height, created_at, updated_at, deleted_at) VALUES
(1, 'Amir', 'male', '2023-06-15', 3.50, 50.20, '2026-01-15 11:00:00', '2026-01-15 11:00:00', NULL),
(1, 'Nisa', 'female', '2024-03-20', 3.20, 49.50, '2026-01-15 11:30:00', '2026-01-15 11:30:00', NULL),
(2, 'Rara', 'female', '2023-09-10', 3.80, 51.00, '2026-01-16 11:00:00', '2026-01-16 11:00:00', NULL),
(2, 'Budi', 'male', '2024-01-05', 3.60, 50.80, '2026-01-16 11:30:00', '2026-01-16 11:30:00', NULL),
(3, 'Yuki', 'female', '2023-12-01', 3.40, 50.00, '2026-01-17 11:00:00', '2026-01-17 11:00:00', NULL);

-- ============================================================
-- 3. INSERT GROWTH RECORDS
-- ============================================================

-- Amir (Child 1) - Normal to At Risk Stunting progression
INSERT INTO growth_records (child_id, measurement_date, age_in_months, weight, height, head_circumference, weight_for_age_zscore, height_for_age_zscore, weight_for_height_zscore, stunting_status, wasting_status, underweight_status, ai_analysis, recommendations, created_at, updated_at) VALUES
(1, '2025-07-15', 1, 3.80, 51.20, 37.50, -0.10, -0.20, 0.10, 'normal', 'normal', 'normal', 'Bayi dalam kondisi normal dan berkembang sesuai usia. Pertumbuhan berat dan tinggi badan dalam range normal.', 'Lanjutkan pemberian ASI eksklusif. Pastikan nutrisi ibu tetap terjaga untuk kualitas ASI.', '2025-07-15 10:00:00', '2025-07-15 10:00:00'),
(1, '2025-08-15', 2, 4.50, 54.30, 38.80, 0.00, -0.10, 0.20, 'normal', 'normal', 'normal', 'Pertumbuhan bayi masih dalam pola normal. Kecepatan pertumbuhan tinggi badan mulai terlihat.', 'Pertahankan pemberian ASI eksklusif. Mulai persiapan MPASI pada usia 6 bulan.', '2025-08-15 10:00:00', '2025-08-15 10:00:00'),
(1, '2025-12-15', 6, 7.20, 65.50, 42.50, -0.30, -0.50, 0.00, 'at_risk', 'normal', 'normal', 'Bayi mulai menunjukkan tanda-tanda risiko stunting. Tinggi badan berada di bawah standar untuk usia 6 bulan. Perlu monitoring lebih ketat dan perbaikan asupan nutrisi.', 'Mulai MPASI dengan makanan bernutrisi tinggi. Tambahkan protein hewani (telur, ikan) minimal 3x seminggu. Teruskan pemberian ASI sampai 2 tahun. Kontrol ke posyandu setiap bulan.', '2025-12-15 10:00:00', '2025-12-15 10:00:00'),
(1, '2026-01-15', 7, 7.80, 66.80, 43.20, -0.40, -0.80, -0.10, 'at_risk', 'normal', 'normal', 'Risiko stunting meningkat. Pertumbuhan tinggi badan melambat. Diperlukan intervensi nutrisi yang lebih agresif.', '1. Berikan MPASI 3-4 kali sehari\n2. Setiap makanan harus mengandung protein\n3. Tambah makanan sumber zat besi (daging, hati, kacang)\n4. Berikan buah dan sayur setiap hari\n5. Monitor berat badan setiap minggu\n6. Konsultasi dengan ahli gizi', '2026-01-15 10:00:00', '2026-01-15 10:00:00');

-- Nisa (Child 2) - Normal growth
INSERT INTO growth_records (child_id, measurement_date, age_in_months, weight, height, head_circumference, weight_for_age_zscore, height_for_age_zscore, weight_for_height_zscore, stunting_status, wasting_status, underweight_status, ai_analysis, recommendations, created_at, updated_at) VALUES
(2, '2024-04-20', 1, 3.50, 50.80, 37.20, -0.20, -0.30, -0.10, 'normal', 'normal', 'normal', 'Bayi perempuan dalam kondisi normal. Pertumbuhan sesuai kurva pertumbuhan WHO.', 'Pemberian ASI eksklusif. Pastikan frekuensi menyusui minimal 8-12 kali per hari.', '2024-04-20 10:00:00', '2024-04-20 10:00:00'),
(2, '2024-10-20', 7, 8.10, 67.50, 43.00, 0.10, 0.20, 0.30, 'normal', 'normal', 'normal', 'Pertumbuhan bayi sangat baik. Berada di atas standar untuk usia 7 bulan.', 'Lanjutkan MPASI yang berkualitas. Teruskan ASI tanpa batas waktu.', '2024-10-20 10:00:00', '2024-10-20 10:00:00');

-- Rara (Child 3) - Normal to Stunted progression
INSERT INTO growth_records (child_id, measurement_date, age_in_months, weight, height, head_circumference, weight_for_age_zscore, height_for_age_zscore, weight_for_height_zscore, stunting_status, wasting_status, underweight_status, ai_analysis, recommendations, created_at, updated_at) VALUES
(3, '2023-10-10', 1, 4.00, 52.00, 37.80, 0.10, 0.20, 0.00, 'normal', 'normal', 'normal', 'Bayi lahir dengan berat dan tinggi normal.', 'Pemberian ASI eksklusif.', '2023-10-10 10:00:00', '2023-10-10 10:00:00'),
(3, '2024-04-10', 7, 7.90, 66.00, 42.80, -0.50, -0.70, -0.20, 'at_risk', 'normal', 'normal', 'Anak menunjukkan tanda risiko stunting. Diperlukan perbaikan nutrisi.', 'Tingkatkan asupan protein. Berikan MPASI berkualitas. Monitoring pertumbuhan setiap bulan.', '2024-04-10 10:00:00', '2024-04-10 10:00:00'),
(3, '2025-10-10', 25, 11.50, 81.20, 48.00, -1.00, -1.50, -0.50, 'stunted', 'normal', 'underweight', 'Anak menunjukkan tanda stunting yang jelas. Tinggi badan signifikan di bawah standar untuk usia 25 bulan. Berat badan juga di bawah rata-rata. Memerlukan intervensi nutrisi dan medis.', '1. KONSULTASI DENGAN DOKTER - Periksa penyakit penyerta\n2. Tingkatkan asupan kalori dan protein\n3. Berikan makanan 4-5x sehari\n4. Setiap makanan harus berkualitas gizi tinggi\n5. Tambah suplemen zat besi dan zinc\n6. Monitoring ketat setiap minggu', '2025-10-10 10:00:00', '2025-10-10 10:00:00');

-- ============================================================
-- 4. INSERT NUTRITION RECORDS
-- ============================================================

-- Amir (Child 1) - 2026-01-15
INSERT INTO nutrition_records (child_id, meal_date, meal_type, food_name, portion_size, calories, protein, carbohydrates, fat, fiber, calcium, iron, zinc, vitamin_a, vitamin_c, vitamin_d, notes, created_at, updated_at) VALUES
(1, '2026-01-15', 'breakfast', 'Bubur nasi dengan ayam', 150, 180, 8.50, 25, 4.50, 1.00, 60, 2.00, 1.20, 150, 5, 0, 'Bubur lunak, cocok untuk bayi 7 bulan', '2026-01-15 08:00:00', '2026-01-15 08:00:00'),
(1, '2026-01-15', 'snack', 'Pisang', 50, 45, 0.50, 11, 0.30, 0.70, 5, 0.20, 0.10, 30, 8, 0, 'Pisang matang, dipotong kecil', '2026-01-15 10:30:00', '2026-01-15 10:30:00'),
(1, '2026-01-15', 'lunch', 'Nasi tim dengan telur dan sayur bayam', 180, 250, 12.00, 30, 7.00, 1.50, 120, 3.50, 1.80, 350, 12, 0, 'Dikukus sampai lembut, dihaluskan untuk memudahkan menelan', '2026-01-15 12:00:00', '2026-01-15 12:00:00'),
(1, '2026-01-15', 'dinner', 'Bubur ikan dengan wortel', 160, 160, 10.50, 18, 4.00, 1.00, 80, 2.50, 1.50, 200, 6, 0, 'Ikan segar dipilih, dihilangkan semua duri', '2026-01-15 18:00:00', '2026-01-15 18:00:00');

-- Nisa (Child 2) - 2026-01-15
INSERT INTO nutrition_records (child_id, meal_date, meal_type, food_name, portion_size, calories, protein, carbohydrates, fat, fiber, calcium, iron, zinc, vitamin_a, vitamin_c, vitamin_d, notes, created_at, updated_at) VALUES
(2, '2026-01-15', 'breakfast', 'Bubur susu dengan sereal bayi', 200, 200, 6.00, 32, 4.00, 0.50, 200, 2.00, 1.00, 100, 0, 10, 'Susu formula untuk bayi 10 bulan', '2026-01-15 08:00:00', '2026-01-15 08:00:00'),
(2, '2026-01-15', 'lunch', 'Nasi kuning dengan daging sapi cincang', 200, 280, 15.00, 32, 8.00, 1.00, 80, 4.00, 3.50, 50, 10, 0, 'Daging sapi berkualitas, dimasak lembut dan dicincang halus', '2026-01-15 12:00:00', '2026-01-15 12:00:00'),
(2, '2026-01-15', 'snack', 'Yogurt plain', 100, 60, 3.50, 5, 2.00, 0, 120, 0.10, 0.50, 20, 0, 1, 'Yogurt tanpa gula tambahan', '2026-01-15 15:00:00', '2026-01-15 15:00:00');

-- Rara (Child 3) - 2026-01-15 (Intervention diet)
INSERT INTO nutrition_records (child_id, meal_date, meal_type, food_name, portion_size, calories, protein, carbohydrates, fat, fiber, calcium, iron, zinc, vitamin_a, vitamin_c, vitamin_d, notes, created_at, updated_at) VALUES
(3, '2026-01-15', 'breakfast', 'Nasi tim dengan tahu', 200, 220, 10.00, 28, 6.00, 1.00, 150, 2.50, 1.20, 80, 5, 0, 'Tahu lembut yang mudah dicerna untuk anak risiko stunting', '2026-01-15 08:00:00', '2026-01-15 08:00:00'),
(3, '2026-01-15', 'lunch', 'Nasi merah dengan ikan bawal dan sayur sawi', 250, 350, 18.00, 40, 9.00, 2.00, 100, 4.50, 2.50, 280, 15, 0, 'Makanan untuk intervensi stunting - tinggi protein dan zat besi', '2026-01-15 12:00:00', '2026-01-15 12:00:00'),
(3, '2026-01-15', 'snack', 'Kacang rebus', 80, 120, 8.00, 10, 4.50, 2.50, 50, 2.00, 1.50, 0, 0, 0, 'Kacang lembut, dipotong agar tidak tersedak', '2026-01-15 15:00:00', '2026-01-15 15:00:00'),
(3, '2026-01-15', 'dinner', 'Bubur daging dengan brokoli', 220, 260, 14.00, 28, 7.00, 2.00, 90, 3.00, 2.00, 180, 35, 0, 'Daging berkualitas dengan sayuran hijau untuk mendukung pemulihan dari stunting', '2026-01-15 18:00:00', '2026-01-15 18:00:00');

-- ============================================================
-- VERIFY DATA
-- ============================================================
-- Setelah insert, jalankan query berikut untuk verifikasi:

-- SELECT COUNT(*) as total_users FROM users;
-- SELECT COUNT(*) as total_children FROM children;
-- SELECT COUNT(*) as total_growth_records FROM growth_records;
-- SELECT COUNT(*) as total_nutrition_records FROM nutrition_records;

-- -- Check user dengan children count
-- SELECT u.id, u.name, COUNT(c.id) as children_count
-- FROM users u
-- LEFT JOIN children c ON u.id = c.user_id
-- GROUP BY u.id, u.name;

-- -- Check children dengan growth records count
-- SELECT c.id, c.name, COUNT(gr.id) as growth_records_count
-- FROM children c
-- LEFT JOIN growth_records gr ON c.id = gr.child_id
-- GROUP BY c.id, c.name;

-- ============================================================
-- NOTES
-- ============================================================
-- 1. Password hash adalah contoh, ganti dengan bcrypt hash yang sesuai
-- 2. Jika ingin set password tertentu, gunakan Laravel seeder dengan bcrypt()
-- 3. Foreign key constraints harus diaktifkan
-- 4. Pastikan migrations sudah dijalankan sebelum insert SQL ini
