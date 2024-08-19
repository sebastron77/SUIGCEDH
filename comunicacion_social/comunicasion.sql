CREATE TABLE `comunicados` (
  `id_comunicados` INTEGER NOT NULL AUTO_INCREMENT,
  `folio` VARCHAR(25)  NOT NULL,
  `fecha_publicacion` DATE NOT NULL,
  `tipo_nota` VARCHAR(20)  NOT NULL,
  `nombre_nota` TEXT  NOT NULL,
  `url_acceso` TEXT  DEFAULT NULL,
  `observaciones` TEXT  DEFAULT NULL,
  `user_creador` INTEGER NOT NULL,
  `fecha_creacion` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY USING BTREE (`id_comunicados`),
  UNIQUE KEY `id_comunicados` USING BTREE (`id_comunicados`),
  UNIQUE KEY `folio` USING BTREE (`folio`),
  KEY `comunicados_fk1` USING BTREE (`user_creador`),
  CONSTRAINT `comunicados_fk1` FOREIGN KEY (`user_creador`) REFERENCES `users` (`id_user`)
) ENGINE=InnoDB
ROW_FORMAT=DYNAMIC CHARACTER SET 'utf8mb4' ;

CREATE TABLE `disenios` (
  `id_disenios` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
  `folio` VARCHAR(50) NOT NULL UNIQUE,
  `fecha_solicitud` DATETIME NOT NULL,
  `fecha_entrega` DATETIME NOT NULL,
  `tipo_disenio` VARCHAR(30) NOT NULL,
  `tema_disenio` TEXT NOT NULL,
  `id_area_solicitud` INTEGER NOT NULL,
  `observaciones` TEXT NOT NULL,
  `user_creador` INTEGER NOT NULL,
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_disenios`)
) ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

CREATE TABLE `entrevistas` (
  `id_entrevistas` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
  `folio` VARCHAR(30) NOT NULL,
  `fecha_entrevista` DATE NOT NULL,
  `tema_entrevista` TEXT NOT NULL,
  `lugar_entrevista` VARCHAR(250) NOT NULL,
  `nombre_entrevistado` VARCHAR(250) NOT NULL,
  `cargo_entrevistado` VARCHAR(250) NOT NULL,
  `temas_destacados` TEXT NOT NULL,
  `observaciones` TEXT NOT NULL,
  `user_creador` INTEGER NOT NULL,
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_entrevistas`)
) ENGINE=InnoDB
ROW_FORMAT=DEFAULT;
;