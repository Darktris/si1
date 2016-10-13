<!DOCTYPE html>
<!-- vim: set noai ts=4 sw=4: -->
<?php
if(isset($_GET["game"]) && isset($_GET["match"])) {
        $xml = simplexml_load_file("db.xml");
        $match = $xml->xpath('/db/category[@*]/game[@id = "'.$_GET["game"].'"]/matches/match[@id = "'.$_GET["match"].'"]')[0];
    if(isset($_GET["1"]) && isset($_GET["2"])) {
        echo '<div class="title">';
        echo '  You just betted for the following match:';
        echo '</div>';
        echo '<div class="match2">';
        if($_GET["1"] == 0) {
            echo $_GET["2"]." €";
        }
        echo '  <img src="'.$match->team[0]->icon.'" alt=""/>';
        echo '  '.$match->team[0]["name"].' vs. '.$match->team[1]["name"];
        echo '  <img src="'.$match->team[1]->icon.'" alt=""/>';
        if($_GET["1"] == 1) {
            echo $_GET["2"]." €";
        }
        echo '</div>';
    } else {
        echo '<div class="title">';
        echo '  You\'re about to bet for the following match:';
        echo '</div>';
        echo '<div class="match2">';
        echo '  <input type="radio" name="team" value="0" checked></input>';
        echo '  <img src="'.$match->team[0]->icon.'" alt=""/>';
        echo '  '.$match->team[0]["name"].' vs. '.$match->team[1]["name"];
        echo '  <img src="'.$match->team[1]->icon.'" alt=""/>';
        echo '  <input type="radio" name="team" value="1"></input>';
        echo '</div><br><br>';
        echo 'Amount: <input name="amount" type="number" min="1" step="0.05" value="1"> €<br>';
        echo '<button onclick=loadContent(\'bet.php?game='.$_GET["game"].'&match='.$_GET["match"].'\',[\'input[name=team]:checked\',\'input[name=amount]\'])>Confirm</button>';
    }
} else {
    echo '<div class="error">Illegal access.</div>';
}
?>
