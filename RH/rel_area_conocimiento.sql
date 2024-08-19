CREATE TABLE `rel_area_conocimiento` (
  `id_rel_area_con` int(11) NOT NULL,
  `id_detalle_usuario` int(11) NOT NULL,
  `tipo_area` int(11) NOT NULL,
  `nombre_carrera` varchar(150) NOT NULL,
  `especialidad` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `rel_area_conocimiento`
  ADD PRIMARY KEY (`id_rel_area_con`),
  ADD KEY `rel_det_area_con` (`id_detalle_usuario`),
  ADD KEY `rel_tipo_area` (`tipo_area`);

ALTER TABLE `rel_area_conocimiento`
  MODIFY `id_rel_area_con` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `rel_area_conocimiento`
  ADD CONSTRAINT `rel_det_area_con` FOREIGN KEY (`id_detalle_usuario`) REFERENCES `detalles_usuario` (`id_det_usuario`),
  ADD CONSTRAINT `rel_tipo_area` FOREIGN KEY (`tipo_area`) REFERENCES `cat_area_conocimiento` (`id_cat_area_con`);
COMMIT;