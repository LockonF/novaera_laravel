-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema Novaera
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `Novaera` ;

-- -----------------------------------------------------
-- Schema Novaera
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `Novaera` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `Novaera` ;

-- -----------------------------------------------------
-- Table `Novaera`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`User` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`User` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `username` VARCHAR(45) NULL COMMENT '',
  `password` VARCHAR(300) NOT NULL COMMENT '',
  `type` ENUM('User', 'Admin') NOT NULL DEFAULT 'User' COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Persona`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Persona` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Persona` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `Nombre` VARCHAR(60) NULL COMMENT '',
  `ApellidoP` VARCHAR(45) NULL COMMENT '',
  `ApellidoM` VARCHAR(45) NULL COMMENT '',
  `Notas` VARCHAR(450) NULL COMMENT '',
  `Descripcion` VARCHAR(450) NULL COMMENT '',
  `idUser` INT NOT NULL COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_Persona_User1_idx` (`idUser` ASC)  COMMENT '',
  CONSTRAINT `fk_Persona_User1`
    FOREIGN KEY (`idUser`)
    REFERENCES `Novaera`.`User` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Pais`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Pais` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Pais` (
  `id` INT NOT NULL COMMENT '',
  `Nombre` VARCHAR(45) NOT NULL COMMENT '',
  `Abrev` VARCHAR(45) NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`EntidadFederativa`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`EntidadFederativa` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`EntidadFederativa` (
  `id` INT(11) NOT NULL COMMENT '',
  `clave` VARCHAR(2) NULL COMMENT '',
  `Nombre` VARCHAR(45) NOT NULL COMMENT '',
  `abrev` VARCHAR(16) NULL COMMENT '',
  `idPais` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_EntidadFederativa_Pais_idx` (`idPais` ASC)  COMMENT '',
  CONSTRAINT `fk_EntidadFederativa_Pais`
    FOREIGN KEY (`idPais`)
    REFERENCES `Novaera`.`Pais` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Municipio`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Municipio` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Municipio` (
  `id` INT(11) NOT NULL COMMENT '',
  `clave` VARCHAR(3) NULL COMMENT '',
  `Nombre` VARCHAR(50) NOT NULL COMMENT '',
  `sigla` VARCHAR(4) NULL COMMENT '',
  `idEntidadFederativa` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_Municipio_EntidadFederativa1_idx` (`idEntidadFederativa` ASC)  COMMENT '',
  CONSTRAINT `fk_Municipio_EntidadFederativa1`
    FOREIGN KEY (`idEntidadFederativa`)
    REFERENCES `Novaera`.`EntidadFederativa` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Organizacion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Organizacion` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Organizacion` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `Titulo` VARCHAR(45) NULL COMMENT '',
  `Descripcion` VARCHAR(450) NULL COMMENT '',
  `Mision` VARCHAR(450) NULL COMMENT '',
  `Vision` VARCHAR(450) NULL COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Contacto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Contacto` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Contacto` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `CorreoElectronico` VARCHAR(100) NULL COMMENT '',
  `TelefonoLocal` VARCHAR(45) NULL COMMENT '',
  `TelefonoCelular` VARCHAR(45) NULL COMMENT '',
  `TelefonoOficina` VARCHAR(45) NULL COMMENT '',
  `Fax` VARCHAR(45) NULL COMMENT '',
  `PaginaWeb` VARCHAR(200) NULL COMMENT '',
  `idPersona` INT NULL COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '',
  `idOrganizacion` INT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_Contacto_Persona1_idx` (`idPersona` ASC)  COMMENT '',
  INDEX `fk_Contacto_Organizacion1_idx` (`idOrganizacion` ASC)  COMMENT '',
  CONSTRAINT `fk_Contacto_Persona1`
    FOREIGN KEY (`idPersona`)
    REFERENCES `Novaera`.`Persona` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Contacto_Organizacion1`
    FOREIGN KEY (`idOrganizacion`)
    REFERENCES `Novaera`.`Organizacion` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Direccion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Direccion` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Direccion` (
  `id` INT NOT NULL COMMENT '',
  `Calle` VARCHAR(50) NOT NULL COMMENT '',
  `NumExt` VARCHAR(4) NOT NULL COMMENT '',
  `NumInt` VARCHAR(5) NULL COMMENT '',
  `Colonia` VARCHAR(45) NULL COMMENT '',
  `CP` VARCHAR(20) NULL COMMENT '',
  `idMunicipio` INT(11) NOT NULL COMMENT '',
  `idContacto` INT NOT NULL COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_Direccion_Municipio1_idx` (`idMunicipio` ASC)  COMMENT '',
  INDEX `fk_Direccion_Contacto1_idx` (`idContacto` ASC)  COMMENT '',
  CONSTRAINT `fk_Direccion_Municipio1`
    FOREIGN KEY (`idMunicipio`)
    REFERENCES `Novaera`.`Municipio` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Direccion_Contacto1`
    FOREIGN KEY (`idContacto`)
    REFERENCES `Novaera`.`Contacto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Persona_Organizacion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Persona_Organizacion` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Persona_Organizacion` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `Puesto` VARCHAR(45) NULL COMMENT '',
  `FechaInicio` DATE NULL COMMENT '',
  `FechaTermino` VARCHAR(45) NULL COMMENT '',
  `idPersona` INT NOT NULL COMMENT '',
  `idOrganizacion` INT NOT NULL COMMENT '',
  `Owner` TINYINT(1) NULL DEFAULT 0 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_Persona_Organizacion_Persona1_idx` (`idPersona` ASC)  COMMENT '',
  INDEX `fk_Persona_Organizacion_Organizacion1_idx` (`idOrganizacion` ASC)  COMMENT '',
  CONSTRAINT `fk_Persona_Organizacion_Persona1`
    FOREIGN KEY (`idPersona`)
    REFERENCES `Novaera`.`Persona` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Persona_Organizacion_Organizacion1`
    FOREIGN KEY (`idOrganizacion`)
    REFERENCES `Novaera`.`Organizacion` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`TipoDescriptor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`TipoDescriptor` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`TipoDescriptor` (
  `id` INT NOT NULL COMMENT '',
  `Nombre` VARCHAR(45) NULL COMMENT '',
  `Aplicable` CHAR(1) NULL COMMENT '',
  `Activo` TINYINT(1) NULL COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '		',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Descriptor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Descriptor` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Descriptor` (
  `id` INT NOT NULL COMMENT '',
  `Titulo` VARCHAR(45) NULL COMMENT '',
  `Descripcion` VARCHAR(450) NULL COMMENT '',
  `CatalogoDescriptorescol` VARCHAR(45) NULL COMMENT '',
  `idTipoDescriptor` INT NOT NULL COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_Descriptor_TipoDescriptor1_idx` (`idTipoDescriptor` ASC)  COMMENT '',
  CONSTRAINT `fk_Descriptor_TipoDescriptor1`
    FOREIGN KEY (`idTipoDescriptor`)
    REFERENCES `Novaera`.`TipoDescriptor` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Descriptor_Organizacion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Descriptor_Organizacion` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Descriptor_Organizacion` (
  `id` INT NOT NULL COMMENT '',
  `FechaInicio` DATE NULL COMMENT '',
  `FechaTermino` DATE NULL COMMENT '',
  `idOrganizacion` INT NOT NULL COMMENT '',
  `idDescriptor` INT NOT NULL COMMENT '',
  `TipoResultado` VARCHAR(45) NULL COMMENT '',
  `NumeroRegistro` VARCHAR(45) NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_Descriptor_Organizacion1_idx` (`idOrganizacion` ASC)  COMMENT '',
  INDEX `fk_Descriptor_CatalogoDescriptores1_idx` (`idDescriptor` ASC)  COMMENT '',
  CONSTRAINT `fk_Descriptor_Organizacion1`
    FOREIGN KEY (`idOrganizacion`)
    REFERENCES `Novaera`.`Organizacion` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Descriptor_CatalogoDescriptores1`
    FOREIGN KEY (`idDescriptor`)
    REFERENCES `Novaera`.`Descriptor` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`OrganizacionLegal`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`OrganizacionLegal` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`OrganizacionLegal` (
  `id` INT NOT NULL COMMENT '',
  `Titulo` VARCHAR(45) NULL COMMENT '',
  `RepresentanteLegal` VARCHAR(45) NULL COMMENT '',
  `RazonSocial` VARCHAR(45) NULL COMMENT '',
  `ActaConstitutiva_O` TINYINT(1) NULL COMMENT '',
  `RFC_O` TINYINT(1) NULL COMMENT '',
  `RENIECyT_O` TINYINT(1) NULL COMMENT '',
  `RFC_V` TINYINT(1) NULL COMMENT '',
  `RENIECyT_V` TINYINT(1) NULL COMMENT '',
  `ActaConstitutiva_V` TINYINT(1) NULL COMMENT '',
  `idOrganizacion` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_OrganizacionLegal_Organizacion1_idx` (`idOrganizacion` ASC)  COMMENT '',
  CONSTRAINT `fk_OrganizacionLegal_Organizacion1`
    FOREIGN KEY (`idOrganizacion`)
    REFERENCES `Novaera`.`Organizacion` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`TipoArchivo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`TipoArchivo` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`TipoArchivo` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `Titulo` VARCHAR(45) NOT NULL COMMENT '',
  `Aplicable` CHAR(1) NULL COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Proyecto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Proyecto` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Proyecto` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `Titulo` VARCHAR(45) NULL COMMENT '',
  `Descripcion` VARCHAR(450) NULL COMMENT '',
  `Antecedentes` VARCHAR(450) NULL COMMENT '',
  `Justificacion` VARCHAR(450) NULL COMMENT '',
  `Objetivos` VARCHAR(450) NULL COMMENT '',
  `Alcances` VARCHAR(450) NULL COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Ejecucion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Ejecucion` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Ejecucion` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `Requisitos` TINYINT NULL DEFAULT 0 COMMENT '',
  `AnalisisEntornoP` TINYINT NULL DEFAULT 0 COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `FactibilidadTecnicaP` TINYINT NULL DEFAULT 0 COMMENT '',
  `FactibilidadEconomicaP` TINYINT NULL DEFAULT 0 COMMENT '',
  `FactibilidadComercialP` TINYINT NULL DEFAULT 0 COMMENT '',
  `BenchmarkComercialP` TINYINT NULL DEFAULT 0 COMMENT '',
  `BenchmarkTecnologicoP` TINYINT NULL DEFAULT 0 COMMENT '',
  `RecursosHumanosP` TINYINT NULL DEFAULT 0 COMMENT '',
  `RecursosFinancierosP` TINYINT NULL DEFAULT 0 COMMENT '',
  `RecursosTecnologicosP` TINYINT NULL DEFAULT 0 COMMENT '',
  `RecursosMaterialesP` TINYINT NULL DEFAULT 0 COMMENT '',
  `idProyecto` INT NOT NULL COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_Ejecucion_Proyecto1_idx` (`idProyecto` ASC)  COMMENT '',
  CONSTRAINT `fk_Ejecucion_Proyecto1`
    FOREIGN KEY (`idProyecto`)
    REFERENCES `Novaera`.`Proyecto` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`EtapaProyecto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`EtapaProyecto` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`EtapaProyecto` (
  `idEtapaProyecto` INT NOT NULL COMMENT '',
  `NombreEtapa` VARCHAR(45) NULL COMMENT '',
  `EntregableEtapa` VARCHAR(45) NULL COMMENT '',
  `idProyecto` INT NOT NULL COMMENT '',
  PRIMARY KEY (`idEtapaProyecto`)  COMMENT '',
  INDEX `fk_EtapaProyecto_Proyecto1_idx` (`idProyecto` ASC)  COMMENT '',
  CONSTRAINT `fk_EtapaProyecto_Proyecto1`
    FOREIGN KEY (`idProyecto`)
    REFERENCES `Novaera`.`Proyecto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`TareaEtapa`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`TareaEtapa` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`TareaEtapa` (
  `id` INT NOT NULL COMMENT '',
  `Tarea` VARCHAR(45) NULL COMMENT '',
  `EntregableTarea` VARCHAR(45) NULL COMMENT '',
  `ArchivoEntregableTarea` VARCHAR(45) NULL COMMENT '',
  `Descripcion` VARCHAR(45) NULL COMMENT '',
  `idEtapa` INT NOT NULL COMMENT '',
  `idPredecesora` INT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_TareaEtapa_EtapaProyecto1_idx` (`idEtapa` ASC)  COMMENT '',
  INDEX `fk_TareaEtapa_TareaEtapa1_idx` (`idPredecesora` ASC)  COMMENT '',
  CONSTRAINT `fk_TareaEtapa_EtapaProyecto1`
    FOREIGN KEY (`idEtapa`)
    REFERENCES `Novaera`.`EtapaProyecto` (`idEtapaProyecto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_TareaEtapa_TareaEtapa1`
    FOREIGN KEY (`idPredecesora`)
    REFERENCES `Novaera`.`TareaEtapa` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Archivos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Archivos` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Archivos` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `Ruta` VARCHAR(500) NULL COMMENT '',
  `idOrganizacionLegal` INT NULL COMMENT '',
  `idEjecucion` INT NULL COMMENT '',
  `idTareaEtapa` INT NULL COMMENT '',
  `idTipoArchivo` INT NOT NULL COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_Archivos_OrganizacionLegal1_idx` (`idOrganizacionLegal` ASC)  COMMENT '',
  INDEX `fk_Archivos_TipoArchivo1_idx` (`idTipoArchivo` ASC)  COMMENT '',
  INDEX `fk_Archivos_Ejecucion1_idx` (`idEjecucion` ASC)  COMMENT '',
  INDEX `fk_Archivos_TareaEtapa1_idx` (`idTareaEtapa` ASC)  COMMENT '',
  CONSTRAINT `fk_Archivos_OrganizacionLegal1`
    FOREIGN KEY (`idOrganizacionLegal`)
    REFERENCES `Novaera`.`OrganizacionLegal` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Archivos_TipoArchivo1`
    FOREIGN KEY (`idTipoArchivo`)
    REFERENCES `Novaera`.`TipoArchivo` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Archivos_Ejecucion1`
    FOREIGN KEY (`idEjecucion`)
    REFERENCES `Novaera`.`Ejecucion` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Archivos_TareaEtapa1`
    FOREIGN KEY (`idTareaEtapa`)
    REFERENCES `Novaera`.`TareaEtapa` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Descriptor_Pesona`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Descriptor_Pesona` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Descriptor_Pesona` (
  `id` INT NOT NULL COMMENT '',
  `idPersona` INT NOT NULL COMMENT '',
  `idDescriptor` INT NOT NULL COMMENT '',
  `FechaInicio` DATE NULL COMMENT '',
  `FechaTemino` DATE NULL COMMENT '',
  `TipoResultado` VARCHAR(45) NULL COMMENT '',
  `NumeroRegistro` VARCHAR(45) NULL COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_Descriptor_Pesona_Persona1_idx` (`idPersona` ASC)  COMMENT '',
  INDEX `fk_Descriptor_Pesona_Descriptor1_idx` (`idDescriptor` ASC)  COMMENT '',
  CONSTRAINT `fk_Descriptor_Pesona_Persona1`
    FOREIGN KEY (`idPersona`)
    REFERENCES `Novaera`.`Persona` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Descriptor_Pesona_Descriptor1`
    FOREIGN KEY (`idDescriptor`)
    REFERENCES `Novaera`.`Descriptor` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`ProgramaFondeo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`ProgramaFondeo` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`ProgramaFondeo` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `Titulo` VARCHAR(45) NULL COMMENT '',
  `PublicoObjetivo` VARCHAR(1000) NULL COMMENT '',
  `FondoTotal` FLOAT NULL COMMENT '',
  `CriteriosElegibilidad` VARCHAR(450) NULL COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`RubrosApoyo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`RubrosApoyo` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`RubrosApoyo` (
  `id` INT NOT NULL COMMENT '',
  `Nombre` VARCHAR(45) NULL COMMENT '',
  `Descripcion` VARCHAR(45) NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`ProgramaFondeo_RubrosApoyo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`ProgramaFondeo_RubrosApoyo` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`ProgramaFondeo_RubrosApoyo` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `idProgramaFondeo` INT NOT NULL COMMENT '',
  `idRubrosApoyo` INT NOT NULL COMMENT '',
  INDEX `fk_ProgramaFondeo_has_RubrosApoyo_RubrosApoyo1_idx` (`idRubrosApoyo` ASC)  COMMENT '',
  INDEX `fk_ProgramaFondeo_has_RubrosApoyo_ProgramaFondeo1_idx` (`idProgramaFondeo` ASC)  COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  CONSTRAINT `fk_ProgramaFondeo_has_RubrosApoyo_ProgramaFondeo1`
    FOREIGN KEY (`idProgramaFondeo`)
    REFERENCES `Novaera`.`ProgramaFondeo` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_ProgramaFondeo_has_RubrosApoyo_RubrosApoyo1`
    FOREIGN KEY (`idRubrosApoyo`)
    REFERENCES `Novaera`.`RubrosApoyo` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Convocatoria`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Convocatoria` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Convocatoria` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `Nombre` VARCHAR(50) NULL COMMENT '',
  `FechaInicio` DATE NULL COMMENT '',
  `FechaTermino` DATE NULL COMMENT '',
  `Requisitos` VARCHAR(450) NULL COMMENT '',
  `MontosMaximosTotales` FLOAT NULL COMMENT '',
  `idProgramaFondeo` INT NOT NULL COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_Convocatoria_ProgramaFondeo1_idx` (`idProgramaFondeo` ASC)  COMMENT '',
  CONSTRAINT `fk_Convocatoria_ProgramaFondeo1`
    FOREIGN KEY (`idProgramaFondeo`)
    REFERENCES `Novaera`.`ProgramaFondeo` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Modalidad`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Modalidad` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Modalidad` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `Nombre` VARCHAR(45) NULL COMMENT '',
  `Montos` DOUBLE NULL COMMENT '',
  `CriteriosEvaluacion` VARCHAR(450) NULL COMMENT '',
  `Entregables` VARCHAR(1000) NULL COMMENT '',
  `FigurasApoyo` VARCHAR(450) NULL COMMENT '',
  `idConvocatoria` INT NOT NULL COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_Modalidad_Convocatoria1_idx` (`idConvocatoria` ASC)  COMMENT '',
  CONSTRAINT `fk_Modalidad_Convocatoria1`
    FOREIGN KEY (`idConvocatoria`)
    REFERENCES `Novaera`.`Convocatoria` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`TRL`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`TRL` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`TRL` (
  `id` INT NOT NULL COMMENT '',
  `Descripcion` VARCHAR(45) NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`ProyectoTRL`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`ProyectoTRL` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`ProyectoTRL` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `idProyecto` INT NOT NULL COMMENT '',
  `idTRL` INT NOT NULL COMMENT '',
  `Descripcion` VARCHAR(450) NULL COMMENT '',
  `Fecha` DATE NULL COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_Proyecto_has_TRL_TRL1_idx` (`idTRL` ASC)  COMMENT '',
  INDEX `fk_Proyecto_has_TRL_Proyecto1_idx` (`idProyecto` ASC)  COMMENT '',
  CONSTRAINT `fk_Proyecto_has_TRL_Proyecto1`
    FOREIGN KEY (`idProyecto`)
    REFERENCES `Novaera`.`Proyecto` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Proyecto_has_TRL_TRL1`
    FOREIGN KEY (`idTRL`)
    REFERENCES `Novaera`.`TRL` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Proyecto_TT`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Proyecto_TT` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Proyecto_TT` (
  `id` INT NOT NULL COMMENT '',
  `ProductosdePropiedad` VARCHAR(45) NULL COMMENT '',
  `ProcesosdeTransferencia` VARCHAR(45) NULL COMMENT '',
  `ValuacioonTecnologica` VARCHAR(45) NULL COMMENT '',
  `idProyecto` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_Proyecto_TT_Proyecto1_idx` (`idProyecto` ASC)  COMMENT '',
  CONSTRAINT `fk_Proyecto_TT_Proyecto1`
    FOREIGN KEY (`idProyecto`)
    REFERENCES `Novaera`.`Proyecto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Proyecto_Modalidad`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Proyecto_Modalidad` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Proyecto_Modalidad` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `idModalidad` INT NOT NULL COMMENT '',
  `idProyecto` INT NOT NULL COMMENT '',
  `Solicitud` VARCHAR(1000) NULL COMMENT '',
  `MontoSolicitado` FLOAT NULL COMMENT '',
  `MontoApoyado` FLOAT NULL COMMENT '',
  `TRLInicial` VARCHAR(45) NULL COMMENT '',
  `TRLFinal` VARCHAR(45) NULL COMMENT '',
  `FechaRegistro` DATE NULL COMMENT '',
  `FechaCierre` DATE NULL COMMENT '',
  `Resultado` VARCHAR(1000) NULL COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_Proyecto_Modalidad_Modalidad1_idx` (`idModalidad` ASC)  COMMENT '',
  INDEX `fk_Proyecto_Modalidad_Proyecto1_idx` (`idProyecto` ASC)  COMMENT '',
  CONSTRAINT `fk_Proyecto_Modalidad_Modalidad1`
    FOREIGN KEY (`idModalidad`)
    REFERENCES `Novaera`.`Modalidad` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Proyecto_Modalidad_Proyecto1`
    FOREIGN KEY (`idProyecto`)
    REFERENCES `Novaera`.`Proyecto` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Organizacion_Modalidad`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Organizacion_Modalidad` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Organizacion_Modalidad` (
  `id` INT NOT NULL COMMENT '',
  `Solicitud` VARCHAR(45) NULL COMMENT '',
  `MontoSolicitado` FLOAT NULL COMMENT '',
  `MontoApoyado` FLOAT NULL COMMENT '',
  `FechaRegistro` DATE NULL COMMENT '',
  `FechaCierre` DATE NULL COMMENT '',
  `idOrganizacion` INT NOT NULL COMMENT '',
  `idModalidad` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_Organizacion_Modalidad_Organizacion1_idx` (`idOrganizacion` ASC)  COMMENT '',
  INDEX `fk_Organizacion_Modalidad_Modalidad1_idx` (`idModalidad` ASC)  COMMENT '',
  CONSTRAINT `fk_Organizacion_Modalidad_Organizacion1`
    FOREIGN KEY (`idOrganizacion`)
    REFERENCES `Novaera`.`Organizacion` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Organizacion_Modalidad_Modalidad1`
    FOREIGN KEY (`idModalidad`)
    REFERENCES `Novaera`.`Modalidad` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`ImpactoyComercializacion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`ImpactoyComercializacion` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`ImpactoyComercializacion` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `ImpactoAmbiental` VARCHAR(250) NULL DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `ImpactoCientifico` VARCHAR(250) NULL DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `ImpactoTecnologico` VARCHAR(250) NULL DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `ImpactoSocial` VARCHAR(250) NULL DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `ImpactoEconomico` VARCHAR(250) NULL DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `PropuestaDeValor` VARCHAR(250) NULL DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `SegmentosDeClientes` VARCHAR(250) NULL DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `SolucionPropuesta` VARCHAR(250) NULL DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `Metricas` VARCHAR(250) NULL DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `SolucionActual` VARCHAR(250) NULL DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `idProyecto` INT NOT NULL COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_ImpactoyComercializacion_Proyecto1_idx` (`idProyecto` ASC)  COMMENT '',
  CONSTRAINT `fk_ImpactoyComercializacion_Proyecto1`
    FOREIGN KEY (`idProyecto`)
    REFERENCES `Novaera`.`Proyecto` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`ProyectoResultados`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`ProyectoResultados` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`ProyectoResultados` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `idProyectoTRL` INT NOT NULL COMMENT '',
  `Avance` VARCHAR(45) NULL COMMENT '',
  `Status` VARCHAR(45) NULL COMMENT '',
  `Fecha` DATE NULL COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` VARCHAR(45) NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_ProyectoResultados_Proyecto_TRL1_idx` (`idProyectoTRL` ASC)  COMMENT '',
  CONSTRAINT `fk_ProyectoResultados_Proyecto_TRL1`
    FOREIGN KEY (`idProyectoTRL`)
    REFERENCES `Novaera`.`ProyectoTRL` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`ResultadoDescriptor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`ResultadoDescriptor` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`ResultadoDescriptor` (
  `idResultadoDescriptor` INT NOT NULL COMMENT '',
  `FechaRegistro` DATE NULL COMMENT '',
  `FechaAprobacion` DATE NULL COMMENT '',
  `idDescriptor` INT NOT NULL COMMENT '',
  PRIMARY KEY (`idResultadoDescriptor`)  COMMENT '',
  INDEX `fk_ResultadoDescriptor_Descriptor1_idx` (`idDescriptor` ASC)  COMMENT '',
  CONSTRAINT `fk_ResultadoDescriptor_Descriptor1`
    FOREIGN KEY (`idDescriptor`)
    REFERENCES `Novaera`.`Descriptor` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Resultado`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Resultado` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Resultado` (
  `idResultado` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `Tipo` VARCHAR(45) NOT NULL COMMENT '',
  `NombreTitulo` VARCHAR(45) NOT NULL COMMENT '',
  `DescripcionResumen` VARCHAR(450) CHARACTER SET 'cp850' NOT NULL COMMENT '',
  `NumeroRegistro` VARCHAR(45) NULL COMMENT '',
  `idProyectoResultados` INT NOT NULL COMMENT '',
  `idResultadoDescriptor` INT NOT NULL COMMENT '',
  PRIMARY KEY (`idResultado`)  COMMENT '',
  INDEX `fk_Resultado_ProyectoResultados1_idx` (`idProyectoResultados` ASC)  COMMENT '',
  INDEX `fk_Resultado_ResultadoDescriptor1_idx` (`idResultadoDescriptor` ASC)  COMMENT '',
  CONSTRAINT `fk_Resultado_ProyectoResultados1`
    FOREIGN KEY (`idProyectoResultados`)
    REFERENCES `Novaera`.`ProyectoResultados` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Resultado_ResultadoDescriptor1`
    FOREIGN KEY (`idResultadoDescriptor`)
    REFERENCES `Novaera`.`ResultadoDescriptor` (`idResultadoDescriptor`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Modaliad_Criterios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Modaliad_Criterios` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Modaliad_Criterios` (
  `id` INT NOT NULL COMMENT '',
  `Descripcion` VARCHAR(45) NULL COMMENT '',
  `Nombre` VARCHAR(45) NULL COMMENT '',
  `idModalidad` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_Modaliad_Criterios_Modalidad1_idx` (`idModalidad` ASC)  COMMENT '',
  CONSTRAINT `fk_Modaliad_Criterios_Modalidad1`
    FOREIGN KEY (`idModalidad`)
    REFERENCES `Novaera`.`Modalidad` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Validacion_Criterio`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Validacion_Criterio` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Validacion_Criterio` (
  `id` INT NOT NULL COMMENT '',
  `idProyectoModalidad` INT NOT NULL COMMENT '',
  `idCriterio` INT NOT NULL COMMENT '',
  `Validado` TINYINT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_Validacion_Modalidad_Proyecto_Modalidad1_idx` (`idProyectoModalidad` ASC)  COMMENT '',
  INDEX `fk_Validacion_Modalidad_Modaliad_Criterios1_idx` (`idCriterio` ASC)  COMMENT '',
  CONSTRAINT `fk_Validacion_Modalidad_Proyecto_Modalidad1`
    FOREIGN KEY (`idProyectoModalidad`)
    REFERENCES `Novaera`.`Proyecto_Modalidad` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Validacion_Modalidad_Modaliad_Criterios1`
    FOREIGN KEY (`idCriterio`)
    REFERENCES `Novaera`.`Modaliad_Criterios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`ModeloNegocio`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`ModeloNegocio` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`ModeloNegocio` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `Canales` VARCHAR(250) NULL DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `VentajaCompetitiva` VARCHAR(250) NULL DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `Problematica` VARCHAR(250) NULL DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `Costos` VARCHAR(250) NULL DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `Ingresos` VARCHAR(250) NULL DEFAULT 'N' COMMENT 'EL char sera equivalente a un ENUM N=null U= subido\nV=validado',
  `ActividadesClave` VARCHAR(250) NULL COMMENT '',
  `RelacionesCliente` VARCHAR(250) NULL COMMENT '',
  `RecursosClave` VARCHAR(250) NULL COMMENT '',
  `AliadosClave` VARCHAR(250) NULL COMMENT '',
  `idProyecto` INT NOT NULL COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_ModeloNegocio_Proyecto1_idx` (`idProyecto` ASC)  COMMENT '',
  CONSTRAINT `fk_ModeloNegocio_Proyecto1`
    FOREIGN KEY (`idProyecto`)
    REFERENCES `Novaera`.`Proyecto` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Persona_Proyecto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Persona_Proyecto` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Persona_Proyecto` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `idProyecto` INT NOT NULL COMMENT '',
  `idPersona` INT NOT NULL COMMENT '',
  `Owner` TINYINT NOT NULL DEFAULT 0 COMMENT '',
  `created_at` TIMESTAMP NULL COMMENT '',
  `updated_at` TIMESTAMP NULL COMMENT '',
  INDEX `fk_Proyecto_has_Persona_Persona1_idx` (`idPersona` ASC)  COMMENT '',
  INDEX `fk_Proyecto_has_Persona_Proyecto1_idx` (`idProyecto` ASC)  COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `unique_proyecto_persona` USING BTREE (`idProyecto` ASC, `idPersona` ASC)  COMMENT '',
  CONSTRAINT `fk_Proyecto_has_Persona_Proyecto1`
    FOREIGN KEY (`idProyecto`)
    REFERENCES `Novaera`.`Proyecto` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Proyecto_has_Persona_Persona1`
    FOREIGN KEY (`idPersona`)
    REFERENCES `Novaera`.`Persona` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Novaera`.`Organizacion_Proyecto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Novaera`.`Organizacion_Proyecto` ;

CREATE TABLE IF NOT EXISTS `Novaera`.`Organizacion_Proyecto` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `idOrganizacion` INT NOT NULL COMMENT '',
  `idProyecto` INT NOT NULL COMMENT '',
  `Owner` TINYINT NOT NULL DEFAULT 0 COMMENT '',
  INDEX `fk_Organizacion_has_Proyecto_Proyecto1_idx` (`idProyecto` ASC)  COMMENT '',
  INDEX `fk_Organizacion_has_Proyecto_Organizacion1_idx` (`idOrganizacion` ASC)  COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  CONSTRAINT `fk_Organizacion_has_Proyecto_Organizacion1`
    FOREIGN KEY (`idOrganizacion`)
    REFERENCES `Novaera`.`Organizacion` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Organizacion_has_Proyecto_Proyecto1`
    FOREIGN KEY (`idProyecto`)
    REFERENCES `Novaera`.`Proyecto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
