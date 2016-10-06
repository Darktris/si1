<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="theme.css">
</head>
<body>


<?php
session_start(); // start session
if (isset($_REQUEST["submit2"])) {
    $final = ($_REQUEST["grados"]=="Doble Grado en Ingeniería Informática y Matemáticas") ? 23 : 22;
    $resto = $final-$_SESSION["edad"];
    echo "Estimad".$_SESSION["sufijo"]." ".$_SESSION["tratamiento"].$_SESSION["nombre"].". Le informamos que la edad media de finalización de estos estudios es ".$final." años.".($resto > 0 ? " Puede terminar por tanto en ".$resto." años." : " Debería de haber terminado por tanto hace ".(-$resto)." años. Su queja al departamento de Circuitos Electrónicos está siendo tramitada en estos instantes.");
}  else if (isset($_REQUEST["submit1"])) {
    $_SESSION["nombre"] =  $_REQUEST["nombre"];
    $_SESSION["edad"] =  $_REQUEST["edad"];
    $_SESSION["genero"] =  $_REQUEST["genero"];
    $_SESSION["tratamiento"] = $_SESSION["genero"] == "Hombre" ? "Sr. " : ($_SESSION["genero"] == "Mujer" ? "Srta. " : "bicho llamado ");
    $_SESSION["sufijo"] = $_SESSION["genero"] == "Hombre" ? "o" : ($_SESSION["genero"] == "Mujer" ? "a" : "erino");
    if (!isset($_REQUEST["eps"])) {
        echo "Por favor, ".$_SESSION["tratamiento"].$_REQUEST["nombre"]." debe acceder a la página Web de la Universidad para seguir con sus gestiones.";
    } else {
        echo "Bienvenid".$_SESSION["sufijo"]." ".$_SESSION["tratamiento"].$_REQUEST["nombre"].", seleccione del siguiente listado el nombre de la titulación que está cursando:<br><br>";
        echo "<form action=\"form1.php\" method=\"POST\">";
        echo "<datalist id=\"grados\">";
        echo "<option value=\"Grado en Ingeniería Informática\">";
        echo "<option value=\"Grado en Ingeniería de Telecomunicaciones\">";
        echo "<option value=\"Doble Grado en Ingeniería Informática y Matemáticas\">";
        echo "</datalist>";
        echo "<label>Nombre de la titulación:</label><br>";
        echo "<input list=\"grados\" name=\"grados\"><br><br>";
        echo "<input type=\"submit\" name=\"submit2\" value=\"Enviar\">";
    }
} else {
    echo "Error: no se ha rellenado el formulario o la redirección es incorrecta.";
}
?>


</body>
</html>
