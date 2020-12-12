-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Des 2020 pada 08.18
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
(6, 3, '2020-12-01', '2020-12-01 07:45:09', 2),
(7, 4, '2020-12-01', '2020-12-01 07:45:11', 2),
(8, 5, '2020-12-01', '2020-12-01 07:46:01', 2),
(9, 1, '2020-12-01', '2020-12-01 01:00:00', 2),
(10, 2, '2020-12-01', '2020-12-01 01:00:00', 1),
(11, 1, '2020-12-02', '2020-12-02 03:56:34', 2),
(12, 2, '2020-12-02', '2020-12-02 03:56:36', 1),
(13, 3, '2020-12-02', '2020-12-02 03:56:39', 2),
(14, 4, '2020-12-02', '2020-12-02 03:56:41', 2),
(15, 5, '2020-12-02', '2020-12-02 03:56:45', 2),
(16, 1, '2020-12-03', '2020-12-03 01:11:12', 2),
(17, 2, '2020-12-03', '2020-12-03 01:11:14', 2),
(18, 3, '2020-12-03', '2020-12-03 01:11:16', 2),
(19, 4, '2020-12-03', '2020-12-03 01:11:18', 2),
(20, 5, '2020-12-03', '2020-12-03 01:11:20', 2),
(21, 1, '2020-12-04', '2020-12-04 04:43:43', 2),
(22, 2, '2020-12-04', '2020-12-04 04:43:45', 3),
(23, 3, '2020-12-04', '2020-12-04 04:43:47', 2),
(24, 4, '2020-12-04', '2020-12-04 04:43:49', 2),
(25, 5, '2020-12-04', '2020-12-04 04:43:51', 2),
(26, 1, '2020-12-05', '2020-12-05 03:36:57', 2),
(27, 2, '2020-12-05', '2020-12-05 03:36:59', 3),
(28, 3, '2020-12-05', '2020-12-05 03:37:01', 2),
(29, 4, '2020-12-05', '2020-12-05 03:37:03', 2),
(30, 5, '2020-12-05', '2020-12-05 03:37:05', 2),
(31, 1, '2020-12-07', '2020-12-07 01:12:01', 2),
(32, 2, '2020-12-07', '2020-12-07 01:12:04', 3),
(33, 3, '2020-12-07', '2020-12-07 01:12:05', 2),
(34, 4, '2020-12-07', '2020-12-07 01:12:07', 2),
(35, 5, '2020-12-07', '2020-12-07 01:12:09', 2),
(36, 1, '2020-12-08', '2020-12-08 01:07:22', 2),
(37, 3, '2020-12-08', '2020-12-08 01:07:24', 2),
(38, 4, '2020-12-08', '2020-12-08 01:07:27', 2),
(39, 5, '2020-12-08', '2020-12-08 01:17:36', 2),
(40, 1, '2020-12-10', '2020-12-10 06:24:33', 2),
(41, 3, '2020-12-10', '2020-12-10 06:24:35', 2),
(42, 4, '2020-12-10', '2020-12-10 06:24:36', 2),
(43, 5, '2020-12-10', '2020-12-10 06:24:38', 2),
(44, 1, '2020-12-11', '2020-12-11 01:05:21', 2),
(45, 3, '2020-12-11', '2020-12-11 01:05:23', 2),
(46, 4, '2020-12-11', '2020-12-11 01:05:26', 2),
(47, 5, '2020-12-11', '2020-12-11 01:31:40', 2),
(48, 1, '2020-12-12', '2020-12-12 01:09:55', 2),
(49, 3, '2020-12-12', '2020-12-12 01:09:57', 2),
(50, 4, '2020-12-12', '2020-12-12 01:09:58', 2),
(51, 5, '2020-12-12', '2020-12-12 01:10:00', 2);

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
  `note` text NOT NULL,
  `bookkeeping_date` date DEFAULT NULL
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
  `is_done` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `bank_transaction_major` int(255) DEFAULT NULL,
  `account_id` int(255) NOT NULL,
  `transaction_reference` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `bank_transaction`
--

INSERT INTO `bank_transaction` (`id`, `value`, `date`, `transaction`, `customer_id`, `supplier_id`, `other_id`, `internal_account_id`, `is_done`, `is_delete`, `bank_transaction_major`, `account_id`, `transaction_reference`) VALUES
(3, '250000.00', '2020-11-16', 1, 274, NULL, NULL, NULL, 0, 0, NULL, 1, NULL),
(4, '250000.00', '2020-11-16', 2, 274, NULL, NULL, NULL, 1, 0, NULL, 1, 3),
(5, '500000.00', '2020-11-24', 1, 274, NULL, NULL, NULL, 1, 0, NULL, 1, NULL),
(6, '2000000.00', '2020-11-25', 1, 274, NULL, NULL, NULL, 0, 1, NULL, 1, NULL),
(7, '556800.00', '2020-11-25', 1, 274, NULL, NULL, NULL, 0, 0, 6, 1, NULL),
(8, '1443200.00', '2020-11-25', 1, 274, NULL, NULL, NULL, 1, 0, 6, 1, NULL),
(19, '150000.00', '2020-12-03', 2, NULL, 1, NULL, NULL, 1, 0, NULL, 1, NULL),
(20, '150000.00', '2020-12-03', 1, NULL, 1, NULL, NULL, 1, 0, NULL, 1, 19),
(21, '500000.00', '2020-12-05', 1, 421, NULL, NULL, NULL, 1, 0, NULL, 1, NULL);

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
(6, 'Holiday Allowance', 'Employee\'s benefit regarding holiday\'s allowance. Given on the month that Eid is celebrated.'),
(7, 'Healthcare', 'Employee\'s benefit regarding healthcare');

-- --------------------------------------------------------

--
-- Struktur dari tabel `billing`
--

