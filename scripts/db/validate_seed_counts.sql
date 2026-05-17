SELECT 'users' AS table_name, 'utama' AS table_group, COUNT(*) AS total_rows, IF(COUNT(*) >= 1000, 'OK', 'NOT_OK') AS status FROM users
UNION ALL
SELECT 'products', 'utama', COUNT(*), IF(COUNT(*) >= 1000, 'OK', 'NOT_OK') FROM products
UNION ALL
SELECT 'transaction_headers', 'utama', COUNT(*), IF(COUNT(*) >= 1000, 'OK', 'NOT_OK') FROM transaction_headers
UNION ALL
SELECT 'transaction_details', 'utama', COUNT(*), IF(COUNT(*) >= 1000, 'OK', 'NOT_OK') FROM transaction_details
UNION ALL
SELECT 'reviews', 'utama', COUNT(*), IF(COUNT(*) >= 1000, 'OK', 'NOT_OK') FROM reviews
UNION ALL
SELECT 'product_categories', 'pendukung', COUNT(*), IF(COUNT(*) >= 20, 'OK', 'NOT_OK') FROM product_categories
UNION ALL
SELECT 'promos', 'pendukung', COUNT(*), IF(COUNT(*) >= 20, 'OK', 'NOT_OK') FROM promos
UNION ALL
SELECT 'shipments', 'pendukung', COUNT(*), IF(COUNT(*) >= 20, 'OK', 'NOT_OK') FROM shipments
UNION ALL
SELECT 'merchants', 'pendukung', COUNT(*), IF(COUNT(*) >= 20, 'OK', 'NOT_OK') FROM merchants
UNION ALL
SELECT 'product_promos', 'pendukung', COUNT(*), IF(COUNT(*) >= 20, 'OK', 'NOT_OK') FROM product_promos
UNION ALL
SELECT 'flash_sale_products', 'pendukung', COUNT(*), IF(COUNT(*) >= 20, 'OK', 'NOT_OK') FROM flash_sale_products
ORDER BY table_group, table_name;
