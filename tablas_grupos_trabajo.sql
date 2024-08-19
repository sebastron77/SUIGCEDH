CREATE TABLE `cat_tipo_accion` (
  `id_cat_tipo_accion` INTEGER NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(150) NOT NULL,
  `estatus` BIT(1) NOT NULL DEFAULT b'1',
  `color_estadistica` VARCHAR(10) DEFAULT NULL,
  PRIMARY KEY USING BTREE (`id_cat_tipo_accion`),
  UNIQUE KEY `id_cat_tipo_accion` USING BTREE (`id_cat_tipo_accion`)
) ENGINE=InnoDB
AUTO_INCREMENT=1;



CREATE TABLE `grupos_trabajo` (
  `id_grupos_trabajo` INTEGER NOT NULL AUTO_INCREMENT,
  `id_cat_tipo_accion` INTEGER NOT NULL,
  `nombre_grupo` VARCHAR(200) NOT NULL,
  `fecha_sesion` DATE NOT NULL,
  `lugar_sesion` VARCHAR(200) NOT NULL,
  `numero_sesion` INTEGER NOT NULL,
  `documento` VARCHAR(200) NOT NULL,
  `alcances_acuerdos` TEXT NOT NULL,
  `observaciones` TEXT NOT NULL,
  `user_creador` INTEGER NOT NULL,
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_grupos_trabajo`)
) ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

ALTER TABLE `grupos_trabajo` ADD CONSTRAINT `grupos_trabajo_fk1` FOREIGN KEY (`id_cat_tipo_accion`) REFERENCES `cat_tipo_accion` (`id_cat_tipo_accion`);
ALTER TABLE `grupos_trabajo` ADD CONSTRAINT `grupos_trabajo_fk2` FOREIGN KEY (`user_creador`) REFERENCES `users` (`id_user`);
ALTER TABLE `grupos_trabajo` ADD COLUMN `folio` VARCHAR(50) NOT NULL AFTER `id_grupos_trabajo`;
ALTER TABLE `grupos_trabajo` ADD COLUMN `no_asistentes` INTEGER NOT NULL AFTER `numero_sesion`;
ALTER TABLE `grupos_trabajo` ADD COLUMN `modalidad` VARCHAR(50) NOT NULL AFTER `numero_sesion`;



CREATE TABLE `rel_grupos_trabajo_participantes` (
  `id_rel_grupos_trabajo_participantes` INTEGER NOT NULL AUTO_INCREMENT,
  `id_grupos_trabajo` INTEGER NOT NULL,
  `nombre_participante` VARCHAR(250) NOT NULL,
  PRIMARY KEY (`id_rel_grupos_trabajo_participantes`)
) ENGINE=InnoDB
ROW_FORMAT=DEFAULT;
ALTER TABLE `rel_grupos_trabajo_participantes` ADD CONSTRAINT `rel_grupos_trabajo_participantes_fk1` FOREIGN KEY (`id_grupos_trabajo`) REFERENCES `grupos_trabajo` (`id_grupos_trabajo`);