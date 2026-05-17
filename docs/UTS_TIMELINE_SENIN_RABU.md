# Timeline Eksekusi UTS (Senin - Rabu)

## Senin
- Audit dan polish landing page (`/`), termasuk pengecekan desktop + mobile.
- Setup Google Form SUS berdasarkan bank pertanyaan.
- Isi `.env` untuk:
  - `IMK_SUS_FORM_URL`
  - `IMK_SUS_SHEET_URL`
- Jalankan cek environment DB:
  - `.\scripts\db\check_mysql_env.ps1`

## Selasa
- Kumpulkan respons minimal 50 (target 35 real + 15 dummy).
- Jalankan seed + validasi tabel:
  - `.\scripts\db\run_seed_and_validate.ps1`
- Ambil bukti hasil count untuk slide (screenshot output validasi).
- Jika perlu import data CSV/Excel:
  - `.\scripts\db\run_import_excel.ps1`

## Rabu
- Finalisasi visualisasi di Google Sheets.
- Sinkronisasi narasi hasil SUS dengan hasil database.
- Finalisasi slide presentasi IMK + Database.
- Simulasi presentasi 5-10 menit dan siapkan Q&A.
