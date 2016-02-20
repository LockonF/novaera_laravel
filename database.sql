#
# SQL Export
# Created by Querious (1010)
# Created: February 19, 2016 at 11:48:52 PM CST
# Encoding: Unicode (UTF-8)
#


DROP DATABASE IF EXISTS `Novaera`;
CREATE DATABASE `Novaera` DEFAULT CHARACTER SET latin1 DEFAULT COLLATE latin1_swedish_ci;
USE `Novaera`;




SET @PREVIOUS_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;


DROP TABLE IF EXISTS `Validacion_Criterio`;
DROP TABLE IF EXISTS `User`;
DROP TABLE IF EXISTS `TRL`;
DROP TABLE IF EXISTS `TransferenciaTecnologica`;
DROP TABLE IF EXISTS `TipoDescriptor`;
DROP TABLE IF EXISTS `TipoArchivo`;
DROP TABLE IF EXISTS `TareaEtapa`;
DROP TABLE IF EXISTS `RubrosApoyo`;
DROP TABLE IF EXISTS `ResultadoDescriptor`;
DROP TABLE IF EXISTS `RegistroProyecto`;
DROP TABLE IF EXISTS `ProyectoResultado`;
DROP TABLE IF EXISTS `ProyectoTRL`;
DROP TABLE IF EXISTS `ProyectoDescriptor`;
DROP TABLE IF EXISTS `Proyecto`;
DROP TABLE IF EXISTS `ProgramaFondeoDescriptor`;
DROP TABLE IF EXISTS `ProgramaFondeo_RubrosApoyo`;
DROP TABLE IF EXISTS `ProgramaFondeo`;
DROP TABLE IF EXISTS `Persona_Proyecto`;
DROP TABLE IF EXISTS `Persona_Organizacion`;
DROP TABLE IF EXISTS `Persona`;
DROP TABLE IF EXISTS `ParqueTecnologico`;
DROP TABLE IF EXISTS `Pais`;
DROP TABLE IF EXISTS `Organizacion_Proyecto`;
DROP TABLE IF EXISTS `Organizacion_Modalidad`;
DROP TABLE IF EXISTS `Organizacion`;
DROP TABLE IF EXISTS `Municipio`;
DROP TABLE IF EXISTS `ModeloNegocio`;
DROP TABLE IF EXISTS `Modalidad`;
DROP TABLE IF EXISTS `ImpactoYComercializacion`;
DROP TABLE IF EXISTS `EtapaProyecto`;
DROP TABLE IF EXISTS `EntidadFederativa`;
DROP TABLE IF EXISTS `Ejecucion`;
DROP TABLE IF EXISTS `Direccion`;
DROP TABLE IF EXISTS `Descriptor_Persona`;
DROP TABLE IF EXISTS `Descriptor_Organizacion`;
DROP TABLE IF EXISTS `Descriptor`;
DROP TABLE IF EXISTS `Convocatoria_Modalidad`;
DROP TABLE IF EXISTS `Convocatoria`;
DROP TABLE IF EXISTS `Contacto`;
DROP TABLE IF EXISTS `Asociacion`;
DROP TABLE IF EXISTS `Archivos`;


CREATE TABLE `Archivos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Ruta` varchar(500) DEFAULT NULL,
  `ProgramaFondeo_id` int(11) DEFAULT NULL,
  `idOrganizacionLegal` int(11) DEFAULT NULL,
  `idEjecucion` int(11) DEFAULT NULL,
  `idTareaEtapa` int(11) DEFAULT NULL,
  `idImpacto` int(11) DEFAULT NULL,
  `idModeloNegocio` int(11) DEFAULT NULL,
  `idTransferenciaTecnologica` int(11) DEFAULT NULL,
  `validado` tinyint(1) DEFAULT NULL,
  `idTipoArchivo` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Archivos_TipoArchivo1_idx` (`idTipoArchivo`),
  KEY `fk_Archivos_Ejecucion1_idx` (`idEjecucion`),
  KEY `fk_Archivos_TareaEtapa1_idx` (`idTareaEtapa`),
  KEY `fk_Archivos_ImpactoyComercializacion1_idx` (`idImpacto`),
  KEY `fk_Archivos_ModeloNegocio1_idx` (`idModeloNegocio`),
  KEY `fk_Archivos_ProgramaFondeo1_idx` (`ProgramaFondeo_id`),
  KEY `fk_Archivos_TransferenciaTecnologica1_idx` (`idTransferenciaTecnologica`),
  CONSTRAINT `fk_Archivos_Ejecucion1` FOREIGN KEY (`idEjecucion`) REFERENCES `Ejecucion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Archivos_ImpactoyComercializacion1` FOREIGN KEY (`idImpacto`) REFERENCES `ImpactoYComercializacion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Archivos_ModeloNegocio1` FOREIGN KEY (`idModeloNegocio`) REFERENCES `ModeloNegocio` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Archivos_ProgramaFondeo1` FOREIGN KEY (`ProgramaFondeo_id`) REFERENCES `ProgramaFondeo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Archivos_TareaEtapa1` FOREIGN KEY (`idTareaEtapa`) REFERENCES `TareaEtapa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Archivos_TipoArchivo1` FOREIGN KEY (`idTipoArchivo`) REFERENCES `TipoArchivo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Archivos_TransferenciaTecnologica1` FOREIGN KEY (`idTransferenciaTecnologica`) REFERENCES `TransferenciaTecnologica` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


CREATE TABLE `Asociacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idModalidad` int(11) NOT NULL,
  `idProgramaFondeo` int(11) NOT NULL,
  `Convocatoria_id` int(11) NOT NULL,
  `RegistroProyecto_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Modalidad_has_ProgramaFondeo_ProgramaFondeo1_idx` (`idProgramaFondeo`),
  KEY `fk_Modalidad_has_ProgramaFondeo_Modalidad1_idx` (`idModalidad`),
  KEY `fk_Asociacion_Convocatoria1_idx` (`Convocatoria_id`),
  KEY `fk_Asociacion_RegistroProyecto1_idx` (`RegistroProyecto_id`),
  CONSTRAINT `fk_Asociacion_Convocatoria1` FOREIGN KEY (`Convocatoria_id`) REFERENCES `Convocatoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Asociacion_RegistroProyecto1` FOREIGN KEY (`RegistroProyecto_id`) REFERENCES `RegistroProyecto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Modalidad_has_ProgramaFondeo_Modalidad1` FOREIGN KEY (`idModalidad`) REFERENCES `Modalidad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Modalidad_has_ProgramaFondeo_ProgramaFondeo1` FOREIGN KEY (`idProgramaFondeo`) REFERENCES `ProgramaFondeo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Contacto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `CorreoElectronico` varchar(100) DEFAULT NULL,
  `TelefonoLocal` varchar(45) DEFAULT NULL,
  `TelefonoCelular` varchar(45) DEFAULT NULL,
  `TelefonoOficina` varchar(45) DEFAULT NULL,
  `Fax` varchar(45) DEFAULT NULL,
  `PaginaWeb` varchar(200) DEFAULT NULL,
  `idPersona` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Contacto_Persona1_idx` (`idPersona`),
  CONSTRAINT `fk_Contacto_Persona1` FOREIGN KEY (`idPersona`) REFERENCES `Persona` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


CREATE TABLE `Convocatoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) DEFAULT NULL,
  `FechaInicio` date DEFAULT NULL,
  `FechaTermino` date DEFAULT NULL,
  `Requisitos` json DEFAULT NULL,
  `MontosMaximosTotales` float DEFAULT NULL,
  `Activo` tinyint(1) DEFAULT NULL,
  `ProgramaAsociado` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;


CREATE TABLE `Convocatoria_Modalidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idModalidad` int(11) NOT NULL,
  `idConvocatoria` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_convocatoria_modalidad` (`idConvocatoria`,`idModalidad`) USING BTREE,
  KEY `fk_Modalidad_has_Convocatoria_Convocatoria1_idx` (`idModalidad`),
  KEY `fk_Modalidad_has_Convocatoria_Modalidad1_idx` (`idConvocatoria`),
  CONSTRAINT `fk_Modalidad_has_Convocatoria_Convocatoria1` FOREIGN KEY (`idModalidad`) REFERENCES `Modalidad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Modalidad_has_Convocatoria_Modalidad1` FOREIGN KEY (`idConvocatoria`) REFERENCES `Convocatoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `Descriptor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(300) DEFAULT NULL,
  `Descripcion` varchar(450) DEFAULT NULL,
  `idTipoDescriptor` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `Activo` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_Descriptor_TipoDescriptor1_idx` (`idTipoDescriptor`),
  CONSTRAINT `fk_Descriptor_TipoDescriptor1` FOREIGN KEY (`idTipoDescriptor`) REFERENCES `TipoDescriptor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;


