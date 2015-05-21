-- MySQL dump 10.13  Distrib 5.6.21, for Win32 (x86)
--
-- Host: localhost    Database: shopping_clean
-- ------------------------------------------------------
-- Server version	5.6.21

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
-- Current Database: `shopping_clean`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `shopping_clean` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `shopping_clean`;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `qty_ordered` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `session_id` varchar(28) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=367 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (283,NULL,0,1,'G7RJ8FJ3789GJW9W9W9JW49JW'),(284,7,1,1,'G7RJ8FJ3789GJUILYG73332HF'),(287,NULL,0,1,'EHEARHHEREHHJUILYG73332HF'),(288,NULL,0,1,'TBEHATEHEHTYGDAHHA73332HF'),(289,1,1,1,'4311344G4GSG4DAHHA73332HF'),(290,2,1,1,'D4311344G4GSGHRAHRHRR32HF'),(291,NULL,0,1,'test1'),(292,1,1,1,'test1'),(293,2,1,1,'test1'),(294,NULL,0,4,'sueirhvggjvnndf4d25vbmh623'),(295,3,1,4,'sueirhvggjvnndf4d25vbmh623'),(296,4,2,4,'sueirhvggjvnndf4d25vbmh623'),(297,2,4,4,'sueirhvggjvnndf4d25vbmh623'),(298,4,1,NULL,'test2'),(299,NULL,0,4,'test3'),(300,2,1,4,'test3'),(301,NULL,0,4,'i809qsa5ubmno50upcgd15g2q7'),(334,3,2,4,'9kg5idpdss913n6kh8hdhcbmh2'),(335,1,1,4,'9kg5idpdss913n6kh8hdhcbmh2'),(336,2,1,4,'9kg5idpdss913n6kh8hdhcbmh2'),(337,2,1,NULL,'1cgj023rnoefqj01k0716j8am3'),(351,1,1,NULL,'5mepldq4ikrmu09r0sb45spbj5'),(355,1,1,29,'kplcim51uerqlrtseqm8po4gm4'),(366,2,1,4,'3m1rddi94v2dmfei4qjhkpt0o1');
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(30) NOT NULL,
  `lname` varchar(40) NOT NULL,
  `email` varchar(80) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (-1,'none','none','none','',''),(1,'John','Jonson','john@email.com','john','ripper'),(2,'Joe','Shmoe','joe@email.com','jname','jpass'),(3,'Henry','The 8th','iam@email.com','henry','the8th'),(4,'John','Henry','j@test.com','john','henry'),(5,'guigu','gct','ctdutd','f','g'),(6,';hli','ygl','1','a','b'),(7,'Tom','Joad','ssrsth','aaa','bbb'),(8,'new','person','rhre','r','w'),(9,'a','b','c','test','test'),(10,'Abe','Bee','ccc@ddd.com','abe','bee'),(11,'Joe','Blow','jblow@gmail.com','joe','blows'),(12,'John','Johnson','johnny@gmail.com','johnny','boy'),(13,'Jerry','O\'Brian','some_thing@ww3.edu','should','pass'),(14,'John','O\'Brian','johnnyboy@gmail.com','random','words'),(15,'Another','Name','email@email.com','hello','again'),(29,'Should','Be A','complete@order.com','admin','password'),(30,'Jerry','Johnson','ohohoh@iohhoi.com','jerry','rocks'),(31,'Someone','Else','hello@imaemail.com','someone','else'),(32,'Joe','Smith','abc@xyz.com','ddd','mmmmm'),(33,'Opoji','Ijoiioh','ugiu@kuhu.iuh','zzzzzzz','zzzzzzz');
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `shipping_id` int(11) NOT NULL,
  `tm` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `customer_id` (`customer_id`),
  KEY `contact_id` (`shipping_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`shipping_id`) REFERENCES `shipping` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,1,1,1,1,NULL),(2,1,2,-1,4,NULL),(3,1,1,-1,22,NULL),(4,1,1,4,23,NULL),(5,3,1,-1,24,NULL),(6,1,1,-1,25,NULL),(7,2,2,-1,25,NULL),(8,3,1,-1,25,NULL),(9,3,2,-1,26,NULL),(10,3,1,6,33,NULL),(11,1,1,-1,34,NULL),(12,1,1,7,36,NULL),(13,6,1,-1,38,NULL),(14,4,1,8,39,NULL),(16,3,1,-1,45,NULL),(17,2,1,-1,46,NULL),(18,1,1,-1,47,NULL),(19,2,1,-1,48,NULL),(20,4,1,-1,48,NULL),(21,6,2,-1,48,NULL),(22,2,2,-1,49,NULL),(23,6,1,-1,49,NULL),(24,4,1,-1,50,NULL),(25,2,1,-1,51,NULL),(26,4,1,-1,51,NULL),(27,2,1,-1,52,NULL),(28,1,1,10,101,NULL),(29,1,1,11,103,NULL),(30,3,1,12,105,NULL),(31,1,1,12,106,NULL),(32,1,1,-1,107,NULL),(33,4,1,20,109,NULL),(34,3,1,20,110,NULL),(38,3,1,-1,125,NULL),(39,3,1,-1,126,NULL),(40,2,1,-1,127,NULL),(47,2,1,-1,135,NULL),(48,1,1,-1,137,NULL),(49,1,1,-1,139,NULL),(50,1,1,-1,140,NULL),(51,1,1,29,141,NULL),(52,1,1,29,142,NULL),(53,3,1,4,143,NULL),(54,3,1,4,144,NULL),(55,2,1,-1,145,'2015-04-28 00:54:29'),(56,1,1,4,146,'2015-04-28 00:55:01'),(57,1,1,30,149,'2015-04-28 00:59:13'),(58,1,1,-1,150,'2015-04-28 01:28:17'),(59,7,1,-1,150,'2015-04-28 01:28:17'),(60,1,1,4,151,'2015-04-28 02:37:56'),(61,2,1,4,151,'2015-04-28 02:37:56'),(62,3,1,4,152,'2015-04-28 02:38:19'),(63,4,1,4,152,'2015-04-28 02:38:19'),(64,2,1,4,153,'2015-04-28 02:41:13'),(65,1,1,4,154,'2015-04-28 02:47:36'),(66,3,1,4,154,'2015-04-28 02:47:36'),(67,4,1,4,154,'2015-04-28 02:47:36'),(68,1,1,4,156,'2015-04-28 03:32:48'),(69,2,1,4,156,'2015-04-28 03:32:48'),(70,3,1,4,156,'2015-04-28 03:32:48'),(71,6,4,4,156,'2015-04-28 03:32:48'),(72,2,1,4,157,'2015-04-28 03:35:10'),(73,1,1,4,9,'2015-04-28 14:34:16'),(74,3,1,4,9,'2015-04-28 14:34:16'),(75,2,1,4,9,'2015-04-28 14:35:04'),(76,3,1,-1,145,'2015-04-28 14:36:07'),(77,2,1,-1,158,'2015-04-28 14:50:14'),(78,2,1,4,9,'2015-04-28 14:50:39'),(79,1,1,-1,159,'2015-04-28 14:55:38'),(80,1,1,4,9,'2015-04-28 15:02:41'),(81,2,1,-1,160,'2015-04-28 15:04:19'),(82,2,1,4,161,'2015-04-28 15:09:35'),(83,2,1,4,9,'2015-04-28 15:13:26'),(84,2,1,4,162,'2015-04-28 15:14:20'),(85,2,1,4,23,'2015-04-28 15:39:44'),(86,2,1,4,23,'2015-04-28 15:45:31'),(87,1,1,4,23,'2015-04-28 15:52:42'),(88,2,1,4,23,'2015-04-28 15:52:42'),(89,2,1,4,23,'2015-04-28 15:53:30'),(90,2,1,4,23,'2015-04-28 16:03:53'),(91,2,1,4,9,'2015-04-28 16:05:31'),(92,2,1,4,23,'2015-04-28 16:08:14'),(93,2,1,4,23,'2015-04-28 16:10:04'),(94,2,1,4,23,'2015-04-28 16:14:04'),(95,1,1,4,23,'2015-04-28 16:17:00'),(96,1,1,4,23,'2015-04-28 16:19:53'),(97,1,1,4,23,'2015-04-28 16:21:14'),(98,1,1,4,23,'2015-04-28 16:22:05'),(99,1,1,4,23,'2015-04-28 16:22:56'),(100,2,1,4,156,'2015-04-28 17:16:55'),(101,2,1,4,23,'2015-04-28 17:17:31'),(102,2,1,4,161,'2015-04-28 17:18:00'),(103,1,2,4,23,'2015-04-28 17:27:51'),(104,2,3,4,23,'2015-04-28 17:27:51'),(105,1,1,4,23,'2015-04-28 17:28:15'),(106,1,1,4,23,'2015-04-28 17:28:36'),(107,1,1,4,23,'2015-04-28 17:30:59'),(108,3,3,4,23,'2015-04-28 17:31:30'),(109,1,1,4,23,'2015-04-28 17:32:16'),(110,3,2,4,23,'2015-04-28 17:32:43'),(111,4,3,4,23,'2015-04-28 17:32:43'),(112,1,1,31,163,'2015-04-28 19:13:41'),(113,2,1,31,163,'2015-04-28 19:13:41'),(114,2,5,33,164,'2015-04-28 19:31:21'),(115,4,3,33,164,'2015-04-28 19:31:21'),(116,6,1,33,164,'2015-04-28 19:31:21'),(117,6,1,4,157,'2015-04-28 19:32:16'),(118,2,2,4,23,'2015-04-28 19:36:40');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(50) NOT NULL,
  `img` varchar(150) NOT NULL,
  `descript` varchar(300) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Baseball Bat Pepper Grinder','img/baseball-bat-pepper-grinder.jpg','Make a homerun grinding pepper!',12.50,46),(2,'Banana Slicer','img/banana-slicer.jpg','Now you can buy something to avoid the difficulty of repeatedly pushing a knife through a banana!',7.99,108),(3,'Bacon Strip Bandages','img/bacon-bandages.jpg','Everything is better with bacon - <br />even minor wounds.',4.00,14),(4,'Sleep at Work Eyeball Stickers','img/eyeball-stickers.jpg','Rest easy while getting paid!',2.50,63),(5,'Custom Edible Wedding Dress','img/edible-wedding-dress.jpg','Be the sweetest bride anyone has ever seen!',399.99,5),(6,'Unicorn Meat','img/unicorn-meat.jpg','Taste the magic in every bite!',3.95,29),(7,'The Boyfriend Pillow','img/boyfriend-pillow.jpg','Great for codependent singles!',18.95,86);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipping`
--

DROP TABLE IF EXISTS `shipping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shipping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `ship_fname` varchar(50) NOT NULL,
  `ship_lname` varchar(50) NOT NULL,
  `street` varchar(150) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` char(2) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `phone` varchar(14) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `shipping_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipping`
--

LOCK TABLES `shipping` WRITE;
/*!40000 ALTER TABLE `shipping` DISABLE KEYS */;
INSERT INTO `shipping` VALUES (8,1,'Joe','Blow','97855 gr ge','cocomo','NY','87647','547-855-9987'),(9,4,'John','Henry','555 A st','Town','AL','66666','555-555-5555'),(13,-1,'first','last','street','city','st','zip','555-555-5555'),(14,-1,'LugiYUFyfl','Iyl','Iyf','Ilyf','ID','y','555-555-5555'),(15,-1,'LugiYUFyfl','Iyl','Iyf','Ilyf','ID','y','555-555-5555'),(17,-1,'LugiYUFyfl','Iyl','Iyf','Ilyf','ID','y','555-555-5555'),(18,-1,'Lyvilii','Klmlknlnk','Lnk','Lnk','NE','lnk','555-555-5555'),(19,-1,'Lyvilii','Klmlknlnk','Lnk','Lnk','NE','lnk','555-555-5555'),(20,-1,'Lyvilii','Klmlknlnk','Lnk','Lnk','NE','lnk','555-555-5555'),(21,-1,'Lyvilii','Klmlknlnk','Lnk','Lnk','NE','lnk','555-555-5555'),(22,-1,'Fffffffff','Ffffffffffffff','Iluf','Ilyf','ID','iuf','555-555-5555'),(23,4,'John','Henry','555 steel driven lane','Town','AL','66666','555-555-5555'),(25,-1,'Tom','Joad','Somewhere','Sallisaw','OK','45041','555-555-5555'),(26,-1,'Ui','Yuf','Ugi','Ug','UT','ug','555-555-5555'),(33,6,';hli','Ygl','Yvl','Yfil','AL','lyuf','555-555-5555'),(34,-1,'Johana','Henry','963 Steel driving ln ','Wheeling','WV','44552','555-555-5555'),(36,7,'Tom','Joad','Ngohiw','Iohgoh','OH','ohi','555-555-5555'),(38,-1,'Ms. Joan','Henry','963 Steel driving ln ','Wheeling','WV','33333','555-555-5555'),(39,8,'New','Person','Rgrrw','Rwwrrw','RI','rww','555-555-5555'),(40,9,'A','B','F','G','HI','i','555-555-5555'),(45,-1,'K','K','K','K','KS','k','555-555-5555'),(46,-1,'J','G','G','G','GA','g','555-555-5555'),(48,-1,'P','P','P','P','PA','p','555-555-5555'),(49,-1,'N','N','N','N','NE','n','555-555-5555'),(50,-1,'M','M','M','M','ME','m','555-555-5555'),(51,-1,'N','N','N','N','NE','n','555-555-5555'),(52,-1,'N','B','G','V','AL','h','555-555-5555'),(101,10,'Abe','Bee','Apt. B 3987 Some Long Name St.','Las Vegas','NV','145899854','525-525-8755'),(103,11,'Joe','Blow','468 S. Somewhere Road','Winston-Durham','NC','45896','598-598-8569'),(105,12,'John','Johnson','6468 Nowhere Rd.','Detroit','MI','58784','895-895-8597'),(106,12,'John','Johnson','6468 Nowhere Rd.','Detroit','MI','58784','895-895-8597'),(114,-1,'Testing','Again','Ijijipjp J64 648','Sgrgrg','AL','15589','123-123-7891'),(115,-1,'Testing','Again','Ijijipjp J64 648','Sgrgrg','AL','15589','123-123-7891'),(126,-1,'Joe','Blow','Somewhere','Cocomos','AL','44444','789-456-1232'),(141,29,'SHould','Be A','84846 Drrdr','Somewhere','AL','12338','987-654-3215'),(142,29,'SHould','Be A','84846 Drrdr','Somewhere','AL','12338','987-987-3215'),(143,4,'John','Henry','123 C Street','Town','AL','66666','555-555-5555'),(144,4,'John','Henry','123 B Street','Town','AL','66666','555-555-5555'),(145,-1,'John','Henry','Somewhere Else','Wheeling','WV','14785','987-589-2587'),(146,4,'John','Henry','123 D Street','Town','AL','66666','555-555-5555'),(149,30,'Jerry','Johnson','816684 Gigiuuig','Denver','AL','17785','987-456-9874'),(150,-1,'John','Henry','333 Another st','Wheeling','WV','14785','147-852-3698'),(151,4,'John','Henry','1 F St','Town','AL','66666','555-555-5555'),(152,4,'John','Henry','125 G St','Town','AL','66666','555-555-5555'),(153,4,'John','Henry','125 H St','Town','AL','66666','555-555-5555'),(154,4,'John','Henry','125 I St','Town','AL','66666','555-555-5555'),(156,4,'John','Henry','125 K St','Town','AL','66666','555-555-5555'),(157,4,'John','Henry','123 L Street','Town','AL','66666','555-555-5555'),(158,-1,'Dzhrhdz','Jtftxu','Kxyk 3443','Tdhtdh','AL','45921','125-789-6542'),(159,-1,'Stu','Uts','43 Dhrhdt','Tyeyts','AL','58465','589-632-1547'),(160,-1,'Fdfd','Gfdd','455 Hddr dh','Dddg','AL','48855','859-456-7895'),(161,4,'John','Henry','New house','Oho','AL','18856','852-741-8525'),(162,4,'Sallie Mae','Henry','58156 A St','Wheeling','WV','58795','789-654-7896'),(163,31,'Someone','Else','1589 S. Somwhere Dr.','Raleigh','NC','47858','654-789-6854'),(164,33,'Opoji','Ijoiioh','4646 Hougi','Uhgiuu','AL','45897','546-789-4569');
/*!40000 ALTER TABLE `shipping` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-04-28 20:11:05
