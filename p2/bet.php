<!DOCTYPE html>
<!-- vim: set noai ts=4 sw=4: -->
<?php
session_start();
if(isset($_GET["game"]) && isset($_GET["match"])) {
    $xml = simplexml_load_file("db.xml");
    $game = $xml->xpath('/db/category[@*]/game[@id = "'.$_GET["game"].'"]')[0]["name"];
    $match = $xml->xpath('/db/category[@*]/game[@id = "'.$_GET["game"].'"]/matches/match[@id = "'.$_GET["match"].'"]')[0];
    unset($xml);
    if(isset($_GET["1"]) && isset($_GET["2"])) {
        /* "1" is left/right team, "2" is amount */
        if(!isset($_SESSION["bag"])) {
            $_SESSION["bag"] = array();
        }
        $_SESSION["bag"][strval($match["id"])] = array(
            "winner" => $_GET["1"],
            "amount" => $_GET["2"]
        );
        echo '<div class="title">';
        echo '  The bet below will be added to your shopping bag';
        echo '</div>';
        echo '<div class="match">';
        if(strcmp($_GET["1"], "0") == 0) {
            echo '  <div class="side0">'.$_GET["2"].' €</div><div class="arrow0"></div>';
        }
        echo '  <div class="matchinfo">';
        echo '      <div class="matchdetail">'.date('D, jS F Y @ H:i',strtotime($match->date)).'</div>';
        echo '      <img src="'.$match->team[0]->icon.'" alt=""/>';
        echo '      '.$match->team[0]["name"].' vs. '.$match->team[1]["name"];
        echo '      <img src="'.$match->team[1]->icon.'" alt=""/>';
        echo '      <div class="matchdetail">'.$game.'</div>';
        echo '  </div>';
        if(strcmp($_GET["1"], "1") == 0) {
            echo '  <div class="side1">'.$_GET["2"].' €</div><div class="arrow1"></div>';
        }
        echo '</div>';
        echo '<form method="post" action="">';
        echo '  <button type="submit">OK</button>';
        echo '</form>';
    } else {
        if(isset($_GET["edit"]) &&
            strcmp($_GET["edit"], "true") == 0 &&
            isset($_SESSION["bag"]) &&
            array_key_exists(strval($match["id"]), $_SESSION["bag"])) {
            $bet = $_SESSION["bag"][strval($match["id"])];
        }
        echo '<div class="title">';
        echo '  Please choose a winner and the amount to bet';
        echo '</div>';
        echo '<div class="match" id="match">';
        if(isset($bet)) {
            if(strcmp($bet["winner"], "0") == 0) {
                echo '<div class="side0" id="wnnrside">'.$bet["amount"].' €</div><div class="arrow0" id="wnnrarrow"></div>';
            }
        } else {
            echo '<div class="side0" id="wnnrside">10 €</div><div class="arrow0" id="wnnrarrow"></div>';
        }
        echo '  <div class="matchinfo">';
        echo '      <div class="matchdetail">'.date('D, jS F Y @ H:i',strtotime($match->date)).'</div>';
        echo '      <input type="radio" name="team" value="0" '.(!isset($bet) || strcmp($bet["winner"], "0") == 0? "checked " : "").'onclick="updateBet(\'0\')">';
        echo '      <img src="'.$match->team[0]->icon.'" alt=""/>';
        echo '      '.$match->team[0]["name"].' vs. '.$match->team[1]["name"];
        echo '      <img src="'.$match->team[1]->icon.'" alt=""/>';
        echo '      <input type="radio" name="team" value="1" '.(isset($bet) && strcmp($bet["winner"], "1") == 0? "checked " : "").'onclick="updateBet(\'1\')">';
        echo '      <div class="matchdetail">'.$game.'</div>';
        echo '  </div>';
        if(isset($bet) && strcmp($bet["winner"], "1") == 0) {
            echo '<div class="side1" id="wnnrside">'.$bet["amount"].' €</div><div class="arrow1" id="wnnrarrow"></div>';
        }
        echo '</div>';
        echo '<form method="post" name="betform" onsubmit="return false">';
        echo '  Amount: <span class="input-euro"><input id="amount" type="number" min="10" max="1000" value="'.(isset($bet)? $bet["amount"] : "10").'" oninput=updateBet()></span><br>';
        echo '  <div class="error" id="amount_error"></div>';
        echo "  <input type='reset' value='Back' onclick=loadContent()>";
        echo "  <input type='submit' value='Confirm' onclick=validateBet('".$_GET["game"]."','".$_GET["match"]."')>";
        echo '</div>';
    }
} else {
    echo '<div class="error">Illegal access.</div>';
}
?>
