-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Agu 2020 pada 09.02
-- Versi server: 10.1.38-MariaDB
-- Versi PHP: 7.3.2

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
-- Struktur dari tabel `attendance_list`
--

CREATE TABLE `attendance_list` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `date` date NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `attendance_list`
--

INSERT INTO `attendance_list` (`id`, `user_id`, `date`, `time`, `status`) VALUES
(1, 1, '2020-08-24', '0000-00-00 00:00:00', 3),
(2, 2, '2020-08-24', '0000-00-00 00:00:00', 2),
(3, 16, '2020-08-24', '2020-08-24 08:48:19', 2);

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
  `is_done` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `bank_transaction_major` int(255) DEFAULT NULL,
  `account_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `bank_transaction`
--

INSERT INTO `bank_transaction` (`id`, `value`, `date`, `transaction`, `customer_id`, `supplier_id`, `other_id`, `is_done`, `is_delete`, `bank_transaction_major`, `account_id`) VALUES
(1, '3000000.00', '2020-08-19', 2, NULL, NULL, 1, 0, 0, NULL, 2),
(2, '23000000.00', '2020-08-22', 1, 87, NULL, NULL, 1, 0, NULL, 2),
(3, '20000000.00', '2020-08-21', 2, NULL, 1, NULL, 1, 0, NULL, 2),
(4, '2000000.00', '2020-08-21', 2, NULL, NULL, NULL, 0, 0, NULL, 2);

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
  `result` text NOT NULL,
  `billed_by` int(255) NOT NULL,
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
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `confirmed_by` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_delivery_order`
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
-- Dumping data untuk tabel `code_delivery_order`
--

INSERT INTO `code_delivery_order` (`id`, `date`, `name`, `is_confirm`, `is_delete`, `is_sent`, `guid`, `invoice_id`) VALUES
(1, '2020-08-19', 'DO-DSE-202008-00010', 1, 0, 1, '8D1E0AF7-A735-4D8F-911A-0D79C7EDBE34', 1),
(2, '2020-08-19', 'DO-DSE-202008-00021', 1, 0, 1, 'BB04DD16-F817-4EFB-AABF-FF08E45CBBD6', 2),
(3, '2020-08-19', 'DO-DSE-202008-00031', 1, 0, 1, 'AE917BE2-726F-4EE6-9192-B97B7F39B05A', 5),
(4, '2020-08-19', 'DO-DSE-202008-00040', 1, 0, 1, 'C6FDAF1D-C9CE-4AFD-A8CE-DD1FF8F9D7AD', 4),
(5, '2020-08-21', 'DO-DSE-202008-00050', 1, 0, 1, '255F8863-9CB6-4831-BDFB-8ED1CE8A4452', 6),
(6, '2020-08-21', 'DO-DSE-202008-00061', 1, 0, 1, '6F99E4F2-5737-4EDC-B03C-1AEA39D0BB3D', NULL),
(7, '2020-08-21', 'DO-DSE-202008-00070', 1, 0, 1, '3FDDF488-1D69-4057-8EE0-2A207E71B2B6', 7),
(8, '2020-08-22', 'DO-DSE-202008-00080', 1, 0, 1, 'F497224A-6DB8-407F-9A0F-1A6F33CA65E9', 8);

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
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `confirmed_by` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `code_event`
--

