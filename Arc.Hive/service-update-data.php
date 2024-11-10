<?php include('header.php'); ?>
<?php include('dbcon.php'); ?>

<?php
// Initialize $row as an empty array to avoid undefined variable errors
$row = [
    'ServiceType' => '',
    'Price' => ''
];

// Fetching the data for the service to be updated
if (isset($_GET['ServiceID'])) {
    $ServiceID = $_GET['ServiceID'];

    // Fetch the record from the `service` table
    $query = "SELECT * FROM `service` WHERE `ServiceID` = '$ServiceID'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($connection));
    } else {
        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            die("No service found with the provided ID.");
        }
    }
} else {
    die("Service ID not provided.");
}
?>

<?php
// Processing the update when the form is submitted
if (isset($_POST['update_service'])) {
    $ServiceID = $_GET['ServiceID']; // Get the current service ID from the URL

    $ServiceType = $_POST['ServiceType'];
    $Price = $_POST['Price'];

    // Check for empty fields
    if (empty($ServiceType) || empty($Price)) {
        header("Location: laundry-Services.php?error=Please fill in all fields.");
        exit();
    }

    // Validate Price as a float
    if (!filter_var($Price, FILTER_VALIDATE_FLOAT)) {
        header("Location: laundry-Services.php?error=Price must be a valid decimal number.");
        exit();
    }

    // Update the service details in the database
    $query = "UPDATE `service` SET 
                `ServiceType` = '$ServiceType', 
                `Price` = '$Price' 
              WHERE `ServiceID` = '$ServiceID'";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($connection));
    } else {
        header('Location: laundry-Services.php?update_msg=Service updated successfully.');
        exit();
    }
}
?>

<!-- The Update Form -->
<form action="service-update-data.php?ServiceID=<?php echo $ServiceID; ?>" method="post">
    <div class="form-group">
        <label for="ServiceType">Service Type</label>
        <input type="text" name="ServiceType" class="form-control" value="<?php echo $row['ServiceType']; ?>" required>
    </div>
    <div class="form-group">
        <label for="Price">Price</label>
        <input type="text" name="Price" class="form-control" value="<?php echo $row['Price']; ?>" required>
    </div>
    <input type="submit" class="btn btn-success" name="update_service" value="Update Service">
</form>

<?php include('footer.php'); ?>
