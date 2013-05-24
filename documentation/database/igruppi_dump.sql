-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 24, 2013 at 11:22 PM
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
  `descrizione` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idcat`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `idgroup` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `descrizione` text COLLATE utf8_unicode_ci NOT NULL,
  `idfondatore` int(10) unsigned NOT NULL,
  `provincia` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `data_creazione` date NOT NULL,
  PRIMARY KEY (`idgroup`),
  KEY `FK_fondatore_idx` (`idfondatore`),
  KEY `fk_group_province1_idx` (`provincia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `groups_produttori`
--

CREATE TABLE IF NOT EXISTS `groups_produttori` (
  `idgp` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idgroup` int(10) unsigned NOT NULL,
  `idproduttore` int(10) unsigned NOT NULL,
  `iduser_ref` int(10) unsigned NOT NULL,
  `stato` enum('N','I','A','B') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`idgp`),
  KEY `FK_user_ref_idx` (`iduser_ref`),
  KEY `FK_group_group_idx` (`idgroup`),
  KEY `FK_produttore_produttore_idx` (`idproduttore`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `ordini`
--

CREATE TABLE IF NOT EXISTS `ordini` (
  `idordine` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idgp` int(10) unsigned NOT NULL,
  `data_inizio` datetime NOT NULL,
  `data_fine` datetime NOT NULL,
  `archiviato` enum('N','S') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `note_consegna` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`idordine`),
  KEY `FK_group_produttori_idx` (`idgp`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `ordini_prodotti`
--

CREATE TABLE IF NOT EXISTS `ordini_prodotti` (
  `idprodotto` int(10) unsigned NOT NULL,
  `idordine` int(10) unsigned NOT NULL,
  `costo` decimal(8,2) NOT NULL,
  `offerta` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `sconto` tinyint(4) NOT NULL,
  PRIMARY KEY (`idprodotto`,`idordine`),
  KEY `fk_ordini_prodotti_prodotti1_idx` (`idprodotto`),
  KEY `fk_ordini_prodotti_ordini1_idx` (`idordine`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ordini_user_prodotti`
--

CREATE TABLE IF NOT EXISTS `ordini_user_prodotti` (
  `iduser` int(10) unsigned NOT NULL,
  `idprodotto` int(10) unsigned NOT NULL,
  `idordine` int(10) unsigned NOT NULL,
  `qta` smallint(5) unsigned NOT NULL,
  `data_ins` datetime NOT NULL,
  PRIMARY KEY (`iduser`,`idprodotto`,`idordine`),
  KEY `FK_iduser_idx` (`iduser`),
  KEY `fk_ordini_user_prodotti_ordini_prodotti1_idx` (`idprodotto`,`idordine`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prodotti`
--

CREATE TABLE IF NOT EXISTS `prodotti` (
  `idprodotto` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idproduttore` int(10) unsigned NOT NULL,
  `idcat` int(10) unsigned NOT NULL,
  `descrizione` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `udm` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `attivo` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'S',
  `costo` decimal(8,2) NOT NULL,
  `note` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idprodotto`),
  KEY `FK_fornitore_idx` (`idproduttore`),
  KEY `fk_prodotti_prodotti_category1_idx` (`idcat`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `prodotti_costi`
--

CREATE TABLE IF NOT EXISTS `prodotti_costi` (
  `idp_c` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idprodotto` int(10) unsigned NOT NULL,
  `idgroup` int(10) unsigned NOT NULL,
  `costo` decimal(8,2) NOT NULL,
  `offerta` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `sconto` tinyint(4) NOT NULL,
  PRIMARY KEY (`idp_c`),
  KEY `FK_prodotto_idx` (`idprodotto`),
  KEY `FK_group_idx` (`idgroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
  PRIMARY KEY (`idproduttore`),
  KEY `fk_produttori_province1_idx` (`provincia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

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
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `iduser` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `cognome` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `comune` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `provincia` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `role` enum('User','Admin') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'User',
  PRIMARY KEY (`iduser`),
  KEY `fk_users_province1_idx` (`provincia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_group`
--

CREATE TABLE IF NOT EXISTS `users_group` (
  `idug` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `iduser` int(10) unsigned NOT NULL,
  `idgroup` int(10) unsigned NOT NULL,
  `attivo` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`idug`),
  KEY `FK_group_idx2` (`idgroup`),
  KEY `FK_user_idx2` (`iduser`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_produttori`
--

CREATE TABLE IF NOT EXISTS `users_produttori` (
  `iduser_p` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `iduser` int(10) unsigned NOT NULL,
  `idproduttore` int(10) unsigned NOT NULL,
  `livello` enum('admin','user') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`iduser_p`),
  KEY `FK_user_idx` (`iduser`),
  KEY `FK_produttore_idx` (`idproduttore`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `FK_fondatore` FOREIGN KEY (`idfondatore`) REFERENCES `users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `groups_produttori`
--
ALTER TABLE `groups_produttori`
  ADD CONSTRAINT `FK_group_group_produttori` FOREIGN KEY (`idgroup`) REFERENCES `groups` (`idgroup`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_produttore_group_produttori` FOREIGN KEY (`idproduttore`) REFERENCES `produttori` (`idproduttore`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_user_ref` FOREIGN KEY (`iduser_ref`) REFERENCES `users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `ordini`
--
ALTER TABLE `ordini`
  ADD CONSTRAINT `FK_group_produttori` FOREIGN KEY (`idgp`) REFERENCES `groups_produttori` (`idgp`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `ordini_prodotti`
--
ALTER TABLE `ordini_prodotti`
  ADD CONSTRAINT `fk_ordini_prodotti_ordini1` FOREIGN KEY (`idordine`) REFERENCES `ordini` (`idordine`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ordini_prodotti_prodotti1` FOREIGN KEY (`idprodotto`) REFERENCES `prodotti` (`idprodotto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `ordini_user_prodotti`
--
ALTER TABLE `ordini_user_prodotti`
  ADD CONSTRAINT `FK_iduser_ordini_user_prodotti` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ordini_user_prodotti_ordini_prodotti1` FOREIGN KEY (`idprodotto`, `idordine`) REFERENCES `ordini_prodotti` (`idprodotto`, `idordine`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `prodotti`
--
ALTER TABLE `prodotti`
  ADD CONSTRAINT `fk_prodotti_prodotti_category1` FOREIGN KEY (`idcat`) REFERENCES `categorie` (`idcat`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_produttore_prodotti` FOREIGN KEY (`idproduttore`) REFERENCES `produttori` (`idproduttore`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `prodotti_costi`
--
ALTER TABLE `prodotti_costi`
  ADD CONSTRAINT `FK_group_prodotti_costi` FOREIGN KEY (`idgroup`) REFERENCES `groups` (`idgroup`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_prodotto_prodotti_costi` FOREIGN KEY (`idprodotto`) REFERENCES `prodotti` (`idprodotto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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
  ADD CONSTRAINT `FK_produttore` FOREIGN KEY (`idproduttore`) REFERENCES `produttori` (`idproduttore`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_user_produttori` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION;
