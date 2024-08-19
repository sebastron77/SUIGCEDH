<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}
</script>
<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING); mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);?>
<?php
//Sintaxis de conexión de la base de datos de muestra para PHP y MySQL.

//Conectar a la base de datos

  $hostname = "localhost";
    $username = "suigcedh";
	$password = "9DvkVuZ915H!";
    $dbname = "suigcedh";

$conn = new mysqli($hostname, $username, $password, $dbname);
if ($conn->connect_error) {
    die("ERROR: No se puede conectar al servidor: " . $mysqli_connect->connect_error);
} else {
?>
    <!-- <label for="area_hija" style="color: black;">Selecciona Subárea</label> -->

<?php
    
    $currentYear = date('y');
    $currentMes = date('m');
    $fecha_alta = $_POST["fecha"];
	
	$id_area =$_POST["id"]; //area padre
	
	
	$query="SELECT  DISTINCT  a.tipo_acuerdo 
FROM rel_queja_acuerdos  a
LEFT JOIN `quejas_dates` b USING(id_queja_date)
LEFT JOIN `area` c  ON c.`id_area` =b.id_area_asignada
WHERE a.tipo_acuerdo NOT LIKe '' AND b.id_area_asignada = " . $id_area . "  AND a.fecha_acuerdo = '" . $fecha_alta . "' ;";

	$result = $conn->query($query);
		if ($row = $result->fetch_assoc()){
			do{
				?>
		<div class="accordion"><?php echo $row['tipo_acuerdo'];?> </div>
		<div class="panel">
		  <ul>
		  <?php
		   $query="SELECT 
a.`id_rel_queja_acuerdos`,
	b.`folio_queja`,
	a.`acuerdo_adjunto_publico`,
	a.`tipo_acuerdo`
FROM rel_queja_acuerdos  a
LEFT JOIN `quejas_dates` b USING(id_queja_date)
LEFT JOIN `area` c  ON c.`id_area` =b.id_area_asignada
					WHERE a.fecha_acuerdo= '".$fecha_alta."' AND a.tipo_acuerdo ='".$row['tipo_acuerdo']."' AND b.id_area_asignada = " . $id_area ." ;";
					
			$result2 = $conn->query($query);
			while ($row2 = $result2->fetch_assoc()){
				$folio_editar = $row2['folio_queja'];
				$resultado = str_replace("/", "-", $folio_editar);
				$acuerdo = $row2['acuerdo_adjunto_publico'];
				?>
				<li class="datos_exp" id="resultado2">
				<a onclick="javascript:window.open('./ver_sintesis.php?id=<?php echo ($row2['id_rel_queja_acuerdos']);?>','popup','width=500,height=600');" href="#">
					<?php echo"" . $row2['folio_queja']."" ;?>	
				</a>					
				</li>
				</br>
				<?php
			}		
		  ?>
		  </ul>
		</div>
		<hr>
	<?php
			}while ($row = $result->fetch_assoc());
		}else{
			?>
<p class="alert_warning">Lo sentimos, actualmente no hay documentos recientes de esta área.</p>
			<?php
		}	
   
  }
  $conn->close();
?>
            
