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
        if(isset($_SESSION["user"])) {
            $hispath = 'users/'.$_SESSION["user"].'/history.xml';
            $his = simplexml_load_file($hispath);
            $newbet = $his->addChild('bet');
            $newbet->addChild('winner',$_GET["1"]);
            $newbet->addChild('amount',$_GET["2"]);
            $newbet->addChild('game',$game);
            $newmatch = $newbet->addChild('match');
            $newmatch->addAttribute('id',$match["id"]);
            $newmatch->addChild('date',$match->date);
            $team0 = $newmatch->addChild('team');
            $team0->addAttribute('name',$match->team[0]["name"]);
            $team0->addChild('icon',$match->team[0]->icon);
            $team1 = $newmatch->addChild('team');
            $team1->addAttribute('name',$match->team[1]["name"]);
            $team1->addChild('icon',$match->team[1]->icon);
            $his->asXML($hispath);
            unset($hispath);
            unset($his);
            unset($newbet);
            unset($newmatch);
            unset($team0);
            unset($team1);
            echo '<div class="title">';
            echo '  Your bet has been successfully submitted.';
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
            echo '<form method="post" onsubmit="return false">';
            echo "  <button onclick=loadContent()>Back</button>";
            echo '</form>';
        } else {
            echo '<div class="error">You must login before submitting a bet.</div>';
        }
    } else {
        echo '<div class="title">';
        echo '  Please choose a winner and the amount to bet.';
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
        echo '  Amount: <span class="input-euro"><input id="amount" type="number" min="10" max="1000" step="10" value="10" oninput=updateBet()></span><br>';
        echo '  <div class="error" id="amount_error"></div>';
        echo "  <input type='reset' value='Back' onclick=loadContent()>";
        echo "  <input type='submit' value='Confirm' onclick=validateBet('".$_GET["game"]."','".$_GET["match"]."')>";
        echo '</div>';
    }
} else {
    echo '<div class="error">Illegal access.</div>';
}
?>
