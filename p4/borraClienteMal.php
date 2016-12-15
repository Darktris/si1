<?php
define("PGUSER", "alumnodb");
define("PGPASSWORD", "alumnodb");
define("DSN","pgsql:host=localhost;dbname=si1;options='--client_encoding=UTF8'");
function print_status($db, $customerid) {
  echo "<hr>";
  echo "<div class='status'>";
  echo "<h2>CLIENTE:</h2>";
  $customer = $db->query("select * from customers where customerid = ".$customerid);
  if($customer->rowCount() == 0) {
    echo "<p>No existe ningún cliente con ID ".$customerid."</p>";
  } else {
    echo "<table>";
    echo "<tr>";
    for ($i = 0; $i<$customer->columnCount(); $i++) {
      echo "<td>";
      echo "<b>".$customer->getColumnMeta($i)['name']."</b>";
      echo "</td>";
    }
    echo "</tr>";
    foreach($customer->fetchAll(PDO::FETCH_ASSOC) as $c) {
      echo "<tr>";
      foreach($c as $field) {
        echo "<td>";
        echo $field;
        echo "</td>";
      }
      echo "</tr>";
    }
    echo "</table>";

    echo "<h2>PEDIDOS:</h2>";
    $order = $db->query("select (select count(*) as bet_count from clientbets as cb where cb.orderid = co.orderid), * from clientorders as co where customerid = ".$customerid);
    if($order->rowCount() == 0) {
      echo "<p>No existe ningún pedido del cliente con ID ".$customerid."</p>";
    } else {
      echo "<table>";
      echo "<tr>";
      for ($i = 0; $i<$order->columnCount(); $i++) {
        echo "<td>";
        echo "<b>".$order->getColumnMeta($i)['name']."</b>";
        echo "</td>";
      }
      echo "</tr>";
      foreach($order->fetchAll(PDO::FETCH_ASSOC) as $o) {
        echo "<tr>";
        foreach($o as $field) {
          echo "<td>";
          echo $field;
          echo "</td>";
        }
        echo "</tr>";
      }
      echo "</table>";
    }
  }
  echo "</div>";
  echo "<hr>";
}
?>

<html>
  <head>
    <title>Borrar Cliente</title>
      <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
      <script>
        function updateCommit() {
          document.getElementsByName("commit")[0].disabled = !document.getElementsByName("transaction")[0].checked
        }
      </script>
      <style type="text/css">
        table {
                border-style: none;
                border-collapse: collapse;
        }
        table th {
                border-width: 1px;
                padding: 1px;
                border-style: solid;
                border-color: gray;
                background-color: rgb(230, 230, 220);
        }
        table td {
                border-width: 1px;
                padding: 1px;
                border-style: solid;
                border-color: gray;
                background-color: rgb(255, 255, 240);
        }
        .status {
                color: gray;
        }
        .status table {
                color: inherit;
        }
      </style>
  </head>
  <body>
    <h1>Borrar Cliente</h1>
    <?php
      if (!isset($_REQUEST['submit'])) {
    ?>
    <form action="" method="GET">
      <i>customerid</i>: <input type="text" name="customerid">
      <br>
      <input type="checkbox" name="transaction" onclick="updateCommit()">Usar transacciones
      <br>
      <input type="checkbox" name="commit" disabled>Realizar COMMIT intermedio
      <br>
      <input type="submit" name="submit" value="Borrar">
    </form>
    <?php
      } else {
        try {
          $db = new PDO(DSN,PGUSER,PGPASSWORD);
          $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          if(isset($_REQUEST['transaction'])) {
            $db->beginTransaction();
          }

          print_status($db, $_REQUEST['customerid']);

          foreach($db->query("select orderid from clientorders where customerid = ".$_REQUEST['customerid']) as $order) {
            $result = $db->exec("delete from clientbets where orderid = ".$order['orderid']);
            if ($result === FALSE){
              echo "<p>Borrando apuestas del pedido con ID ".$order['orderid'].".... ERROR!</p>";
            } else {
              echo "<p>Borrando apuestas del pedido con ID ".$order['orderid']."... OK!</p>";
            }
            /* Se deja intencionadamente el pedido sin borrar para que provoque un fallo de restricción de foreign key */
          }
          print_status($db, $_REQUEST['customerid']);

          if(isset($_REQUEST['transaction']) && isset($_REQUEST['commit'])) {
            $db->commit();
            $db->beginTransaction();
          }

          $result = $db->exec("delete from customers where customerid = ".$_REQUEST['customerid']);
          if ($result === FALSE){
            echo "<p>Borrando cliente con ID ".$_REQUEST['customerid']."... ERROR!</p>";
          } else {
            echo "<p>Borrando cliente con ID ".$_REQUEST['customerid']."... OK!</p>";
          }
          print_status($db, $_REQUEST['customerid']);

          if(isset($_REQUEST['transaction'])) {
            $db->commit();
          }
        } catch (PDOException $e) {
          echo "<p>Error!: " . $e->getMessage() . "</p>";
          if(isset($db)) {
            if(isset($_REQUEST['transaction'])) {
              $db->rollBack();
              echo "<p><i>RollBack</i> realizado.</p>";
            }
            print_status($db, $_REQUEST['customerid']);
          }
        }
        echo '<p><a href="borraClienteMal.php">Volver</a></p>';
        $db = null;
      }
    ?>
  </body>
</html>
<!--
vim: noai:ts=2:sw=2
-->
