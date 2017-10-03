<?php
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>PFW Proj 1 - Admin</title>
    <link href="http://kelvin.ist.rit.edu/~kjs5335/756/ecommerceproject/assests/css/progressstyle.css" type="text/css"
          rel="stylesheet"/>
    <link rel="shortcut icon" type="image/x-icon"
          href="http://kelvin.ist.rit.edu/~kjs5335/756/ecommerceproject/assests/image/favicon.ico">



<link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700' rel='stylesheet' type='text/css'>
</head>
<body>
<h1>Responsive Checkout Progress Bar</h1>
<! -- To test add 'active' class and 'visited' class to different li elements -->

<div class="checkout-wrap">
    <ul class="checkout-bar">

        <li class="visited first">
            <a href="#">Login</a>
        </li>

        <li class="previous visited">Shipping & Billing</li>

        <li class="active">Shipping Options</li>

        <li class="next">Review & Payment</li>

        <li class="">Complete</li>

    </ul>
</div>
</body>
</html>