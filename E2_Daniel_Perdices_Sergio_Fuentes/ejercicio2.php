<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
        <title>Estrellas</title>
        <style>
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }
            th, td {
                padding: 5px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <h1>Estrellas</h1>
        <table>
            <tr>
                <td><b>ID</b></td>
                <td><b>Nombre</b></td>
                <td><b>Año</b></td>
                <td><b>Máximo</b></td>
            </tr>
<?php
$db = new PDO("pgsql:dbname=si1-pelis; host=localhost", "alumnodb", "alumnodb");
foreach($db->query("select * from estrellas") as $row) {
    echo '<tr>';
    echo '<td>'.$row['actor_id'].'</td>';
    echo '<td>'.$row['nombre'].'</td>';
    echo '<td>'.$row['agno'].'</td>';
    echo '<td>'.$row['maximo'].'</td>';
    echo '</tr>';
}
?>
        </table>
    </body>
</html>
