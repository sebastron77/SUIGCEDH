CREATE TABLE `cat_victima_atendida` (
  `id_cat_victima_atendida` INTEGER NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(150) NOT NULL DEFAULT '""',
  `paterno` VARCHAR(150) DEFAULT '""',
  `materno` VARCHAR(150) DEFAULT '""',
  `id_cat_gen` INTEGER NOT NULL,
  `edad` INTEGER NOT NULL,
  `id_cat_nacionalidad` INTEGER NOT NULL,
  `id_cat_ent_fed` INTEGER NOT NULL,
  `id_cat_mun` INTEGER NOT NULL,
  `id_cat_escolaridad` INTEGER NOT NULL,
  `id_cat_ocup` INTEGER NOT NULL,
  `leer_escribir` VARCHAR(50) NOT NULL,
  `id_cat_grupo_vuln` INTEGER NOT NULL,
  `id_cat_disc` INTEGER NOT NULL,
  `id_cat_comun` INTEGER NOT NULL
  PRIMARY KEY USING BTREE (`id_cat_victima_atendida`)
) ENGINE=InnoDB
AUTO_INCREMENT=1;


ALTER TABLE `cat_victima_atendida` ADD CONSTRAINT `cat_victima_atendida_fk1` FOREIGN KEY (`id_cat_gen`) REFERENCES `cat_genero` (`id_cat_gen`);
ALTER TABLE `cat_victima_atendida` ADD CONSTRAINT `cat_victima_atendida_fk2` FOREIGN KEY (`id_cat_nacionalidad`) REFERENCES `cat_nacionalidades` (`id_cat_nacionalidad`);
ALTER TABLE `cat_victima_atendida` ADD CONSTRAINT `cat_victima_atendida_fk3` FOREIGN KEY (`id_cat_ent_fed`) REFERENCES `cat_entidad_fed` (`id_cat_ent_fed`);
ALTER TABLE `cat_victima_atendida` ADD CONSTRAINT `cat_victima_atendida_fk4` FOREIGN KEY (`id_cat_mun`) REFERENCES `cat_municipios` (`id_cat_mun`);
ALTER TABLE `cat_victima_atendida` ADD CONSTRAINT `cat_victima_atendida_fk5` FOREIGN KEY (`id_cat_escolaridad`) REFERENCES `cat_escolaridad` (`id_cat_escolaridad`);
ALTER TABLE `cat_victima_atendida` ADD CONSTRAINT `cat_victima_atendida_fk6` FOREIGN KEY (`id_cat_ocup`) REFERENCES `cat_ocupaciones` (`id_cat_ocup`);
ALTER TABLE `cat_victima_atendida` ADD CONSTRAINT `cat_victima_atendida_fk7` FOREIGN KEY (`id_cat_grupo_vuln`) REFERENCES `cat_grupos_vuln` (`id_cat_grupo_vuln`);
ALTER TABLE `cat_victima_atendida` ADD CONSTRAINT `cat_victima_atendida_fk8` FOREIGN KEY (`id_cat_disc`) REFERENCES `cat_discapacidades` (`id_cat_disc`);
ALTER TABLE `cat_victima_atendida` ADD CONSTRAINT `cat_victima_atendida_fk9` FOREIGN KEY (`id_cat_comun`) REFERENCES `cat_comunidades` (`id_cat_comun`);



CREATE TABLE `cat_persona_desaparecida` (
  `id_cat_persona_desaparecida` INTEGER NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(150) NOT NULL DEFAULT '""',
  `paterno` VARCHAR(150) DEFAULT '""',
  `materno` VARCHAR(150) DEFAULT '""',
  `id_cat_gen` INTEGER NOT NULL,
  `edad` INTEGER NOT NULL,
  `id_cat_nacionalidad` INTEGER NOT NULL,
  `id_cat_ent_fed` INTEGER NOT NULL,
  `id_cat_mun` INTEGER NOT NULL,
  `id_cat_escolaridad` INTEGER NOT NULL,
  `id_cat_ocup` INTEGER NOT NULL,
  `leer_escribir` VARCHAR(50) NOT NULL,
  `id_cat_grupo_vuln` INTEGER NOT NULL,
  `id_cat_disc` INTEGER NOT NULL,
  `id_cat_comun` INTEGER NOT NULL,
  `fecha_desaparicion` DATE DEFAULT NULL,
  `id_cat_ent_fed_desaparicion` INTEGER NOT NULL,
  `id_cat_mun_desaparicion` INTEGER NOT NULL,
  `localidad_desaparicion` VARCHAR(150) DEFAULT '""',
  PRIMARY KEY USING BTREE (`id_cat_persona_desaparecida`)
) ENGINE=InnoDB
AUTO_INCREMENT=1;


