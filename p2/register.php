<!DOCTYPE html>
<!-- vim: set noai ts=4 sw=4: -->
<html>
	<head>
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<title>BetaBet</title>
		<link rel="stylesheet" type="text/css" href="theme.css">
	</head>
	<body>
		<a href="index.html">
			<header>
				BetaBet
			</header>
		</a>
        <div class="dropdown">
            <button id="account">
                <img src="images/account.png" alt=""/>
                My Account
            </button>
            <div class="dropdown-content">
                <a href="mywallet.html">My Wallet</a>
                <a href="mybets.html">My Bets</a>
                <a href="#">Logout</a>
            </div>
        </div>
		<div id="sidebar">
			<form>
				<input type="search" name="search" placeholder="Search...">
			</form>
            <div class="dropdown2">
                <button class="button1"><img src="images/category.png" alt="">MOBA</button>
                <div class="dropdown2-content">
                    <a href="index.html?filter=lol">
                        <img src="lol/icon.png" alt=""/> League of Legends
                    </a>
                    <a href="index.html?filter=dota2">
                        <img src="dota2/icon.png" alt=""/> Dota 2
                    </a>
                </div>
            </div>
            <div class="dropdown2">
                <button class="button1"><img src="images/category.png" alt="">FPS</button>
                <div class="dropdown2-content">
                    <a href="index.html?filter=csgo">
                        <img src="csgo/icon.png" alt=""/> CS:GO
                    </a>
                    <a href="index.html?filter=overwatch">
                        <img src="overwatch/icon.png" alt=""/> Overwatch
                    </a>
                </div>
            </div>
            <div class="dropdown2">
                <button class="button1"><img src="images/category.png" alt="">Cards</button>
                <div class="dropdown2-content">
                    <a href="index.html?filter=hearthstone">
                        <img src="hearthstone/icon.png" alt=""/> Hearthstone
                    </a>
                </div>
            </div>
            <div class="links">
                <a href="login.php"><div class="buttonL">Login</div></a><a href="register.php"><div class="buttonR">Register</div></a>
            </div>
		</div>
		<div id="scrollable">
			<div class="title">
                Register
			</div>
                <?php
                if(!isset($_REQUEST["register"]))
                {?>
                <form method="post" action="register.php">
                    User name:<br>
                    <input type="text" name="user" placeholder="User name" required autofocus><br><br>
                    Password:<br>
                    <input type="password" name="pass" class="form-control input-lg" placeholder="Password" required><br>
                    <div class="pwstrength_viewport_progress"></div>
                    <input type="password" name="passrep" placeholder="Repeat password" required><br><br>
                    E-mail address:<br>
                    <input type="email" name="mail" placeholder="E-mail address" required><br><br>
                    Credit Card No.:<br>
                    <input type="text" name="card" placeholder="Credit Card No." pattern="[0-9]{16}" title="Valid Credit Card number" required><br><br>
                    <input type="submit" name="register" value="Register">
                    <input type="reset" value="Clear">
                </form>
                <?php }
                else {
                    $user_path = "users/".$_REQUEST["user"];
                    if(file_exists($user_path) && is_dir($user_path)) {
                ?>
                <form method="post" action="register.php">
                    User name:<br>
                    <input type="text" name="user" placeholder="User name" required autofocus><div class="error">The user <?php echo $_REQUEST["user"] ?> already exists.</div><br>
                    Password:<br>
                    <input type="password" name="pass" class="form-control input-lg" placeholder="Password" required><br>
                    <div class="pwstrength_viewport_progress"></div>
                    <input type="password" name="passrep" placeholder="Repeat password" required><br><br>
                    E-mail address:<br>
                    <input type="email" name="mail" placeholder="E-mail address" required><br><br>
                    Credit Card No.:<br>
                    <input type="text" name="card" placeholder="Credit Card No." pattern="[0-9]{16}" title="Valid Credit Card number" required><br><br>
                    <input type="submit" name="register" value="Register">
                    <input type="reset" value="Clear">
                </form>
                <?php
                }
                else {
                    if(!mkdir($user_path)) {
                ?>
                <div class="error">There was an error creating a new user.</div>
                <?php
                        return;
                    }
                    $data = fopen($user_path."/data.dat", "w");
                    if(!$data) {
                ?>
                <div class="error">There was an error creating a new user.</div>
                <?php
                        return;
                    }
                    if(!fwrite($data, $_REQUEST["user"]."\n".md5($_REQUEST["pass"])."\n".$_REQUEST["mail"]."\n".$_REQUEST["card"]."\n0")) {
                ?>
                <div class="error">There was an error creating a new user.</div>
                <?php
                        return;
                    }
                    fclose($data);
                    $history = fopen($user_path."/history.xml", "w");
                    if(!$history) {
                    ?>
                <div class="error">There was an error creating a new user.</div>
                    <?php
                        return;
                    }
                    if(!fwrite($history,"<history>\n\t\n</history>")) {
                ?>
                <div class="error">There was an error creating a new user.</div>
                <?php
                        return;
                    }
                    fclose($history);
                ?>
                Register succesful.
                <?php
                }
                } ?>
		</div>
        <footer>
            <img src="http://www.w3.org/Icons/valid-xhtml10-blue" alt="Valid XHTML 1.0!"/>
            <img src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="¡CSS Válido!"/>
        </footer>
	</body>
</html>
