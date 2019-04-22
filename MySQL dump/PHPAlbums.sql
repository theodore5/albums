CREATE DATABASE  IF NOT EXISTS `phpalbums` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `phpalbums`;
-- MySQL dump 10.13  Distrib 5.6.13, for Win32 (x86)
--
-- Host: 127.0.0.1    Database: phpalbums
-- ------------------------------------------------------
-- Server version	5.1.72-community

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
-- Table structure for table `albums`
--

DROP TABLE IF EXISTS `albums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `albums` (
  `alb_ID` int(10) NOT NULL AUTO_INCREMENT,
  `alb_Title` varchar(150) DEFAULT NULL,
  `alb_Creator` varchar(50) DEFAULT NULL,
  `alb_Timestamp` datetime DEFAULT NULL,
  PRIMARY KEY (`alb_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `albums`
--

LOCK TABLES `albums` WRITE;
/*!40000 ALTER TABLE `albums` DISABLE KEYS */;
INSERT INTO `albums` VALUES (10,'Πρωτοχρονιά 2015','21',NULL),(11,'Αποκριά 2015','21',NULL),(12,'Γιορτή','21','2015-02-19 00:11:25'),(13,'Αποφοίτηση','21','2015-02-19 02:29:47');
/*!40000 ALTER TABLE `albums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `Med_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Med_Code` varchar(20) DEFAULT NULL,
  `Med_Album` int(10) DEFAULT NULL,
  `Med_Description` varchar(250) DEFAULT NULL,
  `Med_Status` int(10) DEFAULT NULL,
  `Med_Notes` text,
  `Med_Valid` int(10) DEFAULT NULL,
  `Med_Filename` varchar(100) DEFAULT NULL,
  `Med_URL` varchar(255) DEFAULT NULL,
  `Med_Creator` int(11) DEFAULT NULL,
  `Med_Timestamp` datetime DEFAULT NULL,
  PRIMARY KEY (`Med_ID`),
  KEY `Med_ALbum` (`Med_Album`),
  KEY `Med_Status` (`Med_Status`),
  CONSTRAINT `media_ibfk_1` FOREIGN KEY (`Med_Album`) REFERENCES `albums` (`alb_ID`),
  CONSTRAINT `media_ibfk_2` FOREIGN KEY (`Med_Status`) REFERENCES `status` (`sts_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
INSERT INTO `media` VALUES (46,NULL,10,'Photo 10.01',NULL,'Notes 10.01',NULL,'j0182538 (1).jpg',NULL,NULL,NULL),(47,NULL,10,'Photo 10.02',NULL,'Notes 10.02',NULL,'4319.jpg',NULL,NULL,NULL),(48,NULL,11,'Photo 11.01',NULL,'Notes 11.01',NULL,'25102005(005).jpg',NULL,NULL,NULL),(49,NULL,11,'Γενέθλια',NULL,'Γενέθλια',NULL,'image[5].jpeg','',21,'2015-02-19 00:36:05'),(50,NULL,12,'Ανδρέας',NULL,'Γενέθλια',NULL,'image[1].jpeg','',21,'2015-02-19 00:47:03'),(51,NULL,12,'Γενέθλια',NULL,'Παιδί',NULL,'image[3].jpeg',NULL,21,'2015-02-19 00:48:57');
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status` (
  `sts_ID` int(10) NOT NULL AUTO_INCREMENT,
  `sts_Title` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`sts_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status`
--

LOCK TABLES `status` WRITE;
/*!40000 ALTER TABLE `status` DISABLE KEYS */;
/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `Usr_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Usr_Username` varchar(20) DEFAULT NULL,
  `Usr_Password` varchar(20) DEFAULT NULL,
  `Usr_Lastname` varchar(20) DEFAULT NULL,
  `Usr_Firstname` varchar(20) DEFAULT NULL,
  `Usr_Email` varchar(30) DEFAULT NULL,
  `Usr_Phone` varchar(20) DEFAULT NULL,
  `Usr_Mobile` varchar(20) DEFAULT NULL,
  `Usr_Role_Admin` varchar(5) DEFAULT NULL,
  `Usr_Role_Contributor` varchar(5) DEFAULT NULL,
  `Usr_Status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`Usr_ID`),
  UNIQUE KEY `Usr_Username_UNIQUE` (`Usr_Username`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (21,'1','1','Lastname','Firstname',NULL,NULL,NULL,'True',NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'phpalbums'
--

--
-- Dumping routines for database 'phpalbums'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-02-19 14:56:39
