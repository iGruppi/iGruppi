SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `users` (
  `iduser` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL ,
  `cognome` VARCHAR(45) NOT NULL ,
  `email` VARCHAR(100) NOT NULL ,
  `password` VARCHAR(12) NOT NULL ,
  `num_members` TINYINT NOT NULL ,
  `tel` VARCHAR(30) NOT NULL ,
  `indirizzo` VARCHAR(50) NOT NULL ,
  `localita` VARCHAR(45) NOT NULL ,
  `provincia` CHAR(2) NOT NULL ,
  `in_prova` ENUM('S','N') NOT NULL DEFAULT 'N' ,
  `role` ENUM('User','Admin') NOT NULL DEFAULT 'User' ,
  `note` VARCHAR(1024) NOT NULL ,
  PRIMARY KEY (`iduser`) ,
  INDEX `INDEX_email` (`email` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `province`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `province` (
  `provincia` CHAR(2) NOT NULL ,
  `idregione` TINYINT NOT NULL ,
  `provdesc` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`provincia`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `groups`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `groups` (
  `idgroup` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(50) NOT NULL ,
  `descrizione` TEXT NOT NULL ,
  `provincia` CHAR(2) NOT NULL ,
  `data_creazione` DATE NOT NULL ,
  `email_ml` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`idgroup`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `users_group`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `users_group` (
  `iduser` INT UNSIGNED NOT NULL ,
  `idgroup` INT UNSIGNED NOT NULL ,
  `attivo` ENUM('S','N') NOT NULL DEFAULT 'N' ,
  `fondatore` ENUM('S','N') NOT NULL DEFAULT 'N' ,
  `contabile` ENUM('S','N') NOT NULL DEFAULT 'N' ,
  PRIMARY KEY (`iduser`, `idgroup`) ,
  INDEX `FK_group_idx2` (`idgroup` ASC) ,
  INDEX `FK_user_idx2` (`iduser` ASC) ,
  CONSTRAINT `FK_group_users_group`
    FOREIGN KEY (`idgroup` )
    REFERENCES `groups` (`idgroup` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_user_users_group`
    FOREIGN KEY (`iduser` )
    REFERENCES `users` (`iduser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `produttori`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `produttori` (
  `idproduttore` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `ragsoc` VARCHAR(255) NOT NULL ,
  `indirizzo` VARCHAR(100) NOT NULL ,
  `comune` VARCHAR(50) NOT NULL ,
  `provincia` CHAR(2) NOT NULL ,
  `telefono` VARCHAR(45) NOT NULL ,
  `email` VARCHAR(50) NOT NULL ,
  `note` VARCHAR(2048) NOT NULL ,
  `production` ENUM('S','N') NOT NULL DEFAULT 'N' ,
  PRIMARY KEY (`idproduttore`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `categorie`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `categorie` (
  `idcat` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `descrizione` VARCHAR(20) NOT NULL ,
  `aliquota_iva` TINYINT NOT NULL ,
  PRIMARY KEY (`idcat`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `categorie_sub`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `categorie_sub` (
  `idsubcat` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idcat` INT UNSIGNED NOT NULL ,
  `idproduttore` INT UNSIGNED NOT NULL ,
  `descrizione` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`idsubcat`) ,
  INDEX `fk_categorie_sub_categorie1_idx` (`idcat` ASC) ,
  INDEX `fk_categorie_sub_produttori1_idx` (`idproduttore` ASC) ,
  CONSTRAINT `fk_categorie_sub_categorie1`
    FOREIGN KEY (`idcat` )
    REFERENCES `categorie` (`idcat` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_categorie_sub_produttori1`
    FOREIGN KEY (`idproduttore` )
    REFERENCES `produttori` (`idproduttore` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `prodotti`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `prodotti` (
  `idprodotto` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idproduttore` INT UNSIGNED NOT NULL ,
  `idsubcat` INT UNSIGNED NOT NULL ,
  `iduser_creator` INT UNSIGNED NULL ,
  `codice` VARCHAR(50) NOT NULL ,
  `descrizione` VARCHAR(255) NOT NULL ,
  `udm` VARCHAR(30) NOT NULL ,
  `moltiplicatore` DECIMAL(8,2) NOT NULL DEFAULT 1.00 ,
  `costo` DECIMAL(8,2) NOT NULL ,
  `aliquota_iva` TINYINT NOT NULL ,
  `note` VARCHAR(1024) NOT NULL ,
  `attivo` ENUM('S','N') NOT NULL DEFAULT 'S' ,
  `production` ENUM('S','N') NOT NULL DEFAULT 'S' ,
  PRIMARY KEY (`idprodotto`) ,
  INDEX `fk_prodotti_produttori1_idx` (`idproduttore` ASC) ,
  INDEX `fk_prodotti_sub_categorie1_idx` (`idsubcat` ASC) ,
  INDEX `fk_prodotti_users1_idx` (`iduser_creator` ASC) ,
  CONSTRAINT `FK_produttore_prodotti`
    FOREIGN KEY (`idproduttore` )
    REFERENCES `produttori` (`idproduttore` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_prodotti_sub_categorie1`
    FOREIGN KEY (`idsubcat` )
    REFERENCES `categorie_sub` (`idsubcat` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_prodotti_users1`
    FOREIGN KEY (`iduser_creator` )
    REFERENCES `users` (`iduser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ordini`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ordini` (
  `idordine` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `iduser_supervisore` INT UNSIGNED NOT NULL ,
  `descrizione` VARCHAR(50) NOT NULL ,
  `data_inizio` DATETIME NOT NULL ,
  `data_fine` DATETIME NOT NULL ,
  `data_inviato` DATETIME NULL ,
  `data_arrivato` DATETIME NULL ,
  `data_consegnato` DATETIME NULL ,
  `condivisione` ENUM('PUB','PRI','SHA') NOT NULL DEFAULT 'PRI' ,
  PRIMARY KEY (`idordine`) ,
  INDEX `fk_ordini_users1_idx` (`iduser_supervisore` ASC) ,
  CONSTRAINT `fk_ordini_users1`
    FOREIGN KEY (`iduser_supervisore` )
    REFERENCES `users` (`iduser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ordini_users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ordini_users` (
  `iduser` INT UNSIGNED NOT NULL ,
  `idordine` INT UNSIGNED NOT NULL ,
  `note` VARCHAR(1024) NOT NULL ,
  PRIMARY KEY (`iduser`, `idordine`) ,
  INDEX `fk_ordini_user_ordini1_idx` (`idordine` ASC) ,
  INDEX `fk_ordini_user_ordini2_idx` (`iduser` ASC) ,
  CONSTRAINT `fk_ordini_user_users1`
    FOREIGN KEY (`iduser` )
    REFERENCES `users` (`iduser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ordini_user_ordini1`
    FOREIGN KEY (`idordine` )
    REFERENCES `ordini` (`idordine` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `listini`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `listini` (
  `idlistino` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idproduttore` INT UNSIGNED NOT NULL ,
  `descrizione` VARCHAR(100) NOT NULL ,
  `condivisione` ENUM('PUB','PRI','SHA') NOT NULL DEFAULT 'PRI' ,
  `user_update` DATE NOT NULL ,
  `last_update` DATETIME NOT NULL ,
  PRIMARY KEY (`idlistino`) ,
  INDEX `fk_listini_produttori1_idx` (`idproduttore` ASC) ,
  CONSTRAINT `fk_listini_produttori1`
    FOREIGN KEY (`idproduttore` )
    REFERENCES `produttori` (`idproduttore` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `listini_prodotti`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `listini_prodotti` (
  `idlistino` INT(10) UNSIGNED NOT NULL ,
  `idprodotto` INT(10) UNSIGNED NOT NULL ,
  `costo_listino` DECIMAL(8,2) NOT NULL ,
  `note_listino` VARCHAR(1024) NOT NULL ,
  `attivo_listino` ENUM('S','N') NOT NULL DEFAULT 'S' ,
  PRIMARY KEY (`idlistino`, `idprodotto`) ,
  INDEX `fk_listini_prodotti1_idx` (`idprodotto` ASC) ,
  INDEX `fk_prodotti_listini_listini1_idx` (`idlistino` ASC) ,
  CONSTRAINT `fk_prodotti_listini_listini1`
    FOREIGN KEY (`idlistino` )
    REFERENCES `listini` (`idlistino` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_prodotti_listini_prodotti1`
    FOREIGN KEY (`idprodotto` )
    REFERENCES `prodotti` (`idprodotto` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `ordini_prodotti`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ordini_prodotti` (
  `idordine` INT(10) UNSIGNED NOT NULL ,
  `idlistino` INT(10) UNSIGNED NOT NULL ,
  `idprodotto` INT(10) UNSIGNED NOT NULL ,
  `costo_ordine` DECIMAL(8,2) NOT NULL ,
  `offerta_ordine` ENUM('S','N') NOT NULL DEFAULT 'N' ,
  `sconto_ordine` TINYINT(4) NOT NULL ,
  `disponibile_ordine` ENUM('S','N') NOT NULL DEFAULT 'S' ,
  PRIMARY KEY (`idordine`, `idlistino`, `idprodotto`) ,
  INDEX `fk_ordini_prodotti_ordini1_idx` (`idordine` ASC) ,
  INDEX `fk_ordini_prodotti_prodotti_listini1_idx` (`idlistino` ASC, `idprodotto` ASC) ,
  CONSTRAINT `fk_ordini_prodotti_ordini1`
    FOREIGN KEY (`idordine` )
    REFERENCES `ordini` (`idordine` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ordini_prodotti_prodotti_listini1`
    FOREIGN KEY (`idlistino` , `idprodotto` )
    REFERENCES `listini_prodotti` (`idlistino` , `idprodotto` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `ordini_user_prodotti`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ordini_user_prodotti` (
  `iduser` INT UNSIGNED NOT NULL ,
  `idordine` INT UNSIGNED NOT NULL ,
  `idlistino` INT UNSIGNED NOT NULL ,
  `idprodotto` INT UNSIGNED NOT NULL ,
  `qta` SMALLINT UNSIGNED NOT NULL ,
  `qta_reale` DECIMAL(6,3) NOT NULL ,
  `data_ins` DATETIME NOT NULL ,
  PRIMARY KEY (`iduser`, `idordine`, `idlistino`, `idprodotto`) ,
  INDEX `fk_ordini_user_prodotti_ordini_user1_idx` (`iduser` ASC, `idordine` ASC) ,
  INDEX `fk_ordini_user_prodotti_ordini_prodotti1_idx` (`idlistino` ASC, `idprodotto` ASC, `idordine` ASC) ,
  CONSTRAINT `fk_ordini_user_prodotti_ordini_user1`
    FOREIGN KEY (`iduser` , `idordine` )
    REFERENCES `ordini_users` (`iduser` , `idordine` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ordini_user_prodotti_ordini_prodotti1`
    FOREIGN KEY (`idlistino` , `idprodotto` , `idordine` )
    REFERENCES `ordini_prodotti` (`idlistino` , `idprodotto` , `idordine` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `users_produttori`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `users_produttori` (
  `iduser` INT UNSIGNED NOT NULL ,
  `idproduttore` INT UNSIGNED NOT NULL ,
  `livello` ENUM('ref','user') NOT NULL DEFAULT 'user' ,
  PRIMARY KEY (`iduser`, `idproduttore`) ,
  INDEX `FK_user_idx` (`iduser` ASC) ,
  INDEX `FK_produttore_idx` (`idproduttore` ASC) ,
  CONSTRAINT `FK_user_produttori`
    FOREIGN KEY (`iduser` )
    REFERENCES `users` (`iduser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_produttore`
    FOREIGN KEY (`idproduttore` )
    REFERENCES `produttori` (`idproduttore` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ordini_variazioni`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ordini_variazioni` (
  `idov` INT NOT NULL AUTO_INCREMENT ,
  `idordine` INT UNSIGNED NOT NULL ,
  `data` DATETIME NOT NULL ,
  `descrizione` VARCHAR(255) NOT NULL ,
  `iduser` INT UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`idov`) ,
  INDEX `fk_ordini_variazioni_ordini1_idx` (`idordine` ASC) ,
  INDEX `fk_ordini_variazioni_ordini_user1_idx` (`iduser` ASC) ,
  CONSTRAINT `fk_ordini_variazioni_ordini1`
    FOREIGN KEY (`idordine` )
    REFERENCES `ordini` (`idordine` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ordini_variazioni_ordini_user1`
    FOREIGN KEY (`iduser` )
    REFERENCES `ordini_users` (`iduser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `listini_groups`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `listini_groups` (
  `idlistino` INT UNSIGNED NOT NULL ,
  `idgroup_master` INT UNSIGNED NOT NULL ,
  `idgroup_slave` INT UNSIGNED NOT NULL ,
  `valido_dal` DATE NULL DEFAULT NULL ,
  `valido_al` DATE NULL DEFAULT NULL ,
  `visibile` ENUM('S','N') NOT NULL DEFAULT 'N' ,
  INDEX `fk_groups_listini_groups2_idx` (`idgroup_slave` ASC) ,
  PRIMARY KEY (`idlistino`, `idgroup_master`, `idgroup_slave`) ,
  INDEX `fk_groups_listini_listini1_idx` (`idlistino` ASC) ,
  INDEX `fk_groups_listini_groups1_idx` (`idgroup_master` ASC) ,
  CONSTRAINT `fk_groups_produttori_groups1`
    FOREIGN KEY (`idgroup_master` )
    REFERENCES `groups` (`idgroup` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_groups_produttori_groups2`
    FOREIGN KEY (`idgroup_slave` )
    REFERENCES `groups` (`idgroup` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_groups_listini_listini1`
    FOREIGN KEY (`idlistino` )
    REFERENCES `listini` (`idlistino` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `referenti`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `referenti` (
  `idgroup` INT UNSIGNED NOT NULL ,
  `idproduttore` INT UNSIGNED NOT NULL ,
  `iduser_referente` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`idgroup`, `idproduttore`, `iduser_referente`) ,
  INDEX `fk_referenti_produttori1_idx` (`idproduttore` ASC) ,
  INDEX `fk_referenti_users1_idx` (`iduser_referente` ASC) ,
  CONSTRAINT `fk_referenti_groups1`
    FOREIGN KEY (`idgroup` )
    REFERENCES `groups` (`idgroup` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_referenti_produttori1`
    FOREIGN KEY (`idproduttore` )
    REFERENCES `produttori` (`idproduttore` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_referenti_users1`
    FOREIGN KEY (`iduser_referente` )
    REFERENCES `users` (`iduser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ordini_groups`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ordini_groups` (
  `idordine` INT UNSIGNED NOT NULL ,
  `idgroup_master` INT UNSIGNED NOT NULL ,
  `idgroup_slave` INT UNSIGNED NOT NULL ,
  `iduser_incaricato` INT UNSIGNED NULL DEFAULT NULL ,
  `visibile` ENUM('S','N') NOT NULL DEFAULT 'S' ,
  `extra` VARCHAR(1024) NOT NULL ,
  `archiviato` ENUM('S','N') NOT NULL ,
  `note_consegna` TEXT NOT NULL ,
  PRIMARY KEY (`idordine`, `idgroup_master`, `idgroup_slave`) ,
  INDEX `fk_groups_ordini_ordini1_idx` (`idordine` ASC) ,
  INDEX `fk_groups_ordini_users1_idx` (`iduser_incaricato` ASC) ,
  INDEX `fk_groups_ordini_groups2_idx` (`idgroup_slave` ASC) ,
  INDEX `fk_groups_ordini_groups1_idx` (`idgroup_master` ASC) ,
  CONSTRAINT `fk_groups_ordini_groups1`
    FOREIGN KEY (`idgroup_master` )
    REFERENCES `groups` (`idgroup` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_groups_ordini_ordini1`
    FOREIGN KEY (`idordine` )
    REFERENCES `ordini` (`idordine` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_groups_ordini_users1`
    FOREIGN KEY (`iduser_incaricato` )
    REFERENCES `users` (`iduser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_groups_ordini_groups2`
    FOREIGN KEY (`idgroup_slave` )
    REFERENCES `groups` (`idgroup` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cassa`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `cassa` (
  `idmovimento` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `iduser` INT UNSIGNED NOT NULL ,
  `importo` DECIMAL(10,2) NOT NULL ,
  `data` DATETIME NOT NULL ,
  `descrizione` VARCHAR(512) NOT NULL ,
  `idordine` INT UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`idmovimento`) ,
  INDEX `fk_cassa_users1_idx` (`iduser` ASC) ,
  INDEX `fk_cassa_ordini1_idx` (`idordine` ASC) ,
  CONSTRAINT `fk_cassa_users1`
    FOREIGN KEY (`iduser` )
    REFERENCES `users` (`iduser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cassa_ordini1`
    FOREIGN KEY (`idordine` )
    REFERENCES `ordini` (`idordine` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
