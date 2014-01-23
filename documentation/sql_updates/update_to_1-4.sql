-- 
-- Script to update Schema DB iGruppi 1.4
-- 

ALTER TABLE  `ordini` ADD  `costo_spedizione` DECIMAL( 8, 2 ) NOT NULL,
                      ADD  `data_inconsegna` DATETIME NULL DEFAULT NULL AFTER  `data_fine` ,
                      ADD  `data_consegnato` DATETIME NULL DEFAULT NULL AFTER  `data_inconsegna`;


CREATE  TABLE IF NOT EXISTS `ordini_variazioni` (
  `idov` INT NOT NULL AUTO_INCREMENT ,
  `idordine` INT UNSIGNED NOT NULL ,
  `data` DATETIME NOT NULL ,
  `descrizione` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`idov`) ,
  INDEX `fk_ordini_variazioni_ordini1_idx` (`idordine` ASC) ,
  CONSTRAINT `fk_ordini_variazioni_ordini1`
    FOREIGN KEY (`idordine` )
    REFERENCES `ordini` (`idordine` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


ALTER TABLE  `prodotti` ADD  `aliquota_iva` TINYINT NOT NULL DEFAULT  '0' AFTER  `costo`;

ALTER TABLE  `categorie` ADD  `aliquota_iva` TINYINT NOT NULL DEFAULT  '0' AFTER  `descrizione`;

ALTER TABLE  `ordini_prodotti` ADD  `disponibile` ENUM(  'S',  'N' ) NOT NULL DEFAULT  'S' AFTER  `sconto`;

ALTER TABLE  `ordini_user_prodotti` ADD  `qta_reale` DECIMAL( 6, 3 ) NOT NULL AFTER  `qta`;

ALTER TABLE  `users` ADD INDEX  `INDEX_email` (  `email` );

-- 
-- UPDATE DATA
-- 

UPDATE `ordini_user_prodotti` SET qta_reale = qta;