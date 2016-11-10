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
            $s0 = "WIN";
            $c1 = "r";
            $s1 = "LOSE";
        } elseif(strcmp($teams[1], $winner) == 0) {
            $c0 = "r";
            $s0 = "LOSE";
            $c1 = "g";
            $s1 = "WIN";
        } else {
            $c0 = "";
            $s0 = "TIE";
            $c1 = "";
            $s1 = "TIE";
        }
        echo '<div class="match">';
        echo '  <div class="side0'.$c0.'">'.$s0.'</div><div class="arrow0'.$c0.'"></div>';
    } else {
        echo '<div class="match" onclick="loadContent(\'bet.php?game='."TODO".'&match='."TODO".'\')">';
    }
    echo '  <div class="matchinfo">';
    echo '      <div class="matchdetail">'.$bet["betcloses"].'</div>';
    #echo '      <img src="'.$match->team[0]->icon.'" alt=""/>';
    echo '      '.$teams[0].' vs. '.$teams[1];
    #echo '      <img src="'.$match->team[1]->icon.'" alt=""/>';
    echo '      <div class="matchdetail">'.$category.'</div>';
    echo '  </div>';
    if($set) {
        echo '  <div class="side1'.$c1.'">'.$s1.'</div><div class="arrow1'.$c1.'"></div>';
    }
    echo '</div>';
}
$db = new PDO("pgsql:dbname=si1; host=localhost", "alumnodb", "alumnodb");
echo '<div class="category">Upcoming Matches</div>';
if(isset($_POST["1"]) && !empty($_POST["1"])) {
    $filter = "betdesc like '%".$_POST["1"]."%' and ";
} elseif(isset($_GET["game"])) {

} else {
    $filter = "";
}
foreach ($db->query("select * from bets where ".$filter."winneropt is null order by betcloses asc") as $row) {
    showmatch($db, $row, false);
}
echo '<br>';
echo '<div class="category">Latest Matches</div>';
foreach ($db->query("select * from bets where ".$filter."winneropt is not null order by betcloses desc") as $row) {
    showmatch($db, $row, true);
}
?>
