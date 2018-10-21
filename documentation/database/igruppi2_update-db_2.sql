CREATE TABLE `meta` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tablename` varchar(50) DEFAULT NULL,
  `tableid` int(11) DEFAULT NULL,
  `field` varchar(128) DEFAULT NULL,
  `val` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;