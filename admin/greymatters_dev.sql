-- MySQL dump 10.13  Distrib 5.6.24, for osx10.10 (x86_64)
--
-- Host: localhost    Database: dev_greymatters
-- ------------------------------------------------------
-- Server version	5.6.24

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
-- Table structure for table `flag_types`
--

DROP TABLE IF EXISTS `flag_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `flag_types` (
  `id` int(2) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flag_types`
--

LOCK TABLES `flag_types` WRITE;
/*!40000 ALTER TABLE `flag_types` DISABLE KEYS */;
INSERT INTO `flag_types` VALUES (1,'duplicate'),(2,'inappropriate');
/*!40000 ALTER TABLE `flag_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flags`
--

DROP TABLE IF EXISTS `flags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `flags` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `value` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flags`
--

LOCK TABLES `flags` WRITE;
/*!40000 ALTER TABLE `flags` DISABLE KEYS */;
INSERT INTO `flags` VALUES (0,2,2,2);
/*!40000 ALTER TABLE `flags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contributors`
--

DROP TABLE IF EXISTS `contributors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contributors` (
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `association` enum('creator','contributor','developer','engineer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contributors`
--

LOCK TABLES `contributors` WRITE;
/*!40000 ALTER TABLE `contributors` DISABLE KEYS */;
INSERT INTO `contributors` VALUES (30,1,'creator'),(31,1,'creator'),(32,1,'creator'),(33,1,'creator'),(34,1,'creator'),(35,1,'creator'),(36,1,'creator'),(37,1,'creator'),(38,1,'creator'),(39,1,'creator'),(33,2,'engineer');
/*!40000 ALTER TABLE `contributors` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,2,1,'test','2015-03-14 18:47:12',0),(2,2,2,'asdf','2015-03-14 18:47:55',0),(3,2,3,'sdf','2015-03-14 18:48:50',0),(4,2,4,'me','2015-03-16 13:02:24',0),(5,2,5,'asdf','2015-03-16 13:07:42',0),(6,2,6,'ad','2015-03-16 13:08:03',0),(7,2,7,'asdf','2015-03-16 13:10:02',0),(8,2,8,'fasdf','2015-03-16 13:11:11',0),(9,2,9,'asdf','2015-03-16 13:11:25',0),(10,2,10,'asdf','2015-03-16 13:12:19',0),(11,2,11,'asdf','2015-03-16 13:13:25',0),(12,2,12,'asdf','2015-03-16 13:14:16',0),(13,2,13,'adsf','2015-03-16 13:15:16',0),(14,2,14,'me','2015-03-16 13:16:56',0),(15,2,15,'asdf','2015-03-16 13:17:33',0),(16,2,16,'asdf','2015-03-16 13:17:42',0),(17,2,17,'asdf','2015-03-16 13:22:24',0),(18,2,18,'asdf','2015-03-16 13:23:16',0),(19,2,19,'asdf','2015-03-16 13:23:47',0),(20,2,20,'asdf','2015-03-16 13:24:26',0),(21,2,21,'asdf','2015-03-16 13:24:57',0),(22,2,21,'','2015-03-16 13:24:57',0),(23,2,22,'asdf','2015-03-16 13:25:19',0),(24,2,22,'','2015-03-16 13:25:19',0),(25,2,23,'asdf','2015-03-16 13:25:28',0),(26,2,23,'','2015-03-16 13:25:28',0),(27,2,24,'test','2015-03-16 13:26:35',0),(28,2,24,'','2015-03-16 13:26:35',0),(29,2,25,'sdf','2015-03-16 13:28:42',0),(30,2,25,'','2015-03-16 13:28:42',0),(31,2,26,'2','2015-03-16 13:29:09',0),(32,2,26,'','2015-03-16 13:29:09',0),(33,2,27,'3','2015-03-16 13:29:56',0),(34,2,27,'','2015-03-16 13:29:56',0),(35,2,28,'5','2015-03-16 13:30:36',0),(36,2,28,'','2015-03-16 13:30:36',0),(37,2,29,'sadf','2015-03-16 13:30:49',0),(38,2,29,'','2015-03-16 13:30:49',0),(39,2,30,'sadf','2015-03-16 16:52:26',0),(40,2,30,'','2015-03-16 16:52:26',0),(41,2,31,'asdf','2015-03-17 14:48:21',0),(42,2,31,'','2015-03-17 14:48:21',0),(43,2,32,'a','2015-03-24 19:16:07',0),(44,2,33,'a','2015-03-24 19:16:55',0),(45,2,34,'a','2015-03-24 19:18:09',0),(46,2,35,'a','2015-03-24 19:18:12',0),(47,2,36,'test','2015-03-24 19:35:24',0),(48,2,37,'test','2015-03-24 19:35:28',0),(49,2,38,'test','2015-03-24 19:36:03',0),(50,2,39,'test','2015-03-24 19:36:07',0),(51,2,40,'test','2015-03-24 19:37:10',0),(52,2,41,'test','2015-03-24 19:37:10',0),(53,2,42,'asdf','2015-03-24 19:42:43',0),(54,2,43,'asdf','2015-03-24 19:42:43',0),(55,2,44,'test','2015-03-24 19:43:52',0),(56,2,45,'test','2015-03-24 19:43:52',0),(57,2,46,'test','2015-03-24 19:45:44',0),(58,2,47,'test','2015-03-24 19:45:44',0),(59,2,48,'test','2015-03-24 19:46:19',0),(60,2,49,'test','2015-03-24 19:46:40',0),(61,2,50,'test','2015-03-24 19:46:45',0),(62,2,51,'test','2015-03-24 19:46:49',0),(63,2,52,'test','2015-03-24 20:06:35',0),(64,2,53,'test','2015-03-24 20:07:22',0),(65,2,54,'test','2015-03-24 20:07:29',0),(66,2,55,'2','2015-03-24 20:08:35',0),(67,2,56,'2','2015-03-24 20:08:39',0),(68,2,57,'test','2015-03-24 20:10:22',0),(69,2,58,'test','2015-03-24 20:10:22',0),(70,2,59,'Idk man','2015-03-24 20:11:37',0),(71,2,60,'Idk man','2015-03-24 20:11:37',0),(72,2,61,'Idk man','2015-03-24 20:12:17',0),(73,2,62,'Idk man','2015-03-24 20:12:17',0),(74,2,63,'test','2015-03-24 20:12:26',0),(75,2,64,'test','2015-03-24 20:12:26',0),(76,2,65,'test','2015-03-24 20:12:34',0),(77,2,66,'test','2015-03-24 20:12:34',0),(78,2,67,'dfasd','2015-03-24 20:21:32',0),(79,2,68,'dfasd','2015-03-24 20:21:32',0),(80,2,69,'test','2015-03-24 20:30:49',0),(81,2,70,'test','2015-03-24 20:30:49',0),(82,2,71,'test','2015-03-24 20:30:58',0),(83,2,72,'test','2015-03-24 20:30:58',0),(84,2,73,'test','2015-03-24 20:42:38',0),(85,2,74,'test','2015-03-24 20:45:49',0),(86,2,75,'asdf','2015-03-24 21:05:33',0),(87,2,76,'asdf','2015-03-24 21:05:45',0),(88,1,0,'No boy. No fucking cereal for you. Youve had like 15 boxes today alone!','2015-04-17 22:27:55',0),(89,1,0,'No boy. No fucking cereal for you. Youve had like 15 boxes today alone!','2015-04-17 22:28:44',0),(90,1,0,'No boy. No fucking cereal for you. Youve had like 15 boxes today alone!','2015-04-17 22:28:48',0),(91,1,0,'No boy. No fucking cereal for you. Youve had like 15 boxes today alone!','2015-04-17 22:28:55',0),(92,1,0,'No boy. No fucking cereal for you. Youve had like 15 boxes today alone!','2015-04-17 22:29:00',0),(93,1,0,'No boy. No fucking cereal for you. Youve had like 15 boxes today alone!','2015-04-17 22:29:07',0),(94,1,0,'No boy. No fucking cereal for you. Youve had like 15 boxes today alone!','2015-04-17 22:29:14',0),(95,1,0,'No boy. No fucking cereal for you. Youve had like 15 boxes today alone!','2015-04-17 22:29:19',0),(96,1,77,'mahh','2015-04-20 18:42:44',0);
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
  `shorthand` varchar(205) NOT NULL,
  `title` varchar(160) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '1',
  `category` tinyint(2) NOT NULL,
  `current_trial` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `problems`
--

LOCK TABLES `problems` WRITE;
/*!40000 ALTER TABLE `problems` DISABLE KEYS */;
INSERT INTO `problems` VALUES (1,'asdf','asdf','asdf','2015-03-13 23:38:10',2,0,1,0),(2,'my-ass-hurts','My ass hurts','My ass hurts','2015-03-13 23:38:43',2,0,8,0),(3,'asdf2','asdf','asdf','2015-03-13 23:39:45',2,0,3,0),(4,'testagain','testagain','testagain','2015-03-13 23:53:59',2,0,1,0),(7,'i-need-more-couches-but-they-are-so-expensive','I need more couches, but they are so expensive?','I need more couches, but they are so expensive?','2015-04-16 14:36:54',1,1,6,0),(9,'i-need-more-beds-please','I need more beds please?','BEDS. I NEED BEDS. UUUGGGHHAHAARRRGGGSSSS BEDS?\n\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam consectetur felis ac mollis hendrerit. Etiam commodo, nunc non aliquam vestibulum, ipsum turpis ornare orci, eu suscipit nulla orci nec ante. Duis vel volutpat diam. Suspendisse lacinia metus et imperdiet fringilla. Donec et orci ac lorem ultricies cursus vel sed erat. Praesent bibendum condimentum pulvinar. Phasellus at nisi vitae mi dictum semper.\n\n','2015-04-16 14:44:43',1,1,7,0),(10,'i-want-more-cereal-mom','I want more cereal mom!','MOM! More cereal!!\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam consectetur felis ac mollis hendrerit. Etiam commodo, nunc non aliquam vestibulum, ipsum turpis ornare orci, eu suscipit nulla orci nec ante. Duis vel volutpat diam. Suspendisse lacinia metus et imperdiet fringilla. Donec et orci ac lorem ultricies cursus vel sed erat. Praesent bibendum condimentum pulvinar. Phasellus at nisi vitae mi dictum semper.\n\nFusce at pharetra nisl, vitae rhoncus arcu. Sed et fermentum lacus. Etiam dictum tellus eu sem tristique, id luctus lacus pulvinar. Praesent ut lectus tristique, interdum risus malesuada, congue leo. Maecenas consectetur vestibulum quam, a egestas dolor commodo id. Integer tristique mi vitae lacus consequat, et consequat turpis dignissim. Nam cursus urna vel libero tincidunt, non vehicula quam efficitur. Sed magna lectus, congue nec turpis ac, auctor laoreet nunc. Maecenas rutrum, ex at condimentum mollis, tortor neque tempor sem, tincidunt gravida magna neque vitae justo. Sed viverra, lacus eu sodales sollicitudin, eros lacus porttitor felis, id blandit erat elit non risus. Ut faucibus fermentum massa, sagittis bibendum sem vestibulum nec. Donec dolor eros, efficitur eget elit a, tincidunt accumsan turpis. Duis non nulla arcu.\n\nDuis efficitur purus ut nisl feugiat dapibus. Maecenas auctor eget massa id euismod. Suspendisse vestibulum, elit sed interdum finibus, nibh nulla luctus libero, et fermentum mi sapien et massa. Sed imperdiet viverra nibh sed fringilla. Suspendisse vehicula posuere felis eu pretium. Sed condimentum imperdiet velit, id accumsan eros molestie sit amet. Fusce aliquam nulla vel iaculis tristique. Nunc aliquam quam ac enim fermentum, quis lobortis neque pharetra.\n\nNullam eu ipsum libero. Nulla vitae luctus risus. Mauris bibendum nulla sapien, eu vehicula nulla molestie in. Duis dapibus, dolor vel mollis cursus, massa nibh sagittis metus, ac iaculis tortor odio et quam. Pellentesque ipsum tortor, placerat at tellus sed, lacinia ultrices justo. Aliquam libero ipsum, bibendum eget semper quis, malesuada eu ligula. Nulla faucibus lorem enim, porttitor facilisis turpis pharetra a. Aenean quis cursus sem. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;\nI HACK YOU HARD','2015-04-16 15:10:02',1,1,4,0),(11,'need-more-wake-why-no-wake','Need more wake. Why no wake?','I NEED MORE WAKE SO I CAN BE EDGIMICATED.','2015-04-16 15:44:17',1,1,3,0),(12,'fine','Fine.','Keyboards are cool. And super inefficient. \n\nDvorak is cool ','2015-04-17 22:47:01',1,1,5,0),(13,'finefff2','Fine.','Keyboards are cool. And super inefficient. \n\nDvorak is cool ','2015-04-17 22:47:58',1,1,5,0),(14,'finefff2','Fine.','Keyboards are cool. And super inefficient. \n\nDvorak is cool ','2015-04-17 22:48:56',1,1,5,0),(15,'spaces-are-so-cool','Spaces are so cool','','2015-04-17 22:54:45',1,1,10,0),(16,'i-haz-the-wiki','I HAZ THE WIKI','== Heading ==\n=== Subheading ===\n[http://www.url.com Name of URLs]\n[[File:http://mindcloud.loc/assets/images/logo/mindcloud_full.png Mindcloud bitches]]\n-------------------- (Horizontal line)\n: (Indentation)\n# Ordered bullet point\n# Ordered bullet point 2\n# Ordered bullet point 3\n# Ordered bullet point 4\n\n* Unordered bullet point\n* Unordered bullet point 2\n* Unordered bullet point 3\n* Unordered bullet point 4\n* Unordered bullet point 5','2015-04-17 23:08:43',1,1,4,0),(17,'i-haz-more-wiki-plz','I haz more wiki plz?','== Heading ==\n=== Subheading ===\n[http://www.url.com Name of URLs]\n[[File:http://mindcloud.loc/assets/images/logo/mindcloud_full.png Mindcloud bitches]]\n-------------------- (Horizontal line)\n: (Indentation)\n# Ordered bullet point\n# Ordered bullet point 2\n# Ordered bullet point 3\n# Ordered bullet point 4\n\n* Unordered bullet point\n* Unordered bullet point 2\n* Unordered bullet point 3\n* Unordered bullet point 4\n* Unordered bullet point 5','2015-04-18 02:18:09',1,1,5,0),(18,'computing-upside-down','Why can&#39;t I use my computer upside while in bed?','Blah','2015-04-20 19:19:39',1,1,5,0);
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
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `current_trial` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solutions`
--

LOCK TABLES `solutions` WRITE;
/*!40000 ALTER TABLE `solutions` DISABLE KEYS */;
INSERT INTO `solutions` VALUES (33,10,'cereal-out-the-wazoo','Cereal out the wazoo','So much cereal, I\'ll have no choice but to start shoving it up your bum.','2015-04-20 05:11:25',0,0),(35,16,'the-wiki-its-too-much','TOO MUCH WIKI','DIEEEEE WIKI DIEEE','2015-04-20 05:19:24',0,0),(36,16,'yeahletsdo-this','One more time!','WOOO','2015-04-20 05:22:43',0,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag_associations`
--

