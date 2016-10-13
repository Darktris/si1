<!DOCTYPE html>
<!-- vim: set noai ts=4 sw=4: -->
<html>
    <script>
        function loadContent(content, queries) {
            if(typeof(content) === 'undefined') {
                document.cookie = "content=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
            } else {
                document.cookie = "content=" + content;
                if(queries) {
                    document.cookie = "queries=" + queries;
                }
            }
            location.reload();
        }
    </script>
    <?php
    if(isset($_REQUEST["login"])) {
        $user_path = "users/".$_REQUEST["user"];
        if(file_exists($user_path) && is_dir($user_path)) {
            $data = file($user_path."/data.dat");
            if(!$data) {
                $login_error = "Login error.";
                return;
            }
            if(strncmp(md5($_REQUEST["pass"]), $data[1], strlen(md5($_REQUEST["pass"]))) == 0) {
                setcookie("user", $_REQUEST["user"], time() + (2 * 60 * 60));
                header('Location: '.$_SERVER['PHP_SELF']);
                die;
            } else {
                $login_error = "Invalid user or password.";
            }
        }
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
                <form method="post" action="/">
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
            <div class="dropdown2">
                <button class="button1"><img src="images/category.png" alt="">MOBA</button>
                <div class="dropdown2-content">
                    <a href="/?filter=lol">
                        <img src="lol/icon.png" alt=""/> League of Legends
                    </a>
                    <a href="/?filter=dota2">
                        <img src="dota2/icon.png" alt=""/> Dota 2
                    </a>
                </div>
            </div>
            <div class="dropdown2">
                <button class="button1"><img src="images/category.png" alt="">FPS</button>
                <div class="dropdown2-content">
                    <a href="/?filter=csgo">
                        <img src="csgo/icon.png" alt=""/> CS:GO
                    </a>
                    <a href="/?filter=overwatch">
                        <img src="overwatch/icon.png" alt=""/> Overwatch
                    </a>
                </div>
            </div>
            <div class="dropdown2">
                <button class="button1"><img src="images/category.png" alt="">Cards</button>
                <div class="dropdown2-content">
                    <a href="/?filter=hearthstone">
                        <img src="hearthstone/icon.png" alt=""/> Hearthstone
                    </a>
                </div>
            </div>
            <div class="links">
                <button class=buttonR onclick=loadContent('register')>Register</button>
            </div>
        </div>
        <div id="scrollable">
            <?php
            if(isset($_COOKIE["content"])) {
                if(isset($_COOKIE["queries"])) {
                    $queries = $_COOKIE["queries"];
                    setcookie("queries", "", time() -1);
                }
                require_once $_COOKIE["content"];
            } else {
                require_once "matches.php";
            } ?>
        </div>
        <footer>
            <img src="http://www.w3.org/Icons/valid-xhtml10-blue" alt="Valid XHTML 1.0!"/>
            <img src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="¡CSS Válido!"/>
        </footer>
    </body>
</html>

