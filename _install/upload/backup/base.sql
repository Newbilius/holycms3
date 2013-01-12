-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 12, 2013 at 12:24 PM
-- Server version: 5.1.40
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `demo_empty`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `caption` text NOT NULL,
  `sort` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `folder` int(11) NOT NULL,
  `login` text NOT NULL,
  `email` text NOT NULL,
  `block_control` smallint(6) NOT NULL,
  `pass` text NOT NULL,
  `uid` tinytext NOT NULL,
  `group` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `caption`, `sort`, `parent`, `folder`, `login`, `email`, `block_control`, `pass`, `uid`, `group`) VALUES
(1, 'administrator_sayta', 'Администратор сайта', 200, 0, 0, 'admin', 'dima.am@mail.ru', 1, '21232f297a57a5a743894a0e4a801fc3', 'f09409e7d9e6c1f7804fe3ed3e410493;4447cc8be9811625d655f83c6a9dc1e7;c4220cbffc8ed917f56168a98bcc3add;4ec73b0737676cc3f0ce801871dbaff6', '1'),
(3, 'editor', 'editor', 400, 0, 0, 'editor', 'dima.am@mail.ru', 0, '5aee9dbd2a188839105073571bee1b1f', '', '2');
