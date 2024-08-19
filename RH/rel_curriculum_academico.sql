CREATE TABLE `rel_curriculum_academico` (
  `id_rel_cur_acad` int(11) NOT NULL,
  `id_rel_detalle_usuario` int(11) NOT NULL,
  `estudios` varchar(100) NOT NULL,
  `institucion` varchar(250) NOT NULL,
  `grado` int(11) NOT NULL,
  `cedula_profesional` varchar(250) NOT NULL,
  `archivo_comprobatorio` varchar(100) DEFAULT NULL,
  `observaciones` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `rel_curriculum_academico`
  ADD PRIMARY KEY (`id_rel_cur_acad`),
  ADD KEY `fk_detalle_curriculum` (`id_rel_detalle_usuario`),
  ADD KEY `fk_grado` (`grado`);

ALTER TABLE `rel_curriculum_academico`
  MODIFY `id_rel_cur_acad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `rel_curriculum_academico`
  ADD CONSTRAINT `fk_detalle_curriculum` FOREIGN KEY (`id_rel_detalle_usuario`) REFERENCES `detalles_usuario` (`id_det_usuario`),
  ADD CONSTRAINT `fk_grado` FOREIGN KEY (`grado`) REFERENCES `cat_escolaridad` (`id_cat_escolaridad`);
COMMIT;