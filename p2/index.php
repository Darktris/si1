<!DOCTYPE html>
<!-- vim: set noai ts=4 sw=4: -->
<?php
if(isset($_REQUEST["login"])) {
    $user_path = "users/".$_REQUEST["user"];
    if(file_exists($user_path) && is_dir($user_path)) {
        $data = file($user_path."/data.dat");
        unset($user_path);
        if(!$data) {
            unset($data);
            $login_error = "Corrupt user.";
            return;
        }
        if(strncmp(md5($_REQUEST["pass"]), $data[1], 32) == 0) {
            setcookie("user", $_REQUEST["user"], time() + (2 * 60 * 60));
            unset($data);
            unset($login_error);
            header('Location: /');
            die;
        }
    }
    unset($user_path);
    $login_error = "Invalid user or password.";
} elseif(isset($_GET["logout"])) {
    setcookie("user", "", time() - 1);
    header('Location: /');
    die;
}
?>
<html>
    <head>
        <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
        <title>BetaBet</title>
        <link rel="stylesheet" type="text/css" href="theme.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="functions.js"></script>
        <script>$(document).ready(loadContent())</script>
    </head>
    <body>
        <header onclick=loadContent()>
            <img src="images/logo.png" alt="">etaBet
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
                <form method="post" action="/">
                    User name:
                    <input type="text" name="user" required><br>
                    Password:
                    <input type="password" name="pass"required>
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
            <input type="search" id="search" placeholder="Search..." autocomplete="off" oninput=loadContent('matches.php?',['#search'])>
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
            unset($xml);
            if(!isset($_COOKIE["user"])) {
            ?>
            <div class="links">
                <button class=buttonR onclick=loadContent('register.php')>Register</button>
            </div>
            <?php
            }
            ?>
        </div>
        <div id="scrollable">
        </div>
        <footer>
            <img src="http://www.w3.org/Icons/valid-xhtml10-blue" alt="Valid XHTML 1.0!"/>
            <img src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="¡CSS Válido!"/>
        </footer>
    </body>
</html>
