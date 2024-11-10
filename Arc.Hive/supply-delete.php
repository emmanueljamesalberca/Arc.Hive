<?php
include('dbcon.php');

if (isset($_GET['SupplierID']) && isset($_GET['InvoiceID'])) {
    $SupplierID = $_GET['SupplierID'];
    $InvoiceID = $_GET['InvoiceID'];

    // Use a prepared statement to securely delete the specific entry
    $query = "DELETE FROM supply WHERE SupplierID = ? AND InvoiceID = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ii", $SupplierID, $InvoiceID);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: laundry-supply_details.php?success=Supply deleted successfully");
    } else {
        header("Location: laundry-supply_details.php?error=" . urlencode(mysqli_error($connection)));
    }
    mysqli_stmt_close($stmt);
} else {
    header("Location: laundry-supply_details.php?error=SupplierID or InvoiceID parameter missing from request");
}
?>
