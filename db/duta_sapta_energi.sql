-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Okt 2020 pada 09.14
-- Versi server: 10.4.13-MariaDB
-- Versi PHP: 7.2.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
-- Struktur dari tabel `attendance_list`
--

CREATE TABLE `attendance_list` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `date` date NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `attendance_status`
--

CREATE TABLE `attendance_status` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `point` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `attendance_status`
--

INSERT INTO `attendance_status` (`id`, `name`, `description`, `point`) VALUES
(1, 'Sick', 'This status is created to compensate sick employee. Employee will not receive any benefit on that day.', 1),
(2, 'Present', 'This status is created to accommodate employee\'s attendance. ', 1),
(3, 'Absent', 'This status is created to accommodate employee\'s absence. Employee will not receive any benefit on that day.', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `bank_assignment`
--

CREATE TABLE `bank_assignment` (
  `id` int(255) NOT NULL,
  `bank_id` int(255) NOT NULL,
  `income_id` int(255) DEFAULT NULL,
  `expense_id` int(255) DEFAULT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `bank_transaction`
--

CREATE TABLE `bank_transaction` (
  `id` int(255) NOT NULL,
  `value` decimal(50,2) NOT NULL,
  `date` date NOT NULL,
  `transaction` int(11) NOT NULL,
  `customer_id` int(255) DEFAULT NULL,
  `supplier_id` int(255) DEFAULT NULL,
  `other_id` int(255) DEFAULT NULL,
  `internal_account_id` int(255) DEFAULT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT 0,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `bank_transaction_major` int(255) DEFAULT NULL,
  `account_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `benefit`
--

CREATE TABLE `benefit` (
  `id` int(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `information` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `benefit`
--

INSERT INTO `benefit` (`id`, `name`, `information`) VALUES
(3, 'Transportation', 'Employee\'s benefit regarding transportation'),
(5, 'Telecomunication', 'Employee\'s benefit regarding telecomunication'),
(6, 'Holiday Allowance', 'Employee\'s benefit regarding holiday\'s allowance. Given on the month that Eid is celebrated.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `billing`
--

CREATE TABLE `billing` (
  `id` int(255) NOT NULL,
  `invoice_id` int(255) NOT NULL,
  `result` tinyint(1) NOT NULL DEFAULT 0,
  `note` text NOT NULL,
  `code_billing_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_billing`
--

CREATE TABLE `code_billing` (
  `id` int(255) NOT NULL,
  `date` date NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_by` int(255) NOT NULL,
  `billed_by` int(255) NOT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT 0,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `is_reported` tinyint(1) NOT NULL DEFAULT 0,
  `confirmed_by` int(255) DEFAULT NULL,
  `reported_by` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_delivery_order`
--

CREATE TABLE `code_delivery_order` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `name` varchar(50) NOT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT 0,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `is_sent` tinyint(1) NOT NULL DEFAULT 0,
  `guid` varchar(50) NOT NULL,
  `invoice_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_event`
--

CREATE TABLE `code_event` (
  `id` int(255) NOT NULL,
  `type` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_by` int(255) NOT NULL,
  `date` date NOT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT 0,
  `confirmed_by` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_good_receipt`
--

CREATE TABLE `code_good_receipt` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT 0,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `invoice_id` int(255) DEFAULT NULL,
  `received_date` date NOT NULL,
  `created_by` int(255) NOT NULL,
  `confirmed_by` int(255) DEFAULT NULL,
  `guid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_purchase_order`
--

CREATE TABLE `code_purchase_order` (
  `id` int(255) NOT NULL,
  `date` date NOT NULL,
  `name` varchar(50) NOT NULL,
  `supplier_id` int(255) NOT NULL,
  `created_by` int(255) NOT NULL,
  `confirmed_by` int(255) DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `promo_code` varchar(50) DEFAULT NULL,
  `dropship_address` text DEFAULT NULL,
  `dropship_city` varchar(50) DEFAULT NULL,
  `dropship_contact_person` varchar(50) DEFAULT NULL,
  `dropship_contact` varchar(50) DEFAULT NULL,
  `taxing` tinyint(1) NOT NULL,
  `date_send_request` date DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `guid` varchar(50) NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `is_confirm` tinyint(1) NOT NULL DEFAULT 0,
  `note` text NOT NULL,
  `payment` int(2) NOT NULL DEFAULT 60
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_purchase_return`
--

CREATE TABLE `code_purchase_return` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `supplier_id` int(255) NOT NULL,
  `created_by` int(255) NOT NULL,
  `created_date` date NOT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT 0,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `confirmed_by` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_purchase_return_sent`
--

CREATE TABLE `code_purchase_return_sent` (
  `id` int(255) NOT NULL,
  `created_by` int(255) NOT NULL,
  `created_date` date NOT NULL,
  `confirmed_by` int(255) DEFAULT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT 0,
  `is_delete` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT 0,
  `bank_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_quotation`
--

CREATE TABLE `code_quotation` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `created_by` int(255) NOT NULL,
  `is_confirm` tinyint(4) NOT NULL DEFAULT 0,
  `is_delete` tinyint(4) NOT NULL DEFAULT 0,
  `confirmed_by` int(255) DEFAULT NULL,
  `customer_id` int(255) NOT NULL,
  `taxing` tinyint(1) NOT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_sales_order`
--

CREATE TABLE `code_sales_order` (
  `id` int(255) NOT NULL,
  `customer_id` int(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `taxing` tinyint(1) NOT NULL,
  `seller` int(255) DEFAULT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT 0,
  `confirmed_by` int(255) DEFAULT NULL,
  `guid` varchar(50) NOT NULL,
  `created_by` int(255) NOT NULL,
  `invoicing_method` tinyint(1) NOT NULL DEFAULT 1,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_sales_order_close_request`
--

CREATE TABLE `code_sales_order_close_request` (
  `id` int(255) NOT NULL,
  `date` date NOT NULL,
  `code_sales_order_id` int(255) NOT NULL,
  `information` text NOT NULL,
  `is_approved` tinyint(1) DEFAULT NULL,
  `approved_by` int(255) DEFAULT NULL,
  `approved_date` date DEFAULT NULL,
  `created_by` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_sales_return`
--

CREATE TABLE `code_sales_return` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_by` int(255) NOT NULL,
  `created_date` date NOT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT 0,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `confirmed_by` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_sales_return_received`
--

CREATE TABLE `code_sales_return_received` (
  `id` int(255) NOT NULL,
  `created_by` int(255) NOT NULL,
  `created_date` date NOT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT 0,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `confirmed_by` int(255) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT 0,
  `bank_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_visit_list`
--

CREATE TABLE `code_visit_list` (
  `id` int(255) NOT NULL,
  `date` date NOT NULL,
  `created_by` int(255) NOT NULL,
  `created_date` date NOT NULL,
  `visited_by` int(255) NOT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT 0,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `confirmed_by` int(255) DEFAULT NULL,
  `is_reported` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `customer`
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
  `is_black_list` tinyint(1) NOT NULL DEFAULT 0,
  `block` varchar(10) NOT NULL,
  `npwp` varchar(20) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `pic_name` varchar(50) NOT NULL,
  `date_created` date DEFAULT NULL,
  `created_by` int(255) NOT NULL,
  `latitude` decimal(33,30) DEFAULT NULL,
  `longitude` decimal(33,30) DEFAULT NULL,
  `term_of_payment` int(255) NOT NULL DEFAULT 45,
  `plafond` decimal(50,2) NOT NULL,
  `is_remind` tinyint(1) NOT NULL DEFAULT 1,
  `visiting_frequency` int(1) NOT NULL DEFAULT 1,
  `uid` varchar(50) NOT NULL,
  `password` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `customer`
--

INSERT INTO `customer` (`id`, `name`, `address`, `number`, `rt`, `rw`, `city`, `postal_code`, `area_id`, `is_black_list`, `block`, `npwp`, `phone_number`, `pic_name`, `date_created`, `created_by`, `latitude`, `longitude`, `term_of_payment`, `plafond`, `is_remind`, `visiting_frequency`, `uid`, `password`) VALUES
(1, 'Toko Sumber Lampu', 'Jalan Jendral Ahmad Yani (Plaza IBCC)', '3', '029', '006', 'Kota Bandung', '40271', 1, 0, 'B2', NULL, '(022) 7233271', 'Bapak Ayung', '2020-01-24', 1, NULL, NULL, 45, '3000000.00', 1, 28, '72297039', NULL),
(5, 'Toko Agni Surya', 'Jalan Jendral Ahmad Yani', '353', '000', '000', 'Kota Bandung', '40121', 1, 0, '', NULL, '(022) 7273893', 'Ibu Yani', '2020-01-24', 1, NULL, NULL, 45, '15000000.00', 1, 28, '09715857', NULL),
(6, 'Toko Trijaya 2', 'Jalan Cikawao', '56', '001', '001', 'Kota Bandung', '40261', 1, 0, '', NULL, '(022) 4220661', 'Bapak Yohan', '2020-01-24', 1, NULL, NULL, 45, '3000000.00', 1, 28, '45860382', NULL),
(7, 'Toko Utama Lighting', 'Jalan Jendral Ahmad Yani (Plaza IBCC)', '12', '029', '006', 'Kota Bandung', '40271', 1, 0, 'D2', NULL, '081224499786', 'Ibu Mimi', '2020-01-25', 1, NULL, NULL, 45, '3000000.00', 1, 28, '51644842', NULL),
(8, 'Toko Surya Agung', 'Jalan H. Ibrahim Adjie (Bandung Trade Mall)', '47A', '005', '011', 'Kota Bandung', '40283', 1, 0, 'C1', '', '(022) 7238333', 'Bapak Jajang Aji', '2020-01-29', 1, NULL, NULL, 45, '3000000.00', 1, 28, '88051032', NULL),
(9, 'Toko Dua Saudara Electric', 'Jalan Pungkur', '51', '000', '000', 'Kota Bandung', '40252', 3, 0, '', '', '08122033019', 'Bapak Hendrik', '2020-01-29', 1, NULL, NULL, 45, '3000000.00', 1, 28, '04153061', NULL),
(11, 'Toko Buana Elektrik', 'Jalan Cinangka', '4', '000', '000', 'Kota Bandung', '40616', 1, 0, '', '', '', 'Bapak Darma', '2020-01-29', 1, NULL, NULL, 45, '3000000.00', 1, 28, '59582911', NULL),
(12, 'Toko Central Electronic', 'Jalan Mohammad Toha', '72', '000', '000', 'Kota Bandung', '40243', 1, 0, '', '', '(022) 5225851', 'Ibu Siu Men', '2020-01-29', 1, NULL, NULL, 45, '3000000.00', 1, 28, '58385097', NULL),
(13, 'Toko Kian Sukses', 'Jalan Cikutra', '106A', '000', '000', 'Kota Bandung', '40124', 1, 0, '', '', '081298336288', 'Bapak Firman', '2020-01-29', 1, NULL, NULL, 45, '3000000.00', 1, 28, '26779059', NULL),
(15, 'Toko Utama', 'Jalan Pasar Atas', '076', '000', '000', 'Cimahi', '40525', 2, 0, '000', '', '022-6654795', 'Bapak Sugianto', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '14731462', NULL),
(16, 'Toko Sari Bakti', 'Jalan Babakan Sari I (Kebaktian)', '160', '000', '000', 'Kota Bandung', '40283', 3, 0, '000', '', '082318303322', 'Bapak Jisman', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '35701384', NULL),
(17, 'Toko Surya Indah Rancabolang', 'Jalan Rancabolang', '043', '000', '000', 'Kota Bandung', '40286', 1, 0, '000', '', '081321250208', 'Ibu Dewi', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '96748409', NULL),
(18, 'Toko Bangunan HD', 'Jalan Cingised', '125', '000', '000', 'Kota Bandung', '40293', 1, 0, '000', '', '08156275160', 'Bapak Rudy', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '73398235', NULL),
(19, 'Toko Paranti', 'Jalan Jendral Ahmad Yani', '945', '000', '000', 'Kota Bandung', '40282', 4, 0, '000', '', '085315073966', 'Ibu Lili', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '19400857', NULL),
(21, 'Toko Laksana', 'Jalan Ciroyom', '153', '000', '000', 'Kota Bandung', '40183', 2, 0, '000', '', '08122396777', 'Mr. Tatang', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '86739707', NULL),
(22, 'Toko Nirwana Electronic', 'Jalan Ciroyom', '117', '000', '000', 'Kota Bandung', '40183', 2, 0, '000', '', '08122176094', 'Mr. Suwardi', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '40982336', NULL),
(23, 'Toko Sinar Untung Electrical', 'Jalan Raya Dayeuh Kolot', '295', '000', '000', 'Kota Bandung', '40258', 2, 0, '000', '', '082218456161', 'Mr. Kery', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '14168828', NULL),
(24, 'Toko Depo Listrik', 'Jalan Jendral Ahmad Yani, Plaza IBCC LGF', '008', '000', '000', 'Kota Bandung', '40271', 3, 0, 'D3', '', '022-7238318', 'Bapak Dadang', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '17842175', NULL),
(25, 'Toko Krista Lighting', 'Jalan Jendral Ahmad Yani, Plaza IBCC', '12A', '000', '000', 'Kota Bandung', '40114', 3, 0, 'D1', '', '022-7238369', 'Mr. Yendi', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '21343724', NULL),
(26, 'Toko Prima', 'Jalan Surya Sumantri', '058', '000', '000', 'Kota Bandung', '40164', 4, 0, '000', '', '022-2014967', 'Mrs. Uut', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '31153562', NULL),
(27, 'Toko Sumber Rejeki', 'Jalan Jendral Ahmad Yani', '328', '000', '000', 'Kota Bandung', '40271', 3, 0, '000', '', '081570265893', 'Ibu Sinta', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '70362778', NULL),
(29, 'Toko Bangunan Kurniawan', 'Jalan Boling', '001', '000', '000', 'Kota Bandung', '40293', 1, 0, '000', '', '085101223235', 'Mr. Kurniawan', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '57997527', NULL),
(30, 'Toko Besi Adil', 'Jalan Gatot Subroto', '355', '000', '000', 'Kota Bandung', '40724', 3, 0, '000', '', '08122047066', 'Mr. Julius', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '99961710', NULL),
(31, 'Toko Karunia Sakti', 'Jalan Mohammad Toha', '210', '000', '000', 'Kota Bandung', '40243', 2, 0, '000', '', '087827722212', 'Mrs. Alin', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '79488686', NULL),
(32, 'Toko Aneka Nada', 'Jalan Jendral Ahmad Yani', '180', '000', '000', 'Kota Bandung', '40262', 3, 0, '000', '', '089630011866', 'Mr. Ano', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '85679959', NULL),
(33, 'Toko VIP Elektrik', 'Jalan Pahlawan', '049', '000', '000', 'Kota Bandung', '40122', 4, 0, '000', '', '08122043095', 'Mr. Rudi', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '52951066', NULL),
(34, 'Toko Mitra Elektrik', 'Jalan Raya Cileunyi', '036', '000', '000', 'Kota Bandung', '40622', 1, 0, '000', '', '082129265391', 'Mr. Halifa', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '19707657', NULL),
(35, 'Toko Remaja Teknik', 'Jalan Kiaracondong', '318', '000', '000', 'Kota Bandung', '40275', 3, 0, '000', '', '022-7311813', 'Mrs. Dewi', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '69668982', NULL),
(36, 'Toko Tang Mandiri', 'Jalan Holis', '321-325', '000', '000', 'Kota Bandung', '40212', 2, 0, '000', '', '087779614332', 'Mr. Tikno', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 28, '54923133', NULL),
(37, 'Toko Bangunan Buana Jaya', 'Komplek Batununggal Indah Jalan Waas', '013', '000', '000', 'Kota Bandung', '40266', 3, 0, '000', '', '087878878708', 'Mr. Tatang', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 28, '93344156', NULL),
(38, 'Toko Bangunan Kurniawan Jaya', 'Jalan Terusan Cibaduyut', '052', '000', '000', 'Kota Bandung', '40239', 3, 0, '000', '', '022-5409888', 'Mrs. Lili', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 28, '88500323', NULL),
(39, 'Toko Serly Electric', 'Jalan Raya Cijerah', '242', '000', '000', 'Kota Bandung', '40213', 2, 0, '000', '', '085220265002', 'Mr. Yayan', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 28, '72941448', NULL),
(40, 'Toko Bangunan Rahmat Putra', 'Jalan Terusan Jakarta', '272', '000', '000', 'Kota Bandung', '40291', 1, 0, '000', '', '08122128363', 'Mr. Tanto', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 28, '08912912', NULL),
(41, 'Toko Bangunan Cahaya Logam', 'Jalan Babakan Ciparay', '088', '000', '000', 'Kota Bandung', '40223', 2, 0, '000', '', '022-5402609', 'Mrs. Yani', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 28, '06855577', NULL),
(42, 'Toko Sumber Cahaya', 'Jalan Leuwigajah', '43C', '000', '000', 'Cimahi', '40522', 2, 0, '0000', '', '08988321110', 'Mrs. Nova Wiliana', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 28, '21914045', NULL),
(43, 'Toko D&P Electronics', 'Taman Kopo Indah II', '041', '000', '000', 'Kota Bandung', '40218', 2, 0, '000', '', '08126526986', 'Mrs. Susanti', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 28, '64927313', NULL),
(44, 'Toko Bangunan Pusaka Jaya', 'Jalan Gardujati, Jendral Sudirman', '001', '000', '000', 'Kota Bandung', '40181', 2, 0, '000', '', '022-6031756', 'Mrs. Jeni', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 28, '98204352', NULL),
(45, 'Toko Bangunan Raya Timur', 'Jalan Abdul Haris Nasution, Sindanglaya', '156', '000', '000', 'Kota Bandung', '40293', 1, 0, '000', '', '085974113514', 'Mrs. Safiah', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 28, '46723199', NULL),
(46, 'Toko Guna Jaya Teknik', 'Jalan Sukamenak', '123', '000', '000', 'Kota Bandung', '40228', 4, 0, '000', '', '0895802238369', 'Mrs. Yuliah', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 28, '25854166', NULL),
(47, 'Toko Bangunan Sinar Surya', 'Jalan Terusan Pasirkoja', '108', '000', '000', 'Kota Bandung', '40232', 2, 0, '000', '', '022-6018088', 'Mrs. Mely', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 28, '38239857', NULL),
(48, 'Toko Pada Selamat', 'Jalan Raya Dayeuh Kolot', '314', '000', '000', 'Kota Bandung', '40258', 2, 0, '000', '', '08985085885', 'Mr. Selamet', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 28, '64112119', NULL),
(49, 'Toko Bangunan Kopo Indah', 'Jalan Peta', '200', '000', '000', 'Kota Bandung', '40233', 2, 0, '000', '', '022-6036149', 'Mr. Iwan', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 28, '58742819', NULL),
(50, 'Toko Yasa Elektronik', 'Jalan Margacinta', '165', '000', '000', 'Kota Bandung', '40287', 1, 0, '000', '', '082115065506', 'Mr. Jajang', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 28, '06280309', NULL),
(51, 'Toko Fikriy Berkah Elektronik', 'Jalan Raya Jatinangor', '131', '000', '000', 'Kota Bandung', '45363', 1, 0, '000', '', '082219561667', 'Mr. Agung', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 28, '25976047', NULL),
(52, 'Toko AA Electronic Service', 'Jalan Kyai Haji Ahmad Sadili', '194', '000', '000', 'Kota Bandung', '40394', 1, 0, '000', '', '085316116595', 'Mr. Amir', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 28, '06823947', NULL),
(53, 'Toko Kencana Electric', 'Jalan Sultan Agung', '136', '000', '000', 'Pekalongan', '51126', 1, 0, '000', '', '0285-422035', 'Mr. Akiang', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 28, '42876037', NULL),
(54, 'Toko Kurnia Electronic', 'Jalan Raya Batujajar', '268', '000', '000', 'Kota Bandung', '40561', 2, 0, '000', '', '085797993942', 'Mrs. Alda', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 28, '06621118', NULL),
(55, 'Toko Wira Elektrik', 'Jalan Buah Batu', '036', '000', '000', 'Kota Bandung', '40262', 3, 0, '000', '', '08122136088', 'Mr. Budi', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 28, '02946580', NULL),
(56, 'Toko Bunga Elektrik', 'Jalan Cikondang', '025', '000', '000', 'Kota Bandung', '40133', 4, 0, '000', '', '081320419469', 'Mr. Ayon', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 28, '30546962', NULL),
(57, 'Toko Cahaya Baru Cimuncang', 'Jalan Cimuncang', '037', '000', '000', 'Kota Bandung', '40125', 4, 0, '000', '', '085782724800', 'Mr. Arif', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 28, '81824949', NULL),
(58, 'Toko Sinar Karapitan', 'Jalan Karapitan', '026', '000', '000', 'Kota Bandung', '40261', 3, 0, '000', '', '022-4208474', 'Mr. Yangyang', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 28, '61933227', NULL),
(59, 'Toko Bintang Elektronik', 'Jalan Kebon Bibit, Balubur Town Square', '012', '000', '000', 'Kota Bandung', '40132', 4, 0, '000', '', '022-76665492', 'Mr. Darman', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 28, '16708835', NULL),
(60, 'Toko Anam Elektronik', 'Jalan Kebon Kembang', '003', '000', '000', 'Kota Bandung', '40116', 4, 0, '000', '', '022-4233870', 'Mr. Anam', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 28, '68578865', NULL),
(61, 'Toko Sinar Permata Jaya', 'Jalan Gegerkalong girang', '088', '000', '000', 'Kota Bandung', '40153', 4, 0, '000', '', '081326453213', 'Mr. Andi', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 28, '73691650', NULL),
(62, 'Toko Aneka Niaga', 'Jalan Gegerkalong Tengah', '077', '000', '000', 'Kota Bandung', '40153', 4, 0, '000', '', '022-2010184', 'Mr. Saiful', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 28, '82205398', NULL),
(63, 'Toko Altrik', 'Jalan Sarijadi Raya', '047', '000', '000', 'Kota Bandung', '40151', 4, 0, '000', '', '082320420999', 'Mr. Firman', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 28, '62385719', NULL),
(64, 'Toko Kurnia Elektrik', 'Jalan Gegerkalong Hilir', '165', '000', '000', 'Kota Bandung', '40153', 4, 0, '000', '', '082219152433', 'Mr. Is', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 28, '76437473', NULL),
(65, 'Toko Sinar Logam', 'Jalan Sariasih', '019', '000', '000', 'Kota Bandung', '40151', 4, 0, '006', '', '022-2017598', 'Mr. Fajar', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 28, '72080674', NULL),
(66, 'Toko 8', 'Jalan Baladewa', '008', '000', '000', 'Kota Bandung', '40173', 2, 0, '000', '', '022-6034875', 'Mr. Thomas', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 28, '68114990', NULL),
(67, 'Toko Glory Electric', 'Jalan Komud Supadio', '36A', '000', '000', 'Kota Bandung', '40174', 2, 0, '000', '', '085974901894', 'Mr. Anton', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 28, '37872824', NULL),
(68, 'Toko Lestari', 'Jalan Rajawali Barat', '99A', '000', '000', 'Kota Bandung', '40184', 2, 0, '000', '', '022-6044308', 'Mr. Dedi', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 28, '45508256', NULL),
(69, 'Toko 23', 'Jalan Kebon Kopi', '128', '000', '000', 'Cimahi', '40535', 2, 0, '000', '', '022-6018073', 'Mr. Nanan', '2020-02-03', 1, NULL, NULL, 45, '5000000.00', 1, 28, '78275254', NULL),
(70, 'Toko Abadi', 'Jalan Gegerkalong Hilir', '073', '000', '000', 'Kota Bandung', '40153', 4, 0, '000', '', '022-2010185', 'Mr. Arifin', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 28, '71022859', NULL),
(71, 'Toko Graha Electronic', 'Jalan Melong Asih', '071', '000', '000', 'Kota Bandung', '40213', 2, 0, '000', '', '085722237789', 'Mr. Hendra', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 28, '71798919', NULL),
(72, 'Toko Asih', 'Jalan Melong Asih', '015', '000', '000', 'Cimahi', '40213', 2, 0, '000', '', '022-6016764', '', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 28, '50061887', NULL),
(73, 'Toko Mutiara Jaya', 'Jalan Holis', '330', '000', '000', 'Kota Bandung', '40212', 2, 0, '000', '', '087823231177', 'Mr. Supandi', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 28, '35474619', NULL),
(74, 'Toko Berdikari', 'Jalan Holis', '328', '000', '000', 'Kota Bandung', '40212', 2, 0, '000', '', '022-6010288', 'Mr. Ayung', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '55445048', NULL),
(75, 'Toko Cipta Mandiri Electronics', 'Jalan Jendral Ahmad Yani, Jaya Plaza lt dasar', '238', '000', '000', 'Kota Bandung', '40262', 3, 0, 'B5', '', '081220066835', 'Mr. Tino', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '39261382', NULL),
(76, 'Toko Sinar Makmur Electronics', 'Jalan Jendral Ahmad Yani, Jaya Plaza lt dasar', '238', '000', '000', 'Kota Bandung', '40262', 3, 0, 'G8', '', '081321450345', 'Mrs. Frenita', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '64461574', NULL),
(77, 'Toko Aneka Electric', 'Jalan Jendral Ahmad Yani, Jaya Plaza lt dasar', '238', '000', '000', 'Kota Bandung', '40271', 3, 0, 'C11-12', '', '022-7214731', 'Mr. Iksan', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '40272992', NULL),
(78, 'Toko Tehnik Aneka Prima', 'Jalan Peta, Ruko Kopo Kencana', '002', '000', '000', 'Kota Bandung', '40233', 2, 0, 'A3', '', '082219197020', 'Mr. Wendy Hauw', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '58906992', NULL),
(80, 'Toko 487', 'Jalan Terusan Gatot Subroto', '487', '000', '000', 'Kota Bandung', '40284', 3, 0, '000', '', '08112143030', 'Mr. Udin', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '88568165', NULL),
(81, 'Toko Agung Jaya', 'Jalan Kebon Jati', '264', '000', '000', 'Kota Bandung', '40182', 2, 0, '000', '', '022-20564092', 'Mrs. Shirly', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '05925620', NULL),
(82, 'Toko Alam Ria', 'Jalan Kopo Sayati', '122-124', '000', '000', 'Kota Bandung', '40228', 3, 0, '000', '', '022-54413432', 'Mr. Mukian', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '10424519', NULL),
(83, 'Toko Alka', 'Jalan Caringin', '002', '000', '000', 'Kota Bandung', '40223', 2, 0, '000', '', '022-5408473', 'Mr. Mila Suryantini', '2020-02-04', 1, NULL, NULL, 0, '3000000.00', 1, 28, '30080751', NULL),
(84, 'Toko Alvina Elektronik', 'Jalan Terusan Gatot Subroto', '487', '000', '000', 'Kota Bandung', '40284', 3, 0, '000', '', '081222556066', 'Mr. Dedi', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '84854085', NULL),
(85, 'Toko Aneka Teknik', 'Jalan Jamika', '121', '000', '000', 'Kota Bandung', '40221', 2, 0, '000', '', '022-6024485', 'Mr. Akwet', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '87577953', NULL),
(86, 'Toko Anugerah', 'Jalan Kopo', '356', '000', '000', 'Kota Bandung', '40233', 2, 0, '000', '', '022-6016845', 'Mr. Acen', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '68149352', NULL),
(87, 'Toko Asa', 'Jalan Lengkong Dalam', '009', '000', '000', 'Kota Bandung', '40263', 3, 0, '000', '', '089641476277', 'Mrs. Herlina', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '24899872', NULL),
(88, 'Toko Atari', 'Jalan Sukajadi', '039', '000', '000', 'Kota Bandung', '40162', 4, 0, '000', '', '022-2036944', 'Mr. Adi', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '61264749', NULL),
(89, 'Toko B-33', 'Jalan Banceuy Gang Cikapundung', '016', '000', '000', 'Kota Bandung', '40111', 4, 0, '000', '', '081903151932', 'Mr. Engkus', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '86707011', NULL),
(90, 'Toko Banciang', 'Jalan Gandawijaya', '149', '000', '000', 'Cimahi', '40524', 2, 0, '000', '', '022-6652162', 'Mr. Erik', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '54638724', NULL),
(91, 'Toko Bandung Raya', 'Jalan Otto Iskandar Dinata', '322', '000', '000', 'Kota Bandung', '40241', 2, 0, '000', '', '022-4231988', 'Mr. Tonny', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '35349884', NULL),
(92, 'Toko Bangunan Hurip Jaya', 'Jalan Cikutra', '129A', '000', '000', 'Kota Bandung', '40124', 4, 0, '000', '', '0818423316', 'Mr. Eko', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '59913679', NULL),
(93, 'Toko Bangunan Key Kurnia Jaya', 'Jalan Manglid, Ruko Kopo Lestari', '016', '000', '000', 'Kota Bandung', '40226', 3, 0, '000', '', '082119739191', 'Mr. Kurnia', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '34345357', NULL),
(94, 'Toko Bangunan Mandiri', 'Jalan Babakan Sari I', '144', '000', '000', 'Kota Bandung', '40283', 3, 0, '000', '', '082221000473', 'Mr. Mamat', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '88280734', NULL),
(95, 'Toko Bangunan Mekar Indah', 'Jalan Terusan Jakarta', '177', '000', '000', 'Kota Bandung', '40291', 1, 0, '000', '', '081285885152', 'Mr. Yudha', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '35787553', NULL),
(96, 'Toko Bangunan Rossa', 'Jalan Mohammad Toha', '189', '000', '000', 'Kota Bandung', '40243', 3, 0, '000', '', '081320003205', 'Mr. Rosa', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '47254592', NULL),
(97, 'Toko Bangunan Sakti', 'Jalan Kopo', '499', '000', '000', 'Kota Bandung', '40235', 2, 0, '000', '', '022-5401421', 'Mr. Michael', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '99476327', NULL),
(98, 'Toko Bangunan Sarifah', 'Jalan Cikutra', '180', '000', '000', 'Kota Bandung', '40124', 4, 0, '000', '', '081222125523', 'Mr. Yosef', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '08246341', NULL),
(99, 'Toko Bangunan Sawargi', 'Jalan Sriwijaya', '20-22', '000', '000', 'Kota Bandung', '40253', 3, 0, '000', '', '022-5229954', 'Mr. Wahid Hasim', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '82188310', NULL),
(100, 'Toko Bangunan Tresnaco VI', 'Jalan Ciwastra', '086', '000', '000', 'Kota Bandung', '40287', 1, 0, '000', '', '022-7562368', 'Mr. Aep', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '36403978', NULL),
(101, 'Toko Baros Elektronik', 'Jalan Baros', '30-32', '000', '000', 'Cimahi', '40521', 2, 0, '000', '', '022-6642155', 'Mr. Hadi Kurniawan', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '18422520', NULL),
(102, 'Toko Bintang Elektrik', 'Jalan Mekar Utama', '010', '000', '000', 'Kota Bandung', '40237', 2, 0, '000', '', '085324106262', 'Mr. Bill', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '56420431', NULL),
(103, 'Toko Cahaya Abadi', 'Jalan ABC, Pasar Cikapundung Gedung CEC lt.1', '017', '000', '000', 'Kota Bandung', '40111', 4, 0, 'EE', '', '022-84460646', 'Mr. Ari', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '96303027', NULL),
(104, 'Toko Cahaya Gemilang', 'Jalan Leuwi Panjang', '059', '000', '000', 'Kota Bandung', '40234', 2, 0, '000', '', '0895807009085', 'Mrs. Paula', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '43333165', NULL),
(105, 'Toko Chiefa Elektronik', 'Jalan Pamekar Raya', '001', '000', '000', 'Kota Bandung', '40292', 1, 0, '000', '', '085220056200', 'Mr. Faizin', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '43114169', NULL),
(106, 'Toko Ciumbuleuit', 'Jalan Ciumbuleuit', '009', '000', '000', 'Kota Bandung', '40131', 4, 0, '000', '', '022-2032701', 'Mrs. Isan', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '55778737', NULL),
(107, 'Toko CN Elektrik', 'Taman Kopo Indah Raya', '184B', '000', '000', 'Kota Bandung', '40228', 3, 0, '000', '', '085100807853', 'Mrs. Michel', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '17233710', NULL),
(108, 'Toko Denvar Elektronik', 'Jalan Raya Ujungberung', '367', '000', '000', 'Kota Bandung', '40614', 1, 0, '000', '', '085323469911', 'Mr. Deden', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '55609139', NULL),
(109, 'Toko Dragon CCTV', 'Jalan Peta, Komplek Bumi Kopo Kencana', '019', '000', '000', 'Kota Bandung', '40233', 2, 0, 'E', '', '08122002178', 'Mr. Fendi', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '82745929', NULL),
(110, 'Toko Dunia Bahan Bangunan Bandung', 'Jalan Raya Derwati', '089', '000', '000', 'Kota Bandung', '40292', 1, 0, '000', '', '081299422379', 'Mr. Aldi', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '86559407', NULL),
(111, 'Toko Dunia Electric', 'Jalan Otto Iskandar Dinata', '319', '000', '000', 'Kota Bandung', '40251', 3, 0, '000', '', '022-4230423', 'Mr. Tedy', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '06580908', NULL),
(112, 'Toko Fortuna Elektronik', 'Jalan Rancabolang Margahyu Raya', '045', '000', '000', 'Kota Bandung', '40292', 1, 0, '000', '', '0817436868', 'Mrs. Ika', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '80759977', NULL),
(113, 'Toko Golden Lite', 'Jalan Banceuy', '100', '000', '000', 'Kota Bandung', '40111', 4, 0, '000', '', '081220016888', 'Mr. Joni', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '29152644', NULL),
(114, 'Toko Bangunan Hadap Jaya', 'Jalan Margasari', '082', '000', '000', 'Kota Bandung', '40287', 1, 0, '000', '', '022-7510948', 'Mrs. Suamiati', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '66270755', NULL),
(115, 'Toko Bangunan Hadap Jaya II', 'Jalan Ciwastra', '169', '000', '000', 'Kota Bandung', '40287', 1, 0, '000', '', '082115009077', 'Mr. David', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '39249455', NULL),
(116, 'Perusahaan Dagang Hasil Jaya', 'Jalan Peta', '210', '000', '000', 'Kota Bandung', '40231', 2, 0, '000', '', '022-6036170', 'Mrs. Sandra', '2020-02-04', 1, NULL, NULL, 45, '100000000.00', 1, 28, '00381476', NULL),
(117, 'Toko Bangunan Hidup Sejahtera', 'Jalan Raya Barat', '785', '000', '000', 'Cimahi', '40526', 2, 0, '000', '', '081221204121', 'Mr. Sarip', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '84919567', NULL),
(118, 'Toko Indo Mitra', 'Jalan Leuwi Panjang', '074', '000', '000', 'Kota Bandung', '40233', 2, 0, '000', '', '081220691333', 'Mr. Chandra', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '87605194', NULL),
(119, 'Toko Intio', 'Jalan Babakan Sari I', '105', '000', '000', 'Kota Bandung', '40283', 3, 0, '000', '', '087815400681', 'Mr. Warto', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '69002776', NULL),
(120, 'Toko Jatiluhur', 'Jalan Gandawijaya', '103', '000', '000', 'Cimahi', '40523', 2, 0, '000', '', '0811220270', 'Mr. Victor', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '47961940', NULL),
(121, 'Toko Jaya Elektrik', 'Jalan Cilengkrang II', '012', '000', '000', 'Kota Bandung', '40615', 1, 0, '000', '', '081313401812', 'Mr. Andi', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '98594144', NULL),
(122, 'Toko Jaya Sakti', 'Jalan ABC, Pasar Cikapundung', '007', '000', '000', 'Kota Bandung', '40111', 4, 0, 'Q', '', '081273037722', 'Mr. Teat', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '59389510', NULL),
(123, 'Toko Jingga Elektronik', 'Jalan Raya Bojongsoang', '086', '000', '000', 'Kota Bandung', '40288', 3, 0, '000', '', '089626491468', 'Mrs. Mita', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 28, '36621724', NULL),
(124, 'PT Kencana Elektrindo', 'Jalan Batununggal Indah I', '2A', '000', '000', 'Kota Bandung', '40266', 2, 0, '000', '', '082217772889', 'Mr. Natanael', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 28, '91520857', NULL),
(125, 'Toko Lamora Elektrik', 'Jalan Babakan Sari I', '030', '000', '000', 'Kota Bandung', '40283', 3, 0, '000', '', '081809900750', 'Mr. Andre', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 28, '80580303', NULL),
(126, 'Toko Laris Elektrik', 'Jalan Kiaracondong', '192A', '000', '000', 'Kota Bandung', '40283', 3, 0, '000', '', '081220880699', 'Mr. Wili', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 28, '38912490', NULL),
(127, 'Toko MM Elektrik', 'Jalan Soekarno Hatta', '841', '000', '000', 'Kota Bandung', '40293', 1, 0, '000', '', '082121977326', 'Mr. Miming', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 28, '46965304', NULL),
(128, 'Toko Mega Teknik', 'Jalan Jamika', '151B', '000', '000', 'Kota Bandung', '40232', 2, 0, '000', '', '082124452324', 'Mr. Elvado', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 28, '90468472', NULL),
(129, 'Toko Merpati Elektrik', 'Jalan Otto Iskandar Dinata', '339', '000', '000', 'Kota Bandung', '40251', 2, 0, '000', '', '081320663366', 'Mrs. Erline', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 28, '00984247', NULL),
(130, 'Toko Nceng', 'Jalan Bojong Koneng', '123', '000', '000', 'Kota Bandung', '40191', 4, 0, '000', '', '081395112236', 'Mr. Enceng', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 28, '98191337', NULL),
(131, 'Toko Omega Electric', 'Jalan Indramayu', '012', '000', '000', 'Kota Bandung', '40291', 1, 0, '000', '', '081322922888', 'Mrs. Diana', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 28, '53714447', NULL),
(132, 'Toko Panca Mulya', 'Jalan Kopo Sayati', '144', '000', '000', 'Kota Bandung', '40228', 2, 0, '000', '', '022-5420586', 'Mrs. Dede', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 28, '78507690', NULL),
(133, 'Toko Pelita Putra', 'Ruko Gunung Batu', '009', '000', '000', 'Kota Bandung', '40175', 2, 0, '000', '', '0811239777', 'Mr. Sunsun', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 28, '39844658', NULL),
(134, 'Toko Bangunan Pesantren II', 'Jalan Pagarsih', '339', '000', '000', 'Kota Bandung', '40221', 2, 0, '000', '', '022-6040285', 'Mr. Yanto', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 28, '61249526', NULL),
(135, 'Toko Prima Elektrik', 'Jalan ABC Komplek Cikapundung Electronic Center lt.1', '003', '000', '000', 'Kota Bandung', '40111', 4, 0, 'EE', '', '085227160748', 'Mr. Endhi', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 28, '50445892', NULL),
(136, 'Toko Purnama Jaya Electronic', 'Jalan Cibodas Raya', '006', '000', '000', 'Kota Bandung', '40291', 1, 0, '000', '', '081224798744', 'Mr. Nurzaki', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 28, '69926012', NULL),
(137, 'Toko Echi El', 'Jalan Logam', '7', '000', '000', 'Kota Bandung', '40287', 3, 0, '000', '', '082129554478', 'Bapak Hendar', '2020-03-30', 1, NULL, NULL, 45, '3000000.00', 1, 28, '17759482', NULL),
(261, 'Toko Sinar Agung', 'Jalan Caringin', '258', '000', '000', 'Kota Bandung', '40223', 2, 0, '000', '', '022-6026321', 'Mr. Miming', '2020-01-24', 1, NULL, NULL, 45, '2500000.00', 1, 28, '54444708', NULL),
(262, 'Toko Bangunan Sinar Sekelimus', 'Jalan Soekarno Hatta', '569', '000', '000', 'Kota Bandung', '40275', 1, 0, '000', '', '(022) 7300317', 'Bapak Hendra', '2020-08-14', 1, NULL, NULL, 30, '3000000.00', 1, 28, '04770219', NULL),
(263, 'Toko Bahagia Elektrik', 'Jalan Kopo - Katapang KM 13.6', '', '000', '000', 'Kota Bandung', '', 2, 0, '', '', '085723489618', 'Bapak Sina', '2020-08-19', 1, NULL, NULL, 30, '3000000.00', 1, 28, '04086274', NULL),
(264, 'Toko Atha Elektrik', 'Jalan Ganda Sari', '71', '000', '000', 'Kota Bandung', '', 2, 0, '', '', '083804987086', 'Bapak Arif', '2020-08-19', 1, NULL, NULL, 30, '3000000.00', 1, 28, '18782587', NULL),
(265, 'Toko KS Electric', 'Jalan Lettu Sobri (Odeon)', '3 - 5', '000', '000', 'Sukabumi', '43131', 4, 0, '000', '', '(0266) 222217', 'Bapak Halim Sanjaya', '2020-08-31', 1, '-6.825881100000000000000000000000', '107.002591600000000000000000000000', 30, '3000000.00', 1, 28, '09833784', NULL),
(267, 'Toko Bangunan Sumber Rejeki Ciwastra', 'Jalan Ciwastra', '41', '000', '000', 'Kota Bandung', '40287', 1, 0, '000', '', '081356048035', 'Ibu Lily Yanti', '2020-09-01', 1, '-6.961227000000000000000000000000', '107.667188000000000000000000000000', 30, '3000000.00', 1, 28, '53967121', NULL),
(268, 'Toko Sari Dagang Electric', 'Jalan Sindang Kerta', '1', '000', '000', 'Kab. Bandung Barat', '40562', 2, 0, '000', '', '083817785736', 'Bapak Wildan', '2020-09-01', 1, '-6.980715000000000000000000000000', '107.429073000000000000000000000000', 30, '3000000.00', 1, 28, '80362157', NULL),
(269, 'Toko Bangunan Sinar Mas', 'Jalan Cililin - Sindang Kerta', '74', '000', '000', 'Kab. Bandung Barat', '40562', 2, 0, '000', '', '081221191978', 'Bapak Ujang', '2020-09-01', 1, '-6.959504000000000000000000000000', '107.450271700000000000000000000000', 30, '3000000.00', 1, 28, '43305116', NULL),
(270, 'Yunus Hendra Wijaya', 'Jalan Palangkaraya', '14', '004', '009', 'Kota Bandung', '40291', 1, 0, '000', '', '087825603963', 'Bapak Yunus Hendra Wijaya', '2020-09-02', 1, '-6.872809100000000000000000000000', '107.569453000000000000000000000000', 45, '3000000.00', 1, 28, '92090282', NULL),
(271, 'Toko Tang Electric', 'Jalan Terusan Marga Cinta ', '54', '000', '000', 'Kota Bandung', '40287', 1, 0, '000', NULL, '(022) 7567896', 'Ibu Iva', '2020-10-09', 1, '-6.957193000000000000000000000000', '107.656708000000000000000000000000', 45, '250000000.00', 1, 1, '12345678', NULL),
(272, 'Toko Bandung Electric', 'Komplek Taman Kopo Indah V', '11', '000', '000', 'Kota Bandung', '40218', 2, 0, '000', NULL, '082369766965', 'Bapak Afuk', '2020-10-09', 1, '-6.960807000000000000000000000000', '107.560498000000000000000000000000', 45, '50000000.00', 1, 1, '0896147', NULL),
(273, 'Toko Fuba Elektrik', 'Jalan Mekar Indah', '141', '000', '000', 'Kota Bandung', '40625', 1, 0, '000', NULL, '087824693336', 'Bapak Frieyuda Bierman', '2020-10-09', 1, '-6.943339000000000000000000000000', '107.723918000000000000000000000000', 45, '50000000.00', 1, 1, '78614332', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `customer_accountant`
--

CREATE TABLE `customer_accountant` (
  `id` int(255) NOT NULL,
  `customer_id` int(255) NOT NULL,
  `accountant_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `customer_accountant`
--

INSERT INTO `customer_accountant` (`id`, `customer_id`, `accountant_id`) VALUES
(9, 6, 1),
(10, 7, 1),
(11, 11, 1),
(12, 74, 1),
(13, 75, 1),
(15, 1, 1),
(16, 5, 1),
(17, 8, 1),
(18, 9, 1),
(19, 12, 1),
(20, 13, 1),
(21, 15, 1),
(22, 16, 1),
(23, 17, 1),
(24, 18, 1),
(25, 19, 1),
(26, 21, 1),
(27, 22, 1),
(28, 23, 1),
(29, 24, 1),
(30, 25, 1),
(31, 26, 1),
(32, 27, 1),
(33, 29, 1),
(34, 30, 1),
(35, 31, 1),
(36, 32, 1),
(37, 33, 1),
(38, 34, 1),
(39, 35, 1),
(40, 36, 1),
(41, 37, 1),
(42, 38, 1),
(43, 39, 1),
(44, 40, 1),
(45, 41, 1),
(46, 42, 1),
(47, 43, 1),
(48, 44, 1),
(49, 45, 1),
(50, 46, 1),
(51, 47, 1),
(52, 48, 1),
(53, 49, 1),
(54, 50, 1),
(55, 51, 1),
(56, 52, 1),
(57, 53, 1),
(58, 54, 1),
(59, 55, 1),
(60, 56, 1),
(61, 57, 1),
(62, 58, 1),
(63, 59, 1),
(64, 60, 1),
(65, 61, 1),
(66, 62, 1),
(67, 63, 1),
(68, 64, 1),
(69, 65, 1),
(70, 66, 1),
(71, 67, 1),
(72, 68, 1),
(73, 69, 1),
(74, 70, 1),
(75, 71, 1),
(76, 72, 1),
(77, 73, 1),
(78, 76, 1),
(79, 77, 1),
(80, 78, 1),
(81, 80, 1),
(82, 81, 1),
(83, 82, 1),
(84, 83, 1),
(85, 84, 1),
(86, 85, 1),
(87, 86, 1),
(88, 87, 1),
(89, 88, 1),
(90, 89, 1),
(91, 90, 1),
(92, 91, 1),
(93, 92, 1),
(94, 93, 1),
(95, 94, 1),
(96, 95, 1),
(97, 96, 1),
(98, 97, 1),
(99, 98, 1),
(100, 99, 1),
(101, 100, 1),
(102, 101, 1),
(103, 102, 1),
(104, 103, 1),
(105, 104, 1),
(106, 105, 1),
(107, 106, 1),
(108, 107, 1),
(109, 108, 1),
(110, 109, 1),
(111, 110, 1),
(112, 111, 1),
(113, 112, 1),
(114, 113, 1),
(115, 114, 1),
(116, 115, 1),
(117, 116, 1),
(118, 117, 1),
(119, 118, 1),
(120, 119, 1),
(121, 120, 1),
(122, 121, 1),
(123, 122, 1),
(124, 123, 1),
(125, 124, 1),
(126, 125, 1),
(127, 126, 1),
(128, 127, 1),
(129, 128, 1),
(130, 129, 1),
(131, 130, 1),
(132, 131, 1),
(133, 132, 1),
(134, 133, 1),
(135, 134, 1),
(136, 135, 1),
(137, 136, 1),
(138, 137, 1),
(139, 261, 1),
(140, 262, 1),
(141, 263, 1),
(142, 264, 1),
(143, 265, 1),
(144, 267, 1),
(145, 268, 1),
(146, 269, 1),
(147, 1, 18),
(148, 5, 18),
(149, 6, 18),
(151, 8, 18),
(152, 9, 18),
(153, 11, 18),
(154, 12, 18),
(155, 13, 18),
(156, 15, 18),
(157, 16, 18),
(158, 17, 18),
(159, 18, 18),
(160, 19, 18),
(161, 21, 18),
(162, 22, 18),
(163, 23, 18),
(164, 24, 18),
(165, 25, 18),
(166, 26, 18),
(167, 27, 18),
(168, 29, 18),
(169, 30, 18),
(170, 31, 18),
(171, 32, 18),
(172, 33, 18),
(173, 34, 18),
(174, 35, 18),
(175, 36, 18),
(176, 37, 18),
(177, 38, 18),
(178, 39, 18),
(179, 40, 18),
(180, 41, 18),
(181, 42, 18),
(182, 43, 18),
(183, 44, 18),
(184, 45, 18),
(185, 46, 18),
(186, 47, 18),
(187, 48, 18),
(188, 49, 18),
(189, 50, 18),
(190, 51, 18),
(191, 52, 18),
(192, 53, 18),
(193, 54, 18),
(194, 55, 18),
(195, 56, 18),
(196, 57, 18),
(197, 58, 18),
(198, 59, 18),
(199, 60, 18),
(200, 61, 18),
(201, 62, 18),
(202, 63, 18),
(203, 64, 18),
(204, 65, 18),
(205, 66, 18),
(206, 67, 18),
(207, 68, 18),
(208, 69, 18),
(209, 70, 18),
(210, 71, 18),
(211, 72, 18),
(212, 73, 18),
(213, 74, 18),
(214, 75, 18),
(215, 76, 18),
(216, 77, 18),
(217, 78, 18),
(218, 80, 18),
(219, 81, 18),
(220, 82, 18),
(221, 83, 18),
(222, 84, 18),
(223, 85, 18),
(224, 86, 18),
(225, 87, 18),
(226, 88, 18),
(227, 89, 18),
(228, 90, 18),
(229, 91, 18),
(230, 92, 18),
(231, 93, 18),
(232, 94, 18),
(233, 95, 18),
(234, 96, 18),
(235, 97, 18),
(236, 98, 18),
(237, 99, 18),
(238, 100, 18),
(239, 101, 18),
(240, 102, 18),
(241, 103, 18),
(242, 104, 18),
(243, 105, 18),
(244, 106, 18),
(245, 107, 18),
(246, 108, 18),
(247, 109, 18),
(248, 110, 18),
(249, 111, 18),
(250, 112, 18),
(251, 113, 18),
(252, 114, 18),
(253, 115, 18),
(254, 116, 18),
(255, 117, 18),
(256, 118, 18),
(257, 119, 18),
(258, 120, 18),
(259, 121, 18),
(260, 122, 18),
(261, 123, 18),
(262, 124, 18),
(263, 125, 18),
(264, 126, 18),
(265, 127, 18),
(266, 128, 18),
(267, 129, 18),
(268, 130, 18),
(269, 131, 18),
(270, 132, 18),
(271, 133, 18),
(272, 134, 18),
(273, 135, 18),
(274, 136, 18),
(275, 137, 18),
(276, 261, 18),
(277, 262, 18),
(278, 263, 18),
(279, 264, 18),
(280, 265, 18),
(281, 267, 18),
(282, 268, 18),
(283, 269, 18),
(284, 270, 18);

-- --------------------------------------------------------

--
-- Struktur dari tabel `customer_area`
--

CREATE TABLE `customer_area` (
  `id` int(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `major_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `customer_area`
--

INSERT INTO `customer_area` (`id`, `name`, `major_id`) VALUES
(1, 'Bandung Timur', NULL),
(2, 'Bandung Barat', NULL),
(3, 'Bandung Selatan', NULL),
(4, 'Bandung Utara', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `customer_target`
--

CREATE TABLE `customer_target` (
  `id` int(255) NOT NULL,
  `customer_id` int(255) NOT NULL,
  `dateCreated` date DEFAULT NULL,
  `created_by` int(255) NOT NULL,
  `value` decimal(50,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `customer_target`
--

INSERT INTO `customer_target` (`id`, `customer_id`, `dateCreated`, `created_by`, `value`) VALUES
(1, 1, '2020-08-01', 1, '3000000.0000'),
(2, 5, '2020-08-01', 1, '3000000.0000'),
(3, 6, '2020-08-01', 1, '3000000.0000'),
(4, 7, '2020-08-01', 1, '3000000.0000'),
(5, 8, '2020-08-01', 1, '3000000.0000'),
(6, 11, '2020-08-01', 1, '3000000.0000'),
(7, 12, '2020-08-01', 1, '3000000.0000'),
(8, 13, '2020-08-01', 1, '3000000.0000'),
(9, 17, '2020-08-01', 1, '3000000.0000'),
(10, 18, '2020-08-01', 1, '3000000.0000'),
(11, 29, '2020-08-01', 1, '3000000.0000'),
(12, 34, '2020-08-01', 1, '3000000.0000'),
(13, 40, '2020-08-01', 1, '3000000.0000'),
(14, 45, '2020-08-01', 1, '3000000.0000'),
(15, 50, '2020-08-01', 1, '3000000.0000'),
(16, 51, '2020-08-01', 1, '3000000.0000'),
(17, 52, '2020-08-01', 1, '3000000.0000'),
(18, 53, '2020-08-01', 1, '3000000.0000'),
(19, 95, '2020-08-01', 1, '3000000.0000'),
(20, 100, '2020-08-01', 1, '3000000.0000'),
(21, 105, '2020-08-01', 1, '3000000.0000'),
(22, 108, '2020-08-01', 1, '3000000.0000'),
(23, 110, '2020-08-01', 1, '3000000.0000'),
(24, 112, '2020-08-01', 1, '3000000.0000'),
(25, 114, '2020-08-01', 1, '3000000.0000'),
(26, 115, '2020-08-01', 1, '3000000.0000'),
(27, 121, '2020-08-01', 1, '3000000.0000'),
(28, 127, '2020-08-01', 1, '3000000.0000'),
(29, 131, '2020-08-01', 1, '3000000.0000'),
(30, 136, '2020-08-01', 1, '3000000.0000'),
(31, 262, '2020-08-01', 1, '3000000.0000'),
(32, 267, '2020-08-01', 1, '3000000.0000'),
(33, 270, '2020-08-01', 1, '3000000.0000'),
(34, 15, '2020-08-01', 1, '3000000.0000'),
(35, 21, '2020-08-01', 1, '3000000.0000'),
(36, 22, '2020-08-01', 1, '3000000.0000'),
(37, 23, '2020-08-01', 1, '3000000.0000'),
(38, 31, '2020-08-01', 1, '3000000.0000'),
(39, 36, '2020-08-01', 1, '3000000.0000'),
(40, 39, '2020-08-01', 1, '3000000.0000'),
(41, 41, '2020-08-01', 1, '3000000.0000'),
(42, 42, '2020-08-01', 1, '3000000.0000'),
(43, 43, '2020-08-01', 1, '3000000.0000'),
(44, 44, '2020-08-01', 1, '3000000.0000'),
(45, 47, '2020-08-01', 1, '3000000.0000'),
(46, 48, '2020-08-01', 1, '3000000.0000'),
(47, 49, '2020-08-01', 1, '3000000.0000'),
(48, 54, '2020-08-01', 1, '3000000.0000'),
(49, 66, '2020-08-01', 1, '3000000.0000'),
(50, 67, '2020-08-01', 1, '3000000.0000'),
(51, 68, '2020-08-01', 1, '3000000.0000'),
(52, 69, '2020-08-01', 1, '3000000.0000'),
(53, 71, '2020-08-01', 1, '3000000.0000'),
(54, 72, '2020-08-01', 1, '3000000.0000'),
(55, 73, '2020-08-01', 1, '3000000.0000'),
(56, 74, '2020-08-01', 1, '3000000.0000'),
(57, 78, '2020-08-01', 1, '3000000.0000'),
(58, 81, '2020-08-01', 1, '3000000.0000'),
(59, 83, '2020-08-01', 1, '3000000.0000'),
(60, 85, '2020-08-01', 1, '3000000.0000'),
(61, 86, '2020-08-01', 1, '3000000.0000'),
(62, 90, '2020-08-01', 1, '3000000.0000'),
(63, 91, '2020-08-01', 1, '3000000.0000'),
(64, 97, '2020-08-01', 1, '3000000.0000'),
(65, 101, '2020-08-01', 1, '3000000.0000'),
(66, 102, '2020-08-01', 1, '3000000.0000'),
(67, 104, '2020-08-01', 1, '3000000.0000'),
(68, 109, '2020-08-01', 1, '3000000.0000'),
(69, 116, '2020-08-01', 1, '3000000.0000'),
(70, 117, '2020-08-01', 1, '3000000.0000'),
(71, 118, '2020-08-01', 1, '3000000.0000'),
(72, 120, '2020-08-01', 1, '3000000.0000'),
(73, 124, '2020-08-01', 1, '3000000.0000'),
(74, 128, '2020-08-01', 1, '3000000.0000'),
(75, 129, '2020-08-01', 1, '3000000.0000'),
(76, 132, '2020-08-01', 1, '3000000.0000'),
(77, 133, '2020-08-01', 1, '3000000.0000'),
(78, 134, '2020-08-01', 1, '3000000.0000'),
(79, 261, '2020-08-01', 1, '3000000.0000'),
(80, 263, '2020-08-01', 1, '3000000.0000'),
(81, 264, '2020-08-01', 1, '3000000.0000'),
(82, 268, '2020-08-01', 1, '3000000.0000'),
(83, 269, '2020-08-01', 1, '3000000.0000'),
(84, 9, '2020-08-01', 1, '3000000.0000'),
(85, 16, '2020-08-01', 1, '3000000.0000'),
(86, 24, '2020-08-01', 1, '3000000.0000'),
(87, 25, '2020-08-01', 1, '3000000.0000'),
(88, 27, '2020-08-01', 1, '3000000.0000'),
(89, 30, '2020-08-01', 1, '3000000.0000'),
(90, 32, '2020-08-01', 1, '3000000.0000'),
(91, 35, '2020-08-01', 1, '3000000.0000'),
(92, 37, '2020-08-01', 1, '3000000.0000'),
(93, 38, '2020-08-01', 1, '3000000.0000'),
(94, 55, '2020-08-01', 1, '3000000.0000'),
(95, 58, '2020-08-01', 1, '3000000.0000'),
(96, 75, '2020-08-01', 1, '3000000.0000'),
(97, 76, '2020-08-01', 1, '3000000.0000'),
(98, 77, '2020-08-01', 1, '3000000.0000'),
(99, 80, '2020-08-01', 1, '3000000.0000'),
(100, 82, '2020-08-01', 1, '3000000.0000'),
(101, 84, '2020-08-01', 1, '3000000.0000'),
(102, 87, '2020-08-01', 1, '3000000.0000'),
(103, 93, '2020-08-01', 1, '3000000.0000'),
(104, 94, '2020-08-01', 1, '3000000.0000'),
(105, 96, '2020-08-01', 1, '3000000.0000'),
(106, 99, '2020-08-01', 1, '3000000.0000'),
(107, 107, '2020-08-01', 1, '3000000.0000'),
(108, 111, '2020-08-01', 1, '3000000.0000'),
(109, 119, '2020-08-01', 1, '3000000.0000'),
(110, 123, '2020-08-01', 1, '3000000.0000'),
(111, 125, '2020-08-01', 1, '3000000.0000'),
(112, 126, '2020-08-01', 1, '3000000.0000'),
(113, 137, '2020-08-01', 1, '3000000.0000'),
(114, 19, '2020-08-01', 1, '3000000.0000'),
(115, 26, '2020-08-01', 1, '3000000.0000'),
(116, 33, '2020-08-01', 1, '3000000.0000'),
(117, 46, '2020-08-01', 1, '3000000.0000'),
(118, 56, '2020-08-01', 1, '3000000.0000'),
(119, 57, '2020-08-01', 1, '3000000.0000'),
(120, 59, '2020-08-01', 1, '3000000.0000'),
(121, 60, '2020-08-01', 1, '3000000.0000'),
(122, 61, '2020-08-01', 1, '3000000.0000'),
(123, 62, '2020-08-01', 1, '3000000.0000'),
(124, 63, '2020-08-01', 1, '3000000.0000'),
(125, 64, '2020-08-01', 1, '3000000.0000'),
(126, 65, '2020-08-01', 1, '3000000.0000'),
(127, 70, '2020-08-01', 1, '3000000.0000'),
(128, 88, '2020-08-01', 1, '3000000.0000'),
(129, 89, '2020-08-01', 1, '3000000.0000'),
(130, 92, '2020-08-01', 1, '3000000.0000'),
(131, 98, '2020-08-01', 1, '3000000.0000'),
(132, 103, '2020-08-01', 1, '3000000.0000'),
(133, 106, '2020-08-01', 1, '3000000.0000'),
(134, 113, '2020-08-01', 1, '3000000.0000'),
(135, 122, '2020-08-01', 1, '3000000.0000'),
(136, 130, '2020-08-01', 1, '3000000.0000'),
(137, 135, '2020-08-01', 1, '3000000.0000'),
(138, 265, '2020-08-01', 1, '3000000.0000'),
(139, 116, '2020-09-12', 1, '50000000.0000'),
(140, 116, '2020-10-01', 1, '100000000.0000');

-- --------------------------------------------------------

--
-- Struktur dari tabel `debt_type`
--

CREATE TABLE `debt_type` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `is_operational` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `debt_type`
--

INSERT INTO `debt_type` (`id`, `name`, `description`, `is_operational`) VALUES
(2, 'Service', 'This account is used for service debt.', 1),
(3, 'Service ( Non-operational)', 'This account is used for non-operational service debt.', 0),
(4, 'Transportation', 'This account is used for transportation debt.', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `delivery_order`
--

CREATE TABLE `delivery_order` (
  `id` int(255) NOT NULL,
  `sales_order_id` int(255) NOT NULL,
  `code_delivery_order_id` int(255) NOT NULL,
  `quantity` int(255) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `department`
--

CREATE TABLE `department` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `index_url` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `department`
--

INSERT INTO `department` (`id`, `name`, `index_url`, `icon`) VALUES
(1, 'Accounting', 'accounting', 'accounting'),
(2, 'Sales', 'sales', 'sales'),
(3, 'Purchasing', 'purchasing', 'purchasing'),
(4, 'Inventory', 'inventory', 'inventory'),
(5, 'Finance', 'finance', 'finance'),
(6, 'Human resource', 'human_resource', 'human_resource');

-- --------------------------------------------------------

--
-- Struktur dari tabel `event`
--

CREATE TABLE `event` (
  `id` int(255) NOT NULL,
  `item_id` int(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `transaction` varchar(3) NOT NULL,
  `code_event_id` int(255) NOT NULL,
  `price` decimal(50,4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `expense_class`
--

CREATE TABLE `expense_class` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `parent_id` int(255) DEFAULT NULL,
  `description` text NOT NULL,
  `created_by` int(255) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `type` int(255) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `expense_class`
--

INSERT INTO `expense_class` (`id`, `name`, `parent_id`, `description`, `created_by`, `created_date`, `type`) VALUES
(1, 'Transportation', NULL, 'This class is used for transportation expenses, such as fuel, toll, or vehicle maintenance.', 1, '2020-03-24', 1),
(2, 'Utilities', NULL, 'This class is used for utilities expenses, such as electricity bill, water bill, or phone bill', 1, '2020-03-24', NULL),
(3, 'Tax', NULL, 'This class is used for tax expenses, including income tax, saving tax, value added tax.', 1, '2020-03-24', NULL),
(4, 'Fuel', 1, 'Account for fuel expense (transportation)', 1, '2020-03-24', 1),
(5, 'Toll', 1, 'Account for toll expense (transportation)', 1, '2020-03-24', 1),
(6, 'Maintanance', 1, 'Account for vehicle maintenance (transportation)', 1, '2020-03-24', 1),
(7, 'Electricity bill', 2, 'Account for electricity bill (utilities)', 1, '2020-03-24', 1),
(8, 'Water bill', 2, 'Account for water bill (utilities)', 1, '2020-03-24', 1),
(9, 'Phone bill (land)', 2, 'Account for phone (land) bill (utilities)', 1, '2020-03-24', 1),
(10, 'Phone bill (mobile)', 2, 'Account for mobile phone bill (utilities)', 1, '2020-03-24', 1),
(11, 'Parking', 1, 'Account for parking expense (transportation)', 1, '2020-03-24', 1),
(12, 'Item delivery', 1, 'Account for item delivery expense (transportation)', 1, '2020-03-24', 1),
(13, 'Others', 1, 'Account for other expenses in transportation such as tickets, unloading cost, loading cost', 1, '2020-03-24', 1),
(14, 'Security', 2, 'Account for security service', 1, '2020-03-24', 1),
(15, 'Income tax', 3, 'Account for income tax payment (PPh)', 1, '2020-03-24', 1),
(16, 'Value added tax', 3, 'Account for value-added tax payment (PPn)', 1, '2020-03-24', 1),
(17, 'Tax penalties', 3, 'Account for tax penalties payment', 1, '2020-03-24', 1),
(18, 'Office operational', NULL, 'This class is used for office operational expenses, such as document delivery or office equipment purchases', 1, '2020-03-24', NULL),
(19, 'Document delivery', 18, 'Account for document delivery (invoices, counter-invoices, guarantee letter, and other important documents) expense', 1, '2020-03-24', 1),
(20, 'Office equipment', 18, 'Account for office stationary expense', 1, '2020-03-24', 1),
(21, 'Marketing cost', NULL, 'This class is used for marketing expenses including transportation, marketing fee, and telecommunication.', 1, '2020-08-12', NULL),
(22, 'Marketing fee', 21, 'Account for marketing fee', 1, '2020-08-12', 1),
(23, 'Transportation', 21, 'Account for transportation due to marketing', 1, '2020-08-12', 1),
(24, 'Telecomunication', 21, 'Account for telecomunication due to marketing', 1, '2020-08-12', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `fixed_asset`
--

CREATE TABLE `fixed_asset` (
  `id` int(255) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `sold_date` date DEFAULT NULL,
  `value` decimal(50,4) NOT NULL,
  `depreciation_time` int(11) NOT NULL,
  `date` date NOT NULL,
  `type` int(255) NOT NULL,
  `residue_value` float(50,4) NOT NULL,
  `sold_value` decimal(50,4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `fixed_asset_type`
--

CREATE TABLE `fixed_asset_type` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `fixed_asset_type`
--

INSERT INTO `fixed_asset_type` (`id`, `name`, `description`) VALUES
(1, 'Furniture', 'This asset includes office furniture such as desks, tables, chairs, etc.'),
(2, 'Vehicles', 'This asset includes every vehicles.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `good_receipt`
--

CREATE TABLE `good_receipt` (
  `id` int(255) NOT NULL,
  `purchase_order_id` int(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `code_good_receipt_id` int(255) NOT NULL,
  `billed_price` decimal(50,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `income_class`
--

CREATE TABLE `income_class` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `created_by` int(255) NOT NULL,
  `created_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `income_class`
--

INSERT INTO `income_class` (`id`, `name`, `description`, `created_by`, `created_date`) VALUES
(1, 'Royalty', 'This account is used for royalty income.', 1, '2020-07-07'),
(2, 'Saving Interest', 'This account is used for saving interest income.', 1, '2020-07-07'),
(3, 'Sale of fixed asset', 'This account is used for sale of fixed asset income', 1, '2020-07-07'),
(4, 'Other', 'This account is used for other income', 1, '2020-07-07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `internal_bank_account`
--

CREATE TABLE `internal_bank_account` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `number` varchar(50) NOT NULL,
  `bank` varchar(100) NOT NULL,
  `branch` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `internal_bank_account`
--

INSERT INTO `internal_bank_account` (`id`, `name`, `number`, `bank`, `branch`) VALUES
(1, 'Siauw Tjun Kwek', '8090337433', 'Bank Central Asia', 'KCP Ahmad Yani II, Bandung'),
(2, 'CV Agung Elektrindo', '8090249500', 'Bank Central Asia', 'KCP Ahmad Yani II, Bandung');

-- --------------------------------------------------------

--
-- Struktur dari tabel `invoice`
--

CREATE TABLE `invoice` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` decimal(50,2) NOT NULL,
  `discount` decimal(20,4) NOT NULL DEFAULT 0.0000,
  `delivery` decimal(20,4) NOT NULL DEFAULT 0.0000,
  `date` date NOT NULL,
  `information` text NOT NULL,
  `is_done` tinyint(1) DEFAULT 0,
  `is_confirm` tinyint(1) NOT NULL DEFAULT 0,
  `taxInvoice` varchar(50) DEFAULT NULL,
  `lastBillingDate` date DEFAULT NULL,
  `nextBillingDate` date DEFAULT NULL,
  `is_billed` tinyint(1) NOT NULL DEFAULT 0,
  `opponent_id` int(255) DEFAULT NULL,
  `customer_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `item`
--

CREATE TABLE `item` (
  `id` int(255) NOT NULL,
  `reference` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` int(255) DEFAULT NULL,
  `is_notified_stock` tinyint(1) NOT NULL DEFAULT 0,
  `confidence_level` float(5,2) NOT NULL DEFAULT 90.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `item`
--

INSERT INTO `item` (`id`, `reference`, `name`, `type`, `is_notified_stock`, `confidence_level`) VALUES
(1, 'NYM21_50_EXT', 'Kabel NYM 2 x 1,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 2, 1, 90.00),
(2, 'NYM21_100_EXT', 'Kabel NYM 2 x 1,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 2, 0, 90.00),
(3, 'NYM21_250_EXT', 'Kabel NYM 2 x 1,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 2, 0, 90.00),
(4, 'NYM21_500_EXT', 'Kabel NYM 2 x 1,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 2, 0, 90.00),
(5, 'NYM22_50_EXT', 'Kabel NYM 2 x 2,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 2, 0, 90.00),
(6, 'NYM22_100_EXT', 'Kabel NYM 2 x 2,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 2, 0, 90.00),
(7, 'NYM22_250_EXT', 'Kabel NYM 2 x 2,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 2, 0, 90.00),
(8, 'NYM22_500_EXT', 'Kabel NYM 2 x 2,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 2, 0, 90.00),
(9, 'NYM21_1000_EXT', 'Kabel NYM 2 x 1,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 2, 1, 70.00),
(10, 'NYM22_1000_EXT', 'Kabel NYM 2 x 2,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 2, 0, 90.00),
(11, 'NYM31_50_EXT', 'Kabel NYM 3 x 1,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 2, 0, 90.00),
(12, 'NYM31_100_EXT', 'Kabel NYM 3 x 1,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 2, 0, 90.00),
(13, 'NYM31_250_EXT', 'Kabel NYM 3 x 1,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 2, 0, 90.00),
(14, 'NYM31_500_EXT', 'Kabel NYM 3 x 1,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 2, 0, 90.00),
(15, 'NYM31_1000_EXT', 'Kabel NYM 3 x 1,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 2, 0, 90.00),
(16, 'NYM32_50_EXT', 'Kabel NYM 3 x 2,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 2, 0, 90.00),
(17, 'NYM32_100_EXT', 'Kabel NYM 3 x 2,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 2, 0, 90.00),
(18, 'NYM32_250_EXT', 'Kabel NYM 3 x 2,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 2, 0, 90.00),
(19, 'NYM32_500_EXT', 'Kabel NYM 3 x 2,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 2, 0, 90.00),
(20, 'NYM32_1000_EXT', 'Kabel NYM 3 x 2,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 2, 0, 90.00),
(21, 'NYM41_50_EXT', 'Kabel NYM 4 x 1,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 2, 1, 90.00),
(22, 'NYM41_100_EXT', 'Kabel NYM 4 x 1,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 2, 0, 90.00),
(23, 'NYM41_250_EXT', 'Kabel NYM 4 x 1,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 2, 0, 90.00),
(24, 'NYM41_500_EXT', 'Kabel NYM 4 x 1,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 2, 0, 90.00),
(25, 'NYM41_1000_EXT', 'Kabel NYM 4 x 1,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 2, 0, 90.00),
(26, 'NYM42_50_EXT', 'Kabel NYM 4 x 2,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 2, 0, 90.00),
(27, 'NYM42_100_EXT', 'Kabel NYM 4 x 2,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 2, 0, 90.00),
(28, 'NYM42_250_EXT', 'Kabel NYM 4 x 2,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 2, 0, 90.00),
(29, 'NYM42_500_EXT', 'Kabel NYM 4 x 2,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 2, 0, 90.00),
(30, 'NYM42_1000_EXT', 'Kabel NYM 4 x 2,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 2, 0, 90.00),
(31, 'NYY21_50_EXT', 'Kabel NYY 2 x 1,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 3, 0, 90.00),
(32, 'NYY21_100_EXT', 'Kabel NYY 2 x 1,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 3, 0, 90.00),
(33, 'NYY21_250_EXT', 'Kabel NYY 2 x 1,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 3, 0, 90.00),
(34, 'NYY21_500_EXT', 'Kabel NYY 2 x 1,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 3, 0, 90.00),
(35, 'NYY21_1000_EXT', 'Kabel NYY 2 x 1,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 3, 1, 90.00),
(36, 'NYY22_50_EXT', 'Kabel NYY 2 x 2,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 3, 0, 90.00),
(37, 'NYY22_100_EXT', 'Kabel NYY 2 x 2,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 3, 0, 90.00),
(38, 'NYY22_250_EXT', 'Kabel NYY 2 x 2,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 3, 0, 90.00),
(39, 'NYY22_500_EXT', 'Kabel NYY 2 x 2,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 3, 0, 90.00),
(40, 'NYY22_1000_EXT', 'Kabel NYY 2 x 2,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 3, 0, 90.00),
(41, 'NYY31_50_EXT', 'Kabel NYY 3 x 1,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 3, 0, 90.00),
(42, 'NYY31_100_EXT', 'Kabel NYY 3 x 1,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 3, 0, 90.00),
(43, 'NYY31_250_EXT', 'Kabel NYY 3 x 1,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 3, 0, 90.00),
(44, 'NYY31_500_EXT', 'Kabel NYY 3 x 1,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 3, 0, 90.00),
(45, 'NYY31_1000_EXT', 'Kabel NYY 3 x 1,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 3, 0, 90.00),
(46, 'NYY32_50_EXT', 'Kabel NYY 3 x 2,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 3, 0, 90.00),
(47, 'NYY32_100_EXT', 'Kabel NYY 3 x 2,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 3, 0, 90.00),
(48, 'NYY32_250_EXT', 'Kabel NYY 3 x 2,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 3, 0, 90.00),
(49, 'NYY32_500_EXT', 'Kabel NYY 3 x 2,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 3, 0, 90.00),
(50, 'NYY32_1000_EXT', 'Kabel NYY 3 x 2,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 3, 0, 90.00),
(54, 'NYY41_50_EXT', 'Kabel NYY 4 x 1,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 3, 0, 90.00),
(55, 'NYY41_100_EXT', 'Kabel NYY 4 x 1,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 3, 0, 90.00),
(56, 'NYY41_250_EXT', 'Kabel NYY 4 x 1,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 3, 0, 90.00),
(57, 'NYY41_500_EXT', 'Kabel NYY 4 x 1,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 3, 0, 90.00),
(58, 'NYY41_1000_EXT', 'Kabel NYY 4 x 1,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 3, 0, 90.00),
(59, 'NYY42_50_EXT', 'Kabel NYY 4 x 2,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 3, 0, 90.00),
(60, 'NYY42_100_EXT', 'Kabel NYY 4 x 2,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 3, 0, 90.00),
(61, 'NYY42_250_EXT', 'Kabel NYY 4 x 2,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 3, 0, 90.00),
(62, 'NYY42_500_EXT', 'Kabel NYY 4 x 2,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 3, 0, 90.00),
(63, 'NYY42_1000_EXT', 'Kabel NYY 4 x 2,5mm<sup>2</sup> kemasan 1.000 meter (Extrana)', 3, 0, 90.00),
(64, 'NYMHY2075_50_EXT', 'Kabel NYMHY 2 x 0,75mm<sup>2</sup> kemasan 50 meter (Extrana)', 13, 0, 95.00),
(65, 'NYMHY21_50_EXT', 'Kabel NYMHY 2 x 1,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 13, 0, 90.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `item_class`
--

CREATE TABLE `item_class` (
  `id` int(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `created_by` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `item_class`
--

INSERT INTO `item_class` (`id`, `name`, `description`, `created_by`) VALUES
(2, 'Kabel NYM retail ukuran kecil', 'Kabel NYM dengan ukuran per core lebih kecil dari 4mm<sup>2</sup>', 1),
(3, 'Kabel NYY retail ukuran kecil', 'Kabel NYY dengan ukuran per core lebih kecil dari 4mm<sup>2</sup>', 1),
(5, 'Kabel NYM retail ukuran besar', 'Kabel NYM dengan ukuran per core lebih besar dari 4mm<sup>2</sup> dan lebih kecil dari 10mm<sup>2</sup>', 1),
(10, 'Kabel NYY retail ukuran besar', 'Kabel NYM dengan ukuran per core lebih besar dari 4mm<sup>2</sup> dan lebih kecil dari 10mm<sup>2</sup>', 1),
(11, 'Kabel NYA retail ukuran kecil', 'Kabel NYA dengan ukuran per core lebih kecil dari 4mm<sup>2</sup>', 1),
(12, 'Kabel NYA retail ukuran besar', 'Kabel NYA dengan ukuran per core lebih besar dari 4mm<sup>2</sup> dan lebih kecil dari 10mm<sup>2</sup>', 1),
(13, 'Kabel NYMHY retail ukuran kecil', 'Kabel NYMHY dengan ukuran per core lebih kecil dari 4mm<sup>2</sup>', 1),
(14, 'Kabel NYYHY retail ukuran kecil', 'Kabel NYYHY dengan ukuran per core lebih kecil dari 4mm<sup>2</sup>', 1),
(15, 'Kabel NYAF retail ukuran kecil', 'Kabel NYAF dengan ukuran per core lebih kecil dari 4mm<sup>2</sup>', 1),
(16, 'Kabel NYAF retail ukuran kecil', 'Kabel NYA dengan ukuran per core lebih besar dari 4mm<sup>2</sup> dan lebih kecil dari 10mm<sup>2</sup>', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `other_opponent`
--

CREATE TABLE `other_opponent` (
  `id` int(255) NOT NULL,
  `name` varchar(500) NOT NULL,
  `description` text DEFAULT NULL,
  `type` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `other_opponent_type`
--

CREATE TABLE `other_opponent_type` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `other_opponent_type`
--

INSERT INTO `other_opponent_type` (`id`, `name`, `description`) VALUES
(1, 'Private Opponent', 'This account is used for internal transactions.'),
(3, 'Service supplier', 'This opponent is used to accommodate supplier which supplies services.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payable`
--

CREATE TABLE `payable` (
  `id` int(255) NOT NULL,
  `value` decimal(50,2) NOT NULL,
  `bank_id` int(255) DEFAULT NULL,
  `date` date NOT NULL,
  `purchase_id` int(255) DEFAULT NULL,
  `other_purchase_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `petty_cash`
--

CREATE TABLE `petty_cash` (
  `id` int(255) NOT NULL,
  `date` date NOT NULL,
  `transaction` tinyint(4) NOT NULL,
  `value` decimal(50,2) NOT NULL,
  `information` text NOT NULL,
  `expense_class` int(255) DEFAULT NULL,
  `bank_id` int(255) DEFAULT NULL,
  `created_by` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `plafond_submission`
--

CREATE TABLE `plafond_submission` (
  `id` int(255) NOT NULL,
  `customer_id` int(255) NOT NULL,
  `submitted_plafond` decimal(50,2) NOT NULL,
  `submitted_by` int(255) NOT NULL,
  `submitted_date` date NOT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT 0,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `confirmed_by` int(255) DEFAULT NULL,
  `confirmed_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `price_list`
--

CREATE TABLE `price_list` (
  `id` int(255) NOT NULL,
  `item_id` int(255) NOT NULL,
  `price_list` decimal(20,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `price_list`
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
(56, 1, '200000.000'),
(57, 1, '328000.000'),
(58, 54, '664000.000'),
(59, 55, '1328000.000'),
(60, 56, '3320000.000'),
(61, 57, '6640000.000'),
(62, 58, '13280000.000'),
(63, 59, '923200.000'),
(64, 60, '1846400.000'),
(65, 61, '4616000.000'),
(66, 62, '9232000.000'),
(67, 63, '18464000.000'),
(68, 64, '224000.000'),
(69, 65, '384000.000');

-- --------------------------------------------------------

--
-- Struktur dari tabel `purchase_invoice`
--

CREATE TABLE `purchase_invoice` (
  `id` int(255) NOT NULL,
  `date` date NOT NULL,
  `tax_document` varchar(100) DEFAULT NULL,
  `invoice_document` varchar(100) NOT NULL,
  `created_by` int(255) NOT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT 0,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `confirmed_by` int(255) DEFAULT NULL,
  `is_done` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `purchase_invoice_other`
--

CREATE TABLE `purchase_invoice_other` (
  `id` int(255) NOT NULL,
  `date` date NOT NULL,
  `tax_document` varchar(50) DEFAULT NULL,
  `invoice_document` varchar(50) NOT NULL,
  `supplier_id` int(255) DEFAULT NULL,
  `other_opponent_id` int(255) DEFAULT NULL,
  `value` decimal(50,2) NOT NULL,
  `taxing` tinyint(1) NOT NULL,
  `information` text NOT NULL,
  `created_by` int(255) NOT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT 0,
  `confirmed_by` int(255) DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `is_done` tinyint(1) NOT NULL DEFAULT 0,
  `type` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `purchase_order`
--

CREATE TABLE `purchase_order` (
  `id` int(255) NOT NULL,
  `item_id` int(255) NOT NULL,
  `price_list` decimal(50,4) NOT NULL,
  `net_price` decimal(50,4) NOT NULL,
  `quantity` int(255) NOT NULL,
  `received` int(255) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `code_purchase_order_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `purchase_return`
--

CREATE TABLE `purchase_return` (
  `id` int(255) NOT NULL,
  `item_id` int(255) NOT NULL,
  `price` decimal(24,4) NOT NULL,
  `quantity` int(255) NOT NULL,
  `sent` int(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `code_purchase_return_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `purchase_return_sent`
--

CREATE TABLE `purchase_return_sent` (
  `id` int(255) NOT NULL,
  `purchase_return_id` int(255) NOT NULL,
  `code_purchase_return_sent_id` int(255) NOT NULL,
  `quantity` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `quotation`
--

CREATE TABLE `quotation` (
  `id` int(255) NOT NULL,
  `price_list_id` int(255) NOT NULL,
  `discount` decimal(6,4) NOT NULL,
  `quantity` int(11) NOT NULL,
  `code_quotation_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `receivable`
--

CREATE TABLE `receivable` (
  `id` int(255) NOT NULL,
  `bank_id` int(255) DEFAULT NULL,
  `value` decimal(50,2) NOT NULL,
  `date` date NOT NULL,
  `invoice_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `salary_attendance`
--

CREATE TABLE `salary_attendance` (
  `id` int(255) NOT NULL,
  `salary_slip_id` int(255) NOT NULL,
  `status_id` int(255) NOT NULL,
  `value` decimal(20,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `salary_benefit`
--

CREATE TABLE `salary_benefit` (
  `id` int(255) NOT NULL,
  `benefit_id` int(255) NOT NULL,
  `salary_slip_id` int(255) NOT NULL,
  `value` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `salary_slip`
--

CREATE TABLE `salary_slip` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `month` int(2) NOT NULL,
  `year` int(4) NOT NULL,
  `basic` decimal(10,2) NOT NULL,
  `bonus` decimal(10,2) NOT NULL,
  `deduction` decimal(10,2) NOT NULL,
  `created_by` int(255) NOT NULL,
  `created_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_order`
--

CREATE TABLE `sales_order` (
  `id` int(255) NOT NULL,
  `price_list_id` int(255) NOT NULL,
  `discount` decimal(10,4) NOT NULL,
  `quantity` int(255) NOT NULL,
  `sent` int(255) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `code_sales_order_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_return`
--

CREATE TABLE `sales_return` (
  `id` int(255) NOT NULL,
  `delivery_order_id` int(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `received` int(255) NOT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT 0,
  `code_sales_return_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_return_received`
--

CREATE TABLE `sales_return_received` (
  `id` int(255) NOT NULL,
  `code_sales_return_received_id` int(255) NOT NULL,
  `sales_return_id` int(255) NOT NULL,
  `quantity` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `stock_in`
--

CREATE TABLE `stock_in` (
  `id` int(255) NOT NULL,
  `item_id` int(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `residue` int(255) NOT NULL,
  `supplier_id` int(255) DEFAULT NULL,
  `customer_id` int(25) DEFAULT NULL,
  `good_receipt_id` int(255) DEFAULT NULL,
  `sales_return_received_id` int(255) DEFAULT NULL,
  `event_id` int(255) DEFAULT NULL,
  `price` decimal(50,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `stock_out`
--

CREATE TABLE `stock_out` (
  `id` int(255) NOT NULL,
  `in_id` int(255) NOT NULL,
  `quantity` int(255) DEFAULT NULL,
  `customer_id` int(255) DEFAULT NULL,
  `supplier_id` int(255) DEFAULT NULL,
  `delivery_order_id` int(255) DEFAULT NULL,
  `event_id` int(255) DEFAULT NULL,
  `purchase_return_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
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
  `is_black_list` tinyint(1) NOT NULL DEFAULT 0,
  `block` varchar(10) NOT NULL,
  `npwp` varchar(20) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `pic_name` varchar(50) NOT NULL,
  `date_created` date DEFAULT NULL,
  `created_by` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `supplier`
--

INSERT INTO `supplier` (`id`, `name`, `address`, `number`, `rt`, `rw`, `city`, `postal_code`, `is_black_list`, `block`, `npwp`, `phone_number`, `pic_name`, `date_created`, `created_by`) VALUES
(1, 'PT Prima Indah Lestari', 'Jalan Kamal Raya', '83', '003', '002', 'Jakarta Barat', '', 0, '000', '11.111.111.1-111.111', '(021) 5550861', 'Ibu Lina', '2020-01-27', 1),
(2, 'PT ABC', 'Jalan Hayam Wuruk', '23', '003', '004', 'Jakarta Utara', '40513', 0, '002', '01.003.579.1-357.763', '085290000241', 'Martin', '2020-09-27', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(500) NOT NULL,
  `bank_account` varchar(50) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `entry_date` date DEFAULT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `image_url` varchar(100) DEFAULT NULL,
  `access_level` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `address`, `bank_account`, `is_active`, `entry_date`, `password`, `email`, `image_url`, `access_level`) VALUES
(1, 'Daniel Tri', 'Jalan Jamuju no. 18, Bandung', '8090175441', 1, '2020-01-01', '27a9dc715a8e1b472ba494313425de62', 'danielrudianto12@gmail.com', NULL, 5),
(2, 'Andrew Bambang Rudianto', 'Jalan Jamuju no. 18, Bandung', '8090175441', 1, '2020-07-08', 'df68de1228db0edd7590b6c89f8dab7e', 'andrewbambang@gmail.com', NULL, 5),
(16, 'Christian Gerald', 'Jalan J', '878684654', 0, '2020-08-24', 'df12f589a16a759053fc605a6c24d32a', 'christiangerald@gmail.com', NULL, 2),
(17, 'Daniel Tri 2', 'Jalan Jamuju no. 18, Bandung', '80902495000', 1, '2020-08-30', '27a9dc715a8e1b472ba494313425de62', 'danielrudianto12@gmail.comi', NULL, 1),
(18, 'Vinna Sary Rahayu', 'Cijerokaso RT 001 RW 010, Sarijadi Sukasari', '2820329743', 0, '2020-09-02', '3cc51adb5b8d7f83a02361cd34c29c93', 'vinaarka123@gmail.com', NULL, 2),
(19, 'Martin Luhulima', 'Jalan Cimahi 1, Bandung', '808080', 1, '2020-09-06', '34f74c049edea51851c6924f4a386762', 'martinluhulima@gmail.com', NULL, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_authorization`
--

CREATE TABLE `user_authorization` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `department_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_authorization`
--

INSERT INTO `user_authorization` (`id`, `user_id`, `department_id`) VALUES
(12, 17, 2),
(68, 18, 1),
(69, 18, 5),
(71, 1, 6),
(72, 1, 1),
(73, 1, 5),
(74, 1, 4),
(75, 1, 3),
(76, 1, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `visit_list`
--

CREATE TABLE `visit_list` (
  `id` int(255) NOT NULL,
  `customer_id` int(255) NOT NULL,
  `code_visit_list_id` int(255) NOT NULL,
  `note` text NOT NULL,
  `result` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `attendance_list`
--
ALTER TABLE `attendance_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`);

--
-- Indeks untuk tabel `attendance_status`
--
ALTER TABLE `attendance_status`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `bank_assignment`
--
ALTER TABLE `bank_assignment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_id` (`bank_id`),
  ADD KEY `expense_id` (`expense_id`),
  ADD KEY `income_id` (`income_id`);

--
-- Indeks untuk tabel `bank_transaction`
--
ALTER TABLE `bank_transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `bank_transaction_major` (`bank_transaction_major`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `other_id` (`other_id`),
  ADD KEY `internal_account_id` (`internal_account_id`);

--
-- Indeks untuk tabel `benefit`
--
ALTER TABLE `benefit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code_billing_id` (`code_billing_id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indeks untuk tabel `code_billing`
--
ALTER TABLE `code_billing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `confirmed_by` (`confirmed_by`),
  ADD KEY `reported_by` (`reported_by`);

--
-- Indeks untuk tabel `code_delivery_order`
--
ALTER TABLE `code_delivery_order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indeks untuk tabel `code_event`
--
ALTER TABLE `code_event`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `code_good_receipt`
--
ALTER TABLE `code_good_receipt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `confirmed_by` (`confirmed_by`);

--
-- Indeks untuk tabel `code_purchase_order`
--
ALTER TABLE `code_purchase_order`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `code_purchase_return`
--
ALTER TABLE `code_purchase_return`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indeks untuk tabel `code_purchase_return_sent`
--
ALTER TABLE `code_purchase_return_sent`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_id` (`bank_id`),
  ADD KEY `confirmed_by` (`confirmed_by`),
  ADD KEY `created_by` (`created_by`);

--
-- Indeks untuk tabel `code_quotation`
--
ALTER TABLE `code_quotation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `confirmed_by` (`confirmed_by`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indeks untuk tabel `code_sales_order`
--
ALTER TABLE `code_sales_order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `seller` (`seller`),
  ADD KEY `confirmed_by` (`confirmed_by`);

--
-- Indeks untuk tabel `code_sales_order_close_request`
--
ALTER TABLE `code_sales_order_close_request`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `code_sales_return`
--
ALTER TABLE `code_sales_return`
  ADD PRIMARY KEY (`id`),
  ADD KEY `confirmed_by` (`confirmed_by`),
  ADD KEY `created_by` (`created_by`);

--
-- Indeks untuk tabel `code_sales_return_received`
--
ALTER TABLE `code_sales_return_received`
  ADD PRIMARY KEY (`id`),
  ADD KEY `confirmed_by` (`confirmed_by`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `bank_id` (`bank_id`);

--
-- Indeks untuk tabel `code_visit_list`
--
ALTER TABLE `code_visit_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `confirmed_by` (`confirmed_by`),
  ADD KEY `visited_by` (`visited_by`);

--
-- Indeks untuk tabel `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `area_id` (`area_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indeks untuk tabel `customer_accountant`
--
ALTER TABLE `customer_accountant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accountant_id` (`accountant_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indeks untuk tabel `customer_area`
--
ALTER TABLE `customer_area`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `customer_target`
--
ALTER TABLE `customer_target`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indeks untuk tabel `debt_type`
--
ALTER TABLE `debt_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `delivery_order`
--
ALTER TABLE `delivery_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_order_id` (`sales_order_id`),
  ADD KEY `code_delivery_order_id` (`code_delivery_order_id`);

--
-- Indeks untuk tabel `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code_event_id` (`code_event_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indeks untuk tabel `expense_class`
--
ALTER TABLE `expense_class`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indeks untuk tabel `fixed_asset`
--
ALTER TABLE `fixed_asset`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`);

--
-- Indeks untuk tabel `fixed_asset_type`
--
ALTER TABLE `fixed_asset_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `good_receipt`
--
ALTER TABLE `good_receipt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_id` (`purchase_order_id`),
  ADD KEY `code_good_receipt_id` (`code_good_receipt_id`);

--
-- Indeks untuk tabel `income_class`
--
ALTER TABLE `income_class`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `internal_bank_account`
--
ALTER TABLE `internal_bank_account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`);

--
-- Indeks untuk tabel `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `opponent_id` (`opponent_id`);

--
-- Indeks untuk tabel `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`);

--
-- Indeks untuk tabel `item_class`
--
ALTER TABLE `item_class`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `other_opponent`
--
ALTER TABLE `other_opponent`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `id` (`id`) USING BTREE,
  ADD KEY `type` (`type`);

--
-- Indeks untuk tabel `other_opponent_type`
--
ALTER TABLE `other_opponent_type`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `payable`
--
ALTER TABLE `payable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_id` (`bank_id`),
  ADD KEY `purchase_id` (`purchase_id`),
  ADD KEY `other_purchase_id` (`other_purchase_id`);

--
-- Indeks untuk tabel `petty_cash`
--
ALTER TABLE `petty_cash`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_id` (`bank_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `expense_class` (`expense_class`);

--
-- Indeks untuk tabel `plafond_submission`
--
ALTER TABLE `plafond_submission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submitted_by` (`submitted_by`);

--
-- Indeks untuk tabel `price_list`
--
ALTER TABLE `price_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indeks untuk tabel `purchase_invoice`
--
ALTER TABLE `purchase_invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `confirmed_by` (`confirmed_by`);

--
-- Indeks untuk tabel `purchase_invoice_other`
--
ALTER TABLE `purchase_invoice_other`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `confirmed_by` (`confirmed_by`),
  ADD KEY `type` (`type`),
  ADD KEY `other_opponent_id` (`other_opponent_id`);

--
-- Indeks untuk tabel `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code_purchase_order_id` (`code_purchase_order_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indeks untuk tabel `purchase_return`
--
ALTER TABLE `purchase_return`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code_purchase_return_id` (`code_purchase_return_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indeks untuk tabel `purchase_return_sent`
--
ALTER TABLE `purchase_return_sent`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_return_id` (`purchase_return_id`),
  ADD KEY `code_purchase_return_sent_id` (`code_purchase_return_sent_id`);

--
-- Indeks untuk tabel `quotation`
--
ALTER TABLE `quotation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code_quotation_id` (`code_quotation_id`),
  ADD KEY `price_list_id` (`price_list_id`);

--
-- Indeks untuk tabel `receivable`
--
ALTER TABLE `receivable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_id` (`bank_id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indeks untuk tabel `salary_attendance`
--
ALTER TABLE `salary_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_slip_id` (`salary_slip_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indeks untuk tabel `salary_benefit`
--
ALTER TABLE `salary_benefit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_slip_id` (`salary_slip_id`),
  ADD KEY `benefit_id` (`benefit_id`);

--
-- Indeks untuk tabel `salary_slip`
--
ALTER TABLE `salary_slip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indeks untuk tabel `sales_order`
--
ALTER TABLE `sales_order`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sales_return`
--
ALTER TABLE `sales_return`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_order_id` (`delivery_order_id`),
  ADD KEY `code_sales_return_id` (`code_sales_return_id`);

--
-- Indeks untuk tabel `sales_return_received`
--
ALTER TABLE `sales_return_received`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code_sales_return_received_id` (`code_sales_return_received_id`),
  ADD KEY `sales_return_id` (`sales_return_id`);

--
-- Indeks untuk tabel `stock_in`
--
ALTER TABLE `stock_in`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `good_receipt_id` (`good_receipt_id`);

--
-- Indeks untuk tabel `stock_out`
--
ALTER TABLE `stock_out`
  ADD PRIMARY KEY (`id`),
  ADD KEY `in_id` (`in_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `delivery_order_id` (`delivery_order_id`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `user_authorization`
--
ALTER TABLE `user_authorization`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indeks untuk tabel `visit_list`
--
ALTER TABLE `visit_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `code_visit_list_id` (`code_visit_list_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `attendance_list`
--
ALTER TABLE `attendance_list`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `attendance_status`
--
ALTER TABLE `attendance_status`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `bank_assignment`
--
ALTER TABLE `bank_assignment`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `bank_transaction`
--
ALTER TABLE `bank_transaction`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `benefit`
--
ALTER TABLE `benefit`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `billing`
--
ALTER TABLE `billing`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `code_billing`
--
ALTER TABLE `code_billing`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `code_delivery_order`
--
ALTER TABLE `code_delivery_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `code_event`
--
ALTER TABLE `code_event`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `code_good_receipt`
--
ALTER TABLE `code_good_receipt`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `code_purchase_order`
--
ALTER TABLE `code_purchase_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `code_purchase_return`
--
ALTER TABLE `code_purchase_return`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `code_purchase_return_sent`
--
ALTER TABLE `code_purchase_return_sent`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `code_quotation`
--
ALTER TABLE `code_quotation`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `code_sales_order`
--
ALTER TABLE `code_sales_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `code_sales_order_close_request`
--
ALTER TABLE `code_sales_order_close_request`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `code_sales_return`
--
ALTER TABLE `code_sales_return`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `code_sales_return_received`
--
ALTER TABLE `code_sales_return_received`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `code_visit_list`
--
ALTER TABLE `code_visit_list`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=274;

--
-- AUTO_INCREMENT untuk tabel `customer_accountant`
--
ALTER TABLE `customer_accountant`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=385;

--
-- AUTO_INCREMENT untuk tabel `customer_area`
--
ALTER TABLE `customer_area`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `customer_target`
--
ALTER TABLE `customer_target`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT untuk tabel `debt_type`
--
ALTER TABLE `debt_type`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `delivery_order`
--
ALTER TABLE `delivery_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `department`
--
ALTER TABLE `department`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `event`
--
ALTER TABLE `event`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `expense_class`
--
ALTER TABLE `expense_class`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `fixed_asset`
--
ALTER TABLE `fixed_asset`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `fixed_asset_type`
--
ALTER TABLE `fixed_asset_type`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `good_receipt`
--
ALTER TABLE `good_receipt`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `income_class`
--
ALTER TABLE `income_class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `internal_bank_account`
--
ALTER TABLE `internal_bank_account`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `item`
--
ALTER TABLE `item`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT untuk tabel `item_class`
--
ALTER TABLE `item_class`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `other_opponent`
--
ALTER TABLE `other_opponent`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `other_opponent_type`
--
ALTER TABLE `other_opponent_type`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `payable`
--
ALTER TABLE `payable`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `petty_cash`
--
ALTER TABLE `petty_cash`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `plafond_submission`
--
ALTER TABLE `plafond_submission`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `price_list`
--
ALTER TABLE `price_list`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT untuk tabel `purchase_invoice`
--
ALTER TABLE `purchase_invoice`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `purchase_invoice_other`
--
ALTER TABLE `purchase_invoice_other`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `purchase_return`
--
ALTER TABLE `purchase_return`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `purchase_return_sent`
--
ALTER TABLE `purchase_return_sent`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `quotation`
--
ALTER TABLE `quotation`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `receivable`
--
ALTER TABLE `receivable`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `salary_attendance`
--
ALTER TABLE `salary_attendance`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `salary_benefit`
--
ALTER TABLE `salary_benefit`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `salary_slip`
--
ALTER TABLE `salary_slip`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `sales_order`
--
ALTER TABLE `sales_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sales_return`
--
ALTER TABLE `sales_return`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sales_return_received`
--
ALTER TABLE `sales_return_received`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `stock_in`
--
ALTER TABLE `stock_in`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `stock_out`
--
ALTER TABLE `stock_out`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `user_authorization`
--
ALTER TABLE `user_authorization`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT untuk tabel `visit_list`
--
ALTER TABLE `visit_list`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `attendance_list`
--
ALTER TABLE `attendance_list`
  ADD CONSTRAINT `attendance_list_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `attendance_list_ibfk_2` FOREIGN KEY (`status`) REFERENCES `attendance_status` (`id`);

--
-- Ketidakleluasaan untuk tabel `bank_assignment`
--
ALTER TABLE `bank_assignment`
  ADD CONSTRAINT `bank_assignment_ibfk_1` FOREIGN KEY (`bank_id`) REFERENCES `bank_transaction` (`id`),
  ADD CONSTRAINT `bank_assignment_ibfk_2` FOREIGN KEY (`expense_id`) REFERENCES `expense_class` (`id`),
  ADD CONSTRAINT `bank_assignment_ibfk_3` FOREIGN KEY (`income_id`) REFERENCES `income_class` (`id`);

--
-- Ketidakleluasaan untuk tabel `bank_transaction`
--
ALTER TABLE `bank_transaction`
  ADD CONSTRAINT `bank_transaction_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `internal_bank_account` (`id`),
  ADD CONSTRAINT `bank_transaction_ibfk_2` FOREIGN KEY (`bank_transaction_major`) REFERENCES `bank_transaction` (`id`),
  ADD CONSTRAINT `bank_transaction_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `bank_transaction_ibfk_4` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`),
  ADD CONSTRAINT `bank_transaction_ibfk_5` FOREIGN KEY (`other_id`) REFERENCES `other_opponent` (`id`),
  ADD CONSTRAINT `bank_transaction_ibfk_6` FOREIGN KEY (`internal_account_id`) REFERENCES `internal_bank_account` (`id`);

--
-- Ketidakleluasaan untuk tabel `billing`
--
ALTER TABLE `billing`
  ADD CONSTRAINT `billing_ibfk_1` FOREIGN KEY (`code_billing_id`) REFERENCES `code_billing` (`id`),
  ADD CONSTRAINT `billing_ibfk_3` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`);

--
-- Ketidakleluasaan untuk tabel `code_billing`
--
ALTER TABLE `code_billing`
  ADD CONSTRAINT `code_billing_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `code_billing_ibfk_2` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `code_billing_ibfk_3` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `code_delivery_order`
--
ALTER TABLE `code_delivery_order`
  ADD CONSTRAINT `code_delivery_order_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`);

--
-- Ketidakleluasaan untuk tabel `code_good_receipt`
--
ALTER TABLE `code_good_receipt`
  ADD CONSTRAINT `code_good_receipt_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `purchase_invoice` (`id`),
  ADD CONSTRAINT `code_good_receipt_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `code_good_receipt_ibfk_3` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `code_purchase_return`
--
ALTER TABLE `code_purchase_return`
  ADD CONSTRAINT `code_purchase_return_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`),
  ADD CONSTRAINT `code_purchase_return_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `code_purchase_return_sent`
--
ALTER TABLE `code_purchase_return_sent`
  ADD CONSTRAINT `code_purchase_return_sent_ibfk_1` FOREIGN KEY (`bank_id`) REFERENCES `bank_transaction` (`id`),
  ADD CONSTRAINT `code_purchase_return_sent_ibfk_2` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `code_purchase_return_sent_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `code_quotation`
--
ALTER TABLE `code_quotation`
  ADD CONSTRAINT `code_quotation_ibfk_1` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `code_quotation_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `code_quotation_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);

--
-- Ketidakleluasaan untuk tabel `code_sales_order`
--
ALTER TABLE `code_sales_order`
  ADD CONSTRAINT `code_sales_order_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `code_sales_order_ibfk_2` FOREIGN KEY (`seller`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `code_sales_order_ibfk_3` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `code_sales_return`
--
ALTER TABLE `code_sales_return`
  ADD CONSTRAINT `code_sales_return_ibfk_1` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `code_sales_return_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `code_sales_return_received`
--
ALTER TABLE `code_sales_return_received`
  ADD CONSTRAINT `code_sales_return_received_ibfk_1` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `code_sales_return_received_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `code_sales_return_received_ibfk_3` FOREIGN KEY (`bank_id`) REFERENCES `bank_transaction` (`id`);

--
-- Ketidakleluasaan untuk tabel `code_visit_list`
--
ALTER TABLE `code_visit_list`
  ADD CONSTRAINT `code_visit_list_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `code_visit_list_ibfk_2` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `code_visit_list_ibfk_3` FOREIGN KEY (`visited_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `customer_area` (`id`),
  ADD CONSTRAINT `customer_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `customer_accountant`
--
ALTER TABLE `customer_accountant`
  ADD CONSTRAINT `customer_accountant_ibfk_1` FOREIGN KEY (`accountant_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `customer_accountant_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);

--
-- Ketidakleluasaan untuk tabel `customer_target`
--
ALTER TABLE `customer_target`
  ADD CONSTRAINT `customer_target_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `customer_target_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `delivery_order`
--
ALTER TABLE `delivery_order`
  ADD CONSTRAINT `delivery_order_ibfk_1` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_order` (`id`),
  ADD CONSTRAINT `delivery_order_ibfk_2` FOREIGN KEY (`code_delivery_order_id`) REFERENCES `code_delivery_order` (`id`);

--
-- Ketidakleluasaan untuk tabel `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`);

--
-- Ketidakleluasaan untuk tabel `expense_class`
--
ALTER TABLE `expense_class`
  ADD CONSTRAINT `expense_class_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `expense_class_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `expense_class` (`id`);

--
-- Ketidakleluasaan untuk tabel `fixed_asset`
--
ALTER TABLE `fixed_asset`
  ADD CONSTRAINT `fixed_asset_ibfk_1` FOREIGN KEY (`type`) REFERENCES `fixed_asset_type` (`id`);

--
-- Ketidakleluasaan untuk tabel `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`opponent_id`) REFERENCES `other_opponent` (`id`);

--
-- Ketidakleluasaan untuk tabel `other_opponent`
--
ALTER TABLE `other_opponent`
  ADD CONSTRAINT `other_opponent_ibfk_1` FOREIGN KEY (`type`) REFERENCES `other_opponent_type` (`id`);

--
-- Ketidakleluasaan untuk tabel `payable`
--
ALTER TABLE `payable`
  ADD CONSTRAINT `payable_ibfk_1` FOREIGN KEY (`bank_id`) REFERENCES `bank_transaction` (`id`),
  ADD CONSTRAINT `payable_ibfk_2` FOREIGN KEY (`purchase_id`) REFERENCES `purchase_invoice` (`id`),
  ADD CONSTRAINT `payable_ibfk_4` FOREIGN KEY (`other_purchase_id`) REFERENCES `purchase_invoice_other` (`id`);

--
-- Ketidakleluasaan untuk tabel `petty_cash`
--
ALTER TABLE `petty_cash`
  ADD CONSTRAINT `petty_cash_ibfk_1` FOREIGN KEY (`bank_id`) REFERENCES `bank_transaction` (`id`),
  ADD CONSTRAINT `petty_cash_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `petty_cash_ibfk_3` FOREIGN KEY (`expense_class`) REFERENCES `expense_class` (`id`);

--
-- Ketidakleluasaan untuk tabel `purchase_invoice`
--
ALTER TABLE `purchase_invoice`
  ADD CONSTRAINT `purchase_invoice_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchase_invoice_ibfk_2` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `purchase_invoice_other`
--
ALTER TABLE `purchase_invoice_other`
  ADD CONSTRAINT `purchase_invoice_other_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchase_invoice_other_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`),
  ADD CONSTRAINT `purchase_invoice_other_ibfk_3` FOREIGN KEY (`other_opponent_id`) REFERENCES `other_opponent` (`id`),
  ADD CONSTRAINT `purchase_invoice_other_ibfk_4` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchase_invoice_other_ibfk_5` FOREIGN KEY (`type`) REFERENCES `debt_type` (`id`);

--
-- Ketidakleluasaan untuk tabel `purchase_return`
--
ALTER TABLE `purchase_return`
  ADD CONSTRAINT `purchase_return_ibfk_1` FOREIGN KEY (`code_purchase_return_id`) REFERENCES `code_purchase_return` (`id`),
  ADD CONSTRAINT `purchase_return_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`);

--
-- Ketidakleluasaan untuk tabel `purchase_return_sent`
--
ALTER TABLE `purchase_return_sent`
  ADD CONSTRAINT `purchase_return_sent_ibfk_1` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_return` (`id`),
  ADD CONSTRAINT `purchase_return_sent_ibfk_2` FOREIGN KEY (`code_purchase_return_sent_id`) REFERENCES `code_purchase_return_sent` (`id`);

--
-- Ketidakleluasaan untuk tabel `quotation`
--
ALTER TABLE `quotation`
  ADD CONSTRAINT `quotation_ibfk_1` FOREIGN KEY (`code_quotation_id`) REFERENCES `code_quotation` (`id`),
  ADD CONSTRAINT `quotation_ibfk_2` FOREIGN KEY (`price_list_id`) REFERENCES `price_list` (`id`);

--
-- Ketidakleluasaan untuk tabel `receivable`
--
ALTER TABLE `receivable`
  ADD CONSTRAINT `receivable_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`),
  ADD CONSTRAINT `receivable_ibfk_2` FOREIGN KEY (`bank_id`) REFERENCES `bank_transaction` (`id`);

--
-- Ketidakleluasaan untuk tabel `salary_attendance`
--
ALTER TABLE `salary_attendance`
  ADD CONSTRAINT `salary_attendance_ibfk_1` FOREIGN KEY (`salary_slip_id`) REFERENCES `salary_slip` (`id`),
  ADD CONSTRAINT `salary_attendance_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `attendance_status` (`id`);

--
-- Ketidakleluasaan untuk tabel `salary_benefit`
--
ALTER TABLE `salary_benefit`
  ADD CONSTRAINT `salary_benefit_ibfk_1` FOREIGN KEY (`salary_slip_id`) REFERENCES `salary_slip` (`id`),
  ADD CONSTRAINT `salary_benefit_ibfk_2` FOREIGN KEY (`benefit_id`) REFERENCES `benefit` (`id`);

--
-- Ketidakleluasaan untuk tabel `salary_slip`
--
ALTER TABLE `salary_slip`
  ADD CONSTRAINT `salary_slip_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `salary_slip_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `sales_return`
--
ALTER TABLE `sales_return`
  ADD CONSTRAINT `sales_return_ibfk_1` FOREIGN KEY (`delivery_order_id`) REFERENCES `delivery_order` (`id`),
  ADD CONSTRAINT `sales_return_ibfk_2` FOREIGN KEY (`code_sales_return_id`) REFERENCES `code_sales_return` (`id`);

--
-- Ketidakleluasaan untuk tabel `sales_return_received`
--
ALTER TABLE `sales_return_received`
  ADD CONSTRAINT `sales_return_received_ibfk_1` FOREIGN KEY (`code_sales_return_received_id`) REFERENCES `code_sales_return_received` (`id`),
  ADD CONSTRAINT `sales_return_received_ibfk_2` FOREIGN KEY (`sales_return_id`) REFERENCES `sales_return` (`id`);

--
-- Ketidakleluasaan untuk tabel `stock_in`
--
ALTER TABLE `stock_in`
  ADD CONSTRAINT `stock_in_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `stock_in_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`),
  ADD CONSTRAINT `stock_in_ibfk_3` FOREIGN KEY (`good_receipt_id`) REFERENCES `good_receipt` (`id`),
  ADD CONSTRAINT `stock_in_ibfk_4` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`);

--
-- Ketidakleluasaan untuk tabel `stock_out`
--
ALTER TABLE `stock_out`
  ADD CONSTRAINT `stock_out_ibfk_1` FOREIGN KEY (`in_id`) REFERENCES `stock_in` (`id`),
  ADD CONSTRAINT `stock_out_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `stock_out_ibfk_3` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`),
  ADD CONSTRAINT `stock_out_ibfk_4` FOREIGN KEY (`delivery_order_id`) REFERENCES `delivery_order` (`id`);

--
-- Ketidakleluasaan untuk tabel `user_authorization`
--
ALTER TABLE `user_authorization`
  ADD CONSTRAINT `user_authorization_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_authorization_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`);

--
-- Ketidakleluasaan untuk tabel `visit_list`
--
ALTER TABLE `visit_list`
  ADD CONSTRAINT `visit_list_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `visit_list_ibfk_2` FOREIGN KEY (`code_visit_list_id`) REFERENCES `code_visit_list` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
