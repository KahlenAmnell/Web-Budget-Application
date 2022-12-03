-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 29 Cze 2018, 10:55
-- Wersja serwera: 10.1.30-MariaDB
-- Wersja PHP: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `personal_budget`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `userID` int(11) UNSIGNED NOT NULL,
  `expenseCategoryAssignedToUserID` int(11) UNSIGNED NOT NULL,
  `paymentMethodAssignedToUserID` int(11) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `dateOfExpense` date NOT NULL,
  `expenseComment` varchar(250) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expenses_category_assigned_to_users`
--

CREATE TABLE `expense_Category_Assigned_To_User_ID` (
  `id` int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `userID` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expenses_category_default`
--

CREATE TABLE `expenses_Category_Default` (
  `id` int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `expenses_category_default`
--

INSERT INTO `expenses_Category_Default` (`id`, `name`) VALUES
(1, 'Jedzenie'),
(2, 'Mieszkanie'),
(3, 'Transport'),
(4, 'Telekomunikacja'),
(5, 'Opieka zdrowotna'),
(6, 'Ubrania'),
(7, 'Higiena'),
(8, 'Dzieci'),
(9, 'Rozrywka'),
(10, 'Wycieczki'),
(11, 'Szkolenia'),
(12, 'Książki'),
(13, 'Oszczędności'),
(14, 'Na emeryturę'),
(15, 'Spłata długów'),
(16, 'Darowizna'),
(17, 'Inne wydatki');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `incomes`
--

CREATE TABLE `incomes` (
  `id` int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `userID` int(11) UNSIGNED NOT NULL,
  `incomeCategoryAssignedToUserId` int(11) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `dateOfIncome` date NOT NULL,
  `incomeComment` varchar(250) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `incomes_category_assigned_to_users`
--

CREATE TABLE `incomes_Category_Assigned_To_Users` (
  `id` int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `userID` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `incomes_category_default`
--

CREATE TABLE `incomes_Category_Default` (
  `id` int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `incomes_category_default`
--

INSERT INTO `incomes_Category_Default` (`id`, `name`) VALUES
(1, 'Wynagrodzenie'),
(2, 'Odsetki bankowe'),
(3, 'Sprzedaż na allegro'),
(4, 'Inne');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `payment_methods_assigned_to_users`
--

CREATE TABLE `payment_Methods_Assigned_To_Users` (
  `id` int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `payment_methods_default`
--

CREATE TABLE `payment_Methods_Default` (
  `id` int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `payment_methods_default`
--

INSERT INTO `payment_Methods_Default` (`id`, `name`) VALUES
(1, 'Gotówka'),
(2, 'Karta debetowa'),
(3, 'Karta kredytowa');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_polish_ci NOT NULL UNIQUE,
  `password_reset_hash` varchar(64) COLLATE utf8_polish_ci NULL DEFAULT NULL UNIQUE,
  `password_reset_expires_at` datetime NULL DEFAULT NULL,
  `activation_hash` varchar(64) COLLATE utf8_polish_ci NULL DEFAULT NULL UNIQUE,
  `is_active` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `remembered_login`
--

CREATE TABLE `remembered_login` (
  `token_hash` varchar(64) COLLATE utf8_polish_ci NOT NULL PRIMARY KEY,
  `user_id` int(11) UNSIGNED NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Zmiana relacji w tabeli 'remembered_login'
--

ALTER TABLE `remembered_login` 
ADD CONSTRAINT `remembered_logins_ibfk_1` FOREIGN KEY (`user_id`) 
REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; 

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
