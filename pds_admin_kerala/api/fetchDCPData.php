<?php
require('../util/Connection.php');

function isOnlyCharacters($input) {
    if (!preg_match('/^[a-zA-Z]+$/', $input)) {
        die("");
    }
    return true;
}

$district = isOnlyCharacters($_POST['district']);

$query = "SELECT * FROM dcp WHERE district='$district'";
$result = mysqli_query($con,$query);
$numrows = mysqli_num_rows($result);

$data = null;

while($row = mysqli_fetch_assoc($result)){
	$data[] = $row;
}

$resultarray = [];
if($data==null){
	$data = array();
}
$resultarray["data"] = $data;
echo json_encode($resultarray);
?>
