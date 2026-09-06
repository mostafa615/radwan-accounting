-- ============================================================
-- تصحيح الأرصدة المتأثرة بالتأكيد المزدوج في سستم المصنع
-- عدد الحالات: 35
-- لكل حالة: الناتج اتزود مرتين، والخامة اتخصمت مرتين
-- التصحيح: نرجع الزيادة الزايدة، ونرد الخامة اللي اتخصمت غلط
-- ** راجع الأرقام دي مع الجرد الفعلي قبل التشغيل **
-- ============================================================

START TRANSACTION;

-- 2022-11-13 | مخزن 4 | م خ الواح ستانلس 304 مط 0.5مم 1.25*2.90
--   الناتج: اتزود 2 والمفروض 1
UPDATE quantities SET quantity = quantity - 1
 WHERE item_id = 1345 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 887): اتخصمت 30 والمفروض 15
UPDATE quantities SET quantity = quantity + 15
 WHERE item_id = 887 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2023-01-10 | مخزن 4 | الواح ستانلس  كروم مسنفر 1مم 1.25*2.50
--   الناتج: اتزود 150 والمفروض 75
UPDATE quantities SET quantity = quantity - 75
 WHERE item_id = 245 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 218): اتخصمت 150 والمفروض 75
UPDATE quantities SET quantity = quantity + 75
 WHERE item_id = 218 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2023-02-06 | مخزن 4 | الواح ستانلس  كروم مسنفر 1.5مم 1*2
--   الناتج: اتزود 126 والمفروض 63
UPDATE quantities SET quantity = quantity - 63
 WHERE item_id = 250 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 223): اتخصمت 126 والمفروض 63
UPDATE quantities SET quantity = quantity + 63
 WHERE item_id = 223 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2023-04-12 | مخزن 4 | الواح ستانلس 304 مط 1.2مم 1.25*2.50
--   الناتج: اتزود 120 والمفروض 60
UPDATE quantities SET quantity = quantity - 60
 WHERE item_id = 62 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 896): اتخصمت 120 والمفروض 60
UPDATE quantities SET quantity = quantity + 60
 WHERE item_id = 896 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2023-04-15 | مخزن 4 | الواح ستانلس 304 مسنفر 1مم 1.25*2.50
--   الناتج: اتزود 160 والمفروض 80
UPDATE quantities SET quantity = quantity - 80
 WHERE item_id = 134 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 59): اتخصمت 160 والمفروض 80
UPDATE quantities SET quantity = quantity + 80
 WHERE item_id = 59 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2023-08-06 | مخزن 4 | الواح ستانلس 201 لميع 0.4مم 1.22*2.50
--   الناتج: اتزود 360 والمفروض 180
UPDATE quantities SET quantity = quantity - 180
 WHERE item_id = 294 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 972): اتخصمت 3034 والمفروض 1517
UPDATE quantities SET quantity = quantity + 1517
 WHERE item_id = 972 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2023-09-09 | مخزن 4 | الواح ستانلس 304 لميع 0.6مم 1.25*2.50
--   الناتج: اتزود 200 والمفروض 100
UPDATE quantities SET quantity = quantity - 100
 WHERE item_id = 160 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 907): اتخصمت 2728 والمفروض 1364
UPDATE quantities SET quantity = quantity + 1364
 WHERE item_id = 907 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2024-06-26 | مخزن 4 | بكر ستانلس 304 مسنفر 0.8مم *1.25
--   الناتج: اتزود 13262 والمفروض 6631
UPDATE quantities SET quantity = quantity - 6631
 WHERE item_id = 1198 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 902): اتخصمت 13066 والمفروض 6533
UPDATE quantities SET quantity = quantity + 6533
 WHERE item_id = 902 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2024-09-26 | مخزن 4 | الواح ستانلس 304 مط 1مم 1.25*2.50
--   الناتج: اتزود 120 والمفروض 60
UPDATE quantities SET quantity = quantity - 60
 WHERE item_id = 59 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 893): اتخصمت 2944 والمفروض 1472
UPDATE quantities SET quantity = quantity + 1472
 WHERE item_id = 893 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2024-10-30 | مخزن 4 | الواح ستانلس  كروم لميع 1مم 1*2
--   الناتج: اتزود 120 والمفروض 60
UPDATE quantities SET quantity = quantity - 60
 WHERE item_id = 217 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 1080): اتخصمت 1786 والمفروض 893
UPDATE quantities SET quantity = quantity + 893
 WHERE item_id = 1080 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2024-11-06 | مخزن 4 | الواح ستانلس 304 مسنفر 2مم 1*2
--   الناتج: اتزود 2 والمفروض 1
UPDATE quantities SET quantity = quantity - 1
 WHERE item_id = 142 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 67): اتخصمت 2 والمفروض 1
