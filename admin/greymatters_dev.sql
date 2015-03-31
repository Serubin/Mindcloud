-- phpMyAdmin SQL Dump
-- version 4.3.11.1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Mar 26, 2015 at 11:40 PM
-- Server version: 5.6.23
-- PHP Version: 5.5.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `dev_greymatters`
--
CREATE DATABASE IF NOT EXISTS `dev_greymatters` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `dev_greymatters`;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` tinyint(2) unsigned NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(0, 'art'),
(0, 'automotive'),
(0, 'education'),
(0, 'electronics'),
(0, 'entertainment'),
(0, 'food'),
(0, 'household'),
(0, 'medical'),
(0, 'music'),
(0, 'science'),
(0, 'society'),
(0, 'sustainability');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
CREATE TABLE IF NOT EXISTS `media` (
  `id` int(11) unsigned NOT NULL,
  `cid` int(11) unsigned NOT NULL,
  `role` enum('profile','banner','solution','') NOT NULL,
  `ext` varchar(15) NOT NULL,
  `mime` int(10) unsigned NOT NULL,
  `size` int(11) NOT NULL,
  `md5` varchar(32) NOT NULL,
  `creator` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `media_extensions`
--

DROP TABLE IF EXISTS `media_extensions`;
CREATE TABLE IF NOT EXISTS `media_extensions` (
  `id` int(10) unsigned NOT NULL,
  `ext` varchar(15) NOT NULL,
  `type` enum('application','audio','image','model','text','video') NOT NULL,
  `media` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `thread_id` int(11) unsigned NOT NULL,
  `body` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `problems`
--

DROP TABLE IF EXISTS `problems`;
CREATE TABLE IF NOT EXISTS `problems` (
  `id` int(11) unsigned NOT NULL,
  `shorthand` varchar(40) NOT NULL,
  `title` varchar(160) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `category` tinyint(2) NOT NULL,
  `current_trial` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `solutions`
--

DROP TABLE IF EXISTS `solutions`;
CREATE TABLE IF NOT EXISTS `solutions` (
  `id` int(11) unsigned NOT NULL,
  `pid` int(11) unsigned NOT NULL,
  `shorthand` varchar(40) NOT NULL,
  `title` varchar(160) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator` int(11) unsigned NOT NULL,
  `category` tinyint(2) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `id` tinyint(2) unsigned NOT NULL,
  `value` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `value`) VALUES
(0, 'ACTIVE'),
(0, 'INACTIVE'),
(0, 'HIDDEN'),
(0, 'LOCKED');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) unsigned NOT NULL,
  `identifier` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tag_associations`
--

DROP TABLE IF EXISTS `tag_associations`;
CREATE TABLE IF NOT EXISTS `tag_associations` (
  `id` int(11) unsigned NOT NULL,
  `tag_id` int(11) unsigned NOT NULL,
  `associate` int(11) unsigned NOT NULL,
  `type` enum('PROBLEM','SOLUTION') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `threads`
--

DROP TABLE IF EXISTS `threads`;
CREATE TABLE IF NOT EXISTS `threads` (
  `id` int(11) unsigned NOT NULL,
  `op_id` int(11) unsigned NOT NULL,
  `subject` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `problem_id` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

DROP TABLE IF EXISTS `user_accounts`;
CREATE TABLE IF NOT EXISTS `user_accounts` (
  `id` int(10) unsigned NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(77) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_data`
--

DROP TABLE IF EXISTS `user_data`;
CREATE TABLE IF NOT EXISTS `user_data` (
  `id` int(11) unsigned NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` varchar(1) NOT NULL DEFAULT 'O',
  `year` int(4) NOT NULL,
  `join_date` date NOT NULL,
  `permission` tinyint(1) NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_meta`
--

DROP TABLE IF EXISTS `user_meta`;
CREATE TABLE IF NOT EXISTS `user_meta` (
  `id` int(11) unsigned NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

DROP TABLE IF EXISTS `user_sessions`;
CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` varchar(64) NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `timestamp` int(16) unsigned NOT NULL COMMENT 'Unix timestamp',
  `expire` int(16) NOT NULL COMMENT 'Unix timestamp',
  `ip` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
CREATE TABLE IF NOT EXISTS `votes` (
  `id` int(11) unsigned NOT NULL,
  `ctype` enum('PROBLEM','SOLUTION','THREAD','POST') NOT NULL,
  `cid` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `vote` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
<<<<<<< HEAD
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) unsigned NOT NULL,
  `identifier` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `tag_associations`
--

CREATE TABLE IF NOT EXISTS `tag_associations` (
  `id` int(11) unsigned NOT NULL,
  `tag_id` int(11) unsigned NOT NULL,
  `associate` int(11) unsigned NOT NULL,
  `type` enum('PROBLEM', 'SOLUTION') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `threads`
--

CREATE TABLE IF NOT EXISTS `threads` (
  `id` int(11) unsigned NOT NULL, 
  `op_id` int(11) unsigned NOT NULL,
  `subject` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `problem_id` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) unsigned NOT NULL, 
  `uid` int(11) unsigned NOT NULL,
  `thread_id` int(11) unsigned NOT NULL,
  `body` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `flags`
--

CREATE TABLE `flags` (
  `id` int(11) unsigned NOT NULL, 
  `ctype` enum('PROBLEM','SOLUTION','THREAD','POST') NOT NULL,
  `cid` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `flag` enum('INNAPROPRIATE', 'DUPLICATE', 'INCORRECT') NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
=======
>>>>>>> develop
-- Indexes for dumped tables
--

--
-- Indexes for table `problems`
--
ALTER TABLE `problems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `solutions`
--
ALTER TABLE `solutions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_data`
--
ALTER TABLE `user_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_meta`
--
ALTER TABLE `user_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;