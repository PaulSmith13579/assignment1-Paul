<?php include "template.php";
/**
 *  This will remove a given product
 *
 * @var SQLite3 $conn
 */
?>
    <title>Remove Product from Database</title>

    <h1 class='text-primary'>Remove Product from Database</h1>

<?php
// check first to confirm user is an administrator.

if (isset($_GET["ProdCode"])) {
    // delete user from database
    $productToDelete = $_GET["ProdCode"];
//    echo "<p>".$userToDelete."</p>";
    $query = "DELETE FROM products WHERE code='$productToDelete'";
    $sqlstmt = $conn->prepare($query);
    $sqlstmt->execute();
    echo "<p>Product ".$productToDelete." has been deleted from the database";

} else {
    echo "No Product to Delete";
}
?>