<?php
include('dbcon.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $InvoiceID = $_POST['InvoiceID'];
    $Date = $_POST['Date'];
    $UserID = $_POST['UserID'];
    $Total = $_POST['Total'];

    // Use prepared statements for secure data handling
    $query = "INSERT INTO supply (InvoiceID, Date, UserID, Total) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "isid", $InvoiceID, $Date, $UserID, $Total);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: laundry-supply_details.php?success=Supply added successfully");
    } else {
        header("Location: laundry-supply_details.php?error=" . urlencode(mysqli_error($connection)));
    }
    mysqli_stmt_close($stmt);
} else {
    header("Location: laundry-supply_details.php?error=Invalid request method");
}
?>
