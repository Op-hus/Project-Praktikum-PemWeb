-- phpMyAdmin SQL Dump
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','petugas','warga','donatur') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`) VALUES
(1, 'Admin Utama', 'admin@bantu.com', md5('admin123'), 'admin'),
(2, 'Petugas Verifikasi', 'petugas@bantu.com', md5('petugas123'), 'petugas'),
(3, 'Bapak Budi (Warga)', 'budi@bantu.com', md5('budi123'), 'warga');

CREATE TABLE `kategori_rentan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `bobot_skor` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `kategori_rentan` (`nama_kategori`, `bobot_skor`) VALUES
('Difabel', 30), ('Lansia', 25), ('Kepala Keluarga Perempuan', 20), ('Anak Yatim/Piatu', 15), ('Keluarga Biasa (Prasejahtera)', 10);

CREATE TABLE `penerima` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `alamat` text NOT NULL,
  `penghasilan` decimal(10,2) NOT NULL,
  `jumlah_tanggungan` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`kategori_id`) REFERENCES `kategori_rentan` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `program_bantuan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_program` varchar(150) NOT NULL,
  `jenis` enum('sembako','tunai','alat bantu','beasiswa') NOT NULL,
  `deskripsi` text NOT NULL,
  `kuota` int(11) NOT NULL,
  `status` enum('aktif','selesai') DEFAULT 'aktif',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `program_bantuan` (`id`, `nama_program`, `jenis`, `deskripsi`, `kuota`, `status`) VALUES
(1, 'Bantuan Sembako Beras 10Kg', 'sembako', 'Bantuan pokok bulanan', 100, 'aktif'),
(2, 'BLT Tunai Rp 300.000', 'tunai', 'Bantuan Langsung Tunai', 50, 'aktif');

CREATE TABLE `pengajuan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `penerima_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `alasan` text NOT NULL,
  `status` enum('diajukan','disetujui','ditolak') DEFAULT 'diajukan',
  `tanggal` date NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`penerima_id`) REFERENCES `penerima` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`program_id`) REFERENCES `program_bantuan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `verifikasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pengajuan_id` int(11) NOT NULL,
  `petugas_id` int(11) NOT NULL,
  `skor_prioritas` int(11) NOT NULL,
  `catatan` text,
  `tanggal` date NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuan` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`petugas_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `penyaluran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pengajuan_id` int(11) NOT NULL,
  `jadwal` date NOT NULL,
  `status` enum('belum','sudah diterima') DEFAULT 'belum',
  `tanggal_diterima` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
