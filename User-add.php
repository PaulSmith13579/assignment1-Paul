<?php include "template.php"; ?>
<title>Create New User</title>
<h1 class='text-primary'>Create New User</h1>

<?php
$query = $conn->query("SELECT DISTINCT accessLevel FROM user");
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <div class="container-fluid">
        <div class="row">
            <!--Customer Details-->

            <div class="col-md-6">
                <h2>Account Details</h2>
                <p>Please enter wanted username and password:</p>
                <p>User Name<input type="text" name="username" class="form-control" required="required"></p>
                <p>Password<input type="password" name="password" class="form-control" required="required"></p>
<!--                <p>Access Level<input type="text" name="AccessLevel" class="form-control" required="required"></p>-->
                <p>Access Level
                    <select name="accessLevel">
                        <?php
                        while ($row = $query->fetchArray()) {
                            echo '<option>'.$row[0].'</option>';
                        }
                        ?>
                    </select>
                </p>
            </div>
            <div class="col-md-6">
                <h2>More Details</h2>
                <!--Product List-->
                <p>Please enter More Personal Details:</p>
                <p>Name<input type="text" name="name" class="form-control" required="required"></p>
                <p>Profile Picture <input type="file" name="file" class="form-control" required="required"></p>
            </div>
        </div>
    </div>
    <input type="submit" name="formSubmit" value="Submit">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
//    Customer Details
    $username = sanitise_data($_POST['username']);
    $password = sanitise_data($_POST['password']);
    $name = sanitise_data($_POST['name']);
    $AccessLevel = sanitise_data($_POST['AccessLevel']);

//check if user exists.
    $query = $conn->query("SELECT COUNT(*) FROM user WHERE username='$username'");
    $data = $query->fetchArray();
    $numberOfUsers = (int)$data[0];

    if ($numberOfUsers > 0) {
        echo "Sorry, username already taken";
    } else {
// User Registration commences

//for the user-hashed table.
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

//for the image table.
        $file = $_FILES['file'];

//Variable Names
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];

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
                    $fileDestination = 'images/profilePic/' . $fileNameNew;
                    //command to upload.
                    move_uploaded_file($fileTmpName, $fileDestination);
                    $sql = "INSERT INTO user (username, password, name, profilePic, accessLevel) VALUES (:newUsername, :newPassword, :newName, :newImage, :newAccessLevel)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindValue(':newUsername', $username);
                    $stmt->bindValue(':newPassword', $hashed_password);
                    $stmt->bindValue(':newName', $name);
                    $stmt->bindValue(':newImage', $fileNameNew);
                    $stmt->bindValue(':AccessLevel', $AccessLevel);
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