CREATE TABLE `Descriptor_Organizacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `FechaInicio` date DEFAULT NULL,
  `FechaTermino` date DEFAULT NULL,
  `idOrganizacion` int(11) NOT NULL,
  `idDescriptor` int(11) NOT NULL,
  `TipoResultado` varchar(45) DEFAULT NULL,
  `NumeroRegistro` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Descriptor_Organizacion1_idx` (`idOrganizacion`),
  KEY `fk_Descriptor_CatalogoDescriptores1_idx` (`idDescriptor`),
  CONSTRAINT `fk_Descriptor_CatalogoDescriptores1` FOREIGN KEY (`idDescriptor`) REFERENCES `Descriptor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Descriptor_Organizacion1` FOREIGN KEY (`idOrganizacion`) REFERENCES `Organizacion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Descriptor_Persona` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idPersona` int(11) NOT NULL,
  `idDescriptor` int(11) NOT NULL,
  `FechaInicio` date DEFAULT NULL,
  `FechaTermino` date DEFAULT NULL,
  `TipoResultado` varchar(45) DEFAULT NULL,
  `NumeroRegistro` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Descriptor_Pesona_Persona1_idx` (`idPersona`),
  KEY `fk_Descriptor_Pesona_Descriptor1_idx` (`idDescriptor`),
  CONSTRAINT `fk_Descriptor_Pesona_Descriptor1` FOREIGN KEY (`idDescriptor`) REFERENCES `Descriptor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Descriptor_Pesona_Persona1` FOREIGN KEY (`idPersona`) REFERENCES `Persona` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Direccion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Calle` varchar(50) NOT NULL,
  `NumExt` varchar(4) NOT NULL,
  `NumInt` varchar(5) DEFAULT NULL,
  `Colonia` varchar(45) DEFAULT NULL,
  `CP` varchar(20) DEFAULT NULL,
  `idMunicipio` int(11) NOT NULL,
  `idContacto` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Direccion_Municipio1_idx` (`idMunicipio`),
  KEY `fk_Direccion_Contacto1_idx` (`idContacto`),
  CONSTRAINT `fk_Direccion_Contacto1` FOREIGN KEY (`idContacto`) REFERENCES `Contacto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Direccion_Municipio1` FOREIGN KEY (`idMunicipio`) REFERENCES `Municipio` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Ejecucion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Requisitos` varchar(1000) DEFAULT '0',
  `AnalisisEntornoP` varchar(1000) DEFAULT '0' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `FactibilidadTecnicaP` varchar(1000) DEFAULT '0',
  `FactibilidadEconomicaP` varchar(1000) DEFAULT '0',
  `FactibilidadComercialP` varchar(1000) DEFAULT '0',
  `BenchmarkComercialP` varchar(1000) DEFAULT '0',
  `BenchmarkTecnologicoP` varchar(1000) DEFAULT '0',
  `RecursosHumanosP` varchar(1000) DEFAULT '0',
  `RecursosFinancierosP` varchar(1000) DEFAULT '0',
  `RecursosTecnologicosP` varchar(1000) DEFAULT '0',
  `RecursosMaterialesP` varchar(1000) DEFAULT '0',
  `idProyecto` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Ejecucion_Proyecto1_idx` (`idProyecto`),
  CONSTRAINT `fk_Ejecucion_Proyecto1` FOREIGN KEY (`idProyecto`) REFERENCES `Proyecto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


CREATE TABLE `EntidadFederativa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(2) DEFAULT NULL,
  `Nombre` varchar(45) NOT NULL,
  `abrev` varchar(16) DEFAULT NULL,
  `idPais` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_EntidadFederativa_Pais_idx` (`idPais`),
  CONSTRAINT `fk_EntidadFederativa_Pais` FOREIGN KEY (`idPais`) REFERENCES `Pais` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `EtapaProyecto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  `idProyecto` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_EtapaProyecto_Proyecto1_idx` (`idProyecto`),
  CONSTRAINT `fk_EtapaProyecto_Proyecto1` FOREIGN KEY (`idProyecto`) REFERENCES `Proyecto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;


CREATE TABLE `ImpactoYComercializacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ImpactoAmbiental` varchar(250) DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `ImpactoCientifico` varchar(250) DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `ImpactoTecnologico` varchar(250) DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `ImpactoSocial` varchar(250) DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `ImpactoEconomico` varchar(250) DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `PropuestaDeValor` varchar(250) DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `SegmentosDeClientes` varchar(250) DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `SolucionPropuesta` varchar(250) DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `Metricas` varchar(250) DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `SolucionActual` varchar(250) DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `idProyecto` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ImpactoyComercializacion_Proyecto1_idx` (`idProyecto`),
  CONSTRAINT `fk_ImpactoyComercializacion_Proyecto1` FOREIGN KEY (`idProyecto`) REFERENCES `Proyecto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


CREATE TABLE `Modalidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProgramaFondeo` int(11) NOT NULL,
  `Nombre` varchar(45) DEFAULT NULL,
  `Montos` double DEFAULT NULL,
  `CriteriosEvaluacion` varchar(450) DEFAULT NULL,
  `Entregables` varchar(1000) DEFAULT NULL,
  `FigurasApoyo` varchar(450) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Modalidad_ProgramaFondeo1_idx` (`idProgramaFondeo`),
  CONSTRAINT `fk_Modalidad_ProgramaFondeo1` FOREIGN KEY (`idProgramaFondeo`) REFERENCES `ProgramaFondeo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `ModeloNegocio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Canales` varchar(250) DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `VentajaCompetitiva` varchar(250) DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `Problematica` varchar(250) DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `Costos` varchar(250) DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `Ingresos` varchar(250) DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `ActividadesClave` varchar(250) DEFAULT NULL,
  `RelacionesCliente` varchar(250) DEFAULT NULL,
  `RecursosClave` varchar(250) DEFAULT NULL,
  `AliadosClave` varchar(250) DEFAULT NULL,
  `idProyecto` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ModeloNegocio_Proyecto1_idx` (`idProyecto`),
  CONSTRAINT `fk_ModeloNegocio_Proyecto1` FOREIGN KEY (`idProyecto`) REFERENCES `Proyecto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `Municipio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(3) DEFAULT NULL,
  `Nombre` varchar(50) NOT NULL,
  `sigla` varchar(4) DEFAULT NULL,
  `idEntidadFederativa` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Municipio_EntidadFederativa1_idx` (`idEntidadFederativa`),
  CONSTRAINT `fk_Municipio_EntidadFederativa1` FOREIGN KEY (`idEntidadFederativa`) REFERENCES `EntidadFederativa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Organizacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(100) DEFAULT NULL,
  `Descripcion` varchar(450) DEFAULT NULL,
  `Mision` varchar(450) DEFAULT NULL,
  `RFC` varchar(45) DEFAULT NULL,
  `Vision` varchar(450) DEFAULT NULL,
  `idContacto` int(11) DEFAULT NULL,
  `RepresentanteLegal` varchar(200) DEFAULT NULL,
  `RazonSocial` varchar(45) DEFAULT NULL,
  `Archivos` json DEFAULT NULL,
  `isValidated` tinyint(1) DEFAULT '0',
  `RENIECyTValidated` tinyint(1) DEFAULT '0',
  `RFCValidated` tinyint(1) DEFAULT '0',
  `Giro` varchar(100) DEFAULT NULL,
  `DireccionFiscal` varchar(500) DEFAULT NULL,
  `ActaValidated` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Organizacion_Contacto1_idx` (`idContacto`),
  CONSTRAINT `fk_Organizacion_Contacto1` FOREIGN KEY (`idContacto`) REFERENCES `Contacto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


CREATE TABLE `Organizacion_Modalidad` (
  `id` int(11) NOT NULL,
  `Solicitud` varchar(45) DEFAULT NULL,
  `MontoSolicitado` float DEFAULT NULL,
  `MontoApoyado` float DEFAULT NULL,
  `FechaRegistro` date DEFAULT NULL,
  `FechaCierre` date DEFAULT NULL,
  `idOrganizacion` int(11) NOT NULL,
  `idModalidad` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Organizacion_Modalidad_Organizacion1_idx` (`idOrganizacion`),
  KEY `fk_Organizacion_Modalidad_Modalidad1_idx` (`idModalidad`),
  CONSTRAINT `fk_Organizacion_Modalidad_Modalidad1` FOREIGN KEY (`idModalidad`) REFERENCES `Modalidad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Organizacion_Modalidad_Organizacion1` FOREIGN KEY (`idOrganizacion`) REFERENCES `Organizacion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Organizacion_Proyecto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idOrganizacion` int(11) NOT NULL,
  `idProyecto` int(11) NOT NULL,
  `Owner` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Organizacion_has_Proyecto_Proyecto1_idx` (`idProyecto`),
  KEY `fk_Organizacion_has_Proyecto_Organizacion1_idx` (`idOrganizacion`),
  CONSTRAINT `fk_Organizacion_has_Proyecto_Organizacion1` FOREIGN KEY (`idOrganizacion`) REFERENCES `Organizacion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Organizacion_has_Proyecto_Proyecto1` FOREIGN KEY (`idProyecto`) REFERENCES `Proyecto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


