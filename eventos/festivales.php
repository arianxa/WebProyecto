<?php
require("../database/datos.php");
#Elimina los warning manteniendo el resto de errores
#error_reporting(E_ALL & ~E_WARNING);
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>
<h3>Festivales:</h3>
<label for='nombre_festival'>Festival:</label>
<input type='text' name='nombre_festival' placeholder='Indica nombre festival'><br/>
<label for='ini_festival' required>Fecha comienzo:</label>
<input type='date' name='ini_festival'>
<label for='fin_festival'>Fecha fin:</label>
<input type='date' name='fin_festival' required><br/>
<label for='url_festival'>Web:</label>
<input type='url' name='url_festival' placeholder='URL festival'><br/>
<input type='file' name='imagen_festival'acept='image/*'><br/>
<label for='info_festival'>Otra información:</label><br/>
<textarea name='info_festival' id='info_festival' cols='30' rows='3'></textarea><br/>
<input type='submit' name='enviar' value='Alta festival'>
</form>";
$con = mysqli_connect($host, $user, $pass, $db_name) or die("Error " . mysqli_error($con));
$query = "INSERT INTO festivales (nombre,fecha_inicio,fecha_fin, web, imagen, otra_info) 
VALUES ('".$_POST['nombre_festival']."','" . $_POST['ini_festival'] . "', '" . $_POST['fin_festival'] . "', '" . $_POST['url_festival'] . "', '" . $_POST['imagen_festival'] . "', '" . $_POST['info_festival'] . "')";
mysqli_query($con, $query) or die("Error " . mysqli_error($con));
if (isset($_POST['enviar'])){
    echo"<script>alert('El festival ha sido dado de alta')</script>";
    header("refresh:3; url=eventos.html");
}