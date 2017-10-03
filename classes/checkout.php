<?php
$title = "Checkout";
require_once "page_start.php";
include_once "lib_project1.php";
require_once "header.inc.php";
require_once 'DB.class.php';

if(!isset($_COOKIE['loggedIn'])){
    redirect_to(PATH_LOGIN);

}

?>
<div class ='checkout'>
<form id="billing" action="orderconfirmation.php" method="post" onsubmit="return validateBilling()">
    <p class="billingAndDelivery"><strong>Billing information</strong></p>
    <p><input id="name" type="text" name="name" placeholder="Name" ></p>
    <p><input id="address" type="text" name="address" placeholder="Address" ></p>
    <p class="creditcardTitle">Credit Card <img src="<?php echo IMG_URL . 'creditcardlogo.jpg'; ?>" alt="CreditCardImage"></p>
    <p><input id="account" type="text" name="account" placeholder="Account Number" ></p>
    <p><input id="creditcard" type="text" name="creditcard" placeholder="Credit Card Number" ></p>
    <p><input id="expiration" type="text" name="expiration" placeholder="Expiration Date" ></p>
    <p><input id="securitycode" type="text" name="securitycode" placeholder="Security Code" ></p>
    <div class="billingbutton">
        <input type="submit" id="orderConfirmation" value="Place Order" style="box-shadow: none;border: 3px solid #3bb9ff;">
    </div>
</form>
</div>


<?php
require_once PATH_HOME. "classes/footer.php"
?>


