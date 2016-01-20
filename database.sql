#
# SQL Export
# Created by Querious (1010)
# Created: January 19, 2016 at 8:21:08 PM CST
# Encoding: Unicode (UTF-8)
#


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
DROP TABLE IF EXISTS `ProyectoResultado`;
DROP TABLE IF EXISTS `ProyectoTRL`;
DROP TABLE IF EXISTS `proyectoDescriptor`;
DROP TABLE IF EXISTS `Proyecto_Modalidad`;
DROP TABLE IF EXISTS `Proyecto`;
DROP TABLE IF EXISTS `ProgramaFondeoDescriptor`;
DROP TABLE IF EXISTS `ProgramaFondeo_RubrosApoyo`;
DROP TABLE IF EXISTS `ProgramaFondeo`;
DROP TABLE IF EXISTS `Persona_Proyecto`;
DROP TABLE IF EXISTS `Persona_Organizacion`;
DROP TABLE IF EXISTS `Persona`;
DROP TABLE IF EXISTS `ParqueTecnologico`;
DROP TABLE IF EXISTS `Pais`;
DROP TABLE IF EXISTS `OrganizacionLegal`;
DROP TABLE IF EXISTS `Organizacion_Proyecto`;
DROP TABLE IF EXISTS `Organizacion_Modalidad`;
DROP TABLE IF EXISTS `Organizacion`;
DROP TABLE IF EXISTS `Municipio`;
DROP TABLE IF EXISTS `ModeloNegocio`;
DROP TABLE IF EXISTS `Modaliad_Criterios`;
DROP TABLE IF EXISTS `Modalidad`;
DROP TABLE IF EXISTS `ImpactoyComercializacion`;
DROP TABLE IF EXISTS `EtapaProyecto`;
DROP TABLE IF EXISTS `EntidadFederativa`;
DROP TABLE IF EXISTS `Ejecucion`;
DROP TABLE IF EXISTS `Direccion`;
DROP TABLE IF EXISTS `Descriptor_Persona`;
DROP TABLE IF EXISTS `Descriptor_Organizacion`;
DROP TABLE IF EXISTS `Descriptor`;
DROP TABLE IF EXISTS `Convocatoria`;
DROP TABLE IF EXISTS `Contacto`;
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
  KEY `fk_Archivos_OrganizacionLegal1_idx` (`idOrganizacionLegal`),
  KEY `fk_Archivos_TipoArchivo1_idx` (`idTipoArchivo`),
  KEY `fk_Archivos_Ejecucion1_idx` (`idEjecucion`),
  KEY `fk_Archivos_TareaEtapa1_idx` (`idTareaEtapa`),
  KEY `fk_Archivos_ImpactoyComercializacion1_idx` (`idImpacto`),
  KEY `fk_Archivos_ModeloNegocio1_idx` (`idModeloNegocio`),
  KEY `fk_Archivos_ProgramaFondeo1_idx` (`ProgramaFondeo_id`),
  KEY `fk_Archivos_TransferenciaTecnologica1_idx` (`idTransferenciaTecnologica`),
  CONSTRAINT `fk_Archivos_Ejecucion1` FOREIGN KEY (`idEjecucion`) REFERENCES `Ejecucion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Archivos_ImpactoyComercializacion1` FOREIGN KEY (`idImpacto`) REFERENCES `ImpactoyComercializacion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Archivos_ModeloNegocio1` FOREIGN KEY (`idModeloNegocio`) REFERENCES `ModeloNegocio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Archivos_OrganizacionLegal1` FOREIGN KEY (`idOrganizacionLegal`) REFERENCES `OrganizacionLegal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Archivos_ProgramaFondeo1` FOREIGN KEY (`ProgramaFondeo_id`) REFERENCES `ProgramaFondeo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Archivos_TareaEtapa1` FOREIGN KEY (`idTareaEtapa`) REFERENCES `TareaEtapa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Archivos_TipoArchivo1` FOREIGN KEY (`idTipoArchivo`) REFERENCES `TipoArchivo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Archivos_TransferenciaTecnologica1` FOREIGN KEY (`idTransferenciaTecnologica`) REFERENCES `TransferenciaTecnologica` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
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
  `idOrganizacion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Contacto_Persona1_idx` (`idPersona`),
  KEY `fk_Contacto_Organizacion1_idx` (`idOrganizacion`),
  CONSTRAINT `fk_Contacto_Organizacion1` FOREIGN KEY (`idOrganizacion`) REFERENCES `Organizacion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Contacto_Persona1` FOREIGN KEY (`idPersona`) REFERENCES `Persona` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Convocatoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) DEFAULT NULL,
  `FechaInicio` date DEFAULT NULL,
  `FechaTermino` date DEFAULT NULL,
  `Requisitos` varchar(450) DEFAULT NULL,
  `MontosMaximosTotales` float DEFAULT NULL,
  `idProgramaFondeo` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Convocatoria_ProgramaFondeo1_idx` (`idProgramaFondeo`),
  CONSTRAINT `fk_Convocatoria_ProgramaFondeo1` FOREIGN KEY (`idProgramaFondeo`) REFERENCES `ProgramaFondeo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Descriptor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(300) DEFAULT NULL,
  `Descripcion` varchar(450) DEFAULT NULL,
  `idTipoDescriptor` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Descriptor_TipoDescriptor1_idx` (`idTipoDescriptor`),
  CONSTRAINT `fk_Descriptor_TipoDescriptor1` FOREIGN KEY (`idTipoDescriptor`) REFERENCES `TipoDescriptor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Descriptor_Organizacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `FechaInicio` date DEFAULT NULL,
  `FechaTermino` date DEFAULT NULL,
  `idOrganizacion` int(11) NOT NULL,
  `idDescriptor` int(11) NOT NULL,
  `TipoResultado` varchar(45) DEFAULT NULL,
  `NumeroRegistro` varchar(45) DEFAULT NULL,
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
  CONSTRAINT `fk_Descriptor_Pesona_Descriptor1` FOREIGN KEY (`idDescriptor`) REFERENCES `Descriptor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Descriptor_Pesona_Persona1` FOREIGN KEY (`idPersona`) REFERENCES `Persona` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Direccion` (
  `id` int(11) NOT NULL,
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
  CONSTRAINT `fk_Direccion_Contacto1` FOREIGN KEY (`idContacto`) REFERENCES `Contacto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `ImpactoyComercializacion` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Modalidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(45) DEFAULT NULL,
  `Montos` double DEFAULT NULL,
  `CriteriosEvaluacion` varchar(450) DEFAULT NULL,
  `Entregables` varchar(1000) DEFAULT NULL,
  `FigurasApoyo` varchar(450) DEFAULT NULL,
  `idConvocatoria` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Modalidad_Convocatoria1_idx` (`idConvocatoria`),
  CONSTRAINT `fk_Modalidad_Convocatoria1` FOREIGN KEY (`idConvocatoria`) REFERENCES `Convocatoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Modaliad_Criterios` (
  `id` int(11) NOT NULL,
  `Descripcion` varchar(45) DEFAULT NULL,
  `Nombre` varchar(45) DEFAULT NULL,
  `idModalidad` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Modaliad_Criterios_Modalidad1_idx` (`idModalidad`),
  CONSTRAINT `fk_Modaliad_Criterios_Modalidad1` FOREIGN KEY (`idModalidad`) REFERENCES `Modalidad` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


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
  `Titulo` varchar(45) DEFAULT NULL,
  `Descripcion` varchar(450) DEFAULT NULL,
  `Mision` varchar(450) DEFAULT NULL,
  `Vision` varchar(450) DEFAULT NULL,
  `isValidated` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


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
  CONSTRAINT `fk_Organizacion_Modalidad_Modalidad1` FOREIGN KEY (`idModalidad`) REFERENCES `Modalidad` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Organizacion_Modalidad_Organizacion1` FOREIGN KEY (`idOrganizacion`) REFERENCES `Organizacion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Organizacion_Proyecto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idOrganizacion` int(11) NOT NULL,
  `idProyecto` int(11) NOT NULL,
  `Owner` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_Organizacion_has_Proyecto_Proyecto1_idx` (`idProyecto`),
  KEY `fk_Organizacion_has_Proyecto_Organizacion1_idx` (`idOrganizacion`),
  CONSTRAINT `fk_Organizacion_has_Proyecto_Organizacion1` FOREIGN KEY (`idOrganizacion`) REFERENCES `Organizacion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Organizacion_has_Proyecto_Proyecto1` FOREIGN KEY (`idProyecto`) REFERENCES `Proyecto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `OrganizacionLegal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(45) DEFAULT NULL,
  `RepresentanteLegal` varchar(45) DEFAULT NULL,
  `RazonSocial` varchar(45) DEFAULT NULL,
  `ActaConstitutiva_O` tinyint(1) DEFAULT NULL,
  `RFC_O` tinyint(1) DEFAULT NULL,
  `RENIECyT_O` tinyint(1) DEFAULT NULL,
  `RFC_V` tinyint(1) DEFAULT NULL,
  `RENIECyT_V` tinyint(1) DEFAULT NULL,
  `ActaConstitutiva_V` tinyint(1) DEFAULT NULL,
  `idOrganizacion` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_OrganizacionLegal_Organizacion1_idx` (`idOrganizacion`),
  CONSTRAINT `fk_OrganizacionLegal_Organizacion1` FOREIGN KEY (`idOrganizacion`) REFERENCES `Organizacion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Pais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(45) NOT NULL,
  `Abrev` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;


