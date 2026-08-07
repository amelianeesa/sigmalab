# SIGMA-LAB (Sistem Integrasi Manajemen Laboratorium)
**PT Sucofindo**

SIGMA-LAB adalah sebuah sistem informasi berbasis web yang dirancang khusus untuk mendigitalisasi, mengintegrasikan, dan mengelola seluruh aktivitas operasional laboratorium secara *end-to-end*. Sistem ini mencakup manajemen sumber daya manusia, pengelolaan alat dan bahan, *quality control* (QC) hasil pengujian, pelaporan otomatis, hingga pengawasan (*audit log*) dengan keamanan berlapis berbasis peran (*Role-Based Access Control*).

---

## 🚀 Fitur dan Modul Utama

1. **Modul SDM & Kompetensi**
   - Manajemen profil personil laboratorium.
   - Pelacakan riwayat pelatihan, sertifikasi, dan kompetensi staf.

2. **Modul Alat & Kalibrasi**
   - Inventarisasi alat-alat lab (kode alat, spesifikasi, lokasi).
   - Penjadwalan dan *reminder* otomatis masa jatuh tempo kalibrasi alat.
   - Pencatatan riwayat pemakaian alat pada setiap pengujian.

3. **Modul Inventori & Bahan**
   - Pencatatan stok bahan kimia dan reagen secara *real-time*.
   - Fitur *Auto-Deduction* (Pemotongan otomatis stok bahan saat digunakan untuk pengujian).
   - Sistem *Alert* visual (kuning/merah) ketika stok mendekati batas minimal.

4. **Modul Pengadaan Bahan (Procurement)**
   - Pengajuan permohonan restock bahan yang menipis.
   - Sistem persetujuan (*Approval*) bertingkat oleh Kepala Bidang.
   - Penambahan stok otomatis ketika status pengadaan dinyatakan 'Selesai'.

5. **Modul Quality Control (QC) & Parameter Uji**
   - Manajemen batas inlier/outlier (batas atas & bawah) tiap parameter.
   - **Smart Calculation Engine:** Jika parameter memiliki rumus matematika, sistem otomatis mengekstrak variabelnya menjadi *form input* dan melakukan kalkulasi nilai akhir di *backend*.
   - Penetapan otomatis status Inlier/Outlier pada setiap Hasil Uji.
   - Investigasi dan Tindak Lanjut (*Follow-up*) untuk hasil yang Outlier.

6. **Modul Reporting & Laporan PDF**
   - Visualisasi rasio keberterimaan (Inlier vs Outlier) via Dashboard.
   - Generator dokumen PDF (*Export*) laporan hasil uji secara rapi dengan kop surat berstandar perusahaan.

7. **Modul Audit Log (CCTV Digital)**
   - Merekam setiap riwayat *Create*, *Update*, dan *Delete* pada semua tabel krusial.
   - Fitur X-Ray / Komparasi data yang berubah (menyorot *Data Lama* vs *Data Baru*).

---

## 👥 Sistem Peran (*Role-Based Access Control*)

Sistem ini sangat ketat dalam memisahkan wewenang. Terdapat **7 Role** utama dengan kapabilitas masing-masing:

### 1. Admin Aplikasi (Super Admin)
- **Akses UI:** Sidebar Menu
- **Wewenang:** 
  - Memiliki akses konfigurasi sistem paling tinggi.
  - Dapat mengakses menu SDM & Kompetensi untuk mengatur *user*.
  - Pemegang hak eksklusif (bersama Kabid) untuk melihat **Audit Log** secara sistem.

### 2. Admin Lab
- **Akses UI:** Dashboard Card (Kotak Menu)
- **Wewenang:** 
  - Fokus pada manajemen operasional harian.
  - Memiliki akses penuh (Input/Update) ke **Inventori Bahan**, **Alat & Kalibrasi**, dan pembuatan jadwal **Kegiatan Lab**.

### 3. Koordinator Laboratorium
- **Akses UI:** Dashboard Card (Kotak Menu)
- **Wewenang:**
  - Mengawasi berjalannya seluruh proses lab.
  - Akses penuh ke **Parameter Uji**, pengecekan **Hasil Uji**, serta menentukan dan mengawasi **Tindak Lanjut** atas hasil yang *Outlier*.
  - Dapat melihat **Audit Log** untuk investigasi internal lab.

### 4. Analis
- **Akses UI:** Dashboard Card (Kotak Menu)
- **Wewenang:**
  - Peran eksekutor teknis. 
  - Hanya dapat mengakses **Kegiatan Lab** yang ditugaskan padanya dan melakukan **Input Hasil Uji**.

### 5. HR & GA Office
- **Akses UI:** Dashboard Card (Kotak Menu)
- **Wewenang:**
  - Terfokus murni pada **SDM & Kompetensi** (menambah personil baru, mengurus pembaruan lisensi/sertifikasi agar Analis layak melakukan pengujian).

### 6. Kabid Dukungan Bisnis
- **Akses UI:** Dashboard Card (Kotak Menu)
- **Wewenang:**
  - Pengambil keputusan finansial dan aset.
  - Memiliki akses kunci untuk melakukan **Persetujuan (Approval) Pengadaan Bahan**.
  - Berhak melihat **Laporan QC** dan memantau **Audit Log**.

### 7. Kabid Inspeksi dan Solusi Perdagangan
- **Akses UI:** Dashboard Card (Kotak Menu)
- **Wewenang:**
  - Pengambil keputusan teknis tertinggi.
  - Berhak memantau keseluruhan **Reporting/Laporan QC**.
  - Dapat melihat **Audit Log** untuk memastikan transparansi dan integritas data hasil lab.

---

## 🛠️ Instalasi & Cara Menjalankan (*Developer Guide*)

### Persyaratan Sistem
- PHP >= 8.2
- Composer 2.x
- MySQL >= 8.0 / MariaDB
- Node.js & NPM

### Langkah-Langkah

1. **Clone & Install Dependencies**
   ```bash
   git clone [url-repo]
   cd sigmalab
   composer install
   npm install && npm run build
   ```

2. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Atur koneksi database Anda di dalam file `.env`.*

3. **Migrasi & Seeding Database**
   Sistem ini sangat bergantung pada skema *Role* dan *Hak Akses* awal, jadi pastikan Anda menjalankan seeder.
   ```bash
   php artisan migrate:fresh --seed
   ```
   *(Catatan: Anda dapat mengeksekusi `php set_hak_akses.php` untuk memuat ulang daftar hak akses Audit Log jika diperlukan).*

4. **Storage Link (Untuk upload lampiran/sertifikat)**
   ```bash
   php artisan storage:link
   ```

5. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```
   Akses aplikasi pada `http://localhost:8000`.

---

## 🎨 Teknologi yang Digunakan
- **Framework Core:** Laravel 10 (PHP 8.2)
- **Frontend / UI:** Bootstrap 5, Vanilla JS, FontAwesome, Bootstrap Icons
- **PDF Generator:** `barryvdh/laravel-dompdf`
- **Audit / Rekam Jejak:** `spatie/laravel-activitylog`
- **Smart Math Calculator:** `symfony/expression-language`

*(Dikembangkan dan didesain secara kustom untuk menunjang performa operasional PT Sucofindo).*
