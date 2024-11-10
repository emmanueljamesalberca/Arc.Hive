<?php
include('dbcon.php');

if (isset($_POST['add_customer'])) {
    $Name = $_POST['Name'];
    $Email = $_POST['Email'];
    $Contact = $_POST['Contact'];
    $Address = $_POST['Address'];
    $Gender = $_POST['Gender'];
    $Age = $_POST['Age'];

    // Check for empty fields
    if (empty($Name) || empty($Email) || empty($Contact) || empty($Address) || empty($Gender) || empty($Age)) {
        header("Location: laundry-Customer.php?error=Please fill in all fields.");
        exit();
    }

    // Check if Contact and Age are numeric
    if (!is_numeric($Contact) || !is_numeric($Age)) {
        header("Location: laundry-Customer.php?error=Contact and Age must be numeric values.");
        exit();
    }

    // Validate Email format
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        header("Location: laundry-Customer.php?error=Please enter a valid email address.");
        exit();
    }

    // Insert the customer into the database
    $query = "INSERT INTO `customer` (Name, Email, Contact, Address, Gender, Age) 
          VALUES ('$Name', '$Email', '$Contact', '$Address', '$Gender', '$Age')";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($connection));
    } else {
        header('Location: laundry-Customer.php?success=Customer added successfully.');
    }
}
?>
