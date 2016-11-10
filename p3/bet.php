<!DOCTYPE html>
<?php
session_start();
if(!isset($_POST["index_token"]) || strcmp($_POST["index_token"], $_SESSION["index_token"]) !== 0) {
    header('Location: '.dirname(strtok($_SERVER["REQUEST_URI"],'?')));
    die;
}
?>
<div class="title">
    Bet Details
</div>
<?php
if(isset($_GET["game"]) && isset($_GET["match"])) {
    $xml = simplexml_load_file("db.xml");
    $game = $xml->xpath('/db/category[@*]/game[@id = "'.$_GET["game"].'"]')[0];
    $match = $xml->xpath('/db/category[@*]/game[@id = "'.$_GET["game"].'"]/matches/match[@id = "'.$_GET["match"].'"]')[0];
    unset($xml);
    $edit = isset($_GET["edit"]) && (strcmp($_GET["edit"], "true") == 0);
    if(isset($_POST["1"]) && isset($_POST["2"])) {
        /* "1" is left/right team, "2" is amount */
        if(!isset($_SESSION["bag"])) {
            $_SESSION["bag"] = array();
        }
        $_SESSION["bag"][strval($match["id"])] = array(
            "game" => strval($game["id"]),
            "winner" => $_POST["1"],
            "amount" => $_POST["2"]
        );
        echo '<div class="text">';
        echo '  The bet below will be placed in your shopping bag.';
        echo '</div>';
        echo '<div class="match">';
        if(strcmp($_POST["1"], "0") == 0) {
            echo '  <div class="side0">'.$_POST["2"].' €</div><div class="arrow0"></div>';
        }
        echo '  <div class="matchinfo">';
        echo '      <div class="matchdetail">'.date('D, jS F Y @ H:i',strtotime($match->date)).'</div>';
        echo '      <img src="'.$match->team[0]->icon.'" alt=""/>';
        echo '      '.$match->team[0]["name"].' vs. '.$match->team[1]["name"];
        echo '      <img src="'.$match->team[1]->icon.'" alt=""/>';
        echo '      <div class="matchdetail">'.$game["name"].'</div>';
        echo '  </div>';
        if(strcmp($_POST["1"], "1") == 0) {
            echo '  <div class="side1">'.$_POST["2"].' €</div><div class="arrow1"></div>';
        }
        echo '</div>';
        echo '<form method="post">';
        if($edit) {
            echo '  <input type="hidden" name="content" value="checkout.php">';
        }
        echo '  <button type="submit">Confirm</button>';
        echo '</form>';
    } else {
        if($edit && isset($_SESSION["bag"]) &&
            array_key_exists(strval($match["id"]), $_SESSION["bag"])) {
            $bet = $_SESSION["bag"][strval($match["id"])];
        }
        echo '<div class="text">';
        echo '  Please choose a winner and the amount to bet.';
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
        echo '      <div class="matchdetail">'.$game["name"].'</div>';
        echo '  </div>';
        if(isset($bet) && strcmp($bet["winner"], "1") == 0) {
            echo '<div class="side1" id="wnnrside">'.$bet["amount"].' €</div><div class="arrow1" id="wnnrarrow"></div>';
        }
        echo '</div>';
        echo '<form method="post" name="betform" onsubmit="return false">';
        echo '  Amount: <span class="input-euro"><input id="amount" type="number" min="10" max="1000" step="1" value="'.(isset($bet)? $bet["amount"] : "10").'" oninput=updateBet()></span><br>';
        echo '  <div class="error" id="amount_error"></div>';
        echo '  <input type="reset" value="Back" onclick="loadContent()">';
        echo '  <input type="submit" value="Confirm" onclick="validateBet(\''.$game["id"].'\',\''.$match["id"].'\',\''.($edit? "true" : "false").'\')">';
        echo '</div><br>';
    }
} else {
    echo '<div class="error">Illegal access.</div>';
}
?>
