<?php include('header.php'); ?>
<?php include('dbcon.php'); ?>
	
<!-- Supply CRUD -->
<?php
// Fetch supply data
$supplyQuery = "SELECT supply.SupplierID, supply.InvoiceID, supply.Date, supply.UserID, supply.Total, supplier.SupplierName 
                FROM supply 
                LEFT JOIN supplier ON supply.SupplierID = supplier.SupplierID";


$supplyResult = mysqli_query($connection, $supplyQuery);

if (!$supplyResult) {
    die("Query Failed: " . mysqli_error($connection));
}
?>

<div class="box1">
    <h2>SUPPLY LIST</h2>
    <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplyModal"style="margin-left: 10px;">Add Supply</button> -->
    <button class="btn btn-info" onclick="toggleSupplySearch()" style="margin-left: 10px;">SEARCH</button>
    <table class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th>SupplierID</th>
                <th>InvoiceID</th>
                <th>Date</th>
                <th>UserID</th>
                <th>Total</th>
                <th>Delete</th>
            </tr>
            <!-- Search Row for Supply Table -->
            <tr id="supplySearchRow" style="display: none;">
                <th><input type="text" class="form-control" placeholder="Search SupplierID" oninput="filterSupplyTable()"></th>
                <th><input type="text" class="form-control" placeholder="Search InvoiceID" oninput="filterSupplyTable()"></th>
                <th><input type="text" class="form-control" placeholder="Search Date" oninput="filterSupplyTable()"></th>
                <th><input type="text" class="form-control" placeholder="Search UserID" oninput="filterSupplyTable()"></th>
                <th><input type="text" class="form-control" placeholder="Search Total" oninput="filterSupplyTable()"></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php while ($supplyRow = mysqli_fetch_assoc($supplyResult)) { ?>
                <tr>
                    <td><?php echo $supplyRow['SupplierID']; ?></td>
                    <td><?php echo $supplyRow['InvoiceID']; ?></td>
                    <td><?php echo $supplyRow['Date']; ?></td>
                    <td><?php echo $supplyRow['UserID']; ?></td>
                    <td><?php echo number_format($supplyRow['Total'], 2) . ' ₱'; ?></td>
                    <td><a href="supply-delete.php?SupplierID=<?php echo $supplyRow['SupplierID']; ?>&InvoiceID=<?php echo $supplyRow['InvoiceID']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this supply?')">Delete</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Add Supply Modal -->
<div class="modal fade" id="addSupplyModal" tabindex="-1" aria-labelledby="addSupplyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="supply-insert.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSupplyModalLabel">Add New Supply</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="InvoiceID" class="form-label">Invoice ID</label>
                        <input type="text" class="form-control" id="InvoiceID" name="InvoiceID" required>
                    </div>
                    <div class="mb-3">
                        <label for="Date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="Date" name="Date" required>
                    </div>
                    <div class="mb-3">
                        <label for="UserID" class="form-label">User ID</label>
                        <input type="text" class="form-control" id="UserID" name="UserID" required>
                    </div>
                    <div class="mb-3">
                        <label for="Total" class="form-label">Total</label>
                        <input type="number" step="0.01" class="form-control" id="Total" name="Total" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Supply</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Generate Edit Supply Modal for each entry -->
<?php mysqli_data_seek($supplyResult, 0); // Reset result pointer ?>
<?php while ($supplyRow = mysqli_fetch_assoc($supplyResult)) { ?>
    <div class="modal fade" id="editSupplyModal<?php echo $supplyRow['SupplierID']; ?>" tabindex="-1" aria-labelledby="editSupplyModalLabel<?php echo $supplyRow['SupplierID']; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="supply-update.php" method="post">
                    <input type="hidden" name="SupplierID" value="<?php echo $supplyRow['SupplierID']; ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSupplyModalLabel<?php echo $supplyRow['SupplierID']; ?>">Edit Supply</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="InvoiceID" class="form-label">Invoice ID</label>
                            <input type="text" class="form-control" id="InvoiceID" name="InvoiceID" value="<?php echo $supplyRow['InvoiceID']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="Date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="Date" name="Date" value="<?php echo $supplyRow['Date']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="UserID" class="form-label">User ID</label>
                            <input type="text" class="form-control" id="UserID" name="UserID" value="<?php echo $supplyRow['UserID']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="Total" class="form-label">Total</label>
                            <input type="number" step="0.01" class="form-control" id="Total" name="Total" value="<?php echo $supplyRow['Total']; ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Supply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>

<script>
// Toggle search row visibility
function toggleSupplySearch() {
    const searchRow = document.getElementById('supplySearchRow');
    searchRow.style.display = searchRow.style.display === 'none' ? 'table-row' : 'none';
}

// Filter function for supply table
function filterSupplyTable() {
    const inputFields = document.querySelectorAll('#supplySearchRow input');
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        let isVisible = true;
        inputFields.forEach((input, index) => {
            const cellText = row.cells[index].textContent.toLowerCase();
            const searchText = input.value.toLowerCase();
            if (searchText && !cellText.includes(searchText)) {
                isVisible = false;
            }
        });
        row.style.display = isVisible ? '' : 'none';
    });
}
</script>


