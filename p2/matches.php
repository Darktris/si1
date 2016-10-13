<!DOCTYPE html>
<!-- vim: set noai ts=4 sw=4: -->
<?php
$xml = simplexml_load_file("db.xml");
foreach($xml->category as $category) {
    foreach($category->game as $game) {
        if(empty($_GET["game"]) || isset($_GET["game"]) && strcmp($game["id"], $_GET["game"]) == 0) {
            foreach($game->matches->match as $match) {
                echo '<div class="match1">';
                echo '  <img src="'.$match->team[0]->icon.'" alt=""/>';
                echo '  '.$match->team[0]["name"].' vs. '.$match->team[1]["name"];
                echo '  <img src="'.$match->team[1]->icon.'" alt=""/>';
                if(isset($_COOKIE["user"])) {
                    echo '<br><button class="button2" onclick=loadContent("bet.php?game='.$game["id"].'&match='.$match["id"].'")>Bet</button>';
                }
                echo '</div>';
            }
        }
    }
}
?>
