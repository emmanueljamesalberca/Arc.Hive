<?php
include('dbcon.php');

if (isset($_POST['add_service'])) {
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

    // Insert the new service into the database
    $query = "INSERT INTO `service` (ServiceType, Price) 
          VALUES ('$ServiceType', '$Price')";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($connection));
    } else {
        header('Location: laundry-Services.php?success=Service added successfully.');
        exit();
    }
}
?>
