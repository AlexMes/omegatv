-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Сен 04 2020 г., 14:02
-- Версия сервера: 10.1.28-MariaDB
-- Версия PHP: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `omegatv`
--

-- --------------------------------------------------------

--
-- Структура таблицы `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `company`
--

INSERT INTO `company` (`id`, `name`) VALUES
(1, 'company 1'),
(2, 'company 2'),
(3, 'company 3'),
(4, 'company 4'),
(5, 'company 5');

-- --------------------------------------------------------

--
-- Структура таблицы `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `customer`
--

INSERT INTO `customer` (`id`, `name`) VALUES
(1, 'customer 1'),
(2, 'customer 2'),
(3, 'customer 3'),
(4, 'customer 4'),
(5, 'customer 5'),
(6, 'customer 6'),
(7, 'customer 7'),
(8, 'customer 8');

-- --------------------------------------------------------

--
-- Структура таблицы `tariff`
--

CREATE TABLE `tariff` (
  `id` int(11) NOT NULL,
  `id_company` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tariff`
--

INSERT INTO `tariff` (`id`, `id_company`, `name`) VALUES
(1, 1, 'tariff 11'),
(2, 1, 'tariff 12'),
(3, 1, 'tariff 13'),
(4, 2, 'tariff 21'),
(5, 2, 'tariff 22'),
(6, 3, 'tariff 31'),
(7, 3, 'tariff 32'),
(8, 3, 'tariff 33'),
(9, 3, 'tariff 34'),
(10, 4, 'tariff 41'),
(11, 4, 'tariff 42'),
(12, 4, 'tariff 43'),
(13, 4, 'tariff 44'),
(14, 4, 'tariff 45');

-- --------------------------------------------------------

--
-- Структура таблицы `tariff_customer`
--

CREATE TABLE `tariff_customer` (
  `id_customer` int(11) NOT NULL,
  `id_tariff` int(11) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tariff_customer`
--

INSERT INTO `tariff_customer` (`id_customer`, `id_tariff`, `active`) VALUES
(1, 1, 1),
(1, 2, 1),
(2, 1, 1),
(2, 3, 1),
(3, 4, 1),
(3, 5, 0),
(4, 6, 1),
(4, 7, 1),
(5, 8, 0),
(5, 9, 1),
(6, 8, 1),
(6, 9, 1),
(6, 11, 1),
(7, 8, 0),
(7, 10, 0),
(7, 12, 1),
(8, 12, 1),
(8, 13, 1),
(8, 14, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tariff`
--
ALTER TABLE `tariff`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tariff_customer`
--
ALTER TABLE `tariff_customer`
  ADD PRIMARY KEY (`id_customer`,`id_tariff`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `tariff`
--
ALTER TABLE `tariff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
