<!DOCTYPE html>
<!-- vim: set noai ts=4 sw=4: -->
<?php
$xml = simplexml_load_file("db.xml");
echo '<div class="category">Unsettled Matches</div>';
foreach($xml->category as $category) {
    foreach($category->game as $game) {
        if(empty($_GET["game"]) || isset($_GET["game"]) && strcmp($game["id"], $_GET["game"]) == 0) {
            foreach($game->matches->match as $match) {
                if(!isset($match->result)) {
                    echo '<div class="match" onclick=loadContent("bet.php?game='.$game["id"].'&match='.$match["id"].'")>';
                    echo '  <div class="matchinfo">';
                    echo '      <div class="matchdetail">'.$match->date.'</div>';
                    echo '      <img src="'.$match->team[0]->icon.'" alt=""/>';
                    echo '      '.$match->team[0]["name"].' vs. '.$match->team[1]["name"];
                    echo '      <img src="'.$match->team[1]->icon.'" alt=""/>';
                    echo '      <div class="matchdetail">'.$game["name"].'</div>';
                    echo '  </div>';
                    echo '</div>';
                }
            }
        }
    }
}
echo '<div class="category">Settled Matches</div>';
foreach($xml->category as $category) {
    foreach($category->game as $game) {
        if(empty($_GET["game"]) || isset($_GET["game"]) && strcmp($game["id"], $_GET["game"]) == 0) {
            foreach($game->matches->match as $match) {
                if(isset($match->result)) {
                    echo '<div class="match" onclick=loadContent("bet.php?game='.$game["id"].'&match='.$match["id"].'")>';
                    if(strcmp($match->result, "0") == 0) {
                        echo '  <div class="side0g">'.$match->team[0]->score.'</div><div class="arrow0g"></div>';
                    } elseif(strcmp($match->result, "1") == 0) {
                        echo '  <div class="side0r">'.$match->team[0]->score.'</div><div class="arrow0r"></div>';
                    } elseif(strcmp($match->result, "tie") == 0) {
                        echo '  <div class="side0">'.$match->team[0]->score.'</div><div class="arrow0"></div>';
                    }
                    echo '  <div class="matchinfo">';
                    echo '      <div class="matchdetail">'.$match->date.'</div>';
                    echo '      <img src="'.$match->team[0]->icon.'" alt=""/>';
                    echo '      '.$match->team[0]["name"].' vs. '.$match->team[1]["name"];
                    echo '      <img src="'.$match->team[1]->icon.'" alt=""/>';
                    echo '      <div class="matchdetail">'.$game["name"].'</div>';
                    echo '  </div>';
                    if(strcmp($match->result, "0") == 0) {
                        echo '  <div class="side1r">'.$match->team[1]->score.'</div><div class="arrow1r"></div>';
                    } elseif(strcmp($match->result, "1") == 0) {
                        echo '  <div class="side1g">'.$match->team[1]->score.'</div><div class="arrow1g"></div>';
                    } elseif(strcmp($match->result, "tie") == 0) {
                        echo '  <div class="side1">'.$match->team[1]->score.'</div><div class="arrow1"></div>';
                    }
                    echo '</div>';
                }
            }
        }
    }
}
?>
