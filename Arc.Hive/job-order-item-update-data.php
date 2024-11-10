<?php 
include('header.php'); 
include('dbcon.php'); 

$row = [
    'ItemQuantity' => '',
    'Subtotal' => ''
];

if (isset($_GET['JobOrderID']) && isset($_GET['ItemID'])) {
    $JobOrderID = $_GET['JobOrderID'];
    $ItemID = $_GET['ItemID'];

    // Retrieve the existing item details
    $query = "SELECT * FROM `job-order-items` WHERE `JobOrderID` = '$JobOrderID' AND `ItemID` = '$ItemID'";
    $result = mysqli_query($connection, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
    } else {
        die("Query Failed: " . mysqli_error($connection));
    }
} else {
    die("Job Order ID or Item ID not provided.");
}

if (isset($_POST['update_job_order_item'])) {
    $ItemQuantity = $_POST['ItemQuantity'];
    $Subtotal = $_POST['Subtotal'];

    if (empty($ItemQuantity) || empty($Subtotal)) {
        header("Location: laundry-Sales.php?error=Please fill in all fields.");
        exit();
    }

    if (!filter_var($Subtotal, FILTER_VALIDATE_FLOAT) || !filter_var($ItemQuantity, FILTER_VALIDATE_INT)) {
        header("Location: laundry-Sales.php?error=Subtotal must be a valid decimal and Item Quantity must be an integer.");
        exit();
    }

    // Calculate the difference in quantity
    $oldQuantity = $row['ItemQuantity'];
    $quantityDifference = $ItemQuantity - $oldQuantity;

    // Update the job order item
    $query = "UPDATE `job-order-items` SET 
                `ItemQuantity` = '$ItemQuantity', 
                `Subtotal` = '$Subtotal' 
              WHERE `JobOrderID` = '$JobOrderID' AND `ItemID` = '$ItemID'";

    $result = mysqli_query($connection, $query);

    if ($result) {
        // Adjust inventory
        $updateInventory = "UPDATE item SET ItemQuantity = ItemQuantity - $quantityDifference WHERE ItemID = $ItemID";
        mysqli_query($connection, $updateInventory);

        header('Location: laundry-Sales.php?update_msg=Job order item updated successfully.');
        exit();
    } else {
        die("Query Failed: " . mysqli_error($connection));
    }
}
?>

<form action="job-order-item-update-data.php?JobOrderID=<?php echo $JobOrderID; ?>&ItemID=<?php echo $ItemID; ?>" method="post">
    <div class="form-group">
        <label for="ItemQuantity">Item Quantity</label>
        <input type="number" name="ItemQuantity" class="form-control" value="<?php echo $row['ItemQuantity']; ?>" required>
    </div>
    <div class="form-group">
        <label for="Subtotal">Subtotal</label>
        <input type="text" name="Subtotal" class="form-control" value="<?php echo $row['Subtotal']; ?>" required>
    </div>
    <input type="submit" class="btn btn-success" name="update_job_order_item" value="Update Job Order Item">
</form>

<?php include('footer.php'); ?>