CREATE TABLE `billing` (
  `id` int(255) NOT NULL,
  `invoice_id` int(255) NOT NULL,
  `result` tinyint(1) NOT NULL DEFAULT '0',
  `note` text NOT NULL,
  `code_billing_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `billing`
--

INSERT INTO `billing` (`id`, `invoice_id`, `result`, `note`, `code_billing_id`) VALUES
(1, 3, 0, '', 1),
(2, 5, 0, '', 2),
(3, 3, 0, '', 2),
(4, 5, 0, '', 3),
(5, 3, 0, '', 3);

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
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `is_reported` tinyint(1) NOT NULL DEFAULT '0',
  `confirmed_by` int(255) DEFAULT NULL,
  `reported_by` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `code_billing`
--

INSERT INTO `code_billing` (`id`, `date`, `name`, `created_by`, `billed_by`, `is_confirm`, `is_delete`, `is_reported`, `confirmed_by`, `reported_by`) VALUES
(1, '2020-12-03', 'CB-2020-00590327', 1, 1, 0, 1, 0, 1, NULL),
(2, '2020-12-05', 'CB-2020-56332311', 1, 1, 1, 0, 1, 1, 1),
(3, '2020-12-08', 'CB-2020-80265098', 1, 1, 1, 0, 1, 1, 1);

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
(1, '2020-11-13', 'DO-DSE-202011-00010', 0, 1, 0, 'CF0C05D9-D546-45AF-A8A0-6474CF7FEDEE', NULL),
(2, '2020-11-13', 'DO-DSE-202011-00020', 1, 0, 1, '39613540-4F8A-467F-BFA0-7D4CC1AFBB60', 1),
(3, '2020-12-02', 'DO-DSE-202012-00010', 1, 0, 1, '7E1D833E-0400-45CC-831A-D2C14D0C1AD4', 3),
(4, '2020-12-05', 'DO-DSE-202012-00020', 1, 0, 1, '1220308D-7730-4439-8E03-02B9A8BC8245', 5),
(5, '2020-12-10', 'DO-DSE-202012-00030', 1, 0, 1, 'A5C0E96C-839A-46B2-BE65-213BFC9A0D32', 6);

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
(1, 4, 'EVT-202011-27742064', 1, '2020-11-13', 1, 1),
(2, 2, 'EVT-202011-54833249', 1, '2020-11-27', 1, 1);

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
(1, 'PI-CK-SL-AADEF', '2020-11-13', 1, 0, 2, '2020-11-13', 1, 1, 'D5750C93-E225-43C7-AF57-BAF225D188CB'),
(2, 'abcdc', '2020-12-05', 1, 0, 3, '2020-12-05', 1, 1, '0491BC51-C010-444D-BCB8-92707D6F0332');

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
  `note` text NOT NULL,
  `payment` int(2) NOT NULL DEFAULT '60'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `code_purchase_order`
--

INSERT INTO `code_purchase_order` (`id`, `date`, `name`, `supplier_id`, `created_by`, `confirmed_by`, `is_closed`, `promo_code`, `dropship_address`, `dropship_city`, `dropship_contact_person`, `dropship_contact`, `taxing`, `date_send_request`, `status`, `guid`, `is_delete`, `is_confirm`, `note`, `payment`) VALUES
(1, '2020-11-13', 'PO.DSE-202011-5474', 1, 1, 1, 0, NULL, 'Jalan Kopo no. 123', 'Bandung', 'Daniel Tri', '+62 85290000241', 1, NULL, 'TOP URGENT', '59A6D7F2-357D-483E-80C4-0DA2E407C6FC', 0, 1, 'Mohon dikirimkan ke alamat tertera.', 60),
(2, '2020-11-27', 'PO.DSE-202011-5631', 1, 1, 1, 0, '', NULL, NULL, NULL, NULL, 1, NULL, 'URGENT', '\r\n<div style=\"border:1px solid #990000;padding-lef', 0, 1, '', 60);

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
(1, 'PRS-202011-04692551', 1, 1, '2020-11-13', 1, 0, 1),
(2, 'PRS-202011-61601699', 1, 1, '2020-11-16', 1, 0, 1);

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

--
-- Dumping data untuk tabel `code_purchase_return_sent`
--

INSERT INTO `code_purchase_return_sent` (`id`, `created_by`, `created_date`, `confirmed_by`, `is_confirm`, `is_delete`, `name`, `date`, `is_done`, `bank_id`) VALUES
(1, 1, '2020-11-13', 1, 0, 1, 'ABGG123', '2020-11-13', 0, NULL),
(2, 1, '2020-11-13', 1, 0, 1, 'ABGG1234', '2020-11-14', 0, NULL),
(3, 1, '2020-11-17', 1, 1, 0, 'PRS-202011-04692551', '2020-11-17', 1, 20);

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_quotation`
--

CREATE TABLE `code_quotation` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `created_by` int(255) NOT NULL,
  `is_confirm` tinyint(4) NOT NULL DEFAULT '0',
  `is_delete` tinyint(4) NOT NULL DEFAULT '0',
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
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `confirmed_by` int(255) DEFAULT NULL,
  `guid` varchar(50) NOT NULL,
  `created_by` int(255) NOT NULL,
  `invoicing_method` tinyint(1) NOT NULL DEFAULT '1',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `code_sales_order`
--

INSERT INTO `code_sales_order` (`id`, `customer_id`, `name`, `date`, `taxing`, `seller`, `is_confirm`, `confirmed_by`, `guid`, `created_by`, `invoicing_method`, `is_delete`, `note`) VALUES
(1, 274, '202011.34217896', '2020-11-13', 0, 2, 1, 1, '4B4A8584-C1AD-4B63-B8A9-BFDF87040E51', 1, 1, 0, ''),
(2, 421, '202011.44657356', '2020-11-23', 0, 2, 1, 1, 'EFA07FA2-AA02-4059-BF28-AF0B673EE34E', 1, 1, 0, ''),
(3, 374, '202011.11516117', '2020-11-23', 0, 2, 1, 1, '55B9164E-8D84-40E5-86FF-E7DB11C4B313', 1, 1, 0, ''),
(4, 136, '202011.96005964', '2020-11-23', 0, 2, 1, 1, '31625F5F-232C-45A9-8C1C-0776CB5CE494', 1, 1, 0, ''),
(5, 5, '202011.01640718', '2020-11-23', 0, 2, 1, 1, 'FD9AE235-E6D5-4246-9C07-1B5157ED65F1', 1, 1, 0, ''),
(6, 285, '202011.33934322', '2020-11-23', 0, NULL, 0, NULL, 'EFFACBBD-B2F2-49A3-B564-2C7563856334', 1, 1, 1, ''),
(7, 285, '202011.55021415', '2020-11-23', 0, 4, 1, 1, '8BC0EC5E-F3F3-4613-962D-5BE2CFA31353', 1, 1, 0, ''),
(8, 334, '202011.55743265', '2020-11-23', 0, 4, 1, 1, 'F01B332A-6DAB-4D2C-8982-058FBB26B173', 1, 1, 0, ''),
(9, 63, '202011.98605994', '2020-11-23', 0, 4, 1, 1, 'E7CD1047-948F-4410-A1E7-8A0DF7A7F713', 1, 1, 0, ''),
(10, 93, '202011.97533502', '2020-11-23', 0, 4, 1, 1, 'DA573A3E-B87D-49C8-B313-CD56CB5D2AA2', 1, 1, 0, ''),
(11, 290, '202011.51383325', '2020-11-25', 0, 3, 1, 1, '54B2D314-EB45-445C-AA0A-196911DE6CF7', 1, 1, 0, 'Bonus voucher 50.000'),
(12, 388, '202011.03854048', '2020-11-25', 0, 2, 1, 1, 'A3BE105E-E02B-4A9F-8F2A-3B4846806233', 1, 1, 0, ''),
(13, 287, '202011.85163073', '2020-11-25', 0, 3, 1, 1, '0F9D04B0-36D4-4575-832E-4D94E66AC1B4', 1, 1, 0, ''),
(14, 347, '202011.23161802', '2020-11-25', 0, 4, 1, 1, 'E46B8FCE-EBF9-4FFD-80DE-973310F518BE', 1, 1, 0, ''),
(15, 347, '202011.12074835', '2020-11-25', 0, NULL, 1, 1, '3C72FA41-5D39-43A7-9ABC-F279A706AB6D', 1, 1, 0, ''),
(16, 427, '202011.62044500', '2020-11-26', 0, 4, 1, 1, 'ABF5E679-B0DB-4C1F-85C4-741CEC9E5B2B', 1, 1, 0, ''),
(17, 392, '202011.31391060', '2020-11-26', 0, 2, 1, 1, '5B18D072-B184-4D00-8F15-B1307DBE0A8B', 1, 1, 0, ''),
(18, 7, '202011.81161453', '2020-11-26', 0, 2, 1, 1, 'AED2699C-1B8E-47E0-AF76-31A1D11D1AD3', 1, 1, 0, ''),
(19, 281, '202011.49969010', '2020-11-26', 0, 4, 1, 1, '87335C1A-619B-4F7D-837A-0CF78E09A532', 1, 1, 0, ''),
(20, 342, '202011.28092911', '2020-11-26', 0, 4, 1, 1, 'B48C5087-EFB9-4D41-A85C-BA18629837ED', 1, 1, 0, ''),
(21, 381, '202011.64737409', '2020-11-26', 0, 2, 0, NULL, 'C4923C98-509F-4181-98E4-2DAE0A8F1E87', 1, 1, 1, ''),
(22, 386, '202011.97173751', '2020-11-26', 0, 2, 0, NULL, 'C2F0BB50-04AE-41E0-90F2-A4FA9BE4C07F', 1, 1, 1, ''),
(23, 5, '202011.83878992', '2020-11-27', 0, 2, 1, 1, '56E3D0AF-DA08-4522-89B2-E431CA24BCD8', 1, 1, 0, ''),
(24, 32, '202011.65634928', '2020-11-27', 0, 2, 1, 1, 'C52E8EAE-016A-4613-91DE-A1F36603646D', 1, 1, 0, ''),
(25, 37, '202011.72064296', '2020-11-27', 0, 2, 1, 1, '2823E254-71E7-4225-A0C1-B80FF107B64C', 1, 1, 0, ''),
(26, 23, '202011.56717478', '2020-11-27', 0, 2, 1, 1, '6B7A8BDA-14BF-4AB9-A214-2D6CFA2470DA', 1, 1, 0, ''),
(27, 287, '202011.17601457', '2020-11-28', 0, 4, 1, 1, 'B21DDAAA-2789-42E9-93F8-4F8CFAA81629', 1, 1, 0, ''),
(28, 325, '202011.15672693', '2020-11-28', 0, 2, 1, 1, '35545544-3962-467B-A3BE-71BCEEE720C5', 1, 1, 0, ''),
(29, 385, '202011.16061705', '2020-11-28', 0, 2, 1, 1, '1B43B771-156C-4156-B54D-45450838DA05', 1, 1, 0, ''),
(30, 114, '202011.68408068', '2020-11-28', 0, 2, 1, 1, '630F8654-B57F-4DA5-B913-12F77B6A7A8A', 1, 1, 0, ''),
(31, 431, '202011.02716190', '2020-11-28', 0, 4, 1, 1, '7D47DA81-BDBD-47AF-9EF4-9FE3D6D15A78', 1, 1, 0, ''),
(32, 19, '202011.73223460', '2020-11-28', 0, 2, 1, 1, '8EBF12E2-C288-41A6-BDA2-B80E2DFF5568', 1, 1, 0, ''),
(33, 385, '202011.45400231', '2020-11-28', 0, 2, 1, 1, 'C9EE4080-8DCF-4F0E-AABF-6B83A65EBA96', 1, 1, 0, ''),
(34, 271, '202011.91396966', '2020-11-30', 0, 3, 1, 1, '518A9701-9290-4C65-B248-87D8F44BAB8B', 1, 1, 0, ''),
(35, 5, '202011.77387826', '2020-11-30', 0, 2, 1, 1, '380D0096-9F8C-42D3-908E-94710CC6DAA7', 1, 1, 0, ''),
(36, 381, '202011.99035410', '2020-11-30', 0, 5, 1, 1, '40FA66FB-4314-4834-B173-742ED438F7BC', 1, 1, 0, ''),
(37, 381, '202012.04323418', '2020-12-02', 0, NULL, 0, NULL, '8420B992-FF59-4D67-9E4D-5EF4D4778205', 1, 1, 1, ''),
(38, 344, '202012.81970056', '2020-12-02', 0, NULL, 0, NULL, 'E944570B-CFDC-4BCE-BE80-E7C1DDB6D67E', 1, 1, 1, ''),
(39, 21, '202012.78263381', '2020-12-02', 0, 4, 1, 1, 'C53EB543-7698-4F55-A431-DFDC505F76CB', 1, 1, 0, ''),
(40, 17, '202012.98261151', '2020-12-02', 0, 5, 1, 1, '49F647B4-9C81-4398-8A70-68E63A64242C', 1, 1, 0, ''),
(41, 381, '202012.55662996', '2020-12-02', 0, 4, 0, NULL, '151488E2-C22E-4B62-A304-ABF306C7412E', 1, 1, 1, ''),
(42, 381, '202012.97227747', '2020-12-02', 0, 5, 1, 1, '8DE15E11-D19B-4B82-9AD4-3760F9073F8A', 1, 1, 0, ''),
(43, 344, '202012.26508264', '2020-12-02', 0, NULL, 1, 1, '1FA51C44-E2AC-442D-A443-95A692081F4B', 1, 1, 0, ''),
(44, 5, '202011.43509320', '2020-11-30', 0, 2, 1, 1, '7271F530-4879-4B9E-967E-23B34787D0AB', 1, 1, 0, ''),
(45, 376, '202011.17257314', '2020-11-30', 0, 5, 1, 1, 'C7778594-60E4-405C-AD22-4B3CAE1799DF', 1, 1, 0, ''),
(46, 18, '202011.94324426', '2020-11-30', 0, 5, 1, 1, 'FCB7D5FE-513E-4906-ADD0-96BC569A11FA', 1, 1, 0, ''),
(47, 276, '202011.68081644', '2020-11-30', 0, 5, 1, 1, 'DA5243FE-95D6-4EED-B353-0056CC7E18E4', 1, 1, 0, ''),
(48, 55, '202012.82077983', '2020-12-03', 0, 2, 1, 1, 'AF9F6B7F-43F5-4608-93C1-DB3D0DFF1236', 1, 1, 0, ''),
(49, 48, '202012.62171330', '2020-12-03', 0, 5, 0, NULL, 'A93CA23E-7DB4-4795-BC23-E21B5466590E', 1, 1, 0, ''),
(50, 113, '202012.73103763', '2020-12-03', 0, 2, 0, NULL, '4AD32562-BC8F-4FC9-86A9-649BB94EC32B', 1, 1, 0, '');

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
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `confirmed_by` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `code_sales_return`
--

INSERT INTO `code_sales_return` (`id`, `name`, `created_by`, `created_date`, `is_confirm`, `is_delete`, `confirmed_by`) VALUES
(8, 'SRS-202011-02630476', 1, '2020-11-14', 0, 1, 1),
(9, 'SRS-202011-79058235', 1, '2020-11-14', 1, 0, 1);

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
(1, 1, '2020-11-14', 0, 1, 1, 'DO-DSE-202011-00020', '2020-11-14', 0, NULL),
(2, 1, '2020-11-16', 0, 1, 1, 'DO-DSE-202011-00020-R-0', '2020-11-16', 0, NULL),
(3, 1, '2020-11-16', 1, 0, 1, 'asdf', '2020-11-16', 1, 4);

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
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `confirmed_by` int(255) DEFAULT NULL,
  `is_reported` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `code_visit_list`
--

INSERT INTO `code_visit_list` (`id`, `date`, `created_by`, `created_date`, `visited_by`, `is_confirm`, `is_delete`, `confirmed_by`, `is_reported`) VALUES
(4, '2020-11-09', 1, '2020-11-09', 2, 1, 0, 1, 1),
(5, '2020-11-10', 1, '2020-11-10', 2, 1, 0, 1, 1),
(6, '2020-11-11', 1, '2020-11-11', 2, 0, 1, 1, 0),
(7, '2020-11-11', 1, '2020-11-11', 2, 1, 0, 1, 1),
(8, '2020-11-11', 1, '2020-11-11', 3, 0, 1, 1, 0),
(9, '2020-11-02', 1, '2020-11-11', 2, 1, 0, 1, 1),
(10, '2020-11-03', 1, '2020-11-11', 2, 0, 1, 1, 0),
(11, '2020-11-03', 1, '2020-11-11', 2, 1, 0, 1, 1),
(12, '2020-11-04', 1, '2020-11-11', 2, 1, 0, 1, 1),
(13, '2020-11-05', 1, '2020-11-11', 2, 1, 0, 1, 1),
(14, '2020-11-12', 1, '2020-11-12', 3, 1, 0, 1, 1),
(15, '2020-11-13', 1, '2020-11-13', 2, 1, 0, 1, 1),
(16, '2020-11-14', 1, '2020-11-13', 2, 1, 0, 1, 1),
(17, '2020-11-13', 1, '2020-11-13', 2, 1, 0, 1, 1),
(18, '2020-11-16', 1, '2020-11-16', 2, 1, 0, 1, 1),
(19, '2020-11-17', 1, '2020-11-17', 2, 1, 0, 1, 1),
(20, '2020-11-18', 1, '2020-11-17', 2, 1, 0, 1, 1),
(21, '2020-11-19', 1, '2020-11-17', 2, 1, 0, 1, 1),
(22, '2020-11-18', 1, '2020-11-18', 4, 1, 0, 1, 1),
(23, '2020-11-19', 1, '2020-11-18', 4, 1, 0, 1, 1),
(24, '2020-11-20', 1, '2020-11-20', 2, 1, 0, 1, 1),
(25, '2020-11-20', 1, '2020-11-20', 4, 1, 0, 1, 1),
(26, '2020-11-21', 1, '2020-11-20', 4, 1, 0, 1, 1),
(27, '2020-11-21', 1, '2020-11-20', 2, 1, 0, 1, 1),
(28, '2020-11-24', 1, '2020-11-21', 2, 0, 1, 1, 0),
(29, '2020-11-24', 1, '2020-11-21', 4, 0, 1, 1, 0),
(30, '2020-11-23', 1, '2020-11-23', 2, 1, 0, 1, 1),
(31, '2020-11-23', 1, '2020-11-23', 4, 1, 0, 1, 1),
(32, '2020-11-25', 1, '2020-11-23', 2, 0, 1, 1, 0),
(33, '2020-11-24', 1, '2020-11-23', 2, 1, 0, 1, 1),
(34, '2020-11-24', 1, '2020-11-23', 4, 1, 0, 1, 1),
(35, '2020-11-25', 1, '2020-11-24', 2, 1, 0, 1, 1),
(36, '2020-11-25', 1, '2020-11-24', 4, 1, 0, 1, 1),
(37, '2020-11-26', 1, '2020-11-24', 2, 1, 0, 1, 1),
(38, '2020-11-26', 1, '2020-11-24', 4, 1, 0, 1, 1),
(39, '2020-11-27', 1, '2020-11-25', 2, 1, 0, 1, 1),
(40, '2020-11-27', 1, '2020-11-25', 4, 1, 0, 1, 1),
(41, '2020-11-26', 1, '2020-11-26', 4, 1, 0, 1, 1),
(42, '2020-11-27', 1, '2020-11-27', 4, 1, 0, 1, 1),
(43, '2020-11-28', 1, '2020-11-28', 2, 1, 0, 1, 1),
(44, '2020-11-28', 1, '2020-11-28', 4, 1, 0, 1, 1),
(45, '2020-11-30', 1, '2020-11-30', 2, 0, 1, 1, 0),
(46, '2020-11-30', 1, '2020-11-30', 5, 1, 0, 1, 1),
(47, '2020-11-30', 1, '2020-11-30', 2, 1, 0, 1, 1),
(48, '2020-11-30', 1, '2020-11-30', 4, 1, 0, 1, 1),
(49, '2020-12-01', 1, '2020-11-30', 2, 0, 1, 1, 0),
(50, '2020-12-01', 1, '2020-11-30', 5, 1, 0, 1, 1),
(51, '2020-12-01', 1, '2020-11-30', 4, 1, 0, 1, 1),
(52, '2020-12-02', 1, '2020-12-01', 4, 1, 0, 1, 1),
(53, '2020-12-02', 1, '2020-12-01', 5, 1, 0, 1, 1),
(54, '2020-12-02', 1, '2020-12-01', 2, 0, 1, 1, 0),
(55, '2020-12-03', 1, '2020-12-02', 4, 1, 0, 1, 1),
(56, '2020-12-03', 1, '2020-12-02', 5, 1, 0, 1, 1),
(57, '2020-12-03', 1, '2020-12-02', 2, 0, 1, 1, 0),
(58, '2020-12-03', 1, '2020-12-03', 2, 0, 1, 1, 0),
(59, '2020-12-02', 1, '2020-12-03', 4, 1, 0, 1, 1),
(60, '2020-12-02', 1, '2020-12-03', 5, 1, 0, 1, 1),
(61, '2020-12-03', 1, '2020-12-03', 2, 1, 0, 1, 1),
(62, '2020-04-12', 1, '2020-12-03', 4, 0, 1, 1, 0),
(63, '2020-04-12', 1, '2020-12-03', 5, 0, 1, 1, 0),
(64, '2020-04-12', 1, '2020-12-04', 4, 0, 1, 1, 0),
(65, '2020-04-12', 1, '2020-12-04', 4, 0, 1, 1, 0),
(66, '2020-04-12', 1, '2020-12-04', 4, 0, 1, 1, 0),
(67, '2020-12-04', 1, '2020-12-04', 4, 0, 1, 1, 0),
(68, '2020-12-03', 1, '2020-12-04', 4, 1, 0, 1, 1),
(69, '2020-12-04', 1, '2020-12-04', 5, 1, 0, 1, 1),
(70, '2020-12-04', 1, '2020-12-04', 4, 1, 0, 1, 1),
(71, '2020-12-05', 1, '2020-12-04', 5, 1, 0, 1, 1),
(72, '2020-12-05', 1, '2020-12-04', 4, 1, 0, 1, 1),
(73, '2020-12-04', 1, '2020-12-05', 4, 1, 0, 1, 1),
(74, '2020-12-04', 1, '2020-12-05', 5, 1, 0, 1, 1),
(75, '2020-12-07', 1, '2020-12-05', 5, 1, 0, 1, 1),
(76, '2020-12-07', 1, '2020-12-05', 4, 0, 1, 1, 0),
(77, '2020-12-07', 1, '2020-12-07', 4, 1, 0, 1, 1),
(78, '2020-12-07', 1, '2020-12-07', 5, 1, 0, 1, 1),
(79, '2020-12-05', 1, '2020-12-07', 4, 1, 0, 1, 1),
(80, '2020-12-05', 1, '2020-12-07', 5, 1, 0, 1, 1),
(81, '2020-12-08', 1, '2020-12-07', 4, 1, 0, 1, 1),
(82, '2020-12-08', 1, '2020-12-07', 5, 1, 0, 1, 1),
(83, '2020-12-10', 3, '2020-12-08', 5, 1, 0, 3, 1),
(84, '2020-12-10', 3, '2020-12-08', 4, 1, 0, 3, 1),
(85, '2020-12-08', 3, '2020-12-10', 4, 1, 0, 3, 1),
(86, '2020-12-08', 3, '2020-12-10', 5, 1, 0, 3, 1),
(87, '2020-12-08', 3, '2020-12-10', 4, 1, 0, 3, 1),
(88, '2020-12-11', 3, '2020-12-10', 4, 1, 0, 1, 1),
(89, '2020-12-11', 3, '2020-12-10', 5, 1, 0, 1, 1),
(90, '2020-12-12', 3, '2020-12-10', 4, 1, 0, 3, 0),
(91, '2020-12-12', 3, '2020-12-10', 5, 1, 0, 3, 0),
(92, '2020-12-10', 1, '2020-12-10', 5, 0, 1, 1, 0),
(93, '2020-12-10', 1, '2020-12-10', 5, 1, 0, 1, 1),
(94, '2020-12-10', 1, '2020-12-10', 4, 1, 0, 1, 1),
(95, '2020-12-11', 1, '2020-12-11', 4, 1, 0, 1, 1),
(96, '2020-12-11', 1, '2020-12-11', 5, 1, 0, 1, 1);

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
  `visiting_frequency` int(3) NOT NULL DEFAULT '28',
  `uid` varchar(50) NOT NULL,
  `password` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `customer`
--

INSERT INTO `customer` (`id`, `name`, `address`, `number`, `rt`, `rw`, `city`, `postal_code`, `area_id`, `is_black_list`, `block`, `npwp`, `phone_number`, `pic_name`, `date_created`, `created_by`, `latitude`, `longitude`, `term_of_payment`, `plafond`, `is_remind`, `visiting_frequency`, `uid`, `password`) VALUES
(1, 'Toko Sumber Lampu', 'Jalan Jendral Ahmad Yani (Plaza IBCC)', '3', '029', '006', 'Kota Bandung', '40271', 1, 0, 'B2', '', '(022) 7233271', 'Bapak Ayung', '2020-01-24', 1, '-6.915196000000000000000000000000', '107.633388000000000000000000000000', 30, '3000000.00', 1, 28, '72297039', NULL),
(5, 'Toko Agni Surya', 'Jalan Jendral Ahmad Yani', '353', '000', '000', 'Kota Bandung', '40121', 1, 0, '', '', '(022) 7273893', 'Ibu Yani', '2020-01-24', 1, '-6.911811000000000000000000000000', '107.637205000000000000000000000000', 45, '100000000.00', 1, 7, '09715857', 'e101df5a9fc03e1344eb9743f69c5127'),
(6, 'Toko Trijaya 2', 'Jalan Cikawao', '56', '001', '001', 'Kota Bandung', '40261', 1, 0, '', NULL, '(022) 4220661', 'Bapak Yohan', '2020-01-24', 1, NULL, NULL, 30, '25000000.00', 1, 28, '45860382', NULL),
(7, 'Toko Utama Lighting', 'Jalan Jendral Ahmad Yani (Plaza IBCC)', '12', '029', '006', 'Kota Bandung', '40271', 1, 0, 'D2', '', '081224499786', 'Ibu Mimi', '2020-01-25', 1, '-6.915538000000000000000000000000', '107.633628000000000000000000000000', 45, '25000000.00', 1, 28, '51644842', NULL),
(8, 'Toko Surya Agung', 'Jalan H. Ibrahim Adjie (Bandung Trade Mall)', '47A', '005', '011', 'Kota Bandung', '40283', 1, 0, 'C1', '', '(022) 7238333', 'Bapak Jajang Aji', '2020-01-29', 1, NULL, NULL, 30, '50000000.00', 1, 28, '88051032', NULL),
(9, 'Toko Dua Saudara Electric', 'Jalan Pungkur', '51', '000', '000', 'Kota Bandung', '40252', 3, 0, '', '', '08122033019', 'Bapak Hendrik', '2020-01-29', 1, NULL, NULL, 30, '25000000.00', 1, 28, '04153061', NULL),
(11, 'Toko Buana Elektrik', 'Jalan Cinangka', '4', '000', '000', 'Kota Bandung', '40616', 1, 0, '000', '', '081214069207', 'Bapak Darma', '2020-01-29', 1, '-6.903633000000000000000000000000', '107.704506000000000000000000000000', 1, '5000000.00', 1, 28, '59582911', NULL),
(12, 'Toko Central Electronic', 'Jalan Mohammad Toha', '72', '000', '000', 'Kota Bandung', '40243', 1, 0, '', '', '(022) 5225851', 'Ibu Siu Men', '2020-01-29', 1, NULL, NULL, 30, '3000000.00', 1, 28, '58385097', NULL),
(13, 'Toko Kian Sukses', 'Jalan Cikutra', '106A', '000', '000', 'Kota Bandung', '40124', 1, 0, '', '', '081298336288', 'Bapak Firman', '2020-01-29', 1, NULL, NULL, 30, '3000000.00', 1, 28, '26779059', NULL),
(15, 'Toko Utama', 'Jalan Pasar Atas', '076', '000', '000', 'Kota Cimahi', '40525', 2, 0, '000', '', '(022) 6654795', 'Bapak Sugianto', '2020-01-31', 1, '-6.869218000000000000000000000000', '107.543131000000000000000000000000', 30, '3000000.00', 1, 28, '14731462', NULL),
(16, 'Toko Sari Bakti', 'Jalan Babakan Sari I (Kebaktian)', '160', '000', '000', 'Kota Bandung', '40283', 3, 0, '000', '', '082318303322', 'Bapak Jisman', '2020-01-31', 1, NULL, NULL, 30, '3000000.00', 1, 28, '35701384', NULL),
(17, 'Toko Surya Indah Rancabolang', 'Jalan Rancabolang', '043', '000', '000', 'Kota Bandung', '40286', 1, 0, '000', '', '081321250208', 'Ibu Dewi', '2020-01-31', 1, NULL, NULL, 30, '3000000.00', 1, 28, '96748409', NULL),
(18, 'Toko Bangunan HD', 'Jalan Cingised', '125', '000', '000', 'Kota Bandung', '40293', 1, 0, '000', '', '08156275160', 'Bapak Rudy', '2020-01-31', 1, NULL, NULL, 30, '3000000.00', 1, 28, '73398235', NULL),
(19, 'Toko Paranti', 'Jalan Jendral Ahmad Yani', '945', '000', '000', 'Kota Bandung', '40282', 1, 0, '000', '', '085315073966', 'Ibu Lili', '2020-01-31', 1, '-6.901962000000000000000000000000', '107.656548000000000000000000000000', 30, '15000000.00', 1, 28, '19400857', NULL),
(21, 'Toko Laksana', 'Jalan Ciroyom', '153', '000', '000', 'Kota Bandung', '40183', 2, 0, '000', '', '08122396777', 'Mr. Tatang', '2020-01-31', 1, NULL, NULL, 30, '3000000.00', 1, 28, '86739707', NULL),
(22, 'Toko Nirwana Electronic', 'Jalan Ciroyom', '117', '000', '000', 'Kota Bandung', '40183', 2, 0, '000', '', '08122176094', 'Mr. Suwardi', '2020-01-31', 1, NULL, NULL, 30, '3000000.00', 1, 28, '40982336', NULL),
(23, 'Toko Sinar Untung Electrical', 'Jalan Raya Dayeuh Kolot', '295', '000', '000', 'Kota Bandung', '40258', 1, 0, '000', '', '082218456161', 'Mr. Kery', '2020-01-31', 1, NULL, NULL, 30, '3000000.00', 1, 28, '14168828', NULL),
(24, 'Toko Depo Listrik', 'Jalan Jendral Ahmad Yani, Plaza IBCC LGF', '008', '000', '000', 'Kota Bandung', '40271', 3, 0, 'D3', '', '022-7238318', 'Bapak Dadang', '2020-01-31', 1, NULL, NULL, 30, '3000000.00', 1, 28, '17842175', NULL),
(25, 'Toko Krista Lighting', 'Jalan Jendral Ahmad Yani, Plaza IBCC', '12A', '000', '000', 'Kota Bandung', '40114', 1, 0, 'D1', '', '022-7238369', 'Mr. Yendi', '2020-01-31', 1, '-6.915703000000000000000000000000', '107.633268000000000000000000000000', 30, '3000000.00', 1, 28, '21343724', NULL),
(26, 'Toko Prima', 'Jalan Surya Sumantri', '058', '000', '000', 'Kota Bandung', '40164', 4, 0, '000', '', '022-2014967', 'Mrs. Uut', '2020-01-31', 1, '-6.887339000000000000000000000000', '107.581518000000000000000000000000', 30, '3000000.00', 1, 28, '31153562', NULL),
(27, 'Toko Sumber Rejeki', 'Jalan Jendral Ahmad Yani', '328', '000', '000', 'Kota Bandung', '40271', 3, 0, '000', '', '081570265893', 'Ibu Sinta', '2020-01-31', 1, NULL, NULL, 30, '3000000.00', 1, 28, '70362778', NULL),
(29, 'Toko Bangunan Kurniawan', 'Jalan Boling', '001', '000', '000', 'Kota Bandung', '40293', 1, 0, '000', '', '085101223235', 'Mr. Kurniawan', '2020-01-31', 1, '-6.921018000000000000000000000000', '107.672111000000000000000000000000', 30, '3000000.00', 1, 28, '57997527', NULL),
(30, 'Toko Besi Adil', 'Jalan Gatot Subroto', '355', '000', '000', 'Kota Bandung', '40724', 3, 0, '000', '', '08122047066', 'Mr. Julius', '2020-01-31', 1, NULL, NULL, 30, '25000000.00', 1, 28, '99961710', NULL),
(31, 'Toko Karunia Sakti', 'Jalan Mohammad Toha', '210', '000', '000', 'Kota Bandung', '40243', 1, 0, '000', '', '087827722212', 'Mrs. Alin', '2020-01-31', 1, NULL, NULL, 30, '3000000.00', 1, 28, '79488686', NULL),
(32, 'Toko Aneka Nada', 'Jalan Jendral Ahmad Yani', '180', '000', '000', 'Kota Bandung', '40262', 1, 0, '000', '', '089630011866', 'Bapak Ano ', '2020-01-31', 1, '-6.919610000000000000000000000000', '107.622939000000000000000000000000', 30, '25000000.00', 1, 28, '85679959', NULL),
(33, 'Toko VIP Elektrik', 'Jalan Pahlawan', '049', '000', '000', 'Kota Bandung', '40122', 4, 0, '000', '', '08122043095', 'Mr. Rudi', '2020-01-31', 1, '-6.899480000000000000000000000000', '107.633923000000000000000000000000', 30, '3000000.00', 1, 28, '52951066', NULL),
(34, 'Toko Mitra Elektrik', 'Jalan Raya Cileunyi', '036', '000', '000', 'Kota Bandung', '40622', 1, 0, '000', '', '082129265391', 'Mr. Halifa', '2020-01-31', 1, '-6.938087000000000000000000000000', '107.756106000000000000000000000000', 30, '3000000.00', 1, 28, '19707657', NULL),
(35, 'Toko Remaja Teknik', 'Jalan Kiaracondong', '318', '000', '000', 'Kota Bandung', '40275', 3, 0, '000', '', '022-7311813', 'Mrs. Dewi', '2020-01-31', 1, NULL, NULL, 30, '3000000.00', 1, 28, '69668982', NULL),
(36, 'Toko Tang Mandiri', 'Jalan Holis', '321-325', '000', '000', 'Kota Bandung', '40212', 2, 0, '000', '', '087779614332', 'Mr. Tikno', '2020-01-31', 1, NULL, NULL, 45, '50000000.00', 1, 28, '54923133', NULL),
(37, 'Toko Bangunan Buana Jaya', 'Komplek Batununggal Indah Jalan Waas', '013', '000', '000', 'Kota Bandung', '40266', 3, 0, '000', '', '087878878708', 'Mr. Tatang', '2020-02-01', 1, NULL, NULL, 30, '3000000.00', 1, 28, '93344156', NULL),
(38, 'Toko Bangunan Kurniawan Jaya', 'Jalan Terusan Cibaduyut', '052', '000', '000', 'Kota Bandung', '40239', 2, 0, '000', '', '022-5409888', 'Mrs. Lili', '2020-02-01', 1, '-6.965372000000000000000000000000', '107.592168000000000000000000000000', 30, '3000000.00', 1, 28, '88500323', NULL),
(39, 'Toko Serly Electric', 'Jalan Raya Cijerah', '242', '000', '000', 'Kota Bandung', '40213', 2, 0, '000', '', '085220265002', 'Bapak Yayan', '2020-02-01', 1, '-6.928620000000000000000000000000', '107.565380000000000000000000000000', 30, '3000000.00', 1, 28, '72941448', NULL),
(40, 'Toko Bangunan Rahmat Putra', 'Jalan Terusan Jakarta', '272', '000', '000', 'Kota Bandung', '40291', 1, 0, '000', '', '08122128363', 'Mr. Tanto', '2020-02-01', 1, NULL, NULL, 30, '25000000.00', 1, 28, '08912912', NULL),
(41, 'Toko Bangunan Cahaya Logam', 'Jalan Babakan Ciparay', '088', '000', '000', 'Kota Bandung', '40223', 2, 0, '000', '', '022-5402609', 'Mrs. Yani', '2020-02-01', 1, NULL, NULL, 30, '3000000.00', 1, 28, '06855577', NULL),
(42, 'Toko Sumber Cahaya', 'Jalan Leuwigajah', '43C', '000', '000', 'Kota Cimahi', '40522', 2, 0, '000', '', '08988321110', 'Ibu Nova Wiliana', '2020-02-01', 1, '-6.897010000000000000000000000000', '107.558139000000000000000000000000', 30, '50000000.00', 1, 28, '21914045', NULL),
(43, 'Toko D&P Electronics', 'Taman Kopo Indah II', '041', '000', '000', 'Kota Bandung', '40218', 2, 0, '000', '', '08126526986', 'Mrs. Susanti', '2020-02-01', 1, NULL, NULL, 30, '3000000.00', 1, 28, '64927313', NULL),
(44, 'Toko Bangunan Pusaka Jaya', 'Jalan Gardujati, Jendral Sudirman', '001', '000', '000', 'Kota Bandung', '40181', 2, 0, '000', '', '022-6031756', 'Mrs. Jeni', '2020-02-01', 1, NULL, NULL, 30, '3000000.00', 1, 28, '98204352', NULL),
(45, 'Toko Bangunan Raya Timur', 'Jalan Abdul Haris Nasution, Sindanglaya', '156', '000', '000', 'Kota Bandung', '40293', 1, 0, '000', '', '085974113514', 'Mrs. Safiah', '2020-02-01', 1, '-6.905224000000000000000000000000', '107.680012000000000000000000000000', 30, '3000000.00', 1, 28, '46723199', NULL),
(46, 'Toko Guna Jaya Teknik', 'Jalan Sukamenak', '123', '000', '000', 'Kota Bandung', '40228', 3, 0, '000', '', '0895802238369', 'Mrs. Yuliah', '2020-02-01', 1, '-6.971893000000000000000000000000', '107.584925000000000000000000000000', 30, '5000000.00', 1, 28, '25854166', NULL),
(47, 'Toko Bangunan Sinar Surya', 'Jalan Terusan Pasirkoja', '108', '000', '000', 'Kota Bandung', '40232', 2, 0, '000', '', '022-6018088', 'Mrs. Mely', '2020-02-01', 1, NULL, NULL, 30, '3000000.00', 1, 28, '38239857', NULL),
(48, 'Toko Pada Selamat', 'Jalan Raya Dayeuh Kolot', '314', '000', '000', 'Kota Bandung', '40258', 1, 0, '000', '', '08985085885', 'Mr. Selamet', '2020-02-01', 1, NULL, NULL, 30, '3000000.00', 1, 28, '64112119', NULL),
(49, 'Toko Bangunan Kopo Indah', 'Jalan Peta', '200', '000', '000', 'Kota Bandung', '40233', 2, 0, '000', '', '022-6036149', 'Mr. Iwan', '2020-02-01', 1, NULL, NULL, 30, '3000000.00', 1, 28, '58742819', NULL),
(50, 'Toko Yasa Elektronik', 'Jalan Margacinta', '165', '000', '000', 'Kota Bandung', '40287', 1, 0, '000', '', '082115065506', 'Mr. Jajang', '2020-02-01', 1, NULL, NULL, 30, '25000000.00', 1, 28, '06280309', NULL),
(51, 'Toko Fikriy Berkah Elektronik', 'Jalan Raya Jatinangor', '131', '000', '000', 'Kota Bandung', '45363', 1, 0, '000', '', '082219561667', 'Mr. Agung', '2020-02-01', 1, NULL, NULL, 30, '3000000.00', 1, 28, '25976047', NULL),
(52, 'Toko AA Electronic Service', 'Jalan Kyai Haji Ahmad Sadili', '194', '000', '000', 'Kota Bandung', '40394', 1, 0, '000', '', '085316116595', 'Mr. Amir', '2020-02-01', 1, '-6.957809000000000000000000000000', '107.779909000000000000000000000000', 30, '3000000.00', 1, 28, '06823947', NULL),
(53, 'Toko Kencana Electric', 'Jalan Sultan Agung', '136', '000', '000', 'Pekalongan', '51126', 1, 0, '000', '', '0285-422035', 'Mr. Akiang', '2020-02-01', 1, NULL, NULL, 30, '3000000.00', 1, 28, '42876037', NULL),
(54, 'Toko Kurnia Electronic', 'Jalan Raya Batujajar', '268', '000', '000', 'Kota Bandung', '40561', 2, 0, '000', '', '085797993942', 'Mrs. Alda', '2020-02-01', 1, '-6.913702000000000000000000000000', '107.498058000000000000000000000000', 30, '3000000.00', 1, 28, '06621118', NULL),
(55, 'Toko Wira Elektrik', 'Jalan Buah Batu', '036', '000', '000', 'Kota Bandung', '40262', 3, 0, '000', '', '08122136088', 'Mr. Budi', '2020-02-01', 1, NULL, NULL, 30, '3000000.00', 1, 28, '02946580', NULL),
(56, 'Toko Bunga Elektrik', 'Jalan Cikondang', '025', '000', '000', 'Kota Bandung', '40133', 1, 0, '000', '', '081320419469', 'Mr. Ayon', '2020-02-03', 1, '-6.890672000000000000000000000000', '107.630231000000000000000000000000', 30, '10000000.00', 1, 28, '30546962', NULL),
(57, 'Toko Cahaya Baru Cimuncang', 'Jalan Cimuncang', '037', '000', '000', 'Kota Bandung', '40125', 1, 0, '000', '', '085782724800', 'Mr. Arif', '2020-02-03', 1, '-6.900284000000000000000000000000', '107.650273000000000000000000000000', 30, '3000000.00', 1, 28, '81824949', NULL),
(58, 'Toko Sinar Karapitan', 'Jalan Karapitan', '026', '000', '000', 'Kota Bandung', '40261', 3, 0, '000', '', '022-4208474', 'Mr. Yangyang', '2020-02-03', 1, NULL, NULL, 30, '5000000.00', 1, 28, '61933227', NULL),
(59, 'Toko Bintang Elektronik', 'Jalan Kebon Bibit, Balubur Town Square', '012', '000', '000', 'Kota Bandung', '40132', 4, 0, '000', '', '022-76665492', 'Mr. Darman', '2020-02-03', 1, '-6.898906000000000000000000000000', '107.608872000000000000000000000000', 30, '3000000.00', 1, 28, '16708835', NULL),
(60, 'Toko Anam Elektronik', 'Jalan Kebon Kembang', '003', '000', '000', 'Kota Bandung', '40116', 4, 0, '000', '', '022-4233870', 'Mr. Anam', '2020-02-03', 1, '-6.899232000000000000000000000000', '107.608807000000000000000000000000', 30, '3000000.00', 1, 28, '68578865', NULL),
(61, 'Toko Permata ', 'Jalan Gegerkalong girang', '088', '000', '000', 'Kota Bandung', '40153', 4, 0, '000', '', '081326453213', 'Mr. Andi', '2020-02-03', 1, '-6.862440000000000000000000000000', '107.586914000000000000000000000000', 30, '3000000.00', 1, 28, '73691650', NULL),
(62, 'Toko Aneka Niaga', 'Jalan Gegerkalong Tengah', '077', '000', '000', 'Kota Bandung', '40153', 4, 0, '000', '', '022-2010184', 'Mr. Saiful', '2020-02-03', 1, NULL, NULL, 30, '3000000.00', 1, 28, '82205398', NULL),
(63, 'Toko Altrik', 'Jalan Sarijadi Raya', '047', '000', '000', 'Kota Bandung', '40151', 4, 0, '000', '', '082320420999', 'Bapakl Nana', '2020-02-03', 1, '-6.873107000000000000000000000000', '107.580274000000000000000000000000', 30, '10000000.00', 1, 28, '62385719', NULL),
(64, 'Toko Kurnia Elektrik', 'Jalan Gegerkalong Hilir', '165', '000', '000', 'Kota Bandung', '40153', 4, 0, '000', '', '082219152433', 'Mr. Is', '2020-02-03', 1, NULL, NULL, 45, '25000000.00', 1, 28, '76437473', NULL),
(65, 'Toko Sinar Logam', 'Jalan Sariasih', '019', '000', '000', 'Kota Bandung', '40151', 4, 0, '006', '', '022-2017598', 'Mr. Fajar', '2020-02-03', 1, NULL, NULL, 30, '3000000.00', 1, 28, '72080674', NULL),
(66, 'Toko 8', 'Jalan Baladewa', '008', '000', '000', 'Kota Bandung', '40173', 2, 0, '000', '', '022-6034875', 'Mr. Thomas', '2020-02-03', 1, NULL, NULL, 30, '3000000.00', 1, 28, '68114990', NULL),
(67, 'Toko Glory Electric', 'Jalan Komud Supadio', '36A', '000', '000', 'Kota Bandung', '40174', 2, 0, '000', '', '085974901894', 'Mr. Anton', '2020-02-03', 1, NULL, NULL, 30, '3000000.00', 1, 28, '37872824', NULL),
(68, 'Toko Lestari', 'Jalan Rajawali Barat', '99A', '000', '000', 'Kota Bandung', '40184', 2, 0, '000', '', '022-6044308', 'Mr. Dedi', '2020-02-03', 1, NULL, NULL, 30, '3000000.00', 1, 28, '45508256', NULL),
(69, 'Toko 23', 'Jalan Kebon Kopi', '128', '000', '000', 'Kota Cimahi', '40535', 2, 0, '000', '', '(022) 6018073', 'Bapak Nanan', '2020-02-03', 1, '-6.910716700000000000000000000000', '107.558981500000000000000000000000', 30, '3000000.00', 1, 28, '78275254', NULL),
(70, 'Toko Abadi', 'Jalan Gegerkalong Hilir', '073', '000', '000', 'Kota Bandung', '40153', 4, 0, '000', '', '022-2010185', 'Mr. Arifin', '2020-02-03', 1, '-6.869429000000000000000000000000', '107.588086000000000000000000000000', 30, '3000000.00', 1, 28, '71022859', NULL),
(71, 'Toko Graha Electronic', 'Jalan Melong Asih', '071', '000', '000', 'Kota Bandung', '40213', 2, 0, '000', '', '085722237789', 'Mr. Hendra', '2020-02-03', 1, '-6.919562000000000000000000000000', '107.565842000000000000000000000000', 30, '5000000.00', 1, 28, '71798919', NULL),
(72, 'Toko Asih', 'Jalan Melong Asih', '015', '000', '000', 'Kota Cimahi', '40213', 2, 0, '000', '', '022-6016764', '', '2020-02-03', 1, '-6.919176000000000000000000000000', '107.564381000000000000000000000000', 30, '25000000.00', 1, 28, '50061887', NULL),
(73, 'Toko Mutiara Jaya', 'Jalan Holis', '330', '000', '000', 'Kota Bandung', '40212', 2, 0, '000', '', '087823231177', 'Mr. Supandi', '2020-02-03', 1, NULL, NULL, 30, '3000000.00', 1, 28, '35474619', NULL),
(74, 'Toko Berdikari', 'Jalan Holis', '328', '000', '000', 'Kota Bandung', '40212', 2, 0, '000', '', '022-6010288', 'Mr. Ayung', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '55445048', NULL),
(75, 'Toko Cipta Mandiri Electronics', 'Jalan Jendral Ahmad Yani, Jaya Plaza lt dasar', '238', '000', '000', 'Kota Bandung', '40262', 3, 0, 'B5', '', '081220066835', 'Mr. Tino', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '39261382', NULL),
(76, 'Toko Sinar Makmur Electronics', 'Jalan Jendral Ahmad Yani, Jaya Plaza lt dasar', '238', '000', '000', 'Kota Bandung', '40262', 3, 0, 'G8', '', '081321450345', 'Mrs. Frenita', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '64461574', NULL),
(77, 'Toko Aneka Electric', 'Jalan Jendral Ahmad Yani, Jaya Plaza lt dasar', '238', '000', '000', 'Kota Bandung', '40271', 1, 0, 'C11-12', '', '022-7214731', 'Mr. Iksan', '2020-02-04', 1, '-6.917740000000000000000000000000', '107.626549000000000000000000000000', 30, '3000000.00', 1, 28, '40272992', NULL),
(78, 'Toko Tehnik Aneka Prima', 'Jalan Peta, Ruko Kopo Kencana', '002', '000', '000', 'Kota Bandung', '40233', 2, 0, 'A3', '', '082219197020', 'Mr. Wendy Hauw', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '58906992', NULL),
(80, 'Toko 487', 'Jalan Terusan Gatot Subroto', '487', '000', '000', 'Kota Bandung', '40284', 1, 0, '000', '', '08112143030', 'Mr. Udin', '2020-02-04', 1, '-6.932445000000000000000000000000', '107.644474000000000000000000000000', 30, '3000000.00', 1, 28, '88568165', NULL),
(81, 'Toko Agung Jaya', 'Jalan Kebon Jati', '264', '000', '000', 'Kota Bandung', '40182', 2, 0, '000', '', '022-20564092', 'Mrs. Shirly', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '05925620', NULL),
(82, 'Toko Alam Ria', 'Jalan Kopo Sayati', '122-124', '000', '000', 'Kota Bandung', '40228', 2, 0, '000', '', '022-54413432', 'Mr. Mukian', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '10424519', NULL),
(83, 'Toko Alka', 'Jalan Caringin', '002', '000', '000', 'Kota Bandung', '40223', 2, 0, '000', '', '022-5408473', 'Mr. Mila Suryantini', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '30080751', NULL),
(84, 'Toko Alvina Elektronik', 'Jalan Terusan Gatot Subroto', '487', '000', '000', 'Kota Bandung', '40284', 3, 0, '000', '', '081222556066', 'Mr. Dedi', '2020-02-04', 1, NULL, NULL, 45, '5000000.00', 1, 28, '84854085', NULL),
(85, 'Toko Aneka Teknik', 'Jalan Jamika', '121', '000', '000', 'Kota Bandung', '40221', 2, 0, '000', '', '(022) 6024485', 'Bapak Akwet', '2020-02-04', 1, '-6.924911000000000000000000000000', '107.585816000000000000000000000000', 45, '50000000.00', 1, 28, '87577953', NULL),
(86, 'Toko Anugerah', 'Jalan Kopo', '356', '000', '000', 'Kota Bandung', '40233', 2, 0, '000', '', '022-6016845', 'Mr. Acen', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '68149352', NULL),
(87, 'Toko Asa', 'Jalan Lengkong Dalam', '009', '000', '000', 'Kota Bandung', '40263', 8, 0, '000', '', '089641476277', 'Mrs. Herlina', '2020-02-04', 1, '-6.923008000000000000000000000000', '107.607891000000000000000000000000', 30, '3000000.00', 1, 28, '24899872', NULL),
(88, 'Toko Atari Electronic', 'Jalan Sukajadi', '039', '000', '000', 'Kota Bandung', '40162', 4, 0, '000', '', '022-2036944', 'Bapak Andi', '2020-02-04', 1, '-6.894245000000000000000000000000', '107.597024000000000000000000000000', 30, '5000000.00', 1, 28, '61264749', NULL),
(89, 'Toko B-33', 'Jalan Banceuy Gang Cikapundung', '016', '000', '000', 'Kota Bandung', '40111', 4, 0, '000', '', '081903151932', 'Mr. Engkus', '2020-02-04', 1, '-6.918156000000000000000000000000', '107.606817000000000000000000000000', 30, '3000000.00', 1, 28, '86707011', NULL),
(90, 'Toko Banciang', 'Jalan Gandawijaya', '149', '000', '000', 'Kota Cimahi', '40524', 2, 0, '000', '', '022-6652162', 'Mr. Erik', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '54638724', NULL),
(91, 'Toko Bandung Raya', 'Jalan Otto Iskandar Dinata', '322', '000', '000', 'Kota Bandung', '40241', 2, 0, '000', '', '022-4231988', 'Mr. Tonny', '2020-02-04', 1, NULL, NULL, 30, '5000000.00', 1, 28, '35349884', NULL),
(92, 'Toko Bangunan Hurip Jaya', 'Jalan Cikutra', '129A', '000', '000', 'Kota Bandung', '40124', 1, 0, '000', '', '0818423316', 'Mr. Eko', '2020-02-04', 1, '-6.898919000000000000000000000000', '107.643365000000000000000000000000', 30, '3000000.00', 1, 28, '59913679', NULL),
(93, 'Toko Bangunan Key Kurnia Jaya', 'Jalan Manglid, Ruko Kopo Lestari', '016', '000', '000', 'Kota Bandung', '40226', 2, 0, '000', '', '082119739191', 'Mr. Kurnia', '2020-02-04', 1, '-6.969041200000000000000000000000', '107.566258100000000000000000000000', 30, '3000000.00', 1, 28, '34345357', NULL),
(94, 'Toko Bangunan Mandiri', 'Jalan Babakan Sari I', '144', '000', '000', 'Kota Bandung', '40283', 1, 0, '000', '', '082221000473', 'Mr. Mamat', '2020-02-04', 1, '-6.924481000000000000000000000000', '107.653901000000000000000000000000', 30, '5000000.00', 1, 28, '88280734', NULL),
(95, 'Toko Bangunan Mekar Indah', 'Jalan Terusan Jakarta', '177', '000', '000', 'Kota Bandung', '40291', 1, 0, '000', '', '081285885152', 'Mr. Yudha', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '35787553', NULL),
(96, 'Toko Bangunan Rosa', 'Jalan Mohammad Toha', '189', '000', '000', 'Kota Bandung', '40243', 1, 0, '000', '', '081320003205', 'Mr. Rosa', '2020-02-04', 1, '-6.944486000000000000000000000000', '107.608321000000000000000000000000', 30, '3000000.00', 1, 28, '47254592', NULL),
(97, 'Toko Bangunan Sakti', 'Jalan Kopo', '499', '000', '000', 'Kota Bandung', '40235', 2, 0, '000', '', '022-5401421', 'Mr. Michael', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '99476327', NULL),
(98, 'Toko Bangunan Sarifah', 'Jalan Cikutra', '180', '000', '000', 'Kota Bandung', '40124', 1, 0, '000', '', '081222125523', 'Mr. Yosef', '2020-02-04', 1, '-6.898947000000000000000000000000', '107.643578000000000000000000000000', 30, '3000000.00', 1, 28, '08246341', NULL),
(99, 'Toko Bangunan Sawargi', 'Jalan Sriwijaya', '20-22', '000', '000', 'Kota Bandung', '40253', 3, 0, '000', '', '022-5229954', 'Mr. Wahid Hasim', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '82188310', NULL),
(100, 'Toko Bangunan Tresnaco VI', 'Jalan Ciwastra', '086', '000', '000', 'Kota Bandung', '40287', 1, 0, '000', '', '022-7562368', 'Mr. Aep', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '36403978', NULL),
(101, 'Toko Baros Elektronik', 'Jalan Baros', '30-32', '000', '000', 'Kota Bandung', '40521', 2, 0, '000', '', '(022) 6642155', 'Bapak Hadi Kurniawan', '2020-02-04', 1, '-6.895754000000000000000000000000', '107.536465000000000000000000000000', 30, '25000000.00', 1, 28, '18422520', NULL),
(102, 'Toko Bintang Elektrik', 'Jalan Mekar Utama', '010', '000', '000', 'Kota Bandung', '40237', 2, 0, '000', '', '085324106262', 'Mr. Bill', '2020-02-04', 1, '-6.954603000000000000000000000000', '107.609093000000000000000000000000', 30, '3000000.00', 1, 28, '56420431', NULL),
(103, 'Toko Cahaya Abadi', 'Jalan ABC, Pasar Cikapundung Gedung CEC lt.1', '017', '000', '000', 'Kota Bandung', '40111', 8, 0, 'EE', '', '022-84460646', 'Mr. Ari', '2020-02-04', 1, '-6.919248000000000000000000000000', '107.608320000000000000000000000000', 30, '3000000.00', 1, 28, '96303027', NULL),
(104, 'Toko Cahaya Gemilang', 'Jalan Leuwi Panjang', '059', '000', '000', 'Kota Bandung', '40234', 2, 0, '000', '', '0895807009085', 'Mrs. Paula', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '43333165', NULL),
(105, 'Toko Chiefa Elektronik', 'Jalan Pamekar Raya', '001', '000', '000', 'Kota Bandung', '40292', 1, 0, '000', '', '085220056200', 'Mr. Faizin', '2020-02-04', 1, '-6.924343000000000000000000000000', '107.700249000000000000000000000000', 30, '3000000.00', 1, 28, '43114169', NULL),
(106, 'Toko Besi Ciumbuleuit', 'Jalan Ciumbuleuit', '009', '000', '000', 'Kota Bandung', '40131', 4, 0, '000', '', '022-2032701', 'Mrs. Isan', '2020-02-04', 1, '-6.884130000000000000000000000000', '107.604550000000000000000000000000', 30, '3000000.00', 1, 28, '55778737', NULL),
(107, 'Toko CN Elektrik', 'Taman Kopo Indah Raya', '184B', '000', '000', 'Kota Bandung', '40228', 3, 0, '000', '', '085100807853', 'Mrs. Michel', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '17233710', NULL),
(108, 'Toko Denvar Elektronik', 'Jalan Raya Ujungberung', '367', '000', '000', 'Kota Bandung', '40614', 1, 0, '000', '', '085323469911', 'Mr. Deden', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '55609139', NULL),
(109, 'Toko Dragon CCTV', 'Jalan Peta, Komplek Bumi Kopo Kencana', '019', '000', '000', 'Kota Bandung', '40233', 2, 0, 'E', '', '08122002178', 'Mr. Fendi', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '82745929', NULL),
(110, 'Toko Dunia Bahan Bangunan Bandung', 'Jalan Raya Derwati', '089', '000', '000', 'Kota Bandung', '40292', 1, 0, '000', '', '081299422379', 'Mr. Aldi', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '86559407', NULL),
(111, 'Toko Dunia Electric', 'Jalan Otto Iskandar Dinata', '319', '000', '000', 'Kota Bandung', '40251', 3, 0, '000', '', '022-4230423', 'Mr. Tedy', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '06580908', NULL),
(112, 'Toko Fortuna Elektronik', 'Jalan Rancabolang Margahyu Raya', '045', '000', '000', 'Kota Bandung', '40292', 1, 0, '000', '', '0817436868', 'Mrs. Ika', '2020-02-04', 1, '-6.945285000000000000000000000000', '107.663300000000000000000000000000', 30, '3000000.00', 1, 28, '80759977', NULL),
(113, 'Toko Golden Lite', 'Jalan Banceuy', '100', '000', '000', 'Kota Bandung', '40111', 8, 0, '000', '', '081220016888', 'Bapak Joni', '2020-02-04', 1, '-6.916289000000000000000000000000', '107.606496000000000000000000000000', 30, '25000000.00', 1, 28, '29152644', NULL),
(114, 'Toko Bangunan Hadap Jaya', 'Jalan Margasari', '082', '000', '000', 'Kota Bandung', '40287', 1, 0, '000', '', '022-7510948', 'Mrs. Suamiati', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '66270755', NULL),
(115, 'Toko Bangunan Hadap Jaya II', 'Jalan Ciwastra', '169', '000', '000', 'Kota Bandung', '40287', 1, 0, '000', '', '082115009077', 'Mr. David', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '39249455', NULL),
(116, 'Perusahaan Dagang Hasil Jaya', 'Jalan Peta', '210', '000', '000', 'Kota Bandung', '40231', 8, 0, '000', '', '022-6036170', 'Mrs. Sandra', '2020-02-04', 1, '-6.930959000000000000000000000000', '107.588687000000000000000000000000', 30, '3000000.00', 1, 28, '00381476', NULL),
(117, 'Toko Bangunan Hidup Sejahtera', 'Jalan H. Amir Machmud ', '785', '000', '000', 'Kota Cimahi', '40526', 2, 0, '000', '', '081221204121', 'Bapak Sarip', '2020-02-04', 1, '-6.869055000000000000000000000000', '107.529915000000000000000000000000', 30, '5000000.00', 1, 28, '84919567', NULL),
(118, 'Toko Indo Mitra', 'Jalan Leuwi Panjang', '074', '000', '000', 'Kota Bandung', '40233', 2, 0, '000', '', '081220691333', 'Mr. Chandra', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '87605194', NULL),
(119, 'Toko Intio', 'Jalan Babakan Sari I', '105', '000', '000', 'Kota Bandung', '40283', 3, 0, '000', '', '087815400681', 'Mr. Warto', '2020-02-04', 1, NULL, NULL, 30, '3000000.00', 1, 28, '69002776', NULL),
(120, 'Toko Jatiluhur', 'Jalan Gandawijaya', '103', '000', '000', 'Kota Cimahi', '40523', 2, 0, '000', '', '0811220270', 'Mr. Victor', '2020-02-04', 1, NULL, NULL, 30, '5000000.00', 1, 28, '47961940', NULL),
(121, 'Toko Jaya Elektrik', 'Jalan Cilengkrang II', '012', '000', '000', 'Kota Bandung', '40615', 1, 0, '000', '', '081313401812', 'Mr. Andi', '2020-02-04', 1, '-6.924036000000000000000000000000', '107.711939000000000000000000000000', 30, '5000000.00', 1, 28, '98594144', NULL),
(122, 'Toko Jaya Sakti', 'Jalan ABC, Pasar Cikapundung', '007', '000', '000', 'Kota Bandung', '40111', 4, 0, 'Q', '', '081273037722', 'Mr. Teat', '2020-02-04', 1, '-6.919266000000000000000000000000', '107.608337000000000000000000000000', 30, '5000000.00', 1, 28, '59389510', NULL),
(123, 'Toko Jingga Elektronik', 'Jalan Raya Bojongsoang', '086', '000', '000', 'Kota Bandung', '40288', 3, 0, '000', '', '089626491468', 'Mrs. Mita', '2020-02-04', 1, NULL, NULL, 30, '5000000.00', 1, 28, '36621724', NULL),
(124, 'PT Kencana Elektrindo', 'Jalan Batununggal Indah I', '2A', '000', '000', 'Kota Bandung', '40266', 1, 0, '000', '', '082217772889', 'Mr. Natanael', '2020-02-07', 1, '-6.954304000000000000000000000000', '107.627184000000000000000000000000', 30, '3000000.00', 1, 28, '91520857', NULL),
(125, 'Toko Lamora Elektrik', 'Jalan Babakan Sari I', '030', '000', '000', 'Kota Bandung', '40283', 3, 0, '000', '', '081809900750', 'Mr. Andre', '2020-02-07', 1, NULL, NULL, 30, '5000000.00', 1, 28, '80580303', NULL),
(126, 'Toko Laris Elektrik', 'Jalan Kiaracondong', '192A', '000', '000', 'Kota Bandung', '40283', 3, 0, '000', '', '081220880699', 'Mr. Wili', '2020-02-07', 1, NULL, NULL, 30, '3000000.00', 1, 28, '38912490', NULL),
(127, 'Toko MM Elektrik', 'Jalan Soekarno Hatta', '841', '000', '000', 'Kota Bandung', '40293', 1, 0, '000', '', '082121977326', 'Mr. Miming', '2020-02-07', 1, NULL, NULL, 30, '75000000.00', 1, 28, '46965304', NULL),
(128, 'Toko Mega Teknik', 'Jalan Jamika', '151B', '000', '000', 'Kota Bandung', '40232', 2, 0, '000', '', '082124452324', 'Mr. Elvado', '2020-02-07', 1, NULL, NULL, 30, '25000000.00', 1, 28, '90468472', NULL),
(129, 'Toko Merpati Elektrik', 'Jalan Otto Iskandar Dinata', '339', '000', '000', 'Kota Bandung', '40251', 2, 0, '000', '', '081320663366', 'Mrs. Erline', '2020-02-07', 1, NULL, NULL, 30, '25000000.00', 1, 28, '00984247', NULL),
(130, 'Toko Nceng', 'Jalan Bojong Koneng', '123', '000', '000', 'Kota Bandung', '40191', 4, 0, '000', '', '081395112236', 'Mr. Enceng', '2020-02-07', 1, NULL, NULL, 30, '3000000.00', 1, 28, '98191337', NULL),
(131, 'Toko Omega Electric', 'Jalan Indramayu', '012', '000', '000', 'Kota Bandung', '40291', 1, 0, '000', '', '081322922888', 'Ibu Diana', '2020-02-07', 1, '-6.918294100000000000000000000000', '107.657429600000000000000000000000', 30, '3000000.00', 1, 28, '53714447', NULL),
(132, 'Toko Panca Mulya', 'Jalan Kopo Sayati', '144', '000', '000', 'Kota Bandung', '40228', 2, 0, '000', '', '022-5420586', 'Mrs. Dede', '2020-02-07', 1, NULL, NULL, 30, '3000000.00', 1, 28, '78507690', NULL),
(133, 'Toko Pelita Putra', 'Ruko Gunung Batu, Jalan Gunung Baru', '009', '000', '000', 'Kota Bandung', '40175', 2, 0, '000', '', '0811239777', 'Bapak Sunsun', '2020-02-07', 1, '-6.889779000000000000000000000000', '107.568689000000000000000000000000', 45, '75000000.00', 1, 28, '39844658', 'f79896452e387c185533aa991be99bff'),
(134, 'Toko Bangunan Pesantren II', 'Jalan Pagarsih', '339', '000', '000', 'Kota Bandung', '40221', 2, 0, '000', '', '022-6040285', 'Mr. Yanto', '2020-02-07', 1, NULL, NULL, 30, '3000000.00', 1, 28, '61249526', NULL),
(135, 'Toko Prima Elektrik', 'Jalan ABC Komplek Cikapundung Electronic Center lt.1', '003', '000', '000', 'Kota Bandung', '40111', 8, 0, 'EE', '', '085227160748', 'Mr. Endhi', '2020-02-07', 1, '-6.919251000000000000000000000000', '107.608315000000000000000000000000', 30, '3000000.00', 1, 28, '50445892', NULL),
(136, 'Toko Purnama Jaya Electronic', 'Jalan Cibodas Raya', '006', '000', '000', 'Kota Bandung', '40291', 1, 0, '000', '', '081224798744', 'Mr. Nurzaki', '2020-02-07', 1, '-6.925757000000000000000000000000', '107.665403000000000000000000000000', 30, '3000000.00', 1, 28, '69926012', NULL),
(137, 'Toko Echi El', 'Jalan Logam', '7', '000', '000', 'Kota Bandung', '40287', 3, 0, '000', '', '082129554478', 'Bapak Hendar', '2020-03-30', 1, NULL, NULL, 30, '3000000.00', 1, 28, '17759482', NULL),
(261, 'Toko Sinar Agung', 'Jalan Caringin', '258', '000', '000', 'Kota Bandung', '40223', 2, 0, '000', '', '022-6026321', 'Mr. Miming', '2020-01-24', 1, NULL, NULL, 30, '3000000.00', 1, 28, '54444708', NULL),
(262, 'Toko Bangunan Sinar Sekelimus', 'Jalan Soekarno Hatta', '569', '000', '000', 'Kota Bandung', '40275', 1, 0, '000', '', '(022) 7300317', 'Bapak Hendra', '2020-08-14', 1, NULL, NULL, 30, '3000000.00', 1, 28, '04770219', NULL),
(263, 'Toko Bahagia Elektrik', 'Jalan Kopo - Katapang KM 13.6', '', '000', '000', 'Kota Bandung', '', 2, 0, '', '', '085723489618', 'Bapak Sina', '2020-08-19', 1, '-7.009252000000000000000000000000', '107.550639000000000000000000000000', 30, '3000000.00', 1, 28, '04086274', NULL),
(264, 'Toko Atha Elektrik', 'Jalan Ganda Sari', '71', '000', '000', 'kabupaten Bandung', '40921', 3, 0, '', '', '083804987086', 'Bapak Arif', '2020-08-19', 1, '-7.024487000000000000000000000000', '107.548821000000000000000000000000', 30, '3000000.00', 1, 28, '18782587', NULL),
(265, 'Toko KS Electric', 'Jalan Lettu Sobri (Odeon)', '3 - 5', '000', '000', 'Sukabumi', '43131', 5, 0, '000', '', '(0266) 222217', 'Bapak Halim Sanjaya', '2020-08-31', 1, '-6.825881100000000000000000000000', '107.002591600000000000000000000000', 60, '100000000.00', 1, 28, '09833784', NULL),
(267, 'Toko Bangunan Sumber Rejeki Ciwastra', 'Jalan Ciwastra', '41', '000', '000', 'Kota Bandung', '40287', 1, 0, '000', '', '081356048035', 'Ibu Lily Yanti', '2020-09-01', 1, '-6.961227000000000000000000000000', '107.667188000000000000000000000000', 30, '3000000.00', 1, 28, '53967121', NULL),
(268, 'Toko Sari Dagang Electric', 'Jalan Sindang Kerta', '1', '000', '000', 'Kabupaten Bandung Barat', '40562', 2, 0, '000', '', '083817785736', 'Bapak Wildan', '2020-09-01', 1, '-6.980715000000000000000000000000', '107.429073000000000000000000000000', 30, '15000000.00', 1, 28, '80362157', NULL),
(269, 'Toko Bangunan Sinar Mas', 'Jalan Cililin - Sindang Kerta', '74', '000', '000', 'Kabupaten Bandung Barat', '40562', 2, 0, '000', '', '081221191978', 'Bapak Ujang', '2020-09-01', 1, '-6.959504000000000000000000000000', '107.450271700000000000000000000000', 30, '3000000.00', 1, 28, '43305116', NULL),
(270, 'Yunus Hendra Wijaya', 'Jalan Palangkaraya', '14', '004', '009', 'Kota Bandung', '40291', 1, 0, '000', '', '087825603963', 'Bapak Yunus Hendra Wijaya', '2020-09-02', 1, '-6.872809100000000000000000000000', '107.569453000000000000000000000000', 30, '10000000.00', 1, 28, '92090282', NULL),
(271, 'Toko Tang Electric', 'Jalan Terusan Marga Cinta ', '54', '000', '000', 'Kota Bandung', '40287', 1, 0, '000', NULL, '(022) 7567896', 'Ibu Iva', '2020-10-09', 1, '-6.957193000000000000000000000000', '107.656708000000000000000000000000', 30, '500000000.00', 1, 28, '12345678', NULL),
(272, 'Toko Bandung Electric', 'Komplek Taman Kopo Indah V', '11', '000', '000', 'Kota Bandung', '40218', 2, 0, '000', NULL, '082369766965', 'Bapak Afuk', '2020-10-09', 1, '-6.960807000000000000000000000000', '107.560498000000000000000000000000', 45, '75000000.00', 1, 28, '0896147', NULL),
(273, 'Toko Fuba Elektrik', 'Jalan Mekar Indah', '141', '000', '000', 'Kota Bandung', '40625', 1, 0, '000', NULL, '087824693336', 'Bapak Frieyuda Bierman', '2020-10-09', 1, '-6.943339000000000000000000000000', '107.723918000000000000000000000000', 45, '75000000.00', 1, 28, '78614332', NULL),
(274, 'Toko Acep Elektronik Sukabumi', 'Ruko Pasar Cibadak', '21', '000', '000', 'Sukabumi Regency', '43351', 5, 0, 'A', '', '085722323267', 'Bapak Syaiful', '2020-10-16', 1, '-6.885147000000000000000000000000', '106.778646000000000000000000000000', 30, '10000000.00', 1, 28, '10214307', '6643bdffd462bd197e085a96f975d4bd'),
(275, 'Toko LEF electric', 'Komplek Royal Casablanca Jalan Cipamokolan', '7', '000', '000', 'Kota Bandung', '40292', 1, 0, 'R7', '', '085831375657', 'Bapak Iwan', '2020-10-16', 1, '-6.947213000000000000000000000000', '107.676053000000000000000000000000', 30, '5000000.00', 1, 28, '14669786', NULL),
(276, 'Toko Mulia Elektrik', 'Ruko Madani Regency, Jalan Cijambe', '21', '000', '000', 'Kota Bandung', '40619', 1, 0, '000', '', '081573782560', 'Bapak Sahudi ', '2020-10-16', 1, '-6.909340700000000000000000000000', '107.690662000000000000000000000000', 30, '10000000.00', 1, 28, '52246061', NULL),
(277, 'Toko Omega Elektrik', 'Ruko Segitiga Mas, Jalan Jendral Ahmad Yani ', '221', '000', '000', 'Kota Bandung', '40113', 1, 0, '000', '', '(022) 7202862', 'Ibu Ingeu', '2020-10-16', 1, '-6.917813000000000000000000000000', '107.641955000000000000000000000000', 30, '3000000.00', 1, 28, '39780542', NULL),
(278, 'Toko 29 Elektronik Cianjur', ' Jl. Dr. Muwardi', '29', '000', '000', 'Kabupaten Cianjur', '43215', 9, 0, '000', '', '(0263) 272929', 'Bapak Hiandi ', '2020-10-16', 1, '-6.816567000000000000000000000000', '107.140626000000000000000000000000', 30, '3000000.00', 1, 28, '74928339', NULL),
(279, 'Toko Abadi Prima', 'Jalan Taman Kopo Indah III', 'A2', '000', '000', 'Kota Bandung', '', 2, 0, '000', '', '089694050778', 'Bapak johan', '2020-10-22', 1, '-6.961203000000000000000000000000', '107.558222000000000000000000000000', 30, '25000000.00', 1, 28, '23456323', NULL),
(280, 'Toko AD Elektrik', 'Jalan Raya Rancaekek KM 25', '15', '000', '000', 'Kota Bandung', '', 1, 0, '000', '', '085314314950', 'Bapak Ade Darin', '2020-10-22', 1, '-6.954876000000000000000000000000', '107.771884000000000000000000000000', 30, '5000000.00', 1, 28, '74362404', NULL),
(281, 'Toko Listrik H. Ade', 'Jalan Raya Cihampelas  - Cililin', '129', '000', '000', 'Kabupaten Bandung Barat', '40562', 2, 0, '000', '', '087824698088', 'Bapak Ade', '2020-10-24', 1, '-6.925059000000000000000000000000', '107.479591000000000000000000000000', 30, '25000000.00', 1, 28, '25149089', NULL),
(282, 'Toko Banceuy Elektrik', 'Jalan Pecinan Lama ', '36', '000', '000', 'Kota Bandung', '40111', 8, 0, '000', '', '08592323287', 'Bapak Alex', '2020-10-24', 1, '-6.917269000000000000000000000000', '107.605616000000000000000000000000', 30, '50000000.00', 1, 28, '51701521', NULL),
(283, 'Toko AF Jaya Electronic', 'Jalan Raya Batujajar', '61', '000', '000', 'Kabupaten Bandung Barat', '40561', 2, 0, '000', '', '085721163312', 'Bapak Jejen ', '2020-10-24', 1, '-6.899527000000000000000000000000', '107.502178000000000000000000000000', 45, '10000000.00', 1, 28, '20622516', NULL),
(284, 'PT Nata Buana', 'Jalan Cibadak', '91', '000', '000', 'Kota Bandung', '40241', 8, 0, '000', '', '081214922154', 'Bapak Chandra', '2020-10-24', 1, '-6.921816000000000000000000000000', '107.600904000000000000000000000000', 30, '25000000.00', 1, 28, '09948713', NULL),
(285, 'Toko Terang Jaya', 'Komplek Taman Kopo Indah III ', '116', '000', '000', 'Kabupaten Bandung', '40218', 2, 0, '000', '', '081395223232', 'Bapak Hendra Sofyan', '2020-10-24', 1, '-6.965227000000000000000000000000', '107.554421000000000000000000000000', 30, '75000000.00', 1, 28, '71605690', NULL),
(286, 'Toko Sinar Sejati', 'Jalan Kalipah Apo ', '15A', '000', '000', 'Kota Bandung', '40241', 8, 0, '000', '__.___.___._-___.___', '(022) 4234440', 'Bapak Iyong', '2020-10-24', 1, '-6.923713000000000000000000000000', '107.601105000000000000000000000000', 30, '50000000.00', 1, 28, '87477130', NULL),
(287, 'Toko Sinar Abadi', 'Jalan Cibadak ', '226', '000', '000', 'Kota Bandung', '40241', 8, 0, '000', '', '081385682567', 'Ibu Ena', '2020-10-24', 1, '-6.920899000000000000000000000000', '107.595918000000000000000000000000', 60, '100000000.00', 1, 28, '08504421', NULL),
(288, 'PT Derajat Elektronik', 'Jalan Bojong Koneng Atas Gang Baru', '11C', '000', '000', 'Kota Bandung', '40191', 1, 0, '000', '', '081222060337', 'Bapak Satia ', '2020-10-24', 1, '-7.001432000000000000000000000000', '107.628427000000000000000000000000', 60, '100000000.00', 1, 28, '80151134', NULL),
(289, 'Toko Global Persada Electrical', ' Komplek Taman Kopo Indah V Ruko Summerville', '37', '000', '000', 'Kota Bandung', '40218', 2, 0, '000', '', '087821167879', 'Bapak Hendra', '2020-10-24', 1, '-6.966587000000000000000000000000', '107.549659000000000000000000000000', 30, '100000000.00', 1, 28, '46758691', NULL),
(290, 'Toko Sejahtera Mandiri', 'Jalan Banceuy ', '115', '000', '000', 'Kota Bandung', '40111', 8, 0, '000', '', '(022) 4263523', 'Bapak Bing', '2020-10-24', 1, '-6.915895000000000000000000000000', '107.606462000000000000000000000000', 30, '25000000.00', 1, 28, '35289073', NULL),
(291, 'Toko Sinar Elektrik', 'Jalan Cibiru ', '109', '000', '000', 'Kota Bandung', '40615', 1, 0, '000', '', '08122089025', 'Bapak Nasiun', '2020-10-24', 1, '-6.933167000000000000000000000000', '107.726171000000000000000000000000', 30, '25000000.00', 1, 28, '53530711', NULL),
(292, 'Toko Alisha', 'Komplek Graha Sari Endah, Jalan R.A.A. Wiranata Kusumah', '23-24', '000', '000', 'Kota Bandung', '40375', 1, 0, '000', '', '082114902727', 'Bapak Ade', '2020-10-24', 1, '-7.001364000000000000000000000000', '107.628433000000000000000000000000', 30, '3000000.00', 1, 28, '46433790', NULL),
(293, 'Toko Bangunan Jaya Baru ', 'Jalan Cibaduyut ', '101', '000', '000', 'Kota Bandung', '', 2, 0, '000', '', '089698895743', 'Annisa', '2020-11-04', 1, '-6.968359000000000000000000000000', '107.591467000000000000000000000000', 30, '3000000.00', 1, 28, '33175247', NULL),
(294, 'Toko Lima Listrik', 'Jalan Raya Kopo Sayati', '103A', '000', '000', 'Kota Bandung', '', 2, 0, '000', '', '081919180991', 'Bapak Kelvin', '2020-11-04', 1, '-6.967282000000000000000000000000', '107.575573000000000000000000000000', 30, '3000000.00', 1, 28, '79011336', NULL),
(295, 'Toko Surya Inti Pelita', 'Jalan Jendral Ahmad Yani', '965', '000', '000', 'Kota Bandung', '40282', 1, 0, '000', '', '081312346188', 'Bapak Tommy', '2020-11-04', 1, '-6.901939200000000000000000000000', '107.657301800000000000000000000000', 30, '3000000.00', 1, 28, '57928881', NULL),
(296, 'Toko Sumber Cahaya Kembar', 'Jalan Sadarmanah', '63', '000', '000', 'Kota Bandung', '000', 2, 0, '000', '', '082218841776', 'Bapak Sandi', '2020-11-04', 1, '-6.900666000000000000000000000000', '107.530579000000000000000000000000', 30, '3000000.00', 1, 28, '24508696', NULL),
(297, 'Toko Wajar Elektrik', 'Jalan Taman Holis Indah', '11', '000', '000', 'Kota Bandung', '40214', 3, 0, 'B', '', '(022) 6078882', 'Bapak A. Hermawan', '2020-11-04', 1, '-6.941788000000000000000000000000', '107.563858000000000000000000000000', 30, '3000000.00', 1, 28, '33083000', NULL),
(298, 'Toko Uhintronic Service', 'Jalan Ciwastra', '147', '000', '000', 'Kota Bandung', '40287', 1, 0, 'A7', '', '082218595814', 'Ibu Hema', '2020-11-04', 1, '-6.954218400000000000000000000000', '107.626392600000000000000000000000', 30, '3000000.00', 1, 28, '99797828', NULL),
(299, 'Toko Tunggal Jaya', 'Jalan Sukasari', '33', '001', '012', 'Kabupaten Bandung', '40921', 3, 0, '000', '', '081221917204', 'Bapak Wawan', '2020-11-04', 1, '-6.992941500000000000000000000000', '107.564419500000000000000000000000', 30, '3000000.00', 1, 28, '84788020', NULL),
(300, 'Toko Puri Jaya Sentosa', 'Jalan Terusan Jakarta', '390', '000', '000', 'Kota Bandung', '', 1, 0, '000', '', '0817175340', 'Bapak Burhan', '2020-11-04', 1, '-6.916085000000000000000000000000', '107.667760000000000000000000000000', 30, '3000000.00', 1, 28, '67193016', NULL),
(301, 'Toko Trijaya', 'Jalan Banceuy', '53', '000', '000', 'Kota Bandung', '40111', 8, 0, '000', '', '081222552052', 'Bapak Harlan', '2020-11-04', 1, '-6.917230100000000000000000000000', '107.606196300000000000000000000000', 30, '10000000.00', 1, 28, '42783688', NULL),
(302, 'Toko Bangunan Timur Jaya', 'Jalan Arjuna', '4-6', '000', '000', 'Kota Bandung', '40182', 2, 0, '000', '', '(022) 6011739', 'Bapak Jichao', '2020-11-04', 1, '-6.904819500000000000000000000000', '107.597989500000000000000000000000', 30, '3000000.00', 1, 28, '07746920', NULL),
(303, 'Toko Aditya', 'Jalan Kampung Kudang', '004', '000', '000', 'Kota Bandung', '', 1, 0, '000', '', '085222084763', 'Bapak Ade Hidayat', '2020-11-04', 1, '-6.930030000000000000000000000000', '107.723856000000000000000000000000', 30, '3000000.00', 1, 28, '90160848', NULL),
(304, 'Toko Timor Jaya', 'Jalan Jamika', '50', '000', '000', 'Kota Bandung', '40231', 2, 0, '000', '', '081321916503', 'Bapak Emilie', '2020-11-04', 1, '-6.920704600000000000000000000000', '107.580347400000000000000000000000', 30, '5000000.00', 1, 28, '05519861', NULL),
(305, 'Toko Timbul Jaya Garut', 'Jalan Cimanuk', '323', '000', '000', 'Kabupaten Garut', '44151', 6, 0, '000', '', '087821269948', 'Ibu Euis', '2020-11-04', 1, '-7.131868500000000000000000000000', '107.764474600000000000000000000000', 30, '5000000.00', 1, 28, '21978414', NULL),
(306, 'Toko Safir Biru', 'Jalan Raya A.H Nasution ', '166', '000', '000', 'Kota Bandung', '', 1, 0, '000', '', '081220858182', 'Narton', '2020-11-04', 1, '-6.934438000000000000000000000000', '107.716929000000000000000000000000', 30, '3000000.00', 1, 28, '44626868', NULL),
(307, 'Toko Terang Electric Sukabumi', 'Jalan Zaenal Zakse', '23', '000', '000', 'Kota Sukabumi', '43111', 5, 0, '000', '', '081214958706', 'Bapak Felix', '2020-11-04', 1, '-6.921591100000000000000000000000', '106.931027000000000000000000000000', 30, '25000000.00', 1, 28, '80924319', NULL),
(308, 'Toko Teknindo Electric', 'Jalan Gedebage', '116', '000', '000', 'Kota Bandung', '40295', 1, 0, '000', '', '081221601117', 'Bapak Novi', '2020-11-04', 1, '-6.942465000000000000000000000000', '107.690094000000000000000000000000', 30, '5000000.00', 1, 28, '21367224', NULL),
(309, 'Toko Surya Indah', 'Jalan Mochammad Toha', '204A', '000', '000', 'Kota Bandung', '40243', 2, 0, '000', '', '082118452350', 'Bapak Surya', '2020-11-04', 1, '-6.938038600000000000000000000000', '107.605463700000000000000000000000', 30, '3000000.00', 1, 28, '35278320', NULL),
(310, 'Toko Surya Gasindo', 'Jalan Bojong Soang', '146', '000', '000', 'Kota Bandung', '40287', 1, 0, '000', '', '081321087234', 'Bapak Surya', '2020-11-04', 1, '-6.976343400000000000000000000000', '107.635404900000000000000000000000', 30, '3000000.00', 1, 28, '12064892', NULL),
(311, 'Toko Sunda 95', 'Jalan Sunda', '95', '000', '000', 'Kota Bandung', '40113', 8, 0, '000', '', '08179873777', 'Bapak Hadi', '2020-11-04', 1, '-6.916168400000000000000000000000', '107.616926100000000000000000000000', 30, '5000000.00', 1, 28, '54730815', NULL),
(312, 'Toko Sumber Terang', 'Jalan Taman Kopo Indah 2', '43', '000', '000', 'Kabupaten Bandung', '40218', 2, 0, '000', '', '(022) 5423249', 'Bapak Frans', '2020-11-04', 1, '-6.944337700000000000000000000000', '107.562782000000000000000000000000', 30, '5000000.00', 1, 28, '25543837', NULL),
(313, 'Toko Sumber Sugih', 'Ruko Taman Holis, Jalan Taman Holis Indah', '14-15', '000', '000', 'Kota Bandung', '40114', 2, 0, 'B', '', '081903845454', 'Bapak Vincent', '2020-11-04', 1, '-6.932652600000000000000000000000', '107.572374300000000000000000000000', 30, '3000000.00', 1, 28, '08074444', NULL),
(314, 'Toko Sumber Listrik', 'Jalan Raya Kopo', '396', '000', '000', 'Kota Bandung', '40233', 3, 0, '000', '', '(022) 5402504', 'Bapak Erwin', '2020-11-04', 1, '-6.937278800000000000000000000000', '107.582880900000000000000000000000', 30, '3000000.00', 1, 28, '64094878', NULL),
(315, 'Toko Sumber Mas', 'Jalan Cibadak', '49', '000', '000', 'Kota Bandung', '40241', 8, 0, '000', '', '(022) 4239797', 'Bapak Erick', '2020-11-04', 1, '-6.921943500000000000000000000000', '107.602254500000000000000000000000', 30, '10000000.00', 1, 28, '47218411', NULL),
(316, 'Toko Sumber Cahaya Cibiuk', 'Jalan Pasar Cibiuk', '73', '000', '000', 'Kabupaten Bandung', '40243', 1, 0, '000', '', '0', 'Bapak Isep', '2020-11-04', 1, '-6.961363000000000000000000000000', '107.613203000000000000000000000000', 30, '25000000.00', 1, 28, '78272648', NULL),
(317, 'Toko Sumber Cahaya Banjaran', 'Jalan Raya Banjaran', '112C', '000', '000', 'Kabupaten Bandung', '40377', 3, 0, '000', '', '081221591770', 'Bapak Sungsung', '2020-11-04', 1, '-7.013202700000000000000000000000', '107.587021200000000000000000000000', 30, '25000000.00', 1, 28, '26716739', NULL),
(318, 'Toko Sukarajin III', 'Jalan Cigadung Raya Barat', '21A', '000', '000', 'Kota Bandung', '40191', 1, 0, '000', '', '(022) 2514000', 'Bapak Nano', '2020-11-04', 1, '-6.888050800000000000000000000000', '107.620081300000000000000000000000', 30, '3000000.00', 1, 28, '10736542', NULL),
(319, 'Toko Sugema', 'Jalan Jendral H. Amir Machmud', '821', '000', '000', 'Kota Cimahi', '40526', 2, 0, '000', '__.___.___._-___.___', '087828327782', 'Bapak Hj. Ikah Sultikah', '2020-11-04', 1, '-6.868627000000000000000000000000', '107.529360000000000000000000000000', 30, '3000000.00', 1, 28, '73876026', NULL),
(320, 'Toko Subur Jaya Putra', 'Jalan Tanjung Sari', '158', '000', '000', 'Kabupaten Sumedang', '45362', 1, 0, '000', '', '085220255356', 'Bapak Taupik', '2020-11-04', 1, '-6.906833000000000000000000000000', '107.796722000000000000000000000000', 30, '5000000.00', 1, 28, '37184389', NULL),
(321, 'Toko Subur Jaya', 'Jalan Pagarsih', '219A', '000', '000', 'Kota Bandung', '40231', 2, 0, '000', '', '082115556678', 'Bapak Halim', '2020-11-04', 1, '-6.922125000000000000000000000000', '107.587099000000000000000000000000', 30, '3000000.00', 1, 28, '50012157', NULL),
(322, 'Toko Sparta Lighting', 'Jalan Taman Kopo Indah 2', '34', '000', '000', 'Kabupaten Bandung', '40236', 2, 0, '1B', '', '08568093038', 'Bapak R. Rocmat Adiwijaya', '2020-11-04', 1, '-6.954670000000000000000000000000', '107.561899000000000000000000000000', 30, '5000000.00', 1, 28, '51514680', NULL),
(323, 'Toko Situ Elektronika', 'Jalan Situ', '35', '000', '000', 'Kota Bandung', '40211', 2, 0, '000', '', '087821100118', 'Bapak Laurent', '2020-11-04', 1, '-6.921477000000000000000000000000', '107.583791000000000000000000000000', 30, '3000000.00', 1, 28, '89319100', NULL),
(324, 'Toko Sinar Untung', 'Jalan Terusan Pasirkoja', '171', '000', '000', 'Kota Bandung', '40232', 2, 0, '000', '', '(022) 6018088', 'Ibu Melly', '2020-11-04', 1, '-6.925884000000000000000000000000', '107.589637000000000000000000000000', 30, '3000000.00', 1, 28, '30749982', NULL);
INSERT INTO `customer` (`id`, `name`, `address`, `number`, `rt`, `rw`, `city`, `postal_code`, `area_id`, `is_black_list`, `block`, `npwp`, `phone_number`, `pic_name`, `date_created`, `created_by`, `latitude`, `longitude`, `term_of_payment`, `plafond`, `is_remind`, `visiting_frequency`, `uid`, `password`) VALUES
(325, 'Toko Sinar Tehnik', 'Jalan Saturnus Utara XVII', '11A', '001', '011', 'Kota Bandung', '40286', 1, 0, 'R75', '', '081326453213', 'Bapak Munirul Hakim ', '2020-11-04', 1, '-6.950542000000000000000000000000', '107.663735000000000000000000000000', 30, '3000000.00', 1, 28, '83257743', NULL),
(327, 'Toko Sinar Putra', 'Jalan Terusan Pasirkoja', '180', '000', '000', 'Kota Bandung', '40232', 2, 0, '000', '', '08122004383', 'Ibu Eveline', '2020-11-04', 1, '-6.926170000000000000000000000000', '107.586682000000000000000000000000', 30, '3000000.00', 1, 28, '50126809', NULL),
(328, 'Toko Sinar Permai', 'Jalan Margacinta', '202', '000', '000', 'Kota Bandung', '40287', 1, 0, '000', '', '0812113537988', 'Bapak Aftien', '2020-11-04', 1, '-6.955211000000000000000000000000', '107.647994000000000000000000000000', 30, '3000000.00', 1, 28, '11840320', NULL),
(329, 'Toko Sinar Padasuka', 'Jalan Padasuka', '33B', '000', '000', 'Kota Bandung', '40192', 1, 0, '000', '', '081322574099', 'Bapak Yudi', '2020-11-04', 1, '-6.899525000000000000000000000000', '107.654239000000000000000000000000', 30, '3000000.00', 1, 28, '26856879', NULL),
(330, 'Toko Sinar Mulia', 'Jalan Pajagalan', '33', '000', '000', 'Kota Bandung', '40242', 2, 0, '000', '', '(022) 4236955', 'Bapak Jonni Yahya', '2020-11-04', 1, '-6.927972000000000000000000000000', '107.600743000000000000000000000000', 30, '3000000.00', 1, 28, '23884415', NULL),
(331, 'Toko Sinar Listrik', 'Jalan Satria Raya', '208C', '000', '000', 'Kota Bandung', '40222', 2, 0, '000', '', '083893684128', 'Bapak Sunarno', '2020-11-04', 1, '-6.943747000000000000000000000000', '107.573545000000000000000000000000', 30, '3000000.00', 1, 28, '34406173', NULL),
(332, 'Toko Sinar Langit', 'Jalan Dr. Setiabudi', '206D', '000', '000', 'Kota Bandung', '40141', 4, 0, '000', '', '089505101346', 'Ibu Cing Cing', '2020-11-04', 1, '-6.868494000000000000000000000000', '107.593908000000000000000000000000', 30, '10000000.00', 1, 28, '82011365', NULL),
(333, 'Toko Sinar Inti Stroom', 'Jalan Sadang', '94', '000', '000', 'Kabupaten Bandung', '40911', 2, 0, '000', '', '081222238765', 'Bapak Andri', '2020-11-04', 1, '-6.962392000000000000000000000000', '107.568934000000000000000000000000', 30, '3000000.00', 1, 28, '20334623', NULL),
(334, 'Toko Sinar Elektronik', 'Jalan Rajawali Timur', '18F', '000', '000', 'Kota Bandung', '40182', 2, 0, '000', '', '(022) 6043203', 'Bapak Iceu', '2020-11-04', 1, '-6.916090000000000000000000000000', '107.589056000000000000000000000000', 45, '5000000.00', 1, 28, '36139369', NULL),
(335, 'Toko Agung Electric Tasikmalaya', 'Jalan KHZ Mustofa', '184', '000', '000', 'Tasikmalaya ', '46124', 7, 0, '000', '', '08122069705', 'Alvin', '2020-11-04', 1, '-7.333945000000000000000000000000', '108.218887000000000000000000000000', 30, '25000000.00', 1, 28, '21098844', NULL),
(336, 'Toko Optima Electric', 'Jalan Raya Bandung-Sumedang Tanjungsari', '316', '000', '000', 'Kota Bandung', '', 1, 0, '000', '', '081223909155', 'Bapak Bonny', '2020-11-04', 1, '-6.905222000000000000000000000000', '107.801528000000000000000000000000', 45, '25000000.00', 1, 28, '73547941', NULL),
(337, 'Toko NL Electric', 'Jalan Abdi Negara', '005', '000', '000', 'Kota Bandung', '', 1, 0, 'F6', '', '089612942812', 'Dhanu', '2020-11-04', 1, '0.000000000000000000000000000000', '0.000000000000000000000000000000', 45, '25000000.00', 1, 28, '02094223', NULL),
(338, 'Toko Sinar Jaya Elektronik', 'Jalan Raya Pembangunan', '12', '006', '002', 'Kabupaten Bandung Barat', '40562', 2, 0, '000', '', '085223174576', 'Bapak Ujang', '2020-11-05', 1, '-6.924782000000000000000000000000', '107.479571000000000000000000000000', 45, '25000000.00', 1, 28, '33620419', NULL),
(339, 'Toko Cahaya Baru', 'Jalan Holis', '326', '000', '000', 'Kota Bandung', '40212', 3, 0, '000', '', '(022) 6019617', 'Bapak Cahyar', '2020-11-05', 1, '-6.934725000000000000000000000000', '107.571125000000000000000000000000', 30, '5000000.00', 1, 28, '36193090', NULL),
(340, 'Toko Kurnia Electric', 'Jalan Gunung Batu', '33C', '000', '000', 'Kota Cimahi', '40514', 2, 0, '000', '', '082219152433', 'Bapak Suherman', '2020-11-05', 1, '-6.893666000000000000000000000000', '107.561661000000000000000000000000', 45, '25000000.00', 1, 28, '62517928', NULL),
(341, 'Toko Alga', 'Jalan Sadarmanah', '129A', '000', '000', 'Kota Cimahi', '40532', 2, 0, '000', '', '08121409676', 'Bapak Donie', '2020-11-05', 1, '-6.899335000000000000000000000000', '107.526677000000000000000000000000', 30, '5000000.00', 1, 28, '79304155', NULL),
(342, 'Toko Cahaya Berkat', 'Jalan Kerkof', '173', '005', '009', 'Kota Cimahi', '40531', 2, 0, '000', '', '081320237873', 'Bapak Hengki', '2020-11-05', 1, '-6.900022000000000000000000000000', '107.514546000000000000000000000000', 30, '3000000.00', 1, 28, '70491338', NULL),
(343, 'Toko Bangunan Persahabatan Pembangunan', 'Jalan Pacinan', '6', '000', '000', 'Kota Cimahi', '40525', 2, 0, '000', '', '(022) 6654500', 'Bapak Yung yung', '2020-11-05', 1, '-6.869883000000000000000000000000', '107.542730000000000000000000000000', 30, '25000000.00', 1, 28, '83570132', NULL),
(344, 'Toko Besi Lumayan ', 'Jalan Ibu Ganirah', '90', '000', '000', 'Kota Cimahi', '40531', 2, 0, '000', '', '085722198754', 'Bapak Didit', '2020-11-05', 1, '-6.890596000000000000000000000000', '107.520245000000000000000000000000', 30, '3000000.00', 1, 28, '38971807', NULL),
(345, 'Toko Mandala Electro', 'Jalan Sukagalih', '47', '000', '000', 'Kota Bandung', '40162', 4, 0, '000', '', '08579478000', 'Bapak Raka', '2020-11-05', 1, '-6.892845000000000000000000000000', '107.595042000000000000000000000000', 30, '25000000.00', 1, 28, '47798024', NULL),
(346, 'Toko Cahaya Glass 2', 'Jalan Sukahaji', '19', '000', '000', 'Kota Bandung', '40152', 4, 0, '000', '', '08122354271', 'Bapak Andri', '2020-11-05', 1, '-6.877416000000000000000000000000', '107.583872000000000000000000000000', 30, '3000000.00', 1, 28, '46734430', NULL),
(347, 'Toko Sederhana Elektronik', 'Jalan Jurang', '2', '000', '000', 'Kota Bandung', '40161', 4, 0, '000', '', '083822515665', 'Bapak Ade', '2020-11-05', 1, '-6.890498000000000000000000000000', '107.599804000000000000000000000000', 30, '3000000.00', 1, 28, '66824973', NULL),
(348, 'Toko Bangunan Putra Sari Dagang', 'Jalan Ciririp - Bangsaraya', '29', '000', '000', 'Kabupaten Bandung Barat', '40562', 2, 0, '000', '', '08522449-6592', 'Bapak Nur', '2020-11-05', 1, '-6.983369000000000000000000000000', '107.436554000000000000000000000000', 30, '10000000.00', 1, 28, '66890724', NULL),
(349, 'PD Darma', 'Jalan Banceuy', '20', '000', '000', 'Kota Bandung', '40111', 8, 0, '000', '', '085794402627', 'Ibu Olivia', '2020-11-05', 1, '-6.918268000000000000000000000000', '107.606714000000000000000000000000', 45, '25000000.00', 1, 28, '84670331', NULL),
(350, 'Toko Sinar Jaya Banceuy', 'Komplek Banceuy Permai, Jl. Banceuy', '24', '000', '000', 'Kota Bandung', '40111', 8, 0, '000', '', '08979786828', 'Bapak Andri', '2020-11-05', 1, '-6.919486000000000000000000000000', '107.606754000000000000000000000000', 45, '25000000.00', 1, 28, '81278995', NULL),
(351, 'Toko Sankyo Jamika', 'Jalan Jamika', '140A', '000', '000', 'Kota Bandung', '40221', 2, 0, '000', '', '081214386436', 'Bapak Dedha', '2020-11-05', 1, '-6.924792000000000000000000000000', '107.585433000000000000000000000000', 30, '5000000.00', 1, 28, '44143402', NULL),
(352, 'Toko Karya Gemilang', 'Jalan Jamika', '127', '000', '000', 'Kota Bandung', '40232', 2, 0, '000', '', '081221462327', 'Bapak Taufik', '2020-11-05', 1, '-6.925188000000000000000000000000', '107.585726000000000000000000000000', 30, '5000000.00', 1, 28, '85333926', NULL),
(353, 'Toko Bina Elektrik Engineering', 'Jalan Cibadak', '190', '000', '000', 'Kota Bandung', '40241', 8, 0, '000', '04.146.770.5-422.000', '(022) 6031805', 'Bapak David Tandei', '2020-11-05', 1, '-6.921197000000000000000000000000', '107.597625000000000000000000000000', 30, '25000000.00', 1, 28, '32588613', NULL),
(354, 'Toko Sinar Dhaya', 'Jalan Astana Anyar', '90A', '000', '000', 'Kota Bandung', '40241', 8, 0, '000', '', '08164868232', 'Bapak Susanto', '2020-11-05', 1, '-6.923448000000000000000000000000', '107.598601000000000000000000000000', 30, '3000000.00', 1, 28, '19262377', NULL),
(355, 'Toko Bangunan Paledang Mukti', 'Jalan Paledang', '20', '000', '000', 'Kota Bandung', '40184', 2, 0, '000', '', '081299383576', 'Bapak Ahmad', '2020-11-05', 1, '-6.905235000000000000000000000000', '107.567234000000000000000000000000', 30, '3000000.00', 1, 28, '25211364', NULL),
(356, 'Toko Kian Sentosa', 'Jl. Letkol G.A. Manulang, Padalarang, Kp. Sudimampir', '1', '000', '000', 'Kabupaten Bandung Barat', '40553', 2, 0, '000', '', '08976989947', 'Bapak Kian', '2020-11-05', 1, '-6.851364000000000000000000000000', '107.476964000000000000000000000000', 30, '3000000.00', 1, 28, '49460662', NULL),
(357, 'Toko Bangunan Apolo', 'Jalan Raya Tagog', '571', '000', '000', 'Kabupaten Bandung Barat', '40553', 2, 0, '000', '', '(022) 6809472', 'Bapak Chandra', '2020-11-05', 1, '-6.842703000000000000000000000000', '107.484141000000000000000000000000', 30, '3000000.00', 1, 28, '10242656', NULL),
(358, 'Toko Angkasa Djaja', 'Jalan Kiaracondong', '265', '003', '013', 'Kota Bandung', '40274', 1, 0, '000', '', '087824177653', 'Bapak Lim Joe Tan', '2020-11-05', 1, '-6.928551000000000000000000000000', '107.644311000000000000000000000000', 30, '3000000.00', 1, 28, '57770485', NULL),
(359, 'Toko Anugerah Makmur Teknik', 'Jalan Cicalengka Majalaya', '95', '004', '012', 'Kabupaten Bandung', '40395', 1, 0, '000', '', '085222362499', 'Bapak Deden Sofyan', '2020-11-05', 1, '-6.987442000000000000000000000000', '107.827239000000000000000000000000', 30, '3000000.00', 1, 28, '81565996', NULL),
(360, 'Toko Bangunan Anugerah', 'Jalan Kopo', '356', '000', '000', 'Kota Bandung', '40233', 2, 0, '000', '', '(022) 6016845', 'Bapak Acen', '2020-11-05', 1, '-6.942861000000000000000000000000', '107.590980000000000000000000000000', 30, '3000000.00', 1, 28, '76207815', NULL),
(361, 'Toko Kurnia Elektrik Cilame', 'Jalan Cilame Cibatu', '53', '000', '000', 'Kota Bandung', '', 2, 0, '000', '', '085956310663', 'Bapak Yulius', '2020-11-05', 1, '-6.849349000000000000000000000000', '107.513049000000000000000000000000', 30, '3000000.00', 1, 28, '99324605', NULL),
(362, 'Toko Sinar Putra Banceuy', 'Jalan Banceuy Bandung Banceuy Centre ', '10', '000', '000', 'Kota Bandung', '40111', 2, 0, 'A1', '', '085974808545', 'Bapak Andri', '2020-11-05', 1, '-6.916324000000000000000000000000', '107.606204000000000000000000000000', 30, '10000000.00', 1, 28, '19053841', NULL),
(363, 'Toko Bangunan Karya Indah', 'Jalan Terusan Soreang - Cipatik KM 2', '000', '000', '000', 'Kabupaten Bandung', '40912', 3, 0, '000', '', '(022) 5891849', 'Bapak Budi', '2020-11-07', 1, '-7.014263000000000000000000000000', '107.527378000000000000000000000000', 30, '3000000.00', 1, 28, '22419088', NULL),
(364, 'Toko Bangunan Bunisari', 'Jalan Bunisari', '25', '000', '000', 'Kabupaten Bandung Barat', '40552', 2, 0, '000', '', '087824352137', 'Bapak Buni', '2020-11-07', 1, '-6.869820000000000000000000000000', '107.514563000000000000000000000000', 30, '3000000.00', 1, 28, '08803876', NULL),
(365, 'Toko Bangunan Sumber Rejeki', 'Jalan Ratna Niaga ', '20', '000', '000', 'Kabupaten Bandung Barat', '40553', 2, 0, '000', '', '081912186007', 'Bapak Hendri', '2020-11-07', 1, '-6.868604000000000000000000000000', '107.469161000000000000000000000000', 30, '3000000.00', 1, 28, '86452399', NULL),
(366, 'Toko Besi Laksana', 'Jalan Stasiun Timur ', '97', '000', '000', 'Kabupaten Bandung Barat', '40553', 2, 0, '000', '', '082117024259', 'Bapak Diki', '2020-11-07', 1, '-6.844461000000000000000000000000', '107.498082000000000000000000000000', 30, '3000000.00', 1, 28, '54946872', NULL),
(367, 'Toko Sanjaya Elektronik', 'Jalan Andir Katapang Cikambuy Tengah ', '29', '000', '000', 'kabupaten Bandung', '40291', 3, 0, '000', '', '08156051547', 'Bapak Hengki', '2020-11-07', 1, '-7.000097000000000000000000000000', '107.559628000000000000000000000000', 30, '5000000.00', 1, 28, '60935655', NULL),
(368, 'Toko SG', 'Jalan Citapen - Ciraden', '53', '000', '000', 'Kabupaten Bandung Barat', '40562', 2, 0, '000', '', '085722183159', 'Bapak Cepi', '2020-11-07', 1, '-6.936345000000000000000000000000', '107.496078000000000000000000000000', 30, '3000000.00', 1, 28, '68951538', NULL),
(369, 'Toko Sankyo AC', 'Jalan Kiaracondong', '404', '000', '000', 'Kota Bandung', '40275', 1, 0, '000', '', '(022) 7333240', 'Ibu Lias Handayani', '2020-11-07', 1, '-6.941814000000000000000000000000', '107.641925000000000000000000000000', 30, '10000000.00', 1, 28, '66431538', NULL),
(370, 'Toko Fajar Elektrik', 'Jalan Purwakarta', '121', '000', '000', 'Kota Bandung', '40291', 1, 0, '000', '', '082120048829', 'Bapak Harun', '2020-11-07', 1, '-6.917224000000000000000000000000', '107.657669000000000000000000000000', 30, '3000000.00', 1, 28, '91143992', NULL),
(371, 'Toko Nabilah Electronic', 'Jalan A. H. Nasution', '25', '000', '000', 'Kota Bandung', '40282', 1, 0, '000', '', '085659163428', 'Bapak Kamim', '2020-11-07', 1, '-6.914001000000000000000000000000', '107.700340000000000000000000000000', 45, '10000000.00', 1, 28, '34808282', NULL),
(372, 'Toko Karya Mandiri', 'Jalan Laswi', '37', '000', '000', 'Kota Bandung', '40273', 8, 0, '000', '', '085320674212', 'Bapak Dika', '2020-11-07', 1, '-6.920976000000000000000000000000', '107.631028000000000000000000000000', 30, '3000000.00', 1, 28, '07460036', NULL),
(373, 'Toko Prasida Elektrik', 'Jalan Kiaracondong', '243', '000', '000', 'Kota Bandung', '40274', 1, 0, '000', '', '081320227080', 'Ibu Yeni', '2020-11-07', 1, '-6.927296000000000000000000000000', '107.644555000000000000000000000000', 30, '10000000.00', 1, 28, '96203783', NULL),
(374, 'Toko Sumber Mulya', 'Jalan Terusan PSM', '224', '000', '000', 'Kota Bandung', '40285', 1, 0, '000', '', '082216000355', 'Ibu Sri Mulyana', '2020-11-07', 1, '-6.930944000000000000000000000000', '107.653866000000000000000000000000', 30, '3000000.00', 1, 28, '51415473', NULL),
(375, 'Toko Electric Shop', 'Jalan Antapani Lama', '20', '000', '000', 'Kota Bandung', '40291', 1, 0, '000', '', '082129020119', 'Ibu Ona', '2020-11-07', 1, '-6.905318000000000000000000000000', '107.657335000000000000000000000000', 30, '3000000.00', 1, 28, '77297636', NULL),
(376, 'Toko Bangunan Sumber Timur', 'Jalan A.H. Nasution', '82', '000', '000', 'Kota Bandung', '40293', 1, 0, '000', '', '081282012359', 'Ibu Nani', '2020-11-07', 1, '-6.904409000000000000000000000000', '107.670249000000000000000000000000', 30, '3000000.00', 1, 28, '11338906', NULL),
(377, 'Toko Remaja Teknik 2', 'Jalan Cijambe', '27', '000', '000', 'Kota Bandung', '40619', 1, 0, '000', '', '082118205869', 'Bapak Wildan', '2020-11-07', 1, '-6.907357000000000000000000000000', '107.690884000000000000000000000000', 30, '5000000.00', 1, 28, '97384039', NULL),
(378, 'Toko Bintang Putra Elektrik 2', 'Jalan Permata Biru', '62', '000', '000', 'Kota Bandung', '40624', 1, 0, 'R', '', '082218714378', 'Ibu Christanti Larasingtyas', '2020-11-07', 1, '-6.940922000000000000000000000000', '107.730991000000000000000000000000', 45, '5000000.00', 1, 28, '16838547', NULL),
(379, 'Toko Pao Lin', 'Jalan Terusan Buah Batu', '113', '000', '000', 'Kota Bandung', '40286', 1, 0, '000', '', '081221893401 ', 'Bapak Aris', '2020-11-07', 1, '-6.955388000000000000000000000000', '107.639339000000000000000000000000', 30, '3000000.00', 1, 28, '42781924', NULL),
(380, 'Toko Setia Kawan', 'Jalan Karapitan', '66A', '000', '000', 'Kota Bandung', '40261', 8, 0, '000', '', '081223637686', 'Bapak Dede', '2020-11-07', 1, '-6.927343000000000000000000000000', '107.616707000000000000000000000000', 30, '3000000.00', 1, 28, '29371204', NULL),
(381, 'Toko Danish Elektrik', 'Jalan Raya Laswi', '119B', '000', '000', 'Kabupaten Bandung', '40392', 3, 0, '000', '', '081280163020', 'Bapak Aris', '2020-11-07', 1, '-7.050654000000000000000000000000', '107.753260000000000000000000000000', 30, '3000000.00', 1, 28, '63047751', NULL),
(382, 'Toko Gemar Electronic', 'Jalan Derwati ', '003', '000', '000', 'Kota Bandung', '40296', 1, 0, '000', '', '085222899833', 'Bapak Sikun', '2020-11-07', 1, '-6.964370000000000000000000000000', '107.678195000000000000000000000000', 30, '5000000.00', 1, 28, '98537013', NULL),
(383, 'Toko H. Anda', 'Jalan Cinangka', '102', '000', '000', 'Kota Bandung', '40616', 1, 0, '000', '', '082215002828', 'Bapak Asep', '2020-11-07', 1, '-6.906900000000000000000000000000', '107.703912000000000000000000000000', 30, '5000000.00', 1, 28, '50123284', NULL),
(384, 'Toko Guna El', 'Jalan Masjid Assyifa', '', '000', '000', 'Kabupaten Bandung', '40287', 1, 0, '000', '', '0817205038', 'Bapak Bobby', '2020-11-07', 1, '-6.985427000000000000000000000000', '107.700515000000000000000000000000', 30, '25000000.00', 1, 28, '32362231', NULL),
(385, 'Toko Bangunan Batusari', 'Jalan Batusari ', '30', '000', '000', 'Kota Bandung', '40287', 1, 0, '000', '', '081317177956', 'Wiwi widaningsih', '2020-11-07', 1, '-6.973784000000000000000000000000', '107.673148000000000000000000000000', 30, '3000000.00', 1, 28, '38630264', NULL),
(386, 'Toko Kita Jaya', 'Jalan Babakan Majasetra', '17', '000', '000', 'Kabupaten Bandung', '40392', 1, 0, '000', '', '08997868589', 'Bapak Ivan Tamusi', '2020-11-07', 1, '-7.043758000000000000000000000000', '107.756319000000000000000000000000', 30, '3000000.00', 1, 28, '09737914', NULL),
(387, 'Toko Cahaya Putri Elektrik', 'Jalan Raya Majalaya', '40', '002', '004', 'Kabupaten Bandung', '40394', 1, 0, '000', '', '081214666161', 'Bapak Endang Budi', '2020-11-07', 1, '-6.967105000000000000000000000000', '107.753621000000000000000000000000', 30, '3000000.00', 1, 28, '42091888', NULL),
(388, 'Toko Cicalengka Elektrik', 'Jalan Majalaya Rancaekek', '49', '000', '000', 'Kabupaten Bandung', '40395', 1, 0, '000', '', '082119107999', 'Bapak Lili', '2020-11-07', 1, '-6.986329000000000000000000000000', '107.840978000000000000000000000000', 30, '25000000.00', 1, 28, '16948311', NULL),
(389, 'Toko Rahayu Rindang', 'Jalan Pasirluyu Barat', '70', '000', '000', 'Kota Bandung', '40254', 1, 0, '000', '', '087777073738', 'Bapak Andri', '2020-11-07', 1, '-6.943724000000000000000000000000', '107.615931000000000000000000000000', 30, '3000000.00', 1, 28, '79366906', NULL),
(390, 'Toko Banjaran El', 'Jalan Banjaran', '50', '000', '000', 'Kota Bandung', '40375', 3, 0, '000', '', '085222707896', 'Bapak Kian ', '2020-11-07', 1, '-6.993417000000000000000000000000', '107.624897000000000000000000000000', 30, '5000000.00', 1, 28, '07216897', NULL),
(391, 'Toko Chi-Chi Elektrik', 'Jalan Buah Batu', '222', '000', '000', 'Kota Bandung', '40265', 1, 0, '000', '', '08122332733', 'Bapak Aang', '2020-11-07', 1, '-6.941567000000000000000000000000', '107.627021000000000000000000000000', 30, '3000000.00', 1, 28, '96183882', NULL),
(392, 'Toko Anugrah Electrical Soljer', 'Jalan Raya Rancaekek Solokan Jeruk', '13', '000', '000', 'Kabupaten Bandung', '40383', 1, 0, '000', '', '085222111106', 'Bapak Suryadi', '2020-11-07', 1, '-7.005844000000000000000000000000', '107.748707000000000000000000000000', 30, '5000000.00', 1, 28, '12026205', NULL),
(393, 'Toko Buana Pembangunan ', 'Jalan Jendral Ahmad Yani ', '825', '000', '000', 'Kota Bandung', '40121', 1, 0, '000', '', '087823061350', 'Bapak Edi', '2020-11-07', 1, '-6.902274000000000000000000000000', '107.657559000000000000000000000000', 30, '3000000.00', 1, 28, '61311751', NULL),
(394, 'Toko Depo Sabaraya Putra ', 'Jalan Raya Cileunyi', '2B', '000', '000', 'Kota Bandung', '40622', 1, 0, '000', '', '085624533888', 'Ajeng Nugraha ', '2020-11-07', 1, '-6.938684000000000000000000000000', '107.753821000000000000000000000000', 30, '5000000.00', 1, 28, '98905367', NULL),
(395, 'Toko Simpony 2', 'Jalan Raya Tanjungsari', '30', '000', '000', 'Kabupaten Bandung', '45362', 1, 0, '000', '', '082320059773', 'Bapak Muhammad Abdul Kozin ', '2020-11-07', 1, '-6.910654000000000000000000000000', '107.795239000000000000000000000000', 30, '5000000.00', 1, 28, '72488444', NULL),
(396, 'Toko Paramon Elektronik', 'Jalan Pasar Parakan Muncang', '4', '000', '000', 'Kota Bandung', '45363', 1, 0, '000', '', '085268309983', 'Steven Chandra', '2020-11-07', 1, '-6.961436000000000000000000000000', '107.826486000000000000000000000000', 30, '5000000.00', 1, 28, '99649395', NULL),
(397, 'Toko Yakob Elektrik', 'Jalan Komplek Barangsiang Indah ', '5', '000', '000', 'Kota Bandung', '', 3, 0, 'A2', '', '081220455249', 'Yakobus Sumiyarto', '2020-11-07', 1, '0.000000000000000000000000000000', '0.000000000000000000000000000000', 30, '3000000.00', 1, 28, '27566589', NULL),
(398, 'Toko Cemerlang El', 'Jalan Raya Laswi Bumi Wangi', '8', '000', '000', 'Kota Bandung', '40381', 3, 0, '000', '', '085351125230', 'Asep Koswara', '2020-11-07', 1, '-7.027363000000000000000000000000', '107.694712000000000000000000000000', 30, '5000000.00', 1, 28, '31328597', NULL),
(399, 'Toko Purnama Jaya Baleendah', 'Jalan Laswi - Cangkring ', '', '000', '000', 'Kota Bandung', '', 3, 0, '000', '', '085974808545', 'Iwan Setiawan', '2020-11-07', 1, '0.000000000000000000000000000000', '0.000000000000000000000000000000', 30, '3000000.00', 1, 28, '92262748', NULL),
(400, 'CV Mulia Jaya Teknik', 'Jalan Jendral Ahmad Yani ', '510', '000', '000', 'Kota Bandung', '40281', 1, 0, '000', '', '087822377337', 'Benyamin Christian', '2020-11-07', 1, '-6.908701000000000000000000000000', '107.643629000000000000000000000000', 30, '10000000.00', 1, 28, '92496616', NULL),
(401, 'Toko Eshan Shara Abadi', 'Jalan Cisokan ', '54', '000', '000', 'Kota Bandung', '40121', 2, 0, '000', '', '083899567076', 'Ibu Asri', '2020-11-07', 1, '-6.903115000000000000000000000000', '107.633033000000000000000000000000', 30, '3000000.00', 1, 28, '50591594', NULL),
(402, 'Toko Banguanan Mitra anyar', 'Jalan batujajar ', '3', '000', '000', 'Kota Bandung', '40553', 2, 0, '000', '', '081320395127', 'Bapak Anwar', '2020-11-07', 1, '-6.876258000000000000000000000000', '107.504157000000000000000000000000', 30, '3000000.00', 1, 28, '63266691', NULL),
(403, 'Toko Indah Jaya Electric', 'Jalan Karapitan', '94', '000', '000', 'Kota Bandung', '40261', 8, 0, '000', '', '081809165888', 'Bapak Andri', '2020-11-09', 1, '-6.928892000000000000000000000000', '107.616302000000000000000000000000', 30, '5000000.00', 1, 28, '61822685', NULL),
(404, 'Toko Surya Elektronik', 'Jalan Babakan Sari II', '1C', '000', '000', 'Kota Bandung', '40283', 1, 0, '000', '', '082218100622', 'Bapak Nurcholis', '2020-11-10', 1, '-6.923635000000000000000000000000', '107.644526000000000000000000000000', 30, '3000000.00', 1, 28, '74780404', NULL),
(405, 'Toko Graha Elektrik', 'Jalan Karapitan', '16A', '000', '000', 'Kota Bandung', '40261', 8, 0, '000', '', '0811225290', 'Bapak Andri', '2020-11-11', 1, '-6.924396000000000000000000000000', '107.617231000000000000000000000000', 30, '5000000.00', 1, 28, '66015445', NULL),
(406, 'Toko Nirmala Electro', 'Jalan Gandasoli ', '69', '000', '000', 'kabupaten bandung', '40921', 3, 0, '000', '', '081220583825', 'Bapak Jaenal', '2020-11-11', 1, '-7.023430000000000000000000000000', '107.549322000000000000000000000000', 30, '5000000.00', 1, 28, '56251533', NULL),
(407, 'PD Mega Jaya', 'Jalan Purwakarta', '72', '000', '000', 'Kota Bandung', '40291', 1, 0, '000', '', '(022) 74204590', 'Bapak Feri', '2020-11-11', 1, '-6.915546000000000000000000000000', '107.653309000000000000000000000000', 30, '3000000.00', 1, 28, '62649300', NULL),
(408, 'Toko Mentari Jaya Elektrik', 'Jalan Lettu Bakrie', '1B', '000', '000', 'Kota Sukabumi', '43131', 5, 0, '000', '', '082129294237', 'Bapak Ruben', '2020-11-11', 1, '-6.921515000000000000000000000000', '106.922225000000000000000000000000', 30, '10000000.00', 1, 28, '72998923', NULL),
(409, 'Toko Cibadak El Sukabumi', 'Jalan Surya Kencana ', '141', '000', '000', 'Kota Sukabumi', '43351', 5, 0, '000', '', '08156009764', 'Bapak Andri', '2020-11-11', 1, '-6.887614000000000000000000000000', '106.779839000000000000000000000000', 60, '1000000000.00', 1, 28, '88248274', NULL),
(410, 'Toko Bangunan Hidup Baru', 'Jalan Holis ', '334', '000', '000', 'Kota Bandung', '40212', 2, 0, '000', '', '08562195805', 'Ibu Linda', '2020-11-11', 1, '-6.935083000000000000000000000000', '107.571093000000000000000000000000', 30, '3000000.00', 1, 28, '06923936', NULL),
(411, 'Toko Bangunan Sentika Jaya 4', 'Kampung blok carik Batujajar', '000', '005', '001', 'Kabupaten Bandung Barat', '40561', 2, 0, '000', '', '081221800489', 'Ibu Tari', '2020-11-13', 1, '-6.918888000000000000000000000000', '107.499744000000000000000000000000', 30, '3000000.00', 1, 28, '21595276', NULL),
(412, 'Toko Sanjaya', 'Jalan Rajawali Barat', '274', '000', '000', 'Kota Bandung', '', 2, 0, '000', '', '08991009271', 'Bapak Arif', '2020-11-13', 1, '0.000000000000000000000000000000', '0.000000000000000000000000000000', 30, '3000000.00', 1, 28, '84211319', NULL),
(413, 'Toko Jayanti Electronic', 'Jalan Raya Banjaran', '691', '000', '000', 'Kabupaten Bandung', '40379', 1, 0, '000', '', '08122429949', 'Bapak Jayanti', '2020-11-13', 1, '-7.033556000000000000000000000000', '107.593902000000000000000000000000', 30, '3000000.00', 1, 28, '92756624', NULL),
(414, 'Toko Laksana Sarijadi', 'Jalan Sarijadi ', '78', '000', '000', 'Kota Bandung', '', 2, 0, '000', '', '081221205150', 'Bapak Mamat', '2020-11-18', 1, '0.000000000000000000000000000000', '0.000000000000000000000000000000', 30, '3000000.00', 1, 28, '44389248', NULL),
(415, 'Toko Sejahtera ', 'Jalan Banceuy', '57', '000', '000', 'kota bandung', '40111', 8, 0, '000', '', ' 082119336818', 'Ibu Lim``', '2020-11-23', 1, '-6.917166000000000000000000000000', '107.606211000000000000000000000000', 30, '3000000.00', 1, 28, '97157836', NULL),
(416, 'Toko Sim', 'Jalan Letnan Jendral Suprapto ', '47', '017', '005', 'Kabupaten Subang', '41211', 10, 0, '000', '', '08122120451', 'Bapak Okky', '2020-11-23', 1, '-6.567692928061450000000000000000', '107.758173985535080000000000000000', 30, '3000000.00', 1, 28, '15584348', NULL),
(417, 'Toko Bima Jaya', 'Jalan DI Panjaitan', '157', '000', '000', 'Kabupaten Subang', '41215', 10, 0, '000', '', '(022) 604243643', 'Ibu Roma', '2020-11-23', 1, '-6.569596000000000000000000000000', '107.767466000000000000000000000000', 30, '3000000.00', 1, 28, '94064329', NULL),
(418, 'Toko Bangunan Humas', 'Jalan Jendral Ahmad Yani', '14', '000', '000', 'Kabupaten Subang', '41211', 10, 0, '000', '', '-', 'Bapak Yana', '2020-11-23', 1, '-6.569551000000000000000000000000', '107.759270000000000000000000000000', 30, '3000000.00', 1, 28, '24210821', NULL),
(419, 'Toko Sinar Terang Subang', 'Jalan Ukong Sutaatmaja', '12', '000', '000', 'Kabupaten Subang', '41211', 10, 0, '000', '', '(0260) 417478', 'Bapak Paulus', '2020-11-23', 1, '-6.564876769727315000000000000000', '107.761132414466910000000000000000', 30, '3000000.00', 1, 28, '79791979', NULL),
(420, 'Toko Mega Electrik', 'Jalan Jendral Ahmad Yani ', '161', '000', '000', 'Kota Sukabumi', '43111', 5, 0, '000', '', '081911755591', 'Bapak Hendri', '2020-11-23', 1, '-6.922382000000000000000000000000', '106.930003000000000000000000000000', 30, '3000000.00', 1, 28, '86078919', NULL),
(421, 'Toko Metro Jaya Listrik', 'Jalan Tata Surya', '60C', '000', '000', 'Kota Bandung', '40286', 1, 0, '000', '', '08999996966', 'Bapak Lie Fa Min', '2020-11-24', 1, '-6.943039000000000000000000000000', '107.667108000000000000000000000000', 30, '3000000.00', 1, 28, '95633215', NULL),
(422, 'Toko Mitek Elektrik', ' Jalan Raya Soreang Cincin', '67', '000', '000', 'Kota Bandung', '40911', 3, 0, '000', '', '', '', '2020-11-24', 1, '-7.027761000000000000000000000000', '107.521733000000000000000000000000', 30, '3000000.00', 1, 28, '16866013', NULL),
(423, 'Toko Leo Elektronik', 'Jalan Raya Soreang Cincin', '', '000', '000', 'kabupaten Bandung', '40331', 3, 0, '000', '', '', '', '2020-11-24', 1, '-7.027800000000000000000000000000', '107.519601000000000000000000000000', 30, '3000000.00', 1, 28, '93964593', NULL),
(424, 'Toko Dimensi Elektronik', 'Jalan Raya Citapen', '006', '000', '000', 'Kabupaten Bandung', '40562', 2, 0, '000', '', '082119931767', 'Bapak Azea', '2020-11-26', 1, '-6.933091000000000000000000000000', '107.496871000000000000000000000000', 30, '3000000.00', 1, 28, '05405145', NULL),
(425, 'Toko Bangunan Berkat', 'Jalan Raya Kaum Selatan', '22', '000', '000', 'Kabupaten Bandung Barat', '40561', 2, 0, '000', '', '(022) 5416247', 'Bapak Jimmy', '2020-11-26', 1, '-6.919173857878611500000000000000', '107.491436443611560000000000000000', 30, '3000000.00', 1, 28, '28525231', NULL),
(426, 'Toko Sepuluh Elektronik', 'Jalan Raya Batujajar', '104', '003', '010', 'Kabupaten Bandung Barat', '40552', 2, 0, '000', '', '0823-1991-3387', '', '2020-11-26', 1, '-6.912241186618291000000000000000', '107.501432274878240000000000000000', 30, '3000000.00', 1, 28, '47193710', NULL),
(427, 'Toko Teguh Jaya Electronic', 'Ruko Segitiga Mas', '1', '000', '000', 'Kota Bandung', '40113', 8, 0, 'B', '', '081931406688', 'Bapak Teguh', '2020-11-27', 1, '-6.917396782641505500000000000000', '107.626131378740000000000000000000', 30, '3000000.00', 1, 28, '43283885', NULL),
(428, 'Toko Bangunan Sinar', 'Jalan Baladewa', '1', '000', '000', 'Kota Bandung', '40173', 2, 0, '000', '', '(022) 6015509', 'Bapak Ferry', '2020-11-27', 1, '-6.905533533291362000000000000000', '107.590473234610430000000000000000', 30, '3000000.00', 1, 28, '91884301', NULL),
(429, 'Toko Vienda', 'Jalan Ciroyom', '105', '000', '000', 'Kota Bandung', '40183', 2, 0, '000', '', '085802740051', 'Ibu Vinny', '2020-11-27', 1, '-6.910715132283728000000000000000', '107.582998263208080000000000000000', 30, '3000000.00', 1, 28, '37688997', NULL),
(430, 'Toko Bangunan Restu Ibu', 'Jalan Baladewa', '3', '000', '000', 'Kota Bandung', '40173', 2, 0, '000', '__.___.___._-___.___', '081223767078', 'Ibu Sari', '2020-11-27', 1, '-6.905493028590635500000000000000', '107.590596705570870000000000000000', 30, '3000000.00', 1, 28, '80906469', NULL),
(431, 'Toko Sayagi', 'Jalan Kopo', '132', '000', '000', 'Kota Bandung', '40232', 3, 0, '000', '', '0895802088638', 'Bapak Sayogi', '2020-11-28', 1, '-6.932546334389123000000000000000', '107.596427388322300000000000000000', 30, '3000000.00', 1, 28, '13855883', NULL),
(432, 'Toko Bangunan Bangkit Jaya Selawu', 'Jalan Raya Salawu', '16', '000', '000', 'Tasikmalaya', '46471', 7, 0, '000', '', '082115492254', 'Bapak Muhammad Hilman Majid', '2020-11-28', 1, '-7.368255106552878000000000000000', '108.023917309898600000000000000000', 30, '3000000.00', 1, 28, '34392322', NULL),
(433, 'Toko Barokah Putra Garut', 'Jalan Cimanuk', '110', '000', '000', 'Kabupaten Garut', '44150', 6, 0, '000', '', '082315156668', 'Bapak Irfan', '2020-11-28', 1, '-7.206891204534150000000000000000', '107.894665471103560000000000000000', 30, '3000000.00', 1, 28, '91397487', NULL),
(434, 'Toko Cahaya Putra Garut', 'Jalan Raya Cibodas Cikajang', '000', '000', '000', 'Garut Regency', '44171', 6, 0, '000', '', '085317628282', 'Bapak Hj. Asep', '2020-11-28', 1, '-7.361524739428999300000000000000', '107.814281929810300000000000000000', 30, '3000000.00', 1, 28, '66512250', NULL),
(435, 'Toko Bangunan Sinar Mulya Indah', 'Jalan Terusan Kopo KM 13.5', '275', '000', '000', 'Kabupaten Bandung', '40912', 3, 0, '000', '', '08977952211', 'Bapak Rizki', '2020-12-03', 1, '-6.999688912897178000000000000000', '107.553532506287810000000000000000', 30, '3000000.00', 1, 28, '03924572', NULL),
(436, 'Toko Bangunan Maju Jaya', 'Jalan Cikambuy Girang', '318A', '000', '000', 'Kabupaten Bandung', '40921', 2, 0, '000', '', '(022) 85871183', 'Bapak Ridwan', '2020-12-03', 1, '-7.000290717327175000000000000000', '107.559854031178500000000000000000', 30, '3000000.00', 1, 28, '86363076', NULL),
(437, 'Toko Bangunan Putra Jaya', 'Jalan Gandasari', '000', '002', '005', 'Kabupaten Bandung', '40921', 2, 0, '000', '', '087734986000', 'Bapak Yayan', '2020-12-03', 1, '-7.022665401014346000000000000000', '107.550210326258830000000000000000', 30, '3000000.00', 1, 28, '89987279', NULL),
(438, 'Toko Bangunan Poetra Jaya', 'Jalan Gandasari', '66', '000', '000', 'Kabupaten Bandung', '40921', 2, 0, '000', '', '081321664862', 'Ibu Euis', '2020-12-03', 1, '-7.020675432117798000000000000000', '107.549457538176680000000000000000', 30, '3000000.00', 1, 28, '25937012', NULL),
(439, 'Toko Bangunan Mandala I', 'Jalan Junti', '36', '000', '000', 'Kabupaten Bandung', '40921', 2, 0, '000', '', '081322338997', 'Ibu Iis', '2020-12-03', 1, '-7.002557157384450000000000000000', '107.567315999879710000000000000000', 30, '3000000.00', 1, 28, '52912166', NULL),
(440, 'Toko Citra Abadi', 'Jalan Sukamanah', '404', '000', '000', 'Kabupaten Bandung', '40383', 1, 0, '000', '', '085220211211', 'Bapak Asep Hendra', '2020-12-03', 1, '-7.044657850680226000000000000000', '107.768178241875690000000000000000', 30, '3000000.00', 1, 28, '91019559', NULL),
(441, 'Toko Medina Elektrik', 'Jalan Pasir Panjang', '10', '000', '000', 'kabupaten Bandung', '40562', 2, 0, '000', '', '082118252007', 'Bapak Hadin', '2020-12-04', 1, '-6.980465000000000000000000000000', '107.428905000000000000000000000000', 30, '3000000.00', 1, 28, '06750146', NULL),
(442, 'Toko Jieto Ragi Elektronik', 'Jalan Pameuntasan', '14', '000', '000', 'kabupaten Bandung', '40911', 2, 0, '000', '', '083116489431', 'Bapak Ragil', '2020-12-04', 1, '-6.972860000000000000000000000000', '107.545250000000000000000000000000', 30, '3000000.00', 1, 28, '18206915', NULL),
(443, 'Sinar inti Ac', 'Jalan Taman Kopo Indah II ', '46', '000', '000', 'Bandung', '000', 2, 0, '000', '', '082115161718', 'Bapak Yugos', '2020-12-04', 1, '0.000000000000000000000000000000', '0.000000000000000000000000000000', 30, '3000000.00', 1, 28, '77384550', NULL),
(444, 'Toko Elektra', ' jalan Raya Lembang', '000', '000', '000', 'kabupaten Bandung Barat', '40391', 2, 0, '000', '', '082121240303', 'Bapak Yusuf', '2020-12-04', 1, '-6.812060000000000000000000000000', '107.617824000000000000000000000000', 30, '3000000.00', 1, 28, '16048236', NULL),
(445, 'Toko Jojon Electronic', 'Jalan Grand Hotel', '51', '000', '000', 'Kabupaten Bandung Barat', '000', 2, 0, '000', '', '08999889992', 'Bapak Asep', '2020-12-04', 1, '-6.815663298469239000000000000000', '107.618177902547160000000000000000', 30, '3000000.00', 1, 28, '53046979', NULL),
(446, 'Toko Fadly Elektronik', 'Jalan Raya Banjaran ', '262', '000', '000', 'kabupaten Bandung', '000', 3, 0, '000', '', '085295499199', 'Bapak Iwan', '2020-12-05', 1, '0.000000000000000000000000000000', '0.000000000000000000000000000000', 30, '3000000.00', 1, 28, '88828938', NULL),
(447, 'Toko Delux Peralatan Listrik', 'Jalan Raya Banjaran Km.13', '84', '000', '000', 'kabupaten Bandung', '40376', 3, 0, '000', '', '087821076645', 'Bapak M Sudrajat', '2020-12-05', 1, '-7.018426000000000000000000000000', '107.604755000000000000000000000000', 30, '3000000.00', 1, 28, '67715690', NULL),
(448, 'Toko GNG Elektronic', 'Jalan Raya Banjaran ', '70', '000', '000', 'kabupaten Bandung', '40379', 3, 0, '000', '', '081324133573', 'Bapak Abdul', '2020-12-05', 1, '-7.046693919858445000000000000000', '107.599703835740140000000000000000', 30, '3000000.00', 1, 28, '09295693', NULL),
(449, 'Toko Alat Listrik Hendaryana', 'Jalan Raya Banjaran - Arjasari ', '69', '000', '000', 'kabupaten Bandung', '40379', 3, 0, '000', '', '089655769678', 'Bapak Epi', '2020-12-05', 1, '-7.046690000000000000000000000000', '107.599344000000000000000000000000', 30, '3000000.00', 1, 28, '41504676', NULL),
(450, 'Toko Aniw 2 Electrinic', 'Jalan Prabu Geusan Ulun', '166', '000', '000', 'kabupaten Sumedang', '45311', 1, 0, '000', '', '0261-201713', 'Bapak Miming', '2020-12-05', 1, '-6.850616000000000000000000000000', '107.922892000000000000000000000000', 30, '3000000.00', 1, 28, '58233996', NULL),
(451, 'Toko Aniw 1 elektronic ', 'Jalan Prabu Geusan Ulun', '50', '000', '000', 'Kabupaten Sumedang', '45311', 1, 0, '000', '', '082315473073', 'Ibu Erni', '2020-12-05', 1, '-6.850572000000000000000000000000', '107.922898000000000000000000000000', 30, '3000000.00', 1, 28, '84553996', NULL),
(452, 'Toko Terang Sumedang', 'jalan Prabu Geusan Ulun', '127', '000', '000', 'Kabupaten Sumedang', '45311', 1, 0, '000', '', '0261-201137', 'Alman', '2020-12-05', 1, '-6.849671000000000000000000000000', '107.923013000000000000000000000000', 30, '3000000.00', 1, 28, '59593859', NULL),
(453, 'Toko Mekar Jaya', 'Jalan Dipatiukur', '229', '000', '000', 'Kota Bandung', '40132', 8, 0, '000', '', '085974323864', 'Bapak Paiq', '2020-12-05', 1, '-6.891604000000000000000000000000', '107.617555000000000000000000000000', 30, '3000000.00', 1, 28, '67658209', NULL),
(454, 'Toko Kenari Sukabumi', 'Jalan Pelabuhan ', '46', '000', '000', 'Kota Sukabumi', '43131', 5, 0, '000', '', '081563482107', 'Bapak Hendra', '2020-12-05', 1, '6.926293000000000000000000000000', '106.926664000000000000000000000000', 30, '3000000.00', 1, 28, '52755521', NULL),
(455, 'Humay Listrik', 'Jl. Somawinata, Tanimulya, Kec. Ngamprah, Kabupaten Bandung Barat, Jawa Barat 40552', '002', '000', '000', 'Kabupaten Bandung Barat', '40552', 2, 0, '000', '', '083116489431', 'Bapak Ratno', '2020-12-07', 1, '6.858369000000000000000000000000', '107.526310000000000000000000000000', 30, '3000000.00', 1, 28, '07326990', NULL),
(456, 'Toko Bangunana Inti Metal', 'Jalan Grand Hotel', '003', '000', '000', 'kabupaten Bandung', '40391', 2, 0, '000', '', '081214441166', 'Bapak Karim', '2020-12-07', 1, '6.817531000000000000000000000000', '107.622236000000000000000000000000', 30, '3000000.00', 1, 28, '21578771', NULL),
(457, 'Toko Bangunan Inti Metal 2', 'Jalan Kolonel Masturi', '325A', '000', '000', 'Kabupaten Bandung Barat', '40551', 2, 0, '000', '', '0818690964', 'Bapak Samuel', '2020-12-07', 1, '6.797564000000000000000000000000', '107.573855000000000000000000000000', 30, '3000000.00', 1, 28, '89068386', NULL),
(458, 'Toko Bintang Timur', 'Jalan Kayu Ambon', '10', '000', '000', 'Kabupaten Bandung Barat', '40391', 2, 0, '000', '', '081222738992', 'Bapak Joko', '2020-12-07', 1, '6.817776000000000000000000000000', '107.622709000000000000000000000000', 30, '3000000.00', 1, 28, '73853925', NULL),
(459, 'Toko Rumah Lampu', 'Jalan Cijeruk', '001', '000', '000', 'Kabupaten Bandung Barat', '', 2, 0, '000', '', '081314853398', 'desta', '2020-12-07', 1, '6.817776000000000000000000000000', '107.622313000000000000000000000000', 30, '3000000.00', 1, 28, '01967615', NULL),
(460, 'Sarana Komunika', 'Jalan Istiqomah', '008', '000', '000', 'Kabupaten Bandung Barat', '000', 2, 0, '000', '', '082216660967', 'Bapak Dandi', '2020-12-07', 1, '-6.819003000000000000000000000000', '107.623986000000000000000000000000', 30, '3000000.00', 1, 28, '60994239', NULL),
(461, 'Toko Berlian Electronic', 'Jalan Ciwastra', '57', '000', '000', 'Kota Bandung', '000', 1, 0, '000', '', '085221959629', 'Bapak Ahmad', '2020-12-07', 1, '-6.961609000000000000000000000000', '107.668446000000000000000000000000', 30, '3000000.00', 1, 28, '67838407', NULL),
(462, 'Toko Farta Electronics', ' Jalan Pratista Barat', '006', '000', '000', 'kota Bandung', '40291', 1, 0, '000', '', '082216163616', '', '2020-12-07', 1, '-6.927597000000000000000000000000', '107.662485000000000000000000000000', 30, '3000000.00', 1, 28, '02868747', NULL),
(463, 'Toko Jaya Indah Cipamokolan', 'Jalan Cipamokolan', '001', '000', '000', 'Kota Bandung', '40292', 1, 0, '000', '', '(022) 7531693', '', '2020-12-07', 1, '-6.941597000000000000000000000000', '107.677095000000000000000000000000', 30, '3000000.00', 1, 28, '28037773', NULL),
(464, 'Toko Alvin Listrik', 'Jalan Citunjung Batujajar', 'A1', '000', '000', 'kabupaten Bandung barat', '40561', 2, 0, '000', '', ' (022) 87780360', '', '2020-12-08', 3, '-6.911655000000000000000000000000', '107.505107000000000000000000000000', 30, '3000000.00', 1, 28, '29393398', NULL),
(465, 'Toko Terang Abadi', 'Jalan Raya Padalarang ', '074', '000', '000', 'Kabupaten Bandung Barat', '40553', 2, 0, '000', '', '081220607121', 'Ibu Indrie', '2020-12-10', 3, '-6.841327000000000000000000000000', '107.481748000000000000000000000000', 30, '3000000.00', 1, 28, '55023572', NULL),
(466, 'Toko Esa Elektronik', 'Jalan Raya Cicalengka ', '260', '000', '000', 'kabupaten Bandung', '', 1, 0, '000', '', '082214861829', 'Ibu n Nia Esa', '2020-12-10', 3, '0.000000000000000000000000000000', '0.000000000000000000000000000000', 30, '3000000.00', 1, 28, '44293755', NULL),
(467, 'Toko Agus Elektro', 'Jalan Raya Rancaekek - Majalaya', '118', '000', '000', 'kabupaten Bandung', '40934', 1, 0, '000', '', '081313208272', 'Bapak Agus`', '2020-12-10', 3, '-6.958236000000000000000000000000', '107.766380000000000000000000000000', 30, '3000000.00', 1, 28, '14060251', NULL),
(468, 'Toko Sami Jaya', 'Jalan Cipamokolan', '35', '000', '000', 'kota Bandung', '40292', 1, 0, '000', '', '', '', '2020-12-10', 3, '-6.941348000000000000000000000000', '107.677324000000000000000000000000', 30, '3000000.00', 1, 28, '15865901', NULL),
(469, 'Toko Guci Photo', 'Jalan Raya Derwati', '90', '000', '000', 'Kota Bandung', '40292', 1, 0, '000', '', '085221902977', 'Bapak Dafis', '2020-12-10', 1, '-6.965589662412915000000000000000', '999.999999999999999999999999999999', 30, '3000000.00', 1, 28, '04109872', NULL),
(470, 'PD Sumber Hidup Elektronik', 'Jalan Mayor Abudrahman', '10', '000', '000', 'Kabupaten Sumedang', '45621', 1, 0, '000', '', '08122117462', 'Bapak Acin Cijaya', '2020-12-11', 1, '-6.846394899851483000000000000000', '107.924354090547450000000000000000', 30, '3000000.00', 1, 28, '11672250', NULL);

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
(4, 'Bandung Utara', NULL),
(5, 'Sukabumi', NULL),
(6, 'Garut', NULL),
(7, 'Tasikmalaya', NULL),
(8, 'Bandung Tengah', NULL),
(9, 'Cianjur', NULL),
(10, 'Subang', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `customer_sales`
--

CREATE TABLE `customer_sales` (
  `id` int(255) NOT NULL,
  `customer_id` int(255) NOT NULL,
  `sales_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `customer_sales`
--

INSERT INTO `customer_sales` (`id`, `customer_id`, `sales_id`) VALUES
(1, 273, 2),
(2, 280, 2),
(3, 34, 2),
(5, 320, 2),
(10, 370, 2),
(15, 45, 2),
(16, 308, 2),
(17, 11, 2),
(18, 371, 2),
(19, 105, 2),
(20, 121, 2),
(40, 136, 2),
(41, 373, 2),
(43, 374, 2),
(44, 358, 2),
(48, 5, 2),
(53, 359, 2),
(58, 115, 2),
(59, 18, 2),
(61, 95, 2),
(62, 40, 2),
(64, 376, 2),
(65, 100, 2),
(67, 378, 2),
(70, 96, 2),
(73, 393, 2),
(74, 391, 2),
(75, 388, 2),
(76, 108, 2),
(77, 394, 2),
(79, 110, 2),
(81, 51, 2),
(82, 112, 2),
(83, 382, 2),
(84, 384, 2),
(85, 383, 2),
(89, 386, 2),
(91, 275, 2),
(92, 276, 2),
(93, 127, 2),
(94, 337, 2),
(96, 336, 2),
(97, 379, 2),
(98, 396, 2),
(100, 389, 2),
(101, 377, 2),
(102, 306, 2),
(104, 395, 2),
(107, 329, 2),
(108, 328, 2),
(109, 325, 2),
(110, 318, 2),
(112, 316, 2),
(115, 310, 2),
(117, 295, 2),
(118, 271, 2),
(122, 50, 2),
(123, 1, 2),
(217, 303, 2),
(221, 92, 2),
(227, 56, 2),
(234, 375, 2),
(249, 6, 2),
(262, 70, 3),
(263, 279, 3),
(264, 283, 3),
(265, 81, 3),
(267, 341, 3),
(268, 83, 3),
(270, 84, 3),
(272, 60, 3),
(273, 86, 3),
(275, 87, 3),
(276, 72, 3),
(277, 88, 3),
(279, 89, 3),
(280, 263, 3),
(281, 282, 3),
(282, 90, 3),
(284, 402, 3),
(285, 91, 3),
(286, 360, 3),
(287, 357, 3),
(291, 293, 3),
(293, 93, 3),
(294, 49, 3),
(295, 38, 3),
(296, 355, 3),
(297, 343, 3),
(298, 134, 3),
(299, 44, 3),
(300, 348, 3),
(301, 97, 3),
(304, 365, 3),
(306, 101, 3),
(307, 74, 3),
(310, 353, 3),
(312, 59, 3),
(313, 103, 3),
(314, 339, 3),
(315, 342, 3),
(316, 104, 3),
(317, 346, 3),
(318, 106, 3),
(319, 107, 3),
(320, 43, 3),
(321, 109, 3),
(325, 113, 3),
(326, 46, 3),
(327, 118, 3),
(328, 120, 3),
(329, 122, 3),
(331, 356, 3),
(332, 340, 3),
(333, 54, 3),
(334, 64, 3),
(335, 361, 3),
(336, 21, 3),
(337, 68, 3),
(339, 281, 3),
(340, 345, 3),
(341, 128, 3),
(342, 129, 3),
(343, 22, 3),
(344, 73, 3),
(346, 133, 3),
(347, 26, 3),
(348, 135, 3),
(349, 367, 3),
(352, 268, 3),
(354, 39, 3),
(355, 368, 3),
(362, 338, 3),
(363, 332, 3),
(364, 331, 3),
(367, 61, 3),
(368, 327, 3),
(369, 362, 3),
(370, 286, 3),
(372, 23, 3),
(373, 323, 3),
(374, 322, 3),
(375, 319, 3),
(376, 321, 3),
(377, 42, 3),
(378, 296, 3),
(379, 315, 3),
(380, 314, 3),
(381, 313, 3),
(382, 312, 3),
(384, 309, 3),
(386, 285, 3),
(387, 304, 3),
(388, 301, 3),
(389, 299, 3),
(391, 297, 3),
(393, 380, 2),
(394, 55, 2),
(395, 311, 2),
(397, 405, 2),
(398, 406, 3),
(400, 125, 2),
(401, 84, 2),
(405, 298, 2),
(406, 52, 2),
(411, 381, 2),
(412, 48, 2),
(413, 23, 2),
(414, 37, 2),
(415, 390, 2),
(416, 31, 2),
(417, 19, 2),
(418, 413, 2),
(419, 123, 2),
(423, 347, 3),
(424, 281, 4),
(425, 338, 4),
(426, 283, 4),
(427, 348, 4),
(428, 268, 4),
(431, 397, 2),
(432, 398, 2),
(433, 399, 2),
(436, 282, 4),
(438, 89, 4),
(439, 113, 4),
(441, 362, 4),
(442, 301, 4),
(443, 21, 4),
(444, 323, 4),
(445, 22, 4),
(446, 91, 4),
(448, 128, 4),
(451, 122, 4),
(452, 286, 4),
(454, 129, 4),
(456, 70, 4),
(457, 103, 4),
(458, 279, 4),
(460, 44, 4),
(461, 353, 4),
(463, 104, 4),
(464, 33, 2),
(465, 401, 2),
(466, 32, 2),
(467, 300, 2),
(469, 30, 2),
(471, 119, 2),
(472, 285, 4),
(473, 46, 4),
(475, 93, 4),
(476, 72, 4),
(477, 355, 4),
(478, 41, 4),
(479, 36, 4),
(480, 339, 4),
(483, 101, 4),
(484, 133, 4),
(485, 340, 4),
(486, 54, 4),
(487, 361, 4),
(488, 64, 4),
(489, 38, 4),
(490, 342, 4),
(491, 346, 4),
(492, 42, 4),
(493, 356, 4),
(494, 357, 4),
(495, 364, 4),
(497, 365, 4),
(499, 117, 4),
(500, 363, 4),
(501, 263, 4),
(502, 423, 4),
(503, 422, 4),
(504, 406, 4),
(505, 264, 4),
(506, 367, 4),
(508, 368, 4),
(510, 304, 4),
(511, 134, 4),
(513, 426, 4),
(514, 424, 4),
(515, 425, 4),
(516, 429, 4),
(517, 430, 4),
(518, 428, 4),
(519, 7, 2),
(520, 13, 2),
(521, 287, 4),
(524, 12, 4),
(525, 309, 4),
(526, 118, 4),
(527, 431, 4),
(530, 369, 2),
(531, 375, 5),
(532, 300, 5),
(533, 18, 5),
(534, 370, 5),
(535, 376, 5),
(536, 45, 5),
(537, 276, 5),
(538, 377, 5),
(541, 35, 2),
(542, 322, 4),
(543, 313, 4),
(544, 331, 4),
(545, 410, 4),
(546, 403, 2),
(547, 63, 2),
(548, 345, 2),
(549, 346, 2),
(550, 347, 2),
(551, 332, 2),
(552, 414, 2),
(553, 61, 2),
(554, 26, 2),
(555, 88, 2),
(556, 280, 5),
(557, 123, 5),
(558, 337, 5),
(559, 383, 5),
(560, 388, 5),
(561, 396, 5),
(562, 52, 5),
(563, 120, 5),
(564, 120, 4),
(565, 341, 4),
(566, 15, 4),
(567, 343, 4),
(568, 387, 5),
(569, 392, 5),
(570, 381, 5),
(571, 359, 5),
(572, 386, 5),
(573, 397, 5),
(574, 85, 2),
(575, 128, 2),
(576, 327, 2),
(577, 47, 2),
(578, 351, 2),
(579, 304, 2),
(580, 334, 2),
(581, 269, 4),
(582, 310, 5),
(583, 379, 5),
(584, 316, 5),
(585, 31, 5),
(586, 48, 5),
(587, 292, 5),
(588, 399, 5),
(589, 350, 2),
(590, 349, 2),
(591, 301, 2),
(592, 282, 2),
(593, 290, 2),
(594, 415, 2),
(595, 122, 2),
(596, 113, 2),
(597, 400, 2),
(598, 435, 4),
(599, 436, 4),
(600, 437, 4),
(601, 440, 5),
(602, 102, 4),
(603, 31, 4),
(604, 324, 4),
(605, 48, 4),
(606, 390, 4),
(607, 413, 4),
(608, 316, 4),
(609, 317, 4),
(610, 394, 5),
(611, 336, 5),
(612, 395, 5),
(613, 320, 5),
(614, 34, 5),
(615, 23, 4),
(616, 441, 4),
(617, 442, 4),
(618, 50, 5),
(619, 136, 5),
(620, 17, 5),
(621, 298, 5),
(622, 325, 5),
(623, 382, 5),
(624, 112, 5),
(625, 275, 5),
(626, 308, 5),
(627, 385, 5),
(628, 267, 5),
(629, 445, 4),
(630, 444, 4),
(631, 448, 4),
(632, 449, 4),
(633, 446, 4),
(634, 447, 4),
(635, 451, 5),
(636, 450, 5),
(637, 452, 5),
(638, 378, 5),
(639, 105, 5),
(640, 108, 5),
(641, 121, 5),
(642, 371, 5),
(643, 306, 5),
(644, 303, 5),
(645, 296, 4),
(646, 344, 4),
(647, 261, 4),
(648, 293, 4),
(649, 443, 4),
(650, 39, 4),
(651, 291, 5),
(652, 455, 4),
(653, 457, 4),
(654, 456, 4),
(655, 458, 4),
(656, 459, 4),
(657, 460, 4),
(658, 461, 5),
(659, 462, 5),
(660, 463, 5),
(661, 464, 4),
(662, 366, 4),
(663, 466, 5),
(664, 467, 5),
(665, 465, 4),
(666, 114, 5),
(667, 468, 5),
(668, 469, 5),
(669, 470, 5);

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
(2, 5, '2020-08-01', 1, '50000000.0000'),
(3, 6, '2020-08-01', 1, '10000000.0000'),
(4, 7, '2020-08-01', 1, '10000000.0000'),
(5, 8, '2020-08-01', 1, '10000000.0000'),
(6, 11, '2020-08-01', 1, '3000000.0000'),
(7, 12, '2020-08-01', 1, '3000000.0000'),
(8, 13, '2020-08-01', 1, '3000000.0000'),
(9, 17, '2020-08-01', 1, '3000000.0000'),
(10, 18, '2020-08-01', 1, '3000000.0000'),
(11, 29, '2020-08-01', 1, '3000000.0000'),
(12, 34, '2020-08-01', 1, '3000000.0000'),
(13, 40, '2020-08-01', 1, '10000000.0000'),
(14, 45, '2020-08-01', 1, '3000000.0000'),
(15, 50, '2020-08-01', 1, '10000000.0000'),
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
(28, 127, '2020-08-01', 1, '50000000.0000'),
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
(39, 36, '2020-08-01', 1, '10000000.0000'),
(40, 39, '2020-08-01', 1, '3000000.0000'),
(41, 41, '2020-08-01', 1, '3000000.0000'),
(42, 42, '2020-08-01', 1, '25000000.0000'),
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
(54, 72, '2020-08-01', 1, '10000000.0000'),
(55, 73, '2020-08-01', 1, '3000000.0000'),
(56, 74, '2020-08-01', 1, '3000000.0000'),
(57, 78, '2020-08-01', 1, '3000000.0000'),
(58, 81, '2020-08-01', 1, '3000000.0000'),
(59, 83, '2020-08-01', 1, '3000000.0000'),
(60, 85, '2020-08-01', 1, '10000000.0000'),
(61, 86, '2020-08-01', 1, '3000000.0000'),
(62, 90, '2020-08-01', 1, '3000000.0000'),
(63, 91, '2020-08-01', 1, '3000000.0000'),
(64, 97, '2020-08-01', 1, '3000000.0000'),
(65, 101, '2020-08-01', 1, '10000000.0000'),
(66, 102, '2020-08-01', 1, '3000000.0000'),
(67, 104, '2020-08-01', 1, '3000000.0000'),
(68, 109, '2020-08-01', 1, '3000000.0000'),
(69, 116, '2020-08-01', 1, '3000000.0000'),
(70, 117, '2020-08-01', 1, '3000000.0000'),
(71, 118, '2020-08-01', 1, '3000000.0000'),
(72, 120, '2020-08-01', 1, '3000000.0000'),
(73, 124, '2020-08-01', 1, '3000000.0000'),
(74, 128, '2020-08-01', 1, '10000000.0000'),
(75, 129, '2020-08-01', 1, '10000000.0000'),
(76, 132, '2020-08-01', 1, '3000000.0000'),
(77, 133, '2020-08-01', 1, '25000000.0000'),
(78, 134, '2020-08-01', 1, '3000000.0000'),
(79, 261, '2020-08-01', 1, '3000000.0000'),
(80, 263, '2020-08-01', 1, '3000000.0000'),
(81, 264, '2020-08-01', 1, '3000000.0000'),
(82, 268, '2020-08-01', 1, '10000000.0000'),
(83, 269, '2020-08-01', 1, '3000000.0000'),
(84, 9, '2020-08-01', 1, '10000000.0000'),
(85, 16, '2020-08-01', 1, '3000000.0000'),
(86, 24, '2020-08-01', 1, '3000000.0000'),
(87, 25, '2020-08-01', 1, '3000000.0000'),
(88, 27, '2020-08-01', 1, '3000000.0000'),
(89, 30, '2020-08-01', 1, '10000000.0000'),
(90, 32, '2020-08-01', 1, '10000000.0000'),
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
(114, 19, '2020-08-01', 1, '10000000.0000'),
(115, 26, '2020-08-01', 1, '3000000.0000'),
(116, 33, '2020-08-01', 1, '3000000.0000'),
(117, 46, '2020-08-01', 1, '3000000.0000'),
(118, 56, '2020-08-01', 1, '10000000.0000'),
(119, 57, '2020-08-01', 1, '3000000.0000'),
(120, 59, '2020-08-01', 1, '3000000.0000'),
(121, 60, '2020-08-01', 1, '3000000.0000'),
(122, 61, '2020-08-01', 1, '3000000.0000'),
(123, 62, '2020-08-01', 1, '3000000.0000'),
(124, 63, '2020-08-01', 1, '3000000.0000'),
(125, 64, '2020-08-01', 1, '10000000.0000'),
(126, 65, '2020-08-01', 1, '3000000.0000'),
(127, 70, '2020-08-01', 1, '3000000.0000'),
(128, 88, '2020-08-01', 1, '3000000.0000'),
(129, 89, '2020-08-01', 1, '3000000.0000'),
(130, 92, '2020-08-01', 1, '3000000.0000'),
(131, 98, '2020-08-01', 1, '3000000.0000'),
(132, 103, '2020-08-01', 1, '3000000.0000'),
(133, 106, '2020-08-01', 1, '3000000.0000'),
(134, 113, '2020-08-01', 1, '10000000.0000'),
(135, 122, '2020-08-01', 1, '3000000.0000'),
(136, 130, '2020-08-01', 1, '3000000.0000'),
(137, 135, '2020-08-01', 1, '3000000.0000'),
(138, 265, '2020-08-01', 1, '50000000.0000'),
(141, 274, '2020-10-01', 1, '10000000.0000'),
(142, 275, '2020-10-01', 1, '3000000.0000'),
(143, 276, '2020-10-01', 1, '3000000.0000'),
(144, 277, '2020-10-01', 1, '3000000.0000'),
(145, 278, '2020-10-01', 1, '3000000.0000'),
(147, 271, '2020-11-01', 1, '250000000.0000'),
(148, 272, '2020-11-01', 1, '50000000.0000'),
(149, 273, '2020-11-01', 1, '50000000.0000'),
(150, 279, '2020-11-01', 1, '10000000.0000'),
(151, 280, '2020-11-01', 1, '3000000.0000'),
(152, 281, '2020-11-01', 1, '10000000.0000'),
(153, 282, '2020-11-01', 1, '10000000.0000'),
(154, 283, '2020-11-01', 1, '10000000.0000'),
(155, 284, '2020-11-01', 1, '15000000.0000'),
(156, 285, '2020-11-01', 1, '50000000.0000'),
(157, 286, '2020-11-01', 1, '30000000.0000'),
(158, 287, '2020-11-01', 1, '50000000.0000'),
(159, 288, '2020-11-01', 1, '50000000.0000'),
(160, 289, '2020-11-01', 1, '50000000.0000'),
(161, 290, '2020-11-01', 1, '10000000.0000'),
(162, 291, '2020-11-01', 1, '10000000.0000'),
(163, 292, '2020-11-01', 1, '3000000.0000'),
(164, 293, '2020-11-01', 1, '3000000.0000'),
(165, 294, '2020-11-01', 1, '3000000.0000'),
(166, 295, '2020-11-01', 1, '3000000.0000'),
(167, 296, '2020-11-01', 1, '3000000.0000'),
(168, 297, '2020-11-01', 1, '3000000.0000'),
(169, 298, '2020-11-01', 1, '3000000.0000'),
(170, 299, '2020-11-01', 1, '3000000.0000'),
(171, 300, '2020-11-01', 1, '3000000.0000'),
(172, 301, '2020-11-01', 1, '10000000.0000'),
(173, 302, '2020-11-01', 1, '3000000.0000'),
(174, 303, '2020-11-01', 1, '3000000.0000'),
(175, 304, '2020-11-01', 1, '3000000.0000'),
(176, 305, '2020-11-01', 1, '3000000.0000'),
(177, 306, '2020-11-01', 1, '3000000.0000'),
(178, 307, '2020-11-01', 1, '1000000.0000'),
(179, 308, '2020-11-01', 1, '3000000.0000'),
(180, 309, '2020-11-01', 1, '3000000.0000'),
(181, 310, '2020-11-01', 1, '3000000.0000'),
(182, 311, '2020-11-01', 1, '3000000.0000'),
(183, 312, '2020-11-01', 1, '3000000.0000'),
(184, 313, '2020-11-01', 1, '3000000.0000'),
(185, 314, '2020-11-01', 1, '3000000.0000'),
(186, 315, '2020-11-01', 1, '3000000.0000'),
(187, 316, '2020-11-01', 1, '10000000.0000'),
(188, 317, '2020-11-01', 1, '10000000.0000'),
(189, 318, '2020-11-01', 1, '3000000.0000'),
(190, 319, '2020-11-01', 1, '3000000.0000'),
(191, 320, '2020-11-01', 1, '3000000.0000'),
(192, 321, '2020-11-01', 1, '3000000.0000'),
(193, 322, '2020-11-01', 1, '3000000.0000'),
(194, 323, '2020-11-01', 1, '3000000.0000'),
(195, 324, '2020-11-01', 1, '3000000.0000'),
(196, 325, '2020-11-01', 1, '3000000.0000'),
(197, 327, '2020-11-01', 1, '3000000.0000'),
(198, 328, '2020-11-01', 1, '3000000.0000'),
(199, 329, '2020-11-01', 1, '3000000.0000'),
(200, 330, '2020-11-01', 1, '3000000.0000'),
(201, 331, '2020-11-01', 1, '3000000.0000'),
(202, 332, '2020-11-01', 1, '3000000.0000'),
(203, 333, '2020-11-01', 1, '3000000.0000'),
(204, 334, '2020-11-01', 1, '3000000.0000'),
(205, 335, '2020-11-01', 1, '10000000.0000'),
(206, 336, '2020-11-01', 1, '10000000.0000'),
(207, 337, '2020-11-01', 1, '10000000.0000'),
(208, 338, '2020-11-01', 1, '10000000.0000'),
(209, 339, '2020-11-01', 1, '3000000.0000'),
(210, 340, '2020-11-01', 1, '10000000.0000'),
(211, 341, '2020-11-01', 1, '3000000.0000'),
(212, 342, '2020-11-01', 1, '3000000.0000'),
(213, 343, '2020-11-01', 1, '10000000.0000'),
(214, 344, '2020-11-01', 1, '3000000.0000'),
(215, 345, '2020-11-01', 1, '10000000.0000'),
(216, 346, '2020-11-01', 1, '3000000.0000'),
(217, 347, '2020-11-01', 1, '3000000.0000'),
(218, 348, '2020-11-01', 1, '3000000.0000'),
(219, 349, '2020-11-01', 1, '10000000.0000'),
(220, 350, '2020-11-01', 1, '10000000.0000'),
(221, 351, '2020-11-01', 1, '3000000.0000'),
(222, 352, '2020-11-01', 1, '3000000.0000'),
(223, 353, '2020-11-01', 1, '10000000.0000'),
(224, 354, '2020-11-01', 1, '3000000.0000'),
(225, 355, '2020-11-01', 1, '3000000.0000'),
(226, 356, '2020-11-01', 1, '3000000.0000'),
(227, 357, '2020-11-01', 1, '3000000.0000'),
(228, 358, '2020-11-01', 1, '3000000.0000'),
(229, 359, '2020-11-01', 1, '3000000.0000'),
(230, 360, '2020-11-01', 1, '3000000.0000'),
(231, 361, '2020-11-01', 1, '3000000.0000'),
(232, 362, '2020-11-01', 1, '3000000.0000'),
(233, 363, '2020-11-01', 1, '3000000.0000'),
(234, 364, '2020-11-01', 1, '3000000.0000'),
(235, 365, '2020-11-01', 1, '3000000.0000'),
(236, 366, '2020-11-01', 1, '3000000.0000'),
(237, 367, '2020-11-01', 1, '3000000.0000'),
(238, 368, '2020-11-01', 1, '3000000.0000'),
(239, 369, '2020-11-01', 1, '3000000.0000'),
(240, 370, '2020-11-01', 1, '3000000.0000'),
(241, 371, '2020-11-01', 1, '3000000.0000'),
(242, 372, '2020-11-01', 1, '3000000.0000'),
(243, 373, '2020-11-01', 1, '3000000.0000'),
(244, 374, '2020-11-01', 1, '3000000.0000'),
(245, 375, '2020-11-01', 1, '3000000.0000'),
(246, 376, '2020-11-01', 1, '3000000.0000'),
(247, 377, '2020-11-01', 1, '3000000.0000'),
(248, 378, '2020-11-01', 1, '3000000.0000'),
(249, 379, '2020-11-01', 1, '3000000.0000'),
(250, 380, '2020-11-01', 1, '3000000.0000'),
(251, 381, '2020-11-01', 1, '3000000.0000'),
(252, 382, '2020-11-01', 1, '3000000.0000'),
(253, 383, '2020-11-01', 1, '3000000.0000'),
(254, 384, '2020-11-01', 1, '10000000.0000'),
(255, 385, '2020-11-01', 1, '3000000.0000'),
(256, 386, '2020-11-01', 1, '3000000.0000'),
(257, 387, '2020-11-01', 1, '3000000.0000'),
(258, 388, '2020-11-01', 1, '10000000.0000'),
(259, 389, '2020-11-01', 1, '3000000.0000'),
(260, 390, '2020-11-01', 1, '3000000.0000'),
(261, 391, '2020-11-01', 1, '3000000.0000'),
(262, 392, '2020-11-01', 1, '3000000.0000'),
(263, 393, '2020-11-01', 1, '3000000.0000'),
(264, 394, '2020-11-01', 1, '3000000.0000'),
(265, 395, '2020-11-01', 1, '3000000.0000'),
(266, 396, '2020-11-01', 1, '3000000.0000'),
(267, 397, '2020-11-01', 1, '3000000.0000'),
(268, 398, '2020-11-01', 1, '3000000.0000'),
(269, 399, '2020-11-01', 1, '3000000.0000'),
(270, 400, '2020-11-01', 1, '3000000.0000'),
(271, 401, '2020-11-01', 1, '3000000.0000'),
(272, 402, '2020-11-01', 1, '3000000.0000'),
(273, 403, '2020-11-01', 1, '3000000.0000'),
(274, 404, '2020-11-01', 1, '3000000.0000'),
(275, 405, '2020-11-01', 1, '3000000.0000'),
(276, 406, '2020-11-01', 1, '3000000.0000'),
(277, 407, '2020-11-01', 1, '3000000.0000'),
(278, 408, '2020-11-01', 1, '10000000.0000'),
(279, 409, '2020-11-01', 1, '50000000.0000'),
(280, 410, '2020-11-01', 1, '3000000.0000'),
(281, 411, '2020-11-01', 1, '3000000.0000'),
(282, 412, '2020-11-01', 1, '3000000.0000'),
(283, 413, '2020-11-01', 1, '3000000.0000'),
(284, 414, '2020-11-01', 1, '3000000.0000'),
(285, 415, '2020-11-01', 1, '3000000.0000'),
(286, 416, '2020-11-01', 1, '3000000.0000'),
(287, 417, '2020-11-01', 1, '3000000.0000'),
(288, 418, '2020-11-01', 1, '3000000.0000'),
(289, 419, '2020-11-01', 1, '3000000.0000'),
(290, 420, '2020-11-01', 1, '3000000.0000'),
(291, 421, '2020-11-01', 1, '3000000.0000'),
(292, 422, '2020-11-01', 1, '3000000.0000'),
(293, 423, '2020-11-01', 1, '3000000.0000'),
(294, 424, '2020-11-01', 1, '3000000.0000'),
(295, 425, '2020-11-01', 1, '3000000.0000'),
(296, 426, '2020-11-01', 1, '3000000.0000'),
(297, 427, '2020-11-01', 1, '3000000.0000'),
(298, 428, '2020-11-01', 1, '3000000.0000'),
(299, 429, '2020-11-01', 1, '3000000.0000'),
(300, 430, '2020-11-01', 1, '3000000.0000'),
(301, 431, '2020-11-01', 1, '3000000.0000'),
(302, 432, '2020-11-01', 1, '3000000.0000'),
(303, 433, '2020-11-01', 1, '3000000.0000'),
(304, 434, '2020-11-01', 1, '3000000.0000'),
(305, 435, '2020-12-01', 1, '3000000.0000'),
(306, 436, '2020-12-01', 1, '3000000.0000'),
(307, 437, '2020-12-01', 1, '3000000.0000'),
(308, 438, '2020-12-01', 1, '3000000.0000'),
(309, 439, '2020-12-01', 1, '3000000.0000'),
(310, 440, '2020-12-01', 1, '3000000.0000'),
(311, 441, '2020-12-01', 1, '3000000.0000'),
(312, 442, '2020-12-01', 1, '3000000.0000'),
(313, 443, '2020-12-01', 1, '3000000.0000'),
(314, 444, '2020-12-01', 1, '3000000.0000'),
(315, 445, '2020-12-01', 1, '3000000.0000'),
(316, 446, '2020-12-01', 1, '3000000.0000'),
(317, 447, '2020-12-01', 1, '3000000.0000'),
(318, 448, '2020-12-01', 1, '3000000.0000'),
(319, 449, '2020-12-01', 1, '3000000.0000'),
(320, 450, '2020-12-01', 1, '3000000.0000'),
(321, 451, '2020-12-01', 1, '3000000.0000'),
(322, 452, '2020-12-01', 1, '3000000.0000'),
(323, 453, '2020-12-01', 1, '3000000.0000'),
(324, 454, '2020-12-01', 1, '3000000.0000'),
(325, 455, '2020-12-01', 1, '3000000.0000'),
(326, 456, '2020-12-01', 1, '3000000.0000'),
(327, 457, '2020-12-01', 1, '3000000.0000'),
(328, 458, '2020-12-01', 1, '3000000.0000'),
(329, 459, '2020-12-01', 1, '3000000.0000'),
(330, 460, '2020-12-01', 1, '3000000.0000'),
(331, 461, '2020-12-01', 1, '3000000.0000'),
(332, 462, '2020-12-01', 1, '3000000.0000'),
(333, 463, '2020-12-01', 1, '3000000.0000'),
(334, 464, '2020-12-01', 3, '3000000.0000'),
(335, 465, '2020-12-01', 3, '3000000.0000'),
(336, 466, '2020-12-01', 3, '3000000.0000'),
(337, 467, '2020-12-01', 3, '3000000.0000'),
(338, 468, '2020-12-01', 3, '3000000.0000'),
(339, 469, '2020-12-01', 1, '3000000.0000'),
(340, 470, '2020-12-01', 1, '3000000.0000');

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
  `quantity` int(255) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `delivery_order`
--

INSERT INTO `delivery_order` (`id`, `sales_order_id`, `code_delivery_order_id`, `quantity`) VALUES
(1, 1, 1, 5),
(2, 1, 2, 5),
(3, 74, 3, 5),
(4, 2, 4, 2),
(5, 3, 4, 2),
(6, 4, 4, 2),
(7, 5, 4, 1),
(8, 6, 4, 2),
(9, 10, 5, 5),
(10, 11, 5, 1);

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
(1, 1, 2, 'OUT', 1, '0.0000'),
(2, 2, 1, 'IN', 1, '0.0000'),
(3, 184, 5, 'IN', 2, '10000.0000');

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
(2, 'Utilities', NULL, 'This class is used for utilities expenses, such as electricity bill, water bill, or phone bill', 1, '2020-03-24', 1),
(3, 'Tax', NULL, 'This class is used for tax expenses, including income tax, saving tax, value added tax.', 1, '2020-03-24', 3),
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
(15, 'Income tax', 3, 'Account for income tax payment (PPh)', 1, '2020-03-24', 3),
(16, 'Value added tax', 3, 'Account for value-added tax payment (PPn)', 1, '2020-03-24', 3),
(17, 'Tax penalties', 3, 'Account for tax penalties payment', 1, '2020-03-24', 3),
(18, 'Office operational', NULL, 'This class is used for office operational expenses, such as document delivery or office equipment purchases', 1, '2020-03-24', 1),
(19, 'Document delivery', 18, 'Account for document delivery (invoices, counter-invoices, guarantee letter, and other important documents) expense', 1, '2020-03-24', 1),
(20, 'Office equipment', 18, 'Account for office stationary expense', 1, '2020-03-24', 1),
(21, 'Marketing cost', NULL, 'This class is used for marketing expenses including transportation, marketing fee, and telecommunication.', 1, '2020-08-12', 1),
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

--
-- Dumping data untuk tabel `good_receipt`
--

INSERT INTO `good_receipt` (`id`, `purchase_order_id`, `quantity`, `code_good_receipt_id`, `billed_price`) VALUES
(3, 1, 50, 1, '288640.0000'),
(4, 2, 29, 2, '405155.5200'),
(5, 3, 20, 2, '362435.0400'),
(6, 4, 10, 2, '516780.0000'),
(7, 5, 2, 2, '438229.4400'),
(8, 6, 3, 2, '399643.2000'),
(9, 7, 1, 2, '2825064.0000'),
(10, 8, 7, 2, '192931.2000'),
(11, 9, 2, 2, '658033.2000'),
(12, 10, 10, 2, '330739.2000'),
(13, 11, 1, 2, '310068.0000'),
(14, 12, 5, 2, '447876.0000'),
(15, 13, 6, 2, '385862.4000');

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
(4, 'Other', 'This account is used for other income', 1, '2020-07-07'),
(5, 'Rounding off payments', 'This class is used for rounded customer\'s payment.', 1, '2020-10-22');

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
(1, 'Siauw Tjun Kwek', '8090337433', 'Bank Central Asia', 'KCP Ahmad Yani II, Bandung');

-- --------------------------------------------------------

--
-- Struktur dari tabel `invoice`
--

CREATE TABLE `invoice` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` decimal(50,2) NOT NULL,
  `discount` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `delivery` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `date` date NOT NULL,
  `information` text NOT NULL,
  `is_done` tinyint(1) DEFAULT '0',
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `taxInvoice` varchar(50) DEFAULT NULL,
  `lastBillingDate` date DEFAULT NULL,
  `nextBillingDate` date DEFAULT NULL,
  `is_billed` tinyint(1) NOT NULL DEFAULT '0',
  `opponent_id` int(255) DEFAULT NULL,
  `customer_id` int(255) DEFAULT NULL,
  `type` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `invoice`
--

INSERT INTO `invoice` (`id`, `name`, `value`, `discount`, `delivery`, `date`, `information`, `is_done`, `is_confirm`, `taxInvoice`, `lastBillingDate`, `nextBillingDate`, `is_billed`, `opponent_id`, `customer_id`, `type`) VALUES
(1, 'INV.DSE202011-00020', '1443200.00', '0.0000', '0.0000', '2020-11-13', 'DO-DSE-202011-00020', 1, 1, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(3, 'INV.DSE202012-00010', '1428768.00', '0.0000', '0.0000', '2020-12-02', 'DO-DSE-202012-00010', 0, 1, NULL, '2020-12-05', NULL, 0, NULL, NULL, NULL),
(5, 'INV.DSE202012-00020', '3366528.00', '0.0000', '0.0000', '2020-12-05', 'DO-DSE-202012-00020', 0, 1, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(6, 'INV.DSE202012-00030', '1410400.00', '0.0000', '0.0000', '2020-12-10', 'DO-DSE-202012-00030', 0, 1, NULL, NULL, NULL, 0, NULL, NULL, NULL);

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
(65, 'NYMHY21_50_EXT', 'Kabel NYMHY 2 x 1,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 13, 0, 90.00),
(66, 'NYMHY2075_100_EXT', 'Kabel NYMHY 2 x 0,75mm<sup>2</sup> kemasan 100 meter (Extrana)', 13, 0, 70.00),
(67, 'NYMHY22_50_EXT', 'Kabel NYMHY 2 x 2,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 13, 0, 95.00),
(68, 'NYMHY3075_50_EXT', 'Kabel NYMHY 3 x 0,75mm<sup>2</sup> kemasan 50 meter (Extrana)', 13, 0, 95.00),
(69, 'NYMHY31_50_EXT', 'Kabel NYMHY 3 x 1,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 13, 0, 95.00),
(70, 'NYMHY32_50_EXT', 'Kabel NYMHY 3 x 2,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 13, 0, 95.00),
(71, 'NYMHY4075_50_EXT', 'Kabel NYMHY 4 x 0,75mm<sup>2</sup> kemasan 50 meter (Extrana)', 13, 0, 95.00),
(72, 'NYMHY41_50_EXT', 'Kabel NYMHY 4 x 1,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 13, 0, 95.00),
(73, 'NYMHY42_50_EXT', 'Kabel NYMHY 4 x 2,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 13, 0, 95.00),
(74, 'NYMHY21_100_EXT', 'Kabel NYMHY 2 x 1,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 13, 0, 70.00),
(75, 'NYMHY22_100_EXT', 'Kabel NYMHY 2 x 2,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 13, 0, 70.00),
(76, 'NYMHY3075_100_EXT', 'Kabel NYMHY 3 x 0,75mm<sup>2</sup> kemasan 100 meter (Extrana)', 13, 0, 70.00),
(77, 'NYMHY31_100_EXT', 'Kabel NYMHY 3 x 1,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 13, 0, 70.00),
(78, 'NYMHY32_100_EXT', 'Kabel NYMHY 3 x 2,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 13, 0, 70.00),
(79, 'NYMHY4075_100_EXT', 'Kabel NYMHY 4 x 0,75mm<sup>2</sup> kemasan 100 meter (Extrana)', 13, 0, 0.00),
(80, 'NYMHY41_100_EXT', 'Kabel NYMHY 4 x 1,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 13, 0, 70.00),
(81, 'NYMHY42_100_EXT', 'Kabel NYMHY 4 x 2,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 13, 0, 70.00),
(82, 'NYMHY2075_200_EXT', 'Kabel NYMHY 2 x 0,75mm<sup>2</sup> kemasan 200 meter (Extrana)', 13, 0, 90.00),
(83, 'NYMHY21_200_EXT', 'Kabel NYMHY 2 x 1,5mm<sup>2</sup> kemasan 200 meter (Extrana)', 13, 0, 90.00),
(84, 'NYMHY22_200_EXT', 'Kabel NYMHY 2 x 2,5mm<sup>2</sup> kemasan 200 meter (Extrana)', 13, 0, 90.00),
(85, 'NYMHY3075_200_EXT', 'Kabel NYMHY 3 x 0,75mm<sup>2</sup> kemasan 200 meter (Extrana)', 13, 0, 90.00),
(86, 'NYMHY31_200_EXT', 'Kabel NYMHY 3 x 1,5mm<sup>2</sup> kemasan 200 meter (Extrana)', 13, 0, 90.00),
(87, 'NYMHY32_200_EXT', 'Kabel NYMHY 3 x 2,5mm<sup>2</sup> kemasan 200 meter (Extrana)', 13, 0, 90.00),
(88, 'NYMHY4075_200_EXT', 'Kabel NYMHY 4 x 0,75mm<sup>2</sup> kemasan 200 meter (Extrana)', 13, 0, 90.00),
(89, 'NYMHY41_200_EXT', 'Kabel NYMHY 4 x 1,5mm<sup>2</sup> kemasan 200 meter (Extrana)', 13, 0, 90.00),
(90, 'NYMHY42_200_EXT', 'Kabel NYMHY 4 x 2,5mm<sup>2</sup> kemasan 200 meter (Extrana)', 13, 0, 90.00),
(91, 'NYMHY2075_250_EXT', 'Kabel NYMHY 2 x 0,75mm<sup>2</sup> kemasan 250 meter (Extrana)', 13, 0, 70.00),
(92, 'NYMHY21_250_EXT', 'Kabel NYMHY 2 x 1,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 13, 0, 70.00),
(93, 'NYMHY22_250_EXT', 'Kabel NYMHY 2 x 2,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 13, 1, 70.00),
(94, 'NYMHY3075_250_EXT', 'Kabel NYMHY 3 x 0,75mm<sup>2</sup> kemasan 250 meter (Extrana)', 13, 0, 70.00),
(95, 'NYMHY31_250_EXT', 'Kabel NYMHY 3 x 1,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 13, 0, 70.00),
(96, 'NYMHY32_250_EXT', 'Kabel NYMHY 3 x 2,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 13, 0, 70.00),
(97, 'NYMHY4075_250_EXT', 'Kabel NYMHY 4 x 0,75mm<sup>2</sup> kemasan 250 meter (Extrana)', 13, 0, 70.00),
(98, 'NYMHY41_250_EXT', 'Kabel NYMHY 4 x 1,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 13, 0, 70.00),
(99, 'NYMHY42_250_EXT', 'Kabel NYMHY 4 x 2,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 13, 0, 70.00),
(100, 'NYMHY2075_500_EXT', 'Kabel NYMHY 2 x 0,75mm<sup>2</sup> kemasan 500 meter (Extrana)', 13, 0, 70.00),
(101, 'NYMHY21_500_EXT', 'Kabel NYMHY 2 x 1,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 13, 0, 70.00),
(102, 'NYMHY22_500_EXT', 'Kabel NYMHY 2 x 2,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 14, 0, 70.00),
(103, 'NYMHY3075_500_EXT', 'Kabel NYMHY 3 x 0,75mm<sup>2</sup> kemasan 500 meter (Extrana)', 13, 0, 70.00),
(104, 'NYMHY31_500_EXT', 'Kabel NYMHY 3 x 1,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 13, 0, 70.00),
(105, 'NYMHY32_500_EXT', 'Kabel NYMHY 3 x 2,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 13, 0, 70.00),
(106, 'NYMHY4075_500_EXT', 'Kabel NYMHY 4 x 0,75mm<sup>2</sup> kemasan 500 meter (Extrana)', 13, 0, 70.00),
(107, 'NYMHY41_500_EXT', 'Kabel NYMHY 4 x 1,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 13, 0, 70.00),
(108, 'NYMHY42_500_EXT', 'Kabel NYMHY 4 x 2,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 13, 0, 70.00),
(109, 'NYYHY2075_50_EXT', 'Kabel NYYHY 2 x 0,75mm<sup>2</sup> kemasan 50 meter (Extrana)', 14, 0, 95.00),
(110, 'NYYHY21_50_EXT', 'Kabel NYYHY 2 x 1,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 14, 0, 95.00),
(111, 'NYYHY22_50_EXT', 'Kabel NYYHY 2 x 2,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 14, 0, 95.00),
(112, 'NYYHY3075_50_EXT', 'Kabel NYYHY 3 x 0,75mm<sup>2</sup> kemasan 50 meter (Extrana)', 13, 0, 95.00),
(113, 'NYYHY31_50_EXT', 'Kabel NYYHY 3 x 1,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 14, 0, 95.00),
(114, 'NYYHY32_50_EXT', 'Kabel NYYHY 3 x 2,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 14, 0, 95.00),
(115, 'NYYHY4075_50_EXT', 'Kabel NYYHY 4 x 0,75mm<sup>2</sup> kemasan 50 meter (Extrana)', 14, 0, 95.00),
(116, 'NYYHY41_50_EXT', 'Kabel NYYHY 4 x 1,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 14, 0, 95.00),
(117, 'NYYHY42_50_EXT', 'Kabel NYYHY 4 x 2,5mm<sup>2</sup> kemasan 50 meter (Extrana)', 14, 0, 95.00),
(118, 'NYYHY2075_100_EXT', 'Kabel NYYHY 2 x 0,75mm<sup>2</sup> kemasan 100 meter (Extrana)', 14, 0, 70.00),
(119, 'NYYHY21_100_EXT', 'Kabel NYYHY 2 x 1,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 14, 0, 70.00),
(120, 'NYYHY22_100_EXT', 'Kabel NYYHY 2 x 2,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 14, 0, 70.00),
(121, 'NYYHY3075_100_EXT', 'Kabel NYYHY 3 x 0,75mm<sup>2</sup> kemasan 100 meter (Extrana)', 14, 0, 70.00),
(122, 'NYYHY31_100_EXT', 'Kabel NYYHY 3 x 1,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 14, 0, 70.00),
(123, 'NYYHY32_100_EXT', 'Kabel NYYHY 3 x 2,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 14, 0, 70.00),
(124, 'NYYHY4075_100_EXT', 'Kabel NYYHY 4 x 0,75mm<sup>2</sup> kemasan 100 meter (Extrana)', 14, 0, 70.00),
(125, 'NYYHY41_100_EXT', 'Kabel NYYHY 4 x 1,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 14, 0, 70.00),
(126, 'NYYHY42_100_EXT', 'Kabel NYYHY 4 x 2,5mm<sup>2</sup> kemasan 100 meter (Extrana)', 14, 0, 70.00),
(127, 'NYYHY2075_200_EXT', 'Kabel NYYHY 2 x 0,75mm<sup>2</sup> kemasan 200 meter (Extrana)', 14, 0, 90.00),
(128, 'NYYHY21_200_EXT', 'Kabel NYYHY 2 x 1,5mm<sup>2</sup> kemasan 200 meter (Extrana)', 14, 0, 90.00),
(129, 'NYYHY22_200_EXT', 'Kabel NYYHY 2 x 2,5mm<sup>2</sup> kemasan 200 meter (Extrana)', 14, 0, 90.00),
(130, 'NYYHY3075_200_EXT', 'Kabel NYYHY 3 x 0,75mm<sup>2</sup> kemasan 200 meter (Extrana)', 14, 0, 90.00),
(131, 'NYYHY31_200_EXT', 'Kabel NYYHY 3 x 1,5mm<sup>2</sup> kemasan 200 meter (Extrana)', 14, 0, 90.00),
(132, 'NYYHY32_200_EXT', 'Kabel NYYHY 3 x 2,5mm<sup>2</sup> kemasan 200 meter (Extrana)', 14, 0, 90.00),
(133, 'NYYHY4075_200_EXT', 'Kabel NYYHY 4 x 0,75mm<sup>2</sup> kemasan 200 meter (Extrana)', 14, 0, 90.00),
(134, 'NYYHY41_200_EXT', 'Kabel NYYHY 4 x 1,5mm<sup>2</sup> kemasan 200 meter (Extrana)', 14, 0, 90.00),
(135, 'NYYHY42_200_EXT', 'Kabel NYYHY 4 x 2,5mm<sup>2</sup> kemasan 200 meter (Extrana)', 14, 0, 90.00),
(136, 'NYYHY2075_250_EXT', 'Kabel NYYHY 2 x 0,75mm<sup>2</sup> kemasan 250 meter (Extrana)', 14, 0, 70.00),
(137, 'NYYHY21_250_EXT', 'Kabel NYYHY 2 x 1,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 14, 0, 70.00),
(138, 'NYYHY22_250_EXT', 'Kabel NYYHY 2 x 2,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 14, 0, 70.00),
(139, 'NYYHY3075_250_EXT', 'Kabel NYYHY 3 x 0,75mm<sup>2</sup> kemasan 250 meter (Extrana)', 14, 0, 70.00),
(140, 'NYYHY31_250_EXT', 'Kabel NYYHY 3 x 1,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 14, 0, 70.00),
(141, 'NYYHY32_250_EXT', 'Kabel NYYHY 3 x 2,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 14, 0, 70.00),
(142, 'NYYHY4075_250_EXT', 'Kabel NYYHY 4 x 0,75mm<sup>2</sup> kemasan 250 meter (Extrana)', 14, 0, 70.00),
(143, 'NYYHY41_250_EXT', 'Kabel NYYHY 4 x 1,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 14, 0, 70.00),
(144, 'NYYHY42_250_EXT', 'Kabel NYYHY 4 x 2,5mm<sup>2</sup> kemasan 250 meter (Extrana)', 14, 0, 70.00),
(145, 'NYYHY2075_500_EXT', 'Kabel NYYHY 2 x 0,75mm<sup>2</sup> kemasan 500 meter (Extrana)', 14, 0, 70.00),
(146, 'NYYHY21_500_EXT', 'Kabel NYYHY 2 x 1,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 14, 0, 70.00),
(147, 'NYYHY22_500_EXT', 'Kabel NYYHY 2 x 2,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 14, 0, 70.00),
(148, 'NYYHY3075_500_EXT', 'Kabel NYYHY 3 x 0,75mm<sup>2</sup> kemasan 500 meter (Extrana)', 14, 0, 70.00),
(149, 'NYYHY31_500_EXT', 'Kabel NYYHY 3 x 1,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 14, 0, 70.00),
(150, 'NYYHY32_500_EXT', 'Kabel NYYHY 3 x 2,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 14, 0, 70.00),
(151, 'NYYHY4075_500_EXT', 'Kabel NYYHY 4 x 0,75mm<sup>2</sup> kemasan 500 meter (Extrana)', 14, 0, 70.00),
(152, 'NYYHY41_500_EXT', 'Kabel NYYHY 4 x 1,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 14, 0, 70.00),
(153, 'NYYHY42_500_EXT', 'Kabel NYYHY 4 x 2,5mm<sup>2</sup> kemasan 500 meter (Extrana)', 14, 0, 70.00),
(154, 'NYA1H_100_EXT', 'Kabel NYA 1 x 1,5mm<sup>2</sup> Hitam kemasan 100 meter (Extrana)', 11, 0, 95.00),
(155, 'NYA1M_100_EXT', 'Kabel NYA 1 x 1,5mm<sup>2</sup> Merah kemasan 100 meter (Extrana)', 11, 0, 95.00),
(156, 'NYA1B_100_EXT', 'Kabel NYA 1 x 1,5mm<sup>2</sup> Biru kemasan 100 meter (Extrana)', 11, 0, 90.00),
(157, 'NYA1KH_100_EXT', 'Kabel NYA 1 x 1,5mm<sup>2</sup> Kuning Hijau kemasan 100 meter (Extrana)', 11, 0, 70.00),
(158, 'NYA2H_100_EXT', 'Kabel NYA 1 x 2,5mm<sup>2</sup> Hitam kemasan 100 meter (Extrana)', 11, 0, 95.00),
(159, 'NYA2M_100_EXT', 'Kabel NYA 1 x 2,5mm<sup>2</sup> Merah kemasan 100 meter (Extrana)', 11, 0, 95.00),
(160, 'NYA2B_100_EXT', 'Kabel NYA 1 x 2,5mm<sup>2</sup> Biru kemasan 100 meter (Extrana)', 11, 0, 90.00),
(161, 'NYA2KH_100_EXT', 'Kabel NYA 1 x 2,5mm<sup>2</sup> Kuning Hijau kemasan 100 meter (Extrana)', 11, 0, 70.00),
(162, 'NYAF075H_100_EXT', 'Kabel NYAF 1 x 0,75mm<sup>2</sup> Hitam kemasan 100 meter (Extrana)', 15, 0, 95.00),
(163, 'NYAF075M_100_EXT', 'Kabel NYAF 1 x 0,75mm<sup>2</sup> Merah kemasan 100 meter (Extrana)', 15, 0, 95.00),
(164, 'NYAF075B_100_EXT', 'Kabel NYAF 1 x 0,75mm<sup>2</sup> Biru kemasan 100 meter (Extrana)', 15, 0, 90.00),
(165, 'NYAF075KH_100_EXT', 'Kabel NYAF 1 x 0,75mm<sup>2</sup> Kuning Hijau kemasan 100 meter (Extrana)', 15, 0, 70.00),
(166, 'NYAF1H_100_EXT', 'Kabel NYAF 1 x 1,5mm<sup>2</sup> Hitam kemasan 100 meter (Extrana)', 15, 0, 95.00),
(167, 'NYAF1M_100_EXT', 'Kabel NYAF 1 x 1,5mm<sup>2</sup> Merah kemasan 100 meter (Extrana)', 15, 0, 95.00),
(168, 'NYAF1B_100_EXT', 'Kabel NYAF 1 x 1,5mm<sup>2</sup> Biru kemasan 100 meter (Extrana)', 15, 0, 90.00),
(169, 'NYAF1KH_100_EXT', 'Kabel NYAF 1 x 1,5mm<sup>2</sup> Kuning Hijau kemasan 100 meter (Extrana)', 15, 0, 70.00),
(170, 'NYAF2H_100_EXT', 'Kabel NYAF 1 x 2,5mm<sup>2</sup> Hitam kemasan 100 meter (Extrana)', 15, 0, 95.00),
(171, 'NYAF2M_100_EXT', 'Kabel NYAF 1 x 2,5mm<sup>2</sup> Merah kemasan 100 meter (Extrana)', 15, 0, 95.00),
(172, 'NYAF2B_100_EXT', 'Kabel NYAF 1 x 2,5mm<sup>2</sup> Biru kemasan 100 meter (Extrana)', 15, 0, 90.00),
(173, 'NYAF2KH_100_EXT', 'Kabel NYAF 1 x 2,5mm<sup>2</sup> Kuning Hijau kemasan 100 meter (Extrana)', 15, 0, 70.00),
(174, 'NYA4H_100_EXT', 'Kabel NYA 1 x 4mm<sup>2</sup> Hitam kemasan 100 meter (Extrana)', 12, 0, 95.00),
(175, 'NYA4M_100_EXT', 'Kabel NYA 1 x 4mm<sup>2</sup> Merah kemasan 100 meter (Extrana)', 12, 0, 95.00),
(176, 'NYA4B_100_EXT', 'Kabel NYA 1 x 4mm<sup>2</sup> Biru kemasan 100 meter (Extrana)', 12, 0, 90.00),
(177, 'NYA4KH_100_EXT', 'Kabel NYA 1 x 4mm<sup>2</sup> Kuning Hijau kemasan 100 meter (Extrana)', 12, 0, 70.00),
(178, 'NYA6H_100_EXT', 'Kabel NYA 1 x 6mm<sup>2</sup> Hitam kemasan 100 meter (Extrana)', 12, 0, 95.00),
(179, 'NYA6M_100_EXT', 'Kabel NYA 1 x 6mm<sup>2</sup> Merah kemasan 100 meter (Extrana)', 12, 0, 95.00),
(180, 'NYA6B_100_EXT', 'Kabel NYA 1 x 6mm<sup>2</sup> Biru kemasan 100 meter (Extrana)', 12, 0, 90.00),
(181, 'NYA6KH_100_EXT', 'Kabel NYA 1 x 6mm<sup>2</sup> Kuning Hijau kemasan 100 meter (Extrana)', 12, 0, 70.00),
(182, 'NYA10H_100_EXT', 'Kabel NYA 1 x 10mm<sup>2</sup> Hitam kemasan 100 meter (Extrana)', 12, 0, 95.00),
(183, 'NYA10M_100_EXT', 'Kabel NYA 1 x 10mm<sup>2</sup> Merah kemasan 100 meter (Extrana)', 12, 0, 95.00),
(184, 'NYA10B_100_EXT', 'Kabel NYA 1 x 10mm<sup>2</sup> Biru kemasan 100 meter (Extrana)', 12, 1, 90.00),
(185, 'NYA10KH_100_EXT', 'Kabel NYA 1 x 10mm<sup>2</sup> Kuning Hijau kemasan 100 meter (Extrana)', 12, 0, 70.00),
(186, 'NYAF4H_100_EXT', 'Kabel NYAF 1 x 4mm<sup>2</sup> Hitam kemasan 100 meter (Extrana)', 16, 0, 95.00),
(187, 'NYAF4M_100_EXT', 'Kabel NYAF 1 x 4mm<sup>2</sup> Merah kemasan 100 meter (Extrana)', 16, 0, 95.00),
(188, 'NYAF4B_100_EXT', 'Kabel NYAF 1 x 4mm<sup>2</sup> Biru kemasan 100 meter (Extrana)', 16, 0, 90.00),
(189, 'NYAF4KH_100_EXT', 'Kabel NYAF 1 x 4mm<sup>2</sup> Kuning Hijau kemasan 100 meter (Extrana)', 16, 0, 70.00),
(190, 'NYAF6H_100_EXT', 'Kabel NYAF 1 x 6mm<sup>2</sup> Hitam kemasan 100 meter (Extrana)', 16, 0, 95.00),
(191, 'NYAF6M_100_EXT', 'Kabel NYAF 1 x 6mm<sup>2</sup> Merah kemasan 100 meter (Extrana)', 16, 0, 95.00),
(192, 'NYAF6B_100_EXT', 'Kabel NYAF 1 x 6mm<sup>2</sup> Biru kemasan 100 meter (Extrana)', 16, 0, 90.00),
(193, 'NYAF6KH_100_EXT', 'Kabel NYAF 1 x 6mm<sup>2</sup> Kuning Hijau kemasan 100 meter (Extrana)', 16, 0, 70.00),
(194, 'NYAF10H_100_EXT', 'Kabel NYAF 1 x 10mm<sup>2</sup> Hitam kemasan 100 meter (Extrana)', 16, 1, 95.00),
(195, 'NYAF10M_100_EXT', 'Kabel NYAF 1 x 10mm<sup>2</sup> Merah kemasan 100 meter (Extrana)', 16, 0, 95.00),
(196, 'NYAF10B_100_EXT', 'Kabel NYAF 1 x 10mm<sup>2</sup> Biru kemasan 100 meter (Extrana)', 16, 0, 90.00),
(197, 'NYAF10KH_100_EXT', 'Kabel NYAF 1 x 10mm<sup>2</sup> Kuning Hijau kemasan 100 meter (Extrana)', 16, 0, 70.00),
(198, 'NYY24_50_EXT', 'Kabel NYY 2 x 4mm<sup>2</sup> kemasan 50 meter (Extrana)', 10, 0, 70.00),
(199, 'NYY26_50_EXT', 'Kabel NYY 2 x 6mm<sup>2</sup> kemasan 50 meter (Extrana)', 10, 0, 70.00),
(200, 'NYY210_50_EXT', 'Kabel NYY 2 x 10mm<sup>2</sup> kemasan 50 meter (Extrana)', 10, 0, 70.00),
(201, 'NYY34_50_EXT', 'Kabel NYY 3 x 4mm<sup>2</sup> kemasan 50 meter (Extrana)', 10, 0, 70.00),
(202, 'NYY36_50_EXT', 'Kabel NYY 3 x 6mm<sup>2</sup> kemasan 50 meter (Extrana)', 10, 0, 70.00),
(203, 'NYY310_50_EXT', 'Kabel NYY 3 x 10mm<sup>2</sup> kemasan 50 meter (Extrana)', 10, 0, 70.00),
(204, 'NYY44_50_EXT', 'Kabel NYY 4 x 4mm<sup>2</sup> kemasan 50 meter (Extrana)', 10, 0, 70.00),
(205, 'NYY46_50_EXT', 'Kabel NYY 4 x 6mm<sup>2</sup> kemasan 50 meter (Extrana)', 10, 0, 70.00),
(206, 'NYY410_50_EXT', 'Kabel NYY 4 x 10mm<sup>2</sup> kemasan 50 meter (Extrana)', 10, 0, 70.00),
(207, 'NYY24_100_EXT', 'Kabel NYY 2 x 4mm<sup>2</sup> kemasan 100 meter (Extrana)', 10, 0, 70.00),
(208, 'NYY26_100_EXT', 'Kabel NYY 2 x 6mm<sup>2</sup> kemasan 100 meter (Extrana)', 10, 0, 70.00),
(209, 'NYY210_100_EXT', 'Kabel NYY 2 x 10mm<sup>2</sup> kemasan 100 meter (Extrana)', 10, 0, 70.00),
(210, 'NYY34_100_EXT', 'Kabel NYY 3 x 4mm<sup>2</sup> kemasan 100 meter (Extrana)', 10, 0, 70.00),
(211, 'NYY36_100_EXT', 'Kabel NYY 3 x 6mm<sup>2</sup> kemasan 100 meter (Extrana)', 10, 0, 70.00),
(212, 'NYY310_100_EXT', 'Kabel NYY 3 x 10mm<sup>2</sup> kemasan 100 meter (Extrana)', 10, 0, 70.00),
(213, 'NYY44_100_EXT', 'Kabel NYY 4 x 4mm<sup>2</sup> kemasan 100 meter (Extrana)', 10, 0, 70.00),
(214, 'NYY46_100_EXT', 'Kabel NYY 4 x 6mm<sup>2</sup> kemasan 100 meter (Extrana)', 10, 0, 70.00),
(215, 'NYY410_100_EXT', 'Kabel NYY 4 x 10mm<sup>2</sup> kemasan 100 meter (Extrana)', 10, 0, 70.00),
(216, 'NYYHY24_50_EXT', 'Kabel NYYHY 2 x 4mm<sup>2</sup> kemasan 50 meter (Extrana)', 17, 0, 70.00),
(217, 'NYYHY26_50_EXT', 'Kabel NYYHY 2 x 6mm<sup>2</sup> kemasan 50 meter (Extrana)', 17, 0, 70.00),
(218, 'NYYHY210_50_EXT', 'Kabel NYYHY 2 x 10mm<sup>2</sup> kemasan 50 meter (Extrana)', 17, 0, 70.00),
(219, 'NYYHY34_50_EXT', 'Kabel NYYHY 3 x 4mm<sup>2</sup> kemasan 50 meter (Extrana)', 17, 0, 70.00),
(220, 'NYYHY36_50_EXT', 'Kabel NYYHY 3 x 6mm<sup>2</sup> kemasan 50 meter (Extrana)', 17, 0, 70.00),
(221, 'NYYHY310_50_EXT', 'Kabel NYYHY 3 x 10mm<sup>2</sup> kemasan 50 meter (Extrana)', 17, 0, 70.00),
(222, 'NYYHY44_50_EXT', 'Kabel NYYHY 4 x 4mm<sup>2</sup> kemasan 50 meter (Extrana)', 17, 0, 70.00),
(223, 'NYYHY46_50_EXT', 'Kabel NYYHY 4 x 6mm<sup>2</sup> kemasan 50 meter (Extrana)', 17, 0, 70.00),
(224, 'NYYHY410_50_EXT', 'Kabel NYYHY 4 x 10mm<sup>2</sup> kemasan 50 meter (Extrana)', 2, 0, 70.00),
(225, 'NYYHY24_100_EXT', 'Kabel NYYHY 2 x 4mm<sup>2</sup> kemasan 100 meter (Extrana)', 2, 0, 70.00),
(226, 'NYYHY26_100_EXT', 'Kabel NYYHY 2 x 6mm<sup>2</sup> kemasan 100 meter (Extrana)', 17, 0, 70.00),
(227, 'NYYHY210_100_EXT', 'Kabel NYYHY 2 x 10mm<sup>2</sup> kemasan 100 meter (Extrana)', 17, 0, 70.00),
(228, 'NYYHY34_100_EXT', 'Kabel NYYHY 3 x 4mm<sup>2</sup> kemasan 100 meter (Extrana)', 17, 0, 70.00),
(229, 'NYYHY36_100_EXT', 'Kabel NYYHY 3 x 6mm<sup>2</sup> kemasan 100 meter (Extrana)', 17, 0, 70.00),
(230, 'NYYHY310_100_EXT', 'Kabel NYYHY 3 x 10mm<sup>2</sup> kemasan 100 meter (Extrana)', 17, 0, 70.00),
(231, 'NYYHY44_100_EXT', 'Kabel NYYHY 4 x 4mm<sup>2</sup> kemasan 100 meter (Extrana)', 17, 0, 70.00),
(232, 'NYYHY46_100_EXT', 'Kabel NYYHY 4 x 6mm<sup>2</sup> kemasan 100 meter (Extrana)', 17, 0, 70.00),
(233, 'NYYHY410_100_EXT', 'Kabel NYYHY 4 x 10mm<sup>2</sup> kemasan 100 meter (Extrana)', 17, 0, 70.00),
(234, 'NYM21_200_EXT', 'Kabel NYM 2 x 1,5mm<sup>2</sup> kemasan 200 meter (Extrana)', 2, 0, 90.00),
(235, 'NYM21_300_EXT', 'Kabel NYM 2 x 1,5mm<sup>2</sup> kemasan 300 meter (Extrana)', 2, 0, 70.00),
(236, 'NYM22_200_EXT', 'Kabel NYM 2 x 2,5mm<sup>2</sup> kemasan 200 meter (Extrana)', 2, 0, 70.00),
(237, 'NYM22_300_EXT', 'Kabel NYM 2 x 2,5mm<sup>2</sup> kemasan 300 meter (Extrana)', 2, 0, 70.00),
(238, 'NYM31_200_EXT', 'Kabel NYM 3 x 1,5mm<sup>2</sup> kemasan 200 meter (Extrana)', 2, 0, 90.00),
(239, 'NYM31_300_EXT', 'Kabel NYM 3 x 1,5mm<sup>2</sup> kemasan 300 meter (Extrana)', 2, 0, 70.00),
(240, 'NYM32_200_EXT', 'Kabel NYM 3 x 2,5mm<sup>2</sup> kemasan 200 meter (Extrana)', 2, 0, 90.00),
(241, 'NYM32_300_EXT', 'Kabel NYM 3 x 2,5mm<sup>2</sup> kemasan 300 meter (Extrana)', 2, 0, 70.00),
(242, 'NYM41_200_EXT', 'Kabel NYM 4 x 1,5mm<sup>2</sup> kemasan 200 meter (Extrana)', 2, 0, 70.00),
(243, 'NYM41_300_EXT', 'Kabel NYM 4 x 1,5mm<sup>2</sup> kemasan 300 meter (Extrana)', 2, 0, 70.00),
(244, 'NYM42_200_EXT', 'Kabel NYM 4 x 2,5mm<sup>2</sup> kemasan 200 meter (Extrana)', 2, 0, 70.00),
(245, 'NYM42_300_EXT', 'Kabel NYM 4 x 2,5mm<sup>2</sup> kemasan 300 meter (Extrana)', 2, 0, 70.00),
(246, 'NYMHY2075_300_EXT', 'Kabel NYMHY 2 x 0,75mm<sup>2</sup> kemasan 300 meter (Extrana)', 13, 0, 70.00),
(247, 'NYYHY2075_300_EXT', 'Kabel NYYHY 2 x 0,75mm<sup>2</sup> kemasan 300 meter (Extrana)', 14, 0, 70.00),
(248, 'NYY46_200_EXT', 'Kabel NYY 4 x 6mm<sup>2</sup> kemasan 200 meter (Extrana)', 10, 1, 70.00),
(249, 'NYM44_50_EXT', 'Kabel NYM 4 x 4mm<sup>2</sup> kemasan 50 meter (Extrana)', 5, 0, 90.00),
(250, 'NYM24_50_EXT', 'Kabel NYM 2 x 4mm<sup>2</sup> kemasan 50 meter (Extrana)', 5, 0, 50.00),
(251, 'NYM26_50_EXT', 'Kabel NYM 2 x 6mm<sup>2</sup> kemasan 50 meter (Extrana)', 5, 0, 50.00),
(252, 'NYM210_50_EXT', 'Kabel NYM 2 x 10mm<sup>2</sup> kemasan 50 meter (Extrana)', 5, 0, 50.00),
(253, 'NYM34_50_EXT', 'Kabel NYM 3 x 4mm<sup>2</sup> kemasan 50 meter (Extrana)', 5, 0, 70.00),
(254, 'NYM36_50_EXT', 'Kabel NYM 3 x 6mm<sup>2</sup> kemasan 50 meter (Extrana)', 5, 0, 50.00),
(255, 'NYM310_50_EXT', 'Kabel NYM 3 x 10mm<sup>2</sup> kemasan 50 meter (Extrana)', 5, 0, 50.00),
(256, 'NYM46_50_EXT', 'Kabel NYM 4 x 6mm<sup>2</sup> kemasan 50 meter (Extrana)', 5, 0, 70.00),
(257, 'NYM410_50_EXT', 'Kabel NYM 4 x 10mm<sup>2</sup> kemasan 50 meter (Extrana)', 5, 0, 50.00),
(258, 'NYM24_100_EXT', 'Kabel NYM 2 x 4mm<sup>2</sup> kemasan 100 meter (Extrana)', 5, 0, 50.00),
(259, 'NYM26_100_EXT', 'Kabel NYM 2 x 6mm<sup>2</sup> kemasan 100 meter (Extrana)', 5, 0, 50.00),
(260, 'NYM210_100_EXT', 'Kabel NYM 2 x 10mm<sup>2</sup> kemasan 100 meter (Extrana)', 5, 0, 50.00),
(261, 'NYM34_100_EXT', 'Kabel NYM 3 x 4mm<sup>2</sup> kemasan 100 meter (Extrana)', 5, 0, 70.00),
(262, 'NYM36_100_EXT', 'Kabel NYM 3 x 6mm<sup>2</sup> kemasan 100 meter (Extrana)', 5, 0, 50.00),
(263, 'NYM310_100_EXT', 'Kabel NYM 3 x 10mm<sup>2</sup> kemasan 100 meter (Extrana)', 2, 0, 50.00),
(264, 'NYM44_100_EXT', 'Kabel NYM 4 x 4mm<sup>2</sup> kemasan 100 meter (Extrana)', 5, 0, 70.00),
(265, 'NYM46_100_EXT', 'Kabel NYM 4 x 6mm<sup>2</sup> kemasan 100 meter (Extrana)', 5, 0, 70.00),
(266, 'NYM410_100_EXT', 'Kabel NYM 4 x 10mm<sup>2</sup> kemasan 100 meter (Extrana)', 5, 0, 50.00);

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
(16, 'Kabel NYAF retail ukuran besar', 'Kabel NYA dengan ukuran per core lebih besar dari 4mm<sup>2</sup> dan lebih kecil dari 10mm<sup>2</sup>', 1),
(17, 'Kabel NYYHY retail ukuran besar', 'Kabel NYYHY dengan ukuran per core lebih besar dari 4mm<sup>2</sup> dan lebih kecil dari 10mm<sup>2</sup>', 1);

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
(1, 'Andrew Bambang Rudianto', 'Direktur Utama PT Duta Sapta Energi', 1);

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

--
-- Dumping data untuk tabel `payable`
--

INSERT INTO `payable` (`id`, `value`, `bank_id`, `date`, `purchase_id`, `other_purchase_id`) VALUES
(3, '150000.00', 19, '2020-12-03', 2, NULL);

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
(1, '2020-11-17', 1, '500000.00', 'Pengiriman barang', 12, NULL, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `plafond_submission`
--

CREATE TABLE `plafond_submission` (
  `id` int(255) NOT NULL,
  `customer_id` int(255) NOT NULL,
  `submitted_plafond` decimal(50,2) DEFAULT NULL,
  `submitted_top` int(11) DEFAULT NULL,
  `submitted_by` int(255) NOT NULL,
  `submitted_date` date NOT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `confirmed_by` int(255) DEFAULT NULL,
  `confirmed_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `plafond_submission`
--

INSERT INTO `plafond_submission` (`id`, `customer_id`, `submitted_plafond`, `submitted_top`, `submitted_by`, `submitted_date`, `is_confirm`, `is_delete`, `confirmed_by`, `confirmed_date`) VALUES
(1, 84, '5000000.00', NULL, 1, '2020-11-24', 1, 0, 1, '2020-11-24');

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
(69, 65, '384000.000'),
(70, 66, '448000.000'),
(71, 67, '520000.000'),
(72, 68, '360000.000'),
(73, 69, '530000.000'),
(74, 70, '774000.000'),
(75, 71, '620000.000'),
(76, 72, '796000.000'),
(77, 73, '1018000.000'),
(78, 74, '768000.000'),
(79, 75, '1040000.000'),
(80, 76, '720000.000'),
(81, 77, '1060000.000'),
(82, 78, '1548000.000'),
(83, 79, '1240000.000'),
(84, 80, '1592000.000'),
(85, 81, '2036000.000'),
(86, 82, '896000.000'),
(87, 83, '1536000.000'),
(88, 84, '2080000.000'),
(89, 85, '1440000.000'),
(90, 86, '2120000.000'),
(91, 87, '3096000.000'),
(92, 88, '2480000.000'),
(93, 89, '3184000.000'),
(94, 90, '4072000.000'),
(95, 91, '1120000.000'),
(96, 92, '1920000.000'),
(97, 93, '2600000.000'),
(98, 94, '1800000.000'),
(99, 95, '2650000.000'),
(100, 96, '3870000.000'),
(101, 97, '3100000.000'),
(102, 98, '3980000.000'),
(103, 99, '5090000.000'),
(104, 100, '2240000.000'),
(105, 101, '3840000.000'),
(106, 102, '5200000.000'),
(107, 103, '3600000.000'),
(108, 104, '5300000.000'),
(109, 105, '7740000.000'),
(110, 106, '6200000.000'),
(111, 107, '7960000.000'),
(112, 108, '10180000.000'),
(113, 109, '224000.000'),
(114, 110, '384000.000'),
(115, 111, '520000.000'),
(116, 112, '360000.000'),
(117, 113, '530000.000'),
(118, 114, '774000.000'),
(119, 115, '620000.000'),
(120, 116, '796000.000'),
(121, 117, '1018000.000'),
(122, 118, '448000.000'),
(123, 119, '768000.000'),
(124, 120, '1040000.000'),
(125, 121, '720000.000'),
(126, 122, '1060000.000'),
(127, 123, '1548000.000'),
(128, 124, '1240000.000'),
(129, 125, '1592000.000'),
(130, 126, '2036000.000'),
(131, 127, '896000.000'),
(132, 128, '1536000.000'),
(133, 129, '2080000.000'),
(134, 130, '1440000.000'),
(135, 131, '2120000.000'),
(136, 132, '3096000.000'),
(137, 133, '2480000.000'),
(138, 134, '3184000.000'),
(139, 135, '4072000.000'),
(140, 136, '1120000.000'),
(141, 137, '1920000.000'),
(142, 138, '2600000.000'),
(143, 139, '1800000.000'),
(144, 140, '2650000.000'),
(145, 141, '3870000.000'),
(146, 142, '3100000.000'),
(147, 143, '3980000.000'),
(148, 144, '5090000.000'),
(149, 145, '2240000.000'),
(150, 146, '3840000.000'),
(151, 147, '5200000.000'),
(152, 148, '3600000.000'),
(153, 149, '5300000.000'),
(154, 150, '7740000.000'),
(155, 151, '6200000.000'),
(156, 152, '7960000.000'),
(157, 153, '10180000.000'),
(158, 154, '215000.000'),
(159, 155, '215000.000'),
(160, 156, '215000.000'),
(161, 157, '215000.000'),
(162, 158, '345000.000'),
(163, 159, '345000.000'),
(164, 160, '345000.000'),
(165, 161, '345000.000'),
(166, 162, '160000.000'),
(167, 163, '160000.000'),
(168, 164, '160000.000'),
(169, 165, '160000.000'),
(170, 166, '270000.000'),
(171, 167, '270000.000'),
(172, 168, '270000.000'),
(173, 169, '270000.000'),
(174, 170, '460000.000'),
(175, 171, '460000.000'),
(176, 172, '460000.000'),
(177, 173, '460000.000'),
(178, 174, '950000.000'),
(179, 175, '950000.000'),
(180, 176, '950000.000'),
(181, 177, '950000.000'),
(182, 178, '1380000.000'),
(183, 179, '1380000.000'),
(184, 180, '1380000.000'),
(185, 181, '1380000.000'),
(186, 182, '2280000.000'),
(187, 183, '2280000.000'),
(188, 184, '2280000.000'),
(189, 185, '2280000.000'),
(190, 186, '1250000.000'),
(191, 187, '1250000.000'),
(192, 188, '1250000.000'),
(193, 189, '1250000.000'),
(194, 190, '1800000.000'),
(195, 191, '1800000.000'),
(196, 192, '1800000.000'),
(197, 193, '1800000.000'),
(198, 194, '3100000.000'),
(199, 195, '3100000.000'),
(200, 196, '3100000.000'),
(201, 197, '3100000.000'),
(202, 198, '1577000.000'),
(203, 199, '2000000.000'),
(204, 200, '3158000.000'),
(205, 201, '2023000.000'),
(206, 202, '2802000.000'),
(207, 203, '4553000.000'),
(208, 204, '2621000.000'),
(209, 205, '3668000.000'),
(210, 206, '5852000.000'),
(211, 207, '3154000.000'),
(212, 208, '4000000.000'),
(213, 209, '6316000.000'),
(214, 210, '4046000.000'),
(215, 211, '5604000.000'),
(216, 212, '9106000.000'),
(217, 213, '5242000.000'),
(218, 214, '7336000.000'),
(219, 215, '11704000.000'),
(220, 216, '1470000.000'),
(221, 217, '2042000.000'),
(222, 218, '3608000.000'),
(223, 219, '1965000.000'),
(224, 220, '3177000.000'),
(225, 221, '5300000.000'),
(226, 222, '2858000.000'),
(227, 223, '3912300.000'),
(228, 224, '6865000.000'),
(229, 225, '2940000.000'),
(230, 226, '4084000.000'),
(231, 227, '7216000.000'),
(232, 228, '3930000.000'),
(233, 229, '6354000.000'),
(234, 230, '10600000.000'),
(235, 231, '5716000.000'),
(236, 232, '7824600.000'),
(237, 233, '13730000.000'),
(238, 234, '1312000.000'),
(239, 235, '1968000.000'),
(240, 236, '1881600.000'),
(241, 237, '2822400.000'),
(242, 238, '1683200.000'),
(243, 239, '2524800.000'),
(244, 240, '2400000.000'),
(245, 241, '3600000.000'),
(246, 242, '2035200.000'),
(247, 243, '3052800.000'),
(248, 244, '3056000.000'),
(249, 245, '4584000.000'),
(250, 246, '1344000.000'),
(251, 247, '1344000.000'),
(252, 248, '14672000.000'),
(253, 249, '2236000.000'),
(254, 250, '1296000.000'),
(255, 251, '1712000.000'),
(256, 252, '3027000.000'),
(257, 253, '1736000.000'),
(258, 254, '2376000.000'),
(259, 255, '4019000.000'),
(260, 256, '3144000.000'),
(261, 257, '5120000.000'),
(262, 258, '2592000.000'),
(263, 259, '3424000.000'),
(264, 260, '6054000.000'),
(265, 261, '3472000.000'),
(266, 262, '4752000.000'),
(267, 263, '8038000.000'),
(268, 264, '4472000.000'),
(269, 265, '6288000.000'),
(270, 266, '10240000.000');

-- --------------------------------------------------------

--
-- Struktur dari tabel `promotion`
--

CREATE TABLE `promotion` (
  `id` int(255) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `note` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_by` int(255) NOT NULL,
  `confirmed_by` int(255) DEFAULT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `promotion`
--

INSERT INTO `promotion` (`id`, `title`, `description`, `note`, `start_date`, `end_date`, `created_by`, `confirmed_by`, `is_confirm`, `is_delete`) VALUES
(2, 'Promo Kemerdekaan', 'Beli 20 ribu gratis 1 ribu cashback', 'Beli 20 ribu gratis 1 ribu cashback', '2020-11-10', '2020-11-15', 1, 1, 1, 0);

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
(1, '2020-11-04', '010.005-80.35135763', '010.005-80.35135763', 1, 1, 0, 1, 0),
(2, '2020-11-18', '010.005-35.13543514', '010.005-35.13543514', 1, 1, 0, 1, 0),
(3, '2020-12-05', '010.003-20.51387652', '010.003-20.51387652', 1, 1, 0, 1, 0);

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
  `type` int(255) DEFAULT NULL,
  `payment` int(3) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `purchase_invoice_other`
--

INSERT INTO `purchase_invoice_other` (`id`, `date`, `tax_document`, `invoice_document`, `supplier_id`, `other_opponent_id`, `value`, `taxing`, `information`, `created_by`, `is_confirm`, `confirmed_by`, `is_delete`, `is_done`, `type`, `payment`) VALUES
(1, '2020-11-13', '', 'PI-CK-SL-ABCDE', 1, NULL, '500000.00', 0, 'Ongkos pengiriman Jakarta - Bandung', 1, 1, 1, 0, 0, 4, 60);

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
(1, 1, '328000.0000', '288640.0000', 100, 50, 0, 1),
(2, 5, '470400.0000', '405155.5200', 29, 29, 1, 2),
(3, 11, '420800.0000', '362435.0400', 42, 20, 0, 2),
(4, 16, '600000.0000', '516780.0000', 33, 10, 0, 2),
(5, 21, '508800.0000', '438229.4400', 2, 2, 1, 2),
(6, 31, '464000.0000', '399643.2000', 3, 3, 1, 2),
(7, 4, '3280000.0000', '2825064.0000', 1, 1, 1, 2),
(8, 64, '224000.0000', '192931.2000', 7, 7, 1, 2),
(9, 26, '764000.0000', '658033.2000', 2, 2, 1, 2),
(10, 65, '384000.0000', '330739.2000', 10, 10, 1, 2),
(11, 68, '360000.0000', '310068.0000', 1, 1, 1, 2),
(12, 111, '520000.0000', '447876.0000', 5, 5, 1, 2),
(13, 66, '448000.0000', '385862.4000', 6, 6, 1, 2),
(14, 155, '215000.0000', '185179.5000', 7, 0, 0, 2),
(15, 109, '224000.0000', '192931.2000', 1, 0, 0, 2),
(16, 154, '215000.0000', '185179.5000', 10, 0, 0, 2),
(17, 158, '345000.0000', '297148.5000', 1, 0, 0, 2),
(18, 186, '1250000.0000', '1076625.0000', 1, 0, 0, 2),
(19, 248, '14672000.0000', '12636993.6000', 1, 0, 0, 2);

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
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `code_purchase_return_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `purchase_return`
--

INSERT INTO `purchase_return` (`id`, `item_id`, `price`, `quantity`, `sent`, `status`, `code_purchase_return_id`) VALUES
(1, 1, '50000.0000', 20, 8, 1, 1),
(2, 1, '200000.0000', 1, 0, 1, 2);

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

--
-- Dumping data untuk tabel `purchase_return_sent`
--

INSERT INTO `purchase_return_sent` (`id`, `purchase_return_id`, `code_purchase_return_sent_id`, `quantity`) VALUES
(1, 1, 1, 10),
(2, 1, 2, 5),
(3, 1, 3, 3);

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

--
-- Dumping data untuk tabel `receivable`
--

INSERT INTO `receivable` (`id`, `bank_id`, `value`, `date`, `invoice_id`) VALUES
(2, 5, '500000.00', '2020-11-24', 1),
(4, 8, '943200.00', '2020-11-25', 1),
(5, 21, '500000.00', '2020-12-05', 5);

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
  `sent` int(255) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `code_sales_order_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sales_order`
--

INSERT INTO `sales_order` (`id`, `price_list_id`, `discount`, `quantity`, `sent`, `status`, `code_sales_order_id`) VALUES
(1, 5, '12.0000', 5, 5, 1, 1),
(2, 69, '12.0000', 2, 2, 1, 2),
(3, 5, '12.0000', 2, 2, 1, 2),
(4, 15, '12.0000', 2, 2, 1, 2),
(5, 72, '12.0000', 1, 1, 1, 2),
(6, 20, '12.0000', 2, 2, 1, 2),
(7, 190, '48.6700', 1, 0, 0, 3),
(8, 69, '12.0000', 1, 0, 0, 4),
(9, 68, '12.0000', 3, 0, 0, 4),
(10, 5, '14.0000', 20, 5, 0, 5),
(11, 5, '100.0000', 1, 1, 1, 5),
(12, 70, '14.0000', 3, 0, 0, 6),
(13, 10, '14.0000', 3, 0, 0, 6),
(14, 70, '14.0000', 3, 0, 0, 7),
(15, 10, '14.0000', 3, 0, 0, 7),
(16, 10, '12.0000', 2, 0, 0, 8),
(17, 5, '14.0000', 5, 0, 0, 9),
(18, 15, '14.0000', 4, 0, 0, 9),
(19, 10, '12.0000', 1, 0, 0, 10),
(20, 5, '12.0000', 1, 0, 0, 10),
(21, 15, '12.0000', 1, 0, 0, 10),
(22, 20, '12.0000', 1, 0, 0, 10),
(23, 68, '12.0000', 1, 0, 0, 10),
(24, 15, '14.0000', 15, 0, 0, 11),
(25, 20, '14.0000', 5, 0, 0, 11),
(26, 10, '14.0000', 5, 0, 0, 12),
(27, 115, '14.0000', 5, 0, 0, 12),
(28, 35, '14.0000', 3, 0, 0, 12),
(29, 30, '14.0000', 2, 0, 0, 12),
(30, 25, '14.0000', 2, 0, 0, 12),
(31, 158, '14.0000', 7, 0, 0, 13),
(32, 159, '14.0000', 7, 0, 0, 13),
(33, 5, '12.0000', 2, 0, 0, 14),
(34, 15, '12.0000', 2, 0, 0, 14),
(35, 10, '12.0000', 1, 0, 0, 15),
(36, 69, '12.0000', 4, 0, 0, 16),
(37, 5, '12.0000', 5, 0, 0, 17),
(38, 68, '12.0000', 3, 0, 0, 17),
(39, 69, '12.0000', 3, 0, 0, 17),
(40, 20, '14.0000', 20, 0, 0, 18),
(41, 15, '14.0000', 18, 0, 0, 18),
(42, 10, '14.0000', 14, 0, 0, 18),
(43, 252, '48.7000', 1, 0, 0, 18),
(44, 20, '12.0000', 5, 0, 0, 19),
(45, 8, '12.0000', 1, 0, 0, 19),
(46, 5, '12.0000', 3, 0, 0, 20),
(47, 162, '12.0000', 1, 0, 0, 21),
(48, 158, '12.0000', 3, 0, 0, 21),
(49, 113, '12.0000', 1, 0, 0, 22),
(50, 15, '14.0000', 20, 0, 0, 23),
(51, 253, '48.6700', 1, 0, 0, 24),
(52, 68, '12.0000', 3, 0, 0, 25),
(53, 5, '12.0000', 3, 0, 0, 25),
(54, 15, '12.0000', 1, 0, 0, 26),
(55, 20, '12.0000', 1, 0, 0, 26),
(56, 5, '14.0000', 26, 0, 0, 27),
(57, 15, '14.0000', 10, 0, 0, 27),
(58, 20, '14.0000', 10, 0, 0, 27),
(59, 5, '100.0000', 1, 0, 0, 27),
(60, 68, '13.7600', 2, 0, 0, 28),
(61, 5, '12.0000', 2, 0, 0, 29),
(62, 68, '12.0000', 2, 0, 0, 30),
(63, 113, '12.0000', 2, 0, 0, 31),
(64, 5, '12.0000', 2, 0, 0, 31),
(65, 15, '14.0000', 2, 0, 0, 32),
(66, 10, '12.0000', 1, 0, 0, 33),
(67, 5, '17.6800', 500, 0, 0, 34),
(68, 20, '17.6800', 100, 0, 0, 34),
(69, 5, '100.0000', 25, 0, 0, 34),
(70, 74, '14.0000', 5, 0, 0, 35),
(71, 118, '14.0000', 5, 0, 0, 35),
(72, 205, '50.4100', 2, 0, 0, 35),
(73, 45, '14.0000', 2, 0, 0, 35),
(74, 5, '12.8800', 5, 5, 1, 36),
(75, 20, '12.0000', 2, 0, 0, 37),
(76, 104, '12.8800', 1, 0, 0, 38),
(77, 5, '12.0000', 3, 0, 0, 39),
(78, 68, '12.0000', 10, 0, 0, 40),
(79, 113, '12.0000', 5, 0, 0, 40),
(80, 104, '12.8800', 1, 0, 0, 41),
(81, 20, '12.0000', 2, 0, 0, 42),
(82, 104, '12.8800', 1, 0, 0, 43),
(83, 25, '14.0000', 3, 0, 0, 44),
(84, 30, '14.0000', 3, 0, 0, 44),
(85, 35, '14.0000', 5, 0, 0, 44),
(86, 40, '14.0000', 2, 0, 0, 44),
(87, 114, '14.0000', 10, 0, 0, 44),
(88, 5, '12.0000', 1, 0, 0, 45),
(89, 68, '12.0000', 1, 0, 0, 46),
(90, 113, '12.0000', 1, 0, 0, 46),
(91, 69, '14.0000', 4, 0, 0, 47),
(92, 114, '14.0000', 2, 0, 0, 47),
(93, 5, '12.0000', 1, 0, 0, 48),
(94, 113, '12.0000', 2, 0, 0, 49),
(95, 257, '50.4100', 1, 0, 0, 50),
(96, 260, '50.4100', 1, 0, 0, 50);

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
  `code_sales_return_id` int(255) NOT NULL,
  `price` decimal(20,3) NOT NULL DEFAULT '0.000'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sales_return`
--

INSERT INTO `sales_return` (`id`, `delivery_order_id`, `quantity`, `received`, `is_done`, `code_sales_return_id`, `price`) VALUES
(1, 2, 2, 0, 0, 8, '250000.000'),
(2, 2, 2, 2, 1, 9, '250000.000');

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
(1, 1, 2, 1),
(2, 2, 2, 1),
(3, 3, 2, 1);

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
(1, 1, 50, 27, 1, NULL, 3, NULL, NULL, '288640.0000'),
(2, 2, 1, 1, NULL, NULL, NULL, NULL, 2, '0.0000'),
(5, 1, 1, 1, NULL, 274, NULL, 3, NULL, '250000.0000'),
(6, 184, 5, 5, NULL, NULL, NULL, NULL, 3, '10000.0000'),
(7, 5, 29, 29, 1, NULL, 4, NULL, NULL, '405155.5200'),
(8, 11, 20, 18, 1, NULL, 5, NULL, NULL, '362435.0400'),
(9, 16, 10, 8, 1, NULL, 6, NULL, NULL, '516780.0000'),
(10, 21, 2, 2, 1, NULL, 7, NULL, NULL, '438229.4400'),
(11, 31, 3, 3, 1, NULL, 8, NULL, NULL, '399643.2000'),
(12, 4, 1, 1, 1, NULL, 9, NULL, NULL, '2825064.0000'),
(13, 64, 7, 7, 1, NULL, 10, NULL, NULL, '192931.2000'),
(14, 26, 2, 2, 1, NULL, 11, NULL, NULL, '658033.2000'),
(15, 65, 10, 8, 1, NULL, 12, NULL, NULL, '330739.2000'),
(16, 68, 1, 0, 1, NULL, 13, NULL, NULL, '310068.0000'),
(17, 111, 5, 5, 1, NULL, 14, NULL, NULL, '447876.0000'),
(18, 66, 6, 6, 1, NULL, 15, NULL, NULL, '385862.4000');

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
(5, 1, 2, NULL, NULL, NULL, 1, NULL),
(6, 1, 5, 274, NULL, 2, NULL, NULL),
(7, 1, 3, NULL, 1, NULL, NULL, 3),
(8, 1, 5, 381, NULL, 3, NULL, NULL),
(9, 15, 2, 421, NULL, 4, NULL, NULL),
(10, 1, 2, 421, NULL, 5, NULL, NULL),
(11, 8, 2, 421, NULL, 6, NULL, NULL),
(12, 16, 1, 421, NULL, 7, NULL, NULL),
(13, 9, 2, 421, NULL, 8, NULL, NULL),
(14, 1, 5, 5, NULL, 9, NULL, NULL),
(15, 1, 1, 5, NULL, 10, NULL, NULL);

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
(1, 'Daniel Tri', 'Jalan Jamuju no. 18, Bandung', '8090175441', 1, '2020-01-01', '27a9dc715a8e1b472ba494313425de62', 'danielrudianto12@gmail.com', '8d1c788a7c51458c1782bf11eeba967d.jpeg', 5),
(2, 'Aston Villa', 'Jalan Padasuka Indah II Block G no. 51', '0631281850', 0, '2020-11-04', '7decec7164f97a251136cc914ba4b7a3', 'yukiaz2306@gmail.com', NULL, 1),
(3, 'Dadan Sutisna', 'Jalan Kp. Kandang Sapi no. 18 RT 001 RW 001', '0083445189', 1, '2020-11-07', 'c7283529c7cf2378f146a6457b71c0aa', 'danz.ezzyy90@gmail.com', NULL, 3),
(4, 'Desta Firman', 'Jalan Saluyu Indah Raya, Komplek Riung Duta I - 25', '8105376223', 1, '2020-11-18', '353092aa8a8548bdd3afaabc9b5a51e2', 'destafirman@gmail.com', NULL, 1),
(5, 'Alman Fauzian', 'Komplek Giri Ciheulang RT 002 RW 015 Kel. Ciheulang, Kec. Ciparay', '00000000', 1, '2020-11-30', '53ad92b19fefd388d7d90c1fe7d6edc1', 'almanfauzian7@gmail.com', NULL, 1);

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
(71, 1, 6),
(72, 1, 1),
(73, 1, 5),
(74, 1, 4),
(75, 1, 3),
(76, 1, 2),
(77, 2, 2),
(79, 4, 2),
(80, 5, 2),
(82, 3, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `visit_list`
--

CREATE TABLE `visit_list` (
  `id` int(255) NOT NULL,
  `customer_id` int(255) NOT NULL,
  `code_visit_list_id` int(255) NOT NULL,
  `note` text NOT NULL,
  `result` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `visit_list`
--

INSERT INTO `visit_list` (`id`, `customer_id`, `code_visit_list_id`, `note`, `result`) VALUES
(22, 288, 4, 'Toko terkunjungi', 1),
(23, 358, 4, 'Toko terkunjungi', 1),
(24, 30, 4, 'Toko terkunjungi', 1),
(25, 119, 4, 'Toko terkunjungi', 1),
(26, 372, 4, 'Toko terkunjungi', 1),
(27, 373, 4, 'Toko terkunjungi', 1),
(28, 136, 4, 'Toko terkunjungi', 1),
(29, 374, 4, 'Toko terkunjungi', 1),
(30, 291, 5, 'Toko terkunjungi.', 1),
(31, 273, 5, 'Masih ada pembayaran yang belum dapat diselesaikan.', 0),
(32, 18, 5, 'Toko terkunjungi. Masih ada produk prima belum habis.', 1),
(33, 375, 5, 'Toko terkunjungi. Masih ada tunggakan pembayaran.', 1),
(34, 276, 5, 'Toko terkunjungi. Stock saat ini masih ada.', 1),
(35, 376, 5, 'Toko terkunjungi. Melakukan order.', 1),
(36, 377, 5, 'Toko terkunjungi. Stock saat ini masih ada.', 1),
(37, 378, 5, 'Toko terkunjungi. Stock saat ini masih ada.', 1),
(38, 303, 5, 'Toko terkunjungi. Melakukan order.', 1),
(39, 71, 6, '', 0),
(40, 380, 6, '', 0),
(41, 55, 6, '', 0),
(42, 311, 6, '', 0),
(43, 379, 6, '', 0),
(44, 6, 6, '', 0),
(45, 403, 6, '', 0),
(46, 405, 7, 'Toko terkunjungi , stok masih cukup', 1),
(47, 380, 7, 'terkunjungi , dan Order', 1),
(48, 55, 7, 'terkunjungi , stok masih  cukup', 1),
(49, 311, 7, 'terkunjungi , dan Order', 1),
(50, 379, 7, 'terkunjungi , dan Order', 1),
(51, 6, 7, 'terkunjungi , dan Order', 1),
(52, 403, 7, 'terkunjungi , . masih blom mau order', 1),
(53, 363, 8, '', 0),
(54, 263, 8, '', 0),
(55, 406, 8, '', 0),
(56, 264, 8, '', 0),
(57, 289, 8, '', 0),
(58, 367, 8, '', 0),
(59, 299, 8, '', 0),
(60, 370, 9, 'terkunjungi dan Order', 1),
(61, 35, 9, 'terkunjungi dan stok masih ada', 1),
(62, 125, 9, 'terkunjungi dan Order', 1),
(63, 94, 9, 'terkunjungi dan Order', 1),
(64, 84, 9, 'terkunjungi dan stok masih ada', 1),
(65, 369, 9, 'terkunjungi dan stok masih ada', 1),
(66, 288, 9, 'terkunjungi dan stok masih ada', 1),
(67, 136, 9, 'terkunjungi dan Order', 1),
(68, 375, 9, 'kunjungan dan tagihan', 1),
(69, 407, 9, 'masih belum mau order ', 1),
(70, 45, 10, '', 0),
(71, 105, 10, '', 0),
(72, 121, 10, '', 0),
(73, 371, 10, '', 0),
(74, 308, 10, '', 0),
(75, 11, 10, '', 0),
(76, 291, 10, '', 0),
(77, 273, 10, '', 0),
(78, 108, 10, '', 0),
(79, 45, 11, 'terkunjungi ,stok masih ada', 1),
(80, 105, 11, 'terkunjungi ,stok masih ada', 1),
(81, 371, 11, 'terkunjungi ,stok masih ada', 1),
(82, 308, 11, 'terkunjungi dan order', 1),
(83, 11, 11, 'terkunjungi ,stok masih cukup', 1),
(84, 291, 11, 'terkunjungi ,dan Stok', 1),
(85, 273, 11, 'terkeunjungi ,, masih ada kendala payment', 1),
(86, 108, 11, 'terkunjungi ,stok masih cukup', 1),
(87, 121, 11, 'terkunjungi ,owner tidak d tempat', 0),
(88, 376, 12, 'terkunjungi dan stok masih ada', 1),
(89, 385, 12, 'terkunjungi dan stok masih ada', 1),
(90, 275, 12, 'terkunjungi dan stok masih ada', 1),
(91, 382, 12, 'terkunjungi dan stok masih ada', 1),
(92, 325, 12, 'terkunjungi dan Order', 1),
(93, 298, 12, 'terkunjungi dan stok masih ada', 1),
(94, 50, 12, 'terkunjungi dan stok masih ada', 1),
(95, 114, 12, 'terkunjungi dan stok masih ada', 1),
(96, 110, 12, 'terkunjungi dan stok masih ada', 1),
(97, 394, 13, 'terkunjungi , stok masih ada', 1),
(98, 337, 13, 'tutup, owner lagi keluar', 0),
(99, 336, 13, 'terkunjungi dan order', 1),
(100, 396, 13, 'terkunjungi , stok masih ada', 1),
(101, 395, 13, 'terkunjungi , dan Order', 1),
(102, 280, 13, 'terkunjungi , stok masih ada', 1),
(103, 34, 13, 'toko Tututp', 0),
(104, 52, 13, 'terkunjungi , stok masih ada', 0),
(105, 320, 13, 'terkunjungi , toko tunggu order pendingan', 1),
(106, 273, 13, 'terkendala payment', 0),
(107, 281, 14, 'terkunjungi , stok masih cukup ', 1),
(108, 338, 14, 'terkunjungi , stok masih cukup ', 1),
(109, 283, 14, 'terkunjungi , stok masih cukup ', 1),
(110, 368, 14, 'terkunjungi dan order', 1),
(111, 54, 14, 'terkunjungi , stok masih cukup ', 1),
(112, 348, 14, 'terkunjungi dan order', 1),
(113, 268, 14, 'terkunjungi dan order', 1),
(114, 269, 14, 'terkunjungi , stok masih cukup ', 1),
(115, 316, 15, 'Toko terkunjungi. Stock masih cukup.', 1),
(116, 48, 15, 'Toko terkunjungi. Stock masih cukup.', 1),
(117, 389, 15, 'Toko terkunjungi. Pemilik toko tidak ditempat.', 1),
(118, 23, 15, 'Toko terkunjungi. Stock masih cukup.', 1),
(119, 37, 15, 'Toko terkunjungi. Orderan menyusul.', 1),
(120, 390, 15, 'Toko terkunjungi. Melakukan order.', 1),
(121, 31, 15, 'Toko terkunjungi. Stock masih cukup.', 1),
(122, 5, 16, 'stok masih ada .. retur nym 3x2,5 ', 1),
(123, 7, 16, 'stok masih ada ', 1),
(124, 19, 16, 'Terkunjungi dan Order ', 1),
(125, 13, 16, 'stok masih cukup', 1),
(126, 92, 16, 'stok masih cukup', 1),
(127, 56, 16, 'Terkunjungi dan Order ', 1),
(128, 32, 16, 'stok masih cukup', 1),
(129, 123, 17, 'Toko terkunjungi. Order kabel.', 1),
(130, 413, 17, 'Toko terkunjungi.', 1),
(131, 94, 18, 'satok masih ada', 0),
(132, 369, 18, 'satok masih ada', 1),
(133, 125, 18, 'Terkunjungi dan Order', 1),
(134, 35, 18, 'satok masih ada', 1),
(135, 370, 18, 'Terkunjungi dan Order', 1),
(136, 84, 18, 'stok masih ada', 1),
(137, 119, 18, 'stok masih ada', 1),
(138, 288, 18, 'stok masih ada', 1),
(139, 291, 19, 'terkunjungi dan Order', 1),
(140, 273, 19, 'masih ada payment belum lunas', 0),
(141, 45, 19, 'stok masih ada', 1),
(142, 308, 19, 'stok masih ada', 1),
(143, 11, 19, 'stok masih ada', 1),
(144, 371, 19, 'terkunjungi dan Order', 1),
(145, 105, 19, 'stok masih ada', 1),
(146, 121, 19, 'stok masih ada', 1),
(147, 267, 20, 'Toko terkunjungi. Order', 1),
(148, 110, 20, 'Toko terkunjungi. Stok masih ada.', 1),
(149, 298, 20, 'Toko terkunjungi. Order', 1),
(150, 382, 20, 'Toko terkunjungi. Order', 1),
(151, 385, 20, 'Toko terkunjungi. Stok masih ada.', 1),
(152, 50, 20, 'Toko terkunjungi. Stok masih ada.', 1),
(153, 262, 20, 'Toko terkunjungi. Stok masih ada.`', 1),
(154, 275, 20, 'Toko terkunjungi. Stok masih ada.', 1),
(155, 114, 20, 'Toko terkunjungi. Stok masih ada.`', 1),
(156, 336, 21, 'Toko terkunjungi. Order kabel', 1),
(157, 280, 21, 'Toko terkunjungi. Owner tidak ada di templat.', 1),
(158, 34, 21, 'Toko terkunjugi. Stock masih cukup.', 1),
(159, 52, 21, 'Toko terkunjugi. Stock masih cukup.', 1),
(160, 337, 21, 'Toko terkunjugi. Order.', 1),
(161, 320, 21, 'Toko terkunjugi. Stock masih cukup. Masih menunggu pending pesanan.', 1),
(162, 394, 21, 'Toko terkunjugi. Stock masih cukup.', 1),
(163, 395, 21, 'Toko terkunjugi. Stock masih cukup.', 1),
(164, 396, 21, 'Toko terkunjugi. Stock masih cukup.', 1),
(165, 63, 22, 'Toko terkunjungi. Stok masih ada.', 1),
(166, 345, 22, 'Toko terkunjungi. Stok masih ada.', 1),
(167, 26, 22, 'Toko terkunjungi. Stok masih ada.', 1),
(168, 61, 22, 'Toko terkunjungi. Order.', 1),
(169, 88, 22, 'Toko terkunjungi. Stok masih ada.', 1),
(170, 346, 22, 'Toko terkunjungi. Stok masih ada.', 1),
(171, 414, 22, 'Toko terkunjungi. Stok masih ada.', 1),
(172, 347, 22, 'Toko terkunjungi. Stok masih ada.', 1),
(173, 281, 23, 'Toko terkunjungi, Stok masih cukup', 1),
(174, 338, 23, 'Toko terkunjungi, Order', 1),
(175, 283, 23, 'Toko terkunjungi, Order', 1),
(176, 348, 23, 'Toko terkunjungi, Stok masih cukup', 1),
(177, 268, 23, 'Tidak terkunjungi, waktu tidak cukup.', 0),
(178, 269, 23, 'Toko Tutup.', 0),
(179, 123, 24, 'Toko terkunjungi ,Order ', 1),
(180, 292, 24, 'Toko terkunjungi ,Order ', 1),
(181, 112, 24, 'Toko terkunjungi ,Order ', 1),
(182, 316, 24, 'toko tutup', 0),
(183, 310, 24, 'toko terkunjungi ,stok masih ada', 1),
(184, 397, 24, 'waktu tidak cukup', 0),
(185, 398, 24, 'Toko terkunjungi ,Order ', 1),
(186, 399, 24, 'toko terkunjungi ,stok masih ada', 1),
(187, 17, 24, 'toko terkunjungi ,stok masih ada', 0),
(188, 85, 25, 'Toko Tutup', 0),
(189, 290, 25, 'Toko terkunjungi . stok masih ada', 1),
(190, 282, 25, 'Toko terkunjungi . stok masih ada', 1),
(191, 301, 25, 'Toko terkunjungi . stok masih ada', 1),
(192, 349, 25, 'Toko terkunjungi . Order', 1),
(193, 113, 25, 'Toko terkunjungi . stok masih ada', 0),
(194, 350, 25, 'Toko terkunjungi . stok masih ada', 1),
(195, 362, 25, 'Toko Tutup', 0),
(196, 351, 25, 'Toko terkunjungi . stok masih ada', 1),
(197, 47, 25, 'Toko terkunjungi . stok masih ada', 1),
(198, 286, 26, 'Toko terkunjungi , stok masih cukup', 1),
(199, 284, 26, 'Toko terkunjungi , Order', 1),
(200, 129, 26, 'Toko terkunjungi , stok masih cukup', 1),
(201, 287, 26, 'Tidak terjunjungi , toko sudah tutup', 0),
(202, 104, 26, 'Tidak terjunjungi  waktu tidak cukup', 1),
(203, 352, 26, 'Toko terkunjungi , stok masih cukup', 1),
(204, 44, 26, 'Toko terkunjungi , stok masih cukup', 1),
(205, 353, 26, 'Toko terkunjungi , stok masih cukup', 1),
(206, 354, 26, 'Toko terkunjungi , stok masih cukup', 1),
(207, 19, 27, 'Toko terkunjungi , Order', 1),
(208, 5, 27, 'Toko terkunjungi , Order', 1),
(209, 7, 27, 'Toko terkunjungi , stok masih cukup', 1),
(210, 95, 27, 'Toko terkunjungi , stok masih cukup', 1),
(211, 33, 27, 'Toko terkunjungi , stok masih cukup', 1),
(212, 401, 27, 'Toko tutup', 0),
(213, 32, 27, 'Toko terkunjungi , stok masih cukup', 0),
(214, 300, 27, 'Toko terkunjungi , stok masih cukup', 1),
(215, 372, 28, '', 0),
(216, 136, 28, '', 0),
(217, 373, 28, '', 0),
(218, 30, 28, '', 0),
(219, 374, 28, '', 0),
(220, 358, 28, '', 0),
(221, 288, 28, '', 0),
(222, 119, 28, '', 0),
(223, 285, 29, '', 0),
(224, 46, 29, '', 0),
(225, 261, 29, '', 0),
(226, 41, 29, '', 0),
(227, 93, 29, '', 0),
(228, 72, 29, '', 0),
(229, 355, 29, '', 0),
(230, 36, 29, '', 0),
(231, 339, 29, '', 0),
(232, 372, 30, 'Toko terkunjungi , stok masih cukup', 1),
(233, 136, 30, 'Toko terkunjungi , Order', 1),
(234, 373, 30, 'Toko terkunjungi , stok masih cukup', 1),
(235, 30, 30, 'Toko terkunjungi , stok masih cukup', 1),
(236, 374, 30, 'Toko terkunjungi , Order', 1),
(237, 358, 30, 'Toko terkunjungi , Order', 1),
(238, 288, 30, 'Toko terkunjungi , stok masih cukup', 1),
(239, 119, 30, 'Toko terkunjungi , stok masih cukup', 1),
(240, 285, 31, 'Toko terkunjungi , Order', 1),
(241, 46, 31, 'Toko terkunjungi , stok masih cukup', 1),
(242, 261, 31, 'Toko terkunjungi , stok masih cukup', 1),
(243, 41, 31, 'Toko terkunjungi , stok masih cukup', 1),
(244, 93, 31, 'Toko terkunjungi , Order', 1),
(245, 72, 31, 'Toko terkunjungi , stok masih cukup', 1),
(246, 355, 31, 'Toko terkunjungi , stok masih cukup', 1),
(247, 291, 32, '', 0),
(248, 273, 32, '', 0),
(249, 18, 32, '', 0),
(250, 375, 32, '', 0),
(251, 276, 32, '', 0),
(252, 376, 32, '', 0),
(253, 377, 32, '', 0),
(254, 378, 32, '', 0),
(255, 303, 32, '', 0),
(256, 291, 33, 'toko terkunjungi, Order', 1),
(257, 18, 33, 'toko terkunjungi, stok masih cukup', 1),
(258, 375, 33, 'toko terkunjungi, stok masih cukup', 1),
(259, 276, 33, 'toko terkunjungi, stok masih cukup', 1),
(260, 45, 33, 'toko terkunjungi, order', 1),
(261, 377, 33, 'toko terkunjungi, stok masih cukup', 1),
(262, 378, 33, 'toko terkunjungi, stok masih cukup', 1),
(263, 303, 33, 'toko terkunjungi, stok masih cukup', 1),
(264, 11, 33, 'toko terkunjungi, stok masih cukup', 1),
(265, 121, 33, 'toko terkunjungi, stok masih cukup', 1),
(266, 133, 34, 'toko terkunjungi, stok masih cukup', 1),
(267, 340, 34, 'toko terkunjungi, stok masih cukup', 1),
(268, 356, 34, 'toko terkunjungi, stok masih cukup', 1),
(269, 357, 34, 'toko terkunjungi, stok masih cukup', 1),
(270, 364, 34, 'toko terkunjungi, stok masih cukup', 1),
(271, 366, 34, 'toko terkunjungi, Order', 1),
(272, 365, 34, 'toko terkunjungi, stok masih cukup', 1),
(273, 15, 34, 'toko tutup', 0),
(274, 117, 34, 'toko terkunjungi, stok masih cukup', 1),
(275, 403, 35, 'Terkunjungi , belum mau ambil  kondisin pasar belom stabil', 1),
(276, 380, 35, 'terkunjungi , stok masih cukup', 1),
(277, 55, 35, 'terkunjungi , stok masih cukup', 1),
(278, 405, 35, 'terkunjungi , stok masih cukup , baru kirim kmren ', 1),
(279, 311, 35, 'terkunjungi , stok masih cukup', 1),
(280, 6, 35, 'terkunjungi , stok masih cukup', 1),
(281, 391, 35, 'terkunjungi , belum berani order', 1),
(282, 379, 35, 'terkunjungi , stok masih cukup', 1),
(283, 263, 36, 'terkunjungi stok masih cukup', 1),
(284, 406, 36, 'terkunjungi stok masih cukup', 1),
(285, 264, 36, 'terkunjungi stok masih cukup', 1),
(286, 367, 36, 'terkunjungi stok masih cukup ', 1),
(287, 423, 36, 'terkunjungi stok merek eterna msih ada', 1),
(288, 422, 36, 'terkunjungi stok merek eterna msih ada', 1),
(289, 381, 37, 'Toko terkunjungi. Melakukan order.', 1),
(290, 383, 37, 'Toko terkunjungi. Stock masih cukup.', 1),
(291, 384, 37, 'Toko terkunjungi. Stock masih cukup.', 1),
(292, 386, 37, 'Toko terkunjungi. Melakukan order.', 1),
(293, 387, 37, 'Toko terkunjungi.', 1),
(294, 392, 37, 'Toko terkunjungi. Melakukan order.', 1),
(295, 388, 37, 'Toko tidak terkunjungi.', 0),
(296, 359, 37, 'Toko terkunjungi. Stock masih cukup.', 0),
(297, 281, 38, 'Toko Terkunjugi. Melakukan order.', 1),
(298, 338, 38, 'Toko Terkunjugi. Stock masih cukup.', 1),
(299, 283, 38, 'Toko Terkunjugi. Stock masih cukup.', 1),
(300, 54, 38, 'Toko Terkunjugi. Stock masih cukup.', 1),
(301, 368, 38, 'Toko Terkunjugi. Stock masih cukup.', 1),
(302, 389, 39, 'Toko terkunjugi. Stock masih cukup.', 1),
(303, 37, 39, 'Toko Terkunjungi. Melakukan order.', 1),
(304, 48, 39, 'Toko terkunjugi. Stock masih cukup.', 1),
(305, 31, 39, 'Toko terkunjugi. Stock masih cukup.', 1),
(306, 23, 39, 'Toko Terkunjungi. Melakukan order.', 1),
(307, 292, 39, 'Toko terkunjugi. Stock masih cukup.', 1),
(308, 123, 39, 'Toko terkunjugi. Stock masih cukup.', 1),
(309, 310, 39, 'Toko terkunjugi. Stock masih cukup.', 1),
(310, 316, 39, 'Toko terkunjugi. Stock masih cukup.', 1),
(311, 399, 39, 'Toko terkunjugi. Stock masih cukup.', 1),
(312, 323, 40, 'Toko terkunjugi.', 1),
(313, 304, 40, 'Toko terkunjugi. Stock masih cukup.', 1),
(314, 134, 40, 'Toko terkunjugi.', 1),
(315, 334, 40, 'Toko terkunjugi. Stock masih cukup.', 1),
(316, 22, 40, 'Toko terkunjugi. Stock masih cukup.', 1),
(317, 21, 40, 'Toko terkunjugi. Stock masih cukup.', 1),
(318, 367, 40, 'Kemarin sudah dikunjungi.', 0),
(319, 426, 41, 'Toko terkunjungi.', 1),
(320, 424, 41, 'Toko terkunjungi.', 1),
(321, 425, 41, 'Toko terkunjungi.', 1),
(322, 428, 42, 'Toko Terkunjungi. ', 1),
(323, 429, 42, 'Toko Terkunjugi. Biasanya menggunakan brand \\\"Cosmic\\\".', 1),
(324, 430, 42, 'Toko Terkunjugi. Biasanya menggunakan brand \\\"Cosmic\\\"', 1),
(325, 5, 43, 'Toko terkunjungi. Stock masih cukup.', 1),
(326, 32, 43, 'Toko terkunjungi. Stock masih cukup.', 1),
(327, 19, 43, 'Toko terkunjungi. Melakukan order.', 1),
(328, 7, 43, 'Toko terkunjungi. Stock masih cukup.', 1),
(329, 13, 43, 'Toko terkunjungi. Stock masih cukup.', 1),
(330, 92, 43, 'Toko terkunjungi. Stock masih cukup.', 1),
(331, 295, 43, 'Toko tidak terkunjugi. Waktu tidak cukup.', 0),
(332, 56, 43, 'Toko terkunjungi. Stock masih cukup.', 1),
(333, 393, 43, 'Toko tidak terkunjugi. Waktu tidak cukup.', 0),
(334, 287, 44, 'Toko terkunjungi. Melakukan order.', 1),
(335, 286, 44, 'Toko terkunjungi. Stock masih cukup.', 1),
(336, 284, 44, 'Toko terkunjungi.', 1),
(337, 129, 44, 'Toko terkunjungi. Stock masih cukup.', 1),
(338, 67, 44, 'Toko terkunjungi.', 1),
(339, 12, 44, 'Toko terkunjungi. Stock masih cukup.', 1),
(340, 104, 44, 'Toko terkunjungi.', 1),
(341, 309, 44, 'Toko terkunjungi.', 1),
(342, 118, 44, 'Toko terkunjungi. Stock masih cukup.', 1),
(343, 431, 44, 'Toko terkunjungi. Melakukan order.', 1),
(344, 300, 45, '', 0),
(345, 288, 45, '', 0),
(346, 400, 45, '', 0),
(347, 373, 45, '', 0),
(348, 30, 45, '', 0),
(349, 358, 45, '', 0),
(350, 125, 45, '', 0),
(351, 374, 45, '', 0),
(352, 35, 45, '', 0),
(353, 369, 45, '', 0),
(354, 18, 46, 'terkunjungi , Order', 1),
(355, 45, 46, 'terkunjungi , stok masih cukup', 1),
(356, 376, 46, 'terkunjungi , stok masih cukup', 1),
(357, 375, 46, 'terkunjungi , stok masih cukup', 1),
(358, 370, 46, 'terkunjungi , stok masih cukup', 1),
(359, 276, 46, 'terkunjungi ,Order', 1),
(360, 300, 46, 'terkunjungi , stok masih cukup', 1),
(361, 377, 46, 'terkunjungi , stok masih cukup', 1),
(362, 288, 47, 'waktu tidak cukup', 0),
(363, 400, 47, 'terkunjungi , stok masih cukup', 1),
(364, 373, 47, 'terkunjungi , stok masih cukup', 1),
(365, 30, 47, 'terkunjungi , stok masih cukup', 1),
(366, 374, 47, 'terkunjungi , stok masih cukup', 1),
(367, 358, 47, 'terkunjungi , stok masih cukup', 1),
(368, 369, 47, 'terkunjungi , stok masih cukup', 1),
(369, 125, 47, 'terkunjungi , stok masih cukup', 1),
(370, 35, 47, 'terkunjungi , stok masih cukup', 1),
(371, 119, 47, 'terkunjungi , stok masih cukup', 1),
(372, 285, 48, 'terkunjungi , stok masih cukup', 1),
(373, 322, 48, 'terkunjungi , stok masih cukup', 1),
(374, 279, 48, 'terkunjungi , stok masih cukup', 1),
(375, 339, 48, 'terkunjungi , stok masih cukup', 1),
(376, 313, 48, 'terkunjungi , stok masih cukup', 1),
(377, 331, 48, 'terkunjungi , stok masih cukup', 1),
(378, 410, 48, 'terkunjungi , stok masih cukup', 1),
(379, 63, 49, '', 0),
(380, 345, 49, '', 0),
(381, 26, 49, '', 0),
(382, 61, 49, '', 0),
(383, 88, 49, '', 0),
(384, 346, 49, '', 0),
(385, 414, 49, '', 0),
(386, 347, 49, '', 0),
(387, 280, 50, 'terkunjungi . stok masih cukup', 1),
(388, 337, 50, 'tidak terkunjungi , wktu tidak cukup', 0),
(389, 383, 50, 'terkunjungi . stok masih cukup', 1),
(390, 388, 50, 'tidak terkunjungi , wktu tidak cukup', 0),
(391, 396, 50, 'tidak terkunjungi , wktu tidak cukup', 1),
(392, 42, 51, 'terkunjungi stok masih cukup', 0),
(393, 101, 51, 'terkunjungi stok masih cukup', 1),
(394, 342, 51, 'terkunjungi stok masih cukup', 1),
(395, 120, 51, 'terkunjungi stok masih cukup', 1),
(396, 341, 51, 'terkunjungi . Order', 1),
(397, 15, 51, 'terkunjungi stok masih cukup', 0),
(398, 343, 51, 'terkunjungi . Order', 1),
(399, 263, 52, 'Toko terkunjugi. Stock masih cukup.', 1),
(400, 406, 52, 'Toko terkunjungi. Masih menunggu pengiriman barang.', 1),
(401, 264, 52, 'Toko terkunjugi. Stock masih cukup.', 1),
(402, 367, 52, 'Toko terkunjungi. Masih menunggu pengiriman barang.', 1),
(403, 423, 52, 'Toko terkunjugi. Stock masih cukup.', 1),
(404, 422, 52, 'Toko terkunjugi. Stock masih cukup.', 1),
(405, 397, 53, 'terkunjungi , pemilik lagi keluar', 1),
(406, 359, 53, 'terkunjungi , stok masih ', 1),
(407, 392, 53, 'terkunjungi , stok masih cukup', 1),
(408, 387, 53, 'terkunjungi , stok masih cukup', 1),
(409, 381, 53, 'terkunjungi , Order', 1),
(410, 386, 53, 'terkunjungi , stok masih cukup', 1),
(411, 63, 54, '', 0),
(412, 345, 54, '', 0),
(413, 61, 54, '', 0),
(414, 88, 54, '', 0),
(415, 346, 54, '', 0),
(416, 347, 54, '', 0),
(417, 332, 54, '', 0),
(418, 26, 54, '', 0),
(419, 414, 54, '', 0),
(420, 281, 55, 'terkunjungi .stok masih cukup', 1),
(421, 338, 55, 'terkunjungi .stok masih cukup', 1),
(422, 348, 55, 'terkunjungi .stok masih cukup', 1),
(423, 268, 55, 'terkunjungi .Order', 1),
(424, 283, 55, 'terkunjungi .stok masih cukup', 1),
(425, 269, 55, 'terkunjungi .minggu depan mau order', 1),
(426, 123, 56, 'terkunjungi minggu depan Order ', 1),
(427, 310, 56, 'terkunjungi .stok masih cukup', 1),
(428, 379, 56, 'terkunjungi .stok masih cukup', 1),
(429, 316, 56, 'terkunjungi .stok masih cukup', 1),
(430, 48, 56, 'terkunjungi .Order', 1),
(431, 399, 56, 'terkunjungi .stok masih cukup', 1),
(432, 292, 56, 'terkunjungi .stok masih cukup', 1),
(433, 350, 57, '', 0),
(434, 349, 57, '', 0),
(435, 282, 57, '', 0),
(436, 415, 57, '', 0),
(437, 290, 57, '', 0),
(438, 113, 57, '', 0),
(439, 301, 57, '', 0),
(440, 122, 57, '', 0),
(441, 19, 58, '', 0),
(442, 400, 58, '', 0),
(443, 5, 58, '', 0),
(444, 32, 58, '', 0),
(445, 311, 58, '', 0),
(446, 401, 58, '', 0),
(447, 405, 58, '', 0),
(448, 55, 58, '', 0),
(449, 436, 59, 'Toko Terkunjungi.', 1),
(450, 435, 59, 'Toko Terkunjungi.', 1),
(451, 437, 59, 'Toko Terkunjungi.', 1),
(452, 440, 60, 'Toko Terkunjungi.', 1),
(453, 19, 61, 'Terkunjungi ,  baru kirim kemarin', 1),
(454, 400, 61, 'terkunjungi . stok cukup , tunggu pendingan nym3x2,5', 1),
(455, 5, 61, 'Tidak Terkunjungi . wktu tidak cukup', 0),
(456, 32, 61, 'terkunjungi . barang baru dateng ', 1),
(457, 311, 61, 'terkunjungi .stok masih cukup', 1),
(458, 401, 61, 'terkunjungi .stok masih cukup , ada payment belum lunas', 1),
(459, 405, 61, 'terkunjungi .stok masih cukup', 1),
(460, 55, 61, 'terkunjungi .Order', 1),
(461, 102, 62, '', 0),
(462, 324, 62, '', 0),
(463, 48, 62, '', 0),
(464, 390, 62, '', 0),
(465, 413, 62, '', 0),
(466, 317, 62, '', 0),
(467, 316, 62, '', 0),
(468, 394, 63, '', 0),
(469, 336, 63, '', 0),
(470, 34, 63, '', 0),
(471, 320, 63, '', 0),
(472, 395, 63, '', 0),
(473, 324, 64, '', 0),
(474, 23, 65, '', 0),
(475, 102, 66, '', 0),
(476, 316, 66, '', 0),
(477, 390, 66, '', 0),
(478, 317, 66, '', 0),
(479, 413, 66, '', 0),
(480, 441, 67, '', 0),
(481, 441, 68, 'Toko Baru dan  Order', 1),
(482, 442, 68, 'Toko Baru . masih belum bisa Order', 1),
(483, 34, 69, 'Terkunjungi, stok tinggal dikit lagi , minggu dpan mau order', 1),
(484, 320, 69, 'terkunjungi , Order', 1),
(485, 336, 69, 'Terkunjungi, stok masih cukup', 1),
(486, 395, 69, 'Terkunjungi, stok masih cukup', 1),
(487, 394, 69, 'Terkunjungi, stok masih cukup', 1),
(488, 102, 70, 'Terkunjungi, stok masih cukup , extrana dari Tang elektrik', 1),
(489, 316, 70, 'Terkunjungi, stok masih cukup', 1),
(490, 390, 70, 'Terkunjungi, stok masih cukup', 1),
(491, 317, 70, 'Terkunjungi, stok masih cukup', 1),
(492, 413, 70, 'terkunjungi ,masih belum bisa ambil , masih ada tunggakan ke suplayer lain', 1),
(493, 136, 71, 'Terkunjungi , stok masih ada , ordeer lanjut via WA', 1),
(494, 298, 71, 'Terkunjungi , stok masih ada ', 1),
(495, 325, 71, 'Terkunjungi , stok masih ada ', 1),
(496, 382, 71, 'Terkunjungi , stok masih ada ', 1),
(497, 112, 71, 'Terkunjungi , stok masih ada ', 1),
(498, 308, 71, 'Terkunjungi , stok masih ada ', 1),
(499, 275, 71, 'Terkunjungi , stok masih ada ', 1),
(500, 385, 71, 'Terkunjungi , stok masih ada ', 1),
(501, 267, 71, 'Terkunjungi , stok masih ada ', 1),
(502, 444, 72, 'Terkunjungi , belum mau order . owner tidak d tempat ', 1),
(503, 445, 72, 'Terkunjungi , belum mau order stok kabel lain masih banyak', 1),
(504, 448, 73, 'terkunjungi , toko baru , belum mau order', 0),
(505, 449, 73, 'terkunjungi , toko baru , belum mau order', 1),
(506, 446, 73, 'terkunjungi , toko baru , belum mau order', 1),
(507, 447, 73, 'terkunjungi , toko baru , belum mau order', 1),
(508, 451, 74, 'Terkunjungi , toko baru belum bisa ambil', 1),
(509, 450, 74, 'Terkunjungi , toko baru belum bisa ambil', 1),
(510, 452, 74, 'Terkunjungi , toko baru belum bisa ambil', 1),
(511, 378, 75, 'Terkunjungi , nanti order via wa ', 1),
(512, 105, 75, 'Tidak terkunjungi , toko tidak ketemu ', 0),
(513, 108, 75, 'Toko tutup', 0),
(514, 370, 75, 'Toko tutup', 0),
(515, 121, 75, 'Terkunjungi , nanti order via wa ', 1),
(516, 276, 75, 'Terkunjungi , nanti order via wa ', 1),
(517, 371, 75, 'Toko tutup', 0),
(518, 306, 75, 'Toko tutup', 0),
(519, 300, 75, 'Toko tutup', 1),
(520, 303, 75, 'Terkunjungi  stok masih cukup', 1),
(521, 377, 75, 'Terkunjungi  stok masih cukup', 1),
(522, 342, 76, '', 0),
(523, 296, 76, '', 0),
(524, 341, 76, '', 0),
(525, 344, 76, '', 0),
(526, 101, 76, '', 0),
(527, 120, 76, '', 0),
(528, 15, 76, '', 0),
(529, 117, 76, '', 0),
(530, 364, 76, '', 0),
(531, 21, 76, '', 0),
(532, 357, 76, '', 0),
(533, 356, 76, '', 0),
(534, 410, 77, 'Terkunjungi  stok masih cukup', 1),
(535, 93, 77, 'Terkunjungi  stok masih cukup', 1),
(536, 261, 77, 'Terkunjungi  , Order', 1),
(537, 331, 77, 'Terkunjungi  stok masih cukup', 1),
(538, 41, 77, 'Terkunjungi  , Order', 1),
(539, 46, 77, 'Terkunjungi  , Order', 1),
(540, 339, 77, 'Terkunjungi  stok masih cukup', 1),
(541, 279, 77, 'Terkunjungi  stok masih cukup', 1),
(542, 293, 77, 'Terkunjungi  stok masih cukup', 1),
(543, 285, 77, 'Terkunjungi  stok masih cukup', 1),
(544, 72, 77, 'Terkunjungi  stok masih cukup', 1),
(545, 443, 77, 'Terkunjungi  stok masih cukup', 1),
(546, 39, 77, 'Terkunjungi  stok masih cukup', 1),
(547, 291, 78, 'Terkunjungi stok masih cukup , minggu depan mau order ', 1),
(548, 455, 79, 'Terkunjungi , Toko baru belum bisa order', 1),
(549, 457, 79, 'Terkunjungi , Toko baru belum bisa order', 1),
(550, 456, 79, 'Terkunjungi , Toko baru belum bisa order', 1),
(551, 458, 79, 'Terkunjungi , Toko baru belum bisa order', 1),
(552, 459, 79, 'Terkunjungi , Toko baru belum bisa order', 1),
(553, 460, 79, 'Terkunjungi , Toko baru belum bisa order', 1),
(554, 461, 80, 'Terkunjungi , toko baru , belum bisa ambil petimbangkan dulu orang pusat ', 1),
(555, 342, 81, 'Terkunjungi , stok masih cukup', 1),
(556, 296, 81, 'Terkunjungi , stok masih cukup', 1),
(557, 120, 81, 'Terkunjungi ,order', 1),
(558, 15, 81, 'Terkunjungi , stok masih cukup', 1),
(559, 117, 81, 'Terkunjungi , stok masih cukup', 1),
(560, 364, 81, 'Terkunjungi , stok masih cukup', 1),
(561, 357, 81, 'Terkunjungi  Stok merek lain masih ada ', 1),
(562, 356, 81, 'Terkunjungi , stok masih cukup', 1),
(563, 21, 81, 'Terkunjungi , stok masih cukup', 1),
(564, 101, 81, 'Terkunjungi , stok masih cukup', 1),
(565, 392, 82, 'Tidak terkunjungi , waktu tidak sempat', 0),
(566, 280, 82, 'Terkunjungi , Order', 1),
(567, 52, 82, 'Tidak terkunjungi , waktu tidak sempat', 1),
(568, 383, 82, 'Terkunjungi , stok masih cukup', 1),
(569, 396, 82, 'Terkunjungi , stok masih cukup', 1),
(570, 388, 82, 'Terkunjungi , Order', 1),
(571, 337, 82, 'Terkunjungi , stok masih cukup', 1),
(572, 50, 83, 'Toko tutup. Buka kembali hari Jumat', 0),
(573, 275, 83, 'Toko terkunjugi. Stock masih cukup.', 1),
(574, 462, 83, 'Toko terkunjugi. Stock masih cukup.', 1),
(575, 463, 83, 'Toko terkunjugi.', 1),
(576, 308, 83, 'Toko terkunjugi. Melakukan order', 1),
(577, 461, 83, 'Toko terkunjungi. Untuk melakukan order, perlu ke Guci Photo dan Elektronik.', 1),
(578, 267, 83, 'Toko terkunjugi. Stock masih cukup.', 1),
(579, 385, 83, 'Toko terkunjugi. Stock masih cukup.', 1),
(580, 281, 84, 'Toko terkunjungi. Melakukan order.', 1),
(581, 368, 84, 'Toko terkunjungi.', 1),
(582, 338, 84, 'Toko terkunjungi. Stock masih cukup.', 1),
(583, 283, 84, 'Toko terkunjungi. Stock masih cukup.', 1),
(584, 54, 84, 'Toko terkunjungi. Stock masih cukup.', 1),
(585, 424, 84, 'Toko terkunjungi. Melakukan order.', 1),
(586, 464, 84, 'Toko terkunjungi.', 1),
(587, 366, 85, 'Terkunjungi , stok masih cukup , baru kirim  minggu kemaren', 1),
(588, 466, 86, 'Terkunjungi , toko baru belum bisa order . ', 1),
(589, 467, 86, 'Terkunjungi , toko baru belum bisa order . ', 1),
(590, 465, 87, 'Terkunjungi , toko baru ,order', 1),
(591, 31, 88, 'Toko terkunjungi. Stock masih cukup.', 1),
(592, 48, 88, 'Toko terkunjungi. Stock masih cukup.', 1),
(593, 390, 88, 'Toko terkunjungi. Stock masih cukup.', 1),
(594, 102, 88, 'Toko terkunjungi. Stock masih cukup.', 1),
(595, 23, 88, 'Toko terkunjungi. Stock masih cukup.', 1),
(596, 317, 88, 'Toko terkunjungi. Stock masih cukup.', 1),
(597, 316, 88, 'Toko terkunjungi. Stock masih cukup.', 1),
(598, 449, 88, 'Toko terkunjungi. Stock masih cukup.', 1),
(599, 394, 89, 'Toko terkunjungi. Stock masih cukup.', 1),
(600, 34, 89, 'Toko terkunjungi.', 1),
(601, 336, 89, 'Toko terkunjungi. Stock masih cukup.', 1),
(602, 395, 89, 'Toko terkunjungi. Stock masih cukup.', 1),
(603, 451, 89, 'Toko terkunjungi. Masih ada tagihan belum terbayar.', 1),
(604, 450, 89, 'Toko terkunjungi. Masih akan dikaji terlebih dahulu.', 1),
(605, 444, 90, '', 0),
(606, 445, 90, '', 0),
(607, 455, 90, '', 0),
(608, 457, 90, '', 0),
(609, 456, 90, '', 0),
(610, 459, 90, '', 0),
(611, 386, 91, '', 0),
(612, 310, 91, '', 0),
(613, 123, 91, '', 0),
(614, 379, 91, '', 0),
(615, 114, 92, '', 0),
(616, 468, 92, '', 0),
(617, 469, 92, '', 0),
(618, 114, 93, 'Toko terkunjugi. Melakukan order.', 1),
(619, 469, 93, 'Toko terkunjugi. Toko masih mempertimbangkan harga untuk melakukan order.', 1),
(620, 468, 93, 'Toko terkunjugi. Melakukan order.', 1),
(621, 426, 94, 'Toko terkunjungi.', 1),
(622, 448, 95, 'Toko terkunjungi. Masih banyak kabel dengan merek lain.', 1),
(623, 470, 96, 'Toko terkunjugi. Barang masih dikaji.', 1);

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
  ADD KEY `internal_account_id` (`internal_account_id`),
  ADD KEY `transaction_reference` (`transaction_reference`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `code_sales_order_id` (`code_sales_order_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `approved_by` (`approved_by`);

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
-- Indeks untuk tabel `customer_area`
--
ALTER TABLE `customer_area`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `customer_sales`
--
ALTER TABLE `customer_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `sales_id` (`sales_id`);

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
  ADD KEY `opponent_id` (`opponent_id`),
  ADD KEY `type` (`type`);

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
-- Indeks untuk tabel `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `confirmed_by` (`confirmed_by`),
  ADD KEY `created_by` (`created_by`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `price_list_id` (`price_list_id`),
  ADD KEY `code_sales_order_id` (`code_sales_order_id`);

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
  ADD KEY `good_receipt_id` (`good_receipt_id`),
  ADD KEY `sales_return_received_id` (`sales_return_received_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indeks untuk tabel `stock_out`
--
ALTER TABLE `stock_out`
  ADD PRIMARY KEY (`id`),
  ADD KEY `in_id` (`in_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `delivery_order_id` (`delivery_order_id`),
  ADD KEY `purchase_return_id` (`purchase_return_id`);

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
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

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
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `benefit`
--
ALTER TABLE `benefit`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `billing`
--
ALTER TABLE `billing`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `code_billing`
--
ALTER TABLE `code_billing`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `code_delivery_order`
--
ALTER TABLE `code_delivery_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `code_event`
--
ALTER TABLE `code_event`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `code_good_receipt`
--
ALTER TABLE `code_good_receipt`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `code_purchase_order`
--
ALTER TABLE `code_purchase_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `code_purchase_return`
--
ALTER TABLE `code_purchase_return`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `code_purchase_return_sent`
--
ALTER TABLE `code_purchase_return_sent`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `code_quotation`
--
ALTER TABLE `code_quotation`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `code_sales_order`
--
ALTER TABLE `code_sales_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `code_sales_order_close_request`
--
ALTER TABLE `code_sales_order_close_request`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `code_sales_return`
--
ALTER TABLE `code_sales_return`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `code_sales_return_received`
--
ALTER TABLE `code_sales_return_received`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `code_visit_list`
--
ALTER TABLE `code_visit_list`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT untuk tabel `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=471;

--
-- AUTO_INCREMENT untuk tabel `customer_area`
--
ALTER TABLE `customer_area`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `customer_sales`
--
ALTER TABLE `customer_sales`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=670;

--
-- AUTO_INCREMENT untuk tabel `customer_target`
--
ALTER TABLE `customer_target`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=341;

--
-- AUTO_INCREMENT untuk tabel `debt_type`
--
ALTER TABLE `debt_type`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `delivery_order`
--
ALTER TABLE `delivery_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `income_class`
--
ALTER TABLE `income_class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `internal_bank_account`
--
ALTER TABLE `internal_bank_account`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `item`
--
ALTER TABLE `item`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=267;

--
-- AUTO_INCREMENT untuk tabel `item_class`
--
ALTER TABLE `item_class`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `petty_cash`
--
ALTER TABLE `petty_cash`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `plafond_submission`
--
ALTER TABLE `plafond_submission`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `price_list`
--
ALTER TABLE `price_list`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=271;

--
-- AUTO_INCREMENT untuk tabel `promotion`
--
ALTER TABLE `promotion`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `purchase_invoice`
--
ALTER TABLE `purchase_invoice`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `purchase_invoice_other`
--
ALTER TABLE `purchase_invoice_other`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `purchase_return`
--
ALTER TABLE `purchase_return`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `purchase_return_sent`
--
ALTER TABLE `purchase_return_sent`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `quotation`
--
ALTER TABLE `quotation`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `receivable`
--
ALTER TABLE `receivable`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `salary_attendance`
--
ALTER TABLE `salary_attendance`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT untuk tabel `sales_return`
--
ALTER TABLE `sales_return`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `sales_return_received`
--
ALTER TABLE `sales_return_received`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `stock_in`
--
ALTER TABLE `stock_in`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `stock_out`
--
ALTER TABLE `stock_out`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `user_authorization`
--
ALTER TABLE `user_authorization`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT untuk tabel `visit_list`
--
ALTER TABLE `visit_list`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=624;

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
  ADD CONSTRAINT `bank_transaction_ibfk_6` FOREIGN KEY (`internal_account_id`) REFERENCES `internal_bank_account` (`id`),
  ADD CONSTRAINT `bank_transaction_ibfk_7` FOREIGN KEY (`transaction_reference`) REFERENCES `bank_transaction` (`id`);

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
-- Ketidakleluasaan untuk tabel `code_sales_order_close_request`
--
ALTER TABLE `code_sales_order_close_request`
  ADD CONSTRAINT `code_sales_order_close_request_ibfk_1` FOREIGN KEY (`code_sales_order_id`) REFERENCES `code_sales_order` (`id`),
  ADD CONSTRAINT `code_sales_order_close_request_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `code_sales_order_close_request_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`);

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
-- Ketidakleluasaan untuk tabel `customer_sales`
--
ALTER TABLE `customer_sales`
  ADD CONSTRAINT `customer_sales_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `customer_sales_ibfk_2` FOREIGN KEY (`sales_id`) REFERENCES `users` (`id`);

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
-- Ketidakleluasaan untuk tabel `good_receipt`
--
ALTER TABLE `good_receipt`
  ADD CONSTRAINT `good_receipt_ibfk_1` FOREIGN KEY (`code_good_receipt_id`) REFERENCES `code_good_receipt` (`id`),
  ADD CONSTRAINT `good_receipt_ibfk_2` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_order` (`id`);

--
-- Ketidakleluasaan untuk tabel `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`opponent_id`) REFERENCES `other_opponent` (`id`),
  ADD CONSTRAINT `invoice_ibfk_3` FOREIGN KEY (`type`) REFERENCES `debt_type` (`id`);

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
-- Ketidakleluasaan untuk tabel `price_list`
--
ALTER TABLE `price_list`
  ADD CONSTRAINT `price_list_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`);

--
-- Ketidakleluasaan untuk tabel `promotion`
--
ALTER TABLE `promotion`
  ADD CONSTRAINT `promotion_ibfk_1` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `promotion_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

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
-- Ketidakleluasaan untuk tabel `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD CONSTRAINT `purchase_order_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`),
  ADD CONSTRAINT `purchase_order_ibfk_2` FOREIGN KEY (`code_purchase_order_id`) REFERENCES `code_purchase_order` (`id`);

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
-- Ketidakleluasaan untuk tabel `sales_order`
--
ALTER TABLE `sales_order`
  ADD CONSTRAINT `sales_order_ibfk_1` FOREIGN KEY (`price_list_id`) REFERENCES `price_list` (`id`),
  ADD CONSTRAINT `sales_order_ibfk_2` FOREIGN KEY (`code_sales_order_id`) REFERENCES `code_sales_order` (`id`);

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
  ADD CONSTRAINT `stock_in_ibfk_4` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`),
  ADD CONSTRAINT `stock_in_ibfk_5` FOREIGN KEY (`sales_return_received_id`) REFERENCES `sales_return_received` (`id`),
  ADD CONSTRAINT `stock_in_ibfk_6` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`);

--
-- Ketidakleluasaan untuk tabel `stock_out`
--
ALTER TABLE `stock_out`
  ADD CONSTRAINT `stock_out_ibfk_1` FOREIGN KEY (`in_id`) REFERENCES `stock_in` (`id`),
  ADD CONSTRAINT `stock_out_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `stock_out_ibfk_3` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`),
  ADD CONSTRAINT `stock_out_ibfk_4` FOREIGN KEY (`delivery_order_id`) REFERENCES `delivery_order` (`id`),
  ADD CONSTRAINT `stock_out_ibfk_5` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_return_sent` (`id`),
  ADD CONSTRAINT `stock_out_ibfk_6` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`);

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
  ADD CONSTRAINT `visit_list_ibfk_1` FOREIGN KEY (`code_visit_list_id`) REFERENCES `code_visit_list` (`id`),
  ADD CONSTRAINT `visit_list_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