LOCK TABLES `tag_associations` WRITE;
/*!40000 ALTER TABLE `tag_associations` DISABLE KEYS */;
INSERT INTO `tag_associations` VALUES (1,14,1,'PROBLEM'),(2,11,1,'PROBLEM'),(3,12,1,'PROBLEM'),(4,13,1,'PROBLEM'),(5,21,1,'PROBLEM'),(6,23,2,'PROBLEM'),(7,9,2,'PROBLEM'),(8,10,2,'PROBLEM'),(9,12,2,'PROBLEM'),(10,13,2,'PROBLEM'),(11,21,2,'PROBLEM'),(12,9,3,'PROBLEM'),(13,10,3,'PROBLEM'),(14,11,3,'PROBLEM'),(15,12,3,'PROBLEM'),(16,24,3,'PROBLEM'),(17,9,4,'PROBLEM'),(18,10,4,'PROBLEM'),(19,11,4,'PROBLEM'),(20,12,4,'PROBLEM'),(21,13,4,'PROBLEM'),(22,21,4,'PROBLEM'),(23,9,5,'PROBLEM'),(24,10,5,'PROBLEM'),(25,11,5,'PROBLEM'),(26,12,5,'PROBLEM'),(27,13,5,'PROBLEM'),(28,21,5,'PROBLEM'),(29,29,0,'PROBLEM'),(30,28,0,'PROBLEM'),(31,33,0,'PROBLEM'),(32,36,0,'PROBLEM'),(33,37,0,'PROBLEM'),(34,29,0,'PROBLEM'),(35,28,0,'PROBLEM'),(36,33,0,'PROBLEM'),(37,36,0,'PROBLEM'),(38,37,0,'PROBLEM'),(39,29,0,'PROBLEM'),(40,28,0,'PROBLEM'),(41,33,0,'PROBLEM'),(42,36,0,'PROBLEM'),(43,37,0,'PROBLEM'),(44,29,0,'PROBLEM'),(45,28,0,'PROBLEM'),(46,33,0,'PROBLEM'),(47,36,0,'PROBLEM'),(48,37,0,'PROBLEM'),(49,29,0,'PROBLEM'),(50,28,0,'PROBLEM'),(51,33,0,'PROBLEM'),(52,36,0,'PROBLEM'),(53,37,0,'PROBLEM'),(54,29,0,'PROBLEM'),(55,28,0,'PROBLEM'),(56,33,0,'PROBLEM'),(57,36,0,'PROBLEM'),(58,37,0,'PROBLEM'),(59,29,6,'PROBLEM'),(60,28,6,'PROBLEM'),(61,33,6,'PROBLEM'),(62,36,6,'PROBLEM'),(63,37,6,'PROBLEM'),(64,38,0,'PROBLEM'),(65,39,0,'PROBLEM'),(66,40,0,'PROBLEM'),(67,41,0,'PROBLEM'),(68,42,0,'PROBLEM'),(69,43,0,'PROBLEM'),(70,38,7,'PROBLEM'),(71,39,7,'PROBLEM'),(72,40,7,'PROBLEM'),(73,41,7,'PROBLEM'),(74,42,7,'PROBLEM'),(75,43,7,'PROBLEM'),(76,44,8,'PROBLEM'),(77,45,8,'PROBLEM'),(78,46,8,'PROBLEM'),(79,47,8,'PROBLEM'),(80,48,8,'PROBLEM'),(81,44,9,'PROBLEM'),(82,45,9,'PROBLEM'),(83,46,9,'PROBLEM'),(84,47,9,'PROBLEM'),(85,48,9,'PROBLEM'),(86,49,10,'PROBLEM'),(87,50,10,'PROBLEM'),(88,46,10,'PROBLEM'),(89,51,10,'PROBLEM'),(90,52,10,'PROBLEM'),(91,44,11,'PROBLEM'),(92,53,11,'PROBLEM'),(93,8,11,'PROBLEM'),(94,54,11,'PROBLEM'),(95,55,11,'PROBLEM'),(96,56,12,'PROBLEM'),(97,10,12,'PROBLEM'),(98,11,12,'PROBLEM'),(99,12,12,'PROBLEM'),(100,13,12,'PROBLEM'),(101,21,12,'PROBLEM'),(102,22,12,'PROBLEM'),(103,56,13,'PROBLEM'),(104,10,13,'PROBLEM'),(105,11,13,'PROBLEM'),(106,12,13,'PROBLEM'),(107,13,13,'PROBLEM'),(108,21,13,'PROBLEM'),(109,22,13,'PROBLEM'),(110,56,14,'PROBLEM'),(111,10,14,'PROBLEM'),(112,11,14,'PROBLEM'),(113,12,14,'PROBLEM'),(114,13,14,'PROBLEM'),(115,21,14,'PROBLEM'),(116,22,14,'PROBLEM'),(117,9,15,'PROBLEM'),(118,10,15,'PROBLEM'),(119,11,15,'PROBLEM'),(120,12,15,'PROBLEM'),(121,13,15,'PROBLEM'),(122,21,15,'PROBLEM'),(123,12,16,'PROBLEM'),(124,13,16,'PROBLEM'),(125,21,16,'PROBLEM'),(126,22,16,'PROBLEM'),(127,58,16,'PROBLEM'),(128,59,16,'PROBLEM'),(129,60,17,'PROBLEM'),(130,61,17,'PROBLEM'),(131,62,17,'PROBLEM'),(132,63,17,'PROBLEM'),(133,64,17,'PROBLEM'),(134,65,17,'PROBLEM'),(135,66,17,'PROBLEM'),(136,11,18,'PROBLEM'),(137,12,18,'PROBLEM'),(138,13,18,'PROBLEM'),(139,21,18,'PROBLEM'),(140,22,18,'PROBLEM'),(141,58,18,'PROBLEM');
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
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'m'),(2,'body'),(3,'self'),(4,'energy'),(5,'health'),(6,'tired'),(7,'help'),(8,'me'),(9,'1'),(10,'2'),(11,'3'),(12,'4'),(13,'5'),(14,'asdf'),(15,'66'),(16,'57'),(17,'sadf'),(18,'sdfa'),(19,'dfas'),(20,'asd'),(21,'6'),(22,'7'),(23,'hahahahaha'),(24,'56'),(25,'tag1'),(26,'tag2'),(27,'home'),(28,'desk'),(29,'bed'),(30,'all-in-one'),(31,'fold-out-desk'),(32,'des'),(33,'furniture'),(34,'production'),(35,'cheap'),(36,'affordable'),(37,'dual use'),(38,'lorem'),(39,'ipsum'),(40,'couches'),(41,'mine'),(42,'bitch'),(43,'Etiam'),(44,'please'),(45,'b0ss'),(46,'more'),(47,'beds'),(48,'ple3s'),(49,'cereal'),(50,'mom'),(51,'hacking'),(52,'0wn-you'),(53,'give'),(54,'edgimication'),(55,'why?!'),(56,'tag'),(57,'othertag'),(58,'8'),(59,'WIKI'),(60,'46'),(61,'64'),(62,'346'),(63,'2346'),(64,'26'),(65,'262436'),(66,'234');
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
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `threads`
--

