<!DOCTYPE html>
<!-- vim: set noai ts=4 sw=4: -->
<div class="title">
    You're about to bet for the following match:
</div>
<div class="match2">
    <img src="games/lol/teams/clg.png" alt=""/>
    CLG vs. G2
    <img src="games/lol/teams/g2.png" alt=""/>
</div>
<?php
if(isset($value)) {
    echo $value;
} else {
?>
<form method="post" name="bet" onsubmit=loadContent('bet.php',,'amount')>
    Amount: <input id="amount" type="number" min="1" step="0.05" value="1"> € <br>
    <input type="submit" value="Confirm">
</form>
<?php
}
?>
