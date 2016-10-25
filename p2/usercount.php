<!DOCTYPE html>
<?php
$min = 1;
$max = 1000;
$step = 10;
if(isset($_GET["previous"]) && !empty($_GET["previous"])) {
    $count = min(max(intval($_GET["previous"]) + mt_rand(0, 2*$step) - $step, $min), $max);
} else {
    $count = mt_rand($min, $max);
}
echo '<img src="images/users.png" alt="">'.$count.' users online';
unset($count);
?>
