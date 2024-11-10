<?php 
include('header.php'); 
include('dbcon.php');

$row = [
    'ItemID' => '',
    'Quantity' => '',
    'Base-Price' => '',
    'Subtotal' => ''
];

if (isset($_GET['InvoiceID']) && isset($_GET['ItemID'])) {
    $InvoiceID = $_GET['InvoiceID'];
    $ItemID = $_GET['ItemID'];

    $query = "SELECT * FROM `supply-details` WHERE `InvoiceID` = ? AND `ItemID` = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ii", $InvoiceID, $ItemID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    if (!$row) {
        die("No supply detail found with the provided ID.");
    }
} else {
    die("Invoice ID or Item ID not provided.");
}

if (isset($_POST['update_supply_details'])) {
    $newItemID = $_POST['ItemID'];
    $Quantity = $_POST['Quantity'];
    $BasePrice = $_POST['Base-Price'];
    $Subtotal = $Quantity * $BasePrice;

    $query = "UPDATE `supply-details` SET ItemID = ?, Quantity = ?, `Base-Price` = ?, Subtotal = ? WHERE InvoiceID = ? AND ItemID = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "iidiii", $newItemID, $Quantity, $BasePrice, $Subtotal, $InvoiceID, $ItemID);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: laundry-record-invoice.php?update_msg=You have successfully updated the supply detail.');
        exit;
    } else {
        die("Update Failed: " . mysqli_error($connection));
    }
}
?>

<!-- Update Form -->
<form action="supply-details-update-data.php?InvoiceID=<?php echo $InvoiceID; ?>&ItemID=<?php echo $ItemID; ?>" method="post" onload="calculateSubtotal()">
    <div class="form-group">
        <label for="ItemID">Item ID</label>
        <input type="number" name="ItemID" class="form-control" value="<?php echo htmlspecialchars($row['ItemID']); ?>" required>
    </div>
    <div class="form-group">
        <label for="Quantity">Quantity</label>
        <input type="number" name="Quantity" id="Quantity" class="form-control" value="<?php echo htmlspecialchars($row['Quantity']); ?>" oninput="calculateSubtotal()" required>
    </div>
    <div class="form-group">
        <label for="Base-Price">Base Price</label>
        <input type="text" name="Base-Price" id="BasePrice" class="form-control" value="<?php echo htmlspecialchars($row['Base-Price']); ?>" oninput="calculateSubtotal()" required>
    </div>
    <div class="form-group">
        <label for="Subtotal">Subtotal</label>
        <input type="text" name="Subtotal" id="Subtotal" class="form-control" value="<?php echo htmlspecialchars($row['Subtotal']); ?>" readonly>
    </div>
    <input type="submit" class="btn btn-success" name="update_supply_details" value="UPDATE">
</form>

<script>
function calculateSubtotal() {
    var quantity = document.getElementById('Quantity').value;
    var basePrice = document.getElementById('BasePrice').value;
    var subtotal = quantity * basePrice;
    document.getElementById('Subtotal').value = subtotal.toFixed(2);
}

window.onload = function() {
    calculateSubtotal();
};
</script>

<?php include('footer.php'); ?>