CREATE TABLE `ParqueTecnologico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Persona` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(60) DEFAULT NULL,
  `ApellidoP` varchar(45) DEFAULT NULL,
  `ApellidoM` varchar(45) DEFAULT NULL,
  `Notas` varchar(450) DEFAULT NULL,
  `Descripcion` varchar(450) DEFAULT NULL,
  `isValidated` int(11) DEFAULT NULL,
  `idUser` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Persona_User1_idx` (`idUser`),
  CONSTRAINT `fk_Persona_User1` FOREIGN KEY (`idUser`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `Persona_Organizacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Puesto` varchar(45) DEFAULT NULL,
  `FechaInicio` date DEFAULT NULL,
  `FechaTermino` varchar(45) DEFAULT NULL,
  `idPersona` int(11) NOT NULL,
  `idOrganizacion` int(11) NOT NULL,
  `Owner` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_Persona_Organizacion_Persona1_idx` (`idPersona`),
  KEY `fk_Persona_Organizacion_Organizacion1_idx` (`idOrganizacion`),
  CONSTRAINT `fk_Persona_Organizacion_Organizacion1` FOREIGN KEY (`idOrganizacion`) REFERENCES `Organizacion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Persona_Organizacion_Persona1` FOREIGN KEY (`idPersona`) REFERENCES `Persona` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Persona_Proyecto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProyecto` int(11) NOT NULL,
  `idPersona` int(11) NOT NULL,
  `Owner` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_proyecto_persona` (`idProyecto`,`idPersona`) USING BTREE,
  KEY `fk_Proyecto_has_Persona_Persona1_idx` (`idPersona`),
  KEY `fk_Proyecto_has_Persona_Proyecto1_idx` (`idProyecto`),
  CONSTRAINT `fk_Proyecto_has_Persona_Persona1` FOREIGN KEY (`idPersona`) REFERENCES `Persona` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Proyecto_has_Persona_Proyecto1` FOREIGN KEY (`idProyecto`) REFERENCES `Proyecto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `ProgramaFondeo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(45) DEFAULT NULL,
  `PublicoObjetivo` varchar(1000) DEFAULT NULL,
  `FondoTotal` float DEFAULT NULL,
  `CriteriosElegibilidad` varchar(450) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `ProgramaFondeo_RubrosApoyo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProgramaFondeo` int(11) NOT NULL,
  `idRubrosApoyo` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ProgramaFondeo_has_RubrosApoyo_RubrosApoyo1_idx` (`idRubrosApoyo`),
  KEY `fk_ProgramaFondeo_has_RubrosApoyo_ProgramaFondeo1_idx` (`idProgramaFondeo`),
  CONSTRAINT `fk_ProgramaFondeo_has_RubrosApoyo_ProgramaFondeo1` FOREIGN KEY (`idProgramaFondeo`) REFERENCES `ProgramaFondeo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ProgramaFondeo_has_RubrosApoyo_RubrosApoyo1` FOREIGN KEY (`idRubrosApoyo`) REFERENCES `RubrosApoyo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `ProgramaFondeoDescriptor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `observaciones` varchar(45) DEFAULT NULL,
  `idDescriptor` int(11) NOT NULL,
  `idProgramaFondeo` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ProgramaFondeoDescriptor_Descriptor1_idx` (`idDescriptor`),
  KEY `fk_ProgramaFondeoDescriptor_ProgramaFondeo1_idx` (`idProgramaFondeo`),
  CONSTRAINT `fk_ProgramaFondeoDescriptor_Descriptor1` FOREIGN KEY (`idDescriptor`) REFERENCES `Descriptor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ProgramaFondeoDescriptor_ProgramaFondeo1` FOREIGN KEY (`idProgramaFondeo`) REFERENCES `ProgramaFondeo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `Proyecto_Modalidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idModalidad` int(11) NOT NULL,
  `idProyecto` int(11) NOT NULL,
  `idParque` int(11) NOT NULL,
  `Solicitud` varchar(1000) DEFAULT NULL,
  `MontoSolicitado` float DEFAULT NULL,
  `MontoApoyado` float DEFAULT NULL,
  `TRLInicial` varchar(45) DEFAULT NULL,
  `TRLFinal` varchar(45) DEFAULT NULL,
  `FechaRegistro` date DEFAULT NULL,
  `FechaCierre` date DEFAULT NULL,
  `Resultado` varchar(1000) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `Validado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Proyecto_Modalidad_Modalidad1_idx` (`idModalidad`),
  KEY `fk_Proyecto_Modalidad_Proyecto1_idx` (`idProyecto`),
  KEY `fk_Proyecto_Modalidad_ParqueTecnologico1_idx` (`idParque`),
  CONSTRAINT `fk_Proyecto_Modalidad_Modalidad1` FOREIGN KEY (`idModalidad`) REFERENCES `Modalidad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Proyecto_Modalidad_ParqueTecnologico1` FOREIGN KEY (`idParque`) REFERENCES `ParqueTecnologico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Proyecto_Modalidad_Proyecto1` FOREIGN KEY (`idProyecto`) REFERENCES `Proyecto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `proyectoDescriptor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `observaciones` varchar(45) DEFAULT NULL,
  `idProyecto` int(11) NOT NULL,
  `idDescriptor` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_proyectoDescriptor_Proyecto1_idx` (`idProyecto`),
  KEY `fk_proyectoDescriptor_Descriptor1_idx` (`idDescriptor`),
  CONSTRAINT `fk_proyectoDescriptor_Descriptor1` FOREIGN KEY (`idDescriptor`) REFERENCES `Descriptor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_proyectoDescriptor_Proyecto1` FOREIGN KEY (`idProyecto`) REFERENCES `Proyecto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


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
  CONSTRAINT `fk_Proyecto_has_TRL_TRL1` FOREIGN KEY (`idTRL`) REFERENCES `TRL` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `ProyectoResultado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProyectoTRL` int(11) NOT NULL,
  `Tipo` enum('Producto','Proceso','Servicio','Patente') NOT NULL DEFAULT 'Producto',
  `Nombre` varchar(45) NOT NULL,
  `Resumen` varchar(450) CHARACTER SET cp850 NOT NULL,
  `NumeroRegistro` varchar(45) DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `PaisesProteccion` json DEFAULT NULL,
  `PlanDeExplotacion` varchar(45) DEFAULT NULL,
  `AreaDeAplicacion` varchar(128) DEFAULT NULL,
  `Avance` varchar(1000) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `FechaAprobacion` date DEFAULT NULL,
  `created_at` varchar(45) DEFAULT NULL,
  `updated_at` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Resultado_ProyectoTRL1_idx` (`idProyectoTRL`),
  CONSTRAINT `fk_Resultado_ProyectoTRL1` FOREIGN KEY (`idProyectoTRL`) REFERENCES `ProyectoTRL` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


CREATE TABLE `ResultadoDescriptor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idDescriptor` int(11) NOT NULL,
  `idResultado` int(11) NOT NULL,
  `FechaRegistro` date DEFAULT NULL,
  `FechaAprobacion` date DEFAULT NULL,
  `PCT` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ResultadoDescriptor_Descriptor1_idx` (`idDescriptor`),
  KEY `fk_ResultadoDescriptor_ProyectoResultado1_idx` (`idResultado`),
  CONSTRAINT `fk_ResultadoDescriptor_Descriptor1` FOREIGN KEY (`idDescriptor`) REFERENCES `Descriptor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ResultadoDescriptor_ProyectoResultado1` FOREIGN KEY (`idResultado`) REFERENCES `ProyectoResultado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `TipoArchivo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(45) NOT NULL,
  `Aplicable` char(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `TipoDescriptor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(300) DEFAULT NULL,
  `Aplicable` char(1) DEFAULT NULL,
  `Activo` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '		',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `TransferenciaTecnologica` (
  `id` int(11) NOT NULL,
  `ProductosDePropiedad` varchar(45) DEFAULT NULL,
  `ProcesosDeTransferencia` varchar(45) DEFAULT NULL,
  `ValuacionTecnologica` varchar(45) DEFAULT NULL,
  `idProyecto` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Proyecto_TT_Proyecto1_idx` (`idProyecto`),
  CONSTRAINT `fk_Proyecto_TT_Proyecto1` FOREIGN KEY (`idProyecto`) REFERENCES `Proyecto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `TRL` (
  `id` int(11) NOT NULL,
  `Descripcion` varchar(45) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `Validacion_Criterio` (
  `id` int(11) NOT NULL,
  `idProyectoModalidad` int(11) NOT NULL,
  `idCriterio` int(11) NOT NULL,
  `Validado` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Validacion_Modalidad_Proyecto_Modalidad1_idx` (`idProyectoModalidad`),
  KEY `fk_Validacion_Modalidad_Modaliad_Criterios1_idx` (`idCriterio`),
  CONSTRAINT `fk_Validacion_Modalidad_Modaliad_Criterios1` FOREIGN KEY (`idCriterio`) REFERENCES `Modaliad_Criterios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Validacion_Modalidad_Proyecto_Modalidad1` FOREIGN KEY (`idProyectoModalidad`) REFERENCES `Proyecto_Modalidad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




LOCK TABLES `Archivos` WRITE;
ALTER TABLE `Archivos` DISABLE KEYS;
ALTER TABLE `Archivos` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Contacto` WRITE;
ALTER TABLE `Contacto` DISABLE KEYS;
ALTER TABLE `Contacto` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Convocatoria` WRITE;
ALTER TABLE `Convocatoria` DISABLE KEYS;
ALTER TABLE `Convocatoria` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Descriptor` WRITE;
ALTER TABLE `Descriptor` DISABLE KEYS;
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
ALTER TABLE `Ejecucion` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `EntidadFederativa` WRITE;
ALTER TABLE `EntidadFederativa` DISABLE KEYS;
ALTER TABLE `EntidadFederativa` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `EtapaProyecto` WRITE;
ALTER TABLE `EtapaProyecto` DISABLE KEYS;
ALTER TABLE `EtapaProyecto` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ImpactoyComercializacion` WRITE;
ALTER TABLE `ImpactoyComercializacion` DISABLE KEYS;
ALTER TABLE `ImpactoyComercializacion` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Modalidad` WRITE;
ALTER TABLE `Modalidad` DISABLE KEYS;
ALTER TABLE `Modalidad` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Modaliad_Criterios` WRITE;
ALTER TABLE `Modaliad_Criterios` DISABLE KEYS;
ALTER TABLE `Modaliad_Criterios` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ModeloNegocio` WRITE;
ALTER TABLE `ModeloNegocio` DISABLE KEYS;
ALTER TABLE `ModeloNegocio` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Municipio` WRITE;
ALTER TABLE `Municipio` DISABLE KEYS;
ALTER TABLE `Municipio` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Organizacion` WRITE;
ALTER TABLE `Organizacion` DISABLE KEYS;
ALTER TABLE `Organizacion` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Organizacion_Modalidad` WRITE;
ALTER TABLE `Organizacion_Modalidad` DISABLE KEYS;
ALTER TABLE `Organizacion_Modalidad` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Organizacion_Proyecto` WRITE;
ALTER TABLE `Organizacion_Proyecto` DISABLE KEYS;
ALTER TABLE `Organizacion_Proyecto` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `OrganizacionLegal` WRITE;
ALTER TABLE `OrganizacionLegal` DISABLE KEYS;
ALTER TABLE `OrganizacionLegal` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Pais` WRITE;
ALTER TABLE `Pais` DISABLE KEYS;
INSERT INTO `Pais` (`id`, `Nombre`, `Abrev`) VALUES 
	(1,'Mexico','MX'),
	(2,'Estados Unidos','EUA'),
	(3,'Alemania','GER'),
	(5,'Australia','AUS');
ALTER TABLE `Pais` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ParqueTecnologico` WRITE;
ALTER TABLE `ParqueTecnologico` DISABLE KEYS;
ALTER TABLE `ParqueTecnologico` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Persona` WRITE;
ALTER TABLE `Persona` DISABLE KEYS;
INSERT INTO `Persona` (`id`, `Nombre`, `ApellidoP`, `ApellidoM`, `Notas`, `Descripcion`, `isValidated`, `idUser`, `created_at`, `updated_at`) VALUES 
	(1,'Daniel','Franco','Rodríguez',NULL,NULL,NULL,1,'2016-01-18 05:23:47','2016-01-18 05:23:47');
ALTER TABLE `Persona` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Persona_Organizacion` WRITE;
ALTER TABLE `Persona_Organizacion` DISABLE KEYS;
ALTER TABLE `Persona_Organizacion` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Persona_Proyecto` WRITE;
ALTER TABLE `Persona_Proyecto` DISABLE KEYS;
INSERT INTO `Persona_Proyecto` (`id`, `idProyecto`, `idPersona`, `Owner`, `created_at`, `updated_at`) VALUES 
	(1,1,1,1,NULL,NULL);
ALTER TABLE `Persona_Proyecto` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ProgramaFondeo` WRITE;
ALTER TABLE `ProgramaFondeo` DISABLE KEYS;
ALTER TABLE `ProgramaFondeo` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ProgramaFondeo_RubrosApoyo` WRITE;
ALTER TABLE `ProgramaFondeo_RubrosApoyo` DISABLE KEYS;
ALTER TABLE `ProgramaFondeo_RubrosApoyo` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ProgramaFondeoDescriptor` WRITE;
ALTER TABLE `ProgramaFondeoDescriptor` DISABLE KEYS;
ALTER TABLE `ProgramaFondeoDescriptor` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Proyecto` WRITE;
ALTER TABLE `Proyecto` DISABLE KEYS;
INSERT INTO `Proyecto` (`id`, `Titulo`, `Descripcion`, `Antecedentes`, `Justificacion`, `Objetivos`, `Alcances`, `created_at`, `updated_at`) VALUES 
	(1,'Proyecto 2','Lalalala','Lalalala','Lalalala','xD','Alcances','2016-01-18 05:23:56','2016-01-18 05:23:56');
ALTER TABLE `Proyecto` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Proyecto_Modalidad` WRITE;
ALTER TABLE `Proyecto_Modalidad` DISABLE KEYS;
ALTER TABLE `Proyecto_Modalidad` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `proyectoDescriptor` WRITE;
ALTER TABLE `proyectoDescriptor` DISABLE KEYS;
ALTER TABLE `proyectoDescriptor` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ProyectoTRL` WRITE;
ALTER TABLE `ProyectoTRL` DISABLE KEYS;
INSERT INTO `ProyectoTRL` (`id`, `idProyecto`, `idTRL`, `Descripcion`, `Fecha`, `created_at`, `updated_at`) VALUES 
	(1,1,1,'Descripción','2015-12-01',NULL,NULL);
ALTER TABLE `ProyectoTRL` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `ProyectoResultado` WRITE;
ALTER TABLE `ProyectoResultado` DISABLE KEYS;
INSERT INTO `ProyectoResultado` (`id`, `idProyectoTRL`, `Tipo`, `Nombre`, `Resumen`, `NumeroRegistro`, `Status`, `PaisesProteccion`, `PlanDeExplotacion`, `AreaDeAplicacion`, `Avance`, `Fecha`, `FechaAprobacion`, `created_at`, `updated_at`) VALUES 
	(2,1,'Producto','Producto','Resumen de la Patente','134556','En Proceso',NULL,'Plan',NULL,NULL,'2015-12-01',NULL,'2016-01-18 05:24:44','2016-01-18 06:43:53'),
	(3,1,'Patente','Patente','Resumen del producto','134556','En Proceso','[{"id": 1, "Abrev": "MX", "Nombre": "Mexico"}, {"id": 2, "Abrev": "EUA", "Nombre": "Estados Unidos"}]','Plan','Campo','Avance','2015-12-01','2015-12-10','2016-01-18 05:31:10','2016-01-18 19:49:50');
ALTER TABLE `ProyectoResultado` ENABLE KEYS;
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
ALTER TABLE `TareaEtapa` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `TipoArchivo` WRITE;
ALTER TABLE `TipoArchivo` DISABLE KEYS;
ALTER TABLE `TipoArchivo` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `TipoDescriptor` WRITE;
ALTER TABLE `TipoDescriptor` DISABLE KEYS;
ALTER TABLE `TipoDescriptor` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `TransferenciaTecnologica` WRITE;
ALTER TABLE `TransferenciaTecnologica` DISABLE KEYS;
ALTER TABLE `TransferenciaTecnologica` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `TRL` WRITE;
ALTER TABLE `TRL` DISABLE KEYS;
INSERT INTO `TRL` (`id`, `Descripcion`) VALUES 
	(1,'TRL 1');
ALTER TABLE `TRL` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `User` WRITE;
ALTER TABLE `User` DISABLE KEYS;
INSERT INTO `User` (`id`, `username`, `password`, `type`, `isValidated`, `created_at`, `updated_at`) VALUES 
	(1,'Lockon','$2y$10$JZRKoJ5HmP2M.a6LdQmckOMG8KXL0lZthJ5yqsWgQ89cGidZT1uaS','User',0,'2016-01-18 05:23:37','2016-01-18 05:23:37');
ALTER TABLE `User` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `Validacion_Criterio` WRITE;
ALTER TABLE `Validacion_Criterio` DISABLE KEYS;
ALTER TABLE `Validacion_Criterio` ENABLE KEYS;
UNLOCK TABLES;




SET FOREIGN_KEY_CHECKS = @PREVIOUS_FOREIGN_KEY_CHECKS;


