<?php 
ob_start(); // Start output buffering

include('header.php'); 
include('dbcon.php'); 

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $serviceId = intval($_POST['service']);
    $date = $_POST['date'];
    $customerId = $_POST['customer'] === 'N/A' ? NULL : intval($_POST['customer']);
    $weight = floatval($_POST['weight']);
    $finalAmount = floatval(str_replace('₱', '', $_POST['finalAmount'])); // Convert to float after removing currency symbol

    // Insert the job order into the `job-order` table
    $jobOrderQuery = "INSERT INTO `job-order` (ServiceID, Date, CustomerID, Weight, Total) VALUES ($serviceId, '$date', " . ($customerId ? $customerId : "NULL") . ", $weight, $finalAmount)";
    $jobOrderResult = mysqli_query($connection, $jobOrderQuery);

    if (!$jobOrderResult) {
        die("Failed to create job order: " . mysqli_error($connection));
    }

    // Get the JobOrderID of the newly created job order
    $jobOrderId = mysqli_insert_id($connection);

    // Debugging information to ensure we're getting data
    echo "JobOrderID: $jobOrderId<br>";
    echo "Items:<br>";
    print_r($_POST['items']); // Ensure items are being received
    echo "<br>Quantities:<br>";
    
    // Check if there are any selected items
    if (isset($_POST['items'])) {
        // Loop through selected items to get their quantities
        foreach ($_POST['items'] as $itemId) {
            $quantityField = 'quantity' . $itemId;
    
            // Check if the quantity field exists in the POST data
            if (isset($_POST[$quantityField]) && intval($_POST[$quantityField]) > 0) {
                $quantityUsed = intval($_POST[$quantityField]);
    
                // Debug: Check quantity for each item
                echo "ItemID: $itemId - Quantity: $quantityUsed<br>";
    
                if ($quantityUsed > 0) {
                    // Calculate subtotal
                    $priceQuery = "SELECT Price FROM item WHERE ItemID = $itemId";
                    $priceResult = mysqli_query($connection, $priceQuery);
                    $priceRow = mysqli_fetch_assoc($priceResult);
                    $price = $priceRow['Price'];
                    $subtotal = $price * $quantityUsed;
    
                    // Insert the job order item into `job-order-items` table
                    $jobOrderItemQuery = "INSERT INTO `job-order-items` (JobOrderID, ItemID, ItemQuantity, Subtotal) VALUES ($jobOrderId, $itemId, $quantityUsed, $subtotal)";
                    $jobOrderItemResult = mysqli_query($connection, $jobOrderItemQuery);
    
                    if (!$jobOrderItemResult) {
                        die("Failed to add job order item: " . mysqli_error($connection));
                    }
    
                    // Update the item quantity in the inventory
                    $updateQuery = "UPDATE item SET ItemQuantity = ItemQuantity - $quantityUsed WHERE ItemID = $itemId";
                    $updateResult = mysqli_query($connection, $updateQuery);
    
                    if (!$updateResult) {
                        die("Failed to update inventory: " . mysqli_error($connection));
                    }
                }
            } else {
                echo "Quantity for ItemID $itemId not found or is zero in POST data.<br>"; // Debugging
            }
        }
    } else {
        echo "No items selected."; // Debugging
    }


    // Redirect to a success page
    header("Location: job-order-success.php?job_order_id=" . $jobOrderId);
    exit;
}

ob_end_flush(); // End output buffering and flush the output
?>
