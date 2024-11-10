<?php
include('dbcon.php');

if (isset($_GET['InvoiceID'])) {
    $invoiceID = $_GET['InvoiceID'];

    // Delete from `supply` table
    $deleteSupplyQuery = "DELETE FROM supply WHERE InvoiceID = '$invoiceID'";
    $deleteSupplyResult = mysqli_query($connection, $deleteSupplyQuery);

    // Delete from `supply-details` table
    $deleteSupplyDetailsQuery = "DELETE FROM `supply-details` WHERE InvoiceID = '$invoiceID'";
    $deleteSupplyDetailsResult = mysqli_query($connection, $deleteSupplyDetailsQuery);

    if ($deleteSupplyResult && $deleteSupplyDetailsResult) {
        header('Location: laundry-supply_details.php?delete_msg=Invoice deleted successfully from both tables.');
        exit();
    } else {
        die("Failed to delete Invoice: " . mysqli_error($connection));
    }
} else {
    die("Invoice ID not provided.");
}
?>