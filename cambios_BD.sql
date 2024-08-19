CREATE TABLE `cat_agendas` (
  `id_cat_agendas` INTEGER NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(250) DEFAULT NULL,
  `estatus` TINYINT NOT NULL,
  `color_estadistica` VARCHAR(10) DEFAULT NULL,
  PRIMARY KEY USING BTREE (`id_cat_agendas`),
  UNIQUE KEY `id_cat_agendas` USING BTREE (`id_cat_agendas`)
) ENGINE=InnoDB
AUTO_INCREMENT=1 ROW_FORMAT=DYNAMIC CHARACTER SET 'utf8mb4' ;

CREATE TABLE `cat_ejes_estrategicos` (
  `id_cat_ejes_estrategicos` INTEGER NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(250) DEFAULT NULL,
  `estatus` TINYINT NOT NULL,
  `color_estadistica` VARCHAR(10) DEFAULT NULL,
  PRIMARY KEY USING BTREE (`id_cat_ejes_estrategicos`),
  UNIQUE KEY `id_cat_ejes_estrategicos` USING BTREE (`id_cat_ejes_estrategicos`)
) ENGINE=InnoDB
AUTO_INCREMENT=1 ROW_FORMAT=DYNAMIC CHARACTER SET 'utf8mb4' ;

CREATE TABLE `entregables` (
  `id_entregables` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
  `folio` VARCHAR(20) NOT NULL UNIQUE,
  `tipo_estregable` VARCHAR(20) NOT NULL,
  `nombre_entragable` VARCHAR(250) NOT NULL,
  `id_cat_ejes_estrategicos` INTEGER NOT NULL,
  `id_cat_agendas` INTEGER NOT NULL,
  `descripcion` TEXT NOT NULL,
  `liga_acceso` VARCHAR(250) NOT NULL,
  `no_isbn` VARCHAR(20) NOT NULL,
  `id_user_creador` INTEGER NOT NULL,
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_entregables`)
) ENGINE=InnoDB
AUTO_INCREMENT=1 ROW_FORMAT=DYNAMIC CHARACTER SET 'utf8mb4' ;
ALTER TABLE `entregables` ADD CONSTRAINT `entregables_fk1` FOREIGN KEY (`id_cat_ejes_estrategicos`) REFERENCES `cat_ejes_estrategicos` (`id_cat_ejes_estrategicos`);

ALTER TABLE `entregables` ADD CONSTRAINT `entregables_fk2` FOREIGN KEY (`id_cat_agendas`) REFERENCES `cat_agendas` (`id_cat_agendas`);


ALTER TABLE `gestiones_jurisdiccionales` ADD COLUMN `liga` TEXT DEFAULT NULL AFTER `tipo_gestion`;


ALTER TABLE `informe_actividades_areas` MODIFY COLUMN `area_creacion` INTEGER NOT NULL;


ALTER TABLE `eventos` MODIFY COLUMN `constancia` VARCHAR(250) COLLATE utf8mb3_spanish_ci DEFAULT NULL;

ALTER TABLE `capacitaciones` MODIFY COLUMN `no_asistentes` INTEGER NOT NULL DEFAULT 0;
ALTER TABLE `capacitaciones` CHANGE COLUMN `depto_org` `id_area` INTEGER NOT NULL;
ALTER TABLE `capacitaciones` ADD COLUMN `user_creador` INTEGER NOT NULL AFTER `area_creacion`;
ALTER TABLE `capacitaciones` ADD COLUMN `tipo_capacitacion` VARCHAR(20) NOT NULL DEFAULT 'Impartida' AFTER `id_capacitacion`;
ALTER TABLE `capacitaciones` ADD COLUMN `asistentes_hombres` INTEGER NOT NULL AFTER `no_asistentes`;
ALTER TABLE `capacitaciones` ADD COLUMN `asistentes_mujeres` INTEGER NOT NULL AFTER `no_asistentes`;
ALTER TABLE `capacitaciones` ADD COLUMN `asistentes_nobinario` INTEGER NOT NULL AFTER `no_asistentes`;
ALTER TABLE `capacitaciones` ADD COLUMN `asistentes_otros` INTEGER NOT NULL AFTER `no_asistentes`;
ALTER TABLE `capacitaciones` ADD COLUMN `asistentes_10` INTEGER NOT NULL DEFAULT 0 AFTER `asistentes_hombres`;
ALTER TABLE `capacitaciones` ADD COLUMN `asistentes_20` INTEGER NOT NULL DEFAULT 0 AFTER `asistentes_10`;
ALTER TABLE `capacitaciones` ADD COLUMN `asistentes_30` INTEGER NOT NULL DEFAULT 0 AFTER `asistentes_20`;
ALTER TABLE `capacitaciones` ADD COLUMN `asistentes_40` INTEGER NOT NULL DEFAULT 0 AFTER `asistentes_30`;
ALTER TABLE `capacitaciones` ADD COLUMN `asistentes_50` INTEGER NOT NULL DEFAULT 0 AFTER `asistentes_40`;
ALTER TABLE `capacitaciones` ADD COLUMN `asistentes_60` INTEGER NOT NULL DEFAULT 0 AFTER `asistentes_50`;
ALTER TABLE `capacitaciones` ADD COLUMN `asistentes_70` INTEGER NOT NULL DEFAULT 0 AFTER `asistentes_60`;
ALTER TABLE `capacitaciones` ADD COLUMN `asistentes_80` INTEGER NOT NULL DEFAULT 0 AFTER `asistentes_70`;
ALTER TABLE `capacitaciones` ADD COLUMN `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `capacitaciones` MODIFY COLUMN `area_creacion` INTEGER NOT NULL;

ALTER TABLE `capacitaciones` ADD CONSTRAINT `capacitaciones_fk1` FOREIGN KEY (`id_area`) REFERENCES `area` (`id_area`);
ALTER TABLE `capacitaciones` ADD CONSTRAINT `capacitaciones_fk2` FOREIGN KEY (`user_creador`) REFERENCES `users` (`id_user`);



CREATE TABLE `rel_capacitacion_grupos` (
  `id_rel_capacitacion_grupos` INTEGER NOT NULL AUTO_INCREMENT,
  `id_capacitacion` INTEGER NOT NULL,
  `id_cat_grupo_vuln` INTEGER NOT NULL,
  `no_asistentes` INTEGER NOT NULL,
  PRIMARY KEY USING BTREE (`id_rel_capacitacion_grupos`),
  UNIQUE KEY `id_rel_capacitacion_grupos` USING BTREE (`id_rel_capacitacion_grupos`),
  KEY `rel_capacitacion_grupos_fk1` USING BTREE (`id_capacitacion`),
  KEY `rel_capacitacion_grupos_fk2` USING BTREE (`id_cat_grupo_vuln`),
  CONSTRAINT `rel_capacitacion_grupos_fk1` FOREIGN KEY (`id_capacitacion`) REFERENCES `capacitaciones` (`id_capacitacion`),
  CONSTRAINT `rel_capacitacion_grupos_fk2` FOREIGN KEY (`id_cat_grupo_vuln`) REFERENCES `cat_grupos_vuln` (`id_cat_grupo_vuln`)
) ENGINE=InnoDB
ROW_FORMAT=DYNAMIC CHARACTER SET 'utf8mb4' ;