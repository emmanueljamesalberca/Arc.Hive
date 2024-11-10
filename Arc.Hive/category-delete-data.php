<?php
include('dbcon.php');

if (isset($_GET['CategoryID'])) {
    $CategoryID = $_GET['CategoryID'];
    
    // Debugging: Ensure the ID is being received correctly
    // echo "Deleting Category with ID: " . $CategoryID;

    // Delete the category from the `category` table
    $query = "DELETE FROM `category` WHERE `CategoryID` = '$CategoryID'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($connection));
    } else {
        header('Location: laundry-category.php?delete_msg=Category deleted successfully.');
        exit;
    }
} else {
    echo "CategoryID not set.";
}
?>
