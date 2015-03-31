-- MySQL dump 10.13  Distrib 5.6.23, for osx10.10 (x86_64)
--
-- Host: localhost    Database: dev_greymatters
-- ------------------------------------------------------
-- Server version	5.6.23

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `dev_greymatters`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `dev_greymatters` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `dev_greymatters`;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'art'),(2,'automotive'),(3,'education'),(4,'electronics'),(5,'entertainment'),(6,'food'),(7,'household'),(8,'medical'),(9,'music'),(10,'science'),(11,'society'),(12,'sustainability');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) unsigned NOT NULL,
  `role` enum('profile','banner','solution','') NOT NULL,
  `ext` varchar(15) NOT NULL,
  `mime` int(10) unsigned NOT NULL,
  `size` int(11) NOT NULL,
  `md5` varchar(32) NOT NULL,
  `creator` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_extensions`
--

DROP TABLE IF EXISTS `media_extensions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_extensions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ext` varchar(15) NOT NULL,
  `type` enum('application','audio','image','model','text','video') NOT NULL,
  `media` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_extensions`
--

LOCK TABLES `media_extensions` WRITE;
/*!40000 ALTER TABLE `media_extensions` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_extensions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `thread_id` int(11) unsigned NOT NULL,
  `body` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,2,1,'test','2015-03-14 18:47:12',0),(2,2,2,'asdf','2015-03-14 18:47:55',0),(3,2,3,'sdf','2015-03-14 18:48:50',0),(4,2,4,'me','2015-03-16 13:02:24',0),(5,2,5,'asdf','2015-03-16 13:07:42',0),(6,2,6,'ad','2015-03-16 13:08:03',0),(7,2,7,'asdf','2015-03-16 13:10:02',0),(8,2,8,'fasdf','2015-03-16 13:11:11',0),(9,2,9,'asdf','2015-03-16 13:11:25',0),(10,2,10,'asdf','2015-03-16 13:12:19',0),(11,2,11,'asdf','2015-03-16 13:13:25',0),(12,2,12,'asdf','2015-03-16 13:14:16',0),(13,2,13,'adsf','2015-03-16 13:15:16',0),(14,2,14,'me','2015-03-16 13:16:56',0),(15,2,15,'asdf','2015-03-16 13:17:33',0),(16,2,16,'asdf','2015-03-16 13:17:42',0),(17,2,17,'asdf','2015-03-16 13:22:24',0),(18,2,18,'asdf','2015-03-16 13:23:16',0),(19,2,19,'asdf','2015-03-16 13:23:47',0),(20,2,20,'asdf','2015-03-16 13:24:26',0),(21,2,21,'asdf','2015-03-16 13:24:57',0),(22,2,21,'','2015-03-16 13:24:57',0),(23,2,22,'asdf','2015-03-16 13:25:19',0),(24,2,22,'','2015-03-16 13:25:19',0),(25,2,23,'asdf','2015-03-16 13:25:28',0),(26,2,23,'','2015-03-16 13:25:28',0),(27,2,24,'test','2015-03-16 13:26:35',0),(28,2,24,'','2015-03-16 13:26:35',0),(29,2,25,'sdf','2015-03-16 13:28:42',0),(30,2,25,'','2015-03-16 13:28:42',0),(31,2,26,'2','2015-03-16 13:29:09',0),(32,2,26,'','2015-03-16 13:29:09',0),(33,2,27,'3','2015-03-16 13:29:56',0),(34,2,27,'','2015-03-16 13:29:56',0),(35,2,28,'5','2015-03-16 13:30:36',0),(36,2,28,'','2015-03-16 13:30:36',0),(37,2,29,'sadf','2015-03-16 13:30:49',0),(38,2,29,'','2015-03-16 13:30:49',0),(39,2,30,'sadf','2015-03-16 16:52:26',0),(40,2,30,'','2015-03-16 16:52:26',0),(41,2,31,'asdf','2015-03-17 14:48:21',0),(42,2,31,'','2015-03-17 14:48:21',0),(43,2,32,'a','2015-03-24 19:16:07',0),(44,2,33,'a','2015-03-24 19:16:55',0),(45,2,34,'a','2015-03-24 19:18:09',0),(46,2,35,'a','2015-03-24 19:18:12',0),(47,2,36,'test','2015-03-24 19:35:24',0),(48,2,37,'test','2015-03-24 19:35:28',0),(49,2,38,'test','2015-03-24 19:36:03',0),(50,2,39,'test','2015-03-24 19:36:07',0),(51,2,40,'test','2015-03-24 19:37:10',0),(52,2,41,'test','2015-03-24 19:37:10',0),(53,2,42,'asdf','2015-03-24 19:42:43',0),(54,2,43,'asdf','2015-03-24 19:42:43',0),(55,2,44,'test','2015-03-24 19:43:52',0),(56,2,45,'test','2015-03-24 19:43:52',0),(57,2,46,'test','2015-03-24 19:45:44',0),(58,2,47,'test','2015-03-24 19:45:44',0),(59,2,48,'test','2015-03-24 19:46:19',0),(60,2,49,'test','2015-03-24 19:46:40',0),(61,2,50,'test','2015-03-24 19:46:45',0),(62,2,51,'test','2015-03-24 19:46:49',0),(63,2,52,'test','2015-03-24 20:06:35',0),(64,2,53,'test','2015-03-24 20:07:22',0),(65,2,54,'test','2015-03-24 20:07:29',0),(66,2,55,'2','2015-03-24 20:08:35',0),(67,2,56,'2','2015-03-24 20:08:39',0),(68,2,57,'test','2015-03-24 20:10:22',0),(69,2,58,'test','2015-03-24 20:10:22',0),(70,2,59,'Idk man','2015-03-24 20:11:37',0),(71,2,60,'Idk man','2015-03-24 20:11:37',0),(72,2,61,'Idk man','2015-03-24 20:12:17',0),(73,2,62,'Idk man','2015-03-24 20:12:17',0),(74,2,63,'test','2015-03-24 20:12:26',0),(75,2,64,'test','2015-03-24 20:12:26',0),(76,2,65,'test','2015-03-24 20:12:34',0),(77,2,66,'test','2015-03-24 20:12:34',0),(78,2,67,'dfasd','2015-03-24 20:21:32',0),(79,2,68,'dfasd','2015-03-24 20:21:32',0),(80,2,69,'test','2015-03-24 20:30:49',0),(81,2,70,'test','2015-03-24 20:30:49',0),(82,2,71,'test','2015-03-24 20:30:58',0),(83,2,72,'test','2015-03-24 20:30:58',0),(84,2,73,'test','2015-03-24 20:42:38',0),(85,2,74,'test','2015-03-24 20:45:49',0),(86,2,75,'asdf','2015-03-24 21:05:33',0),(87,2,76,'asdf','2015-03-24 21:05:45',0);
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `problems`
--

DROP TABLE IF EXISTS `problems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `problems` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `shorthand` varchar(40) NOT NULL,
  `title` varchar(160) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `category` tinyint(2) NOT NULL,
  `current_trial` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `problems`
--

LOCK TABLES `problems` WRITE;
/*!40000 ALTER TABLE `problems` DISABLE KEYS */;
INSERT INTO `problems` VALUES (1,'asdf','asdf','asdf','2015-03-13 23:38:10',2,0,1,0),(2,'my-ass-hurts','My ass hurts','My ass hurts','2015-03-13 23:38:43',2,0,8,0),(3,'asdf2','asdf','asdf','2015-03-13 23:39:45',2,0,3,0),(4,'testagain','testagain','testagain','2015-03-13 23:53:59',2,0,1,0),(5,'why-cant-I-type-with-my-mind-yet','Why can&#39;t I type with my mind yet?','Why can&#39;t I type with my mind yet?','2015-03-25 04:10:37',2,0,3,0);
/*!40000 ALTER TABLE `problems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solutions`
--

DROP TABLE IF EXISTS `solutions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solutions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL,
  `shorthand` varchar(40) NOT NULL,
  `title` varchar(160) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator` int(11) unsigned NOT NULL,
  `category` tinyint(2) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solutions`
--

LOCK TABLES `solutions` WRITE;
/*!40000 ALTER TABLE `solutions` DISABLE KEYS */;
/*!40000 ALTER TABLE `solutions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status`
--

LOCK TABLES `status` WRITE;
/*!40000 ALTER TABLE `status` DISABLE KEYS */;
INSERT INTO `status` VALUES (1,'ACTIVE'),(2,'INACTIVE'),(3,'HIDDEN'),(4,'LOCKED');
/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag_associations`
--

DROP TABLE IF EXISTS `tag_associations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tag_associations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) unsigned NOT NULL,
  `associate` int(11) unsigned NOT NULL,
  `type` enum('PROBLEM','SOLUTION') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag_associations`
--

LOCK TABLES `tag_associations` WRITE;
/*!40000 ALTER TABLE `tag_associations` DISABLE KEYS */;
INSERT INTO `tag_associations` VALUES (1,14,1,'PROBLEM'),(2,11,1,'PROBLEM'),(3,12,1,'PROBLEM'),(4,13,1,'PROBLEM'),(5,21,1,'PROBLEM'),(6,23,2,'PROBLEM'),(7,9,2,'PROBLEM'),(8,10,2,'PROBLEM'),(9,12,2,'PROBLEM'),(10,13,2,'PROBLEM'),(11,21,2,'PROBLEM'),(12,9,3,'PROBLEM'),(13,10,3,'PROBLEM'),(14,11,3,'PROBLEM'),(15,12,3,'PROBLEM'),(16,24,3,'PROBLEM'),(17,9,4,'PROBLEM'),(18,10,4,'PROBLEM'),(19,11,4,'PROBLEM'),(20,12,4,'PROBLEM'),(21,13,4,'PROBLEM'),(22,21,4,'PROBLEM'),(23,9,5,'PROBLEM'),(24,10,5,'PROBLEM'),(25,11,5,'PROBLEM'),(26,12,5,'PROBLEM'),(27,13,5,'PROBLEM'),(28,21,5,'PROBLEM');
/*!40000 ALTER TABLE `tag_associations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'m'),(2,'body'),(3,'self'),(4,'energy'),(5,'health'),(6,'tired'),(7,'help'),(8,'me'),(9,'1'),(10,'2'),(11,'3'),(12,'4'),(13,'5'),(14,'asdf'),(15,'66'),(16,'57'),(17,'sadf'),(18,'sdfa'),(19,'dfas'),(20,'asd'),(21,'6'),(22,'7'),(23,'hahahahaha'),(24,'56');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `threads`
--

