-- phpMyAdmin SQL Dump
-- version 2.8.2-Debian-0.2
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tiempo de generación: 08-05-2007 a las 13:57:43
-- Versión del servidor: 5.0.24
-- Versión de PHP: 5.1.6
-- 
-- Base de datos: `packman3`
-- 

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `architecture`
-- 

CREATE TABLE `architecture` (
  `id_architecture` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id_architecture`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Volcar la base de datos para la tabla `architecture`
-- 

INSERT INTO `architecture` (`id_architecture`) VALUES ('i386');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `branch`
-- 

CREATE TABLE `branch` (
  `id_branch` varchar(30) NOT NULL default '',
  `description` text,
  PRIMARY KEY  (`id_branch`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Volcar la base de datos para la tabla `branch`
-- 

INSERT INTO `branch` (`id_branch`, `description`) VALUES ('main', NULL),
('contrib', NULL),
('non-free', NULL),
('guada', NULL),
('restricted', NULL),
('universe', NULL),
('multiverse', NULL);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `distribution`
-- 

CREATE TABLE `distribution` (
  `id_distribution` int(11) NOT NULL auto_increment,
  `id_repository` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `description` text,
  PRIMARY KEY  (`id_distribution`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

-- 
-- Volcar la base de datos para la tabla `distribution`
-- 

INSERT INTO `distribution` (`id_distribution`, `id_repository`, `name`, `description`) VALUES (1, 1, 'liron', NULL),
(2, 1, 'liron/non-US', NULL),
(3, 2, 'sarge', NULL),
(4, 3, 'sarge/non-US', NULL),
(5, 4, 'sarge/updates', NULL),
(6, 5, 'muflon', NULL),
(7, 6, 'breezy', NULL),
(8, 7, 'flamenco', NULL),
(9, 7, 'flamenco-updates', NULL),
(10, 7, 'flamenco-security', NULL),
(11, 7, 'flamenco-backports', NULL),
(12, 8, 'edgy', NULL),
(13, 9, 'toro', NULL),
(14, 9, 'toro-updates', NULL),
(15, 9, 'toro-security', NULL),
(16, 9, 'toro-backports', NULL);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `edition`
-- 

CREATE TABLE `edition` (
  `id_edition` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `description` text,
  `release_date` date default NULL,
  PRIMARY KEY  (`id_edition`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Volcar la base de datos para la tabla `edition`
-- 

INSERT INTO `edition` (`id_edition`, `name`, `description`, `release_date`) VALUES (1, 'Guadalinex V1.0', NULL, '2004-02-22'),
(2, 'Guadalinex 2004', NULL, '2004-10-07'),
(3, 'Guadalinex V3', NULL, '2006-02-08'),
(4, 'Guadalinex V4', NULL, NULL);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `package`
-- 

CREATE TABLE `package` (
  `id_package` int(11) NOT NULL auto_increment,
  `id_relation` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `version` varchar(50) NOT NULL default '',
  `section` varchar(50) default NULL,
  `installed_size` int(11) default '0',
  `maintainer` varchar(50) default NULL,
  `architecture` varchar(30) default NULL,
  `dependes` text,
  `conflicts` varchar(255) default NULL,
  `recommends` varchar(255) default NULL,
  `suggests` varchar(255) default NULL,
  `filename` varchar(255) NOT NULL default '',
  `size` int(11) default '0',
  `description` text,
  `md5` varchar(32) NOT NULL default '',
  `distributed` enum('s','n') default 'n',
  PRIMARY KEY  (`id_package`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `package`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relation`
-- 

CREATE TABLE `relation` (
  `id_relation` int(11) NOT NULL auto_increment,
  `id_distribution` int(11) default NULL,
  `id_branch` varchar(30) default NULL,
  `id_architecture` varchar(10) default NULL,
  `temporary_sign` varchar(40) default NULL,
  PRIMARY KEY  (`id_relation`),
  UNIQUE KEY `id_distribution` (`id_distribution`,`id_branch`,`id_architecture`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=latin1 AUTO_INCREMENT=55 ;

-- 
-- Volcar la base de datos para la tabla `relation`
-- 

INSERT INTO `relation` (`id_relation`, `id_distribution`, `id_branch`, `id_architecture`, `temporary_sign`) VALUES (1, 1, 'main', 'i386', NULL),
(2, 1, 'contrib', 'i386', NULL),
(3, 1, 'non-free', 'i386', NULL),
(4, 1, 'guada', 'i386', NULL),
(5, 2, 'main', 'i386', NULL),
(6, 2, 'contrib', 'i386', NULL),
(7, 2, 'non-free', 'i386', NULL),
(8, 3, 'main', 'i386', NULL),
(9, 3, 'contrib', 'i386', NULL),
(10, 3, 'non-free', 'i386', NULL),
(11, 4, 'main', 'i386', NULL),
(12, 4, 'contrib', 'i386', NULL),
(13, 4, 'non-free', 'i386', NULL),
(14, 5, 'main', 'i386', NULL),
(15, 5, 'contrib', 'i386', NULL),
(16, 5, 'non-free', 'i386', NULL),
(17, 6, 'guada', 'i386', NULL),
(18, 7, 'main', 'i386', NULL),
(19, 7, 'restricted', 'i386', NULL),
(20, 7, 'universe', 'i386', NULL),
(21, 7, 'multiverse', 'i386', NULL),
(22, 8, 'main', 'i386', NULL),
(23, 9, 'main', 'i386', NULL),
(24, 9, 'restricted', 'i386', NULL),
(25, 9, 'universe', 'i386', NULL),
(26, 9, 'multiverse', 'i386', NULL),
(27, 10, 'main', 'i386', NULL),
(28, 10, 'restricted', 'i386', NULL),
(29, 10, 'universe', 'i386', NULL),
(30, 10, 'multiverse', 'i386', NULL),
(31, 11, 'main', 'i386', NULL),
(32, 11, 'restricted', 'i386', NULL),
(33, 11, 'universe', 'i386', NULL),
(34, 11, 'multiverse', 'i386', NULL),
(35, 12, 'main', 'i386', NULL),
(36, 12, 'restricted', 'i386', NULL),
(37, 12, 'universe', 'i386', NULL),
(38, 12, 'multiverse', 'i386', NULL),
(39, 13, 'main', 'i386', NULL),
(40, 13, 'restricted', 'i386', NULL),
(41, 13, 'universe', 'i386', NULL),
(42, 13, 'multiverse', 'i386', NULL),
(43, 14, 'main', 'i386', NULL),
(44, 14, 'restricted', 'i386', NULL),
(45, 14, 'universe', 'i386', NULL),
(46, 14, 'multiverse', 'i386', NULL),
(47, 15, 'main', 'i386', NULL),
(48, 15, 'restricted', 'i386', NULL),
(49, 15, 'universe', 'i386', NULL),
(50, 15, 'multiverse', 'i386', NULL),
(51, 16, 'main', 'i386', NULL),
(52, 16, 'restricted', 'i386', NULL),
(53, 16, 'universe', 'i386', NULL),
(54, 16, 'multiverse', 'i386', NULL);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `repository`
-- 

CREATE TABLE `repository` (
  `id_repository` int(11) NOT NULL auto_increment,
  `id_edition` int(11) NOT NULL default '0',
  `url` varchar(255) NOT NULL default '',
  `name` varchar(30) default NULL,
  `description` text,
  PRIMARY KEY  (`id_repository`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- 
-- Volcar la base de datos para la tabla `repository`
-- 

INSERT INTO `repository` (`id_repository`, `id_edition`, `url`, `name`, `description`) VALUES (1, 1, 'http://http.guadalinex.org/repositorio/', NULL, NULL),
(2, 2, 'http://http.guadalinex.org/debian/', NULL, NULL),
(3, 2, 'http://http.guadalinex.org/debian-non-US/', NULL, NULL),
(4, 2, 'http://http.guadalinex.org/debian-security/', NULL, NULL),
(5, 2, 'http://http.guadalinex.org/repositorio/', NULL, NULL),
(6, 3, 'http://repositorio.guadalinex.org/ubuntu-breezy/', NULL, NULL),
(7, 3, 'http://repositorio.guadalinex.org/guadalinex-flamenco/', NULL, NULL),
(8, 4, 'http://v4.guadalinex.org/ubuntu/', NULL, NULL),
(9, 4, 'http://v4.guadalinex.org/guadalinex-toro/', NULL, NULL);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `section`
-- 

CREATE TABLE `section` (
  `id_section` varchar(50) NOT NULL default '',
  `name` varchar(50) NOT NULL default '',
  `description` text,
  PRIMARY KEY  (`id_section`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Volcar la base de datos para la tabla `section`
-- 

INSERT INTO `section` (`id_section`, `name`, `description`) VALUES ('admin', 'AdministraciÃ³n', NULL),
('doc', 'DocumentaciÃ³n', NULL),
('gnome', 'GNOME', NULL),
('graphics', 'GrÃ¡ficos', NULL),
('games', 'Juegos', NULL),
('kde', 'KDE', NULL),
('libs', 'LibrerÃ­as', NULL),
('perl', 'Perl', NULL),
('python', 'Python', NULL),
('sound', 'Sonido', NULL),
('utils', 'Utilidades', NULL),
('web', 'Web', NULL);
