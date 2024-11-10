<?php include('header.php'); ?>
<?php include('dbcon.php'); ?>

<?php
// Initialize $row as an empty array to avoid undefined variable errors
$row = [
    'Name' => '',
    'Email' => '',
    'Contact' => '',
    'Address' => '',
    'Gender' => '',
    'Age' => ''
];

// Fetching the data for the customer to be updated
if(isset($_GET['CustomerID'])){
    $CustomerID = $_GET['CustomerID'];

    // Fetch the record from the `customer` table
    $query = "SELECT * FROM `customer` WHERE `CustomerID` = '$CustomerID'";
    $result = mysqli_query($connection, $query);

    if(!$result){
        die("Query Failed: " . mysqli_error($connection));
    } else {
        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            die("No customer found with the provided ID.");
        }
    }
} else {
    die("Customer ID not provided.");
}
?>

<?php
// Processing the update when the form is submitted
if(isset($_POST['update_customer'])){
    $CustomerID = $_GET['CustomerID']; // Get the current customer ID from the URL

    $Name = $_POST['Name'];
    $Email = $_POST['Email'];
    $Contact = $_POST['Contact'];
    $Address = $_POST['Address'];
    $Gender = $_POST['Gender'];
    $Age = $_POST['Age'];

    // Update the customer details in the database
    $query = "UPDATE `customer` SET 
                `Name` = '$Name', 
                `Email` = '$Email', 
                `Contact` = '$Contact', 
                `Address` = '$Address', 
                `Gender` = '$Gender', 
                `Age` = '$Age' 
              WHERE `CustomerID` = '$CustomerID'";

    $result = mysqli_query($connection, $query);

    if(!$result){
        die("Query Failed: " . mysqli_error($connection));
    } else {
        header('Location: laundry-Customer.php?update_msg=You have successfully updated the customer data.');
        exit;
    }
}
?>

<!-- The Update Form -->
<form action="customer-update-data.php?CustomerID=<?php echo $CustomerID; ?>" method="post">
    <div class="form-group">
        <label for="Name">Name</label>
        <input type="text" name="Name" class="form-control" value="<?php echo $row['Name']; ?>" required>
    </div>
    <div class="form-group">
        <label for="Email">Email</label>
        <input type="email" name="Email" class="form-control" value="<?php echo $row['Email']; ?>" required>
    </div>
    <div class="form-group">
        <label for="Contact">Contact</label>
        <input type="text" name="Contact" class="form-control" value="<?php echo $row['Contact']; ?>" required>
    </div>
    <div class="form-group">
        <label for="Address">Address</label>
        <input type="text" name="Address" class="form-control" value="<?php echo $row['Address']; ?>" required>
    </div>
    <div class="form-group">
        <label for="Gender">Gender</label>
        <input type="text" name="Gender" class="form-control" value="<?php echo $row['Gender']; ?>" required>
    </div>
    <div class="form-group">
        <label for="Age">Age</label>
        <input type="number" name="Age" class="form-control" value="<?php echo $row['Age']; ?>" required>
    </div>
    <input type="submit" class="btn btn-success" name="update_customer" value="UPDATE">
</form>

<?php include('footer.php'); ?>