LOCK TABLES `threads` WRITE;
/*!40000 ALTER TABLE `threads` DISABLE KEYS */;
INSERT INTO `threads` VALUES (1,2,'test','2015-03-14 18:47:12',0,0),(2,2,'afd','2015-03-14 18:47:55',0,0),(3,2,'sdf','2015-03-14 18:48:50',0,0),(4,2,'show','2015-03-16 13:02:24',0,0),(5,2,'asdf','2015-03-16 13:07:42',0,0),(6,2,'sfadsf','2015-03-16 13:08:03',0,0),(7,2,'asdf','2015-03-16 13:10:02',0,0),(8,2,'asdf','2015-03-16 13:11:11',0,0),(9,2,'asdf','2015-03-16 13:11:25',0,0),(10,2,'asdf','2015-03-16 13:12:19',0,0),(11,2,'as','2015-03-16 13:13:25',0,0),(12,2,'asdf','2015-03-16 13:14:16',0,0),(13,2,'asdf','2015-03-16 13:15:16',0,0),(14,2,'fuc','2015-03-16 13:16:56',0,0),(15,2,'adsf','2015-03-16 13:17:33',0,0),(16,2,'adsf','2015-03-16 13:17:42',0,0),(17,2,'asdf','2015-03-16 13:22:24',0,0),(18,2,'asdf','2015-03-16 13:23:16',0,0),(19,2,'adf','2015-03-16 13:23:47',0,0),(20,2,'adf','2015-03-16 13:24:26',0,0),(21,2,'adf','2015-03-16 13:24:57',0,0),(22,2,'adf','2015-03-16 13:25:19',0,0),(23,2,'adf','2015-03-16 13:25:28',0,0),(24,2,'test','2015-03-16 13:26:35',0,0),(25,2,'asdf','2015-03-16 13:28:42',0,0),(26,2,'1','2015-03-16 13:29:09',0,0),(27,2,'2','2015-03-16 13:29:56',0,0),(28,2,'5','2015-03-16 13:30:36',0,0),(29,2,'asdf','2015-03-16 13:30:49',0,0),(30,2,'asdf','2015-03-16 16:52:25',0,0),(31,2,'asdf','2015-03-17 14:48:21',0,0),(32,2,'a','2015-03-24 19:16:07',1,0),(33,2,'a','2015-03-24 19:16:55',1,0),(34,2,'a','2015-03-24 19:18:09',1,0),(35,2,'a','2015-03-24 19:18:12',1,0),(36,2,'etest','2015-03-24 19:35:24',1,0),(37,2,'etest','2015-03-24 19:35:28',1,0),(38,2,'test','2015-03-24 19:36:03',1,0),(39,2,'test','2015-03-24 19:36:07',1,0),(40,2,'test','2015-03-24 19:37:10',1,0),(41,2,'test','2015-03-24 19:37:10',1,0),(42,2,'asdf','2015-03-24 19:42:43',1,0),(43,2,'asdf','2015-03-24 19:42:43',1,0),(44,2,'test','2015-03-24 19:43:52',1,0),(45,2,'test','2015-03-24 19:43:52',1,0),(46,2,'ytet','2015-03-24 19:45:44',1,0),(47,2,'ytet','2015-03-24 19:45:44',1,0),(48,2,'test','2015-03-24 19:46:19',1,0),(49,2,'test','2015-03-24 19:46:40',1,0),(50,2,'test','2015-03-24 19:46:45',1,0),(51,2,'test','2015-03-24 19:46:49',1,0),(52,2,'test','2015-03-24 20:06:35',1,0),(53,2,'test','2015-03-24 20:07:22',1,0),(54,2,'test','2015-03-24 20:07:29',1,0),(55,2,'test2','2015-03-24 20:08:35',1,0),(56,2,'test2','2015-03-24 20:08:39',1,0),(57,2,'test','2015-03-24 20:10:22',1,0),(58,2,'test','2015-03-24 20:10:22',1,0),(59,2,'What does that even mean','2015-03-24 20:11:37',1,0),(60,2,'What does that even mean','2015-03-24 20:11:37',1,0),(61,2,'What does that even mean','2015-03-24 20:12:17',1,0),(62,2,'What does that even mean','2015-03-24 20:12:17',1,0),(63,2,'test','2015-03-24 20:12:26',1,0),(64,2,'test','2015-03-24 20:12:26',1,0),(65,2,'test','2015-03-24 20:12:34',1,0),(66,2,'test','2015-03-24 20:12:34',1,0),(67,2,'zcvzxZXCVxcvz','2015-03-24 20:21:32',1,0),(68,2,'zcvzxZXCVxcvz','2015-03-24 20:21:32',1,0),(69,2,'test','2015-03-24 20:30:49',1,0),(70,2,'test','2015-03-24 20:30:49',1,0),(71,2,'test','2015-03-24 20:30:58',1,0),(72,2,'test','2015-03-24 20:30:58',1,0),(73,2,'test','2015-03-24 20:42:38',1,0),(74,2,'test','2015-03-24 20:45:49',1,0),(75,2,'asdf','2015-03-24 21:05:33',1,0),(76,2,'asdf','2015-03-24 21:05:45',1,0),(77,1,'Marrr','2015-04-20 18:42:44',33,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_accounts`
--

LOCK TABLES `user_accounts` WRITE;
/*!40000 ALTER TABLE `user_accounts` DISABLE KEYS */;
INSERT INTO `user_accounts` VALUES (1,'serubin@serubin.net','sha256:1000:dGbgS48FrKO70NFVX3bXM+2H6lN2w4mF:QKMkwuBBhG7e4vji6g2ZyyV00hOnnA/E'),(2,'serubin323@gmail.com','sha256:1000:6a8IvbNwjTxYWX7kdgeYdUnksO3ZN+re:npGVtgvZ6JyLIpjUSZ55yI3XM1IejOVq'),(3,'admin@serubin.net','sha256:1000:sC9LSXiEggGVbgHCSRSJMTzCK5TeJGma:qYc+1YZ+enp8QqmG4UUmHcJK0ATQNw95'),(4,'test@serubin.net','sha256:1000:vn04JmRMzL7MtWBcUJtqFlli05nYg5Rh:MvMZrXayL6h7/pIzVR23gQdIlj8UaeC1');
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
INSERT INTO `user_data` VALUES (1,'Solomon','Rubin','O',1996,'2015-04-02',2),(2,'Michael','Shullick','O',1996,'2015-04-02',2),(3,'Solomon','Rubin','O',1996,'2015-04-03',2),(4,'Solomon','Rubin','O',1996,'2015-04-03',2);
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
  `notification_hash` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_meta`
--

LOCK TABLES `user_meta` WRITE;
/*!40000 ALTER TABLE `user_meta` DISABLE KEYS */;
INSERT INTO `user_meta` VALUES (1,1,'d547567b3c2cf5f0afee2510f74fe24673fb735d579cf2f0f213bfc554adf289'),(2,1,'b3a9484e7494982a920ae135b9f01705d4a72c465f7e3cb690dca63f2e857f62'),(3,0,'e72e4fc6098215bd4926eaaabad4a6cf04f60f1d371fb9b5660c78df579bba2c'),(4,0,'615ca7cc98cff2e61a13496c61004ce42452e7b768ef97b62a781b77b592a8c1');
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
  `read` int(1) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_notifications`
--

LOCK TABLES `user_notifications` WRITE;
/*!40000 ALTER TABLE `user_notifications` DISABLE KEYS */;
INSERT INTO `user_notifications` VALUES (51,1,'/problem/asdf','<strong>Michael Shullick</strong> added a thread to your problem <strong>Asdf</strong>',1,'2015-04-07 22:53:40'),(52,1,'/problem/asdf/post/3245','<strong>Michael Shullick</strong> mentioned you in a post!',0,'2015-04-05 00:56:28'),(53,2,'/user/notifications','<strong>Jenkins&#39;s</strong> has made a new thread in <strong>asdf</strong>',0,'2015-04-07 15:15:32'),(54,1,'/user/notifications','<strong>Jenkins&#39;s</strong> has made a new thread in <strong>asdf</strong>',1,'2015-04-07 15:15:39'),(55,1,'/user/notifications','<strong>asdf</strong> has 321 up votes and 9 down votes!',1,'2015-04-07 15:16:27'),(56,1,'/user/notifications','<strong>asdf</strong> trial period has ended! A solution has been picked!',0,'2015-04-07 15:16:50'),(57,1,'/user/notifications','<strong>Michael Shullick</strong> has messaged you about: solution to asdf',0,'2015-04-07 15:17:24'),(58,1,'/user/notifications','<b>Michael Shullick</b> has messaged you about: solution to asdf',1,'2015-04-09 15:30:21'),(59,1,'/user/notifications','<b>Michael Shullick</b> has messaged you about: solution to asdf',1,'2015-04-09 15:30:25'),(60,1,'/user/notifications','This is a test.',1,'2015-04-11 16:23:09'),(61,1,'/user/notifications','This is a second test.',1,'2015-04-11 16:27:47'),(62,1,'/user/notifications','This is a second test.',1,'2015-04-11 16:28:16');
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
INSERT INTO `user_sessions` VALUES ('46119e58cc966ed402ad0965d2b27216edc3535bccbcdcbb2db41592197e6220',2,1428029399,1429238999,'127.0.0.1'),('4c900ec816c4535527643747c3f18833e34dc681cca646a0ca0225a289778607',1,1429474944,1430684544,'127.0.0.1');
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
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votes`
--

LOCK TABLES `votes` WRITE;
/*!40000 ALTER TABLE `votes` DISABLE KEYS */;
INSERT INTO `votes` VALUES (76,'PROBLEM',18,1,1),(80,'PROBLEM',10,1,1),(84,'SOLUTION',33,1,-1),(85,'PROBLEM',2,1,1);
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

-- Dump completed on 2015-04-21 23:09:11
