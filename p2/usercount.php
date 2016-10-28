<!DOCTYPE html>
<?php
session_start();
if(!isset($_POST["index_token"]) || strcmp($_POST["index_token"], $_SESSION["index_token"]) !== 0) {
    header('Location: '.dirname(strtok($_SERVER["REQUEST_URI"],'?')));
    die;
}
$min = 1;
$max = 1000;
$step = 10;
if(isset($_POST["previous"]) && !empty($_POST["previous"])) {
    $count = min(max(intval($_POST["previous"]) + mt_rand(0, 2*$step) - $step, $min), $max);
} else {
    $count = mt_rand($min, $max);
}
echo '<img src="images/users.png" alt="">'.$count.' users online';
unset($count);
?>
