<?php
require_once "page_start.php";
include_once "lib_project1.php";
include_once "upload.php";
require_once 'DB.class.php';



$db = new DB();
$productObj = new Product();
$allproducts = $db->getAllproductNamesPDO();
$productselected = array();
$isDiscountedProduct_old = 0;

//Fill up the fields for editing form
if(isset($_POST['edit']))
{
    $submitvalue = "Update Item";
    if(isset($_POST['pickOne'])){
        $productselected = $productObj->getProductDetailsEdit($allproducts,$_POST['pickOne']);
        $isDiscountedProduct_old =  $productselected['isDiscountedProduct'];
    }else{
        echo"<div id='noSelect' class = 'noSelect'>No Product Selected</div>";
    }
}



if(isset($_POST['additem']))
{
    $submitvalue = "Submit Item";
}

//Form on reset
if(isset($_POST['reset']))
{
    $productselected = array();
}



if( isset( $_POST['submititem'] ))
{
    $clickedButton = $_POST['submititem'];
    $isDiscountedProduct_new = ($_POST['forsale'] == null)? 0 : 1;

    $isDiscountedProductFlag_edit = ($clickedButton == "Update Item" && $isDiscountedProduct_new != $isDiscountedProduct_old)?
                                checkCountOfDiscounted($db,$isDiscountedProduct_new) :"Correct";


    $isDiscountedProductFlag_add = ($clickedButton == "Submit Item") ?
                                checkCountOfDiscounted($db,$isDiscountedProduct_new) : "No Check";



    if( ( $isDiscountedProductFlag_edit == "Correct" && $clickedButton == "Update Item") ||
           $isDiscountedProductFlag_add == "Correct")
    {
        if($clickedButton == "Update Item")
        {


                if($_FILES["fileToUpload"]["name"] == null) {
                    $imageURL = $productselected['image'];

                }
                else{

                    $imageURL = IMAGE_URL . $_FILES["fileToUpload"]["name"];
                    uploadImage($_FILES);
                }
                $updateItem = array( "productID" => $_POST['productID'],
                                    "productName" => $_POST['productName'],
                                    "productDescription" => $_POST['productDescription'],
                                    "productPrice" => $_POST['productPrice'],
                                     "quantity" => $_POST['quantity'],
                                     "salesPrice" => $_POST['salesPrice'],
                                     "forsale" => $isDiscountedProduct_new,
                                      "image" => $imageURL
                                    );
                $updateItem = $db->updateNewProduct($updateItem);
                $updateItem = array();

        }
        else
        {
            $imageURL = IMAGE_URL . $_FILES["fileToUpload"]["name"];
                $newItem = array( "productName" =>$_POST['productName'],
                                 "productDescription" => $_POST['productDescription'],
                                 "productPrice" => $_POST['productPrice'],
                                 "quantity" => $_POST['quantity'],
                                 "salesPrice" => $_POST['salesPrice'],
                                 "forsale" => $isDiscountedProduct_new,
                                 "image" => $imageURL,
                                );
                $insertItem = $db->insertNewProduct($newItem);
                $insertItem = array();
                uploadImage($_FILES);

        }

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
<body>
<div class="logo">
    <a href= "<?= PATH_INDEX; ?>">
        <img src="http://kelvin.ist.rit.edu/~kjs5335/756/ecommerceproject/assests/image/logo.jpg" title="M Your Design"/>
    </a>
</div>

<div id="main">

    <h2 class='heading'>Admin Inventory Page</h2>
    <div class="box">
        <table>

            <tr>

                <td>

                    <div id="chooseitem" class="chooseitem">
                        <form action='admin.php' method='post'>
                            Choose an item to Edit:
                            <select name='pickOne'>
                                <?php

                                foreach($allproducts as $editproduct){
                                    echo $editproduct->editProductsAdmin();
                                }

                                ?>

                            </select>
                            <input type='submit' class="select" id ="select" name='edit' value='Select' />
                            <input type='submit' class="additem" id ="additem" name='additem' value='Add Item' />

                        </form>
                    </div>

                </td>

            </tr>

        </table>

        <br />
    </div>
    <!-- Form will be displayed only if Select/Add item is clicked  -->
    <?php if(isset($_POST['edit']) || isset($_POST['additem']) || isset($_POST['reset']) || isset($_POST['updateitem'])){ ?>
        <div class="box_add">

            <form action='admin.php' method='post' enctype="multipart/form-data">

                <table>

                    <tr>
                        <input type="hidden" name="productID" size="40" value="<?=$productselected['productID']?>" />
                        <td>
                            Product Name:
                        </td>

                        <td>

                            <input type="text" name="productName" size="40" value="<?=$productselected['productName']?>" />

                        </td>

                    </tr>

                    <tr>

                        <td>

                            Product Description:
                        </td>

                        <td>

                            <textarea name="productDescription" rows="3" cols="60"><?=$productselected['productDescription']?></textarea>

                        </td>

                    </tr>

                    <tr>

                        <td>

                            Actual Price:
                        </td>

                        <td>

                            <input type="text" name="productPrice" size="40" value="<?=$productselected['productPrice']?>" />

                        </td>

                    </tr>

                    <tr>

                        <td>
                            Available Quantity:
                        </td>

                        <td>
                            <input type="text" name="quantity" size="40" value="<?=$productselected['quantity']?>" />
                        </td>

                    </tr>

                    <tr>

                        <td>

                            Sale Price:
                        </td>

                        <td>

                            <input type="text" name="salesPrice" size="40" value="<?=$productselected['salePrice']?>" />

                        </td>

                    </tr>

                    <tr>

                        <td>
                            New Image:
                        </td>

                        <td>

                            <input type="file" name="fileToUpload" id="fileToUpload">


                        </td>

                    </tr>
                    <tr>

                        <td>
                            Is Product For Sale:
                        </td>
                        <?php if($productselected['isDiscountedProduct'] == 1){ ?>
                            <td>
                                <input type="checkbox" name="forsale" value="1" checked="checked">
                                </label>
                            </td>
                        <?php }else{  ?>
                            <td>
                                <input type="checkbox" name="forsale" value="1">
                                </label>
                            </td>

                        <?php } ?>
                    </tr>


                </table>



                <input type="submit"  name="reset" id="reset" class="reset" value="Reset Form" />
                <input type="submit" name="submititem" value="<?=$submitvalue ?>" />

            </form>
        </div>
    <?php } ?>

</div>

<?php
require_once PATH_HOME. "classes/footer.php"
?>