<!DOCTYPE html>
<!-- vim: set noai ts=4 sw=4: -->
<script>
    function validateRegister() {
        alert("pene");
        var formok = true;
        var user = document.forms["regform"]["user"].value;
        if(user == null || !"/^[a-zA-Z0-9]*$".test(user)) {
            document.getElementById("user_error").innerHTML = "<div class='error'>Invalid user name.</div><br>";
            formok = false;
        }
        var pass = document.forms["regform"]["pass"].value;
        var passrep = document.forms["regform"]["passrep"].value;
        if(pass == null || pass == "") {
            document.getElementById("pass_error").innerHTML = "<div class='error'>Invalid password.</div><br>";
            formok = false;
        } else if (!passrep.equals(pass)) {
            document.getElementById("pass_error").innerHTML = "<div class='error'>Password fields do not match.</div><br>";
            formok = false;
        }
        var mail = document.forms["regform"]["mail"].value;
        if(mail == null || !"/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/".test(mail)) {
            document.getElementById("pass_error").innerHTML = "<div class='error'>Invalid e-mail address.</div><br>";
            formok = false;
        }
        var card = document.forms["regform"]["card"].value;
        if(card == null || !"/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/".test(card)) {
            document.getElementById("pass_error").innerHTML = "<div class='error'>Invalid credit card number.</div><br>";
            formok = false;
        }
        if(formok) {
            loadContent("register.php?",[$user,$pass,$mail,$card]);
        }
        return false;
    }
</script>
<div class="title">
    Register
</div>
<?php
if(isset($_GET["1"]) && isset($_GET["2"]) && isset($_GET["3"]) && isset($_GET["4"])) {
    /* "1" is user, "2" is pass, "3" is passrep, "4" is mail, "5" is card */
    $user_path = "users/".$_GET["1"];
    mkdir($user_path);
    $data = fopen($user_path."/data.dat", "w");
    fwrite($data, $_GET["1"]."\n".md5($_GET["2"])."\n".$_GET["3"]."\n".$_GET["4"]."\n0");
    fclose($data);
    $his = new SimpleXMLElement("<history></history>");
    $his->asXML($user_path."/history.xml");
    echo 'Register succesful.';
} else {
?>
<form name="regform" onsubmit="return validateRegister()">
    User name:<br>
    <input type="text" name="user" placeholder="User name" autofocus><br>
    <div id="user_error"></div>
    <br>Password:<br>
    <input type="password" name="pass" placeholder="Password"><br>
    <input type="password" name="passrep" placeholder="Repeat password"><br>
    <div id="pass_error"></div>
    <br>E-mail address:<br>
    <input type="email" name="mail" placeholder="E-mail address"><br>
    <div id="mail_error"></div>
    <br>Credit Card No.:<br>
    <input type="text" name="card" placeholder="Credit Card No."><br>
    <div id="card_error"></div>
    <input type="reset" id="clear" value="Clear">
    <input type='submit' id="register" value='Register'>
</form>
<?php
}
?>
