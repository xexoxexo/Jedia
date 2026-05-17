# UTS Database Execution Guide

## Target
- Tabel utama minimal 1000 data (misalnya: users, products, transaction headers/details, reviews).
- Tabel pendukung minimal 20 data (misalnya: kategori, promo, shipment, merchant).

## Seeder yang dipakai
- `database/seeders/DatabaseSeeder.php`
- `database/seeders/PromoSeeder.php`
- `database/seeders/ShipmentSeeder.php`

## Validasi Otomatis
- Query validasi: `scripts/db/validate_seed_counts.sql`
- Script eksekusi:
  - `scripts/db/check_mysql_env.ps1`
  - `scripts/db/run_seed_and_validate.ps1`
  - `scripts/db/run_import_excel.ps1`

## Cek Environment MySQL

```powershell
.\scripts\db\check_mysql_env.ps1
```

## Cara Menjalankan Seeding + Validasi (PowerShell)

```powershell
.\scripts\db\run_seed_and_validate.ps1
```

Jika password MySQL tidak kosong:

```powershell
.\scripts\db\run_seed_and_validate.ps1 -DbPassword "your_password"
```

Jika ingin diminta password interaktif:

```powershell
.\scripts\db\run_seed_and_validate.ps1 -PromptPassword
```

## Cara Import Data Excel/CSV ke MySQL (tanpa operator `<`)

```powershell
.\scripts\db\run_import_excel.ps1
```

Jika ingin diminta password interaktif:

```powershell
.\scripts\db\run_import_excel.ps1 -PromptPassword
```

Script memakai:
- executable penuh: `C:\xampp\mysql\bin\mysql.exe`
- perintah MySQL: `-e "source path/file.sql"`

## Struktur Sample Excel/CSV
Contoh ada di:
- `data-import/excel-samples/users.xlsx.csv`
- `data-import/excel-samples/products.xlsx.csv`
- `data-import/excel-samples/transaction_headers.xlsx.csv`
- `data-import/excel-samples/transaction_details.xlsx.csv`
- `data-import/excel-samples/import_mysql.sql`
