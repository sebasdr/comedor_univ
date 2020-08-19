create database comedordb;
USE comedordb ;

-- -----------------------------------------------------
-- Table `comedordb`.`facultad`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS facultad (
  codF INT NOT NULL,
  nombF VARCHAR(45) NOT NULL,
  PRIMARY KEY (codF))
  ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `comedordb`.`carrera`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS carrera (
  codCar INT NOT NULL,
  nombCar VARCHAR(100) NOT NULL,
  facultad_codF INT NOT NULL,
  PRIMARY KEY (codCar),
  CONSTRAINT fk_carrera_facultad
    FOREIGN KEY (facultad_codF)
    REFERENCES facultad (codF))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `comedordb`.`comidas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS comidas (
  codCo INT NOT NULL,
  nombCo VARCHAR(45) NOT NULL,
  PRIMARY KEY (codCo))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `comedordb`.`estudiante`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS estudiante (
  codE CHAR(10) NOT NULL,
  dniE VARCHAR(8) NOT NULL,
  nombE VARCHAR(45) NOT NULL,
  apelE VARCHAR(45) NOT NULL,
  facultad_codF INT NOT NULL,
  carrera_codCar INT NOT NULL,
  telfE VARCHAR(9) NULL DEFAULT NULL,
  dirE VARCHAR(200) NULL DEFAULT NULL,
  PRIMARY KEY (codE),
  CONSTRAINT fk_estudiante_carrera1
    FOREIGN KEY (carrera_codCar)
    REFERENCES carrera (codCar),
  CONSTRAINT fk_estudiante_facultad1
    FOREIGN KEY (facultad_codF)
    REFERENCES facultad (codF))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `comedordb`.`sede`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS sede (
  codS INT NOT NULL,
  nombS VARCHAR(45) NOT NULL,
  PRIMARY KEY (codS))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `comedordb`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS usuario (
  dniU CHAR(8) NOT NULL,
  passU VARCHAR(200) NOT NULL,
  nombU VARCHAR(45) NOT NULL,
  apelU VARCHAR(45) NOT NULL,
  telfU VARCHAR(9) NULL DEFAULT NULL,
  dirU VARCHAR(200) NULL DEFAULT NULL,
  PRIMARY KEY (dniU))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `comedordb`.`tarjeta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS tarjeta (
  codT INT NOT NULL,
  sem CHAR(5) NOT NULL,
  fechCreacion DATE NOT NULL,
  estudiante_codE CHAR(10) NOT NULL,
  sede_codS INT NOT NULL,
  usuario_dniU char(8) NOT NULL,
  PRIMARY KEY (codT, sem),
  CONSTRAINT fk_tarjeta_estudiante1
    FOREIGN KEY (estudiante_codE)
    REFERENCES estudiante (codE),
  CONSTRAINT fk_tarjeta_sede1
    FOREIGN KEY (sede_codS)
    REFERENCES sede (codS),
  CONSTRAINT fk_tarjeta_usuario1
    FOREIGN KEY (usuario_dniU)
    REFERENCES comedordb.usuario (dniU))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `comedordb`.`registro`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS registro (
  tarjeta_codT INT NOT NULL,
  tarjeta_sem CHAR(5) NOT NULL,
  comidas_codCo INT NOT NULL,
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  usuario_dniU char(8) NOT NULL,
  PRIMARY KEY (tarjeta_codT, tarjeta_sem, comidas_codCo, fecha),
  CONSTRAINT fk_tarjeta_has_comidas_comidas1
    FOREIGN KEY (comidas_codCo)
    REFERENCES comidas (codCo),
  CONSTRAINT fk_tarjeta_has_comidas_tarjeta1
    FOREIGN KEY (tarjeta_codT , tarjeta_sem)
    REFERENCES tarjeta (codT , sem),
  CONSTRAINT fk_tarjeta_has_comidas_usuario1
    FOREIGN KEY (usuario_dniU)
    REFERENCES usuario (dniU))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- Registros
-- -Facultad
insert into facultad values (1, "Ingenieria");
insert into facultad values (2, "Ciencias de la empresa");
select * from facultad;

-- -Carrera
insert into carrera values (1, "Administracion de Empresas", 2);
insert into carrera values (2, "Contabilidad", 2);
insert into carrera values (3, "Educacion Primaria Intercultural", 2);
insert into carrera values (4, "Ingenieria Agroindustrial", 1);
insert into carrera values (5, "Ingenieria Ambiental", 1);
insert into carrera values (6, "Ingenieria de Sistemas", 1);

-- -Sede
insert into sede values (1, "San Jeronimo");
insert into sede values (2, "Talavera");