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
-- Table `comedordb`.`ciclo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS ciclo (
  codC INT NOT NULL,
  PRIMARY KEY (codC))
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
  ciclo_codC INT NOT NULL,
  telfE VARCHAR(9) NULL DEFAULT NULL,
  dirE VARCHAR(200) NULL DEFAULT NULL,
  PRIMARY KEY (codE),
  CONSTRAINT fk_estudiante_carrera1
    FOREIGN KEY (carrera_codCar)
    REFERENCES carrera (codCar),
  CONSTRAINT fk_estudiante_ciclo1
    FOREIGN KEY (ciclo_codC)
    REFERENCES ciclo (codC),
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
  codU INT NOT NULL,
  passU VARCHAR(200) NOT NULL,
  dniU CHAR(8) NOT NULL,
  nombU VARCHAR(45) NOT NULL,
  apelU VARCHAR(45) NOT NULL,
  telfU VARCHAR(9) NULL DEFAULT NULL,
  dirU VARCHAR(200) NULL DEFAULT NULL,
  PRIMARY KEY (codU))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `comedordb`.`tarjeta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS tarjeta (
  codT CHAR(6) NOT NULL,
  sem CHAR(5) NOT NULL,
  fechCreacion DATE NOT NULL,
  estudiante_codE CHAR(10) NOT NULL,
  sede_codS INT NOT NULL,
  usuario_codU INT NOT NULL,
  PRIMARY KEY (codT, sem),
  CONSTRAINT fk_tarjeta_estudiante1
    FOREIGN KEY (estudiante_codE)
    REFERENCES estudiante (codE),
  CONSTRAINT fk_tarjeta_sede1
    FOREIGN KEY (sede_codS)
    REFERENCES sede (codS),
  CONSTRAINT fk_tarjeta_usuario1
    FOREIGN KEY (usuario_codU)
    REFERENCES comedordb.usuario (codU))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `comedordb`.`registro`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS registro (
  tarjeta_codT CHAR(6) NOT NULL,
  tarjeta_sem CHAR(5) NOT NULL,
  comidas_codCo INT NOT NULL,
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  usuario_codU INT NOT NULL,
  PRIMARY KEY (tarjeta_codT, tarjeta_sem, comidas_codCo, fecha),
  CONSTRAINT fk_tarjeta_has_comidas_comidas1
    FOREIGN KEY (comidas_codCo)
    REFERENCES comidas (codCo),
  CONSTRAINT fk_tarjeta_has_comidas_tarjeta1
    FOREIGN KEY (tarjeta_codT , tarjeta_sem)
    REFERENCES tarjeta (codT , sem),
  CONSTRAINT fk_tarjeta_has_comidas_usuario1
    FOREIGN KEY (usuario_codU)
    REFERENCES usuario (codU))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;