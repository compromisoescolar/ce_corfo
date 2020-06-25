-- MySQL dump 10.13  Distrib 5.7.27, for Linux (x86_64)
--
-- Host: localhost    Database: compromiso_escolar
-- ------------------------------------------------------
-- Server version	5.7.27-0ubuntu0.18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ce_comuna`
--

DROP TABLE IF EXISTS `ce_comuna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_comuna` (
  `id_ce_comuna` int(11) NOT NULL AUTO_INCREMENT,
  `ce_region_id_ce_region` int(11) NOT NULL,
  `ce_region_ce_pais_id_ce_pais` int(11) NOT NULL,
  PRIMARY KEY (`id_ce_comuna`),
  KEY `fk_ce_comuna_ce_region1_idx` (`ce_region_id_ce_region`,`ce_region_ce_pais_id_ce_pais`),
  CONSTRAINT `fk_ce_comuna_ce_region1` FOREIGN KEY (`ce_region_id_ce_region`, `ce_region_ce_pais_id_ce_pais`) REFERENCES `ce_region` (`id_ce_region`, `ce_pais_id_ce_pais`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_curso`
--

DROP TABLE IF EXISTS `ce_curso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_curso` (
  `id_ce_curso` int(11) NOT NULL AUTO_INCREMENT,
  `ce_curso_nombre` varchar(45) DEFAULT NULL,
  `ce_fk_establecimiento` int(11) DEFAULT NULL,
  `ce_docente_id_ce_docente` int(11) DEFAULT NULL,
  `ce_fk_nivel` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_ce_curso`),
  KEY `fk_ce_curso_ce_docente1_idx` (`ce_docente_id_ce_docente`),
  KEY `nivel_fk` (`ce_fk_nivel`),
  KEY `establecimiento` (`ce_fk_establecimiento`),
  CONSTRAINT `establecimiento` FOREIGN KEY (`ce_fk_establecimiento`) REFERENCES `ce_establecimiento` (`id_ce_establecimiento`),
  CONSTRAINT `nivel_fk` FOREIGN KEY (`ce_fk_nivel`) REFERENCES `ce_niveles` (`ce_id_niveles`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_dimension`
--

DROP TABLE IF EXISTS `ce_dimension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_dimension` (
  `di_id` int(11) NOT NULL AUTO_INCREMENT,
  `di_nombre` varchar(20) NOT NULL,
  `di_codigo` varchar(5) NOT NULL,
  PRIMARY KEY (`di_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_doc_documentos`
--

DROP TABLE IF EXISTS `ce_doc_documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_doc_documentos` (
  `doc_id` int(11) NOT NULL AUTO_INCREMENT,
  `doc_nombre` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `doc_ruta` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `doc_ruta_imagen_tipo` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `doc_extension` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `doc_id_seccion` int(11) NOT NULL,
  `id_tipo_talleres` int(11) DEFAULT NULL,
  PRIMARY KEY (`doc_id`),
  KEY `doc_id_seccion` (`doc_id_seccion`),
  KEY `fk_tipo_talleres` (`id_tipo_talleres`),
  CONSTRAINT `ce_doc_documentos_ibfk_1` FOREIGN KEY (`doc_id_seccion`) REFERENCES `ce_sec_seccion` (`sec_id`),
  CONSTRAINT `fk_tipo_talleres` FOREIGN KEY (`id_tipo_talleres`) REFERENCES `tipo_talleres` (`id_tip_taller`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_docente`
--

DROP TABLE IF EXISTS `ce_docente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_docente` (
  `id_ce_docente` int(11) NOT NULL AUTO_INCREMENT,
  `ce_docente_nombres` varchar(75) NOT NULL,
  `ce_docente_apellidos` varchar(75) NOT NULL,
  `ce_docente_run` varchar(15) NOT NULL,
  `ce_docente_email` varchar(75) DEFAULT NULL,
  `ce_establecimiento_id_ce_establecimiento` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_ce_docente`),
  KEY `fk_ce_docente_ce_establecimiento1_idx` (`ce_establecimiento_id_ce_establecimiento`)
) ENGINE=InnoDB AUTO_INCREMENT=237 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_encuesta_preguntas_grupo`
--

DROP TABLE IF EXISTS `ce_encuesta_preguntas_grupo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_encuesta_preguntas_grupo` (
  `id_ce_encuesta_preguntas` int(11) NOT NULL,
  `ce_grupos_id_ce_grupos` int(11) NOT NULL,
  `ce_preguntas_id_ce_preguntas` int(11) NOT NULL,
  `ce_encuestas_id_ce_encuestas` int(11) NOT NULL,
  PRIMARY KEY (`id_ce_encuesta_preguntas`),
  KEY `fk_ce_encuesta_preguntas_ce_grupos1_idx` (`ce_grupos_id_ce_grupos`),
  KEY `fk_ce_encuesta_preguntas_ce_preguntas1_idx` (`ce_preguntas_id_ce_preguntas`),
  KEY `fk_ce_encuesta_preguntas_ce_encuestas1_idx` (`ce_encuestas_id_ce_encuestas`),
  CONSTRAINT `fk_ce_encuesta_preguntas_ce_encuestas1` FOREIGN KEY (`ce_encuestas_id_ce_encuestas`) REFERENCES `ce_encuestas` (`id_ce_encuestas`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ce_encuesta_preguntas_ce_grupos1` FOREIGN KEY (`ce_grupos_id_ce_grupos`) REFERENCES `ce_grupos` (`id_ce_grupos`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ce_encuesta_preguntas_ce_preguntas1` FOREIGN KEY (`ce_preguntas_id_ce_preguntas`) REFERENCES `ce_preguntas` (`id_ce_preguntas`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_encuesta_resultado`
--

DROP TABLE IF EXISTS `ce_encuesta_resultado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_encuesta_resultado` (
  `id_ce_encuesta_resultado` int(11) NOT NULL AUTO_INCREMENT,
  `ce_participantes_token_fk` varchar(45) DEFAULT NULL,
  `fecha_inicio` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fecha_termino` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ce_p1` int(11) NOT NULL,
  `ce_p2` int(11) NOT NULL,
  `ce_p3` int(11) NOT NULL,
  `ce_p4` int(11) NOT NULL,
  `ce_p5` int(11) NOT NULL,
  `ce_p6` int(11) DEFAULT NULL,
  `ce_p7` int(11) NOT NULL,
  `ce_p8` int(11) NOT NULL,
  `ce_p9` int(11) NOT NULL,
  `ce_p10` int(11) NOT NULL,
  `ce_p11` int(11) NOT NULL,
  `ce_p12` int(11) NOT NULL,
  `ce_p13` int(11) NOT NULL,
  `ce_p14` int(11) NOT NULL,
  `ce_p15` int(11) NOT NULL,
  `ce_p16` int(11) NOT NULL,
  `ce_p17` int(11) NOT NULL,
  `ce_p18` int(11) NOT NULL,
  `ce_p19` int(11) NOT NULL,
  `ce_p20` int(11) NOT NULL,
  `ce_p21` int(11) NOT NULL,
  `ce_p22` int(11) NOT NULL,
  `ce_p23` int(11) NOT NULL,
  `ce_p24` int(11) NOT NULL,
  `ce_p25` int(11) NOT NULL,
  `ce_p26` int(11) NOT NULL,
  `ce_p27` int(11) NOT NULL,
  `ce_p28` int(11) NOT NULL,
  `ce_p29` int(11) NOT NULL,
  `ce_p30` int(11) NOT NULL,
  `ce_p31` int(11) NOT NULL,
  `ce_p32` int(11) NOT NULL,
  `ce_p33` int(11) NOT NULL,
  `ce_p34` int(11) NOT NULL,
  `ce_p35` int(11) NOT NULL,
  `ce_p36` int(11) NOT NULL,
  `ce_p37` int(11) NOT NULL,
  `ce_p38` int(11) NOT NULL,
  `ce_p39` int(11) NOT NULL,
  `ce_p40` int(11) NOT NULL,
  `ce_p41` int(11) NOT NULL,
  `ce_p42` int(11) NOT NULL,
  `ce_p43` int(11) NOT NULL,
  `ce_p44` int(11) NOT NULL,
  `ce_p45` int(11) NOT NULL,
  `ce_p46` int(11) NOT NULL,
  `ce_p47` int(11) NOT NULL,
  `ce_encuestas_id_ce_encuestas` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_ce_encuesta_resultado`),
  UNIQUE KEY `ce_participantes_token_fk` (`ce_participantes_token_fk`),
  KEY `fk_ce_encuesta_resultado_ce_participantes1_idx` (`ce_participantes_token_fk`),
  KEY `fk_ce_encuesta_resultado_ce_encuestas1_idx` (`ce_encuestas_id_ce_encuestas`)
) ENGINE=InnoDB AUTO_INCREMENT=5968 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_encuestas`
--

DROP TABLE IF EXISTS `ce_encuestas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_encuestas` (
  `id_ce_encuestas` int(11) NOT NULL AUTO_INCREMENT,
  `ce_encuestas_nombre` varchar(100) NOT NULL,
  `ce_encuesta_fecha_creacion` datetime NOT NULL,
  PRIMARY KEY (`id_ce_encuestas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_estable_curso_docente`
--

DROP TABLE IF EXISTS `ce_estable_curso_docente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_estable_curso_docente` (
  `id_esta_curs_doc` int(11) NOT NULL AUTO_INCREMENT,
  `ce_fk_establecimiento` int(11) DEFAULT NULL,
  `ce_fk_docente` int(11) DEFAULT NULL,
  `ce_fk_curso` int(11) DEFAULT NULL,
  `ce_fk_nivel` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_esta_curs_doc`),
  KEY `ce_fk_establecimiento` (`ce_fk_establecimiento`),
  KEY `ce_fk_docente` (`ce_fk_docente`),
  KEY `ce_fk_curo` (`ce_fk_curso`),
  KEY `ce_fk_nivel` (`ce_fk_nivel`),
  CONSTRAINT `ce_estable_curso_docente_ibfk_1` FOREIGN KEY (`ce_fk_establecimiento`) REFERENCES `ce_establecimiento` (`id_ce_establecimiento`),
  CONSTRAINT `ce_estable_curso_docente_ibfk_2` FOREIGN KEY (`ce_fk_docente`) REFERENCES `ce_docente` (`id_ce_docente`),
  CONSTRAINT `ce_estable_curso_docente_ibfk_3` FOREIGN KEY (`ce_fk_curso`) REFERENCES `ce_curso` (`id_ce_curso`),
  CONSTRAINT `ce_fk_nivel` FOREIGN KEY (`ce_fk_nivel`) REFERENCES `ce_niveles` (`ce_id_niveles`)
) ENGINE=InnoDB AUTO_INCREMENT=244 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_establecimiento`
--

DROP TABLE IF EXISTS `ce_establecimiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_establecimiento` (
  `id_ce_establecimiento` int(11) NOT NULL AUTO_INCREMENT,
  `ce_establecimiento_nombre` varchar(45) NOT NULL,
  `ce_establecimiento_rbd` varchar(45) NOT NULL,
  `id_pais` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_ce_establecimiento`),
  KEY `fk_id_pais` (`id_pais`),
  CONSTRAINT `fk_id_pais` FOREIGN KEY (`id_pais`) REFERENCES `ce_pais` (`id_ce_pais`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_establecimiento_sostenedor`
--

DROP TABLE IF EXISTS `ce_establecimiento_sostenedor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_establecimiento_sostenedor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sostenedor_id` int(11) DEFAULT NULL,
  `establecimiento_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ce_establecimiento_sostenedor_sostenedor` (`sostenedor_id`),
  KEY `ce_establecimiento_sostenedor_establecimiento` (`establecimiento_id`),
  CONSTRAINT `ce_establecimiento_sostenedor_establecimiento` FOREIGN KEY (`establecimiento_id`) REFERENCES `ce_establecimiento` (`id_ce_establecimiento`),
  CONSTRAINT `ce_establecimiento_sostenedor_sostenedor` FOREIGN KEY (`sostenedor_id`) REFERENCES `ce_sostenedor` (`id_soste`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_etimologia`
--

DROP TABLE IF EXISTS `ce_etimologia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_etimologia` (
  `id_eti` int(11) NOT NULL AUTO_INCREMENT,
  `text_1_ini` varchar(1000) COLLATE utf8mb4_spanish_ci NOT NULL,
  `text_1_intro` varchar(1000) COLLATE utf8mb4_spanish_ci NOT NULL,
  `id_pais` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_eti`),
  KEY `fk_pais` (`id_pais`),
  CONSTRAINT `fk_pais` FOREIGN KEY (`id_pais`) REFERENCES `ce_pais` (`id_ce_pais`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_excepciones`
--

DROP TABLE IF EXISTS `ce_excepciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_excepciones` (
  `id_excep` int(11) NOT NULL AUTO_INCREMENT,
  `nom_excep` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_excep`)
) ENGINE=InnoDB AUTO_INCREMENT=290 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_grupos`
--

DROP TABLE IF EXISTS `ce_grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_grupos` (
  `id_ce_grupos` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_ce_grupos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_niveles`
--

DROP TABLE IF EXISTS `ce_niveles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_niveles` (
  `ce_id_niveles` int(11) NOT NULL AUTO_INCREMENT,
  `ce_nombre` varchar(30) NOT NULL DEFAULT '0' COMMENT 'Nombre del curso',
  `ce_fecha_ingreso` timestamp NULL DEFAULT NULL,
  `ce_fecha_actualizacion` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ce_id_niveles`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_pais`
--

DROP TABLE IF EXISTS `ce_pais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_pais` (
  `id_ce_pais` int(11) NOT NULL AUTO_INCREMENT,
  `ce_pais_nombre` varchar(50) NOT NULL DEFAULT '0',
  `ce_region_id_ce_region` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_ce_pais`),
  KEY `fk_ce_pais_ce_region1_idx` (`ce_region_id_ce_region`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_participantes`
--

DROP TABLE IF EXISTS `ce_participantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_participantes` (
  `id_ce_participantes` int(11) NOT NULL AUTO_INCREMENT,
  `ce_estado_encuesta` tinyint(1) DEFAULT NULL,
  `ce_ruta_diploma` varchar(50) DEFAULT NULL,
  `ce_participantes_nombres` varchar(70) NOT NULL,
  `ce_participantes_apellidos` varchar(70) NOT NULL,
  `ce_participantes_run` varchar(15) DEFAULT NULL,
  `ce_participantes_fecha_nacimiento` date DEFAULT NULL,
  `ce_participantes_fecha_registro` timestamp NULL DEFAULT NULL,
  `ce_participanes_token` varchar(45) DEFAULT NULL,
  `ce_ciudad` varchar(50) DEFAULT NULL,
  `ce_establecimiento_id_ce_establecimiento` int(11) DEFAULT NULL,
  `ce_docente_id_ce_docente` int(11) DEFAULT NULL,
  `ce_curso_id_ce_curso` int(11) DEFAULT NULL,
  `ce_fk_nivel` int(11) DEFAULT NULL,
  `fk_sostenedor` int(11) DEFAULT NULL,
  `ce_pais_id_ce_pais` int(11) DEFAULT NULL,
  `ce_region_id_ce_region` int(11) DEFAULT NULL,
  `ce_comuna_id_ce_comuna` int(11) DEFAULT NULL,
  `ce_region_ce_pais_id_ce_pais` int(11) DEFAULT NULL,
  `ce_participantes_fecha_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ce_participantes`),
  KEY `fk_ce_participantes_ce_comuna1_idx` (`ce_comuna_id_ce_comuna`),
  KEY `fk_ce_participantes_ce_establecimiento1_idx` (`ce_establecimiento_id_ce_establecimiento`),
  KEY `fk_ce_participantes_ce_docente1_idx` (`ce_docente_id_ce_docente`),
  KEY `fk_ce_participantes_ce_curso1_idx` (`ce_curso_id_ce_curso`),
  KEY `fk_ce_participantes_ce_pais1_idx` (`ce_pais_id_ce_pais`),
  KEY `fk_ce_participantes_ce_region1_idx` (`ce_region_id_ce_region`,`ce_region_ce_pais_id_ce_pais`),
  KEY `fk_niveles` (`ce_fk_nivel`),
  KEY `fk_sostenedor` (`fk_sostenedor`),
  CONSTRAINT `fk_niveles` FOREIGN KEY (`ce_fk_nivel`) REFERENCES `ce_niveles` (`ce_id_niveles`),
  CONSTRAINT `fk_sostenedor` FOREIGN KEY (`fk_sostenedor`) REFERENCES `ce_sostenedor` (`id_soste`)
) ENGINE=InnoDB AUTO_INCREMENT=7517 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_preguntas`
--

DROP TABLE IF EXISTS `ce_preguntas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_preguntas` (
  `id_ce_preguntas` int(11) NOT NULL AUTO_INCREMENT,
  `ce_pregunta_nombre` varchar(250) NOT NULL,
  `ce_orden` int(11) DEFAULT NULL,
  `ce_preguntas_codigo` varchar(10) DEFAULT NULL,
  `ce_nivel` int(11) DEFAULT NULL,
  `ce_dimension_id` int(11) DEFAULT NULL,
  `ce_pais_id_ce_pais` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_ce_preguntas`),
  KEY `fk_ce_preguntas_ce_pais1_idx` (`ce_pais_id_ce_pais`),
  KEY `fk_ce_dimension` (`ce_dimension_id`),
  CONSTRAINT `fk_ce_dimension` FOREIGN KEY (`ce_dimension_id`) REFERENCES `ce_dimension` (`di_id`),
  CONSTRAINT `fk_ce_preguntas_ce_pais1` FOREIGN KEY (`ce_pais_id_ce_pais`) REFERENCES `ce_pais` (`id_ce_pais`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=308 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_region`
--

DROP TABLE IF EXISTS `ce_region`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_region` (
  `id_ce_region` int(11) NOT NULL AUTO_INCREMENT,
  `ce_pais_id_ce_pais` int(11) NOT NULL,
  PRIMARY KEY (`id_ce_region`,`ce_pais_id_ce_pais`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_rol_user`
--

DROP TABLE IF EXISTS `ce_rol_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_rol_user` (
  `id_user_rol` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario_fk` int(11) NOT NULL,
  `id_roles_fk` int(11) NOT NULL,
  PRIMARY KEY (`id_user_rol`),
  KEY `id_usuario_fk` (`id_usuario_fk`),
  KEY `id_roles_fk` (`id_roles_fk`),
  CONSTRAINT `ce_rol_user_ibfk_1` FOREIGN KEY (`id_usuario_fk`) REFERENCES `ce_usuarios` (`id_usu`),
  CONSTRAINT `ce_rol_user_ibfk_2` FOREIGN KEY (`id_roles_fk`) REFERENCES `ce_roles` (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=340 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_roles`
--

DROP TABLE IF EXISTS `ce_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_roles` (
  `id_rol` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(50) NOT NULL,
  `display_nombre_rol` varchar(50) DEFAULT NULL,
  `descripcion_rol` varchar(100) NOT NULL,
  `menu` longtext,
  `modulos_usuario` varchar(1500) NOT NULL DEFAULT '0',
  `fecha_creacion_rol` timestamp NULL DEFAULT NULL,
  `fecha_actualizacion` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_sec_seccion`
--

DROP TABLE IF EXISTS `ce_sec_seccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_sec_seccion` (
  `sec_id` int(11) NOT NULL AUTO_INCREMENT,
  `sec_nombre` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`sec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_sostenedor`
--

DROP TABLE IF EXISTS `ce_sostenedor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_sostenedor` (
  `id_soste` int(11) NOT NULL AUTO_INCREMENT,
  `nom_soste` varchar(100) NOT NULL,
  `apelli_soste` varchar(50) DEFAULT NULL,
  `run_soste` int(13) DEFAULT NULL,
  `fecha_registro_soste` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuario_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_soste`),
  KEY `ce_sostenedor_usuario` (`usuario_id`),
  CONSTRAINT `ce_sostenedor_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `ce_usuarios` (`id_usu`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ce_usuarios`
--

DROP TABLE IF EXISTS `ce_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_usuarios` (
  `id_usu` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_usu` varchar(50) NOT NULL,
  `contrasena_usu` varchar(80) NOT NULL COMMENT 'La contraseña viene encryptada con hash',
  `fecha_ingreso_usu` timestamp NULL DEFAULT NULL,
  `fecha_actualizacion` timestamp NULL DEFAULT NULL,
  `fk_establecimiento` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_usu`),
  UNIQUE KEY `nombre_usu` (`nombre_usu`),
  KEY `fk_establecimiento` (`fk_establecimiento`),
  CONSTRAINT `fk_establecimiento` FOREIGN KEY (`fk_establecimiento`) REFERENCES `ce_establecimiento` (`id_ce_establecimiento`)
) ENGINE=InnoDB AUTO_INCREMENT=343 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipo_talleres`
--

DROP TABLE IF EXISTS `tipo_talleres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_talleres` (
  `id_tip_taller` int(11) NOT NULL AUTO_INCREMENT,
  `nom_taller` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  PRIMARY KEY (`id_tip_taller`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-15 11:53:14
