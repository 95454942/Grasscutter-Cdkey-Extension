-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2024-06-01 12:43:02
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

-- --------------------------------------------------------

--
-- 表的结构 `cdkeys`
--

CREATE TABLE `cdkeys` (
  `cdkey` varchar(32) NOT NULL COMMENT '兑换码',
  `max_usage_count` int(8) NOT NULL DEFAULT '1' COMMENT '最大使用次数',
  `add_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '兑换码添加时间',
  `expire_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '过期时间\r\n0为永不过期',
  `items` json DEFAULT NULL COMMENT 'json数组形式的兑换奖励',
  `is_valid` int(1) NOT NULL DEFAULT '1' COMMENT '是否有效',
  `note` varchar(256) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `cdkey_history`
--

CREATE TABLE `cdkey_history` (
  `id` int(11) NOT NULL COMMENT '自增id主键',
  `cdkey` varchar(32) NOT NULL COMMENT '被使用的兑换码',
  `ip` varchar(15) DEFAULT NULL COMMENT '兑换者IP',
  `uid` int(11) NOT NULL COMMENT '游戏uid',
  `usage_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '被使用时间',
  `note` varchar(256) DEFAULT NULL COMMENT '备注'
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id主键';

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
