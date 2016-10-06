<!DOCTYPE html>
<html>
<body>


<?php
session_start(); // start session 
                 // 
if (isset($_REQUEST["submit2"])) {
	$final = ($_REQUEST["grados"]=="Doble Grado en Ingeniería Informática y Matemáticas")?23:22;
	print "Estimad".$_SESSION["sufijo"]." ".$_SESSION["tratamiento"].$_SESSION["nombre"].". Le informamos que la edad media de finalización de estos estudios es ".$final." años. Puede terminar por tanto en ".($final-$_SESSION["edad"])." años.";
}  else if (isset($_REQUEST["submit1"])) {
	if (!isset($_REQUEST["eps"])) {
		echo "Por favor, ".$_SESSION["tratamiento"].$_REQUEST["nombre"]." debe acceder a la página Web de la Universidad para seguir con sus gestiones";
	} else {
		$_SESSION["nombre"] =  $_REQUEST["nombre"];
		$_SESSION["edad"] =  $_REQUEST["edad"];
		$_SESSION["genero"] =  $_REQUEST["genero"];
		$_SESSION["tratamiento"] = $_SESSION["genero"] == "Hombre"?"Sr. ": "Srta. ";
		$_SESSION["sufijo"] = $_SESSION["genero"] == "Hombre"?"o": "a";
		echo "Bienvenid".$_SESSION["sufijo"]." ".$_SESSION["tratamiento"].$_REQUEST["nombre"].", Seleccione del siguiente listado el 		nombre de la titulación que esta cursando";		
		echo "<form action=\"form1.php\" method=\"POST\"	> ";	
		echo "<datalist id=\"grados\">";	
		echo "<option value=\"Grado en Ingeniería Informática\">";	
		echo "<option value=\"Grado en Ingeniería de Telecomunicaciones\">";	
		echo "<option value=\"Doble Grado en Ingeniería Informática y Matemáticas\">";	
		echo "</datalist>";	
		echo "Nombre de la titulación: <input list=\"grados\" name=\"grados\"> ";
		echo "<input type=\"submit\" name=\"submit2\" value=\"Enviar\">";
	}
} else {
	echo "Error: no se ha rellenado el formulario o la redirección es incorrecta.";
}
?>


</body>
</html>