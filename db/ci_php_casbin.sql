-- MySQL dump 10.13  Distrib 8.0.25, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: ci_php_casbin
-- ------------------------------------------------------
-- Server version	5.7.33

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_desc` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (1,'menu 1'),(2,'menu 2'),(3,'menu 3'),(4,'menu 4'),(5,'menu 5'),(6,'menu 6'),(7,'menu 7'),(8,'menu 8');
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_akses`
--

DROP TABLE IF EXISTS `menu_akses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_akses` (
  `menu_akses_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_akses_desc` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`menu_akses_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_akses`
--

LOCK TABLES `menu_akses` WRITE;
/*!40000 ALTER TABLE `menu_akses` DISABLE KEYS */;
INSERT INTO `menu_akses` VALUES (1,'read'),(2,'create'),(3,'update'),(4,'delete');
/*!40000 ALTER TABLE `menu_akses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` text NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'20190901100537','Casbin\\CodeIgniter\\Database\\Migrations\\AddRule','default','Casbin\\CodeIgniter',1609073820,1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `policy`
--

DROP TABLE IF EXISTS `policy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `policy` (
  `policy_id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_desc` varchar(45) DEFAULT NULL,
  `policy_created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `policy_updated_at` datetime DEFAULT NULL,
  `policy_deleted_at` datetime DEFAULT NULL,
  `policy_deleted` varchar(45) DEFAULT '1',
  PRIMARY KEY (`policy_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `policy`
--

LOCK TABLES `policy` WRITE;
/*!40000 ALTER TABLE `policy` DISABLE KEYS */;
INSERT INTO `policy` VALUES (1,'percobaan apa tuh','2021-02-04 21:44:45',NULL,NULL,'1'),(2,'coba 2','2021-02-04 22:07:18',NULL,NULL,'1'),(3,'iya ya ya','2021-02-06 19:50:41',NULL,NULL,'1');
/*!40000 ALTER TABLE `policy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profil`
--

DROP TABLE IF EXISTS `profil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profil` (
  `user_id` int(11) NOT NULL,
  `profil_firstname` varchar(45) DEFAULT NULL,
  `profil_lastname` varchar(45) DEFAULT NULL,
  `profil_email` varchar(45) DEFAULT NULL,
  `profil_bio` varchar(256) DEFAULT NULL,
  `profil_image` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profil`
--

LOCK TABLES `profil` WRITE;
/*!40000 ALTER TABLE `profil` DISABLE KEYS */;
INSERT INTO `profil` VALUES (1,'Pindi','Ya','nurkhafindi@gmail.com','menggenggam tanganmu merupakan salah satu tujuan hidupku, aku akan berusaha untuk itu','upload/img/profil/2021/06/1624278481_0e14e1283f9aa3b4c4d0.jpeg'),(2,'operator','satu','akun779@gmail.com','','upload/img/profil/2021/01/1609685435_d93211bdf4a7480e8f38.png');
/*!40000 ALTER TABLE `profil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rules`
--

DROP TABLE IF EXISTS `rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ptype` varchar(255) DEFAULT NULL,
  `v0` varchar(255) DEFAULT NULL,
  `v1` varchar(255) DEFAULT NULL,
  `v2` varchar(255) DEFAULT NULL,
  `v3` varchar(255) DEFAULT NULL,
  `v4` varchar(255) DEFAULT NULL,
  `v5` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rules`
--

LOCK TABLES `rules` WRITE;
/*!40000 ALTER TABLE `rules` DISABLE KEYS */;
INSERT INTO `rules` VALUES (7,'g','2','3',NULL,NULL,NULL,NULL),(10,'p','3','2','1',NULL,NULL,NULL),(14,'g','2','2',NULL,NULL,NULL,NULL),(15,'g','3','2',NULL,NULL,NULL,NULL),(16,'p','2','2','1',NULL,NULL,NULL),(19,'p','3','1','1',NULL,NULL,NULL),(21,'p','3','1','3',NULL,NULL,NULL),(23,'p','3','2','3',NULL,NULL,NULL),(27,'p','3','2','2',NULL,NULL,NULL),(31,'g','3','3',NULL,NULL,NULL,NULL),(33,'p','3','1','2',NULL,NULL,NULL),(37,'p','3','3','1',NULL,NULL,NULL),(38,'p','3','3','2',NULL,NULL,NULL),(39,'p','1','1','1',NULL,NULL,NULL),(40,'p','2','2','3',NULL,NULL,NULL);
/*!40000 ALTER TABLE `rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `user_username` varchar(32) DEFAULT NULL,
  `user_password` varchar(256) DEFAULT NULL,
  `user_superadmin` int(1) DEFAULT '2',
  `user_created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_updated_at` datetime DEFAULT NULL,
  `user_aktif` int(1) DEFAULT '1',
  `user_deleted_at` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_username_UNIQUE` (`user_username`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('pindi','$2y$10$Evrg4MfVzfTFhvElZLXPsuh9rEHl6as/mvRNPJRVh9gW9l14gidri',1,'2020-12-27 15:14:39',NULL,1,NULL,1),('opr','$2y$10$MOt7KAU89vHkD0c56rZs8OiTCgQyjzgqmB53pZe638Ss6/qgas64a',2,'2020-12-27 15:14:39',NULL,1,NULL,2),('opr1','$2y$10$OKnMtodTvan0U70eshm3wuGjbYnzqWt0n3w0hsl8jxq6l6PXAbVm6',2,'2021-02-07 13:45:21',NULL,1,NULL,3),('opr2','$2y$10$i9/STRrX6vqQ1mI6j1ljguZwiSiQm/pIykcPoXDvvX7mta7EM41Ia',2,'2021-02-07 13:47:21',NULL,1,'2021-02-15 23:06:01',4),('opr3','$2y$10$OWOYrD8zIp6tZtLkxxH4pulwUeD4K38kTCTF0utBs7Y7q2K7l9/6K',2,'2021-02-07 13:47:28',NULL,1,NULL,5),('opr4','$2y$10$X/4mwVPcCkKbOEk5C3tJHuWWkVyP3YZeY2uQCdbJQ/yio4PUuIJg6',2,'2021-02-07 13:58:33',NULL,1,NULL,6),('user1','$2y$10$zYhrdxs/cKS6L/RiGh11auFtgHG44pDt.v6ALX0EYnpdavXgVXDV2',2,'2021-06-21 10:14:22',NULL,1,'2021-07-06 19:36:31',7);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-07-21 20:02:34
