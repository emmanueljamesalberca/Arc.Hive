<?php
include('dbcon.php');

if (isset($_POST['add_job_order_item'])) {
    $JobOrderID = $_POST['JobOrderID'];
    $ItemID = $_POST['ItemID'];
    $ItemQuantity = $_POST['ItemQuantity'];
    $Subtotal = $_POST['Subtotal'];

    // Check for empty fields
    if (empty($JobOrderID) || empty($ItemID) || empty($ItemQuantity) || empty($Subtotal)) {
        header("Location: laundry-Sales.php?error=Please fill in all fields.");
        exit();
    }

    // Validate Subtotal as a float and ItemQuantity as an integer
    if (!filter_var($Subtotal, FILTER_VALIDATE_FLOAT) || !filter_var($ItemQuantity, FILTER_VALIDATE_INT)) {
        header("Location: laundry-Sales.php?error=Subtotal must be a valid decimal and Item Quantity must be an integer.");
        exit();
    }

    // Insert the job order item into the database
    $query = "INSERT INTO `job-order-items` (JobOrderID, ItemID, ItemQuantity, Subtotal) 
              VALUES ('$JobOrderID', '$ItemID', '$ItemQuantity', '$Subtotal')";

    $result = mysqli_query($connection, $query);

    if ($result) {
        // Reduce the inventory
        $updateInventory = "UPDATE item SET ItemQuantity = ItemQuantity - $ItemQuantity WHERE ItemID = $ItemID";
        mysqli_query($connection, $updateInventory);

        header('Location: laundry-Sales.php?success=Job order item added successfully.');
        exit();
    } else {
        die("Query Failed: " . mysqli_error($connection));
    }
}
?>
