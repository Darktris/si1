<!DOCTYPE html>
<?php
session_start();
if(!isset($_POST["index_token"]) || strcmp($_POST["index_token"], $_SESSION["index_token"]) !== 0) {
    header('Location: '.dirname(strtok($_SERVER["REQUEST_URI"],'?')));
    die;
}
?>
<div class="title">
    Bet Details
</div>
<?php
if(isset($_GET["betid"])) {
    $db = new PDO("pgsql:dbname=si1; host=localhost", "alumnodb", "alumnodb");
    $bet = $db->query("select * from bets where winneropt is null and betid = ".$_GET["betid"]." limit 1")->fetch();
    $category = $db->query("select categorystring from categories where categoryid = ".$bet["categoryid"]." limit 1")->fetch()["categorystring"];
    $teams = explode("-", $bet["betdesc"]);
    if(isset($_SESSION["user"])) {
        $user = $db->query("select * from customers where customerid = ".$_SESSION["user"]);
        if($user->rowCount() == 1) {
            $order = $db->query("select * from clientorders where customerid = ".$_SESSION["user"]." and date is null order by orderid desc limit 1");
            if(isset($_POST["1"]) && isset($_POST["2"])) {
                $edit = false;
                /* "1" is left/right team, "2" is amount */
                if($order->rowCount() == 0) {
                    $db->exec("insert into clientorders (customerid, totalamount) values (".$_SESSION["user"].", 0)");
                    $oid = $db->query("select max(orderid) from clientorders where customerid = ".$_SESSION["user"]." and date is null")->fetchColumn();
                } else {
                    $oid = $order->fetch()["orderid"];
                    if($db->query("select * from clientbets where betid = ".$bet["betid"]." and orderid = ".$oid." limit 1")->rowCount() == 1) {
                        $edit = true;
                    }
                }
                $option = $db->query("select optionid from options where optiondesc like concat('%',".$db->quote($teams[$_POST["1"]]).",'%')")->fetch()["optionid"];
                $ratio = $db->query("select ratio from optionbet where optionid = ".$option." and betid = ".$bet["betid"])->fetch()["ratio"];
                if($edit) {
                    $db->exec("update clientbets set optionid = ".$option.", bet = ".$_POST["2"]." where betid = ".$bet["betid"]." and orderid = ".$oid);
                } else {
                    $db->exec("insert into clientbets (optionid, bet, ratio, betid, orderid) values (".$option.", ".$_POST["2"].", ".$ratio.", ".$bet["betid"].", ".$oid.")");
                }
                unset($oid);
                unset($option);
                unset($ratio);
                echo '<div class="text">';
                echo '  The bet below will be placed in your shopping bag.';
                echo '</div>';
                echo '<div class="match">';
                if(strcmp($_POST["1"], "0") == 0) {
                    echo '  <div class="side0">'.$_POST["2"].' €</div><div class="arrow0"></div>';
                }
                echo '  <div class="matchinfo">';
                echo '      <div class="matchdetail">'.date('D, jS F Y',strtotime($bet["betcloses"])).'</div>';
                echo '      '.$teams[0].' vs. '.$teams[1];
                echo '      <div class="matchdetail">'.$category.'</div>';
                echo '  </div>';
                if(strcmp($_POST["1"], "1") == 0) {
                    echo '  <div class="side1">'.$_POST["2"].' €</div><div class="arrow1"></div>';
                }
                echo '</div>';
                echo '<form method="post">';
                if(isset($_GET["edit"]) && (strcmp($_GET["edit"], "true") == 0)) {
                    echo '  <input type="hidden" name="content" value="checkout.php">';
                }
                echo '  <button type="submit">Confirm</button>';
                echo '</form>';
            } else {
                $edit = false;
                $winner = 0;
                $amount = 10;
                if($order->rowCount() == 1) {
                    $oid = $order->fetch()["orderid"];
                    $qbet = $db->query("select * from clientbets where betid = ".$bet["betid"]." and orderid = ".$oid." limit 1");
                    if($qbet->rowCount() == 1) {
                        $edit = true;
                        $old_bet = $qbet->fetch();
                        $option0 = $db->query("select optionid from options where optiondesc like concat('%',".$db->quote($teams[0]).",'%')")->fetch()["optionid"];
                        $winner = ($old_bet["optionid"] == $option0? 0 : 1);
                        $amount = $old_bet["bet"];
                        unset($old_bet);
                        unset($option0);
                    }
                    unset($oid);
                    unset($qbet);
                }
                echo '<div class="text">';
                echo '  Please choose a winner and the amount to bet.';
                echo '</div>';
                echo '<div class="match" id="match">';
                if($winner == 0) {
                    echo '<div class="side0" id="wnnrside">'.$amount.' €</div><div class="arrow0" id="wnnrarrow"></div>';
                }
                echo '  <div class="matchinfo">';
                echo '      <div class="matchdetail">'.date('D, jS F Y',strtotime($bet["betcloses"])).'</div>';
                echo '      <input type="radio" name="team" value="0" '.($winner == 0? "checked " : "").'onclick="updateBet(\'0\')">';
                echo '      '.$teams[0].' vs. '.$teams[1];
                echo '      <input type="radio" name="team" value="1" '.($winner == 1? "checked " : "").'onclick="updateBet(\'1\')">';
                echo '      <div class="matchdetail">'.$category.'</div>';
                echo '  </div>';
                if($winner == 1) {
                    echo '<div class="side1" id="wnnrside">'.$amount.' €</div><div class="arrow1" id="wnnrarrow"></div>';
                }
                echo '</div>';
                echo '<form method="post" name="betform" onsubmit="return false">';
                echo '  Amount: <span class="input-euro"><input id="amount" type="number" min="10" max="1000" step="1" value="'.$amount.'" oninput=updateBet()></span><br>';
                echo '  <div class="error" id="amount_error"></div>';
                echo '  <input type="reset" value="Back" onclick="loadContent()">';
                echo '  <input type="submit" value="Confirm" onclick="validateBet(\''.$bet["betid"].'\',\''.(isset($_GET["edit"]) && (strcmp($_GET["edit"], "true") == 0)? "true" : "false").'\')">';
                echo '</div><br>';
                unset($winner);
                unset($amount);
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
    } else {
        echo '<div class="text">';
        echo '  You must log in to bet.';
        echo '</div>';
        echo '<form method="post">';
        echo '  <button type="submit">Back</button>';
        echo '</form>';
    }
    unset($db);
    unset($bet);
    unset($category);
    unset($teams);
    unset($edit);
} else {
    echo '<div class="error">Illegal access.</div>';
}
?>
