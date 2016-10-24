<!DOCTYPE html>
<?php
session_start();
if(isset($_REQUEST["login"])) {
    $user_path = "users/".$_REQUEST["user"];
    if(file_exists($user_path) && is_dir($user_path)) {
        $data = file($user_path."/data.dat");
        if(!$data) {
            $login_error = "Corrupt user.";
        } elseif(strncmp(md5($_REQUEST["pass"]), $data[1], 32) == 0) {
            $_SESSION["user"] = $_REQUEST["user"];
            setcookie("user", $_REQUEST["user"], time() + (2 * 60 * 60));
            unset($login_error);
        } else {
            $login_error = "Invalid user or password.";
        }
        unset($data);
    } else {
        $login_error = "Invalid user or password.";
    }
    unset($user_path);
} elseif(isset($_GET["logout"])) {
    session_unset();
    session_destroy();
    header('Location: /');
} elseif(isset($_POST["bag_remove"]) && isset($_SESSION["bag"])) {
    unset($_SESSION["bag"][strval($_POST["bag_remove"])]);
}
?>
<html>
    <head>
        <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
        <title>BetaBet</title>
        <link rel="stylesheet" type="text/css" href="theme.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="functions.js"></script>
        <?php
        if(isset($_POST["content"])) {
            echo '<script>$(document).ready(loadContent("'.$_POST["content"].'"))</script>';
        } else {
            echo '<script>$(document).ready(loadContent())</script>';
        }
        unset($content);
        ?>
    </head>
    <body>
        <header onclick=loadContent()>
            <img src="images/logo.png" alt="">etaBet
        </header>
        <?php
        if(isset($_SESSION["bag"]) && !empty($_SESSION["bag"])) {
            $total = array_reduce($_SESSION["bag"], function($carry, $item) {
                $carry += $item["amount"];
                return $carry;
            }, 0);
            echo "<div id='bag' onclick=loadContent('checkout.php')>";
            echo '  <span id="baginfo">';
            echo '      <img src="images/bag.png" alt="">Total: '.$total.' €';
            echo '  </span>';
            echo '  <span id="checkout">';
            echo '      Checkout';
            echo '  </span>';
            echo '</div>';
            unset($total);
        }
        ?>
        <div class="dropdown">
            <?php
            if(isset($_SESSION["user"])) {
            ?>
            <button id="account">
                <img src="images/account.png" alt=""/>
            <?php
                echo substr($_SESSION["user"], 0, 10);
            ?>
            </button>
            <div class="dropdown-content">
                <button class="buttonD" onclick=loadContent('credit.php')>Credit</button>
                <button class="buttonD" onclick=loadContent('history.php')>Bet History</button>
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
                    <input type="text" name="user" <?php if(isset($_COOKIE["user"])) echo 'value="'.$_COOKIE["user"].'"';?> required><br>
                    Password:
                    <input type="password" name="pass"required>
            <?php
                if(isset($login_error)) {
                    echo "<div class='error'>".$login_error."</div>";
                    unset($login_error);
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
            if(!isset($_SESSION["user"])) {
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
