<?php
require('../util/Connection.php');
require('../structures/Login.php');
require('../util/SessionFunction.php');
require('../util/Security.php');
require ('../util/Encryption.php');
require('../util/Logger.php');
$nonceValue = 'nonce_value';

if(!SessionCheck()){
	return;
}

require('Header.php');
$person = new Login;
$person->setUsername($_POST["username"]);
$Encryption = new Encryption();
$person->setPassword($Encryption->decrypt($_POST["password"], $nonceValue));

if($_SESSION['user']!=$person->getUsername()){
    echo "User is logged in with a different username and password";
    return;
}

if($_POST["newpassword"]!="New Password If you want to change"){

    if (strlen($_POST["newpassword"]) < 5 && strlen($_POST["newusername"]) < 5) {
        echo "Password must be at least 5 characters long.";
        return;
    }

    $newusername = htmlspecialchars($_POST["newusername"], ENT_QUOTES, 'UTF-8');

    // Ensure the new username doesn't contain special characters (optional)
    if (!preg_match('/^[a-zA-Z0-9_@]+$/', $newusername)) {
        echo "Username can only contain letters, numbers, underscores and @.";
        return;
    }
}
// Query the database to get the stored hash for the username
$query = "SELECT * FROM login WHERE username='".$person->getUsername()."'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);

// Check if the username exists and verify the password using password_verify
echo 
$dbHashedPassword = $row['password'];
if (password_verify($person->getPassword(), $dbHashedPassword)) {
    
    $person = new Login;
    $person->setUsername($_POST["newusername"]);
    $person->setPassword($_POST["newpassword"]);
    $person->setRole($_POST["district"]);
    $uid = uniqid();

    // Hash the new password before inserting it into the database
    $hashedPassword = password_hash($person->getPassword(), PASSWORD_DEFAULT);

    // Check if the new username already exists
    $query = "SELECT * FROM login WHERE username='".$person->getUsername()."'";
    $result = mysqli_query($con, $query);
    $numrows = mysqli_num_rows($result);

    if($numrows == 0){
        echo "Error : Username doesn't exists";
    } else {
        // Insert the new user with the hashed password
        if($_POST["newpassword"]!="New Password If you want to change"){
            $query1 = "UPDATE login SET password='".$hashedPassword."', role='".strtolower($person->getRole())."' WHERE username='".$person->getUsername()."'";

        }
        else{
            $query1 = "UPDATE login SET role='".strtolower($person->getRole())."' WHERE username='".$person->getUsername()."'";
        }
        mysqli_query($con, $query1);
        mysqli_close($con);
		$filteredPost = $_POST;
		unset($filteredPost['username'], $filteredPost['password']);
		writeLog("User ->" ." User Add ->". $_SESSION['user'] . "| Requested JSON -> " . json_encode($filteredPost). " | " . $person->getUsername());
        echo "<script>window.location.href = '../Userdata.php';</script>";
    }

} else {
    // Password is incorrect
    echo "Error : Password or Username is incorrect";
    return;
}
?>
<?php require('Fullui.php'); ?>
