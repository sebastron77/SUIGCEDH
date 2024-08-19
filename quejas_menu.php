<?php
require_once('includes/load.php');
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

?>
<ul>
  <li style="margin-bottom: 18px;">
    <a href="#" class="submenu-toggle" style="left: 18px; top:20px">
      <svg style="width:22px;height:22px" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-check" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
        <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z" />
        <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z" />
      </svg>
      <!--<svg style="width:22px;height:22px" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>note</title><path d="M14,10V4.5L19.5,10M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V9L15,3H5Z" /></svg>  -->
      <span style="position: absolute; top: 50%; left: 50%; margin:-11px 0 0 -85px;">Solicitudes</span>
    </a>
    <ul style="margin-top:25px" class="nav submenu">
      <li><a href="quejas.php">Quejas</a> </li>
      <li><a href="orientaciones.php">Orientaciones</a> </li>
      <li><a href="canalizaciones.php">Canalizaciones</a> </li>
    </ul>
  </li>
  <li style="margin-bottom: 18px;">
    <a href="#" class="submenu-toggle" style="left: 18px; top:20px">
      <svg style="width:22px;height:22px" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-diagram-3" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H14a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 2 7h5.5V6A1.5 1.5 0 0 1 6 4.5v-1zM8.5 5a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1zM0 11.5A1.5 1.5 0 0 1 1.5 10h1A1.5 1.5 0 0 1 4 11.5v1A1.5 1.5 0 0 1 2.5 14h-1A1.5 1.5 0 0 1 0 12.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zm4.5.5A1.5 1.5 0 0 1 7.5 10h1a1.5 1.5 0 0 1 1.5 1.5v1A1.5 1.5 0 0 1 8.5 14h-1A1.5 1.5 0 0 1 6 12.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zm4.5.5a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1a1.5 1.5 0 0 1-1.5-1.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1z" />
      </svg>
      <span style="position: absolute; top: 50%; left: 50%; margin:-11px 0 0 -85px;">Procesos por Área</span>
    </a>
    <ul style="margin-top:25px" class="nav submenu">
      <li><a href="solicitudes.php">Ver Áreas </a> </li>
    </ul>
  </li>
  <?php if ($id_user == 46) : ?>
    <li style="margin-top: 18px;">
      <a href="#" class="submenu-toggle" style="left: 18px; top:20px">
        <svg style="width:22px;height:22px" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-layout-wtf" viewBox="0 0 16 16">
          <path d="M5 1v8H1V1h4zM1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H1zm13 2v5H9V2h5zM9 1a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9zM5 13v2H3v-2h2zm-2-1a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1H3zm12-1v2H9v-2h6zm-6-1a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1H9z" />
        </svg>
        <span style="position: absolute; top: 50%; left: 50%; margin:-11px 0 0 -85px;">Gestión de catálogos</span>
      </a>
      <ul style="margin-top:25px" class="nav submenu">
        <li><a href="autoridades.php">Autoridades Responsables</a> </li>
        <li><a href="cat_comunidad.php">Comunidad Indígena</a> </li>
        <li><a href="cat_dicapacidades.php">Discapacidades</a> </li>
        <li><a href="cat_derechos_vulnerados.php">Derechos Vulnerados</a> </li>
        <li><a href="cat_escolaridad.php">Escolaridad</a> </li>
        <li><a href="cat_genero.php">Género</a> </li>
        <li><a href="cat_grupo_vulnerable.php">Grupo Vulnerable</a> </li>
        <li><a href="cat_ocupaciones.php">Ocupaciones</a> </li>
        <li><a href="cat_tipo_resolucion.php">Tipo Resolución</a> </li>
        <li><a href="cat_edo_procesal.php">Estado Procesal</a> </li>
        <li><a href="cat_medio_presentacion.php">Medio de Presentación</a> </li>
      </ul>
    </li>
  <?php endif; ?>

  <li style="margin-top: 18px;">
    <a href="#" class="submenu-toggle" style="left: 18px; top:20px">
      <svg style="width:22px;height:22px" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-graph-down" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0Zm14.817 11.887a.5.5 0 0 0 .07-.704l-4.5-5.5a.5.5 0 0 0-.74-.037L7.06 8.233 3.404 3.206a.5.5 0 0 0-.808.588l4 5.5a.5.5 0 0 0 .758.06l2.609-2.61 4.15 5.073a.5.5 0 0 0 .704.07Z" />
      </svg>

      <span style="position: absolute; top: 50%; left: 50%; margin:-11px 0 0 -85px;">Estadísticas</span>
    </a>
    <ul style="margin-top:25px" class="nav submenu">
      <li><a href="tabla_estadistica_quejas.php">Quejas</a></li>
      <li><a href="tabla_estadistica_orientacion.php">Orientaciones</a></li>
      <li><a href="tabla_estadistica_canalizacion.php">Canalizaciones</a></li>
    </ul>

    <?php if ($id_user == 46 || $id_user == 3) : ?>
  <li style="margin-top: 18px;">
    <a href="#" class="submenu-toggle" style="left: 18px; top:20px">
      <svg style="width:22px;height:22px" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-pulse" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M10 1.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1Zm-5 0A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5v1A1.5 1.5 0 0 1 9.5 4h-3A1.5 1.5 0 0 1 5 2.5v-1Zm-2 0h1v1H3a1 1 0 0 0-1 1V14a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V3.5a1 1 0 0 0-1-1h-1v-1h1a2 2 0 0 1 2 2V14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V3.5a2 2 0 0 1 2-2Zm6.979 3.856a.5.5 0 0 0-.968.04L7.92 10.49l-.94-3.135a.5.5 0 0 0-.895-.133L4.232 10H3.5a.5.5 0 0 0 0 1h1a.5.5 0 0 0 .416-.223l1.41-2.115 1.195 3.982a.5.5 0 0 0 .968-.04L9.58 7.51l.94 3.135A.5.5 0 0 0 11 11h1.5a.5.5 0 0 0 0-1h-1.128L9.979 5.356Z" />
      </svg>

      <span style="position: absolute; top: 50%; left: 50%; margin:-11px 0 0 -85px;">Reporteador</span>
    </a>
    <ul style="margin-top:25px" class="nav submenu">
      <li><a href="reporteador_general.php">General</a></li>
    </ul>
  </li>
<?php endif; ?>
</li>
</ul>