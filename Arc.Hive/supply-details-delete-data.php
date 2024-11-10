<?php 
include('dbcon.php'); 

if (isset($_GET['InvoiceID']) && isset($_GET['ItemID'])) {
    $InvoiceID = $_GET['InvoiceID'];
    $ItemID = $_GET['ItemID'];

    $query = "DELETE FROM `supply-details` WHERE `InvoiceID` = ? AND `ItemID` = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ii", $InvoiceID, $ItemID);

    if (mysqli_stmt_execute($stmt)) {
        echo "Record deleted successfully.";
        header('Location: laundry-supply_details.php?delete_msg=Record deleted successfully.');
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($connection);
    }
} else {
    echo "Error: InvoiceID or ItemID not set.";
}
?>