CREATE TABLE `Pais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(45) NOT NULL,
  `Abrev` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


CREATE TABLE `ParqueTecnologico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;


CREATE TABLE `Persona` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(60) DEFAULT NULL,
  `ApellidoP` varchar(45) DEFAULT NULL,
  `ApellidoM` varchar(45) DEFAULT NULL,
  `Notas` varchar(450) DEFAULT NULL,
  `Descripcion` varchar(450) DEFAULT NULL,
  `isValidated` int(11) NOT NULL DEFAULT '1',
  `idUser` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Persona_User1_idx` (`idUser`),
  CONSTRAINT `fk_Persona_User1` FOREIGN KEY (`idUser`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;


CREATE TABLE `Persona_Organizacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Puesto` varchar(45) DEFAULT NULL,
  `FechaInicio` date DEFAULT NULL,
  `FechaTermino` varchar(45) DEFAULT NULL,
  `idPersona` int(11) NOT NULL,
  `idOrganizacion` int(11) NOT NULL,
  `Owner` tinyint(1) DEFAULT '0',
  `WritePermissions` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_persona_organizacion` (`idPersona`,`idOrganizacion`),
  KEY `fk_Persona_Organizacion_Persona1_idx` (`idPersona`),
  KEY `fk_Persona_Organizacion_Organizacion1_idx` (`idOrganizacion`),
  CONSTRAINT `fk_Persona_Organizacion_Organizacion1` FOREIGN KEY (`idOrganizacion`) REFERENCES `Organizacion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Persona_Organizacion_Persona1` FOREIGN KEY (`idPersona`) REFERENCES `Persona` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


CREATE TABLE `Persona_Proyecto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProyecto` int(11) NOT NULL,
  `idPersona` int(11) NOT NULL,
  `WritePermissions` tinyint(1) DEFAULT NULL,
  `Owner` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_proyecto_persona` (`idProyecto`,`idPersona`) USING BTREE,
  KEY `fk_Proyecto_has_Persona_Persona1_idx` (`idPersona`),
  KEY `fk_Proyecto_has_Persona_Proyecto1_idx` (`idProyecto`),
  CONSTRAINT `fk_Proyecto_has_Persona_Persona1` FOREIGN KEY (`idPersona`) REFERENCES `Persona` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Proyecto_has_Persona_Proyecto1` FOREIGN KEY (`idProyecto`) REFERENCES `Proyecto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;


CREATE TABLE `ProgramaFondeo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(45) DEFAULT NULL,
  `PublicoObjetivo` varchar(1000) DEFAULT NULL,
  `FondoTotal` float DEFAULT NULL,
  `Descripcion` varchar(1000) DEFAULT NULL,
  `RubrosDeApoyo` varchar(1000) DEFAULT NULL,
  `CriteriosElegibilidad` varchar(1000) DEFAULT NULL,
  `Archivos` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;


CREATE TABLE `ProgramaFondeo_RubrosApoyo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProgramaFondeo` int(11) NOT NULL,
  `idRubrosApoyo` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ProgramaFondeo_has_RubrosApoyo_RubrosApoyo1_idx` (`idRubrosApoyo`),
  KEY `fk_ProgramaFondeo_has_RubrosApoyo_ProgramaFondeo1_idx` (`idProgramaFondeo`),
  CONSTRAINT `fk_ProgramaFondeo_has_RubrosApoyo_ProgramaFondeo1` FOREIGN KEY (`idProgramaFondeo`) REFERENCES `ProgramaFondeo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ProgramaFondeo_has_RubrosApoyo_RubrosApoyo1` FOREIGN KEY (`idRubrosApoyo`) REFERENCES `RubrosApoyo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `ProgramaFondeoDescriptor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `observaciones` varchar(500) DEFAULT NULL,
  `idDescriptor` int(11) NOT NULL,
  `idProgramaFondeo` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ProgramaFondeoDescriptor_Descriptor1_idx` (`idDescriptor`),
  KEY `fk_ProgramaFondeoDescriptor_ProgramaFondeo1_idx` (`idProgramaFondeo`),
  CONSTRAINT `fk_ProgramaFondeoDescriptor_Descriptor1` FOREIGN KEY (`idDescriptor`) REFERENCES `Descriptor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ProgramaFondeoDescriptor_ProgramaFondeo1` FOREIGN KEY (`idProgramaFondeo`) REFERENCES `ProgramaFondeo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


CREATE TABLE `Proyecto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(45) DEFAULT NULL,
  `Descripcion` varchar(450) DEFAULT NULL,
  `Antecedentes` varchar(450) DEFAULT NULL,
  `Justificacion` varchar(450) DEFAULT NULL,
  `Objetivos` varchar(450) DEFAULT NULL,
  `Alcances` varchar(450) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;


CREATE TABLE `ProyectoDescriptor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `observaciones` varchar(600) DEFAULT NULL,
  `idProyecto` int(11) NOT NULL,
  `idDescriptor` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_proyectoDescriptor_Proyecto1_idx` (`idProyecto`),
  KEY `fk_proyectoDescriptor_Descriptor1_idx` (`idDescriptor`),
  CONSTRAINT `fk_proyectoDescriptor_Descriptor1` FOREIGN KEY (`idDescriptor`) REFERENCES `Descriptor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_proyectoDescriptor_Proyecto1` FOREIGN KEY (`idProyecto`) REFERENCES `Proyecto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


CREATE TABLE `ProyectoTRL` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProyecto` int(11) NOT NULL,
  `idTRL` int(11) NOT NULL,
  `Descripcion` varchar(450) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Proyecto_has_TRL_TRL1_idx` (`idTRL`),
  KEY `fk_Proyecto_has_TRL_Proyecto1_idx` (`idProyecto`),
  CONSTRAINT `fk_Proyecto_has_TRL_Proyecto1` FOREIGN KEY (`idProyecto`) REFERENCES `Proyecto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Proyecto_has_TRL_TRL1` FOREIGN KEY (`idTRL`) REFERENCES `TRL` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;


CREATE TABLE `ProyectoResultado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProyectoTRL` int(11) NOT NULL,
  `Tipo` varchar(500) NOT NULL,
  `Nombre` varchar(500) NOT NULL,
  `Resumen` varchar(500) CHARACTER SET cp850 NOT NULL,
  `NumeroRegistro` varchar(45) DEFAULT NULL,
  `Status` varchar(500) DEFAULT NULL,
  `PaisesProteccion` json DEFAULT NULL,
  `AreaDeAplicacion` varchar(45) DEFAULT NULL,
  `PlanDeExplotacion` varchar(45) DEFAULT NULL,
  `Avance` varchar(1000) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `FechaAprobacion` date DEFAULT NULL,
  `created_at` varchar(45) DEFAULT NULL,
  `updated_at` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Resultado_ProyectoTRL1_idx` (`idProyectoTRL`),
  CONSTRAINT `fk_Resultado_ProyectoTRL1` FOREIGN KEY (`idProyectoTRL`) REFERENCES `ProyectoTRL` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;


