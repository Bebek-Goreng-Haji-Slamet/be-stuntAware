# 📊 Data Visualization - Pertumbuhan dan Nutrisi

## Growth Progression Chart

### Amir (Child 1) - Progressing to At Risk Stunting

```
HEIGHT (cm) vs AGE (months)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Normal Line (WHO Standard):
Age 1m: ~54cm
Age 2m: ~56cm
Age 6m: ~66cm
Age 7m: ~68cm

Amir's Growth Line:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Age 1m:  51.2cm ┤                     ✓ (Normal)
              │
Age 2m:  54.3cm ┤              ✓ (Normal)
              │
Age 6m:  65.5cm ┤         ✗ (Below standard)
              │
Age 7m:  66.8cm ┤     ✗ (Further below - Worsening trend!)
              │
              └─────────────────────────────────────

Status Progression:
  Age 1m  → NORMAL           ✓
  Age 2m  → NORMAL           ✓
  Age 6m  → AT RISK          ⚠
  Age 7m  → AT RISK (worse)  ⚠⚠

Z-Score Trend:
  Age 1m:  -0.2  (Normal)
  Age 2m:  -0.1  (Normal)
  Age 6m:  -0.5  (At risk)
  Age 7m:  -0.8  (At risk - critical!)
```

### Nisa (Child 2) - Normal Healthy Growth

```
HEIGHT (cm) vs AGE (months)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Age 1m:  50.8cm ┤              ✓ (Normal)
              │
Age 7m:  67.5cm ┤                        ✓ (Above normal!)
              │
              └─────────────────────────────────────

Status Progression:
  Age 1m  → NORMAL           ✓
  Age 7m  → NORMAL (excellent) ✓✓

Z-Score: 0.2 (Above average)
Growth Velocity: Excellent
```

### Rara (Child 3) - Progressive Stunting

```
HEIGHT (cm) vs AGE (months)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Normal Line (WHO):
Age 1m:  ~54cm
Age 7m:  ~66cm
Age 25m: ~88cm

Rara's Line:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Age 1m:   52.0cm ┤              ✓ (Normal)
               │
Age 7m:   66.0cm ┤         ⚠ (At risk)
               │
Age 25m:  81.2cm ┤ ✗✗ (SEVERELY STUNTED - 6.8cm below!)
               │
               └─────────────────────────────────────

Status Progression:
  Age 1m  → NORMAL          ✓
  Age 7m  → AT RISK         ⚠
  Age 25m → SEVERELY STUNTED ✗✗

Z-Score Trend:
  Age 1m:   +0.2  (Normal)
  Age 7m:   -0.7  (At risk)
  Age 25m:  -1.5  (STUNTED - critical intervention needed!)
```

---

## Weight Progression

### Amir Weight Trend

```
WEIGHT (kg)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Recommended:  7.2kg (Age 6m)
            8.0kg (Age 7m)

Amir Actual:  7.2kg (Age 6m) ✓ Sesuai
            7.8kg (Age 7m) ✓ Sesuai (tapi relative kecil untuk tingginya)

Conclusion: Berat cukup, tapi HEIGHT tertinggal = STUNTING RISK
```

### Rara Weight Trend

```
WEIGHT (kg)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Recommended:  12kg (Age 25m)

Rara Actual:  11.5kg ✗ (0.5kg kurang)

Z-Score: -1.0 = UNDERWEIGHT + STUNTED (Double malnutrition!)
```

---

## Nutrition Daily Summary

### Amir Daily Intake (2026-01-15)

```
NUTRITIONAL INTAKE COMPARISON
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Calorie Intake:   635 kcal
Recommended:     1200 kcal (Age 7m)
Status:          ⚠ INSUFFICIENT (53% dari kebutuhan)

Protein:         31.5g
Recommended:     30g
Status:          ✓ Adequate

Iron:            7.7mg
Recommended:     6mg
Status:          ✓ Good

Zinc:            4.5mg
Recommended:     3mg
Status:          ✓ Good

═══════════════════════════════════════════════════
MEALS BREAKDOWN:
─────────────────────────────────────────────────
Breakfast:  Bubur nasi ayam     │ 180 cal  │ 8.5g protein
Snack:      Pisang              │ 45 cal   │ 0.5g protein
Lunch:      Nasi tim telur      │ 250 cal  │ 12g protein
Dinner:     Bubur ikan wortel   │ 160 cal  │ 10.5g protein
═══════════════════════════════════════════════════

ASSESSMENT: Kalori kurang, perlu tambah porsi makanan!
            Protein OK, zat besi OK, tapi CALORIES jadi masalah.
```

