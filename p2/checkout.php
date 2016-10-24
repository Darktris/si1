<!DOCTYPE html>
<!-- vim: set noai ts=4 sw=4: -->
<?php
session_start();
echo '<div class="title">';
echo '  Checkout';
echo '</div>';
if(isset($_SESSION["user"])) {
    if(isset($_SESSION["bag"])) {
        $xml = simplexml_load_file("db.xml");
        foreach($xml->category as $category) {
            foreach($category->game as $game) {
                foreach($game->matches->match as $match) {
                    if(array_key_exists(strval($match["id"]), $_SESSION["bag"])) {
                        $bet = $_SESSION["bag"][strval($match["id"])];
                        echo '<div class="match" name="checkout">';
                        if(strcmp($bet["winner"], "0") == 0) {
                            echo '  <div class="side0">'.$bet["amount"].' €</div><div class="arrow0"></div>';
                            echo '  <div class="edit" onclick="loadContent(\'bet.php?game='.$game["id"].'&match='.$match["id"].'&edit=true\')"><div class="side0g">Edit</div><div class="arrow0g"></div></div>';
                        } else {
                            echo '  <div class="remove" onclick="loadContent(\'checkout.php?remove='.$match["id"].'\')"><div class="side0r">Remove</div><div class="arrow0r"></div></div>';
                        }
                        echo '  <div class="matchinfo">';
                        echo '      <div class="matchdetail">'.date('D, jS F Y @ H:i',strtotime($match->date)).'</div>';
                        echo '      <img src="'.$match->team[0]->icon.'" alt=""/>';
                        echo '      '.$match->team[0]["name"].' vs. '.$match->team[1]["name"];
                        echo '      <img src="'.$match->team[1]->icon.'" alt=""/>';
                        echo '      <div class="matchdetail">'.$game["name"].'</div>';
                        echo '  </div>';
                        if(strcmp($bet["winner"], "1") == 0) {
                            echo '  <div class="side1">'.$bet["amount"].' €</div><div class="arrow1"></div>';
                            echo '  <div class="edit" onclick="loadContent(\'bet.php?game='.$game["id"].'&match='.$match["id"].'&edit=true\')"><div class="side1g">Edit</div><div class="arrow1g"></div></div>';
                        } else {
                            echo '  <div class="remove" onclick="loadContent(\'checkout.php?remove='.$match["id"].'\')"><div class="side1r">Remove</div><div class="arrow1r"></div></div>';
                        }
                        echo '</div>';
                        unset($bet);
                    }
                }
            }
        }
    } else {
        echo '<div class="text">';
        echo '  Your bag is empty.';
        echo '</div>';
        echo '<form method="post" action="">';
        echo '  <button type="submit">Back</button>';
        echo '</form>';
    }
} else {
    echo '<div class="text">';
    echo '  You must login before proceeding to checkout';
    echo '</div>';
    echo '<form method="post" action="">';
    echo '  <button type="submit">Back</button>';
    echo '</form>';
}
