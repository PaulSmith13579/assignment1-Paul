<?php include "template.php";
/**
 *  This is the product Edit page.
 * It shows the product details including picture, to edit.
 *
 * @var SQLite3 $conn
 */
?>
    <title>Edit the Product</title>

    <h1 class='text-primary'>Edit The Product</h1>

<?php

if (isset($_GET["prodCode"])) {
    $prodCode = $_GET["prodCode"];
} else {
    header("location:index.php");
}

$query = $conn->query("SELECT * FROM products WHERE code='$prodCode'");
$prodData = $query->fetchArray();
$prodID = $prodData[0];
$prodName = $prodData[1];
$prodCategory = $prodData[2];
$prodQuantity = $prodData[3];
$prodPrice = $prodData[4];
$prodImage = $prodData[5];

?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <h3>Product Name : <?php echo $prodName; ?></h3>
                <p>Product Picture:</p>
                <?php echo "<img src='images/productImages/" . $prodImage . "' width='100' height='100'>" ?>
            </div>
            <div class="col-md-6">
                <form action="product-edit.php?prodCode=<?php echo $prodCode ?>" method="post"
                      enctype="multipart/form-data">
                    <p>Name: <input type="text" name="prodName" value="<?php echo $prodName ?>"></p>
                    <p>Category: <input type="text" name="prodCategory" value="<?php echo $prodCategory ?>"></p>
                    <p>Quantity : <input type="number" name="prodQuantity" value="<?php echo $prodQuantity ?>"> </p>
                    <p>Price : <input type="number" name="prodPrice" value="<?php echo $prodPrice ?>"> </p>
                    <p>Code : <input type="text" name="prodCode" value="<?php echo $prodCode ?>"></p>
                    <p>Profile Picture: <input type="file" name="file"></p>
                    <input type="submit" name="formSubmit" value="Submit">
                </form>
            </div>
        </div>
    </div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newName = sanitise_data($_POST['prodName']);
    $newCategory = sanitise_data($_POST['prodCategory']);
    $newQuantity = sanitise_data($_POST['prodQuantity']);
    $newPrice = sanitise_data($_POST['prodPrice']);
    $newCode = sanitise_data($_POST['prodCode']);


    // Update Profile picture
    $file = $_FILES['file'];

//Variable Names
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

//defining what type of file is allowed
// We seperate the file, and obtain the end.
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
//We ensure the end is allowable in our thing.
    $allowed = array('jpg', 'jpeg', 'png', 'pdf');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            //File is smaller than yadda.
            if ($fileSize < 10000000000) {
                //file name is now a unique ID based on time with IMG- precedding it, followed by the file type.
                $fileNameNew = uniqid('IMG-', True) . "." . $fileActualExt;
                //upload location
                $fileDestination = 'images/productImages/' . $fileNameNew;
                //command to upload.
                move_uploaded_file($fileTmpName, $fileDestination);


                $sql = "UPDATE products SET productName= :newProdName, category= :newProdCategory, quantity= :newProdQuantity, price= :newProdPrice, image= :newFileName, code= :newProdCode WHERE code='$prodCode'";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':newProdName', $newName);
                $stmt->bindValue(':newProdCategory', $newCategory);
                $stmt->bindValue(':newProdQuantity', $newQuantity);
                $stmt->bindValue(':newProdPrice', $newPrice);
                $stmt->bindValue(':newFileName', $fileNameNew);
                $stmt->bindValue(':newProdCode', $newCode);
                $stmt->execute();
                header("location:index.php");
            } else {
                echo "Your image is too big!";
            }
        } else {
            echo "there was an error uploading your image!";
        }
    } else {
        echo "You cannot upload files of this type!";
    }
}


?>