DROP TABLE IF EXISTS `threads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `threads` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `op_id` int(11) unsigned NOT NULL,
  `subject` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `problem_id` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `threads`
--

LOCK TABLES `threads` WRITE;
/*!40000 ALTER TABLE `threads` DISABLE KEYS */;
INSERT INTO `threads` VALUES (1,2,'test','2015-03-14 18:47:12',0,0),(2,2,'afd','2015-03-14 18:47:55',0,0),(3,2,'sdf','2015-03-14 18:48:50',0,0),(4,2,'show','2015-03-16 13:02:24',0,0),(5,2,'asdf','2015-03-16 13:07:42',0,0),(6,2,'sfadsf','2015-03-16 13:08:03',0,0),(7,2,'asdf','2015-03-16 13:10:02',0,0),(8,2,'asdf','2015-03-16 13:11:11',0,0),(9,2,'asdf','2015-03-16 13:11:25',0,0),(10,2,'asdf','2015-03-16 13:12:19',0,0),(11,2,'as','2015-03-16 13:13:25',0,0),(12,2,'asdf','2015-03-16 13:14:16',0,0),(13,2,'asdf','2015-03-16 13:15:16',0,0),(14,2,'fuc','2015-03-16 13:16:56',0,0),(15,2,'adsf','2015-03-16 13:17:33',0,0),(16,2,'adsf','2015-03-16 13:17:42',0,0),(17,2,'asdf','2015-03-16 13:22:24',0,0),(18,2,'asdf','2015-03-16 13:23:16',0,0),(19,2,'adf','2015-03-16 13:23:47',0,0),(20,2,'adf','2015-03-16 13:24:26',0,0),(21,2,'adf','2015-03-16 13:24:57',0,0),(22,2,'adf','2015-03-16 13:25:19',0,0),(23,2,'adf','2015-03-16 13:25:28',0,0),(24,2,'test','2015-03-16 13:26:35',0,0),(25,2,'asdf','2015-03-16 13:28:42',0,0),(26,2,'1','2015-03-16 13:29:09',0,0),(27,2,'2','2015-03-16 13:29:56',0,0),(28,2,'5','2015-03-16 13:30:36',0,0),(29,2,'asdf','2015-03-16 13:30:49',0,0),(30,2,'asdf','2015-03-16 16:52:25',0,0),(31,2,'asdf','2015-03-17 14:48:21',0,0),(32,2,'a','2015-03-24 19:16:07',1,0),(33,2,'a','2015-03-24 19:16:55',1,0),(34,2,'a','2015-03-24 19:18:09',1,0),(35,2,'a','2015-03-24 19:18:12',1,0),(36,2,'etest','2015-03-24 19:35:24',1,0),(37,2,'etest','2015-03-24 19:35:28',1,0),(38,2,'test','2015-03-24 19:36:03',1,0),(39,2,'test','2015-03-24 19:36:07',1,0),(40,2,'test','2015-03-24 19:37:10',1,0),(41,2,'test','2015-03-24 19:37:10',1,0),(42,2,'asdf','2015-03-24 19:42:43',1,0),(43,2,'asdf','2015-03-24 19:42:43',1,0),(44,2,'test','2015-03-24 19:43:52',1,0),(45,2,'test','2015-03-24 19:43:52',1,0),(46,2,'ytet','2015-03-24 19:45:44',1,0),(47,2,'ytet','2015-03-24 19:45:44',1,0),(48,2,'test','2015-03-24 19:46:19',1,0),(49,2,'test','2015-03-24 19:46:40',1,0),(50,2,'test','2015-03-24 19:46:45',1,0),(51,2,'test','2015-03-24 19:46:49',1,0),(52,2,'test','2015-03-24 20:06:35',1,0),(53,2,'test','2015-03-24 20:07:22',1,0),(54,2,'test','2015-03-24 20:07:29',1,0),(55,2,'test2','2015-03-24 20:08:35',1,0),(56,2,'test2','2015-03-24 20:08:39',1,0),(57,2,'test','2015-03-24 20:10:22',1,0),(58,2,'test','2015-03-24 20:10:22',1,0),(59,2,'What does that even mean','2015-03-24 20:11:37',1,0),(60,2,'What does that even mean','2015-03-24 20:11:37',1,0),(61,2,'What does that even mean','2015-03-24 20:12:17',1,0),(62,2,'What does that even mean','2015-03-24 20:12:17',1,0),(63,2,'test','2015-03-24 20:12:26',1,0),(64,2,'test','2015-03-24 20:12:26',1,0),(65,2,'test','2015-03-24 20:12:34',1,0),(66,2,'test','2015-03-24 20:12:34',1,0),(67,2,'zcvzxZXCVxcvz','2015-03-24 20:21:32',1,0),(68,2,'zcvzxZXCVxcvz','2015-03-24 20:21:32',1,0),(69,2,'test','2015-03-24 20:30:49',1,0),(70,2,'test','2015-03-24 20:30:49',1,0),(71,2,'test','2015-03-24 20:30:58',1,0),(72,2,'test','2015-03-24 20:30:58',1,0),(73,2,'test','2015-03-24 20:42:38',1,0),(74,2,'test','2015-03-24 20:45:49',1,0),(75,2,'asdf','2015-03-24 21:05:33',1,0),(76,2,'asdf','2015-03-24 21:05:45',1,0);
/*!40000 ALTER TABLE `threads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_accounts`
--

DROP TABLE IF EXISTS `user_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(256) NOT NULL,
  `password` varchar(77) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_accounts`
--

LOCK TABLES `user_accounts` WRITE;
/*!40000 ALTER TABLE `user_accounts` DISABLE KEYS */;
INSERT INTO `user_accounts` VALUES (2,'mshullick@icloud.com','sha256:1000:CH0+Xl9OKCx5a6hmGwKRx8TuZkYM/Bau:sRYnloa76tp448/cW0Nc6x6RZqEpUiIR'),(3,'serubin@serubin.net','sha256:1000:VVf2X1ZuoKDA3oOGh5r5J44xj5ejOC8X:bx8ETSt/3JJpWXINEoXNjjAdsdaUetyt');
/*!40000 ALTER TABLE `user_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_data`
--

DROP TABLE IF EXISTS `user_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_data` (
  `id` int(11) unsigned NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` varchar(1) NOT NULL DEFAULT 'O',
  `year` int(4) NOT NULL,
  `join_date` date NOT NULL,
  `permission` tinyint(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_data`
--

LOCK TABLES `user_data` WRITE;
/*!40000 ALTER TABLE `user_data` DISABLE KEYS */;
INSERT INTO `user_data` VALUES (2,'Michael','Shullick','O',2005,'2015-03-12',2),(3,'Solomon','Rubin','O',1996,'2015-03-26',2);
/*!40000 ALTER TABLE `user_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_meta`
--

DROP TABLE IF EXISTS `user_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_meta` (
  `id` int(11) unsigned NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_meta`
--

LOCK TABLES `user_meta` WRITE;
/*!40000 ALTER TABLE `user_meta` DISABLE KEYS */;
INSERT INTO `user_meta` VALUES (2,1),(3,1);
/*!40000 ALTER TABLE `user_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_notifications`
--

DROP TABLE IF EXISTS `user_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_notifications` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `url` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_notifications`
--

LOCK TABLES `user_notifications` WRITE;
/*!40000 ALTER TABLE `user_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_sessions`
--

DROP TABLE IF EXISTS `user_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_sessions` (
  `id` varchar(64) NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `timestamp` int(16) unsigned NOT NULL COMMENT 'Unix timestamp',
  `expire` int(16) NOT NULL COMMENT 'Unix timestamp',
  `ip` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_sessions`
--

LOCK TABLES `user_sessions` WRITE;
/*!40000 ALTER TABLE `user_sessions` DISABLE KEYS */;
INSERT INTO `user_sessions` VALUES ('13f9aaa3244fcf94316dcb844c32013aec66186a8c04dfe9cc75693c7fec8a59',2,1427384294,1428593894,'fe80::1'),('1b683a75aa42f4da9d0f6d2a5ff38a71163116feddf3650ec9194e6f8a98e233',2,1427229030,1428438630,'::1'),('240d13535d8c9ad05d3277e822b6392e948c63846ecd048671cf10167ca87304',2,1426626858,1427836458,'::1'),('2e3d69552c2b5a452795bc5b00ff0817c5515a527ff75571c476aac4c3f08f7e',2,1426209860,1427419460,'127.0.0.1'),('3a0762b6061503c333fd4d0601397f53ede6aa32e79c01872315b19461c2de7f',2,1427215508,1428425108,'::1'),('412dee52ce9d292f3d864e6ffec0a983ae7ae59ee6f2eec8f08e750784956553',2,1427230500,1428440100,'fe80::1'),('497848dabe4603e3e3179543a211fdb6dfae3cf2602ddbb7b76bf83d14d7c678',2,1427230657,1428440257,'::1'),('4f2b5dbcc26334b919eff8dc0a8e53056aa33a6a9cd81cf0487e2cefcefea2f5',2,1427137927,1428347527,'::1'),('57f01cf92856adff8f17c1b4b3a3f507f20ef837023d44acd5420b4f372706d6',2,1427256478,1428466078,'::1'),('65156db437ce45f2aeb0e705e99e89e6ee54cc0c8ea60419ffd921f9c9136bb2',2,1427222577,1428432177,'::1'),('67f02d691bd67abb8588f40797f965ea499b85605e9c529a3770826600f1d57d',2,1427228468,1428438068,'fe80::1'),('6d9587b955de2388990c5bf04af3630076202270e96ed751285a509def82c986',2,1426358649,1427568249,'127.0.0.1'),('77f21ea9f729883ea26f51113dc2731ed6b720f127787b062222a719383080b2',2,1426628605,1427838205,'::1'),('83bfd208aaf0df0e7d59c61faf2503b5dda9b29e0627d1cdd5e085edb06ed7d0',3,1427774262,1428983862,'127.0.0.1'),('88833be6318d9b342487bcdf934f7b47edfe9462f5239179900b5b1b997e5836',2,1426207051,1427416651,'::1'),('9b80d243807765109de9a2e045a47b3d37dd41ff9daf64f10968702d755e1b22',2,1427384756,1428594356,'::1');
/*!40000 ALTER TABLE `user_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `votes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ctype` enum('PROBLEM','SOLUTION','THREAD','POST') NOT NULL,
  `cid` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `vote` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votes`
--

LOCK TABLES `votes` WRITE;
/*!40000 ALTER TABLE `votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `votes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-03-31  9:03:21
