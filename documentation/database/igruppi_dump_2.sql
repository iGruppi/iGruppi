-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 13, 2014 at 11:56 pm
-- Server version: 5.1.54
-- PHP Version: 5.3.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `igruppi`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorie`
--

CREATE TABLE IF NOT EXISTS `categorie` (
  `idcat` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `descrizione` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `aliquota_iva` tinyint(4) NOT NULL,
  PRIMARY KEY (`idcat`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categorie_sub`
--

CREATE TABLE IF NOT EXISTS `categorie_sub` (
  `idsubcat` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idcat` int(10) unsigned NOT NULL,
  `idproduttore` int(10) unsigned NOT NULL,
  `descrizione` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idsubcat`),
  KEY `fk_categorie_sub_categorie1_idx` (`idcat`),
  KEY `fk_categorie_sub_produttori1_idx` (`idproduttore`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `idgroup` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `descrizione` text COLLATE utf8_unicode_ci NOT NULL,
  `provincia` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `data_creazione` date NOT NULL,
  `email_ml` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idgroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups_listini`
--

CREATE TABLE IF NOT EXISTS `groups_listini` (
  `idlistino` int(10) unsigned NOT NULL,
  `idgroup_master` int(10) unsigned NOT NULL,
  `idgroup_slave` int(10) unsigned NOT NULL,
  `valido_dal` date NOT NULL,
  `valido_al` date NOT NULL,
  PRIMARY KEY (`idlistino`,`idgroup_master`,`idgroup_slave`),
  KEY `fk_groups_listini_groups2_idx` (`idgroup_slave`),
  KEY `fk_groups_listini_listini1_idx` (`idlistino`),
  KEY `fk_groups_listini_groups1_idx` (`idgroup_master`),
  KEY `fk_groups_listini_ordini1_idx` (`idlistino`,`idgroup_slave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `listini`
--

CREATE TABLE IF NOT EXISTS `listini` (
  `idlistino` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `descrizione` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `pubblico` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`idlistino`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ordini`
--

CREATE TABLE IF NOT EXISTS `ordini` (
  `idordine` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idlistino` int(10) unsigned NOT NULL,
  `idgroup_slave` int(10) unsigned NOT NULL,
  `data_inizio` datetime NOT NULL,
  `data_fine` datetime NOT NULL,
  `data_inviato` datetime DEFAULT NULL,
  `data_arrivato` datetime DEFAULT NULL,
  `data_consegnato` datetime DEFAULT NULL,
  `archiviato` enum('N','S') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `note_consegna` text COLLATE utf8_unicode_ci,
  `costo_spedizione` decimal(8,2) NOT NULL,
  PRIMARY KEY (`idordine`),
  KEY `fk_ordini_groups_listini1_idx` (`idlistino`,`idgroup_slave`),
  KEY `fk_ordini_groups_listini2_idx` (`idgroup_slave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ordini_prodotti`
--

CREATE TABLE IF NOT EXISTS `ordini_prodotti` (
  `idordine` int(10) unsigned NOT NULL,
  `idlistino` int(10) unsigned NOT NULL,
  `idprodotto` int(10) unsigned NOT NULL,
  `costo` decimal(8,2) NOT NULL,
  `offerta` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `sconto` tinyint(4) NOT NULL,
  `disponibile` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'S',
  PRIMARY KEY (`idordine`,`idlistino`,`idprodotto`),
  KEY `fk_ordini_prodotti_ordini1_idx` (`idordine`),
  KEY `fk_ordini_prodotti_prodotti_listini1_idx` (`idlistino`,`idprodotto`),
  KEY `fk_ordini_prodotti_prodotti_listini1` (`idprodotto`,`idlistino`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ordini_user`
--

CREATE TABLE IF NOT EXISTS `ordini_user` (
  `iduser` int(10) unsigned NOT NULL,
  `idordine` int(10) unsigned NOT NULL,
  `note` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`iduser`,`idordine`),
  KEY `fk_ordini_user_ordini1_idx` (`idordine`),
  KEY `fk_ordini_user_ordini2_idx` (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ordini_user_prodotti`
--

CREATE TABLE IF NOT EXISTS `ordini_user_prodotti` (
  `iduser` int(10) unsigned NOT NULL,
  `idordine` int(10) unsigned NOT NULL,
  `idlistino` int(10) unsigned NOT NULL,
  `idprodotto` int(10) unsigned NOT NULL,
  `qta` smallint(5) unsigned NOT NULL,
  `qta_reale` decimal(6,3) NOT NULL,
  `data_ins` datetime NOT NULL,
  PRIMARY KEY (`iduser`,`idordine`,`idlistino`,`idprodotto`),
  KEY `fk_ordini_user_prodotti_ordini_user1_idx` (`iduser`,`idordine`),
  KEY `fk_ordini_user_prodotti_ordini_prodotti1_idx` (`idlistino`,`idprodotto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ordini_variazioni`
--

CREATE TABLE IF NOT EXISTS `ordini_variazioni` (
  `idov` int(11) NOT NULL AUTO_INCREMENT,
  `idordine` int(10) unsigned NOT NULL,
  `data` datetime NOT NULL,
  `descrizione` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `iduser` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idov`),
  KEY `fk_ordini_variazioni_ordini1_idx` (`idordine`),
  KEY `fk_ordini_variazioni_ordini_user1_idx` (`iduser`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prodotti`
--

CREATE TABLE IF NOT EXISTS `prodotti` (
  `idprodotto` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idproduttore` int(10) unsigned NOT NULL,
  `idsubcat` int(10) unsigned NOT NULL,
  `iduser_creator` int(10) unsigned NOT NULL,
  `codice` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `descrizione` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `udm` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `moltiplicatore` decimal(8,2) NOT NULL,
  `costo` decimal(8,2) NOT NULL,
  `aliquota_iva` tinyint(4) NOT NULL,
  `note` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `attivo` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'S',
  `production` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`idprodotto`),
  KEY `fk_prodotti_produttori1_idx` (`idproduttore`),
  KEY `fk_prodotti_sub_categorie1_idx` (`idsubcat`),
  KEY `fk_prodotti_users1_idx` (`iduser_creator`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prodotti_listini`
--

CREATE TABLE IF NOT EXISTS `prodotti_listini` (
  `idlistino` int(10) unsigned NOT NULL,
  `idprodotto` int(10) unsigned NOT NULL,
  `descrizione` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `costo` decimal(8,2) NOT NULL,
  `note` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `attivo` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'S',
  PRIMARY KEY (`idlistino`,`idprodotto`),
  KEY `fk_listini_prodotti1_idx` (`idprodotto`),
  KEY `fk_prodotti_listini_listini1_idx` (`idlistino`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produttori`
--

CREATE TABLE IF NOT EXISTS `produttori` (
  `idproduttore` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ragsoc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `indirizzo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `comune` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `provincia` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `note` varchar(2048) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idproduttore`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `province`
--

CREATE TABLE IF NOT EXISTS `province` (
  `provincia` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `idregione` tinyint(4) NOT NULL,
  PRIMARY KEY (`provincia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referenti`
--

CREATE TABLE IF NOT EXISTS `referenti` (
  `idgroup` int(10) unsigned NOT NULL,
  `idproduttore` int(10) unsigned NOT NULL,
  `iduser` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idgroup`,`idproduttore`,`iduser`),
  KEY `fk_referenti_produttori1_idx` (`idproduttore`),
  KEY `fk_referenti_users1_idx` (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `iduser` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `cognome` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `num_members` tinyint(4) NOT NULL,
  `comune` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `provincia` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `role` enum('User','Admin') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'User',
  PRIMARY KEY (`iduser`),
  KEY `INDEX_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_group`
--

CREATE TABLE IF NOT EXISTS `users_group` (
  `iduser` int(10) unsigned NOT NULL,
  `idgroup` int(10) unsigned NOT NULL,
  `attivo` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `fondatore` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `contabile` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`iduser`,`idgroup`),
  KEY `FK_group_idx2` (`idgroup`),
  KEY `FK_user_idx2` (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_produttori`
--

CREATE TABLE IF NOT EXISTS `users_produttori` (
  `iduser` int(10) unsigned NOT NULL,
  `idproduttore` int(10) unsigned NOT NULL,
  `livello` enum('ref','user') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`iduser`,`idproduttore`),
  KEY `FK_user_idx` (`iduser`),
  KEY `FK_produttore_idx` (`idproduttore`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categorie_sub`
--
ALTER TABLE `categorie_sub`
  ADD CONSTRAINT `fk_categorie_sub_categorie1` FOREIGN KEY (`idcat`) REFERENCES `categorie` (`idcat`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_categorie_sub_produttori1` FOREIGN KEY (`idproduttore`) REFERENCES `produttori` (`idproduttore`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `groups_listini`
--
ALTER TABLE `groups_listini`
  ADD CONSTRAINT `fk_groups_produttori_groups1` FOREIGN KEY (`idgroup_master`) REFERENCES `groups` (`idgroup`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_groups_produttori_groups2` FOREIGN KEY (`idgroup_slave`) REFERENCES `groups` (`idgroup`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_groups_listini_listini1` FOREIGN KEY (`idlistino`) REFERENCES `listini` (`idlistino`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `ordini`
--
ALTER TABLE `ordini`
  ADD CONSTRAINT `fk_ordini_groups_listini1` FOREIGN KEY (`idlistino`, `idgroup_slave`) REFERENCES `groups_listini` (`idlistino`, `idgroup_slave`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `ordini_prodotti`
--
ALTER TABLE `ordini_prodotti`
  ADD CONSTRAINT `fk_ordini_prodotti_ordini1` FOREIGN KEY (`idordine`) REFERENCES `ordini` (`idordine`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ordini_prodotti_prodotti_listini1` FOREIGN KEY (`idprodotto`, `idlistino`) REFERENCES `prodotti_listini` (`idprodotto`, `idlistino`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `ordini_user`
--
ALTER TABLE `ordini_user`
  ADD CONSTRAINT `fk_ordini_user_users1` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ordini_user_ordini1` FOREIGN KEY (`idordine`) REFERENCES `ordini` (`idordine`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `ordini_user_prodotti`
--
ALTER TABLE `ordini_user_prodotti`
  ADD CONSTRAINT `fk_ordini_user_prodotti_ordini_user1` FOREIGN KEY (`iduser`, `idordine`) REFERENCES `ordini_user` (`iduser`, `idordine`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ordini_user_prodotti_ordini_prodotti1` FOREIGN KEY (`idlistino`, `idprodotto`) REFERENCES `ordini_prodotti` (`idlistino`, `idprodotto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `prodotti`
--
ALTER TABLE `prodotti`
  ADD CONSTRAINT `FK_produttore_prodotti` FOREIGN KEY (`idproduttore`) REFERENCES `produttori` (`idproduttore`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_prodotti_sub_categorie1` FOREIGN KEY (`idsubcat`) REFERENCES `categorie_sub` (`idsubcat`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_prodotti_users1` FOREIGN KEY (`iduser_creator`) REFERENCES `users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `prodotti_listini`
--
ALTER TABLE `prodotti_listini`
  ADD CONSTRAINT `fk_prodotti_listini_prodotti1` FOREIGN KEY (`idprodotto`) REFERENCES `prodotti` (`idprodotto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_prodotti_listini_listini1` FOREIGN KEY (`idlistino`) REFERENCES `listini` (`idlistino`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `referenti`
--
ALTER TABLE `referenti`
  ADD CONSTRAINT `fk_referenti_groups1` FOREIGN KEY (`idgroup`) REFERENCES `groups` (`idgroup`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_referenti_produttori1` FOREIGN KEY (`idproduttore`) REFERENCES `produttori` (`idproduttore`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_referenti_users1` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `users_group`
--
ALTER TABLE `users_group`
  ADD CONSTRAINT `FK_group_users_group` FOREIGN KEY (`idgroup`) REFERENCES `groups` (`idgroup`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_user_users_group` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `users_produttori`
--
ALTER TABLE `users_produttori`
  ADD CONSTRAINT `FK_user_produttori` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_produttore` FOREIGN KEY (`idproduttore`) REFERENCES `produttori` (`idproduttore`) ON DELETE NO ACTION ON UPDATE NO ACTION;