ALTER TABLE `cat_persona_desaparecida` ADD CONSTRAINT `cat_persona_desaparecida_fk1` FOREIGN KEY (`id_cat_gen`) REFERENCES `cat_genero` (`id_cat_gen`);
ALTER TABLE `cat_persona_desaparecida` ADD CONSTRAINT `cat_persona_desaparecida_fk2` FOREIGN KEY (`id_cat_nacionalidad`) REFERENCES `cat_nacionalidades` (`id_cat_nacionalidad`);
ALTER TABLE `cat_persona_desaparecida` ADD CONSTRAINT `cat_persona_desaparecida_fk3` FOREIGN KEY (`id_cat_ent_fed`) REFERENCES `cat_entidad_fed` (`id_cat_ent_fed`);
ALTER TABLE `cat_persona_desaparecida` ADD CONSTRAINT `cat_persona_desaparecida_fk4` FOREIGN KEY (`id_cat_mun`) REFERENCES `cat_municipios` (`id_cat_mun`);
ALTER TABLE `cat_persona_desaparecida` ADD CONSTRAINT `cat_persona_desaparecida_fk5` FOREIGN KEY (`id_cat_escolaridad`) REFERENCES `cat_escolaridad` (`id_cat_escolaridad`);
ALTER TABLE `cat_persona_desaparecida` ADD CONSTRAINT `cat_persona_desaparecida_fk6` FOREIGN KEY (`id_cat_ocup`) REFERENCES `cat_ocupaciones` (`id_cat_ocup`);
ALTER TABLE `cat_persona_desaparecida` ADD CONSTRAINT `cat_persona_desaparecida_fk7` FOREIGN KEY (`id_cat_grupo_vuln`) REFERENCES `cat_grupos_vuln` (`id_cat_grupo_vuln`);
ALTER TABLE `cat_persona_desaparecida` ADD CONSTRAINT `cat_persona_desaparecida_fk8` FOREIGN KEY (`id_cat_disc`) REFERENCES `cat_discapacidades` (`id_cat_disc`);
ALTER TABLE `cat_persona_desaparecida` ADD CONSTRAINT `cat_persona_desaparecida_fk9` FOREIGN KEY (`id_cat_comun`) REFERENCES `cat_comunidades` (`id_cat_comun`);
ALTER TABLE `cat_persona_desaparecida` ADD CONSTRAINT `cat_persona_desaparecida_fk10` FOREIGN KEY (`id_cat_ent_fed_desaparicion`) REFERENCES `cat_comunidades` (`id_cat_comun`);
ALTER TABLE `cat_persona_desaparecida` ADD CONSTRAINT `cat_persona_desaparecida_fk11` FOREIGN KEY (`id_cat_mun_desaparicion`) REFERENCES `cat_comunidades` (`id_cat_comun`);





CREATE TABLE `actividedes_ud` (
  `id_actividedes_ud` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
  `folio` VARCHAR(30) NOT NULL,
  `id_cat_tipo_actividades_ud` INTEGER NOT NULL,
  `fecha_actividad` DATE NOT NULL,
  `id_cat_victima_atendida` INTEGER NOT NULL,
  `id_cat_persona_desaparecida` INTEGER NOT NULL,
  `nombre_solicitante` VARCHAR(200) NOT NULL,
  `no_expediente` VARCHAR(50) NOT NULL DEFAULT '""',
  `motivo_solicitud` VARCHAR(250) NOT NULL DEFAULT '""',
  `acciones` TEXT NOT NULL,  
  `no_atendidos` INTEGER NOT NULL DEFAULT 0,
  `institucion_colaboracion` TEXT DEFAULT NULL,
  `motivo_colaboracion` TEXT DEFAULT NULL,
  `id_cat_ent_fed_colaboracion` INTEGER DEFAULT NULL,
  `quien_atendio` VARCHAR(150) NOT NULL,
  `observaciones` TEXT NOT NULL,
  `user_creador` INTEGER NOT NULL,
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_actividedes_ud`)
) ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

ALTER TABLE `actividedes_ud` ADD CONSTRAINT `actividedes_ud_fk1` FOREIGN KEY (`id_cat_ent_fed_colaboracion`) REFERENCES `cat_entidad_fed` (`id_cat_ent_fed`);

ALTER TABLE `colaboraciones` ADD COLUMN `fecha_actividad` DATE NOT NULL;
ALTER TABLE `colaboraciones` ADD COLUMN	`id_cat_victima_atendida` INTEGER NOT NULL,
ALTER TABLE `colaboraciones` ADD COLUMN	`id_cat_persona_desaparecida` INTEGER NOT NULL,
ALTER TABLE `colaboraciones` ADD COLUMN	`id_cat_ent_fed_colaboracion` INTEGER DEFAULT NULL,
ALTER TABLE `colaboraciones` ADD COLUMN	`no_expediente` VARCHAR(50) NOT NULL DEFAULT '""',
ALTER TABLE `colaboraciones` ADD COLUMN	`no_atendidos` INTEGER NOT NULL DEFAULT 0,
ALTER TABLE `colaboraciones` ADD COLUMN	`quien_atendio` VARCHAR(150) NOT NULL,
ALTER TABLE `colaboraciones` ADD COLUMN	`acciones` TEXT NOT NULL, 
ALTER TABLE `colaboraciones` ADD COLUMN	`institucion_colaboracion` TEXT DEFAULT NULL,  