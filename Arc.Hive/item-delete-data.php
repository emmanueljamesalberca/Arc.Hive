<?php include('dbcon.php'); ?>


<?php

if(isset($_GET['ItemID'])){
    $ItemID = $_GET['ItemID'];
    echo "Deleting Item with ID: " . $ItemID;  // Debug: Check if the ID is being received correctly.

    $query = "delete from `item` where `ItemID` = '$ItemID'";

    $result = mysqli_query($connection, $query);

    if(!$result){
        die("Query Failed".mysqli_error($connection));
    }
    else{
        header('location: laundry-Items.php?delete_msg=You have deleted the record.');
    }
} else {
    echo "ItemID not set.";
}
if ($deleteResult) {
    header('Location: laundry-Items.php?deleted=Item deleted successfully.');
    exit();
}

?>
