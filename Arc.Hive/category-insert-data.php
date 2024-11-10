<?php
include('dbcon.php');

if (isset($_POST['add_category'])) {
    $CategoryName = trim($_POST['CategoryName']);

    // Check if Category Name is provided
    if (empty($CategoryName)) {
        header("Location: laundry-category.php?error=Please fill in the Category Name.");
        exit();
    }

    // Insert the category into the database, letting CategoryID auto-increment
    $query = "INSERT INTO `category` (CategoryName) VALUES ('$CategoryName')";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($connection));
    } else {
        header("Location: laundry-category.php?success=Category added successfully.");
        exit();
    }
}
?>
