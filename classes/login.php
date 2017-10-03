<?php
require_once "page_start.php";

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Login</title>
    <link href="http://kelvin.ist.rit.edu/~kjs5335/756/ecommerceproject/assests/css/mycss.css" type="text/css"
          rel="stylesheet"/>
    <link rel="shortcut icon" type="image/x-icon"
          href="http://kelvin.ist.rit.edu/~kjs5335/756/ecommerceproject/assests/image/favicon.ico">

</head>

<body>
<!-- display logo and clickable to home -->
<div class="logo">
    <a href="<?= PATH_INDEX; ?>">
        <img src="http://kelvin.ist.rit.edu/~kjs5335/756/ecommerceproject/assests/image/logo.jpg"
             title="M Your Design"/>
    </a>
</div>

<!--Display Message if login fails -->
<div class="loginerrorssg" id="loginerrorssg">
    <?= $login_message ?>
</div>

<!-- Login Form -->
<div class="MyAccountPopup MyLoginPopup">

    <div>
        <form id="loginForm" name="loginForm" action="../index.php" method="post" class="form-login">


            <div class="container">
                <label><b>Username</b></label>
                <input type="text" name="username" id="username" value="" class=""/>

                <label><b>Password</b></label>
                <input type="password" name="password" id="password" value="" class=""/>

                <button class="login" type="submit" id="login" name="login" value="Log in">
                    <strong>Log in</strong>
                </button>
                <br>
                <br>
                <br>
                <span class="psw"><a href="/registration.php">Register Now</a></span>
            </div>

        </form>
    </div>
</div>
<!-- Login Ends -->


<?php
require_once PATH_HOME . "classes/footer.php"
?>


