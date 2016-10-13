<!DOCTYPE html>
<!-- vim: set noai ts=4 sw=4: -->
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
    <form method="post" action="index.php">
        User name:<br>
        <input type="text" name="user" placeholder="User name" required autofocus><div class="error">The user <?php echo $_REQUEST["user"] ?> already exists.</div><br>
        Password:<br>
        <input type="password" name="pass" placeholder="Password" required><br>
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
