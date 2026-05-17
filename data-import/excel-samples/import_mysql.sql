-- Jalankan setelah file CSV disiapkan.
-- Sesuaikan path file dengan lokasi di server MySQL Anda.

LOAD DATA LOCAL INFILE 'D:/NJediah/tokoNJedia/data-import/excel-samples/users.xlsx.csv'
IGNORE INTO TABLE users
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(@user_id, @username, @email, @phone, @dob, @gender, @image)
SET
id = IF(NULLIF(@user_id, '') IS NULL, UUID(), @user_id),
username = @username,
email = @email,
phone = NULLIF(@phone, ''),
dob = NULLIF(@dob, ''),
gender = NULLIF(@gender, ''),
image = NULLIF(@image, ''),
password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
created_at = NOW(),
updated_at = NOW();

LOAD DATA LOCAL INFILE 'D:/NJediah/tokoNJedia/data-import/excel-samples/products.xlsx.csv'
IGNORE INTO TABLE products
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(@product_id, @name, @description, @condition, @merchant_id, @product_category_id)
SET
id = IF(NULLIF(@product_id, '') IS NULL, UUID(), @product_id),
name = @name,
description = @description,
`condition` = @condition,
merchant_id = COALESCE(
    (SELECT id FROM merchants WHERE id = NULLIF(@merchant_id, '') LIMIT 1),
    (SELECT id FROM merchants ORDER BY RAND() LIMIT 1)
),
product_category_id = COALESCE(
    (SELECT id FROM product_categories WHERE id = NULLIF(@product_category_id, '') LIMIT 1),
    (SELECT id FROM product_categories ORDER BY RAND() LIMIT 1)
),
created_at = NOW(),
updated_at = NOW();

LOAD DATA LOCAL INFILE 'D:/NJediah/tokoNJedia/data-import/excel-samples/transaction_headers.xlsx.csv'
IGNORE INTO TABLE transaction_headers
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(@transaction_id, @user_id, @location_id, @date)
SET
id = IF(NULLIF(@transaction_id, '') IS NULL, UUID(), @transaction_id),
user_id = COALESCE(
    (SELECT id FROM users WHERE id = NULLIF(@user_id, '') LIMIT 1),
    (SELECT id FROM users ORDER BY RAND() LIMIT 1)
),
location_id = COALESCE(
    (SELECT id FROM locations WHERE id = NULLIF(@location_id, '') LIMIT 1),
    (SELECT id FROM locations ORDER BY RAND() LIMIT 1)
),
`date` = COALESCE(NULLIF(@date, ''), NOW()),
created_at = NOW(),
updated_at = NOW();

LOAD DATA LOCAL INFILE 'D:/NJediah/tokoNJedia/data-import/excel-samples/transaction_details.xlsx.csv'
IGNORE INTO TABLE transaction_details
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(@transaction_id, @product_id, @variant_id, @quantity, @price, @shipment_id, @status, @promo_name, @discount, @total_paid)
SET
transaction_id = COALESCE(
    (SELECT id FROM transaction_headers WHERE id = NULLIF(@transaction_id, '') LIMIT 1),
    (SELECT id FROM transaction_headers ORDER BY RAND() LIMIT 1)
),
variant_id = COALESCE(
    (SELECT id FROM product_variants WHERE id = NULLIF(@variant_id, '') LIMIT 1),
    (SELECT id FROM product_variants ORDER BY RAND() LIMIT 1)
),
product_id = COALESCE(
    (SELECT id FROM products WHERE id = NULLIF(@product_id, '') LIMIT 1),
    (
        SELECT product_id
        FROM product_variants
        WHERE id = COALESCE(
            (SELECT id FROM product_variants WHERE id = NULLIF(@variant_id, '') LIMIT 1),
            (SELECT id FROM product_variants ORDER BY RAND() LIMIT 1)
        )
        LIMIT 1
    )
),
quantity = IFNULL(NULLIF(@quantity, ''), 1) + 0,
price = COALESCE(
    NULLIF(@price, ''),
    (
        SELECT price
        FROM product_variants
        WHERE id = COALESCE(
            (SELECT id FROM product_variants WHERE id = NULLIF(@variant_id, '') LIMIT 1),
            (SELECT id FROM product_variants ORDER BY RAND() LIMIT 1)
        )
        LIMIT 1
    )
),
shipment_id = COALESCE(
    (SELECT id FROM shipments WHERE id = NULLIF(@shipment_id, '') LIMIT 1),
    (SELECT id FROM shipments ORDER BY RAND() LIMIT 1)
),
status = COALESCE(NULLIF(@status, ''), 'Pending'),
promo_name = NULLIF(@promo_name, ''),
discount = IFNULL(NULLIF(@discount, ''), 0) + 0,
total_paid = COALESCE(
    NULLIF(@total_paid, ''),
    (IFNULL(NULLIF(@quantity, ''), 1) + 0) * COALESCE(
        NULLIF(@price, ''),
        (
            SELECT price
            FROM product_variants
            WHERE id = COALESCE(
                (SELECT id FROM product_variants WHERE id = NULLIF(@variant_id, '') LIMIT 1),
                (SELECT id FROM product_variants ORDER BY RAND() LIMIT 1)
            )
            LIMIT 1
        )
    )
),
created_at = NOW(),
updated_at = NOW();
