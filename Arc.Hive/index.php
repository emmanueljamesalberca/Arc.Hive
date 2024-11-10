<?php include('header.php'); ?>
<?php include('dbcon.php'); ?>
	<!-- This file is "laundrydbee/index.php" -->

    <link rel="stylesheet" type="text/css" href="styles-item.css">

    <div class="sidebar">
        <h2>Navigation</h2>
        <a href="laundry-items.php">View Items</a>
        <a href="laundry-category.php">View Category LIST</a>
        <a href="laundry-supplier.php">View Supplier List</a>
        <a href="laundry-supply_details.php">View Supply Details List</a>
        <a href="laundry-job-order.php">Create Job Order and Job Order Items</a>
        <a href="laundry-Sales.php">View Sales</a>
        <a href="laundry-Services.php">View Services</a>
        <a href="laundry-Customer.php">View Customer</a>
        <a href="laundry-InventoryDetails.php">Perform Inventory Checking</a>
    </div>
    
	<!------ ERROR PROMPT-------->
	<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">
        <?php echo $_GET['error']; ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        <?php echo $_GET['success']; ?>
    </div>
<?php endif; ?>


<!-- Item CRUD -->
<div class="box1">
	<h2>ALL ITEMS</h2>
	<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#itemModal">ADD ITEMS</button> <!-- Changed id -->
</div>
<table class="table table-hover table-bordered table-striped">
	<thead>
		<tr>
			<th>ItemID</th>
			<th>Item Name</th>
			<th>Item Quantity</th>
			<th>CategoryID</th>
			<th>Description</th>
			<th>Price</th>
			<th>Update</th>
			<th>Delete</th>
		</tr>
	</thead>
	<tbody>
		<?php
		// Fetch item data
		$query = "SELECT * FROM `item`";
		$result = mysqli_query($connection, $query);	
		if (!$result) {
			die("Query Failed: " . mysqli_error($connection));
		} else {
			while ($row = mysqli_fetch_assoc($result)) {
				?>
				<tr>
					<td><?php echo $row['ItemID']; ?></td>
					<td><?php echo $row['ItemName']; ?></td>
                    <td>
                        <button class="quantity-btn minus" data-id="<?php echo $row['ItemID']; ?>">-10</button>
                        <button class="quantity-btn minus" data-id="<?php echo $row['ItemID']; ?>">-1</button>
                        <span id="quantity-<?php echo $row['ItemID']; ?>"><?php echo $row['ItemQuantity']; ?></span>
                        <button class="quantity-btn plus" data-id="<?php echo $row['ItemID']; ?>">+1</button>
                        <button class="quantity-btn plus" data-id="<?php echo $row['ItemID']; ?>">+10</button>
                    </td>
					<td><?php echo $row['CategoryID']; ?></td>
					<td><?php echo $row['Description']; ?></td>
					<td><?php echo $row['Price']; ?></td>
					<td><a href="item-update-data.php?ItemID=<?php echo $row['ItemID']; ?>" class="btn btn-success">Update</a></td>
					<td><a href="item-delete-data.php?ItemID=<?php echo $row['ItemID'] ; ?>" class="btn btn-danger">Delete</a></td>
				</tr>
				<?php
			}
		}
		?>
	</tbody>
</table>

<!-- Fetch available categories, Before Modal ITEM -->
<?php
$categories = []; // Initialize an empty array to hold categories

// $query = "SELECT CategoryID, CategoryName FROM category";
$query = "SELECT `CategoryID`, `CategoryName` FROM `category`";
$result = mysqli_query($connection, $query);

if(!$result){
    die("Query Failed: " . mysqli_error($connection));
} else {
    while($row = mysqli_fetch_assoc($result)){
        $categories[] = $row; // Store each category in the array
    }
}
?>

