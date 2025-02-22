-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2024 at 09:41 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk_ahp_saw_native`
--

-- --------------------------------------------------------

--
-- Table structure for table `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `no_hp` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `nama`, `no_hp`, `email`) VALUES
(7, 'Adi Miskadi, S.Pd.I.,M.Pd', '6285523686832', 'adimiskadi@gmail.com'),
(8, 'Apt. Abdul Azis, S.Farm', '6285545321243', 'azizabdul@gmail.com'),
(9, 'Reny Angraeny, S.Pd', '6285545674321', 'renianggereny56@gmail.com'),
(10, 'Surahman, S.Pd', '6283045678909', 'surahmmann12@gmail.com'),
(11, 'Syukrina, S.Si.,apt.', '6281247890798', 'syukrina87@gmail.com'),
(12, 'Widiya Irawati. Amd.Kep.SKM', '6285545322138', 'widhiyairawatu@gmail.'),
(13, 'M. Noor Hidayat, S.Psi', '6281345678765', 'NoorHidayat33@gmail.com'),
(14, 'Abdullah Kafabih, S.H.I', '6281689085432', 'AbdullahKaf12@gmail.com'),
(15, 'Apt. Andri Mugiyono, S.Farm', '6282234786543', 'AndriYoono67@gmail.com'),
(16, 'Apt. Sarah Alkornisa, S.Farm', '6281234986500', 'sarahalkornisa@gmail.com'),
(17, 'Ani Noviani, S.Pd', '6285534532189', 'Novianianii9@gmail.com'),
(19, 'Ratih Dian Amalia, A.Md', '6285523678770', 'dianaratiha@gmail.com'),
(20, 'Anisa Nurhuda Utami, M.Pd', '6285927790836', 'muhamadyogahermanto@gmail.com'),
(21, 'Fitri Wulandari, S.Pd', '6289078654323', 'fitrimpitwulan@gmail.com'),
(22, 'Shiminayu Mulia Lestari, S.Pd', '6281200762321', 'shiminayuulestari@gmail.com'),
(23, 'Jeny Puspitasari, M.Pd', '6285543678977', 'jenyyypuspita3@gmail.com'),
(24, 'Melinda Cassanova, S.Kep', '6285523453214', 'melinda5cassano@gmail.com'),
(25, 'Mikpaudin, S.Pd', '6281245785432', 'mikpaudin12@gmail.com'),
(26, 'Mimbar Aditin, S.Pd', '6281234674321', 'aditinmimbar6@gmail.com'),
(27, 'Nur Hermawati, S.Pd', '6285523124358', 'nurhermawatii@gmail.com'),
(28, 'Sri Rahayu, S.Kep.,Ners', '6285523665770', 'srirahayusri@gmail.com'),
(29, 'Dicky Feri Yana', '6285547808621', 'dickyyyana@gmail.com'),
(30, 'Agil Muhammad Sodikin, S.Or', '6281254678906', 'muhammadagil@gmail.com'),
(32, 'Yulistiya Devianti Mugianti Putri', '6285523678905', 'yulistyaputri@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `hasil`
--

CREATE TABLE `hasil` (
  `id_hasil` int(11) NOT NULL,
  `id_alternatif` int(11) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hasil`
--

INSERT INTO `hasil` (`id_hasil`, `id_alternatif`, `nilai`) VALUES
(1, 7, 0.7275),
(2, 8, 0.6955),
(3, 9, 0.633),
(4, 10, 0.7655),
(5, 11, 0.6955),
(6, 12, 0.6955),
(7, 13, 0.8455),
(8, 14, 0.7555),
(9, 15, 0.6955),
(10, 16, 0.3405),
(11, 17, 0.633),
(12, 19, 0.553),
(13, 20, 1.01),
(14, 21, 0.7655),
(15, 22, 0.7655),
(16, 23, 0.8675),
(17, 24, 0.8355),
(18, 25, 0.8355),
(19, 26, 0.8355),
(20, 27, 0.8355),
(21, 28, 0.7655),
(22, 29, 0.882),
(23, 30, 0.7655),
(24, 32, 0.7395);

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `kode_kriteria` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `type` enum('Benefit','Cost') DEFAULT NULL,
  `ada_pilihan` tinyint(1) DEFAULT NULL,
  `bobot` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `kode_kriteria`, `nama`, `type`, `ada_pilihan`, `bobot`) VALUES
(1, 'C01', 'Kehadiran', 'Benefit', 1, 0.28),
(2, 'C02', 'Kedisplinan', 'Benefit', 1, 0.32),
(3, 'C03', 'Tanggung Jawab', 'Benefit', 1, 0.25),
(4, 'C04', 'Jenjang Pendidikan', 'Benefit', 1, 0.16);

-- --------------------------------------------------------

--
-- Table structure for table `kriteria_ahp`
--

CREATE TABLE `kriteria_ahp` (
  `id_kriteria_ahp` int(11) NOT NULL,
  `id_kriteria_1` int(11) NOT NULL,
  `id_kriteria_2` int(11) NOT NULL,
  `nilai_1` float NOT NULL,
  `nilai_2` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kriteria_ahp`
--

INSERT INTO `kriteria_ahp` (`id_kriteria_ahp`, `id_kriteria_1`, `id_kriteria_2`, `nilai_1`, `nilai_2`) VALUES
(1, 1, 2, 1, 1),
(2, 1, 3, 1, 1),
(3, 1, 4, 2, 0.5),
(4, 2, 3, 1, 1),
(5, 2, 4, 3, 0.33),
(6, 3, 4, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `penilaian`
--

CREATE TABLE `penilaian` (
  `id_penilaian` int(11) NOT NULL,
  `id_alternatif` int(10) NOT NULL,
  `id_kriteria` int(10) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penilaian`
--

INSERT INTO `penilaian` (`id_penilaian`, `id_alternatif`, `id_kriteria`, `nilai`) VALUES
(165, 7, 1, 3),
(166, 7, 2, 7),
(167, 7, 3, 11),
(168, 7, 4, 16),
(173, 8, 1, 3),
(174, 8, 2, 7),
(175, 8, 3, 11),
(176, 8, 4, 17),
(177, 9, 1, 3),
(178, 9, 2, 7),
(179, 9, 3, 12),
(180, 9, 4, 17),
(181, 10, 1, 2),
(182, 10, 2, 7),
(183, 10, 3, 11),
(184, 10, 4, 17),
(185, 11, 1, 3),
(186, 11, 2, 7),
(187, 11, 3, 11),
(188, 11, 4, 17),
(189, 12, 1, 3),
(190, 12, 2, 7),
(191, 12, 3, 11),
(192, 12, 4, 17),
(193, 13, 1, 2),
(194, 13, 2, 6),
(195, 13, 3, 11),
(196, 13, 4, 17),
(197, 14, 1, 1),
(198, 14, 2, 8),
(199, 14, 3, 11),
(200, 14, 4, 17),
(201, 15, 1, 3),
(202, 15, 2, 7),
(203, 15, 3, 11),
(204, 15, 4, 17),
(205, 16, 1, 4),
(206, 16, 2, 9),
(207, 16, 3, 14),
(208, 16, 4, 17),
(209, 17, 1, 3),
(210, 17, 2, 7),
(211, 17, 3, 12),
(212, 17, 4, 17),
(213, 19, 1, 3),
(214, 19, 2, 8),
(215, 19, 3, 12),
(216, 19, 4, 17),
(217, 20, 1, 1),
(218, 20, 2, 6),
(219, 20, 3, 10),
(220, 20, 4, 16),
(221, 21, 1, 2),
(222, 21, 2, 7),
(223, 21, 3, 11),
(224, 21, 4, 17),
(225, 22, 1, 2),
(226, 22, 2, 7),
(227, 22, 3, 11),
(228, 22, 4, 17),
(229, 23, 1, 1),
(230, 23, 2, 7),
(231, 23, 3, 11),
(232, 23, 4, 16),
(233, 24, 1, 1),
(234, 24, 2, 7),
(235, 24, 3, 11),
(236, 24, 4, 17),
(237, 25, 1, 1),
(238, 25, 2, 7),
(239, 25, 3, 11),
(240, 25, 4, 17),
(241, 26, 1, 1),
(242, 26, 2, 7),
(243, 26, 3, 11),
(244, 26, 4, 17),
(245, 27, 1, 1),
(246, 27, 2, 7),
(247, 27, 3, 11),
(248, 27, 4, 17),
(249, 28, 1, 2),
(250, 28, 2, 7),
(251, 28, 3, 11),
(252, 28, 4, 17),
(253, 29, 1, 1),
(254, 29, 2, 6),
(255, 29, 3, 10),
(256, 29, 4, 18),
(257, 30, 1, 2),
(258, 30, 2, 7),
(259, 30, 3, 11),
(260, 30, 4, 17),
(261, 32, 1, 1),
(262, 32, 2, 7),
(263, 32, 3, 11),
(264, 32, 4, 18);

-- --------------------------------------------------------

--
-- Table structure for table `sub_kriteria`
--

CREATE TABLE `sub_kriteria` (
  `id_sub_kriteria` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nilai` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sub_kriteria`
--

INSERT INTO `sub_kriteria` (`id_sub_kriteria`, `id_kriteria`, `nama`, `nilai`) VALUES
(1, 1, 'Kehadiran 90% - 100%', '4'),
(2, 1, 'Kehadiran 80% - 89%', '3'),
(3, 1, 'Kehadiran 70% - 79%', '2'),
(4, 1, 'Kehadiran <70%', '1'),
(6, 2, '<07.00 WIB', '4'),
(7, 2, '07.00 - 07.30 WIB', '3'),
(8, 2, '07.30 - 08.00 WIB', '2'),
(9, 2, '>08.00', '1'),
(10, 3, 'Sangat Tanggung Jawab', '4'),
(11, 3, 'Tanggung Jawab', '3'),
(12, 3, 'Kurang Tanggung Jawab', '2'),
(14, 3, 'Tidak Tanggung Jawab', '1'),
(16, 4, 'S2', '5'),
(17, 4, 'S1/D3', '4'),
(18, 4, 'SLTA', '1');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `email`, `role`) VALUES
(1, 'admin', 'f865b53623b121fd34ee5426c792e5c33af8c227', 'Administrator', 'admin@example.com', 1),
(2, 'user', '95c946bf622ef93b0a211cd0fd028dfdfcf7e39e', 'User', 'user@example.com', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indexes for table `hasil`
--
ALTER TABLE `hasil`
  ADD PRIMARY KEY (`id_hasil`),
  ADD KEY `id_alternatif` (`id_alternatif`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `kriteria_ahp`
--
ALTER TABLE `kriteria_ahp`
  ADD PRIMARY KEY (`id_kriteria_ahp`);

--
-- Indexes for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id_penilaian`);

--
-- Indexes for table `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD PRIMARY KEY (`id_sub_kriteria`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `hasil`
--
ALTER TABLE `hasil`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kriteria_ahp`
--
ALTER TABLE `kriteria_ahp`
  MODIFY `id_kriteria_ahp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id_penilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=265;

--
-- AUTO_INCREMENT for table `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  MODIFY `id_sub_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD CONSTRAINT `sub_kriteria_ibfk_1` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
