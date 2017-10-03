<?php

require_once "Product.class.php";

class DB {

    private $dbh;

    function __construct() {
        require_once "";
        try {
            //$mysql_host, $mysql_user, $mysql_pass, $mysql_name
            //open a connection
            $this->dbh = new PDO("mysql:host=$mysql_host;dbname=$mysql_name", $mysql_user,$mysql_pass);

            //change error reporting
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    /*
     * Method to return the objects of Product class on fetching
     * the catalog products from DB
     */

    function getAllObjectsOfCatalogProducts($start , $perPage) {
        try {
            //object mapping
            $data = array();
            $stmt = $this->dbh->prepare("SELECT * FROM products WHERE isDiscountedProduct = 0 LIMIT $start , $perPage");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Product");
            $count = $stmt->rowCount();

            while ($product = $stmt->fetch()) {
                $data[] = $product;
            }

            return $data;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }

    }
    /*
     * Fetch Products available for Sale
     *
     */
    function getAllObjectsOfProductsOnSale() {
        try {
            //object mapping
            //include "Product.class.php";
            $data = array();
            $stmt = $this->dbh->prepare("SELECT * FROM p****s WHERE isDiscountedProduct = 1 LIMIT 3");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Product");

            while ($product = $stmt->fetch()) {
                $data[] = $product;
            }

            return $data;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }

    }
    /*
     * Get number of items in cart for a particular user
     */

    function getNumberOfCartItemsByUser($id) {
        try {
            $data = array();
            $stmt = $this->dbh->prepare("SELECT * FROM Cart WHERE UserID = :id");
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll();

            return $data;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }
    function insertAnonymousUserAO($username){
        try {
            $stmt = $this->dbh->prepare("INSERT INTO users (userName, userType) VALUES (:userName,'Anonymous User')");
            $stmt->execute(array(
                "userName" => $username,
            ));

            return $this->dbh->lastInsertId();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }

    }

    function getUseridByUserName($username){
        $userid = 0;
        //Fetch User ID using user name
        $sql = $this->dbh->prepare('SELECT userID FROM users WHERE userName =:userName');
        //echo $sql;
        $sql->execute(array('userName'=>$username));
        while ($row = $sql->fetch() ) {
            $userid = $row['userID'];
        }
        return $userid;
    }



    function getProductDetailsByID($productid){

        $data = array();
        $stmt = $this->dbh->prepare("SELECT * FROM products WHERE productID = :id");
        $stmt->execute(array('id'=>$productid));

        while ($row = $stmt->fetch() ) {
            $data[] = $row;
        }
        return $data;
    }

    function updateAvailableQuantity($updatedQuantity,$productAdded){
        try {

            $stmt = $this->dbh->prepare("UPDATE  products SET quantity = :qty WHERE productID = :id");
            $stmt->execute(array(
                "qty" => $updatedQuantity,
                "id" => $productAdded,

            ));

            return $this->dbh->lastInsertId();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }

    }

   /*
    * Add product to cart
    */

    function addTocart($productAdded,$userName,$quantityOrdered){
        try {
            $productCost = 0;

            $userid = $this->getUseridByUserName($userName);
            //Fetch Product Data

            $data= $this->getProductDetailsByID($productAdded);
            $productdescription =  $data[0]['productDescription'];
            $productName = $data[0]['productName'];
            $availableQty = $data[0]['quantity'];
            if($data[0]['isDiscountedProduct'] == 0){
                $productCost = $data[0]['productPrice'];
            }
            else{
                $productCost = $data[0]['salePrice'];
            }
            $productLineCost = $productCost * $quantityOrdered;
            $updatedQuantity = $availableQty - $quantityOrdered;
            //Update the available quantity
            $this->updateAvailableQuantity($updatedQuantity,$productAdded);
            //Add Product Data To Cart
            $stmt = $this->dbh->prepare("INSERT INTO 
                                Cart (UserID, ProductID, OrderedQty, ProductLineCost, 
                                ProductName, ProductUnitCost, ProductDescription)
                                VALUES (:userid, :productid,:quantityOrdered, 
                                :productLineCost, :productname, :productunitcost, :productdescription )");
            $stmt->execute(array(
                "userid" => $userid,
                "productid" => $productAdded,
                "quantityOrdered" =>$quantityOrdered,
                "productLineCost" => $productLineCost,
                "productname" => $productName,
                "productunitcost" => $productCost,
                "productdescription" => $productdescription,

            ));

            return $this->dbh->lastInsertId();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }

    }
    /*
     * Get cart items for user
     */

    function getCartListItemsPDO($username){
        try {
            $userid = $this->getUseridByUserName($username);
            $data = array();
            $stmt = $this->dbh->prepare("SELECT * FROM Cart WHERE UserID = :userid");
            $stmt->bindParam(":userid",$userid,PDO::PARAM_INT);
            $stmt->execute();

            while ($row = $stmt->fetch() ) {
                $data[] = $row;
            }
            return $data;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }
    /*
     * Method for emptying the cart and update items quantity
     */

    function emptyCartPDO($cartData,$username){
        try{
            foreach( $cartData as $cartvalue){
                $orderQty =  $cartvalue['OrderedQty'];
                $data= $this->getProductDetailsByID($cartvalue['ProductID']);
                $availableQty = $data[0]['quantity'];
                $updateQty = $availableQty + $orderQty;
                //Update the available quantity
                $this->updateAvailableQuantity($updateQty,$cartvalue['ProductID']);

            }
            $userid = $this->getUseridByUserName($username);
            $stmt = $this->dbh->prepare("DELETE FROM Cart WHERE UserID = :userid");
            $stmt->bindParam(":userid",$userid,PDO::PARAM_INT);
            $stmt->execute();

        }
        catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }

    }
    /*
     *  Counts the anonymous user
     */

    function getCountAnonymousUser() {
        try {

            $stmt = $this->dbh->prepare("SELECT count(*) countAnonymous FROM users WHERE userType = 'Anonymous User'");
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data[0]['countAnonymous'];
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }
   /*
    * Checks user login
    */
    function checkloginPDO($username, $password){
        $stmt = $this->dbh->prepare("SELECT * FROM users WHERE userName = :username AND userPassword = :password ");
        $stmt->execute(array(
            "username" => $username,
            "password" => $password,
        ));
        $stmt->execute();
        $data = $stmt->fetch();


        //echo "<br>execute check:" . $data . "qwer<br/>";
        return $data;
    }

    /*
     * Updates cart tabl for the aannymous user
     */
    function updateCartAnonymousUser($anonymousUser,$userName){
        try {
            $userid = $this->getUseridByUserName($userName);
            $anonymoususerid = $this->getUseridByUserName($anonymousUser);
            //echo "userid" . $userid;
            //echo "anonymoususerid" . $anonymoususerid;
            //Update userID in Cart Table
            $stmt = $this->dbh->prepare("UPDATE  Cart SET UserID = :userid WHERE UserID = :anonymoususerid");
            $stmt->execute(array(
                "userid" => $userid,
                "anonymoususerid" => $anonymoususerid,

            ));
            //Delete Anonymous Usesr from Users Table
            $stmt = $this->dbh->prepare("DELETE FROM users WHERE UserID = :anonymoususerid AND userType = 'Anonymous User'");
            $stmt->bindParam(":anonymoususerid",$anonymoususerid,PDO::PARAM_INT);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    //Admin Page Functions
    /*
     * Gets all the producrts
     */

    function getAllproductNamesPDO(){
        try {
            //object mapping
            $data = array();
            $stmt = $this->dbh->prepare("SELECT * FROM products");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Product");

            while ($product = $stmt->fetch()) {
                $data[] = $product;
            }
            return $data;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }

    }
    /*
     * Inserts new product i database
     */
    function insertNewProduct($newItem){
        try {

            //Add New Product Data To Product Table
            $stmt = $this->dbh->prepare("INSERT INTO products (productName, productDescription, 
                                productPrice, salePrice, quantity, isDiscountedProduct, image)
                                VALUES (:productName, :productDescription,:productPrice, 
                                :salesPrice, :quantity, :isDiscountedProduct, :image)");
            $stmt->execute(array(
                "productName" => $newItem['productName'],
                "productDescription" => $newItem['productDescription'],
                "productPrice" =>$newItem['productPrice'],
                "salesPrice" => $newItem['salesPrice'],
                "quantity" => $newItem['quantity'],
                "isDiscountedProduct" => $newItem['forsale'],
                "image" => $newItem['image']
            ));

            return $this->dbh->lastInsertId();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }

    }
    /*
     * updates the product
     */

    function updateNewProduct($updateItem){
        try {

            //Add New Product Data To Product Table
            $stmt = $this->dbh->prepare("Update products SET productName = :productName , productDescription = :productDescription
                                        ,productPrice = :productPrice, salePrice = :salesPrice, 
                                         quantity = :quantity, isDiscountedProduct =:isDiscountedProduct, image = :image
                                           WHERE productID = :productID");

            $stmt->execute(array(
                "productName" => $updateItem['productName'],
                "productDescription" => $updateItem['productDescription'],
                "productPrice" =>$updateItem['productPrice'],
                "salesPrice" => $updateItem['salesPrice'],
                "quantity" => $updateItem['quantity'],
                "isDiscountedProduct" => $updateItem['forsale'],
                "productID" => $updateItem['productID'],
                "image" => $updateItem['image']
            ));
            return "updated";
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }

    }
/*
 * Gets the total count of the discounted products
 */
    function countDiscountedProducts(){
        try {

            $stmt = $this->dbh->prepare("SELECT count(*) discountedProducts FROM products WHERE isDiscountedProduct = 1");
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data[0]['discountedProducts'];
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }

    }
/*
 * Get the total count of the catalog products
 *
 */
    function getTotalCountCatalog(){

        try {

            $stmt = $this->dbh->prepare("SELECT count(*) CatalogProducts FROM products WHERE isDiscountedProduct = 0");
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data[0]['CatalogProducts'];
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }




} //class
?>