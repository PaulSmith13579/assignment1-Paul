<?php include "template.php";
/**
 *  This is the user's profile page.
 * It shows the Users details including picture, and a link to edit the details.
 *
 * @var SQLite3 $conn
 */
?>
<title>Edit your Profile</title>

<h1 class='text-primary'>Edit Your Profile</h1>

<?php
if (isset($_SESSION["username"])) {
    $userName = $_SESSION["username"];
    $userId = $_SESSION["user_id"];

    $query = $conn->query("SELECT * FROM user WHERE username='$userName'");
    $userData = $query->fetchArray();
    $userName = $userData[1];
    $password = $userData[2];
    $name = $userData[3];
    $profilePic = $userData[4];
    $accessLevel = $userData[5];
} else {
    header("Location:index.php");
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <h3>Username : <?php echo $userName; ?></h3>
            <p> Name : <?php echo $name ?> </p>
            <p> Access Level : <?php echo $accessLevel ?> </p>
            <p>Profile Picture:</p>
            <?php echo "<img src='images/profilePic/" . $profilePic . "' width='100' height='100'>" ?>
        </div>
        <div class="col-md-6">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <p>Name: <input type="text" name="name" value="<?php echo $name ?>"></p>
                <p>Access Level: <input type="text" name="accessLevel" value="<?php echo $accessLevel ?>"></p>
                <p>Profile Picture: <input type="file" name ="file"></p>
                <input type="submit" name="formSubmit" value="Submit">
            </form>
        </div>
    </div>
</div>
<?php
if ($_SERVER["REQUEST_METHOD"]== "POST") {
    $newName = sanitise_data($_POST['name']);
    $newAccessLevel = sanitise_data($_POST['accessLevel']);

    $sql = "UPDATE user SET name = :newName, accessLevel=:newAccessLevel WHERE username='$userName'";
    $sqlStmt = $conn->prepare($sql);
    $sqlStmt->bindValue(":newName", $newName);
    if ($accessLevel == "Administrator") {
        $sqlStmt->bindValue(":newAccessLevel", $newAccessLevel);
    } else {
        $sqlStmt->bindValue(":newAccessLevel", $accessLevel);
    }
    $sqlStmt->execute();
}
// Update Profile picture
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
            //file name is now a unique ID based on time with IMG- precedding it, followed by the file type.
            $fileNameNew = uniqid('IMG-', True) . "." . $fileActualExt;
            //upload location
            $fileDestination = 'images/profilePic/' . $fileNameNew;
            //command to upload.
            move_uploaded_file($fileTmpName, $fileDestination);


            $sql = "UPDATE user SET profilePic=:newFileName WHERE username='$userName'";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':newFileName', $fileNameNew);
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
?>