CREATE TABLE `RegistroProyecto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProyecto` int(11) NOT NULL,
  `idConvocatoriaModalidad` int(11) NOT NULL,
  `idParque` int(11) NOT NULL,
  `Solicitud` varchar(1000) DEFAULT NULL,
  `MontoSolicitado` double DEFAULT '0',
  `MontoApoyado` double DEFAULT '0',
  `idTRLInicial` int(11) DEFAULT '0',
  `idTRLFinal` int(11) DEFAULT '0',
  `FechaRegistro` date DEFAULT NULL,
  `FechaCierre` date DEFAULT NULL,
  `Requisitos` json DEFAULT NULL,
  `Resultado` varchar(1000) DEFAULT NULL,
  `Validado` enum('Aceptado','Rechazado','Pendiente','Culminado') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Proyecto_Modalidad_Proyecto1_idx` (`idProyecto`),
  KEY `fk_Proyecto_Modalidad_ParqueTecnologico1_idx` (`idParque`),
  KEY `fk_RegistroProyecto_Convocatoria_Modalidad1_idx` (`idConvocatoriaModalidad`),
  CONSTRAINT `fk_Proyecto_Modalidad_ParqueTecnologico1` FOREIGN KEY (`idParque`) REFERENCES `ParqueTecnologico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Proyecto_Modalidad_Proyecto1` FOREIGN KEY (`idProyecto`) REFERENCES `Proyecto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_RegistroProyecto_Convocatoria_Modalidad1` FOREIGN KEY (`idConvocatoriaModalidad`) REFERENCES `Convocatoria_Modalidad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `ResultadoDescriptor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idDescriptor` int(11) NOT NULL,
  `idResultado` int(11) NOT NULL,
  `FechaRegistro` date DEFAULT NULL,
  `FechaAprobacion` date DEFAULT NULL,
  `PCT` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ResultadoDescriptor_Descriptor1_idx` (`idDescriptor`),
  KEY `fk_ResultadoDescriptor_ProyectoResultado1_idx` (`idResultado`),
  CONSTRAINT `fk_ResultadoDescriptor_Descriptor1` FOREIGN KEY (`idDescriptor`) REFERENCES `Descriptor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ResultadoDescriptor_ProyectoResultado1` FOREIGN KEY (`idResultado`) REFERENCES `ProyectoResultado` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `RubrosApoyo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(45) DEFAULT NULL,
  `Descripcion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `TareaEtapa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `from` date DEFAULT NULL,
  `to` date DEFAULT NULL,
  `idEtapa` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_TareaEtapa_EtapaProyecto1_idx` (`idEtapa`),
  CONSTRAINT `fk_TareaEtapa_EtapaProyecto1` FOREIGN KEY (`idEtapa`) REFERENCES `EtapaProyecto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;


CREATE TABLE `TipoArchivo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(50) NOT NULL,
  `Aplicable` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;


CREATE TABLE `TipoDescriptor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(300) DEFAULT NULL,
  `Activo` tinyint(1) DEFAULT NULL,
  `Aplicable` enum('Persona','Organizacion','Proyecto','Resultado','ProgramaFondeo','Todos') NOT NULL DEFAULT 'Persona',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '		',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;


CREATE TABLE `TransferenciaTecnologica` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ProductosDePropiedad` varchar(45) DEFAULT NULL,
  `ProcesosDeTransferencia` varchar(45) DEFAULT NULL,
  `ValuacionTecnologica` varchar(45) DEFAULT NULL,
  `idProyecto` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Proyecto_TT_Proyecto1_idx` (`idProyecto`),
  CONSTRAINT `fk_Proyecto_TT_Proyecto1` FOREIGN KEY (`idProyecto`) REFERENCES `Proyecto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


CREATE TABLE `TRL` (
  `id` int(11) NOT NULL,
  `Descripcion` varchar(200) DEFAULT NULL,
  `Nivel` int(2) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(300) NOT NULL,
  `type` enum('User','Supervisor','Admin') NOT NULL DEFAULT 'User',
  `isValidated` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;


CREATE TABLE `Validacion_Criterio` (
  `id` int(11) NOT NULL,
  `idProyectoModalidad` int(11) NOT NULL,
  `idCriterio` int(11) NOT NULL,
  `Validado` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Validacion_Modalidad_Proyecto_Modalidad1_idx` (`idProyectoModalidad`),
  KEY `fk_Validacion_Modalidad_Modaliad_Criterios1_idx` (`idCriterio`),
  CONSTRAINT `fk_Validacion_Modalidad_Proyecto_Modalidad1` FOREIGN KEY (`idProyectoModalidad`) REFERENCES `RegistroProyecto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




LOCK TABLES `Archivos` WRITE;
ALTER TABLE `Archivos` DISABLE KEYS;
INSERT INTO `Archivos` (`id`, `Ruta`, `ProgramaFondeo_id`, `idOrganizacionLegal`, `idEjecucion`, `idTareaEtapa`, `idImpacto`, `idModeloNegocio`, `idTransferenciaTecnologica`, `validado`, `idTipoArchivo`, `created_at`, `updated_at`) VALUES 
	(1,'files/Nuevo/ImpactoAmbiental_BD.png',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,12,'2016-02-17 22:35:29','2016-02-17 22:35:29'),
	(2,'files/Nuevo/ImpactoSocial_Database.sql',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,15,'2016-02-17 22:36:17','2016-02-17 22:36:17'),
	(3,'files/Nuevo/ImpactoEconomico_Gantt Proyecto Novaera.gan',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,16,'2016-02-17 22:36:35','2016-02-17 22:36:35'),
	(4,'files/Nuevo/FactibilidadTecnicaP_Novaera.paw',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,3,'2016-02-17 22:42:30','2016-02-17 22:42:30');
ALTER TABLE `Archivos` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Asociacion` WRITE;
ALTER TABLE `Asociacion` DISABLE KEYS;
ALTER TABLE `Asociacion` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Contacto` WRITE;
ALTER TABLE `Contacto` DISABLE KEYS;
INSERT INTO `Contacto` (`id`, `CorreoElectronico`, `TelefonoLocal`, `TelefonoCelular`, `TelefonoOficina`, `Fax`, `PaginaWeb`, `idPersona`, `created_at`, `updated_at`) VALUES 
	(1,'correo@correo.com','123456','134556','123456','fax134','www.pagina.com.mx',1,'2016-02-07 03:20:26','2016-02-07 03:20:26'),
	(2,'elariosj@gmail.com','5521992387',NULL,'5521992387',NULL,NULL,8,'2016-02-20 00:16:05','2016-02-20 00:16:05'),
	(3,'elariosj@gmail.com','5521992387',NULL,'5521992387',NULL,NULL,9,'2016-02-20 00:21:32','2016-02-20 00:21:32');
ALTER TABLE `Contacto` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Convocatoria` WRITE;
ALTER TABLE `Convocatoria` DISABLE KEYS;
INSERT INTO `Convocatoria` (`id`, `Nombre`, `FechaInicio`, `FechaTermino`, `Requisitos`, `MontosMaximosTotales`, `Activo`, `ProgramaAsociado`, `created_at`, `updated_at`) VALUES 
	(3,'Convocatoria','2015-01-01','2015-12-31','[{"Nombre": "Tener El Sistema Listo", "Descripcion": "El requisito"}, {"Nombre": "Otro Requisito", "Descripcion": "Nada mas por llenar"}]',30000000,NULL,1,'2016-02-06 01:39:49','2016-02-06 01:39:49'),
	(4,'Convocatoria 2','2015-01-01','2015-12-31','[{"Nombre": "Tener El Sistema Listo", "Descripcion": "El requisito"}, {"Nombre": "Otro Requisito", "Descripcion": "Nada mas por llenar"}]',30000000,NULL,1,NULL,NULL),
	(5,'Convocatoria','2015-01-01','2015-12-31','[{"Nombre": "Tener El Sistema Listo", "Descripcion": "El requisito"}, {"Nombre": "Otro Requisito", "Descripcion": "Nada mas por llenar"}]',30000000,NULL,3,'2016-02-14 05:20:29','2016-02-14 05:21:17');
ALTER TABLE `Convocatoria` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Convocatoria_Modalidad` WRITE;
ALTER TABLE `Convocatoria_Modalidad` DISABLE KEYS;
INSERT INTO `Convocatoria_Modalidad` (`id`, `idModalidad`, `idConvocatoria`, `created_at`, `updated_at`) VALUES 
	(1,1,5,'2016-02-14 05:21:17','2016-02-14 05:21:17');
ALTER TABLE `Convocatoria_Modalidad` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Descriptor` WRITE;
ALTER TABLE `Descriptor` DISABLE KEYS;
INSERT INTO `Descriptor` (`id`, `Titulo`, `Descripcion`, `idTipoDescriptor`, `created_at`, `updated_at`, `Activo`) VALUES 
	(4,'Automotriz y autopartes','Sector Automotriz del Estado de Guanajuato',2,'2016-02-19 23:55:17','2016-02-20 00:03:28',1),
	(5,'Productos químicos','Productos químicos',2,'2016-02-20 00:02:40','2016-02-20 00:02:40',1),
	(6,'Textil - Cuero – Calzado Proveeduría','Textil - Cuero – Calzado Proveeduría',2,'2016-02-20 00:03:54','2016-02-20 00:03:54',1),
	(7,'Agroindustrial','Agroindustrial',2,'2016-02-20 00:04:09','2016-02-20 00:04:09',1),
	(8,'Minería de metálicos','Minería de metálicos',2,'2016-02-20 00:04:27','2016-02-20 00:04:27',1),
	(9,'Productos para la construcción','Productos para la construcción',2,'2016-02-20 00:04:48','2016-02-20 00:04:48',1),
	(10,'Turismo','Turismo',2,'2016-02-20 00:05:03','2016-02-20 00:05:03',1),
	(11,'Agricultura','Agricultura',3,'2016-02-20 00:08:28','2016-02-20 00:08:28',1),
	(12,'Pesca','Pesca',3,'2016-02-20 00:08:42','2016-02-20 00:08:42',1),
	(13,'Ganadería','Ganadería',3,'2016-02-20 00:08:57','2016-02-20 00:08:57',1),
	(14,'Minería','Minería',3,'2016-02-20 00:09:05','2016-02-20 00:09:05',1),
	(15,'Aprovechamiento forestal','Aprovechamiento forestal',3,'2016-02-20 00:09:12','2016-02-20 00:09:12',1),
	(16,'Piel y calzado','Piel y calzado',5,'2016-02-20 00:09:43','2016-02-20 00:09:43',1),
	(17,'Industria manufacturera','Industria manufacturera',5,'2016-02-20 00:09:55','2016-02-20 00:09:55',1),
	(18,'Construcción','Construcción',5,'2016-02-20 00:10:09','2016-02-20 00:10:09',1),
	(19,'Transporte y comunicaciones','Transporte y comunicaciones',6,'2016-02-20 00:10:47','2016-02-20 00:10:47',1),
	(20,'Comercio','Comercio',6,'2016-02-20 00:10:54','2016-02-20 00:10:54',1),
	(21,'Turismo','Turismo',6,'2016-02-20 00:11:04','2016-02-20 00:11:04',1);
