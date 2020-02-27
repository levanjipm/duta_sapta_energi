-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2020 at 03:43 PM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `duta_sapta_energi`
--

-- --------------------------------------------------------

--
-- Table structure for table `code_delivery_order`
--

CREATE TABLE `code_delivery_order` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `name` varchar(50) NOT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `is_sent` tinyint(1) NOT NULL DEFAULT '0',
  `guid` varchar(50) NOT NULL,
  `invoice_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `code_delivery_order`
--

INSERT INTO `code_delivery_order` (`id`, `date`, `name`, `is_confirm`, `is_delete`, `is_sent`, `guid`, `invoice_id`) VALUES
(3, '2020-02-05', 'DO-DSE-20200010', 1, 0, 0, '65E6A127-782D-4945-9611-7EAAC46A7E35', NULL),
(4, '2020-02-05', 'DO-DSE-20200020', 1, 0, 0, 'A8DF0F33-EBC7-46E2-B175-A1AE66CC9067', NULL),
(5, '2020-02-04', 'DO-DSE-20200030', 1, 0, 0, '0F681F77-6BCB-4FE4-BFB5-6F70C490A99C', NULL),
(6, '2020-02-05', 'DO-DSE-20200040', 1, 0, 0, '8A7BCF08-5AD9-4696-B5A8-4BE6FDAEA3DE', NULL),
(7, '2020-02-06', 'DO-DSE-20200050', 1, 0, 0, 'A929875B-3611-43CA-83E3-80DA2AB1E20E', NULL),
(8, '2020-02-08', 'DO-DSE-20200060', 1, 0, 0, '36672F44-1429-4CBF-ADC4-1196F3E3ADBF', NULL),
(9, '2020-02-08', 'DO-DSE-20200070', 1, 0, 0, 'BDA3B540-8509-4EDC-A3D6-95B66ED84FE5', NULL),
(10, '2020-02-08', 'DO-DSE-20200080', 1, 0, 0, '0A418982-1528-4511-AEFD-DFC598A8C165', NULL),
(11, '2020-02-10', 'DO-DSE-20200090', 1, 0, 0, 'DDF1737B-9996-4999-8051-4BE9A4F9A968', 0),
(12, '2020-02-10', 'DO-DSE-20200100', 1, 0, 0, '48F4054E-EE52-4804-AAA2-21761503A5E0', 0),
(13, '2020-02-10', 'DO-DSE-20200110', 1, 0, 0, 'E5BD6B7B-B2C8-4A10-9C99-7D56E5165416', 0),
(14, '2020-02-13', 'DO-DSE-20200120', 1, 0, 0, 'F8C3778B-26CB-4A5A-8454-07883D26CBF9', 0),
(15, '2020-02-13', 'DO-DSE-20200130', 1, 0, 0, 'F724B36A-8B14-47D0-A8D5-3790FB4C73BA', 0),
(16, '2020-02-13', 'DO-DSE-20200140', 1, 0, 0, 'F9488CD0-C628-485F-882A-0A5F0683F0A0', 0);

-- --------------------------------------------------------

--
-- Table structure for table `code_purchase_order`
--

CREATE TABLE `code_purchase_order` (
  `id` int(255) NOT NULL,
  `date` date NOT NULL,
  `name` varchar(50) NOT NULL,
  `supplier_id` int(255) NOT NULL,
  `created_by` int(255) NOT NULL,
  `confirmed_by` int(255) DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT '0',
  `promo_code` varchar(50) DEFAULT NULL,
  `dropship_address` text,
  `dropship_city` varchar(50) DEFAULT NULL,
  `dropship_contact_person` varchar(50) DEFAULT NULL,
  `dropship_contact` varchar(50) DEFAULT NULL,
  `taxing` tinyint(1) NOT NULL,
  `date_send_request` date DEFAULT NULL,
  `guid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `code_sales_order`
--

