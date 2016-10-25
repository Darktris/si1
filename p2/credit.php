<!DOCTYPE html>
<div class="title">
    Credit
</div>
<?php
session_start();
if(isset($_SESSION["user"])) {
    $user_path = "users/".$_SESSION["user"];
    if(file_exists($user_path) && is_dir($user_path)) {
        $data = file($user_path."/data.dat");
        if(!$data) {
            echo '<div class="error">Corrupt user.</div>';
        } else {
            if(isset($_GET["option"])) {
                if(strcmp($_GET["option"], "charge") == 0) {
                    if(isset($_GET["1"])) {

                    } else {
                        echo '<div class="text">Charge credit</div>';
                        echo '<form method="post" name="chrgform" onsubmit="return false">';
                        echo '  Amount: <span class="input-euro"><input id="amount" type="number" min="10" max="1000000" step="1" value="1000"></span><br>';
                        echo "  <input type='reset' value='Back' onclick=loadContent()>";
                        echo "  <input type='submit' value='Confirm'>";
                        echo '</div>';
                    }
                } elseif(strcmp($_GET["option"], "withdraw") == 0) {
                    if(isset($_GET["1"])) {

                    } else {
                        echo '<div class="text">Withdraw credit</div>';
                        echo '<form method="post" name="wdrwform" onsubmit="return false">';
                        echo '  Amount: <span class="input-euro"><input id="amount" type="number" min="10" max="'.$data[5].'" step="1" value="10"></span><br>';
                        echo "  <input type='reset' value='Back' onclick=loadContent()>";
                        echo "  <input type='submit' value='Confirm'>";
                        echo '</div>';
                    }
                } else {
                    echo '<div class="error">Invalid option.</div>';
                }
            } else {
                echo '<div class="category">'.$data[5].' €</div>';
                echo '<div class="option" onclick="loadContent(\'credit.php?option=charge\')">';
                echo '  Charge credit';
                echo '</div>';
                echo '<div class="option" onclick="loadContent(\'credit.php?option=withdraw\')">';
                echo '  Withdraw credit';
                echo '</div>';
            }
        }
        unset($data);
    }
    unset($user_path);
}
?>
