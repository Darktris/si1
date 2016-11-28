<!DOCTYPE html>
<?php
session_start();
if(!isset($_POST["index_token"]) || strcmp($_POST["index_token"], $_SESSION["index_token"]) !== 0) {
    header('Location: '.dirname(strtok($_SERVER["REQUEST_URI"],'?')));
    die;
}
?>
<div class="title">
    Checkout
</div>
<?php
if(isset($_SESSION["user"])) {
    $db = new PDO("pgsql:dbname=si1; host=localhost", "alumnodb", "alumnodb");
    $user = $db->query("select * from customers where customerid = ".$_SESSION["user"]);
    if($user->rowCount() == 1) {
        $order = $db->query("select * from clientorders where customerid = ".$_SESSION["user"]." and date is null order by orderid desc limit 1");
        if($order->rowCount() == 1) {
            $oid = $order->fetch()["orderid"];
            if(isset($_GET["remove"])) {
                $qbet = $db->query("select * from clientbets where betid = ".$_GET["remove"]." and orderid = ".$oid." limit 1");
                if($qbet->rowCount() == 1) {
                    $bag_bet = $qbet->fetch();
                    $bet = $db->query("select * from bets where winneropt is null and betid = ".$_GET["remove"]." limit 1")->fetch();
                    $category = $db->query("select categorystring from categories where categoryid = ".$bet["categoryid"]." limit 1")->fetch()["categorystring"];
                    $teams = explode("-", $bet["betdesc"]);
                    $option0 = $db->query("select optionid from options where optiondesc like ".$db->quote($teams[0]))->fetch()["optionid"];
                    $winner = ($bag_bet["optionid"] == $option0? 0 : 1);
                    echo '<div class="text">';
                    echo '  The bet below will be removed from your shopping bag.';
                    echo '</div>';
                    echo '<div class="match">';
                    if($winner == 0) {
                        echo '  <div class="side0">'.$bag_bet["bet"].' €</div><div class="arrow0"></div>';
                    }
                    echo '  <div class="matchinfo">';
                    echo '      <div class="matchdetail">'.date('D, jS F Y',strtotime($bet["betcloses"])).'</div>';
                    echo '      '.$teams[0].' vs. '.$teams[1];
                    echo '      <div class="matchdetail">'.$category.'</div>';
                    echo '  </div>';
                    if($winner == 1) {
                        echo '  <div class="side1">'.$bag_bet["bet"].' €</div><div class="arrow1"></div>';
                    }
                    echo '</div>';
                    echo '<form method="post">';
                    echo '  <input type="hidden" name="content" value="checkout.php">';
                    echo '  <button type="submit">Confirm</button>';
                    echo '</form>';
                    $db->exec("delete from clientbets where betid = ".$_GET["remove"]." and orderid = ".$oid);
                    unset($db);
                    unset($user);
                    unset($order);
                    unset($oid);
                    unset($qbet);
                    unset($bag_bet);
                    unset($bet);
                    unset($category);
                    unset($teams);
                    unset($option0);
                    unset($winner);
                    return;
                }
                unset($qbet);
            }
            $total = $order->fetch()["totalamount"];
            if(isset($_GET["confirm"]) && strcmp($_GET["confirm"], "true") == 0) {
                if($total <= $user->fetch()["credit"]) {
                    $db->exec("update clientorders set date = now() where customerid = ".$_SESSION["user"]." and orderid = ".$order);
                    echo '<div class="text">';
                    echo '  Your shopping bag has been successfully processed.';
                    echo '</div>';
                    echo '<form method="post">';
                    echo '  <button type="submit">Back</button>';
                    echo '</form>';
                    unset($db);
                    unset($user);
                    unset($order);
                    unset($oid);
                    unset($total);
                    unset($chckt_error);
                    return;
                } else {
                    $chckt_error = "Not enough credit to checkout.";
                }
            }
            foreach($db->query("select * from clientbets where orderid = ".$oid) as $bag_bet) {
                $bet = $db->query("select * from bets where winneropt is null and betid = ".$bag_bet["betid"]." limit 1")->fetch();
                $category = $db->query("select categorystring from categories where categoryid = ".$bet["categoryid"]." limit 1")->fetch()["categorystring"];
                $teams = explode("-", $bet["betdesc"]);
                $option0 = $db->query("select optionid from options where optiondesc like ".$db->quote($teams[0]))->fetch()["optionid"];
                $winner = ($bag_bet["optionid"] == $option0? 0 : 1);
                echo '<div class="match" name="bagmatch">';
                if($winner == 0) {
                    echo '  <div class="side0">'.$bag_bet["bet"].' €</div><div class="arrow0"></div>';
                }
                echo '  <div class="edit" onclick="loadContent(\'bet.php?betid='.$bag_bet["betid"].'\')"><div class="side0g">Edit</div><div class="arrow0g"></div></div>';
                echo '  <div class="matchinfo">';
                echo '      <div class="matchdetail">'.date('D, jS F Y',strtotime($bet["betcloses"])).'</div>';
                echo '      '.$teams[0].' vs. '.$teams[1];
                echo '      <div class="matchdetail">'.$category.'</div>';
                echo '  </div>';
                if($winner == 1) {
                    echo '  <div class="side1">'.$bag_bet["bet"].' €</div><div class="arrow1"></div>';
                }
                echo '  <div class="remove" onclick="loadContent(\'checkout.php?remove='.$bag_bet["betid"].'\')"><div class="side1r">Remove</div><div class="arrow1r"></div></div>';
                echo '</div>';
                unset($bet);
                unset($category);
                unset($teams);
                unset($option0);
                unset($winner);
            }
            echo '<hr>';
            echo '<div class="totaltext">Total:</div>';
            echo '<div class="total">'.$total.' €</div>';
            echo '<form method="post" onsubmit="return false">';
            if(isset($chckt_error)) {
                echo '  <div class="error">'.$chckt_error.'</div>';
                unset($chckt_error);
            }
            echo '  <input type="reset" value="Back" onclick="loadContent()">';
            echo '  <input type="submit" value="Confirm" onclick="loadContent(\'checkout.php?confirm=true\')">';
            echo '</div><br>';
            unset($oid);
            unset($total);
        } else {
            echo '<div class="text">';
            echo '  Your bag is empty.';
            echo '</div>';
            echo '<form method="post">';
            echo '  <button type="submit">Back</button>';
            echo '</form>';
        }
        unset($order);
    } else {
        echo '<div class="text">';
        echo '  Corrupt user, please relog.';
        echo '</div>';
        echo '<form method="post">';
        echo '  <button type="submit">Back</button>';
        echo '</form>';
    }
    unset($db);
    unset($user);
} else {
    echo '<div class="text">';
    echo '  You must log in to checkout.';
    echo '</div>';
    echo '<form method="post">';
    echo '  <button type="submit">Back</button>';
    echo '</form>';
}
?>