<!-- Supply details CRUD -->
<div class="box1">
    <h2>SUPPLY DETAILS LIST</h2>
    <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#supplyDetailsModal">ADD SUPPLY DETAILS</button> -->
    <button class="btn btn-info" onclick="toggleSupplyDetailsSearch()"style="margin-right: 10px;">SEARCH</button>

</div>
<table class="table table-hover table-bordered table-striped">
    <thead>
        <tr>
            <th>
                InvoiceID 
                <button class="sort-button" onclick="sortTable(0, true)">↑</button>
                <button class="sort-button" onclick="sortTable(0, false)">↓</button>
            </th>
            <th>
                ItemID 
                <button class="sort-button" onclick="sortTable(1, true)">↑</button>
                <button class="sort-button" onclick="sortTable(1, false)">↓</button>
            </th>
            <th>
                Quantity 
                <button class="sort-button" onclick="sortTable(2, true)">↑</button>
                <button class="sort-button" onclick="sortTable(2, false)">↓</button>
            </th>
            <th>
                Base-Price 
                <button class="sort-button" onclick="sortTable(3, true)">↑</button>
                <button class="sort-button" onclick="sortTable(3, false)">↓</button>
            </th>
            <th>
                Subtotal 
                <button class="sort-button" onclick="sortTable(4, true)">↑</button>
                <button class="sort-button" onclick="sortTable(4, false)">↓</button>
            </th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch supply details data
        $query = "SELECT `supply-details`.InvoiceID, `supply-details`.ItemID, `supply-details`.Quantity, 
                 `supply-details`.`Base-Price`, (`supply-details`.Quantity * `supply-details`.`Base-Price`) AS Subtotal, 
                 item.ItemName 
          FROM `supply-details` 
          JOIN `item` ON `supply-details`.ItemID = item.ItemID";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            die("Query Failed: " . mysqli_error($connection));
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?php echo $row['InvoiceID']; ?></td>
                    <td><?php echo $row['ItemID'] . ' - ' . $row['ItemName']; ?></td> <!-- Display ItemID - ItemName -->
                    <td><?php echo $row['Quantity']; ?></td>
                    <td><?php echo $row['Base-Price'] . ' ₱'; ?></td> <!-- Use backticks for Base-Price -->
                    <td><?php echo number_format($row['Subtotal'], 2) . ' ₱'; ?></td>
                    <td><a href="supply-details-delete-data.php?InvoiceID=<?php echo $row['InvoiceID']; ?>&ItemID=<?php echo $row['ItemID']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item from the invoice?');">Delete</a>
                    </td>
                    
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

<script>
let supplyDetailsSearchRowAdded = false;

function toggleSupplyDetailsSearch() {
    const table = document.querySelector(".table thead");

    if (!supplyDetailsSearchRowAdded) {
        const searchRow = document.createElement("tr");
        searchRow.classList.add("supply-details-search-row");

        searchRow.innerHTML = `
            <th><input type="text" class="form-control" placeholder="Search InvoiceID" oninput="filterSupplyDetailsTable()"></th>
            <th><input type="text" class="form-control" placeholder="Search ItemID" oninput="filterSupplyDetailsTable()"></th>
            <th><input type="text" class="form-control" placeholder="Search Quantity" oninput="filterSupplyDetailsTable()"></th>
            <th><input type="text" class="form-control" placeholder="Search Base-Price" oninput="filterSupplyDetailsTable()"></th>
            <th><input type="text" class="form-control" placeholder="Search Subtotal" oninput="filterSupplyDetailsTable()"></th>
            <th></th>
            <th></th>
        `;

        table.appendChild(searchRow);
        supplyDetailsSearchRowAdded = true;
    } else {
        const searchRow = document.querySelector(".supply-details-search-row");
        searchRow.style.display = searchRow.style.display === "none" ? "table-row" : "none";
    }
}
</script>

<script>
    function sortTable(columnIndex, ascending) {
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
