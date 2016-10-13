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
