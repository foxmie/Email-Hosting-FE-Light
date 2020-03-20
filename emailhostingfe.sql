-- MySQL dump 10.16  Distrib 10.1.44-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: emailhostingfe
-- ------------------------------------------------------
-- Server version	10.1.44-MariaDB-0+deb9u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `aliases`
--

DROP TABLE IF EXISTS `aliases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aliases` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `user` text,
  `domain` text,
  `destination` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aliases`
--

LOCK TABLES `aliases` WRITE;
/*!40000 ALTER TABLE `aliases` DISABLE KEYS */;
/*!40000 ALTER TABLE `aliases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bindextension`
--

DROP TABLE IF EXISTS `bindextension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bindextension` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `extension` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bindextension`
--

LOCK TABLES `bindextension` WRITE;
/*!40000 ALTER TABLE `bindextension` DISABLE KEYS */;
INSERT INTO `bindextension` VALUES (1,'.be'),(2,'.biz'),(3,'.com'),(4,'.eu'),(5,'.fr'),(6,'.info'),(7,'.net'),(8,'.org');
/*!40000 ALTER TABLE `bindextension` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domaine`
--

DROP TABLE IF EXISTS `domaine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `domaine` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `subdomain` text,
  `domain` text,
  `letsencrypt` int(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domaine`
--

LOCK TABLES `domaine` WRITE;
/*!40000 ALTER TABLE `domaine` DISABLE KEYS */;
/*!40000 ALTER TABLE `domaine` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mailaddress`
--

DROP TABLE IF EXISTS `mailaddress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mailaddress` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `user` text,
  `description` text,
  `domain` text,
  `state` int(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailaddress`
--

LOCK TABLES `mailaddress` WRITE;
/*!40000 ALTER TABLE `mailaddress` DISABLE KEYS */;
/*!40000 ALTER TABLE `mailaddress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `lastname` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email` text CHARACTER SET utf8,
  `password` text CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-03-20  5:35:44
