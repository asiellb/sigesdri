-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-03-2018 a las 00:10:21
-- Versión del servidor: 10.1.28-MariaDB
-- Versión de PHP: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sigesdri`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area`
--

CREATE TABLE `area` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `country_birth_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `ci` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `firstName` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `secondName` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `firstLastName` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `secondLastName` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fullName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `fullNameSlug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `foreignEmail` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `privatePhone` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cellPhone` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  `clientType` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `clientPicture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `languages` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `organizations` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `mothersName` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fathersName` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `civilState` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `height` decimal(10,2) DEFAULT NULL,
  `eyesColor` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `skinColor` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hairColor` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pvs` longtext COLLATE utf8_unicode_ci,
  `citizenship` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stateBirth` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cityBirth` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `foreignCityBirth` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `district` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `street` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `highway` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `firstBetween` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secongBetween` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `number` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `km` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `building` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `apartment` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cpa` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `farm` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `town` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `area` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postCode` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `studentsYear` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `studentsPosition` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `studentsCareer` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `studentsState` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `studentsLastUpdate` datetime DEFAULT NULL,
  `studentsInactiveAt` datetime DEFAULT NULL,
  `workersOccupation` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workersSpecialty` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workersEduCategory` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workersSciGrade` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workersPosition` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workersWorkPlace` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workersAdmissionDate` date DEFAULT NULL,
  `workersWorkPhone` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workersPay` decimal(10,2) DEFAULT NULL,
  `workersState` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workersLastUpdate` datetime DEFAULT NULL,
  `workersInactiveAt` datetime DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expiredAt` datetime DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `school_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `client`
--

