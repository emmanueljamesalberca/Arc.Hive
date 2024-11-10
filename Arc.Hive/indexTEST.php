<?php include('header.php'); ?>
<?php include('dbcon.php'); ?>
	<!-- This file is "laundrydbee/index.php" -->

    <link rel="stylesheet" type="text/css" href="styles-item.css">

    <div class="sidebar">
        <h2>Navigation</h2>
        <a href="laundry-items.php"> -View Items- </a>
		<!-- Need Form to check all inventory on a specific date if all is Complete, if an item is not complete; show red  -->
		<a href="laundry-InventoryDetails.php"> -Perform Inventory Checking- </a> 
        <a href="laundry-category.php"> -View Item Category- </a>
        <a href="laundry-supplier.php"> -View Suppliers- </a>
		<!-- Need to show another table above supply details the supply table -->
        <a href="laundry-supply_details.php"> -View Supply Details- </a>
		<!-- Need Form to Create for Supply table and Supply Details Table -->
		<a href="laundry-record-supplies.php"> -Record Incoming Supplies- </a>
		<a href="laundry-Services.php"> -View Services- </a>
        <a href="laundry-Customer.php"> -View Customer- </a>
		<a href="laundry-Sales.php"> -View Sales- </a>
        <a href="laundry-job-order.php"> -Create Job Order and Job Order Items- </a>

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
	<!-- can you create a new button here and say EDIT Quantity -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#itemModal">ADD ITEMS</button> <!-- Changed id -->
    <!-- New Edit -->
    <button class="btn btn-secondary" id="editQuantityButton" onclick="toggleEditQuantity()">EDIT Quantity</button>
    <!-- New Search -->
    <button class="btn btn-info" onclick="toggleSearch()">SEARCH</button>
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
                        <div class="edit-quantity-buttons" style="display: none;">
                            <button class="quantity-btn minus" data-id="<?php echo $row['ItemID']; ?>">-1</button>
                            <span id="quantity-<?php echo $row['ItemID']; ?>"><?php echo $row['ItemQuantity']; ?></span>
                            <button class="quantity-btn plus" data-id="<?php echo $row['ItemID']; ?>">+1</button>
                        </div>
                        <span class="quantity-display" id="quantity-<?php echo $row['ItemID']; ?>"><?php echo $row['ItemQuantity']; ?></span>
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


<!-- Search -->
<script>
let searchRowAdded = false;

function toggleSearch() {
    const table = document.querySelector(".table thead");

    // Check if the search row has already been added
    if (!searchRowAdded) {
        const searchRow = document.createElement("tr");
        searchRow.classList.add("search-row");

        // Define search input fields for each column
        searchRow.innerHTML = `
            <th><input type="text" class="form-control" placeholder="Search ItemID" oninput="filterTable()"></th>
            <th><input type="text" class="form-control" placeholder="Search Item Name" oninput="filterTable()"></th>
            <th><input type="text" class="form-control" placeholder="Search Quantity" oninput="filterTable()"></th>
            <th><input type="text" class="form-control" placeholder="Search CategoryID" oninput="filterTable()"></th>
            <th><input type="text" class="form-control" placeholder="Search Description" oninput="filterTable()"></th>
            <th><input type="text" class="form-control" placeholder="Search Price" oninput="filterTable()"></th>
            <th></th>
            <th></th>
        `;

        // Insert the search row into the table
        table.appendChild(searchRow);
        searchRowAdded = true;
    } else {
        // Toggle the visibility of the search row if it already exists
        const searchRow = document.querySelector(".search-row");
        searchRow.style.display = searchRow.style.display === "none" ? "table-row" : "none";
    }
}

// Function to filter the table based on search inputs
function filterTable() {
    const searchInputs = document.querySelectorAll(".search-row input");
    const tableRows = document.querySelectorAll(".table tbody tr");

    tableRows.forEach(row => {
        let showRow = true;
        searchInputs.forEach((input, index) => {
            const cell = row.cells[index];
            if (cell && input.value) {
                const cellText = cell.textContent.toLowerCase();
                const searchText = input.value.toLowerCase();
                if (!cellText.includes(searchText)) {
                    showRow = false;
                }
            }
        });
        row.style.display = showRow ? "" : "none";
    });
}
</script>


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

<script src="script.js"></script>
<?php include('footer.php'); ?>
