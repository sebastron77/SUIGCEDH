CREATE TABLE `cat_area_conocimiento` (
  `id_cat_area_con` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `estatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `cat_area_conocimiento`
  ADD PRIMARY KEY (`id_cat_area_con`);

ALTER TABLE `cat_area_conocimiento`
  MODIFY `id_cat_area_con` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;