UPDATE quantities SET quantity = quantity + 1
 WHERE item_id = 67 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2025-02-03 | مخزن 4 | الواح ستانلس كروم لميع 1.2مم 1.25*2.50
--   الناتج: اتزود 80 والمفروض 40
UPDATE quantities SET quantity = quantity - 40
 WHERE item_id = 221 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 1088): اتخصمت 2300 والمفروض 1150
UPDATE quantities SET quantity = quantity + 1150
 WHERE item_id = 1088 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2025-02-23 | مخزن 4 | الواح ستانلس كروم لميع 0.7مم 1.25*2.50
--   الناتج: اتزود 100 والمفروض 50
UPDATE quantities SET quantity = quantity - 50
 WHERE item_id = 212 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 1085): اتخصمت 1638 والمفروض 819
UPDATE quantities SET quantity = quantity + 819
 WHERE item_id = 1085 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2025-02-27 | مخزن 4 | الواح ستانلس 304 لميع 1مم 1.25*2.50
--   الناتج: اتزود 160 والمفروض 80
UPDATE quantities SET quantity = quantity - 80
 WHERE item_id = 169 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 913): اتخصمت 3930 والمفروض 1965
UPDATE quantities SET quantity = quantity + 1965
 WHERE item_id = 913 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2025-04-27 | مخزن 4 | الواح ستانلس كروم لميع 0.8مم 1.25*2.50
--   الناتج: اتزود 240 والمفروض 120
UPDATE quantities SET quantity = quantity - 120
 WHERE item_id = 215 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 1086): اتخصمت 4586 والمفروض 2293
UPDATE quantities SET quantity = quantity + 2293
 WHERE item_id = 1086 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2025-05-07 | مخزن 4 | الواح ستانلس 304 مسنفر 3مم 1.50*3
--   الناتج: اتزود 2 والمفروض 1
UPDATE quantities SET quantity = quantity - 1
 WHERE item_id = 760 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 75): اتخصمت 2 والمفروض 1
UPDATE quantities SET quantity = quantity + 1
 WHERE item_id = 75 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2025-05-07 | مخزن 4 | الواح ستانلس 304 مسنفر 3مم 1.50*3
--   الناتج: اتزود 2 والمفروض 1
UPDATE quantities SET quantity = quantity - 1
 WHERE item_id = 760 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 75): اتخصمت 2 والمفروض 1
UPDATE quantities SET quantity = quantity + 1
 WHERE item_id = 75 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2025-05-29 | مخزن 4 | بكر ستانلس 304 مسنفر 1مم*1.5
--   الناتج: اتزود 6062 والمفروض 3031
UPDATE quantities SET quantity = quantity - 3031
 WHERE item_id = 1343 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 894): اتخصمت 5968 والمفروض 2984
UPDATE quantities SET quantity = quantity + 2984
 WHERE item_id = 894 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2025-06-03 | مخزن 4 | الواح ستانلس 201 مط 1.5مم 1.22*2.50
--   الناتج: اتزود 32 والمفروض 16
UPDATE quantities SET quantity = quantity - 16
 WHERE item_id = 1028 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 1012): اتخصمت 1151.5 والمفروض 575.75
UPDATE quantities SET quantity = quantity + 575.75
 WHERE item_id = 1012 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2025-06-28 | مخزن 4 | الواح ستانلس 201 مسنفر 0.5مم 1.22*2.50
--   الناتج: اتزود 4 والمفروض 2
UPDATE quantities SET quantity = quantity - 2
 WHERE item_id = 320 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 1033): اتخصمت 4 والمفروض 2
UPDATE quantities SET quantity = quantity + 2
 WHERE item_id = 1033 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2025-08-23 | مخزن 4 | م خ الواح ستانلس 304 مط 1.2مم 1.25*1.33
--   الناتج: اتزود 2 والمفروض 1
UPDATE quantities SET quantity = quantity - 1
 WHERE item_id = 3789 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 896): اتخصمت 30 والمفروض 15
UPDATE quantities SET quantity = quantity + 15
 WHERE item_id = 896 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2025-09-08 | مخزن 4 | م خ  الواح ستانلس 201 مرايا اسود 0.5مم 1.22*3
--   الناتج: اتزود 24 والمفروض 12
UPDATE quantities SET quantity = quantity - 12
 WHERE item_id = 3145 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 2455): اتخصمت 340 والمفروض 170
UPDATE quantities SET quantity = quantity + 170
 WHERE item_id = 2455 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2025-09-08 | مخزن 4 | مرايا 201 اسود 0.5مم 1.22*2.50
--   الناتج: اتزود 234 والمفروض 117
UPDATE quantities SET quantity = quantity - 117
 WHERE item_id = 933 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 2455): اتخصمت 2825 والمفروض 1412.5
UPDATE quantities SET quantity = quantity + 1412.5
 WHERE item_id = 2455 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2025-10-26 | مخزن 4 | بكر ستانلس 201 مسنفر  2 مم*1.5
