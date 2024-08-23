-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2024-08-23 13:09:20
-- 服务器版本： 8.0.29
-- PHP 版本： 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb3 */;

--
-- 数据库： `example`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin_user`
--

CREATE TABLE `admin_user` (
  `id` int NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `mail` text NOT NULL,
  `last_login_time` int NOT NULL,
  `enable` int NOT NULL DEFAULT '1',
  `2fa` int NOT NULL DEFAULT '0',
  `2fakey` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- 转存表中的数据 `admin_user`
--

INSERT INTO `admin_user` (`id`, `username`, `password`, `mail`, `last_login_time`, `enable`, `2fa`, `2fakey`) VALUES
(1, 'admin', 'f447b20a7fcbf53a5d5be013ea0b15af', 'example@exa.exa', 1724343820, 1, 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `list_domain`
--

CREATE TABLE `list_domain` (
  `id` int NOT NULL,
  `domain` text NOT NULL,
  `servername` text NOT NULL,
  `location` text NOT NULL,
  `zoneid` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- 表的结构 `list_server`
--

CREATE TABLE `list_server` (
  `id` int NOT NULL,
  `servername` text NOT NULL,
  `server` text NOT NULL,
  `secretid` text NOT NULL,
  `secretkey` text NOT NULL,
  `enable` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- 转储表的索引
--

--
-- 表的索引 `admin_user`
--
ALTER TABLE `admin_user`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `list_domain`
--
ALTER TABLE `list_domain`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `list_server`
--
ALTER TABLE `list_server`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admin_user`
--
ALTER TABLE `admin_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `list_domain`
--
ALTER TABLE `list_domain`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `list_server`
--
ALTER TABLE `list_server`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
