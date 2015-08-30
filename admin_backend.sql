-- phpMyAdmin SQL Dump
-- version 4.4.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 29, 2015 at 06:37 PM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `admin_backend`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_permission`
--

CREATE TABLE IF NOT EXISTS `tbl_permission` (
  `id` int(11) NOT NULL,
  `rule_id` int(11) DEFAULT NULL,
  `system` varchar(255) NOT NULL,
  `controller` varchar(255) DEFAULT NULL,
  `actions` longtext
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_permission`
--

INSERT INTO `tbl_permission` (`id`, `rule_id`, `system`, `controller`, `actions`) VALUES
(8, 15, 'backend', 'user', 'a:3:{i:0;s:5:"index";i:1;s:3:"add";i:2;s:4:"edit";}'),
(7, 15, 'backend', 'index', 'a:1:{i:0;s:5:"index";}');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rule`
--

CREATE TABLE IF NOT EXISTS `tbl_rule` (
  `id` int(11) NOT NULL,
  `rule` varchar(255) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_rule`
--

INSERT INTO `tbl_rule` (`id`, `rule`) VALUES
(1, 'Quản trị Backend'),
(2, 'Quản lý'),
(3, 'Nội dung'),
(15, 'Nạp thẻ');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `created` int(11) NOT NULL,
  `admin_system` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(4) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `fullname`, `email`, `password`, `created`, `admin_system`, `status`) VALUES
