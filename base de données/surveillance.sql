-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 31, 2012 at 01:21 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `surveillance`
--

-- --------------------------------------------------------

--
-- Table structure for table `done`
--

CREATE TABLE IF NOT EXISTS `done` (
  `id_done` int(11) NOT NULL AUTO_INCREMENT,
  `day` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `id_test` int(11) NOT NULL,
  `comment` text NOT NULL,
  `rapport` text NOT NULL,
  `time` datetime NOT NULL,
  `id_user` int(11) NOT NULL,
  `from_done` varchar(255) NOT NULL,
  `to_done` varchar(255) NOT NULL,
  `id_support` int(11) NOT NULL,
  `all_days` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `pji` varchar(255) NOT NULL,
  PRIMARY KEY (`id_done`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `done`
--

INSERT INTO `done` (`id_done`, `day`, `month`, `year`, `id_test`, `comment`, `rapport`, `time`, `id_user`, `from_done`, `to_done`, `id_support`, `all_days`, `number`, `answer`, `pji`) VALUES
(12, 31, 7, 2012, 3, '', 'rapports/2012/Juillet/31/procédure1 test1     31 2012 Juillet   youssef derouich   13h 16min.jpg', '2012-07-31 13:16:17', 1, '12:36', '13:11', -1, 2039, 1, '-1', '');

-- --------------------------------------------------------

--
-- Table structure for table `support`
--

CREATE TABLE IF NOT EXISTS `support` (
  `id_support` int(11) NOT NULL AUTO_INCREMENT,
  `id_test` int(11) NOT NULL,
  `detail` varchar(255) NOT NULL,
  `valeur` varchar(255) NOT NULL,
  PRIMARY KEY (`id_support`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE IF NOT EXISTS `test` (
  `id_test` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `parametre` varchar(255) NOT NULL,
  `info` text NOT NULL,
  `id_user` int(11) NOT NULL,
  `period` varchar(255) NOT NULL,
  `re_test` int(11) NOT NULL,
  `attribut` varchar(255) NOT NULL,
  `add_time` datetime NOT NULL,
  `mesurable` int(11) NOT NULL,
  PRIMARY KEY (`id_test`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`id_test`, `name`, `parametre`, `info`, `id_user`, `period`, `re_test`, `attribut`, `add_time`, `mesurable`) VALUES
(3, 'test1', 'procédure1', '', 1, 'jour*1', 1, '-1', '2012-07-31 13:15:57', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `first` varchar(255) NOT NULL,
  `last` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `statue` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `nm` varchar(255) NOT NULL,
  `picture` varchar(255) NOT NULL DEFAULT 'images/pic.png',
  `fonction` varchar(255) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `first`, `last`, `telephone`, `email`, `login`, `password`, `statue`, `type`, `nm`, `picture`, `fonction`) VALUES
(1, 'youssef', 'derouich', '0633333333', 'youssef.derouich@renault.com ', 'derouich', '12345678', 'administrator', 'enable', 'l511339', 'images/pic.png', 'CA');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
