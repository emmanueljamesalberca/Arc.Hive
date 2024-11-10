<?php include('dbcon.php'); ?>

<?php

if (isset($_GET['ServiceID'])) {
    $ServiceID = $_GET['ServiceID'];
    echo "Deleting Service with ID: " . $ServiceID;  // Debug: Check if the ID is being received correctly.

    // Delete the record from the `service` table
    $query = "DELETE FROM `service` WHERE `ServiceID` = '$ServiceID'";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($connection));
    } else {
        header('Location: laundry-Services.php?delete_msg=Service deleted successfully.');
        exit();
    }
} else {
    echo "ServiceID not set.";
}

?>
