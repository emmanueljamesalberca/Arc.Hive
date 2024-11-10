<?php include('header.php'); ?>
<?php include('dbcon.php'); ?>

<?php
// Initialize $row as an empty array to avoid undefined variable errors
$row = [
    'SupplierName' => '',
    'Contact' => '',
    'Address' => ''
];

// Fetching the data for the supplier to be updated
if (isset($_GET['SupplierID'])) {
    $SupplierID = $_GET['SupplierID'];

    // Fetch the record from the `supplier` table
    $query = "SELECT * FROM `supplier` WHERE `SupplierID` = '$SupplierID'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($connection));
    } else {
        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            die("No supplier found with the provided ID.");
        }
    }
} else {
    die("Supplier ID not provided.");
}
?>

<?php
// Processing the update when the form is submitted
if (isset($_POST['update_supplier'])) {
    $SupplierName = $_POST['SupplierName'];
    $Contact = $_POST['Contact'];
    $Address = $_POST['Address'];

    // Check for empty fields
    if (empty($SupplierName) || empty($Contact) || empty($Address)) {
        header("Location: supplier-update-data.php?SupplierID=$SupplierID&error=Please fill in all fields.");
        exit();
    }

    // Update the supplier details in the database
    $query = "UPDATE `supplier` SET 
                `SupplierName` = '$SupplierName', 
                `Contact` = '$Contact', 
                `Address` = '$Address' 
              WHERE `SupplierID` = '$SupplierID'";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($connection));
    } else {
        header('Location: laundry-supplier.php?update_msg=Supplier updated successfully.');
        exit();
    }
}
?>

<!-- The Update Form -->
<form action="supplier-update-data.php?SupplierID=<?php echo $SupplierID; ?>" method="post">
    <div class="form-group">
        <label for="SupplierName">Supplier Name</label>
        <input type="text" name="SupplierName" class="form-control" value="<?php echo htmlspecialchars($row['SupplierName']); ?>" required>
    </div>
    <div class="form-group">
        <label for="Contact">Contact</label>
        <input type="text" name="Contact" class="form-control" value="<?php echo htmlspecialchars($row['Contact']); ?>" required>
    </div>
    <div class="form-group">
        <label for="Address">Address</label>
        <input type="text" name="Address" class="form-control" value="<?php echo htmlspecialchars($row['Address']); ?>" required>
    </div>
    
    <input type="submit" class="btn btn-success" name="update_supplier" value="UPDATE">
</form>

<?php include('footer.php'); ?>
