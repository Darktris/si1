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
        echo '  Your bet has been added to the shopping bag';
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
        echo '  <button type="submit">Back</button>';
        echo '</form>';
    } else {
        echo '<div class="title">';
        echo '  Please choose a winner and the amount to bet';
        echo '</div>';
        echo '<div class="match" id="match">';
        echo '  <div class="side0" id="wnnrside">10 €</div><div class="arrow0" id="wnnrarrow"></div>';
        echo '  <div class="matchinfo">';
        echo '      <div class="matchdetail">'.date('D, jS F Y @ H:i',strtotime($match->date)).'</div>';
        echo "      <input type='radio' name='team' value='0' checked onclick=updateBet('0')>";
        echo '      <img src="'.$match->team[0]->icon.'" alt=""/>';
        echo '      '.$match->team[0]["name"].' vs. '.$match->team[1]["name"];
        echo '      <img src="'.$match->team[1]->icon.'" alt=""/>';
        echo "      <input type='radio' name='team' value='1' onclick=updateBet('1')>";
        echo '      <div class="matchdetail">'.$game.'</div>';
        echo '  </div>';
        echo '</div>';
        echo '<form method="post" name="betform" onsubmit="return false">';
        echo '  Amount: <span class="input-euro"><input id="amount" type="number" min="10" max="1000" value="10" oninput=updateBet()></span><br>';
        echo '  <div class="error" id="amount_error"></div>';
        echo "  <input type='reset' value='Back' onclick=loadContent()>";
        echo "  <input type='submit' value='Confirm' onclick=validateBet('".$_GET["game"]."','".$_GET["match"]."')>";
        echo '</div>';
    }
} else {
    echo '<div class="error">Illegal access.</div>';
}
?>
