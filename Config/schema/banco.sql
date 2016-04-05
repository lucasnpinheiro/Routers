-- MySQL dump 10.13  Distrib 5.6.24, for Win32 (x86)
--
-- Host: 127.0.0.1    Database: aulas
-- ------------------------------------------------------
-- Server version	5.6.26

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
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(265) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `fone` varchar(15) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `senha` varchar(32) DEFAULT NULL,
  `data_cadastro` datetime DEFAULT NULL,
  `data_alteracao` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT  IGNORE INTO `clientes` (`id`, `nome`, `cpf`, `fone`, `email`, `senha`, `data_cadastro`, `data_alteracao`) VALUES (1,'Renan Goncalves','61771813849','16997913870','rename@neomundi.com.br','1234','2016-01-27 22:15:50',NULL),(2,'Cristina','1234567890123','36247411','cris@cris.com.br','1234','2016-01-27 22:23:32',NULL),(3,'Thiago','123456','2505252','thiago@thiago.com','1234','2016-01-27 22:25:49',NULL);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `formas_pagto`
--

DROP TABLE IF EXISTS `formas_pagto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `formas_pagto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `data_cadastro` datetime DEFAULT NULL,
  `data_alteracao` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formas_pagto`
--

LOCK TABLES `formas_pagto` WRITE;
/*!40000 ALTER TABLE `formas_pagto` DISABLE KEYS */;
/*!40000 ALTER TABLE `formas_pagto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_pedido` date DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `total` float(10,2) DEFAULT NULL,
  `forma_pagto_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `data_cadastro` datetime DEFAULT NULL,
  `data_alteracao` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos_itens`
--

DROP TABLE IF EXISTS `pedidos_itens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedidos_itens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) DEFAULT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `qtde` float(10,2) DEFAULT NULL,
  `venda` float(10,2) DEFAULT NULL,
  `total` float(10,2) DEFAULT NULL,
  `data_cadastro` datetime DEFAULT NULL,
  `data_alteracao` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos_itens`
--

LOCK TABLES `pedidos_itens` WRITE;
/*!40000 ALTER TABLE `pedidos_itens` DISABLE KEYS */;
/*!40000 ALTER TABLE `pedidos_itens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produtos`
--

DROP TABLE IF EXISTS `produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(13) NOT NULL,
  `nome` varchar(265) NOT NULL,
  `venda` float(10,2) DEFAULT NULL,
  `estoque` float(10,2) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `descricao_produto` text,
  `data_cadastro` datetime DEFAULT NULL,
  `data_alteracao` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produtos`
--

LOCK TABLES `produtos` WRITE;
/*!40000 ALTER TABLE `produtos` DISABLE KEYS */;
INSERT  IGNORE INTO `produtos` (`id`, `codigo`, `nome`, `venda`, `estoque`, `foto`, `descricao_produto`, `data_cadastro`, `data_alteracao`) VALUES (1,'12','Pilhas Amarelinhas',4.80,10.00,NULL,'Dur[avel, rende muito mais, as verdadeiras amarelinhas....',NULL,NULL),(2,'13','Filtro de LInha',12.60,10.00,NULL,'Filtro balanceado com 4 saidas',NULL,NULL),(3,'14','Oculos de Sol RayBan',125.00,10.00,NULL,'O verdadeiro Oculos da RayBan, Lentes pretas ou azul',NULL,NULL),(4,'15','Mouse s/Fio',45.00,10.00,NULL,'Prático, eficiente, feito para voce.',NULL,NULL),(5,'16','PenDrive Kingston 8GB',25.00,10.00,NULL,'Excelente produto, muito bom para levar suas músicas onde quer que seja.',NULL,NULL),(6,'17','Hd Kingston 500GB',385.00,10.00,NULL,'Para qualquer tipo de gravação.',NULL,NULL);
/*!40000 ALTER TABLE `produtos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `username` varchar(40) DEFAULT NULL,
  `senha` varchar(32) DEFAULT NULL,
  `data_cadastro` datetime DEFAULT NULL,
  `data_alteracao` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT  IGNORE INTO `usuarios` (`id`, `nome`, `username`, `senha`, `data_cadastro`, `data_alteracao`) VALUES (1,'Renan Goncalves','Rename','123456','2016-01-03 00:00:00','2016-02-17 22:30:03'),(2,'Cristina','CristinaEsc','987654','2116-01-02 00:00:00','2016-02-17 23:57:51'),(4,'Cristina','Tia','1234','2016-01-04 00:00:00',NULL),(5,'Lucas Pinto','Pinto1 ','456123',NULL,NULL),(6,'Lucas Pinto','Pinto2','456123',NULL,NULL),(9,'Epaminondas da Silva','Epaminondas','1234',NULL,NULL),(10,'jose das couves','Couves','1234',NULL,NULL),(11,'Jose das Coves Filho','Coves1234','81dc9bdb52d04dc20036dbd8313ed055','2016-02-17 23:32:08','2016-02-17 23:34:15'),(13,'Teotonio Vilela','Teotonio2010','cb3ce9b06932da6faaa7fc70d5b5d2f4','2016-02-18 00:29:42',NULL),(14,'dfasdf','rpinheirosadfasdfwqewqerwwqerf','cd4a829d506c7cf30ace9fedfcc90b5b','2016-02-18 00:43:30','2016-02-18 00:44:31');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-02-17 21:45:33
