<?php
//Sintaxis de conexión de la base de datos de muestra para PHP y MySQL.

//Conectar a la base de datos

$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "servidor_libro";

$conn = new mysqli($hostname, $username, $password, $dbname);
if ($conn->connect_error) {
	die("ERROR: No se puede conectar al servidor: " . $mysqli_connect->connect_error);
} else {
?>
	<label for="area_hija3" style="color: black;">Selecciona Subárea</label>
	<select class="form-control" id="area_hija3" name="area_hija3" style="width: 30%;" onchange="this.form.action()">
		<option value='0'>Selecciona subárea</option>
		<?php
		$result = $conn->query("select id,nombre_area from area where area_padre= " . $_POST["id"] . " AND visible= true;");
		while ($row = $result->fetch_assoc()) {
		?>
			<option value="<?php echo $row["id"]; ?>"><?php echo ucwords($row["nombre_area"]); ?></option>
		<?php
		}
		?>
	</select>
<?php




}
$result->close();

$conn->close();
?>