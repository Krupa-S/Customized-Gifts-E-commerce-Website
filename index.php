<?php
session_start();
$title = "M Your Design";
include_once "classes/lib_project1.php";
require_once "classes/header.inc.php";
require_once 'classes/DB.class.php';
$display = "";
//$db = new DB();

/*
 * Display Products on Sale
 *
 */

echo "<br>";
echo "<div class = 'SaleProductsTag'><h3>Discounted Products</h3></div>";

echo "<div class = 'saleProducts'>";

$saleProducts = $db->getAllObjectsOfProductsOnSale();
foreach ($saleProducts as $sale_product) {
    echo "{$sale_product->getAllSaleProducts()}";
}


echo "</div>";
/*
 * For displaying products which are available for selling
 *  (Catalog Products)
 */

//Pagination For Catalog Products --Starts
//User Input

$page = $_GET['page'];
$perPage = 5;

//Positioning

$start = ($page > 1) ? ($page * $perPage) - $perPage :0;

//pages
$total = $db->getTotalCountCatalog();
$pages = ceil($total/$perPage);
$toPage = (($start + $perPage) > $total) ? $total : $start + $perPage;

echo "<div class = 'catalogProductsTag'><h3>Catalog Products</h3></div>";
echo "<div class = 'showresults'>Showing results " . $start . " - " . $toPage ." of " . $total . ".</div>";

echo "<div class = 'catalogProducts'>";
$products = $db->getAllObjectsOfCatalogProducts( $start , $perPage );
foreach ($products as $product) {
    echo "{$product->getAllCatalogProducts()}";
}

echo "</div>";

///*
// * When Add to Cart Button is hit Set Session and add the product to Cart table of database
// */
//if (isset($_POST['product_addToCart'])) {
//    handleAddToCart($db);
//}

?>

<div class = 'pagination'>
    <table class = 'paginationTable'>
        <tr>
                <?php  for( $x = 1; $x <= $pages; $x++ ) { ?>
           <td>
                <a href="?page= <?php echo $x; ?>" <?php if ($page == $x) { echo "class = 'selected'";}?>>
                <?php echo "\t $x \t"; ?>
                 </a>
           </td>
                <?php } ?>
        </tr>
    </table>
</div>

<?php
require_once PATH_HOME. "classes/footer.php"
?>