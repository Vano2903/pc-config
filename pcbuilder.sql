# Host: localhost  (Version 5.5.5-10.1.36-MariaDB)
# Date: 2023-05-12 12:10:02
# Generator: MySQL-Front 6.1  (Build 1.26)


#
# Structure for table "tipo_componenti"
#

DROP TABLE IF EXISTS `tipo_componenti`;
CREATE TABLE `tipo_componenti` (
  `processore` varchar(20) DEFAULT NULL,
  `scheda_video` varchar(20) DEFAULT NULL,
  `scheda_madre` varchar(20) DEFAULT NULL,
  `case_pc` varchar(20) DEFAULT NULL,
  `alimentatore` varchar(20) DEFAULT NULL,
  `archiviazione` varchar(20) DEFAULT NULL,
  `dissipatore` varchar(20) DEFAULT NULL,
  `ram` varchar(20) DEFAULT NULL,
  `Id_componente` varchar(10) NOT NULL,
  PRIMARY KEY (`Id_componente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "tipo_componenti"
#


#
# Structure for table "componenti"
#

DROP TABLE IF EXISTS `componenti`;
CREATE TABLE `componenti` (
  `marca_processore` varchar(10) DEFAULT NULL,
  `modello_processore` varchar(20) DEFAULT NULL,
  `velocita` varchar(10) DEFAULT NULL,
  `socket_processore` varchar(10) DEFAULT NULL,
  `wattaggio_processore` varchar(10) DEFAULT NULL,
  `n_core` int(2) DEFAULT NULL,
  `n_thread` int(2) DEFAULT NULL,
  `modello_scheda_madre` varchar(20) DEFAULT NULL,
  `socket_sceda_madre` varchar(20) DEFAULT NULL,
  `tipo_memoria` varchar(10) DEFAULT NULL,
  `chipset` varchar(10) DEFAULT NULL,
  `velocita_max_memoria` varchar(10) DEFAULT NULL,
  `capacita_max_memoria` varchar(10) DEFAULT NULL,
  `marca_scheda_video` varchar(10) DEFAULT NULL,
  `modello_scheda_video` varchar(20) DEFAULT NULL,
  `ram` varchar(5) DEFAULT NULL,
  `velocita_gpu` varchar(10) DEFAULT NULL,
  `tipo_ram` varchar(10) DEFAULT NULL,
  `marca_alimentatore` varchar(10) DEFAULT NULL,
  `modello_alimentatore` varchar(20) DEFAULT NULL,
  `wattaggio_alimentatore` varchar(10) DEFAULT NULL,
  `formato` varchar(10) DEFAULT NULL,
  `modulo` varchar(20) DEFAULT NULL,
  `modello_archiviazione` varchar(20) DEFAULT NULL,
  `tipo_archiviazione` varchar(20) DEFAULT NULL,
  `capacita` varchar(10) DEFAULT NULL,
  `marca_case` varchar(20) DEFAULT NULL,
  `tipo_case` varchar(20) DEFAULT NULL,
  `n_ventole` varchar(20) DEFAULT NULL,
  `marca_dissipatore` varchar(20) DEFAULT NULL,
  `tipo_dissipatore` varchar(20) DEFAULT NULL,
  `numero_ventole` varchar(20) DEFAULT NULL,
  `Id_componente` varchar(10) NOT NULL,
  KEY `Id_componente` (`Id_componente`),
  CONSTRAINT `componenti_ibfk_1` FOREIGN KEY (`Id_componente`) REFERENCES `tipo_componenti` (`Id_componente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "componenti"
#


#
# Structure for table "utente"
#

DROP TABLE IF EXISTS `utente`;
CREATE TABLE `utente` (
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "utente"
#

