<?php
// update-quantity.php
include('dbcon.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs to prevent SQL injection
    $itemId = mysqli_real_escape_string($connection, $_POST['itemId']);
    $action = mysqli_real_escape_string($connection, $_POST['action']);
    
    // Validate that itemId is a number and action is either "increase" or "decrease"
    if (is_numeric($itemId) && in_array($action, ['increase', 'decrease'])) {
        
        // Fetch the current quantity from the database
        $query = "SELECT ItemQuantity FROM item WHERE ItemID = $itemId";
        $result = mysqli_query($connection, $query);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            $currentQuantity = $row['ItemQuantity'];
            
            // Update quantity based on action
            if ($action === 'increase') {
                $newQuantity = $currentQuantity + 1;
            } elseif ($action === 'decrease' && $currentQuantity > 0) {
                $newQuantity = $currentQuantity - 1;
            } else {
                $newQuantity = $currentQuantity; // Prevent negative quantity
            }

            // Update the database with the new quantity
            $updateQuery = "UPDATE item SET ItemQuantity = $newQuantity WHERE ItemID = $itemId";
            if (mysqli_query($connection, $updateQuery)) {
                // Return the new quantity on success
                echo $newQuantity;
            } else {
                // Handle update failure
                echo "Error updating quantity: " . mysqli_error($connection);
            }
        } else {
            // Handle error if item not found
            echo "Item not found or query failed.";
        }
    } else {
        // Invalid input handling
        echo "Invalid input data.";
    }
} else {
    // Invalid request method handling
    echo "Invalid request method.";
}
?>
