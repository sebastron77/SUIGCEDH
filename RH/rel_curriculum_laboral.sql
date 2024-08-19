CREATE TABLE `rel_curriculum_laboral` (
  `id_rel_cur_lab` int(11) NOT NULL,
  `id_detalle_usuario` int(11) NOT NULL,
  `puesto` varchar(150) NOT NULL,
  `institucion` varchar(150) NOT NULL,
  `inicio` date NOT NULL,
  `conclusion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `rel_curriculum_laboral`
  ADD PRIMARY KEY (`id_rel_cur_lab`),
  ADD KEY `fk_det_lab` (`id_detalle_usuario`);

ALTER TABLE `rel_curriculum_laboral`
  MODIFY `id_rel_cur_lab` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `rel_curriculum_laboral`
  ADD CONSTRAINT `fk_det_lab` FOREIGN KEY (`id_detalle_usuario`) REFERENCES `detalles_usuario` (`id_det_usuario`);
COMMIT;