ALTER TABLE `Descriptor` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Descriptor_Organizacion` WRITE;
ALTER TABLE `Descriptor_Organizacion` DISABLE KEYS;
ALTER TABLE `Descriptor_Organizacion` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Descriptor_Persona` WRITE;
ALTER TABLE `Descriptor_Persona` DISABLE KEYS;
ALTER TABLE `Descriptor_Persona` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Direccion` WRITE;
ALTER TABLE `Direccion` DISABLE KEYS;
ALTER TABLE `Direccion` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Ejecucion` WRITE;
ALTER TABLE `Ejecucion` DISABLE KEYS;
INSERT INTO `Ejecucion` (`id`, `Requisitos`, `AnalisisEntornoP`, `FactibilidadTecnicaP`, `FactibilidadEconomicaP`, `FactibilidadComercialP`, `BenchmarkComercialP`, `BenchmarkTecnologicoP`, `RecursosHumanosP`, `RecursosFinancierosP`, `RecursosTecnologicosP`, `RecursosMaterialesP`, `idProyecto`, `created_at`, `updated_at`) VALUES 
	(1,'<p>Requerimientos</p>','<p>Entorno</p>','<p>Factibilidad Técnica</p>','<p>Factibilidad Eco</p>','<p>Factibilidad Comercial</p>','<p>Benchmark Comercial</p>','<p>Benchmark Tecnológico</p>','<p>Recursos Humanos</p>','<p>Recursos Financieros</p>','<p>Recursos Tecnológicos</p>','<p>Recursos Materiales</p>',8,'2016-02-17 22:42:30','2016-02-17 22:46:20'),
	(2,'<p>Requerimiento del proyecto</p>','<p>Entorno del Proyecot</p>','<p>Factibilidad Tecnica</p>','<p>Factibilidad Economica</p>','<p>Factibilidad Comercial</p>','<p>Benchmark</p>','<p>BenchMark tecnologicos</p>','<p>Recursos humanos</p>','<p>REcursos Financieros</p>','<p>Recursos Tecnologicos</p>','<p>Recursos Materiales</p>',11,'2016-02-20 01:26:20','2016-02-20 01:27:25');
ALTER TABLE `Ejecucion` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `EntidadFederativa` WRITE;
ALTER TABLE `EntidadFederativa` DISABLE KEYS;
ALTER TABLE `EntidadFederativa` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `EtapaProyecto` WRITE;
ALTER TABLE `EtapaProyecto` DISABLE KEYS;
INSERT INTO `EtapaProyecto` (`id`, `name`, `description`, `idProyecto`, `created_at`, `updated_at`) VALUES 
	(3,'Nueva Etap','Descripcion',6,'2016-02-09 18:43:21','2016-02-09 18:43:21'),
	(6,'Primera Etapa','Fase de Planeación',8,'2016-02-17 22:47:44','2016-02-17 22:47:44'),
	(7,'Segunda Etapa','Diseño',8,'2016-02-17 22:47:45','2016-02-17 22:47:45');
ALTER TABLE `EtapaProyecto` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ImpactoYComercializacion` WRITE;
ALTER TABLE `ImpactoYComercializacion` DISABLE KEYS;
INSERT INTO `ImpactoYComercializacion` (`id`, `ImpactoAmbiental`, `ImpactoCientifico`, `ImpactoTecnologico`, `ImpactoSocial`, `ImpactoEconomico`, `PropuestaDeValor`, `SegmentosDeClientes`, `SolucionPropuesta`, `Metricas`, `SolucionActual`, `idProyecto`, `created_at`, `updated_at`) VALUES 
	(1,'<p>Impacto Ambiental</p>','<p>Impacto Científico</p>','<p>Impacto Tecnológico</p>',NULL,NULL,'<p>Texto</p>','<p>Más Texto</p>','<p>Aún Más Texto</p>','<p>Otro texto más</p>','<p>Finalizar</p>',8,'2016-02-17 22:35:28','2016-02-17 22:37:02'),
	(2,'<p><span lang="ES">Este proyecto prevé un impacto ambiental positivo, ya que la el producto propuesto favorece al suelo tratando el biocontrol de un hongo causante de enfermedad en un cultivo de importancia y también favoreciendo el proceso de biorre','<p><span lang="ES"> El desarrollo de este producto depende de la investigación para solucionar un problema real usando el biocontrol y los mecanismos de antagonismo y competición microbiano, así mismo la evaluación de los resultados y posibles mejora','<p><span lang="ES"> Con este proyecto se busca desarrollar no solo un producto, sino una opción además de los químicos para tratar enfermedades en los suelos, la aplicación de la biotecnología en el desarrollo de este producto asegura que es viable, ','<p><span lang="ES"> El beneficio directo es hacia los agricultores ya que se tendrá un mayor volumen productivo, cultivos de mejor calidad y sin padecer esta enfermedad, ayudando de distintas comunidades productoras y se establecen alternativas para ','<p><span lang="ES">La actividad económica y comercial de la agricultura en especial del cultivo de cebolla se ve beneficiada al tener un mejor producto, mejores rendimientos de cosecha lo que permite vender el producto en este y mercados extranjeros,','<p><span lang="ES">La pudrición blanca en cebolla es una enfermedad que afecta a los agricultores y a este producto ya que genera pérdidas en el volumen de cosecha e impide el uso de este suelo para cultivar la cebolla ya que es una enfermedad altame','<p><span lang="ES"></span></p><p>Este proyecto pretende formular un producto liquido capaz combatir esta enfermedad mediante el uso de un microorganismo antagónico a una alta concentración el cual coloniza los esclerocios enrollan las hifas, deforman','<p><span lang="ES">Elcontrol biológico de la enfermedad de la pudrición blanca en el suelo es unaalternativa al uso de químicos ya que no solo se encarga de combatirla, tambiénbrinda mecanismos por los cuales el suelo es enriquecida y los cultivosveg','<p>asas  dsfsd</p>','<p><span lang="ES"> Existen diversas formas en cómo se ha tratado esta enfermedad, el combate cultural que comprende la rotación de cultivos, inundación, combate físico por aumento de temperatura en suelo, combate químico que comprende su uso como tr',11,'2016-02-20 01:18:34','2016-02-20 01:19:52');
ALTER TABLE `ImpactoYComercializacion` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Modalidad` WRITE;
ALTER TABLE `Modalidad` DISABLE KEYS;
INSERT INTO `Modalidad` (`id`, `idProgramaFondeo`, `Nombre`, `Montos`, `CriteriosEvaluacion`, `Entregables`, `FigurasApoyo`, `created_at`, `updated_at`) VALUES 
	(1,3,'Otra Modalidad de P1',300000,'Criterios','Lista de Entregables','Abogado','2016-02-14 05:19:34','2016-02-14 05:19:34');
