<?php
include('header.php');
include('dbcon.php');

// Initialize `$row` as an empty array to avoid undefined variable errors
$row = ['CategoryName' => ''];

// Fetching data for the category to be updated
if (isset($_GET['CategoryID'])) {
    $CategoryID = $_GET['CategoryID'];

    // Fetch the record from the `category` table
    $query = "SELECT * FROM `category` WHERE `CategoryID` = '$CategoryID'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($connection));
    } else {
        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            die("No category found with the provided ID.");
        }
    }
} else {
    die("Category ID not provided.");
}

// Processing the update when the form is submitted
if (isset($_POST['update_category'])) {
    $CategoryName = $_POST['CategoryName'];

    // Update the category details in the database
    $query = "UPDATE `category` SET 
                `CategoryName` = '$CategoryName'
              WHERE `CategoryID` = '$CategoryID'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($connection));
    } else {
        header('Location: laundry-category.php?update_msg=Category updated successfully.');
        exit;
    }
}
?>

<!-- The Update Form -->
<div class="container">
    <h2>Update Category</h2>
    <form action="category-update-data.php?CategoryID=<?php echo $CategoryID; ?>" method="post">
        <div class="form-group">
            <label for="CategoryName">Category Name</label>
            <input type="text" name="CategoryName" class="form-control" value="<?php echo htmlspecialchars($row['CategoryName']); ?>" required>
        </div>
        
        <input type="submit" class="btn btn-success" name="update_category" value="UPDATE">
    </form>
</div>

<?php include('footer.php'); ?>