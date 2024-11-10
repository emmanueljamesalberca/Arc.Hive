<?php include('header.php'); ?>
<?php include('dbcon.php'); ?>

<?php
// Initialize $row as an empty array to avoid undefined variable errors
$row = [
    'ItemName' => '',
    'ItemQuantity' => '',
    'CategoryID' => '',
    'Description' => '',
    'Price' => ''
];

// Fetching the data for the item to be updated
if(isset($_GET['ItemID'])){
    $ItemID = $_GET['ItemID'];

    // Fetch the record from the `item` table
    $query = "SELECT * FROM `item` WHERE `ItemID` = '$ItemID'";
    $result = mysqli_query($connection, $query);

    if(!$result){
        die("Query Failed: " . mysqli_error($connection));
    } else {
        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            die("No item found with the provided ID.");
        }
    }
} else {
    die("Item ID not provided.");
}
?>

<?php
// Processing the update when the form is submitted
if(isset($_POST['update_item'])){
    $ItemID = $_GET['ItemID']; // Get the current item ID from the URL

    $ItemName = $_POST['ItemName'];
    $ItemQuantity= $_POST['ItemQuantity'];
    $CategoryID = $_POST['CategoryID'];
    $Description = $_POST['Description'];
    $Price = $_POST['Price'];

    // Update the item details in the database
    $query = "UPDATE `item` SET 
                `ItemName` = '$ItemName', 
                `ItemQuantity` = '$ItemQuantity', 
                `CategoryID` = '$CategoryID', 
                `Description` = '$Description', 
                `Price` = '$Price' 
              WHERE `ItemID` = '$ItemID'";

    $result = mysqli_query($connection, $query);

    if(!$result){
        die("Query Failed: " . mysqli_error($connection));
    } else {
        header('Location: laundry-Items.php?update_msg=You have successfully updated the data.');
        exit;
    }
}
?>

<!-- The Update Form -->
<form action="item-update-data.php?ItemID=<?php echo $ItemID; ?>" method="post">
    <div class="form-group">
        <label for="ItemName">Item Name</label>
        <input type="text" name="ItemName" class="form-control" value="<?php echo $row['ItemName']; ?>" required>
    </div>
    <div class="form-group">
        <label for="ItemQuantity">Item Quantity</label>
        <input type="number" name="ItemQuantity" class="form-control" value="<?php echo $row['ItemQuantity']; ?>" required>
    </div>
    <div class="form-group">
        <label for="CategoryID">Category ID</label>
        <input type="number" name="CategoryID" class="form-control" value="<?php echo $row['CategoryID']; ?>" required>
    </div>
    <div class="form-group">
        <label for="Description">Description</label>
        <input type="text" name="Description" class="form-control" value="<?php echo $row['Description']; ?>" required>
    </div>
    <div class="form-group">
        <label for="Price">Price</label>
        <input type="text" name="Price" class="form-control" value="<?php echo $row['Price']; ?>" required>
    </div>
    <input type="submit" class="btn btn-success" name="update_item" value="UPDATE">
</form>

<?php include('footer.php'); ?>
