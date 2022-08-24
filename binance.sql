-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 25 2022 г., 01:37
-- Версия сервера: 10.2.38-MariaDB
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `binance`
--

-- --------------------------------------------------------

--
-- Структура таблицы `lot_size`
--

CREATE TABLE `lot_size` (
  `id` int(11) NOT NULL,
  `symbol` varchar(255) NOT NULL,
  `minQty` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `lot_size`
--

INSERT INTO `lot_size` (`id`, `symbol`, `minQty`) VALUES
(2, 'BTCUSDT', '0.001'),
(3, 'ETHUSDT', '0.001'),
(4, 'BCHUSDT', '0.001'),
(5, 'XRPUSDT', '0.1'),
(6, 'EOSUSDT', '0.1'),
(7, 'LTCUSDT', '0.001'),
(8, 'TRXUSDT', '1'),
(9, 'ETCUSDT', '0.01'),
(10, 'LINKUSDT', '0.01'),
(11, 'XLMUSDT', '1'),
(12, 'ADAUSDT', '1'),
(13, 'XMRUSDT', '0.001'),
(14, 'DASHUSDT', '0.001'),
(15, 'ZECUSDT', '0.001'),
(16, 'XTZUSDT', '0.1'),
(17, 'BNBUSDT', '0.01'),
(18, 'ATOMUSDT', '0.01'),
(19, 'ONTUSDT', '0.1'),
(20, 'IOTAUSDT', '0.1'),
(21, 'BATUSDT', '0.1'),
(22, 'VETUSDT', '1'),
(23, 'NEOUSDT', '0.01'),
(24, 'QTUMUSDT', '0.1'),
(25, 'IOSTUSDT', '1'),
(26, 'THETAUSDT', '0.1'),
(27, 'ALGOUSDT', '0.1'),
(28, 'ZILUSDT', '1'),
(29, 'KNCUSDT', '1'),
(30, 'ZRXUSDT', '0.1'),
(31, 'COMPUSDT', '0.001'),
(32, 'OMGUSDT', '0.1'),
(33, 'DOGEUSDT', '1'),
(34, 'SXPUSDT', '0.1'),
(35, 'KAVAUSDT', '0.1'),
(36, 'BANDUSDT', '0.1'),
(37, 'RLCUSDT', '0.1'),
(38, 'WAVESUSDT', '0.1'),
(39, 'MKRUSDT', '0.001'),
(40, 'SNXUSDT', '0.1'),
(41, 'DOTUSDT', '0.1'),
(42, 'DEFIUSDT', '0.001'),
(43, 'YFIUSDT', '0.001'),
(44, 'BALUSDT', '0.1'),
(45, 'CRVUSDT', '0.1'),
(46, 'TRBUSDT', '0.1'),
(47, 'RUNEUSDT', '1'),
(48, 'SUSHIUSDT', '1'),
(49, 'SRMUSDT', '1'),
(50, 'EGLDUSDT', '0.1'),
(51, 'SOLUSDT', '1'),
(52, 'ICXUSDT', '1'),
(53, 'STORJUSDT', '1'),
(54, 'BLZUSDT', '1'),
(55, 'UNIUSDT', '1'),
(56, 'AVAXUSDT', '1'),
(57, 'FTMUSDT', '1'),
(58, 'HNTUSDT', '1'),
(59, 'ENJUSDT', '1'),
(60, 'FLMUSDT', '1'),
(61, 'TOMOUSDT', '1'),
(62, 'RENUSDT', '1'),
(63, 'KSMUSDT', '0.1'),
(64, 'NEARUSDT', '1'),
(65, 'AAVEUSDT', '0.1'),
(66, 'FILUSDT', '0.1'),
(67, 'RSRUSDT', '1'),
(68, 'LRCUSDT', '1'),
(69, 'MATICUSDT', '1'),
(70, 'OCEANUSDT', '1'),
(71, 'CVCUSDT', '1'),
(72, 'BELUSDT', '1'),
(73, 'CTKUSDT', '1'),
(74, 'AXSUSDT', '1'),
(75, 'ALPHAUSDT', '1'),
(76, 'ZENUSDT', '0.1'),
(77, 'SKLUSDT', '1'),
(78, 'GRTUSDT', '1'),
(79, '1INCHUSDT', '1'),
(80, 'BTCBUSD', '0.001'),
(81, 'CHZUSDT', '1'),
(82, 'SANDUSDT', '1'),
(83, 'ANKRUSDT', '1'),
(84, 'BTSUSDT', '1'),
(85, 'LITUSDT', '0.1'),
(86, 'UNFIUSDT', '0.1'),
(87, 'REEFUSDT', '1'),
(88, 'RVNUSDT', '1'),
(89, 'SFPUSDT', '1'),
(90, 'XEMUSDT', '1'),
(91, 'BTCSTUSDT', '0.1'),
(92, 'COTIUSDT', '1'),
(93, 'CHRUSDT', '1'),
(94, 'MANAUSDT', '1'),
(95, 'ALICEUSDT', '0.1'),
(96, 'HBARUSDT', '1'),
(97, 'ONEUSDT', '1'),
(98, 'LINAUSDT', '1'),
(99, 'STMXUSDT', '1'),
(100, 'DENTUSDT', '1'),
(101, 'CELRUSDT', '1'),
(102, 'HOTUSDT', '1'),
(103, 'MTLUSDT', '1'),
(104, 'OGNUSDT', '1'),
(105, 'NKNUSDT', '1'),
(106, 'SCUSDT', '1'),
(107, 'DGBUSDT', '1'),
(108, '1000SHIBUSDT', '1'),
(109, 'ICPUSDT', '0.01'),
(110, 'BAKEUSDT', '1'),
(111, 'GTCUSDT', '0.1'),
(112, 'ETHBUSD', '0.001'),
(113, 'BTCDOMUSDT', '0.001'),
(114, 'TLMUSDT', '1'),
(115, 'BNBBUSD', '0.01'),
(116, 'ADABUSD', '1'),
(117, 'XRPBUSD', '0.1'),
(118, 'IOTXUSDT', '1'),
(119, 'DOGEBUSD', '1'),
(120, 'AUDIOUSDT', '1'),
(121, 'RAYUSDT', '0.1'),
(122, 'C98USDT', '1'),
(123, 'MASKUSDT', '1'),
(124, 'ATAUSDT', '1'),
(125, 'SOLBUSD', '1'),
(126, 'FTTBUSD', '0.1'),
(127, 'DYDXUSDT', '0.1'),
(128, '1000XECUSDT', '1'),
(129, 'GALAUSDT', '1'),
(130, 'CELOUSDT', '0.1'),
(131, 'ARUSDT', '0.1'),
(132, 'KLAYUSDT', '0.1'),
(133, 'ARPAUSDT', '1'),
(134, 'CTSIUSDT', '1'),
(135, 'LPTUSDT', '0.1'),
(136, 'ENSUSDT', '0.1'),
(137, 'PEOPLEUSDT', '1'),
(138, 'ANTUSDT', '0.1'),
(139, 'ROSEUSDT', '1'),
(140, 'DUSKUSDT', '1'),
(141, 'FLOWUSDT', '0.1'),
(142, 'IMXUSDT', '1'),
(143, 'API3USDT', '0.1'),
(144, 'GMTUSDT', '1'),
(145, 'APEUSDT', '1'),
(146, 'BNXUSDT', '0.1'),
(147, 'WOOUSDT', '1'),
(148, 'FTTUSDT', '0.1'),
(149, 'JASMYUSDT', '1'),
(150, 'DARUSDT', '0.1'),
(151, 'GALUSDT', '1'),
(152, 'AVAXBUSD', '0.1'),
(153, 'NEARBUSD', '0.1'),
(154, 'GMTBUSD', '0.1'),
(155, 'APEBUSD', '0.1'),
(156, 'GALBUSD', '1'),
(157, 'FTMBUSD', '1'),
(158, 'DODOBUSD', '1'),
(159, 'ANCBUSD', '1'),
(160, 'GALABUSD', '1'),
(161, 'TRXBUSD', '1'),
(162, '1000LUNCBUSD', '1'),
(163, 'LUNA2BUSD', '1'),
(164, 'OPUSDT', '0.1'),
(165, 'DOTBUSD', '0.1'),
(166, 'TLMBUSD', '1'),
(167, 'ICPBUSD', '0.1'),
(168, 'BTCUSDT_220930', '0.001'),
(169, 'ETHUSDT_220930', '0.001'),
(170, 'WAVESBUSD', '0.1'),
(171, 'LINKBUSD', '0.1'),
(172, 'SANDBUSD', '0.1'),
(173, 'LTCBUSD', '0.01'),
(174, 'MATICBUSD', '1'),
(175, 'CVXBUSD', '0.1'),
(176, 'FILBUSD', '0.1'),
(177, '1000SHIBBUSD', '1'),
(178, 'LEVERBUSD', '1'),
(179, 'ETCBUSD', '0.1'),
(180, 'LDOBUSD', '0.1'),
(181, 'UNIBUSD', '0.1'),
(182, 'AUCTIONBUSD', '0.1'),
(183, 'INJUSDT', '0.1');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `signal_id` int(11) NOT NULL,
  `checked` varchar(255) NOT NULL DEFAULT '',
  `orderId` varchar(255) NOT NULL,
  `symbol` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `clientOrderId` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `avgPrice` varchar(255) NOT NULL,
  `origQty` varchar(255) NOT NULL,
  `executedQty` varchar(255) NOT NULL,
  `cumQty` varchar(255) DEFAULT NULL,
  `cumQuote` varchar(255) NOT NULL,
  `timeInForce` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `reduceOnly` tinyint(1) DEFAULT NULL,
  `closePosition` tinyint(1) DEFAULT NULL,
  `side` varchar(255) NOT NULL,
  `positionSide` varchar(255) NOT NULL,
  `stopPrice` varchar(255) NOT NULL,
  `workingType` varchar(255) NOT NULL,
  `origType` varchar(255) NOT NULL,
  `updateTime` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `signals`
--

CREATE TABLE `signals` (
  `id` int(11) NOT NULL,
  `symbol` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `in_price` varchar(255) DEFAULT NULL,
  `tp1` varchar(255) NOT NULL,
  `tp2` varchar(255) NOT NULL,
  `tp3` varchar(255) NOT NULL,
  `stop` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'NEW',
  `time` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `signals`
--

INSERT INTO `signals` (`id`, `symbol`, `type`, `in_price`, `tp1`, `tp2`, `tp3`, `stop`, `status`, `time`) VALUES
(1, 'BNBUSDT', 'LONG', NULL, '298', '298.1', '298.2', '296.6', 'NEW', '');

-- --------------------------------------------------------

--
-- Структура таблицы `signals_users`
--

CREATE TABLE `signals_users` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `signal_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `signals_users`
--

INSERT INTO `signals_users` (`id`, `user_id`, `signal_id`, `status`) VALUES
(8, 1, 1, 'CLOSED');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `api_secret` varchar(255) NOT NULL,
  `budget` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `api_key`, `api_secret`, `budget`) VALUES
(1, '', '', 63);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `lot_size`
--
ALTER TABLE `lot_size`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `signals`
--
ALTER TABLE `signals`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `signals_users`
--
ALTER TABLE `signals_users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `lot_size`
--
ALTER TABLE `lot_size`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT для таблицы `signals`
--
ALTER TABLE `signals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `signals_users`
--
ALTER TABLE `signals_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
