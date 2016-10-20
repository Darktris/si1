<!DOCTYPE html>
<!-- vim: set noai ts=4 sw=4: -->
<?php
session_start();
function showmatch($game, $match, $set) {
    if($set) {
        if(strcmp($match->result, "0") == 0) {
            $c0 = "g";
            $c1 = "r";
        } elseif(strcmp($match->result, "1") == 0) {
            $c0 = "r";
            $c1 = "g";
        } elseif(strcmp($match->result, "tie") == 0) {
            $c0 = "";
            $c1 = "";
        }
        echo '<div class="match">';
        echo '  <div class="side0'.$c0.'">'.$match->team[0]->score.'</div><div class="arrow0'.$c0.'"></div>';
    } else {
        echo '<div class="match" onclick=loadContent("bet.php?game='.$game["id"].'&match='.$match["id"].'")>';
    }
    echo '  <div class="matchinfo">';
    echo '      <div class="matchdetail">'.date('D, jS F Y @ H:i',strtotime($match->date)).'</div>';
    echo '      <img src="'.$match->team[0]->icon.'" alt=""/>';
    echo '      '.$match->team[0]["name"].' vs. '.$match->team[1]["name"];
    echo '      <img src="'.$match->team[1]->icon.'" alt=""/>';
    echo '      <div class="matchdetail">'.$game["name"].'</div>';
    echo '  </div>';
    if($set) {
        echo '  <div class="side1'.$c1.'">'.$match->team[1]->score.'</div><div class="arrow1'.$c1.'"></div>';
    }
    echo '</div>';
}
$xml = simplexml_load_file("db.xml");
$upcoming = array();
$latest = array();
foreach($xml->category as $category) {
    foreach($category->game as $game) {
        if(empty($_GET["game"]) || isset($_GET["game"]) && strcmp($game["id"], $_GET["game"]) == 0) {
            foreach($game->matches->match as $match) {
                if(empty($_GET["1"]) || isset($_GET["1"]) && (
                    strpos($category["name"], $_GET["1"]) !== false ||
                    strpos($game["name"], $_GET["1"]) !== false ||
                    strpos($match->team[0]["name"], $_GET["1"]) !== false ||
                    strpos($match->team[1]["name"], $_GET["1"])  !== false
                )) {
                    if(isset($match->result)) {
                        $latest[] = array("g" => $game, "m" => $match);
                    } else {
                        $upcoming[] = array("g" => $game, "m" => $match);
                    }
                }
            }
        }
    }
}
unset($xml);
echo '<div class="category">Upcoming Matches</div>';
usort($upcoming, function($m1, $m2) {
    return strcmp($m1["m"]->date, $m2["m"]->date);
});
foreach($upcoming as $i) {
    showmatch($i["g"], $i["m"], false);
}
unset($upcoming);
echo '<br>';
echo '<div class="category">Latest Matches</div>';
usort($latest, function($m1, $m2) {
    return strcmp($m2["m"]->date,$m1["m"]->date);
});
foreach($latest as $i) {
    showmatch($i["g"], $i["m"], true);
}
unset($latest);
?>
