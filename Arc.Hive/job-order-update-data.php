<?php 
include('header.php'); 
include('dbcon.php');

$row = [
    'ServiceID' => '',
    'Date' => '',
    'CustomerID' => '',
    'Weight' => '',
    'Total' => ''
];

if (isset($_GET['JobOrderID'])) {
    $JobOrderID = $_GET['JobOrderID'];

    $query = "SELECT * FROM `job-order` WHERE `JobOrderID` = '$JobOrderID'";
    $result = mysqli_query($connection, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
    } else {
        die("Query Failed: " . mysqli_error($connection));
    }
}

if (isset($_POST['update_job_order'])) {
    $ServiceID = $_POST['ServiceID'];
    $Date = $_POST['Date'];
    $CustomerID = $_POST['CustomerID'];
    $Weight = $_POST['Weight'];
    $Total = $_POST['Total'];
    $items = $_POST['items'];

    if (empty($ServiceID) || empty($Date) || empty($CustomerID) || empty($Weight) || empty($Total)) {
        header("Location: laundry-Sales.php?error=Please fill in all fields.");
        exit();
    }

    if (!filter_var($Total, FILTER_VALIDATE_FLOAT) || !filter_var($Weight, FILTER_VALIDATE_INT)) {
        header("Location: laundry-Sales.php?error=Total must be a valid decimal and Weight must be an integer.");
        exit();
    }

    $query = "UPDATE `job-order` SET 
                `ServiceID` = '$ServiceID', 
                `Date` = '$Date', 
                `CustomerID` = '$CustomerID', 
                `Weight` = '$Weight', 
                `Total` = '$Total' 
              WHERE `JobOrderID` = '$JobOrderID'";
    $result = mysqli_query($connection, $query);

    if ($result) {
        foreach ($items as $ItemID => $newQuantity) {
            $itemQuery = "SELECT ItemQuantity FROM `job-order-items` WHERE JobOrderID = '$JobOrderID' AND ItemID = '$ItemID'";
            $itemResult = mysqli_query($connection, $itemQuery);
            $oldQuantity = mysqli_fetch_assoc($itemResult)['ItemQuantity'];

            $quantityDifference = $newQuantity - $oldQuantity;
            $updateItemQuery = "UPDATE `job-order-items` SET ItemQuantity = $newQuantity, Subtotal = (SELECT Price FROM item WHERE ItemID = $ItemID) * $newQuantity WHERE JobOrderID = '$JobOrderID' AND ItemID = '$ItemID'";
            mysqli_query($connection, $updateItemQuery);

            $updateInventory = "UPDATE item SET ItemQuantity = ItemQuantity - $quantityDifference WHERE ItemID = $ItemID";
            mysqli_query($connection, $updateInventory);
        }

        header('Location: laundry-Sales.php?update_msg=Job order updated successfully.');
        exit();
    } else {
        die("Query Failed: " . mysqli_error($connection));
    }
}
?>

<form action="job-order-update-data.php?JobOrderID=<?php echo $JobOrderID; ?>" method="post">
    <!-- Form fields for updating job order details -->
</form>

<?php include('footer.php'); ?>
