<?php
include('dbcon.php');

if (isset($_POST['add_job_order'])) {
    $ServiceID = $_POST['ServiceID'];
    $Date = $_POST['Date'];
    $CustomerID = $_POST['CustomerID'];
    $Weight = $_POST['Weight'];
    $Total = $_POST['Total'];
    $items = $_POST['items']; // Assume `items` is an associative array with ItemID as key and quantity as value

    if (empty($ServiceID) || empty($Date) || empty($CustomerID) || empty($Weight) || empty($Total)) {
        header("Location: laundry-Sales.php?error=Please fill in all fields.");
        exit();
    }

    if (!filter_var($Total, FILTER_VALIDATE_FLOAT) || !filter_var($Weight, FILTER_VALIDATE_INT)) {
        header("Location: laundry-Sales.php?error=Total must be a valid decimal and Weight must be an integer.");
        exit();
    }

    $query = "INSERT INTO `job-order` (ServiceID, Date, CustomerID, Weight, Total) 
              VALUES ('$ServiceID', '$Date', '$CustomerID', '$Weight', '$Total')";
    $result = mysqli_query($connection, $query);

    if ($result) {
        $JobOrderID = mysqli_insert_id($connection);

        // Adjust inventory for each item
        foreach ($items as $ItemID => $ItemQuantity) {
            $Subtotal = /* Calculate Subtotal based on item price */;
            $insertItemQuery = "INSERT INTO `job-order-items` (JobOrderID, ItemID, ItemQuantity, Subtotal) 
                                VALUES ('$JobOrderID', '$ItemID', '$ItemQuantity', '$Subtotal')";
            mysqli_query($connection, $insertItemQuery);

            $updateInventory = "UPDATE item SET ItemQuantity = ItemQuantity - $ItemQuantity WHERE ItemID = $ItemID";
            mysqli_query($connection, $updateInventory);
        }

        header('Location: laundry-Sales.php?success=Job order added successfully.');
        exit();
    } else {
        die("Query Failed: " . mysqli_error($connection));
    }
}
?>
