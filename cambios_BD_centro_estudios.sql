CREATE TABLE `cat_modalidad` (
  `id_cat_modalidad` INTEGER NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(100) NOT NULL,
  `estatus` BIT NOT NULL DEFAULT 1,
  `color_estadistica` VARCHAR(10) DEFAULT NULL,
  PRIMARY KEY (`id_cat_modalidad`)
) ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

CREATE TABLE `cat_publico_objetivo` (
  `id_cat_publico_objetivo` INTEGER NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(150)  NOT NULL,
  `estatus` BIT(1) NOT NULL DEFAULT b'1',
  `color_estadistica` VARCHAR(20)  DEFAULT NULL,
  PRIMARY KEY USING BTREE (`id_cat_publico_objetivo`),
  UNIQUE KEY `id_cat_publico_objetivo` USING BTREE (`id_cat_publico_objetivo`)
) ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

CREATE TABLE `cat_tipo_actividad` (
  `id_cat_tipo_actividad` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
  `descripcion` VARCHAR(150) NOT NULL,
  `estatus` BIT NOT NULL DEFAULT 1,
  `color_estadistica` VARCHAR(10) DEFAULT NULL,
  PRIMARY KEY (`id_cat_tipo_actividad`)
) ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

CREATE TABLE `cat_categoria_actividad` (
  `id_cat_categoria_actividad` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
  `descripcion` VARCHAR(150) NOT NULL,
  `estatus` BIT NOT NULL DEFAULT 1,
  `color_estadistica` VARCHAR(10) DEFAULT NULL,
  PRIMARY KEY (`id_cat_categoria_actividad`)
) ENGINE=InnoDB
ROW_FORMAT=DEFAULT;


