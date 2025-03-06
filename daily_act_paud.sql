-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2025 at 12:56 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `daily_act_paud`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `c_admin` varchar(10) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`c_admin`, `nama`, `username`, `password`) VALUES
('adm1', 'Administrator', 'admin', '$2y$10$6E7OAZyYzscaduzr/VcCAurT9Oin3CvlJFocxaIUyOZ0UgXQ1Bzji');

-- --------------------------------------------------------

--
-- Table structure for table `akses_otm`
--

CREATE TABLE `akses_otm` (
  `id` int(11) NOT NULL,
  `nis_siswa` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `no_hp` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `akses_otm`
--

INSERT INTO `akses_otm` (`id`, `nis_siswa`, `password`, `no_hp`) VALUES
(1, '202201001', '8iZGz', NULL),
(2, '202201002', 'tVvl5', NULL),
(3, '202201005', 'fDhuy', NULL),
(4, '202201007', '8cpHD', NULL),
(5, '202201008', 'P7euG', NULL),
(6, '202201010', 'yL9LH', NULL),
(7, '202201011', 'k0dcK', NULL),
(8, '202201012', 'qVkr4', NULL),
(9, '202201013', 'uDg2Q', NULL),
(10, '202201014', 'cKRN5', NULL),
(11, '202201017', 'zdtOu', NULL),
(12, '202201018', 'MICwN', NULL),
(13, '202201020', '2yKGZ', NULL),
(14, '202201021', 'tmUPe', NULL),
(15, '202201022', 'xFyvl', NULL),
(16, '202201024', 'wiM3a', NULL),
(17, '202201025', 'jJxVe', NULL),
(18, '202201026', 'pWnyz', NULL),
(19, '202201027', 'MYasq', NULL),
(20, '202201029', 'xvN3D', NULL),
(21, '202201030', 'QLAiE', NULL),
(22, '202201031', 'VH6Sc', NULL),
(23, '202201032', 'p6ke9', NULL),
(24, '202201033', 'X4kkC', NULL),
(25, '202201034', 'peheM', NULL),
(26, '202201035', 'yJqNi', NULL),
(27, '202201038', 'x4vq2', NULL),
(28, '202201039', 'DxCqz', NULL),
(29, '202201041', 'OMtXX', NULL),
(30, '202201042', 'mQzRJ', NULL),
(31, '202201043', '5M3ZH', NULL),
(32, '202201046', 'ax4OM', NULL),
(33, '202201047', '3nbSe', NULL),
(34, '202201050', '8Gouy', NULL),
(35, '202201051', 'aXYU3', NULL),
(36, '202201052', 'xYWd4', NULL),
(37, '202201053', 'JSdxj', NULL),
(38, '202201054', 'YNXoY', NULL),
(39, '202301001', 'kH6yn', NULL),
(40, '202301002', 'qB2jD', NULL),
(41, '202301003', 'dBZab', NULL),
(42, '202301004', 'WeUCX', NULL),
(43, '202301005', 'UaMah', NULL),
(44, '202301006', 'PWyuj', NULL),
(45, '202301007', 'gYoYR', NULL),
(46, '202301008', 'dzDSo', NULL),
(47, '202301010', 'IkJse', NULL),
(48, '202301011', 'cXSy5', NULL),
(49, '202301012', 'lbt5g', NULL),
(50, '202301013', 'g9IgD', NULL),
(51, '202301014', 'rRwic', NULL),
(52, '202301015', 'Y1utn', NULL),
(53, '202301016', 'phhY4', NULL),
(54, '202301017', 'zesDc', NULL),
(55, '202301018', 'FMgoP', NULL),
(56, '202301019', 'ftu2a', NULL),
(57, '202301020', 'V6wMa', NULL),
(58, '202301021', 'WMySS', NULL),
(59, '202301022', 'rIiK5', NULL),
(60, '202301023', 'KiaIy', NULL),
(61, '202301024', '3IksX', NULL),
(62, '202301025', 'gvQRS', NULL),
(63, '202301026', 'N84kL', NULL),
(64, '202301027', 'vanWO', NULL),
(65, '202301028', 'G01e3', NULL),
(66, '202301029', 'XqD0W', NULL),
(67, '202301030', 'DoqkB', NULL),
(68, '202301031', '1bsh8', NULL),
(69, '202301032', 'zDHQc', NULL),
(70, '202301033', '89rdb', NULL),
(71, '202301034', 'BBAhh', NULL),
(72, '202301035', 'yIwSX', NULL),
(73, '202301036', 'tRxAO', NULL),
(74, '202301037', 'jKXFV', NULL),
(75, '202301038', 'PuJnf', NULL),
(76, '202301039', 'gERIo', NULL),
(77, '202301040', 'vNziI', NULL),
(78, '202301041', 'GKJRo', NULL),
(79, '202301042', 'IKYqS', NULL),
(80, '202301043', 'M0Wku', NULL),
(81, '202301044', 'Rmj84', NULL),
(82, '202301045', 'uqkEx', NULL),
(83, '202301046', 'ZO9n2', NULL),
(84, '202301047', 'u33fN', NULL),
(85, '202301048', 'mXXDH', NULL),
(86, '202301049', 'snuLc', NULL),
(87, '202301050', 'YK7Wr', NULL),
(88, '202301051', 'uuQ8U', NULL),
(89, '202301052', '0l2ci', NULL),
(90, '202301053', 'wNmLm', NULL),
(91, '202301055', 'CQs0D', NULL),
(92, '202301056', 'beuR8', NULL),
(93, '202301057', 'wPULk', NULL),
(94, '202401002', 'xPyuj', NULL),
(95, '202401003', 'Prb0Z', NULL),
(96, '202401004', 'xd0qy', NULL),
(97, '202401005', 'pBDLL', NULL),
(98, '202401006', '8QcvL', NULL),
(99, '202401008', 'vQCvX', NULL),
(100, '202401009', 'EEG6i', NULL),
(101, '202401010', 'FUWtR', NULL),
(102, '202401011', 'JPucg', NULL),
(103, '202401012', 'lHFYU', NULL),
(104, '202401013', 'ZyB6B', NULL),
(105, '202401014', '1NuDY', NULL),
(106, '202401015', 'mYs4d', NULL),
(107, '202401016', 'UOcWF', NULL),
(108, '202401017', 'tQX94', NULL),
(109, '202401018', 'psACL', NULL),
(110, '202401019', '2EuI4', NULL),
(111, '202401020', 'y4RaM', NULL),
(112, '202401021', 'iqsWB', NULL),
(113, '202401022', 'yojHY', NULL),
(114, '202401023', 'aFf8Q', NULL),
(115, '202401024', 'J8xmy', NULL),
(116, '202401025', 'SrQ57', NULL),
(117, '202401026', 'mLvpp', NULL),
(118, '202401027', 'M3jyE', NULL),
(119, '202401028', 'WHut1', NULL),
(120, '202401030', 'fZnsM', NULL),
(121, '202401031', 'ZWv2h', NULL),
(122, '202401032', 'gMlAz', NULL),
(123, '202401033', 'nfixy', NULL),
(124, '202401034', 'kOxte', NULL),
(125, '202401035', 'Rmukn', NULL),
(126, '202401036', 'PKujo', NULL),
(127, '202401037', '6diqf', NULL),
(128, '202401038', '4ytIE', NULL),
(129, '202401040', 'z5Dmk', NULL),
(130, '202401041', 'EQgpy', NULL),
(131, '202401042', 'qIRBg', NULL),
(132, '202401043', 'M95rc', NULL),
(133, '202401044', 'DdrP8', NULL),
(134, '202401045', 'ooS3y', NULL),
(135, '202401046', 'DKY8b', NULL),
(136, '202401047', 'XOx4W', NULL),
(137, '202401048', 'APtUp', NULL),
(138, '202401049', 'kHbvD', NULL),
(139, '202401050', 'uQWjy', NULL),
(140, '202401051', 'NZbnD', NULL),
(141, '202401052', 'xtwwk', NULL),
(142, '202401053', 'EdJVu', NULL),
(143, '202401054', 'IJKWf', NULL),
(144, '202401055', 'NddbA', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `aplikasi`
--

CREATE TABLE `aplikasi` (
  `id` int(2) NOT NULL,
  `alamat` varchar(30) NOT NULL,
  `kepsek` varchar(30) NOT NULL,
  `nipkepsek` varchar(30) NOT NULL,
  `namasek` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `aplikasi`
--

INSERT INTO `aplikasi` (`id`, `alamat`, `kepsek`, `nipkepsek`, `namasek`) VALUES
(1, 'BEKASI', '', '', 'AKHYAR INTERNATIONAL ISLAMIC SCHOOL');

-- --------------------------------------------------------

--
-- Table structure for table `daily_siswa_approved`
--

CREATE TABLE `daily_siswa_approved` (
  `id` int(11) NOT NULL,
  `from_nip` varchar(50) DEFAULT NULL,
  `nis_siswa` varchar(50) DEFAULT NULL,
  `departemen` varchar(50) DEFAULT NULL,
  `title_daily` varchar(250) DEFAULT NULL,
  `isi_daily` varchar(500) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `status_approve` int(11) DEFAULT NULL,
  `tanggal_dibuat` datetime DEFAULT NULL,
  `tanggal_disetujui_atau_tidak` datetime DEFAULT NULL,
  `stamp` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_kelas`
--

CREATE TABLE `group_kelas` (
  `id` int(11) NOT NULL,
  `nama_group_kelas` char(250) DEFAULT NULL,
  `walas` varchar(250) DEFAULT NULL,
  `nip` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group_kelas`
--

INSERT INTO `group_kelas` (`id`, `nama_group_kelas`, `walas`, `nip`) VALUES
(1, 'AL-ASHR', 'DIANA HAFSARI NURPRATIWI,  S.Psi.', '2024126'),
(2, 'AL-QADR', 'YUNITA ROSMALIA,  S.Pd', '2024129'),
(3, 'AT-TIIN', 'CUT ALFIANA ANDARINI,  S.Pd', '2021051'),
(4, 'AL-ALAQ', 'NURUL AINI,  S.Pd', '2023503'),
(5, 'AL-BURUUJ', 'DAFFA ALYA\'IDDINI,  S.Pd', '2024144'),
(6, 'AT-THAARIQ', 'FARAH FAHRIAH,  S.Pd.I', '2024127'),
(7, 'AL-FAJR', 'MEIDA AGUSTIN', '2023504'),
(8, 'AL-BALAD', 'TETI LISTIAWATI,  S.Pd.I', '2019028'),
(9, 'AL-MURSALAT', 'EMBUNT MUZDALIFAH,  S.Pd', '2023119'),
(10, 'AL-MAARIJ', 'REGITHA SASKIA,  S.Pd', '2022094'),
(11, 'AL-INSAN', 'INEZ DELLA MAHARANI,  S.Ag', '2024121'),
(12, 'AL-QALAM', 'NURUL ANNISA FIRDAUS,  S.Pd', '2023500'),
(13, '1A', 'LUTHFIANA,  S.Pd', '2021055'),
(14, '1B', 'MUTHIA IZZA AZHARI,  S.Sos', '2023098'),
(15, '1C', 'NADIA IZZATURRAHMAH,  S.Ag', '2024132'),
(16, '2A', 'AIDAH FITRIAH,  S.Pd', '2023099'),
(17, '2B', 'GEMA INTAN FRINCYLIANA Lc.', '2024122'),
(18, '3A', 'NADIA \'AAFIYAH WAHYUASIH,  S.Pd', '2022070'),
(19, '3B', 'SALSABILA AGIS,  S.Pd', '2024118'),
(20, '4A', 'AJENG SEPTIANTI,  S.Pd', '2024135'),
(21, '4B', 'NESYA NURUL AMALIA,  A.Md', '2023117'),
(22, '5A', 'RANGGA SATRIA AKBAR,  S.Ag', '2024123'),
(23, '5B', 'NURUL AMALIA,  S.Si', '2017006'),
(24, '6A', 'AHMAD BAIDOWI,  M.Pd', '2023086'),
(25, '6B', 'NABIILAH IFFATUL HANUUN,  S.Si', '2021060');

-- --------------------------------------------------------

--
-- Table structure for table `group_siswa_approved`
--

CREATE TABLE `group_siswa_approved` (
  `id` varchar(50) NOT NULL DEFAULT '',
  `from_nip` varchar(50) DEFAULT NULL,
  `group_kelas_id` int(11) DEFAULT NULL,
  `departemen` varchar(50) DEFAULT NULL,
  `title_daily` varchar(250) DEFAULT NULL,
  `isi_daily` varchar(500) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `status_approve` int(11) DEFAULT NULL,
  `tanggal_dibuat` datetime DEFAULT NULL,
  `tanggal_disetujui_atau_tidak` datetime DEFAULT NULL,
  `stamp` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `c_guru` varchar(10) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `temlahir` varchar(20) DEFAULT NULL,
  `tanglahir` date NOT NULL,
  `tgl_join` date NOT NULL,
  `c_jabatan` varchar(50) DEFAULT NULL,
  `jkel` varchar(10) NOT NULL,
  `alamat` text DEFAULT NULL,
  `pendidikan` varchar(100) DEFAULT NULL,
  `jurusan` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`c_guru`, `nip`, `nama`, `temlahir`, `tanglahir`, `tgl_join`, `c_jabatan`, `jkel`, `alamat`, `pendidikan`, `jurusan`, `email`, `username`, `password`, `no_hp`) VALUES
('96hZSPlqa', '2024148', 'VIKA NURAFNI HANDAYANI', 'Bekasi', '2002-03-03', '2024-10-29', 'Guru PAUD Poros', 'P', 'Kp. Setu Tambun RT 001/RW10, Kel. Bintara Jaya Kec Bekasi Barat, Kota Bekasi', 'S1', 'Pend. Agama Islam', '', 'vika', '$2y$10$7pxAYcUuzi6vDAzeErbVTOBLxDoD9bfct9c0Wnq7d5v4rnO8UZB4G', '08'),
('AOFI59146', '2019034', 'PANGESTI PUTRI UTAMI', 'Bekasi', '1992-05-05', '2020-06-12', 'Pjs. Kepala Sekolah PAUD', 'P', 'Jl. Dahlia Raya No.200, RT08/08 Perumnas I Bekasi Barat, Jawa Barat', 'S1 - Sarjana Pendidikan', '', '', 'ines', '$2y$10$oml6JoGEO184SnbhAIkxWOn.fVgG.SpRjaitUGAV900DHn6g2x4Gm', '08'),
('EOAX64544', '2023502', 'TSUWAIBAH AL ASLAMIYAH', 'Bekasi', '1996-07-09', '2025-04-03', 'Guru PAUD', 'P', 'Jl. Sersan Marzuki No.130 RT.04/03 Pekayon Jaya Bekasi Selatan', 'S1, STAI Nurul Iman Parung Bogor - Pend. Bahasa Arab', '', '', 'Mia', '$2y$10$oml6JoGEO184SnbhAIkxWOn.fVgG.SpRjaitUGAV900DHn6g2x4Gm', '089614454427'),
('EOTU53633', '2023504', 'MEIDA AGUSTIN', 'Jakarta', '2003-02-05', '2023-02-05', 'Guru PAUD', 'P', 'Jl. Ciketing Kp. Tenggilis no. 135 RT 002/010, Kec. Mustikajaya Kel. Mustikajaya Bekasi Jawa Barat', 'Kuliah, Universitas Bhayangkara - Psikologi', '', '', 'meida', '$2y$10$oml6JoGEO184SnbhAIkxWOn.fVgG.SpRjaitUGAV900DHn6g2x4Gm', '085883343062'),
('EQ0fPaLZc', '2741382', 'GURU PAUD', 'Jakarta', '1998-10-14', '2024-12-24', 'Guru PAUD', 'L', 'Jabodetabek', 'S1', 'PAI', '', 'gurbar2', '$2y$10$H02lrGfIYHsKU5fNZcMeGev6x3iXhTb4LCfh8G8MODHzueVelw6TK', NULL),
('EQJS85259', '2022074', 'NAJLA ISMAH NURSYAIDAH', 'Bandung', '2000-05-04', '2024-06-05', 'Guru PAUD', 'P', 'Jl. Dep-Kes II Jatibening, Pondok Gede, Kota Bekasi, Jawa Barat', 'Kuliah - PTIQ', '', '', 'najla', '$2y$10$oml6JoGEO184SnbhAIkxWOn.fVgG.SpRjaitUGAV900DHn6g2x4Gm', '081330309838'),
('FAQIyUBJ9', '2024139', 'MELISA SEPTIANI ', 'bekasi', '1993-09-02', '2024-08-01', 'guru PAUD', 'P', 'Jl h Hasan no 98 Pekayon jaya RT 2/21', 'SMA', 'Madrasah Aliyah Al muawanah', '', 'amel', '$2y$10$VTtl.ntyyiDorHb2vdYorOROo.ojeQg2m5AWHIcbQcqZZUYY9n1Uy', '083806151987'),
('fKt6bsQ2z', '2024144', 'DAFFA ALYA\'IDDINI, S.Pd', 'Trenggalek', '2002-03-12', '2024-10-14', 'Wali Kelas PAUD K1', 'P', 'Jl. KH. Agus Salim Gg. Al Kautsar No.4 RT 001/RW 007 Kel. Bekasi Jaya Kec. Bekasi Timur, Kota Bekasi', 'S1', 'Pend. Agama Islam', '', 'daffa', '$2y$10$37M8g9k1jtzzb.mA6iCP0e8eb530wzKYV30s9Kx0fn8kihBUibamu', '081296125802'),
('FNFA24541', '2019029', 'FITRI ANTI', 'Jakarta', '1994-12-03', '2020-05-07', 'Wakepsek PAUD - Bid. Kurikulum', 'P', 'Jl. Dahlia 2 no. 25 C, RT 001/008, Kel. Jakasampurna Kec. Bekasi Barat', 'S1 - PAI Universitas At Tahiriyah Jakarta', '', '', 'fitri', '$2y$10$oml6JoGEO184SnbhAIkxWOn.fVgG.SpRjaitUGAV900DHn6g2x4Gm', NULL),
('GYWD13612', '2023503', 'NURUL AINI', 'Sukabumi', '1997-01-11', '2023-02-05', 'Guru PAUD', 'P', 'Jl. Raya Narogong Gg. H. Kamal RT.06/01 Kel. Bojong, Rawalumbu, Kec. Rawalumbu ', 'S1, PAI STAI Al Masturiyah Sukabumi ', '', '', 'nurulaini', '$2y$10$oml6JoGEO184SnbhAIkxWOn.fVgG.SpRjaitUGAV900DHn6g2x4Gm', '085861342580'),
('HVNI17244', '2022094', 'REGITHA SASKIA', 'Bekasi', '1998-10-07', '2022-08-09', 'Guru PAUD', 'P', 'Bumi Sani Permai Blok i.2 No.18 RT/RW 001/014. Setiamekar, Tambun Selatan, Bekasi Timur', 'S1, Universitas Muhammadiyah Prof.Dr.Hamka (UHAMKA)  - Pendidikan Bahasa Inggris ', '', '', 'saskia', '$2y$10$oml6JoGEO184SnbhAIkxWOn.fVgG.SpRjaitUGAV900DHn6g2x4Gm', '087880133828'),
('lMLXdtFKA', '2024126', 'DIANA HAFSARI NURPRATIWI', 'Sumedang ', '1970-01-01', '1970-01-01', 'guru PAUD', 'P', 'Dusun Sukamulya RT 13 RW 04 Desa Paseh Kidul Kecamatan Paseh Kabupaten Sumedang', 'S1', 'Psikologi Universitas Islam Sultan Agung Semarang', '', 'Diana', '$2y$10$jXRuwMj9xTq3HAw664sKBOHHtw857VJ9.BwngWrHOBAcLg5//wxMO', '082112335938'),
('LPSC86588', '2018015', 'RIZKA GITA RESTIANI', 'Bekasi', '1995-01-09', '2019-03-05', 'Guru & Koordinator Poros Masjid PAUD', 'P', 'Jl. Belanak II No.53 Rt.02/01 Perumnas 2, Kayuringin Jaya, Bekasi Selatan', 'S1 - Sarjana Bahasa Inggris', '', '', 'rizka', '$2y$10$oml6JoGEO184SnbhAIkxWOn.fVgG.SpRjaitUGAV900DHn6g2x4Gm', NULL),
('LvkacYdSk', '2023119', 'EMBUNT MUZDALIFAH ', 'Bekasi', '1970-01-01', '1970-01-01', 'guru PAUD', 'P', 'Jl.budaya, Rt.01/05 no.33b, cililitan, kramat jati, jakarta timur', 'S1', ' Institut Ummul Quro Al Islami, Pendidikan Bahasa ', '', 'Embunt', '$2y$10$15lNDoi7ye4Rsyag/Txdze8H7.ei2XeJGkA4yp/cz2a0bmSUbuNQe', '085719396436'),
('QOMJ17184', '2021051', 'CUT ALFIANA ANDARINI', 'Bekasi', '2000-04-07', '2022-12-05', 'Guru PAUD', 'P', 'Pondok Pekayon Indah, Jl. Ketapang Raya Blok CC3 No. 4, Pekayon Jaya, Bekasi Selatan', 'S1 - Universitas Negeri Jakarta', '', '', 'cut', '$2y$10$oml6JoGEO184SnbhAIkxWOn.fVgG.SpRjaitUGAV900DHn6g2x4Gm', '085811444929'),
('RTLJ46984', '2020037', 'SUCI SULISTYA', 'Bandung', '1995-08-06', '2020-05-03', 'Guru PAUD', 'P', 'Jl. Ratna, Gg. Hj. Dinah Rt002/001, No.67, Jatibening, Pondok Gede, Bekasi', 'S1 - FKIP Bahasa Inggris Universitas Asy-Syafi\'iyah', '', '', 'suci', '$2y$10$oml6JoGEO184SnbhAIkxWOn.fVgG.SpRjaitUGAV900DHn6g2x4Gm', NULL),
('UUNU61307', '2023115', 'ALFIRA RAHMAH SHOLIHAH', 'Bekasi', '2002-07-09', '2023-10-07', 'Guru PAUD', 'P', 'Perum. Graha Asri Jl. Cisanggiri IIA blok R No. 43 RT.002/009 Ds. Jatireja, Kec. Cikarang Timur, Kab. Bekasi', 'S1, Institut Ummul Quro Al-Islami Bogor - Pendidikan Bahasa Arab.', '', '', 'alfira', '$2y$10$oml6JoGEO184SnbhAIkxWOn.fVgG.SpRjaitUGAV900DHn6g2x4Gm', NULL),
('vCpCo2AIW', '2024127', 'FARAH FAHRIAH ', 'Jakarta ', '1970-01-01', '1970-01-01', 'guru Poros Paud', 'P', 'Jl. Pertanian Selatan No. 27 RT. 002?RW. 03 Keluarahan. klender, Kecamatan. Duren Sawit, Jakarta Timur 13470.', 'S1', ' Universitas Islam As Syafi’iyah, Pendidikan Agama', '', 'Farah', '$2y$10$0niNIIymRSIIV9Uz953K9e6lMbB9Hga2st5kV.9Np.29LplbrYlTq', '081384558212'),
('VRIK59733', '2023114', 'IDA FARIDA', 'Jakarta', '1994-03-05', '2023-10-07', 'Guru PAUD', 'P', 'Bumi Berlian Sejahtera 3 blok J 14 Babelan - Bekasi', 'S1, STAI Publisistik Thawalib Jakarta - PGSD', '', '', 'ida', '$2y$10$oml6JoGEO184SnbhAIkxWOn.fVgG.SpRjaitUGAV900DHn6g2x4Gm', NULL),
('VYWP77570', '2019028', 'TETI LISTIAWATI', 'Jakarta', '1988-05-09', '2019-01-07', 'Guru PAUD', 'P', 'Jl. Bintara Jaya 2 Kp. Cibening No. 85 RT 08 RW 03, Bintara Jaya, Bekasi Barat', 'S1 - Sarjana Pendidikan Islam UIN Sunan Gunung Djati', '', '', 'teti', '$2y$10$oml6JoGEO184SnbhAIkxWOn.fVgG.SpRjaitUGAV900DHn6g2x4Gm', '082258178743'),
('WFGO66732', '2023500', 'NURUL ANNISA FIRDAUS', 'Jakarta', '2000-02-09', '2024-01-02', 'Guru PAUD', 'P', 'Perumahan Taman Alamanda blok B 9 No. 58 RT02/012, Kec. Tambun Utara, Kel. Karang Satria, Kab. Bekasi', 'S1, Universitas Islam Jakarta - Pendidikan Bahasa Arab', '', '', 'nurulannisa', '$2y$10$oml6JoGEO184SnbhAIkxWOn.fVgG.SpRjaitUGAV900DHn6g2x4Gm', '081222632775'),
('XMVR74266', '2023501', 'ELPA HASNA SHOFIA', 'Garut', '2004-03-01', '2024-01-03', 'Guru PAUD', 'P', 'Kp. Pasir Jeung Jing RT 003/007, Ds. Simpangsari, Kec. Cisurupan, Kab. Garut, Jawa Barat', 'S1, Universitas Terbuka - PG PAUD', '', '', 'shofia', '$2y$10$oml6JoGEO184SnbhAIkxWOn.fVgG.SpRjaitUGAV900DHn6g2x4Gm', NULL),
('xudiyJWfx', '2024121', 'INEZ DELLA MAHARANI', 'Jakarta ', '1999-09-18', '1970-01-01', 'guru PAUD', 'P', 'Jl Bintara XI rt 002 rw 005 no.25 Bekasi Barat', 'S1', 'Institut Ilmu Al-Qur’an', '', 'della', '$2y$10$Cf/sEoADWe3N4x8OfHT9BeQ3eux7Gmg2ZGaPX4Y9nLvM8iIfLTswa', '087775703242'),
('yB2lD7wKr', '2024129', 'YUNITA ROSMALIA', 'Bogor', '2024-06-19', '2024-07-08', 'guru PAUD', 'P', 'Cilendek Timur, Gang Kutilang 1, rt 02/rw08, kota Bogor, Bogor Barat', 'S1', 'Isntitut Ummul Qura Al Islami Bogor, pen.Bahasa Ar', '', 'Yunita', '$2y$10$26P8PRBGP9ND.mCIxoSEcuKA7.zarlyI6tvylpr34srOFcIh8GgiO', '088298891605'),
('yJ51l7aB0', '2024128', 'ANISA ARFIAH  ', 'Jember', '1998-10-01', '2024-06-06', 'guru Poros Paud', 'P', 'Jl bintara 12a no 100a', 'S1', 'Komunikasi penyiaran Islam', '', 'Anis  ', '$2y$10$wnM9edhXcqTe//1fY5eTledXwUW6z3pLIIwrPK/rhFSmzgCeN8b6.', NULL),
('ZVBH56760', '2022088', 'FAEDIYAH', 'Indramayu', '1996-10-08', '2024-01-07', 'Guru PAUD', 'P', 'Jl. Karangampel-jatibarang No.37 tikungan RT001/001 Ds. Segeran kidul Kec. Juntinyuat Kab. Indramayu Jawa Barat', 'S1, STAI Nurul Iman Parung Bogor - Pend. Bahasa Arab', '', '', 'faediyah', '$2y$10$oml6JoGEO184SnbhAIkxWOn.fVgG.SpRjaitUGAV900DHn6g2x4Gm', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `history_password`
--

CREATE TABLE `history_password` (
  `id` int(11) NOT NULL,
  `nis_siswa` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history_password`
--

INSERT INTO `history_password` (`id`, `nis_siswa`, `password`) VALUES
(1, '201902001', 'NBDHL'),
(2, '201902002', 'lguSC'),
(3, '201902003', 'ucHbz'),
(4, '201902005', 'Z8CDo'),
(5, '201902006', 'i9pY7'),
(6, '201902007', 'DLnvt'),
(7, '201902008', '9fqLn'),
(8, '201902009', 'U5eeB'),
(9, '201902010', 'gg60Z'),
(10, '201902011', 'oISWO'),
(11, '201902012', 'zEBqP'),
(12, '201902014', 'sYJEe'),
(13, '201902016', '7qfGE'),
(14, '201902017', '6e3s0'),
(15, '201902018', '0Ynlx'),
(16, '201902020', '533P2'),
(17, '201902021', 'fQyud'),
(18, '201902022', 'uQpi5'),
(19, '201902023', 'uiZy8'),
(20, '201902024', 'JMY3u'),
(21, '201902025', 'EfvQW'),
(22, '201902026', 'FWL0p'),
(23, '201902027', 'XAAjD'),
(24, '201902028', '5M0x8'),
(25, '201902029', 'QFz3R'),
(26, '201902032', 'VGO3O'),
(27, '202002039', 'PSlN6'),
(28, '202002041', 'UshjZ'),
(29, '202002042', 'MKqUM'),
(30, '202002043', 'hoGSu'),
(31, '202002044', '3qZAQ'),
(32, '202002046', 'yPwzX'),
(33, '202002047', 'funPv'),
(34, '202002051', 'ZB3CC'),
(35, '202002053', 'ryurc'),
(36, '202002054', 'VjvRH'),
(37, '202002055', '8ayPC'),
(38, '202002057', 'HFdzP'),
(39, '202002058', 'AgbRC'),
(40, '202002059', 'a6Dor'),
(41, '202002060', 'Zmbq8'),
(42, '202002061', 'm2ydS'),
(43, '202002062', 'ZLXCf'),
(44, '202002065', 'QfKQj'),
(45, '202002066', 'chuRi'),
(46, '202002067', 'pHy8j'),
(47, '202002069', '269hI'),
(48, '202002070', 'PUUUt'),
(49, '202002072', 'w1JKZ'),
(50, '202002073', 'F6EZO'),
(51, '202002074', '2xty2'),
(52, '202002077', 'UQ3C4'),
(53, '202002079', 'jMUVb'),
(54, '202002080', 'O4zuy'),
(55, '202002082', 'qJlLS'),
(56, '202102083', 'ue3Do'),
(57, '202102084', 'y8mfI'),
(58, '202102086', 'UeRqd'),
(59, '202102087', 'DtYHW'),
(60, '202102088', 'O3rg3'),
(61, '202102089', 'lyV15'),
(62, '202102090', '1431Y'),
(63, '202102091', 'yJlbh'),
(64, '202102092', 'imjpb'),
(65, '202102093', 'Paahb'),
(66, '202102094', '986aa'),
(67, '202102095', 'FlHh3'),
(68, '202102096', 'ekLjH'),
(69, '202102097', 'wI2t4'),
(70, '202102098', '3CWt1'),
(71, '202102102', '4VN5V'),
(72, '202102103', 'Y9jlO'),
(73, '202102104', 'kNM1g'),
(74, '202102105', 'VXaVl'),
(75, '202102106', 'webuO'),
(76, '202102108', 'NvGOz'),
(77, '202102109', 'zPG7H'),
(78, '202202110', 'cME2X'),
(79, '202202111', 'u2hMI'),
(80, '202202112', '7BiN0'),
(81, '202202113', '2kmAX'),
(82, '202202114', 'KUQMJ'),
(83, '202202115', 'yyvuj'),
(84, '202202116', '1DHNd'),
(85, '202202117', 'Lw4l0'),
(86, '202202118', 'ihnVu'),
(87, '202202119', 'tlB4l'),
(88, '202202120', 'JoaJM'),
(89, '202202121', 'q0bJf'),
(90, '202202122', 'nWRA2'),
(91, '202202123', 'kkrQZ'),
(92, '202202124', 'PNsQN'),
(93, '202202125', '9R30a'),
(94, '202202127', 'u8r7q'),
(95, '202202128', 'O01vc'),
(96, '202202129', 'r0nOu'),
(97, '202202130', 'iQzq6'),
(98, '202202131', 'I33Sx'),
(99, '202202132', 'l11ZM'),
(100, '202202134', 'dKKpr'),
(101, '202202135', 'exLSL'),
(102, '202202136', 'Mfloi'),
(103, '202202137', 'RAGj8'),
(104, '202202138', '0wvQj'),
(105, '202202139', 'gb3hJ'),
(106, '202202140', 'pwumG'),
(107, '202202141', 'EEgQ8'),
(108, '202202142', 'vHhuX'),
(109, '202202143', 'WPUxz'),
(110, '202202144', 'LsPQq'),
(111, '202202145', 'uLUWC'),
(112, '202302001', '9XJdw'),
(113, '202302002', 'vZz5D'),
(114, '202302003', 'cHXis'),
(115, '202302004', 'bdQ8t'),
(116, '202302005', 'tvtFp'),
(117, '202302006', '8PtRS'),
(118, '202302007', 'QVwOy'),
(119, '202302008', 'ar58I'),
(120, '202302009', 'pAinH'),
(121, '202302010', 'YHVqc'),
(122, '202302012', 'CHMaJ'),
(123, '202302013', '2Cfeo'),
(124, '202302014', '3L1ia'),
(125, '202302015', 'zYWvv'),
(126, '202302016', 'k7xp1'),
(127, '202302017', 'Yxc9Z'),
(128, '202302018', 'qu0e2'),
(129, '202302019', 'bqjNz'),
(130, '202302020', '9uQR7'),
(131, '202302021', 'uPlKa'),
(132, '202302022', 'Dn0oq'),
(133, '202302023', 'L4ZMC'),
(134, '202302024', 'OfBWb'),
(135, '202302025', 'avPsJ'),
(136, '202302026', 'RDkA4'),
(137, '202302027', 'mDS9K'),
(138, '202302028', 'Ytn7Q'),
(139, '202302029', 'PjEtD'),
(140, '202302030', 'AzgoA'),
(141, '202302031', 'MqGnv'),
(142, '202302033', 'uAahe'),
(143, '202302034', 'W5LZi'),
(144, '202302036', 's0coh'),
(145, '202302037', 'arOhX'),
(146, '202302038', 'kXCBl'),
(147, '202302039', 'DmaPs'),
(148, '202302040', '9Mbet'),
(149, '202302041', 'ywNSF'),
(150, '202302042', 'DDihI'),
(151, '202302043', 'kyias'),
(152, '202302044', '7yN50'),
(153, '202302045', '9c8Yi'),
(154, '202302046', 'KOEWf'),
(155, '202302047', 'ygWan'),
(156, '202402001', 'bsr94'),
(157, '202402002', 'lrFom'),
(158, '202402003', 'SjpWM'),
(159, '202402004', 'uG9uL'),
(160, '202402005', '8fv8e'),
(161, '202402006', 'J66QH'),
(162, '202402007', 'Yw46t'),
(163, '202402008', 'SjbSW'),
(164, '202402009', 'IhimJ'),
(165, '202402010', 'GCyC0'),
(166, '202402011', 'fL2H5'),
(167, '202402012', 'P857Q'),
(168, '202402013', 'pP4xu'),
(169, '202402014', 'SUIMf'),
(170, '202402015', 'dDSAf'),
(171, '202402016', 'eRZFG'),
(172, '202402017', 'CLlBp'),
(173, '202402018', 'aUfUN'),
(174, '202402019', 'MQw4P'),
(175, '202402020', 'pymwS'),
(176, '202402021', 'ZPJJ7'),
(177, '202402023', 'fvYdB'),
(178, '202402024', 'OFpjL'),
(179, '202402025', 'ZiD41'),
(180, '202402026', 'hfef9'),
(181, '202402027', 'c4gBo'),
(182, '202402032', 'qqe3r'),
(183, '202402033', 'XoyHC'),
(184, '202402034', '9fqV6'),
(185, '202402029', 'Y4yJG'),
(186, '202402030', 'LJLjM'),
(187, '202402031', 'Fchv1'),
(188, '202402035', 'GbrMp'),
(189, '202402036', 'JtndX'),
(190, '202402037', 'Fwbkb'),
(191, '202402038', 't5bcO'),
(192, '202402039', 'FGjgZ'),
(193, '202402040', 'yBb9i'),
(194, '202402041', 'LrAoN'),
(195, '202402042', 'nFaoq'),
(196, '202402043', 'WBC3P'),
(197, '202402044', 'SvyBy'),
(198, '202402045', 'lhjzm'),
(199, '202402046', 'FPkJu'),
(200, '202402047', 'Vl53H'),
(201, '202402048', '2rDtU'),
(202, '202402049', 'qvJz7'),
(203, '202402051', 'vS9Qc'),
(204, '202402022', '89BR6'),
(205, '202402052', 'gOQLh'),
(206, '202402053', 'JQdrX'),
(207, '202402054', 'JJh1y'),
(208, '202101001', 'jP5LA'),
(209, '202101002', 'hH3Ik'),
(210, '202101003', 'bEi7a'),
(211, '202101004', 'PEE4y'),
(212, '202101005', 'l0iZx'),
(213, '202101006', 'yZJyv'),
(214, '202101008', '2Z5Lv'),
(215, '202101009', 'qlu9V'),
(216, '202101010', '4ibx6'),
(217, '202101011', 'aEbH0'),
(218, '202101012', '6f4ov'),
(219, '202101015', 'YeKMB'),
(220, '202101016', 'jj9Xu'),
(221, '202101017', 'Uj12S'),
(222, '202101018', '6Vl3m'),
(223, '202101021', 'L6Ltf'),
(224, '202101022', 'mjcXy'),
(225, '202101023', 'KxuNE'),
(226, '202101024', 'q1l5r'),
(227, '202101025', '2juhO'),
(228, '202101026', '3lPlC'),
(229, '202101027', 'iduyR'),
(230, '202101028', 'gKbuq'),
(231, '202101030', 'mIdGq'),
(232, '202101032', 'rU7yc'),
(233, '202101034', 'G0725'),
(234, '202101064', 'X3019'),
(235, '202101065', 'fpwWg'),
(236, '202101066', 'uucqQ'),
(237, '202101067', 'cMZ0Z'),
(238, '202101068', 'ZOfKG'),
(239, '202101069', 'KmFXR'),
(240, '202101074', 'HXJVX'),
(241, '202101076', '18hey'),
(242, '202101077', 'yOyn9'),
(243, '202201001', 'eUZ1y'),
(244, '202201002', 'Ayhs9'),
(245, '202201005', 'BgWsP'),
(246, '202201007', 'Yblub'),
(247, '202201008', 'zpBUA'),
(248, '202201010', 'Bzygt'),
(249, '202201011', '1L94X'),
(250, '202201012', 'ELRtK'),
(251, '202201013', 'Js2Li'),
(252, '202201014', 'HyibU'),
(253, '202201017', 'wE0JF'),
(254, '202201018', 'PnGPG'),
(255, '202201020', 'Mnydu'),
(256, '202201021', 'eHMVO'),
(257, '202201022', 'u1OAk'),
(258, '202201024', '5M3R8'),
(259, '202201025', 'jfpOP'),
(260, '202201026', 'D6ifX'),
(261, '202201027', 'CRPuO'),
(262, '202201029', 'ytOin'),
(263, '202201030', 'u3xrM'),
(264, '202201031', 'zuZNV'),
(265, '202201032', 'qh9fd'),
(266, '202201033', 'vi7su'),
(267, '202201034', 'CQsYF'),
(268, '202201035', '6chfQ'),
(269, '202201038', 'MOqpU'),
(270, '202201039', 'K4Ike'),
(271, '202201041', 'qwMW8'),
(272, '202201042', 'HjfzF'),
(273, '202201043', 'OQnnj'),
(274, '202201045', '7cmb4'),
(275, '202201046', 'sNEbH'),
(276, '202201047', 'BOgoP'),
(277, '202201049', 'kllI9'),
(278, '202201050', 'W37PD'),
(279, '202201051', 'XkKnp'),
(280, '202201052', 'pOQ7Q'),
(281, '202201053', '5A5oB'),
(282, '202201054', '8zsWO'),
(283, '202201055', 'DS2ew'),
(284, '202201056', '2LicK'),
(285, '202201057', 'o9oQq'),
(286, '202301001', 'eqeku'),
(287, '202301002', 'UGY5C'),
(288, '202301003', 'OMHqv'),
(289, '202301004', 'ynJVK'),
(290, '202301005', 'Pct4L'),
(291, '202301006', 'IwzBn'),
(292, '202301007', 'nAbji'),
(293, '202301008', 'FByAJ'),
(294, '202301010', 'yGsS5'),
(295, '202301011', 'bs5NW'),
(296, '202301012', 'uzLph'),
(297, '202301013', 'V9SCu'),
(298, '202301014', 'J8OkH'),
(299, '202301015', 'XiFAM'),
(300, '202301016', 'UIOmM'),
(301, '202301017', 'j8mfW'),
(302, '202301018', 'muM0L'),
(303, '202301019', 'hxuyu'),
(304, '202301020', 'jUImp'),
(305, '202301021', '03BG6'),
(306, '202301022', 'vYVmV'),
(307, '202301023', '77VkV'),
(308, '202301024', 'bB9J9'),
(309, '202301025', 'xaUco'),
(310, '202301026', '8yFeg'),
(311, '202301027', 'u6doG'),
(312, '202301028', 'Zn8rf'),
(313, '202301029', 'QbKRp'),
(314, '202301030', 'UIsEX'),
(315, '202301031', 'y6vbq'),
(316, '202301032', 'PQiaE'),
(317, '202301033', 'xDXe2'),
(318, '202301034', 'A9eWB'),
(319, '202301035', 'ZBhrf'),
(320, '202301036', '7D8ix'),
(321, '202301037', '8uyjE'),
(322, '202301038', 'qrLPE'),
(323, '202301039', 'DqCjs'),
(324, '202301040', 'geOnH'),
(325, '202301041', 'am8Lc'),
(326, '202301042', 'zKvAs'),
(327, '202301043', 'Wm1Js'),
(328, '202301044', 'ZubIU'),
(329, '202301045', 'tcHO6'),
(330, '202301046', 'AchW9'),
(331, '202301047', 'MDL3P'),
(332, '202301048', 'Rh4qF'),
(333, '202301049', 'gUb2A'),
(334, '202301050', 'pkB3F'),
(335, '202301051', 'r6r01'),
(336, '202301052', 'jhbR5'),
(337, '202301053', 'GzI8A'),
(338, '202301054', 'KyVMk'),
(339, '202301055', 'HSczL'),
(340, '202301056', 'xFumQ'),
(341, '202301057', 'gzOy4'),
(342, '202301058', 'IPKXc'),
(343, '202401047', 'QEMqV'),
(344, '202401046', 'VKD4G'),
(345, '202401048', 'j71I3'),
(346, '202401049', 'Ilz8N'),
(347, '202401050', 'XL5k6'),
(348, '202401002', 'WVCib'),
(349, '202401003', 'uY4Wu'),
(350, '202401004', 'W5o6Z'),
(351, '202401005', '01X8L'),
(352, '202401006', 'f1Zpd'),
(353, '202401008', 'J5jfj'),
(354, '202401009', '9olaq'),
(355, '202401010', 'AA7yW'),
(356, '202401011', 'vOole'),
(357, '202401012', 'GC2vo'),
(358, '202401013', 'tib71'),
(359, '202401014', '0YHf7'),
(360, '202401015', '12t7D'),
(361, '202401016', 'liugQ'),
(362, '202401017', 'VsXZs'),
(363, '202401018', 'uAYYD'),
(364, '202401019', 'X1jyy'),
(365, '202401020', '4FoG5'),
(366, '202401021', 'Zz9Ne'),
(367, '202401022', 'NJCwu'),
(368, '202401023', 'FXP6X'),
(369, '202401024', 'YnKXc'),
(370, '202401025', '8yyP6'),
(371, '202401026', 'sVe2Q'),
(372, '202401027', '3ByCF'),
(373, '202401028', 'DhxCu'),
(374, '202401030', 'FQiz9'),
(375, '202401031', 'mPmaL'),
(376, '202401032', '89QmP'),
(377, '202401033', 'hs1VJ'),
(378, '202401034', 'i62s7'),
(379, '202401035', 'HPXbv'),
(380, '202401036', 'iueNp'),
(381, '202401037', 'uPSIG'),
(382, '202401038', 'nihqe'),
(383, '202401040', 'd52Zn'),
(384, '202401041', 'f3uJV'),
(385, '202401042', 'u1Fku'),
(386, '202401043', '27yst'),
(387, '202401044', 'WKBu7'),
(388, '202401045', 'MOUPv'),
(389, '202401051', '0KWz7'),
(390, '202401052', 'gYore'),
(391, '202401053', 'aIZuR'),
(392, '202401054', 'UxJjg'),
(393, '202401055', 'ZLmhq');

-- --------------------------------------------------------

--
-- Table structure for table `info_pengumuman_pembayaran`
--

CREATE TABLE `info_pengumuman_pembayaran` (
  `id` int(11) NOT NULL,
  `nis` varchar(50) DEFAULT NULL,
  `jenis_info_pembayaran` varchar(250) DEFAULT NULL,
  `keterangan` varchar(250) DEFAULT NULL,
  `nominal` varchar(50) DEFAULT NULL,
  `status_pembayaran` int(11) DEFAULT 0,
  `tanggal_dibuat` datetime DEFAULT NULL,
  `tanggal_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kepala_sekolah`
--

CREATE TABLE `kepala_sekolah` (
  `id` int(11) NOT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `nama` varchar(250) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kepala_sekolah`
--

INSERT INTO `kepala_sekolah` (`id`, `nip`, `nama`, `username`, `password`) VALUES
(2, '2019034', 'PANGESTI PUTRI UTAMI', 'pangesti', '$2y$10$kN2tb.HvOiuGXSWJds2LJu7q5HjudP7QrshfwAObp0Sal.dRY2ca.');

-- --------------------------------------------------------

--
-- Table structure for table `m_klp`
--

CREATE TABLE `m_klp` (
  `id` int(11) NOT NULL,
  `nm_klp` varchar(50) NOT NULL,
  `c_kelas` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `m_klp`
--

INSERT INTO `m_klp` (`id`, `nm_klp`, `c_kelas`) VALUES
(1, '1', ''),
(2, '2', ''),
(3, '3 A', ''),
(4, '3 B', ''),
(5, '4 A', ''),
(6, '4 B', ''),
(7, '5 A', ''),
(8, '5 B', ''),
(9, 'KB', ''),
(10, 'TKA', ''),
(11, 'TKB', ''),
(12, '6 A', ''),
(13, '6 B', '');

-- --------------------------------------------------------

--
-- Table structure for table `penomoranmas`
--

CREATE TABLE `penomoranmas` (
  `kode` varchar(15) DEFAULT NULL,
  `nourut` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `penomoranmas`
--

INSERT INTO `penomoranmas` (`kode`, `nourut`) VALUES
('SD', 0),
('PTK', 434);

-- --------------------------------------------------------

--
-- Table structure for table `penomoran_idgroupkelas`
--

CREATE TABLE `penomoran_idgroupkelas` (
  `kode` varchar(50) DEFAULT NULL,
  `nourut` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penomoran_idgroupkelas`
--

INSERT INTO `penomoran_idgroupkelas` (`kode`, `nourut`) VALUES
('groupkelas', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reason`
--

CREATE TABLE `reason` (
  `id` int(11) NOT NULL,
  `is_reason` varchar(250) DEFAULT NULL,
  `daily_siswa_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ruang_pesan`
--

CREATE TABLE `ruang_pesan` (
  `id` int(11) NOT NULL,
  `room_key` varchar(50) DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `daily_id` varchar(50) DEFAULT NULL,
  `room_session` int(11) DEFAULT NULL,
  `created_date_room` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `c_siswa` varchar(10) NOT NULL,
  `nis` varchar(30) DEFAULT NULL,
  `nisn` varchar(15) DEFAULT NULL,
  `nama` varchar(50) NOT NULL,
  `tempat_lahir` varchar(20) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jk` varchar(2) DEFAULT NULL,
  `c_kelas` varchar(10) DEFAULT NULL,
  `group_kelas` int(11) DEFAULT NULL,
  `tahun_join` varchar(50) DEFAULT NULL,
  `panggilan` varchar(20) DEFAULT NULL,
  `c_klp` varchar(20) DEFAULT NULL,
  `berat_badan` varchar(10) DEFAULT NULL,
  `tinggi_badan` varchar(10) DEFAULT NULL,
  `ukuran_baju` varchar(50) DEFAULT NULL,
  `alamat` varchar(200) DEFAULT NULL,
  `telp` varchar(50) DEFAULT NULL,
  `hp` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nama_ayah` varchar(50) DEFAULT NULL,
  `pendidikan_ayah` varchar(100) DEFAULT NULL,
  `pekerjaan_ayah` varchar(100) DEFAULT NULL,
  `ttl_ayah` varchar(100) DEFAULT NULL,
  `nama_ibu` varchar(50) DEFAULT NULL,
  `pendidikan_ibu` varchar(100) DEFAULT NULL,
  `pekerjaan_ibu` varchar(100) DEFAULT NULL,
  `ttl_ibu` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`c_siswa`, `nis`, `nisn`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jk`, `c_kelas`, `group_kelas`, `tahun_join`, `panggilan`, `c_klp`, `berat_badan`, `tinggi_badan`, `ukuran_baju`, `alamat`, `telp`, `hp`, `email`, `nama_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `ttl_ayah`, `nama_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `ttl_ibu`) VALUES
('TK0178', '202101001', '202101001', 'ABIZARD ISMAIL ALI', 'Bekasi', '2018-08-09', 'L', 'TKBLULUS', NULL, '2021', 'ABIZARD', 'K2- Al Mursalat', '22 kg', '102 cm', 'S', 'Komplek Pemda Blok B Jl kresna No 41 Rt 01/Rw 01 Jati Asih Bekasi', '081298567822', '081317802059', 'Ritha_2989@yahoo.com', 'Yandi Arnaz', 'S1', 'Pegawai BUMN', 'Depok/19 April 1988', 'Rita Hartati Lubis', 'S1', 'IRT', 'Jakarta/29 Maret 1989'),
('TK0179', '202101002', '202101002', 'AISYAH GHANIYA ALMAIRA', 'Jakarta', '2018-06-05', 'P', 'TKBLULUS', NULL, '2021', 'AISYAH', 'K2 - Al Qalam', '15/9 kg', '102 cm', 'M', 'Jl Taman Agave V/ M6/27/ Taman Galaxy/ Bekasi', '02182751236', '08159653394/081514577689', 't.jombang@gmail.com', 'Tedi Jombang Nugraha', 'S1', 'Karyawan swasta', 'Padang/ 31 Maret 1979', 'Pipit Anasthasia', 'S1', 'IRT', 'P.Berandan/2 November 1978'),
('TK0180', '202101003', '202101003', 'AISYAH KHUMAIRA PUTRI APSANRA', 'Bukittinggi', '2018-03-07', 'P', 'TKBLULUS', NULL, '2021', 'AISYAH', 'K2 - Al Ma\'arij', '17 kg', '98 cm', 'L', 'Komplek Depnaker Taman Wisma Asri Jalan Merak I Blok K27 No. 12A RT 02/ RW 34 Teluk Pucung Bekasi Utara 17121', '081386825912', '81386608132', 'amieamo3@gmail.com', 'Ikhsan Pramana', 'S1', 'karyawan', 'Padang/9 Juni 1984', 'Rahmi Hayati', 'D4', 'IRT', 'Bukti tinggi/23 July 1987'),
('TK0181', '202101004', '202101004', 'AIZZAH NUR NAZHIFAH', 'Jakarta', '2018-05-10', 'P', 'TKBLULUS', NULL, '2021', 'AIZZAH', 'K2- Al Mursalat', '15 kg', '0', 'L', 'Jl. H Samud No.26/95 / Jati Kramat/ Bekasi', '0', '081292594004/081310405709', 'buncitiwan@gmail.com', 'Muhammad Ridwan', 'S1', 'Karyawan swasta', 'Sukabumi/ 27 Juni 1992', 'Mardhiah Fatwa', 'S1', 'IRT', 'Jakarta/ 24 Oktober 1993'),
('TK0182', '202101005', '202101005', 'ALMEERA SYAHIRA DISTY', 'Jakarta', '2017-03-11', 'P', 'TKBLULUS', NULL, '2021', 'MEERA', 'K2 - Al Qalam', '0', '0', 'S', 'Jl. H umar no.9 jakamulya/cikunir/ bekasi selatan', '021-827-44061', '81289865344', 'septy3023@gmail.com', 'Dimas pramudita (Alm)', 'S1', '0', 'Jakarta/ 1 Maret 1984', 'Septy haryani', 'D3', 'IRT', 'Jakarta/ 15 Oktober 1985'),
('TK0183', '202101006', '202101006', 'AQEELA CEISYA HAFIZHAH ARIEF', 'Jakarta', '2019-04-09', 'P', 'TKBLULUS', NULL, '2021', 'CEISYA', 'K2 - Al Insan', '13 kg', '90 cm', 'S', 'Jakarta Garden City/ Cluster Yarra E8/5', '08111995030', '082299966651', 'muh.arieff7@gmail.com', 'Muhammad Arief', 'S1', 'Karyawan swasta', 'Jakarta/ 25 July 1984', 'Irma suryani', 'S1', 'Karyawan swasta', 'Jakarta/ 12 November 1989'),
('TK0185', '202101008', '202101008', 'ARSYILA GHAIDA AZKADINA', 'Jakarta', '2018-06-05', 'P', 'TKBLULUS', NULL, '2021', 'ARSYILA', 'K2 - Al Ma\'arij', '15/5 kg', '101 cm', 'M', 'Jl taman agave V/ M6/27/ Taman Galaxy/ Bekasi', '02182751236', '08159653394/081514577689', 't.jombang@gmail.com', 'Tedi Jombang Nugraha', 'S1', 'Karyawan swasta', 'Padang/ 31 Maret 1979', 'Pipit Anasthasia', 'S1', 'IRT', 'P.Berandan/2 November 1978'),
('TK0186', '202101009', '202101009', 'ASHALINA MAUZA NAFEESA ASSAD', 'Bekasi', '2018-03-12', 'P', 'TKBLULUS', NULL, '2021', 'ASHA', 'K2 - Al Ma\'arij', '14/5 kg', '97 cm', 'M', 'JL. CENDANA RAYA NO.16/ JAKA PERMAI/ BEKASI BARAT', '0', '08118818787/081310070612', 'muh.assad@gmail.com', 'MUHAMMAD ASSAD', 'S2', 'Pengusaha/motivator', 'Jakarta/ 16 Januari 1987', 'AFRA NURINA AMARILLA', 'S2', 'IRT', 'Tarakan/ 6 Januari 1988'),
('TK0187', '202101010', '202101010', 'AZKARIAN FADILLAH', 'Jakarta', '2017-05-07', 'L', 'TKBLULUS', NULL, '2021', 'AZKA', 'K2 - Al Insan', '12/5 kg', '0', 'M', 'jl.greenview 3 blok D 67 the greenview PTM', '0', '081380008431/082111194998', 'ana.oktora@gmail.com', 'Deny Maturian', 'S1', 'Wiraswasta', 'Pekanbaru/30 Maret 1984', 'AnaOktora', 'S2', 'IRT', 'Solok/ 7 Oktober 1982'),
('TK0188', '202101011', '202101011', 'AZKA TSAQIF ALBANI', 'Jakarta', '2018-06-09', 'L', 'TKBLULUS', NULL, '2021', 'AZKA', 'K2 - Al Insan', '13/1 kg', '93 cm', 'S', 'JL. H. HANAFI GG. ISLAH III RT 0013 RW 02 NO 11 KEL. PONDOK BAMBU KEC. DUREN SAWIT', '0', '081315902512/085781550433', 'm_fitria@yahoo.com', 'AGUS SAHBANI', 'S1', 'Karyawan swasta', 'Jakarta/ 2 July 1977', 'MARYAM FITRIYAH', 'S1', 'IRT', 'Tegal/ 5 July 1984'),
('TK0189', '202101012', '202101012', 'BIMANTARA KHALIF IBRAHIM', 'Bekasi', '2019-08-01', 'L', 'TKBLULUS', NULL, '2021', 'BIMA', 'K2 - Al Insan', '0', '0', 'L', 'Perumahan Villa Pekayon Blok A3 no 12', '08122227520', '081393392811', 'rahman_alif86@gmail.com', 'Alifia Rahman', 'S1', 'Karyawan swasta', 'Jember/11 maret 1986', 'ajeng illastria r.', 'S1', 'PNS', 'Mojokerto/20 Januari 1987'),
('TK0192', '202101015', '202101015', 'IBRAHIM ABDULLAH MALASSA', 'Bekasi', '2019-01-04', 'L', 'TKBLULUS', NULL, '2021', 'IBRAHIM', 'K2 - Al Ma\'arij', '15 kg', '90 cm', 'M', 'Pondok pekayon indah jalan mahoni 1 B14 no 7', '0', '081285180555/082117756606', 'endy.malassa@yahoo.com', 'Endy malassa', 'SMA', 'Pegawai BUMN', 'Jakarta/ 9 July 1992', 'Nisa sofia', 'D3', 'IRT', 'Bandar Lampung/7 Januari 1991'),
('TK0193', '202101016', '202101016', 'KAUTSAR', 'Jakarta', '2018-12-01', 'L', 'TKBLULUS', NULL, '2021', 'KAUTSAR', 'K2 - Al Ma\'arij', '11/5 kg', '90 cm', 'S', 'Apartemen Sentra Timur/ Jl. Sentra Primer Timur/ Pulo Gebang/ Cakung/ Jakarta Timur', '0', '082125760320/081912757072', 'harimansadewa@gmail.com', 'Hariman Sadewa', 'S1', 'Karyawan swasta', 'Jakarta/16 April 1992', 'Aisyah Nasiri', 'S1', 'IRT', 'Ponorogo/ 12 Agustus 1991'),
('TK0194', '202101017', '202101017', 'KEN RASKI DAMARIO', 'Bekasi', '2018-07-04', 'L', 'TKBLULUS', NULL, '2021', 'KEN', 'K2 - Al Insan', '11/5 kg', '75 cm', 'S', 'Jl Rawa Semut 2 no 46 RT02 RW13 Jati Asih Bekasi', '0', '082124932313/08128049320', 'ani.kenraski@gmail.com', 'Wahyu Novianto Kristiono', 'S1', 'PNS', 'Jakarta/15 November 1980', 'Ani Indri Astuti', 'S1', 'karyawan BUMN', 'Jakarta/ 17 Januari 1980'),
('TK0195', '202101018', '202101018', 'KHAIREEN HAFIZA SULTANSYAH', 'Bekasi', '2018-02-04', 'P', 'TKBLULUS', NULL, '2021', 'KHAIREEN', 'K2 - Al Qalam', '0', '0', 'S', 'jl. wahab 2 no.63a/ jatibening baru pondok gede bekasi', '081905501800', '082213606865', 'rizkiturki@gmail.com', 'RIZKI APRIANSYAH', 'S1', 'Guru bahasa Turki', 'Jakarta/17 April 1992', 'DHEANITA TRIBUANA', 'S1', 'IRT', 'Jakarta/ 4 Agustus 1991'),
('TK0198', '202101021', '202101021', 'MUHAMMAD AL FATIH SETIADI', 'Bekasi', '2018-05-04', 'L', 'TKBLULUS', NULL, '2021', 'AL FATIH', 'K2 - Al Ma\'arij', '12 kg', '92 cm', 'S', 'Jl Niaga 9 Blok BE no 17 Kemang Pratama Bekasi 17114', '021-82741800', '0811811690/08118111139', 'adi.setiadi217@gmail.com', 'Adi Setiadi', 'MM', 'Karyawan swasta', 'Sintang/24 september 1976', 'Lany Marliany', 'Apoteker', 'IRT', 'Bandung/ 27 Mei 1976'),
('TK0199', '202101022', '202101022', 'MUHAMMAD ATTHALLAH ALGHIFARY', 'Bekasi', '2018-07-08', 'L', 'TKBLULUS', NULL, '2021', 'ATTHALLAH', 'K2 - Al Qalam', '15 kg', '0', 'M', 'jl kemakmuran 3 nomor 58 magrjaya bekasi selatan 17141', '0', '081316249544', 'regina14.ra@gmail.com', 'danang triatmojo', 'S1', 'Karyawan swasta', 'Gunung kidul/23 Januari 1991', 'Regina Ayu', 'S1', 'IRT', 'Bandung/14 Maret 1991'),
('TK0200', '202101023', '202101023', 'MUHAMMAD FAQIH ALMA MUQODDAM', 'Bekasi', '2019-03-11', 'L', 'TKBLULUS', NULL, '2021', 'FAQIH', 'K2 - Al Insan', '16 kg', '96 cm', 'M', 'Jl Pelabuhan Ratu no.4 Kel Sepanjang Jaya Kec Rawalumbu Kota Bekasi', '0218217725', '081293555354', 'drgemavikossa@gmail.com', 'Gema Vikossa', 'S1', 'dokter', 'jakarta/24 Februari 1985', 'Aliza Ramadhani Putriana', 'S1', 'dokter', 'Indramayu/ 1 Mei 1988'),
('TK0201', '202101024', '202101024', 'MUHAMMAD LUQMAN SALIM', 'Klaten', '2019-12-11', 'L', 'TKBLULUS', NULL, '2021', 'LUQMAN', 'K2- Al Mursalat', '13 kg', '89 cm', 'M', 'JL.ARJUNA 4 NO.139 B RT/RW 3/11 JAKASETIA', '0', '085725846633/087882840928', 'agus38salim@gmail.com', 'AGUS SALIM', 'SMA', 'Karyawan swasta', 'Purworejo/ 3 Agustus 1987', 'NURSYAM BUDI LISTYOWATI', 'D3', 'IRT', 'Klaten/20 Maret 1991'),
('TK0202', '202101025', '202101025', 'MUHAMMAD SALMAN ALFATIH', 'Bekasi', '2019-04-10', 'L', 'TKBLULUS', NULL, '2021', 'SALMAN', 'K2 - Al Ma\'arij', '14/5 kg', '105 cm', 'L', 'Jl. Pulo Sirih Barat 4/ FE 350', '0', '081287807848/082220333255', 'haryotetuko1988@gmail.com', 'HARYO TETUKO', 'S1', 'Wiraswasta', 'Tanjung enim/16 April 1988', 'NURAVER KEELA', 'S1', 'wiraswasta', 'Bandung/20 November 1987'),
('TK0203', '202101026', '202101026', 'MUHAMMAD THAQI ARAFAH', 'Bekasi', '2018-05-08', 'L', 'TKBLULUS', NULL, '2021', 'THAQI', 'K2 - Al Qalam', '18 kg', '115 cm', 'S', 'pondok pekayon indah blok B3 no/10', '0', '081281482210', 'wisnuutomo@gmail.com', 'Wisnu Broto Utomo', 'S1', 'Wiraswasta', 'Jakarta/15 Juni 1980', 'Levana Pauliana', 'S1', 'IRT', 'Garut/ 17 Mei 1986'),
('TK0204', '202101027', '202101027', 'NADIEM BENYAMIN', 'Bekasi', '2019-06-01', 'L', 'TKBLULUS', NULL, '2021', 'NADIEM', 'K2 - Al Ma\'arij', '14 kg', '95 cm', 'M', 'Perumahan Pondok Timur Mas/ Jl. Pondok Jingga Mas 7 blok R3 no 11 Galaxy Bekasi Selatan 17147', '0', '0811222977/0811522133', 'bimayog@gmail.com', 'Bima Yogie Purnama', 'S2', 'Notaris', 'Bekasi/15 Desember 1990', 'Feny Ambarsari', 'S1', 'IRT', 'Bekasi/2 Maret 1991'),
('TK0205', '202101028', '202101028', 'NAHLA JIHAN ALFIYYAH', 'Bekasi', '2019-01-01', 'P', 'TKBLULUS', NULL, '2021', 'JIHAN', 'K2- Al Mursalat', '12 kg', '92 cm', 'S', 'Jl. Alam utama V Perum Bintara Alam Permai Blok D1 no.10 Rt.03/14 kel. Bintara jaya/ bekasi barat', '0', '081286108020/08111221177', 'ferdin.ramdhani@gmail.com', 'Ferdin Amsal Ramdhani', 'S1', 'PNS', 'Jakarta/28 mei 1986', 'Mutia Dwi Rohmiatun', 'S1', 'IRT', 'Pandeglang/8 Juni 1987'),
('TK0207', '202101030', '202101030', 'RAYYAN AHSANI RUSYDAN', 'Jakarta', '2019-04-12', 'L', 'TKBLULUS', NULL, '2021', 'RAYYAN', 'K2 - Al Qalam', '13 kg', '90 cm', 'S', 'CLUSTER TAMAN FIRDAUSI NO 3 JALAN KEMANGSARI 1/ Kel JATIBENING BARU/ Kec PONDOK GEDE KOTA BEKASI', '081288636347', '081311502036', 'indahsusan28216@gmail.com', 'DARYATNO SUBEKTI', 'S1', 'PNS', 'Mataram/ 17 Agustus 1982', 'INDAH SUSANTI', 'S1', 'IRT', 'Magelang/23 Desember 1990'),
('TK0209', '202101032', '202101032', 'SUTAN AVERROES ZAHRI', 'Bekasi', '2019-06-10', 'L', 'TKBLULUS', NULL, '2021', 'AVERROES', 'K2 - Al Insan', '14 kg', '100 cm', 'S', 'Komplek Pondok Timur Mas/ Jl Jingga Mas IV blok E 2 no 23/ Jaka Setia/ Kota Bekasi', '0', '081220443738/082214666011', 'bentom002jk@gmail.com', 'Berlianto Haris', 'S2', 'Karyawan swasta', 'Padang/26 Mei 1985', 'Sukma Faizah', 'S1', 'IRT', 'Medan/4 Desember 1989'),
('TK0211', '202101034', '202101034', 'UWAIS ABQARIZAYAN KINAYUNG', 'Bekasi', '2017-10-07', 'L', 'TKBLULUS', NULL, '2021', 'UWAIS', 'K2- Al Mursalat', '15/5 kg', '102 cm', 'M', 'Jalan Pelabuhan Ratu No.56 Perum.Bumi Bekasi Baru Rawalumbu Kota Bekasi 17115', '0', '081284060054/081290731554', 'wikrama.ananta@gmail.com', 'Anantawikrama Unggul Kinayung', 'S1', 'Wiraswasta', 'Semarang/12 Maret 1987', 'Rachmayanti Nur Padilah', 'S1', 'IRT', 'Bekasi/24 Juni 1963'),
('TK0241', '202101064', '202101064', 'MALIQA RIZHANI HANANDITA', 'Bekasi', '2018-03-09', 'p', 'TKBLULUS', NULL, '2021', 'IZZA', 'K2- Al Mursalat', '14/2 kg', '0', 'S', 'Pondok Ungu Permai Blok KK 2 no 2/ Bekasi Utara 17125', '0', '08175453618/081286867200', 'dimasanggoro21@gmail.com', 'Dimas Anggoro Hanandito', 'S1', 'Karyawan swasta', 'Jakarta/21 Juli 1986', 'Rizky Amelia', 'S1', 'Karyawan swasta', 'Ambon/9 Mei 1990'),
('TK0242', '202101065', '202101065', 'ATHOULLAH HAFIZH AFANDY', 'Jakarta', '2018-03-02', 'L', 'TKBLULUS', NULL, '2021', 'ATHA', 'K2 - Al Qalam', '13/5 kg', '0', 'S', 'Jl. Rawa selatan buntu no.17 Rt.018/04. Kel.kampung Rawa. Kec.Johar baru. Jakpus 10550', '08119825119', '081291999068', 'reza.afandy@gmail.com', 'Reza Afandy Bustaman', 'S2', 'Akuntan', 'Samarinda/1 Februari 1977', 'Maya Indah purwati', 'S1', 'IRT', 'Jakarta/28 Mei 1983'),
('TK0243', '202101066', '202101066', 'MUHAMMAD ARSHAD UWAIS HILABI', 'Jakarta', '2018-12-05', 'L', 'TKBLULUS', NULL, '2021', 'UWAIS', 'K2 - Al Ma\'arij', '15 kg', '101 cm', 'M', 'Jl. Lembah Pinang 3 Blok i10 No.12/ Pondok Kelapa/ Duren Sawit/ Jakarta Timur', '02122845436', '081312206891/081224037770', 'hilman.ismail20@gmail.com', 'Hilman Ismail Hilabi', 'S1', 'Karyawan swasta', 'Jakarta/20 April 1991', 'Raifa Dwinanti', 'S1', 'IRT', 'Bandung/9 Januari 1991'),
('TK0244', '202101067', '202101067', 'KALILA KHANSAIRA', 'Jakarta', '2018-06-10', 'P', 'TKBLULUS', NULL, '2021', 'KALILA', 'K2- Al Mursalat', '11 kg', '88 cm', 'S', 'Jl. Kutilang 2 Blok G9 No.4/ Jatibening Pondok Gede/ Bekasi - 17412', '0', '081381488100/081212797092', 'rizki.fajar88@gmail.com', 'Muhamad Rizki Fajar', 'S1', 'Karyawan swasta', 'Jakarta/8 Agustus 1990', 'Annisa Anggraini', 'S1', 'IRT', 'Jakarta/10 Oktober 1992'),
('TK0245', '202101068', '202101068', 'UWAIS HAMIZAN SYAFIQ', 'Bekasi', '2017-10-09', 'L', 'TKBLULUS', NULL, '2021', 'UWAIS', 'K2 - Al Insan', '10 kg', '0', 'S', 'Jl Gurame V no 317 Perumnas II Bekasi', '0', '081112900093/081283070084', 'hennythantawi190677@gmail.com', 'Ruhimat', 'SMA', 'Guru', 'Sumedang/24 April 1980', 'Hilda', 'S1', 'Guru', 'Jakarta/14 Desember 1983'),
('TK0246', '202101069', '202101069', 'ALVARO RAFAIZAN RAHMAN', 'Tanggerang Selatan', '2017-02-12', 'L', 'TKBLULUS', NULL, '2021', 'VARO', 'K2 - Al Ma\'arij', '13/8 kg', '95 cm', 'S', 'Komplek Bintara Jaya 2 Jalan Anjasmoro Blok A 85D/ Bekasi', '0', '081319855141/081291427649', 'andirahman81@gmail.com', 'Andi Rahman Nugraha', 'S2', 'Wiraswasta', 'Jakarta/10 Agustus 1991', 'Lia Khairunisa', 'S1', 'wiraswasta', 'Jakarta/11 Juni 1990'),
('TK0251', '202101074', '202101074', 'AKHDAN ZIYAD AZHAR', 'Garut', '2019-03-02', 'L', 'TKBLULUS', NULL, '2021', 'ZIYAD', 'K2 - Al Qalam', '16 kg', '103 cm', 'M', 'jalan siliwangi raya/no 123 sepanjang raya/ rawalumbu/ kota BEKSI 17114', '0', '082218529709', 'zaharsofni29@gmail.com', 'Azyad Azhar (alm)', '0', '0', 'Garut/ 5 Agustus 1991', 'Rizky Ayu Akbari', 'S1', 'IRT', 'Garut/ 28 Oktober 1992'),
('TK0253', '202101076', '202101076', 'KAMILA AMANI', 'Bekasi', '2018-11-08', 'P', 'TKBLULUS', NULL, '2021', 'KAMILA', 'K2 - Al Insan', '18 kg', '105 cm', 'XL', 'Jl. Delta Barat VII no B52 Pekayon - Bekasi Selatan', '0', '081290439802/087886888080', 'farhana.hkm@gmail.com', 'Afan Miqdad', 'S1', 'Pegawai swasta', 'Cilacap/19 November 1985', 'Farhanah', 'D3', 'IRT', 'Jakarta/16 Februari 1993'),
('TK0254', '202101077', '202101077', 'ARKANA HANIF SHAGUFTA', 'Bekasi', '2018-10-07', 'L', 'TKBLULUS', NULL, '2021', 'ARKA', 'K2 - Al Qalam', '15 kg', '99 cm', 'M', 'Jln. Merpati Pos A24 RT 02/09 Jakamulya Bekasi Selatan 17146', '0', '08568548410/085716226333', 'fahdarta@gmail.com', 'Fahmi Arie Sidharta', 'S1', 'Pegawai swasta', 'Jakarta/ 21 November 1978', 'Ranty Carolyna', 'SLTA', 'IRT', 'Bandung/ 9 Februari 1983'),
('TK0260', '202201001', '202201001', 'ARUNA NADYA PRAMUDHITA', 'Bekasi', '2019-02-11', 'P', 'TKB', 10, '2022', 'ARUNA', 'K1 - Al Buruj', '12/4 kg', '93 cm', NULL, 'Perum. Angkasa Puri/ Jl. Durian Blok J No.2/ Kel. Jatimekar/ Kec. Jatiasih/ Kota Bekasi/ Jawa Barat 17422', '021 8474851', '08156635500', 'brahmapramudita@gmail.com', 'Brahma Swastika Pramudhita ', 'S1', 'Karyawan swasta', 'Jakarta/ 27-0301989', 'Hanna Khairinisa', 'S1', 'IRT', 'Bandung/ 14-12-1991'),
('TK0261', '202201002', '202201002', 'MUHAMMAD ARKHAM AKHYAR', 'Surabaya', '2018-02-06', 'L', 'TKB', 12, '2022', 'ARKHAM', 'K1 - Al Fajr', '14/10 kg', '98 cm', NULL, ' Pondok Timur Mas Jl Pondok Merah Mas Blok C1/7', '-', '0811321677', 'mohamad.zuhroni@gmail.com', 'Mohamad Zuhroni ', 'S2', 'Pegawai Swasta', 'Surabaya/19-12-1981', 'Vidya Antariksani', 'S1', 'IRT', 'Jakarta/ 03-09-1984'),
('TK0264', '202201005', '202201005', 'ALFARIZKY GHAYDA TAHER', 'Bekasi', '2018-03-11', 'L', 'TKB', 10, '2022', 'FARIZ', 'K1 - Al Fajr', '12/6 kg', '-', NULL, ' Jalan pondok kuning mas 1 blok p no.3 perumahan pondok timur mas', '-', '082122669198', 'ade347@gmail.com', 'Isbudi ', 'D3', 'Karyawan swasta', 'Jakarta/ 18-10-1988', 'Ade Kusumawardani', 'S1', 'IRT', 'Dompu/ 25-08-1988'),
('TK0266', '202201007', '202201007', 'MUHAMMAD ZIANDRA HANANDITO', 'Bekasi', '2018-08-10', 'L', 'TKB', 11, '2022', 'ZIAN', 'K1 -Ath Thoriq', '15/5 kg', '90 cm', NULL, ' Pondok Ungu Permai Blok KK 2 no 2 Bekasi Utara 17125', '-', '08175453618', 'dimasanggoro21@gmail.com', 'Dimas Anggoro Hanandito ', 'S1', 'Karyawan swasta', 'Jakarta/ 21-07-1986', 'Rizky Amelia', 'S1', 'Karyawan swasta', 'Ambon/ 09-05-1990'),
('TK0267', '202201008', '202201008', 'SHAFIYA INSYIRA NAZURA', 'Bekasi', '2019-08-04', 'P', 'TKB', 10, '2022', 'FIYA', 'K1 - Al Fajr', '16 kg', '90 cm', NULL, ' Perumahan Villa Pekayon A3 No 4/ Jl. Katapang/ Pekayon Jaya Bekasi Selatan', '-', '085860066027', 'dithianefara@gmail.com', 'Zulkifli ', 'S1', 'Karyawan swasta', 'Jakarta/ 13-10-1982', 'Fara Fadila', 'D3', 'Karyawan Swasta', 'Bandung/ 28-07-1982'),
('TK0269', '202201010', '202201010', 'MUHAMMAD MAHARDIKA CENDEKIA', 'Jakarta', '2020-06-06', 'L', 'TKB', 9, '2022', 'MUHAMMAD', 'K1 - Al Buruj', '13 kg', '98 cm', NULL, ' CLUSTER TAMAN FIRDAUSI NO. 2/ JATIBENING', '-', '081382140823', 'cendekia.k@gmail.com', ' Cendekia Putra Kartono  ', 'S1', 'Karyawan yayasan', 'Bekasi/ 16-12-1990', 'Detta Olyvia Nirwana', 'S1', 'IRT/freelancer script editor', 'Jakarta/ 16-09-1988'),
('TK0270', '202201011', '202201011', 'OMAR ZANKI ABDURRAHMAN', 'Bekasi', '2020-03-07', 'L', 'TKB', 12, '2022', 'OMAR', 'K1 - Al Fajr', '12/5 kg', '92 cm', NULL, ' kav DKI jalan Lembah Aren X blok K16 no 21 Pondok Kelapa/ Jakarta Timur', NULL, '08121043828', 'adhibr@yahoo.com', ' Adhib Rakhmanto ', 'S1', 'Consultant', 'Kulon Progo/10-09-1987', 'Sefty Kurnadia Weny', 'S1', 'IRT', 'Jakarta/ 12-02-1986'),
('TK0271', '202201012', '202201012', 'ALECIA NAVISHA WIBOWO', 'Surabaya', '2018-11-05', 'P', 'TKB', 12, '2022', 'NAVISHA', 'K1 -Ath Thoriq', '13/4 kg', '98 cm', NULL, ' Taman Century 2 Jl. Kemuning 1 Blok G. 11 Rt 005 Rw 023 Kel. Pekayon Jaya Kec. Bekasi Selatan 17148 Kota Bekasi', '-', '08122908810', 'Mochagungw@gmail.com', ' Moch Agung Wibowo', 'D3', 'Karyawan swasta', 'Semarang/ 16-07-1978', 'Dede Sholihat', 'SMK', 'IRT', 'Ciamis/ 02-11-1992'),
('TK0272', '202201013', '202201013', 'MUHAMMAD ALWI AL HAZMI', 'Jakarta', '2018-11-07', 'L', 'TKB', 9, '2022', 'ALWI', 'K1 - Al Buruj', '12/6 kg', '94 cm', NULL, 'Cluster Griya Mandiri No 6/ Jatiasih', '-', '081286007072', 'friska.melinda.ui@gmail.com', ' Fadjar Rizky Wahyu Ramadhan ', 'S2', 'Karyawan swasta', 'Bekasi/ 25-03-1992', 'Friska Melinda Rizqi', 'S1', 'IRT', 'Magelang/ 22-05-1990'),
('TK0273', '202201014', '202201014', 'MAHREEN ZEEYA JOBAN', 'Jakarta', '2018-11-09', 'P', 'TKB', 9, '2022', 'ZEEYA', 'K1 -Ath Thoriq', '12 kg', '94 cm', NULL, ' Pondok Timur Mas', '-', '082111666266', 'evayulyanti3@gmail.com', ' Riza Wilhansah', 'S1', 'Pegawai', 'Jakarta/ 18-10-1990', ' Eva', 'S1', 'IRT', 'Jakarta/ 03-07-1987'),
('TK0276', '202201017', '202201017', 'MUHAMMAD ABDULLAH AMALI', NULL, '2021-05-05', 'L', 'TKB', 9, '2022', 'AHMAD', 'K1 - Al Buruj', NULL, NULL, NULL, '[NULL]', NULL, NULL, NULL, 'Ustadz Adi Hidayat/ Lc/ M.A', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('TK0277', '202201018', '202201018', 'ALMAHYRA KIANNAYU PRAMESTI ANWAR', 'Bekasi', '2020-06-12', 'P', 'TKB', 10, '2022', 'KINAY', 'K1 -Ath Thoriq', '14/65 kg', '98 cm', NULL, ' The Green View blok C no.19/ Jakasetia/ Bekasi Selatan/ Bekasi 17147', '021 82735730', '085218843520', 'wkem.anwr@yahoo.com', ' Anwar Sadat', 'SMA', 'Wiraswasta', 'Sukabumi/ 01-07-1970', ' Irama Dewi Cahyawulan Hamzah', 'D3', 'wiraswasta', 'Bandung/ 12-07-1982'),
('TK0279', '202201020', '202201020', 'QARIZH AFKARI NADHIF', 'Jakarta', '2019-11-01', 'L', 'TKB', 11, '2022', 'QARIZH', 'K1 - Al Balad', '16 kg', '98 cm', NULL, ' Jl Cendana IV no 18 Perumahan Jaka Permai/ RT05 RW 06A/ Kel Jakasampurna/ Bekasi Barat', '-', '082299992553', 'erlanggaperwiranegara@gmail.com', ' Erlangga Perwira Negara ', 'S1', 'Dokter', 'Bekasi/ 27-04-1991', 'Indah Dwi Rahamah', 'S1', 'IRT', 'Way Jepara/ 17-09-1990'),
('TK0280', '202201021', '202201021', 'KANISA NUR HAFIDZAH', 'Bekasi', '2019-10-09', 'P', 'TKB', 11, '2022', 'KANIS', 'K1 -Ath Thoriq', '13 kg', '80 cm', NULL, 'Villa Jakasetia Blok A No.5', '-', '089661796021', 'faqihsentosa@mail.com', 'Rachmad  Sentosa', 'D3', 'Wiraswasta', 'Jakarta/ 01-01-1987', 'Nur Aini ', 'SMA', 'IRT', 'Jakarta/ 10-08-1988'),
('TK0281', '202201022', '202201022', 'MUHAMMAD HIRO GHAFFARI', 'Bekasi', '2019-05-05', 'L', 'TKB', 9, '2022', 'HIRO', 'K1 -Ath Thoriq', '17 kg', '100 cm', NULL, ' The Green View Blok D-32', '021 82732682', '085664533254', 'suci.arsae@gmail.com', ' Hendra Saputra', 'S1', 'Karyawan swasta', 'Sungai Kayu Ara/ 13-03-1993', ' Suci Leowati', 'S1', 'IRT', 'Boyolali/ 04-09-1991'),
('TK0283', '202201024', '202201024', 'SHAQUEENA AURORA', 'Bekasi', '2018-10-09', 'P', 'TKB', 11, '2022', 'QUEENA', 'K1 - Al Buruj', '13 kg', '93/5 cm', NULL, 'Jl. Ketapang V Pondok Pekayon Indah DD 39 no 1 Bekasi Selatan', '021 8205822', '08558088889', 'rdtychptr@gmail.com', ' Raditiya Cahya Putra ', 'S1', 'Karyawan swasta', 'Jakarta/ 10-09-1990', 'Sari Dahliani', 'S1', 'Karyawan Swasta', 'Cirebon/06-09-1988'),
('TK0284', '202201025', '202201025', 'AZKAYRA ZAHRA MAHENDRA', 'Jakarta', '2018-02-06', 'P', 'TKB', 11, '2022', 'AYRA', 'K1 -Ath Thoriq', '14 kg', '100 cm', NULL, ' Cluster Kiana Bintara Blok A3', '-', '085647282820', 'mahendra.david@gmail.com', ' David Mahendra', 'D4', 'Pegawai Swasta', 'Boyolali/ 12-12-1988', ' Robiatul Kamelia', 'D4', 'IRT', 'Bangkalan/ 20-07-1989'),
('TK0285', '202201026', '202201026', 'ADEEVA RIZKIYYA WAHYUDI', 'Bekasi', '2019-02-07', 'P', 'TKB', 10, '2022', 'ADE', 'K1 - Al Balad', '12 kg', '93 cm', NULL, ' Cipta view regency blok C 6-7 harapan jaya bekasi', '-', '08121990649', 'erwinwahyudi82@gmail.com', 'Erwin Wahyudi ', 'S1', 'Wirausaha', 'Jakarta/ 03-03-1981', 'Riska Yudianti', 'Kedokteran umum', 'Dokter', 'Jakarta/ 06-05-1981'),
('TK0286', '202201027', '202201027', 'MUHAMMAD BATTUTA AFNANSYAH', 'Bekasi', '2018-10-12', 'L', 'TKB', 10, '2022', 'BATUTA', 'K1 -Ath Thoriq', '-', '-', NULL, ' Kp. Pamahan RT 01/09 No. 22 Kelurahan Jatimekar Kecamatan Jatiasih', '021 84996017', '085692729707', 'muhammadfirmansyah77@gmail.com', ' Muhammad Firmansyah', 'S1', 'D3', 'Bekasi/ 23-07-1990', ' Ratna Ayu Purwasih', 'S1', 'IRT', 'Palembang/ 16-09-1990'),
('TK0288', '202201029', '202201029', 'SHAHNAZ ELEANOR MAESAROH', 'Jakarta', '2019-01-04', 'P', 'TKB', 12, '2022', 'SHAHNAZ', 'K1 - Al Fajr', '15 kg', '-', NULL, ' Perumahan taman galaxy jalan cendana III blok P III no 8 jakasetia bekasi selatan', '-', '089610085997', 'bagus.inaport4@gmail.com', 'Bagus Purwanto ', 'S2', 'Pengacara', 'Magelang/ 15-07-1983', 'Kiki Meishara', 'S1', 'IRT', 'Pematangsiantar/ 17-05-1985'),
('TK0289', '202201030', '202201030', 'SAYYIDAH ALESHA NADA', 'Bekasi', '2018-02-02', 'P', 'TKB', 11, '2022', 'ALESHA', 'K1 -Ath Thoriq', '18 kg', '90 cm', NULL, ' Komplek persada kemala blok 17 no. 4-5 jakasampurna/ bekasi barat 17145', '-', '085716427723', 'bhil2.aul54@gmail.com', 'Muhamad Ramadani ', 'S1', 'Karyawan swasta', 'Jakarta/ 25-04-1990', 'Nabila Nur Aulia', 'S1', 'Karyawan swasta', 'Louisiana/ 30-11-1992'),
('TK0290', '202201031', '202201031', 'NAJMA HASKA KAYLA', 'Surakarta', '2019-09-02', 'P', 'TKB', 9, '2022', 'HASKA', 'K1 - Al Buruj', '13 kg', '96 cm', NULL, ' Familia Urban - Ganesha BG 57/ Mustika Sari/ Mustika Jaya/ Bekasi', '-', '082115859616', 'himawan.nurkahfianto@gmail.com', 'Himawan Nurkahfianto', 'S1', 'Karyawan swasta', 'Bekasi/ 12-03-1990', 'Ratih Anggraeny', 'S1', 'IRT', 'Gresik/ 27-08-1989'),
('TK0291', '202201032', '202201032', 'SHAQUEENA ELFIRA AWBINA', 'Bekasi', '2019-04-01', 'P', 'TKB', 9, '2022', 'SHAQUEENA', 'K1 - Al Balad', '15 kg', '80 cm', NULL, ' Taman Galaxy. Jl. Pulo sirih utara dalam 8 DA 169', '021 8206637', '08561070176', 'nailahumammy13@gmail.com', 'Bimo Nugroho ', 'S1', 'Karyawan swasta', 'Jakarta/01-03-1992', 'Nailatul Izza Hummamy', 'S1', 'IRT', 'Bekasi/ 13-06-1994'),
('TK0292', '202201033', '202201033', 'MUHAMMAD HANAN ATTAQI', 'Bekasi', '2018-07-05', 'L', 'TKB', 10, '2022', 'TAQI', 'K1 - Al Balad', '13 kg', '100 cm', NULL, ' Cluster Kiana Bintara blok D no 6', '-', '081510708767', 'hardi.hardiputra@gmail.com', 'Hardiansyah Putra', 'S1', 'Engineer', 'Jakarta/14-08-1989', 'Rhadytia Hanjani', 'S1', 'IRT', 'Jakarta/ 24-04-1989'),
('TK0293', '202201034', '202201034', 'FAYZA ZHAFIRATUL MUNA ISMONO', 'Bekasi', '2019-10-08', 'P', 'TKB', 12, '2022', 'FAYZA', 'K1 - Al Balad', '12/7 kg', '90 cm', NULL, ' Jalan Padang Raya Blok F Nomor 228/ Bekasi Selatan', '-', '081316265159', 'mustika.citra21@gmail.com', 'Indra Verian Ismono', 'S2', 'Karyawan swasta', 'Kudus/ 19-02-1972', 'Citra Ayu Mustika', 'S1', 'Content creator', 'Bandung/ 21-11-1990'),
('TK0294', '202201035', '202201035', 'KEYLA SABILLA MENTARI', 'Bekasi', '2019-09-10', 'P', 'TKB', 12, '2022', 'MENTARI', 'K1 - Al Fajr', '14/5 kg', '90 cm', NULL, ' KRISTAL GARDEN RESIDANCE BLOK G2 NO.2 RT.006/RW.004 CIRIUNG-CIBINONG-KAB. BOGOR JABAR', '021 82402497', '085647851888', 'agungsurya_16@yahoo.com', 'Agung Surya Diyanto ', 'S1', 'PNS', 'Salatiga/ 02-12-1985', 'Rani Puspita Dewi', 'D3', 'PNS', 'Sleman/ 26-05-1989'),
('TK0297', '202201038', '202201038', 'ASKARA LANANG MAHAMERU', 'Jakarta', '2019-01-01', 'L', 'TKB', 11, '2022', 'ASKARA', 'K1 - Al Buruj', '14 kg', '92 cm', NULL, 'Taman Century 2 Jl. Kemuning VI blok M No.27 Pekayon Jaya Bekasi Selatan 17148', '-', '08121572844', 'askaralm01@gmail.com', 'Rhesa Syahrial', 'S1', 'Karyawan Swasta', 'Boyolali/ 07-02-1987', 'Adi Ayu A.P.', 'S1', 'IRT', 'Payakumbuh/ 23-08-1986'),
('TK0298', '202201039', '202201039', 'MUHAMMAD AZZAM KHAIRUDDAROYNI', 'Bekasi', '2020-02-05', 'L', 'TKB', 10, '2022', 'AZZAM', 'K1 - Al Buruj', '15 kg', '102 cm', NULL, 'Pondok Pekayon Indah Blok BB 21 No.11 jalan Pakis 6 C Bekasi selatan', '-', '082110683102', 'indriyanipermatasari.ummunaura@gmail.com', 'Selamet Daroini', 'S1', 'Konsultan lingkungan', 'Pinang Banjar/ 03-09-1975', 'Indriyani Permatasari', 'S2', 'IRT', 'Jakarta/ 29-02-1980'),
('TK0300', '202201041', '202201041', 'AGHNIA MARYAM NOORA PRAFADI', 'Bekasi', '2018-09-07', 'P', 'TKB', 12, '2022', 'AGHNIA', 'K1 - Al Balad', '11 kg', '94 cm', NULL, ' Grand Galaxy City/ Cluster Orchid Garden/ OG 2 no.7/ Jaka Setia/ Bekasi Selatan', '021 82732394', '081311183388', 'pradipta.sura@gmail.com', 'Pradipta Surasebastian', 'S1', 'karyawan swasta', 'Jakarta/ 18-12-1988', 'Fadila Amelia M', 'S2', 'IRT', 'Makassar/ 26-12-1988'),
('TK0301', '202201042', '202201042', 'UBAID HAMIZAN PRIHANDARU', 'Denpasar', '0001-01-01', 'L', 'TKB', 12, '2022', 'UBAID', 'K1 - Al Fajr', '17 kg', '100 cm', NULL, 'Komplek Pemda DKI Blok B 3 No. 19', '-', '08119995062', 'mario.prihandaru@gmail.com', 'Ahmad Mario Prihandaru', 'MM', 'karyawan swasta', 'Jakarta/ 09-03-1990', 'Evy Marcelina', 'S1', 'IRT', 'Jakarta/ 08-01-1992'),
('TK0302', '202201043', '202201043', 'ALFATIH BAHY BHADRIKA', 'Bekasi', '0001-01-01', 'L', 'TKB', 11, '2022', 'ALFATIH', 'K1 -Ath Thoriq', '13 kg', '95 cm', NULL, ' Jl H Anwar Cikunir no.62 Rt.05 Rw.01 Jakamulya Bekasi Selatan Kota Bekasi', '-', '08128761553', 'anggerbagus.ab@gmail.com', 'Sulyanto', 'SLTA', 'Karyawan swasta', 'Sleman/ 19-04-1980', 'Nunung Nursinah', 'SLTA', 'IRT', 'Bekasi/ 15-11-1985'),
('TK0304', '202201045', '202201045', 'MUHAMMAD AZKA DANIYAL ALI', 'Jakarta', '2018-06-04', 'L', 'TKBLULUS', NULL, '2022', 'AZKA', 'K2- Al Mursalat', '19/5 kg', '104 cm', NULL, ' Perumahan Pondok Timur Mas - Jl Pondok Merah Mas II Blok F2 No 16/ Jakasetia', '-', '081802286764', 'dhani_taruna@yahoo.com', ' Dhani Jaya Taruna ', 'S1', 'Karyawan swasta', 'Kotabumi/ 07-07-1983', 'Indri Handayani', 'S1', 'karyawan BUMN', 'Jakarta/ 14-02-1987'),
('TK0305', '202201046', '202201046', 'KHAULAH INARA', 'Bekasi', '2020-06-11', 'P', 'TKB', 12, '2022', 'INARA', 'K1 - Al Balad', '14/2 kg', '97/5 cm', NULL, 'Jalan Nusa Indah VI/ B498/ Kel. Jakasetia/ Bekasi Selatan 17147', NULL, '0811161299 / 0811456434', 'fachturengineering@gmail.com', 'Fachturrizki Ramadhan', 'S1', 'Karyawan swasta', 'Jakarta/ 9 April 1991', 'Chessa Rachmalaputi', 'S2', 'Karyawan swasta', 'Bekasi/ 4 September 1992'),
('TK0306', '202201047', '202201047', 'ABIZAR RIFAT AMIER', 'Bekasi', '2019-02-04', 'L', 'TKB', 12, '2022', 'ABIZAR', 'K1 - Al Balad', '12 kg', '90 cm', NULL, 'Jl Taman Agave II/M3/2 taman Galaxy', NULL, '0817531112 / 0818937879', 'waroengmaksem@gmail.com', 'Rifat Amier', 'S1', 'Wiraswasta', 'Surabaya/29-8-1982', 'Samira Bachmid', 'S1', 'IRT', 'Surabaya/ 25 Desember 1988'),
('TK0308', '202201049', '202201049', 'ARSYILA HANUM RAHMADINA', 'Jakarta', '2018-03-09', 'P', 'TKBLULUS', NULL, '2022', 'HANUM', 'K2 - Al Insan', '14 kg', '90 cm', NULL, 'Perumahan Harapan Baru 1/ Jl. Nangka 4 No. 5 RT 004 RW 005. Kota Baru Bekasi Barat. Bekasi. Jawa Barat. 17133', NULL, '081299366510', 'devihenri@gmail.com', 'Devi Henri Wibowo', 'S1', 'Karyawan swasta', 'Pekalongan/ 26 Oktober 1988', 'Indah Purnamasari Wulanti', 'S1', 'S1', 'Jakarta/ 8 Sept 1988'),
('TK0309', '202201050', '202201050', 'AISHA SHAQUEENA ADZANI', 'Bekasi', '2020-03-11', 'P', 'TKB', 9, '2022', 'AISHA', 'K1 - Al Buruj', '17 kg', '105 cm', NULL, 'The Green View Blok D No. 28', NULL, '087784624484 / 081806706175', 'fadliadzani15051993@gmail.com', 'Fadli Adzani', 'S1', 'Wiraswasta', 'Jakarta/ 15 Mei 1993', 'Hana Triaputri', 'Diploma IV', 'IRT', 'Kupang/ 9 September 1993'),
('TK0310', '202201051', '202201051', 'SALMA ADZKIRA RAWEYAI', 'Bekasi', '2018-08-12', 'P', 'TKB', 10, '2022', 'SALMA', 'K1 - Al Balad', '16 kg', '95 cm', NULL, 'Jl. Kelud Blok A No. 112B RT 007 RW 09 Perumahan Masnaga Jaka Sampurna', NULL, '081218112888 / 081210001882', 'rhonaldraweyai@gmail.com', 'Rhonald', 'SMA', 'Wiraswasta', 'Serui/ 6 September 1974', 'Nini Agororia', 'S1', 'IRT', 'Jakarta/ 21 Agustus 1981'),
('TK0312', '202201052', '202201052', 'KHALISA AZZAHRA ALKIPARO', 'Jakarta', '2019-06-01', 'P', 'TKB', 10, '2022', 'KHALISA', 'K1 - Al Fajr', '14 kg', '93 cm', NULL, 'Jl. Cemara Blok W No 12A/ Taman Galaxy', NULL, '081316548219 / 081281733220', 'alkiparo@gmail.com', 'Kholika Alkiparo', 'S1', 'Wiraswasta', 'Palembang/ 25 Feb 1988', 'Akhlaqul Karimah', 'S1', 'IRT', 'Jakarta/ 7 Des 1991'),
('TK0313', '202201053', '202201053', 'NAFEESHA ALESHA ADNAN', 'Jakarta', '2019-11-04', 'P', 'TKB', 9, '2022', 'NAFEESHA', 'K1 - Al Buruj', '15 kg', '95 cm', NULL, 'Jl akalipa blok c 3 no 16 /kemang pratama 3', NULL, '0817102770 / 081220002770 ', 'nouraesperansza@gmail.com', 'Adnan', 'SMA', 'Wiraswasta', 'Garut/ 13 Feb 1987', 'Noura', 'SMA', 'IRT', 'Pandeglang/ 25 Nov 1991'),
('TK0314', '202201054', '202201054', 'RAYYA ARSYILA HAFIZA', 'Bekasi', '2020-04-02', 'P', 'TKB', 11, '2022', 'RAYYA', 'K1 - Al Balad', '14 kg', '90 cm', NULL, 'Komp.Pulo Permata Sari Blok B5 No 3 Pekayon Jaya/ Bekasi Selatan', NULL, '082217455191 / 081285896381', 'wildanfujiansah@gmail.com', 'Wildan Fujiansah', 'S2', 'PNS', 'Cirebon/ 20 Maret 1986', 'Reti Rohmalia Sari', 'S1', 'Karyawati Swasta', 'Pandeglang/ 17 Maret 1987'),
('TK0315', '202201055', '202201055', 'MUHAMMAD ZAYD ZULKIFLI', 'Jakarta', '2020-02-06', 'L', 'TKBLULUS', NULL, '2022', 'ZAYD', 'K2 - Al Ma\'arij', NULL, NULL, NULL, 'Jl. Nusa Indah Raya blok H-1', '021 8508783', '081318391315 / 081286129259', 'raynugraha@gmail.com', 'Ray Zulham Farras Nugraha', 'S1', 'Wiraswasta', 'Jakarta/ 29 Maret 1993', 'Milka Anisya Norasiya', 'S1', 'dokter', 'Jakarta/ 4 Nov 1991'),
('TK0316', '202201056', '202201056', 'AMIRA AZZAHRA KIMBERLITE', 'Bekasi', '2017-07-11', 'P', 'TKBLULUS', NULL, '2022', 'AMIRA', 'K2- Al Mursalat', '23 kg', '110 cm', NULL, 'Kemang Pratama I JL Utama III Blok BK No 19 RT 007 RW 011/ Kelurahan : Sepanjang Jaya/ Kecamatan : Rawa Lumbu/ Kotamadya Bekasi/ Jawa Barat / Indonesia', '021-82408064', '+628111554564/+62 8111196272', 'onal_05@yahoo.com', 'IR. ONTOWIRYO ALAMSYAH DIPL GEOTH T', 'S2', 'Karyawan swasta', 'YOGYAKARTA/5 Nov 1966', 'VERA MELINDA S.SOS', 'S1', 'wiraswasta', 'Jakarta/ 6 Feb 1972'),
('TK0317', '202201057', '202201057', 'QIANA NAFIAH MUSHABIRA', 'Bekasi', '2017-12-11', 'P', 'TKBLULUS', NULL, '2022', 'QIANA', 'K2- Al Mursalat', '25 kg', '107 cm', NULL, 'Jl. Manggis Raya Blok A-444 RT 08 RW 12/ Perumahan Duren Jaya', NULL, '083892532616', 'fikri77.fm@gmail.com', 'Muhammad Fikri Muzzaki', 'S2', 'Guru SD AIIS', 'HSS/ 22 April 1994', 'Lutfia Niswatul Khasanah', 'D3', 'Okupasi Terapis', 'Jakarta/ 25 Agustus 1992'),
('TK0318', '202301001', '202301001', 'GHUMAISHA ZIDNI ILMA', 'Jakarta', '2019-11-07', 'P', 'TKA', 7, '2023', 'GHUMA', 'KB - At Tiin', '11', '90', NULL, 'Griya kemang raya no. 91 . Jl. Kemang raya. Jaticempaka. Bekasi', NULL, '81281182408', 'dinarputripratiwi@gmail.com', 'Urida zidni ilma', 'S1 - sarjana teknik elektro', 'Swasta', 'Semarang/ 11-Jun-1987', 'Dinar putri pratiwi', 'S1 - pendidikan anak usia dini', 'Irt', 'Palembang/ 22-08-1992'),
('TK0319', '202301002', '202301002', 'TSABINA EMBUN FATHIHA ARIF', 'Bekasi', '2020-06-04', 'P', 'TKA', 5, '2023', 'EMBUN', 'KB - At Tiin', '14', '90', NULL, 'Jalan Jatiluhur Raya 117/ Cluster Rosella Blok Arelian No.6 Rt.2/3 Jakasampurna Berkasi Barat 17145', NULL, '81717756111', 'shirofah89@gmail.com', 'Muhammad Arif Sulaiman', 'S2 - Ilmu Hukum', 'Lawyer', 'Aceh Timur/ 07-Nov-1983', 'Sitti Musyarrafah', 'S1 - Psikologi Pendidikan', 'IRT', 'Jakarta/ 18-01-1989'),
('TK0320', '202301003', '202301003', 'ASIYAH KALILA SAMAH', 'Bekasi', '2020-04-05', 'P', 'TKA', 6, '2023', 'KALILA', 'KB - Al Qadr', '13', '94', NULL, 'Casa alaia residence blok C no 32/ jln bougenville raya RT 1 RW 11 Jakasampurna/ bekasi 17145', NULL, '81297841932', 'mhmmd.bilal24@gmail.com', 'Muhammad Bilal', 'S1 - Akuntansi UI', 'Swasta', 'Jakarta/ 03-Sep-1994', 'ila Wati', 'S1 - Ilmu Ekonomi UI', 'IRT', 'Banyumas/ 02-Sep-1995'),
('TK0321', '202301004', '202301004', 'AISYAH HAURA KARIMAH', 'Jakarta', '2019-01-04', 'P', 'TKA', 5, '2023', 'AISYAH', 'KB - Al \'Alaq', '13kg', NULL, NULL, 'Jl Marzuki 9 rt 05 rw 01 no 42 penggilingan Cakung Jakarta timur', NULL, '87887336292', 'tanyakaromi99@gmail.com', 'Ali karomi', 'Smk', 'Wirausaha', 'Jakarta/ 04-Apr-1991', 'Kamia', 'S1-pendidikan', 'Guru', 'Bekasi/ 06-Jun-1994'),
('TK0322', '202301005', '202301005', 'MUHAMMAD UWAIS AL QARNI', 'Bekasi', '2019-06-10', 'L ', 'TKA', 8, '2023', 'UWAIS', 'KB - Al Qadr', '14', '95', NULL, 'Jl. Cipete Raya No.123 RT/RW : 03/05 (gang kweni) Kelurahan : Mustikasari/ Kecamatan : Mustikajaya/ Kota  Bekasi - 17157', NULL, '81520302710', 'aditya.nugraha1089@gmail.com', 'Muhammad Aditya Nugraha', 'SMK Telkom Shandy putra jakarta', 'Swasta', 'Jakarta/ 10-Oct-1989', 'Andinna Prameswari', 'D3 Akuntansi - Politeknik Pos Indonesia', 'IRT', 'Jakarta/ 06-Oct-1990'),
('TK0323', '202301006', '202301006', 'KAIFEEYA HAFIDZAH MARYAM', 'Bekasi', '2020-05-10', 'P', 'TKA', 6, '2023', 'MARYAM', 'KB - Al Ashr', '12 kg', NULL, NULL, 'Cluster Intan Gardenia No. A4/ Jakasetia/ Bekasi Selatan', NULL, '81210309363', 'feronalizaazis@gmail.com', 'Heru Oktaviana', 'SI - Administrasi Niaga UI', 'Swasta', 'Kuningan/ 10-Apr-1986', 'Ferona Liza', 'S1 / Sarjana Ekonomi STEKPI', 'IRT', 'Jakarta/ 02-Sep-1988'),
('TK0324', '202301007', '202301007', 'HANAN IMAN HABIBURRAHMAN', 'Sukabumi', '2019-03-01', 'L', 'TKA', 5, '2023', 'MAS IMAN', 'KB - Al Ashr', '05-Des', '90', NULL, 'Jln. Pondok Jingga Mas III Blok F5 No 19 Jakasetia/ Kec. Bekasi Selatan Kota Bekasi Jawa Barat 17147', NULL, '87856276326', 'angginalatief@gmail.com', 'HASAN SOFIAN HERNAWAN', 'S1 Teknik Kimia ITB', 'BUMN', 'BANDUNG/ 14-01-1989', 'Anggina Oktapia Latief', 'S1 Psikologi Univ. Ahmad Dahlan', 'IRT', 'Sukabumi/ 15-10-1987'),
('TK0325', '202301008', '202301008', 'ZAFER KENZIE', 'JAKARTA', '2019-10-12', 'L', 'TKA', 8, '2023', 'ZAFER', 'KB - At Tiin', '05-Des', '85', NULL, 'CLUSTER BINTANG RESIDENCE INDAH NO B6/ JL HJ UMAR/ JAKASETIA/ BEKASI SELATAN', NULL, '8111222945', 'islahamalia13@gmail.com', 'ZAHFAN ASADALLAH', 'SMA', 'WIRAUSAHA', 'DEPOK/ 21-03-1994', 'ISLAH AMALIAH', 'S1 - SARJANA EKONOMI', 'IBU RUMAH TANGGA', 'JAKARTA/ 13-12-1994'),
('TK0327', '202301010', '202301010', 'MUHAMMAD EIDLAN DEJARASYAA', 'Jakarta', '2019-05-11', 'L', 'TKA', 6, '2023', 'DEJA', 'KB - Al Ashr', '18/5', '100', NULL, 'Perumahan Bintara Alam Permai Blok D no 7 Bintara Jaya', NULL, '81310557128', 'pujo.widiatno@pancaputramadani.com', 'Pujo Widiatno', 'D3', 'Wirausaha', 'Kebumen/ 08-Sep-1981', 'Chiquitita Hapsari', 'S1', 'IRT', 'Bekasi/ 10-Mar-1982'),
('TK0328', '202301011', '202301011', 'MISHARY MANNAF RABBANI', 'KOTA BEKASI', '2019-07-07', 'L', 'TKA', 5, '2023', 'MISHARY', 'KB - At Tiin', '13', '94', NULL, 'JALAN SADEWA RAYA C 253C JAKASETIA', NULL, NULL, 'muh.4fif@gmail.com', 'Muhamad Afif', 'S1 sarjana teknik UNDIP', 'Wirausaha', 'Kabupaten Semarang/ 12-Apr-1988', 'Nuurul Lathiifah', 'S1 sarjana Teknik UNDIP', 'Wirausaha', 'Pekalongan/ 05-Dec-1989'),
('TK0329', '202301012', '202301012', 'IZZATY QISTHY', 'Bekasi', '2021-07-07', 'P', 'TKA', 8, '2023', 'QISTHY', 'KB - Al Qadr', '10/5kg', NULL, NULL, 'Emprit emas blok B no 51/ jaka setia', NULL, '81324463139', 'email : kastori77@yahoo.co.id', 'Kastori', 'S1 Sastra Arab di UNJ', 'Wirausaha', 'Brebes/ 29-09-1977', 'Ratih Maharani Subekti', 'S1 Ekonomi', 'Ibu Rumah tangga', 'Jakarta/ 13-09-1982'),
('TK0330', '202301013', '202301013', 'HANA NAYLA ASSYIFA', 'Kota Bekasi', '2020-02-06', 'P', 'TKA', 8, '2023', 'HANA', 'KB - Al Ashr', '20', '100', NULL, 'Kp Dua Perum De\'minimalis Blok C No.15 RT.04 RW.02 Jaka Sampurna/ Bekasi Barat 17145', NULL, '81282332250', 'linda.k3ui@gmail.com', 'Sigit Purwanto', 'S1 - Sarjana Teknik Sipil UGM', 'Karyawan BUMN', 'Jakarta/ 15-07-1980', 'Linda Hartini', 'S1 - Sarjana Kesehatan Masyarakat UI', 'IRT', 'Muara Enim/ 16-01-1983'),
('TK0331', '202301014', '202301014', 'ELYSIA AISYAHZAHRAH ANWAR', 'jakarta', '2019-07-12', 'P', 'TKA', 7, '2023', 'AISYAH', 'KB - At Tiin', '12', '101', NULL, 'jl. taman Malaka selatan 2 Blok B10 no 27 Malaka sari duren sawit Jakarta timur 13460', NULL, '81384273237', 'marlinadevi14@gmail.com', 'M. Bahrul Anwar', 'SI - Teknik Mesin UniBraw', 'karyawan swasta', 'pasuruan/ 28-04-1984', 'Devi Marlina', 'SI- Teknologi Industri IPB', 'IRT', 'Bandar Lampung/ 14-03-1985'),
('TK0332', '202301015', '202301015', 'ABDUL HAKIMI', 'Bekasi', '2021-01-03', 'L', 'TKA', 8, '2023', 'ABDUL', 'KB - Al \'Alaq', '13', '95', NULL, 'Perumahan Pondok Timur Mas/ Jalan Pondok Jingga Mas 7 blok R3 no 11', NULL, '811222977', 'ppat.bima@gmail.com', 'Bima Yogie Purnama', 'S2 Magister Kenotariatan/ Universitas Indonesia (UI)', 'Notaris/PPAT & Pengusaha', 'Bekasi/ 15-12-1990', 'Feny Ambarsari', 'S1 FSRD Institut Teknologi Bandung (ITB)', 'Ibu rumah tangga', 'Bekasi/ 03-Feb-1991'),
('TK0333', '202301016', '202301016', 'WIBISANA AFNAN ZAYD', 'Bekasi', '2019-11-11', 'L', 'TKA', 6, '2023', 'WIBI', 'KB - Al \'Alaq', '13', NULL, NULL, 'Perumahan villa pekayon blok A3 no 12', NULL, '81393392811', 'ajengrosalyne.air@gmail.com', 'Alifia Rahman', 'S1 Teknik Mesin Unej', 'Swasta', 'Jember/ 03-Nov-1986', 'Ajeng illastria', 'S2 Farmasi UI', 'PNS', 'Mojokerto/ 20-01-1987'),
('TK0334', '202301017', '202301017', 'UMAR ABQARIZIYAD KINAYUNG', 'Bekasi', '2021-05-03', 'L', 'TKA', 7, '2023', 'UMAY', 'KB - Al Ashr', '13', '93', NULL, 'Jalan pelabuhan ratu no 56 pengasinan Rawalumbu kota Bekasi', NULL, '81284060054', 'wikrama.ananta@gmail.com', 'Anantawikrama Unggul Kinayung', 'S1 Arsitektur Universitas Pancasila', 'Wiraswasta', 'Semarang/ 03-Dec-1987', 'Rachmayanti Nur Padilah', 'S1 Hubungan Internasional UIN', 'IRT', 'Bekasi/ 24-06-1993'),
('TK0335', '202301018', '202301018', 'AIZA HANAA ALHUMAIRA', 'Bekasi', '2022-06-03', 'P', 'TKA', 6, '2023', 'AIZA', 'KB - Al \'Alaq', '11kg', NULL, NULL, 'jalan jeruk 4 no 117 Perumnas 1 Bekasi', NULL, '81310077329', 'dedik08@gmail.com', 'Dedik Cahyono', 'Sarjana teknik', 'pegawai swasta', 'Blitar/ 08-Nov-1979', 'Farida Andam Dewi', 'Sarjana Teknik', 'swasta', 'Jakarta/ 09-Jan-1980'),
('TK0336', '202301019', '202301019', 'MARYAM ATSILAH MALASSA', 'Bekasi', '2021-04-10', 'P', 'TKA', 7, '2023', 'MARYAM', 'KB - At Tiin', '16', '95', NULL, 'Pondok pekayon indah jalan mahoni 1 B14 no 7', NULL, '81285180555', 'endy.malassa@yahoo.com', 'Endy malassa', 'Sma', 'Pegawai Bumn', 'Jakarta/ 07-Sep-1992', 'Nisa sofia', 'D3', 'IRT', 'Bandar lampung/ 01-Jul-1991'),
('TK0337', '202301020', '202301020', 'MUHAMMAD RAFIF ABQARY', 'Bekasi', '2019-03-01', 'L', 'TKA', 5, '2023', 'RAFIF', 'KB - Al Ashr', '18', '95', NULL, 'Jl. Pulo sirih tengah 13 blok ea 390', NULL, '81286382262', 'widiyogop@gmail.com', 'Widi yogo pratomo', 'S1 Akuntansi Univ. Gunadarma', 'Pns', 'Jakarta/ 04-Nov-1988', 'Elin septiana', 'S1 Akuntansi Univ. Gunadarma', 'Irt', 'Lebak/ 22-09-1990'),
('TK0338', '202301021', '202301021', 'ZAIDAN HASAN KARIM', 'Bekasi', '2020-08-05', 'L', 'TKA', 7, '2023', 'ZAIDAN', 'KB - Al Qadr', '13 kg', NULL, NULL, 'Jl cikunir raya no 101 rt01 rw02/ jakamulya/ bekasi selatan', NULL, '85718697759', 'diah647@gmail.com', 'Budi Yatmoko', 'SMP', 'Wirausaha', 'Sragen/ 08-Aug-1985', 'Diah ayu puspitarini', 'SMK', 'IRT', 'Sragen/ 20-05-1992'),
('TK0339', '202301022', '202301022', 'NAURA ARYANARESWARI AZZAHRA', 'Bekasi', '2019-08-02', 'P', 'TKA', 6, '2023', 'NAURA', 'KB - Al Qadr', '13 Kg', NULL, NULL, 'Griya Metropolitan 2 Blok E5 No.3 Pekayon Bekasi', NULL, '82133183060', 'ari.prihandoyo@gmail.com', 'Ari Agung Prihandoyo', 'S2 - Magister Teknik-UI', 'Karyawan Swasta', 'Boyolali/ 01-Jul-1989', 'Widya Ariaty', 'S1 - Pendidikan Dokter - Unpad', 'Mahasiswa PPDS', 'Palembang/ 07-Jul-1988'),
('TK0340', '202301023', '202301023', 'AHMAD FALAH IBRAHIM', 'Jakarta', '2020-12-05', 'L', 'TKA', 5, '2023', 'AHMAD', 'KB - At Tiin', '12', '98', NULL, 'Jl. Tenggiri V No. 12/ RT 002/RW.004/ Kelurahan Kayuringin Jaya/ Kecamatan Bekasi Selatan/ Kota Bekasi/ Jawa Barat/ 17144', NULL, '85336035037', 'amirudin.al.aziz@gmail.com', 'Zakariya Amirudin Al Aziz', 'S1 Teknik Industri', 'Swasta', 'Nganjuk/ 12-Nov-1990', 'Vidya Nurina', 'Dokter Umum', 'Wirausaha', 'Pasuruan/ 20-10-1991'),
('TK0341', '202301024', '202301024', 'SENJA ISLAM MEDINA', 'Cibinong', '2020-02-01', 'P', 'TKA', 7, '2023', 'SENJA', 'KB - Al \'Alaq', '13/5kg', NULL, NULL, 'Komp kristal garden blok G2 no.2 ciriung-cibinong-kan. Bogor', NULL, '85647851888', 'agungsurya_16@yahoo.com', 'Agung Surya Diyanto', 'S1- ekonomi pembangunan UKSW', 'Pns', 'Salatiga/ 12-Feb-1985', 'Rani Puspita Dewi', 'D3 kebidanan poltekkes jakarta 3', 'Pns', 'Sleman/ 26-05-1989'),
('TK0342', '202301025', '202301025', 'MAHARDHIKA ALI ALFATIH MUNTHE', 'Jakarta', '2020-06-07', 'L', 'TKA', 6, '2023', 'ALI', 'KB - At Tiin', '12', '93', NULL, 'Pondok Pekayon Indah Jalan Ketapang I Blok DD48 No 8', NULL, '81361582307', 'fahmi_munthe@yahoo.com', 'Khairul Fahmi Munthe', 'S1 - Sarjana Teknik USU', 'Swasta', 'Jakarta/ 02-May-1988', 'Ayu Aryawati', 'S1 - matematika Unpad', 'BUMN', 'Jakarta/ 28-06-1989'),
('TK0343', '202301026', '202301026', 'ARYENA MYSHA HUDA', 'Bekasi', '2020-12-12', 'P', 'TKA', 6, '2023', 'MYSHA', 'KB - Al \'Alaq', '14/30 kg', NULL, NULL, 'Perum. PTI KHUSUS BLOK K5/no.5/ Rt.005/Rw.010/ kel. Jatimulya/ kec. Tambun selatan', NULL, '87875883813', 'hudafachrizal11@gmail.com', 'Fachrizal huda', 'S1 akuntansi univ mercu buana', 'Wiraswasta', 'Padang panjang/ 20-06-1992', 'Johanna Prasetiarini', 'S1 akuntansi univ. Mercu buana', 'Ibu rumah tangga', 'Jakarta/ 07-Dec-1992'),
('TK0344', '202301027', '202301027', 'ALMAHYRA KIRANI RAMADHANI', 'Jakarta', '2020-03-05', 'P', 'TKA', 6, '2023', 'KIRANI', 'KB - Al \'Alaq', '10', '88', NULL, 'Perumahan Jatibening Estate/ Jl. Kutilang II Blok G9 No.4 Rt.013 Rw.013 Jatibening/ Pondok Gede/ Bekasi Barat', NULL, '81212797092', 'annisa.anggraini10@gmail.com', 'Muhamad Rizki Fajar', 'S1 - Pendidikan Administrasi Perkantoran UNJ', 'Swasta', 'Jakarta/ 08-Aug-1990', 'Annisa Anggraini', 'S1 - Akuntansi UI', 'Swasta', 'Jakarta/ 10-Oct-1992'),
('TK0345', '202301028', '202301028', 'MUHAMMAD AHDAN BILFAQIH', 'Palembang', '2021-01-02', 'L', 'TKA', 7, '2023', 'AHDAN', 'KB - Al Qadr', '18', '106', NULL, 'The Green Palace Jatibening/ Blok C/3/ Jatibening Baru/ Pondok Gede/ Bekasi', NULL, NULL, 'ahmadh809@gmail.com', 'Ahmad Hidayah', 'S1-Sarjana Ekonomi', 'PNS', 'Jakarta/ 05-Nov-1988', 'Firda Aristya', 'S1-Sarjana', 'PNS', 'Palembang/ 04-Nov-1989'),
('TK0346', '202301029', '202301029', 'AZKAYRA KHADEEJAH ALIYA', 'Jakarta', '2019-08-02', 'P', 'TKA', 6, '2023', 'ALIYA', 'KB - Al \'Alaq', '11 kg', '100', NULL, 'Jl penggilingan gg hj masmala rt 13 rw 07 no 70 kel penggilingan kec cakung jakarta timur 13940', NULL, '81315086412', 'fatihaliya99@gmail.com', 'fatih hanabila aliya', 'S2', 'Manager', 'Jakarta/ 29-06-1996', 'Tasya', 'S1', 'Ibu rumah tangga', 'Jakarta/ 02-Apr-2000'),
('TK0347', '202301030', '202301030', 'UMAR MUSA SUHADA', 'Bekasi', '2019-11-11', 'L', 'TKA', 5, '2023', 'UMAR', 'KB - Al Qadr', '12.7', '95', NULL, 'Cluster Lavesh SA5.16 no 12', NULL, '8118416666', 'ahmadcs89@gmail.com', 'Ahmad Chaerus Suhada', 'S1 - Sarjana Statistika IPB', 'Swasta', 'Bekasi/ 03-Oct-1989', 'Puspalia Ayudiar Setiawati', 'S1 - Sarjana Statistika IPB', 'IRT', 'Sumedang/ 10-Mar-1990'),
('TK0348', '202301031', '202301031', 'SHAQUEENA NADA KHALILUNA', 'Bekasi', '2020-11-09', 'P', 'TKA', 5, '2023', 'SHAQUEEN', 'KB - At Tiin', '14.2', '100', NULL, 'Jalan Swatantra 1 - Kav V No 149 RT 09 RW 05 Jatirasa Jatiasih 17424', NULL, NULL, 'febrinahanifah@gmail.com', 'Rino Supriadi Putra', 'S2 Teknik Industri Universitas Indonesia', 'BUMN', 'Lumajang/ 29-06-1992', 'Febrina Hanifah', 'S1 Ilmu Komunikasi Universitas Padjajaran', 'BUMN', 'Jakarta/ 13-02-1992'),
('TK0349', '202301032', '202301032', 'KHAYLA NAFISA DZAKIRAH', 'Jakarta', '2020-03-08', 'P', 'TKA', 5, '2023', 'KHAYLA', 'KB - Al Qadr', '15', '95', NULL, 'Jl. Pondok cipta blok b1 no.8', NULL, '85775915621', 'lalita.pathya92@gmail.com', 'Ardhito Pratama Nugraha', 'S1-Ekonomi', 'Swasta', 'Jakarta/ 15-09-1990', 'Lalita Pathya Sukma', 'S1-Sistem Informasi', 'IRT', 'Jakarta/ 31-03-1992'),
('TK0350', '202301033', '202301033', 'AISYAH ANNAILA', 'Bekasi', '2019-04-02', 'P', 'TKA', 5, '2023', 'AISYAH', 'KB - Al Ashr', '12 Kg', NULL, NULL, 'Perumahan Alam Indah Jl. Garuda II Blok L1 No. 100 RT/RW 001/006 Kel. Poris Plawad Indah Kec. Cipondoh Tangerang', NULL, '85279562281', 'eriwandri@gmail.com', 'Eri Wandri', 'S1 - Sarjana Ilmu Komputer UPI YPTK Padang', 'Karyawan Swasta', 'Padang/ 09-Feb-1991', 'Haizawahyuni', 'S1 - Sarjana Ilmu Komputer UPI YPTK Padang', 'Ibu Rumah Tangga', 'Talawi/ 08-Jan-1992'),
('TK0351', '202301034', '202301034', 'MAHIRA QALESYA PRAKASA', 'Bekasi', '2020-06-06', 'P', 'TKA', 7, '2023', 'UMA', 'KB - Al Qadr', '12', '100', NULL, 'Jl. Pondok Jingga Mas V Blok E3 No 19 Bekasi', NULL, '81586213537', 'dedy.bagus@yahoo.com', 'Dedy Bagus Prakasa', 'S2 Akutansi UI', 'PNS', 'Bekasi/ 23-04-1984', 'Ari Astri Yunita', 'S2 Hukum UI', 'PNS', 'Jakarta/ 06-Apr-1983'),
('TK0352', '202301035', '202301035', 'FATHIMAH AZZAHRA NURGHANIA SANDIKA', 'Bekasi', '2021-08-02', 'P', 'TKA', 8, '2023', 'FATHIMAH', 'KB - Al Ashr', '14', '90', NULL, 'Pondok Timur Mas Blok G2 no 1B', NULL, '81210665556', 'ek.sandika@gmail.com', 'Rosyandi Luddin', 'D3 Keuangan', 'Karyawan Swasta', 'Jakarta/ 26-06-1978', 'Eka Hidayatul Mustafidah', 'S1 keuangan', 'Karyawan', 'Payakumbuh/ 09-May-2009'),
('TK0353', '202301036', '202301036', 'WAODE MARYAM BAKTI', 'Bekasi', '2020-08-11', 'P', 'TKA', 8, '2023', 'MARYAM', 'KB - Al Qadr', '17 kg', NULL, NULL, 'Jl. Penegak 1 no. 75 rawa lumbu bekasi', NULL, NULL, 'Puspasarianggraini82@gmail.com', 'L. M Bakti', 'S2 Magister', 'PNS', 'Yogyakarta/ 14-11-1981', 'Puspa', 'S1- Kesehatan Masyarakat', 'IRT', 'Jakarta/ 07-Jan-1982'),
('TK0354', '202301037', '202301037', 'UMAR KHALID AKRESA', 'Bekasi', '2020-01-10', 'L', 'TKA', 6, '2023', 'UMAR', 'KB - At Tiin', '20', '99', NULL, 'Pondok Surya Mandala Jl. Surya Permata IV blok s1 no.27', NULL, NULL, 'caca.bpjstk@gmail.com', 'Krestianto Aditya Santoso', 'S1 Tekhnologi Informasi', 'Swasta', 'Semarang/ 11-Dec-1988', 'Annisa Kusumawardhani', 'S1 Ilmu Komunikasi', 'BUMN', 'Pangkal Pinang/ 21-09-1991'),
('TK0355', '202301038', '202301038', 'KHANZA NAFISAH IHSAN', 'Jakarta', '2019-08-01', 'P', 'TKA', 5, '2023', 'KANZA', 'KB - Al \'Alaq', '12/6kg', NULL, NULL, 'KPAD Jl.Angkutan blok K no.24 RT 09/RW 06', NULL, '81316453196', 'rifkirinaldi49@gmail.com', 'Rifki Rinaldi Ihsan', 'S1 - Akuntansi', 'Swasta', 'Jakarta/ 12-Jul-1991', 'Dewi Lestari', 'S1 - Ekonomi', 'IRT', 'Jakarta/ 09-Jun-1991'),
('TK0356', '202301039', '202301039', 'MUHAMMAD ATHALLAH SUPRIYONO', 'Bekasi', '2019-07-02', 'L', 'TKA', 5, '2023', 'ATHALLAH', 'KB - Al Ashr', '14', '93', NULL, 'Perum Pondok Timur Indah Jl. Panda 4 Blok D No. 111', NULL, '81808183486', 'shelfisupriyono@gmail.com', 'Iko Supriyono', 'S1 - Akuntansi STIE SUPRA', 'Swasta', 'Jakarta/ 07-Jul-1980', 'Shelfi', 'S1 - BK UNJ', 'IRT', 'Jakarta/ 09-Apr-1981'),
('TK0357', '202301040', '202301040', 'HAZWAN AUSHAF ATHAILLAH', 'Bekasi', '2019-05-11', 'L', 'TKA', 5, '2023', 'HAZWAN', 'KB - Al \'Alaq', '15', '98', NULL, 'Sakura regency 3 no U29', NULL, '82113029480', 'tantra_lesmana@yahoo.co.id', 'Tantra Lesmana', 'S1 pendidikan teknik elektro', 'Swasta', 'Jakarta/ 15-05-1986', 'Hikmelia Dwi Sundari', 'Magister psikologi', 'PNS', 'Tasikmalaya/ 13-07-1987'),
('TK0358', '202301041', '202301041', 'KANISYA AFIZA ALIYA', 'BEKASI', '2021-06-02', 'P', 'TKA', 7, '2023', 'KANISYA', 'KB - At Tiin', '11.5 kg', NULL, NULL, 'Jl. Pulo sirih utama Blok A1 No.21 Villa Galaxy Bekasi', NULL, '85779744504', 'leslyrossa@gmail.com', 'Idam Bariyanto', 'S1 Teknik Mesin UI', 'Karyawan Swasta PT. AHM', 'Jakarta/ 24-10-1984', 'Lesly Rossa', 'S1 Psikologi Unpad', 'IRT', 'Tembagapura/ 10-Apr-1983');
INSERT INTO `siswa` (`c_siswa`, `nis`, `nisn`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jk`, `c_kelas`, `group_kelas`, `tahun_join`, `panggilan`, `c_klp`, `berat_badan`, `tinggi_badan`, `ukuran_baju`, `alamat`, `telp`, `hp`, `email`, `nama_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `ttl_ayah`, `nama_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `ttl_ibu`) VALUES
('TK0359', '202301042', '202301042', 'DILARA NURA BIYA', 'Jakarta', '2021-11-03', 'P', 'TKA', 8, '2023', 'NURA', 'KB - Al Qadr', '11 kg', '92', NULL, 'Cluster trans agung no c6 jatiasih bekasi', NULL, '85322512752', 'panjisatriya91@gmail.com', 'Panji Satriya', 'S1 - Sarjana Ilmu Komunikasi UNPAD', 'Karyawan BUMN', 'Medan/ 19-10-1991', 'Nabila Putri Vihandika', 'S1 - Sarjana Ilmu Komunikasi UNPAD', 'IRT', 'Jakarta/ 28-05-1993'),
('TK0360', '202301043', '202301043', 'ALTHAF KHALID AULIA', 'BEKASI', '2022-06-03', 'L', 'TKA', 7, '2023', 'ALTHAF', 'KB - Al \'Alaq', '13KG', NULL, NULL, 'TAMAN JATISARI PERMAI JALAN JAWA BLOK EA 33 KEL.JATISARI KEC.JATIASIH BEKASI 17426', NULL, '8111229182', 'avia_darini@yahoo.com', 'HENDRA ARIWIJAYA', 'S1', 'SWASTA', 'JAKARTA/ 25-06-1982', 'FIFI AVIA DARINI', 'S1 - PSIKOLOGI', 'WIRAUSAHA', 'Jakarta/ 05-Dec-1986'),
('TK0361', '202301044', '202301044', 'MUHAMMAD HAMIZAN MALIK RIZANSYAH', 'Jakarta', '2020-04-10', 'L', 'TKA', 7, '2023', 'MALIK', 'KB - Al Ashr', '14/5', '96', NULL, 'Persada Kemala Blok 16 Nomor 10/ Jakasampurna/ Bekasi Barat', NULL, '8562870787', 'abrorizani@gmail.com', 'Muhammad Abror Rizani Fahmi', 'S1 - Kedokteran Umum UGM', 'Dokter', 'Bantul/ 08-Dec-1992', 'Jessymia Auliana', 'S1 - Kedokteran Umum UGM', 'Dokter', 'Jakarta/ 05-Jul-1993'),
('TK0362', '202301045', '202301045', 'RAYYAN PRANADIPA SANTIAJI', 'Jakarta', '2019-06-03', 'L', 'TKA', 8, '2023', 'RAYYAN', 'KB - Al Qadr', '14', '94', NULL, 'Perumahan Raffles Hills/ cluster Emerald Crown/ blok EC6 No 12/ Depok', NULL, NULL, 'deeaayunastiti@gmail.com', 'Arie Santiaji', 'MSc - Master of Data Communication and Security/ University of Hertfordshire', 'Swasta', 'Jakarta/ 06-Jul-1980', 'Dea Ayu Nastiti', 'S1 - Kedokteran Gigi/ Universitas Padjadjaran', 'Ibu Rumah Tangga / Dokter gigi', 'Jakarta/ 01-Feb-1993'),
('TK0363', '202301046', '202301046', 'NAWAF FADHIL ATTAMIMI', 'Purwokerto', '2020-08-07', 'L', 'TKA', 8, '2023', 'BAWAF', 'KB - At Tiin', '18 kg', '103', NULL, 'Perumahan griya antar reja jati makmur bekasi', NULL, '81227037172', 'diyanahaidaroh12@gmail.com', 'Fadhil Attamimi', 'Sma Al irsyad tengarab', 'Wirasuasta travel umroh', 'Jeddah/ 08-09-1881', 'Diyana Firdaus', 'S1 psikologi', 'IRT dan mengelola travel umroh', 'Purwokerto/ 12-Dec-1995'),
('TK0364', '202301047', '202301047', 'VANIA IZZATUNNISA AZAHRA', 'Kota Bekasi', '2021-05-08', 'P', 'TKA', 8, '2023', 'VANIA', 'KB - Al Ashr', '13', '90', NULL, 'Jl. H. Pindah no 11/ Jakamulya/ Bekasi Selatan', NULL, '81351937477', 'kardiae@gmail.com', 'Kardi', 'S1-Sarjana Perkapalan', 'Pegawai Swasta', 'Jakarta/ 04-Mar-1984', 'Vidya Ekaningtyas', 'Pendidikan Dokter', 'Swasta', 'Purwokerto/ 10-Aug-1986'),
('TK0365', '202301048', '202301048', 'KHABIB ELKIANO ZINEDINE AZIS', 'Bekasi', '2019-07-07', 'L', 'TKA', 6, '2023', 'ELKIANO', 'KB - Al \'Alaq', '17', '100', NULL, 'Pondok Timur Mas/ Jalan Jingga Mas 1 blok F3 No. 17/ Kel. Jaka Setia/ Kec. Bekasi Selatan/ Kota Bekasi', NULL, '81298843359', 'irvanazis91@gmail.com', 'Irvan Azis/ S.E./ M.Sos.', 'S2 - Pascasarjana Ilmu Sosiologi Univ. Brawijaya', 'PNS', 'Jakarta/ 01-Nov-1991', 'Nicky Amanda/ S.In./ M.Sos.', 'S2 - Pascasarjana Ilmu Sosiologi Univ. Brawijaya', 'PNS', 'Bekasi/ 11-Aug-1990'),
('TK0366', '202301049', '202301049', 'AZZURA FELLAH RAMADHANI', 'Bekasi', '2021-01-05', 'P', 'TKA', 8, '2023', 'ZURA', 'KB - Al Ashr', '13', '94', NULL, 'Jl Surabaya raya blok B2 No 4 Bekasi Jaya Bekasi Timur', NULL, '82116229797', 'abduhmaulanaali2531@gmail.com', 'Abduh Maulana Ali', 'Smu', 'Staff yayasan', 'Jakarta/ 25-01-1985', 'Sariati Christina', 'Smu', 'Irt', 'Bekasi/ 13-09-1989'),
('TK0367', '202301050', '202301050', 'RAFFASYA SYARIEF MUHAMMAD', 'Tangerang Selatan', '2020-11-11', 'L', 'TKA', 8, '2023', 'RAFFASYA', 'KB - At Tiin', '12kg', NULL, NULL, 'Perumahan Serpong Garden 2 Cluster Green Hill Blok B19-12A', NULL, '81316172008', 'uyun.syarief.2008@gmail.com', 'Nur Qurota Uyuny Syarief', 'S2 - Sarjana Teknik UI', 'POLRI', 'Jakarta/ 15-08-1989', 'Chorista Ika Hasnawati', 'S1 - Sarjana Pendidikan UMM (Univ. Muhammadiyah Malang)', 'IRT', 'Malang/ 06-Mar-1996'),
('TK0368', '202301051', '202301051', 'NAQIYYA HAFLA BAHRI', 'Bekasi', '2019-05-12', 'P', 'TKB', 9, '2023', 'NAQIYYA', 'K1 - Al Fajr', '14.7', NULL, NULL, 'Jl. Makrik Gang Jenin no. 107 RT 07/04 Bojong Rawalumbu Bekasi 17116', NULL, '85717906099', 'squnifah20@gmail.com', 'Saepul Bahri', 'SMK', 'Karyawan Swasta', 'Bekasi/ 24-04-1995', 'Qunifah Suwidianti', 'S1 Sastra Jepang', 'Mengurus Rumah Tangga', 'Bekasi/ 20-06-1995'),
('TK0369', '202301052', '202301052', 'ALYA BINTA RAMADANIA', 'Surabaya', '2021-01-04', 'P', 'TKB', 10, '2023', 'BINTA', 'K1 - Al Fajr', '13 kg', NULL, NULL, 'Komplek taman cikas blok c 14 nomor 16', NULL, '81295555090', 'ramabagusp@gmail.com', 'Rama bagus perkasa', 'S2 sarjana teknik sipil', 'Karyawan BUMN', 'Cirebon/ 04-Apr-1990', 'Nadia imaniar sidqon', 'S1 Sarjana kedokteran (dokter umum)', 'Ibu rumah tangga', 'Yogyakarta/ 12-Jul-1990'),
('TK0370', '202301053', '202301053', 'KHADIJAH AZZAHRA MAULIDA', 'Bekasi', '2019-10-11', 'P', 'TKB', 11, '2023', 'KHADIJAH', 'K1 - Al Fajr', '12 kg', NULL, NULL, 'Jl.Bintang raya B.6', NULL, '81365437473', 'isfi050277@gmail.com', 'Isfi hendri', 'SLTA', 'Swasta', 'Payakumbuh/ 02-May-1977', 'Esi endriani', 'SLTA', 'Ibu rumah tangga', 'Durian kapas/ 05-Jan-1980'),
('TK0371', '202301054', '202301054', 'MUHAMMAD SABIQ KHAIRUL', 'Jakarta', '2018-11-01', 'L', 'TKBLULUS', NULL, '2023', 'SABIQ', 'K2 - Al Qalam', '18/3kg', NULL, NULL, 'Jl Marzuki 9 rt 05 rw 01 no 42 penggilingan Cakung Jakarta timur', NULL, '87887336292', 'tanyakaromi99@gmail.com', 'Ali karomi', 'Smk', 'Wirausaha', 'Jakarta/ 04-Apr-1991', 'Kamia', 'S1-pendidikan', 'Guru', 'Bekasi/ 06-Jun-1994'),
('TK0372', '202301055', '202301055', 'NAEEMA ARUNA BIYA', 'Jakarta', '2020-05-10', 'P', 'TKB', 11, '2023', 'NEIMA', 'K1 - Al Balad', '14', '100', NULL, 'Cluster trans agung no c6 jatiasih bekasi', NULL, '85322512752', 'panjisatriya91@gmail.com', 'Panji Satriya', 'S1 Ilmu Komunikasi UNPAD', 'Karyawan BUMN', 'Medan/ 19-10-1991', 'Nabila Putri Vihandika', 'S1 Ilmu Komunikasi UNPAD', 'IRT', 'Jakarta/ 28-05-1993'),
('TK0373', '202301056', '202301056', 'MUHAMMAD KHALID BILFAQIH', 'Bekasi', '2019-04-08', 'L', 'TKA', 6, '2023', 'KHALID', 'KB - Al Qadr', '16', '105', NULL, 'Jl. H. Ilyas Cikunir RT 03 Rw 12', NULL, '+6281316765365\'', 'candrakusuma280888@gmail.com', 'Candra Kusuma', 'D3 - Analis Kesehatan', 'Swasta', 'Surakarta/ 28-08-1988', 'Siska Amalia', 'D3 - Keperawatan', 'PNS', 'Bekasi/ 12-06-1992'),
('TK0374', '202301057', '202301057', 'ARSYA AIMANURRIJAL ASYARI', 'Bekasi ', '2019-05-11', 'L', 'TKA', 7, '2023', 'AIMAN', 'KB - Al \'Alaq', '16', '105', NULL, 'Pondok Timur Mas Jl Pondok Mas Raya A/5/ RT 09 RW 13/ Jakasetia/ Bekasi Selatan/ 17147B', NULL, '085720123381', 'ingridlarasati@gmail.com', 'Abung Asyari', 'S2 - Kependidikan ', 'Dosen', 'Cibinong/ 31-03-1981', 'Ingrid Larasati Agustina', 'S3 - Ekonomi Syariah UIN SGD', 'Dosen ', 'Jakarta/ 21-01-1983'),
('TK0375', '202301058', '202301058', 'MUHAMMAD AL FATIH ANWAR', 'Jakarta', '2018-01-06', 'L', 'TKBLULUS', NULL, '2023', 'AL FATIH', 'K2 - Al Qalam', '18', '105', NULL, 'Jalan pondok jingga mas 2 blok f3 no 2', NULL, '081348881979', 'kazbrother3@gmail.com', 'Anwar Hermansyah', 'SMA', 'Wirausaha', 'Balikpapan/ 1-04-1994', 'Nur fitri Ani Suli', 'SMA', 'IRT    ', 'Jayapura/ 1-4-1994'),
('TK0378', '202401047', '202401047', 'Arkatama Rafizki Abraham', 'Bekasi', '2020-05-11', 'L', 'TKA', 7, '2024', 'Arka', 'TKA', '14 kg', '93 cm', '', 'Jalan Pondok Merah Mas 1 blok G2 no.19, Pondok Timur Mas, Jakasetia', '', '', 'bram.abe90@gmail.com', 'Muhammad Abraham', 'S1', 'WIRASWASTA', 'Jakarta, 18 Juli 1990', 'Annisa Cintya Hersilia', 'S1', 'Dokter', 'Bekasi, 15 Agustus 1989'),
('TK0379', '202401046', '202401046', 'QARIZAL HAMAS MUQAFFI', 'Bekasi', '2019-11-08', 'L', 'TKA', 5, '2024', 'HAMAS', 'TKA', '16 Kg', '104 cm', '', 'jl manggis raya no.444, perum Duren Jaya, Bekasi Timur', '', '089646699994', 'fikri77.fm@gmail.com', 'Muhammad Fikri Muzakky', 'S2', 'KARYAWAN SWASTA', 'Kandangan, 22 April 1994', 'Lutfia Niswatul Khasanah', 'D3', 'KARYAWAN SWASTA', 'Jakarta, 25 Agustus 1992'),
('TK0380', '202401048', '202401048', 'Sayyid Maika Wilwatikta', 'Surabaya', '2019-11-23', 'L', 'TKA', 8, '2024', 'Sayyid', 'TKA', '15', '112', '', 'Jalan swakarasa V no.59B', '', '081242234523', 'dana.meits@gmail.com', 'Dimas Perdana Martino', 'S1', 'WIRASWASTA', 'Surabaya, 15 Maret 1990', 'Anggita claratika', 'S1', 'WIRASWASTA', 'Jakarta, 22 September 1992'),
('TK0381', '202401049', '202401049', 'Hudzaifa Abdilla Hasani', 'Bekasi', '2019-12-11', 'L', 'TKA', 6, '2024', 'Hudza', 'TKA', '14 kg', '95', '', 'GG. Sawo RT.004 RW.008 no.11, Jatimulya, Tambun Selatan, Bekasi Timur', '', '089653392454', 'mihanny11@gmail.com', 'Ahmad Rifqi Hasani', 'S2', 'KARYAWAN SWASTA', 'Jakarta, 01 Mei 1987', 'Umi Hani', 'S1', '', 'Jakarta, 23 November 1993'),
('TK0382', '202401050', '202401050', 'Maryam Salsabiila Kurniawanto', 'Bekasi', '2019-10-09', 'P', 'TKA', 7, '2024', 'Maryam', 'TKA', '12 Kg', '95', '', 'Jl Sadewa 5, C324, Jakasetia, Bekasi Selatan', '', '085884973408', 'kurniawanto.84@gmail.com', 'Kurniawanto', 'S1', 'WIRASWASTA', 'Jakarta, 30 Desember 1984', 'Nanin Wailisahalong', 'S1', '', 'Surabaya, 29 Januari 1985'),
('TK0383', '202401002', '202401002', 'Muhammad Zefa Joban', 'Bekasi', '2020-12-15', 'L', 'KB', 1, '2024', 'Adek/Zefa', 'KB', '', '', '', 'PTM Jl. Pondok Jingga Mas I', '', '', '', 'Riza Wilhansah', 'S1', '', '18 Oktober 1990', 'Eva Yulyanti', 'S1', '', ''),
('TK0384', '202401003', '202401003', 'Faiqah Alma Zhariifah', 'Bekasi', '2020-09-27', 'P', 'KB', 1, '2024', 'Faiqah', 'KB', '', '', '', 'Perumahan Pesona Metropolitan, Cluster Botania 2, Blok E no 23, Kel Bojong Rawalumbu, Kec Rawalumbu, Kota Bekasi', '', '', '', 'Gema Vikossa', 'S1', '', '24 Februari 1985', 'Aliza Ramadhani Putriana', 'S1', '', ''),
('TK0385', '202401004', '202401004', 'Shabira Fezir Elnara', 'Jakarta', '2020-01-03', 'P', 'KB', 1, '2024', 'Shabira', 'KB', '', '', '', 'Jl. Kawi No. 40, Guntur, Jakarta Selatan', '', '', '', 'Ginanjar Udiarera Kresnananta', 'S1', 'KARYAWAN SWASTA', '', 'Nabila Meutia Zahra', 'S1', '', ''),
('TK0386', '202401005', '202401005', 'Shakila Almahyra Eryandi', 'Bekasi', '2020-08-28', 'P', 'KB', 1, '2024', 'Shakila', 'KB', '', '', '', 'Perumahan Pondok Timur Mas, Jl. Pondok Jingga Mas IV, Blok E2, No.20, RT.005/RW.013, Kel. Jaka Setia, Kec. Bekasi Selatan, Kota Bekasi, Jawa Barat', '', '', '', 'Febriyandi', 'S1', 'KARYAWAN SWASTA', '24 Februari 1992', 'Eka Rahmi Kahar', 'S2', '', '15-11-1992'),
('TK0387', '202401006', '202401006', 'Aruni ghaina syawa', 'Bekasi', '2020-05-30', 'P', 'KB', 1, '2024', 'Aruni', 'KB', '', '', '', 'Jl matahari 2 blok i no 1 taman galaxy bekasi selatan', '', '', '', 'Rudi irwanto', 'S1', 'PNS', '27 Oktober 1984', 'Addina sugiarto', 'S1', '', '21 Maret 1984'),
('TK0388', '202401008', '202401008', 'Muhammad Abimanyu Janitra Cahyono', 'Bekasi', '2020-02-13', 'L', 'KB', 1, '2024', 'Abim', 'KB', '', '', '', 'Pondok timur mas blok R1 no.10a', '', '', '', 'Faruq anton cahyono', 'S2', 'WIRASWASTA', '', 'Novi Astuti', 'SMA', 'WIRASWASTA', ''),
('TK0389', '202401009', '202401009', 'Muhammad Musa Abdulloh', 'Cilacap', '2020-07-22', 'L', 'KB', 1, '2024', 'Musa', 'KB', '', '', '', 'Pondok Timur Mas Jl Pondok Merah Mas Blok C1/ 7. Galaxy, Bekasi Selatan', '', '', '', 'Mohamad Zuhroni', 'S2', 'KARYAWAN SWASTA', '19 Desember 1981', 'Vidya Antariksani', 'S1', 'WIRASWASTA', ''),
('TK0390', '202401010', '202401010', 'Sarah Zayn Attiya', 'Jakarta', '2020-12-09', 'P', 'KB', 1, '2024', 'Sarah', 'KB', '', '', '', 'Jalan Lembah Aren x Blok K16 No. 21 rt02 rw09 Kav DKI Pondok Kelapa Duren Sawit JakTim 13450', '', '', '', 'Adhib Rakhmanto', 'S1', '', '', 'Sefty Kurnadia Weny', 'S1', '', ''),
('TK0391', '202401011', '202401011', 'Arrazka Syafiq Firdhani', 'Jakarta', '2020-07-20', 'L', 'KB', 3, '2024', 'Arrazka/Razka/Kakak', 'KB', '', '', '', 'Cluster Taman Sentani 1 Blok B 7 Jl. Asem Sari No. 30 Mustika Jaya Kota Bekasi', '', '', '', 'Adhika Ihram Firdhani', 'S1', 'KARYAWAN SWASTA', '28 Juni 1993', 'Jenny Widha Savira', 'S1', 'KARYAWAN SWASTA', '28 Juni 1993'),
('TK0392', '202401012', '202401012', 'Nazla Ihza Mustafa', 'Bekasi', '2021-02-22', 'P', 'KB', 2, '2024', 'Nazla', 'KB', '', '', '', 'Pondok timur mas blok G4 no.11', '', '', '', 'Aditya pradana mustafa', 'S1', 'KARYAWAN SWASTA', '22 Mei 1985', 'Diyah Tanjung Sari', 'S1', '', '19 September 1983'),
('TK0393', '202401013', '202401013', 'Kareem Arrashad Syam', 'Jakarta', '2020-03-07', 'L', 'KB', 1, '2024', 'Kareem', 'KB', '', '', '', 'Kemang Pratama 3 jl. Akalipa blok F5 no. 29', '', '', '', 'Aditya Syam', 'S1', '', '', 'Noni siti annisa', 'S1', '', ''),
('TK0394', '202401014', '202401014', 'Anindya Masayu Khadijah Duyufurrahman', 'Jakarta', '2020-01-26', 'P', 'KB', 3, '2024', 'DUYU', 'KB', '', '', '', 'Jl swakarsa 2 no 30 rt 03 rw 04 jatibening baru pondok gede bekasi', '', '', '', 'Suradi', 'S3', 'POLRI', '14 Juli 1976', 'Vivi novianti', 'S2', 'POLRI', ''),
('TK0395', '202401015', '202401015', 'Muhammad zaid abdullah', 'Tasikmalaya', '2020-06-23', 'L', 'KB', 3, '2024', 'Zaid', 'KB', '', '', '', 'Jalan kopral bosan 11 no 12 kontrakan warnawarni pintu no 5', '', '', '', 'Aldi ferdiansyah', 'SMA', 'KARYAWAN SWASTA', '27 Maret 1995', 'Vina khoerunnisa', 'SMA', '', '24 September 1998'),
('TK0396', '202401016', '202401016', 'Rumaisha Hasna Humairah', 'Tangerang Selatan', '2021-02-16', 'P', 'KB', 3, '2024', 'Rumaisha atau Rumi', 'KB', '', '', '', 'Pondok Pekayon Indah, Jl. Kenari 1 Blok A2 No. 3', '', '', '', 'Nur Qurota Uyuny Syarief', 'S2', 'POLRI', '15 Agustus 1989', 'Chorista Ika Hasnawati', 'S1', '', ''),
('TK0397', '202401017', '202401017', 'Akhtar Faiz Zahri', 'Jakarta', '2021-01-21', 'L', 'KB', 3, '2024', 'Faiz', 'KB', '', '', '', 'Komplek Pondok Timur Mas jl Pondok Jingga Mas IV blok E 2 no 23 , Jaka Setia , Bekasi', '', '', '', 'Berlianto Haris', 'S2', 'KARYAWAN SWASTA', '26 Mei 1985', 'Sukma Faizah', 'S1', '', ''),
('TK0398', '202401018', '202401018', 'Rania', 'Bekasi', '2020-10-20', 'P', 'KB', 3, '2024', 'Rania', 'KB', '', '', '', 'Jl delta Barat VII no B52', '', '', '', 'Afan Miqda', 'S1', 'KARYAWAN SWASTA', '', 'Farhanah', 'D3', '', '16 Februari 1993'),
('TK0399', '202401019', '202401019', 'Mecca Alyssa Azis', 'Jakarta', '2020-12-24', 'P', 'KB', 3, '2024', 'Alyssa', 'KB', '', '', '', 'Pondok Timur Mas, Jalan Jingga Mas 1 blok F3 No. 17, Kel. Jaka Setia, Kec. Bekasi Selatan, Kota Bekasi', '', '', '', 'Irvan Azis, S.E., M.Sos.', 'S2', 'PNS', '', 'Nicky Amanda, S.In., M.Sos.', 'S2', 'PNS', ''),
('TK0400', '202401020', '202401020', 'Syeikhan zuhdiannoor ramadhan', 'Bekasi', '2020-06-06', 'L', 'KB', 2, '2024', 'Sehan', 'KB', '', '', '', 'Persada kemala blok 17 no. 4-5', '', '', '', 'Muhamad ramadani', 'S1', 'KARYAWAN SWASTA', '25 April 1990', 'Nabila nur aulia', 'S1', 'KARYAWAN SWASTA', '30 November 1992'),
('TK0401', '202401021', '202401021', 'Azka Zhafran Mahendra', 'Jakarta', '2020-06-09', 'L', 'KB', 2, '2024', 'Azka', 'KB', '', '', '', 'Cluster kiana bintara Blok A No. 3', '', '', '', 'David Mahendra', '', 'KARYAWAN SWASTA', '', 'Robiatul Kamelia', '', '', '20 Juli 1989'),
('TK0402', '202401022', '202401022', 'Lito Battani Abdulhakim', 'Jakarta', '2020-11-13', 'L', 'KB', 3, '2024', 'Lito', 'KB', '', '', '', 'Taman Cikas B16-12 Pekayon Jaya, Bekasi', '', '', '', 'Himawan Nurkahfianto', 'S1', 'KARYAWAN SWASTA', '', 'Ratih Anggraeny', 'S1', '', '27 Agustus 1989'),
('TK0403', '202401023', '202401023', 'Musa Damarlangit Cendekia', 'Jakarta', '2020-08-03', 'L', 'KB', 3, '2024', 'Musa', 'KB', '', '', '', 'Cluster Taman Firdausi No. 2, Jatibening Baru', '', '', '', 'Cendekia Putra Kartono', 'S1', 'KARYAWAN SWASTA', '16 Desember 1990', 'Detta Olyvia Nirwana', 'S1', '', '16 September 1988'),
('TK0404', '202401024', '202401024', 'Akasharyu Arshanendra Nugroho', 'Jakarta', '2020-01-10', 'L', 'KB', 2, '2024', '', 'KB', '', '', '', 'Arta kranji residence blok B23', '', '', '', 'Aris Nugroho', 'S1', 'KARYAWAN SWASTA', '', 'Ndaru pamungkas', 'S1', '', ''),
('TK0405', '202401025', '202401025', 'MUHAMMAD', 'BEKASI', '2020-08-22', 'L', 'KB', 1, '2024', 'MUHAMMAD', 'KB', '', '', '', 'Cluster Taman Firdausi No. 13, Jl. Kemang Sari 1, RT/RW 006/003, Kel. Jatibening baru Kec. Pondokgede, Kota Bekasi Jawa Barat 17412', '', '', '', 'FADHLURRAHMAN', 'S1', 'KARYAWAN SWASTA', '26 April 1994', 'SURYA MITRASARI', 'S2', 'KARYAWAN SWASTA', ''),
('TK0406', '202401026', '202401026', 'Labib Ali Madinah', 'Cianjur', '2020-04-21', 'L', 'KB', 2, '2024', 'Labib', 'KB', '', '', '', 'Pekayon', '', '', '', 'Nosa Danniswara', 'SMA', 'KARYAWAN SWASTA', '20 Februari 1989', 'Indriyani', 'S1', '', '26 Februari 1995'),
('TK0407', '202401027', '202401027', 'AISHA SABAI MIKHAINA', 'Jakarta', '2020-10-10', 'P', 'KB', 2, '2024', 'SABAI', 'KB', '', '', '', 'Jl. Malaka Merah Raya No. 38, Pondok Kopi, Jakarta Timur', '', '', '', 'Rendy Artanto', 'S1', 'WIRASWASTA', '', 'Adlina Annisa', 'S2', '', '20 Januari 1986'),
('TK0408', '202401028', '202401028', 'Salman Sae Al-Zuhair', 'Jakarta', '2020-06-13', 'L', 'KB', 4, '2024', 'Salman', 'KB', '', '', '', 'Royal Galaxy Residence Blok B No.2 Jakamulya, Bekasi', '', '', '', 'Lyco Adhy Purwoko', 'S2', 'KARYAWAN SWASTA', '25 Maret 1990', 'Riza Anjari Putri', 'S2', '', ''),
('TK0409', '202401030', '202401030', 'Alano Alvarendra Nugraha', 'Jakarta', '2020-09-26', 'L', 'KB', 1, '2024', 'Alano', 'KB', '', '', '', 'Pondok Timur Mas F2 No 11', '', '', '', 'Mohammad Dion Satria Nugraha', 'S1', 'KARYAWAN SWASTA', '', 'Mayda Rizky Hapsari', 'S1', 'KARYAWAN SWASTA', '30 Mei 1988'),
('TK0410', '202401031', '202401031', 'Hamzah Asadullah Siregar', 'Medan', '2020-07-09', 'L', 'KB', 2, '2024', 'Hamzah', 'KB', '', '', '', 'Perum. Villa Jakasetia. Blok K. No. 9. Jakasetia. Bekasi. Selatan. 17147', '', '', '', 'Fahry Rozy Siregar', 'S1', 'KARYAWAN SWASTA', '28 Juli 1992', 'Nadillah Putri Yanda', 'S1', '', ''),
('TK0411', '202401032', '202401032', 'Fatimah Azzahra', 'Ciputat', '2020-06-16', 'P', 'KB', 2, '2024', 'Ara', 'KB', '', '', '', 'Jln.pulau yapen 16 no 240 perumnas 3', '', '', '', 'Fanza sandita', 'D3', '', '15 Juni 1984', 'Irda Hayati', 'SMA', '', '13 November 1989'),
('TK0412', '202401033', '202401033', 'Razkadeva Qiellino Zarkasih', 'Bekasi', '2020-05-29', 'L', 'KB', 2, '2024', 'Deva', 'KB', '', '', '', 'Pondok Pekayon Indah Jl. Mahoni XIV Blok C1/15, Bekasi Selatan 17148', '', '', '', 'Iqbal Zarkasih', 'SMA', '', '20 Juli 1994', 'Astarie Khonsa Ayulita', 'S1', '', ''),
('TK0413', '202401034', '202401034', 'Zaynatul Maryam', 'Padang', '2020-12-13', 'P', 'KB', 2, '2024', 'Maryam', 'KB', '', '', '', 'Jl Kemandoran Gg. H. Tabrih No. 33 18 RT. 003 RW. 022', '', '', '', 'Eri Wandri', 'S1', 'KARYAWAN SWASTA', 'Bekasi', 'Haizawahyuni', 'S1', '', 'Bekasi'),
('TK0414', '202401035', '202401035', 'Keano Azka Natawidjaja', 'Jakarta', '2020-03-10', 'L', 'KB', 4, '2024', 'Keano', 'KB', '', '', '', 'Cluster bukit jatiasih Indah blok b no.1', '', '', '', 'Yudhitya Sjarief Natawidjaja', 'S1', 'KARYAWAN SWASTA', 'Cirebon, 21 September 1990', 'Khen madona', 'S1', 'KARYAWAN SWASTA', 'Yogyakarta'),
('TK0415', '202401036', '202401036', 'Nabila Sarah Aulia', 'Jakarta', '2020-10-23', 'P', 'KB', 4, '2024', 'Sarah', 'KB', '', '', '', 'Jl.pulo sirih timur 6 blok cb no.175 perum taman galaxy indah, Bekasi Selatan', '', '', '', 'Ali karomi', 'SMA', 'WIRASWASTA', 'Payakumbuh', 'Kamia', 'S1', '', 'Durian kapas'),
('TK0420', '202401037', '202401037', 'Rabi\'ati Khairatun Hisan', 'Bekasi', '2021-02-25', 'P', 'KB', 3, '2024', 'Aira', 'KB', '', '', '', 'nodata', '', '', '', 'Ust Adi Hidayat', 'S2', '', 'nodata', 'nodata', 'S1', '', 'nodata'),
('TK0422', '202401038', '202401038', 'Ibni Rasyid Al Affasi', 'Bekasi', '2020-12-10', 'L', 'KB', 1, '2024', 'Rasyid', 'KB', '', '', '', 'Villa Pekayon blok A1 no.6', '', '', '', 'Agus Taufiq Efendi', 'S1', 'WIRASWASTA', 'Bekasi, 27 September 1977', 'Novi Eka Damayanti', 'S1', '', 'Bekasi'),
('TK0423', '202401040', '202401040', 'Alfarizqi rayaz miharja', 'Bandar lampung', '2020-04-06', 'L', 'KB', 4, '2024', 'Rayaz', 'KB', '', '', '', 'Sakura regency 3 blok Q5 rt 01 rw 019 jatimulya tambun selatan kab bekasi', '', '', '', 'Padmi harja', 'D3', 'WIRASWASTA', 'Cirebon', 'Friesqa ayuningtias', 'S1', '', 'Yogyakarta'),
('TK0424', '202401041', '202401041', 'UKASYAH RUMI SHARIQ', 'Bekasi', '2020-12-02', 'L', 'KB', 3, '2024', 'UKASYAH', 'KB', '', '', '', 'Jln. Gurame 5 No. 305 RT. 06/07 Kel. Kayuringin Jaya Kec. Bekasi Selatan', '', '85294779336', 'rohimat2404@gmail.com', 'Rohimat', 'S1', 'KARYAWAN SWASTA', 'Sumedang, 24-04-1980', 'Hilda', 'S1', '', 'Jakarta, 14-12-1983'),
('TK0426', '202401042', '202401042', 'Hafizh Imtiyaz Suciantoro', 'Bekasi', '2020-04-13', 'L', 'KB', 4, '2024', 'hafizh', 'KB', '', '', '', 'Jl. Perintis 2 RT 03 RW 017 kavling Bulak Sentul kelurahan Harapan Jaya kecamatan Bekasi Utara kota Bekasi', '', '088213283919', 'bagus.suciantoro@gmail.com', 'Bagus Suciantoro', 'S1', 'KARYAWAN SWASTA', 'Sukoharjo, 24 Maret 1995', 'Nurul Safira Harwati', 'SMA', '', 'Bekasi, 22 Februari 1998'),
('TK0427', '202401043', '202401043', 'Eireen Madine Irawan', 'Bekasi', '2020-03-24', 'P', 'KB', 4, '2024', 'Eiren', 'KB', '', '', '', 'Jalan palem barat VII blok CC 28 no 9. Pondok Pekayon Indah', '', '082112382292', 'uninayunin@gmail.com', 'Ady Putra Irawan', 'S1', 'KARYAWAN SWASTA', 'jakarta, 22 Oktober 1990', 'Qurrota Ayunin', 'S1', '', 'Jakarta, 13 Januari 1993'),
('TK0428', '202401044', '202401044', 'MIKAEEL ESHAN ANGGORO', 'Jakarta', '2020-03-09', 'L', 'KB', 4, '2024', 'ESHAN', 'KB', '', '', '', 'Jl bintara jaya no 13', '', '089621181126', 'dinahgiyanti@gmail.com', 'Muhamad Khadapi', 'S1', 'WIRASWASTA', 'Jakarta, 04 Desember 1988', 'Dinah giyanti ruwaida', 'S1', 'KARYAWAN SWASTA', 'Jakarta, 04 Juni 1988'),
('TK0429', '202401045', '202401045', 'KENZYUGA IBRAHIM ARZHANKA ', 'Bekasi', '2020-06-16', 'L', 'KB', 4, '2024', 'Ibrahim ', 'KB', '', '', '', 'Alamat Tamara SD', '', '', '', 'Yusuf Pratama Wijanarko', 'S1', 'KARYAWAN SWASTA', 'Surabaya, 03 Mei 1992', 'Nur Listiya Manggarani Putri', 'S1', 'KARYAWAN SWASTA', 'Surabaya, 25 Juni 1992'),
('TK0430', '202401051', '202401051', 'LAIQA ELMEERA ALGAMI', 'BEKASI', '2021-05-02', 'P', 'KB', 4, '2024', 'GAMI', 'KB', '', '', '', 'Griya Pedurenan 6 No. 86A RT 008 RW 02, Kel. Jatiluhur ,Kec. Jatiasih , Bekasi 17425', '', '', '', 'ALGIFRI', 'SMA', 'WIRASWASTA', 'Padang, 05 Februari 1985', 'AMINAH SARI DAULAY', 'S1', 'KARYAWAN SWASTA', 'Jakarta, 16 Juli 1989'),
('TK0431', '202401052', '202401052', 'BACHTIAR AR RAZI', 'Jakarta', '2020-12-04', 'L', 'KB', 2, '2020', 'RAZI', 'KB', '', '', '', 'Taman cipulir estate blok D3 no 14. Cipadu jaya Larangan Tangerang', '', '', '', 'M. Edy Karyanto', 'S1', 'TNI', 'Pemalang, 21 Mei 1978', 'Prima Nika Helsis', 'S1', '', 'Padang, 07 September 1982'),
('TK0432', '202401053', '202401053', 'NADHIRA FAHIMA QONITA', 'SURAKARTA', '2020-09-18', 'P', 'KB', 2, '2024', 'NADHIRA', 'KB', '', '', '', 'Taman cipulir estate blok D3 no 14. Cipadu jaya Larangan Tangerang', '', '', '', 'RISDIAN FAJAROHMAN', 'D3', 'PNS', 'GUNUNGKIDUL, 24 Oktober 1990', 'TRIYA PUTRI RAHMAWATI UTAMI', 'S1', '', 'BEKASI, 12 April 1990'),
('TK0433', '202401054', '202401054', 'Nailah Al Maratush Sholihah Kautsar Putri', 'JAKARTA', '2020-02-24', 'P', 'KB', 4, '2024', 'NAILAH', 'KB', '', '', '', '', '', '', '', 'Achmad kautsar', 'S1', '', 'Jakarta, 19 November 1977', 'Lina marlina', 'SMA', '', 'GARUT, 19 November 1977'),
('TK0434', '202401055', '202401055', 'SA\'AD KHALILLURRAHIM FAHLEVI', 'JAKARTA', '2020-11-25', 'L', 'KB', 4, '2024', 'SA\'AD', 'KB', '', '', '', 'Lavenue Apartement South Tower Pancoran Jaksel', '', '081119032333', 'rezafahlevihui@gmail.com', 'Reza Fahlevi', 'S1', 'WIRASWASTA', 'Jakarta, 14 April 1988', 'Chaerani Andinawati', '', '', 'Bandung, 07 Mei 1992');

-- --------------------------------------------------------

--
-- Table structure for table `tahun`
--

CREATE TABLE `tahun` (
  `id` int(11) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `tgl_bln_thn` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tahun`
--

INSERT INTO `tahun` (`id`, `role`, `tgl_bln_thn`) VALUES
(1, 'guru', '10-Mar-2025');

-- --------------------------------------------------------

--
-- Table structure for table `tahun_ajaran`
--

CREATE TABLE `tahun_ajaran` (
  `id_tahun_ajaran` int(11) NOT NULL,
  `c_role` varchar(50) DEFAULT NULL,
  `tahun` varchar(50) DEFAULT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tahun_ajaran`
--

INSERT INTO `tahun_ajaran` (`id_tahun_ajaran`, `c_role`, `tahun`, `semester`, `status`) VALUES
(1, 'adm1', '2025/2026', '1', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_komentar`
--

CREATE TABLE `tbl_komentar` (
  `id` int(11) NOT NULL,
  `code_user` varchar(50) DEFAULT NULL,
  `isi_komentar` varchar(550) DEFAULT NULL,
  `room_id` varchar(50) DEFAULT NULL,
  `stamp` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`c_admin`);

--
-- Indexes for table `akses_otm`
--
ALTER TABLE `akses_otm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aplikasi`
--
ALTER TABLE `aplikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daily_siswa_approved`
--
ALTER TABLE `daily_siswa_approved`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_kelas`
--
ALTER TABLE `group_kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`c_guru`);

--
-- Indexes for table `history_password`
--
ALTER TABLE `history_password`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `info_pengumuman_pembayaran`
--
ALTER TABLE `info_pengumuman_pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kepala_sekolah`
--
ALTER TABLE `kepala_sekolah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_klp`
--
ALTER TABLE `m_klp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reason`
--
ALTER TABLE `reason`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ruang_pesan`
--
ALTER TABLE `ruang_pesan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`c_siswa`);

--
-- Indexes for table `tahun`
--
ALTER TABLE `tahun`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  ADD PRIMARY KEY (`id_tahun_ajaran`);

--
-- Indexes for table `tbl_komentar`
--
ALTER TABLE `tbl_komentar`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akses_otm`
--
ALTER TABLE `akses_otm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `aplikasi`
--
ALTER TABLE `aplikasi`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `daily_siswa_approved`
--
ALTER TABLE `daily_siswa_approved`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `group_kelas`
--
ALTER TABLE `group_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `history_password`
--
ALTER TABLE `history_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=394;

--
-- AUTO_INCREMENT for table `info_pengumuman_pembayaran`
--
ALTER TABLE `info_pengumuman_pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kepala_sekolah`
--
ALTER TABLE `kepala_sekolah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `m_klp`
--
ALTER TABLE `m_klp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reason`
--
ALTER TABLE `reason`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ruang_pesan`
--
ALTER TABLE `ruang_pesan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tahun`
--
ALTER TABLE `tahun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  MODIFY `id_tahun_ajaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_komentar`
--
ALTER TABLE `tbl_komentar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
