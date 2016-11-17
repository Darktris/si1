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
    if(isset($_SESSION["bag"]) && !empty($_SESSION["bag"])) {
        if(isset($_GET["remove"]) && array_key_exists(strval($_GET["remove"]), $_SESSION["bag"])) {
            $bag_bet = $_SESSION["bag"][strval($_GET["remove"])];
            unset($_SESSION["bag"][strval($_GET["remove"])]);
            $db = new PDO("pgsql:dbname=si1; host=localhost", "alumnodb", "alumnodb");
            $bet = $db->query("select * from bets where winneropt is null and betid = ".$_GET["remove"]." limit 1")->fetch();
            $category = $db->query("select categorystring from categories where categoryid = ".$bet["categoryid"]." limit 1")->fetch()["categorystring"];
            $teams = explode("-", $bet["betdesc"]);
            echo '<div class="text">';
            echo '  The bet below will be removed from your shopping bag.';
            echo '</div>';
            echo '<div class="match">';
            if(strcmp($bag_bet["winner"], "0") == 0) {
                echo '  <div class="side0">'.$bag_bet["amount"].' €</div><div class="arrow0"></div>';
            }
            echo '  <div class="matchinfo">';
            echo '      <div class="matchdetail">'.date('D, jS F Y',strtotime($bet["betcloses"])).'</div>';
            echo '      '.$teams[0].' vs. '.$teams[1];
            echo '      <div class="matchdetail">'.$category.'</div>';
            echo '  </div>';
            if(strcmp($bag_bet["winner"], "1") == 0) {
                echo '  <div class="side1">'.$bag_bet["amount"].' €</div><div class="arrow1"></div>';
            }
            echo '</div>';
            echo '<form method="post">';
            echo '  <input type="hidden" name="content" value="checkout.php">';
            echo '  <button type="submit">Confirm</button>';
            echo '</form>';
            unset($bag_bet);
            unset($db);
            unset($bet);
            unset($category);
            unset($teams);
            return;
        }
        $total = array_reduce($_SESSION["bag"], function($carry, $item) {
            $carry += $item["amount"];
            return $carry;
        }, 0);
        $db = new PDO("pgsql:dbname=si1; host=localhost", "alumnodb", "alumnodb");
        if(isset($_GET["confirm"]) && strcmp($_GET["confirm"], "true") == 0) {
            if(isset($_SESSION["user"])) {
                $user = $db->query("select * from customers where customerid = ".$_SESSION["user"]);
                if($user->rowCount() == 1) {
                    if($total <= $user->fetch()["credit"]) {
                        $db->query("update customers set credit = credit - ".$total." where customerid = ".$_SESSION["user"]);
                        $order = $db->query("select max(orderid) from clientorders")->fetch()["max"] + 1;
                        $db->query("insert into clientorders (customerid, date, orderid, totalamount) values (".$_SESSION["user"].", now(), ".$order.", ".$total.")");
                        foreach($_SESSION["bag"] as $id => $choices) {
                            $bet = $db->query("select * from bets where winneropt is null and betid = ".$id." limit 1")->fetch();
                            $winner = explode("-", $bet["betdesc"])[$choices["winner"]];
                            $option = $db->query("select optionid from options where optiondesc like ".$db->quote($winner))->fetch()["optionid"];
                            $ratio = $db->query("select ratio from optionbet where optionid = ".$option." and betid = ".$bet["betid"])->fetch()["ratio"];
                            $db->query("insert into clientbets (optionid, bet, ratio, betid, orderid) values (".$option.", ".$choices["amount"].", ".$ratio.", ".$id.", ".$order.")");
                            unset($bet);
                            unset($winner);
                            unset($option);
                            unset($ratio);
                        }
                        unset($_SESSION["bag"]);
                        unset($total);
                        unset($order);
                        unset($chckt_error);
                        echo '<div class="text">';
                        echo '  Your shopping bag has been successfully processed.';
                        echo '</div>';
                        echo '<form method="post">';
                        echo '  <button type="submit">Back</button>';
                        echo '</form>';
                        return;
                    } else {
                        $chckt_error = "Not enough credit to checkout.";
                    }
                } else {
                    $chckt_error = "Corrupt user.";
                }
                unset($user);
            } else {
                $chckt_error = "You must login to checkout.";
            }
        }
        foreach($_SESSION["bag"] as $id => $bag_bet) {
            $bet = $db->query("select * from bets where winneropt is null and betid = ".$id." limit 1")->fetch();
            $category = $db->query("select categorystring from categories where categoryid = ".$bet["categoryid"]." limit 1")->fetch()["categorystring"];
            $teams = explode("-", $bet["betdesc"]);
            echo '<div class="match" name="bagmatch">';
            if(strcmp($bag_bet["winner"], "0") == 0) {
                echo '  <div class="side0">'.$bag_bet["amount"].' €</div><div class="arrow0"></div>';
            }
            echo '  <div class="edit" onclick="loadContent(\'bet.php?betid='.$id.'&edit=true\')"><div class="side0g">Edit</div><div class="arrow0g"></div></div>';
            echo '  <div class="matchinfo">';
            echo '      <div class="matchdetail">'.date('D, jS F Y',strtotime($bet["betcloses"])).'</div>';
            echo '      '.$teams[0].' vs. '.$teams[1];
            echo '      <div class="matchdetail">'.$category.'</div>';
            echo '  </div>';
            if(strcmp($bag_bet["winner"], "1") == 0) {
                echo '  <div class="side1">'.$bag_bet["amount"].' €</div><div class="arrow1"></div>';
            }
            echo '  <div class="remove" onclick="loadContent(\'checkout.php?remove='.$id.'\')"><div class="side1r">Remove</div><div class="arrow1r"></div></div>';
            echo '</div>';
            unset($bet);
            unset($category);
            unset($teams);
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
        unset($db);
        unset($total);
    } else {
        echo '<div class="text">';
        echo '  Your bag is empty.';
        echo '</div>';
        echo '<form method="post">';
        echo '  <button type="submit">Back</button>';
        echo '</form>';
    }
?>