(1, 'Hoang Dao Xuan', 'admin@gmail.com', 'd0f7db40e698109f62a4de9e7b2b93dd', 0, 1, 1),
(3, 'Nguyễn Thị Hoa', 'nguyenhoa@gmail.com', 'b1fb5da96c013092d9a48a71d263c859', 0, 0, 1),
(4, 'Nhung', 'nhung@edu.com', 'ed2b1f468c5f915f3f1cf75d7068baae', 0, 0, 1),
(5, 'Hung', 'hung@edu.com', 'ed2b1f468c5f915f3f1cf75d7068baae', 0, 0, 1),
(9, 'Lê Khắc Sơn', 'sonlk@edu.com', 'e10adc3949ba59abbe56e057f20f883e', 1400825911, 0, 1),
(10, 'Tô Viết Cường', 'cuongto291@gmail.com', '2115a1e542039685b1cd33a0548c3e2f', 1403607870, 0, 0),
(11, 'Hà Anh Mận', 'anhmantk@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 1403672017, 1, 1),
(12, 'Designer', 'designer@edu.com', 'ed2b1f468c5f915f3f1cf75d7068baae', 1404302536, 0, 1),
(13, 'Admin Sentence', 'sentence@edu.com', 'fcea920f7412b5da7be0cf42b8c93759', 1406189641, 0, 1),
(14, 'Voicer', 'voice@edu.com', 'e10adc3949ba59abbe56e057f20f883e', 1408164612, 0, 1),
(15, 'review_test1', 'admin1@gmail.com', 'ed2b1f468c5f915f3f1cf75d7068baae', 1408358865, 0, 1),
(16, 'Hiền', 'hien@edu.com', 'e10adc3949ba59abbe56e057f20f883e', 1408513290, 0, 1),
(17, 'writer_gregcreates', 'writer_gregcreates', '461d5dc1043d43cf3bc65aee1b7699c5', 1408592171, 0, 0),
(18, 'writer_stylusink', 'writer_stylusink', 'ed2b1f468c5f915f3f1cf75d7068baae', 1408674383, 0, 1),
(19, 'dolly_donut', 'dolly_donut', 'ed2b1f468c5f915f3f1cf75d7068baae', 1410652123, 0, 1),
(20, 'Thịnh', 'thinh@edu.com', 'e10adc3949ba59abbe56e057f20f883e', 1412301884, 0, 1),
(21, 'themightypen', 'themightypen', 'ed2b1f468c5f915f3f1cf75d7068baae', 1412614513, 0, 1),
(22, 'american_review', 'american_review', 'ed2b1f468c5f915f3f1cf75d7068baae', 1414176956, 0, 1),
(23, 'british_review', 'british_review', 'ed2b1f468c5f915f3f1cf75d7068baae', 1414993166, 0, 1),
(25, 'American_review_onemissej', 'American_review_onemissej', 'ed2b1f468c5f915f3f1cf75d7068baae', 1421306856, 0, 1),
(24, 'meclairerichard', 'meclairerichard', 'ed2b1f468c5f915f3f1cf75d7068baae', 1415955254, 0, 1),
(26, 'british_review_chelta', 'british_review_chelta', 'ed2b1f468c5f915f3f1cf75d7068baae', 1421435719, 0, 0),
(27, 'french_translator', 'french_translator', 'ed2b1f468c5f915f3f1cf75d7068baae', 1424933351, 0, 1),
(28, 'french_reviewer_mariadam', 'french_reviewer_mariadam', 'ed2b1f468c5f915f3f1cf75d7068baae', 1425869327, 0, 0),
(29, 'hung', 'hung@gmail.com', 'ed2b1f468c5f915f3f1cf75d7068baae', 1425917288, 0, 1),
(30, 'spanish_translator', 'spanish_translator', 'ed2b1f468c5f915f3f1cf75d7068baae', 1425957083, 0, 1),
(31, 'french_reviewer', 'french_reviewer', '828c88f34ecb4c1ca8d89e018c6fad1a', 1426645036, 0, 1),
(32, 'spanish_reviewer_rubiabel', 'spanish_reviewer_rubiabel', 'ed2b1f468c5f915f3f1cf75d7068baae', 1428591345, 0, 1),
(33, 'Tình', 'tinh@daybehoc.com', 'ed2b1f468c5f915f3f1cf75d7068baae', 1430702127, 0, 1),
(34, 'Tinh Nguyen', 'tinh@edu.com', 'ed2b1f468c5f915f3f1cf75d7068baae', 1430719781, 0, 1),
(35, 'Hà Anh Mận', 'agent01@gmail.com', '11d332d9b746280c62062125209e61fc', 1431077997, 0, 1),
(36, 'Trần Anh Thi', 'anhthi@gmail.com', '3fdb367cc264a9dc9b001719f44c1deb', 1431078052, 0, 1),
(37, 'Hà Anh Mận', 'agent01@gmail.com', '2d7de2d6975810f557eefe166a717a9d', 1431325974, 0, 1),
(38, 'chan06nt@gmail.com', 'chan06nt@gmail.com', 'ed2b1f468c5f915f3f1cf75d7068baae', 1433925264, 0, 1),
(39, 'nhung_review@edu.com', 'nhung_review@edu.com', 'ed2b1f468c5f915f3f1cf75d7068baae', 1433925875, 0, 1),
(40, 'trang@edu.com', 'trang@edu.com', 'ed2b1f468c5f915f3f1cf75d7068baae', 1433932132, 0, 1),
(41, 'b.huong@edu.com', 'b.huong@edu.com', 'ed2b1f468c5f915f3f1cf75d7068baae', 1434078036, 0, 1),
(42, 'nga@edu.com', 'nga@edu.com', 'ed2b1f468c5f915f3f1cf75d7068baae', 1434527316, 0, 1),
(43, 'facebook', 'facebook', 'b559bb42edd24527e96d23e30c738253', 1434701654, 0, 1),
(44, 'xuyen', 'xuyen', 'ed2b1f468c5f915f3f1cf75d7068baae', 1435312890, 0, 1),
(45, 'anhminh@edu.com', 'anhminh@edu.com', 'ed2b1f468c5f915f3f1cf75d7068baae', 1436163435, 0, 1),
(46, 'Nguyen Mui', 'nguyenmui90@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 1440865509, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_rule`
--

CREATE TABLE IF NOT EXISTS `tbl_user_rule` (
  `uid` int(11) NOT NULL,
  `rule_id` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_user_rule`
--

INSERT INTO `tbl_user_rule` (`uid`, `rule_id`) VALUES
(45, 1),
(45, 2),
(45, 3),
(45, 15),
(46, 15);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_permission`
--
ALTER TABLE `tbl_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_rule`
--
ALTER TABLE `tbl_rule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user_rule`
--
ALTER TABLE `tbl_user_rule`
  ADD PRIMARY KEY (`uid`,`rule_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_permission`
--
ALTER TABLE `tbl_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `tbl_rule`
--
ALTER TABLE `tbl_rule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `tbl_user_rule`
--
ALTER TABLE `tbl_user_rule`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=47;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
