<!DOCTYPE html>
<?php
session_start();
if(!isset($_POST["index_token"]) || strcmp($_POST["index_token"], $_SESSION["index_token"]) !== 0) {
    header('Location: '.dirname(strtok($_SERVER["REQUEST_URI"],'?')));
    die;
}
date_default_timezone_set('Europe/Madrid');
function showmatch($db, $bet, $count) {
    $category = $db->query("select categorystring from categories where categoryid = ".$bet["categoryid"]." limit 1")->fetch()["categorystring"];
    $teams = explode("-", $bet["betdesc"]);
    $option0 = $db->query("select optionid from options where optiondesc like concat('%',".$db->quote($teams[0]).",'%')")->fetch()["optionid"];
    $winner = ($bet["optionid"] == $option0? 0 : 1);
    echo '<div class="match" onclick="showBetDetails(\''.$count.'\')">';
    if($winner == 0) {
        echo '  <div class="side0">'.$bet["bet"].' €</div><div class="arrow0"></div>';
    }
    echo '  <div class="matchinfo">';
    echo '      <div class="matchdetail">'.date('D, jS F Y',strtotime($bet["betcloses"])).'</div>';
    echo '      '.$teams[0].' vs. '.$teams[1];
    echo '      <div class="matchdetail">'.$category.'</div>';
    echo '  </div>';
    if($winner == 1) {
        echo '  <div class="side1">'.$bet["bet"].' €</div><div class="arrow1"></div>';
    }
    echo '</div>';
    echo '<div class="betdetails" id="details'.$count.'">';
    echo '  Bet made on '.date('D, jS F Y \a\t H:i',strtotime($bet["date"]));
    echo '</div>';
}
if(isset($_SESSION["user"])) {
    $db = new PDO("pgsql:dbname=si1; host=localhost", "alumnodb", "alumnodb");
    $user = $db->query("select * from customers where customerid = ".$_SESSION["user"]);
    if($user->rowCount() == 1) {
        if(isset($_POST["more"]) && isset($_POST["last"])) {
            $count = intval($_POST["last"]);
            foreach($db->query("select * from clientorders as co, clientbets as cb, bets as b where co.customerid = ".$_SESSION["user"]." and cb.orderid = co.orderid and b.betid = cb.betid order by co.date desc offset ".$_POST["last"]." limit 20") as $bet) {
                showmatch($db, $bet, $count);
                $count++;
            }
            if($count == intval($_POST["last"]) + 20) {
                echo '<div class="history_more"></div>';
                echo '<button id="history_more" onclick="loadMoreMatches(\'history\',\''.$count.'\')"><span>➕</span></button>';
            }
        } else {
            $count = 0;
            echo '<div class="title">';
            echo '  Bet History';
            echo '</div>';
            foreach($db->query("select * from clientorders as co, clientbets as cb, bets as b where co.customerid = ".$_SESSION["user"]." and cb.orderid = co.orderid and b.betid = cb.betid order by co.date desc limit 20") as $bet) {
                showmatch($db, $bet, $count);
                $count++;
            }
            if($count == 20) {
                echo '<div class="history_more"></div>';
                echo '<button id="history_more" onclick="loadMoreMatches(\'history\',\'20\')"><span>➕</span></button>';
            }
        }
        unset($db);
        unset($count);
    }
}
?>