INSERT INTO `code_event` (`id`, `type`, `name`, `created_by`, `date`, `is_confirm`, `confirmed_by`) VALUES
(2, 1, 'EVT-202008-02103489', 1, '2020-08-19', 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_good_receipt`
--

CREATE TABLE `code_good_receipt` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `invoice_id` int(255) DEFAULT NULL,
  `received_date` date NOT NULL,
  `created_by` int(255) NOT NULL,
  `confirmed_by` int(255) DEFAULT NULL,
  `guid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `code_good_receipt`
--

INSERT INTO `code_good_receipt` (`id`, `name`, `date`, `is_confirm`, `is_delete`, `invoice_id`, `received_date`, `created_by`, `confirmed_by`, `guid`) VALUES
(1, 'PI-CK-SL-DOT2JUA', '2020-08-18', 1, 0, 1, '2020-08-18', 1, 1, 'FEB314E2-C997-476E-A3B6-9326BA2D9921'),
(2, 'PI-CK-BO-ABGEF', '2020-08-19', 1, 0, 1, '2020-08-18', 1, 1, 'E164166D-56F6-4B96-96C3-11476F2848A7'),
(3, 'PI-CK-BO-ABGEF', '2020-08-18', 1, 0, NULL, '2020-08-18', 1, 1, '8D0C0487-05BD-4363-9870-3D1E11B02CCE'),
(4, 'aaaa', '2020-08-21', 1, 0, NULL, '2020-08-20', 1, 1, '5B55ADB4-F961-428A-90AB-D8B7F8ECA8D1'),
(5, 'bbbb', '2020-08-21', 1, 0, NULL, '2020-08-20', 1, 1, 'FD0423B7-8532-476F-802D-741404E5A14B'),
(6, 'asdf', '2020-08-21', 1, 0, NULL, '2020-08-20', 1, 1, 'BD88AFEF-6FDB-4443-B72C-197E8958C290'),
(7, 'sdfg', '2020-08-21', 1, 0, NULL, '2020-08-20', 1, 1, 'D118641F-DD16-4C25-B963-9439A3583293'),
(8, 'wwww', '2020-08-21', 1, 0, NULL, '2020-08-21', 1, 1, '29E67C43-CDD4-445B-8425-8DE98C26C8E2'),
(9, 'asdf', '2020-08-21', 1, 0, NULL, '2020-08-21', 1, 1, '7B747654-5EDE-45CF-B488-F5CC99B33E77'),
(10, 'oooo', '2020-08-21', 1, 0, NULL, '2020-08-21', 1, 1, '3C73ED3F-2AD7-4398-A6F3-EEB0E0E1897E');

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
  `is_closed` tinyint(1) NOT NULL DEFAULT '0',
  `promo_code` varchar(50) DEFAULT NULL,
  `dropship_address` text,
  `dropship_city` varchar(50) DEFAULT NULL,
  `dropship_contact_person` varchar(50) DEFAULT NULL,
  `dropship_contact` varchar(50) DEFAULT NULL,
  `taxing` tinyint(1) NOT NULL,
  `date_send_request` date DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `guid` varchar(50) NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `code_purchase_order`
--

INSERT INTO `code_purchase_order` (`id`, `date`, `name`, `supplier_id`, `created_by`, `confirmed_by`, `is_closed`, `promo_code`, `dropship_address`, `dropship_city`, `dropship_contact_person`, `dropship_contact`, `taxing`, `date_send_request`, `status`, `guid`, `is_delete`, `is_confirm`, `note`) VALUES
(1, '2020-08-18', 'PO.DSE-202008-4494', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 1, NULL, 'TOP URGENT', '31AE0FC7-9152-4E47-87A8-DAD6B1991AF2', 0, 1, ''),
(2, '2020-08-18', 'PO.DSE-202008-8793', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 1, NULL, 'TOP URGENT', '5F6A076B-9F98-43C3-BC90-BC96153B6771', 0, 1, ''),
(3, '2020-08-18', 'PO.DSE-202008-1285', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 1, NULL, 'TOP URGENT', '6CD78F87-491A-4DE2-A66A-15F1E03C7D88', 0, 1, ''),
(4, '2020-08-20', 'PO.DSE-202008-5864', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 1, NULL, 'TOP URGENT', '8202A024-0B7C-466C-A013-D336CADE1675', 0, 1, ''),
(5, '2020-08-20', 'PO.DSE-202008-7769', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 1, NULL, 'TOP URGENT', 'E04C6C38-D63C-4506-B2ED-05ECEC6AC1B7', 0, 1, ''),
(6, '2020-08-20', 'PO.DSE-202008-5909', 1, 1, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '667EA103-7097-4F0A-811C-0AC649F2743C', 1, 0, ''),
(7, '2020-08-21', 'PO.DSE-202008-7116', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 1, NULL, 'TOP URGENT', 'BB52A959-E010-4A19-A833-015355B9A1BC', 0, 1, '');

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
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `confirmed_by` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `code_purchase_return`
--

INSERT INTO `code_purchase_return` (`id`, `name`, `supplier_id`, `created_by`, `created_date`, `is_confirm`, `is_delete`, `confirmed_by`) VALUES
(1, 'PRS-202008-42687639', 1, 1, '2020-08-22', 0, 0, NULL),
(2, 'PRS-202008-44572414', 1, 1, '2020-08-22', 0, 0, NULL),
(3, 'PRS-202008-10202201', 1, 1, '2020-08-22', 0, 0, NULL),
(4, 'PRS-202008-17680692', 1, 1, '2020-08-22', 1, 0, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_purchase_return_sent`
--

CREATE TABLE `code_purchase_return_sent` (
  `id` int(255) NOT NULL,
  `created_by` int(255) NOT NULL,
  `created_date` date NOT NULL,
  `confirmed_by` int(255) DEFAULT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT '0',
  `bank_id` int(255) DEFAULT NULL
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
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `confirmed_by` int(255) DEFAULT NULL,
  `guid` varchar(50) NOT NULL,
  `created_by` int(255) NOT NULL,
  `invoicing_method` tinyint(1) NOT NULL DEFAULT '1',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `code_sales_order`
--

INSERT INTO `code_sales_order` (`id`, `customer_id`, `name`, `date`, `taxing`, `seller`, `is_confirm`, `confirmed_by`, `guid`, `created_by`, `invoicing_method`, `is_delete`) VALUES
(1, 116, '202008.85617173', '2020-08-18', 1, 1, 1, 1, '7734C80F-239F-4778-B440-F481E0D3B1E3', 1, 2, 0),
(2, 116, '202008.74601026', '2020-08-18', 0, NULL, 1, 1, 'C2E62184-69E8-42D2-8CF9-E6CB919F62FE', 1, 1, 0),
(3, 124, '202008.77832012', '2020-08-19', 0, NULL, 1, 1, '43CEB680-8381-4240-AA25-357DB277A208', 1, 1, 0),
(4, 264, '202008.89276369', '2020-08-20', 0, NULL, 1, 1, '51CEE3A9-BB28-4331-9C07-13ADAF0405FD', 1, 1, 0),
(5, 6, '202008.03642284', '2020-08-20', 0, NULL, 1, 1, '82AA4068-A1FF-4374-B26E-F15AF47875B0', 1, 1, 0),
(6, 87, '202008.83768011', '2020-08-21', 0, NULL, 1, 1, 'EBDBDEA4-0FF7-46A2-BF2D-D0F70C3D9C5D', 1, 1, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_sales_order_close_request`
--

CREATE TABLE `code_sales_order_close_request` (
  `id` int(255) NOT NULL,
  `date` date NOT NULL,
  `code_sales_order_id` int(255) NOT NULL,
  `requested_by` int(255) NOT NULL,
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
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `confirmed_by` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `code_sales_return`
--

INSERT INTO `code_sales_return` (`id`, `name`, `created_by`, `created_date`, `is_confirm`, `is_delete`, `confirmed_by`) VALUES
(1, 'SRS-202008-68932985', 1, '2020-08-18', 1, 0, 1),
(2, 'SRS-202008-29632063', 1, '2020-08-19', 1, 0, NULL),
(3, 'SRS-202008-98134918', 1, '2020-08-20', 1, 0, NULL),
(4, 'SRS-202008-52038778', 1, '2020-08-20', 1, 0, NULL),
(5, 'SRS-202008-04711550', 1, '2020-08-21', 1, 0, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_sales_return_received`
--

CREATE TABLE `code_sales_return_received` (
  `id` int(255) NOT NULL,
  `created_by` int(255) NOT NULL,
  `created_date` date NOT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `confirmed_by` int(255) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT '0',
  `bank_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `code_sales_return_received`
--

INSERT INTO `code_sales_return_received` (`id`, `created_by`, `created_date`, `is_confirm`, `is_delete`, `confirmed_by`, `name`, `date`, `is_done`, `bank_id`) VALUES
(1, 1, '2020-08-18', 1, 0, 1, '202008-00010-RT', '2020-08-18', 0, NULL),
(2, 1, '2020-08-19', 1, 0, 1, 'DO-DSE-202008-00021-RT', '2020-08-19', 0, NULL),
(3, 1, '2020-08-20', 0, 1, 1, 'DO-DSE-202008-00040-RT', '2020-08-20', 0, NULL),
(4, 1, '2020-08-20', 1, 0, 1, '202008-00050-RT', '2020-08-25', 0, NULL),
(5, 1, '2020-08-21', 1, 0, 1, '123RT', '2020-08-22', 0, NULL),
(6, 1, '2020-08-21', 1, 0, 1, '87123', '2020-08-21', 0, NULL),
(7, 1, '2020-08-25', 0, 1, 1, 'asdfasdfasdfasd', '2020-08-25', 0, NULL),
(8, 1, '2020-08-25', 1, 0, 1, 'asdfasdfasdfasdf', '2020-08-25', 0, NULL);

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
  `is_black_list` tinyint(1) NOT NULL DEFAULT '0',
  `block` varchar(10) NOT NULL,
  `npwp` varchar(20) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `pic_name` varchar(50) NOT NULL,
  `date_created` date DEFAULT NULL,
  `created_by` int(255) NOT NULL,
  `latitude` decimal(33,30) DEFAULT NULL,
  `longitude` decimal(33,30) DEFAULT NULL,
  `term_of_payment` int(255) NOT NULL DEFAULT '45',
  `plafond` decimal(50,2) NOT NULL,
  `is_remind` tinyint(1) NOT NULL DEFAULT '1',
  `visiting_frequency` int(1) NOT NULL DEFAULT '1',
  `uid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `customer`
--

INSERT INTO `customer` (`id`, `name`, `address`, `number`, `rt`, `rw`, `city`, `postal_code`, `area_id`, `is_black_list`, `block`, `npwp`, `phone_number`, `pic_name`, `date_created`, `created_by`, `latitude`, `longitude`, `term_of_payment`, `plafond`, `is_remind`, `visiting_frequency`, `uid`) VALUES
(1, 'Toko Sumber Lampu', 'Jalan Jendral Ahmad Yani (Plaza IBCC)', '3', '029', '006', 'Bandung', '40271', 1, 0, 'B2', NULL, '(022) 7233271', 'Bapak Ayung', '2020-01-24', 1, NULL, NULL, 45, '3000000.00', 1, 1, '72297039'),
(5, 'Toko Agni Surya', 'Jalan Jendral Ahmad Yani', '353', '000', '000', 'Bandung', '40121', 1, 0, '', NULL, '(022) 7273893', 'Ibu Yani', '2020-01-24', 1, NULL, NULL, 45, '3000000.00', 1, 1, '09715857'),
(6, 'Toko Trijaya 2', 'Jalan Cikawao', '56', '001', '001', 'Bandung', '40261', 1, 0, '', NULL, '(022) 4220661', 'Bapak Yohan', '2020-01-24', 1, NULL, NULL, 45, '3000000.00', 1, 1, '45860382'),
(7, 'Toko Utama Lighting', 'Jalan Jendral Ahmad Yani (Plaza IBCC)', '12', '029', '006', 'Bandung', '40271', 1, 0, 'D2', NULL, '081224499786', 'Ibu Mimi', '2020-01-25', 1, NULL, NULL, 45, '3000000.00', 1, 1, '51644842'),
(8, 'Toko Surya Agung', 'Jalan H. Ibrahim Adjie (Bandung Trade Mall)', '47A', '005', '011', 'Bandung', '40283', 1, 0, 'C1', '', '(022) 7238333', 'Bapak Jajang Aji', '2020-01-29', 1, NULL, NULL, 45, '3000000.00', 1, 1, '88051032'),
(9, 'Toko Dua Saudara Electric', 'Jalan Pungkur', '51', '000', '000', 'Bandung', '40252', 3, 0, '', '', '08122033019', 'Bapak Hendrik', '2020-01-29', 1, NULL, NULL, 45, '3000000.00', 1, 1, '04153061'),
(11, 'Toko Buana Elektrik', 'Jalan Cinangka', '4', '000', '000', 'Bandung', '40616', 1, 0, '', '', '', 'Bapak Darma', '2020-01-29', 1, NULL, NULL, 45, '3000000.00', 1, 1, '59582911'),
(12, 'Toko Central Electronic', 'Jalan Mohammad Toha', '72', '000', '000', 'Bandung', '40243', 1, 0, '', '', '(022) 5225851', 'Ibu Siu Men', '2020-01-29', 1, NULL, NULL, 45, '3000000.00', 1, 1, '58385097'),
(13, 'Toko Kian Sukses', 'Jalan Cikutra', '106A', '000', '000', 'Bandung', '40124', 1, 0, '', '', '081298336288', 'Bapak Firman', '2020-01-29', 1, NULL, NULL, 45, '3000000.00', 1, 1, '26779059'),
(15, 'Toko Utama', 'Jalan Pasar Atas', '076', '000', '000', 'Cimahi', '40525', 2, 0, '000', '', '022-6654795', 'Bapak Sugianto', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '14731462'),
(16, 'Toko Sari Bakti', 'Jalan Babakan Sari I (Kebaktian)', '160', '000', '000', 'Bandung', '40283', 3, 0, '000', '', '082318303322', 'Bapak Jisman', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '35701384'),
(17, 'Toko Surya Indah Rancabolang', 'Jalan Rancabolang', '043', '000', '000', 'Bandung', '40286', 1, 0, '000', '', '081321250208', 'Ibu Dewi', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '96748409'),
(18, 'Toko Bangunan HD', 'Jalan Cingised', '125', '000', '000', 'Bandung', '40293', 1, 0, '000', '', '08156275160', 'Bapak Rudy', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '73398235'),
(19, 'Toko Paranti', 'Jalan Jendral Ahmad Yani', '945', '000', '000', 'Bandung', '40282', 4, 0, '000', '', '085315073966', 'Ibu Lili', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '19400857'),
(21, 'Toko Laksana', 'Jalan Ciroyom', '153', '000', '000', 'Bandung', '40183', 2, 0, '000', '', '08122396777', 'Mr. Tatang', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '86739707'),
(22, 'Toko Nirwana Electronic', 'Jalan Ciroyom', '117', '000', '000', 'Bandung', '40183', 2, 0, '000', '', '08122176094', 'Mr. Suwardi', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '40982336'),
(23, 'Toko Sinar Untung Electrical', 'Jalan Raya Dayeuh Kolot', '295', '000', '000', 'Bandung', '40258', 2, 0, '000', '', '082218456161', 'Mr. Kery', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '14168828'),
(24, 'Toko Depo Listrik', 'Jalan Jendral Ahmad Yani, Plaza IBCC LGF', '008', '000', '000', 'Bandung', '40271', 3, 0, 'D3', '', '022-7238318', 'Mr. Dadang', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '17842175'),
(25, 'Toko Krista Lighting', 'Jalan Jendral Ahmad Yani, Plaza IBCC', '12A', '000', '000', 'Bandung', '40114', 3, 0, 'D1', '', '022-7238369', 'Mr. Yendi', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '21343724'),
(26, 'Toko Prima', 'Jalan Surya Sumantri', '058', '000', '000', 'Bandung', '40164', 4, 0, '000', '', '022-2014967', 'Mrs. Uut', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '31153562'),
(27, 'Toko Sumber Rejeki', 'Jalan Jendral Ahmad Yani', '328', '000', '000', 'Bandung', '40271', 3, 0, '000', '', '081570265893', 'Ibu Sinta', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '70362778'),
(29, 'Toko Bangunan Kurniawan', 'Jalan Boling', '001', '000', '000', 'Bandung', '40293', 1, 0, '000', '', '085101223235', 'Mr. Kurniawan', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '57997527'),
(30, 'Toko Besi Adil', 'Jalan Gatot Subroto', '355', '000', '000', 'Bandung', '40724', 3, 0, '000', '', '08122047066', 'Mr. Julius', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '99961710'),
(31, 'Toko Karunia Sakti', 'Jalan Mohammad Toha', '210', '000', '000', 'Bandung', '40243', 2, 0, '000', '', '087827722212', 'Mrs. Alin', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '79488686'),
(32, 'Toko Aneka Nada', 'Jalan Jendral Ahmad Yani', '180', '000', '000', 'Bandung', '40262', 3, 0, '000', '', '089630011866', 'Mr. Ano', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '85679959'),
(33, 'Toko VIP Elektrik', 'Jalan Pahlawan', '049', '000', '000', 'Bandung', '40122', 4, 0, '000', '', '08122043095', 'Mr. Rudi', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '52951066'),
(34, 'Toko Mitra Elektrik', 'Jalan Raya Cileunyi', '036', '000', '000', 'Bandung', '40622', 1, 0, '000', '', '082129265391', 'Mr. Halifa', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '19707657'),
(35, 'Toko Remaja Teknik', 'Jalan Kiaracondong', '318', '000', '000', 'Bandung', '40275', 3, 0, '000', '', '022-7311813', 'Mrs. Dewi', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '69668982'),
(36, 'Toko Tang Mandiri', 'Jalan Holis', '321-325', '000', '000', 'Bandung', '40212', 2, 0, '000', '', '087779614332', 'Mr. Tikno', '2020-01-31', 1, NULL, NULL, 45, '3000000.00', 1, 1, '54923133'),
(37, 'Toko Bangunan Buana Jaya', 'Komplek Batununggal Indah Jalan Waas', '013', '000', '000', 'Bandung', '40266', 3, 0, '000', '', '087878878708', 'Mr. Tatang', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 1, '93344156'),
(38, 'Toko Bangunan Kurniawan Jaya', 'Jalan Terusan Cibaduyut', '052', '000', '000', 'Bandung', '40239', 3, 0, '000', '', '022-5409888', 'Mrs. Lili', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 1, '88500323'),
(39, 'Toko Serly Electric', 'Jalan Raya Cijerah', '242', '000', '000', 'Bandung', '40213', 2, 0, '000', '', '085220265002', 'Mr. Yayan', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 1, '72941448'),
(40, 'Toko Bangunan Rahmat Putra', 'Jalan Terusan Jakarta', '272', '000', '000', 'Bandung', '40291', 1, 0, '000', '', '08122128363', 'Mr. Tanto', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 1, '08912912'),
(41, 'Toko Bangunan Cahaya Logam', 'Jalan Babakan Ciparay', '088', '000', '000', 'Bandung', '40223', 2, 0, '000', '', '022-5402609', 'Mrs. Yani', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 1, '06855577'),
(42, 'Toko Sumber Cahaya', 'Jalan Leuwigajah', '43C', '000', '000', 'Cimahi', '40522', 2, 0, '0000', '', '08988321110', 'Mrs. Nova Wiliana', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 1, '21914045'),
(43, 'Toko D&P Electronics', 'Taman Kopo Indah II', '041', '000', '000', 'Bandung', '40218', 2, 0, '000', '', '08126526986', 'Mrs. Susanti', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 1, '64927313'),
(44, 'Toko Bangunan Pusaka Jaya', 'Jalan Gardujati, Jendral Sudirman', '001', '000', '000', 'Bandung', '40181', 2, 0, '000', '', '022-6031756', 'Mrs. Jeni', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 1, '98204352'),
(45, 'Toko Bangunan Raya Timur', 'Jalan Abdul Haris Nasution, Sindanglaya', '156', '000', '000', 'Bandung', '40293', 1, 0, '000', '', '085974113514', 'Mrs. Safiah', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 1, '46723199'),
(46, 'Toko Guna Jaya Teknik', 'Jalan Sukamenak', '123', '000', '000', 'Bandung', '40228', 4, 0, '000', '', '0895802238369', 'Mrs. Yuliah', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 1, '25854166'),
(47, 'Toko Bangunan Sinar Surya', 'Jalan Terusan Pasirkoja', '108', '000', '000', 'Bandung', '40232', 2, 0, '000', '', '022-6018088', 'Mrs. Mely', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 1, '38239857'),
(48, 'Toko Pada Selamat', 'Jalan Raya Dayeuh Kolot', '314', '000', '000', 'Bandung', '40258', 2, 0, '000', '', '08985085885', 'Mr. Selamet', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 1, '64112119'),
(49, 'Toko Bangunan Kopo Indah', 'Jalan Peta', '200', '000', '000', 'Bandung', '40233', 2, 0, '000', '', '022-6036149', 'Mr. Iwan', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 1, '58742819'),
(50, 'Toko Yasa Elektronik', 'Jalan Margacinta', '165', '000', '000', 'Bandung', '40287', 1, 0, '000', '', '082115065506', 'Mr. Jajang', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 1, '06280309'),
(51, 'Toko Fikriy Berkah Elektronik', 'Jalan Raya Jatinangor', '131', '000', '000', 'Bandung', '45363', 1, 0, '000', '', '082219561667', 'Mr. Agung', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 1, '25976047'),
(52, 'Toko AA Electronic Service', 'Jalan Kyai Haji Ahmad Sadili', '194', '000', '000', 'Bandung', '40394', 1, 0, '000', '', '085316116595', 'Mr. Amir', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 1, '06823947'),
(53, 'Toko Kencana Electric', 'Jalan Sultan Agung', '136', '000', '000', 'Pekalongan', '51126', 1, 0, '000', '', '0285-422035', 'Mr. Akiang', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 1, '42876037'),
(54, 'Toko Kurnia Electronic', 'Jalan Raya Batujajar', '268', '000', '000', 'Bandung', '40561', 2, 0, '000', '', '085797993942', 'Mrs. Alda', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 1, '06621118'),
(55, 'Toko Wira Elektrik', 'Jalan Buah Batu', '036', '000', '000', 'Bandung', '40262', 3, 0, '000', '', '08122136088', 'Mr. Budi', '2020-02-01', 1, NULL, NULL, 45, '3000000.00', 1, 1, '02946580'),
(56, 'Toko Bunga Elektrik', 'Jalan Cikondang', '025', '000', '000', 'Bandung', '40133', 4, 0, '000', '', '081320419469', 'Mr. Ayon', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 1, '30546962'),
(57, 'Toko Cahaya Baru Cimuncang', 'Jalan Cimuncang', '037', '000', '000', 'Bandung', '40125', 4, 0, '000', '', '085782724800', 'Mr. Arif', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 1, '81824949'),
(58, 'Toko Sinar Karapitan', 'Jalan Karapitan', '026', '000', '000', 'Bandung', '40261', 3, 0, '000', '', '022-4208474', 'Mr. Yangyang', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 1, '61933227'),
(59, 'Toko Bintang Elektronik', 'Jalan Kebon Bibit, Balubur Town Square', '012', '000', '000', 'Bandung', '40132', 4, 0, '000', '', '022-76665492', 'Mr. Darman', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 1, '16708835'),
(60, 'Toko Anam Elektronik', 'Jalan Kebon Kembang', '003', '000', '000', 'Bandung', '40116', 4, 0, '000', '', '022-4233870', 'Mr. Anam', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 1, '68578865'),
(61, 'Toko Sinar Permata Jaya', 'Jalan Gegerkalong girang', '088', '000', '000', 'Bandung', '40153', 4, 0, '000', '', '081326453213', 'Mr. Andi', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 1, '73691650'),
(62, 'Toko Aneka Niaga', 'Jalan Gegerkalong Tengah', '077', '000', '000', 'Bandung', '40153', 4, 0, '000', '', '022-2010184', 'Mr. Saiful', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 1, '82205398'),
(63, 'Toko Altrik', 'Jalan Sarijadi Raya', '047', '000', '000', 'Bandung', '40151', 4, 0, '000', '', '082320420999', 'Mr. Firman', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 1, '62385719'),
(64, 'Toko Kurnia Elektrik', 'Jalan Gegerkalong Hilir', '165', '000', '000', 'Bandung', '40153', 4, 0, '000', '', '082219152433', 'Mr. Is', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 1, '76437473'),
(65, 'Toko Sinar Logam', 'Jalan Sariasih', '019', '000', '000', 'Bandung', '40151', 4, 0, '006', '', '022-2017598', 'Mr. Fajar', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 1, '72080674'),
(66, 'Toko 8', 'Jalan Baladewa', '008', '000', '000', 'Bandung', '40173', 2, 0, '000', '', '022-6034875', 'Mr. Thomas', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 1, '68114990'),
(67, 'Toko Glory Electric', 'Jalan Komud Supadio', '36A', '000', '000', 'Bandung', '40174', 2, 0, '000', '', '085974901894', 'Mr. Anton', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 1, '37872824'),
(68, 'Toko Lestari', 'Jalan Rajawali Barat', '99A', '000', '000', 'Bandung', '40184', 2, 0, '000', '', '022-6044308', 'Mr. Dedi', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 1, '45508256'),
(69, 'Toko 23', 'Jalan Kebon Kopi', '128', '000', '000', 'Cimahi', '40535', 2, 0, '000', '', '022-6018073', 'Mr. Nanan', '2020-02-03', 1, NULL, NULL, 45, '5000000.00', 1, 1, '78275254'),
(70, 'Toko Abadi', 'Jalan Gegerkalong Hilir', '073', '000', '000', 'Bandung', '40153', 4, 0, '000', '', '022-2010185', 'Mr. Arifin', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 1, '71022859'),
(71, 'Toko Graha Electronic', 'Jalan Melong Asih', '071', '000', '000', 'Bandung', '40213', 2, 0, '000', '', '085722237789', 'Mr. Hendra', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 1, '71798919'),
(72, 'Toko Asih', 'Jalan Melong Asih', '015', '000', '000', 'Cimahi', '40213', 2, 0, '000', '', '022-6016764', '', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 1, '50061887'),
(73, 'Toko Mutiara Jaya', 'Jalan Holis', '330', '000', '000', 'Bandung', '40212', 2, 0, '000', '', '087823231177', 'Mr. Supandi', '2020-02-03', 1, NULL, NULL, 45, '3000000.00', 1, 1, '35474619'),
(74, 'Toko Berdikari', 'Jalan Holis', '328', '000', '000', 'Bandung', '40212', 2, 0, '000', '', '022-6010288', 'Mr. Ayung', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '55445048'),
(75, 'Toko Cipta Mandiri Electronics', 'Jalan Jendral Ahmad Yani, Jaya Plaza lt dasar', '238', '000', '000', 'Bandung', '40262', 3, 0, 'B5', '', '081220066835', 'Mr. Tino', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '39261382'),
(76, 'Toko Sinar Makmur Electronics', 'Jalan Jendral Ahmad Yani, Jaya Plaza lt dasar', '238', '000', '000', 'Bandung', '40262', 3, 0, 'G8', '', '081321450345', 'Mrs. Frenita', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '64461574'),
(77, 'Toko Aneka Electric', 'Jalan Jendral Ahmad Yani, Jaya Plaza lt dasar', '238', '000', '000', 'Bandung', '40271', 3, 0, 'C11-12', '', '022-7214731', 'Mr. Iksan', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '40272992'),
(78, 'Toko Tehnik Aneka Prima', 'Jalan Peta, Ruko Kopo Kencana', '002', '000', '000', 'Bandung', '40233', 2, 0, 'A3', '', '082219197020', 'Mr. Wendy Hauw', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '58906992'),
(80, 'Toko 487', 'Jalan Terusan Gatot Subroto', '487', '000', '000', 'Bandung', '40284', 3, 0, '000', '', '08112143030', 'Mr. Udin', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '88568165'),
(81, 'Toko Agung Jaya', 'Jalan Kebon Jati', '264', '000', '000', 'Bandung', '40182', 2, 0, '000', '', '022-20564092', 'Mrs. Shirly', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '05925620'),
(82, 'Toko Alam Ria', 'Jalan Kopo Sayati', '122-124', '000', '000', 'Bandung', '40228', 3, 0, '000', '', '022-54413432', 'Mr. Mukian', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '10424519'),
(83, 'Toko Alka', 'Jalan Caringin', '002', '000', '000', 'Bandung', '40223', 2, 0, '000', '', '022-5408473', 'Mr. Mila Suryantini', '2020-02-04', 1, NULL, NULL, 0, '3000000.00', 1, 1, '30080751'),
(84, 'Toko Alvina Elektronik', 'Jalan Terusan Gatot Subroto', '487', '000', '000', 'Bandung', '40284', 3, 0, '000', '', '081222556066', 'Mr. Dedi', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '84854085'),
(85, 'Toko Aneka Teknik', 'Jalan Jamika', '121', '000', '000', 'Bandung', '40221', 2, 0, '000', '', '022-6024485', 'Mr. Akwet', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '87577953'),
(86, 'Toko Anugerah', 'Jalan Kopo', '356', '000', '000', 'Bandung', '40233', 2, 0, '000', '', '022-6016845', 'Mr. Acen', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '68149352'),
(87, 'Toko Asa', 'Jalan Lengkong Dalam', '009', '000', '000', 'Bandung', '40263', 3, 0, '000', '', '089641476277', 'Mrs. Herlina', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '24899872'),
(88, 'Toko Atari', 'Jalan Sukajadi', '039', '000', '000', 'Bandung', '40162', 4, 0, '000', '', '022-2036944', 'Mr. Adi', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '61264749'),
(89, 'Toko B-33', 'Jalan Banceuy Gang Cikapundung', '016', '000', '000', 'Bandung', '40111', 4, 0, '000', '', '081903151932', 'Mr. Engkus', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '86707011'),
(90, 'Toko Banciang', 'Jalan Gandawijaya', '149', '000', '000', 'Cimahi', '40524', 2, 0, '000', '', '022-6652162', 'Mr. Erik', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '54638724'),
(91, 'Toko Bandung Raya', 'Jalan Otto Iskandar Dinata', '322', '000', '000', 'Bandung', '40241', 2, 0, '000', '', '022-4231988', 'Mr. Tonny', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '35349884'),
(92, 'Toko Bangunan Hurip Jaya', 'Jalan Cikutra', '129A', '000', '000', 'Bandung', '40124', 4, 0, '000', '', '0818423316', 'Mr. Eko', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '59913679'),
(93, 'Toko Bangunan Key Kurnia Jaya', 'Jalan Manglid, Ruko Kopo Lestari', '016', '000', '000', 'Bandung', '40226', 3, 0, '000', '', '082119739191', 'Mr. Kurnia', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '34345357'),
(94, 'Toko Bangunan Mandiri', 'Jalan Babakan Sari I', '144', '000', '000', 'Bandung', '40283', 3, 0, '000', '', '082221000473', 'Mr. Mamat', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '88280734'),
(95, 'Toko Bangunan Mekar Indah', 'Jalan Terusan Jakarta', '177', '000', '000', 'Bandung', '40291', 1, 0, '000', '', '081285885152', 'Mr. Yudha', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '35787553'),
(96, 'Toko Bangunan Rossa', 'Jalan Mohammad Toha', '189', '000', '000', 'Bandung', '40243', 3, 0, '000', '', '081320003205', 'Mr. Rosa', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '47254592'),
(97, 'Toko Bangunan Sakti', 'Jalan Kopo', '499', '000', '000', 'Bandung', '40235', 2, 0, '000', '', '022-5401421', 'Mr. Michael', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '99476327'),
(98, 'Toko Bangunan Sarifah', 'Jalan Cikutra', '180', '000', '000', 'Bandung', '40124', 4, 0, '000', '', '081222125523', 'Mr. Yosef', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '08246341'),
(99, 'Toko Bangunan Sawargi', 'Jalan Sriwijaya', '20-22', '000', '000', 'Bandung', '40253', 3, 0, '000', '', '022-5229954', 'Mr. Wahid Hasim', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '82188310'),
(100, 'Toko Bangunan Tresnaco VI', 'Jalan Ciwastra', '086', '000', '000', 'Bandung', '40287', 1, 0, '000', '', '022-7562368', 'Mr. Aep', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '36403978'),
(101, 'Toko Baros Elektronik', 'Jalan Baros', '30-32', '000', '000', 'Cimahi', '40521', 2, 0, '000', '', '022-6642155', 'Mr. Hadi Kurniawan', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '18422520'),
(102, 'Toko Bintang Elektrik', 'Jalan Mekar Utama', '010', '000', '000', 'Bandung', '40237', 2, 0, '000', '', '085324106262', 'Mr. Bill', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '56420431'),
(103, 'Toko Cahaya Abadi', 'Jalan ABC, Pasar Cikapundung Gedung CEC lt.1', '017', '000', '000', 'Bandung', '40111', 4, 0, 'EE', '', '022-84460646', 'Mr. Ari', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '96303027'),
(104, 'Toko Cahaya Gemilang', 'Jalan Leuwi Panjang', '059', '000', '000', 'Bandung', '40234', 2, 0, '000', '', '0895807009085', 'Mrs. Paula', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '43333165'),
(105, 'Toko Chiefa Elektronik', 'Jalan Pamekar Raya', '001', '000', '000', 'Bandung', '40292', 1, 0, '000', '', '085220056200', 'Mr. Faizin', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '43114169'),
(106, 'Toko Ciumbuleuit', 'Jalan Ciumbuleuit', '009', '000', '000', 'Bandung', '40131', 4, 0, '000', '', '022-2032701', 'Mrs. Isan', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '55778737'),
(107, 'Toko CN Elektrik', 'Taman Kopo Indah Raya', '184B', '000', '000', 'Bandung', '40228', 3, 0, '000', '', '085100807853', 'Mrs. Michel', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '17233710'),
(108, 'Toko Denvar Elektronik', 'Jalan Raya Ujungberung', '367', '000', '000', 'Bandung', '40614', 1, 0, '000', '', '085323469911', 'Mr. Deden', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '55609139'),
(109, 'Toko Dragon CCTV', 'Jalan Peta, Komplek Bumi Kopo Kencana', '019', '000', '000', 'Bandung', '40233', 2, 0, 'E', '', '08122002178', 'Mr. Fendi', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '82745929'),
(110, 'Toko Dunia Bahan Bangunan Bandung', 'Jalan Raya Derwati', '089', '000', '000', 'Bandung', '40292', 1, 0, '000', '', '081299422379', 'Mr. Aldi', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '86559407'),
(111, 'Toko Dunia Electric', 'Jalan Otto Iskandar Dinata', '319', '000', '000', 'Bandung', '40251', 3, 0, '000', '', '022-4230423', 'Mr. Tedy', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '06580908'),
(112, 'Toko Fortuna Elektronik', 'Jalan Rancabolang Margahyu Raya', '045', '000', '000', 'Bandung', '40292', 1, 0, '000', '', '0817436868', 'Mrs. Ika', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '80759977'),
(113, 'Toko Golden Lite', 'Jalan Banceuy', '100', '000', '000', 'Bandung', '40111', 4, 0, '000', '', '081220016888', 'Mr. Joni', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '29152644'),
(114, 'Toko Bangunan Hadap Jaya', 'Jalan Margasari', '082', '000', '000', 'Bandung', '40287', 1, 0, '000', '', '022-7510948', 'Mrs. Suamiati', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '66270755'),
(115, 'Toko Bangunan Hadap Jaya II', 'Jalan Ciwastra', '169', '000', '000', 'Bandung', '40287', 1, 0, '000', '', '082115009077', 'Mr. David', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '39249455'),
(116, 'Perusahaan Dagang Hasil Jaya', 'Jalan Peta', '210', '000', '000', 'Bandung', '40231', 2, 0, '000', '', '022-6036170', 'Mrs. Sandra', '2020-02-04', 1, NULL, NULL, 45, '100000000.00', 1, 1, '00381476'),
(117, 'Toko Bangunan Hidup Sejahtera', 'Jalan Raya Barat', '785', '000', '000', 'Cimahi', '40526', 2, 0, '000', '', '081221204121', 'Mr. Sarip', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '84919567'),
(118, 'Toko Indo Mitra', 'Jalan Leuwi Panjang', '074', '000', '000', 'Bandung', '40233', 2, 0, '000', '', '081220691333', 'Mr. Chandra', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '87605194'),
(119, 'Toko Intio', 'Jalan Babakan Sari I', '105', '000', '000', 'Bandung', '40283', 3, 0, '000', '', '087815400681', 'Mr. Warto', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '69002776'),
(120, 'Toko Jatiluhur', 'Jalan Gandawijaya', '103', '000', '000', 'Cimahi', '40523', 2, 0, '000', '', '0811220270', 'Mr. Victor', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '47961940'),
(121, 'Toko Jaya Elektrik', 'Jalan Cilengkrang II', '012', '000', '000', 'Bandung', '40615', 1, 0, '000', '', '081313401812', 'Mr. Andi', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '98594144'),
(122, 'Toko Jaya Sakti', 'Jalan ABC, Pasar Cikapundung', '007', '000', '000', 'Bandung', '40111', 4, 0, 'Q', '', '081273037722', 'Mr. Teat', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '59389510'),
(123, 'Toko Jingga Elektronik', 'Jalan Raya Bojongsoang', '086', '000', '000', 'Bandung', '40288', 3, 0, '000', '', '089626491468', 'Mrs. Mita', '2020-02-04', 1, NULL, NULL, 45, '3000000.00', 1, 1, '36621724'),
(124, 'PT Kencana Elektrindo', 'Jalan Batununggal Indah I', '2A', '000', '000', 'Bandung', '40266', 2, 0, '000', '', '082217772889', 'Mr. Natanael', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 1, '91520857'),
(125, 'Toko Lamora Elektrik', 'Jalan Babakan Sari I', '030', '000', '000', 'Bandung', '40283', 3, 0, '000', '', '081809900750', 'Mr. Andre', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 1, '80580303'),
(126, 'Toko Laris Elektrik', 'Jalan Kiaracondong', '192A', '000', '000', 'Bandung', '40283', 3, 0, '000', '', '081220880699', 'Mr. Wili', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 1, '38912490'),
(127, 'Toko MM Elektrik', 'Jalan Soekarno Hatta', '841', '000', '000', 'Bandung', '40293', 1, 0, '000', '', '082121977326', 'Mr. Miming', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 1, '46965304'),
(128, 'Toko Mega Teknik', 'Jalan Jamika', '151B', '000', '000', 'Bandung', '40232', 2, 0, '000', '', '082124452324', 'Mr. Elvado', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 1, '90468472'),
(129, 'Toko Merpati Elektrik', 'Jalan Otto Iskandar Dinata', '339', '000', '000', 'Bandung', '40251', 2, 0, '000', '', '081320663366', 'Mrs. Erline', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 1, '00984247'),
(130, 'Toko Nceng', 'Jalan Bojong Koneng', '123', '000', '000', 'Bandung', '40191', 4, 0, '000', '', '081395112236', 'Mr. Enceng', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 1, '98191337'),
(131, 'Toko Omega Electric', 'Jalan Indramayu', '012', '000', '000', 'Bandung', '40291', 1, 0, '000', '', '081322922888', 'Mrs. Diana', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 1, '53714447'),
(132, 'Toko Panca Mulya', 'Jalan Kopo Sayati', '144', '000', '000', 'Bandung', '40228', 2, 0, '000', '', '022-5420586', 'Mrs. Dede', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 1, '78507690'),
(133, 'Toko Pelita Putra', 'Ruko Gunung Batu', '009', '000', '000', 'Bandung', '40175', 2, 0, '000', '', '0811239777', 'Mr. Sunsun', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 1, '39844658'),
(134, 'Toko Bangunan Pesantren II', 'Jalan Pagarsih', '339', '000', '000', 'Bandung', '40221', 2, 0, '000', '', '022-6040285', 'Mr. Yanto', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 1, '61249526'),
(135, 'Toko Prima Elektrik', 'Jalan ABC Komplek Cikapundung Electronic Center lt.1', '003', '000', '000', 'Bandung', '40111', 4, 0, 'EE', '', '085227160748', 'Mr. Endhi', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 1, '50445892'),
(136, 'Toko Purnama Jaya Electronic', 'Jalan Cibodas Raya', '006', '000', '000', 'Bandung', '40291', 1, 0, '000', '', '081224798744', 'Mr. Nurzaki', '2020-02-07', 1, NULL, NULL, 45, '3000000.00', 1, 1, '69926012'),
(137, 'Toko Echi El', 'Jalan Logam', '7', '000', '000', 'Bandung', '40287', 3, 0, '000', '', '082129554478', 'Bapak Hendar', '2020-03-30', 1, NULL, NULL, 45, '3000000.00', 1, 1, '17759482'),
(261, 'Toko Sinar Agung', 'Jalan Caringin', '258', '000', '000', 'Bandung', '40223', 2, 0, '000', '', '022-6026321', 'Mr. Miming', '2020-01-24', 0, NULL, NULL, 45, '2500000.00', 1, 1, '54444708'),
(262, 'Toko Bangunan Sinar Sekelimus', 'Jalan Soekarno Hatta', '569', '000', '000', 'Bandung', '40275', 1, 0, '000', '', '(022) 7300317', 'Bapak Hendra', '2020-08-14', 1, NULL, NULL, 30, '3000000.00', 1, 1, '04770219'),
(263, 'Toko Bahagia Elektrik', 'Jalan Kopo - Katapang KM 13.6', '', '000', '000', 'Bandung', '', 2, 0, '', '', '085723489618', 'Bapak Sina', '2020-08-19', 1, NULL, NULL, 30, '3000000.00', 1, 1, '04086274'),
(264, 'Toko Atha Elektrik', 'Jalan Ganda Sari', '71', '000', '000', 'Bandung', '', 2, 0, '', '', '083804987086', 'Bapak Arif', '2020-08-19', 1, NULL, NULL, 30, '3000000.00', 1, 1, '18782587');

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
-- Struktur dari tabel `debt_type`
--

CREATE TABLE `debt_type` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `is_operational` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `debt_type`
--

INSERT INTO `debt_type` (`id`, `name`, `description`, `is_operational`) VALUES
(2, 'Service', 'This account is used for service debt.', 1),
(3, 'Service ( Non-operational)', 'This account is used for non-operational service debt.', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `delivery_order`
--

CREATE TABLE `delivery_order` (
  `id` int(255) NOT NULL,
  `sales_order_id` int(255) NOT NULL,
  `code_delivery_order_id` int(255) NOT NULL,
  `quantity` int(255) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `delivery_order`
--

INSERT INTO `delivery_order` (`id`, `sales_order_id`, `code_delivery_order_id`, `quantity`) VALUES
(1, 2, 1, 1),
(2, 1, 2, 2),
(3, 1, 3, 2),
(4, 3, 4, 10),
(5, 4, 4, 1),
(6, 5, 5, 3),
(7, 1, 6, 3),
(8, 6, 7, 3),
(9, 7, 8, 4);

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

--
-- Dumping data untuk tabel `event`
--

INSERT INTO `event` (`id`, `item_id`, `quantity`, `transaction`, `code_event_id`, `price`) VALUES
(3, 1, 1, 'OUT', 2, '0.0000');

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
  `type` int(255) DEFAULT '1'
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
  `residue_value` float(50,4) NOT NULL
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

--
-- Dumping data untuk tabel `good_receipt`
--

INSERT INTO `good_receipt` (`id`, `purchase_order_id`, `quantity`, `code_good_receipt_id`, `billed_price`) VALUES
(1, 1, 300, 1, '268960.0000'),
(2, 2, 100, 1, '3450560.0000'),
(3, 3, 50, 1, '492000.0000'),
(4, 4, 1, 2, '5379200.0000'),
(5, 5, 1, 3, '2689600.0000'),
(6, 6, 2, 4, '314880.0000'),
(7, 7, 2, 5, '364800.0000'),
(8, 7, 1, 6, '364800.0000'),
(9, 6, 1, 7, '314880.0000'),
(10, 4, 1, 8, '5379200.0000'),
(11, 9, 2, 9, '5379200.0000'),
(12, 9, 1, 10, '5379200.0000');

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
  `date` date NOT NULL,
  `information` text NOT NULL,
  `is_done` tinyint(1) DEFAULT '0',
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `taxInvoice` varchar(50) DEFAULT NULL,
  `lastBillingDate` date DEFAULT NULL,
  `nextBillingDate` date DEFAULT NULL,
  `is_billed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `invoice`
--

INSERT INTO `invoice` (`id`, `name`, `value`, `date`, `information`, `is_done`, `is_confirm`, `taxInvoice`, `lastBillingDate`, `nextBillingDate`, `is_billed`) VALUES
(1, 'INV.DSE202008-00010', '2689600.00', '2020-08-19', 'DO-DSE-202008-00010', 0, 1, NULL, NULL, NULL, 0),
(2, 'INV.DSE202008-00021', '537920.00', '2020-08-19', 'DO-DSE-202008-00021', 0, 1, '', NULL, NULL, 0),
(4, 'INV.DSE202008-00040', '2689600.00', '2020-08-19', 'DO-DSE-202008-00040', 0, 0, NULL, NULL, NULL, 0),
(5, 'INV.DSE202008-00031', '537920.00', '2020-08-19', 'DO-DSE-202008-00031', 0, 0, NULL, NULL, NULL, 0),
(6, 'INV.DSE202008-00050', '944640.00', '2020-08-21', 'DO-DSE-202008-00050', 0, 1, NULL, NULL, NULL, 0),
(7, 'INV.DSE202008-00070', '944640.00', '2020-08-21', 'DO-DSE-202008-00070', 0, 0, NULL, NULL, NULL, 0),
(8, 'INV.DSE202008-00080', '23616000.00', '2020-08-22', 'DO-DSE-202008-00080', 0, 1, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `item`
--

CREATE TABLE `item` (
  `id` int(255) NOT NULL,
  `reference` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` int(255) DEFAULT NULL,
  `is_notified_stock` tinyint(1) NOT NULL DEFAULT '0',
  `confidence_level` float(5,2) NOT NULL DEFAULT '90.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `item`
--

INSERT INTO `item` (`id`, `reference`, `name`, `type`, `is_notified_stock`, `confidence_level`) VALUES
(1, 'NYM21_50_EXT', 'Kabel NYM 2 x 1,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 2, 0, 90.00),
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
  `description` text,
  `type` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `other_opponent`
--

INSERT INTO `other_opponent` (`id`, `name`, `description`, `type`) VALUES
(1, 'Cobain aja dulu', 'PT ini biasanya mengerjakan panel panel gitu lah', 3);

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

--
-- Dumping data untuk tabel `payable`
--

INSERT INTO `payable` (`id`, `value`, `bank_id`, `date`, `purchase_id`, `other_purchase_id`) VALUES
(1, '20000000.00', 3, '2020-08-21', 1, NULL);

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

--
-- Dumping data untuk tabel `petty_cash`
--

INSERT INTO `petty_cash` (`id`, `date`, `transaction`, `value`, `information`, `expense_class`, `bank_id`, `created_by`) VALUES
(1, '2020-08-21', 2, '2000000.00', '', NULL, 4, 1),
(2, '2020-08-22', 1, '200000.00', 'Ongkos angkut GTS', NULL, NULL, 1);

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
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
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
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `confirmed_by` int(255) DEFAULT NULL,
  `is_done` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `purchase_invoice`
--

INSERT INTO `purchase_invoice` (`id`, `date`, `tax_document`, `invoice_document`, `created_by`, `is_confirm`, `is_delete`, `confirmed_by`, `is_done`) VALUES
(1, '2020-08-18', '010.003-20.65468687', '010.003-20.65468687', 1, 1, 0, 1, 0);

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
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `confirmed_by` int(255) DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `is_done` tinyint(1) NOT NULL DEFAULT '0',
  `type` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `purchase_invoice_other`
--

INSERT INTO `purchase_invoice_other` (`id`, `date`, `tax_document`, `invoice_document`, `supplier_id`, `other_opponent_id`, `value`, `taxing`, `information`, `created_by`, `is_confirm`, `confirmed_by`, `is_delete`, `is_done`, `type`) VALUES
(1, '2020-08-19', '', 'ASDFASDF', NULL, 1, '30000000.00', 0, 'Kemairn numpang buatkan panel di mereka', 1, 1, 1, 0, 0, 2),
(2, '2020-08-21', '', 'asdf045', 1, NULL, '20000000.00', 0, 'Beli kabel 2 roll tapi gausah dimasukin ke stock ini teh.', 1, 1, 1, 0, 0, 2);

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
  `received` int(255) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `code_purchase_order_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `purchase_order`
--

INSERT INTO `purchase_order` (`id`, `item_id`, `price_list`, `net_price`, `quantity`, `received`, `status`, `code_purchase_order_id`) VALUES
(1, 1, '328000.0000', '268960.0000', 500, 300, 0, 1),
(2, 14, '4208000.0000', '3450560.0000', 200, 100, 0, 1),
(3, 16, '600000.0000', '492000.0000', 100, 50, 0, 1),
(4, 9, '6560000.0000', '5379200.0000', 2, 2, 1, 2),
(5, 4, '3280000.0000', '2689600.0000', 1, 1, 1, 3),
(6, 65, '384000.0000', '314880.0000', 3, 3, 1, 4),
(7, 65, '384000.0000', '364800.0000', 3, 3, 1, 5),
(8, 1, '328000.0000', '268960.0000', 20, 0, 0, 6),
(9, 9, '6560000.0000', '5379200.0000', 5, 3, 0, 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `purchase_return`
--

CREATE TABLE `purchase_return` (
  `id` int(255) NOT NULL,
  `item_id` int(255) NOT NULL,
  `price` decimal(24,4) NOT NULL,
  `quantity` int(255) NOT NULL,
  `received` int(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `code_purchase_return_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `purchase_return`
--

INSERT INTO `purchase_return` (`id`, `item_id`, `price`, `quantity`, `received`, `status`, `code_purchase_return_id`) VALUES
(1, 1, '500000.0000', 20, 0, 0, 4);

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
-- Struktur dari tabel `receivable`
--

CREATE TABLE `receivable` (
  `id` int(255) NOT NULL,
  `bank_id` int(255) NOT NULL,
  `value` decimal(50,2) NOT NULL,
  `date` date NOT NULL,
  `invoice_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `receivable`
--

INSERT INTO `receivable` (`id`, `bank_id`, `value`, `date`, `invoice_id`) VALUES
(1, 2, '23000000.00', '2020-08-22', 8);

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
  `daily` decimal(10,2) NOT NULL,
  `basic` decimal(10,2) NOT NULL,
  `bonus` decimal(10,2) NOT NULL,
  `deduction` decimal(10,2) NOT NULL
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
  `sent` int(255) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `code_sales_order_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sales_order`
--

INSERT INTO `sales_order` (`id`, `price_list_id`, `discount`, `quantity`, `sent`, `status`, `code_sales_order_id`) VALUES
(1, 57, '18.0000', 10, 7, 0, 1),
(2, 8, '18.0000', 1, 1, 1, 2),
(3, 57, '18.0000', 10, 10, 1, 3),
(4, 57, '100.0000', 1, 1, 1, 3),
(5, 69, '18.0000', 3, 3, 1, 4),
(6, 69, '18.0000', 3, 3, 1, 5),
(7, 9, '10.0000', 4, 4, 1, 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales_return`
--

CREATE TABLE `sales_return` (
  `id` int(255) NOT NULL,
  `delivery_order_id` int(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `received` int(255) NOT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT '0',
  `code_sales_return_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sales_return`
--

INSERT INTO `sales_return` (`id`, `delivery_order_id`, `quantity`, `received`, `is_done`, `code_sales_return_id`) VALUES
(1, 1, 1, 1, 1, 1),
(2, 2, 1, 1, 1, 2),
(3, 4, 3, 3, 1, 3),
(4, 6, 3, 3, 1, 4),
(5, 9, 4, 4, 1, 5);

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

--
-- Dumping data untuk tabel `sales_return_received`
--

INSERT INTO `sales_return_received` (`id`, `code_sales_return_received_id`, `sales_return_id`, `quantity`) VALUES
(1, 1, 1, 1),
(2, 2, 2, 1),
(3, 3, 3, 1),
(4, 4, 4, 3),
(5, 5, 5, 2),
(6, 6, 5, 2),
(7, 7, 3, 2),
(8, 8, 3, 1);

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

--
-- Dumping data untuk tabel `stock_in`
--

INSERT INTO `stock_in` (`id`, `item_id`, `quantity`, `residue`, `supplier_id`, `customer_id`, `good_receipt_id`, `sales_return_received_id`, `event_id`, `price`) VALUES
(1, 1, 300, 281, 1, NULL, 1, NULL, NULL, '268960.0000'),
(2, 14, 100, 100, 1, NULL, 2, NULL, NULL, '3450560.0000'),
(3, 16, 50, 50, 1, NULL, 3, NULL, NULL, '492000.0000'),
(4, 9, 1, 0, 1, NULL, 4, NULL, NULL, '5379200.0000'),
(5, 4, 1, 0, 1, NULL, 5, NULL, NULL, '2689600.0000'),
(6, 65, 2, 0, 1, NULL, 6, NULL, NULL, '314880.0000'),
(7, 65, 2, 0, 1, NULL, 7, NULL, NULL, '364800.0000'),
(8, 65, 1, 0, 1, NULL, 8, NULL, NULL, '364800.0000'),
(9, 65, 1, 0, 1, NULL, 9, NULL, NULL, '314880.0000'),
(10, 9, 1, 0, 1, NULL, 10, NULL, NULL, '5379200.0000'),
(11, 9, 2, 0, 1, NULL, 11, NULL, NULL, '0.0000'),
(12, 9, 1, 1, 1, NULL, 12, NULL, NULL, '5379200.0000'),
(14, 9, 2, 2, NULL, 87, NULL, 6, NULL, '2689600.0000'),
(15, 65, 3, 3, NULL, 264, NULL, 4, NULL, '348160.0000'),
(16, 1, 1, 1, NULL, 124, NULL, 8, NULL, '268960.0000');

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

--
-- Dumping data untuk tabel `stock_out`
--

INSERT INTO `stock_out` (`id`, `in_id`, `quantity`, `customer_id`, `supplier_id`, `delivery_order_id`, `event_id`, `purchase_return_id`) VALUES
(1, 5, 1, 116, NULL, 1, NULL, NULL),
(2, 1, 2, 116, NULL, 2, NULL, NULL),
(7, 1, 2, 116, NULL, 3, NULL, NULL),
(8, 1, 1, NULL, NULL, NULL, 3, NULL),
(9, 1, 10, 124, NULL, 4, NULL, NULL),
(10, 1, 1, 124, NULL, 5, NULL, NULL),
(11, 6, 2, 264, NULL, 6, NULL, NULL),
(12, 7, 1, 264, NULL, 6, NULL, NULL),
(13, 1, 3, 116, NULL, 7, NULL, NULL),
(14, 7, 2, 6, NULL, 8, NULL, NULL),
(15, 8, 1, 6, NULL, 8, NULL, NULL),
(16, 9, 0, 6, NULL, 8, NULL, NULL),
(17, 4, 1, 87, NULL, 9, NULL, NULL),
(18, 10, 1, 87, NULL, 9, NULL, NULL),
(19, 11, 2, 87, NULL, 9, NULL, NULL);

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
  `is_black_list` tinyint(1) NOT NULL DEFAULT '0',
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
(1, 'PT Prima Indah Lestari', 'Jalan Kamal Raya', '83', '003', '002', 'Jakarta Barat', '', 0, '000', '11.111.111.1-111.111', '(021) 5550861', 'Ibu Lina', '2020-01-27', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
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
  `image_url` varchar(100) DEFAULT NULL,
  `access_level` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `address`, `bank_account`, `is_active`, `entry_date`, `password`, `email`, `image_url`, `access_level`) VALUES
(1, 'Daniel Tri', 'Jalan Jamuju no. 18, Bandung', '8090175441', 1, '2020-01-01', '27a9dc715a8e1b472ba494313425de62', 'danielrudianto12@gmail.com', NULL, 5),
(2, 'Andrew Bambang Rudianto', 'Jalan Jamuju no. 18, Bandung', '8090175441', 1, '2020-07-08', 'df68de1228db0edd7590b6c89f8dab7e', 'andrewbambang@gmail.com', NULL, 5),
(16, 'Christian Gerald', 'Jalan J', '878684654', 1, '2020-08-24', 'df12f589a16a759053fc605a6c24d32a', 'christiangerald@gmail.com', NULL, 2);

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
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 3, 2),
(8, 3, 1),
(9, 3, 3),
(10, 3, 4),
(11, 3, 5);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `attendance_list`
--
ALTER TABLE `attendance_list`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `attendance_status`
--
ALTER TABLE `attendance_status`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `bank_transaction`
--
ALTER TABLE `bank_transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `bank_transaction_major` (`bank_transaction_major`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `other_id` (`other_id`);

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
  ADD KEY `billed_by` (`billed_by`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indeks untuk tabel `code_billing`
--
ALTER TABLE `code_billing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `confirmed_by` (`confirmed_by`);

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
  ADD KEY `invoice_id` (`invoice_id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `code_sales_order`
--
ALTER TABLE `code_sales_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

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
  ADD KEY `created_by` (`created_by`);

--
-- Indeks untuk tabel `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `area_id` (`area_id`);

--
-- Indeks untuk tabel `customer_area`
--
ALTER TABLE `customer_area`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `purchase_id` (`purchase_id`);

--
-- Indeks untuk tabel `petty_cash`
--
ALTER TABLE `petty_cash`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_id` (`bank_id`);

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
  ADD KEY `purchase_return_id` (`purchase_return_id`);

--
-- Indeks untuk tabel `receivable`
--
ALTER TABLE `receivable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_id` (`bank_id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indeks untuk tabel `salary_benefit`
--
ALTER TABLE `salary_benefit`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `salary_slip`
--
ALTER TABLE `salary_slip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `attendance_list`
--
ALTER TABLE `attendance_list`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `attendance_status`
--
ALTER TABLE `attendance_status`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `bank_transaction`
--
ALTER TABLE `bank_transaction`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `code_event`
--
ALTER TABLE `code_event`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `code_good_receipt`
--
ALTER TABLE `code_good_receipt`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `code_purchase_order`
--
ALTER TABLE `code_purchase_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `code_purchase_return`
--
ALTER TABLE `code_purchase_return`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `code_purchase_return_sent`
--
ALTER TABLE `code_purchase_return_sent`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `code_sales_order`
--
ALTER TABLE `code_sales_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `code_sales_order_close_request`
--
ALTER TABLE `code_sales_order_close_request`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `code_sales_return`
--
ALTER TABLE `code_sales_return`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `code_sales_return_received`
--
ALTER TABLE `code_sales_return_received`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=265;

--
-- AUTO_INCREMENT untuk tabel `customer_area`
--
ALTER TABLE `customer_area`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `debt_type`
--
ALTER TABLE `debt_type`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `delivery_order`
--
ALTER TABLE `delivery_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `department`
--
ALTER TABLE `department`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `event`
--
ALTER TABLE `event`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `good_receipt`
--
ALTER TABLE `good_receipt`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `other_opponent_type`
--
ALTER TABLE `other_opponent_type`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `payable`
--
ALTER TABLE `payable`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `petty_cash`
--
ALTER TABLE `petty_cash`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `purchase_invoice_other`
--
ALTER TABLE `purchase_invoice_other`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `purchase_return`
--
ALTER TABLE `purchase_return`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `purchase_return_sent`
--
ALTER TABLE `purchase_return_sent`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `receivable`
--
ALTER TABLE `receivable`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `salary_benefit`
--
ALTER TABLE `salary_benefit`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `salary_slip`
--
ALTER TABLE `salary_slip`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sales_order`
--
ALTER TABLE `sales_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `sales_return`
--
ALTER TABLE `sales_return`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `sales_return_received`
--
ALTER TABLE `sales_return_received`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `stock_in`
--
ALTER TABLE `stock_in`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `stock_out`
--
ALTER TABLE `stock_out`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `user_authorization`
--
ALTER TABLE `user_authorization`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `bank_transaction`
--
ALTER TABLE `bank_transaction`
  ADD CONSTRAINT `bank_transaction_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `internal_bank_account` (`id`),
  ADD CONSTRAINT `bank_transaction_ibfk_2` FOREIGN KEY (`bank_transaction_major`) REFERENCES `bank_transaction` (`id`),
  ADD CONSTRAINT `bank_transaction_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `bank_transaction_ibfk_4` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`),
  ADD CONSTRAINT `bank_transaction_ibfk_5` FOREIGN KEY (`other_id`) REFERENCES `other_opponent` (`id`);

--
-- Ketidakleluasaan untuk tabel `billing`
--
ALTER TABLE `billing`
  ADD CONSTRAINT `billing_ibfk_1` FOREIGN KEY (`code_billing_id`) REFERENCES `code_billing` (`id`),
  ADD CONSTRAINT `billing_ibfk_2` FOREIGN KEY (`billed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `billing_ibfk_3` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`);

--
-- Ketidakleluasaan untuk tabel `code_billing`
--
ALTER TABLE `code_billing`
  ADD CONSTRAINT `code_billing_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `code_billing_ibfk_2` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `code_delivery_order`
--
ALTER TABLE `code_delivery_order`
  ADD CONSTRAINT `code_delivery_order_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`);

--
-- Ketidakleluasaan untuk tabel `code_good_receipt`
--
ALTER TABLE `code_good_receipt`
  ADD CONSTRAINT `code_good_receipt_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `purchase_invoice` (`id`);

--
-- Ketidakleluasaan untuk tabel `code_purchase_return`
--
ALTER TABLE `code_purchase_return`
  ADD CONSTRAINT `code_purchase_return_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`),
  ADD CONSTRAINT `code_purchase_return_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `code_sales_order`
--
ALTER TABLE `code_sales_order`
  ADD CONSTRAINT `code_sales_order_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);

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
  ADD CONSTRAINT `code_sales_return_received_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `customer_area` (`id`);

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
-- Ketidakleluasaan untuk tabel `payable`
--
ALTER TABLE `payable`
  ADD CONSTRAINT `payable_ibfk_1` FOREIGN KEY (`bank_id`) REFERENCES `bank_transaction` (`id`),
  ADD CONSTRAINT `payable_ibfk_2` FOREIGN KEY (`purchase_id`) REFERENCES `purchase_invoice` (`id`),
  ADD CONSTRAINT `payable_ibfk_3` FOREIGN KEY (`purchase_id`) REFERENCES `purchase_invoice_other` (`id`);

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
  ADD CONSTRAINT `purchase_return_sent_ibfk_1` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_return` (`id`);

--
-- Ketidakleluasaan untuk tabel `receivable`
--
ALTER TABLE `receivable`
  ADD CONSTRAINT `receivable_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`),
  ADD CONSTRAINT `receivable_ibfk_2` FOREIGN KEY (`bank_id`) REFERENCES `bank_transaction` (`id`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
