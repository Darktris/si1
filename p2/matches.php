<!DOCTYPE html>
<!-- vim: set noai ts=4 sw=4: -->
<?php
$xml = simplexml_load_file("db.xml");
$upcoming = array();
$latest = array();
foreach($xml->category as $category) {
    foreach($category->game as $game) {
        if(empty($_GET["game"]) || isset($_GET["game"]) && strcmp($game["id"], $_GET["game"]) == 0) {
            foreach($game->matches->match as $match) {
                if(isset($match->result)) {
                    $latest[] = array("g" => $game, "m" => $match);
                } else {
                    $upcoming[] = array("g" => $game, "m" => $match);
                }
            }
        }
    }
}
echo '<div class="category">Upcoming Matches</div>';
usort($upcoming, function($m1, $m2) {
    return strcmp($m1["m"]->date,$m2["m"]->date);
});
foreach($upcoming as $match) {
    echo '<div class="match" onclick=loadContent("bet.php?game='.$match["g"]["id"].'&match='.$match["m"]["id"].'")>';
    echo '  <div class="matchinfo">';
    echo '      <div class="matchdetail">'.$match["m"]->date.'</div>';
    echo '      <img src="'.$match["m"]->team[0]->icon.'" alt=""/>';
    echo '      '.$match["m"]->team[0]["name"].' vs. '.$match["m"]->team[1]["name"];
    echo '      <img src="'.$match["m"]->team[1]->icon.'" alt=""/>';
    echo '      <div class="matchdetail">'.$match["g"]["name"].'</div>';
    echo '  </div>';
    echo '</div>';
}
echo '<div class="category">Latest Matches</div>';
usort($latest, function($m1, $m2) {
    return strcmp($m2["m"]->date,$m1["m"]->date);
});
foreach($latest as $match) {
    if(strcmp($match["m"]->result, "0") == 0) {
        $c0 = "g";
        $c1 = "r";
    } elseif(strcmp($match["m"]->result, "1") == 0) {
        $c0 = "r";
        $c1 = "g";
    } elseif(strcmp($match["m"]->result, "tie") == 0) {
        $c0 = "";
        $c1 = "";
    }
    echo '<div class="match">';
    echo '  <div class="side0'.$c0.'">'.$match["m"]->team[0]->score.'</div><div class="arrow0'.$c0.'"></div>';
    echo '  <div class="matchinfo">';
    echo '      <div class="matchdetail">'.$match["m"]->date.'</div>';
    echo '      <img src="'.$match["m"]->team[0]->icon.'" alt=""/>';
    echo '      '.$match["m"]->team[0]["name"].' vs. '.$match["m"]->team[1]["name"];
    echo '      <img src="'.$match["m"]->team[1]->icon.'" alt=""/>';
    echo '      <div class="matchdetail">'.$match["g"]["name"].'</div>';
    echo '  </div>';
    echo '  <div class="side1'.$c1.'">'.$match["m"]->team[1]->score.'</div><div class="arrow1'.$c1.'"></div>';
    echo '</div>';
}
?>
