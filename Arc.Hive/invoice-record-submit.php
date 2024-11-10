<?php
include('dbcon.php');

// Retrieve and sanitize main form inputs
$invoiceID = mysqli_real_escape_string($connection, $_POST['invoiceID']);
$supplierID = mysqli_real_escape_string($connection, $_POST['supplier']);
$totalAmount = mysqli_real_escape_string($connection, $_POST['totalAmount']);
$date = date('Y-m-d'); // Current date for the record
$userID = 1; // Replace with actual user ID if needed

// Insert main invoice data into 'supply' table
$supplyQuery = "INSERT INTO supply (SupplierID, InvoiceID, Date, UserID, Total) VALUES ('$supplierID', '$invoiceID', '$date', '$userID', '$totalAmount')";
$supplyResult = mysqli_query($connection, $supplyQuery);

if (!$supplyResult) {
    die("Supply Insert Failed: " . mysqli_error($connection));
}

// Prepare to insert multiple items into 'supply-details' table
$itemIDs = $_POST['items'];
$itemQtys = $_POST['itemQty'];
$itemBasePrices = $_POST['itemBasePrice'];
$itemSubtotals = $_POST['itemSubtotal'];

for ($i = 0; $i < count($itemIDs); $i++) {
    $itemID = mysqli_real_escape_string($connection, $itemIDs[$i]);
    $itemQty = mysqli_real_escape_string($connection, $itemQtys[$i]);
    $itemBasePrice = mysqli_real_escape_string($connection, $itemBasePrices[$i]);
    $itemSubtotal = mysqli_real_escape_string($connection, $itemSubtotals[$i]);

    // Insert each item into 'supply-details' table
    $supplyDetailsQuery = "INSERT INTO `supply-details` (InvoiceID, ItemID, Quantity, `Base-Price`, Subtotal) VALUES ('$invoiceID', '$itemID', '$itemQty', '$itemBasePrice', '$itemSubtotal')";
    $supplyDetailsResult = mysqli_query($connection, $supplyDetailsQuery);

    if (!$supplyDetailsResult) {
        die("Supply Details Insert Failed: " . mysqli_error($connection) . " for InvoiceID: $invoiceID, ItemID: $itemID");
    }

    // Update the Inventory Management table to add the item quantity
    $updateInventoryQuery = "UPDATE item SET ItemQuantity = ItemQuantity + $itemQty WHERE ItemID = $itemID";
    $updateInventoryResult = mysqli_query($connection, $updateInventoryQuery);

    if (!$updateInventoryResult) {
        die("Inventory Update Failed: " . mysqli_error($connection) . " for ItemID: $itemID");
    }
}

// Redirect to a confirmation page or back to the form with a success message
header("Location: laundry-record-invoice.php?success=1");
exit();
?>

