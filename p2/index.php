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
        <?php require_once "header.php"; ?>
        <?php require_once "sidebar.php"; ?>
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
        <?php require_once "footer.php"; ?>
    </body>
</html>

