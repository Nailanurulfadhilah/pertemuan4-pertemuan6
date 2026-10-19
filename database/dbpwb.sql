-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2026 at 01:37 PM
-- Server version: 5.6.25
-- PHP Version: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbpwb`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE IF NOT EXISTS `administrator` (
  `id_admin` int(11) NOT NULL,
  `full_name` varchar(40) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`id_admin`, `full_name`, `username`, `password`) VALUES
(2, 'Naila Nurul Fadhilah', 'admin', '202cb962ac59075b964b07152d234b70');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE IF NOT EXISTS `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama`, `deskripsi`) VALUES
(1, 'baju remaja perempuan', 'blouse'),
(3, 'Baju Seragam Sekolah', 'Seragam sd, smp, sma'),
(4, 'Vest', 'Rompi cantik bermotif bunga'),
(5, 'Daster', 'Daster bahan adem');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE IF NOT EXISTS `produk` (
  `id_produk` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text,
  `kategori_id` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `nama`, `deskripsi`, `kategori_id`, `harga`, `stok`) VALUES
(4, 'Baju Perempuan', 'Bagus, indah dan lembut', 1, 100000, 12),
(6, 'Seragam', 'Sssssssss', 1, 150000, 200),
(7, 'Rompi', 'Rompi ssasdfrehafdc', 4, 50000, 200);

-- --------------------------------------------------------

--
-- Table structure for table `produk_gambar`
--

CREATE TABLE IF NOT EXISTS `produk_gambar` (
  `id_gambar` int(11) NOT NULL,
  `nama_gambar` varchar(100) DEFAULT NULL,
  `produk_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `produk_gambar`
--

INSERT INTO `produk_gambar` (`id_gambar`, `nama_gambar`, `produk_id`) VALUES
(1, 'produk-4-0.jpg', 4),
(2, 'produk-6-0.jpg', 6),
(3, 'produk-7-0.jpg', 7),
(6, 'produk-7-02.jpg', 7),
(7, 'produk-7-03.jpg', 7),
(8, 'produk-7-04.jpg', 7),
(9, 'produk-7-05.jpg', 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `produk_gambar`
--
ALTER TABLE `produk_gambar`
  ADD PRIMARY KEY (`id_gambar`),
  ADD KEY `produk_id` (`produk_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrator`
--
ALTER TABLE `administrator`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `produk_gambar`
--
ALTER TABLE `produk_gambar`
  MODIFY `id_gambar` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id_kategori`);

--
-- Constraints for table `produk_gambar`
--
ALTER TABLE `produk_gambar`
  ADD CONSTRAINT `produk_gambar_ibfk_1` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id_produk`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
