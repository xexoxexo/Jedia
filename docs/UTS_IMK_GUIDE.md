# UTS IMK Execution Guide

## Tujuan
- Landing page siap demo end-to-end (desktop + mobile).
- Kuesioner SUS aktif.
- Data responden minimal 50 (target default: 35 real + 15 dummy berlabel).
- Visualisasi hasil siap presentasi minggu depan.

## Halaman yang sudah disiapkan di project
- `/` (Landing/Home)
- `/imk/sus` (Checklist IMK + bank pertanyaan + progress data)
- `/imk/visualization` (Ringkasan skor dan distribusi)

## Setup Link Google Form & Google Sheets
Tambahkan variabel berikut di `.env`:

```env
IMK_SUS_FORM_URL=https://forms.gle/xxxxxxxx
IMK_SUS_SHEET_URL=https://docs.google.com/spreadsheets/d/xxxxxxxx
```

## Data Sampel
- Bank pertanyaan: `database/data/imk/sus_question_bank.md`
- Sampel 50 respons: `database/data/imk/sus_responses_sample.csv`
  - `source_label=real` untuk 35 data
  - `source_label=dummy` untuk 15 data

## Formula SUS
- Pertanyaan ganjil: `jawaban - 1`
- Pertanyaan genap: `5 - jawaban`
- Total kontribusi x `2.5` = skor SUS (0-100)

## Checklist Presentasi
- Tampilkan landing page + alur fitur inti.
- Tampilkan Google Form SUS (10 pertanyaan + profil tambahan).
- Tampilkan hasil visualisasi di Google Sheets.
- Jelaskan skor SUS rata-rata dan interpretasi grade.
