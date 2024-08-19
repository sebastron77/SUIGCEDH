CREATE TABLE `fichas` (
  `id_ficha` int(11) NOT NULL,
  `folio` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_cat_funcion` int(11) NOT NULL,
  `num_queja` int(11) NOT NULL,
  `id_visitaduria` int(11) NOT NULL,
  `id_area_solicitante` int(11) NOT NULL,
  `id_cat_der_vuln` int(11) NOT NULL,
  `fecha_intervencion` date NOT NULL,
  `resultado` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `documento_emitido` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `ficha_adjunto` varchar(250) DEFAULT NULL,
  `fecha_creacion` date NOT NULL,
  `protocolo_estambul` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `tipo_ficha` int(11) DEFAULT NULL,
  `nombre_especialista` varchar(150) DEFAULT NULL,
  `clave_documento` varchar(20) DEFAULT NULL,
  `quien_creo` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

ALTER TABLE `fichas`
  ADD PRIMARY KEY (`id_ficha`) USING BTREE,
  ADD KEY `fichas_fk1` (`id_visitaduria`) USING BTREE,
  ADD KEY `fichas_fk2` (`id_area_solicitante`) USING BTREE,
  ADD KEY `fichas_fk5` (`id_cat_der_vuln`) USING BTREE,
  ADD KEY `fichas_fk9` (`id_cat_funcion`) USING BTREE,
  ADD KEY `fichas_fk4` (`num_queja`);

ALTER TABLE `fichas`
  MODIFY `id_ficha` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `fichas`
  ADD CONSTRAINT `fichas_fk1` FOREIGN KEY (`id_visitaduria`) REFERENCES `area` (`id_area`),
  ADD CONSTRAINT `fichas_fk2` FOREIGN KEY (`id_area_solicitante`) REFERENCES `area` (`id_area`),
  ADD CONSTRAINT `fichas_fk4` FOREIGN KEY (`num_queja`) REFERENCES `paciente` (`folio_expediente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fichas_fk5` FOREIGN KEY (`id_cat_der_vuln`) REFERENCES `cat_der_vuln` (`id_cat_der_vuln`),
  ADD CONSTRAINT `fichas_fk9` FOREIGN KEY (`id_cat_funcion`) REFERENCES `cat_funcion` (`id_cat_funcion`);
COMMIT;