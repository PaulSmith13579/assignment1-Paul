<?php include "template.php"; ?>
    <title>User Profile</title>

    <h1 class='text-primary'>Profile Page</h1>
<?php

echo $_SESSION["user_id"];
echo "<br>";
echo $_SESSION["username"];
echo "<br>";
echo $_SESSION["Password"];
echo "<br>";
echo $_SESSION["name"];
echo "<br>";
echo $_SESSION["profilePic"];
echo "<br>";
echo $_SESSION['level'];
echo "<br>";




?>