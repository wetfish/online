-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 28, 2017 at 10:34 AM
-- Server version: 10.0.29-MariaDB-0+deb8u1
-- PHP Version: 5.6.29-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `wfo_forum`
--

-- --------------------------------------------------------

--
-- Table structure for table `smf_message_tips`
--

CREATE TABLE IF NOT EXISTS `smf_message_tips` (
`id_message_tip` int(11) NOT NULL,
  `id_message` int(11) NOT NULL,
  `id_member` int(11) NOT NULL,
  `coins` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `smf_message_tips`
--
ALTER TABLE `smf_message_tips`
 ADD PRIMARY KEY (`id_message_tip`), ADD KEY `id_message` (`id_message`) COMMENT 'Frequent lookup of tips for each message';

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `smf_message_tips`
--
ALTER TABLE `smf_message_tips`
MODIFY `id_message_tip` int(11) NOT NULL AUTO_INCREMENT;
