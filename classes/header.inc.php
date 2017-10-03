<?php
require_once "page_start.php";

//DB class object for execuitng queries
$db = new DB();

/*
 * When Add to Cart Button is hit Set Session and add the product to Cart table of database
 */
if (isset($_POST['product_addToCart'])) {
    handleAddToCart($db);
}
$flag = "false";
$login_message = "";

if (isset($_POST['login'])) {
    //Get username and password for validation
    $username = $_POST['username'];
    $password = $_POST['password'];
    $flag = checklogin($db, $username, $password);   //Validates username and password

    //Message to print for correct and incorrect login
    if ($flag == "true") {
        $login_message = ", $username!";
    } else {
        $login_message = "Incorrect Credentials.";
        redirect_to(PATH_LOGIN);
    }

}

/*
 * Handle Logout Functionality
 */
if(isset($_GET['logout']))
{
    if($_GET['logout'] == 'true'){
        $login_message = "";
        //Unset login cookie and session
        session_start();
        $name = "loggedIn";
        $value = $username;
        $path = "/~kjs5335/";
        $domain = 'kelvin.ist.rit.edu';
        $secure = false;
        $http_only = false;
        $expire = time() - 3600;
        setcookie($name, $value, $expire, $path, $domain, $secure, $http_only);
        unset($_SESSION['loggedIn']);
        session_destroy();

        // Unset cart cookie
        session_start();
        $name = "CARTCOOKIE";
        $value = $username;
        $path = "/~kjs5335/";
        $domain = 'kelvin.ist.rit.edu';
        $secure = false;
        $http_only = false;
        $expire = time() - 3600;
        setcookie($name, $value, $expire, $path, $domain, $secure, $http_only);
        unset($_SESSION['CARTCOOKIE']);
        session_destroy();
    }
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title><?= $title; ?></title>
    <link href="http://kelvin.ist.rit.edu/~kjs5335/756/ecommerceproject/assests/css/mycss.css" type="text/css"
          rel="stylesheet"/>
    <link rel="shortcut icon" type="image/x-icon"
          href="http://kelvin.ist.rit.edu/~kjs5335/756/ecommerceproject/assests/image/favicon.ico">

</head>

<script>
    function cartItems() {
        var items = document.getElementById('ajaxBasketCount').innerHTML;
        var numberOfItems = items == 0 ? 1 : (items + 1);
        document.getElementById('ajaxBasketCount').innerHTML = numberOfItems;

    }

</script>
<body>
<div id="navigation_bar" class="nav-bar">
    <div class="logo">
        <a href="<?= PATH_INDEX ?>">
            <img src="http://kelvin.ist.rit.edu/~kjs5335/756/ecommerceproject/assests/image/logo.jpg"
                 title="M Your Design"/>
        </a>
    </div>

    <div id="typeaheadContainer" class="control-group">

        <input type="text" autocomplete="off" placeholder="Search for Gifts"
               class="siteSearchInput left ui-autocomplete-input" accesskey="2"
               title="" id="searchInput" value="" size="40" name="freeText">

        <input type="submit" class="btn-search" value="Search" id="searchSubmitBtn">
        <div id="typeAhead" style="display: none;"></div>
    </div>
    <!-- Header Login -->
    <div class="navigation_login">

        <div class='account'>
            <!-- Login Image -->
            <img src='http://kelvin.ist.rit.edu/~kjs5335/756/ecommerceproject/assests/image/login-icon.png' alt=''
                 title=''/>
            <div>
            <!-- To Display Login Message-->
            <div class='loginmessage'> Hello<?= $login_message; ?></div>
            <!--Check to display links for login and logout -->

            <?php if (!isset($_COOKIE['loggedIn'])) {?>
                <a href='<?= PATH_LOGIN; ?>' class='MyLogin'>
                    <span class='sub-link' id = 'login'>Log In / Register </span>
                </a>

            <?php }else{ ?>
                <!--Logout Link -->
                <a href='?logout=true' class='logout'>
                    <span class='sub-link' id = 'logout'>Log Out </span>

                </a>
            <?php } ?>
            </div>
        </div>
    </div>
    <!-- Login Section Endds -->
    <!-- Header Cart Section starts -->
    <div class="mycart" id="mycart">
        <a href="<?=PATH_CART ?>">
            <div class="cartimage">
                <img src="<? echo URL_IMAGE . 'cart.png'?>" alt="My Cart"
                     title="My Cart">

            <div class="cartText ">
                <div class="mycartTitle">
                    My Cart
                </div>
                <span class="basketcount" id="ajaxBasketCount">
                    <?= $cartItems ?>
                </span>
<!--                <span class="itemLabel" id="itemLabel">items</span>-->
            </div>
            </div>
        </a>
    </div>
    <!-- Header Cart Section Ends -->
    <!--Admin Page Link (Visible Only to Admin)-->
    <?php if($_COOKIE['loggedIn'] == 'admin') {
        echo "<div id= 'adminPage' class= 'admin'><a href='".  PATH_ADMIN . "'>Admin</a></div>";
    } ?>


</div>
