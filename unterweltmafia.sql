-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 08. Feb 2021 um 08:58
-- Server-Version: 10.4.7-MariaDB-log
-- PHP-Version: 7.0.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `web0014_mafia`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `admin_config`
--

CREATE TABLE `admin_config` (
  `config_name` varchar(128) DEFAULT 'undefined_name',
  `config_value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Daten für Tabelle `admin_config`
--

INSERT INTO `admin_config` (`config_name`, `config_value`) VALUES
('bulletBuy_times', '04:00-06:00|10:00-12:00|16:00-18:00|22:00-23:59:59'),
('car_race_closed', 'false'),
('lottery_closed', 'false'),
('killtime_start', '20:00:00'),
('killtime_stop', '20:30:00'),
('bonus_credite', '10'),
('game_version', '1.1.1'),
('game_round', '1'),
('asasin_rank', '3'),
('fortumo_id', '0'),
('fortumo_secret', '0'),
('game_name', 'DeinMafiaSpiel'),
('contact_email', 'mail@mail.com'),
('paypal_email', 'mail@mail.com'),
('game_url', 'https://DeineDomain.com'),
('payeer_key', ''),
('payeer_secret', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `antibot_sessions`
--

CREATE TABLE `antibot_sessions` (
  `id` int(11) NOT NULL,
  `playerid` bigint(11) DEFAULT 0,
  `script_name` varchar(600) DEFAULT '',
  `images_data` varchar(2000) DEFAULT '',
  `correct_imageHash` varchar(400) DEFAULT '',
  `result` varchar(32) DEFAULT 'unknown',
  `active` int(1) DEFAULT 1,
  `added` int(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `antibot_sessions`
--

INSERT INTO `antibot_sessions` (`id`, `playerid`, `script_name`, `images_data`, `correct_imageHash`, `result`, `active`, `added`) VALUES
(41, 6, 'lupte', '{\"text\":\"a Pen\",\"images\":[{\"hash\":\"885d85133622d43109b810d31a26438e3840544a\",\"file\":\"ABImage_PC_3\"},{\"hash\":\"2473073cd59ec1f0277a912ad0c3675d61ce12de\",\"file\":\"ABImage_clock_5\"},{\"hash\":\"35b8c5e1635b8e87c0a85bb127b09ffd182a33fa\",\"file\":\"ABImage_car_2\"},{\"hash\":\"2e529268d451337417e0ca581fd6b84c4bb21b53\",\"file\":\"ABImage_TV_4\"},{\"hash\":\"ecfe757eec07c7593a50e4061fbb55f4f6b7e411\",\"file\":\"ABImage_pen_5\"},{\"hash\":\"3824049c1fabc9f82e36cf9afba2ca42506baedf\",\"file\":\"ABImage_keyboard_3\"}]}', 'ecfe757eec07c7593a50e4061fbb55f4f6b7e411', 'unknown', 1, 1561888841);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `auctions`
--

CREATE TABLE `auctions` (
  `id` int(11) NOT NULL,
  `added_by` int(11) DEFAULT 0,
  `added_by_ip` varchar(64) DEFAULT '',
  `object_type` varchar(128) DEFAULT 'unknown',
  `object_id` int(11) DEFAULT 0,
  `bids` varchar(20000) DEFAULT 'a:0:{}',
  `bid_start` bigint(11) DEFAULT 0,
  `smallest_increase` bigint(11) DEFAULT 0,
  `payment_method` varchar(128) DEFAULT 'cash',
  `end_time` int(64) DEFAULT 0,
  `added_time` int(64) DEFAULT 0,
  `active` int(1) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bank_clients`
--

CREATE TABLE `bank_clients` (
  `id` int(11) NOT NULL,
  `b_id` int(11) DEFAULT 0,
  `playerid` int(11) DEFAULT 0,
  `interests_num` int(11) DEFAULT 0,
  `interests_cash` bigint(11) DEFAULT 0,
  `transfers` varchar(600) DEFAULT 'a:0:{}',
  `used` bigint(11) DEFAULT 0,
  `registred` int(64) DEFAULT 0,
  `active` int(1) DEFAULT 1,
  `accepted` int(1) DEFAULT 1,
  `reg_price` int(11) DEFAULT 0,
  `pass` varchar(2000) DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `bank_clients`
--

INSERT INTO `bank_clients` (`id`, `b_id`, `playerid`, `interests_num`, `interests_cash`, `transfers`, `used`, `registred`, `active`, `accepted`, `reg_price`, `pass`) VALUES
(1, 1, 3, 0, 0, 'a:0:{}', 0, 1561641936, 0, 1, 0, 'a75231379b47d345bc88ff24ee1e0ae1582f3d7e72d066b98a1fa626feb9286480e179aa210a48413edb8be6073253860e5151a0c48995bce58a714657bb0ed5'),
(2, 1, 1, 0, 0, 'a:0:{}', 0, 1561641982, 1, 1, 0, 'd5464a745501a3fe1036cf4090a74612204bc97991e6e83806ddb80d48ea4b1ac27de4d9831f27718e435bb6aab05b4027140c01b9d06e34a737465ad5a117ff'),
(3, 1, 3, 0, 0, 'a:1:{s:5:\"money\";i:2;}', 5101, 1561644545, 1, 1, 5000, '183975cc3e9656d40d8b65da851d7fd1b9220cfa760e2845eda40f7a27348b67770ca3a8a4362abf2e04fadb2e8b176e9e1e3f85b32ea8425e44a4deb5e36650');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bank_interests`
--

CREATE TABLE `bank_interests` (
  `id` int(11) NOT NULL,
  `b_id` int(11) DEFAULT 0,
  `to_user` int(11) DEFAULT 0,
  `to_player` int(11) DEFAULT 0,
  `money_to_player` int(11) DEFAULT 0,
  `percent` int(64) DEFAULT 0,
  `date` int(64) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bank_transfers`
--

CREATE TABLE `bank_transfers` (
  `id` int(11) NOT NULL,
  `b_id` int(11) DEFAULT 0,
  `from_ip` varchar(64) DEFAULT NULL,
  `from_player` int(11) DEFAULT 0,
  `from_user` int(11) DEFAULT 0,
  `to_player` int(11) DEFAULT 0,
  `to_user` int(11) DEFAULT 0,
  `type` varchar(120) DEFAULT 'money',
  `sum` bigint(11) DEFAULT 0,
  `to_bank` bigint(11) DEFAULT 0,
  `sent` int(64) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `bank_transfers`
--

INSERT INTO `bank_transfers` (`id`, `b_id`, `from_ip`, `from_player`, `from_user`, `to_player`, `to_user`, `type`, `sum`, `to_bank`, `sent`) VALUES
(1, 1, '188.210.62.36', 3, 3, 1, 1, 'money', 50, 1, 1561645308),
(2, 1, '188.210.62.36', 3, 3, 1, 1, 'money', 5000, 100, 1561645347);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `blackjack`
--

CREATE TABLE `blackjack` (
  `id` int(11) NOT NULL,
  `dealer` int(11) DEFAULT 0,
  `opponent` int(11) DEFAULT 0,
  `dealer_bet` bigint(11) DEFAULT 0,
  `opponent_bet` bigint(11) DEFAULT 0,
  `bet_type` int(11) NOT NULL DEFAULT 0,
  `dealer_state` int(1) DEFAULT 0,
  `opponent_state` int(1) DEFAULT 0,
  `active` int(1) DEFAULT 1,
  `result` varchar(64) DEFAULT 'unknown',
  `started` int(128) DEFAULT 0,
  `dealer_cards` text DEFAULT NULL,
  `opponent_cards` text DEFAULT NULL,
  `cardDeck` text DEFAULT NULL,
  `expired` int(1) DEFAULT 0,
  `create_ip` varchar(128) DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `blackjack`
--

INSERT INTO `blackjack` (`id`, `dealer`, `opponent`, `dealer_bet`, `opponent_bet`, `bet_type`, `dealer_state`, `opponent_state`, `active`, `result`, `started`, `dealer_cards`, `opponent_cards`, `cardDeck`, `expired`, `create_ip`) VALUES
(17, 20, 0, 20000, 0, 0, 2, 0, 1, 'unknown', 1597948485, 'a:4:{i:0;a:2:{i:0;a:4:{i:0;i:42;i:1;s:6:\"spades\";i:2;i:3;i:3;s:1:\"3\";}i:1;i:3;}i:1;a:2:{i:0;a:4:{i:0;i:46;i:1;s:6:\"spades\";i:2;i:7;i:3;s:1:\"7\";}i:1;i:7;}i:2;a:2:{i:0;a:4:{i:0;i:32;i:1;s:6:\"hearts\";i:2;i:6;i:3;s:1:\"6\";}i:1;i:6;}i:3;a:2:{i:0;a:4:{i:0;i:23;i:1;s:8:\"diamonds\";i:2;i:10;i:3;s:2:\"10\";}i:1;i:10;}}', 'a:0:{}', 'a:48:{i:0;a:4:{i:0;i:1;i:1;s:5:\"clubs\";i:2;s:3:\"ace\";i:3;s:1:\"a\";}i:1;a:4:{i:0;i:2;i:1;s:5:\"clubs\";i:2;i:2;i:3;s:1:\"2\";}i:2;a:4:{i:0;i:3;i:1;s:5:\"clubs\";i:2;i:3;i:3;s:1:\"3\";}i:3;a:4:{i:0;i:4;i:1;s:5:\"clubs\";i:2;i:4;i:3;s:1:\"4\";}i:4;a:4:{i:0;i:5;i:1;s:5:\"clubs\";i:2;i:5;i:3;s:1:\"5\";}i:5;a:4:{i:0;i:6;i:1;s:5:\"clubs\";i:2;i:6;i:3;s:1:\"6\";}i:6;a:4:{i:0;i:7;i:1;s:5:\"clubs\";i:2;i:7;i:3;s:1:\"7\";}i:7;a:4:{i:0;i:8;i:1;s:5:\"clubs\";i:2;i:8;i:3;s:1:\"8\";}i:8;a:4:{i:0;i:9;i:1;s:5:\"clubs\";i:2;i:9;i:3;s:1:\"9\";}i:9;a:4:{i:0;i:10;i:1;s:5:\"clubs\";i:2;i:10;i:3;s:2:\"10\";}i:10;a:4:{i:0;i:11;i:1;s:5:\"clubs\";i:2;s:4:\"jack\";i:3;s:1:\"j\";}i:11;a:4:{i:0;i:12;i:1;s:5:\"clubs\";i:2;s:5:\"queen\";i:3;s:1:\"q\";}i:12;a:4:{i:0;i:13;i:1;s:5:\"clubs\";i:2;s:4:\"king\";i:3;s:1:\"k\";}i:13;a:4:{i:0;i:14;i:1;s:8:\"diamonds\";i:2;s:3:\"ace\";i:3;s:1:\"a\";}i:14;a:4:{i:0;i:15;i:1;s:8:\"diamonds\";i:2;i:2;i:3;s:1:\"2\";}i:15;a:4:{i:0;i:16;i:1;s:8:\"diamonds\";i:2;i:3;i:3;s:1:\"3\";}i:16;a:4:{i:0;i:17;i:1;s:8:\"diamonds\";i:2;i:4;i:3;s:1:\"4\";}i:17;a:4:{i:0;i:18;i:1;s:8:\"diamonds\";i:2;i:5;i:3;s:1:\"5\";}i:18;a:4:{i:0;i:19;i:1;s:8:\"diamonds\";i:2;i:6;i:3;s:1:\"6\";}i:19;a:4:{i:0;i:20;i:1;s:8:\"diamonds\";i:2;i:7;i:3;s:1:\"7\";}i:20;a:4:{i:0;i:21;i:1;s:8:\"diamonds\";i:2;i:8;i:3;s:1:\"8\";}i:21;a:4:{i:0;i:22;i:1;s:8:\"diamonds\";i:2;i:9;i:3;s:1:\"9\";}i:23;a:4:{i:0;i:24;i:1;s:8:\"diamonds\";i:2;s:4:\"jack\";i:3;s:1:\"j\";}i:24;a:4:{i:0;i:25;i:1;s:8:\"diamonds\";i:2;s:5:\"queen\";i:3;s:1:\"q\";}i:25;a:4:{i:0;i:26;i:1;s:8:\"diamonds\";i:2;s:4:\"king\";i:3;s:1:\"k\";}i:26;a:4:{i:0;i:27;i:1;s:6:\"hearts\";i:2;s:3:\"ace\";i:3;s:1:\"a\";}i:27;a:4:{i:0;i:28;i:1;s:6:\"hearts\";i:2;i:2;i:3;s:1:\"2\";}i:28;a:4:{i:0;i:29;i:1;s:6:\"hearts\";i:2;i:3;i:3;s:1:\"3\";}i:29;a:4:{i:0;i:30;i:1;s:6:\"hearts\";i:2;i:4;i:3;s:1:\"4\";}i:30;a:4:{i:0;i:31;i:1;s:6:\"hearts\";i:2;i:5;i:3;s:1:\"5\";}i:32;a:4:{i:0;i:33;i:1;s:6:\"hearts\";i:2;i:7;i:3;s:1:\"7\";}i:33;a:4:{i:0;i:34;i:1;s:6:\"hearts\";i:2;i:8;i:3;s:1:\"8\";}i:34;a:4:{i:0;i:35;i:1;s:6:\"hearts\";i:2;i:9;i:3;s:1:\"9\";}i:35;a:4:{i:0;i:36;i:1;s:6:\"hearts\";i:2;i:10;i:3;s:2:\"10\";}i:36;a:4:{i:0;i:37;i:1;s:6:\"hearts\";i:2;s:4:\"jack\";i:3;s:1:\"j\";}i:37;a:4:{i:0;i:38;i:1;s:6:\"hearts\";i:2;s:5:\"queen\";i:3;s:1:\"q\";}i:38;a:4:{i:0;i:39;i:1;s:6:\"hearts\";i:2;s:4:\"king\";i:3;s:1:\"k\";}i:39;a:4:{i:0;i:40;i:1;s:6:\"spades\";i:2;s:3:\"ace\";i:3;s:1:\"a\";}i:40;a:4:{i:0;i:41;i:1;s:6:\"spades\";i:2;i:2;i:3;s:1:\"2\";}i:42;a:4:{i:0;i:43;i:1;s:6:\"spades\";i:2;i:4;i:3;s:1:\"4\";}i:43;a:4:{i:0;i:44;i:1;s:6:\"spades\";i:2;i:5;i:3;s:1:\"5\";}i:44;a:4:{i:0;i:45;i:1;s:6:\"spades\";i:2;i:6;i:3;s:1:\"6\";}i:46;a:4:{i:0;i:47;i:1;s:6:\"spades\";i:2;i:8;i:3;s:1:\"8\";}i:47;a:4:{i:0;i:48;i:1;s:6:\"spades\";i:2;i:9;i:3;s:1:\"9\";}i:48;a:4:{i:0;i:49;i:1;s:6:\"spades\";i:2;i:10;i:3;s:2:\"10\";}i:49;a:4:{i:0;i:50;i:1;s:6:\"spades\";i:2;s:4:\"jack\";i:3;s:1:\"j\";}i:50;a:4:{i:0;i:51;i:1;s:6:\"spades\";i:2;s:5:\"queen\";i:3;s:1:\"q\";}i:51;a:4:{i:0;i:52;i:1;s:6:\"spades\";i:2;s:4:\"king\";i:3;s:1:\"k\";}}', 0, '89.27.164.188');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `blackmail`
--

CREATE TABLE `blackmail` (
  `id` int(11) NOT NULL,
  `playerid` int(11) NOT NULL DEFAULT 0,
  `last` int(8) DEFAULT 0,
  `last_time` int(64) DEFAULT 0,
  `latency` int(64) DEFAULT 0,
  `stats` varchar(4000) DEFAULT 'a:0:{}',
  `blackmails_made` varchar(4000) DEFAULT 'a:0:{}',
  `blackmails_got` varchar(4000) DEFAULT 'a:0:{}'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `blackmail`
--

INSERT INTO `blackmail` (`id`, `playerid`, `last`, `last_time`, `latency`, `stats`, `blackmails_made`, `blackmails_got`) VALUES
(1, 3, 3, 1575107156, 359, 'a:3:{s:12:\"total_failed\";i:5;s:13:\"total_success\";i:1;s:8:\"cash_got\";i:10713;}', 'a:1:{i:0;a:3:{s:6:\"player\";s:1:\"6\";s:5:\"money\";i:10713;s:4:\"date\";i:1575107156;}}', 'a:0:{}'),
(2, 1, 0, 0, 0, 'a:0:{}', 'a:0:{}', 'a:0:{}'),
(3, 14, 3, 1596022799, 373, 'a:3:{s:12:\"total_failed\";i:17;s:13:\"total_success\";i:1;s:8:\"cash_got\";i:7647;}', 'a:1:{i:0;a:3:{s:6:\"player\";s:1:\"6\";s:5:\"money\";i:7647;s:4:\"date\";i:1596022799;}}', 'a:0:{}');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `brekk`
--

CREATE TABLE `brekk` (
  `id` int(11) NOT NULL,
  `playerid` int(11) NOT NULL DEFAULT 0,
  `last` varchar(1000) DEFAULT '',
  `latency` int(11) DEFAULT 0,
  `chances` varchar(2000) DEFAULT 'a:0:{}',
  `stats` varchar(2000) DEFAULT 'a:0:{}'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `brekk`
--

INSERT INTO `brekk` (`id`, `playerid`, `last`, `latency`, `chances`, `stats`) VALUES
(1, 1, '1588769579,Berlin,11', 150, 'a:3:{i:15;i:270;i:16;i:222;i:11;i:270;}', 'a:8:{s:6:\"failed\";i:33;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:2:{s:9:\"Stockholm\";i:4;s:6:\"Berlin\";i:76;}s:11:\"cash_earned\";i:31353;s:17:\"rankpoints_earned\";i:418;s:19:\"wanted_level_earned\";i:1441;s:11:\"health_lost\";i:30;s:10:\"sucessfull\";i:47;}'),
(2, 3, '1575107143,Berlin,11', 150, 'a:5:{i:15;i:270;i:16;i:147;i:11;i:270;i:12;i:231;i:18;i:12;}', 'a:8:{s:6:\"failed\";i:62;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:2:{s:9:\"Stockholm\";i:15;s:6:\"Berlin\";i:195;}s:11:\"cash_earned\";i:237695;s:17:\"rankpoints_earned\";i:1720;s:19:\"wanted_level_earned\";i:4394;s:11:\"health_lost\";i:107;s:10:\"sucessfull\";i:148;}'),
(3, 5, '', 0, 'a:0:{}', 'a:6:{s:6:\"failed\";i:0;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:0:{}s:11:\"cash_earned\";i:0;s:17:\"rankpoints_earned\";i:0;s:19:\"wanted_level_earned\";i:0;}'),
(4, 6, '1563802084,Kiev,13', 150, 'a:3:{i:15;i:270;i:16;i:20;i:13;i:10;}', 'a:8:{s:6:\"failed\";i:17;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:1:{s:4:\"Kiev\";i:57;}s:11:\"cash_earned\";i:16425;s:17:\"rankpoints_earned\";i:246;s:19:\"wanted_level_earned\";i:1005;s:11:\"health_lost\";i:9;s:10:\"sucessfull\";i:40;}'),
(5, 7, '', 0, 'a:0:{}', 'a:6:{s:6:\"failed\";i:0;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:0:{}s:11:\"cash_earned\";i:0;s:17:\"rankpoints_earned\";i:0;s:19:\"wanted_level_earned\";i:0;}'),
(6, 8, '', 0, 'a:0:{}', 'a:6:{s:6:\"failed\";i:0;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:0:{}s:11:\"cash_earned\";i:0;s:17:\"rankpoints_earned\";i:0;s:19:\"wanted_level_earned\";i:0;}'),
(7, 9, '1580239967,Kiev,15', 170, 'a:2:{i:16;i:14;i:15;i:17;}', 'a:7:{s:6:\"failed\";i:2;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:1:{s:4:\"Kiev\";i:2;}s:11:\"cash_earned\";i:0;s:17:\"rankpoints_earned\";i:6;s:19:\"wanted_level_earned\";i:27;s:11:\"health_lost\";i:1;}'),
(8, 10, '1587805199,Stockholm,15', 170, 'a:1:{i:15;i:19;}', 'a:7:{s:6:\"failed\";i:1;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:1:{s:9:\"Stockholm\";i:1;}s:11:\"cash_earned\";i:0;s:17:\"rankpoints_earned\";i:3;s:19:\"wanted_level_earned\";i:15;s:11:\"health_lost\";i:1;}'),
(9, 11, '1587929454,Kiev,15', 170, 'a:1:{i:15;i:39;}', 'a:7:{s:6:\"failed\";i:2;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:1:{s:4:\"Kiev\";i:2;}s:11:\"cash_earned\";i:0;s:17:\"rankpoints_earned\";i:5;s:19:\"wanted_level_earned\";i:37;s:11:\"health_lost\";i:1;}'),
(10, 12, '', 0, 'a:0:{}', 'a:6:{s:6:\"failed\";i:0;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:0:{}s:11:\"cash_earned\";i:0;s:17:\"rankpoints_earned\";i:0;s:19:\"wanted_level_earned\";i:0;}'),
(11, 13, '', 0, 'a:0:{}', 'a:6:{s:6:\"failed\";i:0;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:0:{}s:11:\"cash_earned\";i:0;s:17:\"rankpoints_earned\";i:0;s:19:\"wanted_level_earned\";i:0;}'),
(12, 14, '1596022778,Berlin,11', 150, 'a:3:{i:15;i:270;i:16;i:252;i:11;i:270;}', 'a:8:{s:6:\"failed\";i:35;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:5:{s:4:\"Kiev\";i:5;s:8:\"Helsinki\";i:4;s:4:\"Oslo\";i:3;s:9:\"Copenhaga\";i:1;s:6:\"Berlin\";i:54;}s:11:\"cash_earned\";i:24995;s:17:\"rankpoints_earned\";i:327;s:19:\"wanted_level_earned\";i:681;s:11:\"health_lost\";i:27;s:10:\"sucessfull\";i:32;}'),
(13, 15, '', 0, 'a:0:{}', 'a:6:{s:6:\"failed\";i:0;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:0:{}s:11:\"cash_earned\";i:0;s:17:\"rankpoints_earned\";i:0;s:19:\"wanted_level_earned\";i:0;}'),
(14, 16, '', 0, 'a:0:{}', 'a:6:{s:6:\"failed\";i:0;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:0:{}s:11:\"cash_earned\";i:0;s:17:\"rankpoints_earned\";i:0;s:19:\"wanted_level_earned\";i:0;}'),
(15, 17, '1595486758,Amsterdam,15', 170, 'a:1:{i:15;i:188;}', 'a:8:{s:6:\"failed\";i:9;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:3:{s:8:\"Varsovia\";i:2;s:6:\"Berlin\";i:4;s:9:\"Amsterdam\";i:5;}s:11:\"cash_earned\";i:788;s:17:\"rankpoints_earned\";i:38;s:19:\"wanted_level_earned\";i:199;s:11:\"health_lost\";i:5;s:10:\"sucessfull\";i:2;}'),
(16, 18, '1593464962,Stockholm,15', 170, 'a:1:{i:15;i:36;}', 'a:7:{s:6:\"failed\";i:2;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:1:{s:9:\"Stockholm\";i:2;}s:11:\"cash_earned\";i:0;s:17:\"rankpoints_earned\";i:5;s:19:\"wanted_level_earned\";i:37;s:11:\"health_lost\";i:1;}'),
(17, 19, '1595170918,Copenhaga,16', 170, 'a:1:{i:16;i:14;}', 'a:7:{s:6:\"failed\";i:1;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:1:{s:9:\"Copenhaga\";i:1;}s:11:\"cash_earned\";i:0;s:17:\"rankpoints_earned\";i:2;s:19:\"wanted_level_earned\";i:15;s:11:\"health_lost\";i:1;}'),
(18, 20, '1597948392,Minsk,16', 170, 'a:1:{i:16;i:13;}', 'a:7:{s:6:\"failed\";i:1;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:1:{s:5:\"Minsk\";i:1;}s:11:\"cash_earned\";i:0;s:17:\"rankpoints_earned\";i:4;s:19:\"wanted_level_earned\";i:13;s:11:\"health_lost\";i:0;}'),
(19, 21, '1598253947,Kiev,16', 170, 'a:1:{i:16;i:15;}', 'a:7:{s:6:\"failed\";i:1;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:1:{s:4:\"Kiev\";i:1;}s:11:\"cash_earned\";i:0;s:17:\"rankpoints_earned\";i:2;s:19:\"wanted_level_earned\";i:13;s:11:\"health_lost\";i:0;}'),
(20, 22, '1606651311,Berlin,15', 170, 'a:2:{i:16;i:14;i:15;i:29;}', 'a:7:{s:6:\"failed\";i:3;s:11:\"successfull\";i:0;s:20:\"conducted_each_place\";a:1:{s:6:\"Berlin\";i:3;}s:11:\"cash_earned\";i:0;s:17:\"rankpoints_earned\";i:10;s:19:\"wanted_level_earned\";i:47;s:11:\"health_lost\";i:3;}');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bulletbuy_sessions`
--

CREATE TABLE `bulletbuy_sessions` (
  `id` int(11) NOT NULL,
  `started` int(64) DEFAULT 0,
  `ends` int(64) DEFAULT 0,
  `bullets_avaliable` int(11) DEFAULT 0,
  `bullets_sold` int(11) DEFAULT 0,
  `bullets_start` int(11) DEFAULT 0,
  `reserved` varchar(3000) DEFAULT 'a:0:{}',
  `sold` varchar(3000) DEFAULT 'a:0:{}',
  `active` int(1) DEFAULT 1,
  `place` int(8) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bunker`
--

CREATE TABLE `bunker` (
  `id` int(11) NOT NULL,
  `player` int(11) DEFAULT 0,
  `sessions` text DEFAULT NULL,
  `last_session_start` int(64) DEFAULT 0,
  `last_session_ends` int(64) DEFAULT 0,
  `bunkers` varchar(10000) DEFAULT 'a:0:{}',
  `access` varchar(5000) DEFAULT 'a:0:{}'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `bunker`
--

INSERT INTO `bunker` (`id`, `player`, `sessions`, `last_session_start`, `last_session_ends`, `bunkers`, `access`) VALUES
(1, 3, 'a:0:{}', 0, 0, 'a:0:{}', 'a:0:{}'),
(2, 5, 'a:0:{}', 0, 0, 'a:0:{}', 'a:0:{}'),
(3, 10, 'a:0:{}', 0, 0, 'a:0:{}', 'a:0:{}'),
(4, 14, 'a:0:{}', 0, 0, 'a:0:{}', 'a:0:{}'),
(5, 17, 'a:0:{}', 0, 0, 'a:0:{}', 'a:0:{}'),
(6, 18, 'a:0:{}', 0, 0, 'a:0:{}', 'a:0:{}'),
(7, 21, 'a:0:{}', 0, 0, 'a:0:{}', 'a:0:{}'),
(8, 22, 'a:0:{}', 0, 0, 'a:0:{}', 'a:0:{}');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `businesses`
--

CREATE TABLE `businesses` (
  `id` int(11) NOT NULL,
  `type` int(32) DEFAULT 0,
  `place` int(32) DEFAULT 0,
  `name` varchar(128) DEFAULT '',
  `job_1` int(11) DEFAULT 0,
  `job_2` int(11) DEFAULT 0,
  `bank` bigint(11) DEFAULT 0,
  `bank_income` bigint(11) DEFAULT 0,
  `bank_loss` bigint(11) DEFAULT 0,
  `deficit_start` int(64) DEFAULT 0,
  `active` int(1) DEFAULT 1,
  `info` text DEFAULT NULL,
  `image` varchar(240) DEFAULT '/game/images/default_firmabilde.jpg',
  `misc` varchar(20000) DEFAULT 'a:0:{}',
  `created` int(64) DEFAULT 0,
  `accepts_soknader` int(1) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `businesses`
--

INSERT INTO `businesses` (`id`, `type`, `place`, `name`, `job_1`, `job_2`, `bank`, `bank_income`, `bank_loss`, `deficit_start`, `active`, `info`, `image`, `misc`, `created`, `accepts_soknader`) VALUES
(1, 1, 6, 'Gangsterbank', 1, 3, -924899, 35101, 1010000, 1587805110, 0, 'Hier ist dein Geld sicher.\r\nHere your money is save.', '/game/images/default_firmabilde.jpg', 'a:9:{s:13:\"account_price\";s:4:\"5000\";s:10:\"rente_type\";i:1;s:13:\"rente_classes\";a:8:{i:0;a:3:{i:0;i:1;i:1;i:4999;i:2;i:2;}i:1;a:3:{i:0;i:5000;i:1;i:99999;i:2;d:1.8;}i:2;a:3:{i:0;i:100000;i:1;i:4999999;i:2;d:1.6000000000000001;}i:3;a:3:{i:0;i:5000000;i:1;i:9999999;i:2;d:1.3999999999999999;}i:4;a:3:{i:0;i:10000000;i:1;i:99999999;i:2;i:1;}i:5;a:3:{i:0;i:100000000;i:1;i:999999999;i:2;d:0.69999999999999996;}i:6;a:3:{i:0;i:1000000000;i:1;i:9999999999;i:2;d:0.29999999999999999;}i:7;a:3:{i:0;i:10000000000;i:1;s:11:\"99999999999\";i:2;d:0.20000000000000001;}}s:12:\"transfer_fee\";i:2;s:15:\"transfer_fee_pp\";i:100000;s:18:\"rente_type_2_value\";i:2;s:11:\"deposit_fee\";s:1:\"3\";s:12:\"deposit_fees\";i:0;s:14:\"total_deposits\";i:0;}', 1561637035, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `business_log`
--

CREATE TABLE `business_log` (
  `id` int(11) NOT NULL,
  `b_id` int(11) DEFAULT 0,
  `text` text DEFAULT NULL,
  `type` int(11) DEFAULT 0,
  `added` int(11) DEFAULT 0,
  `added_date` varchar(200) DEFAULT 'd.m.Y'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `business_log`
--

INSERT INTO `business_log` (`id`, `b_id`, `text`, `type`, `added`, `added_date`) VALUES
(1, 1, '1 cont aprobat. Compania a primit 0 $.', 3, 1561642548, '27.06.2019'),
(2, 1, '1 genehmigtes Konto. Das Unternehmen erhielt 0 $.', 3, 1561644607, '27.06.2019'),
(3, 1, '1 genehmigtes Konto. Das Unternehmen erhielt 5 000 $.', 3, 1561644639, '27.06.2019'),
(4, 1, '@<a href=\"https://nmafia.unterweltmafia.de/game/s/Peppi\" class=\"global_playerlink playerlink\" rel=\"Peppi\">Peppi</a> a confirmat cererea lui @<a href=\"https://nmafia.unterweltmafia.de/game/s/Peppi\" class=\"global_playerlink playerlink\" rel=\"Peppi\">Peppi</a> trimisa pentru pozitia de stellvertreter.', 4, 1561645805, '27.06.2019'),
(5, 1, 'Das Unternehmen erhielt 15 000$, din actiuni', 11, 1561735515, '28.06.2019'),
(6, 1, 'Das Unternehmen erhielt 5 000$, din actiuni', 11, 1561735553, '28.06.2019'),
(7, 1, 'Das Unternehmen erhielt 5 000$, din actiuni', 11, 1561735647, '28.06.2019'),
(8, 1, 'Das Unternehmen erhielt 5 000$, din actiuni', 11, 1561736187, '28.06.2019'),
(9, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1561988016, '01.07.2019'),
(10, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1562161174, '03.07.2019'),
(11, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1562337037, '05.07.2019'),
(12, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1562581503, '08.07.2019'),
(13, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1562853163, '11.07.2019'),
(14, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1563137424, '14.07.2019'),
(15, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1563393628, '17.07.2019'),
(16, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1563574235, '20.07.2019'),
(17, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1563802073, '22.07.2019'),
(18, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1564049459, '25.07.2019'),
(19, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1564423715, '29.07.2019'),
(20, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1564676418, '01.08.2019'),
(21, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1564951036, '04.08.2019'),
(22, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1565193768, '07.08.2019'),
(23, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1565724607, '13.08.2019'),
(24, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1566036112, '17.08.2019'),
(25, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1566810828, '26.08.2019'),
(26, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1568400811, '13.09.2019'),
(27, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1569743837, '29.09.2019'),
(28, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1569918978, '01.10.2019'),
(29, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1570260737, '05.10.2019'),
(30, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1572618792, '01.11.2019'),
(31, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1575106931, '30.11.2019'),
(32, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1575287540, '02.12.2019'),
(33, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1575462820, '04.12.2019'),
(34, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1575640092, '06.12.2019'),
(35, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1575813640, '08.12.2019'),
(36, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1575986688, '10.12.2019'),
(37, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1576169317, '12.12.2019'),
(38, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1576342096, '14.12.2019'),
(39, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1576520674, '16.12.2019'),
(40, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1576696956, '18.12.2019'),
(41, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1576872613, '20.12.2019'),
(42, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1577046066, '22.12.2019'),
(43, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1577221835, '24.12.2019'),
(44, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1577397984, '26.12.2019'),
(45, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1577578193, '29.12.2019'),
(46, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1577752816, '31.12.2019'),
(47, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1577938954, '02.01.2020'),
(48, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1578122293, '04.01.2020'),
(49, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1578297269, '06.01.2020'),
(50, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1578475220, '08.01.2020'),
(51, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1578658411, '10.01.2020'),
(52, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1578832037, '12.01.2020'),
(53, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1579013450, '14.01.2020'),
(54, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1579201749, '16.01.2020'),
(55, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1579374535, '18.01.2020'),
(56, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1579551932, '20.01.2020'),
(57, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1579736215, '23.01.2020'),
(58, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1579912777, '25.01.2020'),
(59, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1580087210, '27.01.2020'),
(60, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1580263661, '29.01.2020'),
(61, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1580436749, '31.01.2020'),
(62, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1580615098, '02.02.2020'),
(63, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1580788331, '04.02.2020'),
(64, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1580961192, '06.02.2020'),
(65, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1581147725, '08.02.2020'),
(66, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1581323629, '10.02.2020'),
(67, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1581517821, '12.02.2020'),
(68, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1581699259, '14.02.2020'),
(69, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1581878150, '16.02.2020'),
(70, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1582061491, '18.02.2020'),
(71, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1582243301, '21.02.2020'),
(72, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1582418431, '23.02.2020'),
(73, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1582598316, '25.02.2020'),
(74, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1582776017, '27.02.2020'),
(75, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1582952481, '29.02.2020'),
(76, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1583133073, '02.03.2020'),
(77, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1583323716, '04.03.2020'),
(78, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1583502948, '06.03.2020'),
(79, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1583683899, '08.03.2020'),
(80, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1583865563, '10.03.2020'),
(81, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1584042904, '12.03.2020'),
(82, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1584218178, '14.03.2020'),
(83, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1584398802, '16.03.2020'),
(84, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1584582498, '19.03.2020'),
(85, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1584755411, '21.03.2020'),
(86, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1584931375, '23.03.2020'),
(87, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1585105282, '25.03.2020'),
(88, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1585278895, '27.03.2020'),
(89, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1585466451, '29.03.2020'),
(90, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1585639691, '31.03.2020'),
(91, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1585815361, '02.04.2020'),
(92, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1585994881, '04.04.2020'),
(93, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1586169568, '06.04.2020'),
(94, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1586347896, '08.04.2020'),
(95, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1586520860, '10.04.2020'),
(96, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1586700692, '12.04.2020'),
(97, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1586885970, '14.04.2020'),
(98, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1587060430, '16.04.2020'),
(99, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1587233348, '18.04.2020'),
(100, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1587407177, '20.04.2020'),
(101, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1587587542, '22.04.2020'),
(102, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1587764307, '24.04.2020'),
(103, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1587943358, '27.04.2020'),
(104, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1588118585, '29.04.2020'),
(105, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1588292115, '01.05.2020'),
(106, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1588473067, '03.05.2020'),
(107, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1588647879, '05.05.2020'),
(108, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1588830475, '07.05.2020'),
(109, 1, 'Stockes where changed and company lost 10 000 $.', 11, 1589006052, '09.05.2020');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `owner` int(11) DEFAULT 0,
  `car_type` int(16) DEFAULT 0,
  `damage` int(64) DEFAULT 0,
  `horsepowers` int(64) DEFAULT 0,
  `place` int(8) DEFAULT 0,
  `acquired` int(64) DEFAULT 0,
  `active` int(1) DEFAULT 1,
  `sale` bigint(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `cars`
--

INSERT INTO `cars` (`id`, `owner`, `car_type`, `damage`, `horsepowers`, `place`, `acquired`, `active`, `sale`) VALUES
(1, 3, 1, 32, 50, 2, 1558728249, 1, 0),
(2, 3, 2, 20, 50, 2, 1558729134, 1, 0),
(3, 3, 2, 36, 50, 2, 1558730048, 1, 0),
(4, 3, 2, 72, 50, 2, 1558730853, 1, 0),
(5, 3, 1, 62, 50, 6, 1558812917, 1, 0),
(6, 3, 1, 24, 50, 6, 1558813396, 1, 0),
(7, 3, 2, 71, 50, 6, 1558813667, 1, 0),
(8, 3, 2, 72, 50, 6, 1558815556, 1, 0),
(9, 3, 1, 36, 50, 6, 1558816187, 1, 0),
(10, 3, 1, 0, 50, 6, 1558820077, 1, 0),
(11, 3, 2, 69, 50, 6, 1558862229, 1, 0),
(12, 3, 2, 37, 50, 6, 1558875873, 1, 0),
(13, 3, 2, 56, 50, 6, 1558876237, 1, 0),
(14, 3, 1, 75, 50, 6, 1558876546, 1, 0),
(15, 3, 1, 34, 50, 6, 1558876880, 1, 0),
(16, 3, 1, 43, 50, 6, 1558880915, 1, 0),
(17, 3, 2, 59, 50, 6, 1558942638, 1, 0),
(18, 3, 1, 21, 50, 6, 1558943055, 1, 0),
(19, 3, 3, 153, 88, 6, 1558974158, 0, 0),
(20, 3, 2, 33, 50, 6, 1559987147, 0, 0),
(21, 3, 1, 21, 50, 6, 1560103910, 1, 0),
(22, 3, 3, 138, 88, 6, 1560244954, 1, 0),
(23, 3, 6, 129, 192, 6, 1560698305, 1, 0),
(24, 3, 8, 135, 384, 6, 1560785055, 1, 0),
(25, 3, 5, 105, 112, 6, 1560805845, 1, 0),
(26, 3, 6, 177, 192, 6, 1560871028, 1, 0),
(27, 3, 6, 87, 192, 6, 1560882418, 1, 0),
(28, 1, 2, 80, 50, 6, 1560882493, 1, 0),
(29, 3, 4, 114, 113, 6, 1560976433, 1, 0),
(30, 3, 5, 105, 112, 6, 1560976837, 1, 0),
(31, 3, 5, 96, 112, 6, 1561027676, 1, 0),
(32, 1, 1, 76, 50, 6, 1561058089, 1, 0),
(33, 1, 1, 53, 50, 6, 1561060527, 1, 0),
(34, 1, 1, 74, 50, 6, 1561061413, 1, 0),
(35, 1, 2, 36, 50, 6, 1561061658, 1, 0),
(36, 1, 2, 57, 50, 6, 1561062447, 1, 0),
(37, 1, 2, 20, 50, 6, 1561063252, 1, 0),
(38, 1, 1, 51, 50, 6, 1561065188, 1, 0),
(39, 1, 2, 60, 50, 6, 1561065457, 1, 0),
(40, 1, 1, 21, 50, 6, 1561121778, 1, 0),
(41, 1, 1, 76, 50, 6, 1561123705, 1, 0),
(42, 3, 4, 169, 113, 6, 1561125764, 1, 0),
(43, 3, 4, 96, 113, 6, 1561132198, 1, 0),
(44, 1, 2, 66, 50, 6, 1561138830, 1, 0),
(45, 3, 4, 89, 113, 6, 1561141741, 1, 0),
(46, 3, 4, 98, 113, 6, 1561142148, 1, 0),
(47, 3, 4, 159, 113, 6, 1561143733, 1, 0),
(48, 3, 4, 81, 113, 6, 1561144149, 1, 0),
(49, 3, 6, 70, 192, 6, 1561145011, 1, 0),
(50, 1, 2, 42, 50, 6, 1561145033, 1, 0),
(51, 1, 1, 60, 50, 6, 1561145515, 1, 0),
(52, 1, 2, 64, 50, 6, 1561188835, 1, 0),
(53, 1, 1, 30, 50, 6, 1561191310, 1, 0),
(54, 1, 2, 47, 50, 6, 1561200239, 1, 0),
(55, 1, 1, 67, 50, 6, 1561200722, 1, 0),
(56, 1, 4, 141, 113, 6, 1561202316, 1, 0),
(57, 1, 4, 134, 113, 6, 1561203625, 1, 0),
(58, 1, 2, 53, 50, 6, 1561205731, 1, 0),
(59, 1, 1, 20, 50, 6, 1561206019, 1, 0),
(60, 1, 3, 80, 88, 6, 1561206428, 1, 0),
(61, 3, 5, 136, 112, 6, 1561215453, 1, 0),
(62, 3, 8, 179, 384, 6, 1561217642, 1, 0),
(63, 3, 7, 156, 360, 6, 1561218050, 1, 0),
(64, 3, 8, 170, 384, 6, 1561218409, 1, 0),
(65, 1, 5, 120, 112, 6, 1561223637, 1, 0),
(66, 3, 7, 138, 360, 6, 1561227951, 1, 0),
(67, 3, 3, 93, 88, 6, 1561228463, 1, 0),
(68, 3, 8, 146, 384, 6, 1561228870, 1, 0),
(69, 3, 3, 92, 88, 6, 1561229475, 1, 0),
(70, 3, 4, 113, 113, 6, 1561229791, 1, 0),
(71, 3, 8, 92, 384, 6, 1561230185, 1, 0),
(72, 3, 5, 111, 112, 6, 1561230684, 1, 0),
(73, 3, 4, 109, 113, 6, 1561236881, 1, 0),
(74, 3, 5, 139, 112, 6, 1561237692, 1, 0),
(75, 3, 4, 98, 113, 6, 1561238866, 1, 0),
(76, 3, 7, 162, 360, 6, 1561294107, 1, 0),
(77, 3, 4, 95, 113, 6, 1561295355, 1, 0),
(78, 3, 7, 161, 360, 6, 1561295688, 1, 0),
(79, 3, 4, 111, 113, 6, 1561298195, 1, 0),
(80, 3, 5, 168, 112, 6, 1561298774, 1, 0),
(81, 3, 5, 126, 112, 6, 1561300347, 1, 0),
(82, 3, 5, 169, 112, 6, 1561301347, 1, 0),
(83, 3, 5, 168, 112, 6, 1561301994, 1, 0),
(84, 3, 3, 134, 88, 6, 1561302504, 1, 0),
(85, 3, 6, 147, 192, 6, 1561306276, 1, 0),
(86, 3, 5, 155, 112, 6, 1561309412, 1, 0),
(87, 3, 9, 118, 1001, 6, 1561319203, 1, 0),
(88, 3, 4, 165, 113, 6, 1561320428, 1, 0),
(89, 3, 6, 84, 192, 6, 1561320901, 1, 0),
(90, 3, 8, 108, 384, 6, 1561321976, 1, 0),
(91, 3, 5, 165, 112, 6, 1561359853, 1, 0),
(92, 3, 6, 126, 192, 6, 1561372052, 1, 0),
(93, 3, 4, 177, 113, 6, 1561379774, 1, 0),
(94, 3, 7, 158, 360, 6, 1561392438, 1, 0),
(95, 3, 6, 180, 192, 6, 1561392871, 1, 0),
(96, 3, 6, 73, 192, 6, 1561399785, 1, 0),
(97, 3, 5, 112, 112, 6, 1561403780, 1, 0),
(98, 3, 3, 122, 88, 6, 1561466474, 1, 0),
(99, 3, 4, 73, 113, 6, 1561476833, 1, 0),
(100, 3, 6, 99, 192, 6, 1561477694, 1, 0),
(101, 3, 4, 153, 113, 6, 1561481439, 1, 0),
(102, 1, 6, 111, 192, 6, 1561485285, 1, 0),
(103, 1, 6, 154, 192, 6, 1561487184, 1, 0),
(104, 1, 5, 138, 112, 6, 1561491574, 1, 0),
(105, 3, 4, 174, 113, 6, 1561491662, 1, 0),
(106, 3, 8, 107, 384, 6, 1561492019, 1, 0),
(107, 3, 5, 180, 112, 6, 1561494866, 1, 0),
(108, 3, 5, 154, 112, 6, 1561496150, 1, 0),
(109, 1, 4, 96, 113, 6, 1561543748, 1, 0),
(110, 3, 3, 129, 88, 6, 1561546733, 1, 0),
(111, 3, 4, 147, 113, 6, 1561550172, 1, 0),
(112, 3, 8, 134, 384, 6, 1561555029, 1, 0),
(113, 3, 4, 130, 113, 6, 1561565877, 1, 0),
(114, 1, 8, 147, 384, 6, 1561642010, 1, 0),
(115, 3, 5, 112, 112, 6, 1561646800, 1, 0),
(116, 3, 5, 70, 112, 6, 1561649061, 1, 0),
(117, 3, 3, 74, 88, 6, 1561651004, 1, 0),
(118, 3, 3, 123, 88, 6, 1561735813, 0, 0),
(119, 3, 4, 83, 113, 6, 1561736210, 1, 0),
(120, 3, 4, 82, 113, 6, 1561751248, 1, 0),
(121, 3, 5, 124, 112, 6, 1561798303, 1, 0),
(122, 3, 1, 50, 50, 6, 1561799134, 1, 0),
(123, 3, 6, 159, 192, 6, 1561801535, 1, 0),
(124, 3, 4, 90, 113, 6, 1561808772, 1, 0),
(125, 3, 4, 170, 113, 6, 1561884657, 1, 0),
(126, 3, 6, 176, 192, 6, 1561888455, 1, 0),
(127, 3, 4, 170, 113, 6, 1561890852, 1, 0),
(128, 1, 2, 55, 50, 6, 1561897948, 1, 0),
(129, 1, 7, 70, 360, 6, 1561898498, 1, 0),
(130, 3, 8, 159, 384, 6, 1561898691, 1, 0),
(131, 3, 6, 172, 192, 6, 1561903216, 1, 0),
(132, 6, 2, 70, 50, 9, 1561927046, 1, 0),
(133, 6, 2, 49, 50, 9, 1561967913, 1, 0),
(134, 3, 6, 147, 192, 6, 1561968077, 1, 0),
(135, 6, 2, 31, 50, 9, 1561968685, 1, 0),
(136, 6, 2, 63, 50, 9, 1561968966, 1, 0),
(137, 6, 1, 52, 50, 9, 1561969834, 1, 0),
(138, 6, 2, 70, 50, 9, 1561970323, 1, 0),
(139, 6, 1, 29, 50, 9, 1561974181, 1, 0),
(140, 6, 1, 78, 50, 9, 1561974614, 1, 0),
(141, 6, 2, 28, 50, 9, 1561975050, 1, 0),
(142, 6, 2, 30, 50, 9, 1561976321, 1, 0),
(143, 6, 1, 65, 50, 9, 1561986493, 1, 0),
(144, 6, 2, 30, 50, 9, 1561988030, 1, 0),
(145, 6, 2, 25, 50, 9, 1561992984, 1, 0),
(146, 6, 2, 40, 50, 9, 1561994780, 1, 0),
(147, 6, 1, 59, 50, 9, 1561995994, 1, 0),
(148, 3, 4, 147, 113, 6, 1561999173, 1, 0),
(149, 3, 3, 116, 88, 6, 1562000854, 1, 0),
(150, 6, 2, 70, 50, 9, 1562080563, 1, 0),
(151, 3, 5, 179, 112, 6, 1562096721, 1, 0),
(152, 6, 2, 55, 50, 9, 1562176004, 1, 0),
(153, 6, 2, 20, 50, 9, 1562178772, 1, 0),
(154, 6, 1, 56, 50, 9, 1562230221, 1, 0),
(155, 6, 2, 70, 50, 9, 1562252806, 1, 0),
(156, 6, 2, 36, 50, 9, 1562337090, 1, 0),
(157, 6, 2, 39, 50, 9, 1562396721, 1, 0),
(158, 6, 1, 72, 50, 9, 1562398689, 1, 0),
(159, 6, 1, 49, 50, 9, 1562401405, 1, 0),
(160, 6, 2, 51, 50, 9, 1562405715, 1, 0),
(161, 6, 2, 48, 50, 9, 1562678175, 1, 0),
(162, 6, 2, 28, 50, 9, 1562687101, 1, 0),
(163, 3, 5, 144, 112, 6, 1562690344, 1, 0),
(164, 3, 2, 50, 50, 6, 1562693211, 1, 0),
(165, 6, 2, 32, 50, 9, 1563535779, 1, 0),
(166, 6, 1, 28, 50, 9, 1563698570, 1, 0),
(167, 6, 1, 27, 50, 9, 1563702579, 1, 0),
(168, 6, 2, 25, 50, 9, 1563733927, 1, 0),
(169, 3, 5, 103, 112, 6, 1572618848, 1, 0),
(170, 3, 3, 113, 88, 6, 1572619888, 1, 0),
(171, 3, 7, 143, 360, 6, 1575107148, 1, 0),
(172, 14, 2, 72, 50, 9, 1589129788, 0, 0),
(173, 14, 2, 53, 50, 3, 1589140221, 1, 0),
(174, 14, 2, 52, 50, 1, 1589154034, 0, 0),
(175, 14, 2, 50, 50, 1, 1589158743, 1, 0),
(176, 14, 2, 60, 50, 4, 1589159775, 1, 0),
(177, 14, 1, 35, 50, 2, 1589160020, 1, 0),
(178, 14, 1, 25, 50, 6, 1589160718, 0, 0),
(179, 14, 1, 56, 50, 6, 1589161026, 0, 0),
(180, 14, 1, 31, 50, 6, 1589198944, 0, 0),
(181, 14, 1, 28, 50, 6, 1589200287, 0, 0),
(182, 14, 1, 33, 50, 6, 1589200741, 0, 0),
(183, 14, 2, 67, 50, 6, 1589202128, 0, 0),
(184, 14, 2, 44, 50, 6, 1589204416, 0, 0),
(185, 14, 3, 169, 88, 6, 1589207216, 0, 0),
(186, 14, 3, 121, 88, 6, 1589212436, 0, 0),
(187, 14, 3, 166, 88, 6, 1589224424, 0, 0),
(188, 14, 4, 96, 113, 6, 1589240586, 0, 0),
(189, 14, 2, 27, 50, 6, 1589284320, 0, 0),
(190, 14, 1, 78, 50, 6, 1589284607, 0, 0),
(191, 14, 4, 91, 113, 6, 1589301023, 0, 0),
(192, 14, 5, 119, 112, 6, 1589301925, 0, 0),
(193, 14, 3, 118, 88, 6, 1589302238, 0, 0),
(194, 14, 5, 141, 112, 6, 1589461950, 0, 0),
(195, 14, 4, 146, 113, 6, 1589471890, 0, 0),
(196, 14, 5, 159, 112, 6, 1589579054, 0, 0),
(197, 14, 5, 168, 112, 6, 1589717038, 0, 0),
(198, 14, 6, 156, 192, 6, 1589749190, 0, 0),
(199, 14, 8, 75, 384, 6, 1589761280, 1, 0),
(200, 14, 5, 128, 112, 6, 1589970291, 1, 0),
(201, 14, 3, 86, 88, 6, 1592481488, 1, 0),
(202, 17, 2, 37, 50, 5, 1593005125, 1, 0),
(203, 17, 1, 27, 50, 5, 1593073163, 1, 0),
(204, 14, 8, 156, 384, 6, 1596022788, 1, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `car_races`
--

CREATE TABLE `car_races` (
  `id` int(11) NOT NULL,
  `started` int(64) DEFAULT 0,
  `ends` int(64) DEFAULT 0,
  `bets` varchar(6000) DEFAULT 'a:0:{}',
  `winner_driver` int(8) DEFAULT 0,
  `winners` varchar(2000) DEFAULT 'a:0:{}',
  `active` int(1) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `car_races`
--

INSERT INTO `car_races` (`id`, `started`, `ends`, `bets`, `winner_driver`, `winners`, `active`) VALUES
(1, 0, 1559761363, 'a:1:{i:3;a:3:{s:6:\"player\";s:1:\"3\";s:9:\"bet_money\";d:10000;s:10:\"bet_driver\";s:1:\"1\";}}', 1, 'a:1:{i:3;a:3:{s:6:\"player\";s:1:\"3\";s:9:\"bet_money\";d:10000;s:10:\"bet_driver\";s:1:\"1\";}}', 0),
(2, 1559761422, 1560887419, 'a:2:{i:1;a:3:{s:6:\"player\";s:1:\"1\";s:9:\"bet_money\";d:10000;s:10:\"bet_driver\";s:1:\"0\";}i:3;a:3:{s:6:\"player\";s:1:\"3\";s:9:\"bet_money\";d:10000;s:10:\"bet_driver\";s:1:\"2\";}}', 1, 'a:0:{}', 0),
(3, 1561123736, 1589763094, 'a:1:{i:14;a:3:{s:6:\"player\";s:2:\"14\";s:9:\"bet_money\";d:20000;s:10:\"bet_driver\";s:1:\"4\";}}', 0, 'a:0:{}', 0),
(4, 1592941973, 1592943773, 'a:0:{}', 0, 'a:0:{}', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `car_theft`
--

CREATE TABLE `car_theft` (
  `id` int(11) NOT NULL,
  `playerid` int(11) NOT NULL DEFAULT 0,
  `last` int(8) DEFAULT 0,
  `last_time` int(64) DEFAULT 0,
  `latency` int(11) DEFAULT 0,
  `chances` varchar(2000) DEFAULT 'a:0:{}',
  `stats` varchar(2000) DEFAULT 'a:0:{}'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `car_theft`
--

INSERT INTO `car_theft` (`id`, `playerid`, `last`, `last_time`, `latency`, `chances`, `stats`) VALUES
(1, 1, 1, 1561898498, 350, 'a:3:{i:3;i:270;i:2;i:179;i:1;i:188;}', 'a:2:{s:12:\"total_failed\";i:31;s:13:\"total_success\";i:31;}'),
(2, 3, 1, 1575107148, 350, 'a:4:{i:3;i:270;i:2;i:270;i:1;i:270;i:4;i:60;}', 'a:2:{s:12:\"total_failed\";i:53;s:13:\"total_success\";i:109;}'),
(3, 5, 0, 0, 0, 'a:0:{}', 'a:0:{}'),
(4, 6, 3, 1563733927, 230, 'a:2:{i:3;i:270;i:1;i:11;}', 'a:2:{s:12:\"total_failed\";i:19;s:13:\"total_success\";i:31;}'),
(5, 7, 0, 0, 0, 'a:0:{}', 'a:0:{}'),
(6, 9, 3, 1580240076, 230, 'a:1:{i:3;i:30;}', 'a:1:{s:12:\"total_failed\";i:2;}'),
(7, 10, 3, 1587805301, 230, 'a:1:{i:3;i:15;}', 'a:1:{s:12:\"total_failed\";i:1;}'),
(8, 12, 3, 1588533323, 230, 'a:1:{i:3;i:13;}', 'a:1:{s:12:\"total_failed\";i:1;}'),
(9, 14, 1, 1596022788, 350, 'a:3:{i:3;i:270;i:2;i:215;i:1;i:269;}', 'a:2:{s:12:\"total_failed\";i:32;s:13:\"total_success\";i:31;}'),
(10, 17, 3, 1593073163, 230, 'a:1:{i:3;i:101;}', 'a:2:{s:12:\"total_failed\";i:5;s:13:\"total_success\";i:2;}'),
(11, 19, 3, 1595177772, 230, 'a:1:{i:3;i:27;}', 'a:1:{s:12:\"total_failed\";i:2;}'),
(12, 20, 0, 0, 0, 'a:0:{}', 'a:0:{}'),
(13, 21, 3, 1598254031, 230, 'a:1:{i:3;i:13;}', 'a:1:{s:12:\"total_failed\";i:1;}'),
(14, 22, 3, 1606650924, 230, 'a:1:{i:3;i:10;}', 'a:1:{s:12:\"total_failed\";i:1;}');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `changelog`
--

CREATE TABLE `changelog` (
  `id` int(11) NOT NULL,
  `author` int(11) NOT NULL DEFAULT 0,
  `added` int(11) NOT NULL DEFAULT 0,
  `title` varchar(500) DEFAULT '',
  `text` text DEFAULT NULL,
  `deleted` int(1) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `player` int(11) DEFAULT 0,
  `text` text DEFAULT NULL,
  `time` int(64) DEFAULT 0,
  `deleted` int(1) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `chat`
--

INSERT INTO `chat` (`id`, `player`, `text`, `time`, `deleted`) VALUES
(1, 1, 'Ã¤Ã¶Ã¼', 1558724513, 0),
(2, 1, 'test', 1558724522, 0),
(3, 3, 'ein TÃ¤Ã¤Ã¤stÃŸ', 1558728092, 0),
(4, 3, 'Hallo Leute', 1561028320, 0),
(5, 3, 'sdfsdf', 1561028384, 0),
(6, 15, 'hallo', 1589369076, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `coinroll`
--

CREATE TABLE `coinroll` (
  `id` int(9) NOT NULL,
  `owner` int(11) DEFAULT 0,
  `place` int(8) DEFAULT 0,
  `max_bet` bigint(11) DEFAULT 0,
  `win_chance` int(11) NOT NULL DEFAULT 50,
  `bank` bigint(11) DEFAULT 0,
  `bank_loss` bigint(11) DEFAULT 0,
  `bank_income` bigint(11) DEFAULT 0,
  `created` int(64) DEFAULT 0,
  `active` int(1) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `coinroll`
--

INSERT INTO `coinroll` (`id`, `owner`, `place`, `max_bet`, `win_chance`, `bank`, `bank_loss`, `bank_income`, `created`, `active`) VALUES
(1, 1, 6, 20000, 50, 89947, 440275, 330222, 1561121886, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `coins_packs`
--

CREATE TABLE `coins_packs` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `coins` int(11) NOT NULL DEFAULT 0,
  `price` decimal(5,2) NOT NULL DEFAULT 0.00
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `coins_packs`
--

INSERT INTO `coins_packs` (`id`, `name`, `coins`, `price`) VALUES
(2, '50 Coins', 50, '1.00'),
(3, '110 Coins', 110, '2.00'),
(4, '300 Coins', 300, '5.00'),
(5, '750 Coins', 750, '10.00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL DEFAULT 0,
  `contact_id` int(11) NOT NULL DEFAULT 0,
  `info` text DEFAULT NULL,
  `type` int(1) DEFAULT 1,
  `added` int(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `countrylist`
--

CREATE TABLE `countrylist` (
  `id` int(11) NOT NULL,
  `country` varchar(200) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `countrylist`
--

INSERT INTO `countrylist` (`id`, `country`) VALUES
(1, 'United States'),
(2, 'Canada'),
(3, 'Mexico'),
(4, 'Afghanistan'),
(5, 'Albania'),
(6, 'Algeria'),
(7, 'Andorra'),
(8, 'Angola'),
(9, 'Anguilla'),
(10, 'Antarctica'),
(11, 'Antigua and Barbuda'),
(12, 'Argentina'),
(13, 'Armenia'),
(14, 'Aruba'),
(15, 'Ascension Island'),
(16, 'Australia'),
(17, 'Austria'),
(18, 'Azerbaijan'),
(19, 'Bahamas'),
(20, 'Bahrain'),
(21, 'Bangladesh'),
(22, 'Barbados'),
(23, 'Belarus'),
(24, 'Belgium'),
(25, 'Belize'),
(26, 'Benin'),
(27, 'Bermuda'),
(28, 'Bhutan'),
(29, 'Bolivia'),
(30, 'Bophuthatswana'),
(31, 'Bosnia-Herzegovina'),
(32, 'Botswana'),
(33, 'Bouvet Island'),
(34, 'Brazil'),
(35, 'British Indian Ocean'),
(36, 'British Virgin Islands'),
(37, 'Brunei Darussalam'),
(38, 'Bulgaria'),
(39, 'Burkina Faso'),
(40, 'Burundi'),
(41, 'Cambodia'),
(42, 'Cameroon'),
(44, 'Cape Verde Island'),
(45, 'Cayman Islands'),
(46, 'Central Africa'),
(47, 'Chad'),
(48, 'Channel Islands'),
(49, 'Chile'),
(50, 'China, Peoples Republic'),
(51, 'Christmas Island'),
(52, 'Cocos (Keeling) Islands'),
(53, 'Colombia'),
(54, 'Comoros Islands'),
(55, 'Congo'),
(56, 'Cook Islands'),
(57, 'Costa Rica'),
(58, 'Croatia'),
(59, 'Cuba'),
(60, 'Cyprus'),
(61, 'Czech Republic'),
(62, 'Denmark'),
(63, 'Djibouti'),
(64, 'Dominica'),
(65, 'Dominican Republic'),
(66, 'Easter Island'),
(67, 'Ecuador'),
(68, 'Egypt'),
(69, 'El Salvador'),
(70, 'England'),
(71, 'Equatorial Guinea'),
(72, 'Estonia'),
(73, 'Ethiopia'),
(74, 'Falkland Islands'),
(75, 'Faeroe Islands'),
(76, 'Fiji'),
(77, 'Finland'),
(78, 'France'),
(79, 'French Guyana'),
(80, 'French Polynesia'),
(81, 'Gabon'),
(82, 'Gambia'),
(83, 'Georgia Republic'),
(84, 'Germany'),
(85, 'Gibraltar'),
(86, 'Greece'),
(87, 'Greenland'),
(88, 'Grenada'),
(89, 'Guadeloupe (French)'),
(90, 'Guatemala'),
(91, 'Guernsey Island'),
(92, 'Guinea Bissau'),
(93, 'Guinea'),
(94, 'Guyana'),
(95, 'Haiti'),
(96, 'Heard and McDonald Isls'),
(97, 'Honduras'),
(98, 'Hong Kong'),
(99, 'Hungary'),
(100, 'Iceland'),
(101, 'India'),
(102, 'Iran'),
(103, 'Iraq'),
(104, 'Ireland'),
(105, 'Isle of Man'),
(106, 'Israel'),
(107, 'Italy'),
(108, 'Ivory Coast'),
(109, 'Jamaica'),
(110, 'Japan'),
(111, 'Jersey Island'),
(112, 'Jordan'),
(113, 'Kazakhstan'),
(114, 'Kenya'),
(115, 'Kiribati'),
(116, 'Kuwait'),
(117, 'Laos'),
(118, 'Latvia'),
(119, 'Lebanon'),
(120, 'Lesotho'),
(121, 'Liberia'),
(122, 'Libya'),
(123, 'Liechtenstein'),
(124, 'Lithuania'),
(125, 'Luxembourg'),
(126, 'Macao'),
(127, 'Macedonia'),
(128, 'Madagascar'),
(129, 'Malawi'),
(130, 'Malaysia'),
(131, 'Maldives'),
(132, 'Mali'),
(133, 'Malta'),
(134, 'Martinique (French)'),
(135, 'Mauritania'),
(136, 'Mauritius'),
(137, 'Mayotte'),
(139, 'Micronesia'),
(140, 'Moldavia'),
(141, 'Monaco'),
(142, 'Mongolia'),
(143, 'Montenegro'),
(144, 'Montserrat'),
(145, 'Morocco'),
(146, 'Mozambique'),
(147, 'Myanmar'),
(148, 'Namibia'),
(149, 'Nauru'),
(150, 'Nepal'),
(151, 'Netherlands Antilles'),
(152, 'Netherlands'),
(153, 'New Caledonia (French)'),
(154, 'New Zealand'),
(155, 'Nicaragua'),
(156, 'Niger'),
(157, 'Niue'),
(158, 'Norfolk Island'),
(159, 'North Korea'),
(160, 'Northern Ireland'),
(161, 'Norway'),
(162, 'Oman'),
(163, 'Pakistan'),
(164, 'Panama'),
(165, 'Papua New Guinea'),
(166, 'Paraguay'),
(167, 'Peru'),
(168, 'Philippines'),
(169, 'Pitcairn Island'),
(170, 'Poland'),
(171, 'Polynesia (French)'),
(172, 'Portugal'),
(173, 'Qatar'),
(174, 'Reunion Island'),
(175, 'Romania'),
(176, 'Russia'),
(177, 'Rwanda'),
(178, 'S.Georgia Sandwich Isls'),
(179, 'Sao Tome, Principe'),
(180, 'San Marino'),
(181, 'Saudi Arabia'),
(182, 'Scotland'),
(183, 'Senegal'),
(184, 'Serbia'),
(185, 'Seychelles'),
(186, 'Shetland'),
(187, 'Sierra Leone'),
(188, 'Singapore'),
(189, 'Slovak Republic'),
(190, 'Slovenia'),
(191, 'Solomon Islands'),
(192, 'Somalia'),
(193, 'South Africa'),
(194, 'South Korea'),
(195, 'Spain'),
(196, 'Sri Lanka'),
(197, 'St. Helena'),
(198, 'St. Lucia'),
(199, 'St. Pierre Miquelon'),
(200, 'St. Martins'),
(201, 'St. Kitts Nevis Anguilla'),
(202, 'St. Vincent Grenadines'),
(203, 'Sudan'),
(204, 'Suriname'),
(205, 'Svalbard Jan Mayen'),
(206, 'Swaziland'),
(207, 'Sweden'),
(208, 'Switzerland'),
(209, 'Syria'),
(210, 'Tajikistan'),
(211, 'Taiwan'),
(212, 'Tahiti'),
(213, 'Tanzania'),
(214, 'Thailand'),
(215, 'Togo'),
(216, 'Tokelau'),
(217, 'Tonga'),
(218, 'Trinidad and Tobago'),
(219, 'Tunisia'),
(220, 'Turkmenistan'),
(221, 'Turks and Caicos Isls'),
(222, 'Tuvalu'),
(223, 'Uganda'),
(224, 'Ukraine'),
(225, 'United Arab Emirates'),
(226, 'Uruguay'),
(227, 'Uzbekistan'),
(228, 'Vanuatu'),
(229, 'Vatican City State'),
(230, 'Venezuela'),
(231, 'Vietnam'),
(232, 'Virgin Islands (Brit)'),
(233, 'Wales'),
(234, 'Wallis Futuna Islands'),
(235, 'Western Sahara'),
(236, 'Western Samoa'),
(237, 'Yemen'),
(238, 'Yugoslavia'),
(239, 'Zaire'),
(240, 'Zambia'),
(241, 'Zimbabwe');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cron_tab`
--

CREATE TABLE `cron_tab` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cron_tab`
--

INSERT INTO `cron_tab` (`name`, `timestamp`) VALUES
('minute', 1612773786),
('day', 1612738800);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `deactivations`
--

CREATE TABLE `deactivations` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `victim` int(11) NOT NULL,
  `by_player` int(11) NOT NULL,
  `reason` text NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `detective`
--

CREATE TABLE `detective` (
  `id` int(11) NOT NULL,
  `started` int(64) DEFAULT 0,
  `ends` int(64) DEFAULT 0,
  `player` int(11) DEFAULT 0,
  `to_find` int(11) DEFAULT 0,
  `length` int(32) DEFAULT 0,
  `result` int(8) DEFAULT 0,
  `on_sale` int(1) DEFAULT 0,
  `sold_to` int(11) DEFAULT 0,
  `finished` int(1) DEFAULT 0,
  `sale_price` bigint(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `family_attacks`
--

CREATE TABLE `family_attacks` (
  `id` int(11) NOT NULL,
  `family` int(11) NOT NULL,
  `victim` int(11) NOT NULL,
  `result` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cash_received` int(11) NOT NULL,
  `strength_lost` int(11) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `family_businesses`
--

CREATE TABLE `family_businesses` (
  `id` int(11) NOT NULL,
  `name` varchar(128) DEFAULT '',
  `family` int(11) NOT NULL DEFAULT 0,
  `type` varchar(128) DEFAULT '',
  `place` int(32) DEFAULT 0,
  `bank_income` bigint(11) NOT NULL DEFAULT 0,
  `bank_loss` bigint(11) DEFAULT 0,
  `guards` varchar(5000) NOT NULL DEFAULT 'a:0:{}',
  `guard_slots` int(16) DEFAULT 2,
  `stats` varchar(10000) DEFAULT 'a:0:{}'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `family_businesses`
--

INSERT INTO `family_businesses` (`id`, `name`, `family`, `type`, `place`, `bank_income`, `bank_loss`, `guards`, `guard_slots`, `stats`) VALUES
(1, 'Oslo Airport', 0, 'travel', 1, 0, 0, 'a:0:{}', 4, 'a:0:{}'),
(2, 'Stockholm Airport', 0, 'travel', 2, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(3, 'Helsinki Airport', 0, 'travel', 3, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(4, 'Copenhaga Airport', 0, 'travel', 4, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(5, 'Amsterdam Airport', 0, 'travel', 5, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(6, 'Berlin Airport', 0, 'travel', 6, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(7, 'Varsovia Airport', 0, 'travel', 7, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(8, 'Oslo Hospital', 0, 'hospital', 1, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(9, 'Stockholm Hospital', 0, 'hospital', 2, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(10, 'Helsinki Hospital', 0, 'hospital', 3, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(11, 'Copenhaga Hospital', 0, 'hospital', 4, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(12, 'Amsterdam Hospital', 0, 'hospital', 5, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(13, 'Berlin Hospital', 0, 'hospital', 6, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(14, 'Varsovia Hospital', 0, 'hospital', 7, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(15, 'Oslo Lottery', 0, 'lottery', 1, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(16, 'Stockholm Lottery', 0, 'lottery', 2, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(17, 'Helsinki Lottery', 0, 'lottery', 3, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(18, 'Copenhaga Lottery', 0, 'lottery', 4, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(19, 'Amsterdam Lottery', 0, 'lottery', 5, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(20, 'Berlin Lottery', 0, 'lottery', 6, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(21, 'Varsovia Lottery', 0, 'lottery', 7, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(22, 'Minsk Airport', 0, 'travel', 8, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(23, 'Minsk Hospital', 0, 'hospital', 8, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(24, 'Minsk Lottery', 0, 'lottery', 8, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(26, 'Kiev Lottery', 0, 'lottery', 9, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(25, 'Kiev Airport', 0, 'travel', 9, 0, 0, 'a:0:{}', 2, 'a:0:{}'),
(27, 'Kiev Hospital', 0, 'hospital', 9, 0, 0, 'a:0:{}', 2, 'a:0:{}');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `family_log`
--

CREATE TABLE `family_log` (
  `id` int(11) NOT NULL,
  `family` int(11) DEFAULT 0,
  `text` varchar(2000) DEFAULT '',
  `type` varchar(128) DEFAULT '0',
  `added` int(64) DEFAULT 0,
  `access_level` int(1) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fighting`
--

CREATE TABLE `fighting` (
  `id` int(11) NOT NULL,
  `player` int(11) NOT NULL DEFAULT 0,
  `level` bigint(11) DEFAULT 1,
  `level_progress` int(128) DEFAULT 0,
  `last_training` int(64) DEFAULT 0,
  `training_wait` int(64) DEFAULT 0,
  `fight_bet` bigint(11) DEFAULT 0,
  `stats` text NOT NULL,
  `last_fight` int(64) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `fighting`
--

INSERT INTO `fighting` (`id`, `player`, `level`, `level_progress`, `last_training`, `training_wait`, `fight_bet`, `stats`, `last_fight`) VALUES
(1, 1, 1, 35, 1561202928, 360, 0, '', 0),
(2, 3, 1, 240, 1561298239, 360, 0, '', 0),
(3, 5, 1, 0, 0, 0, 0, '', 0),
(4, 6, 1, 18, 1561888835, 160, 0, '', 0),
(5, 7, 1, 0, 0, 0, 0, '', 0),
(6, 10, 1, 0, 0, 0, 0, '', 0),
(7, 11, 1, 0, 0, 0, 0, 'a:2:{s:3:\"won\";i:1;s:9:\"won_money\";i:1000;}', 1589129938),
(8, 13, 1, 0, 0, 0, 0, '', 0),
(9, 14, 6, 4, 1596022823, 560, 0, 'a:2:{s:4:\"lost\";i:1;s:10:\"lost_money\";i:1000;}', 1589129938),
(10, 15, 1, 26, 1589369043, 300, 0, '', 0),
(11, 17, 1, 106, 1593073275, 160, 1000, '', 0),
(12, 19, 1, 0, 0, 0, 0, '', 0),
(13, 22, 1, 12, 1606651133, 120, 0, '', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `forum_replies`
--

CREATE TABLE `forum_replies` (
  `id` bigint(11) NOT NULL,
  `topic_id` int(11) DEFAULT 0,
  `playerid` int(11) DEFAULT 0,
  `posted` int(11) DEFAULT 0,
  `text` text DEFAULT NULL,
  `last_edit_playerid` int(11) DEFAULT 0,
  `last_edit_time` int(11) DEFAULT 0,
  `deleted` int(1) DEFAULT 0,
  `read_by` varchar(20000) NOT NULL DEFAULT 'a:0:{}',
  `userid` int(11) DEFAULT NULL,
  `reported` int(1) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `forum_replies`
--

INSERT INTO `forum_replies` (`id`, `topic_id`, `playerid`, `posted`, `text`, `last_edit_playerid`, `last_edit_time`, `deleted`, `read_by`, `userid`, `reported`) VALUES
(1, 1, 3, 1560763188, 'Ein Test mit Umlauten: Ã¤Ã¶Ã¼ÃŸ', 0, 0, 0, 'a:2:{i:0;s:1:\"3\";i:1;s:1:\"1\";}', 3, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `forum_reports`
--

CREATE TABLE `forum_reports` (
  `id` bigint(11) NOT NULL,
  `playerid` int(11) DEFAULT 0,
  `userid` int(11) DEFAULT 0,
  `post_type` varchar(100) DEFAULT 'topic',
  `post_id` int(11) DEFAULT 0,
  `reason` text DEFAULT NULL,
  `treatment` int(1) DEFAULT 0,
  `reported` int(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `forum_topics`
--

CREATE TABLE `forum_topics` (
  `id` bigint(11) NOT NULL,
  `playerid` int(11) DEFAULT 0,
  `forum_id` int(1) DEFAULT 1,
  `family` varchar(200) DEFAULT '',
  `title` varchar(1000) DEFAULT '',
  `text` text DEFAULT NULL,
  `posted` int(11) DEFAULT 0,
  `last_reply` int(11) DEFAULT 0,
  `last_edit_playerid` int(11) DEFAULT 0,
  `last_edit_time` int(11) DEFAULT 0,
  `type` int(1) DEFAULT 1,
  `locked` int(1) DEFAULT 0,
  `deleted` int(1) DEFAULT 0,
  `views` varchar(20000) NOT NULL DEFAULT 'a:0:{}',
  `userid` int(11) DEFAULT NULL,
  `reported` int(1) DEFAULT 0,
  `last_reply_playerid` int(11) DEFAULT 0,
  `replies` int(11) DEFAULT 0,
  `pre_title` varchar(64) DEFAULT '',
  `f_lang` varchar(255) NOT NULL DEFAULT 'RO'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `forum_topics`
--

INSERT INTO `forum_topics` (`id`, `playerid`, `forum_id`, `family`, `title`, `text`, `posted`, `last_reply`, `last_edit_playerid`, `last_edit_time`, `type`, `locked`, `deleted`, `views`, `userid`, `reported`, `last_reply_playerid`, `replies`, `pre_title`, `f_lang`) VALUES
(1, 3, 2, '', 'Ein Testthead. ', 'Dies ist nur ein Test.\nEnde', 1560695711, 1560763188, 0, 0, 1, 0, 1, 'a:2:{i:3;i:1560763188;i:1;i:1561061511;}', 3, 0, 3, 1, '', 'RO'),
(2, 3, 2, '', 'TestThead', 'only for Test. one two...\nEnd', 1560696338, 1560696338, 0, 0, 1, 0, 1, 'a:2:{i:3;i:1560696338;i:1;i:1561061524;}', 3, 0, 0, 0, '', 'EN');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `game_economy`
--

CREATE TABLE `game_economy` (
  `id` int(11) NOT NULL,
  `money` bigint(11) DEFAULT 0,
  `points` bigint(11) DEFAULT 0,
  `time` int(64) DEFAULT 0,
  `players_economy` longtext NOT NULL,
  `businesses_economy` longtext NOT NULL,
  `families_economy` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `game_economy`
--

INSERT INTO `game_economy` (`id`, `money`, `points`, `time`, `players_economy`, `businesses_economy`, `families_economy`) VALUES
(1, 9460701, 650, 1612773786, 'a:21:{i:3;a:3:{s:4:\"cash\";s:7:\"5280461\";s:4:\"bank\";s:6:\"104980\";s:6:\"points\";s:3:\"435\";}i:14;a:2:{s:4:\"cash\";s:7:\"3288290\";s:6:\"points\";s:2:\"15\";}i:1;a:2:{s:4:\"cash\";s:8:\"15113645\";s:6:\"points\";s:3:\"930\";}i:6;a:2:{s:4:\"cash\";s:6:\"383965\";s:6:\"points\";s:2:\"20\";}i:17;a:1:{s:6:\"points\";s:2:\"10\";}i:15;a:1:{s:6:\"points\";s:2:\"20\";}i:22;a:1:{s:6:\"points\";s:2:\"10\";}i:9;a:1:{s:6:\"points\";s:2:\"10\";}i:11;a:1:{s:6:\"points\";s:2:\"10\";}i:19;a:1:{s:6:\"points\";s:2:\"10\";}i:10;a:1:{s:6:\"points\";s:2:\"10\";}i:12;a:1:{s:6:\"points\";s:2:\"10\";}i:18;a:1:{s:6:\"points\";s:2:\"10\";}i:20;a:1:{s:6:\"points\";s:2:\"10\";}i:2;a:1:{s:6:\"points\";s:2:\"10\";}i:4;a:1:{s:6:\"points\";s:2:\"10\";}i:5;a:1:{s:6:\"points\";s:2:\"10\";}i:7;a:1:{s:6:\"points\";s:2:\"10\";}i:8;a:1:{s:6:\"points\";s:2:\"10\";}i:13;a:1:{s:6:\"points\";s:2:\"10\";}i:16;a:1:{s:6:\"points\";s:2:\"10\";}}', 'N;', 'N;');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `game_faq`
--

CREATE TABLE `game_faq` (
  `id` int(11) NOT NULL,
  `title` varchar(30) DEFAULT '',
  `text` longtext DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `game_faq`
--

INSERT INTO `game_faq` (`id`, `title`, `text`) VALUES
(3, 'Rangstatus', 'Um deinen Rangstatus zu erhöhen, musst du Verbrechen begehen, Autos stehlen, mit anderen Spielern kämpfen usw.\r\n<br />\r\n<br />\r\n<b>Liste der Ränge</b>\r\n<table class=\"table\">\r\n<thead>\r\n<tr class=\"small\">\r\n<td>#</td>\r\n<td>Rang</td>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr class=\"c_1\"><td>1</td><td>Neuling</td></tr>\r\n<tr class=\"c_2\"><td>2</td><td>Zänker</td></tr>\r\n<tr class=\"c_1\"><td>3</td><td>Dieb</td></tr>\r\n<tr class=\"c_2\"><td>4</td><td>Gangster</td></tr>\r\n<tr class=\"c_1\"><td>5</td><td>Hitman</td></tr>\r\n<tr class=\"c_2\"><td>6</td><td>Assassin</td></tr>\r\n<tr class=\"c_1\"><td>7</td><td>Anführer</td></tr>\r\n<tr class=\"c_2\"><td>8</td><td>Consigliere</td></tr>\r\n<tr class=\"c_1\"><td>9</td><td>Caporegime</td></tr>\r\n<tr class=\"c_2\"><td>10</td><td>Buchhalter</td></tr>\r\n<tr class=\"c_1\"><td>11</td><td>Pate</td></tr>\r\n<tr class=\"c_2\"><td>12</td><td>Don</td></tr>\r\n<tr class=\"c_1\"><td>13</td><td>Capo Crimini</td></tr>\r\n<tr class=\"c_2\"><td>14</td><td>UWM Veteran</td></tr>\r\n<tr class=\"c_1\"><td>15</td><td>Unterweltkönig</td></tr>\r\n</tbody>\r\n</table>'),
(4, 'Geldstatus', '<b>Tipps um Geld zu verdienen:</b>\r\n<ul>\r\n<li>Raubüberfälle begehen, Spieler erpressen, Autos klauen usw..</li>\r\n<li>Absolviere verschiedene <a href=\"#faq_15\">Missionen</a>!</li>\r\n<li> Frage andere Spieler im Forum!</li>\r\n<li>Coins gegen Geld eintauschen! (<a href=\"/game/?side=magazin-credite\">klicke hier</a>).</li>\r\n</ul>\r\n<br />\r\n<b>Liste der Geld-Ränge:</b>\r\n<table class=\"table\">\r\n<thead>\r\n<tr class=\"small\">\r\n<td>Rang</td>\r\n<td>von</td>\r\n<td>bis</td>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr class=\"c_1\">\r\n<td>Verschuldet</td>\r\n<td>-1 $</td>\r\n<td>0 $</td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>Anfänger</td>\r\n<td>0 $</td>\r\n<td>9999 $</td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>Arbeiter</td>\r\n<td>10 000 $</td>\r\n<td>99 999 $</td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>Gangster</td>\r\n<td>100 000 $</td>\r\n<td>999 999 $</td>\r\n</tr><tr class=\"c_1\">\r\n<td>Sammler</td>\r\n<td>1 000 000 $</td>\r\n<td>14 999 999 $</td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>Banker</td>\r\n<td>15 000 000 $</td>\r\n<td>99 999 999 $</td>\r\n</tr><tr class=\"c_1\">\r\n<td>Millionär</td>\r\n<td>100 000 000 $</td>\r\n<td>999 999 999 $</td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>Multimillionär</td>\r\n<td>1 000 000 000 $</td>\r\n<td>19 999 999 999 $</td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>Milliardär</td>\r\n<td>20 000 000 000 $</td>\r\n<td>99 999 999 999 $</td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>Multimilliardär</td>\r\n<td>100 000 000 000 $</td>\r\n<td>999 999 999 999 &</td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>Der Boss</td>\r\n<td>1 000 000 000 000 $</td>\r\n<td>und mehr...</td>\r\n</tr>\r\n</tbody>\r\n</table>'),
(6, 'Familien', '<b>Grundlegende Informationen:</b>\r\n<ul>\r\n<li>Es können keine zwei Familien in derselben Stadt sein</li>\r\n<li>Man muss mindestens den Rang <b>Anführer</b> haben um eine Familie gründen zu können</li>\r\n<li>Der Name muss zwischen 4 und 20 Zeichen lang sein</li>\r\n</ul>\r\n<br />\r\n<b>DFamiliengrößen:</b>\r\n<ul>\r\n<li><b>Name - Größe - Preis</b></li>\r\n<li>Kleine Familie - 10 - 75 C</li>\r\n<li>Mittlere Famile - 20 - 150 C</li>\r\n<li>Normale Familie - 50 - 250 C</li>\r\n<li>Große Familie - 100 - 400 C</li>\r\n</ul>\r\n<br />\r\n<b>Territorium:</b>\r\n<br />\r\nEine Familie bekommt ein Territorium, wenn sie alle familiären Angelegenheiten an einem bestimmten Ort hat.\r\n<br />\r\n<br />\r\n<b>Familienangelegenheiten:</b>\r\n<br />\r\nEin Familienunternehmen ist ein Familien eigenes Unternehmen.<br />\r\nEine Familie kann mehrere Unternehmen besitzen!<br />\r\n<br />\r\nEs gibt 3 Geschäftsarten:<br />\r\n<ul>\r\n<li>Tourismus</li>\r\n<li>Krankenhäuser</li>\r\n<li>Lotterie</li>\r\n</ul>\r\n<br />\r\nDie Familie, der das Unternehmen gehört, erhält 100% des vom Unternehmen erzielten Umsatzes.<br />\r\n<br />\r\nJedes Geschäft muss von den Wachen (Familienmitgliedern) bewacht werden, damit nicht riskiert wird, dass dieses Geschäft schutzlos ist.\r\n<br />\r\n<br />\r\n<b>Werde ein Familienmitglied</b><br />\r\nDu musst von einer Familie eingeladen werden, um Mitglied dieser Familie zu werden!<br />\r\n<h2>Familienangriffe</h2>\r\n<b>Macht</b><br />\r\nJe höher die Macht einer Familie ist, desto besser ist sie.<br />\r\n<br />\r\nDie Macht der Familie nimmt zu, je nachdem, wie viele Unternehmen sie besitzt\r\n<br />\r\nMacht erhöht sich auch, wenn ein Mitglied einen anderen Spieler umlegt.<br />\r\n<br />\r\n<b>Angriffe</b><br />\r\nDie Familie muss mindestens 3 Tage existieren, um angegriffen zu werden.<br />\r\n<br />\r\nDie Familie, die den Angriff verliert, verliert zwischen 20 und 40 Machtpunkte.\r\n<br />\r\nDie siegreiche Familie erhält Geld (bis zur Hälfte) vom Familienkonto der Familie, die verlorn hat.'),
(5, 'Gefahrenstaus', 'Natürlich ist die Polizei hinter dir her und hat den Wunsch dich zu verhaften.\r\n<br />\r\n<br />\r\nJe höher der Gefahrenstatus, umso besser ist die Polizei und desto eher kommt man ins Gefängnis.<br />\r\nDer Gefahrenstatus steigt nach jedem Verbrechen im Spiel.\r\n<br />\r\n<br />\r\nMit jeder Minute nimmt der Gefahrenstatus wieder leicht ab. Wenn man den Gefahrenstatus sofort wieder komplett zurück setzen möchte, kann man das mit <a href=\"?side=magazin-credite\">Coins</a> machen.'),
(8, 'Killer / Bunker', 'Kriminalität besteht aus mehreren Komponenten:\r\n<ul>\r\n<li>Killer</li>\r\n<li>Detektiv</li>\r\n<li>\r\nWaffenausrüstung:\r\n<ul style=\"margin-top: 2px;\">\r\n<li>Waffen</li>\r\n<li>Schutz</li>\r\n<li>Munition</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n<br />\r\n<b>\"Killer\"</b> ist eine Funktion, mit der du andere Spieler hier im Spiel umlegen kannst. Diese Funktion ist nur zwischen bestimmten Zeitintervallen aktiv.<br />\r\n<br />\r\nEin Spieler stirbt hier im Spiel, wenn sein Leben 0% erreicht.<br />\r\nDu musst mindestens 3 Tage hier im Spiel sein, bevor du jemanden umlegen kannst!<br />\r\n<br />\r\nWenn das Verbrechen erfolgreich war, bekommst du das gesamte Geld vom Opfer!<br/>\r\n<br />\r\n<h2>Waffenausrüstung</h2>\r\nUm ein guter Killer zu sein, ist die Ausrüstung extrem wichtig!<br />\r\n<br />\r\n<b>Liste der Waffen:</b>\r\n<ol class=\"dd_right\">\r\n<li><b>Normale Pistole</b> - 100 000 $</li>\r\n<li><b>Bereta Px4 Storm</b> - 500 000 $</li>\r\n<li><b>Desert Eagle</b> - 1 000 000 $</li>\r\n<li><b>Heckler &amp; Koch MP5</b> - 3 000 000 $</li>\r\n<li><b>Heckler &amp; Koch G36</b> - 6 000 000 $</li>\r\n<li><b>AK-47</b> - 9 000 000 $</li>\r\n<li><b>DSR-50 Sniper Rifle</b> - 12 000 000 $</li>\r\n</ol>\r\n<br />\r\n<b>Liste zum Schutz:</b>\r\n<ol class=\"dd_right\">\r\n<li><b>Pfefferspray</b> - 1 000 000 $</li>\r\n<li><b>Wachhund</b> - 2 000 000 $</li>\r\n<li><b>Kugelsichere Weste</b> - 5 000 000 $</li>\r\n<li><b>Bodyguard</b> - 7 500 000 $</li>\r\n<li><b>Sicherheitsunternehmen</b> - 15 000 000 $</li>\r\n</ol>\r\n<br />\r\n<br />\r\n<h2>Bunker</h2>\r\nWenn du 100% igen Schutz wünschst, wenn der Attentäter aktiv ist, ist der Bunker die beste Lösung!<br />\r\nWenn du im Bunker bist, kannst du nicht angreifen, aber du kannst auch nicht angegriffen werden!\r\n<br />\r\n<br />\r\nDu kannst in jeder Stadt einen Bunker besitzen!<br />\r\n<br />\r\nWenn du dir keinen eigenen Bunker kaufen kannst, dann kannst du andere Mitspieler fragen, ob du deren Bunker benutzen darfst. Hast du einen eignen Bunker, dann kannst du anderen Mitspielern erlauben deinen Bunker zu nutzen.'),
(9, 'Raub', 'Für jeden erfolgreichen Raubüberfall erhältst du Geld und Rangpunkte. Wenn du keinen Raubüberfall planst, bekommst du nur die Erfahrung.\r\n<br />\r\n<br />\r\nMit jedem Raubüberfall steigt Gefahrenstatus.'),
(10, 'Autos / Garage', '<h2>Autodiebstahl</h2>\r\nAutodiebstahl funktioniert prinzipiell genauso wie Raubüberfälle. Wenn du es schaffst, ein Auto zu klauen, kommt es in deine Garage.\r\n<br /><br />\r\n<h2>Garage</h2>\r\nDu hast in jeder Stadt eine Garage. Von hier aus kannst du deine Autos bewegen. Du kannst sie verkaufen oder reparieren.'),
(11, 'Erpressung', 'Mit dieser Funktion kannst du andere Spieler erpressen!<br />\r\nUm einen Spieler erpressen zu können, muss er sich im gleichen Ort wie du befinden und das von dir gewählten Rang erreicht haben.<br />\r\n<br />\r\nSpieler mit dem Rang <b>Neuling</b> und <b>Zänker</b> können nicht erpresst werden. Und diese Spieler können auch selbst ihrerseits nicht erpressen.<br />'),
(12, 'Organisierter Raub', 'Beim \"organisierten Raub / Raub planen\" ist folgendes zu beachten:<br/><br/>\r\nMan kann ein 4-Spieler-Team bilden, um einen größeren Raub zu begehen.<br />\r\nEin organisierter Raubüberfall kann im Abstand von 12 Stunden durchgeführt werden.<br />\r\n<br />\r\nUm einen organisierten Raubüberfall zu starten, muss man mindestens den Rang <b>Assassin</b> haben. Raubkollegen können unabhängig vom Rang beitreten!<br />\r\n<br />\r\n<br />\r\nDas Team besteht aus 4 verschiedenen Positionen:\r\n<ul>\r\n<li><b>Anführer</b> - Plant den organisierten Raub.</li>\r\n<li><b>Fluchtfahrer</b> - Fährt das Team nach dem Raub in Sicherheit.</li>\r\n<li><b>Durchbruchfahrer</b> - Fährt das Auto gegen das Gebäude, durchschlägt die Wand für den Raub.</li>\r\n<li><b>Schütze</b> - Hält die Geiseln in Schach.</li>\r\n</ul>\r\n'),
(13, 'Knast', 'Wenn du im im \"Knast / Gefängnis\" bist, hast du zu verschiedenen Spielfunktionen keinen Zugang mehr.<br />\r\nDu kannst versuchen, schneller zu rauszukommen , indem du den Wachen Bestechungsgelder gibst und versuchst sie zu schmieren.<br />\r\nMan kann das Gefängnis auch sofort und sicher für 10 Coins verlassen.'),
(14, 'Krankenhaus', 'Wenn du Gesundheit verloren hast, kannst du dich im Krankenhaus gegen eine Geldsumme behandeln lassen und die Gesundheit wieder aufbauen. Während des Krankenhausaufenthalts kannst du im Spiel keine illegalen Aktionen ausführen.'),
(15, 'Auktionen', 'Im Auktionshaus kannst du Atikel von Auktionsunternehmen oder Familien verkaufen oder kaufen.<br><br> Auktionen können mit Geld oder Coins durchgeführt werden.'),
(16, 'Karte / Reisen', 'Mit Hilfe der Karte kannst du in eine andere Stadt im Spiel reisen.<br />\r\n<br />\r\nDer orange Punkt auf der Karte zeigt dir, in welcher Stadt du gerade bist.<br />'),
(17, 'Bank', 'Auf der Bank kannst du dein Geld sicher deponieren, Zinsen erhalten oder Geld überweisen.<br />\r\n<br />\r\nBevor du auf die Bank zugreifen kannst, musst du ein Bankkonto bei einer Bankgesellschaft beantragen.<br />\r\n<br />\r\nDu kannst Geld und Coins über die Bank an andere Spieler überweisen.<br />\r\n<br />\r\n<u>Regeln:</u><br />\r\nJede Bank legt ihre eigenen Regeln fest.'),
(18, 'Firmen', '<b>Es gibt 3 verschiedene Geschäftsarten:</b>\r\n<ul>\r\n<li>Bankunternehmen</li>\r\n<li>Zeitungsverlag</li>\r\n<li>Munitions-Fabrik</li>\r\n</ul>\r\n<br />\r\nDie <u>Bank</u> bietet ihren Kunden Bankkonten an und verdient Geld aus Provisionen.<br />\r\n<br />\r\nDie <u>Zeitungsverlage</u> benötigen Journalisten, um Artikel zu veröffentlichen. Das Geld kommt durch den Verkauf von Zeitungen.<br />\r\n<br />\r\nDie <u>Munitions-Fabrik</u> befasst sich mit der Herstellung von Munition. Das Geld kommt durch den Verkauf von Munition.'),
(19, 'Spiele', 'Es gibt verschiedene Arten von Glücksspielen:\r\n<ul>\r\n<li>Rubbellose</li>\r\n<li>Blackjack</li>\r\n<li>Lotterie</li>\r\n<li>Straßenrennen</li>\r\n<li>Coinroll</li>\r\n<li>Roulette</li>\r\n<li>Zahlenraten</li>\r\n<li>Knacke den Safe</li>\r\n<li>Glücksrad</li>\r\n<li>Spielautomat</li>\r\n</ul>\r\n\r\n<u>HINWEIS:</u> Wenn du eine BlackJack-Runde startest, kannst du die Karten erst sehen, wenn ein anderer Spieler (der Gegner) der Runde beitritt.'),
(20, 'BB-Codes', '<p><b>Bilder</b></p>\r\n<p>\r\nCode: [img]adresa[/img]<br />\r\nBeispiel: [img]https://www.unterweltmafia.de/game/images/default_profileimage.png[/img]\r\n</p>\r\n<p><b>Bilder(Vorschaubild - 100x100 px)</b></p>\r\n<p>\r\nCode: [thumbnail]adresa[/thumbnail]<br />\r\nBeispiel: [thumbnail]http://www.unterweltmafia.de/game/images/default_profileimage.png[/thumbnail]\r\n</p>\r\n<p><b>Text Fett</b></p>\r\n<p>\r\nCode: [b]text[/b]<br />\r\n<b>text</b>\r\n</p>\r\n<p><b>Text Kursiv</b></p>\r\n<p>\r\nCode: [i]text[/i]<br />\r\n<i>text</i>\r\n</p>\r\n<p><b>Text Unterstrich</b></p>\r\n<p>\r\nCode: [u]text[/u]<br />\r\n<u>text</u>\r\n</p>\r\n<p><b>Text Durchgestrichen</b></p>\r\n<p>\r\nCode: [s]text[/s]<br />\r\n<s>text</s>\r\n</p>\r\n<p><b>Text Farbe</b></p>\r\n<p>\r\nCode: [color=#Farbcode]text[/color] (<a href=\"http://html-color-codes.com/\" target=\"_blank\">Codes</a>)<br />\r\nBeispiel: [color=#FF6600]text[/color]<br />\r\n<span style=\"color: #FF6600;\">text</span>\r\n</p>\r\n<p><b>Schatten</b></p>\r\n<p>\r\nCode: [shadow]text[/shadow]\r\n</p>\r\n<p><b>Textgröße</b></p>\r\n<p>\r\nCode: [size=dimensiune]text[/size]<br />\r\nBeispiel: [size=16]text[/size]<br />\r\n<span style=\"font-size: 20px;\">text</span>\r\n</p>\r\n<p><b>Link-URL</b></p>\r\n<p>\r\nCode: [url=URL]text[/url]<br />\r\nBeispiel: [url=/game/?side=startside]Startseite[/url]<br />\r\n<a href=\"/game/?side=startside\">Startseite</a>\r\n</p>\r\n<p><b>Zentrierung</b></p>\r\n<p>\r\nCode: [center]text[/center]<br />\r\n<center>text</center>\r\n</p>\r\n<p><b>Spieler-Links</b></p>\r\n<p>\r\nCode ohne Leerzeichen: @Admin<br />\r\nCode mit Leerzeichen: @[Admin 2]<br />\r\n@<a href=\"/game/s/Admin\" class=\"global_playerlink\">Admin</a>\r\n</p>\r\n<p><b>Zitat</b></p>\r\n<p>\r\nCode: [quote]text[/quote]\r\n<div class=\"quote\"><div class=\"q_top\">Zitat</div><div class=\"q_text\">text</div></div>\r\n</p>\r\n<p><b>Code</b></p>\r\n<p>\r\nCode: [code]text[/code]\r\n<div class=\"code\"><div class=\"c_top\">Code</div><div class=\"c_text\">text</div></div>\r\n</p>\r\n<p><b>YouTube</b></p>\r\n<p>\r\nDie Videoclip-ID auf deinem YouTube.<br />\r\nCode: https://www.youtube.com/watch?v=VideoID\r\n</p>\r\n<p><b>Horizontale Linie</b></p>\r\n<p>\r\nCode: [hr /] oder[hr_big /]\r\n</p>\r\n<div class=\"hr\"></div>\r\n<div class=\"hr big\"></div>\r\n<p><b>BB-Code <u>nur</u> für Profil:</b></p>\r\n<p>\r\n<b>[level rang]</b> - Zeigt den Rang an<br />\r\n<b>[level prozent]</b> - Zeigt den Fortschritt des Rangs an<br />\r\n<b>[level balken]</b> - Zeigt den Fortschrittsbalken an<br />\r\n<br />\r\n<b>[prozentuale Gefahr]</b> - Zeigt den Gefahrenstatus an<br />\r\n<b>[Gefahrenbalken]</b> - Zeigt den Gefahrenbalken an<br />\r\n<br />\r\n<b>[Leben Prozent]</b> - Zeigt Gesundheitsprozente an<br />\r\n<b>[Lebensleiste]</b> - Zeigt den Gesundheitsbalken an<br />\r\n<br />\r\n<b>[Bargeld]</b> - Zeigt dein Bargeld an<br />\r\n<b>[Bankgeld]</b> - Zeigt dein Geld auf der Bank an<br />\r\n<b>[Gesamtgeld]</b> - Zeigt an wie viel Geld du insgesamt hast<br />\r\n<br />\r\n<b>[Ausbruch]</b> - Zeigt deine erfolgreichen Ausbrüche an<br />\r\n</p>'),
(21, 'Smileys', '<div style=\"width: 320px; margin: 0px auto;\">\r\n<div class=\"left\" style=\"width: 150px;\">\r\n<table class=\"table center\">\r\n<thead>\r\n<tr class=\"small\">\r\n<td>Code</td>\r\n<td>Smiley</td>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr class=\"c_1\">\r\n<td>:)</td>\r\n<td><img src=\"images/smileys/1.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:(</td>\r\n<td><img src=\"images/smileys/2.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>;)</td>\r\n<td><img src=\"images/smileys/3.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:D</td>\r\n<td><img src=\"images/smileys/4.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>;;)</td>\r\n<td><img src=\"images/smileys/5.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>&gt;:D&lt;</td>\r\n<td><img src=\"images/smileys/6.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:-/</td>\r\n<td><img src=\"images/smileys/7.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:X</td>\r\n<td><img src=\"images/smileys/8.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:x</td>\r\n<td><img src=\"images/smileys/9.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:\"&gt;</td>\r\n<td><img src=\"images/smileys/10.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:P</td>\r\n<td><img src=\"images/smileys/11.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:*</td>\r\n<td><img src=\"images/smileys/12.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>=((</td>\r\n<td><img src=\"images/smileys/13.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:O</td>\r\n<td><img src=\"images/smileys/14.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>X(</td>\r\n<td><img src=\"images/smileys/15.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>B-)</td>\r\n<td><img src=\"images/smileys/16.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>&gt;:P</td>\r\n<td><img src=\"images/smileys/17.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>&gt;:)</td>\r\n<td><img src=\"images/smileys/18.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:((</td>\r\n<td><img src=\"images/smileys/19.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>O:-)</td>\r\n<td><img src=\"images/smileys/20.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:))</td>\r\n<td><img src=\"images/smileys/21.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>brb</td>\r\n<td><img src=\"images/smileys/22.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>(*)</td>\r\n<td><img src=\"images/smileys/23.png\" alt=\"\" /></td>\r\n</tr><tr class=\"c_2\">\r\n<td>:|</td>\r\n<td><img src=\"images/smileys/24.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:-s</td>\r\n<td><img src=\"images/smileys/25.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>\\:D/</td>\r\n<td><img src=\"images/smileys/26.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:-&amp;</td>\r\n<td><img src=\"images/smileys/27.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>TV</td>\r\n<td><img src=\"images/smileys/28.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:-q</td>\r\n<td><img src=\"images/smileys/29.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>;))</td>\r\n<td><img src=\"images/smileys/30.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>=D&gt;</td>\r\n<td><img src=\"images/smileys/31.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:-|</td>\r\n<td><img src=\"images/smileys/32.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:up:</td>\r\n<td><img src=\"images/smileys/up.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:well:</td>\r\n<td><img src=\"images/smileys/well.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:what:</td>\r\n<td><img src=\"images/smileys/what.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:XD:</td>\r\n<td><img src=\"images/smileys/XD.png\" alt=\"\" /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<div class=\"left\" style=\"width: 150px; margin-left: 20px;\">\r\n<table class=\"table center\">\r\n<thead>\r\n<tr class=\"small\">\r\n<td>Code</td>\r\n<td>Smiley</td>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr class=\"c_1\">\r\n<td>:mowhaha:</td>\r\n<td><img src=\"images/smileys/mowhaha.gif\" alt=\"\" /></td>\r\n</tr><tr class=\"c_2\">\r\n<td>:geek:</td>\r\n<td><img src=\"images/smileys/nerd.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:nooo:</td>\r\n<td><img src=\"images/smileys/nooo.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:oeh:</td>\r\n<td><img src=\"images/smileys/oeh.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:angel:</td>\r\n<td><img src=\"images/smileys/angel.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:oh:</td>\r\n<td><img src=\"images/smileys/oh.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:ohno:</td>\r\n<td><img src=\"images/smileys/Ohno.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:ohnoes:</td>\r\n<td><img src=\"images/smileys/ohnoes.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:ohreally:</td>\r\n<td><img src=\"images/smileys/ohreally.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:ohyes:</td>\r\n<td><img src=\"images/smileys/ohyes.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>-.-</td>\r\n<td><img src=\"images/smileys/oke.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:omg:</td>\r\n<td><img src=\"images/smileys/omg.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:omygod:</td>\r\n<td><img src=\"images/smileys/omygod.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:O</td>\r\n<td><img src=\"images/smileys/openmouth.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:pacman:</td>\r\n<td><img src=\"images/smileys/pacman.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:pfff:</td>\r\n<td><img src=\"images/smileys/pfff.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:P</td>\r\n<td><img src=\"images/smileys/PP.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:rawr:</td>\r\n<td><img src=\"images/smileys/Rawr.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:retard:</td>\r\n<td><img src=\"images/smileys/retard.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:retarded:</td>\r\n<td><img src=\"images/smileys/Retarded.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:roll:</td>\r\n<td><img src=\"images/smileys/roll.gif\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:(</td>\r\n<td><img src=\"images/smileys/sad.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:satisfied:</td>\r\n<td><img src=\"images/smileys/satisfied.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:shock:</td>\r\n<td><img src=\"images/smileys/shocked.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:sleep:</td>\r\n<td><img src=\"images/smileys/sleep.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:supergrin:</td>\r\n<td><img src=\"images/smileys/supergrin.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:superhappy:</td>\r\n<td><img src=\"images/smileys/superhappy.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:suspicious:</td>\r\n<td><img src=\"images/smileys/suspicious.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:tonguey:</td>\r\n<td><img src=\"images/smileys/tonguey.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:toobad:</td>\r\n<td><img src=\"images/smileys/toobad.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:twisted:</td>\r\n<td><img src=\"images/smileys/twisted.gif\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_2\">\r\n<td>:yee:</td>\r\n<td><img src=\"images/smileys/Yee.png\" alt=\"\" /></td>\r\n</tr>\r\n<tr class=\"c_1\">\r\n<td>:you:</td>\r\n<td><img src=\"images/smileys/you.png\" alt=\"\" /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<div class=\"clear\"></div>\r\n</div>'),
(22, 'Missionen', '<h2>Missionen</h2>\r\nMissionen sind eine gute Möglichkeit, Geld zu verdienen und Erfahrungen zu sammeln!!<br />\r\n<h2>Schnelle Missionen</h2>\r\nSchnelle Missionen sind wie Missionen, aber die Aufgaben sind leichter (und auch die Einnahmen sind geringer)'),
(23, 'Belohungen', 'Als Belohnungen kann es kleine Geldsummen und Gutschriften im Spiel geben, die für den Besuch bestimmter Websites gutgeschrieben werden. Es gibt 5 Belohnungen, jede kann einmal am Tag erhalten werden.<br><br>Momentan beträgt jede Belohnung 2500 $. Aber die Belohnungswerte können geändert werden, ohne dass die Änderung angekündigt wird. Der Belohnungsmodus ist nicht immer aktiv.'),
(24, 'Haus', 'Du kannst dein eigenes Haus kaufen, das dir verschiedene Vorteile bietet, je mach Ausstattung wie z. B. \"Fitnessraum\" oder \"Keller\".<br><br>\r\n\r\nEin <u>Fitnessraum</u> hilft dir bei \"Kämpfe\" schneller zu trainieren.<br><br>\r\nEin <u>Keller</u> bietet die Möglichkeit, Drogen herzustellen, die du dann verkaufen kannst.<br><br>\r\n\r\n<i>HINWEIS: Je teurer die Häuser mit Keller sind, desto mehr Marihuana kann im Keller produziert werden. Man kann Marihuana jedoch nicht an zwei oder mehreren verschiedenen Orten gleichzeitig produzieren.</i>'),
(26, 'Aktien', 'An der <i>Börse</i> kann man Aktien bestehender Unternehmen kaufen. Die Aktienkurse werden alle 24-48 Stunden aktualisiert, was bedeutet, dass der Aktienkurs steigen kann und du die Aktien zu einem höheren Preis verkaufen kannst(dies bringt dir Gewinn), aber es ist auch möglich, dass der Wert der Aktie fällt, was bedeutet, dass du Geld aus dem investierten Eigenkapital verlieren kannst.<br><br> \r\n<b>WARNUNG:</b> Wenn ein Unternehmen, dessen Aktien du gekauft hast, in Konkurs geht, gehen alle von dir gekauften Aktien verloren. Es besteht also das Risiko, Geld im Eigenkapital zu verlieren.'),
(27, 'Tipps and Tricks', 'Wenn du neu hier bist und du dich noch ein bisschen zurecht finden möchtest, haben wir hier ein paar Tipps und Tricks für dich:\r\n<br><br><br>\r\n1) Für den Anfang kannst du etwas Geld verdienen, indem du einfach auf die 5 Belohnungen klickst. Genauer gesagt, du bekommst unterschiedliche Geldbeträge, wenn du Partnerseiten besuchast. Gehe dazu einfach auf die Startseite und klicke auf die 5 Schaltflächen, die von 1 bis 5 nummeriert sind. Du kannst diese Prämien täglich erhalten.<br><br>\r\n2) Beginne Raubüberfälle und Autodiebstähle, auch wenn du am Anfang öfter von der Polizei erwischt wirst, die Erfahrung und somit die Chancen steigen mit jedem Versuch und der Rangfortschritt schreitet voran.\r\n<br><br>\r\n3) Wenn du in den Raubüberfällen sicher geworden bist und die gut meisterst, schließe Missionen ab. Es gibt normale Missionen und Kurzzeitmissionen, die dir in kurzer Zeit viel Geld einbringen können.'),
(28, 'Begenzter Zugang', 'Wenn du nur eingeschränkten Zugriff auf das Spiel hast, kann es an zwei Möglichkeiten liegen:<br>\r\n<ul>\r\n<li>Dein Spielkonto hat 0% Gesundheit (Du wurdest von einem anderen Spieler umgelegt)</li>\r\n<li>Ein Administrator hat dein Spielerkonto gesperrt</li>\r\n</ul><br>\r\n\r\nWenn du von einem Spieler umgelegt wurdest, kannst du dein Spielerkonto wieder reaktivieren, indem du einen bestimmten Betrag an Guthaben eintauschst. <br> Wenn du kein Guthaben hast, kannst du einen anderen Spielernamen wählen und das Spiel von Grund auf neu starten.<br><br>Bist du der Meinung dein Spielerkonnto wurde zu unrecht gesperrt, wende dich an einen Administrator.'),
(29, 'Coins', 'Coins sind eine spezielle Währung des Spiels, die dir verschiedene zusätzliche Vorteile bieten. Diese können entweder durch Rekrutierung von Spielern(Freunde einladen) oder durch Spenden erworben werden.<br><br>\r\nWenn du Coins für die Rekrutierung von Spielern sammeln möchtest, musst du andere Spieler, z.B. deine Freunde, über den speziellen Link <a href=\"/game/?side=min_side&a=ref&b=main\">Einladen</a>. Für jeden eingeladenen Spieler der den Rang des \"Zänker\" erreicht, bekommst du 20 Coins.<br><br>\r\n\r\nDu kannst auch durch eine Spende Coins erhalten. Dies kannst du unter \"<a href=\"/game/?side=magazin-credite\">Spenden</a>\" tun. Wenn du einen Gutscheincode erhalten hast, kannst du ihn auf derselben Seite einlösen.'),
(30, 'Bordell', 'In einem <b>Bordell</b> können Prostituierte angebworben werden, von denen man jede Stunde profitiert. Prostituierte können auf die Straße geschickt werden. Jede Prostituierte auf der Straße verdient 30$ pro Stunde oder sie können ins Bordell geschickt und verdienen 40$ pro Stunde.\r\n<br><br>');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `game_stats`
--

CREATE TABLE `game_stats` (
  `id` int(11) NOT NULL,
  `user_stats` text DEFAULT NULL,
  `player_stats` text DEFAULT NULL,
  `money_stats` text DEFAULT NULL,
  `online_stats` text DEFAULT NULL,
  `messages_stats` text DEFAULT NULL,
  `forum_stats` text DEFAULT NULL,
  `logevent_stats` text DEFAULT NULL,
  `last_updated` int(64) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `game_stats`
--

INSERT INTO `game_stats` (`id`, `user_stats`, `player_stats`, `money_stats`, `online_stats`, `messages_stats`, `forum_stats`, `logevent_stats`, `last_updated`) VALUES
(1, 'a:6:{s:9:\"num_total\";i:22;s:10:\"num_active\";i:22;s:15:\"num_deactivated\";i:0;s:12:\"regged_today\";i:0;s:16:\"regged_yesterday\";i:0;s:13:\"regged_2_days\";i:0;}', 'a:10:{s:9:\"num_total\";i:22;s:10:\"num_active\";i:22;s:8:\"num_dead\";i:0;s:15:\"num_deactivated\";i:0;s:8:\"ranklist\";a:3:{i:5;i:1;i:3;i:2;i:1;i:18;}s:12:\"ranklist_num\";i:21;s:12:\"regged_today\";i:0;s:16:\"regged_yesterday\";i:0;s:13:\"regged_2_days\";i:0;s:18:\"top_jail_breakouts\";a:0:{}}', 'a:7:{s:5:\"total\";i:9530648;s:12:\"players_cash\";i:9335721;s:12:\"players_bank\";i:104980;s:14:\"players_points\";i:650;s:8:\"business\";i:0;s:8:\"families\";i:0;s:8:\"coinroll\";i:89947;}', 'a:4:{s:14:\"highest_online\";a:2:{i:0;i:3;i:1;i:1558985086;}s:13:\"last_24_hours\";i:0;s:13:\"last_12_hours\";i:0;s:12:\"last_6_hours\";i:0;}', 'a:1:{s:9:\"num_total\";i:0;}', 'a:3:{s:17:\"threads_num_total\";i:2;s:15:\"posts_num_total\";i:1;s:9:\"num_total\";i:3;}', 'a:1:{s:9:\"num_total\";i:32;}', 1612773786);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `jail`
--

CREATE TABLE `jail` (
  `id` int(11) NOT NULL,
  `player` int(11) NOT NULL DEFAULT 0,
  `added` int(64) DEFAULT 0,
  `penalty` int(16) DEFAULT 0,
  `breakout_reward` bigint(11) DEFAULT 0,
  `active` int(1) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `logevents`
--

CREATE TABLE `logevents` (
  `id` int(11) NOT NULL,
  `user` int(11) DEFAULT 0,
  `player` int(11) DEFAULT 0,
  `type` int(8) DEFAULT 0,
  `data` text DEFAULT NULL,
  `time` int(64) DEFAULT 0,
  `read` int(1) DEFAULT 0,
  `archived` int(1) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `logevents`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lottery`
--

CREATE TABLE `lottery` (
  `id` int(11) NOT NULL,
  `started` int(64) DEFAULT 0,
  `ends` int(64) DEFAULT 0,
  `tickets` longtext DEFAULT NULL,
  `winners` longtext DEFAULT NULL,
  `buy_timestamps` longtext DEFAULT NULL,
  `active` int(1) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `lottery`
--

INSERT INTO `lottery` (`id`, `started`, `ends`, `tickets`, `winners`, `buy_timestamps`, `active`) VALUES
(1, 0, 0, NULL, 'a:0:{}', NULL, 0),
(2, 1560074783, 1560078383, NULL, 'a:0:{}', 'a:0:{}', 0),
(3, 1560176394, 1560179994, NULL, 'a:0:{}', 'a:0:{}', 0),
(4, 1560623798, 1560627398, NULL, 'a:0:{}', 'a:0:{}', 0),
(5, 1560775536, 1560779136, NULL, 'a:0:{}', 'a:0:{}', 0),
(6, 1560803163, 1560806763, NULL, 'a:0:{}', 'a:0:{}', 0),
(7, 1561022474, 1561026074, NULL, 'a:0:{}', 'a:0:{}', 0),
(8, 1561112729, 1561116329, NULL, 'a:0:{}', 'a:0:{}', 0),
(9, 1561142226, 1561145826, NULL, 'a:0:{}', 'a:0:{}', 0),
(10, 1561230090, 1561233690, ',3,3', 'a:1:{i:1;a:2:{s:6:\"player\";s:1:\"3\";s:5:\"money\";d:22500;}}', 'a:1:{i:3;i:1561230117;}', 0),
(11, 1561566417, 1561570017, NULL, 'a:0:{}', 'a:0:{}', 0),
(12, 1561636719, 1561640319, NULL, 'a:0:{}', 'a:0:{}', 0),
(13, 1561899945, 1561903545, NULL, 'a:0:{}', 'a:0:{}', 0),
(14, 1561975054, 1561978654, NULL, 'a:0:{}', 'a:0:{}', 0),
(15, 1561986545, 1561990145, NULL, 'a:0:{}', 'a:0:{}', 0),
(16, 1562001910, 1562005510, NULL, 'a:0:{}', 'a:0:{}', 0),
(17, 1589136291, 1589139891, NULL, 'a:0:{}', 'a:0:{}', 0),
(18, 1592931388, 1592934988, NULL, 'a:0:{}', 'a:0:{}', 0),
(19, 1593073326, 1593076926, NULL, 'a:0:{}', 'a:0:{}', 0),
(20, 1593459614, 1593463214, NULL, 'a:0:{}', 'a:0:{}', 0),
(21, 1598254208, 1598257808, NULL, 'a:0:{}', 'a:0:{}', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lozuri`
--

CREATE TABLE `lozuri` (
  `id` int(9) NOT NULL,
  `owner` int(11) DEFAULT 0,
  `place` int(8) DEFAULT 0,
  `lozuri` int(11) NOT NULL DEFAULT 50,
  `bank` bigint(11) DEFAULT 0,
  `bank_loss` bigint(11) DEFAULT 0,
  `bank_income` bigint(11) DEFAULT 0,
  `created` int(64) DEFAULT 0,
  `active` int(1) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `lozuri`
--

INSERT INTO `lozuri` (`id`, `owner`, `place`, `lozuri`, `bank`, `bank_loss`, `bank_income`, `created`, `active`) VALUES
(1, 3, 6, 4, 128010, 342000, 440000, 1560871324, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `marketplace`
--

CREATE TABLE `marketplace` (
  `id` int(11) NOT NULL,
  `seller` int(11) DEFAULT 0,
  `item_type` varchar(64) DEFAULT 'unknown',
  `amount` bigint(11) DEFAULT 0,
  `price` bigint(11) DEFAULT 0,
  `sold_to` int(11) DEFAULT 0,
  `sold_time` int(64) DEFAULT 0,
  `created` int(64) DEFAULT 0,
  `active` int(1) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `marketplace`
--

INSERT INTO `marketplace` (`id`, `seller`, `item_type`, `amount`, `price`, `sold_to`, `sold_time`, `created`, `active`) VALUES
(1, 3, 'bullets', 5, 1000, 0, 0, 1560803059, 0),
(2, 14, 'bullets', 5, 1000, 0, 0, 1589284514, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `title` varchar(258) NOT NULL,
  `players` varchar(258) DEFAULT '|0|',
  `creator` int(11) DEFAULT 0,
  `creator_ip` varchar(128) NOT NULL,
  `created` int(64) DEFAULT 0,
  `last_reply` int(64) DEFAULT 0,
  `deleted` int(1) DEFAULT 0,
  `views` text NOT NULL,
  `num_replies` int(11) DEFAULT 0,
  `last_player` int(11) DEFAULT 0,
  `del_players` varchar(255) NOT NULL DEFAULT '|0|'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `messages_replies`
--

CREATE TABLE `messages_replies` (
  `id` int(11) NOT NULL,
  `message_id` int(11) DEFAULT 0,
  `text` text NOT NULL,
  `creator` int(11) DEFAULT 0,
  `creator_ip` varchar(258) DEFAULT '',
  `created` int(64) DEFAULT 0,
  `deleted` int(1) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mission`
--

CREATE TABLE `mission` (
  `id` int(11) NOT NULL,
  `player` int(11) DEFAULT 0,
  `current_mission` int(8) DEFAULT 1,
  `missions_data` text NOT NULL,
  `minimissions` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `mission`
--

INSERT INTO `mission` (`id`, `player`, `current_mission`, `missions_data`, `minimissions`) VALUES
(1, 1, 1, 'a:1:{i:1;a:1:{s:7:\"objects\";a:2:{i:1;a:1:{s:13:\"num_completed\";i:5;}i:0;a:1:{s:16:\"completed_places\";a:1:{i:0;s:1:\"6\";}}}}}', 'a:0:{}'),
(2, 2, 1, 'a:0:{}', 'a:0:{}'),
(3, 3, 1, 'a:1:{i:1;a:1:{s:7:\"objects\";a:3:{i:1;a:1:{s:13:\"num_completed\";i:2;}i:0;a:1:{s:16:\"completed_places\";a:2:{i:0;s:1:\"2\";i:1;s:1:\"6\";}}i:3;a:1:{s:19:\"blackmailed_players\";a:1:{i:0;s:1:\"6\";}}}}}', 'a:0:{}'),
(4, 4, 1, 'a:0:{}', 'a:0:{}'),
(5, 5, 1, 'a:0:{}', 'a:0:{}'),
(6, 6, 1, 'a:1:{i:1;a:1:{s:7:\"objects\";a:2:{i:1;a:1:{s:13:\"num_completed\";i:0;}i:0;a:1:{s:16:\"completed_places\";a:1:{i:0;s:1:\"9\";}}}}}', 'a:0:{}'),
(7, 7, 1, 'a:0:{}', 'a:0:{}'),
(8, 8, 1, 'a:0:{}', 'a:0:{}'),
(9, 9, 1, 'a:1:{i:1;a:1:{s:7:\"objects\";a:1:{i:1;a:1:{s:13:\"num_completed\";i:0;}}}}', 'a:0:{}'),
(10, 10, 1, 'a:1:{i:1;a:1:{s:7:\"objects\";a:1:{i:1;a:1:{s:13:\"num_completed\";i:0;}}}}', 'a:1:{i:1;a:2:{s:6:\"active\";i:1;s:7:\"started\";i:1587805296;}}'),
(11, 11, 1, 'a:1:{i:1;a:1:{s:7:\"objects\";a:1:{i:1;a:1:{s:13:\"num_completed\";i:0;}}}}', 'a:0:{}'),
(12, 12, 1, 'a:0:{}', 'a:0:{}'),
(13, 13, 1, 'a:0:{}', 'a:0:{}'),
(14, 14, 1, 'a:1:{i:1;a:1:{s:7:\"objects\";a:3:{i:1;a:1:{s:13:\"num_completed\";i:6;}i:0;a:1:{s:16:\"completed_places\";a:5:{i:0;s:1:\"9\";i:1;s:1:\"3\";i:2;s:1:\"1\";i:3;s:1:\"4\";i:4;s:1:\"6\";}}i:3;a:1:{s:19:\"blackmailed_players\";a:1:{i:0;s:1:\"6\";}}}}}', 'a:2:{i:1;a:5:{s:6:\"active\";i:0;s:7:\"started\";i:0;s:4:\"data\";a:0:{}s:10:\"num_failed\";i:4;s:13:\"last_finished\";i:1589461582;}i:2;a:6:{s:6:\"active\";i:0;s:7:\"started\";i:0;s:4:\"data\";a:0:{}s:13:\"num_completed\";i:1;s:13:\"last_finished\";i:1589202271;s:7:\"rewards\";a:3:{s:4:\"cash\";i:5000000;s:10:\"rankpoints\";i:150;s:7:\"bullets\";i:100;}}}'),
(15, 15, 1, 'a:0:{}', 'a:0:{}'),
(16, 16, 1, 'a:0:{}', 'a:0:{}'),
(17, 17, 1, 'a:1:{i:1;a:3:{s:7:\"objects\";a:2:{i:1;a:1:{s:13:\"num_completed\";i:0;}i:0;a:1:{s:16:\"completed_places\";a:1:{i:0;s:1:\"5\";}}}s:7:\"started\";i:1;s:10:\"start_time\";i:1592942447;}}', 'a:1:{i:1;a:5:{s:6:\"active\";i:0;s:7:\"started\";i:0;s:4:\"data\";a:0:{}s:10:\"num_failed\";i:1;s:13:\"last_finished\";i:1593004933;}}'),
(18, 18, 1, 'a:1:{i:1;a:1:{s:7:\"objects\";a:1:{i:1;a:1:{s:13:\"num_completed\";i:0;}}}}', 'a:0:{}'),
(19, 19, 1, 'a:1:{i:1;a:1:{s:7:\"objects\";a:1:{i:1;a:1:{s:13:\"num_completed\";i:0;}}}}', 'a:0:{}'),
(20, 20, 1, 'a:1:{i:1;a:1:{s:7:\"objects\";a:1:{i:1;a:1:{s:13:\"num_completed\";i:0;}}}}', 'a:0:{}'),
(21, 21, 1, 'a:1:{i:1;a:1:{s:7:\"objects\";a:1:{i:1;a:1:{s:13:\"num_completed\";i:0;}}}}', 'a:0:{}'),
(22, 22, 1, 'a:1:{i:1;a:1:{s:7:\"objects\";a:1:{i:1;a:1:{s:13:\"num_completed\";i:0;}}}}', 'a:0:{}');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `name_changes`
--

CREATE TABLE `name_changes` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL DEFAULT 0,
  `playerid` int(11) NOT NULL DEFAULT 0,
  `old_name` varchar(255) NOT NULL,
  `new_name` varchar(255) NOT NULL,
  `date` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `author` int(11) NOT NULL DEFAULT 0,
  `added` int(11) NOT NULL DEFAULT 0,
  `title` varchar(500) DEFAULT '',
  `text` text DEFAULT NULL,
  `deleted` int(1) DEFAULT 0,
  `comments` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `newspapers`
--

CREATE TABLE `newspapers` (
  `id` int(11) NOT NULL,
  `b_id` int(11) DEFAULT 0,
  `title` varchar(256) DEFAULT '',
  `description` text NOT NULL,
  `price` bigint(11) DEFAULT 0,
  `articles` longtext NOT NULL,
  `pending_articles` text NOT NULL,
  `layout` int(8) DEFAULT 1,
  `created` int(64) DEFAULT 0,
  `published` int(1) DEFAULT 0,
  `publish_time` int(64) DEFAULT 0,
  `sold_to` varchar(6000) DEFAULT 'a:0:{}',
  `deleted` int(1) DEFAULT 0,
  `logo` varchar(64) DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `numbers_game`
--

CREATE TABLE `numbers_game` (
  `id` int(11) NOT NULL,
  `starter` int(11) DEFAULT 0,
  `players` varchar(2000) DEFAULT 'a:0:{}',
  `entry_sum` bigint(11) DEFAULT 0,
  `started` int(64) DEFAULT 0,
  `result` varchar(128) DEFAULT 'unknown',
  `number` int(11) NOT NULL DEFAULT 0,
  `active` int(1) DEFAULT 1,
  `winner_cash` int(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `payeer_points`
--

CREATE TABLE `payeer_points` (
  `Id` int(255) NOT NULL,
  `UserId` int(225) DEFAULT 0,
  `Payer_id` varchar(128) DEFAULT '',
  `Transaction` varchar(128) DEFAULT '',
  `Option` varchar(128) DEFAULT '',
  `Custom` varchar(128) DEFAULT '',
  `Is_Collected` int(1) DEFAULT 0,
  `Timestamp` int(64) DEFAULT 0,
  `Num_Points` int(64) DEFAULT 0,
  `Revenue` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `paypal_points`
--

CREATE TABLE `paypal_points` (
  `Id` int(11) NOT NULL,
  `UserId` int(11) DEFAULT 0,
  `Payer_id` varchar(128) DEFAULT '',
  `Payer_email` varchar(128) DEFAULT '',
  `Option` varchar(128) DEFAULT '',
  `Custom` varchar(128) DEFAULT '',
  `Is_Collected` int(1) DEFAULT 0,
  `Timestamp` int(64) DEFAULT 0,
  `Num_Points` int(64) DEFAULT 0,
  `Revenue` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `planned_crime`
--

CREATE TABLE `planned_crime` (
  `id` int(11) NOT NULL,
  `starter` int(11) DEFAULT 0,
  `crime_type` int(8) DEFAULT 0,
  `jobs` varchar(2000) DEFAULT 'a:0:{}',
  `hostages_state` int(11) DEFAULT 0,
  `money_result` int(11) DEFAULT 0,
  `active` int(1) DEFAULT 1,
  `started` int(1) DEFAULT 0,
  `started_time` int(64) DEFAULT 0,
  `ended` int(64) DEFAULT 0,
  `stopped_by` varchar(32) DEFAULT '',
  `status` int(8) DEFAULT 0,
  `last_times` varchar(1000) DEFAULT 'a:0:{}',
  `invites` varchar(2000) DEFAULT 'a:0:{}'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `player_kills`
--

CREATE TABLE `player_kills` (
  `id` int(11) NOT NULL,
  `player` int(11) DEFAULT 0,
  `victim` int(11) DEFAULT 0,
  `place` int(8) DEFAULT 1,
  `weapon_used` int(8) DEFAULT 0,
  `bullets_used` int(11) DEFAULT 0,
  `victim_protection` int(8) DEFAULT 0,
  `victim_result_health` int(32) DEFAULT 0,
  `result` varchar(100) DEFAULT 'unknown',
  `time` int(64) DEFAULT 0,
  `witness` int(11) DEFAULT 0,
  `cash_received` bigint(11) DEFAULT 0,
  `victim_family_name` varchar(64) DEFAULT '',
  `victim_rank` int(8) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `point_transfers`
--

CREATE TABLE `point_transfers` (
  `id` int(11) NOT NULL,
  `from` int(11) DEFAULT 0,
  `to` int(11) DEFAULT 0,
  `amount` bigint(11) DEFAULT 0,
  `price` bigint(11) DEFAULT 0,
  `completed` int(1) DEFAULT 0,
  `active` int(1) DEFAULT 1,
  `time` int(64) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `p_vouchers`
--

CREATE TABLE `p_vouchers` (
  `id` int(11) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `code` varchar(255) NOT NULL,
  `created` int(11) NOT NULL DEFAULT 0,
  `used` int(11) NOT NULL DEFAULT 0,
  `player` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `p_vouchers`
--

INSERT INTO `p_vouchers` (`id`, `points`, `code`, `created`, `used`, `player`) VALUES
(1, 1000, '5818-1747-6456-3711', 1560882548, 1560882582, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rec_ads`
--

CREATE TABLE `rec_ads` (
  `id` int(11) NOT NULL,
  `link` varchar(255) NOT NULL,
  `add_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `days` int(11) NOT NULL DEFAULT 0,
  `clicks_today` int(11) NOT NULL DEFAULT 0,
  `clicks` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sent_respect`
--

CREATE TABLE `sent_respect` (
  `id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `date` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sms_points`
--

CREATE TABLE `sms_points` (
  `Id` int(11) NOT NULL,
  `UserId` int(11) DEFAULT 0,
  `Operator` varchar(128) DEFAULT '',
  `Phone` varchar(32) NOT NULL,
  `Message_ID` varchar(128) DEFAULT '',
  `Date` int(11) DEFAULT 0,
  `Num_Points` int(11) DEFAULT 0,
  `Price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `Revenue` decimal(10,2) NOT NULL DEFAULT 0.00,
  `Currency` varchar(11) NOT NULL DEFAULT 'EUR'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `soknader`
--

CREATE TABLE `soknader` (
  `id` int(11) NOT NULL,
  `from_user` int(11) DEFAULT 0,
  `from_player` int(11) DEFAULT 0,
  `receiver` varchar(120) DEFAULT '0,0',
  `text` text DEFAULT NULL,
  `show_playerhistory` int(1) DEFAULT 0,
  `time_created` int(64) DEFAULT 0,
  `sent` int(64) DEFAULT 0,
  `confirmed` int(1) DEFAULT 0,
  `handled` int(1) DEFAULT 0,
  `deleted` int(1) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `soknader`
--

INSERT INTO `soknader` (`id`, `from_user`, `from_player`, `receiver`, `text`, `show_playerhistory`, `time_created`, `sent`, `confirmed`, `handled`, `deleted`) VALUES
(1, 3, 3, '0,0', 'Ein Test Applications/anfrage/Bewerbung', 0, 1560244675, 0, 0, 0, 1),
(2, 3, 3, '1,1', 'Ich bewerbe mich bei deiner Bank. Darf ich ?', 1, 1561645636, 1561645656, 2, 2, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stocks`
--

CREATE TABLE `stocks` (
  `id` int(11) NOT NULL,
  `business_type` varchar(16) DEFAULT 'game_business',
  `business_id` int(11) DEFAULT 0,
  `shares` text DEFAULT NULL,
  `changes` text DEFAULT NULL,
  `current_income` bigint(11) DEFAULT 0,
  `current_price` bigint(11) DEFAULT 0,
  `last_change_percent` int(8) DEFAULT 0,
  `last_change_time` int(64) DEFAULT 0,
  `last_income` bigint(11) DEFAULT 0,
  `created` int(64) DEFAULT 0,
  `active` int(1) DEFAULT 1,
  `last_price` bigint(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `stocks`
--

INSERT INTO `stocks` (`id`, `business_type`, `business_id`, `shares`, `changes`, `current_income`, `current_price`, `last_change_percent`, `last_change_time`, `last_income`, `created`, `active`, `last_price`) VALUES
(1, 'game_business', 1, 'a:1:{i:3;d:6;}', 'a:102:{i:0;a:3:{s:6:\"change\";d:0;s:8:\"newPrice\";d:5000;s:4:\"time\";i:1561815270;}i:1;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:4800;s:4:\"time\";i:1561988016;}i:2;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:4600;s:4:\"time\";i:1562161174;}i:3;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:4400;s:4:\"time\";i:1562337037;}i:4;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:4200;s:4:\"time\";i:1562581503;}i:5;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:4000;s:4:\"time\";i:1562853163;}i:6;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:3800;s:4:\"time\";i:1563137424;}i:7;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:3600;s:4:\"time\";i:1563393628;}i:8;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:3400;s:4:\"time\";i:1563574235;}i:9;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:3200;s:4:\"time\";i:1563802073;}i:10;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:3000;s:4:\"time\";i:1564049459;}i:11;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:2800;s:4:\"time\";i:1564423715;}i:12;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:2600;s:4:\"time\";i:1564676418;}i:13;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:2400;s:4:\"time\";i:1564951036;}i:14;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:2200;s:4:\"time\";i:1565193768;}i:15;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:2000;s:4:\"time\";i:1565724607;}i:16;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:1800;s:4:\"time\";i:1566036112;}i:17;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:1600;s:4:\"time\";i:1566810828;}i:18;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:1400;s:4:\"time\";i:1568400811;}i:19;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:1200;s:4:\"time\";i:1569743837;}i:20;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:1000;s:4:\"time\";i:1569918978;}i:21;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:800;s:4:\"time\";i:1570260737;}i:22;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:600;s:4:\"time\";i:1572618792;}i:23;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:400;s:4:\"time\";i:1575106931;}i:24;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:200;s:4:\"time\";i:1575287540;}i:25;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";d:0;s:4:\"time\";i:1575462820;}i:26;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1575640092;}i:27;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1575813640;}i:28;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1575986688;}i:29;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1576169317;}i:30;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1576342096;}i:31;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1576520674;}i:32;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1576696956;}i:33;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1576872613;}i:34;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1577046066;}i:35;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1577221835;}i:36;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1577397984;}i:37;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1577578193;}i:38;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1577752816;}i:39;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1577938954;}i:40;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1578122293;}i:41;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1578297269;}i:42;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1578475220;}i:43;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1578658411;}i:44;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1578832037;}i:45;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1579013450;}i:46;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1579201749;}i:47;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1579374535;}i:48;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1579551932;}i:49;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1579736215;}i:50;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1579912777;}i:51;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1580087210;}i:52;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1580263661;}i:53;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1580436749;}i:54;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1580615098;}i:55;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1580788331;}i:56;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1580961192;}i:57;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1581147725;}i:58;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1581323629;}i:59;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1581517821;}i:60;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1581699259;}i:61;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1581878150;}i:62;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1582061491;}i:63;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1582243301;}i:64;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1582418431;}i:65;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1582598316;}i:66;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1582776017;}i:67;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1582952481;}i:68;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1583133073;}i:69;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1583323716;}i:70;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1583502948;}i:71;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1583683899;}i:72;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1583865563;}i:73;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1584042904;}i:74;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1584218178;}i:75;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1584398802;}i:76;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1584582498;}i:77;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1584755411;}i:78;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1584931375;}i:79;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1585105282;}i:80;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1585278895;}i:81;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1585466451;}i:82;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1585639691;}i:83;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1585815361;}i:84;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1585994881;}i:85;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1586169568;}i:86;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1586347896;}i:87;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1586520860;}i:88;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1586700692;}i:89;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1586885970;}i:90;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1587060430;}i:91;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1587233348;}i:92;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1587407177;}i:93;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1587587542;}i:94;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1587764307;}i:95;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1587943358;}i:96;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1588118585;}i:97;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1588292115;}i:98;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1588473067;}i:99;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1588647879;}i:100;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1588830475;}i:101;a:3:{s:6:\"change\";i:200;s:8:\"newPrice\";i:0;s:4:\"time\";i:1589006052;}}', 0, 0, 200, 1589006052, 35101, 1561637086, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int(11) NOT NULL,
  `player` int(11) DEFAULT 0,
  `reservation` int(11) DEFAULT 0,
  `created` int(64) DEFAULT 0,
  `treated` int(1) DEFAULT 0,
  `treated_time` int(64) DEFAULT 0,
  `replies` varchar(20000) DEFAULT 'a:0:{}',
  `text` text NOT NULL,
  `title` varchar(35) DEFAULT '',
  `category` int(8) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `temporary`
--

CREATE TABLE `temporary` (
  `oid` int(11) NOT NULL,
  `id` varchar(128) NOT NULL,
  `playerid` varchar(255) DEFAULT '0',
  `area` varchar(128) DEFAULT '51',
  `expires` int(16) DEFAULT 3600,
  `time_added` int(64) DEFAULT 0,
  `extra` varchar(2000) DEFAULT 'a:0:{}',
  `active` int(1) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `[families]`
--

CREATE TABLE `[families]` (
  `id` int(11) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `boss` int(11) DEFAULT 0,
  `underboss` int(11) DEFAULT 0,
  `members` mediumtext DEFAULT NULL,
  `bank` bigint(11) DEFAULT 0,
  `bank_income` bigint(11) DEFAULT 0,
  `bank_loss` bigint(11) DEFAULT 0,
  `active` int(1) DEFAULT 1,
  `territories` varchar(384) DEFAULT 'a:0:{}',
  `image` varchar(128) DEFAULT '/game/images/default_family_logo.jpg',
  `player_kills` int(11) DEFAULT 0,
  `player_kills_fail` int(11) DEFAULT 0,
  `max_members_type` int(16) DEFAULT 0,
  `created` int(64) DEFAULT 0,
  `information` text NOT NULL,
  `donations` mediumtext DEFAULT NULL,
  `invites` varchar(20000) DEFAULT 'a:0:{}',
  `strength` int(64) DEFAULT 0,
  `total_rankpoints` bigint(11) DEFAULT 0,
  `last_attack` int(64) DEFAULT 0,
  `place` int(8) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `[log]`
--

CREATE TABLE `[log]` (
  `id` int(11) NOT NULL,
  `playerid` bigint(11) NOT NULL DEFAULT 0,
  `IP` varchar(120) DEFAULT '',
  `timestamp` int(11) DEFAULT 0,
  `side` varchar(400) DEFAULT '',
  `extra` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `[players]`
--

CREATE TABLE `[players]` (
  `id` int(11) NOT NULL,
  `userid` int(11) DEFAULT 0,
  `name` varchar(200) DEFAULT '',
  `online` int(11) DEFAULT 0,
  `last_active` int(11) DEFAULT 0,
  `IP_last` varchar(120) DEFAULT '',
  `IP_created_with` varchar(120) DEFAULT '',
  `created` int(11) DEFAULT 0,
  `cash` bigint(11) DEFAULT 0,
  `points` bigint(11) DEFAULT 0,
  `bank` bigint(11) DEFAULT 0,
  `bank_id` int(11) DEFAULT 0,
  `status` int(1) DEFAULT 0,
  `vip_days` int(64) NOT NULL DEFAULT 0,
  `profileimage` varchar(400) DEFAULT '/game/images/default_profileimage.png',
  `profiletext` text NOT NULL,
  `rank` int(10) DEFAULT 1,
  `rankpoints` bigint(11) DEFAULT 0,
  `rank_pos` bigint(11) DEFAULT 0,
  `live` int(11) DEFAULT 1,
  `health` int(11) DEFAULT 300,
  `null` int(11) NOT NULL DEFAULT 0,
  `wanted-level` int(11) DEFAULT 0,
  `respect` int(11) NOT NULL DEFAULT 0,
  `s_respect` int(11) NOT NULL DEFAULT 0,
  `level` int(10) DEFAULT 1,
  `bordel` varchar(2000) NOT NULL DEFAULT 'a:0:{}',
  `family` varchar(200) DEFAULT '',
  `jail_stats` varchar(2000) NOT NULL DEFAULT 'a:9:{s:13:"times_in_jail";i:0;s:12:"time_in_jail";i:0;s:16:"times_stole_keys";i:0;s:12:"times_bribed";i:0;s:10:"bribe_cash";i:0;s:16:"breakouts_earned";i:0;s:14:"breakouts_used";i:0;s:19:"breakouts_successed";i:0;s:16:"breakouts_failed";i:0;}',
  `jail_breakout_chance` int(11) DEFAULT 10,
  `jail_breakout_last` int(11) DEFAULT 0,
  `forumban` varchar(2000) DEFAULT '',
  `last_forum_posts` varchar(200) DEFAULT '0,0',
  `forum_num_posts` varchar(1000) DEFAULT '0,0',
  `forum_signature` text DEFAULT NULL,
  `antibot_enabled` int(1) DEFAULT 0,
  `antibot_last_try` int(11) DEFAULT 0,
  `message_last` int(11) DEFAULT 0,
  `messages_sent` int(11) DEFAULT 0,
  `antibot_data` varchar(2000) DEFAULT 'a:0:{}',
  `travel_last` int(64) DEFAULT 0,
  `travel_latency` int(16) DEFAULT 0,
  `kills` int(11) DEFAULT 0,
  `kills_failed` int(11) DEFAULT 0,
  `hospital_data` varchar(1000) DEFAULT 'a:0:{}',
  `weapon` int(8) DEFAULT 0,
  `protection` int(8) DEFAULT 0,
  `bullets` bigint(11) DEFAULT 0,
  `weapons` varchar(1000) DEFAULT 'a:0:{}',
  `last_weapon_training` int(64) DEFAULT 0,
  `rankboost` varchar(2000) DEFAULT 'a:0:{}',
  `last_planned_crime` int(64) DEFAULT 0,
  `show_jail_logevents` int(1) DEFAULT 1,
  `profile_music` varchar(255) DEFAULT NULL,
  `play_profile_music` int(1) DEFAULT 1,
  `crew_note` text DEFAULT NULL,
  `killpoints` int(11) DEFAULT 0,
  `houses` text NOT NULL,
  `kill_protection` int(1) DEFAULT 1,
  `last_visitors` varchar(2000) DEFAULT '',
  `c_name_r` int(11) NOT NULL DEFAULT 0,
  `ruleta` int(64) NOT NULL DEFAULT 0,
  `roata_noroc` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `[players]`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `[sessions]`
--

CREATE TABLE `[sessions]` (
  `id` int(11) NOT NULL,
  `Userid` varchar(32) NOT NULL,
  `IP` varchar(120) DEFAULT '',
  `User_agent` varchar(1000) DEFAULT '',
  `Time_start` int(11) DEFAULT 0,
  `Last_updated` int(11) DEFAULT 0,
  `Expires` int(11) DEFAULT 3600,
  `Active` int(1) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `[sessions]`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `[users]`
--

CREATE TABLE `[users]` (
  `id` int(11) NOT NULL,
  `email` varchar(132) NOT NULL DEFAULT '',
  `pass` varchar(2000) DEFAULT NULL,
  `online` int(11) DEFAULT 0,
  `last_active` int(11) DEFAULT 0,
  `reg_time` int(11) DEFAULT 0,
  `IP_regged_with` varchar(132) DEFAULT 'Unknown',
  `IP_last` varchar(132) DEFAULT 'Unknown',
  `userlevel` int(1) DEFAULT 1,
  `hasPlayer` int(1) DEFAULT 0,
  `miniapps_last` varchar(200) DEFAULT 'spillerinfo',
  `small_header` int(1) DEFAULT 0,
  `forum_view_signatures` int(1) DEFAULT 1,
  `forum_replies_per_page` int(32) DEFAULT 15,
  `smart_menu` varchar(4000) DEFAULT 'a:0:{}',
  `use_smartMenu` int(1) DEFAULT 0,
  `enlisted_by` int(11) DEFAULT 0,
  `chat_isOpen` int(1) DEFAULT 0,
  `u_chat` int(11) NOT NULL DEFAULT 0,
  `menuSort` varchar(2048) DEFAULT 'a:0:{}',
  `register_source` varchar(500) DEFAULT 'default',
  `ads_hideUntil` int(64) DEFAULT 0,
  `fb_liked` int(11) NOT NULL DEFAULT 0,
  `aff_id` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Daten für Tabelle `[users]`
--


--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `admin_config`
--
ALTER TABLE `admin_config`
  ADD UNIQUE KEY `config_name` (`config_name`);

--
-- Indizes für die Tabelle `antibot_sessions`
--
ALTER TABLE `antibot_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `auctions`
--
ALTER TABLE `auctions`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `bank_clients`
--
ALTER TABLE `bank_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `bank_interests`
--
ALTER TABLE `bank_interests`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `bank_transfers`
--
ALTER TABLE `bank_transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `blackjack`
--
ALTER TABLE `blackjack`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `blackmail`
--
ALTER TABLE `blackmail`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `brekk`
--
ALTER TABLE `brekk`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `bulletbuy_sessions`
--
ALTER TABLE `bulletbuy_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `bunker`
--
ALTER TABLE `bunker`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `businesses`
--
ALTER TABLE `businesses`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `business_log`
--
ALTER TABLE `business_log`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `car_races`
--
ALTER TABLE `car_races`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `car_theft`
--
ALTER TABLE `car_theft`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `changelog`
--
ALTER TABLE `changelog`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `coinroll`
--
ALTER TABLE `coinroll`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `coins_packs`
--
ALTER TABLE `coins_packs`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `countrylist`
--
ALTER TABLE `countrylist`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `cron_tab`
--
ALTER TABLE `cron_tab`
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `deactivations`
--
ALTER TABLE `deactivations`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `detective`
--
ALTER TABLE `detective`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `family_attacks`
--
ALTER TABLE `family_attacks`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `family_businesses`
--
ALTER TABLE `family_businesses`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `family_log`
--
ALTER TABLE `family_log`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `fighting`
--
ALTER TABLE `fighting`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `forum_reports`
--
ALTER TABLE `forum_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `forum_topics`
--
ALTER TABLE `forum_topics`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `game_economy`
--
ALTER TABLE `game_economy`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `game_faq`
--
ALTER TABLE `game_faq`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `game_stats`
--
ALTER TABLE `game_stats`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `jail`
--
ALTER TABLE `jail`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `logevents`
--
ALTER TABLE `logevents`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lottery`
--
ALTER TABLE `lottery`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lozuri`
--
ALTER TABLE `lozuri`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `marketplace`
--
ALTER TABLE `marketplace`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `players` (`players`);

--
-- Indizes für die Tabelle `messages_replies`
--
ALTER TABLE `messages_replies`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `mission`
--
ALTER TABLE `mission`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `name_changes`
--
ALTER TABLE `name_changes`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `newspapers`
--
ALTER TABLE `newspapers`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `numbers_game`
--
ALTER TABLE `numbers_game`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `payeer_points`
--
ALTER TABLE `payeer_points`
  ADD PRIMARY KEY (`Id`);

--
-- Indizes für die Tabelle `paypal_points`
--
ALTER TABLE `paypal_points`
  ADD PRIMARY KEY (`Id`);

--
-- Indizes für die Tabelle `planned_crime`
--
ALTER TABLE `planned_crime`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `player_kills`
--
ALTER TABLE `player_kills`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `point_transfers`
--
ALTER TABLE `point_transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `p_vouchers`
--
ALTER TABLE `p_vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `rec_ads`
--
ALTER TABLE `rec_ads`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `sent_respect`
--
ALTER TABLE `sent_respect`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `sms_points`
--
ALTER TABLE `sms_points`
  ADD PRIMARY KEY (`Id`);

--
-- Indizes für die Tabelle `soknader`
--
ALTER TABLE `soknader`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `temporary`
--
ALTER TABLE `temporary`
  ADD PRIMARY KEY (`oid`);

--
-- Indizes für die Tabelle `[families]`
--
ALTER TABLE `[families]`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `[log]`
--
ALTER TABLE `[log]`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `[players]`
--
ALTER TABLE `[players]`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `[sessions]`
--
ALTER TABLE `[sessions]`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `[users]`
--
ALTER TABLE `[users]`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `antibot_sessions`
--
ALTER TABLE `antibot_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT für Tabelle `auctions`
--
ALTER TABLE `auctions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `bank_clients`
--
ALTER TABLE `bank_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `bank_interests`
--
ALTER TABLE `bank_interests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `bank_transfers`
--
ALTER TABLE `bank_transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `blackjack`
--
ALTER TABLE `blackjack`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT für Tabelle `blackmail`
--
ALTER TABLE `blackmail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `brekk`
--
ALTER TABLE `brekk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT für Tabelle `bulletbuy_sessions`
--
ALTER TABLE `bulletbuy_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `bunker`
--
ALTER TABLE `bunker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT für Tabelle `businesses`
--
ALTER TABLE `businesses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `business_log`
--
ALTER TABLE `business_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT für Tabelle `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=205;

--
-- AUTO_INCREMENT für Tabelle `car_races`
--
ALTER TABLE `car_races`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `car_theft`
--
ALTER TABLE `car_theft`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT für Tabelle `changelog`
--
ALTER TABLE `changelog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `coinroll`
--
ALTER TABLE `coinroll`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `coins_packs`
--
ALTER TABLE `coins_packs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `countrylist`
--
ALTER TABLE `countrylist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- AUTO_INCREMENT für Tabelle `deactivations`
--
ALTER TABLE `deactivations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `detective`
--
ALTER TABLE `detective`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `family_attacks`
--
ALTER TABLE `family_attacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `family_businesses`
--
ALTER TABLE `family_businesses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT für Tabelle `family_log`
--
ALTER TABLE `family_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `fighting`
--
ALTER TABLE `fighting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT für Tabelle `forum_replies`
--
ALTER TABLE `forum_replies`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `forum_reports`
--
ALTER TABLE `forum_reports`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `forum_topics`
--
ALTER TABLE `forum_topics`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `game_economy`
--
ALTER TABLE `game_economy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `game_faq`
--
ALTER TABLE `game_faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT für Tabelle `game_stats`
--
ALTER TABLE `game_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `jail`
--
ALTER TABLE `jail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT für Tabelle `logevents`
--
ALTER TABLE `logevents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT für Tabelle `lottery`
--
ALTER TABLE `lottery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT für Tabelle `lozuri`
--
ALTER TABLE `lozuri`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `marketplace`
--
ALTER TABLE `marketplace`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `messages_replies`
--
ALTER TABLE `messages_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `mission`
--
ALTER TABLE `mission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT für Tabelle `name_changes`
--
ALTER TABLE `name_changes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `newspapers`
--
ALTER TABLE `newspapers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `numbers_game`
--
ALTER TABLE `numbers_game`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `paypal_points`
--
ALTER TABLE `paypal_points`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `planned_crime`
--
ALTER TABLE `planned_crime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `player_kills`
--
ALTER TABLE `player_kills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `point_transfers`
--
ALTER TABLE `point_transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `p_vouchers`
--
ALTER TABLE `p_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `rec_ads`
--
ALTER TABLE `rec_ads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `sent_respect`
--
ALTER TABLE `sent_respect`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `sms_points`
--
ALTER TABLE `sms_points`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `soknader`
--
ALTER TABLE `soknader`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `temporary`
--
ALTER TABLE `temporary`
  MODIFY `oid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT für Tabelle `[families]`
--
ALTER TABLE `[families]`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `[log]`
--
ALTER TABLE `[log]`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=837;

--
-- AUTO_INCREMENT für Tabelle `[players]`
--
ALTER TABLE `[players]`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT für Tabelle `[sessions]`
--
ALTER TABLE `[sessions]`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=431;

--
-- AUTO_INCREMENT für Tabelle `[users]`
--
ALTER TABLE `[users]`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
