<?php
include('dbcon.php');

if (isset($_POST['add_item'])) {
    $ItemName = $_POST['ItemName'];
    $ItemQuantity = $_POST['ItemQuantity'];
    $CategoryID = $_POST['CategoryID'];
    $Description = $_POST['Description'];
    $Price = $_POST['Price'];

    // Check for empty fields
    if (empty($ItemName) || empty($ItemQuantity) || empty($CategoryID) || empty($Description) || empty($Price)) {
        header("Location: laundry-Items.php?error=Please fill in all fields.");
        exit();
    }

    // Check if ItemQuantity and CategoryID are numeric
    if (!is_numeric($ItemQuantity) || !is_numeric($CategoryID)) {
        header("Location: laundry-Items.php?error=Item Quantity and Category ID must be numeric values.");
        exit();
    }

    // Check if Price is a valid float
    if (!filter_var($Price, FILTER_VALIDATE_FLOAT)) {
        header("Location: laundry-Items.php?error=Price must be a valid decimal number.");
        exit();
    }

    // Insert th	e item into the database
    $query = "INSERT INTO `item` (ItemName, ItemQuantity, CategoryID, Description, Price) 
              VALUES ('$ItemName', '$ItemQuantity', '$CategoryID', '$Description', '$Price')";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($connection));
    } else {
        header('Location: laundry-Items.php?success=Item added successfully.');
        
    }
}


?>