<?php include "template.php";
/**
 *  This is the user's profile page.
 * It shows the Users details including picture, and a link to edit the details.
 *
 * @var SQLite3 $conn
 */
?>
<title>Remove User from Database</title>

<h1 class='text-primary'>Remove User from Database</h1>

<?php
// check first to confirm user is an administrator..

if (isset($_GET["user_id"])) {
    // delete user from database
    $userToDelete = $_GET["user_id"];
//    echo "<p>".$userToDelete."</p>";
    $query = "DELETE FROM user WHERE user_id='$userToDelete'";
    $sqlstmt = $conn->prepare($query);
    $sqlstmt->execute();
    echo "<p>User ".$userToDelete." has been deleted from the database";

} else {
    echo "No User to Delete";
}
?>