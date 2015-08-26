SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `cassa` (
  `idmovimento` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `iduser` int(10) unsigned NOT NULL,
  `importo` decimal(10,2) NOT NULL,
  `data` datetime NOT NULL,
  `descrizione` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `idordine` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idmovimento`),
  KEY `fk_cassa_users1_idx` (`iduser`),
  KEY `fk_cassa_ordini1_idx` (`idordine`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `categorie` (
  `idcat` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `descrizione` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `aliquota_iva` tinyint(4) NOT NULL,
  PRIMARY KEY (`idcat`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `categorie_sub` (
  `idsubcat` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idcat` int(10) unsigned NOT NULL,
  `idproduttore` int(10) unsigned NOT NULL,
  `descrizione` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idsubcat`),
  KEY `fk_categorie_sub_categorie1_idx` (`idcat`),
  KEY `fk_categorie_sub_produttori1_idx` (`idproduttore`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `groups` (
  `idgroup` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `descrizione` text COLLATE utf8_unicode_ci NOT NULL,
  `provincia` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `data_creazione` date NOT NULL,
  `email_ml` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idgroup`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `listini` (
  `idlistino` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idproduttore` int(10) unsigned NOT NULL,
  `descrizione` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `condivisione` enum('PUB','PRI','SHA') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'PRI',
  `user_update` date NOT NULL,
  `last_update` datetime NOT NULL,
  PRIMARY KEY (`idlistino`),
  KEY `fk_listini_produttori1_idx` (`idproduttore`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `listini_groups` (
  `idlistino` int(10) unsigned NOT NULL,
  `idgroup_master` int(10) unsigned NOT NULL,
  `idgroup_slave` int(10) unsigned NOT NULL,
  `valido_dal` date DEFAULT NULL,
  `valido_al` date DEFAULT NULL,
  `visibile` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`idlistino`,`idgroup_master`,`idgroup_slave`),
  KEY `fk_groups_listini_groups2_idx` (`idgroup_slave`),
  KEY `fk_groups_listini_listini1_idx` (`idlistino`),
  KEY `fk_groups_listini_groups1_idx` (`idgroup_master`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `listini_prodotti` (
  `idlistino` int(10) unsigned NOT NULL,
  `idprodotto` int(10) unsigned NOT NULL,
  `descrizione_listino` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `costo_listino` decimal(8,2) NOT NULL,
  `note_listino` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `attivo_listino` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'S',
  PRIMARY KEY (`idlistino`,`idprodotto`),
  KEY `fk_listini_prodotti1_idx` (`idprodotto`),
  KEY `fk_prodotti_listini_listini1_idx` (`idlistino`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `ordini` (
  `idordine` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `iduser_supervisore` int(10) unsigned NOT NULL,
  `descrizione` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `data_inizio` datetime NOT NULL,
  `data_fine` datetime NOT NULL,
  `data_inviato` datetime DEFAULT NULL,
  `data_arrivato` datetime DEFAULT NULL,
  `data_consegnato` datetime DEFAULT NULL,
  `archiviato` enum('N','S') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `condivisione` enum('PUB','PRI','SHA') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'PRI',
  PRIMARY KEY (`idordine`),
  KEY `fk_ordini_users1` (`iduser_supervisore`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `ordini_groups` (
  `idordine` int(10) unsigned NOT NULL,
  `idgroup_master` int(10) unsigned NOT NULL,
  `idgroup_slave` int(10) unsigned NOT NULL,
  `iduser_incaricato` int(10) unsigned DEFAULT NULL,
  `visibile` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'S',
  `extra` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `note_consegna` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idordine`,`idgroup_master`,`idgroup_slave`),
  KEY `fk_groups_ordini_ordini1_idx` (`idordine`),
  KEY `fk_groups_ordini_groups2_idx` (`idgroup_slave`),
  KEY `fk_groups_ordini_groups1_idx` (`idgroup_master`),
  KEY `fk_groups_ordini_users1_idx` (`iduser_incaricato`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `ordini_prodotti` (
  `idordine` int(10) unsigned NOT NULL,
  `idlistino` int(10) unsigned NOT NULL,
  `idprodotto` int(10) unsigned NOT NULL,
  `costo_ordine` decimal(8,2) NOT NULL,
  `offerta_ordine` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `sconto_ordine` tinyint(4) NOT NULL,
  `disponibile_ordine` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'S',
  PRIMARY KEY (`idordine`,`idlistino`,`idprodotto`),
  KEY `fk_ordini_prodotti_ordini1_idx` (`idordine`),
  KEY `fk_ordini_prodotti_prodotti_listini1_idx` (`idlistino`,`idprodotto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `ordini_users` (
  `iduser` int(10) unsigned NOT NULL,
  `idordine` int(10) unsigned NOT NULL,
  `note` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`iduser`,`idordine`),
  KEY `fk_ordini_user_ordini1_idx` (`idordine`),
  KEY `fk_ordini_user_ordini2_idx` (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `ordini_user_prodotti` (
  `iduser` int(10) unsigned NOT NULL,
  `idordine` int(10) unsigned NOT NULL,
  `idlistino` int(10) unsigned NOT NULL,
  `idprodotto` int(10) unsigned NOT NULL,
  `qta` smallint(5) unsigned NOT NULL,
  `qta_reale` decimal(6,3) NOT NULL,
  `data_ins` datetime NOT NULL,
  PRIMARY KEY (`iduser`,`idordine`,`idlistino`,`idprodotto`),
  KEY `fk_ordini_user_prodotti_ordini_prodotti1_idx` (`idlistino`,`idprodotto`),
  KEY `fk_ordini_user_prodotti_ordini_user1_idx` (`iduser`,`idordine`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `ordini_variazioni` (
  `idov` int(11) NOT NULL AUTO_INCREMENT,
  `idordine` int(10) unsigned NOT NULL,
  `data` datetime NOT NULL,
  `descrizione` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `iduser` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idov`),
  KEY `fk_ordini_variazioni_ordini1_idx` (`idordine`),
  KEY `fk_ordini_variazioni_ordini_user1_idx` (`iduser`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `prodotti` (
  `idprodotto` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idproduttore` int(10) unsigned NOT NULL,
  `idsubcat` int(10) unsigned NOT NULL,
  `iduser_creator` int(10) unsigned NOT NULL,
  `codice` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `descrizione` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `udm` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `moltiplicatore` decimal(8,2) NOT NULL DEFAULT '1.00',
  `costo` decimal(8,2) NOT NULL,
  `aliquota_iva` tinyint(4) NOT NULL,
  `note` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `attivo` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'S',
  `production` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`idprodotto`),
  KEY `fk_prodotti_produttori1_idx` (`idproduttore`),
  KEY `fk_prodotti_sub_categorie1_idx` (`idsubcat`),
  KEY `fk_prodotti_users1_idx` (`iduser_creator`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `produttori` (
  `idproduttore` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ragsoc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `indirizzo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `comune` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `provincia` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `note` varchar(2048) COLLATE utf8_unicode_ci NOT NULL,
  `production` enum('S','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`idproduttore`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `province` (
  `provincia` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `idregione` tinyint(4) NOT NULL,
  `provdesc` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`provincia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `referenti` (
  `idgroup` int(10) unsigned NOT NULL,
  `idproduttore` int(10) unsigned NOT NULL,
  `iduser_ref` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idgroup`,`idproduttore`,`iduser_ref`),
  KEY `fk_referenti_produttori1_idx` (`idproduttore`),
  KEY `fk_referenti_users1_idx` (`iduser_ref`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `iduser` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `cognome` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `num_members` tinyint(4) NOT NULL,
  `comune` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `provincia` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `role` enum('User','Admin') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'User',
  PRIMARY KEY (`iduser`),
  KEY `INDEX_email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE IF NOT EXISTS `users_produttori` (
  `iduser` int(10) unsigned NOT NULL,
  `idproduttore` int(10) unsigned NOT NULL,
  `livello` enum('ref','user') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`iduser`,`idproduttore`),
  KEY `FK_user_idx` (`iduser`),
  KEY `FK_produttore_idx` (`idproduttore`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `cassa`
  ADD CONSTRAINT `fk_cassa_ordini1` FOREIGN KEY (`idordine`) REFERENCES `ordini` (`idordine`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cassa_users1` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `categorie_sub`
  ADD CONSTRAINT `fk_categorie_sub_categorie1` FOREIGN KEY (`idcat`) REFERENCES `categorie` (`idcat`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_categorie_sub_produttori1` FOREIGN KEY (`idproduttore`) REFERENCES `produttori` (`idproduttore`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `listini`
  ADD CONSTRAINT `fk_listini_produttori1` FOREIGN KEY (`idproduttore`) REFERENCES `produttori` (`idproduttore`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `listini_groups`
  ADD CONSTRAINT `fk_groups_listini_listini1` FOREIGN KEY (`idlistino`) REFERENCES `listini` (`idlistino`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_groups_produttori_groups1` FOREIGN KEY (`idgroup_master`) REFERENCES `groups` (`idgroup`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_groups_produttori_groups2` FOREIGN KEY (`idgroup_slave`) REFERENCES `groups` (`idgroup`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `listini_prodotti`
  ADD CONSTRAINT `fk_prodotti_listini_listini1` FOREIGN KEY (`idlistino`) REFERENCES `listini` (`idlistino`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_prodotti_listini_prodotti1` FOREIGN KEY (`idprodotto`) REFERENCES `prodotti` (`idprodotto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `ordini`
  ADD CONSTRAINT `fk_ordini_users1` FOREIGN KEY (`iduser_supervisore`) REFERENCES `users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `ordini_groups`
  ADD CONSTRAINT `fk_groups_ordini_groups1` FOREIGN KEY (`idgroup_master`) REFERENCES `groups` (`idgroup`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_groups_ordini_groups2` FOREIGN KEY (`idgroup_slave`) REFERENCES `groups` (`idgroup`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_groups_ordini_ordini1` FOREIGN KEY (`idordine`) REFERENCES `ordini` (`idordine`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_groups_ordini_users1` FOREIGN KEY (`iduser_incaricato`) REFERENCES `users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `ordini_prodotti`
  ADD CONSTRAINT `fk_ordini_prodotti_ordini1` FOREIGN KEY (`idordine`) REFERENCES `ordini` (`idordine`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ordini_prodotti_prodotti_listini1` FOREIGN KEY (`idlistino`, `idprodotto`) REFERENCES `listini_prodotti` (`idlistino`, `idprodotto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `ordini_users`
  ADD CONSTRAINT `fk_ordini_user_ordini1` FOREIGN KEY (`idordine`) REFERENCES `ordini` (`idordine`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ordini_user_users1` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `ordini_user_prodotti`
  ADD CONSTRAINT `fk_ordini_user_prodotti_ordini_prodotti1` FOREIGN KEY (`idlistino`, `idprodotto`) REFERENCES `ordini_prodotti` (`idlistino`, `idprodotto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ordini_user_prodotti_ordini_user1` FOREIGN KEY (`iduser`, `idordine`) REFERENCES `ordini_users` (`iduser`, `idordine`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `ordini_variazioni`
  ADD CONSTRAINT `fk_ordini_variazioni_ordini1` FOREIGN KEY (`idordine`) REFERENCES `ordini` (`idordine`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ordini_variazioni_ordini_user1` FOREIGN KEY (`iduser`) REFERENCES `ordini_users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `prodotti`
  ADD CONSTRAINT `FK_produttore_prodotti` FOREIGN KEY (`idproduttore`) REFERENCES `produttori` (`idproduttore`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_prodotti_sub_categorie1` FOREIGN KEY (`idsubcat`) REFERENCES `categorie_sub` (`idsubcat`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_prodotti_users1` FOREIGN KEY (`iduser_creator`) REFERENCES `users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `referenti`
  ADD CONSTRAINT `fk_referenti_groups1` FOREIGN KEY (`idgroup`) REFERENCES `groups` (`idgroup`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_referenti_produttori1` FOREIGN KEY (`idproduttore`) REFERENCES `produttori` (`idproduttore`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_referenti_users1` FOREIGN KEY (`iduser_ref`) REFERENCES `users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `users_group`
  ADD CONSTRAINT `FK_group_users_group` FOREIGN KEY (`idgroup`) REFERENCES `groups` (`idgroup`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_user_users_group` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `users_produttori`
  ADD CONSTRAINT `FK_produttore` FOREIGN KEY (`idproduttore`) REFERENCES `produttori` (`idproduttore`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_user_produttori` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
