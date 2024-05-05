-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2024-05-05 09:49:48
-- 服务器版本： 5.7.43-log
-- PHP 版本： 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `gc_cdkey`
--
CREATE DATABASE IF NOT EXISTS `gc_cdkey` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gc_cdkey`;

-- --------------------------------------------------------

--
-- 表的结构 `cdkeys`
--

CREATE TABLE `cdkeys` (
  `cdkey` varchar(32) NOT NULL,
  `max_usage_count` int(8) NOT NULL DEFAULT '1',
  `add_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expire_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `items` json DEFAULT NULL,
  `is_valid` int(1) NOT NULL DEFAULT '1',
  `note` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `cdkey_history`
--

CREATE TABLE `cdkey_history` (
  `id` int(11) NOT NULL,
  `cdkey` varchar(32) NOT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `uid` int(11) NOT NULL,
  `usage_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `note` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转储表的索引
--

--
-- 表的索引 `cdkeys`
--
ALTER TABLE `cdkeys`
  ADD PRIMARY KEY (`cdkey`);

--
-- 表的索引 `cdkey_history`
--
ALTER TABLE `cdkey_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `CDKEY_VERIFY` (`cdkey`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `cdkey_history`
--
ALTER TABLE `cdkey_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 限制导出的表
--

--
-- 限制表 `cdkey_history`
--
ALTER TABLE `cdkey_history`
  ADD CONSTRAINT `CDKEY_VERIFY` FOREIGN KEY (`cdkey`) REFERENCES `cdkeys` (`cdkey`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
