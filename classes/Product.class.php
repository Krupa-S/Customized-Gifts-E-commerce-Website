<?php

class Product{
    private $productID, $productName, $productDescription, $productPrice, $salePrice, $quantity,$image,$isDiscountedProduct;

    /*
     * To display catalog products
     *
     */
    public function getAllCatalogProducts(){

        $display = "";
        $display .= "<div class = 'product_details' id = 'product_" .$this->productID ."'>";
        $display .= "<div class='product_thumb_image' id ='product_thumb_image'>
                        <img src ='" . $this->image . "' style = 'height:173px; width: 308px'>
                        </img> 
                      </div>";
        $display .= "<div class = 'item_details'><div class = 'item_name'> 
                            <h3>".$this->productName ."</h3>
                     </div>";
        $display .="<div class = 'item_description'>
                            <p>". $this->productDescription ."</p>
                    </div>";
        $display .="<div id = 'availableQty' name = 'available Qty'><span id = 'QtyWrapper'> <strong>Available Quantity:\t</strong></span>\t"
            .$this->quantity . "</div></div><br/>" ;
        $display .= "<div class = 'price_description'>";
        $display .= "<form class = 'productInformation' action='http://kelvin.ist.rit.edu/~kjs5335/756/ecommerceproject/index.php' method='post'>";
        $display .="<input type = 'hidden' name='productID' id='productID' value =". $this->productID ."><br/>";

        $display .="<div class = 'actual_price'><span id = 'priceWrapper'> <strong>Product Price:\t</strong></span>\t $"
            . $this->productPrice ."</div><br/>";
        $display .= "<label><abbr title='Quantity'>Qty</abbr>
			               <input type='text' name='quantityMap' size='3' value='1' id='quantityMap' style='width:40px;'/>
			       </label><span>
			       	<abbr title='Minimum order quantity'> Min: 1 </abbr></span>";

        $display .= "<div id='addToCartButton'><input type ='submit' name = 'product_addToCart'
                     id ='product_addToCart' value='Add To Cart' onclick='cartItems()'></div>";
        $display .= "</form></div></div>";
        return $display;
    }

    /*
     * To display Sale Products
     */
    public function getAllSaleProducts(){
        //return "{$this->FirstName} {$this->LastName} and my nickname is {$this->$NickName}";
        $display = "";
        $display .= "<div class = 'product_details' id = 'product_" .$this->productID ."'>";
        $display .= "<div class='product_thumb_image' id ='product_thumb_image'>
                        <img src ='" . $this->image . "' style = 'height:173px; width: 308px'>
                        </img> 
                      </div>";
        $display .= "<div id = 'item_details'><div class = 'item_name'> 
                            <h3>".$this->productName ."</h3>
                     </div>";
        $display .="<div class = 'item_description'>
                            <p>". $this->productDescription ."</p>
                    </div>";

        $display .="<div id = 'availableQty' name = 'available Qty'><span id = 'QtyWrapper'> <strong>Available Quantity:\t</strong></span>\t"
            .$this->quantity . "</div></div><br/>" ;
        $display .= "<div class = 'price_description'>";
        $display .= "<form class = 'productInformation' action='http://kelvin.ist.rit.edu/~kjs5335/756/ecommerceproject/index.php' method='post'>";
        $display .="<input type = 'hidden' name='productID' id ='productID' value =". $this->productID .">";
        $display .="<div class = 'actual_price'><span id = 'priceWrapper'> <strong>Product Price:\t</strong></span>\t $"
            . $this->productPrice ."</div><br/>";
        $display .="<div class = 'actual_price'><span id = 'salepriceWrapper'> <strong>Sale Price:\t</strong></span>\t $"
            . $this->salePrice ."</div><br/>";

        $display .= "<label><abbr title='Quantity'>Qty</abbr>
			               <input type='text' name='quantityMap' size='3' value='1' id='quantityMap' style='width:40px;'/>
			       </label><span>";

        $display .= "<div class='addToCartButton'><input type ='submit' name = 'product_addToCart'
                     id ='product_addToCart' value='Add To Cart' onclick='cartItems()'></div>";
        $display .= "</form></div></div>";
        return $display;
    }

    /*
     * To edit products - Admin Operations
     */
    function editProductsAdmin(){

        $option = "<option value = '" . $this->productID . "'>" . $this->productName . "</option>";
        return $option;
    }

    function getProductDetailsEdit($allproducts,$selectProductID){
        $editproductdetail = array();
        foreach($allproducts as $product){
                if($product->productID == $selectProductID)
                {

                    $editproductdetail = array("productID"=>$product->productID,
                                                "productName"=>$product->productName,
                                                "productDescription"=>$product->productDescription,
                                                "productPrice"=>$product->productPrice,
                                                "salePrice"=>$product->salePrice,
                                                 "quantity"=>$product->quantity,
                                                 "isDiscountedProduct"=>$product->isDiscountedProduct,
                                                 "image" =>$product->image
                                                );
                }

        }
        return($editproductdetail);
    }


}