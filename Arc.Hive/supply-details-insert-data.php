<?php 
include('header.php'); 
include 'dbcon.php';

if (isset($_POST['add_supply_details'])) {
    $InvoiceID = $_POST['InvoiceID'];
    $ItemID = $_POST['ItemID'];
    $Quantity = $_POST['Quantity'];
    $BasePrice = $_POST['Base-Price'];
    $Subtotal = $Quantity * $BasePrice; // Calculate Subtotal here

    $query = "INSERT INTO `supply-details` (InvoiceID, ItemID, Quantity, `Base-Price`, Subtotal) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "iiidd", $InvoiceID, $ItemID, $Quantity, $BasePrice, $Subtotal);

    if (mysqli_stmt_execute($stmt)) {
        // Update item quantity in stock
        $updateQuery = "UPDATE `item` SET `ItemQuantity` = `ItemQuantity` + ? WHERE `ItemID` = ?";
        $updateStmt = mysqli_prepare($connection, $updateQuery);
        mysqli_stmt_bind_param($updateStmt, "ii", $Quantity, $ItemID);
        mysqli_stmt_execute($updateStmt);
        mysqli_stmt_close($updateStmt);

        // Recalculate and update the total for the InvoiceID
        $totalQuery = "SELECT SUM(Subtotal) AS Total FROM `supply-details` WHERE InvoiceID = ?";
        $totalStmt = mysqli_prepare($connection, $totalQuery);
        mysqli_stmt_bind_param($totalStmt, "i", $InvoiceID);
        mysqli_stmt_execute($totalStmt);
        mysqli_stmt_bind_result($totalStmt, $newTotal);
        mysqli_stmt_fetch($totalStmt);
        mysqli_stmt_close($totalStmt);

        // Update the total in the supply table
        $updateTotalQuery = "UPDATE `supply` SET Total = ? WHERE InvoiceID = ?";
        $updateTotalStmt = mysqli_prepare($connection, $updateTotalQuery);
        mysqli_stmt_bind_param($updateTotalStmt, "di", $newTotal, $InvoiceID);
        mysqli_stmt_execute($updateTotalStmt);
        mysqli_stmt_close($updateTotalStmt);

        header("Location: laundry-supply_details.php?success=Supply details added successfully.");
    } else {
        die("Insert Failed: " . mysqli_error($connection));
    }
}
?>
