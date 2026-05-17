# Script Narasi Presentasi Database (Siap Ucap)

## Slide 1 - Judul
"Selamat pagi/siang Bapak/Ibu. Pada presentasi ini saya mendemokan project database NJedia. Fokusnya adalah pembuktian syarat UTS: data utama minimal 1000 dan data pendukung minimal 20." 

## Slide 2 - Agenda
"Alur presentasi saya: pertama struktur database, kedua bukti jumlah data, ketiga demo query SQL, keempat insight data transaksi, lalu kesimpulan." 

## Slide 3 - Struktur Database
"Di project ini, tabel inti bisnis saya adalah users, products, transaction_headers, transaction_details, dan reviews. Tabel pendukungnya meliputi kategori, promo, shipment, merchant, dan tabel relasi promo. Relasi utama transaksi sudah terhubung lewat foreign key." 

## Slide 4 - Bukti Data Utama
"Ini adalah hasil hitung tabel utama. Semua sudah memenuhi syarat minimal 1000. Users 1249, products 1203, transaction_headers 1003, transaction_details 2035, dan reviews 1000. Jadi syarat pertama terpenuhi." 

## Slide 5 - Bukti Data Pendukung
"Untuk tabel pendukung, semuanya di atas 20 data. Misalnya kategori 25, promo 20, shipment 20, merchant 120, product_promos 300, dan flash_sale_products 50. Jadi syarat kedua juga terpenuhi." 

## Slide 6 - Demo Query SQL
"Di sesi live saya menjalankan query COUNT untuk tabel utama dan pendukung, supaya pembuktian langsung terlihat dari database, bukan dari slide. Setelah itu saya cek beberapa record sample untuk memastikan datanya valid." 

## Slide 7 - Insight Data
"Selain jumlah data, data ini juga bisa dianalisis. Distribusi status transaksi cukup seimbang antara pending, shipping, rejected, dan completed. Saya juga tampilkan top 5 produk berdasarkan kuantitas terjual. Artinya database siap untuk kebutuhan laporan." 

## Slide 8 - Sumber Data
"Sumber data saya menggunakan kombinasi seeder dan import CSV. Seeder dipakai agar data besar bisa konsisten dan reproducible. CSV dipakai untuk simulasi input eksternal atau data manual." 

## Slide 9 - Risiko dan Mitigasi
"Risiko utama biasanya error foreign key saat import, mismatch format CSV, atau salah pilih database aktif. Mitigasinya adalah urutan import parent-child, set format CSV UTF-8, dan cek database aktif sebelum demo." 

## Slide 10 - Penutup
"Kesimpulannya, dua syarat utama UTS sudah terpenuhi: tabel utama sudah minimal 1000 dan tabel pendukung sudah minimal 20. Database juga siap untuk demo query dan analisis. Terima kasih, saya siap untuk pertanyaan." 

---

## Praktik Live yang Disarankan (Urut Cepat)
1. Jalankan query count tabel utama.
2. Jalankan query count tabel pendukung.
3. Tampilkan 5-10 baris sample transaction_details.
4. Jalankan query status transaksi per grup.
5. Jalankan query top produk terjual.

## Query yang Dipakai Saat Praktik
```sql
SELECT 'users' AS table_name, COUNT(*) AS total FROM users
UNION ALL
SELECT 'products', COUNT(*) FROM products
UNION ALL
SELECT 'transaction_headers', COUNT(*) FROM transaction_headers
UNION ALL
SELECT 'transaction_details', COUNT(*) FROM transaction_details
UNION ALL
SELECT 'reviews', COUNT(*) FROM reviews;
```

```sql
SELECT 'product_categories' AS table_name, COUNT(*) AS total FROM product_categories
UNION ALL
SELECT 'promos', COUNT(*) FROM promos
UNION ALL
SELECT 'shipments', COUNT(*) FROM shipments
UNION ALL
SELECT 'merchants', COUNT(*) FROM merchants
UNION ALL
SELECT 'product_promos', COUNT(*) FROM product_promos
UNION ALL
SELECT 'flash_sale_products', COUNT(*) FROM flash_sale_products;
```

```sql
SELECT status, COUNT(*) AS total
FROM transaction_details
GROUP BY status
ORDER BY total DESC;
```

```sql
SELECT p.name, SUM(td.quantity) AS qty_terjual
FROM transaction_details td
JOIN products p ON p.id = td.product_id
GROUP BY p.id, p.name
ORDER BY qty_terjual DESC
LIMIT 5;
```
