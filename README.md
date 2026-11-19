<<<<<<< HEAD
# SetaraBantu 🤝
**Sistem Informasi Penyaluran Bantuan Sosial Inklusif Berbasis Skor Prioritas**

SetaraBantu adalah aplikasi berbasis web yang dirancang untuk mendata, memverifikasi, dan menyalurkan bantuan sosial secara tepat sasaran dan merata. Proyek ini merupakan tugas Responsi 2 Praktikum Pemrograman Web I.

## 🌎 Latar Belakang dan Tujuan (SDGs)
Proses penyaluran bantuan sosial di masyarakat sering kali mengalami kendala berupa pendataan yang kurang akurat dan distribusi yang tidak merata. Terkadang, kelompok yang paling membutuhkan (seperti lansia, difabel, atau perempuan kepala keluarga) justru tertinggal atau terlambat mendapatkan haknya.

**Tujuan Proyek:**
Aplikasi ini dikembangkan untuk menjadi solusi terpadu manajemen bantuan sosial yang mengadopsi prinsip Sustainable Development Goals (SDGs):
* **SDG 1 (Tanpa Kemiskinan):** Memfasilitasi proses pendataan warga prasejahtera dan penyaluran bantuan (sembako, tunai, beasiswa) untuk meringankan beban ekonomi keluarga miskin.
* **SDG 10 (Berkurangnya Kesenjangan):** Menerapkan algoritma Skor Prioritas berdasarkan pembobotan kategori rentan, jumlah tanggungan, dan tingkat penghasilan. Hal ini memastikan kelompok yang sering tertinggal mendapatkan prioritas dan pemerataan yang berkeadilan.

## ⚙️ Daftar Fitur / 9 Modul Implementasi
Aplikasi ini memiliki 4 hak akses (Admin, Petugas, Warga, Donatur) dengan 9 modul yang saling terintegrasi:

1. **Autentikasi:** Sistem Register, Login, dan Logout menggunakan Session, Cookie (Remember Me), dan Hash Password (Bcrypt).
2. **User & Role:** Modul CRUD kelola data pengguna dan pengaturan hak akses.
3. **Data Penerima:** Modul bagi warga untuk melengkapi profil (alamat, penghasilan, jumlah tanggungan).
4. **Kategori Kelompok Rentan:** Penetapan bobot kerentanan (Difabel, Lansia, Kepala Keluarga Perempuan, dll).
5. **Program Bantuan:** Modul CRUD jenis bantuan (Sembako, Tunai, Alat Bantu, Beasiswa).
6. **Pengajuan Bantuan:** Formulir pengajuan bantuan oleh warga.
7. **Verifikasi & Prioritas:** Proses persetujuan oleh petugas sekaligus kalkulasi otomatis Skor Prioritas berdasarkan parameter SDG 10.
8. **Penyaluran:** Tabel antrean penyaluran yang otomatis diurutkan (ORDER BY) dari skor prioritas tertinggi ke terendah, beserta pelacakan status terima.
9. **Dashboard & Laporan:** Halaman ringkasan statistik (total program, total pengajuan, pengajuan disetujui, dan distribusi per kelompok rentan).

## 💻 Teknologi yang Digunakan
* **Bahasa Pemrograman:** PHP (Native), HTML5, JavaScript
* **Database:** MySQL / MariaDB
* **Desain Antarmuka (UI):** CSS 3, Bootstrap 5 (Framework CSS)
* **Web Server Lokal:** XAMPP (Apache)