CREATE TABLE `code_sales_order` (
  `id` int(255) NOT NULL,
  `customer_id` int(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `taxing` tinyint(1) NOT NULL,
  `seller` int(255) DEFAULT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `guid` varchar(50) NOT NULL,
  `created_by` int(255) NOT NULL,
  `invoicing_method` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `code_sales_order`
--

INSERT INTO `code_sales_order` (`id`, `customer_id`, `name`, `date`, `taxing`, `seller`, `is_confirm`, `guid`, `created_by`, `invoicing_method`) VALUES
(1, 0, '202028042267', '2020-02-04', 0, NULL, 0, 'E9AD7117-6000-4D35-B6CE-BC664F550B2A', 0, 1),
(2, 1, '202060570674', '2020-02-04', 0, NULL, 0, 'A1397813-F724-440F-8240-7CD0AC353C89', 0, 1),
(3, 9, '202056481914', '2020-02-04', 0, NULL, 0, '6F951B9A-C18E-4E69-82F4-A7AA1A11F3E3', 0, 1),
(4, 0, '202025423628', '2020-02-06', 0, NULL, 0, '0E32B6A9-8F81-40B7-A17E-6737557C9CBD', 0, 2),
(5, 0, '202006116940', '2020-02-08', 0, NULL, 0, 'C9B6257D-64F4-4030-95D7-FDC39C3BED78', 0, 2),
(6, 26, '202016168968', '2020-02-13', 0, 1, 0, '86C80843-77DA-49CA-8CFB-9FAF14BDF8BA', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(500) NOT NULL,
  `number` varchar(10) DEFAULT NULL,
  `rt` varchar(3) DEFAULT NULL,
  `rw` varchar(3) NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `area_id` int(255) NOT NULL,
  `is_black_list` tinyint(1) NOT NULL DEFAULT '0',
  `block` varchar(10) NOT NULL,
  `npwp` varchar(20) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `pic_name` varchar(50) NOT NULL,
  `date_created` date DEFAULT NULL,
  `created_by` int(255) NOT NULL,
  `latitude` decimal(33,30) DEFAULT NULL,
  `longitude` decimal(33,30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `address`, `number`, `rt`, `rw`, `city`, `postal_code`, `area_id`, `is_black_list`, `block`, `npwp`, `phone_number`, `pic_name`, `date_created`, `created_by`, `latitude`, `longitude`) VALUES
(0, 'Toko Surya Indah', 'Jalan Mochammad Toha', '204A', '000', '000', 'Bandung', '40235', 3, 0, '', 'Bapak -', '(022) 5200016', 'Bapak -', NULL, 0, NULL, NULL),
(1, 'Toko Sumber Lampu', 'Jalan Jendral Ahmad Yani (Plaza IBCC)', '3', '029', '006', 'Bandung', '40271', 1, 0, 'B2', NULL, '(022) 7233271', 'Bapak Ayung', NULL, 1, NULL, NULL),
(5, 'Toko Agni Surya', 'Jalan Jendral Ahmad Yani', '353', '000', '000', 'Bandung', '40121', 1, 0, '', NULL, '(022) 7273893', 'Ibu Yani', '2020-01-24', 1, NULL, NULL),
(6, 'Toko Trijaya 2', 'Jalan Cikawao', '56', '001', '001', 'Bandung', '40261', 1, 0, '', NULL, '(022) 4220661', 'Bapak Yohan', '2020-01-24', 1, NULL, NULL),
(7, 'Toko Utama Lighting', 'Jalan Jendral Ahmad Yani (Plaza IBCC)', '12', '029', '006', 'Bandung', '40271', 1, 0, 'D2', NULL, '081224499786', 'Ibu Mimi', '2020-01-25', 1, NULL, NULL),
(8, 'Toko Surya Agung', 'Jalan H. Ibrahim Adjie (Bandung Trade Mall)', '47A', '005', '011', 'Bandung', '40283', 1, 0, 'C1', '', '(022) 7238333', 'Bapak Jajang Aji', '2020-01-29', 1, NULL, NULL),
(9, 'Toko Dua Saudara Electric', 'Jalan Pungkur', '51', '000', '000', 'Bandung', '40252', 3, 0, '', '', '08122033019', 'Bapak Hendrik', '2020-01-29', 1, NULL, NULL),
(11, 'Toko Buana Elektrik', 'Jalan Cinangka', '4', '000', '000', 'Bandung', '40616', 1, 0, '', '', '', 'Bapak Darma', '2020-01-29', 1, NULL, NULL),
(12, 'Toko Central Electronic', 'Jalan Mohammad Toha', '72', '000', '000', 'Bandung', '40243', 1, 0, '', '', '(022) 5225851', 'Ibu Siu Men', '2020-01-29', 1, NULL, NULL),
(13, 'Toko Kian Sukses', 'Jalan Cikutra', '106A', '000', '000', 'Bandung', '40124', 1, 0, '', '', '081298336288', 'Bapak Firman', '2020-01-29', 1, NULL, NULL),
(14, 'Toko AF Jaya Electronic', 'Jalan Raya Batujajar', '061', '000', '000', 'Bandung', '40561', 2, 0, '000', '', '085721163312', 'Mr. Jejen', '2020-01-31', 1, NULL, NULL),
(15, 'Toko Utama', 'Jalan Pasar Atas', '076', '000', '000', 'Cimahi', '40525', 2, 0, '000', '', '022-6654795', 'Mr. Sugianto', '2020-01-31', 1, NULL, NULL),
(16, 'Toko Sari Bakti', 'Jalan Babakan Sari I (Kebaktian)', '160', '000', '000', 'Bandung', '40283', 3, 0, '000', '', '082318303322', 'Mr. Jisman', '2020-01-31', 1, NULL, NULL),
(17, 'Toko Surya Indah Rancabolang', 'Jalan Rancabolang', '043', '000', '000', 'Bandung', '40286', 1, 0, '000', '', '081321250208', 'Mrs. Dewi', '2020-01-31', 1, NULL, NULL),
(18, 'Toko Bangunan HD', 'Jalan Cingised', '125', '000', '000', 'Bandung', '40293', 1, 0, '000', '', '08156275160', 'Mr. Rudy', '2020-01-31', 1, NULL, NULL),
(19, 'Toko Paranti', 'Jalan Jendral Ahmad Yani', '945', '000', '000', 'Bandung', '40282', 4, 0, '000', '', '085315073966', 'Mrs. Lili', '2020-01-31', 1, NULL, NULL),
(21, 'Toko Laksana', 'Jalan Ciroyom', '153', '000', '000', 'Bandung', '40183', 2, 0, '000', '', '08122396777', 'Mr. Tatang', '2020-01-31', 1, NULL, NULL),
(22, 'Toko Nirwana Electronic', 'Jalan Ciroyom', '117', '000', '000', 'Bandung', '40183', 2, 0, '000', '', '08122176094', 'Mr. Suwardi', '2020-01-31', 1, NULL, NULL),
(23, 'Toko Sinar Untung Electrical', 'Jalan Raya Dayeuh Kolot', '295', '000', '000', 'Bandung', '40258', 2, 0, '000', '', '082218456161', 'Mr. Kery', '2020-01-31', 1, NULL, NULL),
(24, 'Toko Depo Listrik', 'Jalan Jendral Ahmad Yani, Plaza IBCC LGF', '008', '000', '000', 'Bandung', '40271', 3, 0, 'D3', '', '022-7238318', 'Mr. Dadang', '2020-01-31', 1, NULL, NULL),
(25, 'Toko Krista Lighting', 'Jalan Jendral Ahmad Yani, Plaza IBCC', '12A', '000', '000', 'Bandung', '40114', 3, 0, 'D1', '', '022-7238369', 'Mr. Yendi', '2020-01-31', 1, NULL, NULL),
(26, 'Toko Prima', 'Jalan Surya Sumantri', '058', '000', '000', 'Bandung', '40164', 4, 0, '000', '', '022-2014967', 'Mrs. Uut', '2020-01-31', 1, NULL, NULL),
(27, 'Toko Sumber Rejeki', 'Jalan Jendral Ahmad Yani', '328', '000', '000', 'Bandung', '40271', 3, 0, '000', '', '081570265893', 'Mrs. Sinta', '2020-01-31', 1, NULL, NULL),
(28, 'Toko Sinar Agung', 'Jalan Caringin', '258', '000', '000', 'Bandung', '40223', 2, 0, '000', '', '022-6026321', 'Mr. Miming', '2020-01-31', 1, NULL, NULL),
(29, 'Toko Bangunan Kurniawan', 'Jalan Boling', '001', '000', '000', 'Bandung', '40293', 1, 0, '000', '', '085101223235', 'Mr. Kurniawan', '2020-01-31', 1, NULL, NULL),
(30, 'Toko Besi Adil', 'Jalan Gatot Subroto', '355', '000', '000', 'Bandung', '40724', 3, 0, '000', '', '08122047066', 'Mr. Julius', '2020-01-31', 1, NULL, NULL),
(31, 'Toko Karunia Sakti', 'Jalan Mohammad Toha', '210', '000', '000', 'Bandung', '40243', 2, 0, '000', '', '087827722212', 'Mrs. Alin', '2020-01-31', 1, NULL, NULL),
(32, 'Toko Aneka Nada', 'Jalan Jendral Ahmad Yani', '180', '000', '000', 'Bandung', '40262', 3, 0, '000', '', '089630011866', 'Mr. Ano', '2020-01-31', 1, NULL, NULL),
(33, 'Toko VIP Elektrik', 'Jalan Pahlawan', '049', '000', '000', 'Bandung', '40122', 4, 0, '000', '', '08122043095', 'Mr. Rudi', '2020-01-31', 1, NULL, NULL),
(34, 'Toko Mitra Elektrik', 'Jalan Raya Cileunyi', '036', '000', '000', 'Bandung', '40622', 1, 0, '000', '', '082129265391', 'Mr. Halifa', '2020-01-31', 1, NULL, NULL),
(35, 'Toko Remaja Teknik', 'Jalan Kiaracondong', '318', '000', '000', 'Bandung', '40275', 3, 0, '000', '', '022-7311813', 'Mrs. Dewi', '2020-01-31', 1, NULL, NULL),
(36, 'Toko Tang Mandiri', 'Jalan Holis', '321-325', '000', '000', 'Bandung', '40212', 2, 0, '000', '', '087779614332', 'Mr. Tikno', '2020-01-31', 1, NULL, NULL),
(37, 'Toko Bangunan Buana Jaya', 'Komplek Batununggal Indah Jalan Waas', '013', '000', '000', 'Bandung', '40266', 3, 0, '000', '', '087878878708', 'Mr. Tatang', '2020-02-01', 1, NULL, NULL),
(38, 'Toko Bangunan Kurniawan Jaya', 'Jalan Terusan Cibaduyut', '052', '000', '000', 'Bandung', '40239', 3, 0, '000', '', '022-5409888', 'Mrs. Lili', '2020-02-01', 1, NULL, NULL),
(39, 'Toko Serly Electric', 'Jalan Raya Cijerah', '242', '000', '000', 'Bandung', '40213', 2, 0, '000', '', '085220265002', 'Mr. Yayan', '2020-02-01', 1, NULL, NULL),
(40, 'Toko Bangunan Rahmat Putra', 'Jalan Terusan Jakarta', '272', '000', '000', 'Bandung', '40291', 1, 0, '000', '', '08122128363', 'Mr. Tanto', '2020-02-01', 1, NULL, NULL),
(41, 'Toko Bangunan Cahaya Logam', 'Jalan Babakan Ciparay', '088', '000', '000', 'Bandung', '40223', 2, 0, '000', '', '022-5402609', 'Mrs. Yani', '2020-02-01', 1, NULL, NULL),
(42, 'Toko Sumber Cahaya', 'Jalan Leuwigajah', '43C', '000', '000', 'Cimahi', '40522', 2, 0, '0000', '', '08988321110', 'Mrs. Nova Wiliana', '2020-02-01', 1, NULL, NULL),
(43, 'Toko D&P Electronics', 'Taman Kopo Indah II', '041', '000', '000', 'Bandung', '40218', 2, 0, '000', '', '08126526986', 'Mrs. Susanti', '2020-02-01', 1, NULL, NULL),
(44, 'Toko Bangunan Pusaka Jaya', 'Jalan Gardujati, Jendral Sudirman', '001', '000', '000', 'Bandung', '40181', 2, 0, '000', '', '022-6031756', 'Mrs. Jeni', '2020-02-01', 1, NULL, NULL),
(45, 'Toko Bangunan Raya Timur', 'Jalan Abdul Haris Nasution, Sindanglaya', '156', '000', '000', 'Bandung', '40293', 1, 0, '000', '', '085974113514', 'Mrs. Safiah', '2020-02-01', 1, NULL, NULL),
(46, 'Toko Guna Jaya Teknik', 'Jalan Sukamenak', '123', '000', '000', 'Bandung', '40228', 4, 0, '000', '', '0895802238369', 'Mrs. Yuliah', '2020-02-01', 1, NULL, NULL),
(47, 'Toko Bangunan Sinar Surya', 'Jalan Terusan Pasirkoja', '108', '000', '000', 'Bandung', '40232', 2, 0, '000', '', '022-6018088', 'Mrs. Mely', '2020-02-01', 1, NULL, NULL),
(48, 'Toko Pada Selamat', 'Jalan Raya Dayeuh Kolot', '314', '000', '000', 'Bandung', '40258', 2, 0, '000', '', '08985085885', 'Mr. Selamet', '2020-02-01', 1, NULL, NULL),
(49, 'Toko Bangunan Kopo Indah', 'Jalan Peta', '200', '000', '000', 'Bandung', '40233', 2, 0, '000', '', '022-6036149', 'Mr. Iwan', '2020-02-01', 1, NULL, NULL),
(50, 'Toko Yasa Elektronik', 'Jalan Margacinta', '165', '000', '000', 'Bandung', '40287', 1, 0, '000', '', '082115065506', 'Mr. Jajang', '2020-02-01', 1, NULL, NULL),
(51, 'Toko Fikriy Berkah Elektronik', 'Jalan Raya Jatinangor', '131', '000', '000', 'Bandung', '45363', 1, 0, '000', '', '082219561667', 'Mr. Agung', '2020-02-01', 1, NULL, NULL),
(52, 'Toko AA Electronic Service', 'Jalan Kyai Haji Ahmad Sadili', '194', '000', '000', 'Bandung', '40394', 1, 0, '000', '', '085316116595', 'Mr. Amir', '2020-02-01', 1, NULL, NULL),
(53, 'Toko Kencana Electric', 'Jalan Sultan Agung', '136', '000', '000', 'Pekalongan', '51126', 1, 0, '000', '', '0285-422035', 'Mr. Akiang', '2020-02-01', 1, NULL, NULL),
(54, 'Toko Kurnia Electronic', 'Jalan Raya Batujajar', '268', '000', '000', 'Bandung', '40561', 2, 0, '000', '', '085797993942', 'Mrs. Alda', '2020-02-01', 1, NULL, NULL),
(55, 'Toko Wira Elektrik', 'Jalan Buah Batu', '036', '000', '000', 'Bandung', '40262', 3, 0, '000', '', '08122136088', 'Mr. Budi', '2020-02-01', 1, NULL, NULL),
(56, 'Toko Bunga Elektrik', 'Jalan Cikondang', '025', '000', '000', 'Bandung', '40133', 4, 0, '000', '', '081320419469', 'Mr. Ayon', '2020-02-03', 1, NULL, NULL),
(57, 'Toko Cahaya Baru Cimuncang', 'Jalan Cimuncang', '037', '000', '000', 'Bandung', '40125', 4, 0, '000', '', '085782724800', 'Mr. Arif', '2020-02-03', 1, NULL, NULL),
(58, 'Toko Sinar Karapitan', 'Jalan Karapitan', '026', '000', '000', 'Bandung', '40261', 3, 0, '000', '', '022-4208474', 'Mr. Yangyang', '2020-02-03', 1, NULL, NULL),
(59, 'Toko Bintang Elektronik', 'Jalan Kebon Bibit, Balubur Town Square', '012', '000', '000', 'Bandung', '40132', 4, 0, '000', '', '022-76665492', 'Mr. Darman', '2020-02-03', 1, NULL, NULL),
(60, 'Toko Anam Elektronik', 'Jalan Kebon Kembang', '003', '000', '000', 'Bandung', '40116', 4, 0, '000', '', '022-4233870', 'Mr. Anam', '2020-02-03', 1, NULL, NULL),
(61, 'Toko Sinar Permata Jaya', 'Jalan Gegerkalong girang', '088', '000', '000', 'Bandung', '40153', 4, 0, '000', '', '081326453213', 'Mr. Andi', '2020-02-03', 1, NULL, NULL),
(62, 'Toko Aneka Niaga', 'Jalan Gegerkalong Tengah', '077', '000', '000', 'Bandung', '40153', 4, 0, '000', '', '022-2010184', 'Mr. Saiful', '2020-02-03', 1, NULL, NULL),
(63, 'Toko Altrik', 'Jalan Sarijadi Raya', '047', '000', '000', 'Bandung', '40151', 4, 0, '000', '', '082320420999', 'Mr. Firman', '2020-02-03', 1, NULL, NULL),
(64, 'Toko Kurnia Elektrik', 'Jalan Gegerkalong Hilir', '165', '000', '000', 'Bandung', '40153', 4, 0, '000', '', '082219152433', 'Mr. Is', '2020-02-03', 1, NULL, NULL),
(65, 'Toko Sinar Logam', 'Jalan Sariasih', '019', '000', '000', 'Bandung', '40151', 4, 0, '006', '', '022-2017598', 'Mr. Fajar', '2020-02-03', 1, NULL, NULL),
(66, 'Toko 8', 'Jalan Baladewa', '008', '000', '000', 'Bandung', '40173', 2, 0, '000', '', '022-6034875', 'Mr. Thomas', '2020-02-03', 1, NULL, NULL),
(67, 'Toko Glory Electric', 'Jalan Komud Supadio', '36A', '000', '000', 'Bandung', '40174', 2, 0, '000', '', '085974901894', 'Mr. Anton', '2020-02-03', 1, NULL, NULL),
(68, 'Toko Lestari', 'Jalan Rajawali Barat', '99A', '000', '000', 'Bandung', '40184', 2, 0, '000', '', '022-6044308', 'Mr. Dedi', '2020-02-03', 1, NULL, NULL),
(69, 'Toko 23', 'Jalan Kebon Kopi', '128', '000', '000', 'Cimahi', '40535', 2, 0, '000', '', '022-6018073', 'Mr. Nanan', '2020-02-03', 1, NULL, NULL),
(70, 'Toko Abadi', 'Jalan Gegerkalong Hilir', '073', '000', '000', 'Bandung', '40153', 4, 0, '000', '', '022-2010185', 'Mr. Arifin', '2020-02-03', 1, NULL, NULL),
(71, 'Toko Graha Electronic', 'Jalan Melong Asih', '071', '000', '000', 'Bandung', '40213', 2, 0, '000', '', '085722237789', 'Mr. Hendra', '2020-02-03', 1, NULL, NULL),
(72, 'Toko Asih', 'Jalan Melong Asih', '015', '000', '000', 'Cimahi', '40213', 2, 0, '000', '', '022-6016764', '', '2020-02-03', 1, NULL, NULL),
(73, 'Toko Mutiara Jaya', 'Jalan Holis', '330', '000', '000', 'Bandung', '40212', 2, 0, '000', '', '087823231177', 'Mr. Supandi', '2020-02-03', 1, NULL, NULL),
(74, 'Toko Berdikari', 'Jalan Holis', '328', '000', '000', 'Bandung', '40212', 2, 0, '000', '', '022-6010288', 'Mr. Ayung', '2020-02-04', 1, NULL, NULL),
(75, 'Toko Cipta Mandiri Electronics', 'Jalan Jendral Ahmad Yani, Jaya Plaza lt dasar', '238', '000', '000', 'Bandung', '40262', 3, 0, 'B5', '', '081220066835', 'Mr. Tino', '2020-02-04', 1, NULL, NULL),
(76, 'Toko Sinar Makmur Electronics', 'Jalan Jendral Ahmad Yani, Jaya Plaza lt dasar', '238', '000', '000', 'Bandung', '40262', 3, 0, 'G8', '', '081321450345', 'Mrs. Frenita', '2020-02-04', 1, NULL, NULL),
(77, 'Toko Aneka Electric', 'Jalan Jendral Ahmad Yani, Jaya Plaza lt dasar', '238', '000', '000', 'Bandung', '40271', 3, 0, 'C11-12', '', '022-7214731', 'Mr. Iksan', '2020-02-04', 1, NULL, NULL),
(78, 'Toko Tehnik Aneka Prima', 'Jalan Peta, Ruko Kopo Kencana', '002', '000', '000', 'Bandung', '40233', 2, 0, 'A3', '', '082219197020', 'Mr. Wendy Hauw', '2020-02-04', 1, NULL, NULL),
(79, 'CV Anugerah Jaya Lestari', 'Jalan Jamika', '106A', '000', '000', 'Bandung', '40231', 2, 0, '000', '', '082166008808', 'Mr. Frenky', '2020-02-04', 1, NULL, NULL),
(80, 'Toko 487', 'Jalan Terusan Gatot Subroto', '487', '000', '000', 'Bandung', '40284', 3, 0, '000', '', '08112143030', 'Mr. Udin', '2020-02-04', 1, NULL, NULL),
(81, 'Toko Agung Jaya', 'Jalan Kebon Jati', '264', '000', '000', 'Bandung', '40182', 2, 0, '000', '', '022-20564092', 'Mrs. Shirly', '2020-02-04', 1, NULL, NULL),
(82, 'Toko Alam Ria', 'Jalan Kopo Sayati', '122-124', '000', '000', 'Bandung', '40228', 3, 0, '000', '', '022-54413432', 'Mr. Mukian', '2020-02-04', 1, NULL, NULL),
(83, 'Toko Alka', 'Jalan Caringin', '002', '000', '000', 'Bandung', '40223', 2, 0, '000', '', '022-5408473', 'Mr. Mila Suryantini', '2020-02-04', 1, NULL, NULL),
(84, 'Toko Alvina Elektronik', 'Jalan Terusan Gatot Subroto', '487', '000', '000', 'Bandung', '40284', 3, 0, '000', '', '081222556066', 'Mr. Dedi', '2020-02-04', 1, NULL, NULL),
(85, 'Toko Aneka Teknik', 'Jalan Jamika', '121', '000', '000', 'Bandung', '40221', 2, 0, '000', '', '022-6024485', 'Mr. Akwet', '2020-02-04', 1, NULL, NULL),
(86, 'Toko Anugerah', 'Jalan Kopo', '356', '000', '000', 'Bandung', '40233', 2, 0, '000', '', '022-6016845', 'Mr. Acen', '2020-02-04', 1, NULL, NULL),
(87, 'Toko Asa', 'Jalan Lengkong Dalam', '009', '000', '000', 'Bandung', '40263', 3, 0, '000', '', '089641476277', 'Mrs. Herlina', '2020-02-04', 1, NULL, NULL),
(88, 'Toko Atari', 'Jalan Sukajadi', '039', '000', '000', 'Bandung', '40162', 4, 0, '000', '', '022-2036944', 'Mr. Adi', '2020-02-04', 1, NULL, NULL),
(89, 'Toko B-33', 'Jalan Banceuy Gang Cikapundung', '016', '000', '000', 'Bandung', '40111', 4, 0, '000', '', '081903151932', 'Mr. Engkus', '2020-02-04', 1, NULL, NULL),
(90, 'Toko Banciang', 'Jalan Gandawijaya', '149', '000', '000', 'Cimahi', '40524', 2, 0, '000', '', '022-6652162', 'Mr. Erik', '2020-02-04', 1, NULL, NULL),
(91, 'Toko Bandung Raya', 'Jalan Otto Iskandar Dinata', '322', '000', '000', 'Bandung', '40241', 2, 0, '000', '', '022-4231988', 'Mr. Tonny', '2020-02-04', 1, NULL, NULL),
(92, 'Toko Bangunan Hurip Jaya', 'Jalan Cikutra', '129A', '000', '000', 'Bandung', '40124', 4, 0, '000', '', '0818423316', 'Mr. Eko', '2020-02-04', 1, NULL, NULL),
(93, 'Toko Bangunan Key Kurnia Jaya', 'Jalan Manglid, Ruko Kopo Lestari', '016', '000', '000', 'Bandung', '40226', 3, 0, '000', '', '082119739191', 'Mr. Kurnia', '2020-02-04', 1, NULL, NULL),
(94, 'Toko Bangunan Mandiri', 'Jalan Babakan Sari I', '144', '000', '000', 'Bandung', '40283', 3, 0, '000', '', '082221000473', 'Mr. Mamat', '2020-02-04', 1, NULL, NULL),
(95, 'Toko Bangunan Mekar Indah', 'Jalan Terusan Jakarta', '177', '000', '000', 'Bandung', '40291', 1, 0, '000', '', '081285885152', 'Mr. Yudha', '2020-02-04', 1, NULL, NULL),
(96, 'Toko Bangunan Rossa', 'Jalan Mohammad Toha', '189', '000', '000', 'Bandung', '40243', 3, 0, '000', '', '081320003205', 'Mr. Rosa', '2020-02-04', 1, NULL, NULL),
(97, 'Toko Bangunan Sakti', 'Jalan Kopo', '499', '000', '000', 'Bandung', '40235', 2, 0, '000', '', '022-5401421', 'Mr. Michael', '2020-02-04', 1, NULL, NULL),
(98, 'Toko Bangunan Sarifah', 'Jalan Cikutra', '180', '000', '000', 'Bandung', '40124', 4, 0, '000', '', '081222125523', 'Mr. Yosef', '2020-02-04', 1, NULL, NULL),
(99, 'Toko Bangunan Sawargi', 'Jalan Sriwijaya', '20-22', '000', '000', 'Bandung', '40253', 3, 0, '000', '', '022-5229954', 'Mr. Wahid Hasim', '2020-02-04', 1, NULL, NULL),
(100, 'Toko Bangunan Tresnaco VI', 'Jalan Ciwastra', '086', '000', '000', 'Bandung', '40287', 1, 0, '000', '', '022-7562368', 'Mr. Aep', '2020-02-04', 1, NULL, NULL),
(101, 'Toko Baros Elektronik', 'Jalan Baros', '30-32', '000', '000', 'Cimahi', '40521', 2, 0, '000', '', '022-6642155', 'Mr. Hadi Kurniawan', '2020-02-04', 1, NULL, NULL),
(102, 'Toko Bintang Elektrik', 'Jalan Mekar Utama', '010', '000', '000', 'Bandung', '40237', 2, 0, '000', '', '085324106262', 'Mr. Bill', '2020-02-04', 1, NULL, NULL),
(103, 'Toko Cahaya Abadi', 'Jalan ABC, Pasar Cikapundung Gedung CEC lt.1', '017', '000', '000', 'Bandung', '40111', 4, 0, 'EE', '', '022-84460646', 'Mr. Ari', '2020-02-04', 1, NULL, NULL),
(104, 'Toko Cahaya Gemilang', 'Jalan Leuwi Panjang', '059', '000', '000', 'Bandung', '40234', 2, 0, '000', '', '0895807009085', 'Mrs. Paula', '2020-02-04', 1, NULL, NULL),
(105, 'Toko Chiefa Elektronik', 'Jalan Pamekar Raya', '001', '000', '000', 'Bandung', '40292', 1, 0, '000', '', '085220056200', 'Mr. Faizin', '2020-02-04', 1, NULL, NULL),
(106, 'Toko Ciumbuleuit', 'Jalan Ciumbuleuit', '009', '000', '000', 'Bandung', '40131', 4, 0, '000', '', '022-2032701', 'Mrs. Isan', '2020-02-04', 1, NULL, NULL),
(107, 'Toko CN Elektrik', 'Taman Kopo Indah Raya', '184B', '000', '000', 'Bandung', '40228', 3, 0, '000', '', '085100807853', 'Mrs. Michel', '2020-02-04', 1, NULL, NULL),
(108, 'Toko Denvar Elektronik', 'Jalan Raya Ujungberung', '367', '000', '000', 'Bandung', '40614', 1, 0, '000', '', '085323469911', 'Mr. Deden', '2020-02-04', 1, NULL, NULL),
(109, 'Toko Dragon CCTV', 'Jalan Peta, Komplek Bumi Kopo Kencana', '019', '000', '000', 'Bandung', '40233', 2, 0, 'E', '', '08122002178', 'Mr. Fendi', '2020-02-04', 1, NULL, NULL),
(110, 'Toko Dunia Bahan Bangunan Bandung', 'Jalan Raya Derwati', '089', '000', '000', 'Bandung', '40292', 1, 0, '000', '', '081299422379', 'Mr. Aldi', '2020-02-04', 1, NULL, NULL),
(111, 'Toko Dunia Electric', 'Jalan Otto Iskandar Dinata', '319', '000', '000', 'Bandung', '40251', 3, 0, '000', '', '022-4230423', 'Mr. Tedy', '2020-02-04', 1, NULL, NULL),
(112, 'Toko Fortuna Elektronik', 'Jalan Rancabolang Margahyu Raya', '045', '000', '000', 'Bandung', '40292', 1, 0, '000', '', '0817436868', 'Mrs. Ika', '2020-02-04', 1, NULL, NULL),
(113, 'Toko Golden Lite', 'Jalan Banceuy', '100', '000', '000', 'Bandung', '40111', 4, 0, '000', '', '081220016888', 'Mr. Joni', '2020-02-04', 1, NULL, NULL),
(114, 'Toko Bangunan Hadap Jaya', 'Jalan Margasari', '082', '000', '000', 'Bandung', '40287', 1, 0, '000', '', '022-7510948', 'Mrs. Suamiati', '2020-02-04', 1, NULL, NULL),
(115, 'Toko Bangunan Hadap Jaya II', 'Jalan Ciwastra', '169', '000', '000', 'Bandung', '40287', 1, 0, '000', '', '082115009077', 'Mr. David', '2020-02-04', 1, NULL, NULL),
(116, 'Perusahaan Dagang Hasil Jaya', 'Jalan Peta', '210', '000', '000', 'Bandung', '40231', 2, 0, '000', '', '022-6036170', 'Mrs. Sandra', '2020-02-04', 1, NULL, NULL),
(117, 'Toko Bangunan Hidup Sejahtera', 'Jalan Raya Barat', '785', '000', '000', 'Cimahi', '40526', 2, 0, '000', '', '081221204121', 'Mr. Sarip', '2020-02-04', 1, NULL, NULL),
(118, 'Toko Indo Mitra', 'Jalan Leuwi Panjang', '074', '000', '000', 'Bandung', '40233', 2, 0, '000', '', '081220691333', 'Mr. Chandra', '2020-02-04', 1, NULL, NULL),
(119, 'Toko Intio', 'Jalan Babakan Sari I', '105', '000', '000', 'Bandung', '40283', 3, 0, '000', '', '087815400681', 'Mr. Warto', '2020-02-04', 1, NULL, NULL),
(120, 'Toko Jatiluhur', 'Jalan Gandawijaya', '103', '000', '000', 'Cimahi', '40523', 2, 0, '000', '', '0811220270', 'Mr. Victor', '2020-02-04', 1, NULL, NULL),
(121, 'Toko Jaya Elektrik', 'Jalan Cilengkrang II', '012', '000', '000', 'Bandung', '40615', 1, 0, '000', '', '081313401812', 'Mr. Andi', '2020-02-04', 1, NULL, NULL),
(122, 'Toko Jaya Sakti', 'Jalan ABC, Pasar Cikapundung', '007', '000', '000', 'Bandung', '40111', 4, 0, 'Q', '', '081273037722', 'Mr. Teat', '2020-02-04', 1, NULL, NULL),
(123, 'Toko Jingga Elektronik', 'Jalan Raya Bojongsoang', '086', '000', '000', 'Bandung', '40288', 3, 0, '000', '', '089626491468', 'Mrs. Mita', '2020-02-04', 1, NULL, NULL),
(124, 'PT Kencana Elektrindo', 'Jalan Batununggal Indah I', '2A', '000', '000', 'Bandung', '40266', 2, 0, '000', '', '082217772889', 'Mr. Natanael', '2020-02-07', 1, NULL, NULL),
(125, 'Toko Lamora Elektrik', 'Jalan Babakan Sari I', '030', '000', '000', 'Bandung', '40283', 3, 0, '000', '', '081809900750', 'Mr. Andre', '2020-02-07', 1, NULL, NULL),
(126, 'Toko Laris Elektrik', 'Jalan Kiaracondong', '192A', '000', '000', 'Bandung', '40283', 3, 0, '000', '', '081220880699', 'Mr. Wili', '2020-02-07', 1, NULL, NULL),
(127, 'Toko MM Elektrik', 'Jalan Soekarno Hatta', '841', '000', '000', 'Bandung', '40293', 1, 0, '000', '', '082121977326', 'Mr. Miming', '2020-02-07', 1, NULL, NULL),
(128, 'Toko Mega Teknik', 'Jalan Jamika', '151B', '000', '000', 'Bandung', '40232', 2, 0, '000', '', '082124452324', 'Mr. Elvado', '2020-02-07', 1, NULL, NULL),
(129, 'Toko Merpati Elektrik', 'Jalan Otto Iskandar Dinata', '339', '000', '000', 'Bandung', '40251', 2, 0, '000', '', '081320663366', 'Mrs. Erline', '2020-02-07', 1, NULL, NULL),
(130, 'Toko Nceng', 'Jalan Bojong Koneng', '123', '000', '000', 'Bandung', '40191', 4, 0, '000', '', '081395112236', 'Mr. Enceng', '2020-02-07', 1, NULL, NULL),
(131, 'Toko Omega Electric', 'Jalan Indramayu', '012', '000', '000', 'Bandung', '40291', 1, 0, '000', '', '081322922888', 'Mrs. Diana', '2020-02-07', 1, NULL, NULL),
(132, 'Toko Panca Mulya', 'Jalan Kopo Sayati', '144', '000', '000', 'Bandung', '40228', 2, 0, '000', '', '022-5420586', 'Mrs. Dede', '2020-02-07', 1, NULL, NULL),
(133, 'Toko Pelita Putra', 'Ruko Gunung Batu', '009', '000', '000', 'Bandung', '40175', 2, 0, '000', '', '0811239777', 'Mr. Sunsun', '2020-02-07', 1, NULL, NULL),
(134, 'Toko Bangunan Pesantren II', 'Jalan Pagarsih', '339', '000', '000', 'Bandung', '40221', 2, 0, '000', '', '022-6040285', 'Mr. Yanto', '2020-02-07', 1, NULL, NULL),
(135, 'Toko Prima Elektrik', 'Jalan ABC Komplek Cikapundung Electronic Center lt.1', '003', '000', '000', 'Bandung', '40111', 4, 0, 'EE', '', '085227160748', 'Mr. Endhi', '2020-02-07', 1, NULL, NULL),
(136, 'Toko Purnama Jaya Electronic', 'Jalan Cibodas Raya', '006', '000', '000', 'Bandung', '40291', 1, 0, '000', '', '081224798744', 'Mr. Nurzaki', '2020-02-07', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_area`
--

CREATE TABLE `customer_area` (
  `id` int(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `major_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer_area`
--

INSERT INTO `customer_area` (`id`, `name`, `major_id`) VALUES
(1, 'Bandung Timur', NULL),
(2, 'Bandung Barat', NULL),
(3, 'Bandung Selatan', NULL),
(4, 'Bandung Utara', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `delivery_order`
--

CREATE TABLE `delivery_order` (
  `id` int(255) NOT NULL,
  `sales_order_id` int(255) NOT NULL,
  `code_delivery_order_id` int(255) NOT NULL,
  `quantity` int(255) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `delivery_order`
--

INSERT INTO `delivery_order` (`id`, `sales_order_id`, `code_delivery_order_id`, `quantity`) VALUES
(1, 2, 3, 20),
(2, 3, 3, 5),
(3, 2, 4, 20),
(4, 3, 4, 0),
(5, 1, 5, 10),
(6, 2, 6, 0),
(7, 3, 6, 10),
(8, 4, 7, 1),
(9, 5, 8, 5),
(10, 5, 9, 1),
(11, 5, 10, 1),
(12, 1, 11, 1),
(13, 5, 12, 1),
(14, 5, 13, 1),
(15, 5, 14, 1),
(16, 1, 15, 1),
(17, 6, 16, 1);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(255) NOT NULL,
  `reference` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `reference`, `name`, `type`) VALUES
(1, 'NYM21_50_EXT', 'Kabel NYM 2 x 1,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 2),
(2, 'NYM21_100_EXT', 'Kabel NYM 2 x 1,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 2),
(3, 'NYM21_250_EXT', 'Kabel NYM 2 x 1,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 2),
(4, 'NYM21_500_EXT', 'Kabel NYM 2 x 1,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 2),
(5, 'NYM22_50_EXT', 'Kabel NYM 2 x 2,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 2),
(6, 'NYM22_100_EXT', 'Kabel NYM 2 x 2,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 2),
(7, 'NYM22_250_EXT', 'Kabel NYM 2 x 2,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 2),
(8, 'NYM22_500_EXT', 'Kabel NYM 2 x 2,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 2),
(9, 'NYM21_1000_EXT', 'Kabel NYM 2 x 1,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 2),
(10, 'NYM22_1000_EXT', 'Kabel NYM 2 x 2,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 2),
(11, 'NYM31_50_EXT', 'Kabel NYM 3 x 1,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 2),
(12, 'NYM31_100_EXT', 'Kabel NYM 3 x 1,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 2),
(13, 'NYM31_250_EXT', 'Kabel NYM 3 x 1,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 2),
(14, 'NYM31_500_EXT', 'Kabel NYM 3 x 1,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 2),
(15, 'NYM31_1000_EXT', 'Kabel NYM 3 x 1,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 2),
(16, 'NYM32_50_EXT', 'Kabel NYM 3 x 2,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 2),
(17, 'NYM32_100_EXT', 'Kabel NYM 3 x 2,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 2),
(18, 'NYM32_250_EXT', 'Kabel NYM 3 x 2,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 2),
(19, 'NYM32_500_EXT', 'Kabel NYM 3 x 2,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 2),
(20, 'NYM32_1000_EXT', 'Kabel NYM 3 x 2,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 2),
(21, 'NYM41_50_EXT', 'Kabel NYM 4 x 1,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 2),
(22, 'NYM41_100_EXT', 'Kabel NYM 4 x 1,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 2),
(23, 'NYM41_250_EXT', 'Kabel NYM 4 x 1,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 2),
(24, 'NYM41_500_EXT', 'Kabel NYM 4 x 1,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 2),
(25, 'NYM41_1000_EXT', 'Kabel NYM 4 x 1,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 2),
(26, 'NYM42_50_EXT', 'Kabel NYM 4 x 2,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 2),
(27, 'NYM42_100_EXT', 'Kabel NYM 4 x 2,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 2),
(28, 'NYM42_250_EXT', 'Kabel NYM 4 x 2,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 2),
(29, 'NYM42_500_EXT', 'Kabel NYM 4 x 2,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 2),
(30, 'NYM42_1000_EXT', 'Kabel NYM 4 x 2,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 2),
(31, 'NYY21_50_EXT', 'Kabel NYY 2 x 1,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 3),
(32, 'NYY21_100_EXT', 'Kabel NYY 2 x 1,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 3),
(33, 'NYY21_250_EXT', 'Kabel NYY 2 x 1,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 3),
(34, 'NYY21_500_EXT', 'Kabel NYY 2 x 1,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 3),
(35, 'NYY21_1000_EXT', 'Kabel NYY 2 x 1,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 3),
(36, 'NYY22_50_EXT', 'Kabel NYY 2 x 2,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 3),
(37, 'NYY22_100_EXT', 'Kabel NYY 2 x 2,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 3),
(38, 'NYY22_250_EXT', 'Kabel NYY 2 x 2,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 3),
(39, 'NYY22_500_EXT', 'Kabel NYY 2 x 2,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 3),
(40, 'NYY22_1000_EXT', 'Kabel NYY 2 x 2,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 3),
(41, 'NYY31_50_EXT', 'Kabel NYY 3 x 1,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 3),
(42, 'NYY31_100_EXT', 'Kabel NYY 3 x 1,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 3),
(43, 'NYY31_250_EXT', 'Kabel NYY 3 x 1,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 3),
(44, 'NYY31_500_EXT', 'Kabel NYY 3 x 1,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 3),
(45, 'NYY31_1000_EXT', 'Kabel NYY 3 x 1,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 3),
(46, 'NYY32_50_EXT', 'Kabel NYY 3 x 2,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 3),
(47, 'NYY32_100_EXT', 'Kabel NYY 3 x 2,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 3),
(48, 'NYY32_250_EXT', 'Kabel NYY 3 x 2,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 3),
(49, 'NYY32_500_EXT', 'Kabel NYY 3 x 2,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 3),
(50, 'NYY32_1000_EXT', 'Kabel NYY 3 x 2,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 3);

-- --------------------------------------------------------

--
-- Table structure for table `item_class`
--

CREATE TABLE `item_class` (
  `id` int(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `created_by` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_class`
--

INSERT INTO `item_class` (`id`, `name`, `description`, `created_by`) VALUES
(2, 'Kabel NYM retail', 'Kabel NYM dengan ukuran per core lebih kecil dari 4mm<sup>2</sup>', 1),
(3, 'Kabel NYY retail', 'Kabel NYY dengan ukuran per core lebih kecil dari 4mm2 dan jumlah core lebih kecil atau sama dengan 4', 1),
(4, 'Kabel NYM project', 'Kabel NYM dengan ukuran per core sama dengan atau lebih besar dari 4mm<sup>2</sup>', 1);

-- --------------------------------------------------------

--
-- Table structure for table `price_list`
--

CREATE TABLE `price_list` (
  `id` int(255) NOT NULL,
  `item_id` int(255) NOT NULL,
  `price_list` decimal(20,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `price_list`
--

INSERT INTO `price_list` (`id`, `item_id`, `price_list`) VALUES
(5, 1, '328000.000'),
(6, 2, '656000.000'),
(7, 3, '1640000.000'),
(8, 4, '3280000.000'),
(9, 9, '6560000.000'),
(10, 5, '470400.000'),
(11, 6, '940800.000'),
(12, 7, '2352000.000'),
(13, 8, '4704000.000'),
(14, 10, '9408000.000'),
(15, 11, '420800.000'),
(16, 12, '841600.000'),
(17, 13, '2104000.000'),
(18, 14, '4208000.000'),
(19, 15, '8416000.000'),
(20, 16, '600000.000'),
(21, 17, '1200000.000'),
(22, 18, '3000000.000'),
(23, 19, '6000000.000'),
(24, 20, '12000000.000'),
(25, 21, '508800.000'),
(26, 22, '1017600.000'),
(27, 23, '2544000.000'),
(28, 24, '5088000.000'),
(29, 25, '10176000.000'),
(30, 26, '764000.000'),
(31, 27, '1528000.000'),
(32, 28, '3820000.000'),
(33, 29, '7640000.000'),
(34, 30, '1528000.000'),
(35, 31, '464000.000'),
(36, 32, '928000.000'),
(37, 33, '2320000.000'),
(38, 34, '4640000.000'),
(39, 35, '9280000.000'),
(40, 36, '624000.000'),
(41, 37, '1248000.000'),
(42, 38, '3120000.000'),
(43, 39, '6240000.000'),
(44, 40, '12480000.000'),
(45, 41, '572000.000'),
(46, 42, '1144000.000'),
(47, 43, '2860000.000'),
(48, 44, '5720000.000'),
(49, 45, '11440000.000'),
(50, 46, '768000.000'),
(51, 47, '1536000.000'),
(52, 48, '3840000.000'),
(53, 49, '7680000.000'),
(54, 50, '15360000.000'),
(56, 1, '200000.000');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

CREATE TABLE `purchase_order` (
  `id` int(255) NOT NULL,
  `item_id` int(255) NOT NULL,
  `price_list` decimal(50,4) NOT NULL,
  `net_price` decimal(50,4) NOT NULL,
  `quantity` int(255) NOT NULL,
  `code_purchase_order_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order`
--

CREATE TABLE `sales_order` (
  `id` int(255) NOT NULL,
  `price_list_id` int(255) NOT NULL,
  `discount` decimal(10,4) NOT NULL,
  `quantity` int(255) NOT NULL,
  `sent` int(255) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `code_sales_order_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales_order`
--

INSERT INTO `sales_order` (`id`, `price_list_id`, `discount`, `quantity`, `sent`, `status`, `code_sales_order_id`) VALUES
(1, 30, '18.0000', 20, 12, 0, 2),
(2, 30, '18.0000', 20, 20, 1, 3),
(3, 43, '10.0000', 10, 10, 1, 3),
(4, 30, '20.0000', 1, 1, 1, 4),
(5, 30, '18.0000', 10, 10, 1, 5),
(6, 54, '14.0000', 20, 1, 0, 6);

-- --------------------------------------------------------

--
-- Table structure for table `stock_in`
--

CREATE TABLE `stock_in` (
  `id` int(255) NOT NULL,
  `item_id` int(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `residue` int(255) NOT NULL,
  `supplier_id` int(255) DEFAULT NULL,
  `customer_id` int(25) DEFAULT NULL,
  `code_good_receipt_id` int(255) DEFAULT NULL,
  `code_sales_return_id` int(255) DEFAULT NULL,
  `code_event_id` int(255) DEFAULT NULL,
  `price` decimal(50,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stock_out`
--

CREATE TABLE `stock_out` (
  `id` int(255) NOT NULL,
  `in_id` int(255) NOT NULL,
  `quantity` int(255) DEFAULT NULL,
  `customer_id` int(255) DEFAULT NULL,
  `supplier_id` int(255) DEFAULT NULL,
  `code_delivery_order_id` int(255) DEFAULT NULL,
  `code_event_id` int(255) DEFAULT NULL,
  `code_purchase_return_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(500) NOT NULL,
  `number` varchar(10) DEFAULT NULL,
  `rt` varchar(3) DEFAULT NULL,
  `rw` varchar(3) NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `is_black_list` tinyint(1) NOT NULL DEFAULT '0',
  `block` varchar(10) NOT NULL,
  `npwp` varchar(20) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `pic_name` varchar(50) NOT NULL,
  `date_created` date DEFAULT NULL,
  `created_by` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `name`, `address`, `number`, `rt`, `rw`, `city`, `postal_code`, `is_black_list`, `block`, `npwp`, `phone_number`, `pic_name`, `date_created`, `created_by`) VALUES
(1, 'PT Prima Indah Lestari', 'Jalan Kamal Raya', '83', '003', '002', 'Jakarta Barat', '11820', 0, '000', '01.737.201.2-038.000', '(021) 5550861', 'Ibu Lina', '2020-01-27', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(500) NOT NULL,
  `bank_account` varchar(50) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `entry_date` date DEFAULT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `address`, `bank_account`, `is_active`, `entry_date`, `password`, `email`, `role`) VALUES
(1, 'Daniel Tri', 'Jalan Jamuju no. 18, Bandung', '8090175441', 1, '2020-01-01', '27a9dc715a8e1b472ba494313425de62', 'danielrudianto12@gmail.com', 'super_admin');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_code_delivery_order`
-- (See below for the actual view)
--
CREATE TABLE `view_code_delivery_order` (
`id` int(11)
,`date` date
,`name` varchar(50)
,`is_confirm` tinyint(1)
,`is_delete` tinyint(1)
,`is_sent` tinyint(1)
,`guid` varchar(50)
,`invoice_id` int(255)
,`sales_order_name` varchar(100)
,`customer_name` varchar(100)
,`address` varchar(500)
,`city` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_delivery_order_detail`
-- (See below for the actual view)
--
CREATE TABLE `view_delivery_order_detail` (
`id` int(255)
,`code_delivery_order_id` int(11)
,`quantity` int(255)
,`reference` varchar(50)
,`name` varchar(100)
,`price_list` decimal(20,3)
,`discount` decimal(10,4)
);

-- --------------------------------------------------------

--
-- Structure for view `view_code_delivery_order`
--
DROP TABLE IF EXISTS `view_code_delivery_order`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_code_delivery_order`  AS  select `code_delivery_order`.`id` AS `id`,`code_delivery_order`.`date` AS `date`,`code_delivery_order`.`name` AS `name`,`code_delivery_order`.`is_confirm` AS `is_confirm`,`code_delivery_order`.`is_delete` AS `is_delete`,`code_delivery_order`.`is_sent` AS `is_sent`,`code_delivery_order`.`guid` AS `guid`,`code_delivery_order`.`invoice_id` AS `invoice_id`,`code_sales_order`.`name` AS `sales_order_name`,`customer`.`name` AS `customer_name`,`customer`.`address` AS `address`,`customer`.`city` AS `city` from ((((`code_delivery_order` join `delivery_order` on((`code_delivery_order`.`id` = `delivery_order`.`code_delivery_order_id`))) join `sales_order` on((`delivery_order`.`sales_order_id` = `sales_order`.`id`))) join `code_sales_order` on((`code_sales_order`.`id` = `sales_order`.`code_sales_order_id`))) join `customer` on((`code_sales_order`.`customer_id` = `customer`.`id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view_delivery_order_detail`
--
DROP TABLE IF EXISTS `view_delivery_order_detail`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_delivery_order_detail`  AS  select `delivery_order`.`id` AS `id`,`code_delivery_order`.`id` AS `code_delivery_order_id`,`delivery_order`.`quantity` AS `quantity`,`item`.`reference` AS `reference`,`item`.`name` AS `name`,`price_list`.`price_list` AS `price_list`,`sales_order`.`discount` AS `discount` from ((((`delivery_order` join `sales_order` on((`delivery_order`.`sales_order_id` = `sales_order`.`id`))) join `price_list` on((`price_list`.`item_id` = `sales_order`.`price_list_id`))) join `item` on((`price_list`.`item_id` = `item`.`id`))) join `code_delivery_order` on((`code_delivery_order`.`id` = `delivery_order`.`code_delivery_order_id`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `code_delivery_order`
--
ALTER TABLE `code_delivery_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `code_purchase_order`
--
ALTER TABLE `code_purchase_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `code_sales_order`
--
ALTER TABLE `code_sales_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `area_id` (`area_id`);

--
-- Indexes for table `customer_area`
--
ALTER TABLE `customer_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_order`
--
ALTER TABLE `delivery_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_order_id` (`sales_order_id`),
  ADD KEY `code_delivery_order_id` (`code_delivery_order_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `item_class`
--
ALTER TABLE `item_class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `price_list`
--
ALTER TABLE `price_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code_purchase_order_id` (`code_purchase_order_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `sales_order`
--
ALTER TABLE `sales_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_in`
--
ALTER TABLE `stock_in`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `code_delivery_order`
--
ALTER TABLE `code_delivery_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `code_purchase_order`
--
ALTER TABLE `code_purchase_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `code_sales_order`
--
ALTER TABLE `code_sales_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `customer_area`
--
ALTER TABLE `customer_area`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `delivery_order`
--
ALTER TABLE `delivery_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `item_class`
--
ALTER TABLE `item_class`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `price_list`
--
ALTER TABLE `price_list`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order`
--
ALTER TABLE `sales_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stock_in`
--
ALTER TABLE `stock_in`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `code_sales_order`
--
ALTER TABLE `code_sales_order`
  ADD CONSTRAINT `code_sales_order_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `customer_area` (`id`);

--
-- Constraints for table `delivery_order`
--
ALTER TABLE `delivery_order`
  ADD CONSTRAINT `delivery_order_ibfk_1` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_order` (`id`),
  ADD CONSTRAINT `delivery_order_ibfk_2` FOREIGN KEY (`code_delivery_order_id`) REFERENCES `code_delivery_order` (`id`);

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`type`) REFERENCES `item_class` (`id`);

--
-- Constraints for table `price_list`
--
ALTER TABLE `price_list`
  ADD CONSTRAINT `price_list_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`);

--
-- Constraints for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD CONSTRAINT `purchase_order_ibfk_1` FOREIGN KEY (`code_purchase_order_id`) REFERENCES `code_purchase_order` (`id`),
  ADD CONSTRAINT `purchase_order_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`);

--
-- Constraints for table `stock_in`
--
ALTER TABLE `stock_in`
  ADD CONSTRAINT `stock_in_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `stock_in_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`),
  ADD CONSTRAINT `stock_in_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
