<!DOCTYPE html>
<?php
session_start();
if(!isset($_POST["index_token"]) || strcmp($_POST["index_token"], $_SESSION["index_token"]) !== 0) {
    header('Location: '.dirname(strtok($_SERVER["REQUEST_URI"],'?')));
    die;
}
?>
<div class="title">
    Credit
</div>
<?php
if(isset($_SESSION["user"])) {
    $db = new PDO("pgsql:dbname=si1; host=localhost", "alumnodb", "alumnodb");
    $user = $db->query("select * from customers where customerid = ".$_SESSION["user"]);
    if($user->rowCount() == 1) {
        $credit = $db->query("select credit from customers where customerid = ".$_SESSION["user"]." limit 1")->fetch()["credit"];
        if(isset($_GET["option"])) {
            if(strcmp($_GET["option"], "charge") == 0) {
                if(isset($_POST["1"])) {
                    $db->query("update customers set credit = credit + ".$_POST["1"]." where customerid = ".$_SESSION["user"]);
                    echo '<div class="text">';
                    echo '  You have charged '.$_POST["1"].' € into your account.';
                    echo '</div>';
                    echo '<form method="post" onsubmit="return false">';
                    echo '  <button type="submit" onclick="loadContent(\'credit.php\')">Back</button>';
                    echo '</form>';
                } else {
                    echo '<div class="text">Charge credit</div>';
                    echo '<form method="post" name="chargeform" onsubmit="return false">';
                    echo '  Amount: <span class="input-euro"><input id="amount" type="number" min="10" max="1000000" step="1" value="1000"></span><br>';
                    echo '  <div class="error" id="amount_error"></div>';
                    echo '  <input type="reset" value="Back" onclick="loadContent(\'credit.php\')">';
                    echo '  <input type="submit" value="Confirm" onclick="validateCredit(\'charge\')">';
                    echo '</div><br>';
                }
            } elseif(strcmp($_GET["option"], "withdraw") == 0) {
                if(isset($_POST["1"])) {
                    $db->query("update customers set credit = credit - ".$_POST["1"]." where customerid = ".$_SESSION["user"]);
                    echo '<div class="text">';
                    echo '  You have withdrawn '.$_POST["1"].' € from your account.';
                    echo '</div>';
                    echo '<form method="post" onsubmit="return false">';
                    echo '  <button type="submit" onclick="loadContent(\'credit.php\')">Back</button>';
                    echo '</form>';
                } else {
                    echo '<div class="text">Withdraw credit</div>';
                    echo '<form method="post" name="withdrawform" onsubmit="return false">';
                    echo '  Amount: <span class="input-euro"><input id="amount" type="number" min="1" max="'.$credit.'" step="1" value="10"></span><br>';
                    echo '  <div class="error" id="amount_error"></div>';
                    echo '  <input type="reset" value="Back" onclick="loadContent(\'credit.php\')">';
                    echo '  <input type="submit" value="Confirm" onclick="validateCredit(\'withdraw\')">';
                    echo '</div><br>';
                }
            } else {
                echo '<div class="error">Invalid option.</div>';
            }
        } else {
            echo '<div class="category">'.$credit.' €</div>';
            echo '<div class="option" onclick="loadContent(\'credit.php?option=charge\')">';
            echo '  Charge credit';
            echo '</div>';
            echo '<div class="option" onclick="loadContent(\'credit.php?option=withdraw\')">';
            echo '  Withdraw credit';
            echo '</div>';
        }
    }
    unset($db);
    unset($user);
}
?>