ALTER TABLE `Modalidad` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ModeloNegocio` WRITE;
ALTER TABLE `ModeloNegocio` DISABLE KEYS;
INSERT INTO `ModeloNegocio` (`id`, `Canales`, `VentajaCompetitiva`, `Problematica`, `Costos`, `Ingresos`, `ActividadesClave`, `RelacionesCliente`, `RecursosClave`, `AliadosClave`, `idProyecto`, `created_at`, `updated_at`) VALUES 
	(1,'<p>1212</p>','<p>1212</p>','<p>1212</p>','<p>1212</p>','<p>1212</p>','<p>1212</p>','<p>1212</p>','<p>1212</p>','<p>1212</p>',11,'2016-02-20 01:22:24','2016-02-20 01:22:24');
ALTER TABLE `ModeloNegocio` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Municipio` WRITE;
ALTER TABLE `Municipio` DISABLE KEYS;
ALTER TABLE `Municipio` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Organizacion` WRITE;
ALTER TABLE `Organizacion` DISABLE KEYS;
INSERT INTO `Organizacion` (`id`, `Titulo`, `Descripcion`, `Mision`, `RFC`, `Vision`, `idContacto`, `RepresentanteLegal`, `RazonSocial`, `Archivos`, `isValidated`, `RENIECyTValidated`, `RFCValidated`, `Giro`, `DireccionFiscal`, `ActaValidated`, `created_at`, `updated_at`) VALUES 
	(1,'La Organizacion','Una Organizacion','Ser una organizacion','FARD921018','Seremos una organizacion',1,'Chadwick','Empresa',NULL,1,0,0,NULL,NULL,0,NULL,'2016-02-08 07:37:27'),
	(2,'Mi Organizacion','Una Nueva Organizacion','Ser la mejor organizacion','FARD921018','Seremos la mejor organizacion dentro de 20 años',1,'Chadwick Carreto Arellano','Empresa S.A de C.V',NULL,1,0,0,NULL,NULL,0,'2016-02-07 03:20:28','2016-02-08 07:37:27'),
	(3,'Organización','Lalala','Ser los mejores en todo','FARD921018','Tener una vision',NULL,'Chadwick','x S.A de C.V',NULL,1,1,1,NULL,NULL,1,'2016-02-15 21:23:12','2016-02-15 21:23:12'),
	(4,'Organización de Adán','Una Nueva Organizacion','Ser la mejor organizacion','FARD921018','Seremos la mejor organizacion dentro de 20 años',1,'Chadwick Carreto Arellano','Empresa S.A de C.V',NULL,0,0,0,NULL,NULL,0,'2016-02-19 07:12:40','2016-02-19 07:12:40');
ALTER TABLE `Organizacion` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Organizacion_Modalidad` WRITE;
ALTER TABLE `Organizacion_Modalidad` DISABLE KEYS;
ALTER TABLE `Organizacion_Modalidad` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Organizacion_Proyecto` WRITE;
ALTER TABLE `Organizacion_Proyecto` DISABLE KEYS;
INSERT INTO `Organizacion_Proyecto` (`id`, `idOrganizacion`, `idProyecto`, `Owner`, `created_at`, `updated_at`) VALUES 
	(2,2,2,1,NULL,NULL),
	(3,2,6,1,NULL,NULL);
ALTER TABLE `Organizacion_Proyecto` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Pais` WRITE;
ALTER TABLE `Pais` DISABLE KEYS;
INSERT INTO `Pais` (`id`, `Nombre`, `Abrev`) VALUES 
	(1,'Mexico','MEX'),
	(2,'Alemania','GER');
ALTER TABLE `Pais` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ParqueTecnologico` WRITE;
ALTER TABLE `ParqueTecnologico` DISABLE KEYS;
INSERT INTO `ParqueTecnologico` (`id`, `Nombre`) VALUES 
	(1,'Parque Tecnológico 100'),
	(2,'Parque IberoInnovación'),
	(3,'Parque de Innovación De La Salle'),
	(4,'Parque de Innovación Agrobioteg'),
	(5,'Parque Guanajuato Tecno Parque'),
	(6,'Parque Tecnológico SanMiguelense'),
	(7,'Centro Mexicano de Energías Renovables (CEMER)');
ALTER TABLE `ParqueTecnologico` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Persona` WRITE;
ALTER TABLE `Persona` DISABLE KEYS;
INSERT INTO `Persona` (`id`, `Nombre`, `ApellidoP`, `ApellidoM`, `Notas`, `Descripcion`, `isValidated`, `idUser`, `created_at`, `updated_at`) VALUES 
	(1,'Daniel','Franco','Rodríguez',NULL,NULL,0,2,'2016-02-03 04:55:15','2016-02-19 07:00:49'),
	(2,'Persona','De','Prueba',NULL,NULL,0,1,'2016-02-04 07:42:56','2016-02-20 05:14:57'),
	(3,'Daniel','Franco','Rodríguez',NULL,NULL,0,3,'2016-02-17 22:28:00','2016-02-17 22:28:00'),
	(7,'adan2','sdsd','sd',NULL,NULL,1,6,'2016-02-19 05:56:04','2016-02-20 05:16:22'),
	(8,'Edgar','Larios','Tapia',NULL,NULL,1,9,'2016-02-20 00:15:50','2016-02-20 00:15:50'),
	(9,'Edgar','Larios','Tapia',NULL,NULL,1,8,'2016-02-20 00:21:11','2016-02-20 00:21:11');
ALTER TABLE `Persona` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Persona_Organizacion` WRITE;
ALTER TABLE `Persona_Organizacion` DISABLE KEYS;
INSERT INTO `Persona_Organizacion` (`id`, `Puesto`, `FechaInicio`, `FechaTermino`, `idPersona`, `idOrganizacion`, `Owner`, `WritePermissions`, `created_at`, `updated_at`) VALUES 
	(1,'CEO','2014-01-01',NULL,1,2,1,1,NULL,NULL),
	(2,'C.E.O','2016-02-19',NULL,1,3,1,1,NULL,NULL),
	(3,'CEO','2014-01-01',NULL,7,4,1,1,NULL,NULL);
ALTER TABLE `Persona_Organizacion` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Persona_Proyecto` WRITE;
ALTER TABLE `Persona_Proyecto` DISABLE KEYS;
INSERT INTO `Persona_Proyecto` (`id`, `idProyecto`, `idPersona`, `WritePermissions`, `Owner`, `created_at`, `updated_at`) VALUES 
	(4,7,1,1,1,NULL,NULL),
	(5,8,3,1,1,NULL,NULL),
	(6,10,7,1,1,NULL,NULL),
	(7,11,9,1,1,NULL,NULL);
