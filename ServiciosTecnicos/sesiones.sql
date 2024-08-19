CREATE TABLE `sesiones` (
  `id_sesion` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `folio` varchar(80) NOT NULL,
  `fecha_atencion` date NOT NULL,
  `estatus` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nota_sesion` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `atendio` varchar(200) NOT NULL,
  `fecha_creacion` date NOT NULL,
  `usuario_creador` int(11) NOT NULL,
  `no_sesion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `sesiones`
  ADD PRIMARY KEY (`id_sesion`),
  ADD KEY `fk_paciente` (`id_paciente`),
  ADD KEY `fk_creador` (`usuario_creador`);

ALTER TABLE `sesiones`
  MODIFY `id_sesion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `sesiones`
  ADD CONSTRAINT `fk_creador` FOREIGN KEY (`usuario_creador`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `fk_paciente` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id_paciente`);
COMMIT;