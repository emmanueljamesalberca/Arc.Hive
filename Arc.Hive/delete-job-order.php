<?php
include('dbcon.php');

if (isset($_GET['JobOrderID'])) {
    $jobOrderID = $_GET['JobOrderID'];

    // Delete job order items
    $deleteItemsQuery = "DELETE FROM `job-order-items` WHERE JobOrderID = $jobOrderID";
    mysqli_query($connection, $deleteItemsQuery);

    // Delete job order
    $deleteOrderQuery = "DELETE FROM `job-order` WHERE JobOrderID = $jobOrderID";
    if (mysqli_query($connection, $deleteOrderQuery)) {
        echo "Job Order #$jobOrderID and its items were deleted successfully.";
    } else {
        echo "Error deleting Job Order #$jobOrderID: " . mysqli_error($connection);
    }
}
?>
