CREATE TABLE `fichas` (
  `id_ficha` INTEGER NOT NULL AUTO_INCREMENT,
  `folio` VARCHAR(40) COLLATE utf8mb3_spanish_ci NOT NULL,
  `tipo_ficha` INTEGER DEFAULT NULL,
  `id_cat_funcion` INTEGER NOT NULL,
  `id_area_solicitante` INTEGER NOT NULL,
  `id_visitaduria` INTEGER NOT NULL,
  `resultado` VARCHAR(50) COLLATE utf8mb3_spanish_ci NOT NULL,
  `documento_emitido` VARCHAR(250) COLLATE utf8mb3_spanish_ci NOT NULL,
  `clave_documento` VARCHAR(20) COLLATE latin1_swedish_ci DEFAULT NULL,
  `nombre_especialista` VARCHAR(150) COLLATE latin1_swedish_ci DEFAULT NULL,
  `ficha_adjunto` VARCHAR(250) COLLATE latin1_swedish_ci DEFAULT NULL,
  `id_paciente` VARCHAR(50) COLLATE utf8mb3_spanish_ci NOT NULL,
  `fecha_intervencion` DATE NOT NULL,
  `protocolo_estambul` VARCHAR(50) COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `fecha_creacion` DATE NOT NULL,
  `quien_creo` VARCHAR(250) COLLATE latin1_swedish_ci DEFAULT NULL,
  
  
  `id_cat_ocup` INTEGER NOT NULL,
  `id_cat_escolaridad` INTEGER NOT NULL,
  `id_cat_der_vuln` INTEGER NOT NULL,
  `id_cat_aut` INTEGER NOT NULL,
  `nombre_usuario` VARCHAR(150) COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `edad` INTEGER NOT NULL,
  `id_cat_gen` INTEGER NOT NULL,
  `id_cat_grupo_vuln` INTEGER NOT NULL,
  PRIMARY KEY USING BTREE (`id_ficha`),
  KEY `fichas_fk1` USING BTREE (`id_visitaduria`),
  KEY `fichas_fk2` USING BTREE (`id_area_solicitante`),
  KEY `fichas_fk3` USING BTREE (`id_cat_ocup`),
  KEY `fichas_fk4` USING BTREE (`id_cat_escolaridad`),
  KEY `fichas_fk5` USING BTREE (`id_cat_der_vuln`),
  KEY `fichas_fk6` USING BTREE (`id_cat_aut`),
  KEY `fichas_fk7` USING BTREE (`id_cat_gen`),
  KEY `fichas_fk8` USING BTREE (`id_cat_grupo_vuln`),
  KEY `fichas_fk9` USING BTREE (`id_cat_funcion`),
  CONSTRAINT `fichas_fk1` FOREIGN KEY (`id_visitaduria`) REFERENCES `area` (`id_area`),
  CONSTRAINT `fichas_fk2` FOREIGN KEY (`id_area_solicitante`) REFERENCES `area` (`id_area`),
  CONSTRAINT `fichas_fk3` FOREIGN KEY (`id_cat_ocup`) REFERENCES `cat_ocupaciones` (`id_cat_ocup`),
  CONSTRAINT `fichas_fk4` FOREIGN KEY (`id_cat_escolaridad`) REFERENCES `cat_escolaridad` (`id_cat_escolaridad`),
  CONSTRAINT `fichas_fk5` FOREIGN KEY (`id_cat_der_vuln`) REFERENCES `cat_der_vuln` (`id_cat_der_vuln`),
  CONSTRAINT `fichas_fk6` FOREIGN KEY (`id_cat_aut`) REFERENCES `cat_autoridades` (`id_cat_aut`),
  CONSTRAINT `fichas_fk7` FOREIGN KEY (`id_cat_gen`) REFERENCES `cat_genero` (`id_cat_gen`),
  CONSTRAINT `fichas_fk8` FOREIGN KEY (`id_cat_grupo_vuln`) REFERENCES `cat_grupos_vuln` (`id_cat_grupo_vuln`),
  CONSTRAINT `fichas_fk9` FOREIGN KEY (`id_cat_funcion`) REFERENCES `cat_funcion` (`id_cat_funcion`)
) ENGINE=InnoDB
AUTO_INCREMENT=365 ROW_FORMAT=DYNAMIC CHARACTER SET 'latin1' COLLATE 'latin1_swedish_ci';


ALTER TABLE `fichas` CHANGE COLUMN `num_queja` `id_paciente` INTEGER NOT NULL;
ALTER TABLE `fichas` CHANGE COLUMN `quien_creo` `user_creador` INTEGER NOT NULL;

ALTER TABLE `fichas` DROP FOREIGN KEY `fichas_fk3`;
ALTER TABLE `fichas` DROP FOREIGN KEY `fichas_fk4`;
ALTER TABLE `fichas` DROP FOREIGN KEY `fichas_fk5`;
ALTER TABLE `fichas` DROP FOREIGN KEY `fichas_fk6`;
ALTER TABLE `fichas` DROP FOREIGN KEY `fichas_fk7`;

ALTER TABLE `fichas` DROP COLUMN `id_cat_ocup`;
ALTER TABLE `fichas` DROP COLUMN `id_cat_escolaridad`;
ALTER TABLE `fichas` DROP COLUMN `id_cat_der_vuln`;
ALTER TABLE `fichas` DROP COLUMN `nombre_usuario`;
ALTER TABLE `fichas` DROP COLUMN `edad`;
ALTER TABLE `fichas` DROP COLUMN `id_cat_gen`;
ALTER TABLE `fichas` DROP COLUMN `id_cat_grupo_vuln`;



ALTER TABLE `paciente` ENGINE=InnoDB, AUTO_INCREMENT=1;
ALTER TABLE `fichas` ENGINE=InnoDB, AUTO_INCREMENT=1;
ALTER TABLE `sesiones` ENGINE=InnoDB, AUTO_INCREMENT=1;