<!DOCTYPE html>
<?php
session_start();
if(!isset($_POST["index_token"]) || strcmp($_POST["index_token"], $_SESSION["index_token"]) !== 0) {
    header('Location: '.dirname(strtok($_SERVER["REQUEST_URI"],'?')));
    die;
}
function showmatch($db, $bet, $set) {
    $category = $db->query("select categorystring from categories where categoryid = ".$bet["categoryid"]." limit 1")->fetch()["categorystring"];
    $teams = explode("-", $bet["betdesc"]);
    if($set) {
        $winner = $db->query("select optiondesc from options where optionid = ".$bet["winneropt"]." limit 1")->fetch()["optiondesc"];
        if(strcmp($teams[0], $winner) == 0) {
            $c0 = "g";
            $s0 = "Won";
            $c1 = "r";
            $s1 = "Lost";
        } elseif(strcmp($teams[1], $winner) == 0) {
            $c0 = "r";
            $s0 = "Lost";
            $c1 = "g";
            $s1 = "Won";
        } else {
            $c0 = "";
            $s0 = "Tied";
            $c1 = "";
            $s1 = "Tied";
        }
        echo '<div class="match">';
        echo '  <div class="side0'.$c0.'">'.$s0.'</div><div class="arrow0'.$c0.'"></div>';
    } else {
        echo '<div class="match" onclick="loadContent(\'bet.php?betid='.$bet["betid"].'\')">';
    }
    echo '  <div class="matchinfo">';
    echo '      <div class="matchdetail">'.$bet["betcloses"].'</div>';
    echo '      '.$teams[0].' vs. '.$teams[1];
    echo '      <div class="matchdetail">'.$category.'</div>';
    echo '  </div>';
    if($set) {
        echo '  <div class="side1'.$c1.'">'.$s1.'</div><div class="arrow1'.$c1.'"></div>';
    }
    echo '</div>';
}
$db = new PDO("pgsql:dbname=si1; host=localhost", "alumnodb", "alumnodb");
if(isset($_POST["1"]) && !empty($_POST["1"])) {
    $filter = "betdesc like '%".$_POST["1"]."%' and ";
    $query = "&1=".$_POST["1"];
} elseif(isset($_REQUEST["category"])) {
    $filter = "categoryid = ".$_REQUEST["category"]." and ";
    $query = "&category=".$_REQUEST["category"];
} else {
    $filter = "";
    $query = "";
}
$count = 0;
if(isset($_POST["more"]) && isset($_POST["last"])) {
    if(strcmp($_POST["more"], "upcoming") == 0) {
        foreach($db->query("select * from bets where ".$filter."winneropt is null order by betcloses asc offset ".$_POST["last"]." limit 10") as $row) {
            showmatch($db, $row, false);
            $count++;
        }
        if($count == 10) {
            echo '<div class="upcoming_more"></div>';
            echo '<button id="upcoming_more" onclick="loadMoreMatches(\'upcoming\',\''.(intval($_POST["last"])+10).'\',\''.$query.'\')"><span>➕</span></button>';
        }
    } elseif(strcmp($_POST["more"], "latest") == 0) {
        foreach($db->query("select * from bets where ".$filter."winneropt is not null order by betcloses desc offset ".$_POST["last"]." limit 10") as $row) {
            showmatch($db, $row, true);
            $count++;
        }
        if($count == 10) {
            echo '<div class="latest_more"></div>';
            echo '<button id="latest_more" onclick="loadMoreMatches(\'latest\',\''.(intval($_POST["last"])+10).'\',\''.$query.'\')"><span>➕</span></button>';
        }
    }
} else {
    echo '<div class="category">Upcoming Matches</div>';
    foreach($db->query("select * from bets where ".$filter."winneropt is null order by betcloses asc, betid asc limit 10") as $row) {
        showmatch($db, $row, false);
        $count++;
    }
    if($count == 10) {
        echo '<div class="upcoming_more"></div>';
        echo '<button id="upcoming_more" onclick="loadMoreMatches(\'upcoming\',\''.$count.'\',\''.$query.'\')"><span>➕</span></button>';
    }
    echo '<br>';
    echo '<div class="category">Latest Matches</div>';
    $count = 0;
    foreach($db->query("select * from bets where ".$filter."winneropt is not null order by betcloses desc, betid asc limit 10") as $row) {
        showmatch($db, $row, true);
        $count++;
    }
    if($count == 10) {
        echo '<div class="latest_more"></div>';
        echo '<button id="latest_more" onclick="loadMoreMatches(\'latest\',\''.$count.'\',\''.$query.'\')"><span>➕</span></button>';
    }
}
unset($db);
unset($count);
unset($filter);
unset($query);
?>
