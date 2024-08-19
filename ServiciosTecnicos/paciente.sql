CREATE TABLE `paciente` (
  `id_paciente` int(11) NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `paterno` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `materno` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `genero` int(11) NOT NULL,
  `edad` int(11) NOT NULL,
  `nacionalidad` int(11) NOT NULL,
  `municipio` int(11) NOT NULL,
  `entidad` int(11) NOT NULL,
  `escolaridad` int(11) NOT NULL,
  `ocupacion` int(11) NOT NULL,
  `discapacidad` int(11) NOT NULL,
  `autoridad_responsable` int(11) NOT NULL,
  `grupo_vulnerable` int(11) NOT NULL,
  `leer_escribir` varchar(80) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `comunidad` int(11) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `folio_expediente` int(11) NOT NULL,
  `tipo_expediente` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id_paciente`),
  ADD KEY `fk_genero_pa` (`genero`),
  ADD KEY `fk_ent_pa` (`entidad`),
  ADD KEY `fk_esc_pa` (`escolaridad`),
  ADD KEY `fk_ocup_pa` (`ocupacion`),
  ADD KEY `fk_disc_pa` (`discapacidad`),
  ADD KEY `fk_gv_pa` (`grupo_vulnerable`),
  ADD KEY `fk_com_pa` (`comunidad`),
  ADD KEY `fk_mun_pa` (`municipio`),
  ADD KEY `fk_nacionalidad_pa` (`nacionalidad`),
  ADD KEY `fk_aut_pa` (`autoridad_responsable`),
  ADD KEY `fk_folio_pa` (`folio_expediente`);

ALTER TABLE `paciente`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `paciente`
  ADD CONSTRAINT `fk_aut_pa` FOREIGN KEY (`autoridad_responsable`) REFERENCES `cat_autoridades` (`id_cat_aut`),
  ADD CONSTRAINT `fk_com_pa` FOREIGN KEY (`comunidad`) REFERENCES `cat_comunidades` (`id_cat_comun`),
  ADD CONSTRAINT `fk_disc_pa` FOREIGN KEY (`discapacidad`) REFERENCES `cat_discapacidades` (`id_cat_disc`),
  ADD CONSTRAINT `fk_ent_pa` FOREIGN KEY (`entidad`) REFERENCES `cat_entidad_fed` (`id_cat_ent_fed`),
  ADD CONSTRAINT `fk_esc_pa` FOREIGN KEY (`escolaridad`) REFERENCES `cat_escolaridad` (`id_cat_escolaridad`),
  ADD CONSTRAINT `fk_folio_pa` FOREIGN KEY (`folio_expediente`) REFERENCES `folios` (`id_folio`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_genero_pa` FOREIGN KEY (`genero`) REFERENCES `cat_genero` (`id_cat_gen`),
  ADD CONSTRAINT `fk_gv_pa` FOREIGN KEY (`grupo_vulnerable`) REFERENCES `cat_grupos_vuln` (`id_cat_grupo_vuln`),
  ADD CONSTRAINT `fk_mun_pa` FOREIGN KEY (`municipio`) REFERENCES `cat_municipios` (`id_cat_mun`),
  ADD CONSTRAINT `fk_nacionalidad_pa` FOREIGN KEY (`nacionalidad`) REFERENCES `cat_nacionalidades` (`id_cat_nacionalidad`),
  ADD CONSTRAINT `fk_ocup_pa` FOREIGN KEY (`ocupacion`) REFERENCES `cat_ocupaciones` (`id_cat_ocup`);
COMMIT;