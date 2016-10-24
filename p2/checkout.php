<!DOCTYPE html>
<!-- vim: set noai ts=4 sw=4: -->
<?php
session_start();
echo '<div class="title">';
echo '  Checkout';
echo '</div>';
if(isset($_SESSION["user"])) {
    if(isset($_SESSION["bag"]) && !empty($_SESSION["bag"])) {
        if(isset($_GET["confirm"]) && strcmp($_GET["confirm"], "true") == 0) {
            $total = array_reduce($_SESSION["bag"], function($carry, $item) {
                $carry += $item["amount"];
                return $carry;
            }, 0);
            $user_path = "users/".$_SESSION["user"];
            if(file_exists($user_path) && is_dir($user_path)) {
                $data = file($user_path."/data.dat");
                if(!$data) {
                    $chckt_error = "Corrupt user.";
                } elseif($total <= $data[5]) {
                    // Cool stuff
                    return;
                } else {
                    $chckt_error = "Not enough credit to checkout.";
                }
                unset($data);
            } else {
                $chckt_error = "Corrupt user.";
            }
            unset($total);
            unset($user_path);
        }
        $his = simplexml_load_file('users/'.$_SESSION["user"].'/history.xml');
        $xml = simplexml_load_file("db.xml");
        foreach($_SESSION["bag"] as $id => $bet) {
            $game = $xml->xpath('/db/category[@*]/game[@id = "'.$bet["game"].'"]')[0];
            $match = $xml->xpath('/db/category[@*]/game[@id = "'.$bet["game"].'"]/matches/match[@id = "'.$id.'"]')[0];
            echo '<form action="/" method="post" id="remove'.$id.'">';
            echo '  <input type="hidden" name="bag_remove" value="'.$id.'">';
            echo '  <input type="hidden" name="content" value="checkout.php">';
            echo '</form>';
            echo '<div class="match" name="checkout">';
            if(strcmp($bet["winner"], "0") == 0) {
                echo '  <div class="side0">'.$bet["amount"].' €</div><div class="arrow0"></div>';
                echo '  <div class="edit" onclick="loadContent(\'bet.php?game='.$game["id"].'&match='.$id.'&edit=true\')"><div class="side0g">Edit</div><div class="arrow0g"></div></div>';
            } else {
                echo '  <div class="remove" onclick="$(\'#remove'.$id.'\').submit()"><div class="side0r">Remove</div><div class="arrow0r"></div></div>';
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
                echo '  <div class="edit" onclick="loadContent(\'bet.php?game='.$game["id"].'&match='.$id.'&edit=true\')"><div class="side1g">Edit</div><div class="arrow1g"></div></div>';
            } else {
                echo '  <div class="remove" onclick="$(\'#remove'.$id.'\').submit()"><div class="side1r">Remove</div><div class="arrow1r"></div></div>';
            }
            echo '</div>';
            unset($game);
            unset($match);
        }
        echo '<form method="post" name="chcktform" onsubmit="return false">';
        if(isset($chckt_error)) {
            echo '  <div class="error">'.$chckt_error.'</div>';
            unset($chckt_error);
        }
        echo "  <input type='reset' value='Back' onclick=loadContent()>";
        echo "  <input type='submit' value='Confirm' onclick=loadContent('checkout.php?confirm=true')>";
        echo '</div>';
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
