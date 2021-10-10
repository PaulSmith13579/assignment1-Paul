<?php include "template.php"; ?>
<?php include 'login.php'; ?>
<title>Product List</title>

<h1 class='text-primary'>Product List</h1>

<?php
$productList = $conn->query("SELECT ProductName, image FROM products");
?>

<div class="container-fluid">
    <?php
    while ($productData = $productList->fetchArray()) {
        ?>
        <div class="row">
            <div class="col-md-2">
                <?php
                echo '<img src="images/productImages/'.$productData[1].'" width="50" height="50">';
                ?>
            </div>
            <div class="col-md-4">
                <?php echo $productData[0]; ?>
            </div>
            <div class="col-md-2">
                <!--            edit button-->
                <a href="product-edit.php?prodCode=<?php echo $productData[2]; ?>">Edit</a>
            </div>
            <div class="col-md-2">
                <!--            delete button-->
                <a href="Product-remove.php?ProdCode=<?php echo $productData[3]; ?>">Delete</a>
            </div>
        </div>
        <?php
    }
    ?>


</div>