### Rara Daily Intake (2026-01-15) - INTERVENTION DIET

```
NUTRITIONAL INTAKE COMPARISON (INTERVENTION)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Calorie Intake:   1190 kcal
Recommended:      1300 kcal (Age 25m)
Status:           ✓ Adequate (92%)

Protein:          50g
Recommended:      20g
Status:           ✓✓ EXCELLENT (250% dari kebutuhan!)

Iron:             11.0mg
Recommended:      6.5mg
Status:           ✓✓ EXCELLENT (170%)

Zinc:             7.2mg
Recommended:      3.5mg
Status:           ✓✓ EXCELLENT (205%)

Vitamin A:        740mcg
Recommended:      400mcg
Status:           ✓✓ EXCELLENT

═══════════════════════════════════════════════════
MEALS BREAKDOWN (High-Protein Diet):
─────────────────────────────────────────────────
Breakfast:  Nasi tim tahu       │ 220 cal  │ 10g protein
Lunch:      Nasi merah ikan     │ 350 cal  │ 18g protein
Snack:      Kacang rebus        │ 120 cal  │ 8g protein
Dinner:     Bubur daging        │ 260 cal  │ 14g protein
═══════════════════════════════════════════════════

ASSESSMENT: ✓ Diet intervensi dengan nutrisi tinggi TEPAT!
            Kalori dan protein optimal untuk pemulihan stunting.
            Makanan dipilih dengan zinc dan iron tinggi.
```

---

## Status Comparison Matrix

```
┌─────────────────────────────────────────────────────────────────┐
│ CHILD  │ AGE │ HEIGHT │ Z-SCORE │ STATUS      │ INTERVENTION   │
├─────────────────────────────────────────────────────────────────┤
│ Amir   │ 7m  │ 66.8cm │  -0.8   │ AT RISK     │ ⚠ Mulai serius │
│ Nisa   │ 7m  │ 67.5cm │  +0.2   │ NORMAL      │ ✓ Continue ASI │
│ Rara   │25m  │ 81.2cm │  -1.5   │ STUNTED     │ ✗✗ DR + DIET   │
│ Budi   │24m  │ ?      │  ?      │ NO DATA     │ Need records   │
│ Yuki   │13m  │ ?      │  ?      │ NO DATA     │ Need records   │
└─────────────────────────────────────────────────────────────────┘
```

---

## Nutritional Needs by Age

```
AGE GROUP: 7 MONTHS (Amir & Nisa)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Calories:     1000-1200 kcal/day
Protein:      10-15g/day
Fat:          30-35g/day
Iron:         6mg/day
Zinc:         2-3mg/day
Calcium:      500mg/day

Status:
  Amir:  ⚠ Calorie deficient - perlu MPASI berkualitas
  Nisa:  ✓ Dalam range normal


AGE GROUP: 25 MONTHS (Rara)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Calories:     1200-1300 kcal/day
Protein:      15-20g/day
Fat:          35-40g/day
Iron:         6.5mg/day
Zinc:         3-3.5mg/day
Calcium:      600mg/day

Status:
  Rara:  ✓ Diet intervensi sudah optimal
```

---

## Key Insights

### 🚨 Red Flags Detected

1. **Amir (7 months)**
    - Z-Score dropping (-0.8) - WORSENING trend
    - Calorie intake insufficient
    - Action: Intensive nutrition counseling needed

2. **Rara (25 months)**
    - Severely stunted (-1.5 Z-score)
    - DOUBLE malnutrition (stunted + underweight)
    - Action: Doctor consultation + specialized diet

### ✅ Positive Findings

1. **Nisa (7 months)**
    - Normal growth trajectory
    - Z-Score above average (+0.2)
    - Action: Maintain current feeding

2. **Intervention Diet for Rara**
    - Properly designed high-protein diet
    - Adequate micronutrients (Fe, Zn, Vit A)
    - Calorie dense meals

---

## Recommendations Algorithm

```
IF stunting_status = 'normal' AND Z_score > 0:
  → MAINTAIN current practices
  → Continue ASI, introduce MPASI gradually

ELSE IF stunting_status = 'at_risk' AND Z_score between -0.8 and -1:
  → INCREASE MPASI frequency (3-4x daily)
  → HIGH PROTEIN foods (telur, daging, ikan)
  → Monitor weekly
  → Nutritionist consultation

ELSE IF stunting_status = 'stunted' AND Z_score < -2:
  → URGENT: Doctor consultation
  → Therapeutic diet
  → Micronutrient supplementation
  → Monitor bi-weekly
  → Rule out medical causes

END IF
```

---

Generated: January 31, 2026
Data Version: 1.0
Status: Ready for Testing ✅
