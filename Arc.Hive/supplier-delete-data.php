<?php
include('dbcon.php');

if (isset($_GET['SupplierID'])) {
    $SupplierID = $_GET['SupplierID'];

    // Delete the supplier
    $query = "DELETE FROM `supplier` WHERE `SupplierID` = '$SupplierID'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($connection));
    } else {
        header('Location: laundry-supplier.php?delete_msg=Supplier deleted successfully.');
        exit();
    }
} else {
    header('Location: laundry-supplier.php?error=Supplier ID not set.');
    exit();
}
?>
