<?php include('dbcon.php'); ?>

<?php

if(isset($_GET['CustomerID'])){
    $CustomerID = $_GET['CustomerID'];
    echo "Deleting Customer with ID: " . $CustomerID;  // Debug: Check if the ID is being received correctly.

    // Delete the record from the `customer` table
    $query = "DELETE FROM `customer` WHERE `CustomerID` = '$CustomerID'";

    $result = mysqli_query($connection, $query);

    if(!$result){
        die("Query Failed: " . mysqli_error($connection));
    }
    else{
        header('Location: laundry-Customer.php?delete_msg=You have deleted the customer record.');
        exit();
    }
} else {
    echo "CustomerID not set.";
}

?>