ALTER TABLE `Persona_Proyecto` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ProgramaFondeo` WRITE;
ALTER TABLE `ProgramaFondeo` DISABLE KEYS;
INSERT INTO `ProgramaFondeo` (`id`, `Titulo`, `PublicoObjetivo`, `FondoTotal`, `Descripcion`, `RubrosDeApoyo`, `CriteriosElegibilidad`, `Archivos`, `created_at`, `updated_at`) VALUES 
	(3,'Programa de Fondeo 2','Publico de todas las edades',20000000,'La Descripcion','Algunos Rubros','Algunos Criterios','{"DescripcionFile": "fondeos/3/Descripcion_BD.png", "RubrosDeApoyoFile": null, "CriteriosDeElegibilidadFile": null}','2016-02-12 03:50:06','2016-02-20 03:05:17'),
	(4,'hhhhh','Emprendedores',100000,'<p>dsdddasdsas</p>','<p>ssdsddssdsddsds</p>','<p>ssdsdsdsddssd</p>','{"DescripcionFile": null, "RubrosDeApoyoFile": null, "CriteriosDeElegibilidadFile": null}','2016-02-17 23:13:37','2016-02-17 23:13:37'),
	(5,'PROGRAMA	ESTATAL DE DESARROLLO DE EMPRESAS DE','En esta Convocatoria podrán participar los Parques del Sistema de Parques  e  Innovación,  NOVAERA, quienes prestarán servicios a los beneficiarios finales, que podrán ser personas físicas y/o morales establecidas en el Estado de Guanajuato. Para participar,  los  beneficiarios  finales  deberán  hacerlo  a través de alguno de los Parques del Sistema de Parques Tecnológicos e Innovación del Estado de Guanajuato, NOVAERA.',0,'<p><span style="text-align: justify;">El</span><span style="text-align: justify;"> </span><span style="text-align: justify;">Gobierno</span><span style="text-align: justify;"> </span><span style="text-align: justify;">del</span><span style="text-align: justify;"> </span><span style="text-align: justify;">Estado</span><span style="text-align: justify;"> </span><span style="text-align: justify;">de</span><span style="text-align: justify;"> </span><span style="text-align: justify;">Guanajuato,</span><span style="text-align: justify;"> </span><span style="text-align: justify;">a</span><span style="text-align: justify;"> </span><span style="text-align: justify;">través</span><span style="text-align: justify;"></span><span style="text-align: justify;">de</span><span style="text-align: justify;"> </span><span style="text-align: justify;">la</span><span style="text-align: justify;"> </span><span style="text-align: justify;">Secretaría</span><span style="text-align: justify;"> </span><span styl','<p class="TableParagraph"><b>MONTO MÁXIMO DE APOYO POR SOLICITUD</b><br/></p><p class="TableParagraph"><span></span></p><p class="TableParagraph"><b><span>TRANSFERENCIA<span> </span>DE<span> </span>TECNOLOGÍA</span></b><span></span></p><p class="TableParagraph"><span>Evaluación </span><span>$70,000.00</span></p><p class="TableParagraph"><span></span></p><p class="TableParagraph"><span>Comercialización </span><span>$80,000.00</span></p><p class="TableParagraph"><span></span></p><p class="TableParagraph"><b><span>CREACIÓN<span> </span>DE<span> </span>EMPRESAS<span> </span>DE<span> </span>BASE<span> </span>TECNOLÓGICA</span></b><span></span></p><p class="TableParagraph"><span>Formalización <span></span>de<span> </span>empresa<span> </span>de<span> </span>base<span> </span>tecnológica </span><span>$26,000.00</span></p><p class="TableParagraph"><span></span></p><p class="TableParagraph"><b><span>HOSPEDAJE<span> </span>EN<span> </span>PARQUE<span></span>DE<span> </span>NOVAERA</span></b><spa','<p class="MsoBodyText" style="text-align: justify;">Cada<span> </span>miembro<span> </span>del<span> </span>Comité<span> </span>de<span> </span>Revisión<span><span> </span></span>emitirá<span><span> </span></span>una<span><span> </span></span>recomendación<span><span> </span></span>por<span><span> </span></span>solicitud,<span><span> </span></span>basada<span><span> </span></span>en<span><span> </span></span>la<span> </span>pertinencia,<span> </span>el<span> </span>impacto<span> </span>potencial<span> </span>y<span> </span>la<span> </span>viabilidad<span> </span>en<span> </span>la<span> </span>implementación<span> </span>del<span> </span>producto<span> </span>y/o<span> </span>servicio<span> </span>presentado.<span> </span>Las<span> </span>solicitudes<span> </span>con<span> </span>al<span> </span>menos<span> </span>una<span> </span>recomendación<span> </span>positiva<span> </span>serán<span> </span>presentadas<span> </span>ante<span> </span>el<span> </span>Comité<span> </span>Evaluador.','{"DescripcionFile": null, "RubrosDeApoyoFile": null, "CriteriosDeElegibilidadFile": null}','2016-02-19 23:45:14','2016-02-19 23:45:19');
ALTER TABLE `ProgramaFondeo` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ProgramaFondeo_RubrosApoyo` WRITE;
ALTER TABLE `ProgramaFondeo_RubrosApoyo` DISABLE KEYS;
ALTER TABLE `ProgramaFondeo_RubrosApoyo` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ProgramaFondeoDescriptor` WRITE;
ALTER TABLE `ProgramaFondeoDescriptor` DISABLE KEYS;
INSERT INTO `ProgramaFondeoDescriptor` (`id`, `observaciones`, `idDescriptor`, `idProgramaFondeo`, `created_at`, `updated_at`) VALUES 
	(2,NULL,4,5,NULL,NULL);
ALTER TABLE `ProgramaFondeoDescriptor` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Proyecto` WRITE;
ALTER TABLE `Proyecto` DISABLE KEYS;
INSERT INTO `Proyecto` (`id`, `Titulo`, `Descripcion`, `Antecedentes`, `Justificacion`, `Objetivos`, `Alcances`, `created_at`, `updated_at`) VALUES 
	(2,'Proyecto 2','Lalalala','Lalalala','Lalalala','xD','Alcances','2016-02-09 18:32:02','2016-02-09 18:32:02'),
	(6,'Proyecto 3','Lalalala','Lalalala','Lalalala','xD','Alcances','2016-02-09 18:34:12','2016-02-09 18:34:12'),
	(7,'Proyecto de Prueba','Lalalala','Lalalala','Lalalala','xD','Alcances','2016-02-10 01:19:44','2016-02-10 01:19:44'),
	(8,'Proyecto 1','Descripción','<p>Antecedentes</p>','<p>Justificación</p>','<p>Objetivo</p>','<p>Alcances</p>','2016-02-17 22:34:30','2016-02-17 22:34:30'),
	(9,'prueba1','prueba1','<p>adada</p>','<p>adad</p>','<p>adada</p>','<p>adada</p>','2016-02-19 05:20:52','2016-02-19 05:20:52'),
	(10,'asd','asd','asd','asd','asd','asd',NULL,NULL),
	(11,'Elaboración de tratamiento para Sclerotium ce','Este proyecto plantea la formulación y validación de un producto capaz de tratar la enfermedad llamada pudrición blanca que ataca a la cebolla, causada por el hongo Sclerotium cepivorum el cual es difícil de combatir. \n\n','<p style="text-align: justify;">Esteproyecto plantea la formulación y validación de un producto capaz de tratar la enfermedadllamada pudrición blanca que ataca a la cebolla, causada por el hongo <i>Sclerotium cepivorum</i> el cual es difícil decombatir. </p><p style="text-align: justify;">Deacuerdo diversas investigaciones y estudios, el hongo <i>Trichoderma sp </i>es un antagónico y buen biocontrolador para estehongo patógeno, además que cuenta ','<p>La pudrición blanca en cebolla es una enfermedad que afecta a los agricultores y a este producto ya que genera pérdidas en el volumen de cosecha e impide el uso de este suelo para cultivar la cebolla ya que es una enfermedad altamente latente y fácil de propagar.<br/></p>','<p>Este proyectopretende formular un producto liquido capaz combatir esta enfermedad medianteel uso de un microorganismo antagónico a una alta concentración el cualcoloniza los esclerocios enrollan las hifas, deforman y lisan a este patógeno,además este producto contara con otras cepas microbianas que mejorando lascondiciones del suelo facilitando el desarrollo y viabilidad del hongoantagónico, asimismo de participar en el proceso de biorremediac','<p>Listo -------</p>','2016-02-20 00:26:56','2016-02-20 00:26:58');
ALTER TABLE `Proyecto` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ProyectoDescriptor` WRITE;
ALTER TABLE `ProyectoDescriptor` DISABLE KEYS;
INSERT INTO `ProyectoDescriptor` (`id`, `observaciones`, `idProyecto`, `idDescriptor`, `created_at`, `updated_at`) VALUES 
	(1,NULL,11,7,NULL,NULL);
ALTER TABLE `ProyectoDescriptor` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ProyectoTRL` WRITE;
ALTER TABLE `ProyectoTRL` DISABLE KEYS;
INSERT INTO `ProyectoTRL` (`id`, `idProyecto`, `idTRL`, `Descripcion`, `Fecha`, `created_at`, `updated_at`) VALUES 
	(2,9,1,'ssss','2016-02-18','2016-02-19 05:21:12','2016-02-19 05:21:12'),
	(3,10,1,'asas','2016-02-19','2016-02-19 06:04:53','2016-02-19 06:04:53');
ALTER TABLE `ProyectoTRL` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ProyectoResultado` WRITE;
ALTER TABLE `ProyectoResultado` DISABLE KEYS;
INSERT INTO `ProyectoResultado` (`id`, `idProyectoTRL`, `Tipo`, `Nombre`, `Resumen`, `NumeroRegistro`, `Status`, `PaisesProteccion`, `AreaDeAplicacion`, `PlanDeExplotacion`, `Avance`, `Fecha`, `FechaAprobacion`, `created_at`, `updated_at`) VALUES 
	(3,2,'Proceso','Un Producto','El producto es Pro',NULL,'Sin iniciar',NULL,'Metalurgia','El Plan','Se ha completado una fase del proyecto','2015-12-01',NULL,'2016-02-19 05:33:17','2016-02-19 05:33:17'),
	(5,2,'Proceso','adada','aad',NULL,'Sin iniciar','[]','adad','adad','adad','2016-02-03',NULL,'2016-02-19 05:46:17','2016-02-19 05:46:17'),
	(6,2,'Patente','ada','adad','adadad',NULL,'"[{\\"id\\":1,\\"Nombre\\":\\"Mexico\\",\\"Abrev\\":\\"MEX\\"},{\\"id\\":2,\\"Nombre\\":\\"Alemania\\",\\"Abrev\\":\\"GER\\"}]"',NULL,'adadad',NULL,'2016-02-18',NULL,'2016-02-19 05:47:40','2016-02-19 05:47:40');
ALTER TABLE `ProyectoResultado` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `RegistroProyecto` WRITE;
ALTER TABLE `RegistroProyecto` DISABLE KEYS;
ALTER TABLE `RegistroProyecto` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ResultadoDescriptor` WRITE;
ALTER TABLE `ResultadoDescriptor` DISABLE KEYS;
ALTER TABLE `ResultadoDescriptor` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `RubrosApoyo` WRITE;
ALTER TABLE `RubrosApoyo` DISABLE KEYS;
ALTER TABLE `RubrosApoyo` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `TareaEtapa` WRITE;
ALTER TABLE `TareaEtapa` DISABLE KEYS;
INSERT INTO `TareaEtapa` (`id`, `name`, `description`, `from`, `to`, `idEtapa`, `created_at`, `updated_at`) VALUES 
	(3,'Nueva Tarea',NULL,'2016-01-10','2016-01-27',3,'2016-02-09 18:43:21','2016-02-09 18:43:21'),
	(4,'Nueva Tarea',NULL,'2016-01-28','2016-02-10',3,'2016-02-09 18:43:21','2016-02-09 18:43:21'),
	(7,'Análisis y Diseño',NULL,'2016-01-19','2016-02-09',6,'2016-02-17 22:47:44','2016-02-17 22:47:44'),
	(8,'Nueva Tarea',NULL,'2016-02-12','2016-03-03',6,'2016-02-17 22:47:44','2016-02-17 22:47:44'),
	(9,'Diseño de la Base de Datos',NULL,'2016-01-25','2016-02-13',7,'2016-02-17 22:47:45','2016-02-17 22:47:45'),
	(10,'Nueva Tarea',NULL,'2016-02-18','2016-03-08',7,'2016-02-17 22:47:45','2016-02-17 22:47:45');
