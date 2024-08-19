<html>
<head>
<title>Descargar Expediente de Queja</title>
</head>
<body>
<?php 

/**
 * creación de archivo zip
 * con un poco de contenido, para mandarlo
 * para su descarga
 *
 */
require_once('includes/load.php');
$user = current_user();

if (isset($_GET['id'])) {
	
	$id = $_GET['id'];	
	
	$queja_acuerdos = find_by_id_queja((int)$_GET['id']);
	
	$e_detalle2 = find_by_id_acuerdo((int)$_GET['id']);
	
	$folio = str_replace("/", "-",$queja_acuerdos['folio_queja']);
		
	$carpeta_expediente = 'uploads/quejas/' . $folio.'/Acuerdos/';
	
	$zip = new ZipArchive();
	
	// Ruta del archivo
	$nombreArchivoZip = __DIR__ .'/'.$carpeta_expediente. "/".$folio.".zip";
	

	if (!$zip->open($nombreArchivoZip, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
		exit("Error abriendo ZIP en $nombreArchivoZip");
	}
	
	$generalesfolio  = "Folio: ".remove_junk(ucwords($queja_acuerdos['folio_queja']))."\n";
	$generalesfolio .= "Fecha de Presentación: ".remove_junk(ucwords($queja_acuerdos['fecha_presentacion']))."\n";
	$generalesfolio .= "Medio de Presentación: ".remove_junk(ucwords($queja_acuerdos['medio_pres']))."\n";
	$generalesfolio .= "Autoridad Responsable: ".remove_junk(ucwords($queja_acuerdos['nombre_autoridad']))."\n";
	$generalesfolio .= "Estado Procesal: ".remove_junk(ucwords($queja_acuerdos['estado_procesal']))."\n";
						
	$zip->addFromString("Generales_".$folio.".txt", $generalesfolio);
	
	//obtengo los nombres de archivos
	 foreach ($e_detalle2 as $detalle) : 
		 if($detalle['acuerdo_adjunto']!=''){
			$rutaAbsoluta = __DIR__ .'/'.$carpeta_expediente . $detalle['acuerdo_adjunto'];
			$nombre = basename($rutaAbsoluta);
			$zip->addFile($rutaAbsoluta, $nombre);
		 }
		 if($detalle['acuerdo_adjunto_publico']!=''){
			$rutaAbsoluta1 = __DIR__ .'/'.$carpeta_expediente . $detalle['acuerdo_adjunto_publico'];
			$nombre1 = basename($rutaAbsoluta1);
			$zip->addFile($rutaAbsoluta1, $nombre1);
		 }
		
		echo $carpeta_expediente . $detalle['acuerdo_adjunto'].'<br>';
		echo $carpeta_expediente . $detalle['acuerdo_adjunto_publico'].'<br>';
		
		
		
	 endforeach;
	
	

	// No olvides cerrar el archivo
	$resultado = $zip->close();
	if (!$resultado) {
		exit("Error creando archivo");
	}

	// Ahora que ya tenemos el archivo lo enviamos como respuesta
	// para su descarga

	// El nombre con el que se descarga
	$nombreAmigable = $folio.".zip";
	header('Content-Type: application/octet-stream');
	header("Content-Transfer-Encoding: Binary");
	header("Content-disposition: attachment; filename=$nombreAmigable");
	// Leer el contenido binario del zip y enviarlo
	readfile($nombreArchivoZip);
	
	insertAccion($user['id_user'],'El usuario -'.$user['username'].'- descargo el expediente de la queja '.$queja_acuerdos['folio_queja'].'.');
}
?>
</body>
</html>