--   الناتج: اتزود 4040 والمفروض 2020
UPDATE quantities SET quantity = quantity - 2020
 WHERE item_id = 3955 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 3935): اتخصمت 4000 والمفروض 2000
UPDATE quantities SET quantity = quantity + 2000
 WHERE item_id = 3935 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2026-05-13 | مخزن 4 | م خ الواح ستانلس 304 لميع 0.6مم 1*0.70
--   الناتج: اتزود 44 والمفروض 22
UPDATE quantities SET quantity = quantity - 22
 WHERE item_id = 2764 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 906): اتخصمت 150 والمفروض 75
UPDATE quantities SET quantity = quantity + 75
 WHERE item_id = 906 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2026-06-06 | مخزن 4 | مرايا 201 اسود 0.6مم 1.22*2.50
--   الناتج: اتزود 100 والمفروض 50
UPDATE quantities SET quantity = quantity - 50
 WHERE item_id = 439 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 3610): اتخصمت 1420 والمفروض 710
UPDATE quantities SET quantity = quantity + 710
 WHERE item_id = 3610 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2026-06-25 | مخزن 4 | بكر ستانلس 304 مسنفر 1مم*1.5
--   الناتج: اتزود 7118 والمفروض 3559
UPDATE quantities SET quantity = quantity - 3559
 WHERE item_id = 1343 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 894): اتخصمت 6942 والمفروض 3471
UPDATE quantities SET quantity = quantity + 3471
 WHERE item_id = 894 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2026-06-30 | مخزن 4 | الواح ستانلس  كروم لميع 1مم 1*2
--   الناتج: اتزود 200 والمفروض 100
UPDATE quantities SET quantity = quantity - 100
 WHERE item_id = 217 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 1080): اتخصمت 3176 والمفروض 1588
UPDATE quantities SET quantity = quantity + 1588
 WHERE item_id = 1080 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2026-07-01 | مخزن 4 | الواح ستانلس كروم مسنفر 1مم 1*2
--   الناتج: اتزود 100 والمفروض 50
UPDATE quantities SET quantity = quantity - 50
 WHERE item_id = 244 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 217): اتخصمت 100 والمفروض 50
UPDATE quantities SET quantity = quantity + 50
 WHERE item_id = 217 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2026-07-01 | مخزن 4 | الواح ستانلس  كروم لميع 0.6مم 1*2
--   الناتج: اتزود 300 والمفروض 150
UPDATE quantities SET quantity = quantity - 150
 WHERE item_id = 208 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 1077): اتخصمت 2866 والمفروض 1433
UPDATE quantities SET quantity = quantity + 1433
 WHERE item_id = 1077 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2026-07-07 | مخزن 4 | الواح ستانلس 304 مسنفر 1.5مم 1.5*3
--   الناتج: اتزود 2 والمفروض 1
UPDATE quantities SET quantity = quantity - 1
 WHERE item_id = 141 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 190): اتخصمت 2 والمفروض 1
UPDATE quantities SET quantity = quantity + 1
 WHERE item_id = 190 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2026-08-30 | مخزن 4 | الواح ستانلس 304 مسنفر 1.5مم 1*2
--   الناتج: اتزود 6 والمفروض 3
UPDATE quantities SET quantity = quantity - 3
 WHERE item_id = 139 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 64): اتخصمت 6 والمفروض 3
UPDATE quantities SET quantity = quantity + 3
 WHERE item_id = 64 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2026-08-30 | مخزن 4 | الواح ستانلس 304 لميع 0.8مم 1.50*3
--   الناتج: اتزود 30 والمفروض 15
UPDATE quantities SET quantity = quantity - 15
 WHERE item_id = 187 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 1239): اتخصمت 847 والمفروض 423.5
UPDATE quantities SET quantity = quantity + 423.5
 WHERE item_id = 1239 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2026-08-31 | مخزن 4 | الواح ستانلس  مرايا 201 دهبي 0.7مم 1.5*3
--   الناتج: اتزود 14 والمفروض 7
UPDATE quantities SET quantity = quantity - 7
 WHERE item_id = 5932 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 3966): اتخصمت 14 والمفروض 7
UPDATE quantities SET quantity = quantity + 7
 WHERE item_id = 3966 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- 2026-09-03 | مخزن 4 | الواح ستانلس 201 مسنفر 0.4مم 1.22*2.50
--   الناتج: اتزود 8 والمفروض 4
UPDATE quantities SET quantity = quantity - 4
 WHERE item_id = 318 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';
--   الخامة (صنف 383): اتخصمت 8 والمفروض 4
UPDATE quantities SET quantity = quantity + 4
 WHERE item_id = 383 AND ownerable_id = 4 AND ownerable_type = 'App\Models\Store';

-- COMMIT;   <-- شيل التعليق بعد المراجعة
-- ROLLBACK;
