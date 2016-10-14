<!DOCTYPE html>
<!-- vim: set noai ts=4 sw=4: -->
<html>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>
        function loadContent(page, array) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("scrollable").innerHTML = this.responseText;
                }
            };
            if(array) {
                if(page.indexOf("?") == -1) {
                    page += "?";
                }
                var count=0;
                array.forEach(function(entry) {
                    count++;
                    page += "&" + count + "=" + $(entry).val();
                });
            }
            xhttp.open("GET", typeof(page) === 'undefined'? "matches.php" : page, true);
            xhttp.send();
        }
        function validateRegister() {
            var regex;
            var formok = true;
            var user = document.forms["regform"]["user"].value;
            regex = /^[a-zA-Z0-9]*$/;
            if(user == null || user == '' || !regex.test(user)) {
                document.getElementById("user_error").innerHTML = "Invalid user name.";
                formok = false;
            } else {
                document.getElementById("user_error").innerHTML = "";
            }
            var pass = document.forms["regform"]["pass"].value;
            var passrep = document.forms["regform"]["passrep"].value;
            if(pass == null || pass == '') {
                document.getElementById("pass_error").innerHTML = "Invalid password.";
                formok = false;
            } else if (passrep == null || passrep == '' || passrep != pass) {
                document.getElementById("pass_error").innerHTML = "Password fields do not match.";
                formok = false;
            } else {
                document.getElementById("pass_error").innerHTML = "";
            }
            var mail = document.forms["regform"]["mail"].value;
            regex = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
            if(mail == null || mail == '' || !regex.test(mail)) {
                document.getElementById("mail_error").innerHTML = "Invalid e-mail address.";
                formok = false;
            } else {
                document.getElementById("mail_error").innerHTML = "";
            }
            var card = document.forms["regform"]["card"].value;
            regex = /^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/;
            if(card == null || card == '' || !regex.test(card)) {
                document.getElementById("card_error").innerHTML = "Invalid credit card number.";
                formok = false;
            } else {
                document.getElementById("card_error").innerHTML = "";
            }
            if(formok) {
                loadContent("register.php?",['#user','#pass','#mail','#card']);
            }
        }
        $(document).ready(loadContent());
    </script>
    <?php
    if(isset($_REQUEST["login"])) {
        $user_path = "users/".$_REQUEST["user"];
        if(file_exists($user_path) && is_dir($user_path)) {
            $data = file($user_path."/data.dat");
            if(!$data) {
                $login_error = "Corrupt user.";
                return;
            }
            if(strncmp(md5($_REQUEST["pass"]), $data[1], 32) == 0) {
                setcookie("user", $_REQUEST["user"], time() + (2 * 60 * 60));
                if(isset($login_error)) {
                    unset($login_error);
                }
                header('Location: '.$_SERVER['PHP_SELF']);
                die;
            }
        }
        $login_error = "Invalid user or password.";
    } elseif(isset($_GET["logout"])) {
        setcookie("user", "", time() - 1);
        header('Location: '.$_SERVER['PHP_SELF']);
        die;
    }
    ?>
    <head>
        <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
        <title>BetaBet</title>
        <link rel="stylesheet" type="text/css" href="theme.css">
    </head>
    <body>
        <header onclick=loadContent()>
            BetaBet
        </header>
        <div class="dropdown">
            <?php
            if(isset($_COOKIE["user"])) {
            ?>
            <button id="account">
                <img src="images/account.png" alt=""/>
            <?php
                echo substr($_COOKIE["user"], 0, 10);
            ?>
            </button>
            <div class="dropdown-content">
                <button class="buttonD" onclick=loadContent('mywallet.php')>My Wallet</button>
                <button class="buttonD" onclick=loadContent('mybets.php')>My Bets</button>
                <a href="/?logout=">Logout</a>
            </div>
            <?php
            } else {
            ?>
            <button id="account">
                Login
            </button>
            <div class="dropdown-content">
                <form method="post" action="index.php">
                    User name:
                    <input type="text" name="user" autofocus required></input><br>
                    Password:
                    <input type="password" name="pass"required></input>
            <?php
            if(isset($login_error)) {
                echo "<div class='error'>".$login_error."</div>";
            }
            ?>
                    <input type="submit" name="login" value="Login">
                    <input type="reset" value="Clear">
                </form>
            </div>
            <?php
            }
            ?>
        </div>
        <div id="sidebar">
            <form>
                <input type="search" name="search" placeholder="Search...">
            </form>
            <?php
            $xml = simplexml_load_file("db.xml");
            foreach($xml->category as $category) {
                echo '<div class="dropdown2">';
                echo '    <button class="button1"><img src="images/category.png" alt="">'.$category["name"].'</button>';
                echo '    <div class="dropdown2-content">';
                foreach($category->game as $game) {
                    echo '        <button class="button3" onclick=loadContent("matches.php?game='.$game["id"].'")>'.'<img src="'.$game->icon.'" alt="">'.$game["name"].'</button>';
                }
                echo '    </div>';
                echo '</div>';
            }
            ?>
            <div class="links">
                <button class=buttonR onclick=loadContent('register.php')>Register</button>
            </div>
        </div>
        <div id="scrollable">
        </div>
        <footer>
            <img src="http://www.w3.org/Icons/valid-xhtml10-blue" alt="Valid XHTML 1.0!"/>
            <img src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="¡CSS Válido!"/>
        </footer>
    </body>
</html>
