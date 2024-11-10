<?php
include('dbcon.php');

if (isset($_POST['add_supplier'])) {
    $SupplierName = $_POST['SupplierName'];
    $Contact = $_POST['Contact'];
    $Address = $_POST['Address'];

    // Check for empty fields
    if (empty($SupplierName) || empty($Contact) || empty($Address)) {
        header("Location: laundry-supplier.php?error=Please fill in all fields.");
        exit();
    }

    // Insert the supplier into the database
    $query = "INSERT INTO `supplier` (SupplierName, Contact, Address) VALUES ('$SupplierName', '$Contact', '$Address')";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($connection));
    } else {
        header('Location: laundry-supplier.php?success=Supplier added successfully.');
        exit();
    }
}
?>
