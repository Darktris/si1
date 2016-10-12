<!DOCTYPE html>
<!-- vim: set noai ts=4 sw=4: -->
<html>
    <?php
    if(isset($_REQUEST["login"])) {
        $user_path = "users/".$_REQUEST["user"];
        if(file_exists($user_path) && is_dir($user_path)) {
            $data = file($user_path."/data.dat");
            if(!$data) {
                $login_error = "Login error.";
                unset($_REQUEST["user"]);
                return;
            }
            if(strncmp(md5($_REQUEST["pass"]), $data[1], strlen(md5($_REQUEST["pass"]))) == 0) {
                setcookie("user", $_REQUEST["user"], time() + (2 * 60 * 60));
            } else {
                $login_error = "Invalid user or password.";
                unset($_REQUEST["user"]);
            }
        }
    } elseif(isset($_COOKIE["user"])) {
        $_REQUEST["user"] = $_COOKIE["user"];
    }
    if(isset($_GET["logout"])) {
        unset($_REQUEST["user"]);
        setcookie("user", $_REQUEST["user"], time() - 1);
    }
    ?>
	<head>
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<title>BetaBet</title>
		<link rel="stylesheet" type="text/css" href="theme.css">
	</head>
	<body>
		<a href="/">
			<header>
				BetaBet
			</header>
		</a>
        <div class="dropdown">
            <?php
            if(isset($_REQUEST["user"])) {
            ?>
            <button id="account">
                <img src="images/account.png" alt=""/>
            <?php
                echo substr($_REQUEST["user"], 0, 10);
            ?>
            </button>
            <div class="dropdown-content">
                <a href="mywallet.html">My Wallet</a>
                <a href="mybets.html">My Bets</a>
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
                <a href="login.php"><div class="buttonL">Login</div></a><a href="register.php"><div class="buttonR">Register</div></a>
            </div>
		</div>
		<div id="scrollable">
			<div class="category">Last Matches</div>
			<div class="match1">
				<img src="lol/teams/g2.png" alt=""/>
				G2 vs. CLG
				<img src="lol/teams/clg.png" alt=""/>
			</div>
			<div class="category">Upcoming Matches</div>
			<div class="match2">
				<a href="bet.html?match=1&team=left"><div class="button2"><div>Bet</div></div></a>
				<img src="lol/teams/clg.png" alt=""/>
				CLG vs. G2
				<img src="lol/teams/g2.png" alt=""/>
				<a href="bet.html?match=1&team=right"><div class="button2"><div>Bet</div></div></a>
			</div>
			<div class="match2">
				<a href="bet.html?match=2&team=left"><div class="button2"><div>Bet</div></div></a>
				<img src="lol/teams/g2.png" alt=""/>
				G2 vs. CLG
				<img src="lol/teams/clg.png" alt=""/>
				<a href="bet.html?match=2&team=right"><div class="button2"><div>Bet</div></div></a>
			</div>
			<div class="match2">
				<a href="bet.html?match=3&team=left"><div class="button2"><div>Bet</div></div></a>
				<img src="lol/teams/clg.png" alt=""/>
				CLG vs. G2
				<img src="lol/teams/g2.png" alt=""/>
				<a href="bet.html?match=3&team=right"><div class="button2"><div>Bet</div></div></a>
			</div>
			<div class="match2">
				<a href="bet.html?match=4&team=left"><div class="button2"><div>Bet</div></div></a>
				<img src="lol/teams/g2.png" alt=""/>
				G2 vs. CLG
				<img src="lol/teams/clg.png" alt=""/>
				<a href="bet.html?match=4&team=right"><div class="button2"><div>Bet</div></div></a>
			</div>
			<div class="match2">
				<a href="bet.html?match=5&team=left"><div class="button2"><div>Bet</div></div></a>
				<img src="lol/teams/clg.png" alt=""/>
				CLG vs. G2
				<img src="lol/teams/g2.png" alt=""/>
				<a href="bet.html?match=5&team=right"><div class="button2"><div>Bet</div></div></a>
			</div>
			<div class="match2">
				<a href="bet.html?match=6&team=left"><div class="button2"><div>Bet</div></div></a>
				<img src="lol/teams/g2.png" alt=""/>
				G2 vs. CLG
				<img src="lol/teams/clg.png" alt=""/>
				<a href="bet.html?match=6&team=right"><div class="button2"><div>Bet</div></div></a>
			</div>
			<div class="match2">
				<a href="bet.html?match=7&team=left"><div class="button2"><div>Bet</div></div></a>
				<img src="lol/teams/clg.png" alt=""/>
				CLG vs. G2
				<img src="lol/teams/g2.png" alt=""/>
				<a href="bet.html?match=7&team=right"><div class="button2"><div>Bet</div></div></a>
			</div>
			<div class="match2">
				<a href="bet.html?match=8&team=left"><div class="button2"><div>Bet</div></div></a>
				<img src="lol/teams/g2.png" alt=""/>
				G2 vs. CLG
				<img src="lol/teams/clg.png" alt=""/>
				<a href="bet.html?match=8&team=right"><div class="button2"><div>Bet</div></div></a>
			</div>
			<div class="match2">
				<a href="bet.html?match=9&team=left"><div class="button2"><div>Bet</div></div></a>
				<img src="lol/teams/clg.png" alt=""/>
				CLG vs. G2
				<img src="lol/teams/g2.png" alt=""/>
				<a href="bet.html?match=9&team=right"><div class="button2"><div>Bet</div></div></a>
			</div>
		</div>
        <footer>
            <img src="http://www.w3.org/Icons/valid-xhtml10-blue" alt="Valid XHTML 1.0!"/>
            <img src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="¡CSS Válido!"/>
        </footer>
	</body>
</html>

