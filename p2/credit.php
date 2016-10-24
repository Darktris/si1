<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user"])) {
    $user_path = "users/".$_SESSION["user"];
    if(file_exists($user_path) && is_dir($user_path)) {
        $data = file($user_path."/data.dat");
        if(!$data) {
            echo '<div class="error">Corrupt user.</div>';
        } else {
            echo '<div class="title">Current credit:</div>';
            echo '<div class="category">'.$data[5].' €</div>';
            echo '<div class="option">';
            echo '  Charge credit';
            echo '</div>';
            echo '<div class="option">';
            echo '  Withdraw credit';
            echo '</div>';
        }
        unset($data);
    }
    unset($user_path);
}
?>
