CREATE TABLE `cat_tipo_integrante` (
  `id_tipo_integrante` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `estatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cat_tipo_integrante`
--

INSERT INTO `cat_tipo_integrante` ( `descripcion`, `estatus`) VALUES
( 'Funcionario', 1),
( 'Servidor(a) Público(a)', 1),
( 'Servidor(a) Público(a) Eventual', 1),
( 'Personal de Confianza', 1),
( 'Prestador de Servicios Personales', 1),
( 'Otro', 1);

ALTER TABLE `cat_tipo_integrante`
  ADD PRIMARY KEY (`id_tipo_integrante`);

ALTER TABLE `cat_tipo_integrante`
  MODIFY `id_tipo_integrante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;
