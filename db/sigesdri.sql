-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 24-05-2019 a las 01:47:24
-- Versión del servidor: 5.7.24-log
-- Versión de PHP: 7.1.26

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
CREATE DATABASE IF NOT EXISTS `sigesdri` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sigesdri`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agr_application`
--

DROP TABLE IF EXISTS `agr_application`;
CREATE TABLE IF NOT EXISTS `agr_application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `number_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `institution_id` int(11) DEFAULT NULL,
  `background` longtext COLLATE utf8_unicode_ci,
  `objetives` longtext COLLATE utf8_unicode_ci NOT NULL,
  `basement` longtext COLLATE utf8_unicode_ci NOT NULL,
  `commitments` longtext COLLATE utf8_unicode_ci NOT NULL,
  `validity` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `member_for_cuban_part` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `member_for_foreign_part` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `results` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expenses` decimal(10,2) DEFAULT NULL,
  `state` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `confirm_date` datetime DEFAULT NULL,
  `reject_date` datetime DEFAULT NULL,
  `reject_reasons` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `closed` tinyint(1) DEFAULT NULL,
  `used` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_user_id` int(11) DEFAULT NULL,
  `updated_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9E9DD89A96901F54` (`number`),
  UNIQUE KEY `UNIQ_9E9DD89ADA5950D7` (`number_slug`),
  KEY `IDX_9E9DD89A10405986` (`institution_id`),
  KEY `IDX_9E9DD89AE104C1D3` (`created_user_id`),
  KEY `IDX_9E9DD89ABB649746` (`updated_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agr_institution`
--

DROP TABLE IF EXISTS `agr_institution`;
CREATE TABLE IF NOT EXISTS `agr_institution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `acronym` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `country_state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rector` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_599DBE5E237E06` (`name`),
  UNIQUE KEY `UNIQ_599DBEDF2B4115` (`name_slug`),
  KEY `IDX_599DBEF92F3E70` (`country_id`),
  KEY `IDX_599DBE85564492` (`create_user_id`),
  KEY `IDX_599DBEE0DFCA6C` (`update_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `agr_institution`
--

INSERT INTO `agr_institution` (`id`, `name`, `name_slug`, `acronym`, `country_id`, `country_state`, `province`, `city`, `logo`, `url`, `rector`, `created_at`, `updated_at`, `create_user_id`, `update_user_id`, `address`) VALUES
(1, 'Universidad Nacional de Comahue', 'universidad-nacional-de-comahue', 'UNC', 265, NULL, NULL, NULL, 'arg_universidad-nacional-de-comahue.png', 'www.uncoma.edu.ar/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:55', 2, 2, NULL),
(2, 'Universidad Nacional del Centro de la Provincia de Buenos Aires', 'universidad-nacional-del-centro-de-la-provincia-de-buenos-aires', 'UNCPBA', 265, NULL, NULL, NULL, 'arg_universidad-nacional-del-centro-de-la-provincia-de-buenos-aires.png', 'www.unicen.edu.ar/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:56', 2, 2, NULL),
(3, 'Universidad Nacional del Litoral', 'universidad-nacional-del-litoral', 'UNL', 265, NULL, NULL, NULL, 'arg_universidad-nacional-del-litoral.png', 'www.unl.edu.ar', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:56', 2, 2, NULL),
(4, 'Universidad Autónoma Gabriel René Moreno de Santa Cruz de la Sierra', 'universidad-autonoma-gabriel-rene-moreno-de-santa-cruz-de-la-sierra', 'UAGRMSCS', 281, NULL, NULL, NULL, 'bol_universidad-autonoma-gabriel-rene-moreno-de-santa-cruz-de-la-sierra.png', 'http://www.uagrm.edu.bo', NULL, '2019-03-21 00:00:00', '2019-05-07 17:02:00', 2, 2, 'Bolivia'),
(5, 'Universidad Loyola de Bolivia', 'universidad-loyola-de-bolivia', 'ULB', 281, NULL, NULL, NULL, 'bol_universidad-loyola-de-bolivia.png', 'www.loyola.edu.bo/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:56', 2, 2, NULL),
(6, 'Escuela Superior de Estudios Especializados para el Desarrollo Humano', 'escuela-superior-de-estudios-especializados-para-el-desarrollo-humano', 'ESEEDH', 281, NULL, NULL, NULL, NULL, NULL, NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:56', 2, 2, NULL),
(7, 'Universidad Mayor de San Andrés', 'universidad-mayor-de-san-andres', 'UMSA', 281, NULL, NULL, NULL, 'bol_universidad-mayor-de-san-andres.png', 'www.umsa.bo', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:56', 2, 2, NULL),
(8, 'Universidad Técnica de Oruro', 'universidad-tecnica-de-oruro', 'UTO', 281, NULL, NULL, NULL, 'bol_universidad-tecnica-de-oruro.png', 'www.uto.edu.bo', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:56', 2, 2, NULL),
(9, 'Universidad Privada Abierta Latinoamericana', 'universidad-privada-abierta-latinoamericana', 'UPAL', 281, NULL, NULL, NULL, 'bol_universidad-privada-abierta-latinoamericana.png', 'www.upal.edu.bo', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:56', 2, 2, NULL),
(10, 'Universidad Metodista de Piracicaba', 'universidad-metodista-de-piracicaba', 'UMP', 284, NULL, NULL, NULL, 'bra_universidad-metodista-de-piracicaba.png', 'unimep.edu.br', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:56', 2, 2, NULL),
(11, 'Universidad Federal de Reconcavo de Bahía', 'universidad-federal-de-reconcavo-de-bahia', 'UFRB', 284, NULL, NULL, NULL, 'bra_universidad-federal-de-reconcavo-de-bahia.png', 'ufrb.edu.br', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:56', 2, 2, NULL),
(12, 'Universidad del Estado de Bahía', 'universidad-del-estado-de-bahia', 'UEB', 284, NULL, NULL, NULL, 'bra_universidad-del-estado-de-bahia.png', 'www.uneb.br', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:56', 2, 2, NULL),
(13, 'Universidad Federal de Paraná', 'universidad-federal-de-parana', 'UFP', 284, NULL, NULL, NULL, 'bra_universidad-federal-de-parana.png', 'www.ufpr.br', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:56', 2, 2, NULL),
(14, 'Universidad Federal Rural de Amazonía', 'universidad-federal-rural-de-amazonia', 'UFRA', 284, NULL, NULL, NULL, 'bra_universidad-federal-rural-de-amazonia.png', 'novo.ufra.edu.br', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:56', 2, 2, NULL),
(15, 'Universidad Central de Colombia', 'universidad-central-de-colombia', 'UCC', 298, NULL, NULL, NULL, 'col_universidad-central-de-colombia.png', 'www.ucentral.edu.co', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:56', 2, 2, NULL),
(16, 'Universidad Colegio Mayor de Cundinamarca', 'universidad-colegio-mayor-de-cundinamarca', 'UCMC', 298, NULL, NULL, NULL, 'col_universidad-colegio-mayor-de-cundinamarca.png', 'www.unicolmayor.edu.co', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:57', 2, 2, NULL),
(17, 'Universidad Pedagógica y Tecnológica de Colombia', 'universidad-pedagogica-y-tecnologica-de-colombia', 'UPTC', 298, NULL, NULL, NULL, 'col_universidad-pedagogica-y-tecnologica-de-colombia.png', 'www.uptc.edu.co', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:57', 2, 2, NULL),
(18, 'Universidad Fidélitas - Universidad Hispanoamericana- S.J', 'universidad-fidelitas-universidad-hispanoamericana-sj', 'UFUHSJ', 305, NULL, NULL, NULL, 'cri_universidad-fidelitas-universidad-hispanoamericana-sj.png', 'ufidelitas.ac.cr', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:57', 2, 2, NULL),
(19, 'Universidad Estatal a Distancia', 'universidad-estatal-a-distancia', 'UED', 305, NULL, NULL, NULL, 'cri_universidad-estatal-a-distancia.png', 'www.uned.ac.cr', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:57', 2, 2, NULL),
(20, 'Universidad Tecnológica de Panamá', 'universidad-tecnologica-de-panama', 'UTP', 429, NULL, NULL, NULL, 'pan_universidad-tecnologica-de-panama.png', 'www.utp.ac.pa', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:57', 2, 2, NULL),
(21, 'Universidad Nacional de Ingeniería', 'universidad-nacional-de-ingenieria', 'UNI', 417, NULL, NULL, NULL, 'nic_universidad-nacional-de-ingenieria.png', 'uni.edu.ni', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:57', 2, 2, NULL),
(22, 'Universidad APEC', 'universidad-apec', 'UNAPEC', 441, NULL, NULL, NULL, 'dom_universidad-apec.png', 'www.unapec.edu.do/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:57', 2, 2, NULL),
(23, 'Universidad Abierta para Adultos', 'universidad-abierta-para-adultos', 'UAA', 441, NULL, NULL, NULL, 'dom_universidad-abierta-para-adultos.png', 'www.uapa.edu.do/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:57', 2, 2, NULL),
(24, 'Universidad Central del Este', 'universidad-central-del-este', 'UCE', 441, NULL, NULL, NULL, 'dom_universidad-central-del-este.png', 'sp.uce.edu.do', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:57', 2, 2, NULL),
(25, 'Universidad Tecnológica del Sur', 'universidad-tecnologica-del-sur', 'UTS', 441, NULL, NULL, NULL, 'dom_universidad-tecnologica-del-sur.png', 'www.utesur.edu.do', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:57', 2, 2, NULL),
(26, 'Universidad Autónoma de Santo Domingo', 'universidad-autonoma-de-santo-domingo', 'UASD', 441, NULL, NULL, NULL, 'dom_universidad-autonoma-de-santo-domingo.png', 'www.uasd.edu.do', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:57', 2, 2, NULL),
(27, 'Universidad ISA', 'universidad-isa', 'UISA', 441, NULL, NULL, NULL, 'dom_universidad-isa.png', 'www.isa.edu.do', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:57', 2, 2, NULL),
(28, 'Universidad Estatal de Bolívar', 'universidad-estatal-de-bolivar', 'UEB', 310, NULL, NULL, NULL, 'ecu_universidad-estatal-de-bolivar.png', 'www.ueb.edu.ec/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:57', 2, 2, NULL),
(29, 'Universidad Laica Vicente Rocafuerte de Guayaquil', 'universidad-laica-vicente-rocafuerte-de-guayaquil', 'ULVRG', 310, NULL, NULL, NULL, 'ecu_universidad-laica-vicente-rocafuerte-de-guayaquil.png', 'www.ulvr.edu.ec', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:57', 2, 2, NULL),
(30, 'Universidad de Cuenca', 'universidad-de-cuenca', 'UC', 310, NULL, NULL, NULL, 'ecu_universidad-de-cuenca.png', 'www.ucuenca.edu.ec', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:57', 2, 2, NULL),
(31, 'Universidad Laica Eloy Alfaro de Manabí', 'universidad-laica-eloy-alfaro-de-manabi', 'ULEAM', 310, NULL, NULL, NULL, 'ecu_universidad-laica-eloy-alfaro-de-manabi.png', 'www.uleam.edu.ec/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:57', 2, 2, NULL),
(32, 'Ministerio de Trabajo Social de Quito', 'ministerio-de-trabajo-social-de-quito', 'MTSQ', 310, NULL, NULL, NULL, NULL, 'www.trabajo.gob.ec', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:57', 2, 2, NULL),
(33, 'Colegio San Felipe Neri - Riobamba', 'colegio-san-felipe-neri-riobamba', 'CSFNR', 310, NULL, NULL, NULL, 'ecu_colegio-san-felipe-neri-riobamba.png', 'www.sfelipeneri.edu.ec/myindex.php', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(34, 'Universidad Técnica del Norte', 'universidad-tecnica-del-norte', 'UTN', 310, NULL, NULL, NULL, 'ecu_universidad-tecnica-del-norte.png', 'www.utn.edu.ec', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(35, 'Universidad Técnica de Ambato', 'universidad-tecnica-de-ambato', 'UTA', 310, NULL, NULL, NULL, 'ecu_universidad-tecnica-de-ambato.png', 'www.uta.edu.ec/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(36, 'Universidad Técnica de Cotopaxi', 'universidad-tecnica-de-cotopaxi', 'UTC', 310, NULL, NULL, NULL, 'ecu_universidad-tecnica-de-cotopaxi.png', 'www.utc.edu.ec/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(37, 'Escuela Superior Politécnica Ecológica Amazónica', 'escuela-superior-politecnica-ecologica-amazonica', 'ESPEA', 310, NULL, NULL, NULL, 'ecu_escuela-superior-politecnica-ecologica-amazonica.png', 'www.espea.edu.ec/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(38, 'Universidad Estatal Del Sur De Manabi', 'universidad-estatal-del-sur-de-manabi', 'UEDSDM', 310, NULL, NULL, NULL, 'ecu_universidad-estatal-del-sur-de-manabi.png', 'unesum.edu.ec', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(39, 'Universidad de Especialidades Espíritu Santo', 'universidad-de-especialidades-espiritu-santo', 'UEES', 310, NULL, NULL, NULL, 'ecu_universidad-de-especialidades-espiritu-santo.png', 'uees.me/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(40, 'Escuela Superior Politécnica del Litoral', 'escuela-superior-politecnica-del-litoral', 'ESPL', 310, NULL, NULL, NULL, 'ecu_escuela-superior-politecnica-del-litoral.png', 'www.espol.edu.ec/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(41, 'Escuela Superior Politécnica del Chimborazo', 'escuela-superior-politecnica-del-chimborazo', 'ESPC', 310, NULL, NULL, NULL, 'ecu_escuela-superior-politecnica-del-chimborazo.png', 'www.espoch.edu.ec/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(42, 'Instituto Tecnológico Superior Angel Polibio Chaves', 'instituto-tecnologico-superior-angel-polibio-chaves', 'ITSAPC', 310, NULL, NULL, NULL, 'ecu_instituto-tecnologico-superior-angel-polibio-chaves.png', NULL, NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(43, 'Universidad Tecnológica Indoamérica', 'universidad-tecnologica-indoamerica', 'UTI', 310, NULL, NULL, NULL, 'ecu_universidad-tecnologica-indoamerica.png', 'www.uti.edu.ec', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(44, 'Centro Ecuatoriano de Biotecnología del Ambiente', 'centro-ecuatoriano-de-biotecnologia-del-ambiente', 'CEBA', 310, NULL, NULL, NULL, 'ecu_centro-ecuatoriano-de-biotecnologia-del-ambiente.png', 'www.ceba.one/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(45, 'Escuela Superior Politécnica Agropecuaria- Manabí', 'escuela-superior-politecnica-agropecuaria-manabi', 'ESPAM', 310, NULL, NULL, NULL, 'ecu_escuela-superior-politecnica-agropecuaria-manabi.png', 'espam.edu.ec/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(46, 'Universidad Católica de Cuenca', 'universidad-catolica-de-cuenca', 'UCC', 310, NULL, NULL, NULL, 'ecu_universidad-catolica-de-cuenca.png', 'www.ucacue.edu.ec/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(47, 'Universidad Nacional Agraria La Molina', 'universidad-nacional-agraria-la-molina', 'UNALM', 432, NULL, NULL, NULL, NULL, 'www.lamolina.edu.pe', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(48, 'Universidad Peruana Los Andes. Huancayo', 'universidad-peruana-los-andes-huancayo', 'UPLAH', 432, NULL, NULL, NULL, NULL, 'www.upla.edu.pe', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(49, 'Universidad de Huánuco', 'universidad-de-huanuco', 'UH', 432, NULL, NULL, NULL, 'per_universidad-de-huanuco.png', 'www.udh.edu.pe', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(50, 'Universidad Nacional Hermilio Valdizán', 'universidad-nacional-hermilio-valdizan', 'UNHV', 432, NULL, NULL, NULL, 'per_universidad-nacional-hermilio-valdizan.png', 'www.unheval.edu.pe', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(51, 'Universidad Continental de Ciencias e Ingenierías', 'universidad-continental-de-ciencias-e-ingenierias', 'UCCI', 432, NULL, NULL, NULL, NULL, NULL, NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(52, 'Universidad Ricardo Palma', 'universidad-ricardo-palma', 'URP', 432, NULL, NULL, NULL, 'per_universidad-ricardo-palma.png', 'www.urp.edu.pe/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:58', 2, 2, NULL),
(53, 'Universidad Peruana Los Andes', 'universidad-peruana-los-andes', 'UPLA', 432, NULL, NULL, NULL, 'per_universidad-peruana-los-andes.png', 'www.upla.edu.pe', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(54, 'Universidad Alas Peruanas', 'universidad-alas-peruanas', 'UAP', 432, NULL, NULL, NULL, 'per_universidad-alas-peruanas.png', 'www.uap.edu.pe', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(55, 'Universidad Nacional Huancavelica', 'universidad-nacional-huancavelica', 'UNH', 432, NULL, NULL, NULL, 'per_universidad-nacional-huancavelica.png', 'www.unh.edu.pe/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(56, 'Universidad Tecnológica de Los Andes', 'universidad-tecnologica-de-los-andes', 'UTLA', 432, NULL, NULL, NULL, 'per_universidad-tecnologica-de-los-andes.png', 'www.utea.edu.pe', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(57, 'Universidad Nacional de Tumbes', 'universidad-nacional-de-tumbes', 'UNT', 432, NULL, NULL, NULL, 'per_universidad-nacional-de-tumbes.png', 'www.untumbes.edu.pe', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(58, 'Universidad Señor de Sipán', 'universidad-senor-de-sipan', 'USS', 432, NULL, NULL, NULL, 'per_universidad-senor-de-sipan.png', 'www.uss.edu.pe', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(59, 'Universidad Nacional de Cajamarca', 'universidad-nacional-de-cajamarca', 'UNC', 432, NULL, NULL, NULL, 'per_universidad-nacional-de-cajamarca.png', 'www.unc.edu.pe', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(60, 'Universidad Nacional de San Marcos', 'universidad-nacional-de-san-marcos', 'UNSM', 432, NULL, NULL, NULL, 'per_universidad-nacional-de-san-marcos.png', 'www.unmsm.edu.pe/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(61, 'Agencia Peruana de Colaboración', 'agencia-peruana-de-colaboracion', 'APC', 432, NULL, NULL, NULL, NULL, NULL, NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(62, 'Universidad Nacional Santiago Antúnez de Mañolo', 'universidad-nacional-santiago-antunez-de-manolo', 'UNSAM', 432, NULL, NULL, NULL, 'per_universidad-nacional-santiago-antunez-de-manolo.png', 'www.unasam.edu.pe', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(63, 'Consejo Nacional para la Autorización de Funcionamiento de Universidades Peruanas', 'consejo-nacional-para-la-autorizacion-de-funcionamiento-de-universidades-peruanas', 'CNAFUP', 432, NULL, NULL, NULL, NULL, NULL, NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(64, 'Instituto Universitario de Puebla', 'instituto-universitario-de-puebla', 'IUP', 394, NULL, NULL, NULL, 'mex_instituto-universitario-de-puebla.png', 'www.iupuebla.edu.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(65, 'Instituto Tecnológico de Tlaxcala', 'instituto-tecnologico-de-tlaxcala', 'ITT', 394, NULL, NULL, NULL, 'mex_instituto-tecnologico-de-tlaxcala.png', 'www.institutotecnologicodetlaxcala.edu.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(66, 'Universidad Nacional Autónoma de México', 'universidad-nacional-autonoma-de-mexico', 'UNAM', 394, NULL, NULL, NULL, 'mex_universidad-nacional-autonoma-del-estado-de-mexico.png', NULL, NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(67, 'Universidad Quetzalcoatl', 'universidad-quetzalcoatl', 'UQ', 394, NULL, NULL, NULL, 'mex_universidad-quetzalcoatl.png', 'www.uqi.edu.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(68, 'Instituto Tecnológico Sup. de Ciudad Constitución', 'instituto-tecnologico-sup-de-ciudad-constitucion', 'ITSCC', 394, NULL, NULL, NULL, 'mex_instituto-tecnologico-sup-de-ciudad-constitucion.png', 'itscc.edu.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(69, 'Seguridad y Medio Ambiente S.A de C.V ( Syma)', 'seguridad-y-medio-ambiente-sa-de-cv-syma', 'SMASACVS', 394, NULL, NULL, NULL, NULL, NULL, NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(70, 'Universidad Autónoma Agraria Antonio Narro', 'universidad-autonoma-agraria-antonio-narro', 'UAAAN', 394, NULL, NULL, NULL, 'mex_universidad-autonoma-agraria-antonio-narro.png', 'www.uaaan.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(71, 'Instituto Veracruzano de Educación Superior', 'instituto-veracruzano-de-educacion-superior', 'IVES', 394, NULL, NULL, NULL, 'mex_instituto-veracruzano-de-educacion-superior.png', 'www.ives.edu.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(72, 'Colegio de Tlaxcala', 'colegio-de-tlaxcala', 'CT', 394, NULL, NULL, NULL, 'mex_colegio-de-tlaxcala.png', 'www.coltlax.edu.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(73, 'Universidad Angelópolis', 'universidad-angelopolis', 'UA', 394, NULL, NULL, NULL, 'mex_universidad-angelopolis.png', 'www.uniangelopolis.edu.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(74, 'Universidad Autónoma de Tlaxcala', 'universidad-autonoma-de-tlaxcala', 'UAT', 394, NULL, NULL, NULL, 'mex_universidad-autonoma-de-tlaxcala.png', 'www.uatx.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:41:59', 2, 2, NULL),
(75, 'Centro de Investigación e Innovación Educativa del Noroeste', 'centro-de-investigacion-e-innovacion-educativa-del-noroeste', 'CIIEN', 394, NULL, NULL, NULL, 'mex_centro-de-investigacion-e-innovacion-educativa-del-noroeste.png', 'ciien.com.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(76, 'Universidad de Guadalajara', 'universidad-de-guadalajara', 'UDG', 394, NULL, NULL, NULL, 'mex_universidad-de-guadalajara.png', 'www.udg.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(77, 'Centro Universitario de la Costa Sur - UDG', 'centro-universitario-de-la-costa-sur-udg', 'CUCSUR', 394, 'Jalisco', 'Guadalajara', 'Autlán de Navarro', 'mex_centro-universitario-de-la-costa-sur-udg.png', 'http://www.cucsur.udg.mx', NULL, '2019-03-21 00:00:00', '2019-03-29 17:13:42', 2, 2, NULL),
(78, 'Colegio de Bachilleres del Estado de Puebla', 'colegio-de-bachilleres-del-estado-de-puebla', 'CBEP', 394, NULL, NULL, NULL, 'mex_colegio-de-bachilleres-del-estado-de-puebla.png', 'www.cobaep.edu.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(79, 'Instituto Tecnológico Superior de Atlixco', 'instituto-tecnologico-superior-de-atlixco', 'ITSA', 394, NULL, NULL, NULL, 'mex_instituto-tecnologico-superior-de-atlixco.png', 'www.itsatlixco.edu.mx/tec/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(80, 'Universidad de los Ángeles de Puebla', 'universidad-de-los-angeles-de-puebla', 'UP', 394, NULL, NULL, NULL, 'mex_universidad-de-los-angeles-de-puebla.png', 'www.udeap.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(81, 'Universidad de Oriente - Puebla', 'universidad-de-oriente-puebla', 'UOP', 394, NULL, NULL, NULL, 'mex_universidad-de-oriente-puebla.png', 'www.uo.edu.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(82, 'Universidad Autónoma de Nuevo León', 'universidad-autonoma-de-nuevo-leon', 'UANL', 394, NULL, NULL, NULL, 'mex_universidad-autonoma-de-nuevo-leon.png', 'www.uanl.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(83, 'Centro de Investigación y de Estudios Avanzados del Instituto Politécnico Nacional', 'centro-de-investigacion-y-de-estudios-avanzados-del-instituto-politecnico-nacional', 'CIEAIPN', 394, NULL, NULL, NULL, 'mex_centro-de-investigacion-y-de-estudios-avanzados-del-instituto-politecnico-nacional.png', 'www.cinvestav.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(84, 'Instituto Tecnológico de Culiacán', 'instituto-tecnologico-de-culiacan', 'ITC', 394, NULL, NULL, NULL, 'mex_instituto-tecnologico-de-culiacan.png', 'itculiacan.edu.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(85, 'Benemérita Universidad Autónoma de Puebla', 'benemerita-universidad-autonoma-de-puebla', 'BUAP', 394, NULL, NULL, NULL, 'mex_benemerita-universidad-autonoma-de-puebla.png', 'www.buap.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(86, 'Instituto de Estudio Superior en Arquitectura y Diseño Puebla', 'instituto-de-estudio-superior-en-arquitectura-y-diseno-puebla', 'IESADP', 394, NULL, NULL, NULL, 'mex_instituto-de-estudio-superior-en-arquitectura-y-diseno-puebla.png', 'iesac.edu.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(87, 'Universidad Autónoma Benito Juárez de Oaxaca', 'universidad-autonoma-benito-juarez-de-oaxaca', 'UABJO', 394, NULL, NULL, NULL, 'mex_universidad-autonoma-benito-juarez-de-oaxaca.png', 'www.uabjo.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(88, 'Fundación Movimiento es Salud A.C', 'fundacion-movimiento-es-salud-ac', 'FMSAC', 394, NULL, NULL, NULL, 'mex_fundacion-movimiento-es-salud-ac.png', 'fundacionmovimientoessalud.org.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(89, 'Instituto Mexicano de Estudios Pedagógicos', 'instituto-mexicano-de-estudios-pedagogicos', 'IMEP', 394, NULL, NULL, NULL, 'mex_instituto-mexicano-de-estudios-pedagogicos.png', 'www.imep.edu.mx', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(90, 'Universidad de Vigo', 'universidad-de-vigo', 'UV', 317, NULL, NULL, NULL, 'esp_universidad-de-vigo.png', 'www.uvigo.es', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(91, 'Universidad de Cantabria', 'universidad-de-cantabria', 'UC', 317, NULL, NULL, NULL, 'esp_universidad-de-cantabria.png', 'www.unican.es', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(92, 'Universidad Málaga', 'universidad-malaga', 'UM', 317, NULL, NULL, NULL, 'esp_universidad-malaga.png', 'www.uma.es', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(93, 'Universidad de Sevilla', 'universidad-de-sevilla', 'US', 317, NULL, NULL, NULL, 'esp_universidad-de-sevilla.png', 'www.us.es', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(94, 'Universidad de Jaen', 'universidad-de-jaen', 'UJ', 317, NULL, NULL, NULL, 'esp_universidad-de-jaen.png', 'www.ujaen.es', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(95, 'Universidad de Navarra', 'universidad-de-navarra', 'UN', 317, NULL, NULL, NULL, 'esp_universidad-de-navarra.png', 'www.unav.edu', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:00', 2, 2, NULL),
(96, 'Universidad de las Islas Baleares', 'universidad-de-las-islas-baleares', 'UIB', 317, NULL, NULL, NULL, 'esp_universidad-de-las-islas-baleares.png', 'www.uib.es', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:01', 2, 2, NULL),
(97, 'Universidad de Valencia', 'universidad-de-valencia', 'UV', 317, NULL, NULL, NULL, 'esp_universidad-valencia.png', 'www.uv.es', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:01', 2, 2, NULL),
(98, 'Amadeus IT Group', 'amadeus-it-group', 'AITG', 317, NULL, NULL, NULL, 'esp_amadeus-it-group.png', 'www.amadeus.com', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:01', 2, 2, NULL),
(99, 'Universidad de Oviedo', 'universidad-de-oviedo', 'UO', 317, NULL, NULL, NULL, 'esp_universidad-de-oviedo.png', 'www.uniovi.es', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:01', 2, 2, NULL),
(100, 'Universidad Autonoma de Madrid', 'universidad-autonoma-de-madrid', 'UAM', 317, NULL, NULL, NULL, 'esp_universidad-autonoma-de-madrid.png', 'www.uam.es', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:01', 2, 2, NULL),
(101, 'Universidad de Santiago de Compostela', 'universidad-de-santiago-de-compostela', 'USC', 317, NULL, NULL, NULL, 'esp_universidad-de-santiago-de-compostela.png', 'www.usc.es', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:01', 2, 2, NULL),
(102, 'Institut polytechnique LaSalle Beauvais - ESITPA', 'institut-polytechnique-lasalle-beauvais-esitpa', 'ILSBESITPA', 324, NULL, NULL, NULL, 'fra_institut-polytechnique-lasalle-beauvais-esitpa.png', 'www.unilasalle.fr', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:01', 2, 2, NULL),
(103, 'Università degli Studi Reggio Calabria', 'universita-degli-studi-reggio-calabria', 'USRC', 375, NULL, NULL, NULL, 'ita_universita-degli-studi-reggio-calabria.png', 'www.unirc.it', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:01', 2, 2, NULL),
(104, 'Università degli Studi di Sassari', 'universita-degli-studi-di-sassari', 'USS', 375, NULL, NULL, NULL, 'ita_universita-degli-studi-di-sassari.png', 'www.uniss.it', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:01', 2, 2, NULL),
(105, 'Università degli Studi di Firenze', 'universita-degli-studi-di-firenze', 'USF', 375, NULL, NULL, NULL, 'ita_universita-degli-studi-di-firenze.png', 'www.unifi.it/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:01', 2, 2, NULL),
(106, 'Universidad de Ciencias Aplicadas de Münster', 'universidad-de-ciencias-aplicadas-de-munster', 'UCAM', 256, NULL, NULL, NULL, 'deu_universidad-de-ciencias-aplicadas-de-munster.png', 'en.fh-muenster.de', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:01', 2, 2, NULL),
(107, 'Norwegian School of Veterinary Science', 'norwegian-school-of-veterinary-science', 'NSVS', 421, NULL, NULL, NULL, 'nor_norwegian-school-of-veterinary-science.png', 'www.nmbu.no/fakultet/vet', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:01', 2, 2, NULL),
(108, 'Universidad Estatal de Belarús', 'universidad-estatal-de-belarus', 'UEB', 279, NULL, NULL, NULL, 'blr_universidad-estatal-de-belarus.png', 'www.bsu.by', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:01', 2, 2, NULL),
(109, 'Universidad Provincial de Córdova', 'universidad-provincial-de-cordova', 'UPC', 265, NULL, NULL, NULL, 'arg_universidad-provincial-de-cordova.png', 'www.upc.edu.ar', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:01', 2, 2, NULL),
(110, 'Universidad Nacional de San Antonio de Areco', 'universidad-nacional-de-san-antonio-de-areco', 'UNSAA', 265, NULL, NULL, NULL, 'arg_universidad-nacional-de-san-antonio-de-areco.png', 'www.unsada.edu.ar', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:01', 2, 2, NULL),
(111, 'Universidad Santo Tomás de Aquino - Tunja', 'universidad-santo-tomas-de-aquino-tunja', 'USTAT', 298, NULL, NULL, NULL, 'col_universidad-santo-tomas-de-aquino-tunja.png', 'www.ustatunja.edu.co/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:01', 2, 2, NULL),
(112, 'Universidad de La Américas', 'universidad-de-la-americas', 'UDELAS', 429, NULL, NULL, NULL, 'pan_universidad-de-la-americas.png', 'www.udelas.ac.pa', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:01', 2, 2, NULL),
(113, 'Universidad de Santo Domingo', 'universidad-de-santo-domingo', 'USD', 441, NULL, NULL, NULL, 'dom_universidad-de-santo-domingo.png', 'uasd.edu.do/index.php', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:02', 2, 2, NULL),
(114, 'Universidad Federico Henrique y Carvajal', 'universidad-federico-henrique-y-carvajal', 'UFHC', 441, NULL, NULL, NULL, 'dom_universidad-federico-henrique-y-carvajal.png', 'ufhec.edu.do/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:02', 2, 2, NULL),
(115, 'Universidad Enrique Díaz de León', 'universidad-enrique-diaz-de-leon', 'UEDL', 394, NULL, NULL, NULL, 'mex_universidad-enrique-diaz-de-leon.png', 'unedl.edu.mx/portal/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:02', 2, 2, NULL),
(116, 'Universidad Autónoma de Sinaloa', 'universidad-autonoma-de-sinaloa', 'UAS', 394, NULL, NULL, NULL, 'mex_universidad-autonoma-de-sinaloa.png', 'www.uas.edu.mx/', NULL, '2019-03-21 00:00:00', '2019-03-28 23:42:02', 2, 2, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agr_institutional`
--

DROP TABLE IF EXISTS `agr_institutional`;
CREATE TABLE IF NOT EXISTS `agr_institutional` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `number_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `institution_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `action_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `mes_approval` tinyint(1) NOT NULL,
  `digital_copy` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_user_id` int(11) DEFAULT NULL,
  `updated_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8DC309FC3E030ACD` (`application_id`),
  UNIQUE KEY `UNIQ_8DC309FC727ACA70` (`parent_id`),
  KEY `IDX_8DC309FCE104C1D3` (`created_user_id`),
  KEY `IDX_8DC309FCBB649746` (`updated_user_id`),
  KEY `IDX_8DC309FC10405986` (`institution_id`),
  KEY `IDX_8DC309FCF92F3E70` (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `agr_institutional`
--

INSERT INTO `agr_institutional` (`id`, `number`, `number_slug`, `application_id`, `institution_id`, `country_id`, `action_type`, `parent_id`, `start_date`, `end_date`, `mes_approval`, `digital_copy`, `created_at`, `updated_at`, `created_user_id`, `updated_user_id`) VALUES
(1, '1', '1', NULL, 1, 265, '', NULL, '2010-03-21', '2019-02-21', 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:47', 2, 2),
(2, '2', '2', NULL, 2, 265, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:47', 2, 2),
(3, '3', '3', NULL, 3, 265, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:47', 2, 2),
(4, '4', '4', NULL, 4, 281, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:47', 2, 2),
(5, '5', '5', NULL, 5, 281, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:47', 2, 2),
(6, '6', '6', NULL, 6, 281, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:47', 2, 2),
(7, '7', '7', NULL, 7, 281, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:47', 2, 2),
(8, '8', '8', NULL, 8, 281, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:47', 2, 2),
(9, '9', '9', NULL, 9, 281, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:47', 2, 2),
(10, '10', '10', NULL, 10, 284, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:47', 2, 2),
(11, '11', '11', NULL, 11, 284, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(12, '12', '12', NULL, 12, 284, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(13, '13', '13', NULL, 13, 284, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(14, '14', '14', NULL, 14, 284, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(15, '15', '15', NULL, 15, 298, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(16, '16', '16', NULL, 16, 298, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(17, '17', '17', NULL, 17, 298, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(18, '18', '18', NULL, 18, 305, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(19, '19', '19', NULL, 19, 305, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(20, '20', '20', NULL, 20, 429, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(21, '21', '21', NULL, 21, 417, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(22, '22', '22', NULL, 22, 441, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(23, '23', '23', NULL, 23, 441, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(24, '24', '24', NULL, 24, 441, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(25, '25', '25', NULL, 25, 441, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(26, '26', '26', NULL, 26, 441, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(27, '27', '27', NULL, 27, 441, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(28, '28', '28', NULL, 28, 310, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(29, '29', '29', NULL, 29, 310, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(30, '30', '30', NULL, 30, 310, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:48', 2, 2),
(31, '31', '31', NULL, 31, 310, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(32, '32', '32', NULL, 32, 310, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(33, '33', '33', NULL, 33, 310, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(34, '34', '34', NULL, 34, 310, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(35, '35', '35', NULL, 35, 310, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(36, '36', '36', NULL, 36, 310, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(37, '37', '37', NULL, 37, 310, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(38, '38', '38', NULL, 38, 310, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(39, '39', '39', NULL, 39, 310, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(40, '40', '40', NULL, 40, 310, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(41, '41', '41', NULL, 41, 310, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(42, '42', '42', NULL, 42, 310, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(43, '43', '43', NULL, 43, 310, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(44, '44', '44', NULL, 44, 310, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(45, '45', '45', NULL, 45, 310, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(46, '46', '46', NULL, 46, 310, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(47, '47', '47', NULL, 47, 432, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(48, '48', '48', NULL, 48, 432, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(49, '49', '49', NULL, 49, 432, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(50, '50', '50', NULL, 50, 432, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:49', 2, 2),
(51, '51', '51', NULL, 51, 432, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:50', 2, 2),
(52, '52', '52', NULL, 52, 432, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:50', 2, 2),
(53, '53', '53', NULL, 53, 432, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:50', 2, 2),
(54, '54', '54', NULL, 54, 432, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:50', 2, 2),
(55, '55', '55', NULL, 55, 432, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:50', 2, 2),
(56, '56', '56', NULL, 56, 432, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:50', 2, 2),
(57, '57', '57', NULL, 57, 432, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:50', 2, 2),
(58, '58', '58', NULL, 58, 432, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:50', 2, 2),
(59, '59', '59', NULL, 59, 432, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:50', 2, 2),
(60, '60', '60', NULL, 60, 432, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:50', 2, 2),
(61, '61', '61', NULL, 61, 432, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:50', 2, 2),
(62, '62', '62', NULL, 62, 432, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:50', 2, 2),
(63, '63', '63', NULL, 63, 432, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:50', 2, 2),
(64, '64', '64', NULL, 64, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:50', 2, 2),
(65, '65', '65', NULL, 65, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:50', 2, 2),
(66, '66', '66', NULL, 66, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:51', 2, 2),
(67, '67', '67', NULL, 67, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:51', 2, 2),
(68, '68', '68', NULL, 68, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:51', 2, 2),
(69, '69', '69', NULL, 69, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:51', 2, 2),
(70, '70', '70', NULL, 70, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:51', 2, 2),
(71, '71', '71', NULL, 71, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:51', 2, 2),
(72, '72', '72', NULL, 72, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:51', 2, 2),
(73, '73', '73', NULL, 73, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:51', 2, 2),
(74, '74', '74', NULL, 74, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:51', 2, 2),
(75, '75', '75', NULL, 75, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:51', 2, 2),
(76, '76', '76', NULL, 76, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:51', 2, 2),
(77, '77', '77', NULL, 77, 394, '', NULL, NULL, NULL, 1, '', '2019-03-21 00:00:00', '2019-03-28 23:45:51', 2, 2),
(78, '78', '78', NULL, 78, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:51', 2, 2),
(79, '79', '79', NULL, 79, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:51', 2, 2),
(80, '80', '80', NULL, 80, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:51', 2, 2),
(81, '81', '81', NULL, 81, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:51', 2, 2),
(82, '82', '82', NULL, 82, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:51', 2, 2),
(83, '83', '83', NULL, 83, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:51', 2, 2),
(84, '84', '84', NULL, 84, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:51', 2, 2),
(85, '85', '85', NULL, 85, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(86, '86', '86', NULL, 86, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(87, '87', '87', NULL, 87, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(88, '88', '88', NULL, 88, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(89, '89', '89', NULL, 89, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(90, '90', '90', NULL, 90, 317, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(91, '91', '91', NULL, 91, 317, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(92, '92', '92', NULL, 92, 317, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(93, '93', '93', NULL, 93, 317, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(94, '94', '94', NULL, 94, 317, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(95, '95', '95', NULL, 95, 317, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(96, '96', '96', NULL, 96, 317, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(97, '97', '97', NULL, 97, 317, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(98, '98', '98', NULL, 98, 317, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(99, '99', '99', NULL, 99, 317, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(100, '100', '100', NULL, 100, 317, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(101, '101', '101', NULL, 101, 317, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(102, '102', '102', NULL, 102, 324, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(103, '103', '103', NULL, 103, 375, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(104, '104', '104', NULL, 104, 375, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(105, '105', '105', NULL, 105, 375, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(106, '106', '106', NULL, 106, 256, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:52', 2, 2),
(107, '107', '107', NULL, 107, 421, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:53', 2, 2),
(108, '108', '108', NULL, 108, 279, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:53', 2, 2),
(109, '109', '109', NULL, 109, 265, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:53', 2, 2),
(110, '110', '110', NULL, 110, 265, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:53', 2, 2),
(111, '111', '111', NULL, 111, 298, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:53', 2, 2),
(112, '112', '112', NULL, 112, 429, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:53', 2, 2),
(113, '113', '113', NULL, 113, 441, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:53', 2, 2),
(114, '114', '114', NULL, 114, 441, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:53', 2, 2),
(115, '115', '115', NULL, 115, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:53', 2, 2),
(116, '116', '116', NULL, 116, 394, '', NULL, NULL, NULL, 1, NULL, '2019-03-21 00:00:00', '2019-03-28 23:45:53', 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agr_institutionals_areas`
--

DROP TABLE IF EXISTS `agr_institutionals_areas`;
CREATE TABLE IF NOT EXISTS `agr_institutionals_areas` (
  `institutional_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  PRIMARY KEY (`institutional_id`,`area_id`),
  KEY `IDX_16F11723C1A65D29` (`institutional_id`),
  KEY `IDX_16F11723BD0F409C` (`area_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agr_national`
--

DROP TABLE IF EXISTS `agr_national`;
CREATE TABLE IF NOT EXISTS `agr_national` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) DEFAULT NULL,
  `benefited_areas` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_user_id` int(11) DEFAULT NULL,
  `updated_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1B456768F92F3E70` (`country_id`),
  KEY `IDX_1B456768E104C1D3` (`created_user_id`),
  KEY `IDX_1B456768BB649746` (`updated_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `agr_national`
--

INSERT INTO `agr_national` (`id`, `country_id`, `benefited_areas`, `state`, `created_at`, `updated_at`, `created_user_id`, `updated_user_id`) VALUES
(1, 492, NULL, 1, '2019-03-21 00:00:00', '2019-03-21 00:00:00', 2, 2),
(2, 259, NULL, 1, '2019-03-21 00:00:00', '2019-03-21 00:00:00', 2, 2),
(3, 283, NULL, 1, '2019-03-21 00:00:00', '2019-03-21 00:00:00', 2, 2),
(4, 295, NULL, 1, '2019-03-21 00:00:00', '2019-03-21 00:00:00', 2, 2),
(5, 339, NULL, 1, '2019-03-21 00:00:00', '2019-03-21 00:00:00', 2, 2),
(6, 413, NULL, 1, '2019-03-21 00:00:00', '2019-03-21 00:00:00', 2, 2),
(7, 414, NULL, 1, '2019-03-21 00:00:00', '2019-03-21 00:00:00', 2, 2),
(8, 493, NULL, 1, '2019-03-21 00:00:00', '2019-03-21 00:00:00', 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cln_client`
--

DROP TABLE IF EXISTS `cln_client`;
CREATE TABLE IF NOT EXISTS `cln_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ci` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `second_name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_last_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `second_last_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `full_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `full_name_slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `short_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `short_name_slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `foreign_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `private_phone` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cell_phone` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_type` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `client_picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `languages` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `organizations` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `area_id` int(11) DEFAULT NULL,
  `mothers_name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fathers_name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `civil_state` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `height` decimal(10,2) DEFAULT NULL,
  `eyes_color` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `skin_color` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hair_color` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pvs` longtext COLLATE utf8_unicode_ci,
  `citizenship` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_birth_id` int(11) DEFAULT NULL,
  `state_birth` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city_birth` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `foreign_city_birth` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `state` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `district` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `street` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `highway` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_between` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secong_between` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `number` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `km` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `building` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `apartment` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cpa` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `farm` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `town` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `circunscription` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `post_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `students_year` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `students_position` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `students_career` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `students_state` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `students_last_update` datetime DEFAULT NULL,
  `students_inactive_at` datetime DEFAULT NULL,
  `workers_occupation` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workers_specialty` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workers_edu_category` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workers_sci_grade` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workers_position` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workers_work_place` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workers_admission_date` date DEFAULT NULL,
  `workers_work_phone` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workers_pay` decimal(10,2) DEFAULT NULL,
  `workers_state` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workers_last_update` datetime DEFAULT NULL,
  `workers_inactive_at` datetime DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expired_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_user_id` int(11) DEFAULT NULL,
  `updated_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5772B943B67F367` (`ci`),
  UNIQUE KEY `UNIQ_5772B94E7927C74` (`email`),
  UNIQUE KEY `UNIQ_5772B94472B0CD0` (`foreign_email`),
  UNIQUE KEY `UNIQ_5772B9444E60BF4` (`client_picture`),
  KEY `IDX_5772B94F722092F` (`country_birth_id`),
  KEY `IDX_5772B94F92F3E70` (`country_id`),
  KEY `IDX_5772B94E104C1D3` (`created_user_id`),
  KEY `IDX_5772B94BB649746` (`updated_user_id`),
  KEY `IDX_5772B94BD0F409C` (`area_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `cln_client`
--

INSERT INTO `cln_client` (`id`, `ci`, `first_name`, `second_name`, `first_last_name`, `second_last_name`, `full_name`, `full_name_slug`, `short_name`, `short_name_slug`, `birthday`, `gender`, `email`, `foreign_email`, `private_phone`, `cell_phone`, `client_type`, `client_picture`, `languages`, `organizations`, `area_id`, `mothers_name`, `fathers_name`, `civil_state`, `weight`, `height`, `eyes_color`, `skin_color`, `hair_color`, `pvs`, `citizenship`, `country_birth_id`, `state_birth`, `city_birth`, `foreign_city_birth`, `country_id`, `state`, `city`, `district`, `street`, `highway`, `first_between`, `secong_between`, `number`, `km`, `building`, `apartment`, `cpa`, `farm`, `town`, `circunscription`, `post_code`, `students_year`, `students_position`, `students_career`, `students_state`, `students_last_update`, `students_inactive_at`, `workers_occupation`, `workers_specialty`, `workers_edu_category`, `workers_sci_grade`, `workers_position`, `workers_work_place`, `workers_admission_date`, `workers_work_phone`, `workers_pay`, `workers_state`, `workers_last_update`, `workers_inactive_at`, `enabled`, `locked`, `expired`, `expired_at`, `created_at`, `updated_at`, `created_user_id`, `updated_user_id`) VALUES
(1, '87102522022', 'José', 'Ramón', 'Abadía', 'Lugo', 'José Ramón Abadía Lugo', 'jose-ramon-abadia-lugo', 'José Abadía', 'jose-abadia', '1987-10-25', 'M', 'jose.abadia@reduc.edu.cu', 'jr.avalug@gmail.com', '(32) 27-41-51', '(53) 77-79-74', 'cua', '87102522022-jose-ramon-abadia-lugo.png', 'a:1:{i:0;s:2:\"es\";}', 'a:2:{i:0;s:3:\"cdr\";i:1;s:3:\"ctc\";}', 5, 'Saskia', 'José', 'CAS', '90.00', '180.00', 'Pardos', 'Blanca', 'Negro', NULL, 'cubana', 307, 'Camagüey', 'Nuevitas', NULL, 307, 'Camagüey', 'Camagüey', NULL, 'Cristo', NULL, 'Honda', 'Bembeta', '184', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '70 100', NULL, NULL, NULL, NULL, NULL, NULL, 'Profesor', 'Ciencia de la Computación', 'INS', 'LIC', 'Profesor', 'Dirección de Relaciones Internacionales', '2014-10-01', NULL, '525.00', NULL, '2019-05-23 19:26:59', NULL, 1, 0, 0, NULL, '2018-06-28 17:03:26', '2019-05-23 19:27:00', NULL, 2),
(2, '87090922095', 'Anisabel', 'Regla', 'Gálvez', 'Fernández', 'Anisabel Regla Gálvez Fernández', 'anisabel-regla-galvez-fernandez', 'Anisabel Gálvez', 'anisabel-galvez', '1987-09-09', 'F', 'anisabel.galvez@reduc.edu.cu', NULL, '(32) 27-41-51', '(53) 65-29-40', 'doc', '87090922095-anisabel-regla-galvez-fernandez.png', 'a:1:{i:0;s:2:\"es\";}', 'a:4:{i:0;s:3:\"cdr\";i:1;s:3:\"ctc\";i:2;s:3:\"fmc\";i:3;s:3:\"ujc\";}', 2, 'Ana', 'Mario', 'CAS', NULL, NULL, 'Pardos', 'Blanca', 'Castaño', NULL, 'cubana', 307, 'Camagüey', 'Nuevitas', NULL, 307, 'Camagüey', 'Camagüey', NULL, 'Cristo', NULL, 'Honda', 'Bembeta', '184', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '70 100', NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Contabilidad', 'AUX', 'MSC', 'Metodóloga', 'Dirección de Relaciones Internacionales', NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-06-28 17:20:08', '2019-05-15 18:48:13', NULL, 2),
(3, '78060323018', 'Yunia', NULL, 'Llanes', 'Estrada', 'Yunia  Llanes Estrada', 'yunia-llanes-estrada', 'Yunia Llanes', 'yunia-llanes', '1978-06-03', 'F', 'yunia.llanes@reduc.edu.cu', NULL, NULL, '(52) 49-67-34', 'nod', '78060323018-yunia-llanes-estrada.png', 'a:2:{i:0;s:2:\"es\";i:1;s:2:\"en\";}', 'a:2:{i:0;s:3:\"cdr\";i:1;s:3:\"fmc\";}', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cubana', 307, 'Camagüey', NULL, NULL, 307, 'Camagüey', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-06-29 12:57:38', NULL, 'Especialista en Relaciones Internacionales', 'Licenciada en Educación (Especialidad Inglés)', NULL, 'MSC', NULL, 'Dirección de Relaciones Internacionales', NULL, '32266839', '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-06-29 12:57:40', '2019-05-15 18:48:13', 10, 11),
(4, '89040934210', 'Yanela', NULL, 'Rodríguez', 'Alvarez', 'Yanela  Rodríguez Alvarez', 'yanela-rodriguez-alvarez', 'Yanela Rodríguez', 'yanela-rodriguez', '1989-04-09', 'F', 'yanela.rodriguez@reduc.edu.cu', 'yanela.ralvarez89@gmail.com', '(32) 24-51-82', '(53) 62-20-51', 'doc', NULL, 'a:2:{i:0;s:5:\"en_US\";i:1;s:2:\"pt\";}', 'a:4:{i:0;s:3:\"cdr\";i:1;s:3:\"ctc\";i:2;s:3:\"fmc\";i:3;s:3:\"pcc\";}', 5, 'Nelsa', 'José Manuel', 'CAS', '75.00', '165.00', 'Pardos', 'Blanca', 'Castaño', 'ninguna', 'cubana', 307, 'Camagüey', 'Camagüey', NULL, 307, 'Camagüey', 'Camagüey', '-', 'Padre Valencia', NULL, 'Lugareño', 'San Ramón', '279', NULL, NULL, NULL, '-', '-', '-', '-', '70 100', NULL, NULL, NULL, NULL, NULL, NULL, 'Especialista DRI', 'Ingeniería Informática', 'ASI', 'MSC', 'Especialista DRI', 'DRI', '2012-08-27', '32234217', '707.75', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-07-17 12:54:09', '2019-05-15 18:48:13', NULL, 4),
(5, '78102236826', 'Lindsay', 'Alonso', 'Gómez', 'Beltrán', 'Lindsay Alonso Gómez Beltrán', 'lindsay-alonso-gomez-beltran', 'Lindsay Gómez', 'lindsay-gomez', '1978-10-22', 'M', 'lindsay.gomez@reduc.edu.cu', NULL, '(32) 29-58-60', NULL, 'doc', NULL, 'a:2:{i:0;s:2:\"es\";i:1;s:2:\"en\";}', 'a:1:{i:0;s:3:\"pcc\";}', 5, 'María Ester', 'Alonso', 'SOL', '90.00', '180.00', 'Pardos', 'Blanca', 'Negro', NULL, 'cubana', 307, 'Camagüey', 'Camagüey', NULL, 307, 'Camagüey', 'Camagüey', NULL, 'Calle 5ta', NULL, 'Chino Manuel', 'Mariano Barberan', '7', NULL, NULL, NULL, NULL, NULL, NULL, 'José Martí', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesor', 'Informática', 'ASI', 'DRC', 'Jefe de Departamento', 'Departamento de Computación', NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-07-19 15:07:48', '2019-05-15 18:48:13', NULL, 2),
(6, '69042408154', 'Milady', 'Vicenta', 'Macareño', 'Pérez', 'Milady Vicenta Macareño Pérez', 'milady-vicenta-macareno-perez', 'Milady Macareño', 'milady-macareno', '1969-04-24', 'F', 'milady.macareno@reduc.edu.cu', 'milady.macareno@gmail.com', '(32) 27-15-69', '(58) 63-88-07', 'doc', '69042408154-milady-vicenta-macareno-perez.png', 'a:1:{i:0;s:2:\"ru\";}', 'a:4:{i:0;s:3:\"cdr\";i:1;s:3:\"ctc\";i:2;s:3:\"fmc\";i:3;s:3:\"pcc\";}', 9, 'Alicia', 'Manuel', 'CAS', '65.00', '169.00', 'Pardos', 'Blanca', 'Rubio', NULL, 'cubana', 307, 'Camagüey', 'camaguey', NULL, 307, 'Camagüey', 'Camaguey', NULL, '3ra', NULL, 'Agramonte', 'Van Horne', '17 A', NULL, NULL, NULL, NULL, NULL, 'Jayamá', 'Jayamá', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Licenciada en Educ en Química', 'ASI', 'MSC', 'Profesora', 'Universidad de camaguey', '2011-05-06', '32266839', '825.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-13 14:01:19', '2019-05-15 18:48:13', 4, 4),
(7, '79072827182', 'Maikel', NULL, 'Pons', 'Giralt', 'Maikel  Pons Giralt', 'maikel-pons-giralt', 'Maikel Pons', 'maikel-pons', '1979-07-28', 'M', 'maikel.pons@reduc.edu.cu', NULL, '(32) 29-51-48', '(55) 27-98-05', 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:2:{i:0;s:3:\"cdr\";i:1;s:3:\"pcc\";}', 7, 'Lourdes', 'Román', 'CAS', NULL, '176.00', 'Pardos', 'Mulata', 'Negro', NULL, 'cubana', 307, 'Camagüey', 'Camagüey', NULL, 307, 'Camagüey', 'Camagüey', NULL, 'San Rafael', NULL, 'Vázquez', 'Serdeira', '715', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesor', 'Ciencias Militares', 'ASI', NULL, NULL, NULL, NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-20 14:43:26', '2019-05-15 18:48:13', NULL, 10),
(8, '80121215974', 'Evelyn', NULL, 'Márquez', 'Alvarez', 'Evelyn  Márquez Alvarez', 'evelyn-marquez-alvarez', 'Evelyn Márquez', 'evelyn-marquez', '1980-12-12', 'F', 'evelyn.marquez@reduc.edu.cu', NULL, '(32) 26-68-39', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:3:{i:0;s:3:\"cdr\";i:1;s:3:\"fmc\";i:2;s:3:\"pcc\";}', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cubana', 307, 'Camagüey', 'Florida', NULL, 307, 'Camagüey', 'Florida', NULL, 'Prolongación de Pasaje', NULL, 'Rivero', 'Abelardo', '814', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Marxismo', NULL, 'MSC', 'Jefa de Dpto Marxismo', NULL, NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-20 15:36:59', '2019-05-15 18:48:13', NULL, 10),
(9, '78110620877', 'Ethel', NULL, 'Ramírez', 'Velázquez', 'Ethel  Ramírez Velázquez', 'ethel-ramirez-velazquez', 'Ethel Ramírez', 'ethel-ramirez', '1978-11-06', 'F', 'ethel.ramirez@reduc.edu.cu', NULL, '(32) 27-41-82', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:2:{i:0;s:3:\"cdr\";i:1;s:3:\"fmc\";}', 2, 'María', 'Alberto', NULL, '60.00', '163.00', 'Pardos', 'Blanca', 'Castaño', NULL, 'cubana', 307, 'Camagüey', NULL, NULL, 307, 'Camagüey', 'Camagüey', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '50', '13', NULL, NULL, 'Julio A. Mella', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Psicología', 'AUX', 'MSC', NULL, NULL, NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-20 15:52:15', '2019-05-15 18:48:13', NULL, 10),
(10, '67062313656', 'Maricela', NULL, 'Alfonseca', 'Guerra', 'Maricela  Alfonseca Guerra', 'maricela-alfonseca-guerra', 'Maricela Alfonseca', 'maricela-alfonseca', '1967-06-23', 'F', 'maricela.alfonseca@reduc.edu.cu', NULL, '(32) 25-05-31', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:2:{i:0;s:3:\"cdr\";i:1;s:3:\"fmc\";}', 7, 'Dilcia', 'Rafael', 'SOL', '60.00', '159.00', 'Claros', 'Blanca', 'Rubio', NULL, 'cubana', 307, 'Granma', 'Manzanillo', NULL, 307, 'Camagüey', 'Camagüey', NULL, '5ta', NULL, 'Avenida Agramonte', 'Carretera Central', '3', NULL, NULL, NULL, NULL, NULL, NULL, 'Versalles', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Psicología', 'TIT', 'DRC', NULL, NULL, NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-20 16:06:20', '2019-05-15 18:48:13', NULL, 10),
(11, '63012608851', 'María', 'Victoria', 'González', 'Peña', 'María Victoria González Peña', 'maria-victoria-gonzalez-pena', 'María González', 'maria-gonzalez', '1963-01-26', 'F', 'maria.gonzalez@reduc.edu.cu', NULL, '(32) 28-59-13', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:3:{i:0;s:3:\"cdr\";i:1;s:3:\"fmc\";i:2;s:3:\"pcc\";}', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cubana', 307, 'Camagüey', 'Camagüey', NULL, 307, 'Camagüey', 'Camagüey', NULL, 'República', NULL, 'Línea', 'Francisquito', '559', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Sociología', 'TIT', 'DRC', NULL, NULL, NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-20 17:44:36', '2019-05-15 18:48:13', NULL, 10),
(12, '59020703695', 'Mirtha', 'Juliana', 'Yordi', 'García', 'Mirtha Juliana Yordi García', 'mirtha-juliana-yordi-garcia', 'Mirtha Yordi', 'mirtha-yordi', '1959-02-07', 'F', 'mirtha.yordi@reduc.edu.cu', NULL, '(32) 28-50-56', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:1:{i:0;s:3:\"fmc\";}', 7, 'Clara', NULL, 'SOL', '75.00', '170.00', NULL, NULL, NULL, NULL, 'cubana', 307, 'Camagüey', 'Camagüey', NULL, 307, 'Camagüey', 'Camagüey', NULL, 'Teresita', NULL, 'Antonio Varona', 'Final', '31', NULL, NULL, NULL, NULL, NULL, NULL, 'Montejo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Ciencias Sociales', 'TIT', 'DRC', NULL, NULL, NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-20 17:52:34', '2019-05-15 18:48:13', NULL, 10),
(13, '55111603558', 'María', 'Elena', 'Pulgares', 'Caro', 'María Elena Pulgares Caro', 'maria-elena-pulgares-caro', 'María Pulgares', 'maria-pulgares', '1955-11-16', 'F', 'maria.pulgares@reduc.edu.cu', NULL, '(32) 27-22-45', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:2:{i:0;s:3:\"fmc\";i:1;s:3:\"pcc\";}', 7, 'María', 'Evelio', 'CAS', NULL, '160.00', NULL, NULL, NULL, NULL, 'cubana', 307, 'Camagüey', 'Camagüey', NULL, 307, 'Camagüey', 'Camagüey', NULL, 'Olazábal', NULL, 'Capitán Frit', 'Acosta', '14', NULL, NULL, NULL, NULL, NULL, NULL, 'Marquesado', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Filosofía', 'AUX', 'MSC', NULL, NULL, NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-20 18:20:11', '2019-05-15 18:48:13', NULL, 10),
(14, '63102509373', 'Natacha', 'Diana', 'Rodríguez', 'Mozo', 'Natacha Diana Rodríguez Mozo', 'natacha-diana-rodriguez-mozo', 'Natacha Rodríguez', 'natacha-rodriguez', '1963-10-25', 'F', 'natacha.rodriguez@reduc.edu.cu', NULL, '(32) 28-18-93', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:3:{i:0;s:3:\"cdr\";i:1;s:3:\"fmc\";i:2;s:3:\"pcc\";}', 7, 'Margarita', 'Roberto', 'SOL', '51.00', '150.00', 'Pardos', 'Blanca', 'Negro', NULL, 'cubana', 307, 'Camagüey', 'Camagüey', NULL, 307, 'Camagüey', 'Camagüey', NULL, 'Santa Rosa', NULL, 'San Martín', 'San José', '92', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Ciencias Sociales', 'ASI', 'MSC', NULL, NULL, NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-20 18:28:45', '2019-05-15 18:48:13', NULL, 10),
(15, '78092720864', 'Yunior', NULL, 'Rodríguez', 'Rodríguez', 'Yunior  Rodríguez Rodríguez', 'yunior-rodriguez-rodriguez', 'Yunior Rodríguez', 'yunior-rodriguez', '1978-09-27', 'M', 'yunior.rodriguez@reduc.edu.cu', NULL, NULL, '(53) 52-65-19', 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:2:{i:0;s:3:\"cdr\";i:1;s:3:\"pcc\";}', 7, 'Milagros', 'Vicente', 'SOL', '110.00', '178.00', 'Pardos', 'Blanca', 'Castaño', NULL, 'cubana', 307, 'Camagüey', NULL, NULL, 307, 'Camagüey', 'Camagüey', NULL, '2da', NULL, 'D', 'Final', '94', NULL, NULL, NULL, NULL, NULL, NULL, 'Villa Mariana', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesor', 'Psicología', 'ASI', 'MSC', NULL, NULL, NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-20 18:36:16', '2019-05-15 18:48:13', NULL, 10),
(16, '58111606812', 'Marianela', 'Rosa', 'Parrado', 'Alvarez', 'Marianela Rosa Parrado Alvarez', 'marianela-rosa-parrado-alvarez', 'Marianela Parrado', 'marianela-parrado', '1958-11-16', 'F', 'marianela.parrado@reduc.edu.cu', NULL, '(32) 28-35-74', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:3:{i:0;s:3:\"cdr\";i:1;s:3:\"fmc\";i:2;s:3:\"pcc\";}', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cubana', 307, 'Camagüey', 'Camagüey', NULL, 307, 'Camagüey', 'Camagüey', NULL, 'Julio Sanguily', NULL, 'Joaquín de Aguero', 'Miguel Benavides', '303', NULL, NULL, NULL, NULL, NULL, NULL, 'La Vigía', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Sociología', 'AUX', 'MSC', NULL, NULL, NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-20 18:48:17', '2019-05-15 18:48:13', NULL, 10),
(17, '69123004772', 'Adela', 'María', 'García', 'Yero', 'Adela María García Yero', 'adela-maria-garcia-yero', 'Adela García', 'adela-garcia', '1969-12-30', 'F', 'adela.garcia@reduc.edu.cu', NULL, '(32) 26-68-39', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:3:{i:0;s:3:\"cdr\";i:1;s:3:\"fmc\";i:2;s:3:\"pcc\";}', 4, 'Olga', 'Alberto', 'CAS', NULL, '160.00', 'Pardos', 'Blanca', 'Castaño', NULL, 'cubana', 307, 'Camagüey', 'Camagüey', NULL, 307, 'Camagüey', 'Camagüey', NULL, 'Alfredo Adán', NULL, '4ta Paralela', '5ta Paralela', '659', NULL, NULL, NULL, NULL, NULL, NULL, 'La Vigía', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Arquitectura', 'TIT', 'DRC', NULL, NULL, NULL, '32262487', '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-21 12:09:48', '2019-05-15 18:48:13', NULL, 10),
(18, '92050541802', 'Alberto', NULL, 'Mancebo', 'Socarrás', 'Alberto  Mancebo Socarrás', 'alberto-mancebo-socarras', 'Alberto Mancebo', 'alberto-mancebo', '1992-05-05', 'M', 'alberto.mancebo@reduc.edu.cu', NULL, '(32) 28-56-75', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:2:{i:0;s:3:\"cdr\";i:1;s:3:\"ujc\";}', 4, 'Beatríz', 'Alberto', 'SOL', NULL, '175.00', 'Pardos', 'Blanca', 'Castaño', NULL, 'cubana', 307, 'Camagüey', 'Camagüey', NULL, 307, 'Camagüey', 'Camagüey', NULL, 'Julio Sanguily', NULL, '1ra', '2da Paralela', '503', NULL, NULL, NULL, NULL, NULL, NULL, 'Beneficencia', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesor', 'Arquitectura', 'INS', NULL, NULL, NULL, NULL, '32262487', '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-21 12:34:32', '2019-05-15 18:48:13', NULL, 10),
(19, '67040504118', 'Aymeé', NULL, 'Alonso', 'Gatell', 'Aymeé  Alonso Gatell', 'aymee-alonso-gatell', 'Aymeé Alonso', 'aymee-alonso', '1967-04-05', 'F', 'aymee.alonso@reduc.edu.cu', NULL, '(32) 26-11-71', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:3:{i:0;s:3:\"cdr\";i:1;s:3:\"fmc\";i:2;s:3:\"pcc\";}', 4, 'Genoveva', 'Aldo', 'CAS', NULL, '170.00', 'Claros', 'Blanca', 'Rubio', NULL, 'cubana', 307, 'Villa Clara', 'Santa Clara', NULL, 307, 'Camagüey', 'Camagüey', NULL, 'Alfredo Adán', NULL, 'Benavides', 'Julio Sanguily', NULL, NULL, '347', '4', NULL, NULL, NULL, 'La Vigía', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Arquitectura', 'TIT', 'DRC', NULL, NULL, NULL, '32262487', '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-21 13:51:14', '2019-05-15 18:48:13', NULL, 10),
(20, '78073120893', 'Annelis', NULL, 'Avalos', 'Acevedo', 'Annelis  Avalos Acevedo', 'annelis-avalos-acevedo', 'Annelis Avalos', 'annelis-avalos', '1978-07-31', 'F', 'anneli.avalos@reduc.edu.cu', NULL, '(32) 00-00-00', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:2:{i:0;s:3:\"cdr\";i:1;s:3:\"fmc\";}', NULL, 'Nancy', 'Miguel', 'CAS', NULL, '168.00', 'Pardos', 'Blanca', 'Castaño', NULL, 'cubana', 307, 'La Habana', 'Marianao', NULL, 307, 'Camagüey', 'Camagüey', NULL, '2da', NULL, '1ra', '3ra', '33', NULL, NULL, NULL, NULL, NULL, NULL, 'La Gloria', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Contabilidad', 'ASI', 'MSC', NULL, NULL, NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-21 15:08:05', '2019-05-15 18:48:13', NULL, 10),
(21, '62063004637', 'Georgina', 'Marcia', 'Soto', 'Senra', 'Georgina Marcia Soto Senra', 'georgina-marcia-soto-senra', 'Georgina Soto', 'georgina-soto', '1962-06-30', 'F', 'georgina.sotosenra@reduc.edu.cu', NULL, '(32) 26-47-38', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:3:{i:0;s:3:\"cdr\";i:1;s:3:\"fmc\";i:2;s:3:\"pcc\";}', 7, 'Georgina', 'Virginio', 'CAS', NULL, '157.00', 'Pardos', 'Blanca', 'Castaño', NULL, 'cubana', 307, 'Camagüey', 'Camagüey', NULL, 307, 'Camagüey', 'Camagüey', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'H', NULL, NULL, NULL, 'Paco Borrero', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Derecho', 'TIT', 'DRC', NULL, NULL, NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-21 15:16:23', '2019-05-15 18:48:13', NULL, 10),
(22, '56030906832', 'Eva', 'Catalina', 'Perón', 'Delgado', 'Eva Catalina Perón Delgado', 'eva-catalina-peron-delgado', 'Eva Perón', 'eva-peron', '1956-03-09', 'F', 'eva.peron@reduc.edu.cu', NULL, '(32) 29-53-90', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:3:{i:0;s:3:\"cdr\";i:1;s:3:\"fmc\";i:2;s:3:\"pcc\";}', 2, 'Carmen', 'Natalio', 'DIV', NULL, '165.00', 'Pardos', 'Blanca', 'Canoso', NULL, 'cubana', 307, 'Camagüey', 'Camagüey', NULL, 307, 'Camagüey', 'Camagüey', NULL, 'San José', NULL, 'San Ramón', 'Industria', '576', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Economía', 'TIT', 'DRC', NULL, NULL, NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-21 15:41:41', '2019-05-15 18:48:13', NULL, 10),
(23, '59021507555', 'María', 'Elena', 'Betancourt', 'García', 'María Elena Betancourt García', 'maria-elena-betancourt-garcia', 'María Betancourt', 'maria-betancourt', '1959-02-15', 'F', 'maria.betancourt@reduc.edu.cu', NULL, '(32) 25-16-97', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:3:{i:0;s:3:\"cdr\";i:1;s:3:\"fmc\";i:2;s:3:\"pcc\";}', 2, 'Mirella', 'Pedro', 'DIV', NULL, '165.00', 'Claros', 'Blanca', 'Castaño', NULL, 'cubana', 307, 'Camagüey', 'Camagüey', NULL, 307, 'Camagüey', 'Camagüey', NULL, '15', NULL, 'Lucas', '13', '203', NULL, NULL, NULL, NULL, NULL, NULL, 'Previsora', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Contabilidad', 'AUX', 'DRC', NULL, NULL, NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-21 16:04:47', '2019-05-15 18:48:13', NULL, 10),
(24, '59101907354', 'Angela', 'Laura', 'Palacios', 'Hidalgo', 'Angela Laura Palacios Hidalgo', 'angela-laura-palacios-hidalgo', 'Angela Palacios', 'angela-palacios', '1959-10-19', 'F', 'angela.palacios@reduc.edu.cu', NULL, '(32) 24-26-64', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:3:{i:0;s:3:\"cdr\";i:1;s:3:\"fmc\";i:2;s:3:\"pcc\";}', 2, 'Irma', 'Luis', 'CAS', NULL, '158.00', 'Pardos', 'Blanca', 'Canoso', NULL, 'cubana', 307, 'Camagüey', 'Camagüey', NULL, 307, 'Camagüey', 'Camagüey', NULL, 'Cuba', NULL, 'Pancha Agramonte', 'Domingo Puentes', '224', NULL, NULL, NULL, NULL, NULL, NULL, 'La Caridad', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Administración', 'AUX', 'DRC', NULL, NULL, NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-21 16:12:33', '2019-05-15 18:48:13', NULL, 10),
(25, '81081615837', 'Ania', NULL, 'Deniz', 'Cruz', 'Ania  Deniz Cruz', 'ania-deniz-cruz', 'Ania Deniz', 'ania-deniz', '1981-08-16', 'F', 'ania.deniz@reduc.edu.cu', NULL, '(32) 29-44-93', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:2:{i:0;s:3:\"cdr\";i:1;s:3:\"fmc\";}', 2, 'Mérida', 'Juan', 'DIV', NULL, '165.00', 'Pardos', 'Blanca', 'Negro', NULL, 'cubana', 307, 'Camagüey', 'Camagüey', NULL, 307, 'Camagüey', 'Camagüey', NULL, 'San Martín', NULL, 'Industria', 'Lugareño', '676-A', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Economía', 'ASI', 'MSC', NULL, NULL, NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-21 16:20:17', '2019-05-15 18:48:13', NULL, 10),
(26, '64061108328', 'Héctor', NULL, 'Rodríguez', 'Pérez', 'Héctor  Rodríguez Pérez', 'hector-rodriguez-perez', 'Héctor Rodríguez', 'hector-rodriguez', '1964-06-11', 'M', 'hector.rodriguez@reduc.edu.cu', NULL, '(32) 00-00-00', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:1:{i:0;s:3:\"cdr\";}', 2, 'Dora', 'Héctor', NULL, NULL, '175.00', 'Negros', 'Blanca', 'Canoso', NULL, 'cubana', 307, 'Camagüey', NULL, NULL, 307, 'Camagüey', 'Camagüey', NULL, 'Santa Rosa', NULL, 'Línea', 'San José', '129', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesor', 'Contabilidad y Finanzas', NULL, NULL, NULL, 'Universidad de Camagüey', NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-21 16:28:09', '2019-05-15 18:48:13', NULL, 10),
(27, '89100334235', 'Dianelis', NULL, 'Falls', 'Valdivieso', 'Dianelis  Falls Valdivieso', 'dianelis-falls-valdivieso', 'Dianelis Falls', 'dianelis-falls', '1989-10-03', 'F', 'dianelis.falls@reduc.edu.cu', NULL, '(32) 25-84-60', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:3:{i:0;s:3:\"cdr\";i:1;s:3:\"fmc\";i:2;s:3:\"ujc\";}', 4, 'Madelaine', 'Luis Daniel', 'CAS', '47.00', '153.00', 'Pardos', 'Blanca', 'Castaño', NULL, 'cubana', 307, 'Camagüey', 'Camaguey', NULL, 307, 'Camagüey', 'Camaguey', NULL, 'Matias Varona', NULL, 'Cisnero', 'Matias Varona', '105', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Arquitectura', 'INS', NULL, '32 262487', 'Facultad de Construcciones', NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-21 16:41:18', '2019-05-15 18:48:13', NULL, 11),
(28, '47082804192', 'Iris', 'María', 'González', 'Torres', 'Iris María González Torres', 'iris-maria-gonzalez-torres', 'Iris González', 'iris-gonzalez', '1947-08-28', 'F', 'iris.gonzalez@reduc.edu.cu', NULL, '(32) 00-00-00', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:3:{i:0;s:3:\"cdr\";i:1;s:3:\"fmc\";i:2;s:3:\"pcc\";}', 2, 'Adelaida', 'Juan', 'DIV', NULL, '173.00', 'Pardos', 'Blanca', 'Canoso', NULL, 'cubana', 307, 'Camagüey', 'Camagüey', NULL, 307, 'Camagüey', 'Camagüey', NULL, 'Avellaneda', NULL, 'San Esteban', 'San Martín', '276', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Economía', 'TIT', 'DRC', NULL, 'Universidad de Camagüey', NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-21 16:49:44', '2019-05-15 18:48:13', NULL, 10),
(29, '79092915191', 'Cristina', NULL, 'Balbis', 'Iraola', 'Cristina  Balbis Iraola', 'cristina-balbis-iraola', 'Cristina Balbis', 'cristina-balbis', '1979-09-29', 'F', 'cristina.balbis@reduc.edu.cu', NULL, '(32) 26-14-83', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:3:{i:0;s:3:\"cdr\";i:1;s:3:\"ctc\";i:2;s:3:\"pcc\";}', 4, 'Silvia', 'Carlos', 'SOL', '90.00', '170.00', 'Pardos', 'Blanca', 'Castaño', NULL, 'cubana', 307, 'Camagüey', 'Camaguey', NULL, 307, 'Camagüey', 'Camaguey', NULL, 'Ave Varona', NULL, '5ta', '6ta', '105', NULL, NULL, NULL, NULL, NULL, NULL, 'Puerto Príncipe', '70 800', NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Arquitectura', 'ASI', 'MSC', 'Jefe de disciplina', 'Facultad de Construcciones', NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-21 17:00:19', '2019-05-15 18:48:13', NULL, 11),
(30, '79072015222', 'Oliek', NULL, 'González', 'Solán', 'Oliek  González Solán', 'oliek-gonzalez-solan', 'Oliek González', 'oliek-gonzalez', '1979-07-20', 'M', 'oliek.gonzalez@reduc.edu.cu', NULL, '(32) 24-24-92', NULL, 'doc', NULL, 'a:1:{i:0;s:6:\"es_419\";}', 'a:2:{i:0;s:3:\"cdr\";i:1;s:3:\"pcc\";}', 2, 'Rafaela', 'Ramón', 'CAS', NULL, '174.00', 'Claros', 'Blanca', 'Castaño', NULL, 'cubana', 307, 'Camagüey', 'Camagüey', NULL, 307, 'Camagüey', 'Camagüey', NULL, 'Industria', NULL, 'San José', 'San Martín', '110', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesor', 'Economía', 'TIT', 'DRC', 'Decano', 'Universidad de Camagüey', NULL, NULL, '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-21 17:04:01', '2019-05-15 18:48:13', NULL, 10),
(31, '56050606884', 'Elio', 'Rosendo', 'Pérez', 'Ramírez', 'Elio Rosendo Pérez Ramírez', 'elio-rosendo-perez-ramirez', 'Elio Pérez', 'elio-perez', '1956-05-06', 'M', 'elio.perez@reduc.edu.cu', NULL, '(32) 26-21-69', NULL, 'doc', NULL, 'a:1:{i:0;s:2:\"es\";}', 'a:2:{i:0;s:3:\"cdr\";i:1;s:3:\"pcc\";}', 4, 'María', 'Elio', 'CAS', NULL, '171.00', 'Pardos', 'Blanca', 'Canoso', NULL, 'cubana', 307, 'Camagüey', 'Camagüey', NULL, 307, 'Camagüey', 'Camagüey', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3-A', '19', NULL, NULL, NULL, 'Puerto Príncipe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profesor', 'Arquitectura', 'TIT', 'DRC', NULL, 'Universidad de Camagüey', NULL, '32262487', '0.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-09-21 17:13:00', '2019-05-15 18:48:13', NULL, 10),
(32, '83122315855', 'Yainerys', 'Estrella', 'Martínez', 'Fernández', 'Yainerys Estrella Martínez Fernández', 'yainerys-estrella-martinez-fernandez', 'Yainerys Martínez', 'yainerys-martinez', '1983-12-23', 'F', 'yainerys.martinez@reduc.edu.cu', NULL, '(32) 27-29-62', '(55) 76-52-98', 'doc', NULL, 'a:2:{i:0;s:2:\"es\";i:1;s:2:\"en\";}', 'a:3:{i:0;s:3:\"cdr\";i:1;s:3:\"fmc\";i:2;s:3:\"ujc\";}', 9, 'Nancy', 'Alexis', 'SOL', '60.00', '175.00', 'Negros', 'Negra', 'Negro', NULL, 'cubana', 307, 'Camagüey', 'Camagüey', NULL, 307, 'Camagüey', 'Camagüey', NULL, 'Avenida A', NULL, NULL, NULL, NULL, NULL, '1', '14', NULL, NULL, NULL, 'Jayamá', '70 600', NULL, NULL, NULL, NULL, NULL, NULL, 'Profesora', 'Ing. Química', 'INS', 'ING', NULL, NULL, NULL, NULL, '590.00', NULL, '2019-05-15 18:48:13', NULL, 1, 0, 0, NULL, '2018-11-07 15:17:06', '2019-05-15 18:48:13', 4, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ext_application`
--

DROP TABLE IF EXISTS `ext_application`;
CREATE TABLE IF NOT EXISTS `ext_application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) DEFAULT NULL,
  `proposed_client_id` int(11) DEFAULT NULL,
  `number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `number_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `in_plan` tinyint(1) NOT NULL,
  `lapsed` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `exit_date` date NOT NULL,
  `arrival_date` date NOT NULL,
  `directive_substitute` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `goe_substitute` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pcc_approval` tinyint(1) DEFAULT NULL,
  `pcc_approval_date` date DEFAULT NULL,
  `vri_approval` tinyint(1) DEFAULT NULL,
  `vri_approval_date` date DEFAULT NULL,
  `rs_approval` tinyint(1) DEFAULT NULL,
  `rs_approval_date` date DEFAULT NULL,
  `os_approval` tinyint(1) DEFAULT NULL,
  `os_approval_date` date DEFAULT NULL,
  `state` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `agreement` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `approval_date` datetime DEFAULT NULL,
  `approval_observations` longtext COLLATE utf8_unicode_ci,
  `reject_date` datetime DEFAULT NULL,
  `reject_reason` longtext COLLATE utf8_unicode_ci,
  `closed` tinyint(1) DEFAULT NULL,
  `used` tinyint(1) DEFAULT NULL,
  `command_file_id` int(11) DEFAULT NULL,
  `manager_travel_plan_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `digital_copy` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8422EABE96901F54` (`number`),
  UNIQUE KEY `UNIQ_8422EABEDA5950D7` (`number_slug`),
  UNIQUE KEY `UNIQ_8422EABE954B9FA3` (`manager_travel_plan_id`),
  UNIQUE KEY `UNIQ_8422EABE7CA12A8F` (`command_file_id`),
  KEY `IDX_8422EABE19EB6921` (`client_id`),
  KEY `IDX_8422EABE957A40DD` (`proposed_client_id`),
  KEY `IDX_8422EABE85564492` (`create_user_id`),
  KEY `IDX_8422EABEE0DFCA6C` (`update_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ext_command_file`
--

DROP TABLE IF EXISTS `ext_command_file`;
CREATE TABLE IF NOT EXISTS `ext_command_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ipw_actions` longtext COLLATE utf8_unicode_ci NOT NULL,
  `mwo_actions` longtext COLLATE utf8_unicode_ci NOT NULL,
  `itt_actions` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ext_departure`
--

DROP TABLE IF EXISTS `ext_departure`;
CREATE TABLE IF NOT EXISTS `ext_departure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `number_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `application_id` int(11) DEFAULT NULL,
  `passport_id` int(11) DEFAULT NULL,
  `passport_delivery` date DEFAULT NULL,
  `departure_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `passport_collection` date DEFAULT NULL,
  `observations` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `results` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `closed` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9BEAF6AE96901F54` (`number`),
  UNIQUE KEY `UNIQ_9BEAF6AEDA5950D7` (`number_slug`),
  UNIQUE KEY `UNIQ_9BEAF6AE3E030ACD` (`application_id`),
  KEY `IDX_9BEAF6AE19EB6921` (`client_id`),
  KEY `IDX_9BEAF6AEABF410D0` (`passport_id`),
  KEY `IDX_9BEAF6AE85564492` (`create_user_id`),
  KEY `IDX_9BEAF6AEE0DFCA6C` (`update_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ext_economic`
--

DROP TABLE IF EXISTS `ext_economic`;
CREATE TABLE IF NOT EXISTS `ext_economic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `source` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `exit_manager_travel_plan_id` int(11) DEFAULT NULL,
  `mission_id` int(11) DEFAULT NULL,
  `event_acount` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_63B716AABCB727D8` (`exit_manager_travel_plan_id`),
  KEY `IDX_63B716AABE6CAE90` (`mission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ext_manager_travel_plan`
--

DROP TABLE IF EXISTS `ext_manager_travel_plan`;
CREATE TABLE IF NOT EXISTS `ext_manager_travel_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `number_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `objetives` longtext COLLATE utf8_unicode_ci NOT NULL,
  `departure_date` date NOT NULL,
  `lapsed` int(11) NOT NULL,
  `state` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `approval` tinyint(1) NOT NULL,
  `approval_date` datetime DEFAULT NULL,
  `approval_observations` longtext COLLATE utf8_unicode_ci,
  `reject` tinyint(1) NOT NULL,
  `reject_date` datetime DEFAULT NULL,
  `reject_reason` longtext COLLATE utf8_unicode_ci,
  `closed` tinyint(1) DEFAULT NULL,
  `used` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `canceled` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E605CB8D96901F54` (`number`),
  UNIQUE KEY `UNIQ_E605CB8DDA5950D7` (`number_slug`),
  KEY `IDX_E605CB8D19EB6921` (`client_id`),
  KEY `IDX_E605CB8D85564492` (`create_user_id`),
  KEY `IDX_E605CB8DE0DFCA6C` (`update_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ext_manager_travel_plans_countries`
--

DROP TABLE IF EXISTS `ext_manager_travel_plans_countries`;
CREATE TABLE IF NOT EXISTS `ext_manager_travel_plans_countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manager_travel_plan_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CC3543BF954B9FA3` (`manager_travel_plan_id`),
  KEY `IDX_CC3543BFF92F3E70` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ext_mission`
--

DROP TABLE IF EXISTS `ext_mission`;
CREATE TABLE IF NOT EXISTS `ext_mission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) DEFAULT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `application_id` int(11) DEFAULT NULL,
  `province_country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `institution` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `person_who_invites_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `person_who_invites_position` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `from_date` date NOT NULL,
  `until_date` date NOT NULL,
  `time_amount` int(11) NOT NULL,
  `concept` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `objetives` longtext COLLATE utf8_unicode_ci NOT NULL,
  `work_plan_synthesis` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `monthly_pay` decimal(10,2) DEFAULT NULL,
  `total_pay` decimal(10,2) DEFAULT NULL,
  `approved` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_83D8100BF92F3E70` (`country_id`),
  KEY `IDX_83D8100B85564492` (`create_user_id`),
  KEY `IDX_83D8100BE0DFCA6C` (`update_user_id`),
  KEY `IDX_83D8100B3E030ACD` (`application_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fst_postgraduate`
--

DROP TABLE IF EXISTS `fst_postgraduate`;
CREATE TABLE IF NOT EXISTS `fst_postgraduate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `created_user_id` int(11) DEFAULT NULL,
  `updated_user_id` int(11) DEFAULT NULL,
  `ci` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `names` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_names` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `full_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `full_name_slug` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `foreign_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cell_phone` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passport_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `course_type` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `short_course` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entry_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_769833FF3B67F367` (`ci`),
  UNIQUE KEY `UNIQ_769833FFE7927C74` (`email`),
  UNIQUE KEY `UNIQ_769833FF4EF9AAC4` (`passport_number`),
  UNIQUE KEY `UNIQ_769833FF472B0CD0` (`foreign_email`),
  UNIQUE KEY `UNIQ_769833FF16DB4F89` (`picture`),
  KEY `IDX_769833FFF92F3E70` (`country_id`),
  KEY `IDX_769833FF591CC992` (`course_id`),
  KEY `IDX_769833FFE104C1D3` (`created_user_id`),
  KEY `IDX_769833FFBB649746` (`updated_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fst_undergraduate`
--

DROP TABLE IF EXISTS `fst_undergraduate`;
CREATE TABLE IF NOT EXISTS `fst_undergraduate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ci` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `names` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_names` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `full_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `full_name_slug` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `foreign_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cell_phone` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `passport_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `career_id` int(11) DEFAULT NULL,
  `entry_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `year` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_user_id` int(11) DEFAULT NULL,
  `updated_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_14BDDAE63B67F367` (`ci`),
  UNIQUE KEY `UNIQ_14BDDAE6E7927C74` (`email`),
  UNIQUE KEY `UNIQ_14BDDAE64EF9AAC4` (`passport_number`),
  UNIQUE KEY `UNIQ_14BDDAE6472B0CD0` (`foreign_email`),
  UNIQUE KEY `UNIQ_14BDDAE616DB4F89` (`picture`),
  KEY `IDX_14BDDAE6F92F3E70` (`country_id`),
  KEY `IDX_14BDDAE6B58CDA09` (`career_id`),
  KEY `IDX_14BDDAE6E104C1D3` (`created_user_id`),
  KEY `IDX_14BDDAE6BB649746` (`updated_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pas_application`
--

DROP TABLE IF EXISTS `pas_application`;
CREATE TABLE IF NOT EXISTS `pas_application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `number_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `reason` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `application_date` date NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `passport_type` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `application_type` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `organ` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `travel_reason` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `send_date` datetime DEFAULT NULL,
  `confirm_date` datetime DEFAULT NULL,
  `reject_date` datetime DEFAULT NULL,
  `reject_reasons` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `closed` tinyint(1) DEFAULT NULL,
  `used` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_46500E0E96901F54` (`number`),
  UNIQUE KEY `UNIQ_46500E0EDA5950D7` (`number_slug`),
  KEY `IDX_46500E0E19EB6921` (`client_id`),
  KEY `IDX_46500E0E85564492` (`create_user_id`),
  KEY `IDX_46500E0EE0DFCA6C` (`update_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pas_control`
--

DROP TABLE IF EXISTS `pas_control`;
CREATE TABLE IF NOT EXISTS `pas_control` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `number_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `passport_id` int(11) DEFAULT NULL,
  `delivery_date` date NOT NULL,
  `receives_client_id` int(11) DEFAULT NULL,
  `pick_up_date` date DEFAULT NULL,
  `delivers_client_id` int(11) DEFAULT NULL,
  `closed` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9E1F611E96901F54` (`number`),
  UNIQUE KEY `UNIQ_9E1F611EDA5950D7` (`number_slug`),
  KEY `IDX_9E1F611EABF410D0` (`passport_id`),
  KEY `IDX_9E1F611E85564492` (`create_user_id`),
  KEY `IDX_9E1F611EE0DFCA6C` (`update_user_id`),
  KEY `IDX_9E1F611E158A1589` (`receives_client_id`),
  KEY `IDX_9E1F611EBF217D37` (`delivers_client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pas_passport`
--

DROP TABLE IF EXISTS `pas_passport`;
CREATE TABLE IF NOT EXISTS `pas_passport` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `number_slug` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `type` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `first_page` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_extension` tinyint(1) DEFAULT NULL,
  `first_extension_date` date DEFAULT NULL,
  `second_extension` tinyint(1) DEFAULT NULL,
  `second_extension_date` date DEFAULT NULL,
  `drop_passport` tinyint(1) DEFAULT NULL,
  `drop_date` date DEFAULT NULL,
  `drop_reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_ci` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `closed` tinyint(1) DEFAULT NULL,
  `in_store` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_AED00F3E96901F54` (`number`),
  UNIQUE KEY `UNIQ_AED00F3EDA5950D7` (`number_slug`),
  UNIQUE KEY `UNIQ_AED00F3E3E030ACD` (`application_id`),
  KEY `IDX_AED00F3E19EB6921` (`client_id`),
  KEY `IDX_AED00F3E85564492` (`create_user_id`),
  KEY `IDX_AED00F3EE0DFCA6C` (`update_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=369 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `pas_passport`
--

INSERT INTO `pas_passport` (`id`, `number`, `number_slug`, `application_id`, `client_id`, `issue_date`, `expiry_date`, `type`, `first_page`, `first_extension`, `first_extension_date`, `second_extension`, `second_extension_date`, `drop_passport`, `drop_date`, `drop_reason`, `client_ci`, `closed`, `in_store`, `created_at`, `updated_at`, `create_user_id`, `update_user_id`) VALUES
(1, 'E252974', 'e252974', NULL, NULL, '2014-02-18', '2020-02-18', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '39111103532', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(2, 'E221736', 'e221736', NULL, NULL, '2013-06-20', '2019-06-20', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '42050406696', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(3, 'E211569', 'e211569', NULL, NULL, '2013-08-01', '2019-08-01', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '42102903881', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(4, 'E282318', 'e282318', NULL, NULL, '2014-12-04', '2020-12-04', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '44010203983', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(5, 'E378672', 'e378672', NULL, NULL, '2017-09-20', '2023-09-20', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '46071701308', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(6, 'X012362', 'x012362', NULL, NULL, '2012-10-05', '2018-10-05', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '46071701308', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, 2),
(7, 'E284627', 'e284627', NULL, NULL, '2015-01-16', '2021-01-13', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '46081606649', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(8, 'E282279', 'e282279', NULL, NULL, '2014-12-04', '2020-12-04', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '46091205754', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(9, 'E315897', 'e315897', NULL, 28, '2015-11-02', '2021-11-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '47082804192', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(10, 'E387290', 'e387290', NULL, NULL, '2018-02-21', '2024-02-21', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '47102105527', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(11, 'E383612', 'e383612', NULL, NULL, '2017-12-08', '2023-12-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '48091204308', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(12, 'E205148', 'e205148', NULL, NULL, '2013-03-08', '2019-03-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '49060204065', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(13, 'E306226', 'e306226', NULL, NULL, '2015-07-20', '2021-07-20', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '50030702349', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(14, 'E290234', 'e290234', NULL, NULL, '2015-03-09', '2021-03-09', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '50050300206', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(15, 'E290235', 'e290235', NULL, NULL, '2015-03-09', '2021-03-09', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '50051004049', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(16, 'E218937', 'e218937', NULL, NULL, '2013-07-11', '2019-07-11', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '50102903970', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(17, 'E266490', 'e266490', NULL, NULL, '2017-05-20', '2020-05-20', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '50110407140', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(18, 'E286560', 'e286560', NULL, NULL, '2015-02-02', '2021-02-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '51022004100', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(19, 'I832255', 'i832255', NULL, NULL, '2015-11-02', '2021-11-02', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '51091706397', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(20, 'I832253', 'i832253', NULL, NULL, '2015-11-02', '2021-11-02', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '52012407038', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(21, 'E387543', 'e387543', NULL, NULL, '2018-02-23', '2024-02-23', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '52032903622', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(22, 'E326094', 'e326094', NULL, NULL, '2016-03-22', '2022-03-22', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '52070405317', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(23, 'E386029', 'e386029', NULL, NULL, '2018-02-06', '2024-02-06', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '52072504060', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(24, 'E380984', 'e380984', NULL, NULL, '2017-09-06', '2023-09-06', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '52110606734', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(25, 'E348292', 'e348292', NULL, NULL, '2017-01-30', '2023-01-30', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '52123002046', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(26, 'E355237', 'e355237', NULL, NULL, '2016-03-09', '2022-03-09', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '53020400609', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(27, 'E366461', 'e366461', NULL, NULL, '2017-06-02', '2023-06-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '53030703960', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(28, 'E326304', 'e326304', NULL, NULL, '2016-03-23', '2020-03-23', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '53053106543', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(29, 'E205041', 'e205041', NULL, NULL, '2013-03-08', '2019-03-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '53060706825', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(30, 'E218588', 'e218588', NULL, NULL, '2013-04-30', '2019-04-30', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '53061307331', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(31, 'E207740', 'e207740', NULL, NULL, '2013-02-26', '2019-02-26', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '53071207258', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(32, 'E325330', 'e325330', NULL, NULL, '2016-03-10', '2022-03-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '54042026501', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(33, 'E224610', 'e224610', NULL, NULL, '2013-09-06', '2019-09-06', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '54050107303', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(34, 'E306759', 'e306759', NULL, NULL, '2015-07-22', '2021-07-22', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '54050907101', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(35, 'E279615', 'e279615', NULL, NULL, '2014-11-03', '2020-11-03', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '54080407505', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(36, 'E211165', 'e211165', NULL, NULL, '2013-07-03', '2019-07-03', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '54090726341', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(37, 'E360664', 'e360664', NULL, NULL, '2017-03-16', '2023-03-16', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '54092404911', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(38, 'E312000', 'e312000', NULL, NULL, '2015-09-16', '2021-09-16', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '54100507300', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(39, 'E212834', 'e212834', NULL, NULL, '2013-07-22', '2019-07-22', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '54102004073', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(40, 'E312973', 'e312973', NULL, NULL, '2015-09-28', '2021-09-28', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '54120207075', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(41, 'E288364', 'e288364', NULL, NULL, '2015-02-27', '2021-02-27', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '55031306905', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(42, 'E382475', 'e382475', NULL, NULL, '2017-10-24', '2023-10-24', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '55062203588', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(43, 'I267889', 'i267889', NULL, NULL, '2014-03-14', '2020-03-14', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '55081507022', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(44, 'E254851', 'e254851', NULL, NULL, '2014-02-24', '2020-02-24', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '55092907366', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(45, 'E299383', 'e299383', NULL, 13, '2015-05-08', '2021-05-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '55111603558', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(46, 'E209473', 'e209473', NULL, NULL, '2013-05-08', '2019-05-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '55111806627', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(47, 'E354526', 'e354526', NULL, NULL, '2018-04-06', '2024-04-06', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '56010703581', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(48, 'E320829', 'e320829', NULL, NULL, '2016-01-13', '2022-01-13', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '56021414747', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(49, 'E363016', 'e363016', NULL, NULL, '2017-04-21', '2023-04-21', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '56022207012', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(50, 'E234683', 'e234683', NULL, NULL, '2013-09-10', '2019-09-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '56032106810', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(51, 'I102639', 'i102639', NULL, NULL, '2013-04-26', '2019-04-26', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '56042003713', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(52, 'E360724', 'e360724', NULL, 31, '2017-03-16', '2023-03-16', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '56050606884', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(53, 'E332970', 'e332970', NULL, NULL, '2016-06-07', '2022-06-07', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '56082407282', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(54, 'E205711', 'e205711', NULL, NULL, '2013-03-06', '2019-03-06', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '56092404189', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(55, 'E306623', 'e306623', NULL, NULL, '2015-07-22', '2021-07-22', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '56100206732', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(56, 'E312421', 'e312421', NULL, NULL, '2015-09-16', '2021-09-16', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '56110406928', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(57, 'E383024', 'e383024', NULL, NULL, '2017-11-06', '2023-11-06', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '56111013964', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(58, 'E312403', 'e312403', NULL, NULL, '2015-09-16', '2021-09-16', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '56111607268', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(59, 'E332822', 'e332822', NULL, NULL, '2016-06-03', '2022-06-03', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '56121706242', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(60, 'I597605', 'i597605', NULL, NULL, '2015-03-31', '2021-03-31', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '56121706242', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(61, 'E290309', 'e290309', NULL, NULL, '2015-03-09', '2021-03-09', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '56122402215', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(62, 'E205640', 'e205640', NULL, NULL, '2013-03-06', '2019-03-06', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '57050704013', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(63, 'E205046', 'e205046', NULL, NULL, '2013-03-08', '2019-03-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '57061106838', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(64, 'E211799', 'e211799', NULL, NULL, '2013-07-16', '2019-07-16', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '57062507249', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(65, 'E285467', 'e285467', NULL, NULL, '2015-01-22', '2021-01-22', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '57071400616', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(66, 'E208852', 'e208852', NULL, NULL, '2013-06-17', '2019-06-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '57100514231', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(67, 'E361017', 'e361017', NULL, NULL, '2017-03-22', '2023-03-22', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '57103003881', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(68, 'E392560', 'e392560', NULL, NULL, '2017-07-07', '2023-07-07', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '57122303907', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(69, 'E356491', 'e356491', NULL, NULL, '2018-04-26', '2024-04-26', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '58012903703', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(70, 'E331713', 'e331713', NULL, NULL, '2016-06-08', '2022-06-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '58061903996', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(71, 'E209093', 'e209093', NULL, NULL, '2013-05-08', '2019-05-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '58072206975', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(72, 'E305865', 'e305865', NULL, NULL, '2015-07-17', '2021-07-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '58081106970', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(73, 'E209348', 'e209348', NULL, NULL, '2013-05-08', '2019-05-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '58082810483', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(74, 'I104975', 'i104975', NULL, NULL, '2013-03-25', '2019-03-28', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '58092604185', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(75, 'E366522', 'e366522', NULL, NULL, '2017-06-06', '2023-06-06', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '58093003837', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(76, 'E387540', 'e387540', NULL, 16, '2015-02-23', '2024-02-23', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '58111606812', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(77, 'E254934', 'e254934', NULL, NULL, '2014-02-27', '2020-02-27', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '58112403754', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(78, 'E209092', 'e209092', NULL, NULL, '2015-05-08', '2019-05-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '58112607112', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(79, 'E227376', 'e227376', NULL, 12, '2013-09-10', '2019-09-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '59020703695', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(80, 'E299385', 'e299385', NULL, 23, '2015-05-08', '2021-05-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '59021507555', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(81, 'E205071', 'e205071', NULL, NULL, '2013-03-08', '2019-03-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '59042910519', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(82, 'E221887', 'e221887', NULL, NULL, '2013-06-21', '2019-06-21', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '59052902461', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(83, 'E383022', 'e383022', NULL, NULL, '2017-11-06', '2023-11-06', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '59062113852', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(84, 'E325329', 'e325329', NULL, NULL, '2016-03-10', '2022-03-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '59080606992', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(85, 'E331706', 'e331706', NULL, NULL, '2016-06-08', '2022-06-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '59092304182', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(86, 'E230764', 'e230764', NULL, NULL, '2013-09-13', '2019-09-13', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '59100504763', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(87, 'E330571', 'e330571', NULL, 24, '2016-05-10', '2022-05-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '59101907354', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(88, 'E349552', 'e349552', NULL, NULL, '2017-01-09', '2023-01-09', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '59112700317', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(89, 'E286638', 'e286638', NULL, NULL, '2015-02-02', '2021-02-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '59112904149', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(90, 'E317892', 'e317892', NULL, NULL, '2015-11-30', '2021-11-30', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '60010804331', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(91, 'I635708', 'i635708', NULL, NULL, '2015-05-05', '2021-05-05', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '60010804331', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(92, 'E286919', 'e286919', NULL, NULL, '2015-02-06', '2021-02-06', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '60012404022', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(93, 'I832254', 'i832254', NULL, NULL, '2015-11-02', '2021-11-02', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '60020204533', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(94, 'E362068', 'e362068', NULL, NULL, '2017-03-10', '2023-03-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '60021207908', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(95, 'E286649', 'e286649', NULL, NULL, '2015-02-02', '2021-02-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '60041012446', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(96, 'E325236', 'e325236', NULL, NULL, '2016-03-09', '2022-03-09', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '60041300310', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(97, 'J615479', 'j615479', NULL, NULL, '2017-09-06', '2023-09-06', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '60041300310', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(98, 'E286646', 'e286646', NULL, NULL, '2015-02-02', '2021-02-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '60050904197', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(99, 'E362072', 'e362072', NULL, NULL, '2017-04-10', '2023-04-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '60071704447', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(100, 'E366457', 'e366457', NULL, NULL, '2017-06-02', '2023-06-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '60111004684', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(101, 'E225859', 'e225859', NULL, NULL, '2014-07-17', '2020-07-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '60121803218', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(102, 'E344784', 'e344784', NULL, NULL, '2016-10-28', '2022-10-28', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '61011107692', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(103, 'E274272', 'e274272', NULL, NULL, '2014-09-17', '2020-09-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '61011415485', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(104, 'E392790', 'e392790', NULL, NULL, '2017-07-13', '2023-09-13', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '61022608180', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(105, 'E324539', 'e324539', NULL, NULL, '2016-02-29', '2022-02-28', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '61031104614', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(106, 'E360240', 'e360240', NULL, NULL, '2017-03-14', '2023-03-14', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '61041415716', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(107, 'E285472', 'e285472', NULL, NULL, '2015-01-22', '2021-01-22', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '61061107927', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(108, 'E340805', 'e340805', NULL, NULL, '2016-09-21', '2022-09-21', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '61061807446', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(109, 'E274466', 'e274466', NULL, NULL, '2014-09-17', '2020-09-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '61071715780', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(110, 'E230698', 'e230698', NULL, NULL, '2013-09-12', '2019-09-12', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '61080701845', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(111, 'E217093', 'e217093', NULL, NULL, '2013-04-12', '2019-04-12', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '61090216135', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(112, 'E324541', 'e324541', NULL, NULL, '2016-02-29', '2022-02-28', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '61100104789', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(113, 'E363483', 'e363483', NULL, NULL, '2017-04-24', '2023-04-24', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '61101004283', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(114, 'E330802', 'e330802', NULL, NULL, '2016-05-12', '2022-05-12', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '61110600907', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(115, 'E204637', 'e204637', NULL, NULL, '2013-03-12', '2019-03-12', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '61120300216', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(116, 'E334026', 'e334026', NULL, NULL, '2016-06-20', '2022-06-20', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '62012316031', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(117, 'E387247', 'e387247', NULL, NULL, '2018-02-21', '2024-02-21', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '62012504650', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(118, 'E254545', 'e254545', NULL, NULL, '2014-02-21', '2020-02-24', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '62040408359', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(119, 'E312703', 'e312703', NULL, NULL, '2015-09-22', '2021-09-22', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '62041908043', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(120, 'E229750', 'e229750', NULL, NULL, '2014-07-02', '2020-07-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '62041920930', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(121, 'E282124', 'e282124', NULL, NULL, '2014-12-04', '2020-12-04', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '62052608779', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(122, 'E319251', 'e319251', NULL, 21, '2015-12-23', '2021-12-23', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '62063004637', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(123, 'I102969', 'i102969', NULL, NULL, '2013-05-10', '2019-05-10', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '62063004637', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(124, 'E286551', 'e286551', NULL, NULL, '2015-02-02', '2021-02-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '62082508593', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(125, 'E304953', 'e304953', NULL, NULL, '2015-07-06', '2021-07-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '62090308753', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(126, 'E254804', 'e254804', NULL, NULL, '2014-02-24', '2020-02-24', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '62110404554', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(127, 'E285468', 'e285468', NULL, NULL, '2015-01-22', '2021-01-22', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '63010117545', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(128, 'E238583', 'e238583', NULL, 11, '2013-09-27', '2019-09-27', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '63012608851', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(129, 'I294687', 'i294687', NULL, NULL, '2014-04-15', '2020-04-15', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '63012608851', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(130, 'E201125', 'e201125', NULL, NULL, '2013-02-05', '2019-02-05', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '63020504728', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(131, 'E266496', 'e266496', NULL, NULL, '2014-05-20', '2020-05-20', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '63020504736', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(132, 'E254900', 'e254900', NULL, NULL, '2014-02-27', '2020-02-24', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '63040207071', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(133, 'E356488', 'e356488', NULL, NULL, '2018-04-26', '2024-04-26', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '63042707839', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(134, 'E288933', 'e288933', NULL, NULL, '2015-02-24', '2021-02-24', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '63081308648', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(135, 'E286650', 'e286650', NULL, 14, '2015-02-02', '2021-02-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '63102509373', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(136, 'E201123', 'e201123', NULL, NULL, '2013-02-05', '2019-02-05', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '63112909046', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(137, 'E366995', 'e366995', NULL, NULL, '2017-06-08', '2023-06-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '64022429795', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(138, 'E366524', 'e366524', NULL, NULL, '2017-06-06', '2023-06-06', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '64031108398', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(139, 'E321727', 'e321727', NULL, NULL, '2016-01-27', '2022-01-27', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '64032408393', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(140, 'E377244', 'e377244', NULL, 26, '2017-09-27', '2023-09-27', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '64061108328', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(141, 'E376407', 'e376407', NULL, NULL, '2017-11-24', '2023-11-24', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '64112929053', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(142, 'E225821', 'e225821', NULL, NULL, '2014-07-17', '2020-07-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '64122528883', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(143, 'E212628', 'e212628', NULL, NULL, '2013-07-18', '2019-07-18', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '65011204903', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(144, 'E280979', 'e280979', NULL, NULL, '2014-11-18', '2020-11-18', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '65050509243', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(145, 'E356756', 'e356756', NULL, NULL, '2018-05-02', '2024-05-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '65051008614', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(146, 'E205045', 'e205045', NULL, NULL, '2013-03-08', '2019-03-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '65070404764', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(147, 'E356749', 'e356749', NULL, NULL, '2018-05-02', '2024-05-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '65120208397', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(148, 'E346339', 'e346339', NULL, NULL, '2016-11-08', '2022-11-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '66123008321', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(149, 'E297932', 'e297932', NULL, NULL, '2015-04-23', '2021-04-23', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '67031700317', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(150, 'E266494', 'e266494', NULL, 19, '2014-05-20', '2020-05-20', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '67040504118', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(151, 'I366225', 'i366225', NULL, NULL, '2014-07-14', '2020-07-14', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '67040504118', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(152, 'E262839', 'e262839', NULL, 10, '2014-04-04', '2020-04-24', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '67062313656', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(153, 'E356693', 'e356693', NULL, NULL, '2018-05-02', '2024-05-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '67090830179', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(154, 'J614258', 'j614258', NULL, NULL, '2017-09-19', '2023-09-19', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '67090830179', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, 2),
(155, 'E335775', 'e335775', NULL, NULL, '2016-07-18', '2022-07-18', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '67100108795', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(156, 'E254802', 'e254802', NULL, NULL, '2014-02-21', '2020-02-24', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '68060804197', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(157, 'E282281', 'e282281', NULL, NULL, '2014-12-04', '2020-12-04', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '68081008879', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(158, 'E315898', 'e315898', NULL, NULL, '2015-11-02', '2021-11-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '68112408566', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(159, 'E352712', 'e352712', NULL, NULL, '2018-04-18', '2024-04-18', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '69010704087', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(160, 'E257761', 'e257761', NULL, NULL, '2014-03-17', '2020-03-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '69022302493', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(161, 'E384449', 'e384449', NULL, 6, '2017-12-14', '2023-12-14', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '69042408154', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(162, 'E352710', 'e352710', NULL, NULL, '2018-04-18', '2024-04-18', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '69060208057', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(163, 'E381035', 'e381035', NULL, NULL, '2017-09-07', '2023-09-07', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '69070409736', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(164, 'E254897', 'e254897', NULL, NULL, '2014-02-24', '2020-02-24', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '69111532777', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(165, 'E350076', 'e350076', NULL, 17, '2017-01-16', '2023-01-16', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '69123004772', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(166, 'E212284', 'e212284', NULL, NULL, '2013-07-09', '2019-07-09', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '70020208381', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(167, 'E367205', 'e367205', NULL, NULL, '2017-06-12', '2023-06-12', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '70020607959', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(168, 'E334615', 'e334615', NULL, NULL, '2016-07-01', '2022-07-01', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '70021308100', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(169, 'E383019', 'e383019', NULL, NULL, '2017-11-06', '2023-11-06', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '70031109095', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(170, 'E229934', 'e229934', NULL, NULL, '2014-07-01', '2020-07-01', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '70051301219', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(171, 'E286552', 'e286552', NULL, NULL, '2015-02-02', '2021-02-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '70083108566', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(172, 'I755160', 'i755160', NULL, NULL, '2015-09-16', '2021-09-16', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '70083108566', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(173, 'E378670', 'e378670', NULL, NULL, '2017-09-20', '2023-09-20', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '70091903946', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(174, 'E286642', 'e286642', NULL, NULL, '2015-02-02', '2021-02-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '70092804014', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(175, 'E330573', 'e330573', NULL, NULL, '2016-05-10', '2022-05-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '70101507515', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(176, 'E229933', 'e229933', NULL, NULL, '2014-07-01', '2020-07-01', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '70110207538', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(177, 'E225858', 'e225858', NULL, NULL, '2014-07-17', '2020-07-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '71011507769', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(178, 'I102715', 'i102715', NULL, NULL, '2013-04-30', '2019-04-30', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '71030718141', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(179, 'E352904', 'e352904', NULL, NULL, '2018-04-18', '2024-04-18', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '71050407451', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(180, 'E383614', 'e383614', NULL, NULL, '2017-12-08', '2023-12-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '71092216171', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(181, 'E201422', 'e201422', NULL, NULL, '2013-01-29', '2019-01-29', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '71092908126', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(182, 'J818850', 'j818850', NULL, NULL, '2018-01-31', '2024-01-31', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '71092908126', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(183, 'E386936', 'e386936', NULL, NULL, '2018-01-31', '2024-01-31', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '71101108302', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(184, 'I870028', 'i870028', NULL, NULL, '2016-01-15', '2022-01-15', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '71121004390', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(185, 'E223010', 'e223010', NULL, NULL, '2013-09-25', '2019-09-25', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '72010715685', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(186, 'E306625', 'e306625', NULL, NULL, '2015-07-22', '2021-07-22', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '72032007668', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(187, 'E317891', 'e317891', NULL, NULL, '2015-11-30', '2021-11-30', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '72052015364', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(188, 'E383021', 'e383021', NULL, NULL, '2017-11-06', '2023-11-06', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '72101504361', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(189, 'E363510', 'e363510', NULL, NULL, '2017-04-25', '2023-04-25', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '72121814875', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(190, 'E335284', 'e335284', NULL, NULL, '2016-07-12', '2022-06-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '73052506720', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(191, 'E340535', 'e340535', NULL, NULL, '2016-09-17', '2022-09-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '73072206316', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(192, 'E257144', 'e257144', NULL, NULL, '2014-03-14', '2020-03-14', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '73073006280', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:13', 2, NULL),
(193, 'E387542', 'e387542', NULL, NULL, '2018-02-23', '2024-02-23', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '73073103189', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(194, 'E340811', 'e340811', NULL, NULL, '2016-09-21', '2022-09-21', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '73110606315', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(195, 'E283507', 'e283507', NULL, NULL, '2014-12-20', '2020-12-20', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '73123013880', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(196, 'E347240', 'e347240', NULL, NULL, '2016-12-16', '2022-12-16', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '74051309205', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(197, 'E360661', 'e360661', NULL, NULL, '2017-03-16', '2023-03-16', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '74060809482', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(198, 'E205028', 'e205028', NULL, NULL, '2013-03-08', '2019-03-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '74092309484', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(199, 'E352711', 'e352711', NULL, NULL, '2018-04-18', '2024-04-18', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '74102311337', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(200, 'E214271', 'e214271', NULL, NULL, '2013-05-10', '2019-05-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '75102212980', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(201, 'E340366', 'e340366', NULL, NULL, '2016-09-16', '2022-09-16', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '76010410574', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(202, 'E226369', 'e226369', NULL, NULL, '2014-07-10', '2020-07-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '76020229527', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(203, 'E211462', 'e211462', NULL, NULL, '2013-07-31', '2019-07-31', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '76021409683', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(204, 'J573075', 'j573075', NULL, NULL, '2017-07-14', '2023-07-14', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '76031703166', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(205, 'E276882', 'e276882', NULL, NULL, '2014-10-08', '2020-10-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '76061710228', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(206, 'J409531', 'j409531', NULL, NULL, '2017-03-16', '2023-03-16', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '76070710609', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(207, 'E229935', 'e229935', NULL, NULL, '2014-07-01', '2020-07-01', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '76081910490', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(208, 'E297959', 'e297959', NULL, NULL, '2015-05-14', '2021-05-14', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '76101210544', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(209, 'E326313', 'e326313', NULL, NULL, '2016-03-23', '2022-03-23', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '76111118867', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(210, 'E230783', 'e230783', NULL, NULL, '2013-09-13', '2019-09-13', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '77012215903', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(211, 'E257142', 'e257142', NULL, NULL, '2014-03-14', '2020-03-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '77020415185', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(212, 'E216732', 'e216732', NULL, NULL, '2013-04-11', '2019-04-11', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '77021023402', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(213, 'E285922', 'e285922', NULL, NULL, '2015-01-26', '2021-01-26', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '77031515891', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(214, 'E240417', 'e240417', NULL, NULL, '2013-10-11', '2019-10-11', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '77040217905', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(215, 'E352905', 'e352905', NULL, NULL, '2018-04-18', '2024-04-18', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '77041521006', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(216, 'E366286', 'e366286', NULL, NULL, '2017-05-31', '2023-05-31', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '77050515931', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(217, 'E354759', 'e354759', NULL, NULL, '2018-04-09', '2024-04-09', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '77090341604', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(218, 'E244683', 'e244683', NULL, NULL, '2013-11-18', '2019-11-18', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '78022820898', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(219, 'E303399', 'e303399', NULL, NULL, '2015-06-17', '2021-06-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '78031120904', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(220, 'E324542', 'e324542', NULL, NULL, '2016-02-29', '1900-01-29', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '78050820961', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(221, 'E338576', 'e338576', NULL, NULL, '2016-08-25', '2022-08-25', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '78051320859', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(222, 'E354221', 'e354221', NULL, 3, '2018-03-27', '2024-03-27', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '78060323018', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(223, 'E238785', 'e238785', NULL, 20, '2013-10-01', '2019-10-01', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '78073120893', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(224, 'E330651', 'e330651', NULL, 15, '2016-05-10', '2022-05-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '78092720864', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(225, 'E384360', 'e384360', NULL, 5, '2017-12-15', '2023-12-15', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '78102236826', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(226, 'E258826', 'e258826', NULL, 9, '2014-03-24', '2020-03-24', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '78110620877', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(227, 'E313081', 'e313081', NULL, NULL, '2015-09-25', '2021-09-25', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '78111720989', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(228, 'J677569', 'j677569', NULL, NULL, '2018-02-22', '2024-02-22', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '78111720989', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(229, 'I105951', 'i105951', NULL, NULL, '2013-01-31', '2019-01-21', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '79032915123', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(230, 'E285082', 'e285082', NULL, NULL, '2015-01-21', '2021-01-21', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '79040615169', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(231, 'E361263', 'e361263', NULL, NULL, '2017-03-24', '2023-03-24', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '79062320065', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(232, 'E387265', 'e387265', NULL, NULL, '2018-02-21', '2024-02-21', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '79062614171', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(233, 'E241430', 'e241430', NULL, 30, '2013-10-22', '2019-10-22', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '79072015222', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(234, 'E349460', 'e349460', NULL, 7, '2017-01-05', '2023-01-05', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '79072827182', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(235, 'E228339', 'e228339', NULL, NULL, '2014-03-07', '2020-03-07', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '79081915120', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(236, 'E272797', 'e272797', NULL, NULL, '2014-09-09', '2020-09-09', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '79090615151', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(237, 'E358712', 'e358712', NULL, NULL, '2017-02-17', '2023-02-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '79090615876', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(238, 'E225843', 'e225843', NULL, 29, '2014-07-17', '2020-07-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '79092915191', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(239, 'E203949', 'e203949', NULL, NULL, '2013-03-25', '2019-03-25', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '80051815232', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(240, 'E392561', 'e392561', NULL, NULL, '2017-07-07', '2023-07-07', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '80070216845', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(241, 'J199401', 'j199401', NULL, NULL, '2016-09-15', '2022-09-15', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '80111716465', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(242, 'E299903', 'e299903', NULL, 8, '2015-05-14', '2021-05-14', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '80121215974', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(243, 'E240733', 'e240733', NULL, NULL, '2013-10-14', '2019-10-14', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '81011915229', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(244, 'E848278', 'e848278', NULL, NULL, '2018-04-04', '2024-04-04', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '81022316349', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(245, 'E214279', 'e214279', NULL, NULL, '2013-05-10', '2019-05-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '81032115216', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(246, 'E360237', 'e360237', NULL, NULL, '2017-03-14', '2023-03-14', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '81041815807', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(247, 'E205706', 'e205706', NULL, NULL, '2013-06-06', '2019-06-06', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '81061216748', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(248, 'E266495', 'e266495', NULL, NULL, '2014-05-20', '2020-05-20', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '81081615873', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(249, 'E349063', 'e349063', NULL, NULL, '2016-12-26', '2022-12-26', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '81112115247', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(250, 'E273678', 'e273678', NULL, NULL, '2014-09-11', '2020-09-11', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '82010720230', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(251, 'E286647', 'e286647', NULL, NULL, '2015-02-02', '2021-02-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '82021520326', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(252, 'E297809', 'e297809', NULL, NULL, '2015-04-21', '2021-04-21', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '82022421818', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(253, 'E287649', 'e287649', NULL, NULL, '2015-02-16', '2021-02-16', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '82050220343', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(254, 'E285453', 'e285453', NULL, NULL, '2016-01-22', '2021-01-22', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '82060320292', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(255, 'J491149', 'j491149', NULL, NULL, '2017-06-02', '2023-06-02', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '82060520290', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL);
INSERT INTO `pas_passport` (`id`, `number`, `number_slug`, `application_id`, `client_id`, `issue_date`, `expiry_date`, `type`, `first_page`, `first_extension`, `first_extension_date`, `second_extension`, `second_extension_date`, `drop_passport`, `drop_date`, `drop_reason`, `client_ci`, `closed`, `in_store`, `created_at`, `updated_at`, `create_user_id`, `update_user_id`) VALUES
(256, 'E355821', 'e355821', NULL, NULL, '2018-03-14', '2024-03-14', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '82062231198', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(257, 'J635572', 'j635572', NULL, NULL, '2018-03-21', '2024-03-21', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '82062231198', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(258, 'E384499', 'e384499', NULL, NULL, '2017-12-15', '2023-12-15', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '82091520249', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(259, 'J726742', 'j726742', NULL, NULL, '2017-12-08', '2023-12-08', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '82091520249', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(260, 'E298020', 'e298020', NULL, NULL, '2015-04-15', '2021-04-21', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '82101020242', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(261, 'E212147', 'e212147', NULL, NULL, '2013-07-18', '2019-07-18', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '82101223010', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(262, 'E378870', 'e378870', NULL, NULL, '2017-09-21', '2023-09-21', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '82110620261', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(263, 'E380209', 'e380209', NULL, NULL, '2017-09-26', '2023-09-26', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '83010715860', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(264, 'E376262', 'e376262', NULL, NULL, '2017-11-20', '2023-11-20', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '83111515874', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(265, 'J677625', 'j677625', NULL, 32, '2018-02-23', '2024-02-23', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '83122315855', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(266, 'E358811', 'e358811', NULL, NULL, '2017-02-20', '2023-02-20', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '84012818590', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(267, 'J199402', 'j199402', NULL, NULL, '2016-09-15', '2022-09-15', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '84012818590', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(268, 'E353804', 'e353804', NULL, NULL, '2018-03-21', '2024-03-21', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '84022018079', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(269, 'J528210', 'j528210', NULL, NULL, '2017-06-19', '2023-06-19', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '84081418016', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(270, 'E341336', 'e341336', NULL, NULL, '2016-09-29', '2022-09-29', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '84100818145', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(271, 'I782455', 'i782455', NULL, NULL, '2015-10-09', '2021-10-09', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '84100818145', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(272, 'E384497', 'e384497', NULL, NULL, '2017-12-15', '2023-12-15', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '84103018011', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(273, 'E212148', 'e212148', NULL, NULL, '2013-07-18', '2019-07-18', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '84110818040', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(274, 'E353810', 'e353810', NULL, NULL, '2018-03-21', '2024-03-21', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '85030418088', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(275, 'E364142', 'e364142', NULL, NULL, '2017-05-10', '2023-05-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '85070818105', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(276, 'J449805', 'j449805', NULL, NULL, '2017-04-24', '2023-04-24', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '85070818105', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(277, 'E340385', 'e340385', NULL, NULL, '2016-09-16', '2022-09-16', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '85080618052', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(278, 'J528812', 'j528812', NULL, NULL, '2017-06-19', '2023-06-19', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '86031818078', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(279, 'E360065', 'e360065', NULL, NULL, '2017-03-10', '2023-03-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '86050518108', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(280, 'E209899', 'e209899', NULL, NULL, '2015-05-14', '2021-05-14', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '86060417778', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(281, 'E349107', 'e349107', NULL, NULL, '2016-12-26', '2022-12-26', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '86081817349', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(282, 'E356753', 'e356753', NULL, NULL, '2018-05-02', '2024-05-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '86082921612', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(283, 'E347979', 'e347979', NULL, NULL, '2017-01-31', '2023-01-31', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '86091918066', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(284, 'E255280', 'e255280', NULL, NULL, '2014-02-26', '2020-02-26', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '86122518141', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(285, 'E382470', 'e382470', NULL, NULL, '2017-10-24', '2023-10-24', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '87010923142', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(286, 'J745204', 'j745204', NULL, NULL, '2017-12-26', '2023-12-26', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '87010923142', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(287, 'E335162', 'e335162', NULL, NULL, '2016-07-11', '2022-07-11', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '87041315217', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(288, 'E312700', 'e312700', NULL, NULL, '2015-09-22', '2021-09-22', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '87060923451', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(289, 'E286065', 'e286065', NULL, NULL, '2015-01-27', '2021-01-27', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '87073101204', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(290, 'J225559', 'j225559', NULL, NULL, '2016-10-03', '2022-10-03', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '87073101204', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(291, 'E306840', 'e306840', NULL, NULL, '2015-07-23', '2021-07-23', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '87081521387', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(292, 'E312975', 'e312975', NULL, 2, '2015-09-28', '2021-09-28', 'OFI', 'E312975.png', NULL, NULL, NULL, NULL, 0, NULL, NULL, '87090922095', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, 2),
(293, 'E321875', 'e321875', NULL, 1, '2016-01-27', '2022-01-17', 'OFI', 'E321875.png', NULL, NULL, NULL, NULL, 0, NULL, NULL, '87102522022', 0, 1, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, 2),
(294, 'E361261', 'e361261', NULL, NULL, '2017-03-24', '2023-03-24', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '88071922263', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(295, 'I698638', 'i698638', NULL, NULL, '2015-07-08', '2021-07-08', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '88100322441', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(296, 'E360019', 'e360019', NULL, NULL, '2017-03-10', '2023-03-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '89021034182', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(297, 'J751674', 'j751674', NULL, NULL, '2017-12-15', '2023-12-15', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '89032921130', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(298, 'E360658', 'e360658', NULL, 4, '2017-03-16', '2023-03-16', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '89040934210', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(299, 'E299378', 'e299378', NULL, NULL, '2015-05-08', '2021-05-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '89072634202', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(300, 'I715236', 'i715236', NULL, NULL, '2015-07-23', '2021-07-23', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '89072634202', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(301, 'E340772', 'e340772', NULL, 27, '2016-09-22', '2022-09-22', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '89100334235', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(302, 'J304518', 'j304518', NULL, NULL, '2016-12-09', '2022-12-09', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '89100334235', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(303, 'E312058', 'e312058', NULL, NULL, '2015-09-01', '2021-09-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '89101334343', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(304, 'I706170', 'i706170', NULL, NULL, '2015-07-16', '2021-07-10', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '89122334188', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(305, 'E299900', 'e299900', NULL, NULL, '2015-05-14', '2021-05-14', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '90010938516', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(306, 'I697632', 'i697632', NULL, NULL, '2015-07-06', '2021-07-06', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '90021138519', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(307, 'E354531', 'e354531', NULL, NULL, '2018-03-06', '2024-03-06', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '90030938633', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(308, 'J901271', 'j901271', NULL, NULL, '2018-05-02', '2024-05-02', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '90030938633', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(309, 'E364143', 'e364143', NULL, NULL, '2017-05-10', '2023-05-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '90032839063', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(310, 'E384226', 'e384226', NULL, NULL, '2017-12-04', '2023-12-04', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '90071338208', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(311, 'E335090', 'e335090', NULL, NULL, '2016-07-08', '2022-07-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '90102338567', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(312, 'J096816', 'j096816', NULL, NULL, '2016-06-09', '2022-06-09', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '91012940940', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(313, 'E266493', 'e266493', NULL, NULL, '2014-05-20', '2020-05-20', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '91021740268', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(314, 'E360052', 'e360052', NULL, NULL, '2017-03-10', '2023-03-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '91052840855', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(315, 'E356694', 'e356694', NULL, NULL, '2018-05-02', '2024-05-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '91060240815', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(316, 'E352900', 'e352900', NULL, NULL, '2018-04-18', '2024-04-18', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '91062140833', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(317, 'J745203', 'j745203', NULL, NULL, '2017-12-26', '2023-12-26', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '91062140833', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(318, 'J457169', 'j457169', NULL, NULL, '2017-05-02', '2023-05-02', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '91062240798', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(319, 'J572307', 'j572307', NULL, NULL, '2017-07-24', '2023-07-24', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '91063040857', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(320, 'E393193', 'e393193', NULL, NULL, '2017-07-14', '2023-07-14', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '91072206665', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(321, 'J351191', 'j351191', NULL, NULL, '2017-01-29', '2023-01-26', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '91072206665', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(322, 'E384231', 'e384231', NULL, NULL, '2017-12-04', '2023-12-04', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '91072604835', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(323, 'E364141', 'e364141', NULL, NULL, '2017-05-10', '2023-05-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '91120344272', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(324, 'J449806', 'j449806', NULL, NULL, '2017-04-27', '2023-04-24', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '91120344272', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(325, 'E247198', 'e247198', NULL, NULL, '2013-12-11', '2019-12-11', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '91122239944', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(326, 'J523041', 'j523041', NULL, NULL, '2017-07-03', '2023-07-03', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '92011440085', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(327, 'E362070', 'e362070', NULL, NULL, '2017-04-10', '2023-04-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '92012440127', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(328, 'E269523', 'e269523', NULL, NULL, '2014-06-17', '2020-06-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '92021940192', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(329, 'E360069', 'e360069', NULL, NULL, '2017-03-10', '2023-03-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '92022340109', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(330, 'E304965', 'e304965', NULL, NULL, '2018-07-06', '2021-07-06', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '92022840109', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(331, 'E225822', 'e225822', NULL, NULL, '2014-07-17', '2020-07-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '92032638855', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(332, 'E360646', 'e360646', NULL, NULL, '2017-03-16', '2023-03-16', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '92042340080', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(333, 'E212602', 'e212602', NULL, 18, '2013-07-18', '2019-07-18', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '92050541802', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(334, 'E303387', 'e303387', NULL, NULL, '2015-06-17', '2021-06-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '92063041780', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(335, 'E360619', 'e360619', NULL, NULL, '2017-03-16', '2023-03-16', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '92090800301', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(336, 'E381290', 'e381290', NULL, NULL, '2017-03-24', '2023-03-24', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '92100340079', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(337, 'E331717', 'e331717', NULL, NULL, '2016-06-08', '2022-06-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '93040222104', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(338, 'J404172', 'j404172', NULL, NULL, '2017-03-15', '2023-03-15', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '93051617053', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(339, 'E361287', 'e361287', NULL, NULL, '2017-03-24', '2023-03-24', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '93070117038', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(340, 'E360066', 'e360066', NULL, NULL, '2017-03-10', '2023-03-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '93073117079', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(341, 'E246944', 'e246944', NULL, NULL, '2013-12-10', '2019-12-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '93092617119', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(342, 'E363509', 'e363509', NULL, NULL, '2017-04-25', '2023-04-25', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '93122117132', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(343, 'E303398', 'e303398', NULL, NULL, '2015-06-17', '2022-06-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '94022240501', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(344, 'E331712', 'e331712', NULL, NULL, '2016-06-08', '2022-06-08', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '94040540103', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(345, 'E279405', 'e279405', NULL, NULL, '2014-10-31', '2020-10-31', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '94041916748', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(346, 'E246945', 'e246945', NULL, NULL, '2013-12-10', '2019-12-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '94042100501', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(347, 'J677606', 'j677606', NULL, NULL, '2018-02-23', '2024-09-23', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '94072440096', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(348, 'E366460', 'e366460', NULL, NULL, '2017-06-02', '2023-06-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '94080740523', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(349, 'E366459', 'e366459', NULL, NULL, '2017-06-02', '2023-06-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '94101539302', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(350, 'E303391', 'e303391', NULL, NULL, '2016-06-17', '2021-06-17', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '94102241245', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(351, 'E340806', 'e340806', NULL, NULL, '2016-09-21', '2022-09-21', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '94120640120', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(352, 'E290622', 'e290622', NULL, NULL, '2015-03-10', '2021-03-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '95061940359', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(353, 'E393130', 'e393130', NULL, NULL, '2017-07-14', '2023-07-14', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '95121040893', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(354, 'E315899', 'e315899', NULL, NULL, '2015-11-02', '2021-11-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '96072417237', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(355, 'J500758', 'j500758', NULL, NULL, '2017-06-16', '2023-06-16', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '96110917230', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(356, 'E317890', 'e317890', NULL, NULL, '2015-11-30', '2021-11-30', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '97032800407', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(357, 'E325600', 'e325600', NULL, NULL, '2016-03-16', '2022-03-16', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '97060915472', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(358, 'E366456', 'e366456', NULL, NULL, '2017-06-02', '2023-06-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '97062315212', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(359, 'E378671', 'e378671', NULL, NULL, '2017-09-20', '2023-09-20', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '97070519707', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(360, 'E366458', 'e366458', NULL, NULL, '2017-06-02', '2023-06-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '97122915390', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(361, 'J500759', 'j500759', NULL, NULL, '2017-06-16', '2023-06-16', 'COR', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '98051915391', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(362, 'E366455', 'e366455', NULL, NULL, '2017-06-02', '2023-06-02', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '98062215453', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(363, 'E378673', 'e378673', NULL, NULL, '2017-09-20', '2023-09-20', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '99030815137', 0, 0, '2018-05-17 10:31:00', '2019-05-16 23:39:14', 2, NULL),
(364, 'E246948', 'e246948', NULL, NULL, '2013-12-10', '2019-12-10', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '56101703517', 0, 0, '2018-06-28 15:05:06', '2019-05-16 23:39:14', 2, NULL),
(367, 'E123456', 'e123456', NULL, NULL, '2014-01-01', '2020-01-01', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '87102522024', 0, 0, '2019-03-02 13:38:25', '2019-05-16 23:39:14', 2, NULL),
(368, 'E123457', 'e123457', NULL, NULL, '2013-07-19', '2019-07-19', 'OFI', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '87102522026', 0, 0, '2019-03-02 13:48:34', '2019-05-16 23:39:14', 2, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usf_area`
--

DROP TABLE IF EXISTS `usf_area`;
CREATE TABLE IF NOT EXISTS `usf_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `leader` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_CE2CDC025E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usf_area`
--

INSERT INTO `usf_area` (`id`, `name`, `leader`) VALUES
(1, 'Facultad de Ciencias Agropecuarias', 'Amilcar Arenal'),
(2, 'Facultad de Ciencias Económicas', 'Olie González'),
(3, 'Facultad de Electromecánica', 'Alguien'),
(4, 'Facultad de Construcciones', 'Alguien 2'),
(5, 'Facultad de Informática y Ciencias Exactas', 'Yaima Filiberto'),
(6, 'Facultad de Lenguas y Comunicación', 'Alguien 3'),
(7, 'Facultad de Ciencias Sociales', 'Alguien 4'),
(8, 'Facultad de Cultura Física', 'Francisco'),
(9, 'Facultad de Ciencias Aplicadas', 'Isnel Benites'),
(10, 'Facultad de Ciencias Pedagógicas', 'Dania Santi'),
(11, 'Rectoría', 'Santiago Lages Choy'),
(12, 'Vicerectoría Primera', 'Yosbany Batista Miranda'),
(13, 'Vicerectoría Investigación y Posgrado', 'Pablo Galindo Llanes'),
(14, 'Vicerrectoría Docente', 'Alicia Gregorich'),
(15, 'Vicerrectoría de Informatización', 'Julio Madera Quintana'),
(16, 'Vicerrectoría de Extención Universitaria', 'Hilario Amado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usf_career`
--

DROP TABLE IF EXISTS `usf_career`;
CREATE TABLE IF NOT EXISTS `usf_career` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `leader` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_FA4424FF5E237E06` (`name`),
  KEY `IDX_FA4424FFBD0F409C` (`area_id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usf_career`
--

INSERT INTO `usf_career` (`id`, `name`, `leader`, `area_id`) VALUES
(1, 'Medicina Veterinaria', NULL, 1),
(2, 'Ingeniería Agronómica', NULL, 1),
(3, 'Licenciatura en Educación Agropecuaria', NULL, 1),
(4, 'Licenciatura en Economía', NULL, 2),
(5, 'Licenciatura en Contabilidad y Finanzas', NULL, 2),
(6, 'Licenciatura en Turismo', NULL, 2),
(7, 'Licenciatura en Educación Economía', NULL, 2),
(8, 'Ingeniería Mecánica', NULL, 3),
(9, 'Ingeniería Eléctrica', NULL, 3),
(10, 'Licenciatura en Educación Mecánica', NULL, 3),
(11, 'Licenciatura en Educación Eléctrica', NULL, 3),
(12, 'Licenciatura en Educación Mecanización', NULL, 3),
(13, 'Arquitectura y Urbanismo', NULL, 4),
(14, 'Ingeniería en Construcción Civil', NULL, 4),
(15, 'Licenciatura en Educación Construcción', NULL, 4),
(16, 'Licenciatura en Gestión del Patrimonio', NULL, 4),
(17, 'Licenciatura en Educación Laboral', NULL, 4),
(18, 'Ingeniería Informática', NULL, 5),
(19, 'Licenciatura en Ciencias de la Información', NULL, 5),
(20, 'Licenciatura en Educación Matemática-Física', NULL, 5),
(21, 'Licenciatura en Educación Laboral-Informática', NULL, 5),
(22, 'Licenciatura en Educación Informática', NULL, 5),
(23, 'Licenciatura en Educación Matemática', NULL, 5),
(24, 'Licenciatura en Educación Física', NULL, 5),
(25, 'Licenciatura en Periodismo', NULL, 6),
(26, 'Licenciatura en Comunicación Social', NULL, 6),
(27, 'Licenciatura en Lengua Inglesa', NULL, 6),
(28, 'Licenciatura en Educación Español- Literatura', NULL, 6),
(29, 'Licenciatura en Educación Lenguas Extranjeras', NULL, 6),
(30, 'Licenciatura en Estudios Socioculturales', NULL, 7),
(31, 'Licenciatura en Historia', NULL, 7),
(32, 'Licenciatura en Psicología', NULL, 7),
(33, 'Licenciatura en Sociología', NULL, 7),
(34, 'Licenciatura en Derecho', NULL, 7),
(35, 'Licenciatura en Educación Marxismo Leninismo Historia', NULL, 7),
(36, 'Licenciatura en Gestión Sociocultural para el desarrollo', NULL, 7),
(37, 'Licenciatura en Cultura Física', NULL, 8),
(38, 'Ingeniería Química', NULL, 9),
(39, 'Ingeniería Industrial', NULL, 9),
(40, 'Ciencias Alimentarias', NULL, 9),
(41, 'Ingeniería en Procesos Agroindustriales', NULL, 9),
(42, 'Licenciatura en Educación Biología-Geografía', NULL, 9),
(43, 'Licenciatura en Educación Biología-Química', NULL, 9),
(44, 'Licenciatura en Educación Biología', NULL, 9),
(45, 'Licenciatura en Educación Geografía', NULL, 9),
(46, 'Licenciatura en Educación Química', NULL, 9),
(47, 'Licenciatura en Educación Preescolar', NULL, 10),
(48, 'Licenciatura en Educación Especial', NULL, 10),
(49, 'Licenciatura en Educación Logopedia', NULL, 10),
(50, 'Licenciatura en Educación Primaria', NULL, 10),
(51, 'Licenciatura en Educación Pedagogía-Psicología', NULL, 10),
(52, 'Licenciatura en Educación Artística', NULL, 10),
(53, 'Licenciatura en Instructor de Arte', NULL, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usf_country`
--

DROP TABLE IF EXISTS `usf_country`;
CREATE TABLE IF NOT EXISTS `usf_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sp_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `continent` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `area` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sub_area` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iso2` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `iso3` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `phone_code` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flag_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_file_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_94EC7E9A5E237E06` (`name`),
  UNIQUE KEY `UNIQ_94EC7E9A8EEAE109` (`sp_name`),
  UNIQUE KEY `UNIQ_94EC7E9A1B6F9774` (`iso2`),
  UNIQUE KEY `UNIQ_94EC7E9A6C68A7E2` (`iso3`)
) ENGINE=InnoDB AUTO_INCREMENT=499 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usf_country`
--

INSERT INTO `usf_country` (`id`, `name`, `sp_name`, `continent`, `area`, `sub_area`, `iso2`, `iso3`, `phone_code`, `flag_image`, `last_file_update`) VALUES
(254, 'Afghanistan', 'Afganistán', 'Asia', 'Asia del Sur', 'Medio Oriente', 'AF', 'AFG', '93', 'afg.png', '2016-01-01 00:00:00'),
(255, 'Albania', 'Albania', 'Europa', 'Europa Oriental', 'Europa Oriental', 'AL', 'ALB', '355', 'alb.png', '2016-01-01 00:00:00'),
(256, 'Germany', 'Alemania', 'Europa', 'Europa Central', NULL, 'DE', 'DEU', '49', 'deu.png', '2016-01-01 00:00:00'),
(257, 'Algeria', 'Argelia', 'África', 'África del Norte', NULL, 'DZ', 'DZA', '213', 'dza.png', '2016-01-01 00:00:00'),
(258, 'Andorra', 'Andorra', 'Europa', 'Europa Occidental', NULL, 'AD', 'AND', '376', 'and.png', '2016-01-01 00:00:00'),
(259, 'Angola', 'Angola', 'África', 'África del Sur', NULL, 'AO', 'AGO', '244', 'ago.png', '2016-01-01 00:00:00'),
(260, 'Anguilla', 'Anguila', 'América', 'Caribe', 'Antillas Menores', 'AI', 'AIA', '1264', 'aia.png', '2016-01-01 00:00:00'),
(261, 'Antarctica', 'Antártida', 'Antartida', 'Antartida', NULL, 'AQ', 'ATA', '672', 'ata.png', '2016-01-01 00:00:00'),
(262, 'Antigua and Barbuda', 'Antigua y Barbuda', 'América', 'Caribe', 'Antillas Menores', 'AG', 'ATG', '1268', 'atg.png', '2016-01-01 00:00:00'),
(263, 'Netherlands Antilles', 'Antillas Neerlandesas', 'América', 'Caribe', 'Antillas Menores', 'AN', 'ANT', '599', 'ant.png', '2016-01-01 00:00:00'),
(264, 'Saudi Arabia', 'Arabia Saudita', 'Asia', 'Asia Central', 'Medio Oriente', 'SA', 'SAU', '966', 'sau.png', '2016-01-01 00:00:00'),
(265, 'Argentina', 'Argentina', 'América', 'América del Sur', 'Cono Sur', 'AR', 'ARG', '54', 'arg.png', '2016-01-01 00:00:00'),
(266, 'Armenia', 'Armenia', 'Europa', 'Caucaso Sur', NULL, 'AM', 'ARM', '374', 'arm.png', '2016-01-01 00:00:00'),
(267, 'Aruba', 'Aruba', 'América', 'Caribe', 'Antillas Menores', 'AW', 'ABW', '297', 'abw.png', '2016-01-01 00:00:00'),
(268, 'Australia', 'Australia', 'Oceania', 'Oceania', NULL, 'AU', 'AUS', '61', 'aus.png', '2016-01-01 00:00:00'),
(269, 'Austria', 'Austria', 'Europa', 'Europa Central', NULL, 'AT', 'AUT', '43', 'aut.png', '2016-01-01 00:00:00'),
(270, 'Azerbaijan', 'Azerbaiyán', 'Europa', 'Caucaso', NULL, 'AZ', 'AZE', '994', 'aze.png', '2016-01-01 00:00:00'),
(271, 'Belgium', 'Bélgica', 'Europa', 'Europa Occidental', NULL, 'BE', 'BEL', '32', 'bel.png', '2016-01-01 00:00:00'),
(272, 'Bahamas', 'Bahamas', 'América', 'Caribe', 'Antillas', 'BS', 'BHS', '1242', 'bhs.png', '2016-01-01 00:00:00'),
(273, 'Bahrain', 'Bahrein', 'Asia', 'Asia Occidental', 'Medio Oriente', 'BH', 'BHR', '973', 'bhr.png', '2016-01-01 00:00:00'),
(274, 'Bangladesh', 'Bangladesh', 'Asia', 'Asia del Sur', NULL, 'BD', 'BGD', '880', 'bgd.png', '2016-01-01 00:00:00'),
(275, 'Barbados', 'Barbados', 'América', 'Caribe', 'Antillas Menores', 'BB', 'BRB', '1246', 'brb.png', '2016-01-01 00:00:00'),
(276, 'Belize', 'Belice', 'América', 'América Central', 'América Central', 'BZ', 'BLZ', '501', 'blz.png', '2016-01-01 00:00:00'),
(277, 'Benin', 'Benín', 'África', 'África Occidental', NULL, 'BJ', 'BEN', '229', 'ben.png', '2016-01-01 00:00:00'),
(278, 'Bhutan', 'Bhután', 'Asia', 'Asia del Sur', NULL, 'BT', 'BTN', '975', 'btn.png', '2016-01-01 00:00:00'),
(279, 'Belarus', 'Bielorrusia', 'Europa', 'Europa Oriental', NULL, 'BY', 'BLR', '375', 'blr.png', '2016-01-01 00:00:00'),
(280, 'Myanmar', 'Birmania', 'Asia', 'Sudeste de Asia', NULL, 'MM', 'MMR', '95', 'mmr.png', '2016-01-01 00:00:00'),
(281, 'Bolivia', 'Bolivia', 'América', 'América del Sur', 'Zona Andina', 'BO', 'BOL', '591', 'bol.png', '2016-01-01 00:00:00'),
(282, 'Bosnia and Herzegovina', 'Bosnia y Herzegovina', 'Europa', 'Europa Oriental', NULL, 'BA', 'BIH', '387', 'bih.png', '2016-01-01 00:00:00'),
(283, 'Botswana', 'Botsuana', 'África', 'África del Sur', NULL, 'BW', 'BWA', '267', 'bwa.png', '2016-01-01 00:00:00'),
(284, 'Brazil', 'Brasil', 'América', 'América del Sur', 'Cono Sur', 'BR', 'BRA', '55', 'bra.png', '2016-01-01 00:00:00'),
(285, 'Brunei', 'Brunéi', 'Asia', 'Sudeste de Asia', NULL, 'BN', 'BRN', '673', 'brn.png', '2016-01-01 00:00:00'),
(286, 'Bulgaria', 'Bulgaria', 'Europa', 'Europa Oriental', NULL, 'BG', 'BGR', '359', 'bgr.png', '2016-01-01 00:00:00'),
(287, 'Burkina Faso', 'Burkina Faso', 'África', 'África Occidental', NULL, 'BF', 'BFA', '226', 'bfa.png', '2016-01-01 00:00:00'),
(288, 'Burundi', 'Burundi', 'África', 'África Oriental', NULL, 'BI', 'BDI', '257', 'bdi.png', '2016-01-01 00:00:00'),
(289, 'Cape Verde', 'Cabo Verde', 'África', 'África Occidental', NULL, 'CV', 'CPV', '238', 'cpv.png', '2016-01-01 00:00:00'),
(290, 'Cambodia', 'Camboya', 'Asia', 'Sudeste de Asia', NULL, 'KH', 'KHM', '855', 'khm.png', '2016-01-01 00:00:00'),
(291, 'Cameroon', 'Camerún', 'África', 'África Central', NULL, 'CM', 'CMR', '237', 'cmr.png', '2016-01-01 00:00:00'),
(292, 'Canada', 'Canadá', 'América', 'América del Norte', 'América del Norte', 'CA', 'CAN', '1', 'can.png', '2016-01-01 00:00:00'),
(293, 'Chad', 'Chad', 'África', 'África del Norte', NULL, 'TD', 'TCD', '235', 'tcd.png', '2016-01-01 00:00:00'),
(294, 'Chile', 'Chile', 'América', 'América del Sur', 'Cono Sur', 'CL', 'CHL', '56', 'chl.png', '2016-01-01 00:00:00'),
(295, 'China', 'China', 'Asia', 'Asia del Este', NULL, 'CN', 'CHN', '86', 'chn.png', '2016-01-01 00:00:00'),
(296, 'Cyprus', 'Chipre', 'Asia', 'Asia Occidental', 'Medio Oriente', 'CY', 'CYP', '357', 'cyp.png', '2016-01-01 00:00:00'),
(297, 'Vatican City State', 'Ciudad del Vaticano', 'Europa', 'Europa Occidental', NULL, 'VA', 'VAT', '39', 'vat.png', '2016-01-01 00:00:00'),
(298, 'Colombia', 'Colombia', 'América', 'América del Sur', 'Zona Andina', 'CO', 'COL', '57', 'col.png', '2016-01-01 00:00:00'),
(299, 'Comoros', 'Comoras', 'África', 'África Oriental', NULL, 'KM', 'COM', '269', 'com.png', '2016-01-01 00:00:00'),
(300, 'Congo', 'Congo', 'África', 'África Central', NULL, 'CG', 'COG', '242', 'cog.png', '2016-01-01 00:00:00'),
(301, 'Democratic Republic of Congo', 'República Democrática del Congo', 'África', 'África Central', NULL, 'CD', 'COD', '243', 'cod.png', '2016-01-01 00:00:00'),
(302, 'North Korea', 'Corea del Norte', 'Asia', 'Asia del Este', NULL, 'KP', 'PRK', '850', 'prk.png', '2016-01-01 00:00:00'),
(303, 'South Korea', 'Corea del Sur', 'Asia', 'Asia del Este', NULL, 'KR', 'KOR', '82', 'kor.png', '2016-01-01 00:00:00'),
(304, 'Ivory Coast', 'Costa de Marfil', 'África', 'África Occidental', NULL, 'CI', 'CIV', '225', 'civ.png', '2016-01-01 00:00:00'),
(305, 'Costa Rica', 'Costa Rica', 'América', 'América Central', 'América Central', 'CR', 'CRI', '506', 'cri.png', '2016-01-01 00:00:00'),
(306, 'Croatia', 'Croacia', 'Europa', 'Europa del Sur', NULL, 'HR', 'HRV', '385', 'hrv.png', '2016-01-01 00:00:00'),
(307, 'Cuba', 'Cuba', 'América', 'Caribe', 'Antillas Mayores', 'CU', 'CUB', '53', 'cub.png', '2016-01-01 00:00:00'),
(308, 'Denmark', 'Dinamarca', 'Europa', 'Europa Occidental', NULL, 'DK', 'DNK', '45', 'dnk.png', '2016-01-01 00:00:00'),
(309, 'Dominica', 'Dominica', 'América', 'Caribe', 'Antillas Menores', 'DM', 'DMA', '1767', 'dma.png', '2016-01-01 00:00:00'),
(310, 'Ecuador', 'Ecuador', 'América', 'América del Sur', 'Zona Andina', 'EC', 'ECU', '593', 'ecu.png', '2016-01-01 00:00:00'),
(311, 'Egypt', 'Egipto', 'África', 'África del Norte', NULL, 'EG', 'EGY', '20', 'egy.png', '2016-01-01 00:00:00'),
(312, 'El Salvador', 'El Salvador', 'América', 'América Central', 'América Central', 'SV', 'SLV', '503', 'slv.png', '2016-01-01 00:00:00'),
(313, 'United Arab Emirates', 'Emiratos Árabes Unidos', 'Asia', 'Asia Occidental', 'Medio Oriente', 'AE', 'ARE', '971', 'are.png', '2016-01-01 00:00:00'),
(314, 'Eritrea', 'Eritrea', 'África', 'África Oriental', NULL, 'ER', 'ERI', '291', 'eri.png', '2016-01-01 00:00:00'),
(315, 'Slovakia', 'Eslovaquia', 'Europa', 'Europa Central', NULL, 'SK', 'SVK', '421', 'svk.png', '2016-01-01 00:00:00'),
(316, 'Slovenia', 'Eslovenia', 'Europa', 'Europa Central', NULL, 'SI', 'SVN', '386', 'svn.png', '2016-01-01 00:00:00'),
(317, 'Spain', 'España', 'Europa', 'Europa Occidental', NULL, 'ES', 'ESP', '34', 'esp.png', '2016-01-01 00:00:00'),
(318, 'United States of America', 'Estados Unidos de América', 'América', 'América del Norte', 'América del Norte', 'US', 'USA', '1', 'usa.png', '2016-01-01 00:00:00'),
(319, 'Estonia', 'Estonia', 'Europa', 'Europa Oriental', NULL, 'EE', 'EST', '372', 'est.png', '2016-01-01 00:00:00'),
(320, 'Ethiopia', 'Etiopía', 'África', 'África Oriental', NULL, 'ET', 'ETH', '251', 'eth.png', '2016-01-01 00:00:00'),
(321, 'Philippines', 'Filipinas', 'Asia', 'Sudeste de Asia', NULL, 'PH', 'PHL', '63', 'phl.png', '2016-01-01 00:00:00'),
(322, 'Finland', 'Finlandia', 'Europa', 'Europa Occidental', NULL, 'FI', 'FIN', '358', 'fin.png', '2016-01-01 00:00:00'),
(323, 'Fiji', 'Fiyi', 'Oceania', 'Oceania', 'Melanesia  ', 'FJ', 'FJI', '679', 'fji.png', '2016-01-01 00:00:00'),
(324, 'France', 'Francia', 'Europa', 'Europa Occidental', NULL, 'FR', 'FRA', '33', 'fra.png', '2016-01-01 00:00:00'),
(325, 'Gabon', 'Gabón', 'África', 'África Central', NULL, 'GA', 'GAB', '241', 'gab.png', '2016-01-01 00:00:00'),
(326, 'Gambia', 'Gambia', 'África', 'África Occidental', NULL, 'GM', 'GMB', '220', 'gmb.png', '2016-01-01 00:00:00'),
(327, 'Georgia', 'Georgia', 'Europa', 'Europa Oriental', NULL, 'GE', 'GEO', '995', 'geo.png', '2016-01-01 00:00:00'),
(328, 'Ghana', 'Ghana', 'África', 'África Occidental', NULL, 'GH', 'GHA', '233', 'gha.png', '2016-01-01 00:00:00'),
(329, 'Gibraltar', 'Gibraltar', 'Europa', 'Europa Occidental', NULL, 'GI', 'GIB', '350', 'gib.png', '2016-01-01 00:00:00'),
(330, 'Grenada', 'Granada', 'América', 'Caribe', 'Antillas Menores', 'GD', 'GRD', '1473', 'grd.png', '2016-01-01 00:00:00'),
(331, 'Greece', 'Grecia', 'Europa', 'Europa Oriental', NULL, 'GR', 'GRC', '30', 'grc.png', '2016-01-01 00:00:00'),
(332, 'Greenland', 'Groenlandia', 'América', 'América del Norte', 'América del Norte', 'GL', 'GRL', '299', 'grl.png', '2016-01-01 00:00:00'),
(333, 'Guadeloupe', 'Guadalupe', 'América', 'Caribe', 'Antillas Francesas', 'GP', 'GLP', '', 'glp.png', '2016-01-01 00:00:00'),
(334, 'Guam', 'Guam', 'Oceania', 'Oceania', NULL, 'GU', 'GUM', '1671', 'gum.png', '2016-01-01 00:00:00'),
(335, 'Guatemala', 'Guatemala', 'América', 'América Central', 'América Central', 'GT', 'GTM', '502', 'gtm.png', '2016-01-01 00:00:00'),
(336, 'French Guiana', 'Guayana Francesa', 'América', 'América del Sur', NULL, 'GF', 'GUF', '', 'guf.png', '2016-01-01 00:00:00'),
(337, 'Guernsey', 'Guernsey', 'Europa', 'Europa Central', NULL, 'GG', 'GGY', '', 'ggy.png', '2016-01-01 00:00:00'),
(338, 'Guinea', 'Guinea', 'África', 'África Occidental', NULL, 'GN', 'GIN', '224', 'gin.png', '2016-01-01 00:00:00'),
(339, 'Equatorial Guinea', 'Guinea Ecuatorial', 'África', 'África Central', NULL, 'GQ', 'GNQ', '240', 'gnq.png', '2016-01-01 00:00:00'),
(340, 'Guinea-Bissau', 'Guinea-Bissau', 'África', 'África Occidental', NULL, 'GW', 'GNB', '245', 'gnb.png', '2016-01-01 00:00:00'),
(341, 'Guyana', 'Guyana', 'América', 'América del Sur', NULL, 'GY', 'GUY', '592', 'guy.png', '2016-01-01 00:00:00'),
(342, 'Haiti', 'Haití', 'América', 'Caribe', 'Antillas Mayores', 'HT', 'HTI', '509', 'hti.png', '2016-01-01 00:00:00'),
(343, 'Honduras', 'Honduras', 'América', 'América Central', 'América Central', 'HN', 'HND', '504', 'hnd.png', '2016-01-01 00:00:00'),
(344, 'Hong Kong', 'Hong kong', 'Asia', 'Asia del Sur', NULL, 'HK', 'HKG', '852', 'hkg.png', '2016-01-01 00:00:00'),
(345, 'Hungary', 'Hungría', 'Europa', 'Europa Central', NULL, 'HU', 'HUN', '36', 'hun.png', '2016-01-01 00:00:00'),
(346, 'India', 'India', 'Asia', 'Asia del Sur', NULL, 'IN', 'IND', '91', 'ind.png', '2016-01-01 00:00:00'),
(347, 'Indonesia', 'Indonesia', 'Asia', 'Sudeste de Asia', NULL, 'ID', 'IDN', '62', 'idn.png', '2016-01-01 00:00:00'),
(348, 'Iran', 'Irán', 'Asia', 'Asia del Sur', NULL, 'IR', 'IRN', '98', 'irn.png', '2016-01-01 00:00:00'),
(349, 'Iraq', 'Irak', 'Asia', 'Asia Occidental', 'Medio Oriente', 'IQ', 'IRQ', '964', 'irq.png', '2016-01-01 00:00:00'),
(350, 'Ireland', 'Irlanda', 'Europa', 'Europa Occidental', NULL, 'IE', 'IRL', '353', 'irl.png', '2016-01-01 00:00:00'),
(351, 'Bouvet Island', 'Isla Bouvet', 'Europa', 'Océano Atlantico', NULL, 'BV', 'BVT', '', 'bvt.png', '2016-01-01 00:00:00'),
(352, 'Isle of Man', 'Isla de Man', 'Europa', 'Europa Occidental', NULL, 'IM', 'IMN', '44', 'imn.png', '2016-01-01 00:00:00'),
(353, 'Christmas Island', 'Isla de Navidad', 'Oceania', 'Oceania', NULL, 'CX', 'CXR', '61', 'cxr.png', '2016-01-01 00:00:00'),
(354, 'Norfolk Island', 'Isla Norfolk', 'Oceania', 'Oceania', NULL, 'NF', 'NFK', '', 'nfk.png', '2016-01-01 00:00:00'),
(355, 'Iceland', 'Islandia', 'Europa', 'Europa Occidental', NULL, 'IS', 'ISL', '354', 'isl.png', '2016-01-01 00:00:00'),
(356, 'Bermuda Islands', 'Islas Bermudas', 'América', 'América del Norte', 'América del Norte', 'BM', 'BMU', '1441', 'bmu.png', '2016-01-01 00:00:00'),
(357, 'Cayman Islands', 'Islas Caimán', 'América', 'Caribe', NULL, 'KY', 'CYM', '1345', 'cym.png', '2016-01-01 00:00:00'),
(358, 'Cocos (Keeling) Islands', 'Islas Cocos (Keeling)', 'Oceania', 'Oceania', NULL, 'CC', 'CCK', '61', 'cck.png', '2016-01-01 00:00:00'),
(359, 'Cook Islands', 'Islas Cook', 'Oceania', 'Oceania', NULL, 'CK', 'COK', '682', 'cok.png', '2016-01-01 00:00:00'),
(360, 'Åland Islands', 'Islas de Åland', 'Europa', 'Europa Occidental', NULL, 'AX', 'ALA', '', 'ala.png', '2016-01-01 00:00:00'),
(361, 'Faroe Islands', 'Islas Feroe', 'Europa', 'Europa Occidental', NULL, 'FO', 'FRO', '298', 'fro.png', '2016-01-01 00:00:00'),
(362, 'South Georgia and the South Sandwich Islands', 'Islas Georgias del Sur y Sandwich del Sur', 'América', 'América del Sur', NULL, 'GS', 'SGS', '', 'sgs.png', '2016-01-01 00:00:00'),
(363, 'Heard Island and McDonald Islands', 'Islas Heard y McDonald', 'Oceania', 'Oceania', NULL, 'HM', 'HMD', '', 'hmd.png', '2016-01-01 00:00:00'),
(364, 'Maldives', 'Islas Maldivas', 'Asia', 'Asia del Sur', NULL, 'MV', 'MDV', '960', 'mdv.png', '2016-01-01 00:00:00'),
(365, 'Falkland Islands (Malvinas)', 'Islas Malvinas', 'América', 'América del Sur', NULL, 'FK', 'FLK', '500', 'flk.png', '2016-01-01 00:00:00'),
(366, 'Northern Mariana Islands', 'Islas Marianas del Norte', 'Oceania', 'Oceania', NULL, 'MP', 'MNP', '1670', 'mnp.png', '2016-01-01 00:00:00'),
(367, 'Marshall Islands', 'Islas Marshall', 'Oceania', 'Oceania', 'Micronesia  ', 'MH', 'MHL', '692', 'mhl.png', '2016-01-01 00:00:00'),
(368, 'Pitcairn Islands', 'Islas Pitcairn', 'Oceania', 'Oceania', NULL, 'PN', 'PCN', '870', 'pcn.png', '2016-01-01 00:00:00'),
(369, 'Solomon Islands', 'Islas Salomón', 'Oceania', 'Oceania', 'Melanesia  ', 'SB', 'SLB', '677', 'slb.png', '2016-01-01 00:00:00'),
(370, 'Turks and Caicos Islands', 'Islas Turcas y Caicos', 'América', 'Caribe', NULL, 'TC', 'TCA', '1649', 'tca.png', '2016-01-01 00:00:00'),
(371, 'United States Minor Outlying Islands', 'Islas Ultramarinas Menores de Estados Unidos', 'América', 'América del Norte', 'América del Norte', 'UM', 'UMI', '', 'umi.png', '2016-01-01 00:00:00'),
(372, 'Virgin Islands', 'Islas Vírgenes Británicas', 'América', 'Caribe', NULL, 'VG', 'VGB', '1284', 'vgb.png', '2016-01-01 00:00:00'),
(373, 'United States Virgin Islands', 'Islas Vírgenes de los Estados Unidos', 'América', 'América Central', 'América Central', 'VI', 'VIR', '1340', 'vir.png', '2016-01-01 00:00:00'),
(374, 'Israel', 'Israel', 'Asia', 'Asia Occidental', 'Medio Oriente', 'IL', 'ISR', '972', 'isr.png', '2016-01-01 00:00:00'),
(375, 'Italy', 'Italia', 'Europa', 'Europa Occidental', NULL, 'IT', 'ITA', '39', 'ita.png', '2016-01-01 00:00:00'),
(376, 'Jamaica', 'Jamaica', 'América', 'Caribe', 'Antillas Mayores', 'JM', 'JAM', '1876', 'jam.png', '2016-01-01 00:00:00'),
(377, 'Japan', 'Japón', 'Asia', 'Asia del Este', NULL, 'JP', 'JPN', '81', 'jpn.png', '2016-01-01 00:00:00'),
(378, 'Jersey', 'Jersey', 'Europa', 'Europa Occidental', NULL, 'JE', 'JEY', '', 'jey.png', '2016-01-01 00:00:00'),
(379, 'Jordan', 'Jordania', 'Asia', 'Asia Occidental', 'Medio Oriente', 'JO', 'JOR', '962', 'jor.png', '2016-01-01 00:00:00'),
(380, 'Kazakhstan', 'Kazajistán', 'Asia', 'Asia Central', NULL, 'KZ', 'KAZ', '7', 'kaz.png', '2016-01-01 00:00:00'),
(381, 'Kenya', 'Kenia', 'África', 'África Oriental', NULL, 'KE', 'KEN', '254', 'ken.png', '2016-01-01 00:00:00'),
(382, 'Kyrgyzstan', 'Kirgizstán', 'Asia', 'Asia Central', NULL, 'KG', 'KGZ', '996', 'kgz.png', '2016-01-01 00:00:00'),
(383, 'Kiribati', 'Kiribati', 'Oceania', 'Oceania', 'Micronesia  ', 'KI', 'KIR', '686', 'kir.png', '2016-01-01 00:00:00'),
(384, 'Kuwait', 'Kuwait', 'Asia', 'Asia Occidental', 'Medio Oriente', 'KW', 'KWT', '965', 'kwt.png', '2016-01-01 00:00:00'),
(385, 'Lebanon', 'Líbano', 'Asia', 'Asia Occidental', 'Medio Oriente', 'LB', 'LBN', '961', 'lbn.png', '2016-01-01 00:00:00'),
(386, 'Laos', 'Laos', 'Asia', 'Sudeste de Asia', NULL, 'LA', 'LAO', '856', 'lao.png', '2016-01-01 00:00:00'),
(387, 'Lesotho', 'Lesoto', 'África', 'África del Sur', NULL, 'LS', 'LSO', '266', 'lso.png', '2016-01-01 00:00:00'),
(388, 'Latvia', 'Letonia', 'Europa', 'Europa Oriental', NULL, 'LV', 'LVA', '371', 'lva.png', '2016-01-01 00:00:00'),
(389, 'Liberia', 'Liberia', 'África', 'África Occidental', NULL, 'LR', 'LBR', '231', 'lbr.png', '2016-01-01 00:00:00'),
(390, 'Libya', 'Libia', 'África', 'África del Norte', NULL, 'LY', 'LBY', '218', 'lby.png', '2016-01-01 00:00:00'),
(391, 'Liechtenstein', 'Liechtenstein', 'Europa', 'Europa Central', NULL, 'LI', 'LIE', '423', 'lie.png', '2016-01-01 00:00:00'),
(392, 'Lithuania', 'Lituania', 'Europa', 'Europa Oriental', NULL, 'LT', 'LTU', '370', 'ltu.png', '2016-01-01 00:00:00'),
(393, 'Luxembourg', 'Luxemburgo', 'Europa', 'Europa Occidental', NULL, 'LU', 'LUX', '352', 'lux.png', '2016-01-01 00:00:00'),
(394, 'Mexico', 'México', 'América', 'América del Norte', 'América del Norte', 'MX', 'MEX', '52', 'mex.png', '2016-01-01 00:00:00'),
(395, 'Monaco', 'Mónaco', 'Europa', 'Europa Occidental', NULL, 'MC', 'MCO', '377', 'mco.png', '2016-01-01 00:00:00'),
(396, 'Macao', 'Macao', 'Asia', 'Asia del Este', NULL, 'MO', 'MAC', '853', 'mac.png', '2016-01-01 00:00:00'),
(397, 'Macedonia', 'Macedónia', 'Europa', 'Europa Oriental', NULL, 'MK', 'MKD', '389', 'mkd.png', '2016-01-01 00:00:00'),
(398, 'Madagascar', 'Madagascar', 'África', 'África Oriental', NULL, 'MG', 'MDG', '261', 'mdg.png', '2016-01-01 00:00:00'),
(399, 'Malaysia', 'Malasia', 'Asia', 'Sudeste de Asia', NULL, 'MY', 'MYS', '60', 'mys.png', '2016-01-01 00:00:00'),
(400, 'Malawi', 'Malawi', 'África', 'África Oriental', NULL, 'MW', 'MWI', '265', 'mwi.png', '2016-01-01 00:00:00'),
(401, 'Mali', 'Mali', 'África', 'África del Norte', NULL, 'ML', 'MLI', '223', 'mli.png', '2016-01-01 00:00:00'),
(402, 'Malta', 'Malta', 'Europa', 'Europa Occidental', NULL, 'MT', 'MLT', '356', 'mlt.png', '2016-01-01 00:00:00'),
(403, 'Morocco', 'Marruecos', 'África', 'África del Norte', NULL, 'MA', 'MAR', '212', 'mar.png', '2016-01-01 00:00:00'),
(404, 'Martinique', 'Martinica', 'América', 'Caribe', 'Antillas Francesas', 'MQ', 'MTQ', '', 'mtq.png', '2016-01-01 00:00:00'),
(405, 'Mauritius', 'Mauricio', 'África', 'África Oriental', NULL, 'MU', 'MUS', '230', 'mus.png', '2016-01-01 00:00:00'),
(406, 'Mauritania', 'Mauritania', 'África', 'África del Norte', NULL, 'MR', 'MRT', '222', 'mrt.png', '2016-01-01 00:00:00'),
(407, 'Mayotte', 'Mayotte', 'África', 'África Oriental', NULL, 'YT', 'MYT', '262', 'myt.png', '2016-01-01 00:00:00'),
(408, 'Estados Federados de Micronesia', 'Micronesia', 'Oceania', 'Micronesia', NULL, 'FM', 'FSM', '691', 'fsm.png', '2016-01-01 00:00:00'),
(409, 'Moldova', 'Moldavia', 'Europa', 'Europa Oriental', NULL, 'MD', 'MDA', '373', 'mda.png', '2016-01-01 00:00:00'),
(410, 'Mongolia', 'Mongolia', 'Asia', 'Asia del Este', NULL, 'MN', 'MNG', '976', 'mng.png', '2016-01-01 00:00:00'),
(411, 'Montenegro', 'Montenegro', 'Europa', 'Europa Oriental', NULL, 'ME', 'MNE', '382', 'mne.png', '2016-01-01 00:00:00'),
(412, 'Montserrat', 'Montserrat', 'América', 'Caribe', NULL, 'MS', 'MSR', '1664', 'msr.png', '2016-01-01 00:00:00'),
(413, 'Mozambique', 'Mozambique', 'África', 'África Oriental', NULL, 'MZ', 'MOZ', '258', 'moz.png', '2016-01-01 00:00:00'),
(414, 'Namibia', 'Namibia', 'África', 'África del Sur', NULL, 'NA', 'NAM', '264', 'nam.png', '2016-01-01 00:00:00'),
(415, 'Nauru', 'Nauru', 'Oceania', 'Oceania', 'Micronesia  ', 'NR', 'NRU', '674', 'nru.png', '2016-01-01 00:00:00'),
(416, 'Nepal', 'Nepal', 'Asia', 'Asia del Sur', NULL, 'NP', 'NPL', '977', 'npl.png', '2016-01-01 00:00:00'),
(417, 'Nicaragua', 'Nicaragua', 'América', 'América Central', 'América Central', 'NI', 'NIC', '505', 'nic.png', '2016-01-01 00:00:00'),
(418, 'Niger', 'Niger', 'África', 'África del Norte', NULL, 'NE', 'NER', '227', 'ner.png', '2016-01-01 00:00:00'),
(419, 'Nigeria', 'Nigeria', 'África', 'África Occidental', NULL, 'NG', 'NGA', '234', 'nga.png', '2016-01-01 00:00:00'),
(420, 'Niue', 'Niue', 'Oceania', 'Oceania', NULL, 'NU', 'NIU', '683', 'niu.png', '2016-01-01 00:00:00'),
(421, 'Norway', 'Noruega', 'Europa', 'Europa Occidental', NULL, 'NO', 'NOR', '47', 'nor.png', '2016-01-01 00:00:00'),
(422, 'New Caledonia', 'Nueva Caledonia', 'Oceania', 'Oceania', NULL, 'NC', 'NCL', '687', 'ncl.png', '2016-01-01 00:00:00'),
(423, 'New Zealand', 'Nueva Zelanda', 'Oceania', 'Oceania', NULL, 'NZ', 'NZL', '64', 'nzl.png', '2016-01-01 00:00:00'),
(424, 'Oman', 'Omán', 'Asia', 'Asia Occidental', 'Medio Oriente', 'OM', 'OMN', '968', 'omn.png', '2016-01-01 00:00:00'),
(425, 'Netherlands', 'Países Bajos', 'Europa', 'Europa Occidental', NULL, 'NL', 'NLD', '31', 'nld.png', '2016-01-01 00:00:00'),
(426, 'Pakistan', 'Pakistán', 'Asia', 'Asia del Sur', NULL, 'PK', 'PAK', '92', 'pak.png', '2016-01-01 00:00:00'),
(427, 'Palau', 'Palau', 'Oceania', 'Oceania', 'Micronesia  ', 'PW', 'PLW', '680', 'plw.png', '2016-01-01 00:00:00'),
(428, 'Palestine', 'Palestina', 'Asia', 'Asia Occidental', 'Medio Oriente', 'PS', 'PSE', '', 'pse.png', '2016-01-01 00:00:00'),
(429, 'Panama', 'Panamá', 'América', 'América Central', 'América Central', 'PA', 'PAN', '507', 'pan.png', '2016-01-01 00:00:00'),
(430, 'Papua New Guinea', 'Papúa Nueva Guinea', 'Oceania', 'Oceania', 'Melanesia  ', 'PG', 'PNG', '675', 'png.png', '2016-01-01 00:00:00'),
(431, 'Paraguay', 'Paraguay', 'América', 'América del Sur', 'Cono Sur', 'PY', 'PRY', '595', 'pry.png', '2016-01-01 00:00:00'),
(432, 'Peru', 'Perú', 'América', 'América del Sur', 'Zona Andina', 'PE', 'PER', '51', 'per.png', '2016-01-01 00:00:00'),
(433, 'French Polynesia', 'Polinesia Francesa', 'Oceania', 'Oceania', NULL, 'PF', 'PYF', '689', 'pyf.png', '2016-01-01 00:00:00'),
(434, 'Poland', 'Polonia', 'Europa', 'Europa Central', NULL, 'PL', 'POL', '48', 'pol.png', '2016-01-01 00:00:00'),
(435, 'Portugal', 'Portugal', 'Europa', 'Europa Occidental', NULL, 'PT', 'PRT', '351', 'prt.png', '2016-01-01 00:00:00'),
(436, 'Puerto Rico', 'Puerto Rico', 'América', 'Caribe', 'Antillas Mayores', 'PR', 'PRI', '1', 'pri.png', '2016-01-01 00:00:00'),
(437, 'Qatar', 'Qatar', 'Asia', 'Asia Occidental', 'Medio Oriente', 'QA', 'QAT', '974', 'qat.png', '2016-01-01 00:00:00'),
(438, 'United Kingdom', 'Reino Unido', 'Europa', 'Europa Occidental', NULL, 'GB', 'GBR', '44', 'gbr.png', '2016-01-01 00:00:00'),
(439, 'Central African Republic', 'República Centroafricana', 'África', 'África Central', NULL, 'CF', 'CAF', '236', 'caf.png', '2016-01-01 00:00:00'),
(440, 'Czech Republic', 'República Checa', 'Europa', 'Europa Central', NULL, 'CZ', 'CZE', '420', 'cze.png', '2016-01-01 00:00:00'),
(441, 'Dominican Republic', 'República Dominicana', 'América', 'Caribe', 'Antillas Mayores', 'DO', 'DOM', '1809', 'dom.png', '2016-01-01 00:00:00'),
(442, 'Réunion', 'Reunión', 'África', 'África Oriental', NULL, 'RE', 'REU', '', 'reu.png', '2016-01-01 00:00:00'),
(443, 'Rwanda', 'Ruanda', 'África', 'África Oriental', NULL, 'RW', 'RWA', '250', 'rwa.png', '2016-01-01 00:00:00'),
(444, 'Romania', 'Rumanía', 'Europa', 'Europa Oriental', NULL, 'RO', 'ROU', '40', 'rou.png', '2016-01-01 00:00:00'),
(445, 'Russia', 'Rusia', 'Asia', 'Asia del Norte', NULL, 'RU', 'RUS', '7', 'rus.png', '2016-01-01 00:00:00'),
(446, 'Western Sahara', 'Sahara Occidental', 'África', 'África del Norte', NULL, 'EH', 'ESH', '', 'esh.png', '2016-01-01 00:00:00'),
(447, 'Samoa', 'Samoa', 'Oceania', 'Oceania', 'Polinesia  ', 'WS', 'WSM', '685', 'wsm.png', '2016-01-01 00:00:00'),
(448, 'American Samoa', 'Samoa Americana', 'Oceania', 'Oceania', NULL, 'AS', 'ASM', '1684', 'asm.png', '2016-01-01 00:00:00'),
(449, 'Saint Barthélemy', 'San Bartolomé', 'América', 'Caribe', 'Antillas Francesas', 'BL', 'BLM', '590', 'blm.png', '2016-01-01 00:00:00'),
(450, 'Saint Kitts and Nevis', 'San Cristóbal y Nieves', 'América', 'Caribe', 'Antillas Menores', 'KN', 'KNA', '1869', 'kna.png', '2016-01-01 00:00:00'),
(451, 'San Marino', 'San Marino', 'Europa', 'Europa Occidental', NULL, 'SM', 'SMR', '378', 'smr.png', '2016-01-01 00:00:00'),
(452, 'Saint Martin (French part)', 'San Martín (Francia)', 'América', 'Caribe', 'Antillas Francesas', 'MF', 'MAF', '1599', 'maf.png', '2016-01-01 00:00:00'),
(453, 'Saint Vincent and the Grenadines', 'San Vicente y las Granadinas', 'América', 'Caribe', 'Antillas Menores', 'VC', 'VCT', '1784', 'vct.png', '2016-01-01 00:00:00'),
(454, 'AscensiÃ³n y TristÃ¡n de AcuÃ±a', 'Santa Elena', 'África', 'África del Sur', NULL, 'SH', 'SHN', '290', 'shn.png', '2016-01-01 00:00:00'),
(455, 'Saint Lucia', 'Santa Lucía', 'América', 'Caribe', 'Antillas Menores', 'LC', 'LCA', '1758', 'lca.png', '2016-01-01 00:00:00'),
(456, 'Sao Tome and Principe', 'Santo Tomá y Príncipe', 'África', 'África Central', NULL, 'ST', 'STP', '239', 'stp.png', '2016-01-01 00:00:00'),
(457, 'Senegal', 'Senegal', 'África', 'África Occidental', NULL, 'SN', 'SEN', '221', 'sen.png', '2016-01-01 00:00:00'),
(458, 'Serbia', 'Serbia', 'Europa', 'Europa Oriental', NULL, 'RS', 'SRB', '381', 'srb.png', '2016-01-01 00:00:00'),
(459, 'Seychelles', 'Seychelles', 'África', 'África Oriental', NULL, 'SC', 'SYC', '248', 'syc.png', '2016-01-01 00:00:00'),
(460, 'Sierra Leone', 'Sierra Leona', 'África', 'África Occidental', NULL, 'SL', 'SLE', '232', 'sle.png', '2016-01-01 00:00:00'),
(461, 'Singapore', 'Singapur', 'Asia', 'Sudeste de Asia', NULL, 'SG', 'SGP', '65', 'sgp.png', '2016-01-01 00:00:00'),
(462, 'Syria', 'Siria', 'Asia', 'Asia Occidental', 'Medio Oriente', 'SY', 'SYR', '963', 'syr.png', '2016-01-01 00:00:00'),
(463, 'Somalia', 'Somalia', 'África', 'África Oriental', NULL, 'SO', 'SOM', '252', 'som.png', '2016-01-01 00:00:00'),
(464, 'Sri Lanka', 'Sri lanka', 'Asia', 'Asia del Sur', NULL, 'LK', 'LKA', '94', 'lka.png', '2016-01-01 00:00:00'),
(465, 'South Africa', 'Sudáfrica', 'África', 'África del Sur', NULL, 'ZA', 'ZAF', '27', 'zaf.png', '2016-01-01 00:00:00'),
(466, 'Sudan', 'Sudán', 'África', 'África Oriental', NULL, 'SD', 'SDN', '249', 'sdn.png', '2016-01-01 00:00:00'),
(467, 'Sweden', 'Suecia', 'Europa', 'Europa Occidental', NULL, 'SE', 'SWE', '46', 'swe.png', '2016-01-01 00:00:00'),
(468, 'Switzerland', 'Suiza', 'Europa', 'Europa Central', NULL, 'CH', 'CHE', '41', 'che.png', '2016-01-01 00:00:00'),
(469, 'Suriname', 'Surinám', 'América', 'América del Sur', NULL, 'SR', 'SUR', '597', 'sur.png', '2016-01-01 00:00:00'),
(470, 'Svalbard and Jan Mayen', 'Svalbard y Jan Mayen', 'Europa', 'Europa Occidental', NULL, 'SJ', 'SJM', '', 'sjm.png', '2016-01-01 00:00:00'),
(471, 'Swaziland', 'Swazilandia', 'África', 'África del Sur', NULL, 'SZ', 'SWZ', '268', 'swz.png', '2016-01-01 00:00:00'),
(472, 'Tajikistan', 'Tadjikistán', 'Asia', 'Asia Central', NULL, 'TJ', 'TJK', '992', 'tjk.png', '2016-01-01 00:00:00'),
(473, 'Thailand', 'Tailandia', 'Asia', 'Sudeste de Asia', NULL, 'TH', 'THA', '66', 'tha.png', '2016-01-01 00:00:00'),
(474, 'Taiwan', 'Taiwán', 'Asia', 'Asia del Este', NULL, 'TW', 'TWN', '886', 'twn.png', '2016-01-01 00:00:00'),
(475, 'Tanzania', 'Tanzania', 'África', 'África Oriental', NULL, 'TZ', 'TZA', '255', 'tza.png', '2016-01-01 00:00:00'),
(476, 'British Indian Ocean Territory', 'Territorio Británico del Océano Índico', 'Asia', 'Asia del Sur', NULL, 'IO', 'IOT', '', 'iot.png', '2016-01-01 00:00:00'),
(477, 'French Southern Territories', 'Territorios Australes y Antárticas Franceses', 'Antartida', 'Antartida', NULL, 'TF', 'ATF', '', 'atf.png', '2016-01-01 00:00:00'),
(478, 'East Timor', 'Timor Oriental', 'Asia', 'Sudeste de Asia', NULL, 'TL', 'TLS', '670', 'tls.png', '2016-01-01 00:00:00'),
(479, 'Togo', 'Togo', 'África', 'África Occidental', NULL, 'TG', 'TGO', '228', 'tgo.png', '2016-01-01 00:00:00'),
(480, 'Tokelau', 'Tokelau', 'Oceania', 'Oceania', NULL, 'TK', 'TKL', '690', 'tkl.png', '2016-01-01 00:00:00'),
(481, 'Tonga', 'Tonga', 'Oceania', 'Oceania', 'Polinesia  ', 'TO', 'TON', '676', 'ton.png', '2016-01-01 00:00:00'),
(482, 'Trinidad and Tobago', 'Trinidad y Tobago', 'América', 'Caribe', 'Antillas Menores', 'TT', 'TTO', '1868', 'tto.png', '2016-01-01 00:00:00'),
(483, 'Tunisia', 'Tunez', 'África', 'África del Norte', NULL, 'TN', 'TUN', '216', 'tun.png', '2016-01-01 00:00:00'),
(484, 'Turkmenistan', 'Turkmenistán', 'Asia', 'Asia Central', NULL, 'TM', 'TKM', '993', 'tkm.png', '2016-01-01 00:00:00'),
(485, 'Turkey', 'Turquía', 'Asia', 'Asia Occidental', 'Medio Oriente', 'TR', 'TUR', '90', 'tur.png', '2016-01-01 00:00:00'),
(486, 'Tuvalu', 'Tuvalu', 'Oceania', 'Oceania', 'Polinesia  ', 'TV', 'TUV', '688', 'tuv.png', '2016-01-01 00:00:00'),
(487, 'Ukraine', 'Ucrania', 'Europa', 'Europa Oriental', NULL, 'UA', 'UKR', '380', 'ukr.png', '2016-01-01 00:00:00'),
(488, 'Uganda', 'Uganda', 'África', 'África Oriental', NULL, 'UG', 'UGA', '256', 'uga.png', '2016-01-01 00:00:00'),
(489, 'Uruguay', 'Uruguay', 'América', 'América del Sur', 'Cono Sur', 'UY', 'URY', '598', 'ury.png', '2016-01-01 00:00:00'),
(490, 'Uzbekistan', 'Uzbekistán', 'Asia', 'Asia Central', NULL, 'UZ', 'UZB', '998', 'uzb.png', '2016-01-01 00:00:00'),
(491, 'Vanuatu', 'Vanuatu', 'Oceania', 'Oceania', 'Melanesia  ', 'VU', 'VUT', '678', 'vut.png', '2016-01-01 00:00:00'),
(492, 'Venezuela', 'Venezuela', 'América', 'América del Sur', 'Zona Andina', 'VE', 'VEN', '58', 'ven.png', '2016-01-01 00:00:00'),
(493, 'Vietnam', 'Vietnam', 'Asia', 'Sudeste de Asia', NULL, 'VN', 'VNM', '84', 'vnm.png', '2016-01-01 00:00:00'),
(494, 'Wallis and Futuna', 'Wallis y Futuna', 'Oceania', 'Oceania', NULL, 'WF', 'WLF', '681', 'wlf.png', '2016-01-01 00:00:00'),
(495, 'Yemen', 'Yemen', 'Asia', 'Asia Occidental', 'Medio Oriente', 'YE', 'YEM', '967', 'yem.png', '2016-01-01 00:00:00'),
(496, 'Djibouti', 'Yibuti', 'África', 'África Oriental', NULL, 'DJ', 'DJI', '253', 'dji.png', '2016-01-01 00:00:00'),
(497, 'Zambia', 'Zambia', 'África', 'África Oriental', NULL, 'ZM', 'ZMB', '260', 'zmb.png', '2016-01-01 00:00:00'),
(498, 'Zimbabwe', 'Zimbabue', 'África', 'África Oriental', NULL, 'ZW', 'ZWE', '263', 'zwe.png', '2016-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usf_course`
--

DROP TABLE IF EXISTS `usf_course`;
CREATE TABLE IF NOT EXISTS `usf_course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area_id` int(11) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `coordinator` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5E8127C2BD0F409C` (`area_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usf_course`
--

INSERT INTO `usf_course` (`id`, `area_id`, `name`, `coordinator`, `type`) VALUES
(1, 1, 'Reproducción Animal', 'Dr. C. Redimio Pedraza Olivera', 'DOC'),
(2, 2, 'Ciencias Contables y Financieras', 'Dr. C. Arístides Pelegrín Mesa', 'DOC'),
(3, 9, 'Ingeniería Química', 'Dr. C. Luis Ramos Sánchez', 'DOC'),
(4, NULL, 'Ciencias Pedagógicas', 'Dr. C. Jorge García Batán', 'DOC'),
(5, 2, 'Ciencias Económicas', 'Dr. C. Alfredo González Tamayo', 'DOC'),
(6, 3, 'Ingeniería Eléctrica', 'Dra. C. Milagros Diez Rodríguez', 'DOC'),
(8, NULL, 'Ciencias Pedagógicas (Programa cubano de tipo Tutelar grupal que se desarrolla en la BUAP)', 'Dra. C. María Machado Durán', 'DOC'),
(9, NULL, 'Desarrollo Social Comunitario', 'Dra. C. Mirtha Yordi García', 'DOC'),
(10, 2, 'Economía y Gestión del Desarrollo', 'Dr. C. Ramón González Fontes', 'DOC'),
(11, NULL, 'Teoría de la Pedagogía', 'Dr. C. José Emilio Hernández Sánchez', 'DOC'),
(12, NULL, 'Ciencias de la Educación', 'Dr. C. Antonio Sáez Palmero', 'DOC'),
(13, 5, 'Informática Aplicada', NULL, 'MAE'),
(14, 5, 'Enseñanza de la Matemática', NULL, 'MAE'),
(15, 4, 'Ingeniería Civil', NULL, 'MAE'),
(16, 4, 'Conservación de Centros Históricos y Rehabilitación del Patrimonio Edificado', NULL, 'MAE'),
(17, 2, 'Gestión Turística', NULL, 'MAE'),
(18, 9, 'Análisis de Procesos de la Industria Química', NULL, 'MAE'),
(20, 9, 'Análisis de Procesos de la Industria Química (IUT Alonso Gamero, Falcón)', NULL, 'MAE'),
(21, 9, 'Análisis de Procesos de la Industria Química (IUT Federico Rivero Palacio, Miranda)', NULL, 'MAE'),
(22, 9, 'Enseñanza de la Química', NULL, 'MAE'),
(23, 1, 'Producción Animal Sostenible', NULL, 'MAE'),
(24, 1, 'Diagnóstico Veterinario', NULL, 'MAE'),
(26, 3, 'Ingeniería Eléctrica', NULL, 'MAE'),
(27, 3, 'Eficiencia Energética', NULL, 'MAE'),
(28, 3, 'Ingeniería Mecánica', NULL, 'MAE'),
(29, NULL, 'Ciencias de la Educación Superior', NULL, 'MAE'),
(30, NULL, 'Ciencias de la Educación. Edición BUAP', NULL, 'MAE'),
(31, 2, 'Administración de Negocios', NULL, 'MAE'),
(32, 2, 'Dirección de Empresa', NULL, 'MAE'),
(33, NULL, 'Gerencia de la ciencia y la Innovación', NULL, 'MAE'),
(34, 2, 'Contabilidad Gerencial', NULL, 'MAE'),
(35, 2, 'Desarrollo Regional', NULL, 'MAE'),
(36, 7, 'Derecho Constitucional y Administrativo', NULL, 'MAE'),
(37, 6, 'Ciencias de la Comunicación', NULL, 'MAE'),
(38, NULL, 'Manejo sostenible de tierras', NULL, 'MAE'),
(39, NULL, 'Educación Ambiental', NULL, 'MAE'),
(40, NULL, 'Educación', NULL, 'MAE'),
(41, 8, 'Investigación en pedagogía de la cultura física y el deporte', NULL, 'MAE'),
(42, 2, 'Comercialización Turística', NULL, 'ESP'),
(43, NULL, 'Gestión de los Servicios de Alimentos y Bebidas', NULL, 'ESP'),
(44, NULL, 'Transporte Automotor', NULL, 'ESP'),
(45, NULL, 'Extensión Agraria', NULL, 'ESP');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usr_user`
--

DROP TABLE IF EXISTS `usr_user`;
CREATE TABLE IF NOT EXISTS `usr_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `full_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `science_category` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee` tinyint(1) NOT NULL,
  `work_department` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `work_phone` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `home_phone` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cel_phone` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_update_image` datetime NOT NULL,
  `home` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `about` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C6C77AE92FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_C6C77AEA0D96FBF` (`email_canonical`),
  UNIQUE KEY `UNIQ_C6C77AEC05FB297` (`confirmation_token`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usr_user`
--

INSERT INTO `usr_user` (`id`, `first_name`, `last_name`, `full_name`, `username`, `username_canonical`, `email`, `email_canonical`, `science_category`, `position`, `employee`, `work_department`, `work_phone`, `home_phone`, `cel_phone`, `user_image`, `last_update_image`, `home`, `about`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`) VALUES
(2, 'José', 'Abadía', 'José Abadía', 'jose.abadia', 'jose.abadia', 'jose.abadia@reduc.edu.cu', 'jose.abadia@reduc.edu.cu', NULL, 'Especialista', 1, 'Informatización', '32266839', '32274151', '53777974', 'jose.abadia_2.png', '2019-02-06 15:59:00', 'Sta Rita #149 / San Ramón y Lugareño', 'Desarrollador WEB de la DRI.', 1, 'g6c3i3bco80kgskocgw0k8ko0gcgggo', '$2y$13$.e.qOZEnm46wkXLivskNWepRMQh484gkQFWq.uDvdT5T1OvjTRO.e', '2019-05-23 23:03:18', NULL, NULL, 'a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}'),
(3, 'María', 'Escanel', 'María Escanel', 'maria.escanel', 'maria.escanel', 'maria.escanel@reduc.edu.cu', 'maria.escanel@reduc.edu.cu', NULL, NULL, 0, NULL, NULL, NULL, NULL, '', '2018-02-27 03:07:57', NULL, NULL, 1, 'db7nq7l6l1c080wckcwck4oswswo8c4', '$2y$13$dE3XlQpZ.ek.xvLpjfpu7evpYMRlLKE4E7hMIGbop9tlFXFeXMzri', '2018-06-28 11:10:33', NULL, NULL, 'a:1:{i:0;s:20:\"ROLE_INFO_SPECIALIST\";}'),
(4, 'Milady', 'Macareño', 'Milady Macareño', 'milady.macareno', 'milady.macareno', 'milady.macareno@reduc.edu.cu', 'milady.macareno@reduc.edu.cu', NULL, NULL, 0, NULL, NULL, NULL, NULL, '', '2018-02-27 03:20:37', NULL, NULL, 1, 'm2pqscxyqlw848o0ckcw80wokwwckg4', '$2y$13$WONaPHX1bDiiIO2sqECEyeedXrmBE6YV.9rfDvRFHlY/s1zNIvNHC', NULL, NULL, NULL, 'a:1:{i:0;s:23:\"ROLE_REQUIRE_SPECIALIST\";}'),
(5, 'Yailé', 'Caballero', 'Yailé Caballero', 'yaile.caballero', 'yaile.caballero', 'yaile.caballero@reduc.edu.cu', 'yaile.caballero@reduc.edu.cu', NULL, NULL, 0, NULL, NULL, NULL, NULL, '', '2018-02-27 03:21:57', NULL, NULL, 1, 'gfnompeljlcs0owcc040c0owkoggo40', '$2y$13$aanXnqHS/o2hyvZJQoGkLO1X8rPLePDWNMfgmoRRYBDyw/VcCq0jK', '2018-02-27 11:47:20', NULL, NULL, 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}'),
(6, 'Anisabel', 'Gálvez', 'Anisabel Gálvez', 'anisabel.galvez', 'anisabel.galvez', 'anisabel.galvez@reduc.edu.cu', 'anisabel.galvez@reduc.edu.cu', NULL, NULL, 0, NULL, NULL, NULL, NULL, '', '2018-02-27 03:22:29', NULL, NULL, 1, '7947tvt2wdc0c48sw04so8og8wcwc00', '$2y$13$q7wnvaIO2BltvRmWabF7VeI.LWEShZvS5pSTa2lcltagGIwNUuFL.', '2018-02-27 03:27:05', NULL, NULL, 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}'),
(7, 'Mercedes', 'Salas', 'Mercedes Salas', 'mercedes.salas', 'mercedes.salas', 'mercedes.salas@reduc.edu.cu', 'mercedes.salas@reduc.edu.cu', NULL, NULL, 0, NULL, NULL, NULL, NULL, '', '2018-02-27 03:22:56', NULL, NULL, 1, 'd4f0vx4ere0owwwwcs8koo880wws480', '$2y$13$5YLiZFe3uoIc9uS./TI/nOUjpfdJNWBGqA.0W4TgvDMIWgSyyWgRy', NULL, NULL, NULL, 'a:1:{i:0;s:14:\"ROLE_SECRETARY\";}'),
(8, 'Iliana', 'Delgado', 'Iliana Delgado', 'iliana.delgado', 'iliana.delgado', 'iliana.delgado@reduc.edu.cu', 'iliana.delgado@reduc.edu.cu', NULL, NULL, 0, NULL, NULL, NULL, NULL, '', '2018-02-27 03:23:30', NULL, NULL, 1, 'dm1vgpzec7k8ok4sk0kw0scwgg44ksw', '$2y$13$4QX8FLZdonDn157r3lG.IOdZYV0Qtmpnd6j4inI7O4UuYu6U1V1D.', '2019-02-23 00:39:05', NULL, NULL, 'a:1:{i:0;s:22:\"ROLE_MANAGE_SPECIALIST\";}'),
(9, 'Yanela', 'Rodríguez', 'Yanela Rodríguez', 'yanela.rodriguez', 'yanela.rodriguez', 'yanela.rodriguez@reduc.edu.cu', 'yanela.rodriguez@reduc.edu.cu', NULL, NULL, 0, NULL, NULL, NULL, NULL, '', '2018-02-27 03:24:00', NULL, NULL, 1, 'tsytqoibgqo0wc8048ckg4ksk88sowc', '$2y$13$wTFGah1xk3jJnokBmImu3eb0Z7/0oylK22MbLw30JO5YDrlb47SPW', NULL, NULL, NULL, 'a:1:{i:0;s:22:\"ROLE_MANAGE_SPECIALIST\";}'),
(10, 'Yunia', 'Llanes', 'Yunia Llanes', 'yunia.llanes', 'yunia.llanes', 'yunia.llanes@reduc.edu.cu', 'yunia.llanes@reduc.edu.cu', NULL, NULL, 0, NULL, NULL, NULL, NULL, '', '2018-02-27 03:24:38', NULL, NULL, 1, 'edqgfiesbe0o0kss88k0k84wgsgco8s', '$2y$13$PKA4ZKXTOP7cRg/CMRKJDOnuin4zSzubUzt4sA6Yjr2JO1SwCNo2K', '2018-06-28 13:12:45', NULL, NULL, 'a:1:{i:0;s:23:\"ROLE_REQUIRE_SPECIALIST\";}'),
(11, 'Ilderis', 'Letford', 'Ilderis Letford', 'ilderis.letford', 'ilderis.letford', 'ilderis.letford@reduc.edu.cu', 'ilderis.letford@reduc.edu.cu', NULL, NULL, 0, NULL, NULL, NULL, NULL, '', '2018-02-27 03:25:15', NULL, NULL, 1, 'rpnywag2tqo8ws0o4sgooo8gskko448', '$2y$13$ITqQOV3hcnvAggircBmAqOYgK9rG4awBMvxmG3lGFvUDUhXRs5iTO', NULL, NULL, NULL, 'a:1:{i:0;s:23:\"ROLE_REQUIRE_SPECIALIST\";}');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `agr_application`
--
ALTER TABLE `agr_application`
  ADD CONSTRAINT `FK_63D9C1D10405986` FOREIGN KEY (`institution_id`) REFERENCES `agr_institution` (`id`),
  ADD CONSTRAINT `FK_63D9C1D85564492` FOREIGN KEY (`created_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_63D9C1DE0DFCA6C` FOREIGN KEY (`updated_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `agr_institution`
--
ALTER TABLE `agr_institution`
  ADD CONSTRAINT `FK_3A9F98E585564492` FOREIGN KEY (`create_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_3A9F98E5E0DFCA6C` FOREIGN KEY (`update_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_3A9F98E5F92F3E70` FOREIGN KEY (`country_id`) REFERENCES `usf_country` (`id`);

--
-- Filtros para la tabla `agr_institutional`
--
ALTER TABLE `agr_institutional`
  ADD CONSTRAINT `FK_88B01A5910405986` FOREIGN KEY (`institution_id`) REFERENCES `agr_institution` (`id`),
  ADD CONSTRAINT `FK_88B01A593E030ACD` FOREIGN KEY (`application_id`) REFERENCES `agr_application` (`id`),
  ADD CONSTRAINT `FK_88B01A59727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `agr_institutional` (`id`),
  ADD CONSTRAINT `FK_88B01A59E0DFCA6C` FOREIGN KEY (`updated_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_88B01A59E104C1D3` FOREIGN KEY (`created_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_88B01A59F92F3E70` FOREIGN KEY (`country_id`) REFERENCES `usf_country` (`id`);

--
-- Filtros para la tabla `agr_institutionals_areas`
--
ALTER TABLE `agr_institutionals_areas`
  ADD CONSTRAINT `FK_A43A4896BD0F409C` FOREIGN KEY (`area_id`) REFERENCES `usf_area` (`id`),
  ADD CONSTRAINT `FK_A43A4896C1A65D29` FOREIGN KEY (`institutional_id`) REFERENCES `agr_institutional` (`id`);

--
-- Filtros para la tabla `agr_national`
--
ALTER TABLE `agr_national`
  ADD CONSTRAINT `FK_41EE7875E0DFCA6C` FOREIGN KEY (`updated_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_41EE7875E104C1D3` FOREIGN KEY (`created_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_41EE7875F92F3E70` FOREIGN KEY (`country_id`) REFERENCES `usf_country` (`id`);

--
-- Filtros para la tabla `cln_client`
--
ALTER TABLE `cln_client`
  ADD CONSTRAINT `FK_C7440455BD0F409C` FOREIGN KEY (`area_id`) REFERENCES `usf_area` (`id`),
  ADD CONSTRAINT `FK_C7440455E0DFCA6C` FOREIGN KEY (`updated_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_C7440455E104C1D3` FOREIGN KEY (`created_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_C7440455F722092F` FOREIGN KEY (`country_birth_id`) REFERENCES `usf_country` (`id`),
  ADD CONSTRAINT `FK_C7440455F92F3E70` FOREIGN KEY (`country_id`) REFERENCES `usf_country` (`id`);

--
-- Filtros para la tabla `ext_application`
--
ALTER TABLE `ext_application`
  ADD CONSTRAINT `FK_AF8153D619EB6921` FOREIGN KEY (`client_id`) REFERENCES `cln_client` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_AF8153D67CA12A8F` FOREIGN KEY (`command_file_id`) REFERENCES `ext_command_file` (`id`),
  ADD CONSTRAINT `FK_AF8153D685564492` FOREIGN KEY (`create_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_AF8153D6954B9FA3` FOREIGN KEY (`manager_travel_plan_id`) REFERENCES `ext_manager_travel_plan` (`id`),
  ADD CONSTRAINT `FK_AF8153D6957A40DD` FOREIGN KEY (`proposed_client_id`) REFERENCES `cln_client` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_AF8153D6E0DFCA6C` FOREIGN KEY (`update_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `ext_departure`
--
ALTER TABLE `ext_departure`
  ADD CONSTRAINT `FK_45E9C67119EB6921` FOREIGN KEY (`client_id`) REFERENCES `cln_client` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_45E9C67185564492` FOREIGN KEY (`create_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_45E9C671ABF410D0` FOREIGN KEY (`passport_id`) REFERENCES `pas_passport` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_45E9C671E0DFCA6C` FOREIGN KEY (`update_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_9BEAF6AE3E030ACD` FOREIGN KEY (`application_id`) REFERENCES `ext_application` (`id`);

--
-- Filtros para la tabla `ext_economic`
--
ALTER TABLE `ext_economic`
  ADD CONSTRAINT `FK_63B716AABCB727D8` FOREIGN KEY (`exit_manager_travel_plan_id`) REFERENCES `ext_manager_travel_plan` (`id`),
  ADD CONSTRAINT `FK_63B716AABE6CAE90` FOREIGN KEY (`mission_id`) REFERENCES `ext_mission` (`id`);

--
-- Filtros para la tabla `ext_manager_travel_plan`
--
ALTER TABLE `ext_manager_travel_plan`
  ADD CONSTRAINT `FK_E605CB8D19EB6921` FOREIGN KEY (`client_id`) REFERENCES `cln_client` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_E605CB8D85564492` FOREIGN KEY (`create_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_E605CB8DE0DFCA6C` FOREIGN KEY (`update_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `ext_manager_travel_plans_countries`
--
ALTER TABLE `ext_manager_travel_plans_countries`
  ADD CONSTRAINT `FK_CC3543BF954B9FA3` FOREIGN KEY (`manager_travel_plan_id`) REFERENCES `ext_manager_travel_plan` (`id`),
  ADD CONSTRAINT `FK_CC3543BFF92F3E70` FOREIGN KEY (`country_id`) REFERENCES `usf_country` (`id`);

--
-- Filtros para la tabla `ext_mission`
--
ALTER TABLE `ext_mission`
  ADD CONSTRAINT `FK_83D8100B3E030ACD` FOREIGN KEY (`application_id`) REFERENCES `ext_application` (`id`),
  ADD CONSTRAINT `FK_83D8100B85564492` FOREIGN KEY (`create_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_83D8100BE0DFCA6C` FOREIGN KEY (`update_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_83D8100BF92F3E70` FOREIGN KEY (`country_id`) REFERENCES `usf_country` (`id`);

--
-- Filtros para la tabla `fst_postgraduate`
--
ALTER TABLE `fst_postgraduate`
  ADD CONSTRAINT `FK_3508142A591CC992` FOREIGN KEY (`course_id`) REFERENCES `usf_course` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_3508142ABB649746` FOREIGN KEY (`updated_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_3508142AE104C1D3` FOREIGN KEY (`created_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_3508142AF92F3E70` FOREIGN KEY (`country_id`) REFERENCES `usf_country` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `fst_undergraduate`
--
ALTER TABLE `fst_undergraduate`
  ADD CONSTRAINT `FK_DE64A90B58CDA09` FOREIGN KEY (`career_id`) REFERENCES `usf_career` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_DE64A90BB649746` FOREIGN KEY (`updated_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_DE64A90E104C1D3` FOREIGN KEY (`created_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_DE64A90F92F3E70` FOREIGN KEY (`country_id`) REFERENCES `usf_country` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `pas_application`
--
ALTER TABLE `pas_application`
  ADD CONSTRAINT `FK_5116FBC119EB6921` FOREIGN KEY (`client_id`) REFERENCES `cln_client` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_5116FBC185564492` FOREIGN KEY (`create_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_5116FBC1E0DFCA6C` FOREIGN KEY (`update_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `pas_control`
--
ALTER TABLE `pas_control`
  ADD CONSTRAINT `FK_1D0CD6BA662CFC2F` FOREIGN KEY (`delivers_client_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_1D0CD6BA85564492` FOREIGN KEY (`create_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_1D0CD6BAABF410D0` FOREIGN KEY (`passport_id`) REFERENCES `pas_passport` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_1D0CD6BACC879491` FOREIGN KEY (`receives_client_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_1D0CD6BAE0DFCA6C` FOREIGN KEY (`update_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `pas_passport`
--
ALTER TABLE `pas_passport`
  ADD CONSTRAINT `FK_B5A26E0819EB6921` FOREIGN KEY (`client_id`) REFERENCES `cln_client` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_B5A26E083E030ACD` FOREIGN KEY (`application_id`) REFERENCES `pas_application` (`id`),
  ADD CONSTRAINT `FK_B5A26E0885564492` FOREIGN KEY (`create_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_B5A26E08E0DFCA6C` FOREIGN KEY (`update_user_id`) REFERENCES `usr_user` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `usf_career`
--
ALTER TABLE `usf_career`
  ADD CONSTRAINT `FK_B25B6C84BD0F409C` FOREIGN KEY (`area_id`) REFERENCES `usf_area` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `usf_course`
--
ALTER TABLE `usf_course`
  ADD CONSTRAINT `FK_413E3469BD0F409C` FOREIGN KEY (`area_id`) REFERENCES `usf_area` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
