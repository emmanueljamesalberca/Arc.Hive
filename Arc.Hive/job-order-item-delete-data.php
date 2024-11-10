<?php 
include('dbcon.php');

if (isset($_GET['JobOrderID']) && isset($_GET['ItemID'])) {
    $JobOrderID = $_GET['JobOrderID'];
    $ItemID = $_GET['ItemID'];

    // Fetch the quantity of the item being deleted to update inventory
    $quantityQuery = "SELECT ItemQuantity FROM `job-order-items` WHERE `JobOrderID` = '$JobOrderID' AND `ItemID` = '$ItemID'";
    $quantityResult = mysqli_query($connection, $quantityQuery);
    $row = mysqli_fetch_assoc($quantityResult);
    $quantityUsed = $row['ItemQuantity'];

    // Delete the job order item
    $query = "DELETE FROM `job-order-items` WHERE `JobOrderID` = '$JobOrderID' AND `ItemID` = '$ItemID'";
    $result = mysqli_query($connection, $query);

    if ($result) {
        // Restore the inventory
        $updateInventory = "UPDATE item SET ItemQuantity = ItemQuantity + $quantityUsed WHERE ItemID = $ItemID";
        mysqli_query($connection, $updateInventory);

        header('Location: laundry-Sales.php?delete_msg=Job order item deleted successfully.');
        exit();
    } else {
        die("Query Failed: " . mysqli_error($connection));
    }
} else {
    echo "JobOrderID or ItemID not set.";
}
?>