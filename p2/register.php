<!DOCTYPE html>
<!-- vim: set noai ts=4 sw=4: -->
<div class="title">
    Register
</div>
<?php
if(isset($_GET["1"]) && isset($_GET["2"]) && isset($_GET["3"]) && isset($_GET["4"])) {
    /* "1" is user, "2" is pass, "3" is mail, "4" is card */
    $user_path = "users/".$_GET["1"];
    if(!file_exists($user_path)) {
        mkdir($user_path);
        $data = fopen($user_path."/data.dat", "w");
        fwrite($data, $_GET["1"]."\n".md5($_GET["2"])."\n".$_GET["3"]."\n".$_GET["4"]."\n0");
        fclose($data);
        unset($data);
        $his = new SimpleXMLElement("<history></history>");
        $his->asXML($user_path."/history.xml");
        unset($his);
        unset($user_path);
        echo 'Register succesful.';
        return;
    } else {
        $uexists = $_GET["1"];
    }
}
?>
<form method="post" name="regform" onsubmit="return false">
    User name:<br>
    <input type="text" id="user" placeholder="User name" autofocus><br>
    <div class="error" id="user_error"><?php if(isset($uexists)) echo 'User '.$uexists.' already exists.';?></div>
    <br>Password:<br>
    <input type="password" id="pass" placeholder="Password" onkeyup="passwordStrength(this.value)"><br>
    <input type="password" id="passrep" placeholder="Repeat password"><br>
    <div id="strength" class="strength0">Password Strength</div>
    <div class="error" id="pass_error"></div>
    <br>E-mail address:<br>
    <input type="email" id="mail" placeholder="E-mail address"><br>
    <div class="error" id="mail_error"></div>
    <br>Credit Card No.:<br>
    <input type="text" id="card" placeholder="Credit Card No."><br>
    <div class="error" id="card_error"></div>
    <input type="reset" id="clear" value="Clear">
    <input type="submit" id="register" value="Register" onclick="validateRegister()">
</form>