<!-- Modal FOR ITEM INVENTORY -->
<!-- item-insert-data.php -->
<form action="item-insert-data.php" method="post">
	<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel" aria-hidden="true"> <!-- Changed id -->
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="itemModalLabel">ADD ITEMS</h5> <!-- Changed aria-labelledby -->
					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="ItemName">Item Name</label>
						<input type="text" name="ItemName" class="form-control">
					</div>
					<div class="form-group">
						<label for="ItemQuantity">Item Quantity</label>
						<input type="text" name="ItemQuantity" class="form-control">
					</div>
					<!-- Dropdown for CategoryID -->
					<div class="form-group">
        			    <label for="CategoryID">Category ID</label>
        			    <select name="CategoryID" class="form-control" required>
        			        <option value="">Select a CategoryID</option> <!-- Placeholder option -->
        			        <?php foreach ($categories as $category): ?>
        			            <option value="<?php echo $category['CategoryID']; ?>">
        			                <?php echo $category['CategoryID'] . ' - ' . $category['CategoryName']; ?>
                                </option>
        			        <?php endforeach; ?>
        			    </select>
        			</div>

					<div class="form-group">
						<label for="Description">Description</label>
						<input type="text" name="Description" class="form-control">
					</div>
					<div class="form-group">
						<label for="Price">Price</label>
						<input type="float" name="Price" class="form-control">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<input type="submit" class="btn btn-success" name="add_item" value="ADD">
				</div>
			</div>
		</div>
	</div>
</form>


<!-- COPIED TO SCRIPT.js -->
<!-- Your custom JavaScript -->
<script>
$(document).ready(function() {
    // Increase quantity
    $('.plus').click(function() {
        var itemId = $(this).data('id');
        updateQuantity(itemId, 'increase');
    });

    // Decrease quantity
    $('.minus').click(function() {
        var itemId = $(this).data('id');
        updateQuantity(itemId, 'decrease');
    });

    // Function to send AJAX request to update quantity
    function updateQuantity(itemId, action) {
        $.ajax({
            url: 'item-update-quantity.php',
            type: 'POST',
            data: {
                itemId: itemId,
                action: action
            },
            success: function(response) {
                $('#quantity-' + itemId).text(response);
            },
            error: function(xhr, status, error) {
                console.log("AJAX error: " + error);
            }
        });
    }
});
</script>

<!-- Category CRUD -->
<div class="box1">
	<h2>Category LIST</h2>
	<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">ADD CATEGORY</button> <!-- Changed id -->
</div>
<table class="table table-hover table-bordered table-striped">
	<thead>
		<tr>
			<th>CategoryID</th>
			<th>Category Name</th>
			<th>Update</th>
			<th>Delete</th>
		</tr>
	</thead>
	<tbody>
		<?php
		// Fetch category data
		$query = "SELECT * FROM `category`";
		$result = mysqli_query($connection, $query);	
		if (!$result) {
			die("Query Failed: " . mysqli_error($connection));
		} else {
			while ($row = mysqli_fetch_assoc($result)) {
				?>
				<tr>
					<td><?php echo $row['CategoryID']; ?></td>
					<td><?php echo $row['CategoryName']; ?></td>
					<td><a href="category-update-data.php?CategoryID=<?php echo $row['CategoryID']; ?>" class="btn btn-success">Update</a></td>
					<td><a href="category-delete-data.php?CategoryID=<?php echo $row['CategoryID']; ?>" class="btn btn-danger">Delete</a></td>
				</tr>
				<?php
			}
		}
		?>
	</tbody>
</table>



<!-- Modal FOR CATEGORY LIST-->
<!-- category-insert-data.php -->
<form action="category-insert-data.php" method="post">
	<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true"> <!-- Changed id -->
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="categoryModalLabel">ADD CATEGORY</h5> <!-- Changed aria-labelledby -->
					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="CategoryID">CategoryID</label>
						<input type="text" name="CategoryID" class="form-control">
					</div>
					<div class="form-group">
						<label for="CategoryName">Category Name</label>
						<input type="text" name="CategoryName" class="form-control">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<input type="submit" class="btn btn-success" name="add_category" value="ADD">
				</div>
			</div>
		</div>
	</div>
</form>


<!-- Supplier CRUD -->
<div class="box1">
    <h2>SUPPLIER LIST</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#supplierModal">ADD SUPPLIER</button>
</div>
<table class="table table-hover table-bordered table-striped">
    <thead>
        <tr>
            <th>SupplierID</th>
            <th>Supplier Name</th>
            <th>Contact</th>
            <th>Address</th>
			<th>Update</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch supplier data
        $query = "SELECT * FROM `supplier`";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            die("Query Failed: " . mysqli_error($connection));
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?php echo $row['SupplierID']; ?></td>
                    <td><?php echo $row['SupplierName']; ?></td>
                    <td><?php echo $row['Contact']; ?></td>
                    <td><?php echo $row['Address']; ?></td>
					<td><a href="supplier-update-data.php?SupplierID=<?php echo $row['SupplierID']; ?>" class="btn btn-success">Update</a></td>
                    <td><a href="supplier-delete-data.php?SupplierID=<?php echo $row['SupplierID']; ?>" class="btn btn-danger">Delete</a></td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>

