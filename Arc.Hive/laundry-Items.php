<?php include('header.php'); ?>
<?php include('dbcon.php'); ?>


	<!-- This file is "laundrydbee/index.php" -->
<!-- Item CRUD -->
<div class="box1">
	<h2>Items Inventory</h2>
	<!-- can you create a new button here and say EDIT Quantity -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#itemModal">ADD ITEMS</button> <!-- Changed id -->
    <!-- New Edit -->
    <button class="btn btn-secondary" id="editQuantityButton" onclick="toggleEditQuantity()"style="margin-right: 10px;">EDIT QUANTITY</button>
    <!-- New Search -->
    <button class="btn btn-info" onclick="toggleSearch()"style="margin-right: 10px;">SEARCH</button>
</div>
<div class="table-container2">
	<table class="table table-hover table-bordered table-striped">
		<thead>
				<th>
					ItemID
					<button class="sort-button" onclick="sortItemTable(0, true)">↑</button>
					<button class="sort-button" onclick="sortItemTable(0, false)">↓</button>
				</th>
				<th>
					Item Name
					<button class="sort-button" onclick="sortItemTable(1, true)">↑</button>
					<button class="sort-button" onclick="sortItemTable(1, false)">↓</button>
				</th>
				<th>
					Item Quantity
					<button class="sort-button" onclick="sortItemTable(2, true)">↑</button>
					<button class="sort-button" onclick="sortItemTable(2, false)">↓</button>
				</th>
				<th>
					CategoryID
					<button class="sort-button" onclick="sortItemTable(3, true)">↑</button>
					<button class="sort-button" onclick="sortItemTable(3, false)">↓</button>
				</th>
				<th>
					Description
					<button class="sort-button" onclick="sortItemTable(4, true)">↑</button>
					<button class="sort-button" onclick="sortItemTable(4, false)">↓</button>
				</th>
				<th>
					Price
					<button class="sort-button" onclick="sortItemTable(5, true)">↑</button>
					<button class="sort-button" onclick="sortItemTable(5, false)">↓</button>
				</th>
				<th>Update</th>
				<th>Delete</th>
		</thead>
		<tbody>
			<?php
			// Fetch item data with category names, ordered by ItemName
			$query = "SELECT item.ItemID, item.ItemName, item.ItemQuantity, item.CategoryID, 
						category.CategoryName, item.Description, item.Price 
					FROM item 
					LEFT JOIN category ON item.CategoryID = category.CategoryID 
					ORDER BY item.ItemName ASC";
			$result = mysqli_query($connection, $query);	
			if (!$result) {
				die("Query Failed: " . mysqli_error($connection));
			} else {
				while ($row = mysqli_fetch_assoc($result)) {
					// Set background color based on quantity
					$quantity = $row['ItemQuantity'];
					$bgColor = '';
					if ($quantity > 20) {
						$bgColor = 'background-color: #d4edda;'; // Light Green
					} elseif ($quantity > 10) {
						$bgColor = 'background-color: #fff3cd;'; // Light Orange
					} else {
						$bgColor = 'background-color: #f8d7da;'; // Light Red
					}
					?>
					<tr>
						<td><?php echo $row['ItemID']; ?></td>
						<td><?php echo $row['ItemName']; ?></td>
						<td style="<?php echo $bgColor; ?>">
							<div class="edit-quantity-buttons" style="display: none;">
								<button class="quantity-btn minus" data-id="<?php echo $row['ItemID']; ?>">-1</button>
								<span id="quantity-<?php echo $row['ItemID']; ?>"><?php echo $row['ItemQuantity']; ?></span>
								<button class="quantity-btn plus" data-id="<?php echo $row['ItemID']; ?>">+1</button>
							</div>
							<span class="quantity-display" id="quantity-<?php echo $row['ItemID']; ?>"><?php echo $row['ItemQuantity']; ?></span>
						</td>
						<td><?php echo $row['CategoryID'] . ' - ' . $row['CategoryName']; ?></td>
						<td><?php echo $row['Description']; ?></td>
						<td><?php echo $row['Price'] . ' ₱'; ?></td>
						<td><a href="item-update-data.php?ItemID=<?php echo $row['ItemID']; ?>" class="btn btn-success">Update</a></td>
						<td><a href="item-delete-data.php?ItemID=<?php echo $row['ItemID'] ; ?>" class="btn btn-danger"onclick="return confirmDelete()">Delete</a></td>
					</tr>
					<?php
				}
			}
			?>
		</tbody>
	</table>
</div>

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
						<input type="text" name="ItemName" class="form-control" placeholder="Enter Item Name" required>
					</div>
					<div class="form-group">
						<label for="ItemQuantity">Item Quantity</label>
						<input type="number" name="ItemQuantity" class="form-control" placeholder="Enter Quantity" required>
					</div>
					<div class="form-group">
        			    <label for="CategoryID">Category ID</label>
        			    <select name="CategoryID" class="form-control" required>
        			        <option value="">Select a CategoryID</option>
        			        <?php foreach ($categories as $category): ?>
        			            <option value="<?php echo $category['CategoryID']; ?>">
        			                <?php echo $category['CategoryID'] . ' - ' . $category['CategoryName']; ?>
                                </option>
        			        <?php endforeach; ?>
        			    </select>
        			</div>
					<div class="form-group">
						<label for="Description">Description</label>
						<input type="text" name="Description" class="form-control" placeholder="Enter a description for the item" required>
					</div>
					<div class="form-group">
						<label for="Price">Price</label>
						<div class="input-group">
							<input type="number" step="0.01" name="Price" class="form-control" placeholder="Enter Price" required>
							<span class="input-group-text">₱</span>
						</div>
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
function toggleEditQuantity() {
    const editQuantityButtons = document.querySelectorAll(".edit-quantity-buttons");
    const quantityDisplays = document.querySelectorAll(".quantity-display");

    editQuantityButtons.forEach(buttons => {
        buttons.style.display = buttons.style.display === "none" ? "inline-block" : "none";
    });

    quantityDisplays.forEach(display => {
        display.style.display = display.style.display === "none" ? "inline-block" : "none";
    });
}

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
                console.log(xhr.responseText); // This will help to identify the server error
            }
        });
    }
});
</script>
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

<script>
	function sortItemTable(columnIndex, ascending) {
    const table = document.querySelector(".table tbody");
    const rows = Array.from(table.querySelectorAll("tr"));

    rows.sort((a, b) => {
        const cellA = a.cells[columnIndex].textContent.trim();
        const cellB = b.cells[columnIndex].textContent.trim();

        // Determine if we are dealing with numbers or strings
        const valueA = isNaN(cellA) ? cellA.toLowerCase() : parseFloat(cellA);
        const valueB = isNaN(cellB) ? cellB.toLowerCase() : parseFloat(cellB);

        if (ascending) {
            return valueA > valueB ? 1 : -1;
        } else {
            return valueA < valueB ? 1 : -1;
        }
    });

    // Append the sorted rows back into the table body
    rows.forEach(row => table.appendChild(row));
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
