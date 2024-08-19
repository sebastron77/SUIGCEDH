CREATE TABLE `detalles_usuario` (
  `id_det_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `sexo` varchar(1) NOT NULL,
  `correo` varchar(45) NOT NULL,
  `curp` varchar(25) DEFAULT NULL,
  `rfc` varchar(12) DEFAULT NULL,
  `calle_num` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `colonia` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `municipio` int(11) DEFAULT NULL,
  `estado` varchar(80) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefono` varchar(10) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `acta_nacimiento` varchar(100) DEFAULT NULL,
  `carta_no_ant` varchar(100) DEFAULT NULL,
  `const_no_in` varchar(100) DEFAULT NULL,
  `comp_dom` varchar(100) DEFAULT NULL,
  `carta_rec1` varchar(100) DEFAULT NULL,
  `carta_rec2` varchar(100) DEFAULT NULL,
  `puesto` varchar(100) DEFAULT NULL,
  `area_adscripcion` int(11) DEFAULT NULL,
  `monto_bruto` varchar(20) DEFAULT NULL,
  `monto_neto` varchar(20) DEFAULT NULL,
  `tipo_inte` int(11) DEFAULT NULL,
  `clave` varchar(100) DEFAULT NULL,
  `niv_puesto` varchar(100) DEFAULT NULL,
  `id_cargo` int(11) NOT NULL,
  `estatus_detalle` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `detalles_usuario`
  ADD PRIMARY KEY (`id_det_usuario`),
  ADD KEY `fk_cargo_idx` (`id_cargo`),
  ADD KEY `fk_muni` (`municipio`),
  ADD KEY `fk_area_det` (`area_adscripcion`),
  ADD KEY `fk_tipo_inte` (`tipo_inte`);

ALTER TABLE `detalles_usuario`
  MODIFY `id_det_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `detalles_usuario`
  ADD CONSTRAINT `fk_area_det` FOREIGN KEY (`area_adscripcion`) REFERENCES `area` (`id_area`),
  ADD CONSTRAINT `fk_cargo` FOREIGN KEY (`id_cargo`) REFERENCES `cargos` (`id_cargos`),
  ADD CONSTRAINT `fk_muni` FOREIGN KEY (`municipio`) REFERENCES `cat_municipios` (`id_cat_mun`),
  ADD CONSTRAINT `fk_tipo_inte` FOREIGN KEY (`tipo_inte`) REFERENCES `cat_tipo_integrante` (`id_tipo_integrante`);
COMMIT;



ALTER TABLE `detalles_usuario` ADD COLUMN	`curp` varchar(25) DEFAULT NULL;
ALTER TABLE `detalles_usuario` ADD COLUMN	  `rfc` varchar(12) DEFAULT NULL;
ALTER TABLE `detalles_usuario` ADD COLUMN	  `calle_num` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL;
ALTER TABLE `detalles_usuario` ADD COLUMN	  `colonia` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL;
ALTER TABLE `detalles_usuario` ADD COLUMN	  `municipio` int(11) DEFAULT NULL;
ALTER TABLE `detalles_usuario` ADD COLUMN	  `estado` varchar(80) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL;
ALTER TABLE `detalles_usuario` ADD COLUMN	  `telefono` varchar(10) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL;
ALTER TABLE `detalles_usuario` ADD COLUMN	  `acta_nacimiento` varchar(100) DEFAULT NULL;
ALTER TABLE `detalles_usuario` ADD COLUMN	  `carta_no_ant` varchar(100) DEFAULT NULL;
ALTER TABLE `detalles_usuario` ADD COLUMN	  `const_no_in` varchar(100) DEFAULT NULL;
ALTER TABLE `detalles_usuario` ADD COLUMN	  `comp_dom` varchar(100) DEFAULT NULL;
ALTER TABLE `detalles_usuario` ADD COLUMN	  `carta_rec1` varchar(100) DEFAULT NULL;
ALTER TABLE `detalles_usuario` ADD COLUMN	  `carta_rec2` varchar(100) DEFAULT NULL;
ALTER TABLE `detalles_usuario` ADD COLUMN	  `puesto` varchar(100) DEFAULT NULL;
ALTER TABLE `detalles_usuario` ADD COLUMN	  `area_adscripcion` int(11) DEFAULT NULL;
ALTER TABLE `detalles_usuario` ADD COLUMN	  `monto_bruto` varchar(20) DEFAULT NULL;
ALTER TABLE `detalles_usuario` ADD COLUMN	  `monto_neto` varchar(20) DEFAULT NULL;
ALTER TABLE `detalles_usuario` ADD COLUMN	  `tipo_inte` int(11) DEFAULT NULL;
ALTER TABLE `detalles_usuario` ADD COLUMN	  `clave` varchar(100) DEFAULT NULL;
ALTER TABLE `detalles_usuario` ADD COLUMN	  `niv_puesto` varchar(100) DEFAULT NULL;



ALTER TABLE `detalles_usuario`
  ADD CONSTRAINT `fk_area_det` FOREIGN KEY (`area_adscripcion`) REFERENCES `area` (`id_area`),
  ADD CONSTRAINT `fk_muni` FOREIGN KEY (`municipio`) REFERENCES `cat_municipios` (`id_cat_mun`),
  ADD CONSTRAINT `fk_tipo_inte` FOREIGN KEY (`tipo_inte`) REFERENCES `cat_tipo_integrante` (`id_tipo_integrante`);