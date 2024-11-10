<?php 
include('dbcon.php');

if (isset($_GET['JobOrderID'])) {
    $JobOrderID = $_GET['JobOrderID'];

    // Fetch the job order items associated with the JobOrderID to restore inventory
    $itemQuery = "SELECT ItemID, ItemQuantity FROM `job-order-items` WHERE JobOrderID = '$JobOrderID'";
    $itemResult = mysqli_query($connection, $itemQuery);

    // Restore inventory for each item in the job order
    while ($itemRow = mysqli_fetch_assoc($itemResult)) {
        $ItemID = $itemRow['ItemID'];
        $ItemQuantity = $itemRow['ItemQuantity'];
        $updateInventory = "UPDATE item SET ItemQuantity = ItemQuantity + $ItemQuantity WHERE ItemID = $ItemID";
        mysqli_query($connection, $updateInventory);
    }

    // Delete the job order items related to the JobOrderID
    $deleteItemsQuery = "DELETE FROM `job-order-items` WHERE `JobOrderID` = '$JobOrderID'";
    mysqli_query($connection, $deleteItemsQuery);

    // Delete the job order itself
    $query = "DELETE FROM `job-order` WHERE `JobOrderID` = '$JobOrderID'";
    $result = mysqli_query($connection, $query);

    if ($result) {
        header('Location: laundry-Sales.php?delete_msg=Job order deleted successfully.');
        exit();
    } else {
        die("Query Failed: " . mysqli_error($connection));
    }
} else {
    echo "JobOrderID not set.";
}
?>