## 🛠️ Panduan Instalasi dan Konfigurasi
Untuk menjalankan proyek ini di local machine, ikuti langkah-langkah berikut:
1. Unduh atau Clone repository ini.
2. Ekstrak folder proyek dan ubah nama foldernya menjadi `SetaraBantu`.
3. Pindahkan folder `SetaraBantu` ke dalam direktori web server lokal Anda (Untuk Windows/XAMPP: letakkan di `C:\xampp\htdocs\`).
4. Buka XAMPP Control Panel dan jalankan modul **Apache** dan **MySQL**.
5. Buka phpMyAdmin di browser, buat database baru, lalu import file `.sql` yang tersedia.
=======
# SetaraBantu 🤝
**Sistem Informasi Penyaluran Bantuan Sosial Inklusif Berbasis Skor Prioritas**

SetaraBantu adalah aplikasi berbasis web yang dirancang untuk mendata, memverifikasi, dan menyalurkan bantuan sosial secara tepat sasaran dan merata. Proyek ini merupakan tugas Responsi 2 Praktikum Pemrograman Web I.

## 🌎 Latar Belakang dan Tujuan (SDGs)
Proses penyaluran bantuan sosial di masyarakat sering kali mengalami kendala berupa pendataan yang kurang akurat dan distribusi yang tidak merata. Terkadang, kelompok yang paling membutuhkan (seperti lansia, difabel, atau perempuan kepala keluarga) justru tertinggal atau terlambat mendapatkan haknya.

**Tujuan Proyek:**
Aplikasi ini dikembangkan untuk menjadi solusi terpadu manajemen bantuan sosial yang mengadopsi prinsip Sustainable Development Goals (SDGs):
* **SDG 1 (Tanpa Kemiskinan):** Memfasilitasi proses pendataan warga prasejahtera dan penyaluran bantuan (sembako, tunai, beasiswa) untuk meringankan beban ekonomi keluarga miskin.
* **SDG 10 (Berkurangnya Kesenjangan):** Menerapkan algoritma Skor Prioritas berdasarkan pembobotan kategori rentan, jumlah tanggungan, dan tingkat penghasilan. Hal ini memastikan kelompok yang sering tertinggal mendapatkan prioritas dan pemerataan yang berkeadilan.

## ⚙️ Daftar Fitur / 9 Modul Implementasi
Aplikasi ini memiliki 4 hak akses (Admin, Petugas, Warga, Donatur) dengan 9 modul yang saling terintegrasi:

1. **Autentikasi:** Sistem Register, Login, dan Logout menggunakan Session, Cookie (Remember Me), dan Hash Password (Bcrypt).
2. **User & Role:** Modul CRUD kelola data pengguna dan pengaturan hak akses.
3. **Data Penerima:** Modul bagi warga untuk melengkapi profil (alamat, penghasilan, jumlah tanggungan).
4. **Kategori Kelompok Rentan:** Penetapan bobot kerentanan (Difabel, Lansia, Kepala Keluarga Perempuan, dll).
5. **Program Bantuan:** Modul CRUD jenis bantuan (Sembako, Tunai, Alat Bantu, Beasiswa).
6. **Pengajuan Bantuan:** Formulir pengajuan bantuan oleh warga.
7. **Verifikasi & Prioritas:** Proses persetujuan oleh petugas sekaligus kalkulasi otomatis Skor Prioritas berdasarkan parameter SDG 10.
8. **Penyaluran:** Tabel antrean penyaluran yang otomatis diurutkan (ORDER BY) dari skor prioritas tertinggi ke terendah, beserta pelacakan status terima.
9. **Dashboard & Laporan:** Halaman ringkasan statistik (total program, total pengajuan, pengajuan disetujui, dan distribusi per kelompok rentan).

## 💻 Teknologi yang Digunakan
* **Bahasa Pemrograman:** PHP (Native), HTML5, JavaScript
* **Database:** MySQL / MariaDB
* **Desain Antarmuka (UI):** CSS 3, Bootstrap 5 (Framework CSS)
* **Web Server Lokal:** XAMPP (Apache)

## 🛠️ Panduan Instalasi dan Konfigurasi
Untuk menjalankan proyek ini di local machine, ikuti langkah-langkah berikut:
1. Unduh atau Clone repository ini.
2. Ekstrak folder proyek dan ubah nama foldernya menjadi `SetaraBantu`.
3. Pindahkan folder `SetaraBantu` ke dalam direktori web server lokal Anda (Untuk Windows/XAMPP: letakkan di `C:\xampp\htdocs\`).
4. Buka XAMPP Control Panel dan jalankan modul **Apache** dan **MySQL**.
5. Buka phpMyAdmin di browser, buat database baru, lalu import file `.sql` yang tersedia.
>>>>>>> 6fdaa30ab8cb39ee22bb8a97db164372eb7ef925