CREATE TABLE `cursos_diplomados` (
  `id_cursos_diplomados` INTEGER NOT NULL,
  `folio` VARCHAR(20) NOT NULL UNIQUE ,
  `id_cat_ejes_estrategicos` INTEGER NOT NULL,
  `id_cat_agendas` INTEGER NOT NULL,
  `id_cat_tipo_actividad` INTEGER NOT NULL,
  `id_cat_categoria_actividad` INTEGER NOT NULL,
  `nombre_curso` VARCHAR(150) NOT NULL,
  `objetivo` TEXT NOT NULL,
  `descripcion` TEXT NOT NULL,
  `fecha_apertura` DATE NOT NULL,
  `duracion_horas` INTEGER NOT NULL,
  `liga_acceso` TEXT NOT NULL,
  `id_area_responsable` INTEGER NOT NULL,
  `nombre_responsable` VARCHAR(150) NOT NULL,
  `id_cat_modalidad` INTEGER NOT NULL,
  `fecha_tecnica` VARCHAR(150) NOT NULL,
  `expediente_tecnico` VARCHAR(150) NOT NULL,
  `observaciones` TEXT NOT NULL,
  `user_creador` INTEGER NOT NULL,
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB
ROW_FORMAT=DEFAULT;


ALTER TABLE `cursos_diplomados` ADD CONSTRAINT `cursos_diplomados_fk1` FOREIGN KEY (`id_cat_ejes_estrategicos`) REFERENCES `cat_ejes_estrategicos` (`id_cat_ejes_estrategicos`);
ALTER TABLE `cursos_diplomados` ADD CONSTRAINT `cursos_diplomados_fk2` FOREIGN KEY (`id_cat_agendas`) REFERENCES `cat_agendas` (`id_cat_agendas`);
ALTER TABLE `cursos_diplomados` ADD CONSTRAINT `cursos_diplomados_fk3` FOREIGN KEY (`id_area_responsable`) REFERENCES `area` (`id_area`);
ALTER TABLE `cursos_diplomados` ADD CONSTRAINT `cursos_diplomados_fk4` FOREIGN KEY (`id_cat_modalidad`) REFERENCES `cat_modalidad` (`id_cat_modalidad`);
ALTER TABLE `cursos_diplomados` ADD CONSTRAINT `cursos_diplomados_fk5` FOREIGN KEY (`user_creador`) REFERENCES `users` (`id_user`);
ALTER TABLE `cursos_diplomados` ADD CONSTRAINT `cursos_diplomados_fk6` FOREIGN KEY (`id_cat_tipo_actividad`) REFERENCES `cat_tipo_actividad` (`id_cat_tipo_actividad`);
ALTER TABLE `cursos_diplomados` ADD CONSTRAINT `cursos_diplomados_fk7` FOREIGN KEY (`id_cat_categoria_actividad`) REFERENCES `cat_categoria_actividad` (`id_cat_categoria_actividad`);


CREATE TABLE `rel_cursos_publico` (
  `id_rel_cursos_publico` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
  `id_cursos_diplomados` INTEGER NOT NULL,
  `id_cat_publico_objetivo` INTEGER NOT NULL,
  PRIMARY KEY (`id_rel_cursos_publico`)
) ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

ALTER TABLE `rel_cursos_publico` ADD CONSTRAINT `rel_cursos_publico_fk1` FOREIGN KEY (`id_cursos_diplomados`) REFERENCES `cursos_diplomados` (`id_cursos_diplomados`);
ALTER TABLE `rel_cursos_publico` ADD CONSTRAINT `rel_cursos_publico_fk2` FOREIGN KEY (`id_cat_publico_objetivo`) REFERENCES `cat_publico_objetivo` (`id_cat_publico_objetivo`);




CREATE TABLE `rel_cursos_grupos_vulnerables` (
  `id_rel_cursos_grupos_vulnerables` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
  `id_cursos_diplomados` INTEGER NOT NULL,
  `id_cat_grupo_vuln` INTEGER NOT NULL,
  PRIMARY KEY (`id_rel_cursos_grupos_vulnerables`)
) ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

ALTER TABLE `rel_cursos_grupos_vulnerables` ADD CONSTRAINT `rel_cursos_grupos_vulnerables_fk1` FOREIGN KEY (`id_cursos_diplomados`) REFERENCES `cursos_diplomados` (`id_cursos_diplomados`);
ALTER TABLE `rel_cursos_grupos_vulnerables` ADD CONSTRAINT `rel_cursos_grupos_vulnerables_fk2` FOREIGN KEY (`id_cat_grupo_vuln`) REFERENCES `cat_grupos_vuln` (`id_cat_grupo_vuln`);



CREATE TABLE `certificaciones` (
  `id_certificaciones` INTEGER NOT NULL AUTO_INCREMENT,
  `folio` VARCHAR(20) DEFAULT NULL,
  `nombre_certificacion` VARCHAR(250) NOT NULL,
  `objetivo_certificacion` TEXT NOT NULL,
  `id_cat_ejes_estrategicos` INTEGER NOT NULL,
  `id_cat_agendas` INTEGER NOT NULL,
  `emisor_certificacion` VARCHAR(250) NOT NULL,
  `contacto_certificacion` VARCHAR(250) NOT NULL,
  `fecha_inicio_proceso` DATE NOT NULL,
  `avance_proceso` INTEGER NOT NULL,
  `fecha_actualizacion_avance` DATE DEFAULT NULL,
  `id_area_responsable` INTEGER NOT NULL,
  `nombre_responsable` VARCHAR(250) NOT NULL,
  `ficha_tecnica` VARCHAR(150) NOT NULL,
  `expediente_tecnico` VARCHAR(150) NOT NULL,
  `observaciones` TEXT NOT NULL,
  `user_creador` INTEGER NOT NULL,
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_certificaciones`)
) ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

ALTER TABLE `certificaciones` ADD CONSTRAINT `certificaciones_fk1` FOREIGN KEY (`id_cat_ejes_estrategicos`) REFERENCES `cat_ejes_estrategicos` (`id_cat_ejes_estrategicos`);
ALTER TABLE `certificaciones` ADD CONSTRAINT `certificaciones_fk2` FOREIGN KEY (`id_cat_agendas`) REFERENCES `cat_agendas` (`id_cat_agendas`);
ALTER TABLE `certificaciones` ADD CONSTRAINT `certificaciones_fk3` FOREIGN KEY (`id_area_responsable`) REFERENCES `area` (`id_area`);
ALTER TABLE `certificaciones` ADD CONSTRAINT `certificaciones_fk4` FOREIGN KEY (`user_creador`) REFERENCES `users` (`id_user`);



CREATE TABLE `rel_cetificaciones_publico` (
  `id_rel_cetificaciones_publico` INTEGER NOT NULL,
  `id_certificaciones` INTEGER NOT NULL,
  `id_cat_publico_objetivo` INTEGER NOT NULL
) ENGINE=InnoDB;
ALTER TABLE `rel_cetificaciones_publico` ADD CONSTRAINT `rel_cetificaciones_publico_fk2` FOREIGN KEY (`id_cat_publico_objetivo`) REFERENCES `cat_publico_objetivo` (`id_cat_publico_objetivo`);
ALTER TABLE `rel_cetificaciones_publico` ADD CONSTRAINT `rel_cetificaciones_publico_fk1` FOREIGN KEY (`id_certificaciones`) REFERENCES `certificaciones` (`id_certificaciones`);


CREATE TABLE `rel_certificaciones_grupos_vulnerables` (
  `id_rel_certificaciones_grupos_vulnerables` INTEGER NOT NULL,
  `id_certificaciones` INTEGER NOT NULL,
  `id_cat_grupo_vuln` INTEGER NOT NULL
) ENGINE=InnoDB ;
ALTER TABLE `rel_certificaciones_grupos_vulnerables` ADD PRIMARY KEY USING BTREE (`id_rel_certificaciones_grupos_vulnerables`);

ALTER TABLE `rel_certificaciones_grupos_vulnerables` ADD UNIQUE INDEX `id_rel_certificaciones_grupos_vulnerables` USING BTREE (`id_rel_cursos_grupos_vulnerables`);

ALTER TABLE `rel_certificaciones_grupos_vulnerables` ADD INDEX `rel_certificaciones_grupos_vulnerables_fk1` USING BTREE (`id_certificaciones`);

ALTER TABLE `rel_certificaciones_grupos_vulnerables` ADD INDEX `rel_certificaciones_grupos_vulnerables_fk2` USING BTREE (`id_cat_grupo_vuln`);

ALTER TABLE `rel_certificaciones_grupos_vulnerables` ADD CONSTRAINT `rel_certificaciones_grupos_vulnerables_fk1` FOREIGN KEY (`id_certificaciones`) REFERENCES `certificaciones` (`id_certificaciones`);

ALTER TABLE `rel_certificaciones_grupos_vulnerables` ADD CONSTRAINT `rel_certificaciones_grupos_vulnerables_fk2` FOREIGN KEY (`id_cat_grupo_vuln`) REFERENCES `cat_grupos_vuln` (`id_cat_grupo_vuln`);

ALTER TABLE `rel_certificaciones_grupos_vulnerables` MODIFY COLUMN `id_rel_certificaciones_grupos_vulnerables` INTEGER NOT NULL AUTO_INCREMENT ;


CREATE TABLE `colecciones_estudios` (
  `id_colecciones_estudios` INTEGER NOT NULL AUTO_INCREMENT,
  `folio` VARCHAR(20) NOT NULL,
  `nombre_coleccion` VARCHAR(200) NOT NULL,
  `id_area_responsable` INTEGER NOT NULL,
  `nombre_responsable` VARCHAR(200) NOT NULL,
  `tipo_publicacion` VARCHAR(20) NOT NULL,
  `temporalidad_proyecto` VARCHAR(20) NOT NULL,
  `hipervinculo_proyecto` VARCHAR(250) NOT NULL,
  `observaciones` TEXT NOT NULL,
  `id_user_creador` INTEGER NOT NULL,
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_colecciones_estudios`)
) ENGINE=InnoDB
ROW_FORMAT=DEFAULT;


ALTER TABLE `colecciones_estudios` ADD CONSTRAINT `colecciones_estudios_fk1` FOREIGN KEY (`id_area_responsable`) REFERENCES `area` (`id_area`);
ALTER TABLE `colecciones_estudios` ADD CONSTRAINT `colecciones_estudios_fk2` FOREIGN KEY (`id_user_creador`) REFERENCES `users` (`id_user`);