<!-- Modal FOR SUPPLIER LIST-->
<!-- supplier-insert-data.php -->
<form action="supplier-insert-data.php" method="post">
    <div class="modal fade" id="supplierModal" tabindex="-1" role="dialog" aria-labelledby="supplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="supplierModalLabel">ADD SUPPLIER</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="SupplierID">Supplier ID</label>
                        <input type="text" name="SupplierID" class="form-control" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="SupplierName">Supplier Name</label>
                        <input type="text" name="SupplierName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="Contact">Contact</label>
                        <input type="text" name="Contact" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="Address">Address</label>
                        <input type="text" name="Address" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-success" name="add_supplier" value="ADD">
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Supply details CRUD -->
<div class="box1">
    <h2>SUPPLY DETAILS LIST</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#supplyDetailsModal">ADD SUPPLY DETAILS</button>
</div>
<table class="table table-hover table-bordered table-striped">
    <thead>
        <tr>
            <th>InvoiceID</th>
            <th>ItemID</th>
            <th>Quantity</th>
            <th>Base-Price</th>
            <th>Subtotal</th>
            <th>Update</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch supply details data
        $query = "SELECT * FROM `supply-details`";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            die("Query Failed: " . mysqli_error($connection));
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?php echo $row['InvoiceID']; ?></td>
                    <td><?php echo $row['ItemID']; ?></td>
                    <td><?php echo $row['Quantity']; ?></td>
                    <td><?php echo $row['Base-Price']; ?></td>
                    <td><?php echo $row['Subtotal']; ?></td>
                    <td><a href="supply-details-update-data.php?InvoiceID=<?php echo $row['InvoiceID']; ?>" class="btn btn-success">Update</a></td>
                    <td><a href="supply-details-delete-data.php?InvoiceID=<?php echo $row['InvoiceID']; ?>" class="btn btn-danger">Delete</a></td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>


<!-- NEW -->
<!-- Fetch available ItemID and ItemName before Modal ITEM -->
<?php
$items = []; // Initialize an array for storing fetched items

$query = "SELECT `ItemID`, `ItemName` FROM `item`"; // Query to fetch ItemID and ItemName
$result = mysqli_query($connection, $query);

if(!$result){
    die("Query Failed: " . mysqli_error($connection));
} else {
    while($row = mysqli_fetch_assoc($result)){
        $items[] = $row; // Store each row in the $items array
    }
}
?>

<!-- Modal FOR SUPPLY DETAILS -->
<form action="supply-details-insert-data.php" method="post">
    <div class="modal fade" id="supplyDetailsModal" tabindex="-1" role="dialog" aria-labelledby="supplyDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="supplyDetailsModalLabel">ADD SUPPLY DETAILS</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
					<div class="form-group">
                        <label for="InvoiceID">Invoice ID</label>
                        <input type="number" name="InvoiceID" class="form-control" required>
                    </div>
					<div class="form-group">
                        <label for="ItemID">Item ID</label>
                        <select name="ItemID" class="form-control" required>
                            <option value="">Select an Item</option> <!-- Placeholder option -->
                            <?php foreach ($items as $item): ?>
                                <option value="<?php echo $item['ItemID']; ?>">
                                    <?php echo $item['ItemID'] . ' - ' . $item['ItemName']; ?> <!-- Display both ItemID and ItemName -->
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Quantity">Quantity</label>
                        <input type="number" name="Quantity" id="Quantity" class="form-control" oninput="calculateSubtotal()" required>
                    </div>
                    <div class="form-group">
                        <label for="Base-Price">Base Price</label>
                        <input type="text" name="Base-Price" id="BasePrice" class="form-control" oninput="calculateSubtotal()" required>
                    </div>
                    <div class="form-group">
                        <label for="Subtotal">Subtotal</label>
                        <input type="text" name="Subtotal" id="Subtotal" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-success" name="add_supply_details" value="ADD">
                </div>
            </div>
        </div>
    </div>
</form>


<!-- Done -->
<script>
function calculateSubtotal() {
    // Get the values of Quantity and Base Price
    var quantity = document.getElementById('Quantity').value;
    var basePrice = document.getElementById('BasePrice').value;

    // Calculate Subtotal
    var subtotal = quantity * basePrice;

    // Update the Subtotal field
    document.getElementById('Subtotal').value = subtotal.toFixed(2); // Formatting to 2 decimal places
}
</script>

<?php include('footer.php'); ?>
