-- Adminer 4.8.0 MySQL 8.0.23 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;

SET NAMES utf8mb4;

CREATE DATABASE `alfurqon` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `alfurqon`;

CREATE TABLE `label_pembelian` (
  `id` int NOT NULL AUTO_INCREMENT,
  `label` text COLLATE utf8mb4_general_ci NOT NULL,
  `total_transaksi` int DEFAULT NULL,
  `cash` int DEFAULT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `master_data` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_barang` text COLLATE utf8mb4_general_ci NOT NULL,
  `kode_barang` text COLLATE utf8mb4_general_ci NOT NULL,
  `stock_barang` int NOT NULL,
  `harga_barang` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `ms_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `status` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `pembelian` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_label` int NOT NULL,
  `nama_barang` text COLLATE utf8mb4_general_ci NOT NULL,
  `harga_barang` int NOT NULL,
  `quantity` int NOT NULL,
  `total` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_label` (`id_label`),
  CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`id_label`) REFERENCES `label_pembelian` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='status';


-- 2021-06-12 02:11:11
