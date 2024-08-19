CREATE TABLE `cat_tipo_actividades_ud` (
  `id_cat_tipo_actividades_ud` INTEGER NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(250) DEFAULT NULL,
  `estatus` TINYINT NOT NULL,
  `color_estadistica` VARCHAR(10) DEFAULT NULL,
  PRIMARY KEY ADD CONSTRAINT (`id_cat_tipo_actividades_ud`)
) ENGINE=InnoDB
AUTO_INCREMENT=1 ;



CREATE TABLE `actividades_ud` (
  `id_actividades_ud` INTEGER NOT NULL AUTO_INCREMENT,
  `folio` VARCHAR(30) DEFAULT NULL,
  `id_cat_tipo_actividades_ud` INTEGER NOT NULL,
  `fecha_actividad` DATE NOT NULL,
  `id_cat_victima_atendida` INTEGER NOT NULL,
  `id_cat_persona_desaparecida` INTEGER NOT NULL,
  `nombre_solicitante` VARCHAR(200) NOT NULL,
  `no_expediente` VARCHAR(50) NOT NULL DEFAULT '""',
  `motivo_solicitud` VARCHAR(250) NOT NULL DEFAULT '""',
  `no_atendidos` INTEGER NOT NULL DEFAULT 0,
  `institucion_colaboracion` TEXT DEFAULT NULL,
  `motivo_colaboracion` TEXT DEFAULT NULL,
  `id_cat_ent_fed_colaboracion` INTEGER DEFAULT NULL,
  `quien_atendio` VARCHAR(150) NOT NULL,
  `observaciones` TEXT NOT NULL,
  `user_creador` INTEGER NOT NULL,
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `acciones` TEXT NOT NULL,
  PRIMARY KEY ADD CONSTRAINT (`id_actividades_ud`)
) ENGINE=InnoDB;


ALTER TABLE `actividades_ud`  MODIFY `id_actividades_ud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;





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
  PRIMARY KEY ADD CONSTRAINT (`id_cat_persona_desaparecida`)
) ENGINE=InnoDB;

ALTER TABLE `cat_persona_desaparecida`  MODIFY `id_cat_persona_desaparecida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;





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
  `id_cat_comun` INTEGER NOT NULL,
  PRIMARY KEY ADD CONSTRAINT (`id_cat_victima_atendida`)
) ENGINE=InnoDB;

ALTER TABLE `cat_victima_atendida`  MODIFY `id_cat_victima_atendida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;


  
ALTER TABLE `actividades_ud`
  ADD CONSTRAINT `actividades_fk1` FOREIGN KEY (`id_cat_ent_fed_colaboracion`) REFERENCES `cat_entidad_fed` (`id_cat_ent_fed`),
  ADD CONSTRAINT `actividades_fk2` FOREIGN KEY (`id_cat_tipo_actividades_ud`) REFERENCES `cat_tipo_actividades_ud` (`id_cat_tipo_actividades_ud`),
  ADD CONSTRAINT `actividades_fk3` FOREIGN KEY (`id_cat_victima_atendida`) REFERENCES `cat_victima_atendida` (`id_cat_victima_atendida`) ,
  ADD CONSTRAINT `actividades_fk4` FOREIGN KEY (`id_cat_persona_desaparecida`) REFERENCES `cat_persona_desaparecida` (`id_cat_persona_desaparecida`);
COMMIT;



ALTER TABLE `cat_persona_desaparecida`
  ADD CONSTRAINT `cat_persona_desaparecida_fk1` FOREIGN KEY (`id_cat_gen`) REFERENCES `cat_genero` (`id_cat_gen`),
  ADD CONSTRAINT `cat_persona_desaparecida_fk2` FOREIGN KEY (`id_cat_nacionalidad`) REFERENCES `cat_nacionalidades` (`id_cat_nacionalidad`),
  ADD CONSTRAINT `cat_persona_desaparecida_fk3` FOREIGN KEY (`id_cat_ent_fed`) REFERENCES `cat_entidad_fed` (`id_cat_ent_fed`),
  ADD CONSTRAINT `cat_persona_desaparecida_fk4` FOREIGN KEY (`id_cat_mun`) REFERENCES `cat_municipios` (`id_cat_mun`),
  ADD CONSTRAINT `cat_persona_desaparecida_fk5` FOREIGN KEY (`id_cat_escolaridad`) REFERENCES `cat_escolaridad` (`id_cat_escolaridad`),
  ADD CONSTRAINT `cat_persona_desaparecida_fk6` FOREIGN KEY (`id_cat_ocup`) REFERENCES `cat_ocupaciones` (`id_cat_ocup`),
  ADD CONSTRAINT `cat_persona_desaparecida_fk7` FOREIGN KEY (`id_cat_grupo_vuln`) REFERENCES `cat_grupos_vuln` (`id_cat_grupo_vuln`),
  ADD CONSTRAINT `cat_persona_desaparecida_fk8` FOREIGN KEY (`id_cat_disc`) REFERENCES `cat_discapacidades` (`id_cat_disc`),
  ADD CONSTRAINT `cat_persona_desaparecida_fk9` FOREIGN KEY (`id_cat_comun`) REFERENCES `cat_comunidades` (`id_cat_comun`),
  ADD CONSTRAINT `cat_persona_desaparecida_fk10` FOREIGN KEY (`id_cat_ent_fed_desaparicion`) REFERENCES `cat_entidad_fed` (`id_cat_ent_fed`),
  ADD CONSTRAINT `cat_persona_desaparecida_fk11` FOREIGN KEY (`id_cat_mun_desaparicion`) REFERENCES `cat_municipios` (`id_cat_mun`);
COMMIT;






ALTER TABLE `cat_victima_atendida`
  ADD CONSTRAINT `cat_victima_atendida_fk1` FOREIGN KEY (`id_cat_gen`) REFERENCES `cat_genero` (`id_cat_gen`),
  ADD CONSTRAINT `cat_victima_atendida_fk2` FOREIGN KEY (`id_cat_nacionalidad`) REFERENCES `cat_nacionalidades` (`id_cat_nacionalidad`),
  ADD CONSTRAINT `cat_victima_atendida_fk3` FOREIGN KEY (`id_cat_ent_fed`) REFERENCES `cat_entidad_fed` (`id_cat_ent_fed`),
  ADD CONSTRAINT `cat_victima_atendida_fk4` FOREIGN KEY (`id_cat_mun`) REFERENCES `cat_municipios` (`id_cat_mun`),
  ADD CONSTRAINT `cat_victima_atendida_fk5` FOREIGN KEY (`id_cat_escolaridad`) REFERENCES `cat_escolaridad` (`id_cat_escolaridad`),
  ADD CONSTRAINT `cat_victima_atendida_fk6` FOREIGN KEY (`id_cat_ocup`) REFERENCES `cat_ocupaciones` (`id_cat_ocup`),
  ADD CONSTRAINT `cat_victima_atendida_fk7` FOREIGN KEY (`id_cat_grupo_vuln`) REFERENCES `cat_grupos_vuln` (`id_cat_grupo_vuln`),
  ADD CONSTRAINT `cat_victima_atendida_fk8` FOREIGN KEY (`id_cat_disc`) REFERENCES `cat_discapacidades` (`id_cat_disc`),
  ADD CONSTRAINT `cat_victima_atendida_fk9` FOREIGN KEY (`id_cat_comun`) REFERENCES `cat_comunidades` (`id_cat_comun`);
COMMIT;  