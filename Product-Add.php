<?php include "template.php"; ?>
<title>Create New Product</title>
<h1 class='text-primary'>Create New Product</h1>

<?php
$query = $conn->query("SELECT DISTINCT category FROM products");
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <div class="container-fluid">
        <div class="row">
            <!--Products Details-->

            <div class="col-md-6">
                <h2>Products Details</h2>
                <p>Product Name<input type="text" name="ProdName" class="form-control" required="required"></p>
                <p>Product Category
                    <select name="ProdCategory">
                        <?php
                        while ($row = $query->fetchArray()) {
                            echo '<option>'.$row[0].'</option>';
                        }
                        ?>
                    </select>
                </p>
                <p>Quantity<input type="Number" name="ProdQuantity" class="form-control" required="required"></p>
            </div>
            <div class="col-md-6">
                <h2>More Details</h2>
                <!--Product List-->
                <p>Price<input type="Number" name="ProdPrice" step="0.01" class="form-control" required="required"></p>
                <p>Product Code<input type="text" name="ProdCode" class="form-control" required="required"></p>
                <p>Product Picture <input type="file" name="ProdImage" class="form-control" required="required"></p>
            </div>
        </div>
    </div>
    <input type="submit" name="formSubmit" value="Submit">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
//    Customer Details
    $ProdName = sanitise_data($_POST['ProdName']);
    $ProdCategory = sanitise_data($_POST['ProdCategory']);
    $ProdQuantity = sanitise_data($_POST['ProdQuantity']);
    $ProdPrice = sanitise_data($_POST['ProdPrice']);
    $ProdCode = sanitise_data($_POST['ProdCode']);

//check if user exists.
    $query = $conn->query("SELECT COUNT(*) FROM user WHERE code='$ProdCode'");
    $data = $query->fetchArray();
    $numberOfProducts = (int)$data[0];

    if ($numberOfProducts > 0) {
        echo "Sorry, username already taken";
    } else {
// Product Registration commences

//for the image table.
        $file = $_FILES['ProdImage'];

//Variable Names
        $fileName = $_FILES['ProdImage']['name'];
        $fileTmpName = $_FILES['ProdImage']['tmp_name'];
        $fileSize = $_FILES['ProdImage']['size'];
        $fileError = $_FILES['ProdImage']['error'];
        $fileType = $_FILES['ProdImage']['type'];

//defining what type of file is allowed
// We separate the file, and obtain the end.
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
//We ensure the end is allowable in our thing.
        $allowed = array('jpg', 'jpeg', 'png', 'pdf');

        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                //File is smaller than yadda.
                if ($fileSize < 10000000000) {
                    //file name is now a unique ID based on time with IMG- preceding it, followed by the file type.
                    $fileNameNew = uniqid('IMG-', True) . "." . $fileActualExt;
                    //upload location
                    $fileDestination = 'images/ProductImages/' . $fileNameNew;
                    //command to upload.
                    move_uploaded_file($fileTmpName, $fileDestination);
                    $sql = "INSERT INTO products (ProductName, category, quantity, price, image, code)  VALUES (:newProdName, :newProdCategory, :newProdQuantity, :newProdPrice, :newProdImage, :newProdCode, )";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindValue(':newProdName', $ProdName);
                    $stmt->bindValue(':newProdCategory', $ProdCategory);
                    $stmt->bindValue(':newProdQuantity', $ProdQuantity);
                    $stmt->bindValue(':newProdPrice', $ProdPrice);
                    $stmt->bindValue(':newProdImage', $fileDestination);
                    $stmt->bindValue(':newProdCode', $ProdCode);
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
}
?>

</body>
</html>
