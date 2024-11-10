<?php
include('dbcon.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $SupplierID = $_POST['SupplierID'];
    $InvoiceID = $_POST['InvoiceID'];
    $Date = $_POST['Date'];
    $UserID = $_POST['UserID'];
    $Total = $_POST['Total'];

    // Use prepared statements to prevent SQL injection
    $query = "UPDATE supply SET InvoiceID = ?, Date = ?, UserID = ?, Total = ? WHERE SupplierID = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "isidi", $InvoiceID, $Date, $UserID, $Total, $SupplierID);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: laundry-supply_details.php?success=Supply updated successfully");
    } else {
        header("Location: laundry-supply_details.php?error=" . urlencode(mysqli_error($connection)));
    }
    mysqli_stmt_close($stmt);
} else {
    header("Location: laundry-supply_details.php?error=Invalid request method");
}
?>
