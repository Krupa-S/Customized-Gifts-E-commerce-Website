<?php
$title = "Cart Summary";
include_once "lib_project1.php";
require_once "header.inc.php";
require_once 'DB.class.php';

// cart session
session_start();
$totalOrderCost = 0;
$cartData = array();  //array for cart data

if (isset($_COOKIE['CARTCOOKIE'])) {
    $cartData = cartListItems($_COOKIE['CARTCOOKIE'], $db);   //function call for
}

if (isset($cartData) && !empty($cartData)) {
    $display = "";
    $display .= "<h2>Shopping Cart</h2>";
    $display .= "<div class='carttable'>";
    $display .= "<table>";
    $display .= "<tr><th>Item</th><th>Quantity</th><th>Price</th></tr>";
    foreach ($cartData as $productline) {
        $display .= "<div class = 'productlineentry'>";
        $display .= "<tr>";
        $display .= "<div class ='itemdetails'>";
        $display .= "<td><h4>" . $productline['ProductName'] . "</h4>
                    <span>" . $productline['ProductDescription'] . "</span></td>";
        $display .= "</div>";
        $display .= "<div class ='orderedqty'><td>" . $productline['OrderedQty'] . "</td></div>";
        $display .= "<div class ='productlinecost'><td>" . $productline['ProductLineCost'] . "</td></div>";
        $display .= "</tr>";
        $display .= "</div>";
        $totalOrderCost = $totalOrderCost + $productline['ProductLineCost'];
    }
    if ($totalOrderCost != 0) {
        $display .= "<tr><td></td><td></td><td><strong>Subtotal:\t</strong>" . "$" . $totalOrderCost . "</td></tr>";
    }
    $display .= "</table></div>";
} else {
    $display = "Cart is Empty!";
}


//Empty Cart Functionality
if (isset($_POST['emptycart'])) {
    if ($cartData != null) {
        emptycart($cartData, $db, $_COOKIE['CARTCOOKIE']);

        $name = "CARTCOOKIE";
        $value = $username;
        $path = "/~kjs5335/";
        $domain = 'kelvin.ist.rit.edu';
        $secure = false;
        $http_only = false;
        $expire = time() - 3600;
        setcookie($name, $value, $expire, $path, $domain, $secure, $http_only);

        unset($_COOKIE['CARTCOOKIE']);
        unset($_SESSION['CARTCOOKIE']);

        session_destroy();
        $display = "Cart is Empty!!";
    }
}
//Redirect to checkout page

if(isset($_POST['checkout'])){
    redirect_to(PATH_CHECKOUT);
}
?>
<?php
if(!isset($_COOKIE['loggedIn'])){
    echo "<div class = 'dologin'>Please <a href ='<?=PATH_LOGIN;>'>Login/Register </a> to place your order.</div>";
}
?>

<div class = 'emptycart'><?= $display; ?></div>
<div>
    <form method="post" action="cart.php" id="cartpageForm">
        <input type="submit" value="Empty Cart" name="emptycart" id="emptycart">
        <input type='submit' name='checkout' id='checkout' value="Go To Checkout">
    </form>
</div>