ALTER TABLE `TareaEtapa` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `TipoArchivo` WRITE;
ALTER TABLE `TipoArchivo` DISABLE KEYS;
INSERT INTO `TipoArchivo` (`id`, `Titulo`, `Aplicable`, `created_at`, `updated_at`) VALUES 
	(1,'Requisitos','Ejecucion',NULL,NULL),
	(2,'AnalisisEntornoP','Ejecucion',NULL,NULL),
	(3,'FactibilidadTecnicaP','Ejecucion',NULL,NULL),
	(4,'FactibilidadEconomicaP','Ejecucion',NULL,NULL),
	(5,'FactibilidadComercialP','Ejecucion',NULL,NULL),
	(6,'BenchmarkComercialP','Ejecucion',NULL,NULL),
	(7,'BenchmarkTecnologicoP','Ejecucion',NULL,NULL),
	(8,'RecursosHumanosP','Ejecucion',NULL,NULL),
	(9,'RecursosFinancierosP','Ejecucion',NULL,NULL),
	(10,'RecursosTecnologicosP','Ejecucion',NULL,NULL),
	(11,'RecursosMaterialesP','Ejecucion',NULL,NULL),
	(12,'ImpactoAmbiental','Impacto',NULL,NULL),
	(13,'ImpactoCientifico','Impacto',NULL,NULL),
	(14,'ImpactoTecnologico','Impacto',NULL,NULL),
	(15,'ImpactoSocial','Impacto',NULL,NULL),
	(16,'ImpactoEconomico','Impacto',NULL,NULL),
	(17,'PropuestaDeValor','Impacto',NULL,NULL),
	(18,'SegmentosDeClientes','Impacto',NULL,NULL),
	(19,'SolucionPropuesta','Impacto',NULL,NULL),
	(20,'Metricas','Impacto',NULL,NULL),
	(21,'SolucionActual','Impacto',NULL,NULL),
	(22,'Canales','ModeloNegocio',NULL,NULL),
	(23,'VentajaCompetitiva','ModeloNegocio',NULL,NULL),
	(24,'Problematica','ModeloNegocio',NULL,NULL),
	(25,'Costos','ModeloNegocio',NULL,NULL),
	(26,'Ingresos','ModeloNegocio',NULL,NULL),
	(27,'ActividadesClave','ModeloNegocio',NULL,NULL),
	(28,'RelacionesCliente','ModeloNegocio',NULL,NULL),
	(29,'RecursosClave','ModeloNegocio',NULL,NULL),
	(30,'AliadosClave','ModeloNegocio',NULL,NULL);
ALTER TABLE `TipoArchivo` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `TipoDescriptor` WRITE;
ALTER TABLE `TipoDescriptor` DISABLE KEYS;
INSERT INTO `TipoDescriptor` (`id`, `Nombre`, `Activo`, `Aplicable`, `created_at`, `updated_at`) VALUES 
	(2,'Sector Estratégico del  Estado de Guanajuato',1,'','2016-02-19 23:54:02','2016-02-20 00:03:01'),
	(3,'Sector Primario',0,'','2016-02-19 23:54:30','2016-02-20 00:08:06'),
	(4,'Industria de Aplicacion',NULL,'','2016-02-19 23:54:50','2016-02-19 23:54:50'),
	(5,'Sector Secundario',NULL,'','2016-02-20 00:09:31','2016-02-20 00:09:31'),
	(6,'Sector Terceario',NULL,'','2016-02-20 00:10:31','2016-02-20 00:10:31');
ALTER TABLE `TipoDescriptor` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `TransferenciaTecnologica` WRITE;
ALTER TABLE `TransferenciaTecnologica` DISABLE KEYS;
INSERT INTO `TransferenciaTecnologica` (`id`, `ProductosDePropiedad`, `ProcesosDeTransferencia`, `ValuacionTecnologica`, `idProyecto`, `created_at`, `updated_at`) VALUES 
	(1,'<p>Producto</p>','<p>Proceso</p>','<p>Evaluación</p>',8,'2016-02-17 22:49:06','2016-02-17 22:49:06'),
	(2,'<p>Otro Registro</p>','<p>Nuevo Proceso</p>','<p>Nueva Evaluación</p>',8,'2016-02-17 22:49:42','2016-02-17 22:49:42');
ALTER TABLE `TransferenciaTecnologica` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `TRL` WRITE;
ALTER TABLE `TRL` DISABLE KEYS;
INSERT INTO `TRL` (`id`, `Descripcion`, `Nivel`, `created_at`, `updated_at`) VALUES 
	(1,'Idea',0,NULL,NULL),
	(2,'Investigación Básica',1,NULL,NULL),
	(3,'Formulación de la tecnología',2,NULL,NULL),
	(4,'Investigación Aplicada',3,NULL,NULL),
	(5,'Unidad de Desarrollo de Prototipo a Pequeña Escala',4,NULL,NULL),
	(6,'Unidad de Desarrollo de Prototipo a Gran Escala',5,NULL,NULL),
	(7,'Sistemas Prototipo',6,NULL,NULL),
	(8,'Sistemas de Demostración',7,NULL,NULL),
	(9,'Primer Sistema de Tipo Comercial',8,NULL,NULL),
	(10,'Aplicación Operacional completa',9,NULL,NULL);
ALTER TABLE `TRL` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `User` WRITE;
ALTER TABLE `User` DISABLE KEYS;
INSERT INTO `User` (`id`, `username`, `password`, `type`, `isValidated`, `created_at`, `updated_at`) VALUES 
	(1,'demo','$2y$10$uCHVSUra3c8.EGIccE.i5eS9iT39pt4oiW9sBieDwCoQqGI6tF0b2','User',0,'2016-02-03 01:07:51','2016-02-03 01:07:51'),
	(2,'Lockon','$2y$10$8G/Ny5pYu6w1dDg53isLV.mazSHWApEltq4NPPHiVkXPKRpbn6sBW','Supervisor',0,'2016-02-03 01:08:08','2016-02-03 01:08:08'),
	(3,'Nuevo','$2y$10$1lQfyzLTIkIGtORGIYSpaeVkBWX0BFYQLm2dEPrh4yKiX59Gt7EPy','User',0,'2016-02-17 22:26:01','2016-02-17 22:26:01'),
	(6,'adan2','$2y$10$jj/U0pJ.AvEvvZsEmhPHbeMMoMJRHQBe4MGYpsqL7B/bX3drd2Tiq','Supervisor',1,'2016-02-19 05:17:41','2016-02-19 05:17:41'),
	(7,'novaera1','$2y$10$B1RLdkkZe8axazKht0hd.OeShpPDRToGBMRoVEcOrW0O1RvYH4ofm','User',0,'2016-02-19 06:58:23','2016-02-19 06:58:23'),
	(8,'novaera2','$2y$10$mYGbaZuG7LTqDcOF38AdMugotiEfMvitor/mf4f7MUaPRNkOXjGuS','Supervisor',0,'2016-02-19 06:58:27','2016-02-19 06:58:27'),
	(9,'elariosj@gmail.com','$2y$10$qyA1DdAM9aBc92y3M3i/suP0AqoBR/dMKmMIbR.cGJUMJl.pQBPfS','User',0,'2016-02-20 00:14:18','2016-02-20 00:14:18');
ALTER TABLE `User` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Validacion_Criterio` WRITE;
ALTER TABLE `Validacion_Criterio` DISABLE KEYS;
ALTER TABLE `Validacion_Criterio` ENABLE KEYS;
UNLOCK TABLES;




SET FOREIGN_KEY_CHECKS = @PREVIOUS_FOREIGN_KEY_CHECKS;


