<?php

/*
 * Reusable functions
 *  This file has all function s that are used  at mulipl place
 * Some functions has the call to DB.class
 */

//Function for redirection
function redirect_to($new_location){
    header("Location:" . $new_location);
    exit;
}

//Function for getting cart items parameters

//function insertAnonymousUser($anonymousUserName){
//    $db = new DB();
//    $lastId = $db->insertAnonymousUserAO($anonymousUserName);
//    echo "$lastId";
//
//}
//

/*
 * Function To handle add to cart event
 * Sets the cart cookie and add proucts to data base
 */

function handleAddToCart($db){

    $productAdded = $_POST['productID'];
        $quantityOrdered = $_POST['quantityMap'];
    $_SESSION['CARTCOOKIE'] = "true";

        //When product is added to cart for first time session is set
        if (!isset($_COOKIE['CARTCOOKIE']) && !isset($_COOKIE['loggedIn'])) {

            $name = "CARTCOOKIE";
            $countAnony = $db->getCountAnonymousUser();
            $counter = $countAnony +1;
            $value = "AnonymousUser".$counter;
            $path = "/~kjs5335/";
            $domain = 'kelvin.ist.rit.edu';
            $secure = false;
            $http_only = false;
            $expire = time() + 86400;//static variable
            setcookie($name, $value, $expire, $path, $domain, $secure, $http_only);

            $db->insertAnonymousUserAO($value);

            // Add To Cart

            $userName = $_COOKIE['CARTCOOKIE'];
            //Add the product to cart
            $db->addTocart($productAdded,$userName,$quantityOrdered);
        } else if(isset($_COOKIE['CARTCOOKIE']) && !isset($_COOKIE['loggedIn']))
        {
            //when user has cart cookie and not loggedIn
            $userName = $_COOKIE['CARTCOOKIE'];
             $db->addTocart($productAdded, $userName, $quantityOrdered);


        }else if( !isset($_COOKIE['CARTCOOKIE']) && isset($_COOKIE['loggedIn']))
        {
            //when user is logged in and does not have cart cookie
            $name = "CARTCOOKIE";
            $value = $_COOKIE['loggedIn'];
            $path = "/~kjs5335/";
            $domain = 'kelvin.ist.rit.edu';
            $secure = false;
            $http_only = false;
            $expire = time() + 86400;//static variable
            setcookie($name, $value, $expire, $path, $domain, $secure, $http_only);
            $userName = $_COOKIE['loggedIn'];
            //Add the product to cart
            $db->addTocart($productAdded, $userName, $quantityOrdered);
        }
        else{
             //when user has cart cookie and is logged in
            $cartCookie = $_COOKIE['CARTCOOKIE'];
            $name = "CARTCOOKIE";
            $path = "/~kjs5335/";
            $domain = 'kelvin.ist.rit.edu';
            $secure = false;
            $http_only = false;
            $expire = time() + 86400;//static variable
            $userName = $_COOKIE['loggedIn'];
            setcookie($name, $userName, $expire, $path, $domain, $secure, $http_only);
            //call to addto cart function
            $db->updateCartAnonymousUser($cartCookie,$userName);

            //Add the product to cart

            $db->addTocart($productAdded, $userName, $quantityOrdered);
        }

            return "Product Added To Cart";

}

/*
 * Function to list the cart items on the cartpage
 */
function cartListItems($username, $db){
     $cartListItems = array();
    $cartListItems = $db->getCartListItemsPDO($username);
    return $cartListItems;
}



/*
 * Function to empty cart
 */

function emptycart($cartData, $db,$username){
    $db->emptyCartPDO($cartData,$username);

}

/*
 * Function to check authorization of  the user while login on Login page
*/
function checklogin($db, $username, $password){

    $hash = md5($password); //MD5 encoding for password

    $authentication = $db->checkloginPDO($username, $hash); //call to DB.class function

     if($authentication != null){
         //set the login cookie and session
         $_SESSION["loggedIn"] = "true";
         $name = "loggedIn";
         $value = $username;
         $path = "/~kjs5335/";
         $domain = 'kelvin.ist.rit.edu';
         $secure = false;
         $http_only = false;
         $expire = time() + 86400;
         setcookie($name, $value, $expire, $path, $domain, $secure, $http_only);
         return "true";
     }
     return "false";
}
// Admin Page Functions
function getAllproductNames($db){

    $data = $db->getAllproductNamesPDO();
     return $data;
}

/*
 * Function to check the count of discounted products
 * Minimum 3 Products and maximum 5 products
 */
function checkCountOfDiscounted($db, $checkProductInSale)
{
    $countDiscountedProducts = $db->countDiscountedProducts();  //call to DB.class functions

    //if discounted is checked
    if( $checkProductInSale == 1 ){
        $tempCount = $countDiscountedProducts + 1;
    }
    else
    {
        $tempCount = $countDiscountedProducts - 1;
    }

    if( $tempCount < 3 )
    {
        $display_message =  "<br>Minimum 3 Products should be discounted.<br>";

    }
    else if( $tempCount > 5 )
    {
        $display_message = "<br>Maximum Limit of 5 for Discounted Products is exceeded.<br>";
    }
    else
    {
        $display_message = "Correct";
    }
    return $display_message;

}
?>