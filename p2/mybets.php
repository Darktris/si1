<!DOCTYPE html>
<!-- vim: set noai ts=4 sw=4: -->
<?php
if(isset($_COOKIE["user"])) {
    echo '<div class="title">';
    echo '  My bets';
    echo '</div>';
    $his = simplexml_load_file('users/'.$_COOKIE["user"].'/history.xml');
    foreach($his->bet as $bet) {
        $match = $bet->match;
        echo '<div class="match">';
        if(strcmp($bet->winner, "0") == 0) {
            echo '  <div class="side0">'.$bet->amount.' €</div><div class="arrow0"></div>';
        }
        echo '  <div class="matchinfo">';
        echo '      <div class="matchdetail">'.date('D, jS F Y @ H:i',strtotime($match->date)).'</div>';
        echo '      <img src="'.$match->team[0]->icon.'" alt=""/>';
        echo '      '.$match->team[0]["name"].' vs. '.$match->team[1]["name"];
        echo '      <img src="'.$match->team[1]->icon.'" alt=""/>';
        echo '      <div class="matchdetail">'.$bet->game.'</div>';
        echo '  </div>';
        if(strcmp($bet->winner, "1") == 0) {
            echo '  <div class="side1">'.$bet->amount.' €</div><div class="arrow1"></div>';
        }
        echo '</div>';
        unset($match);
    }
}