INSERT INTO `client` (`id`, `country_birth_id`, `country_id`, `created_by_id`, `update_user_id`, `ci`, `firstName`, `secondName`, `firstLastName`, `secondLastName`, `fullName`, `fullNameSlug`, `birthday`, `gender`, `email`, `foreignEmail`, `privatePhone`, `cellPhone`, `clientType`, `clientPicture`, `languages`, `organizations`, `mothersName`, `fathersName`, `civilState`, `weight`, `height`, `eyesColor`, `skinColor`, `hairColor`, `pvs`, `citizenship`, `stateBirth`, `cityBirth`, `foreignCityBirth`, `state`, `city`, `district`, `street`, `highway`, `firstBetween`, `secongBetween`, `number`, `km`, `building`, `apartment`, `cpa`, `farm`, `town`, `area`, `postCode`, `studentsYear`, `studentsPosition`, `studentsCareer`, `studentsState`, `studentsLastUpdate`, `studentsInactiveAt`, `workersOccupation`, `workersSpecialty`, `workersEduCategory`, `workersSciGrade`, `workersPosition`, `workersWorkPlace`, `workersAdmissionDate`, `workersWorkPhone`, `workersPay`, `workersState`, `workersLastUpdate`, `workersInactiveAt`, `enabled`, `locked`, `expired`, `expiredAt`, `createdAt`, `updatedAt`, `school_id`) VALUES
(1, 307, 307, 2, 2, '87102522022', 'José', 'Ramón', 'Abadía', 'Lugo', 'José Ramón Abadía Lugo', 'jose-ramon-abadia-lugo', '1987-10-25', 'M', 'jose.abadia@reduc.edu.cu', 'jr.avalug@gmail.com', '32274151', '53777974', 'DOC', '87102522022-jose-ramon-abadia-lugo.png', 'a:2:{i:0;s:2:\"es\";i:1;s:2:\"en\";}', 'a:2:{i:0;s:3:\"cdr\";i:1;s:3:\"ctc\";}', 'Saskia', 'José', 'CAS', '90.00', '180.00', 'Pardos', 'Blanca', 'Negro', NULL, 'cubana', 'Camagüey', 'Nuevitas', NULL, 'Camagüey', 'Camagüey', NULL, 'Cristo', NULL, 'Honda', 'Bembeta', '184', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-18 21:12:05', NULL, 'Profesor en funciones de especialista', 'Ciencia de la Computación', 'INS', 'LIC', NULL, 'DRI', '2014-10-01', '32234217', '547.00', NULL, '2018-01-18 22:38:08', NULL, 1, 0, 0, NULL, '2018-01-09 18:51:50', '2018-02-27 18:43:42', 5),
(2, 307, 307, 2, 2, '87090922095', 'Anisabel', 'Regla', 'Gálvez', 'Fernández', 'Anisabel Regla Gálvez Fernández', 'anisabel-regla-galvez-fernandez', '1987-09-09', 'F', 'anisabel.galvez@reduc.edu.cu', NULL, '32274151', '53652940', 'DOC', '87090922095-anisabel-regla-galvez-fernandez.png', 'a:2:{i:0;s:2:\"es\";i:1;s:2:\"en\";}', 'a:3:{i:0;s:3:\"cdr\";i:1;s:3:\"fmc\";i:2;s:3:\"ujc\";}', 'Ana', 'Mario', 'CAS', '55.00', '170.00', 'Pardos', 'Blanca', 'Castaño', NULL, 'cubana', 'Camagüey', 'Nuevitas', NULL, 'Camagüey', 'Camagüey', NULL, 'Cristo', NULL, 'Honda', 'Bembeta', '184', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesor en funciones demetodóloga', 'Contabilidad', 'ASI', 'MSC', 'Metodóloga de Relaciones Intenacionales', 'DRI', '2010-09-01', '32234217', '750.00', NULL, '2018-01-17 17:01:02', NULL, 1, 0, 0, NULL, '2018-01-17 17:01:02', '2018-02-05 21:59:23', 2),
(11, 307, 307, 2, 2, '12345678978', 'Yaile', NULL, 'Caballero', 'Mota', 'Yaile  Caballero Mota', 'yaile-caballero-mota', '2018-02-16', 'M', 'yaile.caballero@reduc.edu.cu', NULL, '53777974', NULL, 'DOC', NULL, 'a:1:{i:0;s:2:\"es\";}', 'a:1:{i:0;s:3:\"cdr\";}', NULL, NULL, 'SOL', NULL, NULL, 'Pardos', 'Blanca', 'Castaño', NULL, 'cubana', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TIT', 'DRC', NULL, NULL, NULL, NULL, NULL, NULL, '2018-02-09 05:17:24', NULL, 1, 0, 0, NULL, '2018-02-09 05:17:24', '2018-03-14 14:42:40', 5),
(13, 307, 307, 2, 2, '65485275395', 'Yanela', NULL, 'Rodriguez', 'Algo', 'Yanela  Rodriguez Algo', 'yanela-rodriguez-algo', '2018-02-17', 'M', 'yanela.rodriguez@reduc.edu.cu', NULL, '32659847', NULL, 'DOC', '65485275395-yanela-rodriguez-algo.png', 'a:1:{i:0;s:2:\"es\";}', 'a:1:{i:0;s:3:\"cdr\";}', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cubana', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-02-13 08:35:20', NULL, 1, 0, 0, NULL, '2018-02-13 08:35:20', '2018-02-27 01:00:31', NULL),
(14, 307, 307, 2, 2, '82469173645', 'María', 'Elena', 'Escanel', 'Algo', 'María Elena Escanel Algo', 'maria-elena-escanel-algo', '2018-02-16', 'F', 'maria.escanell@reduc.edu.cu', NULL, NULL, '53895623', 'NOD', '82469173645-maria-elena-escanel-algo.png', 'a:1:{i:0;s:2:\"es\";}', 'a:1:{i:0;s:3:\"cdr\";}', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cubana', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-02-15 17:02:42', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, '2018-02-13 18:18:51', '2018-02-15 17:02:43', NULL),
(16, 307, 307, 2, 2, '35789632147', 'asdasdasd', NULL, 'asdfasdfasdf', 'Algo', 'asdasdasd  asdfasdfasdf Algo', 'asdasdasd-asdfasdfasdf-algo', '2018-02-17', 'F', 'asdd@asdasd.asd', NULL, '32256489', NULL, 'EST', '35789632147-asdasdasd-asdfasdfasdf-algo.png', 'a:1:{i:0;s:3:\"ach\";}', 'a:1:{i:0;s:3:\"ujc\";}', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cubana', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-02-14 07:43:52', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, '2018-02-14 07:43:54', '2018-02-26 23:46:47', NULL),
(17, 307, 307, 2, 2, '63103122095', 'Saskia', NULL, 'Lugo', 'Primelles', 'Saskia  Lugo Primelles', 'saskia-lugo-primelles', '2018-02-25', 'F', 'saskia.lugo@nauta.com.cu', NULL, '32414486', NULL, 'NOD', NULL, 'a:1:{i:0;s:2:\"es\";}', 'a:1:{i:0;s:3:\"cdr\";}', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cubana', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-02-14 07:58:17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, '2018-02-14 07:58:19', '2018-02-26 17:15:09', NULL),
(18, 307, 307, 2, NULL, '56235623562', 'Evaristo', NULL, 'Aasdasd', 'Casdaras', 'Evaristo  Aasdasd Casdaras', 'evaristo-aasdasd-casdaras', '2018-02-04', 'M', 'eva@reduc.edu.cu', NULL, '54826428', NULL, 'NOD', '56235623562-evaristo-aasdasd-casdaras.png', 'a:1:{i:0;s:2:\"es\";}', 'a:1:{i:0;s:3:\"pcc\";}', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cubana', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-02-27 01:37:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, '2018-02-27 01:37:47', '2018-02-27 01:37:47', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `country`
--

CREATE TABLE `country` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `spName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `continent` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `area` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subArea` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iso2` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `iso3` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `phoneCode` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flagImage` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastFileUpdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `country`
--

INSERT INTO `country` (`id`, `name`, `spName`, `continent`, `area`, `subArea`, `iso2`, `iso3`, `phoneCode`, `flagImage`, `lastFileUpdate`) VALUES
(254, 'Afghanistan', 'Afganistán', 'Asia', 'Asia del Sur', 'Medio Oriente', 'AF', 'AFG', '93', 'afg.png', '2017-03-17 19:24:37'),
(255, 'Albania', 'Albania', 'Europa', 'Europa Oriental', 'Europa Oriental', 'AL', 'ALB', '355', 'alb.png', '2017-03-17 19:25:27'),
(256, 'Germany', 'Alemania', 'Europa', 'Europa Central', '', 'DE', 'DEU', '49', 'deu.png', '0000-00-00 00:00:00'),
(257, 'Algeria', 'Argelia', 'África', 'África del Norte', '', 'DZ', 'DZA', '213', 'dza.png', '0000-00-00 00:00:00'),
(258, 'Andorra', 'Andorra', 'Europa', 'Europa Occidental', '', 'AD', 'AND', '376', 'and.png', '0000-00-00 00:00:00'),
(259, 'Angola', 'Angola', 'África', 'África del Sur', '', 'AO', 'AGO', '244', 'ago.png', '0000-00-00 00:00:00'),
(260, 'Anguilla', 'Anguila', 'América', 'Caribe', 'Antillas Menores', 'AI', 'AIA', '1264', 'aia.png', '0000-00-00 00:00:00'),
(261, 'Antarctica', 'Antártida', 'Antartida', 'Antartida', '', 'AQ', 'ATA', '672', 'ata.png', '0000-00-00 00:00:00'),
(262, 'Antigua and Barbuda', 'Antigua y Barbuda', 'América', 'Caribe', 'Antillas Menores', 'AG', 'ATG', '1268', 'atg.png', '0000-00-00 00:00:00'),
(263, 'Netherlands Antilles', 'Antillas Neerlandesas', 'América', 'Caribe', 'Antillas Menores', 'AN', 'ANT', '599', 'ant.png', '0000-00-00 00:00:00'),
(264, 'Saudi Arabia', 'Arabia Saudita', 'Asia', 'Asia Central', 'Medio Oriente', 'SA', 'SAU', '966', 'sau.png', '0000-00-00 00:00:00'),
(265, 'Argentina', 'Argentina', 'América', 'Suramérica', 'Cono Sur', 'AR', 'ARG', '54', 'arg.png', '0000-00-00 00:00:00'),
(266, 'Armenia', 'Armenia', 'Europa', 'Caucaso Sur', '', 'AM', 'ARM', '374', 'arm.png', '0000-00-00 00:00:00'),
(267, 'Aruba', 'Aruba', 'América', 'Caribe', 'Antillas Menores', 'AW', 'ABW', '297', 'abw.png', '0000-00-00 00:00:00'),
(268, 'Australia', 'Australia', 'Oceania', 'Oceania', '', 'AU', 'AUS', '61', 'aus.png', '0000-00-00 00:00:00'),
(269, 'Austria', 'Austria', 'Europa', 'Europa Central', '', 'AT', 'AUT', '43', 'aut.png', '0000-00-00 00:00:00'),
(270, 'Azerbaijan', 'Azerbaiyán', 'Europa', 'Caucaso', '', 'AZ', 'AZE', '994', 'aze.png', '0000-00-00 00:00:00'),
(271, 'Belgium', 'Bélgica', 'Europa', 'Europa Occidental', '', 'BE', 'BEL', '32', 'bel.png', '0000-00-00 00:00:00'),
(272, 'Bahamas', 'Bahamas', 'América', 'Caribe', '', 'BS', 'BHS', '1242', 'bhs.png', '0000-00-00 00:00:00'),
(273, 'Bahrain', 'Bahrein', 'Asia', 'Asia Occidental', '', 'BH', 'BHR', '973', 'bhr.png', '0000-00-00 00:00:00'),
(274, 'Bangladesh', 'Bangladesh', 'Asia', 'Asia del Sur', '', 'BD', 'BGD', '880', 'bgd.png', '0000-00-00 00:00:00'),
(275, 'Barbados', 'Barbados', 'América', 'Caribe', '', 'BB', 'BRB', '1246', 'brb.png', '0000-00-00 00:00:00'),
(276, 'Belize', 'Belice', 'América', 'Centroamérica', '', 'BZ', 'BLZ', '501', 'blz.png', '0000-00-00 00:00:00'),
(277, 'Benin', 'Benín', 'África', 'África Occidental', '', 'BJ', 'BEN', '229', 'ben.png', '0000-00-00 00:00:00'),
(278, 'Bhutan', 'Bhután', 'Asia', 'Asia del Sur', '', 'BT', 'BTN', '975', 'btn.png', '0000-00-00 00:00:00'),
(279, 'Belarus', 'Bielorrusia', 'Europa', 'Europa Oriental', '', 'BY', 'BLR', '375', 'blr.png', '0000-00-00 00:00:00'),
(280, 'Myanmar', 'Birmania', 'Asia', 'Sudeste de Asia', '', 'MM', 'MMR', '95', 'mmr.png', '0000-00-00 00:00:00'),
(281, 'Bolivia', 'Bolivia', 'América', 'Suramérica', '', 'BO', 'BOL', '591', 'bol.png', '0000-00-00 00:00:00'),
(282, 'Bosnia and Herzegovina', 'Bosnia y Herzegovina', 'Europa', 'Europa Oriental', '', 'BA', 'BIH', '387', 'bih.png', '0000-00-00 00:00:00'),
(283, 'Botswana', 'Botsuana', 'África', 'África del Sur', '', 'BW', 'BWA', '267', 'bwa.png', '0000-00-00 00:00:00'),
(284, 'Brazil', 'Brasil', 'América', 'Suramérica', '', 'BR', 'BRA', '55', 'bra.png', '0000-00-00 00:00:00'),
(285, 'Brunei', 'Brunéi', 'Asia', 'Sudeste de Asia', '', 'BN', 'BRN', '673', 'brn.png', '0000-00-00 00:00:00'),
(286, 'Bulgaria', 'Bulgaria', 'Europa', 'Europa Oriental', '', 'BG', 'BGR', '359', 'bgr.png', '0000-00-00 00:00:00'),
(287, 'Burkina Faso', 'Burkina Faso', 'África', 'África Occidental', '', 'BF', 'BFA', '226', 'bfa.png', '0000-00-00 00:00:00'),
(288, 'Burundi', 'Burundi', 'África', 'África Oriental', '', 'BI', 'BDI', '257', 'bdi.png', '0000-00-00 00:00:00'),
(289, 'Cape Verde', 'Cabo Verde', 'África', 'África Occidental', '', 'CV', 'CPV', '238', 'cpv.png', '0000-00-00 00:00:00'),
(290, 'Cambodia', 'Camboya', 'Asia', 'Sudeste de Asia', '', 'KH', 'KHM', '855', 'khm.png', '0000-00-00 00:00:00'),
(291, 'Cameroon', 'Camerún', 'África', 'África Central', '', 'CM', 'CMR', '237', 'cmr.png', '0000-00-00 00:00:00'),
(292, 'Canada', 'Canadá', 'América', 'Norteamérica', '', 'CA', 'CAN', '1', 'can.png', '0000-00-00 00:00:00'),
(293, 'Chad', 'Chad', 'África', 'África del Norte', '', 'TD', 'TCD', '235', 'tcd.png', '0000-00-00 00:00:00'),
(294, 'Chile', 'Chile', 'América', 'Suramérica', '', 'CL', 'CHL', '56', 'chl.png', '0000-00-00 00:00:00'),
(295, 'China', 'China', 'Asia', 'Asia del Este', '', 'CN', 'CHN', '86', 'chn.png', '0000-00-00 00:00:00'),
(296, 'Cyprus', 'Chipre', 'Asia', 'Asia Occidental', '', 'CY', 'CYP', '357', 'cyp.png', '0000-00-00 00:00:00'),
(297, 'Vatican City State', 'Ciudad del Vaticano', 'Europa', 'Europa Occidental', '', 'VA', 'VAT', '39', 'vat.png', '0000-00-00 00:00:00'),
(298, 'Colombia', 'Colombia', 'América', 'Suramérica', '', 'CO', 'COL', '57', 'col.png', '0000-00-00 00:00:00'),
(299, 'Comoros', 'Comoras', 'África', 'África Oriental', '', 'KM', 'COM', '269', 'com.png', '0000-00-00 00:00:00'),
(300, 'Congo', 'Congo', 'África', 'África Central', '', 'CG', 'COG', '242', 'cog.png', '0000-00-00 00:00:00'),
(301, 'Democratic Republic of Congo', 'República Democrática del Congo', 'África', 'África Central', '', 'CD', 'COD', '243', 'cod.png', '0000-00-00 00:00:00'),
(302, 'North Korea', 'Corea del Norte', 'Asia', 'Asia del Este', '', 'KP', 'PRK', '850', 'prk.png', '0000-00-00 00:00:00'),
(303, 'South Korea', 'Corea del Sur', 'Asia', 'Asia del Este', '', 'KR', 'KOR', '82', 'kor.png', '0000-00-00 00:00:00'),
(304, 'Ivory Coast', 'Costa de Marfil', 'África', 'África Occidental', '', 'CI', 'CIV', '225', 'civ.png', '0000-00-00 00:00:00'),
(305, 'Costa Rica', 'Costa Rica', 'América', 'Centroamérica', '', 'CR', 'CRI', '506', 'cri.png', '0000-00-00 00:00:00'),
(306, 'Croatia', 'Croacia', 'Europa', 'Europa del Sur', '', 'HR', 'HRV', '385', 'hrv.png', '0000-00-00 00:00:00'),
(307, 'Cuba', 'Cuba', 'América', 'Caribe', 'Antillas Mayores', 'CU', 'CUB', '53', 'cub.png', '0000-00-00 00:00:00'),
(308, 'Denmark', 'Dinamarca', 'Europa', 'Europa Occidental', '', 'DK', 'DNK', '45', 'dnk.png', '0000-00-00 00:00:00'),
(309, 'Dominica', 'Dominica', 'América', 'Caribe', '', 'DM', 'DMA', '1767', 'dma.png', '0000-00-00 00:00:00'),
(310, 'Ecuador', 'Ecuador', 'América', 'Suramérica', '', 'EC', 'ECU', '593', 'ecu.png', '0000-00-00 00:00:00'),
(311, 'Egypt', 'Egipto', 'África', 'África del Norte', '', 'EG', 'EGY', '20', 'egy.png', '0000-00-00 00:00:00'),
(312, 'El Salvador', 'El Salvador', 'América', 'Centroamérica', '', 'SV', 'SLV', '503', 'slv.png', '0000-00-00 00:00:00'),
(313, 'United Arab Emirates', 'Emiratos Árabes Unidos', 'Asia', 'Asia Occidental', '', 'AE', 'ARE', '971', 'are.png', '0000-00-00 00:00:00'),
(314, 'Eritrea', 'Eritrea', 'África', 'África Oriental', '', 'ER', 'ERI', '291', 'eri.png', '0000-00-00 00:00:00'),
(315, 'Slovakia', 'Eslovaquia', 'Europa', 'Europa Central', '', 'SK', 'SVK', '421', 'svk.png', '0000-00-00 00:00:00'),
(316, 'Slovenia', 'Eslovenia', 'Europa', 'Europa Central', '', 'SI', 'SVN', '386', 'svn.png', '0000-00-00 00:00:00'),
(317, 'Spain', 'España', 'Europa', 'Europa Occidental', '', 'ES', 'ESP', '34', 'esp.png', '0000-00-00 00:00:00'),
(318, 'United States of America', 'Estados Unidos de América', 'América', 'Norteamérica', '', 'US', 'USA', '1', 'usa.png', '0000-00-00 00:00:00'),
(319, 'Estonia', 'Estonia', 'Europa', 'Europa Oriental', '', 'EE', 'EST', '372', 'est.png', '0000-00-00 00:00:00'),
(320, 'Ethiopia', 'Etiopía', 'África', 'África Oriental', '', 'ET', 'ETH', '251', 'eth.png', '0000-00-00 00:00:00'),
(321, 'Philippines', 'Filipinas', 'Asia', 'Sudeste de Asia', '', 'PH', 'PHL', '63', 'phl.png', '0000-00-00 00:00:00'),
(322, 'Finland', 'Finlandia', 'Europa', 'Europa Occidental', '', 'FI', 'FIN', '358', 'fin.png', '0000-00-00 00:00:00'),
(323, 'Fiji', 'Fiyi', 'Oceania', 'Oceania', '', 'FJ', 'FJI', '679', 'fji.png', '0000-00-00 00:00:00'),
(324, 'France', 'Francia', 'Europa', 'Europa Occidental', '', 'FR', 'FRA', '33', 'fra.png', '0000-00-00 00:00:00'),
(325, 'Gabon', 'Gabón', 'África', 'África Central', '', 'GA', 'GAB', '241', 'gab.png', '0000-00-00 00:00:00'),
(326, 'Gambia', 'Gambia', 'África', 'África Occidental', '', 'GM', 'GMB', '220', 'gmb.png', '0000-00-00 00:00:00'),
(327, 'Georgia', 'Georgia', 'Europa', 'Europa Oriental', '', 'GE', 'GEO', '995', 'geo.png', '0000-00-00 00:00:00'),
(328, 'Ghana', 'Ghana', 'África', 'África Occidental', '', 'GH', 'GHA', '233', 'gha.png', '0000-00-00 00:00:00'),
(329, 'Gibraltar', 'Gibraltar', 'Europa', 'Europa Occidental', '', 'GI', 'GIB', '350', 'gib.png', '0000-00-00 00:00:00'),
(330, 'Grenada', 'Granada', 'América', 'Caribe', '', 'GD', 'GRD', '1473', 'grd.png', '0000-00-00 00:00:00'),
(331, 'Greece', 'Grecia', 'Europa', 'Europa Oriental', '', 'GR', 'GRC', '30', 'grc.png', '0000-00-00 00:00:00'),
(332, 'Greenland', 'Groenlandia', 'América', 'Norteamérica', '', 'GL', 'GRL', '299', 'grl.png', '0000-00-00 00:00:00'),
(333, 'Guadeloupe', 'Guadalupe', 'América', 'Caribe', '', 'GP', 'GLP', '', 'glp.png', '0000-00-00 00:00:00'),
(334, 'Guam', 'Guam', 'Oceania', 'Oceania', '', 'GU', 'GUM', '1671', 'gum.png', '0000-00-00 00:00:00'),
(335, 'Guatemala', 'Guatemala', 'América', 'Centroamérica', '', 'GT', 'GTM', '502', 'gtm.png', '0000-00-00 00:00:00'),
(336, 'French Guiana', 'Guayana Francesa', 'América', 'Suramérica', '', 'GF', 'GUF', '', 'guf.png', '0000-00-00 00:00:00'),
(337, 'Guernsey', 'Guernsey', 'Europa', 'Europa Central', '', 'GG', 'GGY', '', 'ggy.png', '0000-00-00 00:00:00'),
(338, 'Guinea', 'Guinea', 'África', 'África Occidental', '', 'GN', 'GIN', '224', 'gin.png', '0000-00-00 00:00:00'),
(339, 'Equatorial Guinea', 'Guinea Ecuatorial', 'África', 'África Central', '', 'GQ', 'GNQ', '240', 'gnq.png', '0000-00-00 00:00:00'),
(340, 'Guinea-Bissau', 'Guinea-Bissau', 'África', 'África Occidental', '', 'GW', 'GNB', '245', 'gnb.png', '0000-00-00 00:00:00'),
(341, 'Guyana', 'Guyana', 'América', 'Suramérica', '', 'GY', 'GUY', '592', 'guy.png', '0000-00-00 00:00:00'),
(342, 'Haiti', 'Haití', 'América', 'Caribe', '', 'HT', 'HTI', '509', 'hti.png', '0000-00-00 00:00:00'),
(343, 'Honduras', 'Honduras', 'América', 'Centroamérica', '', 'HN', 'HND', '504', 'hnd.png', '0000-00-00 00:00:00'),
(344, 'Hong Kong', 'Hong kong', 'Asia', 'Asia del Sur', '', 'HK', 'HKG', '852', 'hkg.png', '0000-00-00 00:00:00'),
(345, 'Hungary', 'Hungría', 'Europa', 'Europa Central', '', 'HU', 'HUN', '36', 'hun.png', '0000-00-00 00:00:00'),
(346, 'India', 'India', 'Asia', 'Asia del Sur', '', 'IN', 'IND', '91', 'ind.png', '0000-00-00 00:00:00'),
(347, 'Indonesia', 'Indonesia', 'Asia', 'Sudeste de Asia', '', 'ID', 'IDN', '62', 'idn.png', '0000-00-00 00:00:00'),
(348, 'Iran', 'Irán', 'Asia', 'Asia del Sur', '', 'IR', 'IRN', '98', 'irn.png', '0000-00-00 00:00:00'),
(349, 'Iraq', 'Irak', 'Asia', 'Asia Occidental', '', 'IQ', 'IRQ', '964', 'irq.png', '0000-00-00 00:00:00'),
(350, 'Ireland', 'Irlanda', 'Europa', 'Europa Occidental', '', 'IE', 'IRL', '353', 'irl.png', '0000-00-00 00:00:00'),
(351, 'Bouvet Island', 'Isla Bouvet', 'Europa', 'Océano Atlantico', '', 'BV', 'BVT', '', 'bvt.png', '0000-00-00 00:00:00'),
(352, 'Isle of Man', 'Isla de Man', 'Europa', 'Europa Occidental', '', 'IM', 'IMN', '44', 'imn.png', '0000-00-00 00:00:00'),
(353, 'Christmas Island', 'Isla de Navidad', 'Oceania', 'Oceania', '', 'CX', 'CXR', '61', 'cxr.png', '0000-00-00 00:00:00'),
(354, 'Norfolk Island', 'Isla Norfolk', 'Oceania', 'Oceania', '', 'NF', 'NFK', '', 'nfk.png', '0000-00-00 00:00:00'),
(355, 'Iceland', 'Islandia', 'Europa', 'Europa Occidental', '', 'IS', 'ISL', '354', 'isl.png', '0000-00-00 00:00:00'),
(356, 'Bermuda Islands', 'Islas Bermudas', 'América', 'Norteamérica', '', 'BM', 'BMU', '1441', 'bmu.png', '0000-00-00 00:00:00'),
(357, 'Cayman Islands', 'Islas Caimán', 'América', 'Caribe', '', 'KY', 'CYM', '1345', 'cym.png', '0000-00-00 00:00:00'),
(358, 'Cocos (Keeling) Islands', 'Islas Cocos (Keeling)', 'Oceania', 'Oceania', '', 'CC', 'CCK', '61', 'cck.png', '0000-00-00 00:00:00'),
(359, 'Cook Islands', 'Islas Cook', 'Oceania', 'Oceania', '', 'CK', 'COK', '682', 'cok.png', '0000-00-00 00:00:00'),
(360, 'Åland Islands', 'Islas de Åland', 'Europa', 'Europa Occidental', '', 'AX', 'ALA', '', 'ala.png', '0000-00-00 00:00:00'),
(361, 'Faroe Islands', 'Islas Feroe', 'Europa', 'Europa Occidental', '', 'FO', 'FRO', '298', 'fro.png', '0000-00-00 00:00:00'),
(362, 'South Georgia and the South Sandwich Islands', 'Islas Georgias del Sur y Sandwich del Sur', 'América', 'Suramérica', '', 'GS', 'SGS', '', 'sgs.png', '0000-00-00 00:00:00'),
(363, 'Heard Island and McDonald Islands', 'Islas Heard y McDonald', 'Oceania', 'Oceania', '', 'HM', 'HMD', '', 'hmd.png', '0000-00-00 00:00:00'),
(364, 'Maldives', 'Islas Maldivas', 'Asia', 'Asia del Sur', '', 'MV', 'MDV', '960', 'mdv.png', '0000-00-00 00:00:00'),
(365, 'Falkland Islands (Malvinas)', 'Islas Malvinas', 'América', 'Suramérica', '', 'FK', 'FLK', '500', 'flk.png', '0000-00-00 00:00:00'),
(366, 'Northern Mariana Islands', 'Islas Marianas del Norte', 'Oceania', 'Oceania', '', 'MP', 'MNP', '1670', 'mnp.png', '0000-00-00 00:00:00'),
(367, 'Marshall Islands', 'Islas Marshall', 'Oceania', 'Oceania', '', 'MH', 'MHL', '692', 'mhl.png', '0000-00-00 00:00:00'),
(368, 'Pitcairn Islands', 'Islas Pitcairn', 'Oceania', 'Oceania', '', 'PN', 'PCN', '870', 'pcn.png', '0000-00-00 00:00:00'),
(369, 'Solomon Islands', 'Islas Salomón', 'Oceania', 'Oceania', '', 'SB', 'SLB', '677', 'slb.png', '0000-00-00 00:00:00'),
(370, 'Turks and Caicos Islands', 'Islas Turcas y Caicos', 'América', 'Caribe', '', 'TC', 'TCA', '1649', 'tca.png', '0000-00-00 00:00:00'),
(371, 'United States Minor Outlying Islands', 'Islas Ultramarinas Menores de Estados Unidos', 'América', 'Norteamérica', '', 'UM', 'UMI', '', 'umi.png', '0000-00-00 00:00:00'),
(372, 'Virgin Islands', 'Islas Vírgenes Británicas', 'América', 'Caribe', '', 'VG', 'VGB', '1284', 'vgb.png', '0000-00-00 00:00:00'),
(373, 'United States Virgin Islands', 'Islas Vírgenes de los Estados Unidos', 'América', 'Centroamérica', '', 'VI', 'VIR', '1340', 'vir.png', '0000-00-00 00:00:00'),
(374, 'Israel', 'Israel', 'Asia', 'Asia Occidental', '', 'IL', 'ISR', '972', 'isr.png', '0000-00-00 00:00:00'),
(375, 'Italy', 'Italia', 'Europa', 'Europa Occidental', '', 'IT', 'ITA', '39', 'ita.png', '0000-00-00 00:00:00'),
(376, 'Jamaica', 'Jamaica', 'América', 'Caribe', '', 'JM', 'JAM', '1876', 'jam.png', '0000-00-00 00:00:00'),
(377, 'Japan', 'Japón', 'Asia', 'Asia del Este', '', 'JP', 'JPN', '81', 'jpn.png', '0000-00-00 00:00:00'),
(378, 'Jersey', 'Jersey', 'Europa', 'Europa Occidental', '', 'JE', 'JEY', '', 'jey.png', '0000-00-00 00:00:00'),
(379, 'Jordan', 'Jordania', 'Asia', 'Asia Occidental', '', 'JO', 'JOR', '962', 'jor.png', '0000-00-00 00:00:00'),
(380, 'Kazakhstan', 'Kazajistán', 'Asia', 'Asia Central', '', 'KZ', 'KAZ', '7', 'kaz.png', '0000-00-00 00:00:00'),
(381, 'Kenya', 'Kenia', 'África', 'África Oriental', '', 'KE', 'KEN', '254', 'ken.png', '0000-00-00 00:00:00'),
(382, 'Kyrgyzstan', 'Kirgizstán', 'Asia', 'Asia Central', '', 'KG', 'KGZ', '996', 'kgz.png', '0000-00-00 00:00:00'),
(383, 'Kiribati', 'Kiribati', 'Oceania', 'Oceania', '', 'KI', 'KIR', '686', 'kir.png', '0000-00-00 00:00:00'),
(384, 'Kuwait', 'Kuwait', 'Asia', 'Asia Occidental', '', 'KW', 'KWT', '965', 'kwt.png', '0000-00-00 00:00:00'),
(385, 'Lebanon', 'Líbano', 'Asia', 'Asia Occidental', '', 'LB', 'LBN', '961', 'lbn.png', '0000-00-00 00:00:00'),
(386, 'Laos', 'Laos', 'Asia', 'Sudeste de Asia', '', 'LA', 'LAO', '856', 'lao.png', '0000-00-00 00:00:00'),
(387, 'Lesotho', 'Lesoto', 'África', 'África del Sur', '', 'LS', 'LSO', '266', 'lso.png', '0000-00-00 00:00:00'),
(388, 'Latvia', 'Letonia', 'Europa', 'Europa Oriental', '', 'LV', 'LVA', '371', 'lva.png', '0000-00-00 00:00:00'),
(389, 'Liberia', 'Liberia', 'África', 'África Occidental', '', 'LR', 'LBR', '231', 'lbr.png', '0000-00-00 00:00:00'),
(390, 'Libya', 'Libia', 'África', 'África del Norte', '', 'LY', 'LBY', '218', 'lby.png', '0000-00-00 00:00:00'),
(391, 'Liechtenstein', 'Liechtenstein', 'Europa', 'Europa Central', '', 'LI', 'LIE', '423', 'lie.png', '0000-00-00 00:00:00'),
(392, 'Lithuania', 'Lituania', 'Europa', 'Europa Oriental', '', 'LT', 'LTU', '370', 'ltu.png', '0000-00-00 00:00:00'),
(393, 'Luxembourg', 'Luxemburgo', 'Europa', 'Europa Occidental', '', 'LU', 'LUX', '352', 'lux.png', '0000-00-00 00:00:00'),
(394, 'Mexico', 'México', 'América', 'Norteamérica', '', 'MX', 'MEX', '52', 'mex.png', '0000-00-00 00:00:00'),
(395, 'Monaco', 'Mónaco', 'Europa', 'Europa Occidental', '', 'MC', 'MCO', '377', 'mco.png', '0000-00-00 00:00:00'),
(396, 'Macao', 'Macao', 'Asia', 'Asia del Este', '', 'MO', 'MAC', '853', 'mac.png', '0000-00-00 00:00:00'),
(397, 'Macedonia', 'Macedónia', 'Europa', 'Europa Oriental', '', 'MK', 'MKD', '389', 'mkd.png', '0000-00-00 00:00:00'),
(398, 'Madagascar', 'Madagascar', 'África', 'África Oriental', '', 'MG', 'MDG', '261', 'mdg.png', '0000-00-00 00:00:00'),
(399, 'Malaysia', 'Malasia', 'Asia', 'Sudeste de Asia', '', 'MY', 'MYS', '60', 'mys.png', '0000-00-00 00:00:00'),
(400, 'Malawi', 'Malawi', 'África', 'África Oriental', '', 'MW', 'MWI', '265', 'mwi.png', '0000-00-00 00:00:00'),
(401, 'Mali', 'Mali', 'África', 'África del Norte', '', 'ML', 'MLI', '223', 'mli.png', '0000-00-00 00:00:00'),
(402, 'Malta', 'Malta', 'Europa', 'Europa Occidental', '', 'MT', 'MLT', '356', 'mlt.png', '0000-00-00 00:00:00'),
(403, 'Morocco', 'Marruecos', 'África', 'África del Norte', '', 'MA', 'MAR', '212', 'mar.png', '0000-00-00 00:00:00'),
(404, 'Martinique', 'Martinica', 'América', 'Caribe', '', 'MQ', 'MTQ', '', 'mtq.png', '0000-00-00 00:00:00'),
(405, 'Mauritius', 'Mauricio', 'África', 'África Oriental', '', 'MU', 'MUS', '230', 'mus.png', '0000-00-00 00:00:00'),
(406, 'Mauritania', 'Mauritania', 'África', 'África del Norte', '', 'MR', 'MRT', '222', 'mrt.png', '0000-00-00 00:00:00'),
(407, 'Mayotte', 'Mayotte', 'África', 'África Oriental', '', 'YT', 'MYT', '262', 'myt.png', '0000-00-00 00:00:00'),
(408, 'Estados Federados de Micronesia', 'Micronesia', 'Oceania', 'Micronesia', '', 'FM', 'FSM', '691', 'fsm.png', '0000-00-00 00:00:00'),
(409, 'Moldova', 'Moldavia', 'Europa', 'Europa Oriental', '', 'MD', 'MDA', '373', 'mda.png', '0000-00-00 00:00:00'),
(410, 'Mongolia', 'Mongolia', 'Asia', 'Asia del Este', '', 'MN', 'MNG', '976', 'mng.png', '0000-00-00 00:00:00'),
(411, 'Montenegro', 'Montenegro', 'Europa', 'Europa Oriental', '', 'ME', 'MNE', '382', 'mne.png', '0000-00-00 00:00:00'),
(412, 'Montserrat', 'Montserrat', 'América', 'Caribe', '', 'MS', 'MSR', '1664', 'msr.png', '0000-00-00 00:00:00'),
(413, 'Mozambique', 'Mozambique', 'África', 'África Oriental', '', 'MZ', 'MOZ', '258', 'moz.png', '0000-00-00 00:00:00'),
(414, 'Namibia', 'Namibia', 'África', 'África del Sur', '', 'NA', 'NAM', '264', 'nam.png', '0000-00-00 00:00:00'),
(415, 'Nauru', 'Nauru', 'Oceania', 'Oceania', '', 'NR', 'NRU', '674', 'nru.png', '0000-00-00 00:00:00'),
(416, 'Nepal', 'Nepal', 'Asia', 'Asia del Sur', '', 'NP', 'NPL', '977', 'npl.png', '0000-00-00 00:00:00'),
(417, 'Nicaragua', 'Nicaragua', 'América', 'Centroamérica', '', 'NI', 'NIC', '505', 'nic.png', '0000-00-00 00:00:00'),
(418, 'Niger', 'Niger', 'África', 'África del Norte', '', 'NE', 'NER', '227', 'ner.png', '0000-00-00 00:00:00'),
(419, 'Nigeria', 'Nigeria', 'África', 'África Occidental', '', 'NG', 'NGA', '234', 'nga.png', '0000-00-00 00:00:00'),
(420, 'Niue', 'Niue', 'Oceania', 'Oceania', '', 'NU', 'NIU', '683', 'niu.png', '0000-00-00 00:00:00'),
(421, 'Norway', 'Noruega', 'Europa', 'Europa Occidental', '', 'NO', 'NOR', '47', 'nor.png', '0000-00-00 00:00:00'),
(422, 'New Caledonia', 'Nueva Caledonia', 'Oceania', 'Oceania', '', 'NC', 'NCL', '687', 'ncl.png', '0000-00-00 00:00:00'),
(423, 'New Zealand', 'Nueva Zelanda', 'Oceania', 'Oceania', '', 'NZ', 'NZL', '64', 'nzl.png', '0000-00-00 00:00:00'),
(424, 'Oman', 'Omán', 'Asia', 'Asia Occidental', '', 'OM', 'OMN', '968', 'omn.png', '0000-00-00 00:00:00'),
(425, 'Netherlands', 'Países Bajos', 'Europa', 'Europa Occidental', '', 'NL', 'NLD', '31', 'nld.png', '0000-00-00 00:00:00'),
(426, 'Pakistan', 'Pakistán', 'Asia', 'Asia del Sur', '', 'PK', 'PAK', '92', 'pak.png', '0000-00-00 00:00:00'),
(427, 'Palau', 'Palau', 'Oceania', 'Oceania', '', 'PW', 'PLW', '680', 'plw.png', '0000-00-00 00:00:00'),
(428, 'Palestine', 'Palestina', 'Asia', 'Asia Occidental', '', 'PS', 'PSE', '', 'pse.png', '0000-00-00 00:00:00'),
(429, 'Panama', 'Panamá', 'América', 'Centroamérica', '', 'PA', 'PAN', '507', 'pan.png', '0000-00-00 00:00:00'),
(430, 'Papua New Guinea', 'Papúa Nueva Guinea', 'Oceania', 'Oceania', '', 'PG', 'PNG', '675', 'png.png', '0000-00-00 00:00:00'),
(431, 'Paraguay', 'Paraguay', 'América', 'Suramérica', '', 'PY', 'PRY', '595', 'pry.png', '0000-00-00 00:00:00'),
(432, 'Peru', 'Perú', 'América', 'Suramérica', '', 'PE', 'PER', '51', 'per.png', '0000-00-00 00:00:00'),
(433, 'French Polynesia', 'Polinesia Francesa', 'Oceania', 'Oceania', '', 'PF', 'PYF', '689', 'pyf.png', '0000-00-00 00:00:00'),
(434, 'Poland', 'Polonia', 'Europa', 'Europa Central', '', 'PL', 'POL', '48', 'pol.png', '0000-00-00 00:00:00'),
(435, 'Portugal', 'Portugal', 'Europa', 'Europa Occidental', '', 'PT', 'PRT', '351', 'prt.png', '0000-00-00 00:00:00'),
(436, 'Puerto Rico', 'Puerto Rico', 'América', 'Caribe', '', 'PR', 'PRI', '1', 'pri.png', '0000-00-00 00:00:00'),
(437, 'Qatar', 'Qatar', 'Asia', 'Asia Occidental', '', 'QA', 'QAT', '974', 'qat.png', '0000-00-00 00:00:00'),
(438, 'United Kingdom', 'Reino Unido', 'Europa', 'Europa Occidental', '', 'GB', 'GBR', '44', 'gbr.png', '0000-00-00 00:00:00'),
(439, 'Central African Republic', 'República Centroafricana', 'África', 'África Central', '', 'CF', 'CAF', '236', 'caf.png', '0000-00-00 00:00:00'),
(440, 'Czech Republic', 'República Checa', 'Europa', 'Europa Central', '', 'CZ', 'CZE', '420', 'cze.png', '0000-00-00 00:00:00'),
(441, 'Dominican Republic', 'República Dominicana', 'América', 'Caribe', '', 'DO', 'DOM', '1809', 'dom.png', '0000-00-00 00:00:00'),
(442, 'Réunion', 'Reunión', 'África', 'África Oriental', '', 'RE', 'REU', '', 'reu.png', '0000-00-00 00:00:00'),
(443, 'Rwanda', 'Ruanda', 'África', 'África Oriental', '', 'RW', 'RWA', '250', 'rwa.png', '0000-00-00 00:00:00'),
(444, 'Romania', 'Rumanía', 'Europa', 'Europa Oriental', '', 'RO', 'ROU', '40', 'rou.png', '0000-00-00 00:00:00'),
(445, 'Russia', 'Rusia', 'Asia', 'Asia del Norte', '', 'RU', 'RUS', '7', 'rus.png', '0000-00-00 00:00:00'),
(446, 'Western Sahara', 'Sahara Occidental', 'África', 'África del Norte', '', 'EH', 'ESH', '', 'esh.png', '0000-00-00 00:00:00'),
(447, 'Samoa', 'Samoa', 'Oceania', 'Oceania', '', 'WS', 'WSM', '685', 'wsm.png', '0000-00-00 00:00:00'),
(448, 'American Samoa', 'Samoa Americana', 'Oceania', 'Oceania', '', 'AS', 'ASM', '1684', 'asm.png', '0000-00-00 00:00:00'),
(449, 'Saint Barthélemy', 'San Bartolomé', 'América', 'Caribe', '', 'BL', 'BLM', '590', 'blm.png', '0000-00-00 00:00:00'),
(450, 'Saint Kitts and Nevis', 'San Cristóbal y Nieves', 'América', 'Caribe', '', 'KN', 'KNA', '1869', 'kna.png', '0000-00-00 00:00:00'),
(451, 'San Marino', 'San Marino', 'Europa', 'Europa Occidental', '', 'SM', 'SMR', '378', 'smr.png', '0000-00-00 00:00:00'),
(452, 'Saint Martin (French part)', 'San Martín (Francia)', 'América', 'Caribe', '', 'MF', 'MAF', '1599', 'maf.png', '0000-00-00 00:00:00'),
(453, 'Saint Vincent and the Grenadines', 'San Vicente y las Granadinas', 'América', 'Caribe', '', 'VC', 'VCT', '1784', 'vct.png', '0000-00-00 00:00:00'),
(454, 'AscensiÃ³n y TristÃ¡n de AcuÃ±a', 'Santa Elena', 'África', 'África del Sur', '', 'SH', 'SHN', '290', 'shn.png', '0000-00-00 00:00:00'),
(455, 'Saint Lucia', 'Santa Lucía', 'América', 'Caribe', '', 'LC', 'LCA', '1758', 'lca.png', '0000-00-00 00:00:00'),
(456, 'Sao Tome and Principe', 'Santo Tomá y Príncipe', 'África', 'África Central', '', 'ST', 'STP', '239', 'stp.png', '0000-00-00 00:00:00'),
(457, 'Senegal', 'Senegal', 'África', 'África Occidental', '', 'SN', 'SEN', '221', 'sen.png', '0000-00-00 00:00:00'),
(458, 'Serbia', 'Serbia', 'Europa', 'Europa Oriental', '', 'RS', 'SRB', '381', 'srb.png', '0000-00-00 00:00:00'),
(459, 'Seychelles', 'Seychelles', 'África', 'África Oriental', '', 'SC', 'SYC', '248', 'syc.png', '0000-00-00 00:00:00'),
(460, 'Sierra Leone', 'Sierra Leona', 'África', 'África Occidental', '', 'SL', 'SLE', '232', 'sle.png', '0000-00-00 00:00:00'),
(461, 'Singapore', 'Singapur', 'Asia', 'Sudeste de Asia', '', 'SG', 'SGP', '65', 'sgp.png', '0000-00-00 00:00:00'),
(462, 'Syria', 'Siria', 'Asia', 'Asia Occidental', '', 'SY', 'SYR', '963', 'syr.png', '0000-00-00 00:00:00'),
(463, 'Somalia', 'Somalia', 'África', 'África Oriental', '', 'SO', 'SOM', '252', 'som.png', '0000-00-00 00:00:00'),
(464, 'Sri Lanka', 'Sri lanka', 'Asia', 'Asia del Sur', '', 'LK', 'LKA', '94', 'lka.png', '0000-00-00 00:00:00'),
(465, 'South Africa', 'Sudáfrica', 'África', 'África del Sur', '', 'ZA', 'ZAF', '27', 'zaf.png', '0000-00-00 00:00:00'),
(466, 'Sudan', 'Sudán', 'África', 'África Oriental', '', 'SD', 'SDN', '249', 'sdn.png', '0000-00-00 00:00:00'),
(467, 'Sweden', 'Suecia', 'Europa', 'Europa Occidental', '', 'SE', 'SWE', '46', 'swe.png', '0000-00-00 00:00:00'),
(468, 'Switzerland', 'Suiza', 'Europa', 'Europa Central', '', 'CH', 'CHE', '41', 'che.png', '0000-00-00 00:00:00'),
(469, 'Suriname', 'Surinám', 'América', 'Suramérica', '', 'SR', 'SUR', '597', 'sur.png', '0000-00-00 00:00:00'),
(470, 'Svalbard and Jan Mayen', 'Svalbard y Jan Mayen', 'Europa', 'Europa Occidental', '', 'SJ', 'SJM', '', 'sjm.png', '0000-00-00 00:00:00'),
(471, 'Swaziland', 'Swazilandia', 'África', 'África del Sur', '', 'SZ', 'SWZ', '268', 'swz.png', '0000-00-00 00:00:00'),
(472, 'Tajikistan', 'Tadjikistán', 'Asia', 'Asia Central', '', 'TJ', 'TJK', '992', 'tjk.png', '0000-00-00 00:00:00'),
(473, 'Thailand', 'Tailandia', 'Asia', 'Sudeste de Asia', '', 'TH', 'THA', '66', 'tha.png', '0000-00-00 00:00:00'),
(474, 'Taiwan', 'Taiwán', 'Asia', 'Asia del Este', '', 'TW', 'TWN', '886', 'twn.png', '0000-00-00 00:00:00'),
(475, 'Tanzania', 'Tanzania', 'África', 'África Oriental', '', 'TZ', 'TZA', '255', 'tza.png', '0000-00-00 00:00:00'),
(476, 'British Indian Ocean Territory', 'Territorio Británico del Océano Índico', 'Asia', 'Asia del Sur', '', 'IO', 'IOT', '', 'iot.png', '0000-00-00 00:00:00'),
(477, 'French Southern Territories', 'Territorios Australes y Antárticas Franceses', 'Antartida', 'Antartida', '', 'TF', 'ATF', '', 'atf.png', '0000-00-00 00:00:00'),
(478, 'East Timor', 'Timor Oriental', 'Asia', 'Sudeste de Asia', '', 'TL', 'TLS', '670', 'tls.png', '0000-00-00 00:00:00'),
(479, 'Togo', 'Togo', 'África', 'África Occidental', '', 'TG', 'TGO', '228', 'tgo.png', '0000-00-00 00:00:00'),
(480, 'Tokelau', 'Tokelau', 'Oceania', 'Oceania', '', 'TK', 'TKL', '690', 'tkl.png', '0000-00-00 00:00:00'),
(481, 'Tonga', 'Tonga', 'Oceania', 'Oceania', '', 'TO', 'TON', '676', 'ton.png', '0000-00-00 00:00:00'),
(482, 'Trinidad and Tobago', 'Trinidad y Tobago', 'América', 'Suramérica', '', 'TT', 'TTO', '1868', 'tto.png', '0000-00-00 00:00:00'),
(483, 'Tunisia', 'Tunez', 'África', 'África del Norte', '', 'TN', 'TUN', '216', 'tun.png', '0000-00-00 00:00:00'),
(484, 'Turkmenistan', 'Turkmenistán', 'Asia', 'Asia Central', '', 'TM', 'TKM', '993', 'tkm.png', '0000-00-00 00:00:00'),
(485, 'Turkey', 'Turquía', 'Asia', 'Asia Occidental', '', 'TR', 'TUR', '90', 'tur.png', '0000-00-00 00:00:00'),
(486, 'Tuvalu', 'Tuvalu', 'Oceania', 'Oceania', '', 'TV', 'TUV', '688', 'tuv.png', '0000-00-00 00:00:00'),
(487, 'Ukraine', 'Ucrania', 'Europa', 'Europa Oriental', '', 'UA', 'UKR', '380', 'ukr.png', '0000-00-00 00:00:00'),
(488, 'Uganda', 'Uganda', 'África', 'África Oriental', '', 'UG', 'UGA', '256', 'uga.png', '0000-00-00 00:00:00'),
(489, 'Uruguay', 'Uruguay', 'América', 'Suramérica', '', 'UY', 'URY', '598', 'ury.png', '0000-00-00 00:00:00'),
(490, 'Uzbekistan', 'Uzbekistán', 'Asia', 'Asia Central', '', 'UZ', 'UZB', '998', 'uzb.png', '0000-00-00 00:00:00'),
(491, 'Vanuatu', 'Vanuatu', 'Oceania', 'Oceania', '', 'VU', 'VUT', '678', 'vut.png', '0000-00-00 00:00:00'),
(492, 'Venezuela', 'Venezuela', 'América', 'Suramérica', '', 'VE', 'VEN', '58', 'ven.png', '0000-00-00 00:00:00'),
(493, 'Vietnam', 'Vietnam', 'Asia', 'Sudeste de Asia', '', 'VN', 'VNM', '84', 'vnm.png', '0000-00-00 00:00:00'),
(494, 'Wallis and Futuna', 'Wallis y Futuna', 'Oceania', 'Oceania', '', 'WF', 'WLF', '681', 'wlf.png', '0000-00-00 00:00:00'),
(495, 'Yemen', 'Yemen', 'Asia', 'Asia Occidental', '', 'YE', 'YEM', '967', 'yem.png', '0000-00-00 00:00:00'),
(496, 'Djibouti', 'Yibuti', 'África', 'África Oriental', '', 'DJ', 'DJI', '253', 'dji.png', '0000-00-00 00:00:00'),
(497, 'Zambia', 'Zambia', 'África', 'África Oriental', '', 'ZM', 'ZMB', '260', 'zmb.png', '0000-00-00 00:00:00'),
(498, 'Zimbabwe', 'Zimbabue', 'África', 'África Oriental', '', 'ZW', 'ZWE', '263', 'zwe.png', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `exit_application`
--

CREATE TABLE `exit_application` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `proposed_client_id` int(11) DEFAULT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `institution` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `lapsed` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `exitDate` date NOT NULL,
  `arrivalDate` date NOT NULL,
  `concept` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `workPlanSynthesis` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `directiveSubstitute` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `goeSubstitute` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `monthlyPay` decimal(10,2) DEFAULT NULL,
  `totalPay` decimal(10,2) DEFAULT NULL,
  `economics` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `pccApproval` tinyint(1) DEFAULT NULL,
  `pccApprovalDate` date DEFAULT NULL,
  `dcApproval` tinyint(1) DEFAULT NULL,
  `dcApprovalDate` date DEFAULT NULL,
  `agreement` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ivApproval` tinyint(1) DEFAULT NULL,
  `ivApprovalDate` date DEFAULT NULL,
  `secretOffice` tinyint(1) DEFAULT NULL,
  `secretOfficeDate` date DEFAULT NULL,
  `state` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `approvalDate` datetime DEFAULT NULL,
  `approvalObservations` longtext COLLATE utf8_unicode_ci,
  `rejectDate` datetime DEFAULT NULL,
  `rejectReason` longtext COLLATE utf8_unicode_ci,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `exit_application`
--

INSERT INTO `exit_application` (`id`, `client_id`, `country_id`, `proposed_client_id`, `create_user_id`, `update_user_id`, `institution`, `lapsed`, `exitDate`, `arrivalDate`, `concept`, `workPlanSynthesis`, `directiveSubstitute`, `goeSubstitute`, `monthlyPay`, `totalPay`, `economics`, `pccApproval`, `pccApprovalDate`, `dcApproval`, `dcApprovalDate`, `agreement`, `ivApproval`, `ivApprovalDate`, `secretOffice`, `secretOfficeDate`, `state`, `approvalDate`, `approvalObservations`, `rejectDate`, `rejectReason`, `createdAt`, `updatedAt`) VALUES
(1, 1, 394, 1, 2, 2, 'UDG', '2 meses', '2018-01-01', '2018-02-28', '4', NULL, NULL, NULL, NULL, NULL, 'a:0:{}', 1, '2018-01-01', 1, '2018-01-01', '154', 1, '2018-01-01', 1, '2018-01-01', '2', '2018-01-01 00:00:00', 'asdasdasd', NULL, NULL, '2018-01-19 03:41:48', '2018-01-19 03:41:47'),
(2, 2, 254, 2, 2, 2, 'zdasdasdasd', '1 mes', '2018-01-01', '2018-01-31', '4', NULL, NULL, NULL, NULL, NULL, 'a:0:{}', 1, '2018-01-01', 1, '2018-01-01', 'asd', 1, '2018-01-01', 1, '2018-01-01', '2', '2018-01-01 00:00:00', NULL, NULL, NULL, '2018-01-19 04:10:08', '2018-01-19 04:10:08'),
(3, 2, 254, 2, 2, 2, 'zdasdasdasd', '1 mes', '2018-01-01', '2018-01-31', '4', NULL, NULL, NULL, NULL, NULL, 'a:0:{}', 1, '2018-01-01', 1, '2018-01-01', 'asd', 1, '2018-01-01', 1, '2018-01-01', '2', '2018-01-01 00:00:00', NULL, NULL, NULL, '2018-01-19 04:10:14', '2018-01-19 04:10:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fos_user`
--

CREATE TABLE `fos_user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `science_category` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee` tinyint(1) NOT NULL,
  `work_department` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `work_phone` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `home_phone` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cel_phone` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_update_image` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `fos_user`
--

INSERT INTO `fos_user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`, `first_name`, `last_name`, `science_category`, `position`, `employee`, `work_department`, `work_phone`, `home_phone`, `cel_phone`, `user_image`, `last_update_image`) VALUES
(2, 'jose.abadia', 'jose.abadia', 'jose.abadia@reduc.edu.cu', 'jose.abadia@reduc.edu.cu', 1, 'g6c3i3bco80kgskocgw0k8ko0gcgggo', '$2y$13$TWH6zd29O2XNenkBjkMzQ.Hce6b6v2jhX0uU3lmhvY8z9hogmL0Bm', '2018-03-29 19:47:45', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}', 0, NULL, '', '', NULL, NULL, 0, NULL, NULL, NULL, NULL, '', '2018-01-05 17:34:32'),
(3, 'maria.escanel', 'maria.escanel', 'maria.escanel@reduc.edu.cu', 'maria.escanel@reduc.edu.cu', 1, 'db7nq7l6l1c080wckcwck4oswswo8c4', '$2y$13$dE3XlQpZ.ek.xvLpjfpu7evpYMRlLKE4E7hMIGbop9tlFXFeXMzri', '2018-02-27 03:15:43', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:20:\"ROLE_INFO_SPECIALIST\";}', 0, NULL, '', '', NULL, NULL, 0, NULL, NULL, NULL, NULL, '', '2018-02-27 03:07:57'),
(4, 'milady.macareno', 'milady.macareno', 'milady.macareno@reduc.edu.cu', 'milady.macareno@reduc.edu.cu', 1, 'm2pqscxyqlw848o0ckcw80wokwwckg4', '$2y$13$WONaPHX1bDiiIO2sqECEyeedXrmBE6YV.9rfDvRFHlY/s1zNIvNHC', NULL, 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:23:\"ROLE_REQUIRE_SPECIALIST\";}', 0, NULL, '', '', NULL, NULL, 0, NULL, NULL, NULL, NULL, '', '2018-02-27 03:20:37'),
(5, 'yaile.caballero', 'yaile.caballero', 'yaile.caballero@reduc.edu.cu', 'yaile.caballero@reduc.edu.cu', 1, 'gfnompeljlcs0owcc040c0owkoggo40', '$2y$13$aanXnqHS/o2hyvZJQoGkLO1X8rPLePDWNMfgmoRRYBDyw/VcCq0jK', '2018-02-27 11:47:20', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}', 0, NULL, '', '', NULL, NULL, 0, NULL, NULL, NULL, NULL, '', '2018-02-27 03:21:57'),
(6, 'anisabel.galvez', 'anisabel.galvez', 'anisabel.galvez@reduc.edu.cu', 'anisabel.galvez@reduc.edu.cu', 1, '7947tvt2wdc0c48sw04so8og8wcwc00', '$2y$13$q7wnvaIO2BltvRmWabF7VeI.LWEShZvS5pSTa2lcltagGIwNUuFL.', '2018-02-27 03:27:05', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}', 0, NULL, '', '', NULL, NULL, 0, NULL, NULL, NULL, NULL, '', '2018-02-27 03:22:29'),
(7, 'mercedes.salas', 'mercedes.salas', 'mercedes.salas@reduc.edu.cu', 'mercedes.salas@reduc.edu.cu', 1, 'd4f0vx4ere0owwwwcs8koo880wws480', '$2y$13$5YLiZFe3uoIc9uS./TI/nOUjpfdJNWBGqA.0W4TgvDMIWgSyyWgRy', NULL, 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:14:\"ROLE_SECRETARY\";}', 0, NULL, '', '', NULL, NULL, 0, NULL, NULL, NULL, NULL, '', '2018-02-27 03:22:56'),
(8, 'iliana.delgado', 'iliana.delgado', 'iliana.delgado@reduc.edu.cu', 'iliana.delgado@reduc.edu.cu', 1, 'dm1vgpzec7k8ok4sk0kw0scwgg44ksw', '$2y$13$4QX8FLZdonDn157r3lG.IOdZYV0Qtmpnd6j4inI7O4UuYu6U1V1D.', NULL, 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:22:\"ROLE_MANAGE_SPECIALIST\";}', 0, NULL, '', '', NULL, NULL, 0, NULL, NULL, NULL, NULL, '', '2018-02-27 03:23:30'),
(9, 'yanela.rodriguez', 'yanela.rodriguez', 'yanela.rodriguez@reduc.edu.cu', 'yanela.rodriguez@reduc.edu.cu', 1, 'tsytqoibgqo0wc8048ckg4ksk88sowc', '$2y$13$wTFGah1xk3jJnokBmImu3eb0Z7/0oylK22MbLw30JO5YDrlb47SPW', NULL, 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:22:\"ROLE_MANAGE_SPECIALIST\";}', 0, NULL, '', '', NULL, NULL, 0, NULL, NULL, NULL, NULL, '', '2018-02-27 03:24:00'),
(10, 'yunia.llanes', 'yunia.llanes', 'yunia.llanes@reduc.edu.cu', 'yunia.llanes@reduc.edu.cu', 1, 'edqgfiesbe0o0kss88k0k84wgsgco8s', '$2y$13$PKA4ZKXTOP7cRg/CMRKJDOnuin4zSzubUzt4sA6Yjr2JO1SwCNo2K', NULL, 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:23:\"ROLE_REQUIRE_SPECIALIST\";}', 0, NULL, '', '', NULL, NULL, 0, NULL, NULL, NULL, NULL, '', '2018-02-27 03:24:38'),
(11, 'ilderis.letford', 'ilderis.letford', 'ilderis.letford@reduc.edu.cu', 'ilderis.letford@reduc.edu.cu', 1, 'rpnywag2tqo8ws0o4sgooo8gskko448', '$2y$13$ITqQOV3hcnvAggircBmAqOYgK9rG4awBMvxmG3lGFvUDUhXRs5iTO', NULL, 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:23:\"ROLE_REQUIRE_SPECIALIST\";}', 0, NULL, '', '', NULL, NULL, 0, NULL, NULL, NULL, NULL, '', '2018-02-27 03:25:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `image`
--

INSERT INTO `image` (`id`, `name`, `image`, `updatedAt`) VALUES
(1, 'jhgjhg', '5a87e274d5592.png', '2018-02-17 03:06:12'),
(2, 'sdasdasd', '5a91a7566b70a.png', '2018-02-24 12:56:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `passport`
--

CREATE TABLE `passport` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `noPas` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `issueDate` date NOT NULL,
  `expiryDate` date NOT NULL,
  `type` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `first_page` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dropReason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dropDate` date DEFAULT NULL,
  `exits` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `passport`
--

INSERT INTO `passport` (`id`, `client_id`, `create_user_id`, `update_user_id`, `noPas`, `issueDate`, `expiryDate`, `type`, `state`, `first_page`, `dropReason`, `dropDate`, `exits`, `createdAt`, `updatedAt`) VALUES
(1, 1, 2, NULL, 'E321875', '2016-01-27', '2022-01-27', 'OFI', 'ACT', '', NULL, '2013-01-01', 'N;', '2018-03-26 10:42:05', '2018-03-26 10:42:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `passport_application`
--

CREATE TABLE `passport_application` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `passport_id` int(11) DEFAULT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `applicationNumber` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `applicationReason` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `applicationDate` date NOT NULL,
  `passportType` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `applicationType` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `applicantOrgan` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `travelReason` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `sendDate` datetime DEFAULT NULL,
  `confirmDate` datetime DEFAULT NULL,
  `rejectDate` datetime DEFAULT NULL,
  `rejectReasons` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `passport_application`
--

INSERT INTO `passport_application` (`id`, `client_id`, `passport_id`, `create_user_id`, `update_user_id`, `applicationNumber`, `applicationReason`, `applicationDate`, `passportType`, `applicationType`, `applicantOrgan`, `travelReason`, `state`, `sendDate`, `confirmDate`, `rejectDate`, `rejectReasons`, `createdAt`, `updatedAt`) VALUES
(2, 2, NULL, 2, NULL, 'CON180208OFI2', 'CON', '2018-02-08', 'OFI', 'REG', 'Universidad de Camagüey', 'piuyt', 'CON', NULL, NULL, NULL, NULL, '2018-02-15 23:19:11', '2018-02-15 23:19:11'),
(3, 1, NULL, 2, NULL, 'CON180201OFI1', 'CON', '2018-02-01', 'OFI', 'REG', 'Universidad de Camagüey', 'asdasdasdasdasd', 'CON', NULL, NULL, NULL, NULL, '2018-02-15 23:46:51', '2018-02-15 23:46:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `school`
--

CREATE TABLE `school` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `leader` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `school`
--

INSERT INTO `school` (`id`, `name`, `leader`) VALUES
(1, 'Facultad de Ciencias Agropecuarias', 'Amilcar'),
(2, 'Facultad de Ciencias Económicas', 'Nestor'),
(3, 'Facultad de Electromecánica', 'Alguien'),
(4, 'Facultad de Construcciones', 'Alguien 2'),
(5, 'Facultad de Informática y Ciencias Exactas', 'Yaima Filiberto'),
(6, 'Facultad de Lenguas y Comunicación', 'Alguien 3'),
(7, 'Facultad de Ciencias Sociales', 'Alguien 4'),
(8, 'Facultad de Cultura Física', 'Alguien 5'),
(9, 'Facultad de Ciencias Aplicadas', 'Alguien 6'),
(10, 'Facultad de Ciencias Pedagógicas', 'Alguien 7');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_D7943D685E237E06` (`name`),
  ADD UNIQUE KEY `UNIQ_D7943D6819EB6921` (`client_id`);

--
-- Indices de la tabla `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_C74404553B67F367` (`ci`),
  ADD UNIQUE KEY `UNIQ_C7440455E7927C74` (`email`),
  ADD UNIQUE KEY `UNIQ_C7440455C8E4FF8B` (`foreignEmail`),
  ADD UNIQUE KEY `UNIQ_C744045575F3DA25` (`clientPicture`),
  ADD KEY `IDX_C7440455F722092F` (`country_birth_id`),
  ADD KEY `IDX_C7440455F92F3E70` (`country_id`),
  ADD KEY `IDX_C7440455B03A8386` (`created_by_id`),
  ADD KEY `IDX_C7440455E0DFCA6C` (`update_user_id`),
  ADD KEY `IDX_C7440455C32A47EE` (`school_id`);

--
-- Indices de la tabla `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_5373C9665E237E06` (`name`),
  ADD UNIQUE KEY `UNIQ_5373C9661AF33027` (`spName`),
  ADD UNIQUE KEY `UNIQ_5373C9661B6F9774` (`iso2`),
  ADD UNIQUE KEY `UNIQ_5373C9666C68A7E2` (`iso3`);

--
-- Indices de la tabla `exit_application`
--
ALTER TABLE `exit_application`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BF83C2C419EB6921` (`client_id`),
  ADD KEY `IDX_BF83C2C4F92F3E70` (`country_id`),
  ADD KEY `IDX_BF83C2C4957A40DD` (`proposed_client_id`),
  ADD KEY `IDX_BF83C2C485564492` (`create_user_id`),
  ADD KEY `IDX_BF83C2C4E0DFCA6C` (`update_user_id`);

--
-- Indices de la tabla `fos_user`
--
ALTER TABLE `fos_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_957A647992FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_957A6479A0D96FBF` (`email_canonical`),
  ADD UNIQUE KEY `UNIQ_957A6479C05FB297` (`confirmation_token`);

--
-- Indices de la tabla `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `passport`
--
ALTER TABLE `passport`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_B5A26E085A338DB4` (`noPas`),
  ADD KEY `IDX_B5A26E0819EB6921` (`client_id`),
  ADD KEY `IDX_B5A26E0885564492` (`create_user_id`),
  ADD KEY `IDX_B5A26E08E0DFCA6C` (`update_user_id`);

--
-- Indices de la tabla `passport_application`
--
ALTER TABLE `passport_application`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5116FBC119EB6921` (`client_id`),
  ADD KEY `IDX_5116FBC1ABF410D0` (`passport_id`),
  ADD KEY `IDX_5116FBC185564492` (`create_user_id`),
  ADD KEY `IDX_5116FBC1E0DFCA6C` (`update_user_id`);

--
-- Indices de la tabla `school`
--
ALTER TABLE `school`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_F99EDABB5E237E06` (`name`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `area`
--
ALTER TABLE `area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `country`
--
ALTER TABLE `country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=499;

--
-- AUTO_INCREMENT de la tabla `exit_application`
--
ALTER TABLE `exit_application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `fos_user`
--
ALTER TABLE `fos_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `passport`
--
ALTER TABLE `passport`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `passport_application`
--
ALTER TABLE `passport_application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `school`
--
ALTER TABLE `school`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `area`
--
ALTER TABLE `area`
  ADD CONSTRAINT `FK_D7943D6819EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`);

--
-- Filtros para la tabla `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `FK_C7440455B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `fos_user` (`id`),
  ADD CONSTRAINT `FK_C7440455C32A47EE` FOREIGN KEY (`school_id`) REFERENCES `school` (`id`),
  ADD CONSTRAINT `FK_C7440455E0DFCA6C` FOREIGN KEY (`update_user_id`) REFERENCES `fos_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_C7440455F722092F` FOREIGN KEY (`country_birth_id`) REFERENCES `country` (`id`),
  ADD CONSTRAINT `FK_C7440455F92F3E70` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`);

--
-- Filtros para la tabla `exit_application`
--
ALTER TABLE `exit_application`
  ADD CONSTRAINT `FK_BF83C2C419EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_BF83C2C485564492` FOREIGN KEY (`create_user_id`) REFERENCES `fos_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_BF83C2C4957A40DD` FOREIGN KEY (`proposed_client_id`) REFERENCES `client` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_BF83C2C4E0DFCA6C` FOREIGN KEY (`update_user_id`) REFERENCES `fos_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_BF83C2C4F92F3E70` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `passport`
--
ALTER TABLE `passport`
  ADD CONSTRAINT `FK_B5A26E0819EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_B5A26E0885564492` FOREIGN KEY (`create_user_id`) REFERENCES `fos_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_B5A26E08E0DFCA6C` FOREIGN KEY (`update_user_id`) REFERENCES `fos_user` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `passport_application`
--
ALTER TABLE `passport_application`
  ADD CONSTRAINT `FK_5116FBC119EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_5116FBC185564492` FOREIGN KEY (`create_user_id`) REFERENCES `fos_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_5116FBC1ABF410D0` FOREIGN KEY (`passport_id`) REFERENCES `passport` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_5116FBC1E0DFCA6C` FOREIGN KEY (`update_user_id`) REFERENCES `fos_user` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
